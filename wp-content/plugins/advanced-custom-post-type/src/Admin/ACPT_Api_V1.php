<?php

namespace ACPT\Admin;

use ACPT\Core\API\V1\Controllers\CustomPostTypeController;
use ACPT\Core\API\V1\Controllers\CustomPostTypeMetaBoxController;
use ACPT\Core\API\V1\Controllers\FilterQueryController;
use ACPT\Core\API\V1\Controllers\LicenseController;
use ACPT\Core\API\V1\Controllers\OptionPageController;
use ACPT\Core\API\V1\Controllers\OptionPageMetaController;
use ACPT\Core\API\V1\Controllers\SchemaController;
use ACPT\Core\API\V1\Controllers\TaxonomyController;
use ACPT\Core\API\V1\Controllers\TaxonomyMetaController;
use ACPT\Core\API\V1\Controllers\TemplateController;
use ACPT\Core\API\V1\Controllers\UserMetaController;
use ACPT\Core\API\V1\Controllers\WooCommerceController;

class ACPT_Api_V1
{
    const BASE_V1 = 'acpt/v1';

    /**
     * Register REST routes
     */
    public function registerRestRoutes()
    {
    	// license
	    $this->registerRestRoute('license/deactivate', 'POST', [new LicenseController(), 'deactivate'], false);

        // schema
        $this->registerRestRoute('schema', 'GET', [new SchemaController(), 'schema'], false);

        // filter
        $this->registerRestRoute('(?P<slug>[a-zA-Z0-9-_]+)/filter/query', 'POST', [new FilterQueryController(), 'search']);

        // Custom Post Types
        $this->registerRestRoute('cpt', 'GET', [new CustomPostTypeController(), 'getAll']);
        $this->registerRestRoute('cpt/(?P<slug>[a-zA-Z0-9-_]+)', 'GET', [new CustomPostTypeController(), 'get']);
        $this->registerRestRoute('cpt', 'POST', [new CustomPostTypeController(), 'create']);
        $this->registerRestRoute('cpt/(?P<slug>[a-zA-Z0-9-_]+)', 'DELETE', [new CustomPostTypeController(), 'delete']);
        $this->registerRestRoute('cpt/(?P<slug>[a-zA-Z0-9-_]+)', 'PUT', [new CustomPostTypeController(), 'update']);

        // Custom Post Types meta
        $this->registerRestRoute('meta/(?P<slug>[a-zA-Z0-9-_]+)', 'GET', [new CustomPostTypeMetaBoxController(), 'getByPostSlug']);
        $this->registerRestRoute('meta/(?P<slug>[a-zA-Z0-9-_]+)/(?P<id>[a-zA-Z0-9-_]+)', 'GET', [new CustomPostTypeMetaBoxController(), 'get']);
        $this->registerRestRoute('meta/(?P<slug>[a-zA-Z0-9-_]+)', 'DELETE', [new CustomPostTypeMetaBoxController(), 'deleteAll']);
        $this->registerRestRoute('meta/(?P<slug>[a-zA-Z0-9-_]+)', 'POST', [new CustomPostTypeMetaBoxController(), 'create']);
        $this->registerRestRoute('meta/(?P<slug>[a-zA-Z0-9-_]+)/(?P<id>[a-zA-Z0-9-_]+)', 'DELETE', [new CustomPostTypeMetaBoxController(), 'delete']);
        $this->registerRestRoute('meta/(?P<slug>[a-zA-Z0-9-_]+)', 'PUT', [new CustomPostTypeMetaBoxController(), 'update']);

        // Option page
	    $this->registerRestRoute('option-page', 'GET', [new OptionPageController(), 'getAll']);
	    $this->registerRestRoute('option-page/(?P<slug>[a-zA-Z0-9-_]+)', 'GET', [new OptionPageController(), 'get']);
	    $this->registerRestRoute('option-page', 'POST', [new OptionPageController(), 'create']);
	    $this->registerRestRoute('option-page/(?P<slug>[a-zA-Z0-9-_]+)', 'DELETE', [new OptionPageController(), 'delete']);
	    $this->registerRestRoute('option-page/(?P<slug>[a-zA-Z0-9-_]+)', 'PUT', [new OptionPageController(), 'update']);

	    // Option page meta
	    $this->registerRestRoute('option-page-meta/(?P<slug>[a-zA-Z0-9-_]+)', 'GET', [new OptionPageMetaController(), 'getByMenuSlug']);
	    $this->registerRestRoute('option-page-meta/(?P<slug>[a-zA-Z0-9-_]+)/(?P<id>[a-zA-Z0-9-_]+)', 'GET', [new OptionPageMetaController(), 'get']);
	    $this->registerRestRoute('option-page-meta/(?P<slug>[a-zA-Z0-9-_]+)', 'DELETE', [new OptionPageMetaController(), 'deleteAll']);
	    $this->registerRestRoute('option-page-meta/(?P<slug>[a-zA-Z0-9-_]+)', 'POST', [new OptionPageMetaController(), 'create']);
	    $this->registerRestRoute('option-page-meta/(?P<slug>[a-zA-Z0-9-_]+)/(?P<id>[a-zA-Z0-9-_]+)', 'DELETE', [new OptionPageMetaController(), 'delete']);
	    $this->registerRestRoute('option-page-meta/(?P<slug>[a-zA-Z0-9-_]+)', 'PUT', [new OptionPageMetaController(), 'update']);

        // Taxonomy
        $this->registerRestRoute('taxonomy', 'GET', [new TaxonomyController(), 'getAll']);
        $this->registerRestRoute('taxonomy/(?P<slug>[a-zA-Z0-9-_]+)', 'GET', [new TaxonomyController(), 'get']);
        $this->registerRestRoute('taxonomy', 'POST', [new TaxonomyController(), 'create']);
        $this->registerRestRoute('taxonomy/(?P<slug>[a-zA-Z0-9-_]+)', 'DELETE', [new TaxonomyController(), 'delete']);
        $this->registerRestRoute('taxonomy/(?P<slug>[a-zA-Z0-9-_]+)', 'PUT', [new TaxonomyController(), 'update']);
        $this->registerRestRoute('taxonomy/assoc/(?P<slug>[a-zA-Z0-9-_]+)/(?P<cpt>[a-zA-Z0-9-_]+)', 'POST', [new TaxonomyController(), 'assocToPostType']);

        // Taxonomy meta
        $this->registerRestRoute('taxonomy-meta/(?P<slug>[a-zA-Z0-9-_]+)', 'GET', [new TaxonomyMetaController(), 'getByTaxonomySlug']);
        $this->registerRestRoute('taxonomy-meta/(?P<slug>[a-zA-Z0-9-_]+)/(?P<id>[a-zA-Z0-9-_]+)', 'GET', [new TaxonomyMetaController(), 'get']);
        $this->registerRestRoute('taxonomy-meta/(?P<slug>[a-zA-Z0-9-_]+)', 'DELETE', [new TaxonomyMetaController(), 'deleteAll']);
        $this->registerRestRoute('taxonomy-meta/(?P<slug>[a-zA-Z0-9-_]+)', 'POST', [new TaxonomyMetaController(), 'create']);
        $this->registerRestRoute('taxonomy-meta/(?P<slug>[a-zA-Z0-9-_]+)/(?P<id>[a-zA-Z0-9-_]+)', 'DELETE', [new TaxonomyMetaController(), 'delete']);
        $this->registerRestRoute('taxonomy-meta/(?P<slug>[a-zA-Z0-9-_]+)', 'PUT', [new TaxonomyMetaController(), 'update']);

        // WooCommerce
        $this->registerRestRoute('woocommerce/product-data', 'GET', [new WooCommerceController(), 'getAll']);
        $this->registerRestRoute('woocommerce/product-data/(?P<id>[a-zA-Z0-9-_]+)', 'GET', [new WooCommerceController(), 'get']);
        $this->registerRestRoute('woocommerce/product-data', 'POST', [new WooCommerceController(), 'create']);
        $this->registerRestRoute('woocommerce/product-data/(?P<id>[a-zA-Z0-9-_]+)', 'DELETE', [new WooCommerceController(), 'delete']);
        $this->registerRestRoute('woocommerce/product-data/(?P<id>[a-zA-Z0-9-_]+)', 'PUT', [new WooCommerceController(), 'update']);
        $this->registerRestRoute('woocommerce/product-data/(?P<id>[a-zA-Z0-9-_]+)/fields', 'GET', [new WooCommerceController(), 'getFields']);
        $this->registerRestRoute('woocommerce/product-data/(?P<id>[a-zA-Z0-9-_]+)/fields', 'POST', [new WooCommerceController(), 'createFields']);
        $this->registerRestRoute('woocommerce/product-data/(?P<id>[a-zA-Z0-9-_]+)/fields', 'PUT', [new WooCommerceController(), 'updateFields']);
        $this->registerRestRoute('woocommerce/product-data/(?P<id>[a-zA-Z0-9-_]+)/fields', 'DELETE', [new WooCommerceController(), 'deleteFields']);
        $this->registerRestRoute('woocommerce/product-data/(?P<id>[a-zA-Z0-9-_]+)/fields/(?P<field>[a-zA-Z0-9-_]+)', 'GET', [new WooCommerceController(), 'getField']);
        $this->registerRestRoute('woocommerce/product-data/(?P<id>[a-zA-Z0-9-_]+)/fields/(?P<field>[a-zA-Z0-9-_]+)', 'DELETE', [new WooCommerceController(), 'deleteField']);

        // User meta
        $this->registerRestRoute('user-meta/', 'GET', [new UserMetaController(), 'getAll']);
        $this->registerRestRoute('user-meta/(?P<id>[a-zA-Z0-9-_]+)', 'GET', [new UserMetaController(), 'get']);
        $this->registerRestRoute('user-meta/', 'DELETE', [new UserMetaController(), 'deleteAll']);
        $this->registerRestRoute('user-meta/', 'POST', [new UserMetaController(), 'create']);
        $this->registerRestRoute('user-meta/(?P<id>[a-zA-Z0-9-_]+)', 'DELETE', [new UserMetaController(), 'delete']);
        $this->registerRestRoute('user-meta/', 'PUT', [new UserMetaController(), 'update']);

        // Template
        $this->registerRestRoute('template/store', 'POST', [new TemplateController(), 'store'], false);
        $this->registerRestRoute('template/load/(?P<belongsTo>[a-zA-Z0-9-_]+)/(?P<template>[a-zA-Z0-9-_]+)', 'GET', [new TemplateController(), 'load'], false);
        $this->registerRestRoute('template/load/(?P<belongsTo>[a-zA-Z0-9-_]+)/(?P<template>[a-zA-Z0-9-_]+)/(?P<find>[a-zA-Z0-9-_]+)', 'GET', [new TemplateController(), 'load'], false);
        $this->registerRestRoute('template/load/(?P<belongsTo>[a-zA-Z0-9-_]+)/(?P<template>[a-zA-Z0-9-_]+)/(?P<find>[a-zA-Z0-9-_]+)/(?P<metaFieldId>[a-zA-Z0-9-_]+)', 'GET', [new TemplateController(), 'load'], false);
        $this->registerRestRoute('template/block/(?P<belongsTo>[a-zA-Z0-9-_]+)/(?P<template>[a-zA-Z0-9-_]+)', 'GET', [new TemplateController(), 'block'], false);
        $this->registerRestRoute('template/block/(?P<belongsTo>[a-zA-Z0-9-_]+)/(?P<template>[a-zA-Z0-9-_]+)/(?P<find>[a-zA-Z0-9-_]+)', 'GET', [new TemplateController(), 'block'], false);
        $this->registerRestRoute('template/block/(?P<belongsTo>[a-zA-Z0-9-_]+)/(?P<template>[a-zA-Z0-9-_]+)/(?P<find>[a-zA-Z0-9-_]+)/(?P<metaFieldId>[a-zA-Z0-9-_]+)', 'GET', [new TemplateController(), 'block'], false);
    }

    /**
     * @param string   $route
     * @param string   $methods
     * @param callable $callback
     * @param bool     $isASecurizedRoute
     */
    private function registerRestRoute( $route, $methods, $callback, $isASecurizedRoute = true)
    {
        $options = [
            'methods' => $methods,
            'callback' => $callback,
        ];
        $options['permission_callback'] = ($isASecurizedRoute)  ? [new ACPT_Api_Auth(), 'authenticate'] : '__return_true';

        register_rest_route( self::BASE_V1 , $route, $options );
    }
}