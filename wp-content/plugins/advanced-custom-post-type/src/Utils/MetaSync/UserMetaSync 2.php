<?php

namespace ACPT\Utils\MetaSync;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxModel;
use ACPT\Includes\ACPT_DB;

class UserMetaSync extends AbstractMetaSync
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
			self::updateBoxPostMeta($boxNames['newKey'], $boxNames['oldKey']);
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
			$sql = "UPDATE `{$wpdb->prefix}usermeta` um 
                    SET um.meta_key=REPLACE(um.meta_key, %s, %s) 
                    WHERE um.meta_key LIKE %s";
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
	 * @param $postType
	 *
	 * @throws \Exception
	 */
	private static function updateFieldPostMeta($newKey, $oldKey)
	{
		global $wpdb;

		if($newKey !== $oldKey){
			$sql = "
                UPDATE `{$wpdb->prefix}usermeta` um 
                SET um.meta_key=%s 
                WHERE um.meta_key=%s
            ";

			ACPT_DB::executeQueryOrThrowException($sql, [$newKey, $oldKey]);

			$sql = "
                UPDATE `{$wpdb->prefix}usermeta` um 
                SET meta_key=REPLACE(um.meta_key, %s, %s) 
                WHERE um.meta_key LIKE %s
            ";
			ACPT_DB::executeQueryOrThrowException($sql, [$oldKey.'_', $newKey.'_', $oldKey.'_%']);
		}
	}
}