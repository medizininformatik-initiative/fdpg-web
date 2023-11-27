<?php

namespace ACPT\Core\Generators;

use ACPT\Core\Generators\CustomPostTypeFields\PostField;
use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Models\Taxonomy\TaxonomyModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Core\Repository\TaxonomyRepository;
use ACPT\Core\Validators\MetaDataValidator;
use ACPT\Costants\MetaTypes;
use ACPT\Costants\RelationCostants;
use ACPT\Includes\ACPT_DB;
use ACPT\Utils\Data\Sanitizer;
use ACPT\Utils\PHP\Arrays;
use ACPT\Utils\Wordpress\Nonce;

/**
 * *************************************************
 * CustomPostTypeGenerator class
 * *************************************************
 *
 * @author Mauro Cassani
 * @link https://github.com/mauretto78/
 */
class CustomPostTypeGenerator extends AbstractGenerator
{
    /**
     * The name of the post type.
     * @var string
     */
    private $postTypeName;

	/**
	 * @var bool
	 */
    private $isNative;

    /**
     * A list of user-specific options for the post type.
     * @var array
     */
    private $postTypeArgs;

    /**
     * Sets default values, registers the passed post type, and
     * listens for when the post is saved.
     *
     * @param string $postTypeName The name of the desired post type.
     * @param bool   $isNative
     * @param array  $postTypeArgs
     * @param null   $language
     */
    public function __construct($postTypeName, $isNative, $postTypeArgs = [], $language = null)
    {
        $this->postTypeName = strtolower($postTypeName);
        $this->postTypeArgs = (array) $postTypeArgs;
        $this->isNative = $isNative;

        $this->init([&$this, "registerPostType"]);

        add_action( 'admin_notices', [$this, 'legacyAdminNotices' ]);

        $this->savePost();
    }

    /**
     * Unregisters a post type in the WP db.
     */
    public function unregisterPostType()
    {
        unregister_post_type(ucwords($this->postTypeName));
    }

    /**
     * Registers a new post type in the WP db.
     */
    public function registerPostType()
    {
	    $taxonomyNames = [];
	    $taxonomies = TaxonomyRepository::get([
        	'customPostType' => $this->postTypeName
        ]);

	    // register taxonomies first
	    foreach ($taxonomies as $taxonomyModel){
	        if(!$taxonomyModel->isNative()){
		        $this->registerTaxonomy($taxonomyModel);
	        } else {
		        register_taxonomy_for_object_type($taxonomyModel->getSlug(), $this->postTypeName);
	        }

		    $taxonomyNames[] = $taxonomyModel->getSlug();
        }

        // register custom post type if not native
        if(!$this->isNative){
	        $n = ucwords($this->postTypeName);
	        $n = str_replace("_", " ", $n);

	        $args = [
		        "label" => $n,
		        'singular_name' => $n,
		        'labels' => [
			        'add_new_item' => 'Add ' . $n,
			        'add_new' => 'Add ' . $n,
			        'view_item' => 'View ' . $n,
			        'search_items' => 'Search ' . $n,
			        'edit_item' => 'Modify ' . $n,
			        'not_found' => 'No ' . $n . ' was found'
		        ],
		        "public" => true,
		        "publicly_queryable" => true,
		        "query_var" => true,
		        "menu_icon" => "dashicons-admin-site-alt3",
		        "rewrite" => true,
		        "capability_type" => "post",
		        "hierarchical" => false,
		        "menu_position" => null,
		        "supports" => ["title", "editor", "thumbnail"],
		        'has_archive' => true,
		        "show_in_rest" => true,
		        'taxonomies' => $taxonomyNames
	        ];

	        // custom_rewrite
	        if(isset($this->postTypeArgs['rewrite']) and $this->postTypeArgs['rewrite'] === true and !empty($this->postTypeArgs['custom_rewrite'])){
		        $this->postTypeArgs['rewrite'] = [
			        'slug' => $this->postTypeArgs['custom_rewrite'],
			        'with_front' => true
		        ];
	        }

	        // Take user provided options, and override the defaults.
	        $args = array_merge($args, $this->postTypeArgs);

	        register_post_type($this->postTypeName, $args);

	        // Manually add settings
            if(isset($args['settings'])){
	            foreach ($args['settings'] as $setting){
		            if(!post_type_supports( $this->postTypeName, $setting )){
			            add_post_type_support( $this->postTypeName, $setting );
		            }
	            }
            }
        }
    }

	/**
	 * Registers a new taxonomy, associated with the instantiated post type(s).
	 *
	 * @param TaxonomyModel $taxonomyModel
	 */
    private function registerTaxonomy(TaxonomyModel $taxonomyModel)
    {
	    $slug = $taxonomyModel->getSlug();
	    $taxonomyName = ucwords($slug);
	    $plural = $taxonomyModel->getPlural();
	    $options = array_merge(
		    [
			    'singular_label' => $taxonomyModel->getSingular(),
			    'label' => $taxonomyModel->getPlural(),
			    'labels' => $taxonomyModel->getLabels(),
		    ],
		    $taxonomyModel->getSettings()
	    );

        if (empty($plural) or $plural === '') {
            $plural = $taxonomyName . 's';
        }

        $taxonomyName = ucwords($taxonomyName);

	    $options = array_merge(
		    [
			    "hierarchical" => true,
			    "label" => $taxonomyName,
			    "singular_label" => $plural,
			    "show_ui" => true,
			    "query_var" => true,
			    'show_admin_column' => true,
			    "show_in_rest" => true,
			    "rewrite" => ["slug" => strtolower($taxonomyName)]
		    ], $options
	    );

	    // fix for post_tag
		if($slug === 'post_tag'){
			$options["hierarchical"] = false;
		}

	    $customPostTypesArray = [];

	    foreach ($taxonomyModel->getCustomPostTypes() as $customPostTypeModel){
		    $customPostTypesArray[] = $customPostTypeModel->getName();
        }

	    register_taxonomy(strtolower($taxonomyName), $customPostTypesArray, $options);
    }

    /**
     * When a post saved/updated in the database using `save_post_my_post_type` callback [Wordpress 3.7+],
     * this methods updates the meta box params in the db as well.
     */
    public function savePost()
    {
        add_action( 'save_post_'.$this->postTypeName, [&$this, "saveCustomPostType"]);
    }

	/**
	 * Custom save_post function
	 *
	 * @param $postId
	 * @throws \Exception
	 */
    public function saveCustomPostType($postId)
    {
        $errors = [];

        if ($_POST and !isset($_POST[Nonce::ACPT_NONCE])) {
            return;
        }

	    if (isset($_POST[Nonce::ACPT_NONCE]) and !Nonce::verify(@$_POST[Nonce::ACPT_NONCE])) {
		    return;
	    }

        if(isset($_POST['meta_fields'])){

            $metaFields = $_POST['meta_fields'];

            foreach ($metaFields as $key) {

                if (!empty($_FILES[$key])) {
                    if (!empty($_FILES[$key]['tmp_name'])) {
                        $upload = wp_upload_bits($_FILES[$key]['name'], null, file_get_contents($_FILES[$key]['tmp_name']));

                        if (isset($upload['error']) && $upload['error'] != 0) {
                            wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
                        } else {
                            update_post_meta($postId, $key, esc_sql($upload['url']));
                        }
                    }
                } else {
                    if(isset($_POST[$key])){

                    	$rawValue = $_POST[$key];
                        $type = (isset($_POST[$key.'_type'])) ? sanitize_text_field($_POST[$key.'_type']) : null;
                        $isRequired = (isset($_POST[$key.'_required']) and $_POST[$key.'_required'] == 1) ? true : false;

                        // validation
                        try {
	                        MetaDataValidator::validate($type, $rawValue, $isRequired);
                        } catch (\Exception $exception){
                            wp_die('There was an error during saving data. The error is: ' . $exception->getMessage());
                        }

                        if(Strings::contains(RelationCostants::RELATION_KEY, $key)){
                            $this->handleRelations($postId, $key);
                        } else {

                            $value = $rawValue;

                            if(is_array($value)){
                                if($type === CustomPostTypeMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE){

	                                $minimumBlocks = isset($_POST[$key.'_min_blocks']) ? $_POST[$key.'_min_blocks'] : null;
	                                $maximumBlocks = isset($_POST[$key.'_max_blocks']) ? $_POST[$key.'_max_blocks'] : null;
	                                $numberOfBlocks = count($value);

	                                if($minimumBlocks and ($numberOfBlocks < $minimumBlocks )){
		                                wp_die('There was an error during saving data. Minimum number of blocks is : ' . $minimumBlocks);
	                                }

	                                if($maximumBlocks and ($numberOfBlocks > $maximumBlocks )){
		                                wp_die('There was an error during saving data. Maximum number of blocks is : ' . $maximumBlocks);
	                                }

                                    foreach ($value as $blockName => $fields){
	                                    $value[$blockName] = Arrays::reindex($fields);
                                    }
                                } else {
	                                $value = Arrays::reindex($value);
                                }
                            }

                            update_post_meta($postId, $key, Sanitizer::sanitizeRawData($type, $value));
                        }
                    } else {
                        update_post_meta($postId, $key, '');
                    }
                }
            }

            if(!empty($errors)){
                set_transient( "acpt_plugin_error_msg_".$postId, $errors, 60 );
                add_filter( 'redirect_post_location', [$this, 'addNoticeQueryVar'], 99 );
            }
        }
    }

    /**
     * This function handles the post relationship complex logic
     *
     * @param int $postId
     * @param string $key
     *
     * @throws \Exception
     */
    private function handleRelations($postId, $key)
    {
	    $relationship = null;
        $originalValues = explode(",", $_POST[$key.'_original_values']);
        $inversedIds = explode(",", sanitize_text_field($_POST[$key]));
        $strippedKey = str_replace(RelationCostants::RELATION_KEY, '', $key);
	    $explodedStrippedKey = explode(PostField::SEPARATOR, $strippedKey);
	    $box = $explodedStrippedKey[0];
	    $field = $explodedStrippedKey[1];
	    $plainKey = $box . '_' .$field;

        if('' !== $_POST[$key] and !empty($inversedIds)){

	        $inversedMeteFieldModel = MetaRepository::getMetaFieldByName([
		        'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
		        'find' => get_post_type($inversedIds[0]),
		        'boxName' => $box,
		        'fieldName' => $field,
	        ]);

            foreach ($inversedIds as $inversedId){

                $inversedId = (int)$inversedId;

                update_post_meta($inversedId, $plainKey.'_type', CustomPostTypeMetaBoxFieldModel::POST_TYPE);

                if($inversedMeteFieldModel !== null){
	                $relationships = $inversedMeteFieldModel->getRelations();

	                if(!empty($relationships)){
		                $relationship = $relationships[0];

		                if(true === $relationship->isMany()){

			                $previousValues = (get_post_meta($inversedId, $plainKey, true) !== '') ? get_post_meta($inversedId, $plainKey, true) : [];

			                if(!in_array($postId, $previousValues)){
				                $previousValues[] = $postId;
			                }

			                update_post_meta($inversedId, $plainKey, Sanitizer::sanitizeRawData(CustomPostTypeMetaBoxFieldModel::POST_TYPE, $previousValues));

		                } else {
			                $this->deleteOrUpdateOtherPostMeta($relationship->getInversedBy()->getDbName(), $postId, $inversedId);
			                update_post_meta($inversedId, $plainKey, Sanitizer::sanitizeRawData(CustomPostTypeMetaBoxFieldModel::POST_TYPE, $postId));
		                }
	                }
                }
            }

            if($relationship){
	            $idsToBeDeleted = array_diff($originalValues, $inversedIds);

	            foreach ($idsToBeDeleted as $idToBeDeleted){
	                $this->deleteOrUpdateOtherPostMeta($relationship->getMetaBoxField()->getDbName(), $idToBeDeleted, $postId);
	            }
            }
        }
    }

    /**
     * Used ONLY for not Multi relations.
     *
     * This function deletes or updates the cpts metas with post_id = $postId
     * which value contains $inversedId
     *
     * @param $key
     * @param $postId
     * @param $inversedId
     *
     * @throws \Exception
     */
    private function deleteOrUpdateOtherPostMeta($key, $postId, $inversedId)
    {
        global $wpdb;

        $baseQuery = "
            SELECT
                *
            FROM `{$wpdb->prefix}postmeta`
            WHERE
                `meta_key` = %s
            AND 
                `post_id` = %d
            ";

        $results = ACPT_DB::getResults($baseQuery,
            [
                $key,
	            $postId
            ]
        );

        foreach ($results as $result){

            // check if data is serialized
            if(is_serialized($result->meta_value)){

                $value = unserialize($result->meta_value);
                $postIdMatch = array_search($inversedId, $value);

                if(false !== $postIdMatch){
                    unset($value[$postIdMatch]);
                }

                if(!empty($value)){
                    $sql = "UPDATE `{$wpdb->prefix}postmeta` SET meta_value = %s WHERE meta_id = %s";
                    ACPT_DB::executeQueryOrThrowException($sql, [
                            serialize($value),
                            $result->meta_id,
                    ]);
                } else {
                    $sql = "DELETE FROM `{$wpdb->prefix}postmeta` WHERE meta_id = %s";
                    ACPT_DB::executeQueryOrThrowException($sql, [ $result->meta_id ]);
                }
            } else {
	            $sql = "DELETE FROM `{$wpdb->prefix}postmeta` WHERE meta_id = %s";
	            ACPT_DB::executeQueryOrThrowException($sql, [ $result->meta_id ]);
            }
        }
    }

    /**
     * @param $location
     * @return mixed
     */
    public function addNoticeQueryVar( $location )
    {
        remove_filter( 'redirect_post_location', array( $this, 'addNoticeQueryVar' ), 99 );

        return add_query_arg( ['errors' => true], $location );
    }

    /**
     * Display legacy notices
     */
    public function legacyAdminNotices()
    {
        if ( ! isset( $_GET['errors'] ) ) {
            return;
        }

        global $post;

        if($post->post_type === $this->postTypeName){
            if ( false !== ( $errors = get_transient( "acpt_plugin_error_msg_{$post->ID}" ) ) && $errors) {
                delete_transient( "acpt_plugin_error_msg_{$post->ID}" );
                foreach ($errors as $error){
                ?>
                    <div class="notice notice-error is-dismissible">
                        <p><?php esc_html( $error ); ?></p>
                    </div>
                <?php
                }
            }
        }
    }
}

