<?php

use ACPT\Core\Helper\Strings;
use ACPT\Core\Helper\Uuid;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldAdvancedOptionModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldOptionModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldVisibilityModel;
use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;
use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Core\Repository\TaxonomyRepository;
use ACPT\Core\Validators\ArgumentsArrayValidator;
use ACPT\Core\Validators\VisibilityConditionValidator;
use ACPT\Costants\MetaTypes;

if( !function_exists('add_acpt_tax_meta_box') )
{
    /**
     * Add meta box settings to taxonomy.
     *
     * @param string $taxonomy
     * @param string $box_name
     * @param null $box_label
     *
     * @return bool
     */
    function add_acpt_tax_meta_box($taxonomy, $box_name, $box_label = null)
    {
        try {

            if(!TaxonomyRepository::exists($taxonomy)){
                return false;
            }

            $all_meta = MetaRepository::get([
                'belongsTo' => MetaTypes::TAXONOMY,
                'find' => $taxonomy
            ]);

            if(MetaRepository::existsMetaBox([
                'belongsTo' => MetaTypes::TAXONOMY,
                'find' => $taxonomy,
                'boxName' => $box_name,
            ])){
                return add_acpt_tax_meta_box($taxonomy, Strings::getUniqueName($box_name), $box_label);
            } else {
                $meta_box_model = TaxonomyMetaBoxModel::hydrateFromArray([
                    'id' => Uuid::v4(),
                    'taxonomy' => $taxonomy,
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

if( !function_exists('add_acpt_tax_meta_field') )
{
    /**
     * Add meta field settings to taxonomy.
     *
     * @param array $args
     *
     * @return bool
     */
    function add_acpt_tax_meta_field($args = [])
    {
        try {
            // validate array
            $mandatory_keys = [
                'taxonomy' => [
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
                        TaxonomyMetaBoxFieldModel::ADDRESS_TYPE,
                        TaxonomyMetaBoxFieldModel::COLOR_TYPE,
                        TaxonomyMetaBoxFieldModel::CHECKBOX_TYPE,
                        TaxonomyMetaBoxFieldModel::CURRENCY_TYPE,
                        TaxonomyMetaBoxFieldModel::DATE_TYPE,
                        TaxonomyMetaBoxFieldModel::DATE_RANGE_TYPE,
                        TaxonomyMetaBoxFieldModel::EDITOR_TYPE,
                        TaxonomyMetaBoxFieldModel::EMAIL_TYPE,
                        TaxonomyMetaBoxFieldModel::EMBED_TYPE,
                        TaxonomyMetaBoxFieldModel::FILE_TYPE,
                        TaxonomyMetaBoxFieldModel::HTML_TYPE,
                        TaxonomyMetaBoxFieldModel::GALLERY_TYPE,
                        TaxonomyMetaBoxFieldModel::IMAGE_TYPE,
                        TaxonomyMetaBoxFieldModel::LENGTH_TYPE,
                        TaxonomyMetaBoxFieldModel::LIST_TYPE,
                        TaxonomyMetaBoxFieldModel::NUMBER_TYPE,
                        TaxonomyMetaBoxFieldModel::PHONE_TYPE,
                        TaxonomyMetaBoxFieldModel::RADIO_TYPE,
                        TaxonomyMetaBoxFieldModel::RANGE_TYPE,
                        TaxonomyMetaBoxFieldModel::RATING_TYPE,
                        TaxonomyMetaBoxFieldModel::SELECT_TYPE,
                        TaxonomyMetaBoxFieldModel::SELECT_MULTI_TYPE,
                        TaxonomyMetaBoxFieldModel::TEXT_TYPE,
                        TaxonomyMetaBoxFieldModel::TEXTAREA_TYPE,
                        TaxonomyMetaBoxFieldModel::TIME_TYPE,
                        TaxonomyMetaBoxFieldModel::TOGGLE_TYPE,
                        TaxonomyMetaBoxFieldModel::VIDEO_TYPE,
                        TaxonomyMetaBoxFieldModel::WEIGHT_TYPE,
                        TaxonomyMetaBoxFieldModel::URL_TYPE,
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

            if(!$validator->validate($mandatory_keys, $args)){
                return false;
            }

            if(!TaxonomyRepository::exists($args['taxonomy'])){
                return false;
            }

            if(!MetaRepository::existsMetaBox([
                'belongsTo' => MetaTypes::TAXONOMY,
                'find' => $args['taxonomy'],
                'boxName' => $args['box_name'],
            ])){
                add_acpt_tax_meta_box($args['taxonomy'], $args['box_name']);
            }

            if(MetaRepository::existsMetaBoxField([
                'belongsTo' => MetaTypes::TAXONOMY,
                'find' => $args['taxonomy'],
                'boxName' => $args['box_name'],
                'fieldName' => $args['field_name']
            ])){

                $args['field_name'] = Strings::getUniqueName($args['field_name']);

                return add_acpt_tax_meta_field($args);

            } else {

                $box_model = MetaRepository::get([
                    'belongsTo' => MetaTypes::TAXONOMY,
                    'find' => $args['taxonomy'],
                    'boxName' => $args['box_name']
                ])[0];

                $meta_field_model = TaxonomyMetaBoxFieldModel::hydrateFromArray([
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
                                'belongsTo' => MetaTypes::TAXONOMY,
                                'find' => $args['taxonomy'],
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

                MetaRepository::saveMetaBoxField($meta_field_model);
            }

            return true;
        } catch (\Exception $exception){
            return false;
        }
    }
}

if( !function_exists('delete_acpt_tax_meta') )
{
    /**
     * Delete all meta settings for a taxonomy.
     *
     * @param $taxonomy
     *
     * @return bool
     */
    function delete_acpt_tax_meta($taxonomy)
    {
        try {
            MetaRepository::deleteAll([
                'belongsTo' => MetaTypes::TAXONOMY,
                'find' => $taxonomy
            ]);

            return true;

        } catch (\Exception $exception){
            return false;
        }
    }
}

if( !function_exists('delete_acpt_tax_meta_box') )
{
    /**
     * Delete meta box settings.
     *
     * @param string $taxonomy
     * @param string $box_name
     *
     * @return bool
     */
    function delete_acpt_tax_meta_box($taxonomy, $box_name)
    {
        try {
            $meta = MetaRepository::get([
                'belongsTo' => MetaTypes::TAXONOMY,
                'find' => $taxonomy,
                'boxName' => $box_name,
            ]);

            if(!isset($meta[0])){
                return false;
            }

            MetaRepository::deleteMetaBox([
                'belongsTo' => MetaTypes::TAXONOMY,
                'find' => $taxonomy,
                'metaBox' => $meta[0],
            ]);

            return true;

        } catch (\Exception $exception){
            return false;
        }
    }
}

if( !function_exists('delete_acpt_tax_meta_field') )
{
    /**
     * Delete meta field settings.
     *
     * @param string $taxonomy
     * @param string $box_name
     * @param string $field_name
     *
     * @return bool
     */
    function delete_acpt_tax_meta_field($taxonomy, $box_name, $field_name)
    {
        try {

            $meta_field = MetaRepository::getMetaFieldByName([
                'belongsTo' => MetaTypes::TAXONOMY,
                'find' => $taxonomy,
                'boxName' => $box_name,
                'fieldName' => $field_name,
            ]);

            if($meta_field === null){
                return false;
            }

            MetaRepository::deleteMetaField([
                'belongsTo' => MetaTypes::TAXONOMY,
                'find' => $taxonomy,
                'metaBoxField' => $meta_field,
            ]);

            return true;

        } catch (\Exception $exception){
            return false;
        }
    }
}

if( !function_exists('edit_acpt_tax_meta_box') )
{
    /**
     * Edit meta box settings.
     *
     * @param string $taxonomy
     * @param string $old_box_name
     * @param string $new_box_name
     *
     * @return bool
     */
    function edit_acpt_tax_meta_box($taxonomy, $old_box_name, $new_box_name)
    {
        try {

            if(!TaxonomyRepository::exists($taxonomy)){
                return false;
            }

            $meta_box_model = MetaRepository::getMetaBoxByName([
                'belongsTo' => MetaTypes::TAXONOMY,
                'find' => $taxonomy,
                'boxName' => $old_box_name,
            ]);

            if($meta_box_model === null){
                return  false;
            }

            $new_meta_box_model = TaxonomyMetaBoxModel::hydrateFromArray([
                'id' => $meta_box_model->getId(),
                'taxonomy' => $taxonomy,
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

if( !function_exists('edit_acpt_tax_meta_field') )
{
    /**
     * Edit meta field settings.
     *
     * @param array $args
     *
     * @return bool
     */
    function edit_acpt_tax_meta_field($args = [])
    {
        try {
            // validate array
            $mandatory_keys = [
                'taxonomy' => [
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
                        TaxonomyMetaBoxFieldModel::ADDRESS_TYPE,
                        TaxonomyMetaBoxFieldModel::COLOR_TYPE,
                        TaxonomyMetaBoxFieldModel::CURRENCY_TYPE,
                        TaxonomyMetaBoxFieldModel::DATE_TYPE,
                        TaxonomyMetaBoxFieldModel::EDITOR_TYPE,
                        TaxonomyMetaBoxFieldModel::EMAIL_TYPE,
                        TaxonomyMetaBoxFieldModel::EMBED_TYPE,
                        TaxonomyMetaBoxFieldModel::FILE_TYPE,
                        TaxonomyMetaBoxFieldModel::HTML_TYPE,
                        TaxonomyMetaBoxFieldModel::GALLERY_TYPE,
                        TaxonomyMetaBoxFieldModel::IMAGE_TYPE,
                        TaxonomyMetaBoxFieldModel::LENGTH_TYPE,
                        TaxonomyMetaBoxFieldModel::LIST_TYPE,
                        TaxonomyMetaBoxFieldModel::NUMBER_TYPE,
                        TaxonomyMetaBoxFieldModel::PHONE_TYPE,
                        TaxonomyMetaBoxFieldModel::SELECT_TYPE,
                        TaxonomyMetaBoxFieldModel::SELECT_MULTI_TYPE,
                        TaxonomyMetaBoxFieldModel::TEXT_TYPE,
                        TaxonomyMetaBoxFieldModel::TEXTAREA_TYPE,
                        TaxonomyMetaBoxFieldModel::TIME_TYPE,
                        TaxonomyMetaBoxFieldModel::TOGGLE_TYPE,
                        TaxonomyMetaBoxFieldModel::VIDEO_TYPE,
                        TaxonomyMetaBoxFieldModel::WEIGHT_TYPE,
                        TaxonomyMetaBoxFieldModel::URL_TYPE,
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

            if(!TaxonomyRepository::exists($args['taxonomy'])){
                return false;
            }

            if(!MetaRepository::existsMetaBoxField([
                'belongsTo' => MetaTypes::TAXONOMY,
                'find' => $args['taxonomy'],
                'boxName' => $args['box_name'],
                'fieldName' => $args['old_field_name']
            ])){
                return false;
            }

            $saved_meta_field_model = MetaRepository::getMetaFieldByName([
                'belongsTo' => MetaTypes::TAXONOMY,
                'find' => $args['taxonomy'],
                'boxName' => $args['box_name'],
                'fieldName' => $args['old_field_name']
            ]);

            $meta_field_model = TaxonomyMetaBoxFieldModel::hydrateFromArray([
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
                            'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
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
						        TaxonomyMetaBoxFieldModel::ADDRESS_TYPE,
						        TaxonomyMetaBoxFieldModel::COLOR_TYPE,
						        TaxonomyMetaBoxFieldModel::CURRENCY_TYPE,
						        TaxonomyMetaBoxFieldModel::DATE_TYPE,
						        TaxonomyMetaBoxFieldModel::EDITOR_TYPE,
						        TaxonomyMetaBoxFieldModel::EMAIL_TYPE,
						        TaxonomyMetaBoxFieldModel::EMBED_TYPE,
						        TaxonomyMetaBoxFieldModel::FILE_TYPE,
						        TaxonomyMetaBoxFieldModel::HTML_TYPE,
						        TaxonomyMetaBoxFieldModel::GALLERY_TYPE,
						        TaxonomyMetaBoxFieldModel::IMAGE_TYPE,
						        TaxonomyMetaBoxFieldModel::LENGTH_TYPE,
						        TaxonomyMetaBoxFieldModel::LIST_TYPE,
						        TaxonomyMetaBoxFieldModel::NUMBER_TYPE,
						        TaxonomyMetaBoxFieldModel::PHONE_TYPE,
						        TaxonomyMetaBoxFieldModel::REPEATER_TYPE,
						        TaxonomyMetaBoxFieldModel::SELECT_TYPE,
						        TaxonomyMetaBoxFieldModel::SELECT_MULTI_TYPE,
						        TaxonomyMetaBoxFieldModel::TEXT_TYPE,
						        TaxonomyMetaBoxFieldModel::TEXTAREA_TYPE,
						        TaxonomyMetaBoxFieldModel::TIME_TYPE,
						        TaxonomyMetaBoxFieldModel::TOGGLE_TYPE,
						        TaxonomyMetaBoxFieldModel::VIDEO_TYPE,
						        TaxonomyMetaBoxFieldModel::WEIGHT_TYPE,
						        TaxonomyMetaBoxFieldModel::URL_TYPE,
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

			        $child_field_model = TaxonomyMetaBoxFieldModel::hydrateFromArray([
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

if( !function_exists('get_acpt_tax_meta_objects') )
{
    /**
     * Returns the settings of all fields saved on a specific post.
     *
     * @param string $taxonomy
     *
     * @return array
     */
    function get_acpt_tax_meta_objects($taxonomy)
    {
        $meta = [];

        try {
            $models = MetaRepository::get([
                'belongsTo' => MetaTypes::TAXONOMY,
                'find' => $taxonomy,
            ]);

            foreach ($models as $box_model){
                $meta[] = get_acpt_tax_box_object($taxonomy, $box_model->getName());
            }

            return $meta;

        } catch (\Exception $exception){
            return $meta;
        }
    }
}

if( !function_exists('get_acpt_tax_box_object') )
{
    /**
     * Returns the settings of a specific box.
     *
     * @param string $taxonomy
     * @param string $box_name
     *
     * @return \stdClass|null
     */
    function get_acpt_tax_box_object($taxonomy, $box_name)
    {
        try {

            $meta = MetaRepository::get([
                'belongsTo' => MetaTypes::TAXONOMY,
                'find' => $taxonomy,
                'boxName' => $box_name,
            ]);

            if(!isset($meta[0])){
                return null;
            }

            $box_model = new \stdClass();
            $box_model->box_name = $meta[0]->getName();
            $box_model->taxonomy = $taxonomy;
            $box_model->belongs_to = MetaTypes::TAXONOMY;
            $box_model->fields = [];

            foreach ($meta[0]->getFields() as $field){
                $box_model->fields[] = get_acpt_tax_field_object($taxonomy, $box_name, $field->getName());
            }

            return $box_model;

        } catch (\Exception $exception){
            return null;
        }
    }
}

if( !function_exists('get_acpt_tax_field_object') )
{
    /**
     * Returns the settings of a specific field.
     *
     * @param string $taxonomy
     * @param string $box_name
     * @param string $field_name
     *
     * @return \stdClass|null
     */
    function get_acpt_tax_field_object($taxonomy, $box_name, $field_name)
    {
        try {
            $meta_field = MetaRepository::getMetaFieldByName([
                'belongsTo' => MetaTypes::TAXONOMY,
                'find' => $taxonomy,
                'boxName' => $box_name,
                'fieldName' => $field_name
            ]);

            if(!isset($meta_field)){
                return null;
            }

            $field_object = new \stdClass();
            $field_object->field_name = $meta_field->getName();
            $field_object->field_type = $meta_field->getType();
            $field_object->is_required = (bool)$meta_field->isRequired();
            $field_object->default_value = $meta_field->getDefaultValue();
            $field_object->description = $meta_field->getDescription();
            $field_object->options = [];
            $field_object->advanced_options = [];
            $field_object->visibility_conditions = [];

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

                if($render_condition_object->type === 'other_fields' and !empty($type['value'])){

                	$meta_field_model = MetaRepository::getMetaField([
                		'belongsTo' => MetaTypes::TAXONOMY,
		                'id' => $type['value'],
		                'lazy' => true,
	                ]);

                	if($meta_field_model !== null){
		                $render_condition_object->meta_field = $meta_field_model->getName();
	                }
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