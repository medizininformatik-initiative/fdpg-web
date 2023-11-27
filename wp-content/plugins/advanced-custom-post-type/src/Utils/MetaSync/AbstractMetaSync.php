<?php

namespace ACPT\Utils\MetaSync;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxModel;
use ACPT\Costants\MetaTypes;
use ACPT\Includes\ACPT_DB;

abstract class AbstractMetaSync
{
	/**
	 * @param AbstractMetaBoxModel $metaBoxModel
	 *
	 * @return mixed
	 */
	public abstract static function syncBox(AbstractMetaBoxModel $metaBoxModel);

	/**
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 *
	 * @return mixed
	 */
	public abstract static function syncField(AbstractMetaBoxFieldModel $fieldModel);

	/**
	 * @param AbstractMetaBoxModel $metaBoxModel
	 *
	 * @return mixed|bool
	 */
	protected static function getMetaBoxData(AbstractMetaBoxModel $metaBoxModel)
	{
		// check if box already exists
		$query = "SELECT * FROM 
            `".self::metaBoxTable($metaBoxModel)."` 
            WHERE id = %s 
        ";
		$results = ACPT_DB::getResults($query, [$metaBoxModel->getId()]);

		if(isset($results[0]) and count($results) === 1){
			return $results[0];
		}

		return false;
	}

	/**
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 *
	 * @return mixed|bool
	 */
	protected static function getMetaFieldData(AbstractMetaBoxFieldModel $fieldModel)
	{
		$query = "SELECT * FROM 
            `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD)."` f
            JOIN `".self::metaBoxTable($fieldModel->getMetaBox())."` b ON b.id = f.meta_box_id
            WHERE f.id = %s 
            GROUP BY f.id
        ";
		$results = ACPT_DB::getResults($query, [$fieldModel->getId()]);

		if(isset($results[0]) and count($results) === 1){
			return $results[0];
		}

		return false;
	}

	/**
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 *
	 * @return mixed|bool
	 */
	protected static function getParentMetaFieldData(AbstractMetaBoxFieldModel $fieldModel)
	{
		$query = "SELECT * FROM 
            `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD)."` f
            JOIN `".self::metaBoxTable($fieldModel->getMetaBox())."` b ON b.id = f.meta_box_id
            WHERE f.id = %s 
            GROUP BY f.id
        ";
		$results = ACPT_DB::getResults($query, [$fieldModel->getParentId()]);

		if(isset($results[0]) and count($results) === 1){
			return $results[0];
		}

		return false;
	}

	/**
	 * @param AbstractMetaBoxModel $metaBoxModel
	 * @return string|null
	 */
	protected static function metaBoxTable(AbstractMetaBoxModel $metaBoxModel)
	{
		$boxMetaType = $metaBoxModel->metaType();

		switch ($boxMetaType){
			case MetaTypes::CUSTOM_POST_TYPE:
				return ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE_META_BOX);

			case MetaTypes::TAXONOMY:
				return ACPT_DB::prefixedTableName(ACPT_DB::TABLE_TAXONOMY_META_BOX);

			case MetaTypes::OPTION_PAGE:
				return ACPT_DB::prefixedTableName(ACPT_DB::TABLE_OPTION_PAGE_META_BOX);

			case MetaTypes::USER:
				return ACPT_DB::prefixedTableName(ACPT_DB::TABLE_USER_META_BOX);
		}

		return null;
	}

	/**
	 * @param $oldBoxName
	 * @param $newBoxName
	 *
	 * @return array
	 */
	protected static function changedBoxNames($oldBoxName, $newBoxName)
	{
		return [
			'newKey' => Strings::toDBFormat($newBoxName).'_',
			'oldKey' => Strings::toDBFormat($oldBoxName).'_',
		];
	}
}