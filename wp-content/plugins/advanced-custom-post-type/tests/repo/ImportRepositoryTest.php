<?php

namespace ACPT\Tests;

use ACPT\Core\Repository\ImportRepository;
use ACPT\Costants\MetaTypes;

class ImportRepositoryTest extends AbstractTestCase
{
	/**
	 * @test
	 */
	public function raise_exception()
	{
		$data = [
			'cavolo' => [],
			MetaTypes::TAXONOMY => [],
			MetaTypes::USER => [],
		];

		try {
			ImportRepository::import($data);
		} catch (\Exception $exception){
			$this->assertNotNull($exception->getMessage());
		}
	}

	/**
	 * @test
	 * @throws \Exception
	 */
	public function can_import_data()
	{
		$data = [
			MetaTypes::CUSTOM_POST_TYPE => [
				0 => [
					'id' => '10f9e6c0-8372-4049-b7a3-b272f0f25268',
					'name' => 'post',
					'singular' => 'Post',
					'plural' => 'Posts',
					'icon' => 'admin-post',
					'postCount' => '1',
					'supports' => [
					],
					'labels' => [
					],
					'settings' => [
					],
					'meta' => [
						0 => [
							'id' => '937b71ec-57cc-4e8f-8246-15a24ab5d6ea',
							'postType' => 'post',
							'name' => 'Box name',
							'sort' => 1,
							'fields' => [
							],
						],
					],
					'taxonomies' => [
						0 => [
							'id' => '074b1929-87b4-4945-9b1b-5cb84d1fb158',
							'slug' => 'category',
							'singular' => 'Category',
							'plural' => 'Categories',
							'labels' => [
							],
							'settings' => [
								'hierarchical' => true,
							],
							'postCount' => NULL,
						],
						1 => [
							'id' => '841145af-837e-427f-b512-d2c64c02b485',
							'slug' => 'post_tag',
							'singular' => 'Tag',
							'plural' => 'Tags',
							'labels' => [
							],
							'settings' => [
								'hierarchical' => true,
							],
							'postCount' => NULL,
						],
					],
					'templates' => [
						0 => [
							'id' => '87d1cf2b-1016-4162-bafd-b18255da11b5',
							'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
							'find' => 'post',
							'metaFieldId' => NULL,
							'metaField' => NULL,
							'templateType' => 'related',
							'json' => '{"assets":[],"styles":[],"pages":[{"frames":[{"component":{"type":"wrapper","stylable":["background","background-color","background-image","background-repeat","background-attachment","background-position","background-size"],"_undoexc":["status","open"],"components":[{"tagName":"p","type":"text","_undoexc":["status","open"],"components":[{"type":"textnode","content":"No content yet. Drag components in this canvas and start building something awesome!","_undoexc":["status","open"]}]}]}}],"id":"mZhZG7DZkvv1ChBa"}]}',
							'html' => '[{"html":"<body><p>No content yet. Drag components in this canvas and start building something awesome!<\\/p><\\/body>","css":"* { box-sizing: border-box; } body {margin: 0;}"}]',
							'meta' => [
							],
							'link' => '/template/custom_post_type/related/post',
						],
					],
					'existsArchivePageInTheme' => true,
					'existsSinglePageInTheme' => true,
				],
				1 => [
					'id' => 'a12d54f4-2922-4ad8-a4e2-72f12e65db08',
					'name' => 'page',
					'singular' => 'Page',
					'plural' => 'Pages',
					'icon' => 'admin-page',
					'postCount' => '3',
					'supports' => [
					],
					'labels' => [
					],
					'settings' => [
					],
					'meta' => [
					],
					'taxonomies' => [
					],
					'templates' => [
					],
					'existsArchivePageInTheme' => false,
					'existsSinglePageInTheme' => true,
				],
				2 => [
					'id' => '71f3840f-cb96-4983-9c85-b5e93eee1ef9',
					'name' => 'movie',
					'singular' => 'Movie',
					'plural' => 'Movies',
					'icon' => 'admin-comments',
					'postCount' => '3',
					'supports' => [
						0 => 'title',
						1 => 'editor',
						2 => 'thumbnail',
						3 => 'excerpt',
					],
					'labels' => [
						'menu_name' => 'Movie',
						'all_items' => 'All Movies',
						'add_new' => 'Add Movie',
						'add_new_item' => 'Add Movie',
						'edit_item' => 'Edit Movie',
						'new_item' => 'New Movie',
						'view_item' => 'View Movie',
						'view_items' => 'View Movies',
						'search_item' => 'Search Movies',
						'not_found' => 'No Movie found',
						'not_found_in_trash' => 'No Movie found',
						'parent_item_colon' => 'Parent item',
						'featured_image' => 'Featured image',
						'set_featured_image' => 'Set featured image',
						'remove_featured_image' => 'Remove featured image',
						'use_featured_image' => 'Use featured image',
						'archives' => 'Archives',
						'insert_into_item' => 'Insert',
						'uploaded_to_this_item' => 'Upload',
						'filter_items_list' => 'Filter Movies list',
						'items_list_navigation' => 'Navigation list Movies',
						'items_list' => 'List Movies',
						'filter_by_date' => 'Filter by date',
						'item_published' => 'Movie published',
						'item_published_privately' => 'Movie published privately',
						'item_reverted_to_draft' => 'Movie reverted to draft',
						'item_scheduled' => 'Movie scheduled',
						'item_updated' => 'Movie updated',
					],
					'settings' => [
						'public' => true,
						'publicly_queryable' => NULL,
						'show_ui' => true,
						'show_in_menu' => true,
						'show_in_nav_menus' => true,
						'show_in_admin_bar' => true,
						'show_in_rest' => true,
						'rest_base' => NULL,
						'menu_position' => NULL,
						'capability_type' => 'post',
						'has_archive' => true,
						'rewrite' => NULL,
						'custom_rewrite' => NULL,
						'query_var' => NULL,
						'custom_query_var' => NULL,
						'show_in_graphql' => true,
						'graphql_single_name' => 'Movie',
						'graphql_plural_name' => 'Movies',
					],
					'meta' => [
					],
					'taxonomies' => [
					],
					'templates' => [
					],
					'existsArchivePageInTheme' => false,
					'existsSinglePageInTheme' => false,
				],
			],
			MetaTypes::TAXONOMY => [
				0 => [
					'id' => '074b1929-87b4-4945-9b1b-5cb84d1fb158',
					'slug' => 'category',
					'singular' => 'Category',
					'plural' => 'Categories',
					'postCount' => '1',
					'isNative' => true,
					'labels' => [
					],
					'settings' => [
						'hierarchical' => true,
					],
					'meta' => [
						0 => [
							'id' => '23c3e436-1b27-4a72-9273-dc659874c05e',
							'taxonomy' => 'category',
							'name' => 'fdsfdsfds',
							'sort' => 1,
							'fields' => [
							],
						],
					],
					'customPostTypes' => [
						0 => [
							'id' => '10f9e6c0-8372-4049-b7a3-b272f0f25268',
							'name' => 'post',
							'singular' => 'Post',
							'plural' => 'Posts',
							'icon' => 'admin-post',
							'supports' => [
							],
							'labels' => [
							],
							'settings' => [
							],
						],
					],
					'templates' => [
						0 => [
							'id' => '3c6e66f2-b66e-4997-841d-be21b6c68d76',
							'belongsTo' => MetaTypes::TAXONOMY,
							'find' => 'category',
							'metaFieldId' => NULL,
							'metaField' => NULL,
							'templateType' => 'single',
							'json' => '{"assets":[],"styles":[{"selectors":[{"name":"row","private":1}],"style":{"display":"flex","justify-content":"flex-start","align-items":"stretch","flex-wrap":"nowrap","padding-top":"10px","padding-right":"10px","padding-bottom":"10px","padding-left":"10px"}},{"selectors":[{"name":"row","private":1}],"style":{"flex-wrap":"wrap"},"mediaText":"(max-width: 768px)","atRuleType":"media"},{"selectors":[{"name":"cell","private":1}],"style":{"min-height":"75px","flex-grow":"1","flex-basis":"100%"}},{"selectors":["#ip6r"],"style":{"padding":"10px"}}],"pages":[{"frames":[{"component":{"type":"wrapper","stylable":["background","background-color","background-image","background-repeat","background-attachment","background-position","background-size"],"attributes":{"id":"i1lk"},"_undoexc":["status","open"],"components":[{"name":"Row","droppable":".cell","resizable":{"tl":0,"tc":0,"tr":0,"cl":0,"cr":0,"bl":0,"br":0,"minDim":1},"classes":[{"name":"row","private":1}],"attributes":{"id":"in0v"},"_undoexc":["status","open"],"components":[{"name":"Cell","draggable":".row","stylable-require":["flex-basis"],"unstylable":["width"],"resizable":{"tl":0,"tc":0,"tr":0,"cl":0,"cr":1,"bl":0,"br":0,"minDim":1,"bc":0,"currentUnit":1,"step":0.2,"keyWidth":"flex-basis"},"classes":[{"name":"cell","private":1}],"_undoexc":["status","open"],"components":[{"type":"text","attributes":{"id":"ip6r"},"_undoexc":["status","open"],"components":[{"type":"textnode","content":"Insert your text here cazz","_undoexc":["status","open"]}]}]},{"name":"Cell","draggable":".row","stylable-require":["flex-basis"],"unstylable":["width"],"resizable":{"tl":0,"tc":0,"tr":0,"cl":0,"cr":1,"bl":0,"br":0,"minDim":1,"bc":0,"currentUnit":1,"step":0.2,"keyWidth":"flex-basis"},"classes":[{"name":"cell","private":1}],"_undoexc":["status","open"],"components":[{"tagName":"p","type":"text","attributes":{"id":"i6ns"},"_undoexc":["status","open"],"components":[{"type":"textnode","content":"No content yet. Drag components in this canvas and start building something awesome!","_undoexc":["status","open"]}]}]}]}]}}],"id":"peT3d2H1FhTbuB7K"}]}',
							'html' => '[{"html":"<body id=\\"i1lk\\"><div id=\\"in0v\\" class=\\"row\\"><div class=\\"cell\\"><div id=\\"ip6r\\">Insert your text here cazz<\\/div><\\/div><div class=\\"cell\\"><p id=\\"i6ns\\">No content yet. Drag components in this canvas and start building something awesome!<\\/p><\\/div><\\/div><\\/body>","css":"* { box-sizing: border-box; } body {margin: 0;}.row{display:flex;justify-content:flex-start;align-items:stretch;flex-wrap:nowrap;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;}.cell{min-height:75px;flex-grow:1;flex-basis:100%;}#ip6r{padding:10px;}@media (max-width: 768px){.row{flex-wrap:wrap;}}"}]',
							'meta' => [
							],
							'link' => '/template/taxonomy/single/category',
						],
					],
				],
				1 => [
					'id' => '76b78b72-11b0-4c86-bb58-42942bf4770c',
					'slug' => 'dsadsadsa',
					'singular' => 'dd',
					'plural' => 'dd',
					'postCount' => 0,
					'isNative' => false,
					'labels' => [
						'name' => 'dsadsadsa',
						'singular_name' => 'dd',
						'search_items' => 'Search dd',
						'popular_items' => 'Popular dd',
						'all_items' => 'All dd',
						'parent_item' => 'Parent dd',
						'parent_item_colon' => 'Parent item',
						'edit_item' => 'Edit',
						'view_item' => 'View',
						'update_item' => 'Update dd',
						'add_new_item' => 'Add new dd',
						'new_item_name' => 'New dd',
						'separate_items_with_commas' => 'Separate dd with commas',
						'add_or_remove_items' => 'Add or remove dd',
						'choose_from_most_used' => 'Choose from most used dd',
						'not_found' => 'No dd found',
						'no_terms' => 'No dd',
						'filter_by_item' => 'Filter by dd',
						'items_list_navigation' => 'Navigation list dd',
						'items_list' => 'List dd',
						'most_used' => 'Most used dd',
						'back_to_items' => 'Back to dd',
					],
					'settings' => [
						'public' => true,
						'publicly_queryable' => NULL,
						'hierarchical' => true,
						'show_ui' => true,
						'show_in_menu' => true,
						'show_in_nav_menus' => true,
						'show_in_rest' => true,
						'rest_base' => NULL,
						'rest_controller_class' => NULL,
						'show_tagcloud' => true,
						'show_in_quick_edit' => true,
						'show_admin_column' => true,
						'capabilities' => [
							0 => 'manage_terms',
							1 => 'edit_terms',
							2 => 'delete_terms',
							3 => 'assign_terms',
						],
						'rewrite' => NULL,
						'custom_rewrite' => NULL,
						'query_var' => NULL,
						'custom_query_var' => NULL,
						'default_term' => NULL,
						'sort' => NULL,
					],
					'meta' => [
					],
					'customPostTypes' => [
					],
					'templates' => [
					],
				],
				2 => [
					'id' => '841145af-837e-427f-b512-d2c64c02b485',
					'slug' => 'post_tag',
					'singular' => 'Tag',
					'plural' => 'Tags',
					'postCount' => 0,
					'isNative' => true,
					'labels' => [
					],
					'settings' => [
						'hierarchical' => true,
					],
					'meta' => [
					],
					'customPostTypes' => [
						0 => [
							'id' => '10f9e6c0-8372-4049-b7a3-b272f0f25268',
							'name' => 'post',
							'singular' => 'Post',
							'plural' => 'Posts',
							'icon' => 'admin-post',
							'supports' => [
							],
							'labels' => [
							],
							'settings' => [
							],
						],
					],
					'templates' => [
						0 => [
							'id' => '5c166041-1fa4-4d14-8f81-4b2a78e7759e',
							'belongsTo' => MetaTypes::TAXONOMY,
							'find' => 'post_tag',
							'metaFieldId' => NULL,
							'metaField' => NULL,
							'templateType' => 'single',
							'json' => '{"assets":[],"styles":[{"selectors":["#it2k"],"style":{"padding":"10px"}}],"pages":[{"frames":[{"component":{"type":"wrapper","stylable":["background","background-color","background-image","background-repeat","background-attachment","background-position","background-size"],"_undoexc":["status","open"],"components":[{"tagName":"p","type":"text","_undoexc":["status","open"],"components":[{"type":"textnode","content":"No content yet. Drag components in this canvas and start building something awesome!","_undoexc":["status","open"]}]},{"type":"text","attributes":{"id":"it2k"},"_undoexc":["status","open"],"components":[{"type":"textnode","content":"Insert your text here dsadsadsadsa","_undoexc":["status","open"]}]}]}}],"id":"VYTLJVPcyDPpLGRg"}]}',
							'html' => '[{"html":"<body><p>No content yet. Drag components in this canvas and start building something awesome!<\\/p><div id=\\"it2k\\">Insert your text here dsadsadsadsa<\\/div><\\/body>","css":"* { box-sizing: border-box; } body {margin: 0;}#it2k{padding:10px;}"}]',
							'meta' => [
							],
							'link' => '/template/taxonomy/single/post_tag',
						],
					],
				],
			],
			MetaTypes::OPTION_PAGE => [],
			MetaTypes::USER => [
				0 => [
					'id' => '0d34d4d1-ce4f-4f74-9416-25246e48e885',
					'title' => 'dsa',
					'sort' => 1,
					'belongsTo' => MetaTypes::USER,
					'fields' => [
						0 => [
							'id' => 'e7b388d9-194a-4fb6-80bf-d1fa8d20cf68',
							'boxId' => '0d34d4d1-ce4f-4f74-9416-25246e48e885',
							'db_name' => 'dsads',
							'ui_name' => 'Dsads',
							'name' => 'dsads',
							'type' => 'Text',
							'defaultValue' => '',
							'description' => '',
							'isRequired' => false,
							'showInArchive' => false,
							'sort' => 1,
							'advancedOptions' => [
							],
							'options' => [
							],
							'visibilityConditions' => [
							],
						],
					],
				],
			],
		];

		ImportRepository::import($data);

		$this->assertEquals(1,1);

		delete_acpt_meta('post');
		delete_acpt_meta('page');
		delete_acpt_meta('movie');
		delete_acpt_tax_meta('category');
		delete_acpt_tax_meta('dsadsadsa');
		delete_acpt_tax_meta('post_tag');
		delete_acpt_user_meta();
	}
}