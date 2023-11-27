<?php

namespace ACPT\Core\Repository;

use ACPT\Core\Validators\ImportFileValidator;
use ACPT\Costants\MetaTypes;
use ACPT\Includes\ACPT_DB;

class ImportRepository
{
    /**
     * Import data from array $datum
     * from imported json file
     *
     * @param array $data
     *
     * @throws \Exception
     * @since    1.0.0
     */
    public static function import(array $data)
    {
    	if(!ImportFileValidator::validate($data)){
    		throw new \Exception('Data provided is invalid');
	    }

	    ACPT_DB::startTransaction();

	    try {
		    self::importCustomPostTypes($data[MetaTypes::CUSTOM_POST_TYPE]);
		    self::importTaxonomies($data[MetaTypes::TAXONOMY]);
		    self::importOptionPages($data[MetaTypes::OPTION_PAGE]);
		    self::importUserMeta($data[MetaTypes::USER]);
	    } catch (\Exception $exception){
		    ACPT_DB::rollbackTransaction();
		    throw new \Exception($exception->getMessage());
	    }

	    ACPT_DB::commitTransaction();
    }

	/**
	 * @param array $data
	 *
	 * @throws \Exception
	 */
	private static function importCustomPostTypes(array $data = [])
	{
		foreach ($data as $datum){

			$sql = "
	            INSERT INTO `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE)."` 
	            (
		            `id`,
		            `post_name` ,
		            `singular` ,
		            `plural`,
		            `icon`,
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
	                %s 
	            ) ON DUPLICATE KEY UPDATE 
	                `post_name` = %s,
	                `singular` = %s, 
	                `plural` = %s, 
	                `icon` = %s, 
	                `supports` = %s, 
	                `labels` = %s, 
	                `settings` = %s 
            ;";

			ACPT_DB::executeQueryOrThrowException($sql, [
				$datum['id'],
				$datum['name'],
				$datum['singular'],
				$datum['plural'],
				$datum['icon'],
				json_encode($datum['supports']),
				json_encode($datum['labels']),
				json_encode($datum['settings']),
				$datum['name'],
				$datum['singular'],
				$datum['plural'],
				$datum['icon'],
				json_encode($datum['supports']),
				json_encode($datum['labels']),
				json_encode($datum['settings']),
			]);

			foreach ($datum['taxonomies'] as $taxonomy) {
				$sql = "
	                INSERT INTO `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_TAXONOMY)."` 
	                (`id`,
	                `slug`,
	                `singular`,
	                `plural`,
	                `labels`,
	                `settings`
	                ) VALUES (
	                    %s,
	                    %s,
	                    %s,
	                    %s,
	                    %s,
	                    %s
	                ) ON DUPLICATE KEY UPDATE 
	                    `slug` = %s,
	                    `singular` = %s, 
	                    `plural` = %s, 
	                    `labels` = %s, 
	                    `settings` = %s 
	            ;";

				ACPT_DB::executeQueryOrThrowException($sql, [
					$taxonomy['id'],
					$taxonomy['slug'],
					$taxonomy['singular'],
					$taxonomy['plural'],
					json_encode($taxonomy['labels']),
					json_encode($taxonomy['settings']),
					$taxonomy['slug'],
					$taxonomy['singular'],
					$taxonomy['plural'],
					json_encode($taxonomy['labels']),
					json_encode($taxonomy['settings']),
				]);

				$sql = "
	                INSERT INTO
	                    `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_TAXONOMY_PIVOT)."`
	                    (
	                        `custom_post_type_id`, 
	                        `taxonomy_id` 
	                    ) VALUES (
	                        %s, 
	                        %s
	                    ) ON DUPLICATE KEY UPDATE 
	                        `custom_post_type_id` = %s,
	                        `taxonomy_id` = %s
	                ;";

				ACPT_DB::executeQueryOrThrowException($sql, [
					$datum['id'],
					$taxonomy['id'],
					$datum['id'],
					$taxonomy['id']
				]);
			}

			self::importMeta(MetaTypes::CUSTOM_POST_TYPE, $datum['meta']);
			self::importTemplates($datum['templates']);
		}
	}

	/**
	 * @param array $data
	 *
	 * @throws \Exception
	 */
	private static function importTaxonomies(array $data = [])
	{
		foreach ($data as $datum){

			$sql = "
	            INSERT INTO `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_TAXONOMY)."` 
	            (`id`,
	            `slug`,
	            `singular`,
	            `plural`,
	            `native`,
	            `labels`,
	            `settings`
	            ) VALUES (
	                %s,
	                %s,
	                %s,
	                %s,
	                %s,
	                %s,
	                %s
	            ) ON DUPLICATE KEY UPDATE 
	                `slug` = %s,
	                `singular` = %s,
	                `plural` = %s,
	                `native` = %s,
	                `labels` = %s,
	                `settings` = %s
	        ;";

			ACPT_DB::executeQueryOrThrowException($sql, [
				$datum['id'],
				$datum['slug'],
				$datum['singular'],
				$datum['plural'],
				$datum['isNative'],
				json_encode($datum['labels']),
				json_encode($datum['settings']),
				$datum['slug'],
				$datum['singular'],
				$datum['plural'],
				$datum['isNative'],
				json_encode($datum['labels']),
				json_encode($datum['settings']),
			]);

			self::importMeta(MetaTypes::TAXONOMY, $datum['meta']);
			self::importTemplates($datum['templates']);
		}
	}

	/**
	 * @param array $data
	 *
	 * @throws \Exception
	 */
	private static function importOptionPages(array $data = [])
	{
		foreach ($data as $datum){
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
	                %s,
	                %s
	            ) ON DUPLICATE KEY UPDATE 
	                `page_title` = %s,
		            `menu_title` = %s,
		            `capability` = %s,
		            `menu_slug` = %s,
		            `icon` = %s,
		            `description` = %s,
		            `parent_id` = %s,
		            `sort` = %s,
		            `page_position` = %s
	        ;";

			ACPT_DB::executeQueryOrThrowException($sql, [
				$datum['id'],
				$datum['pageTitle'],
				$datum['menuTitle'],
				$datum['capability'],
				$datum['menuSlug'],
				$datum['icon'],
				$datum['description'],
				$datum['parentId'],
				$datum['sort'],
				$datum['position'],
				$datum['pageTitle'],
				$datum['menuTitle'],
				$datum['capability'],
				$datum['menuSlug'],
				$datum['icon'],
				$datum['description'],
				$datum['parentId'],
				$datum['sort'],
				$datum['position'],
			]);

			self::importMeta(MetaTypes::OPTION_PAGE, $datum['meta']);
		}
	}

	/**
	 * @param array $meta
	 *
	 * @throws \Exception
	 */
	private static function importUserMeta(array $meta = [])
	{
		self::importMeta(MetaTypes::USER, $meta);
	}

	/**
	 * @param $belongsTo
	 * @param array $meta
	 *
	 * @throws \Exception
	 */
	private static function importMeta($belongsTo, array $meta = [])
	{
		foreach ($meta as $box) {

			switch ($belongsTo){
				case MetaTypes::CUSTOM_POST_TYPE:
					$sql = "
		                INSERT INTO `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE_META_BOX)."` 
		                (`id`,
		                `post_type` ,
		                `meta_box_name` ,
		                `sort`
		                ) VALUES (
		                    %s,
		                    %s,
		                    %s,
		                    %s
		                ) ON DUPLICATE KEY UPDATE 
		                    `post_type` = %s,
		                    `meta_box_name` = %s,
		                    `sort` = %s
		            ;";

					ACPT_DB::executeQueryOrThrowException($sql, [
						$box['id'],
						$box['postType'],
						$box['name'],
						$box['sort'],
						$box['postType'],
						$box['name'],
						$box['sort'],
					]);
					break;

				case MetaTypes::TAXONOMY:
					$sql = "
		                INSERT INTO `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_TAXONOMY_META_BOX)."` 
		                (`id`,
		                `taxonomy` ,
		                `meta_box_name` ,
		                `sort`
		                ) VALUES (
		                    %s,
		                    %s,
		                    %s,
		                    %s
		                ) ON DUPLICATE KEY UPDATE 
		                    `taxonomy` = %s,
		                    `meta_box_name` = %s,
		                    `sort` = %s
		            ;";

					ACPT_DB::executeQueryOrThrowException($sql, [
						$box['id'],
						$box['taxonomy'],
						$box['name'],
						$box['sort'],
						$box['taxonomy'],
						$box['name'],
						$box['sort'],
					]);
					break;

				case MetaTypes::OPTION_PAGE:
					$sql = "
		                INSERT INTO `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_OPTION_PAGE_META_BOX)."` 
		                (`id`,
		                `page` ,
		                `meta_box_name` ,
		                `sort`
		                ) VALUES (
		                    %s,
		                    %s,
		                    %s,
		                    %s
		                ) ON DUPLICATE KEY UPDATE 
		                    `page` = %s,
		                    `meta_box_name` = %s,
		                    `sort` = %s
		            ;";

					ACPT_DB::executeQueryOrThrowException($sql, [
						$box['id'],
						$box['optionPage'],
						$box['name'],
						$box['sort'],
						$box['optionPage'],
						$box['name'],
						$box['sort'],
					]);
					break;

				case MetaTypes::USER:
					$sql = "
		                INSERT INTO `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_USER_META_BOX)."` 
		                (`id`,
		                `meta_box_name` ,
		                `sort`
		                ) VALUES (
		                    %s,
		                    %s,
		                    %s
		                ) ON DUPLICATE KEY UPDATE 
		                    `meta_box_name` = %s,
		                    `sort` = %s
		            ;";

					ACPT_DB::executeQueryOrThrowException($sql, [
						$box['id'],
						$box['title'],
						$box['sort'],
						$box['title'],
						$box['sort'],
					]);
					break;
			}

			foreach ($box['fields'] as $field) {

				$showInArchive = (isset($field[ 'showInArchive' ]) and $field[ 'showInArchive' ]) ? '1' : '0';
				$isRequired = (isset($field[ 'required' ]) and $field[ 'required' ]) ? '1' : '0';

				$sql = "
                    INSERT INTO `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD)."` 
                    (`id`,
                    `meta_box_id` ,
                    `field_name` ,
                    `field_type` ,
                    `field_default_value` ,
                    `field_description` ,
                    `showInArchive` ,
                    `required` ,
                    `sort`
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
                        `meta_box_id` = %s,
                        `field_name` = %s,
                        `field_type` = %s,
                        `field_default_value` = %s,
                        `field_description` = %s,
                        `showInArchive` = %s,
                        `required` = %s,
                        `sort` = %s
                ;";

				ACPT_DB::executeQueryOrThrowException( $sql, [
					$field[ 'id' ],
					$box[ 'id' ],
					$field[ 'name' ],
					$field[ 'type' ],
					$field[ 'defaultValue' ],
					$field[ 'description' ],
					$showInArchive,
					$isRequired,
					$field[ 'sort'] ,
					$box[ 'id' ],
					$field[ 'name' ],
					$field[ 'type' ],
					$field[ 'defaultValue' ],
					$field[ 'description' ],
					$showInArchive,
					$isRequired,
					$field[ 'sort'] ,
				] );

				foreach ( $field[ 'options' ] as $option ) {
					$sql = "
                        INSERT INTO `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE_OPTION)."` 
                        (`id`,
                        `meta_box_id` ,
                        `meta_field_id` ,
                        `option_label` ,
                        `option_value` ,
                        `sort`
                        ) VALUES (
                            %s,
                            %s,
                            %s,
                            %s,
                            %s,
                            %s
                        ) ON DUPLICATE KEY UPDATE 
                            `meta_box_id` = %s,
                            `meta_field_id` = %s,
                            `option_label` = %s,
                            `option_value` = %s,
                            `sort` = %s 
                    ;";

					ACPT_DB::executeQueryOrThrowException( $sql, [
						$option[ 'id' ],
						$box[ 'id' ],
						$field[ 'id' ],
						$option[ 'label' ],
						$option[ 'value' ],
						$option[ 'sort'] ,
						$box[ 'id' ],
						$field[ 'id' ],
						$option[ 'label' ],
						$option[ 'value' ],
						$option[ 'sort'] ,
					] );
				}

				foreach ( $field['visibilityConditions'] as $visibilityCondition ) {
					$sql = "
                        INSERT INTO `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE_VISIBILITY)."` 
                            (`id`,
                            `meta_box_id` ,
                            `meta_field_id` ,
                            `visibility_type` ,
                            `operator` ,
                            `visibility_value`,
                            `logic`,
                            `sort`
                            ) VALUES (
                                %s,
                                %s,
                                %s,
                                %s,
                                %s,
                                %s,
                                %s,
                                %d
                            ) ON DUPLICATE KEY UPDATE 
                                `meta_box_id` = %s,
                                `meta_field_id` = %s,
                                `visibility_type` = %s,
                                `operator` = %s,
                                `visibility_value` = %s,
                                `logic` = %s,
                                `sort` = %d
                        ;";

					ACPT_DB::executeQueryOrThrowException($sql, [
						$visibilityCondition[ 'id' ],
						$box[ 'id' ],
						$field[ 'id' ],
						json_encode($visibilityCondition['type']),
						$visibilityCondition['operator'],
						$visibilityCondition['value'],
						$visibilityCondition['logic'],
						$visibilityCondition['sort'],
						$box[ 'id' ],
						$field[ 'id' ],
						json_encode($visibilityCondition['type']),
						$visibilityCondition['operator'],
						$visibilityCondition['value'],
						$visibilityCondition['logic'],
						$visibilityCondition['sort'],
					]);
				}

				if(isset($field['relations'])){
					foreach ( $field[ 'relations' ] as $relation ) {

						$a = ($relation['inversedBoxId'] !== null) ? $relation['inversedBoxId']  : 'NULL';
						$b = ($relation['inversedBoxName'] !== null) ? "'".$relation['inversedBoxName']."'"  : 'NULL';
						$c = ($relation['inversedFieldId'] !== null) ? $relation['inversedFieldId']  : 'NULL';
						$d = ($relation['inversedFieldName'] !== null) ? "'".$relation['inversedFieldName']."'"  : 'NULL';

						$sql = "
                    INSERT INTO `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE_RELATION)."` 
                        (`id`,
                        `meta_box_id` ,
                        `meta_field_id` ,
                        `relationship` ,
                        `related_post_type` ,
                        `inversed_meta_box_id` ,
                        `inversed_meta_box_name`,
                        `inversed_meta_field_id` ,
                        `inversed_meta_field_name`
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
                            `meta_box_id` = %s,
                            `meta_field_id` = %s,
                            `relationship` = %s,
                            `related_post_type` = %s,
                            `inversed_meta_box_id` = %s,
                            `inversed_meta_box_name` = %s,
                            `inversed_meta_field_id` = %s,
                            `inversed_meta_field_name` = %s
                    ;";

						ACPT_DB::executeQueryOrThrowException($sql, [
							$relation['id'],
							$relation['boxId'],
							$relation['fieldId'],
							$relation['type'],
							$relation['relatedPostType'],
							$a,
							$b,
							$c,
							$d,
							$relation['boxId'],
							$relation['fieldId'],
							$relation['type'],
							$relation['relatedPostType'],
							$a,
							$b,
							$c,
							$d
						]);
					}
				}
			}
		}
	}

	/**
	 * @param array $templates
	 *
	 * @throws \Exception
	 */
	private static function importTemplates(array $templates = [])
	{
		foreach ($templates as $template) {
			$sql = "
                INSERT INTO `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TEMPLATE)."` 
                (`id`,
                `belongs_to` ,
	            `template_type`,
	            `meta_field_id`,
	            `json`,
	            `html`,
	            `find`,
	            `meta`
                ) VALUES (
	                %s,
	                %s,
	                %s,
	                %s,
	                %s,
	                %s,
	                %s,
	                %s
	            ) ON DUPLICATE KEY UPDATE 
	                `belongs_to` = %s,
	                `template_type` = %s,
	                `meta_field_id` = %s,
	                `json` = %s,
	                `html` = %s,
	                `find` = %s,
	                `meta` = %s
            ;";

			ACPT_DB::executeQueryOrThrowException($sql, [
				$template['id'],
				$template['belongsTo'],
				$template['templateType'],
				isset($template['metaFieldId']) ? $template['metaFieldId'] : null,
				$template['json'],
				$template['html'],
				$template['find'],
				(isset($template['meta']) and !empty($template['meta'])) ? json_encode($template['meta']) : json_encode([]),
				$template['belongsTo'],
				$template['templateType'],
				isset($template['metaFieldId']) ? $template['metaFieldId'] : null,
				$template['json'],
				$template['html'],
				$template['find'],
				(isset($template['meta']) and !empty($template['meta'])) ? json_encode($template['meta']) : json_encode([]),
			]);
		}
	}
}