<?php

namespace ACPT\Utils\MetaSync;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxModel;
use ACPT\Includes\ACPT_DB;

class TaxonomyMetaSync extends AbstractMetaSync
{
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
			self::updateBoxPostMeta($boxNames['newKey'], $boxNames['oldKey'], $oldBox->taxonomy);
		}
	}

	/**
	 * @param $newKey
	 * @param $oldKey
	 * @param $taxonomy
	 *
	 * @throws \Exception
	 */
	private static function updateBoxPostMeta($newKey, $oldKey, $taxonomy)
	{
		global $wpdb;

		if($newKey !== $oldKey){
			$sql = "UPDATE `{$wpdb->prefix}termmeta` tm 
                    JOIN `{$wpdb->prefix}terms` t ON t.term_id=tm.term_id
                    JOIN `wp_term_taxonomy` tax ON tax.term_id=t.term_id
                    SET tm.meta_key=REPLACE(tm.meta_key, %s, %s) 
                    WHERE tm.meta_key LIKE %s AND tax.taxonomy=%s";
			ACPT_DB::executeQueryOrThrowException($sql, [$oldKey, $newKey, $oldKey.'%', $taxonomy]);
		}
	}

	/**
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 *
	 * @throws \Exception
	 */
	public static function syncField( AbstractMetaBoxFieldModel $fieldModel )
	{
		if($fieldModel->getParentId() !== null){
			self::updateNestedPostMetaWhenFieldNameChanges($fieldModel);
		} else {
			self::updatePostMetaWhenFieldNameChanges($fieldModel);
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

				global $wpdb;

				// old parent key
				$parentBoxName = $parentField->meta_box_name;
				$parentFieldName = $parentField->field_name;
				$parentKey = Strings::toDBFormat($parentBoxName).'_'.Strings::toDBFormat($parentFieldName);

				// old key
				$fieldName = $oldField->field_name;
				$postType = $oldField->post_type;
				$oldKey = Strings::toDBFormat($fieldName);

				// new key
				$newKey = Strings::toDBFormat($fieldModel->getName());

				$query = "
                    SELECT * FROM `{$wpdb->prefix}termmeta` tm 
                    JOIN `{$wpdb->prefix}terms` t ON t.term_id=tm.term_id
                    WHERE tm.meta_key=%s AND t.slug=%s
                ";
				$results = ACPT_DB::getResults($query, [$parentKey, $postType]);

				foreach ($results as $result) {

					$oldMetaValue = unserialize( $result->meta_value );

					// key update
					if($newKey !== $oldKey){
						$oldMetaValue[$newKey] = $oldMetaValue[$oldKey];
						unset($oldMetaValue[$oldKey]);

						$metaValue = serialize($oldMetaValue);

						$sql = "
                            UPDATE `{$wpdb->prefix}termmeta` tm 
                            JOIN `{$wpdb->prefix}terms` t ON t.term_id=tm.term_id
                            SET tm.meta_value=%s 
                            WHERE tm.meta_key=%s AND t.slug=%s
                        ";
						ACPT_DB::executeQueryOrThrowException($sql, [$metaValue, $parentKey, $postType]);
					}
				}
			}
		}
	}

	/**
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 *
	 * @throws \Exception
	 */
	private  static function updatePostMetaWhenFieldNameChanges(AbstractMetaBoxFieldModel $fieldModel)
	{
		if($oldField = self::getMetaFieldData($fieldModel)){

			// old key
			$boxName = $oldField->meta_box_name;
			$taxonomy = $oldField->taxonomy;
			$fieldName = $oldField->field_name;
			$oldKey = Strings::toDBFormat($boxName).'_'.Strings::toDBFormat($fieldName);

			// new key
			$newKey = $fieldModel->getDbName();

			self::updateFieldPostMeta($newKey, $oldKey, $taxonomy);
		}
	}

	/**
	 * @param $newKey
	 * @param $oldKey
	 * @param $taxonomy
	 *
	 * @throws \Exception
	 */
	private static function updateFieldPostMeta($newKey, $oldKey, $taxonomy)
	{
		global $wpdb;

		if($newKey !== $oldKey){
			$sql = "
                UPDATE `{$wpdb->prefix}termmeta` tm 
                JOIN `{$wpdb->prefix}terms` t ON t.term_id=tm.term_id
                SET tm.meta_key=%s 
                WHERE tm.meta_key=%s AND t.slug=%s
            ";

			ACPT_DB::executeQueryOrThrowException($sql, [$newKey, $oldKey, $taxonomy]);

			$sql = "
                UPDATE `{$wpdb->prefix}termmeta` tm 
                JOIN `{$wpdb->prefix}terms` t ON t.term_id=tm.term_id
                SET meta_key=REPLACE(tm.meta_key, %s, %s) 
                WHERE tm.meta_key LIKE %s AND t.slug=%s
            ";
			ACPT_DB::executeQueryOrThrowException($sql, [$oldKey.'_', $newKey.'_', $oldKey.'_%', $taxonomy]);
		}
	}
}