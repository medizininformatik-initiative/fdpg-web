<?php

use ACPT\Core\Helper\Strings;
use ACPT\Core\Helper\Uuid;
use ACPT\Core\Models\MetaField\MetaBoxFieldAdvancedOptionModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldBlockModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldOptionModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldVisibilityModel;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Core\Repository\OptionPageRepository;
use ACPT\Core\Validators\ArgumentsArrayValidator;
use ACPT\Core\Validators\VisibilityConditionValidator;
use ACPT\Costants\MetaTypes;

if( !function_exists('add_acpt_option_page_meta_box'))
{
	/**
	 * @param string $option_page
	 * @param string $box_name
	 * @param null $box_label
	 *
	 * @return bool
	 */
	function add_acpt_option_page_meta_box($option_page, $box_name, $box_label = null)
	{
		try {
			if(!OptionPageRepository::exists($option_page)){
				return false;
			}

			$all_meta = OptionPageRepository::get([
				'belongsTo' => MetaTypes::OPTION_PAGE,
				'find' => $option_page
			]);

			if(MetaRepository::existsMetaBox([
				'belongsTo' => MetaTypes::OPTION_PAGE,
				'find' => $option_page,
				'boxName' => $box_name,
			])){
				return add_acpt_option_page_meta_box($option_page, Strings::getUniqueName($box_name), $box_label);
			} else {
				$meta_box_model = OptionPageMetaBoxModel::hydrateFromArray([
					'id' => Uuid::v4(),
					'optionPage' => $option_page,
					'name' => $box_name,
					'sort' => count($all_meta)+1
				]);

				if($box_label !== null){
					$meta_box_model->changeLabel($box_label);
				}
			}

			MetaRepository::saveMetaBox($meta_box_model);

			return true;

		} catch (\Exception $exception){
			return false;
		}
	}
}

if( !function_exists('add_acpt_option_page_meta_field'))
{
	/**
	 * @param array $args
	 *
	 * @return bool
	 */
	function add_acpt_option_page_meta_field($args = [])
	{
		try {
			// validate array
			$mandatory_keys = [
				'option_page' => [
					'required' => true,
					'type' => 'string',
				],
				'box_name' => [
					'required' => true,
					'type' => 'string',
				],
				'field_name' => [
					'required' => true,
					'type' => 'string',
				],
				'field_type' => [
					'required' => true,
					'type' => 'string',
					'enum' => [
						OptionPageMetaBoxFieldModel::ADDRESS_TYPE,
						OptionPageMetaBoxFieldModel::COLOR_TYPE,
						OptionPageMetaBoxFieldModel::CURRENCY_TYPE,
						OptionPageMetaBoxFieldModel::CHECKBOX_TYPE,
						OptionPageMetaBoxFieldModel::DATE_TYPE,
						OptionPageMetaBoxFieldModel::DATE_RANGE_TYPE,
						OptionPageMetaBoxFieldModel::EDITOR_TYPE,
						OptionPageMetaBoxFieldModel::EMAIL_TYPE,
						OptionPageMetaBoxFieldModel::EMBED_TYPE,
						OptionPageMetaBoxFieldModel::FILE_TYPE,
						OptionPageMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE,
						OptionPageMetaBoxFieldModel::HTML_TYPE,
						OptionPageMetaBoxFieldModel::GALLERY_TYPE,
						OptionPageMetaBoxFieldModel::IMAGE_TYPE,
						OptionPageMetaBoxFieldModel::LENGTH_TYPE,
						OptionPageMetaBoxFieldModel::LIST_TYPE,
						OptionPageMetaBoxFieldModel::NUMBER_TYPE,
						OptionPageMetaBoxFieldModel::PHONE_TYPE,
						OptionPageMetaBoxFieldModel::RADIO_TYPE,
						OptionPageMetaBoxFieldModel::REPEATER_TYPE,
						OptionPageMetaBoxFieldModel::SELECT_TYPE,
						OptionPageMetaBoxFieldModel::SELECT_MULTI_TYPE,
						OptionPageMetaBoxFieldModel::TEXT_TYPE,
						OptionPageMetaBoxFieldModel::TEXTAREA_TYPE,
						OptionPageMetaBoxFieldModel::TIME_TYPE,
						OptionPageMetaBoxFieldModel::TOGGLE_TYPE,
						OptionPageMetaBoxFieldModel::VIDEO_TYPE,
						OptionPageMetaBoxFieldModel::WEIGHT_TYPE,
						OptionPageMetaBoxFieldModel::URL_TYPE,
					],
				],
				'required' => [
					'required' => true,
					'type' => 'boolean',
				],
				'default_value' => [
					'required' => false,
					'type' => 'integer|string',
				],
				'description' => [
					'required' => false,
					'type' => 'string',
				],
				'advanced_options' => [
					'required' => false,
					'type' => 'array',
				],
				'options' => [
					'required' => false,
					'type' => 'array',
				],
				'children' => [
					'required' => false,
					'type' => 'array',
				],
				'visibility_conditions' => [
					'required' => false,
					'type' => 'array',
				],
				'blocks' => [
					'required' => false,
					'type' => 'array',
				],
			];

			$validator = new ArgumentsArrayValidator();

			if(!$validator->validate($mandatory_keys, $args)){
				return false;
			}

			if(!OptionPageRepository::exists($args['option_page'])){
				return false;
			}

			if(!MetaRepository::existsMetaBox([
				'belongsTo' => MetaTypes::OPTION_PAGE,
				'find' => $args['option_page'],
				'boxName' => $args['box_name'],
			])){
				add_acpt_option_page_meta_box($args['option_page'], $args['box_name']);
			}

			if(MetaRepository::existsMetaBoxField([
				'belongsTo' => MetaTypes::OPTION_PAGE,
				'find' =>$args['option_page'],
				'boxName' =>$args['box_name'],
				'fieldName' => $args['field_name']
			])){

				$args['field_name'] = Strings::getUniqueName($args['field_name']);

				return add_acpt_option_page_meta_field($args);

			} else {

				$box_model = MetaRepository::get([
					'belongsTo' => MetaTypes::OPTION_PAGE,
					'find' => $args['option_page'],
					'boxName' => $args['box_name']
				])[0];

				$meta_field_model = OptionPageMetaBoxFieldModel::hydrateFromArray([
					'id' => Uuid::v4(),
					'metaBox' => $box_model,
					'name' => $args['field_name'],
					'type' => $args['field_type'],
					'required' => $args['required'],
					'defaultValue' => isset($args['default_value']) ? $args['default_value'] : null,
					'description' => isset($args['description']) ? $args['description'] : null,
					'sort' => count($box_model->getFields())+1
				]);

				// advanced_options
				if(isset($args['advanced_options'])){
					foreach ($args['advanced_options'] as $index => $option){

						// validate option
						$mandatory_keys = [
							'key' => [
								'required' => true,
								'type' => 'string',
							],
							'value' => [
								'required' => true,
								'type' => 'integer|string',
							],
						];

						$validator = new ArgumentsArrayValidator();

						if(!$validator->validate($mandatory_keys, $option)){
							return false;
						}

						$meta_field_advanced_option_model = MetaBoxFieldAdvancedOptionModel::hydrateFromArray([
							'id' => Uuid::v4(),
							'metaBoxField' => $meta_field_model,
							'key' => $option['key'],
							'value' => $option['value'],
						]);

						$meta_field_model->addAdvancedOption($meta_field_advanced_option_model);
					}
				}

				// options
				if(isset($args['options'])){
					foreach ($args['options'] as $index => $option){

						// validate option
						$mandatory_keys = [
							'label' => [
								'required' => true,
								'type' => 'string',
							],
							'value' => [
								'required' => true,
								'type' => 'integer|string',
							],
						];

						$validator = new ArgumentsArrayValidator();

						if(!$validator->validate($mandatory_keys, $option)){
							return false;
						}

						$meta_field_option_model = MetaBoxFieldOptionModel::hydrateFromArray([
							'id' => Uuid::v4(),
							'metaBoxField' => $meta_field_model,
							'label' => $option['label'],
							'value' => $option['value'],
							'sort' => $index+1
						]);

						$meta_field_model->addOption($meta_field_option_model);
					}
				}

				// visibility_conditions
				if(isset($args['visibility_conditions'])) {

					// data morphing
					$visibility_conditions = [];

					foreach ( $args[ 'visibility_conditions' ] as $condition ) {
						// validate option
						$mandatory_keys = [
							'type' => [
								'required' => true,
								'type' => 'string',
								'enum' => [
									'value',
									'post_id',
									'taxonomy',
									'other_fields',
								],
							],
							'operator' => [
								'required' => false,
								'type' => 'string',
								'enum' => [
									'=',
									'!=',
									'<',
									'>',
									'<=',
									'>=',
									'in',
									'not_in',
									'like',
									'not_like',
									'blank',
									'not_blank',
									'checked',
									'not_checked',
								],
							],
							'value' => [
								'required' => true,
								'type' => 'array|string|integer|object',
							],
							'meta_field' => [
								'required' => false,
								'type' => 'string',
							],
							'tax_name' => [
								'required' => false,
								'type' => 'string',
							],
							'logic' => [
								'required' => false,
								'type' => 'string',
								'enum' => [
									'and',
									'or',
								],
							],
						];

						$validator = new ArgumentsArrayValidator();

						if(!$validator->validate($mandatory_keys, $condition)){
							return false;
						}

						if($condition['type'] === 'other_fields'){

							$related_field_model = MetaRepository::getMetaFieldByName([
								'belongsTo' => MetaTypes::OPTION_PAGE,
								'find' => $args['option_page'],
								'boxName' => $args['box_name'],
								'fieldName' => $condition['meta_field']
							]);

							if($related_field_model === null){
								throw new \Exception('Unknown related field');
							}

							$condition['type'] = [
								'type' => strtoupper($condition['type']),
								'value' => $related_field_model->getId(),
							];

						} elseif($condition['type'] === 'taxonomy'){

							if(!isset($condition['tax_name'])){
								throw new \Exception('`tax_name` needed');
							}

							$condition['type'] = [
								'type' => strtoupper($condition['type']),
								'value' => $condition['tax_name'],
							];

						} else {
							$condition['type'] = [
								'type' => strtoupper($condition['type']),
								'value' => $condition['type'],
							];
						}

						if($condition['value'] instanceof \WP_Post){
							$condition['value'] = $condition['value']->ID;
						}

						if($condition['value'] instanceof \WP_Term){
							$condition['value'] = $condition['value']->term_id;
						}

						$condition['operator'] = isset($condition['operator']) ? strtoupper($condition['operator']) : '=';

						$visibility_conditions[] = $condition;
					}

					VisibilityConditionValidator::validate($meta_field_model, $visibility_conditions);

					foreach ( $visibility_conditions as $index => $condition ) {

						$visibility_condition_model = MetaBoxFieldVisibilityModel::hydrateFromArray([
							'id' => Uuid::v4(),
							'type' => $condition['type'],
							'value' => $condition['value'],
							'operator' => $condition['operator'],
							'logic' => (isset($condition['logic'])) ? strtoupper($condition['logic']) : null,
							'sort' => ($index+1),
							'metaBoxField' => $meta_field_model
						]);

						$meta_field_model->addVisibilityCondition($visibility_condition_model);
					}
				}

				// blocks
				if(isset($args['blocks']) and $meta_field_model->getType() === OptionPageMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE){
					foreach ( $args[ 'blocks' ] as $index => $block ) {

						// validate array
						$mandatory_keys = [
							'block_name' => [
								'required' => true,
								'type' => 'string',
							],
							'block_label' => [
								'required' => false,
								'type' => 'string',
							],
							'fields' => [
								'required' => false,
								'type' => 'array',
							],
						];

						$validator = new ArgumentsArrayValidator();

						if(!$validator->validate($mandatory_keys, $block)){
							return false;
						}

						$block_field_model = MetaBoxFieldBlockModel::hydrateFromArray([
							'id' => Uuid::v4(),
							'metaBoxField' => $meta_field_model,
							'name' => $block['block_name'],
							'label' => isset($block['block_label']) ? $block['block_label'] : null,
							'sort' => $index+1
						]);

						// loop all fields
						foreach ($block['fields'] as $nested_field){

							$mandatory_keys = [
								'field_name' => [
									'required' => true,
									'type' => 'string',
								],
								'field_type' => [
									'required' => true,
									'type' => 'string',
									'enum' => [
										OptionPageMetaBoxFieldModel::ADDRESS_TYPE,
										OptionPageMetaBoxFieldModel::COLOR_TYPE,
										OptionPageMetaBoxFieldModel::CURRENCY_TYPE,
										OptionPageMetaBoxFieldModel::CHECKBOX_TYPE,
										OptionPageMetaBoxFieldModel::DATE_TYPE,
										OptionPageMetaBoxFieldModel::DATE_RANGE_TYPE,
										OptionPageMetaBoxFieldModel::EDITOR_TYPE,
										OptionPageMetaBoxFieldModel::EMAIL_TYPE,
										OptionPageMetaBoxFieldModel::EMBED_TYPE,
										OptionPageMetaBoxFieldModel::FILE_TYPE,
										OptionPageMetaBoxFieldModel::HTML_TYPE,
										OptionPageMetaBoxFieldModel::GALLERY_TYPE,
										OptionPageMetaBoxFieldModel::IMAGE_TYPE,
										OptionPageMetaBoxFieldModel::LENGTH_TYPE,
										OptionPageMetaBoxFieldModel::LIST_TYPE,
										OptionPageMetaBoxFieldModel::NUMBER_TYPE,
										OptionPageMetaBoxFieldModel::POST_TYPE,
										OptionPageMetaBoxFieldModel::PHONE_TYPE,
										OptionPageMetaBoxFieldModel::RADIO_TYPE,
										OptionPageMetaBoxFieldModel::SELECT_TYPE,
										OptionPageMetaBoxFieldModel::SELECT_MULTI_TYPE,
										OptionPageMetaBoxFieldModel::TEXT_TYPE,
										OptionPageMetaBoxFieldModel::TEXTAREA_TYPE,
										OptionPageMetaBoxFieldModel::TIME_TYPE,
										OptionPageMetaBoxFieldModel::TOGGLE_TYPE,
										OptionPageMetaBoxFieldModel::VIDEO_TYPE,
										OptionPageMetaBoxFieldModel::WEIGHT_TYPE,
										OptionPageMetaBoxFieldModel::URL_TYPE,
									],
								],
								'required' => [
									'required' => true,
									'type' => 'boolean',
								],
								'default_value' => [
									'required' => false,
									'type' => 'integer|string',
								],
								'description' => [
									'required' => false,
									'type' => 'string',
								],
								'advanced_options' => [
									'required' => false,
									'type' => 'array',
								],
								'options' => [
									'required' => false,
									'type' => 'array',
								],
								'visibility_conditions' => [
									'required' => false,
									'type' => 'array',
								],
							];

							$validator = new ArgumentsArrayValidator();

							if(!$validator->validate($mandatory_keys, $nested_field)){
								return false;
							}

							$meta_nested_field_model = OptionPageMetaBoxFieldModel::hydrateFromArray([
								'id' => Uuid::v4(),
								'metaBox' => $box_model,
								'name' => $nested_field['field_name'],
								'type' => $nested_field['field_type'],
								'required' => $nested_field['required'],
								'defaultValue' => isset($nested_field['default_value']) ? $nested_field['default_value'] : null,
								'description' => isset($nested_field['description']) ? $args['description'] : null,
								'sort' => count($block_field_model->getFields())+1
							]);

							// advanced_options
							if(isset($nested_field['advanced_options'])){
								foreach ($nested_field['advanced_options'] as $index => $option){

									// validate option
									$mandatory_keys = [
										'key' => [
											'required' => true,
											'type' => 'string',
										],
										'value' => [
											'required' => true,
											'type' => 'integer|string',
										],
									];

									$validator = new ArgumentsArrayValidator();

									if(!$validator->validate($mandatory_keys, $option)){
										return false;
									}

									$meta_field_advanced_option_model = MetaBoxFieldAdvancedOptionModel::hydrateFromArray([
										'id' => Uuid::v4(),
										'metaBoxField' => $meta_field_model,
										'key' => $option['key'],
										'value' => $option['value'],
									]);

									$meta_field_model->addAdvancedOption($meta_field_advanced_option_model);
								}
							}

							// options
							if(isset($nested_field['options'])){
								foreach ($nested_field['options'] as $option_index => $option){

									// validate option
									$mandatory_keys = [
										'label' => [
											'required' => true,
											'type' => 'string',
										],
										'value' => [
											'required' => true,
											'type' => 'integer|string',
										],
									];

									$validator = new ArgumentsArrayValidator();

									if(!$validator->validate($mandatory_keys, $option)){
										return false;
									}

									$meta_field_option_model = MetaBoxFieldOptionModel::hydrateFromArray([
										'id' => Uuid::v4(),
										'metaBoxField' => $meta_nested_field_model,
										'label' => $option['label'],
										'value' => $option['value'],
										'sort' => $option_index+1
									]);

									$meta_nested_field_model->addOption($meta_field_option_model);
								}
							}

							// visibility_conditions
							if(isset($nested_field['visibility_conditions'])) {

								// data morphing
								$visibility_conditions = [];

								foreach ( $nested_field[ 'visibility_conditions' ] as $condition ) {
									// validate option
									$mandatory_keys = [
										'type' => [
											'required' => true,
											'type' => 'string',
											'enum' => [
												'value',
												'post_id',
												'taxonomy',
												'other_fields',
											],
										],
										'operator' => [
											'required' => false,
											'type' => 'string',
											'enum' => [
												'=',
												'!=',
												'<',
												'>',
												'<=',
												'>=',
												'in',
												'not_in',
												'like',
												'not_like',
												'blank',
												'not_blank',
												'checked',
												'not_checked',
											],
										],
										'value' => [
											'required' => true,
											'type' => 'array|string|integer|object',
										],
										'meta_field' => [
											'required' => false,
											'type' => 'string',
										],
										'tax_name' => [
											'required' => false,
											'type' => 'string',
										],
										'logic' => [
											'required' => false,
											'type' => 'string',
											'enum' => [
												'and',
												'or',
											],
										],
									];

									$validator = new ArgumentsArrayValidator();

									if(!$validator->validate($mandatory_keys, $condition)){
										return false;
									}

									if($condition['type'] === 'other_fields'){

										$related_field_model = MetaRepository::getMetaFieldByName([
											'belongsTo' => MetaTypes::OPTION_PAGE,
											'find' => $args['post_type'],
											'boxName' => $args['box_name'],
											'fieldName' => $condition['meta_field']
										]);

										if($related_field_model === null){
											throw new \Exception('Unknown related field');
										}

										$condition['type'] = [
											'type' => strtoupper($condition['type']),
											'value' => $related_field_model->getId(),
										];

									} elseif($condition['type'] === 'taxonomy'){

										if(!isset($condition['tax_name'])){
											throw new \Exception('`tax_name` needed');
										}

										$condition['type'] = [
											'type' => strtoupper($condition['type']),
											'value' => $condition['tax_name'],
										];

									} else {
										$condition['type'] = [
											'type' => strtoupper($condition['type']),
											'value' => $condition['type'],
										];
									}

									if($condition['value'] instanceof \WP_Post){
										$condition['value'] = $condition['value']->ID;
									}

									if($condition['value'] instanceof \WP_Term){
										$condition['value'] = $condition['value']->term_id;
									}

									$condition['operator'] = isset($condition['operator']) ? strtoupper($condition['operator']) : '=';

									$visibility_conditions[] = $condition;
								}

								VisibilityConditionValidator::validate($meta_field_model, $visibility_conditions);

								foreach ( $visibility_conditions as $index => $condition ) {

									$visibility_condition_model = MetaBoxFieldVisibilityModel::hydrateFromArray([
										'id' => Uuid::v4(),
										'type' => $condition['type'],
										'value' => $condition['value'],
										'operator' => $condition['operator'],
										'logic' => (isset($condition['logic'])) ? strtoupper($condition['logic']) : null,
										'sort' => ($index+1),
										'metaBoxField' => $meta_field_model
									]);

									$meta_nested_field_model->addVisibilityCondition($visibility_condition_model);
								}
							}

							$meta_nested_field_model->setBlockId($block_field_model->getId());
							$block_field_model->addField($meta_nested_field_model);
						}

						$meta_field_model->addBlock($block_field_model);
					}
				}
				
				// children
				if(isset($args['children'])) {
					foreach ( $args[ 'children' ] as $index => $child ) {

						// validate array
						$mandatory_keys = [
							'field_name' => [
								'required' => true,
								'type' => 'string',
							],
							'field_type' => [
								'required' => true,
								'type' => 'string',
								'enum' => [
									OptionPageMetaBoxFieldModel::ADDRESS_TYPE,
									OptionPageMetaBoxFieldModel::COLOR_TYPE,
									OptionPageMetaBoxFieldModel::CURRENCY_TYPE,
									OptionPageMetaBoxFieldModel::DATE_TYPE,
									OptionPageMetaBoxFieldModel::EDITOR_TYPE,
									OptionPageMetaBoxFieldModel::EMAIL_TYPE,
									OptionPageMetaBoxFieldModel::EMBED_TYPE,
									OptionPageMetaBoxFieldModel::FILE_TYPE,
									OptionPageMetaBoxFieldModel::HTML_TYPE,
									OptionPageMetaBoxFieldModel::GALLERY_TYPE,
									OptionPageMetaBoxFieldModel::IMAGE_TYPE,
									OptionPageMetaBoxFieldModel::LENGTH_TYPE,
									OptionPageMetaBoxFieldModel::LIST_TYPE,
									OptionPageMetaBoxFieldModel::NUMBER_TYPE,
									OptionPageMetaBoxFieldModel::PHONE_TYPE,
									OptionPageMetaBoxFieldModel::REPEATER_TYPE,
									OptionPageMetaBoxFieldModel::SELECT_TYPE,
									OptionPageMetaBoxFieldModel::SELECT_MULTI_TYPE,
									OptionPageMetaBoxFieldModel::TEXT_TYPE,
									OptionPageMetaBoxFieldModel::TEXTAREA_TYPE,
									OptionPageMetaBoxFieldModel::TIME_TYPE,
									OptionPageMetaBoxFieldModel::TOGGLE_TYPE,
									OptionPageMetaBoxFieldModel::VIDEO_TYPE,
									OptionPageMetaBoxFieldModel::WEIGHT_TYPE,
									OptionPageMetaBoxFieldModel::URL_TYPE,
								],
							],
							'required' => [
								'required' => true,
								'type' => 'boolean',
							],
							'default_value' => [
								'required' => false,
								'type' => 'integer|string',
							],
							'description' => [
								'required' => false,
								'type' => 'string',
							],
							'options' => [
								'required' => false,
								'type' => 'array',
							],
						];

						$validator = new ArgumentsArrayValidator();

						if(!$validator->validate($mandatory_keys, $child)){
							return false;
						}

						$child_field_model = OptionPageMetaBoxFieldModel::hydrateFromArray([
							'id' => Uuid::v4(),
							'metaBox' => $box_model,
							'name' => $child['field_name'],
							'type' => $child['field_type'],
							'required' => $child['required'],
							'defaultValue' => isset($child['default_value']) ? $child['default_value'] : null,
							'description' => isset($child['description']) ? $child['description'] : null,
							'sort' => $index+1
						]);

						// options
						if(isset($child['options'])){
							foreach ($child['options'] as $option_index => $option){

								// validate option
								$mandatory_keys = [
									'label' => [
										'required' => true,
										'type' => 'string',
									],
									'value' => [
										'required' => true,
										'type' => 'integer|string',
									],
								];

								$validator = new ArgumentsArrayValidator();

								if(!$validator->validate($mandatory_keys, $option)){
									return false;
								}

								$meta_field_option_model = MetaBoxFieldOptionModel::hydrateFromArray([
									'id' => Uuid::v4(),
									'metaBoxField' => $child_field_model,
									'label' => $option['label'],
									'value' => $option['value'],
									'sort' => $option_index+1
								]);

								$child_field_model->addOption($meta_field_option_model);
							}
						}

						$child_field_model->setParentId($meta_field_model->getId());
						$meta_field_model->addChild($child_field_model);
					}

					MetaRepository::saveMetaBoxField($meta_field_model);
				}

				MetaRepository::saveMetaBoxField($meta_field_model);
			}

			return true;
		} catch (\Exception $exception){
			return false;
		}
	}
}

if( !function_exists('delete_acpt_option_page_meta') )
{
	/**
	 * @param $option_page
	 *
	 * @return bool
	 */
	function delete_acpt_option_page_meta($option_page)
	{
		try {
			MetaRepository::deleteAll([
				'belongsTo' => MetaTypes::OPTION_PAGE,
				'find' => $option_page
			]);

			return true;

		} catch (\Exception $exception){
			return false;
		}
	}
}

if( !function_exists('delete_acpt_option_page_meta_box'))
{
	/**
	 * @param $option_page
	 * @param $box_name
	 *
	 * @return bool
	 */
	function delete_acpt_option_page_meta_box($option_page, $box_name)
	{
		try {
			$meta = MetaRepository::get([
				'belongsTo' => MetaTypes::OPTION_PAGE,
				'find' => $option_page,
				'boxName' => $box_name,
			]);

			if(!isset($meta[0])){
				return false;
			}

			MetaRepository::deleteMetaBox([
				'belongsTo' => MetaTypes::OPTION_PAGE,
				'find' => $option_page,
				'metaBox' => $meta[0],
			]);

			return true;

		} catch (\Exception $exception){
			return false;
		}
	}
}

if( !function_exists('delete_acpt_option_page_meta_field'))
{
	/**
	 * @param $option_page
	 * @param $box_name
	 * @param $field_name
	 *
	 * @return bool
	 */
	function delete_acpt_option_page_meta_field($option_page, $box_name, $field_name)
	{
		try {

			$meta_field = MetaRepository::getMetaFieldByName([
				'belongsTo' => MetaTypes::OPTION_PAGE,
				'find' => $option_page,
				'boxName' => $box_name,
				'fieldName' => $field_name,
			]);

			if($meta_field === null){
				return false;
			}

			MetaRepository::deleteMetaField([
				'belongsTo' => MetaTypes::OPTION_PAGE,
				'find' => $option_page,
				'metaBoxField' => $meta_field,
			]);

			return true;

		} catch (\Exception $exception){
			return false;
		}
	}
}

if( !function_exists('edit_acpt_option_page_meta_box'))
{
	/**
	 * @param $option_page
	 * @param $old_box_name
	 * @param $new_box_name
	 *
	 * @return bool
	 */
	function edit_acpt_option_page_meta_box($option_page, $old_box_name, $new_box_name)
	{
		try {

			if(!OptionPageRepository::exists($option_page)){
				return false;
			}

			$meta_box_model = MetaRepository::getMetaBoxByName([
				'belongsTo' => MetaTypes::OPTION_PAGE,
				'find' => $option_page,
				'boxName' => $old_box_name,
			]);

			if($meta_box_model === null){
				return  false;
			}

			$new_meta_box_model = OptionPageMetaBoxModel::hydrateFromArray([
				'id' => $meta_box_model->getId(),
				'optionPage' => $option_page,
				'name' => $new_box_name,
				'sort' => $meta_box_model->getSort()
			]);

			MetaRepository::saveMetaBox($new_meta_box_model);

			return true;

		} catch (\Exception $exception){
			return false;
		}
	}
}

if( !function_exists('edit_acpt_option_page_meta_field'))
{
	/**
	 * @param array $args
	 *
	 * @return bool
	 */
	function edit_acpt_option_page_meta_field($args = [])
	{
		try {
			// validate array
			$mandatory_keys = [
				'option_page' => [
					'required' => true,
					'type' => 'string',
				],
				'box_name' => [
					'required' => true,
					'type' => 'string',
				],
				'field_name' => [
					'required' => true,
					'type' => 'string',
				],
				'old_field_name' => [
					'required' => true,
					'type' => 'string',
				],
				'field_type' => [
					'required' => true,
					'type' => 'string',
					'enum' => [
						OptionPageMetaBoxFieldModel::ADDRESS_TYPE,
						OptionPageMetaBoxFieldModel::COLOR_TYPE,
						OptionPageMetaBoxFieldModel::CURRENCY_TYPE,
						OptionPageMetaBoxFieldModel::DATE_TYPE,
						OptionPageMetaBoxFieldModel::EDITOR_TYPE,
						OptionPageMetaBoxFieldModel::EMAIL_TYPE,
						OptionPageMetaBoxFieldModel::EMBED_TYPE,
						OptionPageMetaBoxFieldModel::FILE_TYPE,
						OptionPageMetaBoxFieldModel::HTML_TYPE,
						OptionPageMetaBoxFieldModel::GALLERY_TYPE,
						OptionPageMetaBoxFieldModel::IMAGE_TYPE,
						OptionPageMetaBoxFieldModel::LENGTH_TYPE,
						OptionPageMetaBoxFieldModel::LIST_TYPE,
						OptionPageMetaBoxFieldModel::NUMBER_TYPE,
						OptionPageMetaBoxFieldModel::PHONE_TYPE,
						OptionPageMetaBoxFieldModel::SELECT_TYPE,
						OptionPageMetaBoxFieldModel::SELECT_MULTI_TYPE,
						OptionPageMetaBoxFieldModel::TEXT_TYPE,
						OptionPageMetaBoxFieldModel::TEXTAREA_TYPE,
						OptionPageMetaBoxFieldModel::TIME_TYPE,
						OptionPageMetaBoxFieldModel::TOGGLE_TYPE,
						OptionPageMetaBoxFieldModel::VIDEO_TYPE,
						OptionPageMetaBoxFieldModel::WEIGHT_TYPE,
						OptionPageMetaBoxFieldModel::URL_TYPE,
					],
				],
				'required' => [
					'required' => true,
					'type' => 'boolean',
				],
				'default_value' => [
					'required' => false,
					'type' => 'integer|string',
				],
				'description' => [
					'required' => false,
					'type' => 'string',
				],
				'options' => [
					'required' => false,
					'type' => 'array',
				],
				'children' => [
					'required' => false,
					'type' => 'array',
				],
				'visibility_conditions' => [
					'required' => false,
					'type' => 'array',
				],
				'advanced_options' => [
					'required' => false,
					'type' => 'array',
				],
			];

			$validator = new ArgumentsArrayValidator();

			if(!$validator->validate($mandatory_keys, $args)){
				return false;
			}

			if(!OptionPageRepository::exists($args['option_page'])){
				return false;
			}

			if(!MetaRepository::existsMetaBoxField([
				'belongsTo' => MetaTypes::OPTION_PAGE,
				'find' => $args['option_page'],
				'boxName' => $args['box_name'],
				'fieldName' => $args['old_field_name']
			])){
				return false;
			}

			$saved_meta_field_model = MetaRepository::getMetaFieldByName([
				'belongsTo' => MetaTypes::OPTION_PAGE,
				'find' => $args['option_page'],
				'boxName' => $args['box_name'],
				'fieldName' => $args['old_field_name']
			]);

			$meta_field_model = OptionPageMetaBoxFieldModel::hydrateFromArray([
				'id' => $saved_meta_field_model->getId(),
				'metaBox' => $saved_meta_field_model->getMetaBox(),
				'name' => $args['field_name'],
				'type' => $args['field_type'],
				'required' => $args['required'],
				'defaultValue' => isset($args['default_value']) ? $args['default_value'] : null,
				'description' => isset($args['description']) ? $args['description'] : null,
				'sort' => $saved_meta_field_model->getSort()
			]);

			// advanced_options
			if(isset($args['advanced_options'])){

				$meta_field_model->clearAdvancedOptions();

				foreach ($args['advanced_options'] as $index => $option){

					// validate option
					$mandatory_keys = [
						'key' => [
							'required' => true,
							'type' => 'string',
						],
						'value' => [
							'required' => true,
							'type' => 'integer|string',
						],
					];

					$validator = new ArgumentsArrayValidator();

					if(!$validator->validate($mandatory_keys, $option)){
						return false;
					}

					$meta_field_advanced_option_model = MetaBoxFieldAdvancedOptionModel::hydrateFromArray([
						'id' => Uuid::v4(),
						'metaBoxField' => $meta_field_model,
						'key' => $option['key'],
						'value' => $option['value'],
					]);

					$meta_field_model->addAdvancedOption($meta_field_advanced_option_model);
				}
			}

			// options
			if(isset($args['options'])){

				$meta_field_model->clearOptions();

				foreach ($args['options'] as $index => $option){

					// validate option
					$mandatory_keys = [
						'label' => [
							'required' => true,
							'type' => 'string',
						],
						'value' => [
							'required' => true,
							'type' => 'integer|string',
						],
					];

					$validator = new ArgumentsArrayValidator();

					if(!$validator->validate($mandatory_keys, $option)){
						return false;
					}

					$meta_field_option_model = MetaBoxFieldOptionModel::hydrateFromArray([
						'id' => Uuid::v4(),
						'metaBoxField' => $meta_field_model,
						'label' => $option['label'],
						'value' => $option['value'],
						'sort' => $index+1
					]);

					$meta_field_model->addOption($meta_field_option_model);
				}
			}

			// visibility_conditions
			if(isset($args['visibility_conditions'])) {

				// data morphing
				$visibility_conditions = [];

				$meta_field_model->clearVisibilityConditions();

				foreach ( $args[ 'visibility_conditions' ] as $condition ) {
					// validate option
					$mandatory_keys = [
						'type' => [
							'required' => true,
							'type' => 'string',
							'enum' => [
								'value',
								'post_id',
								'taxonomy',
								'other_fields',
							],
						],
						'operator' => [
							'required' => false,
							'type' => 'string',
							'enum' => [
								'=',
								'!=',
								'<',
								'>',
								'<=',
								'>=',
								'in',
								'not_in',
								'like',
								'not_like',
								'blank',
								'not_blank',
								'checked',
								'not_checked',
							],
						],
						'value' => [
							'required' => true,
							'type' => 'array|string|integer|object',
						],
						'meta_field' => [
							'required' => false,
							'type' => 'string',
						],
						'tax_name' => [
							'required' => false,
							'type' => 'string',
						],
						'logic' => [
							'required' => false,
							'type' => 'string',
							'enum' => [
								'and',
								'or',
							],
						],
					];

					$validator = new ArgumentsArrayValidator();

					if(!$validator->validate($mandatory_keys, $condition)){
						return false;
					}

					if($condition['type'] === 'other_fields'){

						$related_field_model = MetaRepository::getMetaFieldByName([
							'belongsTo' => MetaTypes::OPTION_PAGE,
							'find' => $args['post_type'],
							'boxName' => $args['box_name'],
							'fieldName' => $condition['meta_field']
						]);

						if($related_field_model === null){
							throw new \Exception('Unknown related field');
						}

						$condition['type'] = [
							'type' => strtoupper($condition['type']),
							'value' => $related_field_model->getId(),
						];

					} elseif($condition['type'] === 'taxonomy'){

						if(!isset($condition['tax_name'])){
							throw new \Exception('`tax_name` needed');
						}

						$condition['type'] = [
							'type' => strtoupper($condition['type']),
							'value' => $condition['tax_name'],
						];

					} else {
						$condition['type'] = [
							'type' => strtoupper($condition['type']),
							'value' => $condition['type'],
						];
					}

					if($condition['value'] instanceof \WP_Post){
						$condition['value'] = $condition['value']->ID;
					}

					if($condition['value'] instanceof \WP_Term){
						$condition['value'] = $condition['value']->term_id;
					}

					$condition['operator'] = isset($condition['operator']) ? strtoupper($condition['operator']) : '=';

					$visibility_conditions[] = $condition;
				}

				VisibilityConditionValidator::validate($meta_field_model, $visibility_conditions);

				foreach ( $visibility_conditions as $index => $condition ) {

					$visibility_condition_model = MetaBoxFieldVisibilityModel::hydrateFromArray([
						'id' => Uuid::v4(),
						'type' => $condition['type'],
						'value' => $condition['value'],
						'operator' => $condition['operator'],
						'logic' => (isset($condition['logic'])) ? strtoupper($condition['logic']) : null,
						'sort' => ($index+1),
						'metaBoxField' => $meta_field_model
					]);

					$meta_field_model->addVisibilityCondition($visibility_condition_model);
				}
			}

			// children
			if(isset($args['children'])) {

				$meta_field_model->clearChildren();

				foreach ( $args[ 'children' ] as $index => $child ) {

					// validate array
					$mandatory_keys = [
						'field_name' => [
							'required' => true,
							'type' => 'string',
						],
						'field_type' => [
							'required' => true,
							'type' => 'string',
							'enum' => [
								OptionPageMetaBoxFieldModel::ADDRESS_TYPE,
								OptionPageMetaBoxFieldModel::COLOR_TYPE,
								OptionPageMetaBoxFieldModel::CURRENCY_TYPE,
								OptionPageMetaBoxFieldModel::DATE_TYPE,
								OptionPageMetaBoxFieldModel::EDITOR_TYPE,
								OptionPageMetaBoxFieldModel::EMAIL_TYPE,
								OptionPageMetaBoxFieldModel::EMBED_TYPE,
								OptionPageMetaBoxFieldModel::FILE_TYPE,
								OptionPageMetaBoxFieldModel::HTML_TYPE,
								OptionPageMetaBoxFieldModel::GALLERY_TYPE,
								OptionPageMetaBoxFieldModel::IMAGE_TYPE,
								OptionPageMetaBoxFieldModel::LENGTH_TYPE,
								OptionPageMetaBoxFieldModel::LIST_TYPE,
								OptionPageMetaBoxFieldModel::NUMBER_TYPE,
								OptionPageMetaBoxFieldModel::PHONE_TYPE,
								OptionPageMetaBoxFieldModel::REPEATER_TYPE,
								OptionPageMetaBoxFieldModel::SELECT_TYPE,
								OptionPageMetaBoxFieldModel::SELECT_MULTI_TYPE,
								OptionPageMetaBoxFieldModel::TEXT_TYPE,
								OptionPageMetaBoxFieldModel::TEXTAREA_TYPE,
								OptionPageMetaBoxFieldModel::TIME_TYPE,
								OptionPageMetaBoxFieldModel::TOGGLE_TYPE,
								OptionPageMetaBoxFieldModel::VIDEO_TYPE,
								OptionPageMetaBoxFieldModel::WEIGHT_TYPE,
								OptionPageMetaBoxFieldModel::URL_TYPE,
							],
						],
						'required' => [
							'required' => true,
							'type' => 'boolean',
						],
						'default_value' => [
							'required' => false,
							'type' => 'integer|string',
						],
						'description' => [
							'required' => false,
							'type' => 'string',
						],
						'options' => [
							'required' => false,
							'type' => 'array',
						],
					];

					$validator = new ArgumentsArrayValidator();

					if(!$validator->validate($mandatory_keys, $child)){
						return false;
					}

					$child_field_model = OptionPageMetaBoxFieldModel::hydrateFromArray([
						'id' => Uuid::v4(),
						'metaBox' => $meta_field_model->getMetaBox(),
						'title' => $child['field_name'],
						'type' => $child['field_type'],
						'showInArchive' => false,
						'required' => $child['required'],
						'defaultValue' => isset($child['default_value']) ? $child['default_value'] : null,
						'description' => isset($child['description']) ? $child['description'] : null,
						'sort' => $index+1
					]);

					// options
					if(isset($child['options'])){
						foreach ($child['options'] as $option_index => $option){

							// validate option
							$mandatory_keys = [
								'label' => [
									'required' => true,
									'type' => 'string',
								],
								'value' => [
									'required' => true,
									'type' => 'integer|string',
								],
							];

							$validator = new ArgumentsArrayValidator();

							if(!$validator->validate($mandatory_keys, $option)){
								return false;
							}

							$meta_field_option_model = MetaBoxFieldOptionModel::hydrateFromArray([
								'id' => Uuid::v4(),
								'metaBoxField' => $child_field_model,
								'label' => $option['label'],
								'value' => $option['value'],
								'sort' => $option_index+1
							]);

							$child_field_model->addOption($meta_field_option_model);
						}
					}

					$child_field_model->setParentId($meta_field_model->getId());
					$meta_field_model->addChild($child_field_model);
				}
			}

			MetaRepository::saveMetaBoxField($meta_field_model);

			return true;

		} catch (\Exception $exception){
			return false;
		}
	}
}

if( !function_exists('get_acpt_option_page_meta_objects') )
{
	/**
	 * @param $option_page
	 *
	 * @return array
	 */
	function get_acpt_option_page_meta_objects($option_page)
	{
		$meta = [];

		try {
			$models = MetaRepository::get([
				'belongsTo' => MetaTypes::OPTION_PAGE,
				'find' => $option_page,
			]);

			foreach ($models as $box_model){
				$meta[] = get_acpt_option_page_box_object($option_page, $box_model->getName());
			}

			return $meta;

		} catch (\Exception $exception){
			return $meta;
		}
	}
}

if( !function_exists('get_acpt_option_page_box_object'))
{
	/**
	 * @param $option_page
	 * @param $box_name
	 *
	 * @return stdClass|null
	 */
	function get_acpt_option_page_box_object($option_page, $box_name)
	{
		try {

			$meta = MetaRepository::get([
				'belongsTo' => MetaTypes::OPTION_PAGE,
				'find' => $option_page,
				'boxName' => $box_name,
			]);

			if(!isset($meta[0])){
				return null;
			}

			$box_model = new \stdClass();
			$box_model->box_name = $meta[0]->getName();
			$box_model->option_page = $option_page;
			$box_model->belongs_to = MetaTypes::OPTION_PAGE;
			$box_model->fields = [];

			foreach ($meta[0]->getFields() as $field){
				$box_model->fields[] = get_acpt_option_page_field_object($option_page, $box_name, $field->getName());
			}

			return $box_model;

		} catch (\Exception $exception){
			return null;
		}
	}
}

if( !function_exists('get_acpt_option_page_field_object'))
{
	/**
	 * @param $option_page
	 * @param $box_name
	 * @param $field_name
	 *
	 * @return stdClass|null
	 */
	function get_acpt_option_page_field_object($option_page, $box_name, $field_name)
	{
		try {
			$meta_field = MetaRepository::getMetaFieldByName([
				'belongsTo' => MetaTypes::OPTION_PAGE,
				'find' => $option_page,
				'boxName' => $box_name,
				'fieldName' => $field_name
			]);

			if(!isset($meta_field)){
				return null;
			}

			$field_object = new \stdClass();
			$field_object->field_name = $meta_field->getName();
			$field_object->field_type = $meta_field->getType();
			$field_object->show_in_archive = (bool)$meta_field->isShowInArchive();
			$field_object->is_required = (bool)$meta_field->isRequired();
			$field_object->default_value = $meta_field->getDefaultValue();
			$field_object->description = $meta_field->getDescription();
			$field_object->children = [];
			$field_object->options = [];
			$field_object->advanced_options = [];
			$field_object->visibility_conditions = [];
			$field_object->blocks = [];

			foreach ($meta_field->getBlocks() as $block){
				$block_object = new \stdClass();
				$block_object->block_name = $block->getName();
				$block_object->block_label = $block->getLabel();
				$block_object->fields = [];

				foreach ($block->getFields() as $nested_field){
					$nested_field_object = new \stdClass();
					$nested_field_object->field_name = $nested_field->getName();
					$nested_field_object->field_type = $nested_field->getType();
					$nested_field_object->is_required = (bool)$nested_field->isRequired();
					$nested_field_object->default_value = $nested_field->getDefaultValue();
					$nested_field_object->description = $nested_field->getDescription();
					$nested_field_object->options = [];
					$nested_field_object->advanced_options = [];
					$nested_field_object->visibility_conditions = [];

					foreach ($nested_field->getAdvancedOptions() as $advanced_option){
						$advanced_option_model = new \stdClass();
						$advanced_option_model->key = $advanced_option->getKey();
						$advanced_option_model->value = $advanced_option->getValue();
						$nested_field_object->advanced_options[] = $advanced_option_model;
					}

					foreach ($nested_field->getOptions() as $option){
						$option_model = new \stdClass();
						$option_model->label = $option->getLabel();
						$option_model->value = $option->getValue();
						$nested_field_object->options[] = $option_model;
					}

					foreach ($nested_field->getVisibilityConditions() as $visibilityCondition){

						$render_condition_object = new \stdClass();
						$type = (array)$visibilityCondition->getType();
						$render_condition_object->type = $type['type'];
						$render_condition_object->type = strtolower($render_condition_object->type);

						if($render_condition_object->type === 'taxonomy'){
							$render_condition_object->tax_name = $type['value'];
						}

						if($render_condition_object->type === 'other_fields' and $type['value'] instanceof OptionPageMetaBoxFieldModel){
							$render_condition_object->meta_field = $type['value']->getName();
						}

						$render_condition_object->operator = strtolower($visibilityCondition->getOperator());
						$render_condition_object->value = $visibilityCondition->getValue(); //@TODO value can be an object?
						$render_condition_object->logic = strtolower($visibilityCondition->getLogic());

						$nested_field_object->visibility_conditions[] = $render_condition_object;
					}

					$block_object->fields[] = $nested_field_object;
				}

				$field_object->blocks[] = $block_object;
			}

			foreach ($meta_field->getChildren() as $child){

				$child_object = new \stdClass();
				$child_object->field_name = $child->getName();
				$child_object->field_type = $child->getType();
				$child_object->is_required = (bool)$child->isRequired();
				$child_object->default_value = $child->getDefaultValue();
				$child_object->description = $child->getDescription();
				$child_object->options = [];

				foreach ($child->getOptions() as $option){

					$option_model = new \stdClass();
					$option_model->label = $option->getLabel();
					$option_model->value = $option->getValue();

					$child_object->options[] = $option_model;
				}

				$field_object->children[] = $child_object;
			}

			foreach ($meta_field->getAdvancedOptions() as $advanced_option){

				$advanced_option_model = new \stdClass();
				$advanced_option_model->key = $advanced_option->getKey();
				$advanced_option_model->value = $advanced_option->getValue();

				$field_object->advanced_options[] = $advanced_option_model;
			}

			foreach ($meta_field->getOptions() as $option){

				$option_model = new \stdClass();
				$option_model->label = $option->getLabel();
				$option_model->value = $option->getValue();

				$field_object->options[] = $option_model;
			}

			foreach ($meta_field->getVisibilityConditions() as $visibilityCondition){

				$render_condition_object = new \stdClass();
				$type = (array)$visibilityCondition->getType();
				$render_condition_object->type = $type['type'];
				$render_condition_object->type = strtolower($render_condition_object->type);

				if($render_condition_object->type === 'taxonomy'){
					$render_condition_object->tax_name = $type['value'];
				}

				if($render_condition_object->type === 'other_fields' and $type['value'] instanceof OptionPageMetaBoxFieldModel){
					$render_condition_object->meta_field = $type['value']->getName();
				}

				$render_condition_object->operator = strtolower($visibilityCondition->getOperator());
				$render_condition_object->value = $visibilityCondition->getValue(); //@TODO value can be an object?
				$render_condition_object->logic = strtolower($visibilityCondition->getLogic());

				$field_object->visibility_conditions[] = $render_condition_object;
			}

			return $field_object;

		} catch (\Exception $exception){
			return null;
		}
	}
}

if( !function_exists('get_acpt_option_page_field_row_object') ){

	/**
	 * @param $option_page
	 * @param $box_name
	 * @param $field_name
	 * @param $child_field_name
	 *
	 * @return |null
	 */
	function get_acpt_option_page_field_row_object($option_page, $box_name, $field_name, $child_field_name)
	{
		try {
			$field_object = get_acpt_option_page_field_object($option_page, $box_name, $field_name);

			if(!isset($field_object->children)){
				return null;
			}

			if(empty($field_object->children) or !is_array($field_object->children)){
				return null;
			}

			foreach ($field_object->children as $child_object){
				if($child_object->field_name === $child_field_name){
					return $child_object;
				}
			}

			return null;

		} catch (\Exception $exception){
			return null;
		}
	}
}

if( !function_exists('get_acpt_option_page_field_block_object') ){

	/**
	 * Returns the settings of a specific block.
	 * Only for Flexible field.
	 *
	 * @param $option_page
	 * @param $box_name
	 * @param $field_name
	 * @param $block_name
	 *
	 * @return \stdClass|null
	 */
	function get_acpt_option_page_field_block_object($option_page, $box_name, $field_name, $block_name)
	{
		try {
			$field_object = get_acpt_option_page_field_object($option_page, $box_name, $field_name);

			if(!isset($field_object->blocks)){
				return null;
			}

			if(empty($field_object->blocks) or !is_array($field_object->blocks)){
				return null;
			}

			foreach ($field_object->blocks as $block_object){
				if($block_object->block_name === $block_name){
					return $block_object;
				}
			}

			return null;

		} catch (\Exception $exception){
			return null;
		}
	}
}

if( !function_exists('get_acpt_option_page_nested_field_row_object') ){

	/**
	 * Returns the settings of a specific sub field inside a block.
	 * Only for Flexible field.
	 *
	 * @param $option_page
	 * @param $box_name
	 * @param $field_name
	 * @param $block_name
	 * @param $nested_field_name
	 *
	 * @return \stdClass|null
	 */
	function get_acpt_option_page_nested_field_row_object($option_page, $box_name, $field_name, $block_name, $nested_field_name)
	{
		try {
			$field_object = get_acpt_option_page_field_object($option_page, $box_name, $field_name);

			if(!isset($field_object->blocks)){
				return null;
			}

			if(empty($field_object->blocks) or !is_array($field_object->blocks)){
				return null;
			}

			foreach ($field_object->blocks as $block_object){
				if($block_object->block_name === $block_name){
					foreach ($block_object->fields as $nested_field_object){
						if($nested_field_object->field_name === $nested_field_name){
							return $nested_field_object;
						}
					}
				}
			}

			return null;
		} catch (\Exception $exception){
			return null;
		}
	}
}

