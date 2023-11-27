<?php

namespace ACPT\Integrations\WPGraphQL;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Models\CustomPostType\CustomPostTypeModel;
use ACPT\Core\Models\WooCommerce\WooCommerceProductDataFieldModel;
use ACPT\Core\Models\WooCommerce\WooCommerceProductDataModel;
use ACPT\Core\Repository\CustomPostTypeRepository;
use ACPT\Integrations\AbstractIntegration;

class ACPT_WPGraphQL extends AbstractIntegration
{
    /**
     * @inheritDoc
     */
    protected function isActive()
    {
        return is_plugin_active( 'wp-graphql/wp-graphql.php' );
    }

    /**
     * @inheritDoc
     */
    protected function runIntegration()
    {
        $postTypes = CustomPostTypeRepository::get([]);

        /** @var CustomPostTypeModel $postType */
        foreach ($postTypes as $postType){

            $this->saveSettingsForNativePosts($postType);
            $settings = $postType->getSettings();

            if(isset($settings['show_in_graphql']) and $settings['show_in_graphql'] === true){

                $singleName = $settings["graphql_single_name"];
                $pluralName = $settings["graphql_plural_name"];

                add_action( 'graphql_register_types', function() use ($singleName, $pluralName, $postType) {

                    register_graphql_field('RootQueryTo'.ucfirst($singleName).'ConnectionWhereArgs',
            'query', [
                            'type' => 'AcptQuery',
                            'description' => __('The meta query object to filter by', 'your-textdomain'),
                        ]
                    );

                    $this->registerAcptTypes();
                    register_graphql_field( $singleName, 'acpt', $this->acptFieldSettings($postType));
                    register_graphql_field( $pluralName, 'acpt', $this->acptFieldSettings($postType));
                });

                add_filter('graphql_post_object_connection_query_args', function ($query_args, $source, $args, $context, $info) {

                    $query = $args['where']['query'];

                    if (isset($query)) {
                        $query_args['meta_query'] = $query;
                    }

                    return $query_args;
                }, 10, 5);
            }
        }
    }

    /**
     * @param CustomPostTypeModel $postType
     *
     * @throws \Exception
     */
    private function saveSettingsForNativePosts(CustomPostTypeModel $postType)
    {
        if($postType->isNative() and !isset($settings['show_in_graphql'])){

            $settings['show_in_graphql'] = true;
            $settings['graphql_single_name'] = strtolower($postType->getSingular());
            $settings['graphql_plural_name'] = strtolower($postType->getPlural());
            $postType->modifySettings($settings);

            CustomPostTypeRepository::save($postType);
        }
    }

    /**
     * Register ACPT types and subtypes
     */
    private function registerAcptTypes()
    {
        // acpt
        register_graphql_object_type( 'Acpt', [
            'description' => __( "ACPT meta data", 'your-textdomain' ),
            'fields' => [
                'meta' => [
                    'type' => [ 'list_of' => 'AcptMetaBox' ],
                    'description' => __( 'List of all meta', 'your-textdomain' ),
                ],
                'product_data' => [
                    'type' => [ 'list_of' => 'WooCommerceProductData' ],
                    'description' => __( 'List of all product data (only for WooCommerce product post type)', 'your-textdomain' ),
                ],
            ],
        ] );

        // box
        register_graphql_object_type( 'AcptMetaBox', [
            'description' => __( "ACPT meta box", 'your-textdomain' ),
            'fields' => [
                'meta_box' => [
                    'type' => 'String',
                    'description' => __( 'The name of the meta box', 'your-textdomain' ),
                ],
                'meta_fields' => [
                    'type' => [ 'list_of' => 'AcptMetaField' ]
                ],
            ],
        ]);

        // field
        register_graphql_object_type( 'AcptMetaField', [
            'description' => __( "ACPT meta field", 'your-textdomain' ),
            'fields' => [
                'name' => [
                    'type' => 'String',
                    'description' => __( 'The name of the meta field', 'your-textdomain' ),
                ],
                'type' => [
                    'type' => 'String',
                    'description' => __( 'The type of the meta field', 'your-textdomain' ),
                ],
                'values' => [
                    'type' => [ 'list_of' => 'String' ],
                    'description' => __( 'The value of the meta field', 'your-textdomain' ),
                ],
            ],
        ]);

        register_graphql_object_type( 'WooCommerceProductData', [
            'description' => __( "WooCommerce product data", 'your-textdomain' ),
            'fields' => [
                'name' => [
                    'type' => 'String',
                    'description' => __( 'The name of the meta box', 'your-textdomain' ),
                ],
                'fields' => [
                    'type' => [ 'list_of' => 'WooCommerceProductDataField' ]
                ],
            ],
        ]);

        register_graphql_object_type( 'WooCommerceProductDataField', [
            'description' => __( "WooCommerce product data field", 'your-textdomain' ),
            'fields' => [
                'name' => [
                    'type' => 'String',
                    'description' => __( 'The name of the meta field', 'your-textdomain' ),
                ],
                'type' => [
                    'type' => 'String',
                    'description' => __( 'The type of the meta field', 'your-textdomain' ),
                ],
                'values' => [
                    'type' => [ 'list_of' => 'String' ],
                    'description' => __( 'The value of the meta field', 'your-textdomain' ),
                ],
            ],
        ]);

        // @TODO enums?
        // https://github.com/wp-graphql/wp-graphql-meta-query/blob/develop/wp-graphql-meta-query.php

        // query object
        register_graphql_input_type( 'AcptQuery', [
            'description' => __( "Query object", 'your-textdomain' ),
            'fields' => [
                'meta_query' => [
                    'type' => 'AcptMetaQuery',
                    'description' => __( 'Meta query', 'your-textdomain' ),
                ],
            ]
        ]);

        // meta_query
        register_graphql_input_type( 'AcptMetaQuery', [
            'description' => __( "Meta query object", 'your-textdomain' ),
            'fields' => [
                'relation' => [
                    'type' => 'String',
                    'description' => __( 'Meta query relation', 'your-textdomain' ),
                ],
                'elements' => [
                    'type' => [ 'list_of' => 'AcptMetaQueryElement' ],
                    'description' => __( 'Meta query element object', 'your-textdomain' ),
                ],
            ],
        ]);

        // meta_query element
        register_graphql_input_type( 'AcptMetaQueryElement', [
            'description' => __( "Meta query element object", 'your-textdomain' ),
            'fields' => [
                'type' => [
                    'type' => 'String',
                    'description' => __( 'Meta query element type', 'your-textdomain' ),
                ],
                'key' => [
                    'type' => 'String',
                    'description' => __( 'Meta query element key', 'your-textdomain' ),
                ],
                'value' => [
                    'type' => 'String',
                    'description' => __( 'Meta query element value', 'your-textdomain' ),
                ],
                'value_num'  => [
                    'type' => 'Integer',
                    'description' => __( 'Meta query element value (numeric)', 'your-textdomain' ),
                ],
                'compare' => [
                    'type' => 'String',
                    'description' => __( 'Meta query element compare operator', 'your-textdomain' ),
                ],
            ],
        ]);
    }

    /**
     * ACPT field settings
     *
     * @param CustomPostTypeModel $postType
     * @see https://www.wpgraphql.com/2020/03/11/registering-graphql-fields-with-arguments
     *
     * @return array
     */
    private function acptFieldSettings( CustomPostTypeModel $postType)
    {
        return [
            'type' => 'Acpt',
            'resolve' => function( $post, $args, $context, $info ) use ($postType) {

                $postId = (int)$post->databaseId;

                $meta = [];
                $meta['meta'] = [];
                $meta['product_data'] = [];

                foreach ($postType->getMetaBoxes() as $metaBox){

                    $metaBoxArray = [];
                    $metaBoxArray['meta_box'] = $metaBox->getName();
                    $metaBoxArray['meta_fields'] = [];

                    foreach ($metaBox->getFields() as $field){

                        $listTypes = [
                            CustomPostTypeMetaBoxFieldModel::GALLERY_TYPE,
                            CustomPostTypeMetaBoxFieldModel::LIST_TYPE,
                            CustomPostTypeMetaBoxFieldModel::SELECT_MULTI_TYPE,
                        ];
                        $key = Strings::toDBFormat($metaBox->getName()) . '_' . Strings::toDBFormat($field->getName());
                        $values = (in_array($field->getType(), $listTypes)) ? get_post_meta($postId, $key, true) : [get_post_meta($postId, $key, true)];

                        $metaBoxArray['meta_fields'][] = [
                            'name' => $field->getName(),
                            'type' => $field->getType(),
                            'values' => (!empty($values)) ? $values : [],
                        ];
                    }

                    $meta['meta'][] = $metaBoxArray;
                }

                // WooCommerce
                if($postType->isWooCommerce()){

                    /** @var WooCommerceProductDataModel $productDatum */
                    foreach ($postType->getWoocommerceProductData() as $productDatum){

                        $productDatumArray = [];
                        $productDatumArray['name'] = $productDatum->getName();
                        $productDatumArray['fields'] = [];

                        /** @var WooCommerceProductDataFieldModel $field */
                        foreach ($productDatum->getFields() as $field){

                            $listTypes = [
                                WooCommerceProductDataFieldModel::SELECT_TYPE,
                                WooCommerceProductDataFieldModel::RADIO_TYPE,
                            ];

                            $key = $field->getDbName();
                            $values = (in_array($field->getType(), $listTypes)) ? get_post_meta($postId, $key, true) : [get_post_meta($postId, $key, true)];

                            $productDatumArray['fields'][] = [
                                    'name' => $field->getName(),
                                    'type' => $field->getType(),
                                    'values' => (!empty($values)) ? $values : [],
                            ];
                        }

                        $meta['product_data'][] = $productDatumArray;
                    }
                }

                return $meta;
            },
        ];
    }
}