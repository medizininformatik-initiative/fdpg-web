<?php

namespace ACPT\Utils\MetaSync;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxModel;
use ACPT\Core\Models\OptionPage\OptionPageModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Core\Repository\OptionPageRepository;
use ACPT\Costants\MetaTypes;
use ACPT\Includes\ACPT_DB;

class OptionPageMetaSync extends AbstractMetaSync
{
	/**
	 * @param OptionPageModel $optionPage
	 *
	 * @throws \Exception
	 */
	public static function syncAllMeta(OptionPageModel $optionPage)
	{
		$savedPage = OptionPageRepository::getById($optionPage->getId(), true);

		if($savedPage !== null){
			$sql = "
                UPDATE `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_OPTION_PAGE_META_BOX)."` 
                SET page=%s 
                WHERE page=%s
            ";

			ACPT_DB::executeQueryOrThrowException($sql, [$optionPage->getMenuSlug(), $savedPage->getMenuSlug()]);
		}
	}

	/**
	 * @param AbstractMetaBoxModel $metaBoxModel
	 *
	 * @return mixed|void
	 * @throws \Exception
	 */
	public static function syncBox( AbstractMetaBoxModel $metaBoxModel )
	{
		if($oldBox = self::getMetaBoxData($metaBoxModel)){
			$boxNames = self::changedBoxNames($oldBox->meta_box_name, $metaBoxModel->getName());
			self::updateBoxPostMeta( $boxNames['newKey'], $boxNames['oldKey'] );
		}
	}

	/**
	 * @param $newKey
	 * @param $oldKey
	 * @param $postType
	 *
	 * @throws \Exception
	 */
	private static function updateBoxPostMeta($newKey, $oldKey)
	{
		global $wpdb;

		if($newKey !== $oldKey){
			$sql = "UPDATE `{$wpdb->prefix}options` o 
                    SET o.option_name=REPLACE(o.option_name, %s, %s) 
                    WHERE o.option_name LIKE %s";
			ACPT_DB::executeQueryOrThrowException($sql, [$oldKey, $newKey, $oldKey.'%']);
		}
	}

	/**
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 *
	 * @return mixed|void
	 * @throws \Exception
	 */
	public static function syncField( AbstractMetaBoxFieldModel $fieldModel )
	{
		if($fieldModel->getParentId() !== null){
			self::updateNestedPostMetaWhenFieldNameChanges($fieldModel);
		} elseif($fieldModel->getBlockId() !== null){
			self::updateNestedPostMetaInABlockWhenFieldNameChanges($fieldModel);
		}  else {
			self::updatePostMetaWhenFieldNameChanges($fieldModel);
		}
	}

	/**
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 *
	 * @throws \Exception
	 */
	private static function updateNestedPostMetaInABlockWhenFieldNameChanges(AbstractMetaBoxFieldModel $fieldModel )
	{
		if($oldField = self::getMetaFieldData($fieldModel)){
			$parentBlockModel = MetaRepository::getMetaBlockById([
				'belongsTo' => MetaTypes::OPTION_PAGE,
				'id' => $fieldModel->getBlockId()
			]);

			if($parentBlockModel !== null){
				$parentField = $parentBlockModel->getMetaBoxField();

				// old parent key
				$parentBoxName = $parentField->getMetaBox()->getName();
				$parentFieldName = $parentField->getName();
				$parentKey = Strings::toDBFormat($parentBoxName).'_'.Strings::toDBFormat($parentFieldName);

				// old key
				$fieldName = $oldField->field_name;
				$postType = $oldField->post_type;
				$oldKey = Strings::toDBFormat($fieldName);

				// new key
				$newKey = Strings::toDBFormat($fieldModel->getName());

				self::updateNestedPostMetaData($parentKey, $oldKey, $newKey);
			}
		}
	}

	/**
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 *
	 * @throws \Exception
	 */
	private static function updateNestedPostMetaWhenFieldNameChanges(AbstractMetaBoxFieldModel $fieldModel )
	{
		if($oldField = self::getMetaFieldData($fieldModel)){

			if($parentField = self::getParentMetaFieldData($fieldModel)){

				// old parent key
				$parentBoxName = $parentField->meta_box_name;
				$parentFieldName = $parentField->field_name;
				$parentKey = Strings::toDBFormat($parentBoxName).'_'.Strings::toDBFormat($parentFieldName);

				// old key
				$fieldName = $oldField->field_name;
				$oldKey = Strings::toDBFormat($fieldName);

				// new key
				$newKey = Strings::toDBFormat($fieldModel->getName());

				self::updateNestedPostMetaData($parentKey, $oldKey, $newKey);
			}
		}
	}

	/**
	 * @param $parentKey
	 * @param $oldKey
	 * @param $newKey
	 *
	 * @throws \Exception
	 */
	private static function updateNestedPostMetaData($parentKey, $oldKey, $newKey)
	{
		global $wpdb;

		$query = "
                    SELECT * FROM `{$wpdb->prefix}options` o 
                    WHERE o.option_name=%s
                ";
		$results = ACPT_DB::getResults($query, [$parentKey]);

		foreach ($results as $result) {

			$oldMetaValue = unserialize( $result->option_value );

			// key update
			if($newKey !== $oldKey){

				// blocks
				if(isset($oldMetaValue['blocks']) and is_array($oldMetaValue['blocks'])){
					foreach ($oldMetaValue['blocks'] as $blockIndex => $block){
						foreach ($block as $blockName => $blockValues){
							foreach ($blockValues as $fieldName => $fieldValues){
								if($fieldName === $oldKey){
									$oldMetaValue['blocks'][$blockIndex][$blockName][$newKey] = $oldMetaValue['blocks'][$blockIndex][$blockName][$oldKey];
									unset($oldMetaValue['blocks'][$blockIndex][$blockName][$oldKey]);
								}
							}
						}
					}
				} else {
					$oldMetaValue[$newKey] = $oldMetaValue[$oldKey];
					unset($oldMetaValue[$oldKey]);
				}

				$metaValue = serialize($oldMetaValue);

				$sql = "
                            UPDATE `{$wpdb->prefix}options` o 
                            SET o.option_value=%s 
                            WHERE o.option_name=%s 
                        ";
				ACPT_DB::executeQueryOrThrowException($sql, [$metaValue, $parentKey]);
			}
		}
	}

	/**
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 *
	 * @throws \Exception
	 */
	private static function updatePostMetaWhenFieldNameChanges(AbstractMetaBoxFieldModel $fieldModel)
	{
		if($oldField = self::getMetaFieldData($fieldModel)){

			// old key
			$boxName = $oldField->meta_box_name;
			$fieldName = $oldField->field_name;
			$oldKey = Strings::toDBFormat($boxName).'_'.Strings::toDBFormat($fieldName);

			// new key
			$newKey = $fieldModel->getDbName();

			self::updateFieldPostMeta($newKey, $oldKey);
		}
	}

	/**
	 * @param $newKey
	 * @param $oldKey
	 *
	 * @throws \Exception
	 */
	private static function updateFieldPostMeta($newKey, $oldKey)
	{
		global $wpdb;

		if($newKey !== $oldKey){
			$sql = "
                UPDATE `{$wpdb->prefix}options` o 
                SET o.option_name=%s 
                WHERE o.option_name=%s
            ";

			ACPT_DB::executeQueryOrThrowException($sql, [$newKey, $oldKey]);

			$sql = "
                UPDATE `{$wpdb->prefix}options` o 
                SET o.option_name=REPLACE(o.option_name, %s, %s) 
                WHERE o.option_name LIKE %s 
            ";
			ACPT_DB::executeQueryOrThrowException($sql, [$oldKey.'_', $newKey.'_', $oldKey.'_%']);
		}
	}
}