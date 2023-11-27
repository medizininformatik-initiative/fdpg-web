<?php

use ACPT\Core\Helper\Strings;
use ACPT\Core\Helper\Uuid;
use ACPT\Core\Models\MetaField\MetaBoxFieldOptionModel;
use ACPT\Core\Models\User\UserMetaBoxFieldModel;
use ACPT\Core\Models\User\UserMetaBoxModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Core\Validators\ArgumentsArrayValidator;
use ACPT\Costants\MetaTypes;

if( !function_exists('add_acpt_user_meta_box') )
{
    /**
     * Add user meta box settings to custom post type.
     *
     * @param string $box_name
     *
     * @return bool
     */
    function add_acpt_user_meta_box($box_name)
    {
        try {
            $all_meta = MetaRepository::get([
                'belongsTo' => MetaTypes::USER,
            ]);

            if(MetaRepository::existsMetaBox([
                'belongsTo' => MetaTypes::USER,
                'boxName' => $box_name
            ])){

                return add_acpt_user_meta_box(Strings::getUniqueName($box_name));

            } else {
                $meta_box_model = UserMetaBoxModel::hydrateFromArray([
                    'id' => Uuid::v4(),
                    'name' => $box_name,
                    'sort' => count($all_meta)+1
                ]);
            }

            MetaRepository::saveMetaBox($meta_box_model);

            return true;

        } catch (\Exception $exception){
            return false;
        }
    }
}

if( !function_exists('add_acpt_user_meta_field') )
{
    /**
     * Add user meta field settings to custom post type.
     *
     * @param array $args
     *
     * @return bool
     */
    function add_acpt_user_meta_field($args = [])
    {
        try {
            // validate array
            $mandatory_keys = [
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
                                    UserMetaBoxFieldModel::ADDRESS_TYPE,
                                    UserMetaBoxFieldModel::COLOR_TYPE,
                                    UserMetaBoxFieldModel::CHECKBOX_TYPE,
                                    UserMetaBoxFieldModel::CURRENCY_TYPE,
                                    UserMetaBoxFieldModel::DATE_TYPE,
                                    UserMetaBoxFieldModel::DATE_RANGE_TYPE,
                                    UserMetaBoxFieldModel::EDITOR_TYPE,
                                    UserMetaBoxFieldModel::EMAIL_TYPE,
                                    UserMetaBoxFieldModel::EMBED_TYPE,
                                    UserMetaBoxFieldModel::FILE_TYPE,
                                    UserMetaBoxFieldModel::HTML_TYPE,
                                    UserMetaBoxFieldModel::GALLERY_TYPE,
                                    UserMetaBoxFieldModel::IMAGE_TYPE,
                                    UserMetaBoxFieldModel::LENGTH_TYPE,
                                    UserMetaBoxFieldModel::LIST_TYPE,
                                    UserMetaBoxFieldModel::NUMBER_TYPE,
                                    UserMetaBoxFieldModel::PHONE_TYPE,
                                    UserMetaBoxFieldModel::RADIO_TYPE,
                                    UserMetaBoxFieldModel::SELECT_TYPE,
                                    UserMetaBoxFieldModel::SELECT_MULTI_TYPE,
                                    UserMetaBoxFieldModel::TEXT_TYPE,
                                    UserMetaBoxFieldModel::TEXTAREA_TYPE,
                                    UserMetaBoxFieldModel::TIME_TYPE,
                                    UserMetaBoxFieldModel::TOGGLE_TYPE,
                                    UserMetaBoxFieldModel::VIDEO_TYPE,
                                    UserMetaBoxFieldModel::WEIGHT_TYPE,
                                    UserMetaBoxFieldModel::URL_TYPE,
                            ],
                    ],
                    'show_in_archive' => [
                            'required' => true,
                            'type' => 'boolean',
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

            if(!$validator->validate($mandatory_keys, $args)){
                return false;
            }

            if(!MetaRepository::existsMetaBox([
                'belongsTo' => MetaTypes::USER,
                'boxName' => $args['box_name'],
            ])){
                add_acpt_user_meta_box($args['box_name']);
            }

            if(MetaRepository::existsMetaBoxField([
                'belongsTo' => MetaTypes::USER,
                'boxName' => $args['box_name'],
                'fieldName' => $args['field_name'],
            ])){

                $args['field_name'] = Strings::getUniqueName($args['field_name']);

                return add_acpt_user_meta_field($args);

            } else {

                $box_model = MetaRepository::get([
                    'belongsTo' => MetaTypes::USER,
                    'boxName' => $args['box_name']
                ])[0];

                $meta_field_model = UserMetaBoxFieldModel::hydrateFromArray([
                        'id' => Uuid::v4(),
                        'metaBox' => $box_model,
                        'name' => $args['field_name'],
                        'type' => $args['field_type'],
                        'showInArchive' => $args['show_in_archive'],
                        'required' => $args['required'],
                        'defaultValue' => isset($args['default_value']) ? $args['default_value'] : null,
                        'description' => isset($args['description']) ? $args['description'] : null,
                        'sort' => count($box_model->getFields())+1
                ]);

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

                MetaRepository::saveMetaBoxField($meta_field_model);
            }

            return true;

        } catch (\Exception $exception){
            return false;
        }
    }
}

if( !function_exists('delete_acpt_user_meta') )
{
    /**
     * Delete all user meta settings.
     *
     * @return bool
     */
    function delete_acpt_user_meta()
    {
        try {
            MetaRepository::deleteAll([
                'belongsTo' => MetaTypes::USER,
            ]);

            return true;

        } catch (\Exception $exception){
            return false;
        }
    }
}

if( !function_exists('delete_acpt_user_meta_box') )
{
    /**
     * Delete user meta box settings.
     *
     * @param string $box_name
     *
     * @return bool
     */
    function delete_acpt_user_meta_box($box_name)
    {
        try {
            $meta = MetaRepository::get([
                'belongsTo' => MetaTypes::USER,
                'boxName' => $box_name
            ]);

            if(!isset($meta[0])){
                return false;
            }

            MetaRepository::deleteMetaBoxById([
                'belongsTo' => MetaTypes::USER,
                'id' => $meta[0]->getId()
            ]);

            return true;

        } catch (\Exception $exception){
            return false;
        }
    }
}

if( !function_exists('delete_acpt_user_meta_field') )
{
    /**
     * Delete user meta field settings.
     *
     * @param string $box_name
     * @param string $field_name
     *
     * @return bool
     */
    function delete_acpt_user_meta_field($box_name, $field_name)
    {
        try {
            $meta_field = MetaRepository::getMetaFieldByName([
                'belongsTo' => MetaTypes::USER,
                'boxName' => $box_name,
                'fieldName' => $field_name,
            ]);

            if($meta_field === null){
                return false;
            }

            MetaRepository::deleteMetaField([
                'metaBoxField' => $meta_field,
                'belongsTo' => MetaTypes::USER,
            ]);

            return true;

        } catch (\Exception $exception){
            return false;
        }
    }
}

if( !function_exists('edit_acpt_user_meta_box') )
{
    /**
     * Edit user meta box settings.
     *
     * @param string $old_box_name
     * @param string $new_box_name
     *
     * @return bool
     */
    function edit_acpt_user_meta_box($old_box_name, $new_box_name)
    {
        try {
            $meta_box_model = MetaRepository::getMetaBoxByName([
                'belongsTo' => MetaTypes::USER,
                'boxName' => $old_box_name
            ]);

            if($meta_box_model === null){
                return  false;
            }

            $new_meta_box_model = UserMetaBoxModel::hydrateFromArray([
                    'id' => $meta_box_model->getId(),
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

if( !function_exists('edit_acpt_user_meta_field') )
{
    /**
     * Edit user meta field settings.
     *
     * @param array $args
     *
     * @return bool
     */
    function edit_acpt_user_meta_field($args = [])
    {
        try {
            // validate array
            $mandatory_keys = [
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
                                    UserMetaBoxFieldModel::ADDRESS_TYPE,
                                    UserMetaBoxFieldModel::COLOR_TYPE,
                                    UserMetaBoxFieldModel::CURRENCY_TYPE,
                                    UserMetaBoxFieldModel::DATE_TYPE,
                                    UserMetaBoxFieldModel::EDITOR_TYPE,
                                    UserMetaBoxFieldModel::EMAIL_TYPE,
                                    UserMetaBoxFieldModel::EMBED_TYPE,
                                    UserMetaBoxFieldModel::FILE_TYPE,
                                    UserMetaBoxFieldModel::HTML_TYPE,
                                    UserMetaBoxFieldModel::GALLERY_TYPE,
                                    UserMetaBoxFieldModel::IMAGE_TYPE,
                                    UserMetaBoxFieldModel::LENGTH_TYPE,
                                    UserMetaBoxFieldModel::LIST_TYPE,
                                    UserMetaBoxFieldModel::NUMBER_TYPE,
                                    UserMetaBoxFieldModel::PHONE_TYPE,
                                    UserMetaBoxFieldModel::SELECT_TYPE,
                                    UserMetaBoxFieldModel::SELECT_MULTI_TYPE,
                                    UserMetaBoxFieldModel::TEXT_TYPE,
                                    UserMetaBoxFieldModel::TEXTAREA_TYPE,
                                    UserMetaBoxFieldModel::TIME_TYPE,
                                    UserMetaBoxFieldModel::TOGGLE_TYPE,
                                    UserMetaBoxFieldModel::VIDEO_TYPE,
                                    UserMetaBoxFieldModel::WEIGHT_TYPE,
                                    UserMetaBoxFieldModel::URL_TYPE,
                            ],
                    ],
                    'show_in_archive' => [
                            'required' => true,
                            'type' => 'boolean',
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

            if(!$validator->validate($mandatory_keys, $args)){
                return false;
            }

            if(!MetaRepository::existsMetaBoxField([
                'belongsTo' => MetaTypes::USER,
                'boxName' => $args['box_name'],
                'fieldName' => $args['old_field_name'],
            ])){
                return false;
            }

            $saved_meta_field_model = MetaRepository::getMetaFieldByName([
                'belongsTo' => MetaTypes::USER,
                'boxName' => $args['box_name'],
                'fieldName' => $args['old_field_name'],
            ]);

            $meta_field_model = UserMetaBoxFieldModel::hydrateFromArray([
                    'id' => $saved_meta_field_model->getId(),
                    'metaBox' => $saved_meta_field_model->getMetaBox(),
                    'name' => $args['field_name'],
                    'type' => $args['field_type'],
                    'showInArchive' => $args['show_in_archive'],
                    'required' => $args['required'],
                    'defaultValue' => isset($args['default_value']) ? $args['default_value'] : null,
                    'description' => isset($args['description']) ? $args['description'] : null,
                    'sort' => $saved_meta_field_model->getSort()
            ]);

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

                    $meta_field_option_model = new MetaBoxFieldOptionModel(
                            Uuid::v4(),
                            $meta_field_model,
                            $option['label'],
                            $option['value'],
                            $index+1
                    );

                    $meta_field_model->addOption($meta_field_option_model);
                }
            }

            MetaRepository::saveMetaBoxField($meta_field_model);

            return true;

        } catch (\Exception $exception){
            return false;
        }
    }
}

if( !function_exists('get_acpt_user_meta_objects') )
{
    /**
     * Returns the settings of all user fields saved.
     *
     * @return array
     */
    function get_acpt_user_meta_objects()
    {
        $meta = [];

        try {
            $models = MetaRepository::get([
                'belongsTo' => MetaTypes::USER,
            ]);

            foreach ($models as $box_model){
                $meta[] = get_acpt_user_box_object($box_model->getName());
            }

            return $meta;

        } catch (\Exception $exception){
            return $meta;
        }
    }
}

if( !function_exists('get_acpt_user_box_object') )
{
    /**
     * Returns the settings of a specific user box.
     *
     * @param string $box_name
     *
     * @return \stdClass|null
     */
    function get_acpt_user_box_object($box_name)
    {
        try {

            $meta = MetaRepository::get([
                'belongsTo' => MetaTypes::USER,
                'boxName' => $box_name
            ]);

            if(!isset($meta[0])){
                return null;
            }

            $box_model = new \stdClass();
            $box_model->box_name = $meta[0]->getName();
	        $box_model->belongs_to = MetaTypes::USER;
            $box_model->fields = [];

            foreach ($meta[0]->getFields() as $field){
                $box_model->fields[] = get_acpt_user_field_object($box_name, $field->getName());
            }

            return $box_model;

        } catch (\Exception $exception){
            return null;
        }
    }
}

if( !function_exists('get_acpt_user_field_object') )
{
    /**
     * Returns the settings of a specific user field.
     *
     * @param string $box_name
     * @param string $field_name
     *
     * @return \stdClass|null
     */
    function get_acpt_user_field_object($box_name, $field_name)
    {
        try {
            $meta_field = MetaRepository::getMetaFieldByName([
                'belongsTo' => MetaTypes::USER,
                'boxName' => $box_name,
                'fieldName' => $field_name,
            ]);

            if(!isset($meta_field)){
                return null;
            }

            $field_object = new \stdClass();
            $field_object->field_name = $meta_field->getName();
            $field_object->field_type = $meta_field->getType();
            $field_object->show_in_archive = $meta_field->isShowInArchive();
            $field_object->is_required = (bool)$meta_field->isRequired();
            $field_object->default_value = $meta_field->getDefaultValue();
            $field_object->description = $meta_field->getDescription();
            $field_object->options = [];

            foreach ($meta_field->getOptions() as $option){

                $option_model = new \stdClass();
                $option_model->label = $option->getLabel();
                $option_model->value = $option->getValue();

                $field_object->options[] = $option_model;
            }

            return $field_object;

        } catch (\Exception $exception){
            return null;
        }
    }
}
