<?php

use ACPT\Core\Helper\Uuid;
use ACPT\Core\Models\OptionPage\OptionPageModel;
use ACPT\Core\Repository\OptionPageRepository;
use ACPT\Core\Validators\ArgumentsArrayValidator;

if( !function_exists('register_acpt_option_page') ){
	function register_acpt_option_page($args = [])
	{
		try {
			// validate array
			$mandatory_keys = [
				'menu_slug' => [
					'required' => true,
					'type' => 'string',
				],
				'page_title' => [
					'required' => true,
					'type' => 'string',
				],
				'menu_title' => [
					'required' => true,
					'type' => 'string',
				],
				'icon' => [
					'required' => false,
					'type' => 'string',
				],
				'capability' => [
					'required' => true,
					'type' => 'string',
					'enum' => [
						'moderate_comments',
						'manage_options',
						'manage_categories',
						'manage_links',
						'unfiltered_html',
						'edit_others_posts',
						'edit_pages',
						'edit_others_pages',
						'edit_published_pages',
						'publish_pages',
						'delete_pages',
						'delete_others_pages',
						'delete_published_pages',
						'delete_others_posts',
						'delete_private_posts',
						'edit_private_posts',
						'read_private_posts',
						'delete_private_pages',
						'edit_private_pages',
						'read_private_pages',
					],
				],
				'description' => [
					'required' => false,
					'type' => 'string',
				],
				'parent' => [
					'required' => false,
					'type' => 'string',
				],
				'position' => [
					'required' => true,
					'type' => 'integer',
				]
			];

			$validator = new ArgumentsArrayValidator();

			if(!$validator->validate($mandatory_keys, $args)){
				return false;
			}

			$id = (OptionPageRepository::exists($args["menu_slug"])) ? OptionPageRepository::getByMenuSlug($args["menu_slug"])->getId() : Uuid::v4();
			$parentId = null;

			if(isset($args['parent'])){
				$parentId = (OptionPageRepository::exists($args["parent"])) ? OptionPageRepository::getByMenuSlug($args["parent"])->getId() : null;

				if(!$parentId){
					return false;
				}
			}

			$sort = count(OptionPageRepository::getAllIds()) + 1;

			$optionPageModel = OptionPageModel::hydrateFromArray([
				'id' => $id,
				'parentId' => $parentId,
				'pageTitle' => $args["page_title"],
				'menuTitle' => $args["menu_title"],
				'capability' => $args["capability"],
				'menuSlug' => $args["menu_slug"],
				'position' => $args["position"],
				'icon' => isset($args["icon"]) ? $args["icon"] : null,
				'description' => $args["description"],
				'sort' => $sort
			]);

			OptionPageRepository::save($optionPageModel);

			return true;

		} catch (\Exception $exception){
			return false;
		}
	}
}

if( !function_exists('delete_acpt_option_page') ){
	function delete_acpt_option_page($page_slug, $delete_options = false)
	{
		if(!OptionPageRepository::exists($page_slug)){
			return false;
		}

		try {
			$option_page = OptionPageRepository::getByMenuSlug($page_slug);
			OptionPageRepository::delete($option_page, $delete_options);

			return true;

		} catch (\Exception $exception){
			return false;
		}
	}
}