<?php

use ACPT\Core\Generators\CustomPostTypeGenerator;
use ACPT\Core\Helper\Uuid;
use ACPT\Core\Models\CustomPostType\CustomPostTypeModel;
use ACPT\Core\Repository\CustomPostTypeRepository;
use ACPT\Core\Validators\ArgumentsArrayValidator;

if( !function_exists('register_acpt_post_type') ){

    /**
     * Register a new custom post type.
     *
     * @param array $args
     *
     * @return \WP_Post_Type|null
     */
    function register_acpt_post_type($args = [])
    {
        try {
            // validate array
            $mandatory_keys = [
                'post_name' => [
                    'required' => true,
                    'type' => 'string',
                ],
                'singular_label' => [
                    'required' => true,
                    'type' => 'string',
                ],
                'plural_label' => [
                    'required' => true,
                    'type' => 'string',
                ],
                'icon' => [
                    'required' => true,
                    'type' => 'string',
                ],
                'supports' => [
                    'required' => true,
                    'type' => 'array',
                ],
                'labels' => [
                    'required' => true,
                    'type' => 'array',
                ],
                'settings' => [
                    'required' => true,
                    'type' => 'array',
                ]
            ];

            $validator = new ArgumentsArrayValidator();

            if(!$validator->validate($mandatory_keys, $args)){
                return null;
            }

            // save ACPT definition
            $id = (CustomPostTypeRepository::exists($args["post_name"])) ? CustomPostTypeRepository::getId($args["post_name"]) : Uuid::v4();
            $post_type_model = CustomPostTypeModel::hydrateFromArray([
                'id' => $id,
                'name' => $args["post_name"],
                'singular' => $args["singular_label"],
                'plural' => $args["plural_label"],
                'icon' => $args["icon"],
                'native' => false,
                'supports' => $args['supports'],
                'labels' => $args['labels'],
                'settings' => $args['settings']
            ]);

            CustomPostTypeRepository::save($post_type_model);

            // generate CPT in WP tables
            $customPostTypeGenerator = new CustomPostTypeGenerator(
                $post_type_model->getName(),
                $post_type_model->isNative(),
                array_merge(
                    [
                        'supports' => $post_type_model->getSupports(),
                        'label' => $post_type_model->getPlural(),
                        'labels' => $post_type_model->getLabels(),
                        "menu_icon" => $post_type_model->getIcon()
                    ],
                    $post_type_model->getSettings()
                )
            );

            $customPostTypeGenerator->registerPostType();

            return get_post_type_object($args["post_name"]);

        } catch (\Exception $exception){
            return null;
        }
    }
}

if( !function_exists('delete_acpt_post_type') ){

    /**
     * Delete a custom post type.
     *
     * @param string $post_type
     * @param bool   $delete_posts
     *
     * @return bool
     */
    function delete_acpt_post_type($post_type, $delete_posts = false)
    {
        if(!CustomPostTypeRepository::exists($post_type)){
            return false;
        }

        try {
            CustomPostTypeRepository::delete($post_type, $delete_posts);
            unregister_post_type($post_type);

            return true;

        } catch (\Exception $exception){
            return false;
        }
    }
}