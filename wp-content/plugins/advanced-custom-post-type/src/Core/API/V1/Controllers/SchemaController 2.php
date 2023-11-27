<?php

namespace ACPT\Core\API\V1\Controllers;

use ACPT\Core\Helper\Strings;
use ACPT\Core\JSON\CustomPostTypeMetaBoxSchema;
use ACPT\Core\JSON\CustomPostTypeSchema;
use ACPT\Core\JSON\OptionPageMetaBoxSchema;
use ACPT\Core\JSON\OptionPageSchema;
use ACPT\Core\JSON\QueryFilterSchema;
use ACPT\Core\JSON\QueryResultSchema;
use ACPT\Core\JSON\TaxonomyMetaBoxSchema;
use ACPT\Core\JSON\TaxonomySchema;
use ACPT\Core\JSON\UserMetaBoxSchema;
use ACPT\Core\JSON\WooCommerceProductDataFieldSchema;
use ACPT\Core\JSON\WooCommerceProductDataSchema;
use ACPT\Core\Repository\CustomPostTypeRepository;

class SchemaController extends AbstractController
{
    const SWAGGER_VERSION = '2.0';

    /**
     * @return string
     */
    private function getBasePath()
    {
        $restUrl = get_rest_url();

        if(Strings::contains('?rest_route=', $restUrl)){
            return '/?rest_route=/acpt/v1';
        }

        return '/wp-json/acpt/v1';
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function schema()
    {
        return [
            'swagger' => self::SWAGGER_VERSION,
            'host' => (isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : 'http://localhost:83',
            'basePath' => $this->getBasePath(),
            'tags' => $this->getTags(),
            'schemes' => $this->getSchemas(),
            'paths' => $this->getPaths(),
            'definitions' => $this->getModelDefinitions(),
            'securityDefinitions' => $this->getSecurityDefinitions(),
            'security' => $this->getSecurity(),
            'externalDocs' => $this->getExternalDocs(),
        ];
    }

    /**
     * Tag definitions
     *
     * @return array
     */
    private function getTags()
    {
        return [
            [
                'name' => 'cpt',
                'description' => 'Manage ACPT custom post types',
                'externalDocs' => $this->getExternalDocs(),
            ],
            [
                'name' => 'cpt-meta',
                'description' => 'Manage custom post types meta',
                'externalDocs' => $this->getExternalDocs(),
            ],
	        [
		        'name' => 'option-page',
		        'description' => 'Manage option pages',
		        'externalDocs' => $this->getExternalDocs(),
	        ],
	        [
		        'name' => 'option-page-meta',
		        'description' => 'Manage option pages meta',
		        'externalDocs' => $this->getExternalDocs(),
	        ],
            [
                'name' => 'taxonomy',
                'description' => 'Manage taxonomies',
                'externalDocs' => $this->getExternalDocs(),
            ],
	        [
		        'name' => 'taxonomy-meta',
		        'description' => 'Manage taxonomies meta',
		        'externalDocs' => $this->getExternalDocs(),
	        ],
            [
                'name' => 'user-meta',
                'description' => 'Manage user meta',
                'externalDocs' => $this->getExternalDocs(),
            ],
	        [
		        'name' => 'woocommerce',
		        'description' => 'WooCommerce product data',
		        'externalDocs' => $this->getExternalDocs(),
	        ],
            [
                'name' => 'filter',
                'description' => 'Filter posts by query',
                'externalDocs' => $this->getExternalDocs(),
            ],
        ];
    }

    /**
     * http schemas
     *
     * @return array
     */
    private function getSchemas()
    {
        return [
            'http',
            'https',
        ];
    }

    /**
     * Path definitions
     *
     * @return array
     * @throws \Exception
     */
    private function getPaths()
    {
        $paths = [
            '/cpt' => [
                'get' => [
                    'tags' => [
                        'cpt',
                    ],
                    'summary' => 'Get all cpts',
                    'description' => 'Use this endpoint to fetch all the registered cpts',
                    'operationId' => 'getAllCpt',
                    'consumes' => [
                        'application/json',
                    ],
                    'produces' => [
                        'application/json',
                    ],
                    'parameters' => [
                        [
                            'name' => 'page',
                            'in' => 'query',
                            'description' => 'current page',
                            'required' => false,
                            'type' => 'integer',
                        ],
                        [
                            'name' => 'per_page',
                            'in' => 'query',
                            'description' => 'choose pagination size (Max 100)',
                            'required' => false,
                            'type' => 'integer',
                            'minimum' => 1,
                            'maximum' => 100,
                            'default' => 20,
                        ],
                    ],
                    'responses' => [
                        200 => [
                            'description' => 'successful operation',
                            'schema' => $this->getPaginatedResponse('#/definitions/CustomPostType'),
                        ],
                    ],
                ],
                'post' => [
                    'tags' => [
                        'cpt',
                    ],
                    'summary' => 'Create a cpt',
                    'description' => 'Use this endpoint to create a new cpt',
                    'operationId' => 'createCpt',
                    'consumes' => [
                        'application/json',
                    ],
                    'produces' => [
                        'application/json',
                    ],
                    'parameters' => [
                        [
                            'name' => 'body',
                            'in' => 'body',
                            'description' => 'The cpt model',
                            'schema' => [
                                '$ref' => '#/definitions/CustomPostType',
                            ],
                        ],
                    ],
                    'responses' => [
                        201 => [
                            'description' => 'successful operation',
                            'schema' => [
                                '$ref' => '#/definitions/CreateResponse',
                            ],
                        ],
                        500 => [
                            'description' => 'error',
                            'schema' => [
                                '$ref' => '#/definitions/ErrorResponse',
                            ],
                        ],
                    ],
                ],
            ],
            '/cpt/{slug}' => [
                'get' => [
                    'tags' => [
                        'cpt',
                    ],
                    'summary' => 'Get a single cpt',
                    'description' => 'Use this endpoint to fetch a single registered cpt',
                    'operationId' => 'getCpt',
                    'consumes' => [
                        'application/json',
                    ],
                    'produces' => [
                        'application/json',
                    ],
                    'parameters' => [
                        [
                            'name' => 'slug',
                            'in' => 'path',
                            'description' => 'the slug of cpt',
                            'required' => true,
                            'type' => 'string',
                        ],
                    ],
                    'responses' => [
                        200 => [
                            'description' => 'successful operation',
                            'schema' => [
                                '$ref' => '#/definitions/CustomPostType',
                            ],
                        ],
                        404 => [
                            'description' => 'record not found',
                            'schema' => [
                                '$ref' => '#/definitions/NotFoundResponse',
                            ],
                        ],
                    ],
                ],
                'put' => [
                    'tags' => [
                        'cpt',
                    ],
                    'summary' => 'Update a single cpt',
                    'description' => 'Use this endpoint to update a single registered cpt',
                    'operationId' => 'updateCpt',
                    'consumes' => [
                        'application/json',
                    ],
                    'produces' => [
                        'application/json',
                    ],
                    'parameters' => [
                        [
                            'name' => 'slug',
                            'in' => 'path',
                            'description' => 'the slug of cpt',
                            'required' => true,
                            'type' => 'string',
                        ],
                        [
                            'name' => 'body',
                            'in' => 'body',
                            'description' => 'The cpt model',
                            'schema' => [
                                    '$ref' => '#/definitions/CustomPostType',
                            ],
                        ],
                    ],
                    'responses' => [
                        200 => [
                            'description' => 'successful operation',
                            'schema' => [
                                '$ref' => '#/definitions/CustomPostType',
                            ],
                        ],
                        404 => [
                            'description' => 'record not found',
                            'schema' => [
                                '$ref' => '#/definitions/NotFoundResponse',
                            ],
                        ],
                        500 => [
                            'description' => 'error',
                            'schema' => [
                                '$ref' => '#/definitions/ErrorResponse',
                            ],
                        ],
                    ],
                ],
                'delete' => [
                    'tags' => [
                        'cpt',
                    ],
                    'summary' => 'Delete a single cpt',
                    'description' => 'Use this endpoint to delete a single registered cpt',
                    'operationId' => 'deleteCpt',
                    'consumes' => [
                        'application/json',
                    ],
                    'produces' => [
                        'application/json',
                    ],
                    'parameters' => [
                        [
                            'name' => 'slug',
                            'in' => 'path',
                            'description' => 'the slug of cpt',
                            'required' => true,
                            'type' => 'string',
                        ],
                    ],
                    'responses' => [
                        200 => [
                            'description' => 'successful operation',
                            'schema' => [
                                '$ref' => '#/definitions/DeleteResponse',
                            ],
                        ],
                        404 => [
                            'description' => 'record not found',
                            'schema' => [
                                '$ref' => '#/definitions/NotFoundResponse',
                            ],
                        ],
                        500 => [
                            'description' => 'error',
                            'schema' => [
                                '$ref' => '#/definitions/ErrorResponse',
                            ],
                        ],
                    ],
                ],
            ],
            '/meta/{slug}' => [
                'get' => [
                    'tags' => [
                        'cpt-meta',
                    ],
                    'summary' => 'Get the meta boxes associated to a single cpt',
                    'description' => 'Use this endpoint to fetch the meta boxes associated to a single cpt',
                    'operationId' => 'getMetaBox',
                    'consumes' => [
                        'application/json',
                    ],
                    'produces' => [
                        'application/json',
                    ],
                    'parameters' => [
                        [
                            'name' => 'slug',
                            'in' => 'path',
                            'description' => 'the slug of cpt',
                            'required' => true,
                            'type' => 'string',
                        ],
                    ],
                    'responses' => [
                        200 => [
                            'description' => 'successful operation',
                            'schema' => [
                                'type' => 'array',
                                'items' => [
                                    '$ref' => '#/definitions/MetaBox'
                                ]
                            ],
                        ],
                        404 => [
                            'description' => 'record not found',
                            'schema' => [
                                '$ref' => '#/definitions/NotFoundResponse',
                            ],
                        ],
                    ],
                ],
                'post' => [
                    'tags' => [
                        'cpt-meta',
                    ],
                    'summary' => 'Create meta boxes',
                    'description' => 'Use this endpoint to create meta boxes for a registered cpt',
                    'operationId' => 'createMetaBox',
                    'consumes' => [
                        'application/json',
                    ],
                    'produces' => [
                        'application/json',
                    ],
                    'parameters' => [
                        [
                            'name' => 'body',
                            'in' => 'body',
                            'description' => 'Array of meta box models',
                            'type' => 'array',
                            'items' => [
                                '$ref' => '#/definitions/MetaBox',
                            ],
                        ],
                    ],
                    'responses' => [
                        201 => [
                            'description' => 'successful operation',
                            'schema' => [
                                '$ref' => '#/definitions/CreateMetaboxResponse',
                            ],
                        ],
                        500 => [
                            'description' => 'error',
                            'schema' => [
                                '$ref' => '#/definitions/ErrorResponse',
                            ],
                        ],
                    ],
                ],
                'put' => [
                    'tags' => [
                        'cpt-meta',
                    ],
                    'summary' => 'Update meta boxes',
                    'description' => 'Use this endpoint to update meta boxes for a registered cpt',
                    'operationId' => 'updateMetaBox',
                    'consumes' => [
                        'application/json',
                    ],
                    'produces' => [
                        'application/json',
                    ],
                    'parameters' => [
                        [
                            'name' => 'body',
                            'in' => 'body',
                            'description' => 'Array of meta box models',
                            'type' => 'array',
                            'items' => [
                                '$ref' => '#/definitions/MetaBox',
                            ],
                        ],
                    ],
                    'responses' => [
                        200 => [
                            'description' => 'successful operation',
                            'schema' => [
                                '$ref' => '#/definitions/CreateMetaboxResponse',
                            ],
                        ],
                        500 => [
                            'description' => 'error',
                            'schema' => [
                                '$ref' => '#/definitions/ErrorResponse',
                            ],
                        ],
                    ],
                ],
                'delete' => [
                    'tags' => [
                        'cpt-meta',
                    ],
                    'summary' => 'Delete a all meta boxes',
                    'description' => 'Use this endpoint to delete all meta boxes associated to a cpt',
                    'operationId' => 'deleteAllMetaBox',
                    'consumes' => [
                        'application/json',
                    ],
                    'produces' => [
                        'application/json',
                    ],
                    'parameters' => [
                        [
                            'name' => 'slug',
                            'in' => 'path',
                            'description' => 'the slug of cpt',
                            'required' => true,
                            'type' => 'string',
                        ],
                    ],
                    'responses' => [
                        200 => [
                            'description' => 'successful operation',
                            'schema' => [
                                '$ref' => '#/definitions/DeleteMultiResponse',
                            ],
                        ],
                        404 => [
                            'description' => 'record not found',
                            'schema' => [
                                '$ref' => '#/definitions/NotFoundResponse',
                            ],
                        ],
                        500 => [
                            'description' => 'error',
                            'schema' => [
                                '$ref' => '#/definitions/ErrorResponse',
                            ],
                        ],
                    ],
                ],
            ],
            '/meta/{slug}/{id}' => [
                'get' => [
                    'tags' => [
                        'cpt-meta',
                    ],
                    'summary' => 'Get a single meta box',
                    'description' => 'Use this endpoint to fetch a single meta box',
                    'operationId' => 'getSingleMetaBox',
                    'consumes' => [
                        'application/json',
                    ],
                    'produces' => [
                        'application/json',
                    ],
                    'parameters' => [
                        [
                            'name' => 'slug',
                            'in' => 'path',
                            'description' => 'the slug of cpt',
                            'required' => true,
                            'type' => 'string',
                        ],
                        [
                            'name' => 'id',
                            'in' => 'path',
                            'description' => 'the id of meta box',
                            'required' => true,
                            'type' => 'string',
                            'format' => 'uuid',
                        ],
                    ],
                    'responses' => [
                        200 => [
                            'description' => 'successful operation',
                            'schema' => [
                                '$ref' => '#/definitions/MetaBox',
                            ],
                        ],
                        404 => [
                            'description' => 'record not found',
                            'schema' => [
                                '$ref' => '#/definitions/NotFoundResponse',
                            ],
                        ],
                    ],
                ],
                'delete' => [
                    'tags' => [
                        'cpt-meta',
                    ],
                    'summary' => 'Delete a single meta box',
                    'description' => 'Use this endpoint to delete a single meta box',
                    'operationId' => 'deleteMetaBox',
                    'consumes' => [
                        'application/json',
                    ],
                    'produces' => [
                        'application/json',
                    ],
                    'parameters' => [
                        [
                            'name' => 'slug',
                            'in' => 'path',
                            'description' => 'the slug of cpt',
                            'required' => true,
                            'type' => 'string',
                        ],
                        [
                            'name' => 'id',
                            'in' => 'path',
                            'description' => 'the id of meta box',
                            'required' => true,
                            'type' => 'string',
                            'format' => 'uuid',
                        ],
                    ],
                    'responses' => [
                        200 => [
                            'description' => 'successful operation',
                            'schema' => [
                                '$ref' => '#/definitions/DeleteResponse',
                            ],
                        ],
                        404 => [
                            'description' => 'record not found',
                            'schema' => [
                                '$ref' => '#/definitions/NotFoundResponse',
                            ],
                        ],
                        500 => [
                            'description' => 'error',
                            'schema' => [
                                '$ref' => '#/definitions/ErrorResponse',
                            ],
                        ],
                    ],
                ],
            ],
            '/option-page' => [
	            'get' => [
		            'tags' => [
			            'option-page',
		            ],
		            'summary' => 'Get all option pages',
		            'description' => 'Use this endpoint to fetch all the registered option pages',
		            'operationId' => 'getAllOptionPages',
		            'consumes' => [
			            'application/json',
		            ],
		            'produces' => [
			            'application/json',
		            ],
		            'parameters' => [
			            [
				            'name' => 'page',
				            'in' => 'query',
				            'description' => 'current page',
				            'required' => false,
				            'type' => 'integer',
			            ],
			            [
				            'name' => 'per_page',
				            'in' => 'query',
				            'description' => 'choose pagination size (Max 100)',
				            'required' => false,
				            'type' => 'integer',
				            'minimum' => 1,
				            'maximum' => 100,
				            'default' => 20,
			            ],
		            ],
		            'responses' => [
			            200 => [
				            'description' => 'successful operation',
				            'schema' => $this->getPaginatedResponse('#/definitions/OptionPage'),
			            ],
		            ],
	            ],
	            'post' => [
		            'tags' => [
			            'option-page',
		            ],
		            'summary' => 'Create a option page',
		            'description' => 'Use this endpoint to create a new option page',
		            'operationId' => 'createOptionPage',
		            'consumes' => [
			            'application/json',
		            ],
		            'produces' => [
			            'application/json',
		            ],
		            'parameters' => [
			            [
				            'name' => 'body',
				            'in' => 'body',
				            'description' => 'The taxonomy model',
				            'schema' => [
					            '$ref' => '#/definitions/OptionPage',
				            ],
			            ],
		            ],
		            'responses' => [
			            201 => [
				            'description' => 'successful operation',
				            'schema' => [
					            '$ref' => '#/definitions/CreateResponse',
				            ],
			            ],
			            500 => [
				            'description' => 'error',
				            'schema' => [
					            '$ref' => '#/definitions/ErrorResponse',
				            ],
			            ],
		            ],
	            ],
            ],
            '/option-page/{slug}' => [
	            'get' => [
		            'tags' => [
			            'option-page',
		            ],
		            'summary' => 'Get a single option page',
		            'description' => 'Use this endpoint to fetch a single registered option page',
		            'operationId' => 'getOptionPage',
		            'consumes' => [
			            'application/json',
		            ],
		            'produces' => [
			            'application/json',
		            ],
		            'parameters' => [
			            [
				            'name' => 'slug',
				            'in' => 'path',
				            'description' => 'the slug of the option page',
				            'required' => true,
				            'type' => 'string',
			            ],
		            ],
		            'responses' => [
			            200 => [
				            'description' => 'successful operation',
				            'schema' => [
					            '$ref' => '#/definitions/OptionPage',
				            ],
			            ],
			            404 => [
				            'description' => 'record not found',
				            'schema' => [
					            '$ref' => '#/definitions/NotFoundResponse',
				            ],
			            ],
		            ],
	            ],
	            'put' => [
		            'tags' => [
			            'option-page',
		            ],
		            'summary' => 'Update a single option page',
		            'description' => 'Use this endpoint to update a single registered option page',
		            'operationId' => 'updateOptionPage',
		            'consumes' => [
			            'application/json',
		            ],
		            'produces' => [
			            'application/json',
		            ],
		            'parameters' => [
			            [
				            'name' => 'slug',
				            'in' => 'path',
				            'description' => 'the slug of option page',
				            'required' => true,
				            'type' => 'string',
			            ],
			            [
				            'name' => 'body',
				            'in' => 'body',
				            'description' => 'The option page model',
				            'schema' => [
					            '$ref' => '#/definitions/Taxonomy',
				            ],
			            ],
		            ],
		            'responses' => [
			            200 => [
				            'description' => 'successful operation',
				            'schema' => [
					            '$ref' => '#/definitions/OptionPage',
				            ],
			            ],
			            404 => [
				            'description' => 'record not found',
				            'schema' => [
					            '$ref' => '#/definitions/NotFoundResponse',
				            ],
			            ],
			            500 => [
				            'description' => 'error',
				            'schema' => [
					            '$ref' => '#/definitions/ErrorResponse',
				            ],
			            ],
		            ],
	            ],
	            'delete' => [
		            'tags' => [
			            'option-page',
		            ],
		            'summary' => 'Delete a single option page',
		            'description' => 'Use this endpoint to delete a single registered option page',
		            'operationId' => 'deleteOptionPage',
		            'consumes' => [
			            'application/json',
		            ],
		            'produces' => [
			            'application/json',
		            ],
		            'parameters' => [
			            [
				            'name' => 'slug',
				            'in' => 'path',
				            'description' => 'the slug of option page',
				            'required' => true,
				            'type' => 'string',
			            ],
		            ],
		            'responses' => [
			            200 => [
				            'description' => 'successful operation',
				            'schema' => [
					            '$ref' => '#/definitions/DeleteResponse',
				            ],
			            ],
			            404 => [
				            'description' => 'record not found',
				            'schema' => [
					            '$ref' => '#/definitions/NotFoundResponse',
				            ],
			            ],
			            500 => [
				            'description' => 'error',
				            'schema' => [
					            '$ref' => '#/definitions/ErrorResponse',
				            ],
			            ],
		            ],
	            ],
            ],
            '/option-page-meta/{slug}' => [
	            'get' => [
		            'tags' => [
			            'option-page-meta',
		            ],
		            'summary' => 'Get the meta boxes associated to a single option page',
		            'description' => 'Use this endpoint to fetch the meta boxes associated to a single option page',
		            'operationId' => 'getMetaBox',
		            'consumes' => [
			            'application/json',
		            ],
		            'produces' => [
			            'application/json',
		            ],
		            'parameters' => [
			            [
				            'name' => 'slug',
				            'in' => 'path',
				            'description' => 'the slug of option page',
				            'required' => true,
				            'type' => 'string',
			            ],
		            ],
		            'responses' => [
			            200 => [
				            'description' => 'successful operation',
				            'schema' => [
					            'type' => 'array',
					            'items' => [
						            '$ref' => '#/definitions/OptionPageMetaBox'
					            ]
				            ],
			            ],
			            404 => [
				            'description' => 'record not found',
				            'schema' => [
					            '$ref' => '#/definitions/NotFoundResponse',
				            ],
			            ],
		            ],
	            ],
	            'post' => [
		            'tags' => [
			            'option-page-meta',
		            ],
		            'summary' => 'Create option page meta boxes',
		            'description' => 'Use this endpoint to create meta boxes for a registered option page-',
		            'operationId' => 'createMetaBox',
		            'consumes' => [
			            'application/json',
		            ],
		            'produces' => [
			            'application/json',
		            ],
		            'parameters' => [
			            [
				            'name' => 'body',
				            'in' => 'body',
				            'description' => 'Array of meta box models',
				            'type' => 'array',
				            'items' => [
					            '$ref' => '#/definitions/OptionPageMetaBox',
				            ],
			            ],
		            ],
		            'responses' => [
			            201 => [
				            'description' => 'successful operation',
				            'schema' => [
					            '$ref' => '#/definitions/CreateMetaboxResponse',
				            ],
			            ],
			            500 => [
				            'description' => 'error',
				            'schema' => [
					            '$ref' => '#/definitions/ErrorResponse',
				            ],
			            ],
		            ],
	            ],
	            'put' => [
		            'tags' => [
			            'option-page-meta',
		            ],
		            'summary' => 'Update option page meta boxes',
		            'description' => 'Use this endpoint to update meta boxes for a registered option page-',
		            'operationId' => 'updateMetaBox',
		            'consumes' => [
			            'application/json',
		            ],
		            'produces' => [
			            'application/json',
		            ],
		            'parameters' => [
			            [
				            'name' => 'body',
				            'in' => 'body',
				            'description' => 'Array of meta box models',
				            'type' => 'array',
				            'items' => [
					            '$ref' => '#/definitions/OptionPageMetaBox',
				            ],
			            ],
		            ],
		            'responses' => [
			            200 => [
				            'description' => 'successful operation',
				            'schema' => [
					            '$ref' => '#/definitions/CreateMetaboxResponse',
				            ],
			            ],
			            500 => [
				            'description' => 'error',
				            'schema' => [
					            '$ref' => '#/definitions/ErrorResponse',
				            ],
			            ],
		            ],
	            ],
	            'delete' => [
		            'tags' => [
			            'option-page-meta',
		            ],
		            'summary' => 'Delete a all option page meta boxes',
		            'description' => 'Use this endpoint to delete all meta boxes associated to a option page-',
		            'operationId' => 'deleteAllMetaBox',
		            'consumes' => [
			            'application/json',
		            ],
		            'produces' => [
			            'application/json',
		            ],
		            'parameters' => [
			            [
				            'name' => 'slug',
				            'in' => 'path',
				            'description' => 'the slug of option page-',
				            'required' => true,
				            'type' => 'string',
			            ],
		            ],
		            'responses' => [
			            200 => [
				            'description' => 'successful operation',
				            'schema' => [
					            '$ref' => '#/definitions/DeleteMultiResponse',
				            ],
			            ],
			            404 => [
				            'description' => 'record not found',
				            'schema' => [
					            '$ref' => '#/definitions/NotFoundResponse',
				            ],
			            ],
			            500 => [
				            'description' => 'error',
				            'schema' => [
					            '$ref' => '#/definitions/ErrorResponse',
				            ],
			            ],
		            ],
	            ],
            ],
            '/option-page-meta/{slug}/{id}' => [
	            'get' => [
		            'tags' => [
			            'option-page-meta',
		            ],
		            'summary' => 'Get a single option page meta box',
		            'description' => 'Use this endpoint to fetch a single option page meta box',
		            'operationId' => 'getSingleMetaBox',
		            'consumes' => [
			            'application/json',
		            ],
		            'produces' => [
			            'application/json',
		            ],
		            'parameters' => [
			            [
				            'name' => 'slug',
				            'in' => 'path',
				            'description' => 'the slug of option page',
				            'required' => true,
				            'type' => 'string',
			            ],
			            [
				            'name' => 'id',
				            'in' => 'path',
				            'description' => 'the id of meta box',
				            'required' => true,
				            'type' => 'string',
				            'format' => 'uuid',
			            ],
		            ],
		            'responses' => [
			            200 => [
				            'description' => 'successful operation',
				            'schema' => [
					            '$ref' => '#/definitions/OptionPageMetaBox',
				            ],
			            ],
			            404 => [
				            'description' => 'record not found',
				            'schema' => [
					            '$ref' => '#/definitions/NotFoundResponse',
				            ],
			            ],
		            ],
	            ],
	            'delete' => [
		            'tags' => [
			            'option-page-meta',
		            ],
		            'summary' => 'Delete a single option page meta box',
		            'description' => 'Use this endpoint to delete a single option page meta box',
		            'operationId' => 'deleteMetaBox',
		            'consumes' => [
			            'application/json',
		            ],
		            'produces' => [
			            'application/json',
		            ],
		            'parameters' => [
			            [
				            'name' => 'slug',
				            'in' => 'path',
				            'description' => 'the slug of option page',
				            'required' => true,
				            'type' => 'string',
			            ],
			            [
				            'name' => 'id',
				            'in' => 'path',
				            'description' => 'the id of meta box',
				            'required' => true,
				            'type' => 'string',
				            'format' => 'uuid',
			            ],
		            ],
		            'responses' => [
			            200 => [
				            'description' => 'successful operation',
				            'schema' => [
					            '$ref' => '#/definitions/DeleteResponse',
				            ],
			            ],
			            404 => [
				            'description' => 'record not found',
				            'schema' => [
					            '$ref' => '#/definitions/NotFoundResponse',
				            ],
			            ],
			            500 => [
				            'description' => 'error',
				            'schema' => [
					            '$ref' => '#/definitions/ErrorResponse',
				            ],
			            ],
		            ],
	            ],
            ],
	        '/taxonomy-meta/{slug}' => [
	        	'get' => [
			        'tags' => [
				        'taxonomy-meta',
			        ],
			        'summary' => 'Get the meta boxes associated to a single taxonomy',
			        'description' => 'Use this endpoint to fetch the meta boxes associated to a single taxonomy-',
			        'operationId' => 'getTaxonomyMetaBox',
			        'consumes' => [
				        'application/json',
			        ],
			        'produces' => [
				        'application/json',
			        ],
			        'parameters' => [
				        [
					        'name' => 'slug',
					        'in' => 'path',
					        'description' => 'the slug of taxonomy',
					        'required' => true,
					        'type' => 'string',
				        ],
			        ],
			        'responses' => [
				        200 => [
					        'description' => 'successful operation',
					        'schema' => [
						        'type' => 'array',
						        'items' => [
							        '$ref' => '#/definitions/TaxonomyMetaBox'
						        ]
					        ],
				        ],
				        404 => [
					        'description' => 'record not found',
					        'schema' => [
						        '$ref' => '#/definitions/NotFoundResponse',
					        ],
				        ],
			        ],
		        ],
	        	'post' => [
			        'tags' => [
				        'taxonomy-meta',
			        ],
			        'summary' => 'Create taxonomy meta boxes',
			        'description' => 'Use this endpoint to create meta boxes for a registered taxonomy',
			        'operationId' => 'createTaxonomyMetaBox',
			        'consumes' => [
				        'application/json',
			        ],
			        'produces' => [
				        'application/json',
			        ],
			        'parameters' => [
				        [
					        'name' => 'body',
					        'in' => 'body',
					        'description' => 'Array of taxonomy meta box models',
					        'type' => 'array',
					        'items' => [
						        '$ref' => '#/definitions/TaxonomyMetaBox',
					        ],
				        ],
			        ],
			        'responses' => [
				        201 => [
					        'description' => 'successful operation',
					        'schema' => [
						        '$ref' => '#/definitions/CreateMetaboxResponse',
					        ],
				        ],
				        500 => [
					        'description' => 'error',
					        'schema' => [
						        '$ref' => '#/definitions/ErrorResponse',
					        ],
				        ],
			        ],
		        ],
	        	'put' => [
			        'tags' => [
				        'taxonomy-meta',
			        ],
			        'summary' => 'Update taxonomy meta boxes',
			        'description' => 'Use this endpoint to update meta boxes for a registered taxonomy',
			        'operationId' => 'updateTaxonomyMetaBox',
			        'consumes' => [
				        'application/json',
			        ],
			        'produces' => [
				        'application/json',
			        ],
			        'parameters' => [
				        [
					        'name' => 'body',
					        'in' => 'body',
					        'description' => 'Array of taxonomy meta box models',
					        'type' => 'array',
					        'items' => [
						        '$ref' => '#/definitions/TaxonomyMetaBox',
					        ],
				        ],
			        ],
			        'responses' => [
				        200 => [
					        'description' => 'successful operation',
					        'schema' => [
						        '$ref' => '#/definitions/CreateMetaboxResponse',
					        ],
				        ],
				        500 => [
					        'description' => 'error',
					        'schema' => [
						        '$ref' => '#/definitions/ErrorResponse',
					        ],
				        ],
			        ],
		        ],
	        	'delete' => [
			        'tags' => [
				        'taxonomy-meta',
			        ],
			        'summary' => 'Delete a all taxonomy meta boxes',
			        'description' => 'Use this endpoint to delete all meta boxes associated to a taxonomy',
			        'operationId' => 'deleteAllTaxonomyMetaBox',
			        'consumes' => [
				        'application/json',
			        ],
			        'produces' => [
				        'application/json',
			        ],
			        'parameters' => [
				        [
					        'name' => 'slug',
					        'in' => 'path',
					        'description' => 'the slug of taxonomy',
					        'required' => true,
					        'type' => 'string',
				        ],
			        ],
			        'responses' => [
				        200 => [
					        'description' => 'successful operation',
					        'schema' => [
						        '$ref' => '#/definitions/DeleteMultiResponse',
					        ],
				        ],
				        404 => [
					        'description' => 'record not found',
					        'schema' => [
						        '$ref' => '#/definitions/NotFoundResponse',
					        ],
				        ],
				        500 => [
					        'description' => 'error',
					        'schema' => [
						        '$ref' => '#/definitions/ErrorResponse',
					        ],
				        ],
			        ],
		        ],
	        ],
	        '/taxonomy-meta/{slug}/{id}' => [
		        'get' => [
			        'tags' => [
				        'taxonomy-meta',
			        ],
			        'summary' => 'Get a single taxonomy meta box',
			        'description' => 'Use this endpoint to fetch a single taxonomy meta box',
			        'operationId' => 'getSingleTaxonomyMetaBox',
			        'consumes' => [
				        'application/json',
			        ],
			        'produces' => [
				        'application/json',
			        ],
			        'parameters' => [
				        [
					        'name' => 'slug',
					        'in' => 'path',
					        'description' => 'the slug of taxonomy',
					        'required' => true,
					        'type' => 'string',
				        ],
				        [
					        'name' => 'id',
					        'in' => 'path',
					        'description' => 'the id of taxonomy meta box',
					        'required' => true,
					        'type' => 'string',
					        'format' => 'uuid',
				        ],
			        ],
			        'responses' => [
				        200 => [
					        'description' => 'successful operation',
					        'schema' => [
						        '$ref' => '#/definitions/TaxonomyMetaBox',
					        ],
				        ],
				        404 => [
					        'description' => 'record not found',
					        'schema' => [
						        '$ref' => '#/definitions/NotFoundResponse',
					        ],
				        ],
			        ],
		        ],
		        'delete' => [
			        'tags' => [
				        'taxonomy-meta',
			        ],
			        'summary' => 'Delete a single taxonomy meta box',
			        'description' => 'Use this endpoint to delete a single taxonomy meta box',
			        'operationId' => 'deleteTaxonomyMetaBox',
			        'consumes' => [
				        'application/json',
			        ],
			        'produces' => [
				        'application/json',
			        ],
			        'parameters' => [
				        [
					        'name' => 'slug',
					        'in' => 'path',
					        'description' => 'the slug of taxonomy',
					        'required' => true,
					        'type' => 'string',
				        ],
				        [
					        'name' => 'id',
					        'in' => 'path',
					        'description' => 'the id of taxonomy meta box',
					        'required' => true,
					        'type' => 'string',
					        'format' => 'uuid',
				        ],
			        ],
			        'responses' => [
				        200 => [
					        'description' => 'successful operation',
					        'schema' => [
						        '$ref' => '#/definitions/DeleteResponse',
					        ],
				        ],
				        404 => [
					        'description' => 'record not found',
					        'schema' => [
						        '$ref' => '#/definitions/NotFoundResponse',
					        ],
				        ],
				        500 => [
					        'description' => 'error',
					        'schema' => [
						        '$ref' => '#/definitions/ErrorResponse',
					        ],
				        ],
			        ],
		        ],
	        ],
            '/taxonomy' => [
                'get' => [
                    'tags' => [
                        'taxonomy',
                    ],
                    'summary' => 'Get all taxonomies',
                    'description' => 'Use this endpoint to fetch all the registered taxonomies',
                    'operationId' => 'getAllTaxonomies',
                    'consumes' => [
                        'application/json',
                    ],
                    'produces' => [
                        'application/json',
                    ],
                    'parameters' => [
                        [
                            'name' => 'page',
                            'in' => 'query',
                            'description' => 'current page',
                            'required' => false,
                            'type' => 'integer',
                        ],
                        [
                            'name' => 'per_page',
                            'in' => 'query',
                            'description' => 'choose pagination size (Max 100)',
                            'required' => false,
                            'type' => 'integer',
                            'minimum' => 1,
                            'maximum' => 100,
                            'default' => 20,
                        ],
                    ],
                    'responses' => [
                        200 => [
                            'description' => 'successful operation',
                            'schema' => $this->getPaginatedResponse('#/definitions/Taxonomy'),
                        ],
                    ],
                ],
                'post' => [
                    'tags' => [
                        'taxonomy',
                    ],
                    'summary' => 'Create a taxonomy',
                    'description' => 'Use this endpoint to create a new taxonomy',
                    'operationId' => 'createTaxonomy',
                    'consumes' => [
                        'application/json',
                    ],
                    'produces' => [
                        'application/json',
                    ],
                    'parameters' => [
                        [
                            'name' => 'body',
                            'in' => 'body',
                            'description' => 'The taxonomy model',
                            'schema' => [
                                '$ref' => '#/definitions/Taxonomy',
                            ],
                        ],
                    ],
                    'responses' => [
                        201 => [
                            'description' => 'successful operation',
                            'schema' => [
                                '$ref' => '#/definitions/CreateResponse',
                            ],
                        ],
                        500 => [
                            'description' => 'error',
                            'schema' => [
                                '$ref' => '#/definitions/ErrorResponse',
                            ],
                        ],
                    ],
                ],
            ],
            '/taxonomy/{slug}' => [
                'get' => [
                    'tags' => [
                        'taxonomy',
                    ],
                    'summary' => 'Get a single taxonomy',
                    'description' => 'Use this endpoint to fetch a single registered taxonomy',
                    'operationId' => 'getTaxonomy',
                    'consumes' => [
                        'application/json',
                    ],
                    'produces' => [
                        'application/json',
                    ],
                    'parameters' => [
                        [
                            'name' => 'slug',
                            'in' => 'path',
                            'description' => 'the slug of taxonomy',
                            'required' => true,
                            'type' => 'string',
                        ],
                    ],
                    'responses' => [
                        200 => [
                            'description' => 'successful operation',
                            'schema' => [
                                '$ref' => '#/definitions/Taxonomy',
                            ],
                        ],
                        404 => [
                            'description' => 'record not found',
                            'schema' => [
                                '$ref' => '#/definitions/NotFoundResponse',
                            ],
                        ],
                    ],
                ],
                'put' => [
                    'tags' => [
                        'taxonomy',
                    ],
                    'summary' => 'Update a single taxonomy',
                    'description' => 'Use this endpoint to update a single registered taxonomy',
                    'operationId' => 'updateTaxonomy',
                    'consumes' => [
                        'application/json',
                    ],
                    'produces' => [
                        'application/json',
                    ],
                    'parameters' => [
                        [
                            'name' => 'slug',
                            'in' => 'path',
                            'description' => 'the slug of taxonomy',
                            'required' => true,
                            'type' => 'string',
                        ],
                        [
                            'name' => 'body',
                            'in' => 'body',
                            'description' => 'The taxonomy model',
                            'schema' => [
                                '$ref' => '#/definitions/Taxonomy',
                            ],
                        ],
                    ],
                    'responses' => [
                        200 => [
                            'description' => 'successful operation',
                            'schema' => [
                                '$ref' => '#/definitions/Taxonomy',
                            ],
                        ],
                        404 => [
                            'description' => 'record not found',
                            'schema' => [
                                '$ref' => '#/definitions/NotFoundResponse',
                            ],
                        ],
                        500 => [
                            'description' => 'error',
                            'schema' => [
                                '$ref' => '#/definitions/ErrorResponse',
                            ],
                        ],
                    ],
                ],
                'delete' => [
                    'tags' => [
                        'taxonomy',
                    ],
                    'summary' => 'Delete a single taxonomy',
                    'description' => 'Use this endpoint to delete a single registered taxonomy',
                    'operationId' => 'deleteTaxonomy',
                    'consumes' => [
                        'application/json',
                    ],
                    'produces' => [
                        'application/json',
                    ],
                    'parameters' => [
                        [
                            'name' => 'slug',
                            'in' => 'path',
                            'description' => 'the slug of taxonomy',
                            'required' => true,
                            'type' => 'string',
                        ],
                    ],
                    'responses' => [
                        200 => [
                            'description' => 'successful operation',
                            'schema' => [
                                '$ref' => '#/definitions/DeleteResponse',
                            ],
                        ],
                        404 => [
                            'description' => 'record not found',
                            'schema' => [
                                '$ref' => '#/definitions/NotFoundResponse',
                            ],
                        ],
                        500 => [
                            'description' => 'error',
                            'schema' => [
                                '$ref' => '#/definitions/ErrorResponse',
                            ],
                        ],
                    ],
                ],
            ],
            '/taxonomy/assoc/{slug}/{cpt}' => [
                'post' => [
                    'tags' => [
                        'taxonomy',
                    ],
                    'summary' => 'Associate a taxonomy to a cpt',
                    'description' => 'Use this endpoint to associate a registered taxonomy to a cpt',
                    'operationId' => 'assocTaxonomy',
                    'consumes' => [
                        'application/json',
                    ],
                    'produces' => [
                        'application/json',
                    ],
                    'parameters' => [
                        [
                            'name' => 'slug',
                            'in' => 'path',
                            'description' => 'the slug of taxonomy',
                            'required' => true,
                            'type' => 'string',
                        ],
                        [
                            'name' => 'cpt',
                            'in' => 'path',
                            'description' => 'the slug of custom post type',
                            'required' => true,
                            'type' => 'string',
                        ],
                    ],
                    'responses' => [
                        200 => [
                            'description' => 'successful operation',
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'cptId' => [
                                        'type' => 'string',
                                        'format' => 'uuid',
                                    ],
                                    'taxonomyId' => [
                                        'type' => 'string',
                                        'format' => 'uuid',
                                    ],
                                ],
                            ],
                        ],
                        404 => [
                            'description' => 'record not found',
                            'schema' => [
                                '$ref' => '#/definitions/NotFoundResponse',
                            ],
                        ],
                        500 => [
                            'description' => 'error',
                            'schema' => [
                                '$ref' => '#/definitions/ErrorResponse',
                            ],
                        ],
                    ],
                ]
            ],
            '/woocommerce/product-data' => [
                'get' => [
                    'tags' => [
                        'woocommerce',
                    ],
                    'summary' => 'Get all WooCommerce product data',
                    'description' => 'Use this endpoint to fetch all the registered WooCommerce product data',
                    'operationId' => 'getAllWooCommerceProductData',
                    'consumes' => [
                            'application/json',
                    ],
                    'produces' => [
                            'application/json',
                    ],
                    'parameters' => [
                        [
                            'name' => 'page',
                            'in' => 'query',
                            'description' => 'current page',
                            'required' => false,
                            'type' => 'integer',
                        ],
                        [
                            'name' => 'per_page',
                            'in' => 'query',
                            'description' => 'choose pagination size (Max 100)',
                            'required' => false,
                            'type' => 'integer',
                            'minimum' => 1,
                            'maximum' => 100,
                            'default' => 20,
                        ],
                    ],
                    'responses' => [
                        200 => [
                            'description' => 'successful operation',
                            'schema' => $this->getPaginatedResponse('#/definitions/WooCommerceProductData'),
                        ],
                    ],
                ],
                'post' => [
                    'tags' => [
                        'woocommerce',
                    ],
                    'summary' => 'Create a WooCommerce product data',
                    'description' => 'Use this endpoint to create a new WooCommerce product data',
                    'operationId' => 'createWooCommerceProductData',
                    'consumes' => [
                        'application/json',
                    ],
                    'produces' => [
                        'application/json',
                    ],
                    'parameters' => [
                        [
                            'name' => 'body',
                            'in' => 'body',
                            'description' => 'The WooCommerce product data model',
                            'schema' => [
                                '$ref' => '#/definitions/WooCommerceProductData',
                            ],
                        ],
                    ],
                    'responses' => [
                        201 => [
                            'description' => 'successful operation',
                            'schema' => [
                                    '$ref' => '#/definitions/CreateResponse',
                            ],
                        ],
                        500 => [
                            'description' => 'error',
                            'schema' => [
                                    '$ref' => '#/definitions/ErrorResponse',
                            ],
                        ],
                    ],
                ],
            ],
            '/woocommerce/product-data/{id}' => [
                    'get' => [
                            'tags' => [
                                    'woocommerce',
                            ],
                            'summary' => 'Get a single WooCommerce product data',
                            'description' => 'Use this endpoint to fetch a single registered WooCommerce product data',
                            'operationId' => 'getWooCommerceProductData',
                            'consumes' => [
                                    'application/json',
                            ],
                            'produces' => [
                                    'application/json',
                            ],
                            'parameters' => [
                                    [
                                            'name' => 'id',
                                            'in' => 'path',
                                            'description' => 'the id of WooCommerce product data',
                                            'required' => true,
                                            'type' => 'string',
                                            'format' => 'uuid',
                                    ],
                            ],
                            'responses' => [
                                    200 => [
                                            'description' => 'successful operation',
                                            'schema' => [
                                                    '$ref' => '#/definitions/WooCommerceProductData',
                                            ],
                                    ],
                                    404 => [
                                            'description' => 'record not found',
                                            'schema' => [
                                                    '$ref' => '#/definitions/NotFoundResponse',
                                            ],
                                    ],
                            ],
                    ],
                    'put' => [
                        'tags' => [
                                'woocommerce',
                        ],
                        'summary' => 'Update a single WooCommerce product data',
                        'description' => 'Use this endpoint to update a single registered WooCommerce product data',
                        'operationId' => 'updateWooCommerceProductData',
                        'consumes' => [
                                'application/json',
                        ],
                        'produces' => [
                                'application/json',
                        ],
                        'parameters' => [
                                [
                                        'name' => 'id',
                                        'in' => 'path',
                                        'description' => 'the id of WooCommerce product data',
                                        'required' => true,
                                        'type' => 'string',
                                        'format' => 'uuid',
                                ],
                                [
                                        'name' => 'body',
                                        'in' => 'body',
                                        'description' => 'The WooCommerce product data model',
                                        'schema' => [
                                                '$ref' => '#/definitions/WooCommerceProductData',
                                        ],
                                ],
                        ],
                        'responses' => [
                                200 => [
                                        'description' => 'successful operation',
                                        'schema' => [
                                                '$ref' => '#/definitions/WooCommerceProductData',
                                        ],
                                ],
                                404 => [
                                        'description' => 'record not found',
                                        'schema' => [
                                                '$ref' => '#/definitions/NotFoundResponse',
                                        ],
                                ],
                                500 => [
                                        'description' => 'error',
                                        'schema' => [
                                                '$ref' => '#/definitions/ErrorResponse',
                                        ],
                                ],
                        ],
                    ],
                    'delete' => [
                            'tags' => [
                                    'woocommerce',
                            ],
                            'summary' => 'Delete a single WooCommerce product data',
                            'description' => 'Use this endpoint to delete a single registered WooCommerce product data',
                            'operationId' => 'deleteWooCommerceProductData',
                            'consumes' => [
                                    'application/json',
                            ],
                            'produces' => [
                                    'application/json',
                            ],
                            'parameters' => [
                                    [
                                            'name' => 'id',
                                            'in' => 'path',
                                            'description' => 'the id of WooCommerce product data',
                                            'required' => true,
                                            'type' => 'string',
                                            'format' => 'uuid',
                                    ],
                            ],
                            'responses' => [
                                    200 => [
                                            'description' => 'successful operation',
                                            'schema' => [
                                                    '$ref' => '#/definitions/DeleteResponse',
                                            ],
                                    ],
                                    404 => [
                                            'description' => 'record not found',
                                            'schema' => [
                                                    '$ref' => '#/definitions/NotFoundResponse',
                                            ],
                                    ],
                                    500 => [
                                            'description' => 'error',
                                            'schema' => [
                                                    '$ref' => '#/definitions/ErrorResponse',
                                            ],
                                    ],
                            ],
                    ],
            ],
            '/woocommerce/product-data/{id}/fields' => [
                'get' => [
                        'tags' => [
                                'woocommerce',
                        ],
                        'summary' => 'Get the WooCommerce product data fields',
                        'description' => 'Use this endpoint to fetch the product data associated to a single WooCommerce product data',
                        'operationId' => 'getWooCommerceProductDataFields',
                        'consumes' => [
                                'application/json',
                        ],
                        'produces' => [
                                'application/json',
                        ],
                        'parameters' => [
                                [
                                        'name' => 'id',
                                        'in' => 'path',
                                        'description' => 'the id of WooCommerce product data',
                                        'required' => true,
                                        'type' => 'string',
                                        'format' => 'uuid',
                                ],
                        ],
                        'responses' => [
                                200 => [
                                        'description' => 'successful operation',
                                        'schema' => [
                                                'type' => 'array',
                                                'items' => [
                                                        '$ref' => '#/definitions/WooCommerceProductDataField'
                                                ]
                                        ],
                                ],
                                404 => [
                                        'description' => 'record not found',
                                        'schema' => [
                                                '$ref' => '#/definitions/NotFoundResponse',
                                        ],
                                ],
                        ],
                ],
                'post' => [
                    'tags' => [
                            'woocommerce',
                    ],
                    'summary' => 'Create WooCommerce product data fields',
                    'description' => 'Use this endpoint to create WooCommerce product data fields',
                    'operationId' => 'createWooCommerceProductDataFields',
                    'consumes' => [
                            'application/json',
                    ],
                    'produces' => [
                            'application/json',
                    ],
                    'parameters' => [
                            [
                                'name' => 'body',
                                'in' => 'body',
                                'description' => 'Array ofWooCommerce product data fields models',
                                'type' => 'array',
                                'items' => [
                                    '$ref' => '#/definitions/WooCommerceProductDataField',
                                ],
                            ],
                    ],
                    'responses' => [
                            201 => [
                                    'description' => 'successful operation',
                                    'schema' => [
                                            '$ref' => '#/definitions/WooCommerceFieldsCreateResponse',
                                    ],
                            ],
                            500 => [
                                    'description' => 'error',
                                    'schema' => [
                                            '$ref' => '#/definitions/ErrorResponse',
                                    ],
                            ],
                    ],
                ],
                'put' => [
                        'tags' => [
                                'woocommerce',
                        ],
                        'summary' => 'Update WooCommerce product data fields',
                        'description' => 'Use this endpoint to update WooCommerce product data fields',
                        'operationId' => 'updateWooCommerceProductDataFields',
                        'consumes' => [
                                'application/json',
                        ],
                        'produces' => [
                                'application/json',
                        ],
                        'parameters' => [
                                [
                                        'name' => 'body',
                                        'in' => 'body',
                                        'description' => 'Array ofWooCommerce product data fields models',
                                        'type' => 'array',
                                        'items' => [
                                                '$ref' => '#/definitions/WooCommerceProductDataField',
                                        ],
                                ],
                        ],
                        'responses' => [
                                200 => [
                                        'description' => 'successful operation',
                                        'schema' => [
                                                '$ref' => '#/definitions/WooCommerceFieldsCreateResponse',
                                        ],
                                ],
                                500 => [
                                        'description' => 'error',
                                        'schema' => [
                                                '$ref' => '#/definitions/ErrorResponse',
                                        ],
                                ],
                        ],
                ],
                'delete' => [
                    'tags' => [
                        'woocommerce',
                    ],
                    'summary' => 'Delete all WooCommerce product data fields',
                    'description' => 'Use this endpoint to delete all fields associated to single WooCommerce product data',
                    'operationId' => 'deleteWooCommerceProductDataFields',
                    'consumes' => [
                            'application/json',
                    ],
                    'produces' => [
                            'application/json',
                    ],
                    'parameters' => [
                            [
                                    'name' => 'id',
                                    'in' => 'path',
                                    'description' => 'the id of WooCommerce product data',
                                    'required' => true,
                                    'type' => 'string',
                                    'format' => 'uuid',
                            ],
                    ],
                    'responses' => [
                            200 => [
                                    'description' => 'successful operation',
                                    'schema' => [
                                            '$ref' => '#/definitions/DeleteResponse',
                                    ],
                            ],
                            404 => [
                                    'description' => 'record not found',
                                    'schema' => [
                                            '$ref' => '#/definitions/NotFoundResponse',
                                    ],
                            ],
                            500 => [
                                    'description' => 'error',
                                    'schema' => [
                                            '$ref' => '#/definitions/ErrorResponse',
                                    ],
                            ],
                    ],
                ],
            ],
            '/woocommerce/product-data/{id}/fields/{field}' => [
                    'get' => [
                            'tags' => [
                                    'woocommerce',
                            ],
                            'summary' => 'Get a single WooCommerce product data field',
                            'description' => 'Use this endpoint to fetch a single registered WooCommerce product data field',
                            'operationId' => 'getWooCommerceProductDataField',
                            'consumes' => [
                                    'application/json',
                            ],
                            'produces' => [
                                    'application/json',
                            ],
                            'parameters' => [
                                    [
                                            'name' => 'id',
                                            'in' => 'path',
                                            'description' => 'the id of WooCommerce product data field',
                                            'required' => true,
                                            'type' => 'string',
                                            'format' => 'uuid',
                                    ],
                            ],
                            'responses' => [
                                    200 => [
                                            'description' => 'successful operation',
                                            'schema' => [
                                                    '$ref' => '#/definitions/WooCommerceProductDataField',
                                            ],
                                    ],
                                    404 => [
                                            'description' => 'record not found',
                                            'schema' => [
                                                    '$ref' => '#/definitions/NotFoundResponse',
                                            ],
                                    ],
                            ],
                    ],
                    'delete' => [
                            'tags' => [
                                'woocommerce',
                            ],
                            'summary' => 'Delete a single WooCommerce product data field',
                            'description' => 'Use this endpoint to delete a single registered WooCommerce product data field',
                            'operationId' => 'deleteWooCommerceProductFataField',
                            'consumes' => [
                                    'application/json',
                            ],
                            'produces' => [
                                    'application/json',
                            ],
                            'parameters' => [
                                [
                                    'name' => 'id',
                                    'in' => 'path',
                                    'description' => 'the slug of cpt',
                                    'required' => true,
                                    'type' => 'string',
                                    'format' => 'uuid',
                                ],
                                [
                                    'name' => 'field',
                                    'in' => 'path',
                                    'description' => 'the slug of cpt',
                                    'required' => true,
                                    'type' => 'string',
                                    'format' => 'uuid',
                                ],
                            ],
                            'responses' => [
                                200 => [
                                    'description' => 'successful operation',
                                    'schema' => [
                                            '$ref' => '#/definitions/DeleteResponse',
                                    ],
                                ],
                                404 => [
                                    'description' => 'record not found',
                                    'schema' => [
                                            '$ref' => '#/definitions/NotFoundResponse',
                                    ],
                                ],
                                500 => [
                                    'description' => 'error',
                                    'schema' => [
                                            '$ref' => '#/definitions/ErrorResponse',
                                    ],
                                ],
                            ],
                    ],
            ],
            '/user-meta' => [
                'get' => [
                    'tags' => [
                        'user-meta',
                    ],
                    'summary' => 'Get the user meta boxes',
                    'description' => 'Use this endpoint to fetch the user meta boxes',
                    'operationId' => 'getUserMetaBox',
                    'consumes' => [
                        'application/json',
                    ],
                    'produces' => [
                        'application/json',
                    ],
                    'responses' => [
                        200 => [
                            'description' => 'successful operation',
                            'schema' => [
                                'type' => 'array',
                                'items' => [
                                    '$ref' => '#/definitions/UserMetaBox'
                                ]
                            ],
                        ],
                        404 => [
                            'description' => 'record not found',
                            'schema' => [
                                '$ref' => '#/definitions/NotFoundResponse',
                            ],
                        ],
                    ],
                ],
                'post' => [
                        'tags' => [
                                'user-meta',
                        ],
                        'summary' => 'Create user meta boxes',
                        'description' => 'Use this endpoint to create user meta boxes',
                        'operationId' => 'createUserMetaBox',
                        'consumes' => [
                                'application/json',
                        ],
                        'produces' => [
                                'application/json',
                        ],
                        'parameters' => [
                                [
                                        'name' => 'body',
                                        'in' => 'body',
                                        'description' => 'Array of meta box models',
                                        'type' => 'array',
                                        'items' => [
                                                '$ref' => '#/definitions/UserMetaBox',
                                        ],
                                ],
                        ],
                        'responses' => [
                                201 => [
                                        'description' => 'successful operation',
                                        'schema' => [
                                                '$ref' => '#/definitions/CreateMetaboxResponse',
                                        ],
                                ],
                                500 => [
                                        'description' => 'error',
                                        'schema' => [
                                                '$ref' => '#/definitions/ErrorResponse',
                                        ],
                                ],
                        ],
                ],
                'put' => [
                        'tags' => [
                                'user-meta',
                        ],
                        'summary' => 'Update user meta boxes',
                        'description' => 'Use this endpoint to update user meta boxes',
                        'operationId' => 'updateUserMetaBox',
                        'consumes' => [
                                'application/json',
                        ],
                        'produces' => [
                                'application/json',
                        ],
                        'parameters' => [
                                [
                                        'name' => 'body',
                                        'in' => 'body',
                                        'description' => 'Array of meta box models',
                                        'type' => 'array',
                                        'items' => [
                                                '$ref' => '#/definitions/UserMetaBox',
                                        ],
                                ],
                        ],
                        'responses' => [
                                200 => [
                                        'description' => 'successful operation',
                                        'schema' => [
                                                '$ref' => '#/definitions/CreateMetaboxResponse',
                                        ],
                                ],
                                500 => [
                                        'description' => 'error',
                                        'schema' => [
                                                '$ref' => '#/definitions/ErrorResponse',
                                        ],
                                ],
                        ],
                ],
                'delete' => [
                        'tags' => [
                                'user-meta',
                        ],
                        'summary' => 'Delete all user meta boxes',
                        'description' => 'Use this endpoint to delete all user meta boxes',
                        'operationId' => 'deleteAllUserMetaBox',
                        'consumes' => [
                                'application/json',
                        ],
                        'produces' => [
                                'application/json',
                        ],
                        'responses' => [
                                200 => [
                                        'description' => 'successful operation',
                                        'schema' => [
                                                '$ref' => '#/definitions/DeleteMultiResponse',
                                        ],
                                ],
                                404 => [
                                        'description' => 'record not found',
                                        'schema' => [
                                                '$ref' => '#/definitions/NotFoundResponse',
                                        ],
                                ],
                                500 => [
                                        'description' => 'error',
                                        'schema' => [
                                                '$ref' => '#/definitions/ErrorResponse',
                                        ],
                                ],
                        ],
                ],
            ],
            '/user-meta/{id}' => [
                'get' => [
                        'tags' => [
                                'user-meta',
                        ],
                        'summary' => 'Get a single user meta box',
                        'description' => 'Use this endpoint to fetch a single user meta box',
                        'operationId' => 'getSingleUserMetaBox',
                        'consumes' => [
                                'application/json',
                        ],
                        'produces' => [
                                'application/json',
                        ],
                        'parameters' => [
                                [
                                        'name' => 'id',
                                        'in' => 'path',
                                        'description' => 'the id of meta box',
                                        'required' => true,
                                        'type' => 'string',
                                        'format' => 'uuid',
                                ],
                        ],
                        'responses' => [
                                200 => [
                                        'description' => 'successful operation',
                                        'schema' => [
                                                '$ref' => '#/definitions/UserMetaBox',
                                        ],
                                ],
                                404 => [
                                        'description' => 'record not found',
                                        'schema' => [
                                                '$ref' => '#/definitions/NotFoundResponse',
                                        ],
                                ],
                        ],
                ],
                'delete' => [
                            'tags' => [
                                    'user-meta',
                            ],
                            'summary' => 'Delete a single user meta box',
                            'description' => 'Use this endpoint to delete a single user meta box',
                            'operationId' => 'deleteUserMetaBox',
                            'consumes' => [
                                    'application/json',
                            ],
                            'produces' => [
                                    'application/json',
                            ],
                            'parameters' => [
                                    [
                                            'name' => 'id',
                                            'in' => 'path',
                                            'description' => 'the id of meta box',
                                            'required' => true,
                                            'type' => 'string',
                                            'format' => 'uuid',
                                    ],
                            ],
                            'responses' => [
                                    200 => [
                                            'description' => 'successful operation',
                                            'schema' => [
                                                    '$ref' => '#/definitions/DeleteResponse',
                                            ],
                                    ],
                                    404 => [
                                            'description' => 'record not found',
                                            'schema' => [
                                                    '$ref' => '#/definitions/NotFoundResponse',
                                            ],
                                    ],
                                    500 => [
                                            'description' => 'error',
                                            'schema' => [
                                                    '$ref' => '#/definitions/ErrorResponse',
                                            ],
                                    ],
                            ],
                    ],
            ],
        ];

        $cpts = CustomPostTypeRepository::get([], true);

        foreach ($cpts as $cpt){

            $path = '/' . $cpt->getName() . '/filter/query';

            $paths[ $path ] = [
                'post' => [
                    'tags' => [
                            'filter',
                    ],
                    'summary' => 'Advanced posts search',
                    'description' => 'Use this endpoint to search post',
                    'operationId' => 'queryFilter',
                    'consumes' => [
                            'application/json',
                    ],
                    'produces' => [
                            'application/json',
                    ],
                    'parameters' => [
                        [
                            'name' => 'body',
                            'in' => 'body',
                            'description' => 'The filters array',
                            'type' => 'object',
                            'schema' => [
                                '$ref' => '#/definitions/QueryFilter',
                            ],
                        ],
                        [
                            'name' => 'page',
                            'in' => 'query',
                            'description' => 'current page',
                            'required' => false,
                            'type' => 'integer',
                            'default' => 1,
                        ],
                        [
                            'name' => 'per_page',
                            'in' => 'query',
                            'description' => 'choose pagination size (Max 100)',
                            'required' => false,
                            'type' => 'integer',
                            'minimum' => 1,
                            'maximum' => 100,
                            'default' => 20,
                        ],
                    ],
                    'responses' => [
                            200 => [
                                'description' => 'successful operation',
                                'schema' => $this->getPaginatedResponse('#/definitions/QueryResult'),
                            ],
                    ],
                ],
            ];
        }

        return $paths;
    }

    /**
     * Model definitions
     *
     * @return array
     */
    private function getModelDefinitions()
    {
        return [
            'CustomPostType' => (new CustomPostTypeSchema())->toArray(),
            'MetaBox' => (new CustomPostTypeMetaBoxSchema())->toArray(),
            'OptionPage' => (new OptionPageSchema())->toArray(),
            'OptionPageMetaBox' => (new OptionPageMetaBoxSchema())->toArray(),
            'Taxonomy' => (new TaxonomySchema())->toArray(),
            'TaxonomyMetaBox' => (new TaxonomyMetaBoxSchema())->toArray(),
            'QueryFilter' => (new QueryFilterSchema())->toArray(),
            'WooCommerceProductData' => (new WooCommerceProductDataSchema())->toArray(),
            'WooCommerceProductDataField' => (new WooCommerceProductDataFieldSchema())->toArray(),
            'QueryResult' => (new QueryResultSchema())->toArray(),
            'UserMetaBox' => (new UserMetaBoxSchema())->toArray(),
            'CreateResponse' => $this->getCreateResponse(),
            'CreateMetaboxResponse' => $this->getMetaboxCreateResponse(),
            'WooCommerceFieldsCreateResponse' => $this->getWooCommerceFieldsCreateResponse(),
            'DeleteResponse' => $this->getDeleteResponse(),
            'DeleteMultiResponse' => $this->getDeleteMultiResponse(),
            'NotFoundResponse' => $this->getNotFoundResponse(),
            'ErrorResponse' => $this->getErrorResponse(),
        ];
    }

    /**
     * Security definitions
     *
     * @return array
     */
    private function getSecurityDefinitions()
    {
        return [
            'ApiKeyAuth' => [
                'type' => 'apiKey',
                'in' => 'header',
                'name' => 'acpt-api-key',
            ],
            'BasicAuth' => [
                'type' => 'basic'
            ],
        ];
    }

    /**
     * Security
     *
     * @return array
     */
    private function getSecurity()
    {
        return [
            [
                'ApiKeyAuth' => []
            ],
            [
                'BasicAuth' => []
            ],
        ];
    }

    /**
     * Paginated response
     *
     * @param $ref
     *
     * @return array
     */
    private function getPaginatedResponse($ref)
    {
        return [
                'type' => 'object',
                'properties' => [
                        'currentPage' => [
                            'type' => 'integer',
                            'example' => 1,
                        ],
                        'prev' => [
                                'type' => 'string',
                                'nullable' => true,
                        ],
                        'next' => [
                                'type' => 'string',
                                'nullable' => true,
                        ],
                        'total' => [
                                'type' => 'integer',
                        ],
                        'records' => [
                                'type' => 'array',
                                'items' => [
                                        '$ref' => $ref
                                ],
                        ],
                ],
        ];
    }

    private function getCreateResponse()
    {
        return [
            'type' => 'object',
            'properties' => [
                'id' => [
                    'type' => 'string',
                    'format' => 'uuid',
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    private function getMetaboxCreateResponse()
    {
        return [
            'type' => 'object',
            'properties' => [
                'ids' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'slug' => [
                                'type' => 'array',
                                'items' => [
                                    'type' => 'object',
                                    'properties' => [
                                        "boxes" => [
                                            'type' => 'array',
                                            'items' => [
                                                'type' => 'string',
                                                'format' => 'uuid',
                                            ]
                                        ],
                                        "fields" => [
                                            'type' => 'array',
                                            'items' => [
                                                'type' => 'string',
                                                'format' => 'uuid',
                                            ]
                                        ],
                                        "options" => [
                                            'type' => 'array',
                                            'items' => [
                                                'type' => 'string',
                                                'format' => 'uuid',
                                            ]
                                        ],
                                    ]
                                ]
                            ]
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    private function getWooCommerceFieldsCreateResponse()
    {
        return [
            'type' => 'object',
            'properties' => [
                'ids' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                              "product_data_id" => [
                                  'type' => 'string',
                                  'format' => 'uuid',
                              ],
                              "field" => [
                                  'type' => 'string',
                                  'format' => 'uuid',
                              ],
                              "options" => [
                                    'type' => 'array',
                                    'items' => [
                                        'type' => 'string',
                                        'format' => 'uuid',
                                    ]
                              ],
                        ],
                    ],
                ]
            ]
        ];
    }

    private function getDeleteResponse()
    {
        return [
            'type' => 'object',
            'properties' => [
                'id' => [
                    'type' => 'string',
                    'format' => 'uuid',
                ],
            ],
        ];
    }

    private function getDeleteMultiResponse()
    {
        return [
                'type' => 'object',
                'properties' => [
                    'ids' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'string',
                            'format' => 'uuid',
                        ],
                    ],
                ],
        ];
    }

    private function getNotFoundResponse()
    {
        return [
            'type' => 'object',
            'properties' => [
                'message' => [
                    'type' => 'string',
                ],
            ],
        ];
    }

    private function getErrorResponse()
    {
        return [
            'type' => 'object',
            'properties' => [
                'message' => [
                    'type' => 'string',
                ],
                'code' => [
                    'type' => 'integer',
                ],
                'line' => [
                    'type' => 'integer',
                ],
                'trace' => [
                    'type' => 'string',
                ],
            ],
        ];
    }

    /**
     * External docs links
     *
     * @return array
     */
    private function getExternalDocs()
    {
        return [
            'description' => 'Find out more about ACPT',
            'url' => $this->documentationLink(),
        ];
    }

    /**
     * @return string
     */
    private function documentationLink()
    {
        return 'https://acpt.io/documentation';
    }
}