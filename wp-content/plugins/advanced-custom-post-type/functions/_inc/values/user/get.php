<?php

use ACPT\Core\Models\User\UserMetaBoxFieldModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Core\Validators\ArgumentsArrayValidator;
use ACPT\Core\Values\UserMetaValue;
use ACPT\Costants\MetaTypes;
use ACPT\Utils\Checker\FieldVisibilityChecker;

if( !function_exists('get_acpt_user_fields') ){

    /**
     * Returns an array of user field values (name => value) for a specific box.
     *
     * @param array $args
     *
     * @return array
     */
    function get_acpt_user_fields(array $args = [])
    {
        try {

            $user = wp_get_current_user();

            if($user->ID === 0){
                return [];
            }

            // validate array
            $mandatory_keys = [
                    'box_name' => [
                            'required' => true,
                            'type' => 'string',
                    ],
            ];

            $validator = new ArgumentsArrayValidator();

            if(!$validator->validate($mandatory_keys, $args)){
                return null;
            }

            $meta_box_model = MetaRepository::getMetaBoxByName([
                'belongsTo' => MetaTypes::USER,
                'boxName' => $args['box_name']
            ]);

            $values = [];

            foreach ($meta_box_model->getFields() as $meta_field_model){
                $post_meta_value = new UserMetaValue($meta_field_model, $user->ID);
                $values[$meta_field_model->getName()] = $post_meta_value->getValue();
            }

            return $values;

        } catch (\Exception $exception){
            return [];
        }
    }
}

if( !function_exists('get_acpt_user_field') ){

    /**
     * Returns the value of a specific user field.
     *
     * @param array $args
     *
     * @return mixed|null
     */
    function get_acpt_user_field(array $args = [])
    {
        try {

            // validate array
            $mandatory_keys = [
                'user_id' => [
	                'required' => false,
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

            $meta_field_model = MetaRepository::getMetaFieldByName([
                'belongsTo' => MetaTypes::USER,
                'boxName' => $args['box_name'],
                'fieldName' => $args['field_name'],
            ]);

            if($meta_field_model === null){
                return null;
            }

	        $user = wp_get_current_user();
            $user_id = (isset($args['user_id'])) ? $args['user_id'] : $user->ID;

	        if($user_id === null){
		        return [];
	        }

            $post_meta_value = new UserMetaValue($meta_field_model, $user_id);

            return $post_meta_value->getValue();

        } catch (\Exception $exception){
            return null;
        }
    }
}

if( !function_exists('acpt_user_field_has_rows') ){

    /**
     * Used to loop through a parent field's value.
     * Only usable for List field.
     *
     * @param array $args
     *
     * @return bool
     */
    function acpt_user_field_has_rows(array $args = [])
    {
        try {
            $get_acpt_field = get_acpt_user_field($args);

            $meta_field_model = MetaRepository::getMetaFieldByName([
                'belongsTo' => MetaTypes::USER,
                'boxName' => $args['box_name'],
                'fieldName' => $args['field_name']
            ]);

            if($meta_field_model->getType() !== UserMetaBoxFieldModel::LIST_TYPE){
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

if( !function_exists('is_acpt_user_field_visible') ){

	/**
	 * @param array $args
	 *
	 * @return bool
	 * @throws Exception
	 */
	function is_acpt_user_field_visible(array $args = [])
	{
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
			'fieldName' => $args['field_name']
		]);

		if($meta_field_model === null){
			return false;
		}

		return FieldVisibilityChecker::isFieldVisible($user->ID, $meta_field_model);
	}
}