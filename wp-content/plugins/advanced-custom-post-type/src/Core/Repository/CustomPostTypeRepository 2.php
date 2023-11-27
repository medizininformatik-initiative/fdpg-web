<?php

namespace ACPT\Core\Repository;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxModel;
use ACPT\Core\Models\CustomPostType\CustomPostTypeModel;
use ACPT\Core\Models\Taxonomy\TaxonomyModel;
use ACPT\Core\Models\Template\TemplateModel;
use ACPT\Core\Models\WooCommerce\WooCommerceProductDataFieldModel;
use ACPT\Core\Models\WooCommerce\WooCommerceProductDataModel;
use ACPT\Costants\MetaTypes;
use ACPT\Includes\ACPT_DB;
use ACPT\Utils\Wordpress\WPUtils;

class CustomPostTypeRepository
{
    /**
     * @return int
     */
    public static function count()
    {
        $baseQuery = "
            SELECT 
                count(id) as count
            FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE)."`
            ";

        $results = ACPT_DB::getResults($baseQuery);

        return (int)$results[0]->count;
    }

    /**
     * Delete a custom post type
     *
     * @param string $postType
     * @param bool $deletePosts
     *
     * @return string|null
     * @throws \Exception
     * @since    1.0.0
     */
    public static function delete($postType, $deletePosts = false)
    {
        if($postType === 'post' or $postType === 'page'){
            throw new \Exception('You cannot delete page or post CPT.');
        }

        if(self::exists($postType)) {

            $postModel = self::get([
                    'postType' => $postType
            ])[0];

            ACPT_DB::startTransaction();

            try {
                $sql = "
                    DELETE
                        FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE)."`
                        WHERE id = %s
                    ";

                MetaRepository::deleteAll([
                    'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
                    'find' => $postType,
                ]);
                TemplateRepository::deleteByPostType($postType);
                TaxonomyRepository::deleteAssociations($postModel->getId());

                if($postModel->isWooCommerce()){
                    WooCommerceProductDataRepository::clear();
                }

                MetaRepository::removeOrphanRelationships();
                ACPT_DB::executeQueryOrThrowException($sql, [$postModel->getId()]);
                ACPT_DB::commitTransaction();
            } catch (\Exception $exception){
                ACPT_DB::rollbackTransaction();
                throw new \Exception($exception->getMessage());
            }

            if($deletePosts){
                self::deletePostType($postType);
            }

            return true;
        }

        return false;
    }

    /**
     * Check if a post type exists
     *
     * @since    1.0.0
     * @param $postType
     *
     * @return bool
     */
    public static function exists($postType)
    {
        $baseQuery = "
            SELECT 
                id
            FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE)."`
            WHERE post_name = %s
            ";

        $posts = ACPT_DB::getResults($baseQuery, [$postType]);

        return count($posts) === 1;
    }
    
    /**
     * Get the registered custom post types
     *
     * @param array $meta
     * @param bool  $lazy
     *
     * @return CustomPostTypeModel[]
     * @throws \Exception
     * @since    1.0.0
     */
    public static function get(array $meta = [], $lazy = false)
    {
        $results = [];
        $args = [];

        $baseQuery = "
            SELECT 
                cp.id, 
                cp.post_name as name,
                cp.singular,
                cp.plural,
                cp.icon,
                cp.native,
                cp.supports,
                cp.labels,
                cp.settings,
                COUNT(p.id) as post_count
            FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE)."` cp
            LEFT JOIN `".ACPT_DB::prefix()."posts` p ON p.post_type = cp.post_name AND p.`post_status` = %s
            WHERE 1=1
            ";

        $args[] = 'publish';

        if(isset($meta['id'])){
            $baseQuery .= " AND cp.id = %s";
            $args[] = $meta['id'];
        }

        if(isset($meta['postType'])){
            $baseQuery .= " AND cp.post_name = %s ";
            $args[] = $meta['postType'];
        }

	    if(isset($meta['exclude'])){
		    $baseQuery .= " AND cp.post_name != %s ";
		    $args[] = $meta['exclude'];
	    }

        $baseQuery .= " GROUP BY cp.id";
        $baseQuery .= " ORDER BY cp.native DESC, cp.post_name ASC";

        if(isset($meta['page']) and isset($meta['perPage'])){
            $baseQuery .= " LIMIT ".$meta['perPage']." OFFSET " . ($meta['perPage'] * ($meta['page'] - 1));
        }

        $baseQuery .= ';';
        $posts = ACPT_DB::getResults($baseQuery, $args);

        foreach ($posts as $post){
            $postModel = CustomPostTypeModel::hydrateFromArray([
                'id' => $post->id,
                'name' => $post->name,
                'singular' => $post->singular,
                'plural' => $post->plural,
                'icon' => $post->icon,
                'native' => $post->native == '0' ? false : true,
                'supports' => json_decode($post->supports),
                'labels' => json_decode($post->labels, true),
                'settings' => json_decode($post->settings, true),
            ]);
            $postModel->setPostCount($post->post_count);

            // NOT-LAZY MODE
            if(!$lazy){

                // Meta boxes
                $metaBoxQuery = "
                    SELECT 
                        id, 
                        meta_box_name as name,
                        meta_box_label as label,
                        sort
                    FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE_META_BOX)."`
                    WHERE post_type = %s
                ";
                $metaBoxArgs = [$post->name];

                if(isset($meta['boxName'])){
                    $metaBoxQuery .= " AND meta_box_name = %s";
                    $metaBoxArgs[] = $meta['boxName'];
                }

                $metaBoxQuery .= " ORDER BY sort;";

                $boxes = ACPT_DB::getResults($metaBoxQuery, $metaBoxArgs);

                foreach ($boxes as $boxIndex => $box){
                    $boxModel = CustomPostTypeMetaBoxModel::hydrateFromArray([
                        'id' => $box->id,
                        'postType' => $postModel->getName(),
                        'name' => $box->name,
                        'sort' => $box->sort
                    ]);

                    if($boxModel !== null and $box->label !== null){
	                    $boxModel->changeLabel($box->label);
                    }

                    $sql = "
                        SELECT
                            id,
                            field_name as name,
                            field_type,
                            field_default_value,
                            field_description,
                            required,
                            showInArchive,
                            filter_in_admin,
	                        quick_edit,
                            sort
                        FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD)."`
                        WHERE meta_box_id = %s
                        AND parent_id IS NULL
                        AND block_id IS NULL
                    ";

                    if(isset($meta['excludeFields'])){
                        $sql .= " AND id NOT IN ('".implode("','", $meta['excludeFields'])."')";
                    }

                    $sql .= " ORDER BY sort;";

                    $fields = ACPT_DB::getResults($sql, [$box->id]);

                    // Meta box fields
                    foreach ($fields as $fieldIndex => $field){
                        $fieldModel = MetaRepository::hydrateMetaBoxFieldModel($field, $boxModel);
                        $boxModel->addField($fieldModel);
                    }

                    $postModel->addMetaBox($boxModel);
                }

                // Add more data
                $postModel = self::addTaxonomiesToPostTypeModel($postModel);
                $postModel = self::addTemplatesToPostTypeModel($postModel);
                $postModel = self::addWooCommerceDataToPostTypeModel($postModel);
            }

            $results[] = self::addTemplateVariablesToPostTypeModel($postModel);
        }

        return $results;
    }

    /**
     * @param CustomPostTypeModel $postModel
     *
     * @return CustomPostTypeModel
     * @throws \Exception
     */
    private static function addTaxonomiesToPostTypeModel(CustomPostTypeModel $postModel)
    {
        $taxonomies = ACPT_DB::getResults("
                    SELECT
                        t.id,
                        t.slug ,
                        t.singular,
                        t.plural,
                        t.labels,
                        t.settings
                    FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_TAXONOMY)."` t
                    JOIN `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_TAXONOMY_PIVOT)."` p ON p.taxonomy_id = t.id
                    WHERE p.custom_post_type_id = %s
                ;", [$postModel->getId()]);

        foreach ($taxonomies as $taxonomy) {
            $taxonomyModel = TaxonomyModel::hydrateFromArray([
                    'id' => $taxonomy->id,
                    'slug' => $taxonomy->slug,
                    'singular' => $taxonomy->singular,
                    'plural' => $taxonomy->plural,
                    'native' => (isset($taxonomy->native) and $taxonomy->native == '1') ? true : false,
                    'labels' => json_decode($taxonomy->labels, true),
                    'settings' => json_decode($taxonomy->settings, true),
            ]);

	        $postModel->addTaxonomy($taxonomyModel);
        }

        return $postModel;
    }

    /**
     * @param CustomPostTypeModel $postModel
     *
     * @return CustomPostTypeModel
     * @throws \Exception
     */
    private static function addTemplatesToPostTypeModel(CustomPostTypeModel $postModel)
    {
        $templates = ACPT_DB::getResults("
                SELECT 
                    id, 
                    belongs_to as belongsTo,
                    find,
                    template_type as templateType,
                    json,
                    html,
                    meta
                FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TEMPLATE)."`
                WHERE belongs_to = %s and find = %s
        ;", [MetaTypes::CUSTOM_POST_TYPE ,$postModel->getName()]);

        foreach ($templates as $template) {
            $templateModel = TemplateModel::hydrateFromArray([
                    'id' => $template->id,
                    'belongsTo' => $template->belongsTo,
                    'find' => $template->find,
                    'templateType' => $template->templateType,
                    'json' => $template->json,
                    'html' => $template->html,
                    'meta' => json_decode($template->meta, true),
                    'metaFieldId' =>  (isset($template->metaFieldId) and $template->metaFieldId !== null) ? $template->metaFieldId : null,
            ]);

            $postModel->addTemplate($templateModel);
        }

        return $postModel;
    }

    /**
     * @param CustomPostTypeModel $postModel
     *
     * @return CustomPostTypeModel
     * @throws \Exception
     */
    private static function addWooCommerceDataToPostTypeModel( CustomPostTypeModel $postModel)
    {
        if($postModel->isWooCommerce()){
            $productData = ACPT_DB::getResults("
                        SELECT 
                            id,
                            product_data_name,
                            icon,
                            visibility,
                            show_in_ui
                        FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_WOOCOMMERCE_PRODUCT_DATA)."`
                    ;", []);

            foreach ($productData as $productDatum){
                $wooCommerceProductDataModel = WooCommerceProductDataModel::hydrateFromArray([
                        'id' => $productDatum->id,
                        'name' => $productDatum->product_data_name,
                        'icon' => json_decode($productDatum->icon, true),
                        'visibility' => $productDatum->visibility,
                        'showInUI' => $productDatum->show_in_ui == '0' ? false : true,
                ]);

                $productDataFields = ACPT_DB::getResults("
                            SELECT 
                                id,
                                product_data_id,
                                field_name,
                                field_type,
                                required,
                                sort
                            FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_WOOCOMMERCE_PRODUCT_DATA_FIELD)."`
                            WHERE product_data_id = %s ORDER BY sort DESC
                        ;", [$productDatum->id]);

                foreach ($productDataFields as $productDataField){
                    $wooCommerceProductDataFieldModel = WooCommerceProductDataFieldModel::hydrateFromArray([
                            'id' => $productDataField->id,
                            'productDataModel' => $wooCommerceProductDataModel,
                            'name' => $productDataField->field_name,
                            'type' => $productDataField->field_type,
                            'required' => $productDataField->required == '1',
                            'sort' => $productDataField->sort,
                            'defaultValue' => null,
                            'description' => null,
                    ]);

                    $wooCommerceProductDataModel->addField($wooCommerceProductDataFieldModel);
                }

                $postModel->addWoocommerceProductData($wooCommerceProductDataModel);
            }

            return $postModel;
        }

        return $postModel;
    }

    /**
     * @param CustomPostTypeModel $postModel
     *
     * @return CustomPostTypeModel
     */
    private static function addTemplateVariablesToPostTypeModel( CustomPostTypeModel $postModel)
    {
        if($postModel->getName() === 'post'){
            $themeFiles = ['single.php', 'acpt/single.php'];
            $existsInTheme = WPUtils::locateTemplate($themeFiles, false);
            $postModel->setExistsSinglePageInTheme($existsInTheme != '');

            $themeFiles = ['category.php', 'acpt/category.php'];
            $existsInTheme = WPUtils::locateTemplate($themeFiles, false);
            $postModel->setExistsArchivePageInTheme($existsInTheme != '');

        } elseif ($postModel->getName() === 'page') {
            $themeFiles = ['page.php', 'acpt/page.php'];
            $existsInTheme = WPUtils::locateTemplate($themeFiles, false);
            $postModel->setExistsSinglePageInTheme($existsInTheme != '');
        } else {
            $themeFiles = ['single-'.$postModel->getName().'.php', 'acpt/single-'.$postModel->getName().'.php'];
            $existsInTheme = WPUtils::locateTemplate($themeFiles, false);
            $postModel->setExistsSinglePageInTheme($existsInTheme != '');

            $themeFiles = ['archive-'.$postModel->getName().'.php', 'acpt/archive-'.$postModel->getName().'.php'];
            $existsInTheme = WPUtils::locateTemplate($themeFiles, false);
            $postModel->setExistsArchivePageInTheme($existsInTheme != '');
        }

        return $postModel;
    }

    /**
     * Get the id of a post type by registered name
     *
     * @since    1.0.0
     * @param $postType
     *
     * @return string|null
     */
    public static function getId($postType)
    {
        $baseQuery = "
            SELECT 
                id
            FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE)."`
            WHERE post_name = %s
            ";

        $posts = ACPT_DB::getResults($baseQuery, [
                $postType
        ]);

        if(count($posts) === 1){
            return $posts[0]->id;
        }

        return null;
    }
    
    /**
     * Save custom post type data
     *
     * @param CustomPostTypeModel $model
     *
     * @throws \Exception
     */
    public static function save(CustomPostTypeModel $model)
    {
        $sql = "
            INSERT INTO `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE)."` 
            (`id`,
            `post_name` ,
            `singular` ,
            `plural`,
            `icon`,
            `native`,
            `supports`,
            `labels`,
            `settings`
            ) VALUES (
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s
            ) ON DUPLICATE KEY UPDATE 
                `post_name` = %s,
                `singular` = %s,
                `plural` = %s,
                `icon` = %s,
                `native` = %s,
                `supports` = %s,
                `labels` = %s,
                `settings` = %s
        ;";

        ACPT_DB::executeQueryOrThrowException($sql, [
                $model->getId(),
                $model->getName(),
                $model->getSingular(),
                $model->getPlural(),
                $model->getIcon(),
                $model->isNative(),
                json_encode($model->getSupports()),
                json_encode($model->getLabels()),
                json_encode($model->getSettings()),
                $model->getName(),
                $model->getSingular(),
                $model->getPlural(),
                $model->getIcon(),
                $model->isNative(),
                json_encode($model->getSupports()),
                json_encode($model->getLabels()),
                json_encode($model->getSettings())
        ]);
    }

    /**
     * Delete all posts by post type
     *
     * @param $postType
     *
     * @throws \Exception
     */
    private static function deletePostType($postType)
    {
        global $wpdb;

        $query = "DELETE a,b,c
            FROM `{$wpdb->prefix}posts` a
            LEFT JOIN `{$wpdb->prefix}term_relationships` b
                ON (a.ID = b.object_id)
            LEFT JOIN `{$wpdb->prefix}postmeta` c
                ON (a.ID = c.post_id)
            WHERE a.post_type = %s";

        ACPT_DB::executeQueryOrThrowException($query, [$postType]);
    }
}