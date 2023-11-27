<?php

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Core\Validators\ArgumentsArrayValidator;
use ACPT\Core\Values\PostMetaValue;
use ACPT\Costants\MetaTypes;
use ACPT\Utils\Checker\FieldVisibilityChecker;

if( !function_exists('get_acpt_fields') ){

    /**
     * Returns an array of field values (name => value) for a specific post/box.
     *
     * @param array $args
     *
     * @return array
     */
    function get_acpt_fields(array $args = [])
    {
        try {
            // validate array
            $mandatory_keys = [
                'post_id' => [
                    'required' => true,
                    'type' => 'integer',
                ],
                'box_name' => [
                    'required' => true,
                    'type' => 'string',
                ],
            ];

            $validator = new ArgumentsArrayValidator();

            if(!$validator->validate($mandatory_keys, $args)){
                return null;
            }

            $post_type = get_post_type($args['post_id']);

            $meta_box_model = MetaRepository::getMetaBoxByName([
                'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
                'find' => $post_type,
                'boxName' => $args['box_name'],
            ]);

            $values = [];

            foreach ($meta_box_model->getFields() as $meta_field_model){
                $post_meta_value = new PostMetaValue($meta_field_model, $args['post_id']);
                $values[$meta_field_model->getName()] = $post_meta_value->getValue();
            }

           return $values;

        } catch (\Exception $exception){
            return [];
        }
    }
}

if( !function_exists('get_acpt_field') ){

    /**
     * Returns the value of a specific field.
     *
     * @param array $args
     *
     * @return mixed|null
     */
    function get_acpt_field(array $args = [])
    {
        try {
            // validate array
            $mandatory_keys = [
                'post_id' => [
                    'required' => true,
                    'type' => 'integer',
                ],
                'box_name' => [
                    'required' => true,
                    'type' => 'string',
                ],
                'field_name' => [
                    'required' => true,
                    'type' => 'string',
                ],
            ];

            $validator = new ArgumentsArrayValidator();

            if(!$validator->validate($mandatory_keys, $args)){
                return null;
            }

            $post_type = get_post_type($args['post_id']);

            $meta_field_model = MetaRepository::getMetaFieldByName([
                'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
                'find' => $post_type,
                'boxName' => $args['box_name'],
                'fieldName' => $args['field_name']
            ]);

            if($meta_field_model === null){
                return null;
            }

            $post_meta_value = new PostMetaValue($meta_field_model, $args['post_id']);

            return $post_meta_value->getValue();

        } catch (\Exception $exception){
            return null;
        }
    }
}

if( !function_exists('acpt_field_has_blocks') ){

	function acpt_field_has_blocks(array $args = [])
	{
		try {
			$get_acpt_field = get_acpt_field($args);

			$post_type = get_post_type($args['post_id']);
			$meta_field_model = MetaRepository::getMetaFieldByName([
				'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
				'find' => $post_type,
				'boxName' => $args['box_name'],
				'fieldName' => $args['field_name']
			]);

			if($meta_field_model->getType() !== CustomPostTypeMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE){
				return false;
			}

			if(!is_array($get_acpt_field)){
				return false;
			}

			if(!isset($get_acpt_field['blocks'])){
				return false;
			}

			return !empty($get_acpt_field['blocks']);

		} catch (\Exception $exception){
			return false;
		}
	}
}

if( !function_exists('acpt_field_has_rows') ){

    /**
     * Used to loop through a parent field's value.
     * Only usable for Repeater and List fields.
     *
     * @param array $args
     *
     * @return bool
     */
    function acpt_field_has_rows(array $args = [])
    {
        try {
            $get_acpt_field = get_acpt_field($args);

            $post_type = get_post_type($args['post_id']);
            $meta_field_model = MetaRepository::getMetaFieldByName([
                'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
                'find' => $post_type,
                'boxName' => $args['box_name'],
                'fieldName' => $args['field_name']
            ]);

            if($meta_field_model === null){
	            return false;
            }

            if($meta_field_model->getType() !== CustomPostTypeMetaBoxFieldModel::REPEATER_TYPE and $meta_field_model->getType() !== CustomPostTypeMetaBoxFieldModel::LIST_TYPE){
                return false;
            }

            if(!is_array($get_acpt_field)){
                return false;
            }

            return !empty($get_acpt_field);

        } catch (\Exception $exception){
            return false;
        }
    }
}

if( !function_exists('get_acpt_child_field') ){

	/**
	 * Displays the value of a specific child field.
	 *
	 * @param array $args
	 *
	 * @return mixed
	 */
	function get_acpt_child_field(array $args = [])
	{
		// validate array
		$mandatory_keys = [
			'post_id' => [
				'required' => true,
				'type' => 'integer',
			],
			'box_name' => [
				'required' => true,
				'type' => 'string',
			],
			'field_name' => [
				'required' => true,
				'type' => 'string',
			],
			'parent_field_name' => [
				'required' => true,
				'type' => 'string',
			],
			'index' => [
				'required' => true,
				'type' => 'string|integer',
			],
		];

		$validator = new ArgumentsArrayValidator();

		if(!$validator->validate($mandatory_keys, $args)){
			return null;
		}

		$parent_field = get_acpt_field([
			'post_id' => $args['post_id'],
			'box_name' => $args['box_name'],
			'field_name' => $args['parent_field_name'],
		]);

		return (isset($parent_field[$args['index']][$args['field_name']])) ? $parent_field[$args['index']][$args['field_name']] : null;
	}
}

if( !function_exists('get_acpt_block') ){

	function get_acpt_block(array $args = [])
	{
		// validate array
		$mandatory_keys = [
			'post_id' => [
				'required' => true,
				'type' => 'integer',
			],
			'box_name' => [
				'required' => true,
				'type' => 'string',
			],
			'parent_field_name' => [
				'required' => true,
				'type' => 'string',
			],
			'block_name' => [
				'required' => true,
				'type' => 'string',
			],
		];

		$validator = new ArgumentsArrayValidator();

		if(!$validator->validate($mandatory_keys, $args)){
			return null;
		}

		$parent_field = get_acpt_field([
			'post_id' => $args['post_id'],
			'box_name' => $args['box_name'],
			'field_name' => $args['parent_field_name'],
		]);

		if(!isset($parent_field['blocks'])){
			return null;
		}

		$blocks = [];

		foreach ($parent_field['blocks'] as $block){
			foreach (array_keys($block) as $blockName){
				if($blockName === $args['block_name']){
					$blocks[] = $block;
				}
			}
		}

		return $blocks;
	}
}

if( !function_exists('get_acpt_block_child_field') ){

	function get_acpt_block_child_field(array $args = [])
	{
		// validate array
		$mandatory_keys = [
			'post_id' => [
				'required' => true,
				'type' => 'integer',
			],
			'box_name' => [
				'required' => true,
				'type' => 'string',
			],
			'field_name' => [
				'required' => true,
				'type' => 'string',
			],
			'parent_field_name' => [
				'required' => true,
				'type' => 'string',
			],
			'index' => [
				'required' => true,
				'type' => 'string|integer',
			],
			'block_name' => [
				'required' => false,
				'type' => 'string',
			],
			'block_index' => [
				'required' => false,
				'type' => 'string|integer',
			],
		];

		$validator = new ArgumentsArrayValidator();

		if(!$validator->validate($mandatory_keys, $args)){
			return null;
		}

		$parent_field = get_acpt_field([
			'post_id' => $args['post_id'],
			'box_name' => $args['box_name'],
			'field_name' => $args['parent_field_name'],
		]);

		if(!isset($parent_field['blocks'])){
			return null;
		}

		if(!isset($parent_field['blocks'][$args['block_index']])){
			return null;
		}

		if(!isset($parent_field['blocks'][$args['block_index']][$args['block_name']])){
			return null;
		}

		if(!isset($parent_field['blocks'][$args['block_index']][$args['block_name']])){
			return null;
		}

		return (isset($parent_field['blocks'][$args['block_index']][$args['block_name']][$args['field_name']][$args['index']])) ? $parent_field['blocks'][$args['block_index']][$args['block_name']][$args['field_name']][$args['index']] : null;
	}
}

if( !function_exists('acpt_block_child_field') ){

	/**
	 * Displays the value of a specific child (nested) field inside a block.
	 *
	 * @param array $args
	 *
	 * @return mixed
	 */
	function acpt_block_child_field(array $args = [])
	{
		// validate array
		$mandatory_keys = [
			'post_id' => [
				'required' => true,
				'type' => 'integer',
			],
			'box_name' => [
				'required' => true,
				'type' => 'string',
			],
			'field_name' => [
				'required' => true,
				'type' => 'string',
			],
			'parent_field_name' => [
				'required' => true,
				'type' => 'string',
			],
			'index' => [
				'required' => true,
				'type' => 'string|integer',
			],
			'date_format' => [
				'required' => false,
				'type' => 'string',
			],
			'width' => [
				'required' => false,
				'type' => 'string|integer',
			],
			'height' => [
				'required' => false,
				'type' => 'string|integer',
			],
			'target' => [
				'required' => false,
				'type' => 'string',
				'enum' => [
					'_blank',
					'_self',
					'_parent',
					'_top',
				],
			],
			'elements' => [
				'required' => false,
				'type' => 'integer',
			],
			'block_name' => [
				'required' => false,
				'type' => 'string',
			],
			'block_index' => [
				'required' => false,
				'type' => 'string|integer',
			],
		];

		$validator = new ArgumentsArrayValidator();

		if(!$validator->validate($mandatory_keys, $args)){
			return null;
		}

		$shortcode = '[acpt pid="'.$args['post_id'].'" box="'.$args['box_name'].'" parent="'.$args['parent_field_name'].'" block_name="'.$args['block_name'].'" block_index="'.$args['block_index'].'" index="'.$args['index'].'" field="'.$args['field_name'].'"';

		if(isset($args['date_format'])){
			$shortcode .= ' date-format="'.$args['date_format'].'"';
		}

		if(isset($args['width'])){
			$shortcode .= ' width="'.$args['width'].'"';
		}

		if(isset($args['height'])){
			$shortcode .= ' height="'.$args['height'].'"';
		}

		if(isset($args['target'])){
			$shortcode .= ' target="'.$args['target'].'"';
		}

		if(isset($args['elements'])){
			$shortcode .= ' elements="'.$args['elements'].'"';
		}

		$shortcode .= ']';

		return do_shortcode($shortcode);
	}
}

if( !function_exists('acpt_child_field') ){

	/**
	 * Displays the value of a specific child (nested) field.
	 *
	 * @param array $args
	 *
	 * @return mixed
	 */
	function acpt_child_field(array $args = [])
	{
		// validate array
		$mandatory_keys = [
			'post_id' => [
				'required' => true,
				'type' => 'integer',
			],
			'box_name' => [
				'required' => true,
				'type' => 'string',
			],
			'field_name' => [
				'required' => true,
				'type' => 'string',
			],
			'parent_field_name' => [
				'required' => true,
				'type' => 'string',
			],
			'index' => [
				'required' => true,
				'type' => 'string|integer',
			],
			'date_format' => [
				'required' => false,
				'type' => 'string',
			],
			'width' => [
				'required' => false,
				'type' => 'string|integer',
			],
			'height' => [
				'required' => false,
				'type' => 'string|integer',
			],
			'target' => [
				'required' => false,
				'type' => 'string',
				'enum' => [
					'_blank',
					'_self',
					'_parent',
					'_top',
				],
			],
			'elements' => [
				'required' => false,
				'type' => 'integer',
			],
		];

		$validator = new ArgumentsArrayValidator();

		if(!$validator->validate($mandatory_keys, $args)){
			return null;
		}

		$shortcode = '[acpt pid="'.$args['post_id'].'" box="'.$args['box_name'].'" parent="'.$args['parent_field_name'].'" index="'.$args['index'].'" field="'.$args['field_name'].'"';

		if(isset($args['date_format'])){
			$shortcode .= ' date-format="'.$args['date_format'].'"';
		}

		if(isset($args['width'])){
			$shortcode .= ' width="'.$args['width'].'"';
		}

		if(isset($args['height'])){
			$shortcode .= ' height="'.$args['height'].'"';
		}

		if(isset($args['target'])){
			$shortcode .= ' target="'.$args['target'].'"';
		}

		if(isset($args['elements'])){
			$shortcode .= ' elements="'.$args['elements'].'"';
		}

		$shortcode .= ']';

		return do_shortcode($shortcode);
	}
}

if( !function_exists('acpt_field') ){

    /**
     * Displays the value of a specific field.
     *
     * @param array $args
     *
     * @return mixed
     */
    function acpt_field(array $args = [])
    {
        // validate array
        $mandatory_keys = [
            'post_id' => [
                'required' => true,
                'type' => 'integer',
            ],
            'box_name' => [
                'required' => true,
                'type' => 'string',
            ],
            'field_name' => [
                'required' => true,
                'type' => 'string',
            ],
            'date_format' => [
                'required' => false,
                'type' => 'string',
            ],
            'width' => [
                'required' => false,
                'type' => 'string|integer',
            ],
            'height' => [
                'required' => false,
                'type' => 'string|integer',
            ],
            'target' => [
                'required' => false,
                'type' => 'string',
                'enum' => [
                    '_blank',
                    '_self',
                    '_parent',
                    '_top',
                ],
            ],
            'elements' => [
                'required' => false,
                'type' => 'integer',
            ],
        ];

        $validator = new ArgumentsArrayValidator();

        if(!$validator->validate($mandatory_keys, $args)){
            return null;
        }

        $shortcode = '[acpt pid="'.$args['post_id'].'" box="'.$args['box_name'].'" field="'.$args['field_name'].'"';

        if(isset($args['date_format'])){
            $shortcode .= ' date-format="'.$args['date_format'].'"';
        }

        if(isset($args['width'])){
            $shortcode .= ' width="'.$args['width'].'"';
        }

        if(isset($args['height'])){
            $shortcode .= ' height="'.$args['height'].'"';
        }

        if(isset($args['target'])){
            $shortcode .= ' target="'.$args['target'].'"';
        }

        if(isset($args['elements'])){
            $shortcode .= ' elements="'.$args['elements'].'"';
        }

        $shortcode .= ']';

        return do_shortcode($shortcode);
    }
}

if( !function_exists('is_acpt_field_visible') ){

	/**
	 * Check if field is visible or not
	 * (depending on conditional logic)
	 *
	 * @param array $args
	 *
	 * @return bool
	 * @throws Exception
	 */
	function is_acpt_field_visible(array $args = [])
	{
		$mandatory_keys = [
			'post_id' => [
				'required' => true,
				'type' => 'integer',
			],
			'box_name' => [
				'required' => true,
				'type' => 'string',
			],
			'field_name' => [
				'required' => true,
				'type' => 'string',
			],
			'parent_field_name' => [
				'required' => false,
				'type' => 'string',
			],
			'index' => [
				'required' => false,
				'type' => 'string|integer',
			],
			'block_name' => [
				'required' => false,
				'type' => 'string',
			],
			'block_index' => [
				'required' => false,
				'type' => 'string|integer',
			],
		];

		$validator = new ArgumentsArrayValidator();

		if(!$validator->validate($mandatory_keys, $args)){
			return false;
		}

		$meta_field_model = null;

		// fields nested in a Flexible
		if(isset($args['parent_field_name']) and isset($args['block_name']) and isset($args['block_index'])){
			$parent_meta_field_model = MetaRepository::getMetaFieldByName([
				'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
				'find' => get_post_type($args['post_id']),
				'boxName' => $args['box_name'],
				'fieldName' => $args['parent_field_name']
			]);

			if($parent_meta_field_model !== null){
				foreach ($parent_meta_field_model->getBlocks() as $block_model){
					foreach ($block_model->getFields() as $nested_field_model){
						if($nested_field_model->getName() ===  $args['field_name']){
							$meta_field_model = $nested_field_model;
						}
					}
				}
			}

		} elseif(isset($args['parent_field_name']) and isset($args['index'])){ // fields nested in a Repeater
			$parent_meta_field_model = MetaRepository::getMetaFieldByName([
				'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
				'find' => get_post_type($args['post_id']),
				'boxName' => $args['box_name'],
				'fieldName' => $args['parent_field_name']
			]);

			if($parent_meta_field_model !== null){
				foreach ($parent_meta_field_model->getChildren() as $child_field_model){
					if($child_field_model->getName() ===  $args['field_name']){
						$meta_field_model = $child_field_model;
					}
				}
			}

		} else {
			$meta_field_model = MetaRepository::getMetaFieldByName([
				'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
				'find' => get_post_type($args['post_id']),
				'boxName' => $args['box_name'],
				'fieldName' => $args['field_name']
			]);
		}

		if($meta_field_model === null){
			return false;
		}

		$index = (isset($args['index'])) ? $args['index'] : null;
		$block_name = (isset($args['block_name'])) ? $args['block_name'] : null;
		$block_index = (isset($args['block_index'])) ? $args['block_index'] : null;

		return FieldVisibilityChecker::isFieldVisible($args['post_id'], $meta_field_model, $index, $block_name, $block_index);
	}
}