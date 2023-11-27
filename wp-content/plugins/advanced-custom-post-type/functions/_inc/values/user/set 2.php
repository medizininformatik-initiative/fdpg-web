<?php

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\User\UserMetaBoxFieldModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Core\Validators\ArgumentsArrayValidator;
use ACPT\Costants\MetaTypes;
use ACPT\Includes\ACPT_DB;
use ACPT\Utils\Data\Sanitizer;
use ACPT\Utils\PHP\GeoLocation;
use ACPT\Utils\Wordpress\WPAttachment;

if( !function_exists('add_acpt_user_meta_field_value') ){

    /**
     * Add user meta field value.
     *
     * @param array $args
     *
     * @return bool
     */
    function add_acpt_user_meta_field_value(array $args = [])
    {
        try {

            $user = wp_get_current_user();

            if($user->ID === 0){
                return false;
            }
            
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
                    'value' => [
                            'required' => true,
                            'type' => 'boolean|string|integer|array|object',
                    ],
            ];

            $validator = new ArgumentsArrayValidator();

            if(!$validator->validate($mandatory_keys, $args)){
                return false;
            }
            
            $meta_field_model = MetaRepository::getMetaFieldByName([
                'belongsTo' => MetaTypes::USER,
                'boxName' => $args['box_name'],
                'fieldName' => $args['field_name']
            ]);

            if($meta_field_model === null){
                return false;
            }

            $meta_field_model_type = $meta_field_model->getType();
            $value = $args['value'];

            $saved_field_type = get_user_meta($user->ID, $meta_field_model->getDbName().'_type', true);
            if($saved_field_type !== $meta_field_model_type){
                if(!update_user_meta($user->ID, $meta_field_model->getDbName().'_type', $meta_field_model_type)){
                    return false;
                }
            }

            switch ($meta_field_model_type){

                // ADDRESS_TYPE
                case UserMetaBoxFieldModel::ADDRESS_TYPE:

                    $saved_field_value = get_user_meta($user->ID, $meta_field_model->getDbName(), true);

                    if($saved_field_value !== $value){

                        if(!update_user_meta($user->ID, $meta_field_model->getDbName(), Sanitizer::sanitizePostTypeRawData(UserMetaBoxFieldModel::ADDRESS_TYPE, $value))){
                            return false;
                        }

                        $coordinates = GeoLocation::getCoordinates($value);

                        if($coordinates){
                            if(!update_user_meta($user->ID, $meta_field_model->getDbName().'_lat', Sanitizer::sanitizePostTypeRawData(UserMetaBoxFieldModel::TEXT_TYPE, $coordinates['lat']))){
                                return false;
                            }

                            if(!update_user_meta($user->ID, $meta_field_model->getDbName().'_lng', Sanitizer::sanitizePostTypeRawData(UserMetaBoxFieldModel::TEXT_TYPE, $coordinates['lng']))){
                                return false;
                            }
                        }
                    }

                    break;

                // CURRENCY_TYPE
                case UserMetaBoxFieldModel::CURRENCY_TYPE:

                    $mandatory_keys = [
                            'amount' => [
                                    'required' => true,
                                    'type' => 'integer',
                            ],
                            'unit' => [
                                    'required' => true,
                                    'type' => 'string',
                            ],
                    ];

                    $validator = new ArgumentsArrayValidator();

                    if(!$validator->validate($mandatory_keys, $value)){
                        return false;
                    }

                    $amount = $value['amount'];
                    $unit = $value['unit'];

                    $saved_field_value = get_user_meta($user->ID, $meta_field_model->getDbName(), true);

                    if($saved_field_value !== $amount){

                        if(!update_user_meta($user->ID, $meta_field_model->getDbName(), Sanitizer::sanitizePostTypeRawData(UserMetaBoxFieldModel::CURRENCY_TYPE, $amount))){
                            return false;
                        }

                        if(!update_user_meta($user->ID, $meta_field_model->getDbName().'_currency', Sanitizer::sanitizePostTypeRawData(UserMetaBoxFieldModel::TEXT_TYPE, $unit))){
                            return false;
                        }
                    }

                    break;

	            // DATE_RANGE_TYPE
	            case UserMetaBoxFieldModel::DATE_RANGE_TYPE:

		            if(!is_array($value)){
			            return false;
		            }

		            $value = implode(" - ", $value);
		            if(!update_user_meta($user->ID, $meta_field_model->getDbName(), Sanitizer::sanitizePostTypeRawData($meta_field_model_type, $value))){
			            return false;
		            }

		            break;

                // EMBED_TYPE
                case UserMetaBoxFieldModel::EMBED_TYPE:

                    $embed = (new \WP_Embed())->shortcode([
                            'width' => 180,
                            'height' => 135,
                    ], $value);

                    if(!Strings::contains('<iframe', $embed)){
                        return false;
                    }

                    $saved_field_value = get_user_meta($user->ID, $meta_field_model->getDbName(), true);
                    if($saved_field_value !== $value){
                        if(!update_user_meta($user->ID, $meta_field_model->getDbName(), Sanitizer::sanitizePostTypeRawData($meta_field_model_type, $value))){
                            return false;
                        }
                    }

                    break;

                // FILE_TYPE
                case UserMetaBoxFieldModel::FILE_TYPE:

	                $mandatory_keys = [
		                'url' => [
			                'required' => true,
			                'type' => 'string',
		                ],
		                'label' => [
			                'required' => true,
			                'type' => 'string',
		                ],
	                ];

	                $validator = new ArgumentsArrayValidator();

	                if(!$validator->validate($mandatory_keys, $value)){
		                return false;
	                }

	                $url = $value['url'];
	                $label = $value['label'];

                    $wpAttachment = WPAttachment::fromUrl($url);
                    $isImage = $wpAttachment->isImage();
                    $isVideo = $wpAttachment->isVideo();
                    $fileData = $wpAttachment;

                    if($fileData->isEmpty()){
                        return false;
                    }

                    if($isImage or $isVideo){
                        return false;
                    }

	                $saved_field_value = get_user_meta($user->ID, $meta_field_model->getDbName(), true);
	                $saved_field_label = get_user_meta($user->ID, $meta_field_model->getDbName().'_label', true);

	                if($saved_field_value !== $url){
		                if(!update_user_meta($user->ID, $meta_field_model->getDbName(), Sanitizer::sanitizePostTypeRawData($meta_field_model_type, $url))){
			                return false;
		                }
	                }

	                if($saved_field_label !== $label){
		                if(!update_user_meta($user->ID, $meta_field_model->getDbName().'_label', Sanitizer::sanitizePostTypeRawData(UserMetaBoxFieldModel::TEXT_TYPE, $label))){
			                return false;
		                }
	                }

                    break;

                // GALLERY_TYPE
                case UserMetaBoxFieldModel::GALLERY_TYPE:

                    if(!is_array($value)){
                        return false;
                    }

                    foreach ($value as $image){
                        $wpAttachment = WPAttachment::fromUrl($image);
                        $isImage = $wpAttachment->isImage();

                        if(!$isImage){
                            return false;
                        }
                    }

                    $saved_field_value = get_user_meta($user->ID, $meta_field_model->getDbName(), true);
                    if($saved_field_value !== $value){
                        if(!update_user_meta($user->ID, $meta_field_model->getDbName(), Sanitizer::sanitizePostTypeRawData($meta_field_model_type, $value))){
                            return false;
                        }
                    }

                    break;

                // IMAGE_TYPE
                case UserMetaBoxFieldModel::IMAGE_TYPE:

                    $wpAttachment = WPAttachment::fromUrl($value);
                    $isImage = $wpAttachment->isImage();

                    if(!$isImage){
                        return false;
                    }

                    $saved_field_value = get_user_meta($user->ID, $meta_field_model->getDbName(), true);
                    if($saved_field_value !== $value){
                        if(!update_user_meta($user->ID, $meta_field_model->getDbName(), Sanitizer::sanitizePostTypeRawData($meta_field_model_type, $value))){
                            return false;
                        }
                    }

                    break;

                // LENGTH_TYPE
                case UserMetaBoxFieldModel::LENGTH_TYPE:

                    $mandatory_keys = [
                            'length' => [
                                    'required' => true,
                                    'type' => 'integer',
                            ],
                            'unit' => [
                                    'required' => true,
                                    'type' => 'string',
                            ],
                    ];

                    $validator = new ArgumentsArrayValidator();

                    if(!$validator->validate($mandatory_keys, $value)){
                        return false;
                    }

                    $length = $value['length'];
                    $unit = $value['unit'];

                    $saved_field_value = get_user_meta($user->ID, $meta_field_model->getDbName(), true);

                    if($saved_field_value !== $length){

                        if(!update_user_meta($user->ID, $meta_field_model->getDbName(), Sanitizer::sanitizePostTypeRawData(UserMetaBoxFieldModel::WEIGHT_TYPE, $length))){
                            return false;
                        }

                        if(!update_user_meta($user->ID, $meta_field_model->getDbName().'_length', Sanitizer::sanitizePostTypeRawData(UserMetaBoxFieldModel::TEXT_TYPE, $unit))){
                            return false;
                        }
                    }

                    break;

                case UserMetaBoxFieldModel::LIST_TYPE:

                    if(!is_array($value)){
                        return false;
                    }

                    foreach ($value as $item){
                        if(!add_acpt_meta_field_row_value([
                                'post_id' => $user->ID,
                                'box_name' => $args['box_name'],
                                'field_name' => $args['field_name'],
                                'value' => $item,
                        ])){
                            return false;
                        }
                    }

                    break;

                // SELECT_TYPE
                case UserMetaBoxFieldModel::RADIO_TYPE:
                case UserMetaBoxFieldModel::SELECT_TYPE:

                    if(in_array($value, $meta_field_model->getOptionValues())){
                        $saved_field_value = get_user_meta($user->ID, $meta_field_model->getDbName(), true);
                        if($saved_field_value !== $value){
                            if(!update_user_meta($user->ID, $meta_field_model->getDbName(), Sanitizer::sanitizePostTypeRawData($meta_field_model_type, $value))){
                                return false;
                            }
                        }

                        return true;
                    }

                    return false;

                    break;

                // SELECT_MULTI_TYPE
                case UserMetaBoxFieldModel::CHECKBOX_TYPE:
                case UserMetaBoxFieldModel::SELECT_MULTI_TYPE:

                    if(!is_array($value)){
                        return false;
                    }

                    $optionValues = $meta_field_model->getOptionValues();

                    foreach ($value as $item){
                        if(!in_array($item, $optionValues)){
                            return false;
                        }
                    }

                    $saved_field_value = get_user_meta($user->ID, $meta_field_model->getDbName(), true);
                    if($saved_field_value !== $value){
                        if(!update_user_meta($user->ID, $meta_field_model->getDbName(), Sanitizer::sanitizePostTypeRawData($meta_field_model_type, $value))){
                            return false;
                        }
                    }

                    break;

                // VIDEO_TYPE
                case UserMetaBoxFieldModel::VIDEO_TYPE:

                    $wpAttachment = WPAttachment::fromUrl($value);
                    $isVideo = $wpAttachment->isVideo();

                    if(!$isVideo){
                        return false;
                    }

                    $saved_field_value = get_user_meta($user->ID, $meta_field_model->getDbName(), true);
                    if($saved_field_value !== $value){
                        if(!update_user_meta($user->ID, $meta_field_model->getDbName(), Sanitizer::sanitizePostTypeRawData($meta_field_model_type, $value))){
                            return false;
                        }
                    }

                    break;

                // WEIGHT_TYPE
                case UserMetaBoxFieldModel::WEIGHT_TYPE:

                    $mandatory_keys = [
                            'weight' => [
                                    'required' => true,
                                    'type' => 'integer',
                            ],
                            'unit' => [
                                    'required' => true,
                                    'type' => 'string',
                            ],
                    ];

                    $validator = new ArgumentsArrayValidator();

                    if(!$validator->validate($mandatory_keys, $value)){
                        return false;
                    }

                    $weight = $value['weight'];
                    $unit = $value['unit'];

                    $saved_field_value = get_user_meta($user->ID, $meta_field_model->getDbName(), true);

                    if($saved_field_value !== $weight){

                        if(!update_user_meta($user->ID, $meta_field_model->getDbName(), Sanitizer::sanitizePostTypeRawData(UserMetaBoxFieldModel::WEIGHT_TYPE, $weight))){
                            return false;
                        }

                        if(!update_user_meta($user->ID, $meta_field_model->getDbName().'_weight', Sanitizer::sanitizePostTypeRawData(UserMetaBoxFieldModel::TEXT_TYPE, $unit))){
                            return false;
                        }
                    }

                    break;

                // URL_TYPE
                case UserMetaBoxFieldModel::URL_TYPE:

                    $mandatory_keys = [
                            'url' => [
                                    'required' => true,
                                    'type' => 'string',
                            ],
                            'label' => [
                                    'required' => true,
                                    'type' => 'string',
                            ],
                    ];

                    $validator = new ArgumentsArrayValidator();

                    if(!$validator->validate($mandatory_keys, $value)){
                        return false;
                    }

                    $url = $value['url'];
                    $label = $value['label'];

                    $saved_field_value = get_user_meta($user->ID, $meta_field_model->getDbName(), true);
                    if($saved_field_value !== $url){
                        if(!update_user_meta($user->ID, $meta_field_model->getDbName(), Sanitizer::sanitizePostTypeRawData($meta_field_model_type, $url))){
                            return false;
                        }

                        if(!update_user_meta($user->ID, $meta_field_model->getDbName().'_label', Sanitizer::sanitizePostTypeRawData(UserMetaBoxFieldModel::TEXT_TYPE, $label))){
                            return false;
                        }
                    }

                    break;

                // Default behaviour
                default:
                    $saved_field_value = get_user_meta($user->ID, $meta_field_model->getDbName(), true);
                    if($saved_field_value !== $value){
                        if(!update_user_meta($user->ID, $meta_field_model->getDbName(), Sanitizer::sanitizePostTypeRawData($meta_field_model_type, $value))){
                            return false;
                        }
                    }
            }

            return true;
        } catch (\Exception $exception){
            return false;
        }
    }
}

if( !function_exists('delete_acpt_user_meta_field_value') ){

    /**
     * Delete user meta field value.
     *
     * @param array $args
     *
     * @return bool
     */
    function delete_acpt_user_meta_field_value(array $args = [])
    {
        try {

            $user = wp_get_current_user();

            if($user->ID === 0){
                return false;
            }

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
            ];

            $validator = new ArgumentsArrayValidator();

            if(!$validator->validate($mandatory_keys, $args)){
                return false;
            }

            $meta_field_model = MetaRepository::getMetaFieldByName([
                'belongsTo' => MetaTypes::USER,
                'boxName' => $args['box_name'],
                'fieldName' => $args['field_name'],
            ]);

            if($meta_field_model === null){
                return false;
            }

            global $wpdb;

            $sql = "
                DELETE FROM `{$wpdb->prefix}usermeta`
                WHERE meta_key LIKE %s AND user_id = %d
            ";

            ACPT_DB::executeQueryOrThrowException($sql, [
                $meta_field_model->getDbName().'%',
                    $user->ID
            ]);

            return true;

        } catch (\Exception $exception){
            return false;
        }
    }
}

if( !function_exists('edit_acpt_user_meta_field_value') ){

    /**
     * Edit user meta field value.
     *
     * @param array $args
     *
     * @return bool
     */
    function edit_acpt_user_meta_field_value(array $args = [])
    {
        return add_acpt_user_meta_field_value($args);
    }
}

if( !function_exists('add_acpt_user_meta_field_row_value') ){

    /**
     * Add user meta field row value (only for Repeater and List fields).
     *
     * @param array $args
     *
     * @return bool
     */
    function add_acpt_user_meta_field_row_value(array $args = [])
    {
        try {

            $user = wp_get_current_user();

            if($user->ID === 0){
                return false;
            }

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
                    'value' => [
                            'required' => true,
                            'type' => 'string|integer|array|object',
                    ],
            ];

            $validator = new ArgumentsArrayValidator();

            if(!$validator->validate($mandatory_keys, $args)){
                return false;
            }

            $meta_field_model = MetaRepository::getMetaFieldByName([
                'belongsTo' => MetaTypes::USER,
                'boxName' => $args['box_name'],
                'fieldName' => $args['field_name'],
            ]);
            if($meta_field_model === null){
                return false;
            }

            $meta_field_model_type = $meta_field_model->getType();

            if($meta_field_model_type !== UserMetaBoxFieldModel::LIST_TYPE){
                return false;
            }

            $value = $args['value'];

            $saved_field_type = get_user_meta   ($user->ID, $meta_field_model->getDbName().'_type', true);
            $saved_field_value = get_user_meta($user->ID, $meta_field_model->getDbName(), true);

            if($saved_field_type !== $meta_field_model_type){
                if(!update_user_meta($user->ID, $meta_field_model->getDbName().'_type', $meta_field_model_type)){
                    return false;
                }
            }

            switch ($meta_field_model_type){

                // LIST_TYPE
                case UserMetaBoxFieldModel::LIST_TYPE:

                    if(empty($saved_field_value)){
                        $values = [$value];
                    } else {
                        array_push($saved_field_value, $value);
                        $values = $saved_field_value;
                    }

                    if(!update_user_meta($user->ID, $meta_field_model->getDbName(), Sanitizer::sanitizePostTypeRawData(UserMetaBoxFieldModel::LIST_TYPE, $values))){
                        return false;
                    }

                    break;
            }

            return true;

        } catch (\Exception $exception){
            return false;
        }
    }
}

if( !function_exists('delete_acpt_user_meta_field_row_value') ){

    /**
     * Delete user meta field row value (only for List field).
     * Use array index [0, 1, 2, 3 etc.....]
     *
     * @param array $args
     *
     * @return bool
     */
    function delete_acpt_user_meta_field_row_value(array $args = [])
    {
        try {

            $user = wp_get_current_user();

            if($user->ID === 0){
                return false;
            }
            
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
                    'index' => [
                            'required' => true,
                            'type' => 'integer',
                    ],
            ];

            $validator = new ArgumentsArrayValidator();

            if(!$validator->validate($mandatory_keys, $args)){
                return false;
            }
            
            $meta_field_model = MetaRepository::getMetaFieldByName([
                'belongsTo' => MetaTypes::USER,
                'boxName' => $args['box_name'],
                'fieldName' => $args['field_name'],
            ]);

            if($meta_field_model === null){
                return false;
            }

            $saved_field_value = get_user_meta($user->ID, $meta_field_model->getDbName(), true);
            $index = $args['index'];

            unset($saved_field_value[$index]);

            if(!update_user_meta($user->ID, $meta_field_model->getDbName(), Sanitizer::sanitizePostTypeRawData(UserMetaBoxFieldModel::LIST_TYPE, array_values($saved_field_value)))){
                return false;
            }

            $acpt_field = get_acpt_user_field([
                    'box_name' => $args['box_name'],
                    'field_name' => $args['field_name'],
            ]);

            if(empty($acpt_field)){
                delete_acpt_user_meta_field($args['box_name'], $args['field_name']);
            }

            return true;

        } catch (\Exception $exception){
            return false;
        }
    }
}

if( !function_exists('edit_acpt_user_meta_field_row_value') ){

    /**
     * Edit meta field row value (only for List field).
     * Use array index [0, 1, 2, 3 etc.....]
     *
     * @param array $args
     *
     * @return bool
     */
    function edit_acpt_user_meta_field_row_value(array $args = [])
    {
        try {

            $user = wp_get_current_user();

            if($user->ID === 0){
                return false;
            }
            
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
                    'value' => [
                            'required' => true,
                            'type' => 'string|integer|array|object',
                    ],
                    'index' => [
                            'required' => true,
                            'type' => 'integer',
                    ],
            ];

            $validator = new ArgumentsArrayValidator();

            if(!$validator->validate($mandatory_keys, $args)){
                return false;
            }
            
            $meta_field_model = MetaRepository::getMetaFieldByName([
                'belongsTo' => MetaTypes::USER,
                'boxName' => $args['box_name'],
                'fieldName' => $args['field_name'],
            ]);
            if($meta_field_model === null){
                return false;
            }

            $meta_field_model_type = $meta_field_model->getType();

            if($meta_field_model_type !== UserMetaBoxFieldModel::LIST_TYPE){
                return false;
            }

            $value = $args['value'];
            $index = $args['index'];

            $saved_field_type = get_user_meta($user->ID, $meta_field_model->getDbName().'_type', true);
            $saved_field_value = get_user_meta($user->ID, $meta_field_model->getDbName(), true);

            if($saved_field_type !== $meta_field_model_type){
                if(!update_user_meta($user->ID, $meta_field_model->getDbName().'_type', $meta_field_model_type)){
                    return false;
                }
            }

            switch ($meta_field_model_type){

                case UserMetaBoxFieldModel::LIST_TYPE:

                    if($saved_field_value[$index] !== $value){
                        $saved_field_value[$index] = $value;

                        if(!update_user_meta($user->ID, $meta_field_model->getDbName(), Sanitizer::sanitizePostTypeRawData(UserMetaBoxFieldModel::LIST_TYPE, $saved_field_value))){
                            return false;
                        }
                    }

                    break;
            }

            return true;

        } catch (\Exception $exception){
            return false;
        }
    }
}
