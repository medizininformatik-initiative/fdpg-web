<?php

namespace ACPT\Utils\MetaSync;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Costants\MetaTypes;
use ACPT\Includes\ACPT_DB;

class PostMetaSync extends AbstractMetaSync
{
    /**
     * @param AbstractMetaBoxModel $metaBoxModel
     * @throws \Exception
     */
    public static function syncBox(AbstractMetaBoxModel $metaBoxModel)
    {
        if($oldBox = self::getMetaBoxData($metaBoxModel)){
	        $boxNames = self::changedBoxNames($oldBox->meta_box_name, $metaBoxModel->getName());
	        self::updateBoxPostMeta($boxNames['newKey'], $boxNames['oldKey'], $oldBox->post_type);
        }
    }

	/**
	 * @param $newKey
	 * @param $oldKey
	 * @param $postType
	 *
	 * @throws \Exception
	 */
    private static function updateBoxPostMeta($newKey, $oldKey, $postType)
    {
	    global $wpdb;

	    if($newKey !== $oldKey){
		    $sql = "UPDATE `{$wpdb->prefix}postmeta` pm 
                    JOIN `{$wpdb->prefix}posts` p ON p.ID=pm.post_id
                    SET pm.meta_key=REPLACE(pm.meta_key, %s, %s) 
                    WHERE pm.meta_key LIKE %s AND p.post_type=%s";
		    ACPT_DB::executeQueryOrThrowException($sql, [$oldKey, $newKey, $oldKey.'%', $postType]);
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
		} elseif($fieldModel->getBlockId() !== null){
			self::updateNestedPostMetaInABlockWhenFieldNameChanges($fieldModel);
		} else {
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
				'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
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

				self::updateNestedPostMetaData($parentKey, $oldKey, $newKey, $postType);
			}
		}
	}

	/**
     * @param AbstractMetaBoxFieldModel $fieldModel
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
	            $postType = $oldField->post_type;
	            $oldKey = Strings::toDBFormat($fieldName);

	            // new key
	            $newKey = Strings::toDBFormat($fieldModel->getName());

            	self::updateNestedPostMetaData($parentKey, $oldKey, $newKey, $postType);
            }
        }
    }

	/**
	 * @param $parentKey
	 * @param $oldKey
	 * @param $newKey
	 * @param $postType
	 *
	 * @throws \Exception
	 */
    private static function updateNestedPostMetaData($parentKey, $oldKey, $newKey, $postType)
    {
	    global $wpdb;

	    $query = "
                    SELECT * FROM `{$wpdb->prefix}postmeta` pm 
                    JOIN `{$wpdb->prefix}posts` p ON p.ID=pm.post_id
                    WHERE pm.meta_key=%s AND p.post_type=%s
                ";
	    $results = ACPT_DB::getResults($query, [$parentKey, $postType]);

	    foreach ($results as $result) {

		    $oldMetaValue = unserialize( $result->meta_value );

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
                            UPDATE `{$wpdb->prefix}postmeta` pm 
                            JOIN `{$wpdb->prefix}posts` p ON p.ID=pm.post_id
                            SET pm.meta_value=%s 
                            WHERE pm.meta_key=%s AND p.post_type=%s
                        ";
			    ACPT_DB::executeQueryOrThrowException($sql, [$metaValue, $parentKey, $postType]);
		    }
	    }
    }

    /**
     * @param AbstractMetaBoxFieldModel $fieldModel
     * @throws \Exception
     */
	private  static function updatePostMetaWhenFieldNameChanges(AbstractMetaBoxFieldModel $fieldModel)
    {
        if($oldField = self::getMetaFieldData($fieldModel)){

            // old key
            $boxName = $oldField->meta_box_name;
            $postType = $oldField->post_type;
            $fieldName = $oldField->field_name;
            $oldKey = Strings::toDBFormat($boxName).'_'.Strings::toDBFormat($fieldName);

            // new key
            $newKey = $fieldModel->getDbName();

            self::updateFieldPostMeta($newKey, $oldKey, $postType);
        }
    }

    /**
     * @param $newKey
     * @param $oldKey
     * @param $postType
     *
     * @throws \Exception
     */
    private static function updateFieldPostMeta($newKey, $oldKey, $postType)
    {
        global $wpdb;

        if($newKey !== $oldKey){
            $sql = "
                UPDATE `{$wpdb->prefix}postmeta` pm 
                JOIN `{$wpdb->prefix}posts` p ON p.ID=pm.post_id
                SET pm.meta_key=%s 
                WHERE pm.meta_key=%s AND p.post_type=%s
            ";

            ACPT_DB::executeQueryOrThrowException($sql, [$newKey, $oldKey, $postType]);

            $sql = "
                UPDATE `{$wpdb->prefix}postmeta` pm 
                JOIN `{$wpdb->prefix}posts` p ON p.ID=pm.post_id
                SET meta_key=REPLACE(pm.meta_key, %s, %s) 
                WHERE pm.meta_key LIKE %s AND p.post_type=%s
            ";
            ACPT_DB::executeQueryOrThrowException($sql, [$oldKey.'_', $newKey.'_', $oldKey.'_%', $postType]);
        }
    }
}