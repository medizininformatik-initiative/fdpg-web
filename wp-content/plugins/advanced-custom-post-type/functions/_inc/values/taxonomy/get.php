<?php

use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Core\Validators\ArgumentsArrayValidator;
use ACPT\Core\Values\TaxonomyMetaValue;
use ACPT\Costants\MetaTypes;
use ACPT\Utils\Checker\FieldVisibilityChecker;

if( !function_exists('get_acpt_tax_fields') ){

    /**
     * Returns an array of field values (name => value) for a specific post/box.
     *
     * @param array $args
     *
     * @return array
     */
    function get_acpt_tax_fields(array $args = [])
    {
        try {
            // validate array
            $mandatory_keys = [
                'term_id' => [
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

            $taxonomyObject = get_term( $args['term_id']);

            if($taxonomyObject === null){
                return null;
            }
            
            $taxonomy = $taxonomyObject->taxonomy;

            $meta_box_model = MetaRepository::getMetaBoxByName([
                'belongsTo' => MetaTypes::TAXONOMY,
                'find' => $taxonomy,
                'boxName' => $args['box_name'],
            ]);

            $values = [];

            foreach ($meta_box_model->getFields() as $meta_field_model){
                $post_meta_value = new TaxonomyMetaValue($meta_field_model, $args['term_id']);
                $values[$meta_field_model->getName()] = $post_meta_value->getValue();
            }

            return $values;

        } catch (\Exception $exception){
            return [];
        }
    }
}

if( !function_exists('get_acpt_tax_field') ){

    /**
     * Returns the value of a specific field.
     *
     * @param array $args
     *
     * @return mixed|null
     */
    function get_acpt_tax_field(array $args = [])
    {
        try {
            // validate array
            $mandatory_keys = [
                'term_id' => [
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

            $taxonomyObject = get_term( $args['term_id']);
            
            if($taxonomyObject === null){
                return null;
            }
            
            $taxonomy = $taxonomyObject->taxonomy;

            $meta_field_model = MetaRepository::getMetaFieldByName([
                'belongsTo' => MetaTypes::TAXONOMY,
                'find' => $taxonomy,
                'boxName' => $args['box_name'],
                'fieldName' => $args['field_name']
            ]);
            
            if($meta_field_model === null){
                return null;
            }

            $post_meta_value = new TaxonomyMetaValue($meta_field_model, $args['term_id']);

            return $post_meta_value->getValue();

        } catch (\Exception $exception){
            return null;
        }
    }
}

if( !function_exists('acpt_tax_field_has_rows') ){

    /**
     * Used to loop through a parent field's value.
     * Only usable for Repeater and List fields.
     *
     * @param array $args
     *
     * @return bool
     */
    function acpt_tax_field_has_rows(array $args = [])
    {
        try {
            $get_acpt_tax_field = get_acpt_tax_field($args);

            $taxonomyObject = get_term( $args['term_id']);

            if($taxonomyObject === null){
                return null;
            }

            $taxonomy = $taxonomyObject->taxonomy;

            $meta_field_model = MetaRepository::getMetaFieldByName([
                'belongsTo' => MetaTypes::TAXONOMY,
                'find' => $taxonomy,
                'boxName' => $args['box_name'],
                'fieldName' => $args['field_name']
            ]);

            if($meta_field_model->getType() !== TaxonomyMetaBoxFieldModel::LIST_TYPE){
                return false;
            }

            if(!is_array($get_acpt_tax_field)){
                return false;
            }

            return !empty($get_acpt_tax_field);

        } catch (\Exception $exception){
            return false;
        }
    }
}

if( !function_exists( 'get_acpt_tax_child_field' ) )
{
	function get_acpt_tax_child_field(array $args = [])
	{
		// validate array
		$mandatory_keys = [
			'term_id' => [
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

		$parent_field = get_acpt_tax_field([
			'term_id' => $args['term_id'],
			'box_name' => $args['box_name'],
			'field_name' => $args['parent_field_name'],
		]);

		return (isset($parent_field[$args['index']][$args['field_name']])) ? $parent_field[$args['index']][$args['field_name']] : null;
	}
}

if( !function_exists( 'acpt_tax_child_field' ) )
{
	function acpt_tax_child_field(array $args = [])
	{
		// validate array
		$mandatory_keys = [
			'term_id' => [
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
			'render' => [
				'required' => false,
				'type' => 'string',
			],
		];

		$validator = new ArgumentsArrayValidator();

		if(!$validator->validate($mandatory_keys, $args)){
			return null;
		}

		$shortcode = '[acpt_tax tid="'.$args['term_id'].'" box="'.$args['box_name'].'" parent="'.$args['parent_field_name'].'" index="'.$args['index'].'" field="'.$args['field_name'].'"';

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

		if(isset($args['render'])){
			$shortcode .= ' render="'.$args['render'].'"';
		}

		$shortcode .= ']';

		return do_shortcode($shortcode);
	}
}

if( !function_exists('acpt_tax_field') ){

    /**
     * Displays the value of a specific field.
     *
     * @param array $args
     *
     * @return mixed
     */
    function acpt_tax_field(array $args = [])
    {
        // validate array
        $mandatory_keys = [
            'term_id' => [
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
            'render' => [
	            'required' => false,
	            'type' => 'string',
            ],
        ];

        $validator = new ArgumentsArrayValidator();

        if(!$validator->validate($mandatory_keys, $args)){
            return null;
        }

        $shortcode = '[acpt_tax tid="'.$args['term_id'].'" box="'.$args['box_name'].'" field="'.$args['field_name'].'"';

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

	    if(isset($args['render'])){
		    $shortcode .= ' render="'.$args['render'].'"';
	    }

        $shortcode .= ']';

        return do_shortcode($shortcode);
    }
}

if( !function_exists('is_acpt_tax_field_visible') ){

	/**
	 * @param array $args
	 *
	 * @return bool
	 * @throws Exception
	 */
	function is_acpt_tax_field_visible(array $args = [])
	{
		// validate array
		$mandatory_keys = [
			'term_id' => [
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
			return false;
		}

		$taxonomyObject = get_term( $args['term_id']);

		if($taxonomyObject === null){
			return null;
		}

		$taxonomy = $taxonomyObject->taxonomy;

		$meta_field_model = MetaRepository::getMetaFieldByName([
			'belongsTo' => MetaTypes::TAXONOMY,
			'find' => $taxonomy,
			'boxName' => $args['box_name'],
			'fieldName' => $args['field_name']
		]);

		if($meta_field_model === null){
			return false;
		}

		return FieldVisibilityChecker::isFieldVisible($args['term_id'], $meta_field_model);
	}
}

