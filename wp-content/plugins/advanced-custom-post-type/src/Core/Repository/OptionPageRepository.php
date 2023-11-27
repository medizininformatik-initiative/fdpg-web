<?php

namespace ACPT\Core\Repository;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxModel;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxModel;
use ACPT\Core\Models\OptionPage\OptionPageModel;
use ACPT\Costants\MetaTypes;
use ACPT\Includes\ACPT_DB;
use ACPT\Utils\MetaSync\MetaSync;
use ACPT\Utils\MetaSync\OptionPageMetaSync;
use Automattic\WooCommerce\GoogleListingsAndAds\Admin\MetaBox\AbstractMetaBox;

class OptionPageRepository
{
	/**
	 * @return int
	 */
	public static function count()
	{
		$baseQuery = "
            SELECT 
                count(id) as count
            FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_OPTION_PAGE)."`
            ";

		$results = ACPT_DB::getResults($baseQuery);

		return (int)$results[0]->count;
	}

	/**
	 * @param bool $deleteOptions
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public static function deleteAll($deleteOptions = false)
	{
		$optionPageModels = self::get([]);

		foreach ($optionPageModels as $optionPageModel){
			if(self::delete($optionPageModel, $deleteOptions) === false){
				return false;
			}

			foreach ($optionPageModel->getChildren() as $childPageModel){
				if(self::delete($childPageModel, $deleteOptions) === false){
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * @param OptionPageModel $optionPageModel
	 * @param bool $deleteOptions
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public static function delete(OptionPageModel $optionPageModel, $deleteOptions = false)
	{
		ACPT_DB::startTransaction();

		try {
			$sql = "
	            DELETE
	                FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_OPTION_PAGE)."`
	                WHERE id = %s
	            ";

			MetaRepository::deleteAll([
				'belongsTo' => MetaTypes::OPTION_PAGE,
				'find' => $optionPageModel->getMenuSlug(),
			]);

			ACPT_DB::executeQueryOrThrowException($sql, [$optionPageModel->getId()]);
			ACPT_DB::commitTransaction();

			if($deleteOptions){
				self::deleteOptions($optionPageModel->getMetaBoxes());
			}

			foreach ($optionPageModel->getChildren() as $childPage){
				self::delete($childPage, $deleteOptions);
			}

			return true;
		} catch (\Exception $exception){
			ACPT_DB::rollbackTransaction();

			return false;
		}
	}

	/**
	 * @param AbstractMetaBoxModel[] $metaBoxes
	 *
	 * @throws \Exception
	 */
	private static function deleteOptions(array $metaBoxes)
	{
		global $wpdb;

		foreach ($metaBoxes as $metaBoxModel){
			foreach ($metaBoxModel->getFields() as $metaFieldModel){
				$metaFieldModel->getDbName();

				$query = "DELETE 
            		FROM `{$wpdb->prefix}options` o
            		WHERE o.option_name = %s";

				ACPT_DB::executeQueryOrThrowException($query, [$metaFieldModel->getDbName()]);
			}
		}
	}

	/**
	 * @param $menuSlug
	 *
	 * @return bool
	 */
	public static function exists($menuSlug)
	{
		$baseQuery = "
            SELECT 
                id
            FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_OPTION_PAGE)."`
            WHERE menu_slug = %s
            ";

		$pages = ACPT_DB::getResults($baseQuery, [$menuSlug]);

		return count($pages) === 1;
	}

	/**
	 * @param $menuSlug
	 *
	 * @return mixed|null
	 */
	public static function getId($menuSlug)
	{
		$baseQuery = "
            SELECT 
                id
            FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_OPTION_PAGE)."`
            WHERE menu_slug = %s
            ";

		$posts = ACPT_DB::getResults($baseQuery, [$menuSlug]);

		if(count($posts) === 1){
			return $posts[0]->id;
		}

		return null;
	}

	/**
	 * @return array
	 */
	public static function getAllIds()
	{
		$results = [];

		$baseQuery = "
            SELECT 
                op.id
            FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_OPTION_PAGE)."` op
            GROUP BY op.id ORDER BY op.sort ASC;
            ";

		$optionPageIds = ACPT_DB::getResults($baseQuery, []);

		foreach ($optionPageIds as $optionPageId){
			$results[] = $optionPageId->id;
		}

		return $results;
	}

	/**
	 * @param array $meta
	 * @param bool $lazy
	 *
	 * @return OptionPageModel[]
	 * @throws \Exception
	 */
	public static function get(array $meta = [], $lazy = false)
	{
		$results = [];
		$args = [];

		$baseQuery = "
            SELECT 
                op.id, 
                op.page_title,
	            op.menu_title,
	            op.capability,
	            op.menu_slug,
                op.icon,
                op.description,
                op.parent_id,
                op.sort,
                op.page_position as `position`
            FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_OPTION_PAGE)."` op
            WHERE parent_id = ''
            ";

		if(isset($meta['id'])){
			$baseQuery .= " AND op.id = %s";
			$args[] = $meta['id'];
		}

		if(isset($meta['exclude'])){
			$baseQuery .= " AND op.menu_slug != %s";
			$args[] = $meta['exclude'];
		}

		if(isset($meta['excludeIds'])){
			$baseQuery .= " AND op.id NOT IN ('".implode("','", $meta['excludeIds'])."')";
		}

		if(isset($meta['menuSlug'])){
			$baseQuery .= " AND op.menu_slug = %s ";
			$args[] = $meta['menuSlug'];
		}

		$baseQuery .= " GROUP BY op.id";
		$baseQuery .= " ORDER BY op.sort ASC";

		if(isset($meta['page']) and isset($meta['perPage'])){
			$baseQuery .= " LIMIT ".$meta['perPage']." OFFSET " . ($meta['perPage'] * ($meta['page'] - 1));
		}

		$baseQuery .= ';';
		$optionPages = ACPT_DB::getResults($baseQuery, $args);

		foreach ($optionPages as $optionPage){
			$optionPageModel = OptionPageModel::hydrateFromArray([
				'id' => $optionPage->id,
				'pageTitle' => $optionPage->page_title,
				'menuTitle' => $optionPage->menu_title,
				'capability' => $optionPage->capability,
				'menuSlug' => $optionPage->menu_slug,
				'icon' => $optionPage->icon,
				'description' => $optionPage->description,
				'parentId' => null,
				'sort' => $optionPage->sort,
				'position' => $optionPage->position,
			]);

			// Children here
			$baseQuery = "
	            SELECT 
	                ch.id, 
	                ch.page_title,
		            ch.menu_title,
		            ch.capability,
		            ch.menu_slug,
	                ch.icon,
	                ch.description,
	                ch.parent_id,
	                ch.sort,
	                ch.page_position as `position`
	            FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_OPTION_PAGE)."` ch
	            WHERE ch.parent_id = %s
            ";

			if(isset($args['excludeIds'])){
				$baseQuery .= " AND ch.id NOT IN ('".implode("','", $args['excludeIds'])."')";
			}

			$baseQuery .= ' GROUP BY ch.id ORDER BY ch.sort ASC';

			$childrenPages = ACPT_DB::getResults($baseQuery, [$optionPageModel->getId()]);

			foreach ($childrenPages as $childrenPage){
				$childPageModel = OptionPageModel::hydrateFromArray([
					'id' => $childrenPage->id,
					'parentId' => $optionPage->id,
					'pageTitle' => $childrenPage->page_title,
					'menuTitle' => $childrenPage->menu_title,
					'capability' => $childrenPage->capability,
					'menuSlug' => $childrenPage->menu_slug,
					'description' => $childrenPage->description,
					'sort' => $childrenPage->sort,
					'position' => $childrenPage->position,
				]);

				$optionPageModel->addChild($childPageModel);
			}

			// Meta: NOT-LAZY MODE
			if(!$lazy){
				$optionPageModel = self::addMetaToOptionPage($optionPageModel);
			}

			$results[] = $optionPageModel;
		}

		return $results;
	}

	/**
	 * @param OptionPageModel $optionPageModel
	 *
	 * @return OptionPageModel
	 * @throws \Exception
	 */
	private static function addMetaToOptionPage(OptionPageModel $optionPageModel)
	{
		// Meta boxes
		$metaBoxQuery = "
            SELECT 
                id, 
                meta_box_name as name,
                meta_box_label as label,
                sort
            FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_OPTION_PAGE_META_BOX)."`
            WHERE page = %s
            ";
		$metaBoxArgs = [$optionPageModel->getMenuSlug()];
		$metaBoxQuery .= " ORDER BY sort;";

		$boxes = ACPT_DB::getResults($metaBoxQuery, $metaBoxArgs);

		foreach ($boxes as $boxIndex => $box){
			$boxModel = OptionPageMetaBoxModel::hydrateFromArray([
				'id' => $box->id,
				'optionPage' => $optionPageModel->getMenuSlug(),
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
                        sort
                    FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD)."`
                    WHERE meta_box_id = %s
                    AND parent_id IS NULL 
                    AND block_id IS NULL
                ";

			$sql .= " ORDER BY sort;";

			$fields = ACPT_DB::getResults($sql, [$box->id]);

			// Meta box fields
			foreach ($fields as $fieldIndex => $field){
				$fieldModel = MetaRepository::hydrateMetaBoxFieldModel($field, $boxModel);
				$boxModel->addField($fieldModel);
			}

			$optionPageModel->addMetaBox($boxModel);
		}

		foreach ($optionPageModel->getChildren() as $childPageModel){
			$optionPageModel->removeChild($childPageModel);
			$optionPageModel->addChild(self::addMetaToOptionPage($childPageModel));
		}

		return $optionPageModel;
	}

	/**
	 * @param $slug
	 * @param bool $lazy
	 *
	 * @return OptionPageModel|null
	 * @throws \Exception
	 */
	public static function getByMenuSlug($slug, $lazy = false)
	{
		$baseQuery = "
            SELECT 
                id
            FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_OPTION_PAGE)."`
            WHERE menu_slug = %s
            ";

		$page = @ACPT_DB::getResults($baseQuery, [$slug])[0];

		if($page){
			return self::getById($page->id, $lazy);
		}

		return null;
	}

	/**
	 * @param $id
	 * @param bool $lazy
	 *
	 * @return OptionPageModel|null
	 * @throws \Exception
	 */
	public static function getById($id, $lazy = false)
	{
		$result = null;

		$baseQuery = "
            SELECT 
                op.id, 
                op.page_title,
	            op.menu_title,
	            op.capability,
	            op.menu_slug,
                op.icon,
                op.description,
                op.parent_id,
                op.sort,
                op.page_position as `position`
            FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_OPTION_PAGE)."` op
            WHERE id = %s;
            ";

		$optionPages = ACPT_DB::getResults($baseQuery, [$id]);

		foreach ($optionPages as $optionPage){
			$optionPageModel = OptionPageModel::hydrateFromArray([
				'id' => $optionPage->id,
				'pageTitle' => $optionPage->page_title,
				'menuTitle' => $optionPage->menu_title,
				'capability' => $optionPage->capability,
				'menuSlug' => $optionPage->menu_slug,
				'icon' => $optionPage->icon,
				'description' => $optionPage->description,
				'parentId' => $optionPage->parent_id,
				'sort' => $optionPage->sort,
				'position' => $optionPage->position,
			]);

			// Children here
			$baseQuery = "
	            SELECT 
	                ch.id, 
	                ch.page_title,
		            ch.menu_title,
		            ch.capability,
		            ch.menu_slug,
	                ch.icon,
	                ch.description,
	                ch.parent_id,
	                ch.sort,
	                ch.page_position as `position`
	            FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_OPTION_PAGE)."` ch
	            WHERE ch.parent_id = %s GROUP BY ch.id ORDER BY ch.sort ASC;
            ";

			$childrenPages = ACPT_DB::getResults($baseQuery, [$optionPageModel->getId()]);

			foreach ($childrenPages as $childrenPage){
				$childPageModel = OptionPageModel::hydrateFromArray([
					'id' => $childrenPage->id,
					'parentId' => $optionPage->id,
					'pageTitle' => $childrenPage->page_title,
					'menuTitle' => $childrenPage->menu_title,
					'capability' => $childrenPage->capability,
					'menuSlug' => $childrenPage->menu_slug,
					'description' => $childrenPage->description,
					'sort' => $childrenPage->sort,
					'position' => $childrenPage->position,
				]);

				$optionPageModel->addChild($childPageModel);
			}

			// Meta: NOT-LAZY MODE
			if(!$lazy){
				$optionPageModel = self::addMetaToOptionPage($optionPageModel);
			}

			$result = $optionPageModel;
		}

		return $result;
	}

	/**
	 * @param OptionPageModel $optionPage
	 *
	 * @throws \Exception
	 */
	public static function save(OptionPageModel $optionPage)
	{
		OptionPageMetaSync::syncAllMeta($optionPage);

		$sql = "
            INSERT INTO `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_OPTION_PAGE)."` 
            (`id`,
            `page_title`,
			`menu_title`,
			`capability`,
			`menu_slug`,
            `icon`,
            `description`,
            `parent_id`,
            `sort`,
            `page_position`
            ) VALUES (
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %d,
                %d
            ) ON DUPLICATE KEY UPDATE 
                `page_title` = %s,
				`menu_title` = %s,
				`capability` = %s,
				`menu_slug` = %s,
                `icon` = %s,
                `description` = %s,
                `parent_id` = %s,
                `sort` = %d,
                `page_position` = %d
        ;";

		ACPT_DB::executeQueryOrThrowException($sql, [
			$optionPage->getId(),
			$optionPage->getPageTitle(),
			$optionPage->getMenuTitle(),
			$optionPage->getCapability(),
			$optionPage->getMenuSlug(),
			$optionPage->getIcon(),
			$optionPage->getDescription(),
			$optionPage->getParentId(),
			$optionPage->getSort(),
			$optionPage->getPosition(),
			$optionPage->getPageTitle(),
			$optionPage->getMenuTitle(),
			$optionPage->getCapability(),
			$optionPage->getMenuSlug(),
			$optionPage->getIcon(),
			$optionPage->getDescription(),
			$optionPage->getParentId(),
			$optionPage->getSort(),
			$optionPage->getPosition(),
		]);

		foreach ($optionPage->getChildren() as $childOptionPage){
			self:self::save($childOptionPage);
		}
	}
}