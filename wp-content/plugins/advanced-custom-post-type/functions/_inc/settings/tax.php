<?php

use ACPT\Core\Helper\Uuid;
use ACPT\Core\Models\Taxonomy\TaxonomyModel;
use ACPT\Core\Repository\CustomPostTypeRepository;
use ACPT\Core\Repository\TaxonomyRepository;
use ACPT\Core\Validators\ArgumentsArrayValidator;

if( !function_exists('register_acpt_taxonomy') ){

    /**
     * Register a new custom taxonomy.
     *
     * @param array $args
     *
     * @return bool
     */
    function register_acpt_taxonomy($args = [])
    {
        try {
            // validate array
            $mandatory_keys = [
                    'slug' => [
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
                    'labels' => [
                            'required' => true,
                            'type' => 'array',
                    ],
                    'settings' => [
                            'required' => true,
                            'type' => 'array',
                    ],
                    'post_types' => [
                            'required' => true,
                            'type' => 'array',
                    ]
            ];

            $validator = new ArgumentsArrayValidator();

            if(!$validator->validate($mandatory_keys, $args)){
                return false;
            }

            // Save ACPT definition
            $id = (TaxonomyRepository::exists($args["slug"])) ? TaxonomyRepository::getId($args["slug"]) : Uuid::v4();
            $taxonomy_model = TaxonomyModel::hydrateFromArray([
                    'id' => $id,
                    'slug' => $args["slug"],
                    'singular' => $args["singular_label"],
                    'plural' => $args["plural_label"],
                    'labels' => $args['labels'],
                    'native' => false,
                    'settings' => $args['settings']
            ]);
            TaxonomyRepository::save($taxonomy_model);

            // Assoc post types
            foreach ($args['post_types'] as $post_type){

                // Assoc ACPT post type definition
                $post_id = CustomPostTypeRepository::getId($post_type);
                TaxonomyRepository::assocToPostType($post_id, $id);

                $modelOptions = array_merge(
                        [
                                'singular_label' => $taxonomy_model->getSingular(),
                                'label' => $taxonomy_model->getPlural(),
                                'labels' => $taxonomy_model->getLabels(),
                        ],
                        $taxonomy_model->getSettings()
                );

                $options = array_merge(
                        [
                                "hierarchical" => false,
                                "label" => $args["slug"],
                                "singular_label" => $args["plural_label"],
                                "show_ui" => true,
                                "query_var" => true,
                                'show_admin_column' => true,
                                "show_in_rest" => true,
                                "rewrite" => ["slug" => strtolower($args["slug"])]
                        ], $modelOptions
                );

                register_taxonomy(strtolower($args["slug"]), $post_type, $options);

                return true;
            }
        } catch (\Exception $exception){
            return false;
        }
    }
}

if( !function_exists('delete_acpt_taxonomy') ){

    /**
     * Delete a custom taxonomy.
     *
     * @param string $taxonomy
     *
     * @return bool
     */
    function delete_acpt_taxonomy($taxonomy)
    {
        try {
            TaxonomyRepository::delete($taxonomy);
            unregister_taxonomy($taxonomy);

            return true;

        } catch (\Exception $exception){
            return false;
        }
    }
}

if( !function_exists('assoc_acpt_taxonomy_to_acpt_post') ){

    /**
     * Associate a custom taxonomy to a custom post type.
     *
     * @param string $taxonomy
     * @param string $post_type
     *
     * @return bool
     */
    function assoc_acpt_taxonomy_to_acpt_post($taxonomy, $post_type)
    {
        try {
            $taxonomy_id = TaxonomyRepository::getId($taxonomy);
            $post_id = CustomPostTypeRepository::getId($post_type);

            TaxonomyRepository::assocToPostType($post_id, $taxonomy_id);

            $taxonomy_model = TaxonomyRepository::get(['id' => $taxonomy_id])[0];

            $modelOptions = array_merge(
                    [
                            'singular_label' => $taxonomy_model->getSingular(),
                            'label' => $taxonomy_model->getPlural(),
                            'labels' => $taxonomy_model->getLabels(),
                    ],
                    $taxonomy_model->getSettings()
            );

            $options = array_merge(
                    [
                            "hierarchical" => false,
                            "label" => $taxonomy_model->getSlug(),
                            "singular_label" => $taxonomy_model->getPlural(),
                            "show_ui" => true,
                            "query_var" => true,
                            'show_admin_column' => true,
                            "show_in_rest" => true,
                            "rewrite" => ["slug" => strtolower($taxonomy_model->getSlug())]
                    ], $modelOptions
            );

            register_taxonomy(strtolower($taxonomy_model->getSlug()), $post_type, $options);

        } catch (\Exception $exception){
            return false;
        }
    }
}

if( !function_exists('remove_assoc_acpt_taxonomy_to_acpt_post') ){

    /**
     * Remove association between a custom taxonomy to a custom post type.
     *
     * @param string $taxonomy
     * @param string $post_type
     *
     * @return bool
     */
    function remove_assoc_acpt_taxonomy_from_acpt_post($taxonomy, $post_type)
    {
        try {
            $taxonomy_id = TaxonomyRepository::getId($taxonomy);
            $post_id = CustomPostTypeRepository::getId($post_type);

            TaxonomyRepository::removeAssocPost($post_id, $taxonomy_id);
            unregister_taxonomy($taxonomy);

        } catch (\Exception $exception){
            return false;
        }
    }
}