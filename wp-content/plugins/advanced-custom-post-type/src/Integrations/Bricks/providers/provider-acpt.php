<?php

namespace Bricks\Integrations\Dynamic_Data\Providers;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;
use ACPT\Core\Models\OptionPage\OptionPageModel;
use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;
use ACPT\Core\Models\Taxonomy\TaxonomyModel;
use ACPT\Core\Repository\OptionPageRepository;
use ACPT\Core\Repository\TaxonomyRepository;
use ACPT\Costants\MetaTypes;
use ACPT\Utils\Data\DataAggregator;
use ACPT\Utils\Wordpress\Translator;
use ACPT\Utils\Wordpress\WPAttachment;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Provider_ACPT extends Base
{
	/**
	 * @return bool
	 */
	public static function load_me()
	{
		return class_exists( 'ACPT' );
	}

	/**
	 * Register tags
	 */
	public function register_tags()
	{
		$fields = self::get_fields();

		foreach ( $fields as $field ) {
			$this->register_tag( $field );
		}

		// var_dump(self::get_fields()[25]); die();
	}

	/**
	 * @param       $field
	 * @param array $parent_field
	 */
	private function register_tag( $field, $parent_field = [] )
	{
		$type = $field['type'];
		$contexts = self::get_fields_by_context();

		if ( ! isset( $contexts[ $type ] ) ) {
			return;
		}

		$contextsByType = $contexts[ $type ];
		$belongsTo = $field['belongsTo'];

		switch ($belongsTo){
			case MetaTypes::TAXONOMY:
				$prefixName = 'acpt_tax_';
				break;

			case MetaTypes::OPTION_PAGE:
				$prefixName = 'acpt_option_';
				break;

			default:
			case MetaTypes::CUSTOM_POST_TYPE:
				$prefixName = 'acpt_';
				break;
		}

		$name = $prefixName . $field['slug'];
		$label = $field['name'];

		$tag = [
			'name'     => '{' . $name . '}',
			'label'    => $label,
			'group'    => $field['group_name'],
			'field'    => $field,
			'provider' => $this->name,
			'contexts' => $contextsByType,
		];

		if ( ! empty( $parent_field ) ) {

			// Add the parent field attributes to the child tag so we could retrieve the value of group sub-fields
			$tag['parent'] = [
				'slug'        => $parent_field['slug'],
				'name'        => $parent_field['name'],
				'type'        => $parent_field['type'],
				'box_name'    => $parent_field['box_name'],
				'field_name'  => ((isset($parent_field['parent_field_name'])) ? $parent_field['parent_field_name'] : $parent_field['field_name'] ),
				'block_name'  => ((isset($parent_field['block_name'])) ? $parent_field['block_name'] : null),
			];
		}

		// List/Repeater field (loop)
		if ( in_array ( $type, [ CustomPostTypeMetaBoxFieldModel::REPEATER_TYPE, CustomPostTypeMetaBoxFieldModel::LIST_TYPE ] ) and empty($parent_field) ) {
			$this->loop_tags[ $name ] = $tag;

			// Check for sub-fields (including group field sub-fields)
			if ( ! empty( $field['children'] ) ) {
				foreach ( $field['children'] as $sub_field ) {
					$this->register_tag( $sub_field, $field ); // Recursive
				}
			}
		}
		// Flexible field blocks (loop)
		elseif($type === CustomPostTypeMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE){

			if(isset($tag['field']) and isset($tag['field']['children']) and is_array($tag['field']['children'])){
				foreach ($tag['field']['children'] as $block){
					$block['label'] =  $block['name'];
					$this->loop_tags[ $block['slug'] ] = $block;

					if(isset($block['children']) and is_array($block['children'])){
						foreach ( $block['children'] as $sub_field ) {
							$this->register_tag( $sub_field, $block ); // Recursive
						}
					}
				}
			}

		} else {
			// Regular fields
			$this->tags[ $name ] = $tag;
		}
	}

	/**
	 * @return array
	 */
	public static function get_fields() {

		if ( ! function_exists( 'get_acpt_meta_objects' ) or
		     ! function_exists( 'get_acpt_field_object' ) or
		     ! function_exists('get_acpt_field') or
		     ! function_exists('get_acpt_tax_meta_objects') or
		     ! function_exists('get_acpt_option_page_meta_objects')
		) {
			return [];
		}

		$post_types = self::get_post_types();
		$acpt_meta_objects = [];
		$fields = [];

		// CPT meta
		foreach ($post_types as $post_type){
			$acpt_meta_objects[$post_type] = array_merge($acpt_meta_objects, get_acpt_meta_objects($post_type));
		}

		foreach ($acpt_meta_objects as $custom_post_type => $acpt_meta_box_objects){
			foreach ($acpt_meta_box_objects as $acpt_meta_box_object){
				if(isset($acpt_meta_box_object->fields)){
					foreach ($acpt_meta_box_object->fields as $acpt_meta_field) {
						$field_slug = $custom_post_type . ' ' . $acpt_meta_box_object->box_name . ' ' . $acpt_meta_field->field_name;
						$field_slug = strtolower(str_replace(' ', '_', $field_slug));
						$field_type = $acpt_meta_field->field_type;
						$group_name = 'ACPT';
						$display_field_name = '['.Translator::translate($custom_post_type) . '] - ' . $acpt_meta_box_object->box_name . ' ' . $acpt_meta_field->field_name;

						$children = [];

						// Repeater fields
						if( isset($acpt_meta_field->children) and ! empty( $acpt_meta_field->children ) ){
							foreach ($acpt_meta_field->children as $child_field){
								$child_field_slug = $field_slug . ' ' . $child_field->field_name;
								$child_field_slug = strtolower(str_replace(' ', '_', $child_field_slug));
								$child_field_type = $child_field->field_type;
								$child_display_field_name = $display_field_name . ' ' . $child_field->field_name;

								$children[] = [
									'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
									'slug' => $child_field_slug,
									'type' => $child_field_type,
									'group_name' => $group_name,
									'name' => $child_display_field_name,
									'box_name' => $acpt_meta_box_object->box_name,
									'field_name' => $child_field->field_name,
									'parent_field_name' => $acpt_meta_field->field_name,
								];
							}
						}

						// Flexible fields
						if( isset($acpt_meta_field->blocks) and ! empty( $acpt_meta_field->blocks ) ){
							foreach ($acpt_meta_field->blocks as $child_block){

								$block_slug = $field_slug . ' ' . $child_block->block_name;
								$block_slug = strtolower(str_replace(' ', '_', $block_slug));
								$block_display_name = $display_field_name . ' ' . $child_block->block_name;

								$nested_fields = [];

								if(isset($child_block->fields) and is_array($child_block->fields) and !empty($child_block->fields)){
									foreach ($child_block->fields as $nested_field){
										$nested_field_slug = $field_slug . ' ' . $child_block->block_name . ' ' . $nested_field->field_name;
										$nested_field_slug = strtolower(str_replace(' ', '_', $nested_field_slug));
										$nested_field_type = $nested_field->field_type;
										$nested_display_field_name = $display_field_name . ' ' . $child_block->block_name . ' ' . $nested_field->field_name;

										$nested_fields[] = [
											'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
											'slug' => $nested_field_slug,
											'type' => $nested_field_type,
											'group_name' => $group_name,
											'name' => $nested_display_field_name,
											'box_name' => $acpt_meta_box_object->box_name,
											'field_name' => $nested_field->field_name,
											'parent_field_name' => $acpt_meta_field->field_name,
											'parent_block_name' => $child_block->block_name,
										];
									}
								}

								$children[] = [
									'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
									'slug' => $block_slug,
									'type' => 'Block',
									'group_name' => $group_name,
									'name' => $block_display_name,
									'box_name' => $acpt_meta_box_object->box_name,
									'parent_field_name' => $acpt_meta_field->field_name,
									'block_name' => $child_block->block_name,
									'children' => $nested_fields,
								];
							}
						}

						// Add List fields children
						if($acpt_meta_field->field_type === CustomPostTypeMetaBoxFieldModel::LIST_TYPE){

							$child_field_slug = $field_slug . '_value';
							$child_field_slug = strtolower(str_replace(' ', '_', $child_field_slug));
							$child_field_type = CustomPostTypeMetaBoxFieldModel::TEXT_TYPE;
							$child_display_field_name = $display_field_name . ' value';

							$children[] = [
								'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
								'slug' => $child_field_slug,
								'type' => $child_field_type,
								'group_name' => $group_name,
								'name' => $child_display_field_name,
								'box_name' => $acpt_meta_box_object->box_name,
								'field_name' => $acpt_meta_field->field_name,
								'parent_field_name' => $acpt_meta_field->field_name,
							];
						}

						$fields[] = [
							'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
							'slug' => $field_slug,
							'type' => $field_type,
							'group_name' => $group_name,
							'name' => $display_field_name,
							'box_name' => $acpt_meta_box_object->box_name,
							'field_name' => $acpt_meta_field->field_name,
							'children' => $children,
						];
					}
				}
			}
		}

		// Taxonomy meta
		$taxonomies = self::get_taxonomies();
		$acpt_meta_tax_objects = [];

		foreach ($taxonomies as $taxonomy){
			$acpt_meta_tax_objects[$taxonomy->getSlug()] = get_acpt_tax_meta_objects($taxonomy->getSlug());
		}

		foreach ($acpt_meta_tax_objects as $taxonomy => $acpt_meta_box_objects){
			foreach ($acpt_meta_box_objects as $acpt_meta_box_object){
				if(isset($acpt_meta_box_object->fields)){

					$terms = get_terms( [
						'taxonomy' => $taxonomy,
						'hide_empty' => false,
					] );

					foreach ($terms as $term){
						foreach ($acpt_meta_box_object->fields as $acpt_meta_field) {

							$field_slug = $taxonomy . ' ' . $acpt_meta_box_object->box_name . ' ' . $acpt_meta_field->field_name;
							$field_slug = strtolower(str_replace(' ', '_', $field_slug));
							$field_type = $acpt_meta_field->field_type;
							$group_name = 'ACPT';
							$display_field_name = '['.Translator::translate($taxonomy) . '] - ' . $acpt_meta_box_object->box_name . ' ' . $acpt_meta_field->field_name;

							$children = [];
							if( isset($acpt_meta_field->children) and ! empty( $acpt_meta_field->children ) ){
								foreach ($acpt_meta_field->children as $child_field){
									$child_field_slug = $field_slug . ' ' . $child_field->field_name;
									$child_field_slug = strtolower(str_replace(' ', '_', $child_field_slug));
									$child_field_type = $child_field->field_type;
									$child_display_field_name = $display_field_name . ' ' . $child_field->field_name;

									$children[] = [
										'belongsTo' => MetaTypes::TAXONOMY,
										'taxonomy' => $taxonomy,
										'slug' => $child_field_slug,
										'type' => $child_field_type,
										'group_name' => $group_name,
										'name' => $child_display_field_name,
										'box_name' => $acpt_meta_box_object->box_name,
										'field_name' => $child_field->field_name,
										'parent_field_name' => $acpt_meta_field->field_name,
									];
								}
							}

							// add children di LIST field
							if($acpt_meta_field->field_type === TaxonomyMetaBoxFieldModel::LIST_TYPE){

								$child_field_slug = $field_slug . '_value';
								$child_field_slug = strtolower(str_replace(' ', '_', $child_field_slug));
								$child_field_type = TaxonomyMetaBoxFieldModel::TEXT_TYPE;
								$child_display_field_name = $display_field_name . ' value';

								$children[] = [
									'belongsTo' => MetaTypes::TAXONOMY,
									'slug' => $child_field_slug,
									'type' => $child_field_type,
									'group_name' => $group_name,
									'name' => $child_display_field_name,
									'box_name' => $acpt_meta_box_object->box_name,
									'field_name' => $acpt_meta_field->field_name,
									'parent_field_name' => $acpt_meta_field->field_name,
								];
							}

							$fields[] = [
								'belongsTo' => MetaTypes::TAXONOMY,
								'taxonomy' => $taxonomy,
								'slug' => $field_slug,
								'type' => $field_type,
								'group_name' => $group_name,
								'name' => $display_field_name,
								'box_name' => $acpt_meta_box_object->box_name,
								'field_name' => $acpt_meta_field->field_name,
								'children' => $children,
							];
						}
					}
				}
			}
		}

		// Options page meta
		$option_pages = self::get_option_pages();
		$acpt_meta_option_page_objects = [];

		foreach ($option_pages as $option_page){
			$acpt_meta_option_page_objects[$option_page->getPageTitle()] = [
				'menu_slug' => $option_page->getMenuSlug(),
				'meta_objects' => get_acpt_option_page_meta_objects($option_page->getMenuSlug()),
			];

			foreach ($option_page->getChildren() as $child_option_page){
				$acpt_meta_option_page_objects[$child_option_page->getPageTitle()] = [
					'menu_slug' => $child_option_page->getMenuSlug(),
					'meta_objects' => get_acpt_option_page_meta_objects($child_option_page->getMenuSlug()),
				];
			}
		}

		foreach ($acpt_meta_option_page_objects as $option_page => $acpt_meta_box_objects){

			$menu_slug = $acpt_meta_box_objects['menu_slug'];
			$meta_objects = $acpt_meta_box_objects['meta_objects'];

			foreach ($meta_objects as $acpt_meta_box_object) {
				if(isset($acpt_meta_box_object->fields)){
					foreach ($acpt_meta_box_object->fields as $acpt_meta_field) {
						$field_slug = $menu_slug . ' ' . $acpt_meta_box_object->box_name . ' ' . $acpt_meta_field->field_name;
						$field_slug = strtolower(str_replace(' ', '_', $field_slug));
						$field_type = $acpt_meta_field->field_type;
						$group_name = 'ACPT';
						$display_field_name = '['.Translator::translate($option_page) . '] - ' . $acpt_meta_box_object->box_name . ' ' . $acpt_meta_field->field_name;

						$children = [];

						// Repeater field
						if( isset($acpt_meta_field->children) and ! empty( $acpt_meta_field->children ) ){
							foreach ($acpt_meta_field->children as $child_field){
								$child_field_slug = $field_slug . ' ' . $child_field->field_name;
								$child_field_slug = strtolower(str_replace(' ', '_', $child_field_slug));
								$child_field_type = $child_field->field_type;
								$child_display_field_name = $display_field_name . ' ' . $child_field->field_name;

								$children[] = [
									'option_page' => $menu_slug,
									'belongsTo' => MetaTypes::OPTION_PAGE,
									'slug' => $child_field_slug,
									'type' => $child_field_type,
									'group_name' => $group_name,
									'name' => $child_display_field_name,
									'box_name' => $acpt_meta_box_object->box_name,
									'field_name' => $child_field->field_name,
									'parent_field_name' => $acpt_meta_field->field_name,
								];
							}
						}

						// Flexible fields
						if( isset($acpt_meta_field->blocks) and ! empty( $acpt_meta_field->blocks ) ){
							foreach ($acpt_meta_field->blocks as $child_block){

								$block_slug = $field_slug . ' ' . $child_block->block_name;
								$block_slug = strtolower(str_replace(' ', '_', $block_slug));
								$block_display_name = $display_field_name . ' ' . $child_block->block_name;

								$nested_fields = [];

								if(isset($child_block->fields) and is_array($child_block->fields) and !empty($child_block->fields)){
									foreach ($child_block->fields as $nested_field){
										$nested_field_slug = $field_slug . ' ' . $child_block->block_name . ' ' . $nested_field->field_name;
										$nested_field_slug = strtolower(str_replace(' ', '_', $nested_field_slug));
										$nested_field_type = $nested_field->field_type;
										$nested_display_field_name = $display_field_name . ' ' . $child_block->block_name . ' ' . $nested_field->field_name;

										$nested_fields[] = [
											'option_page' => $menu_slug,
											'belongsTo' => MetaTypes::OPTION_PAGE,
											'slug' => $nested_field_slug,
											'type' => $nested_field_type,
											'group_name' => $group_name,
											'name' => $nested_display_field_name,
											'box_name' => $acpt_meta_box_object->box_name,
											'field_name' => $nested_field->field_name,
											'parent_field_name' => $acpt_meta_field->field_name,
											'parent_block_name' => $child_block->block_name,
										];
									}
								}

								$children[] = [
									'option_page' => $menu_slug,
									'belongsTo' => MetaTypes::OPTION_PAGE,
									'slug' => $block_slug,
									'type' => 'Block',
									'group_name' => $group_name,
									'name' => $block_display_name,
									'box_name' => $acpt_meta_box_object->box_name,
									'parent_field_name' => $acpt_meta_field->field_name,
									'block_name' => $child_block->block_name,
									'children' => $nested_fields,
								];
							}
						}

						// Add List fields children
						if($acpt_meta_field->field_type === OptionPageMetaBoxFieldModel::LIST_TYPE){

							$child_field_slug = $field_slug . '_value';
							$child_field_slug = strtolower(str_replace(' ', '_', $child_field_slug));
							$child_field_type = OptionPageMetaBoxFieldModel::TEXT_TYPE;
							$child_display_field_name = $display_field_name . ' value';

							$children[] = [
								'belongsTo' => MetaTypes::OPTION_PAGE,
								'slug' => $child_field_slug,
								'type' => $child_field_type,
								'group_name' => $group_name,
								'name' => $child_display_field_name,
								'box_name' => $acpt_meta_box_object->box_name,
								'field_name' => $acpt_meta_field->field_name,
								'parent_field_name' => $acpt_meta_field->field_name,
							];
						}

						$fields[] = [
							'option_page' => $menu_slug,
							'belongsTo' => MetaTypes::OPTION_PAGE,
							'slug' => $field_slug,
							'type' => $field_type,
							'group_name' => $group_name,
							'name' => $display_field_name,
							'box_name' => $acpt_meta_box_object->box_name,
							'field_name' => $acpt_meta_field->field_name,
							'children' => $children,
						];
					}
				}
			}
		}

		return $fields;
	}

	/**
	 * @return array
	 */
	private static function get_post_types()
	{
		return get_post_types( [
			'public'  => true,
			'show_ui' => true,
		], 'names', 'and' );
	}

	/**
	 * @return TaxonomyModel[]
	 */
	private static function get_taxonomies()
	{
		try {
			return TaxonomyRepository::get();
		} catch (\Exception $exception){
			return [];
		}
	}

	/**
	 * @return OptionPageModel[]
	 */
	private static function get_option_pages()
	{
		try {
			return OptionPageRepository::get();
		} catch (\Exception $exception){
			return [];
		}
	}

	/**
	 * Get tag value main function
	 *
	 * @param string $tag
	 * @param \WP_Post $post
	 * @param array $args
	 * @param string $context
	 *
	 * @return array|string|void
	 * @throws \Exception
	 */
	public function get_tag_value( $tag, $post, $args, $context )
	{
		$post_id = isset( $post->ID ) ? $post->ID : '';

		$field = $this->tags[ $tag ]['field'];
		$contexts = $this->tags[ $tag ]['contexts'];

		if( ! in_array( $context, $contexts )){
			return;
		}

		// STEP: Check for filter args
		$filters = $this->get_filters_from_args( $args );

		// STEP: Get the value
		$raw_acpt_value = $this->get_raw_value( $tag, $post_id );

		// render tag depending on its type
		switch ($field['type']){

			case CustomPostTypeMetaBoxFieldModel::RATING_TYPE:

				if(!empty($raw_acpt_value)){
					$value = ($raw_acpt_value/2) . "/5";
				} else {
					$value = $raw_acpt_value;
				}

				break;

			case CustomPostTypeMetaBoxFieldModel::CURRENCY_TYPE:

				$value = $this->render_amount_field($raw_acpt_value['amount'], $raw_acpt_value['unit']);
				break;

			case CustomPostTypeMetaBoxFieldModel::DATE_TYPE:

				$value = null;
				if($raw_acpt_value !== null and $raw_acpt_value !== ''){
					$default_format = 'Y-m-d';
					$date = \DateTime::createFromFormat( $default_format, $raw_acpt_value );
					$value = $date->format( 'U' );
					$filters['object_type'] = 'date';
				}

				break;

			case CustomPostTypeMetaBoxFieldModel::EMAIL_TYPE:

				$filters['link'] = true;
				$value = '<a href="mailto:'.$raw_acpt_value.'">'.$raw_acpt_value.'</a>';
				break;

			case CustomPostTypeMetaBoxFieldModel::LENGTH_TYPE:

				$value = $this->render_amount_field($raw_acpt_value['length'], $raw_acpt_value['unit']);
				break;

			case CustomPostTypeMetaBoxFieldModel::SELECT_MULTI_TYPE:
			case CustomPostTypeMetaBoxFieldModel::CHECKBOX_TYPE:

				$value = $this->render_list_item($raw_acpt_value);
				break;

			case CustomPostTypeMetaBoxFieldModel::FILE_TYPE:

				$filters['object_type'] = 'media';
				$filters['link'] = true;

				if(isset($raw_acpt_value['file']) and $this->isAnAttachment($raw_acpt_value['file'])){
					$wpAttachment = $raw_acpt_value['file'];
					$value = [$wpAttachment->getId()];
				} elseif(isset($raw_acpt_value['file']) and !empty($raw_acpt_value['file']) and $this->isAnAttachment($raw_acpt_value['file'])){
					$value = [$raw_acpt_value['file']->getId()];
				} else {
					$value = [];
				}

				break;

			case CustomPostTypeMetaBoxFieldModel::VIDEO_TYPE:

				$filters['video']   = true;
				$filters['object_type'] = 'media';

				if(is_array($raw_acpt_value)){
					$value = [];

					foreach ($raw_acpt_value as $img){
						if($this->isAnAttachment($img)){
							$value[] = $img->getId();
						}
					}
				} else {
					if($this->isAnAttachment($raw_acpt_value)){
						$value = [$raw_acpt_value->getId()];
					}
				}

				break;

			case CustomPostTypeMetaBoxFieldModel::GALLERY_TYPE:
			case CustomPostTypeMetaBoxFieldModel::IMAGE_TYPE:

				$filters['object_type'] = 'media';
				$filters['image']   = true;
				$filters['separator']   = '';

				// check is a single WPAttachment or not
				if($this->isAnAttachment($raw_acpt_value)){
					$value = [$raw_acpt_value->getId()];
				} else {
					$value = [];

					if(is_array($raw_acpt_value)){
						foreach ($raw_acpt_value as $img){
							if($this->isAnAttachment($img)){
								$value[] = $img->getId();
							} else {
								if(is_array($img)){
									foreach ($img as $nested_img){
										if($this->isAnAttachment($nested_img)){
											$value[] = $nested_img->getId();
										}
									}
								}
							}
						}
					}
				}

				break;

			case CustomPostTypeMetaBoxFieldModel::TOGGLE_TYPE:
				$value = $raw_acpt_value ? esc_html__( 'True', 'bricks' ) : esc_html__( 'False', 'bricks' );
				break;

			case CustomPostTypeMetaBoxFieldModel::WEIGHT_TYPE:

				$value = $this->render_amount_field($raw_acpt_value['weight'], $raw_acpt_value['unit']);
				break;

			case CustomPostTypeMetaBoxFieldModel::URL_TYPE:

				if(empty($raw_acpt_value)){
					$value = null;
				} else {

					if(!isset($raw_acpt_value['url'])){
						return null;
					}

					$filters['link'] = true;
					$value = $raw_acpt_value['url'];
				}

				break;

			default:
				$value = $raw_acpt_value;
		}

		$value = $this->format_value_for_context( $value, $tag, $post_id, $filters, $context );

		return $value;
	}

	/**
	 * @param string $imgUrl
	 *
	 * @return int
	 */
	private function attachment_url_to_postID($imgUrl)
	{
		$postId = attachment_url_to_postid($imgUrl);

		// try to find a scaled version
		if($postId === 0){
			$path = pathinfo($imgUrl);

			if(!is_array($path)){
				return 0;
			}

			$newFilename = $path['filename'] . '-scaled';
			$postId = attachment_url_to_postid($path['dirname'] . '/' . $newFilename . '.' . $path['extension']);
		}

		return $postId;
	}

	/**
	 * @param $amount
	 * @param $unit
	 *
	 * @return string
	 */
	private function render_amount_field($amount, $unit)
	{
		return $amount . ' ' . $unit;
	}

	/**
	 * @param $raw_acpt_value
	 *
	 * @return string
	 */
	private function render_list_item($raw_acpt_value)
	{
		if(!is_array($raw_acpt_value)){
			return null;
		}

		$value = '<ul>';

		foreach ($raw_acpt_value as $item){
			$value .= '<li>' . $item . '</li>';
		}

		$value .= '</ul>';

		return $value;
	}

	/**
	 * @param $tag
	 * @param $post_id
	 *
	 * @return array|mixed|string|null
	 * @throws \Exception
	 */
	private function get_raw_value( $tag, $post_id )
	{
		$tag_object = $this->tags[ $tag ];
		$field      = $tag_object['field'];

		if ( \Bricks\Query::is_looping() ) {

			// Check if this loop belongs to this provider
			$query_type = \Bricks\Query::get_query_object_type(); // post or term

			// Flexible/Repeater/List fields
			if ( array_key_exists( $query_type, $this->loop_tags ) ) {

				$parent_tag = $this->loop_tags[ $query_type ];
				$query_loop_object = \Bricks\Query::get_loop_object();

				// Render a field nested in List or Repeater field
				if (
					isset( $parent_tag['field']['slug'] ) &&
					isset( $tag_object['parent']['slug'] ) &&
					$parent_tag['field']['slug'] == $tag_object['parent']['slug']
				) {

					// For List field
					if($parent_tag['field']['type'] === AbstractMetaBoxFieldModel::LIST_TYPE){
						return $query_loop_object;
					}

					// For Repeater field: sub-field not found in the loop object (array)
					if($parent_tag['field']['type'] === AbstractMetaBoxFieldModel::REPEATER_TYPE){
						if ( ! is_array( $query_loop_object ) || ! array_key_exists( $field['field_name'], $query_loop_object ) ) {
							return '';
						}
					}

					return $query_loop_object[ $field['field_name'] ];
				}

				// Render a field nested in a block
				if($parent_tag['type'] === 'Block'){

					// calculate the numeric index of $query_loop_object
					$nested_child_index = 0;

					if(isset($parent_tag['children']) and is_array($parent_tag['children'])){
						foreach ($parent_tag['children'] as $nested_field_index => $nested_field){
							if($nested_field['slug'] === $field['slug']){
								$nested_child_index = $nested_field['field_name'];
							}
						}
					}

					return (isset($query_loop_object[$nested_child_index])) ? $query_loop_object[$nested_child_index] : null;
				}

			} elseif($query_type === 'term' and $field['belongsTo'] === MetaTypes::TAXONOMY){
				// Loop of taxonomies
				$query_loop_object = \Bricks\Query::get_loop_object();
				$field['term_id'] = $query_loop_object->term_id;

				return $this->get_acpt_value($field);
			}
		}

		// Display taxonomy meta for the current post
		if($field['belongsTo'] === MetaTypes::TAXONOMY) {
			global $post;
			$terms = get_the_terms($post->ID, $field['taxonomy']);

			$acpt_values = [];

			if(!empty($terms)){
				foreach ($terms as $term){
					$field['term_id'] = $term->term_id;
					$acpt_values[] = $this->get_acpt_value($field);
				}
			}

			return $acpt_values;
		}

		// STEP: Still here, get the regular value for this field
		$field['post_id'] = $post_id;

		return $this->get_acpt_value($field);
	}

	/**
	 * @param $field
	 *
	 * @return mixed|null
	 * @throws \Exception
	 */
	private function get_acpt_value($field)
	{
		switch ($field['belongsTo']){
			case MetaTypes::OPTION_PAGE:
				return $this->get_option_page_meta_value($field);

			case MetaTypes::TAXONOMY:
				return $this->get_tax_meta_value($field);

			default:
			case MetaTypes::CUSTOM_POST_TYPE:
				return $this->get_post_meta_value($field);
		}
	}

	/**
	 * @param $post_id
	 * @param $field
	 *
	 * @return array|mixed|null
	 * @throws \Exception
	 */
	private function get_post_meta_value($field)
	{
		if(!isset($field['post_id']) and !isset($field['box_name']) and !isset($field['field_name'])){
			return null;
		}

		// flexible field block nested element
		if(isset($field['parent_field_name'])){
			$acpt_value = get_acpt_field([
				'post_id' => $field['post_id'],
				'box_name' => $field['box_name'],
				'field_name' => $field['parent_field_name'],
			]);

			$nested_values = [];

			if(is_array($acpt_value)){
				foreach ($acpt_value as $index => $nested_value){
					if(is_acpt_field_visible([
						'post_id' => $field['post_id'],
						'box_name' => $field['box_name'],
						'parent_field_name' => $field['parent_field_name'],
						'field_name' => $field['field_name'],
						'index' => $index,
					])){
						$nested_values[] = get_acpt_child_field([
							'post_id' => $field['post_id'],
							'box_name' => $field['box_name'],
							'parent_field_name' => $field['parent_field_name'],
							'field_name' => $field['field_name'],
							'index' => $index,
						]);
					}
				}
			}

			return $nested_values;
		}

		// repeater field nested element
		if(isset($field['children']) and !empty($field['children'])){

			// Example:
			//
			// 0 => [fancy => ciao]
			// 1 => [fancy => dsgffds fdsfddsf]
			// 2 => [fancy => dfsdfs]
			//
			$get_acpt_field = get_acpt_field([
				'post_id' => $field['post_id'],
				'box_name' => $field['box_name'],
				'field_name' => $field['field_name'],
			]);

			if(is_array($get_acpt_field)){


				foreach ($field['children'] as $child_field){

					for($i = 0; $i < count($get_acpt_field); $i++){
						$is_acpt_field_visible = is_acpt_field_visible([
							'post_id' => $field['post_id'],
							'box_name' => $child_field['box_name'],
							'parent_field_name' => $child_field['parent_field_name'],
							'field_name' => $child_field['field_name'],
							'index' => $i,
						]);

						if(!$is_acpt_field_visible){
							if(isset($get_acpt_field[$i][$child_field['field_name']])){
								unset($get_acpt_field[$i][$child_field['field_name']]);
							}
						}
					}
				}
			}

			return $get_acpt_field;
		}

		if(!is_acpt_field_visible([
			'post_id' => $field['post_id'],
			'box_name' => $field['box_name'],
			'field_name' => $field['field_name'],
		])){
			return null;
		}

		return get_acpt_field([
			'post_id' => $field['post_id'],
			'box_name' => $field['box_name'],
			'field_name' => $field['field_name'],
		]);
	}

	/**
	 * @param $field
	 *
	 * @return array|mixed|null
	 * @throws \Exception
	 */
	private function get_tax_meta_value($field)
	{
		if(!isset($field['term_id']) and !isset($field['box_name']) and !isset($field['field_name'])){
			return null;
		}

		// if child element
		if(isset($field['parent_field_name'])){
			$acpt_value = get_acpt_tax_field([
				'term_id' => $field['term_id'],
				'box_name' => $field['box_name'],
				'field_name' => $field['parent_field_name'],
			]);

			$nested_values = [];

			if(is_array($acpt_value)){
				foreach ($acpt_value as $index => $nested_value){
					$nested_values[] = get_acpt_tax_child_field([
						'term_id' => $field['term_id'],
						'box_name' => $field['box_name'],
						'parent_field_name' => $field['parent_field_name'],
						'field_name' => $field['field_name'],
						'index' => $index,
					]);
				}
			}

			return $nested_values;
		}

		if(!is_acpt_tax_field_visible([
			'term_id' => $field['term_id'],
			'box_name' => $field['box_name'],
			'field_name' => $field['field_name'],
		])){
			return null;
		}

		return get_acpt_tax_field([
			'term_id' => $field['term_id'],
			'box_name' => $field['box_name'],
			'field_name' => $field['field_name'],
		]);
	}

	/**
	 * @param $field
	 *
	 * @return array|mixed|null
	 * @throws \Exception
	 */
	private function get_option_page_meta_value($field)
	{
		if(!isset($field['option_page']) and !isset($field['box_name']) and !isset($field['field_name'])){
			return null;
		}

		// flexible field block nested element
		if(isset($field['parent_field_name'])){
			$acpt_value = get_acpt_option_page_field([
				'option_page' => $field['option_page'],
				'box_name' => $field['box_name'],
				'field_name' => $field['parent_field_name'],
			]);

			$nested_values = [];

			if(is_array($acpt_value)){
				foreach ($acpt_value as $index => $nested_value){
					if(is_acpt_option_page_field_visible([
						'option_page' => $field['option_page'],
						'box_name' => $field['box_name'],
						'parent_field_name' => $field['parent_field_name'],
						'field_name' => $field['field_name'],
						'index' => $index,
					])){
						$nested_values[] = get_acpt_option_page_child_field([
							'option_page' => $field['option_page'],
							'box_name' => $field['box_name'],
							'parent_field_name' => $field['parent_field_name'],
							'field_name' => $field['field_name'],
							'index' => $index,
						]);
					}
				}
			}

			return $nested_values;
		}

		// repeater field nested element
		if(isset($field['children']) and !empty($field['children'])){

			// Example:
			//
			// 0 => [fancy => ciao]
			// 1 => [fancy => dsgffds fdsfddsf]
			// 2 => [fancy => dfsdfs]
			//
			$get_acpt_field = get_acpt_option_page_field([
				'option_page' => $field['option_page'],
				'box_name' => $field['box_name'],
				'field_name' => $field['field_name'],
			]);

			if(is_array($get_acpt_field)){

				foreach ($field['children'] as $child_field){

					for($i = 0; $i < count($get_acpt_field); $i++){
						$is_acpt_field_visible = is_acpt_option_page_field_visible([
							'option_page' => $field['option_page'],
							'box_name' => $child_field['box_name'],
							'parent_field_name' => $child_field['parent_field_name'],
							'field_name' => $child_field['field_name'],
							'index' => $i,
						]);

						if(!$is_acpt_field_visible){
							if(isset($get_acpt_field[$i][$child_field['field_name']])){
								unset($get_acpt_field[$i][$child_field['field_name']]);
							}
						}
					}
				}
			}

			return $get_acpt_field;
		}

		if(!is_acpt_option_page_field_visible([
			'option_page' => $field['option_page'],
			'box_name' => $field['box_name'],
			'field_name' => $field['field_name'],
		])){
			return null;
		}

		return get_acpt_option_page_field([
			'option_page' => $field['option_page'],
			'box_name' => $field['box_name'],
			'field_name' => $field['field_name'],
		]);
	}

	/**
	 * @param $block
	 *
	 * @return array
	 * @throws \Exception
	 */
	private function get_acpt_block($block)
	{
		switch ($block['belongsTo']){
			case MetaTypes::OPTION_PAGE:
				return $this->get_option_page_meta_block_values($block);

			default:
			case MetaTypes::CUSTOM_POST_TYPE:
				return $this->get_post_meta_block_values($block);
		}
	}

	/**
	 * @param $block
	 *
	 * @return array
	 * @throws \Exception
	 */
	private function get_post_meta_block_values($block)
	{
		if(!isset($block['post_id']) and !isset($block['box_name']) and !isset($block['parent_field_name'])){
			return null;
		}

		$acpt_parent_field_value = get_acpt_field([
			'post_id' => $block['post_id'],
			'box_name' => $block['box_name'],
			'field_name' => $block['parent_field_name'],
		]);

		$nested_values = [];

		if(is_array($acpt_parent_field_value) and isset($acpt_parent_field_value['blocks'])){
			foreach ($acpt_parent_field_value['blocks'] as $block_index => $block_values){
				foreach ($block_values as $block_name => $block_value){
					if($block_name === $block['block_name']){
						if(is_array($block_value)){
							foreach ($block_value as $nested_child_field_name => $nested_child_field_values){
								foreach ($nested_child_field_values as $nested_child_field_index => $nested_child_field_value){

									$is_acpt_field_visible = is_acpt_field_visible([
										'post_id' => $block['post_id'],
										'box_name' => $block['box_name'],
										'field_name' => $nested_child_field_name,
										'parent_field_name' => $block['parent_field_name'],
										'block_name' => $block_name,
										'block_index' => $block_index,
										'index' => $nested_child_field_index,
									]);

									if($is_acpt_field_visible){

										// This is a map like this:
										//
										// 0_0 => [
										//     'email_testo' => 'mauro@email.com',
										//     'testo' => 'value2',
										// ]
										//
										// An aggregate index is needed to aggregate data from blocks with same name
										// avoiding data override
										//
										$aggregate_index = $block_index . '_' . $nested_child_field_index;
										$nested_values[$aggregate_index][$nested_child_field_name] = get_acpt_block_child_field([
											'post_id' => $block['post_id'],
											'box_name' => $block['box_name'],
											'field_name' => $nested_child_field_name,
											'parent_field_name' => $block['parent_field_name'],
											'block_name' => $block_name,
											'block_index' => $block_index,
											'index' => $nested_child_field_index,
										]);
									}
								}
							}
						}
					}
				}
			}
		}

		return $nested_values;
	}

	/**
	 * @param $block
	 *
	 * @return array|null
	 * @throws \Exception
	 */
	private function get_option_page_meta_block_values($block)
	{
		if(!isset($block['option_page']) and !isset($block['box_name']) and !isset($block['parent_field_name'])){
			return null;
		}

		$acpt_parent_field_value = get_acpt_option_page_field([
			'option_page' => $block['option_page'],
			'box_name' => $block['box_name'],
			'field_name' => $block['parent_field_name'],
		]);

		$nested_values = [];

		if(is_array($acpt_parent_field_value) and isset($acpt_parent_field_value['blocks'])){
			foreach ($acpt_parent_field_value['blocks'] as $block_index => $block_values){
				foreach ($block_values as $block_name => $block_value){
					if($block_name === $block['block_name']){
						if(is_array($block_value)){
							foreach ($block_value as $nested_child_field_name => $nested_child_field_values){
								foreach ($nested_child_field_values as $nested_child_field_index => $nested_child_field_value){

									$is_acpt_option_page_field_visible = is_acpt_option_page_field_visible([
										'option_page' => $block['option_page'],
										'box_name' => $block['box_name'],
										'field_name' => $nested_child_field_name,
										'parent_field_name' => $block['parent_field_name'],
										'block_name' => $block_name,
										'block_index' => $block_index,
										'index' => $nested_child_field_index,
									]);

									if($is_acpt_option_page_field_visible){
										// This is a map like this:
										//
										// 0_0 => [
										//     'email_testo' => 'mauro@email.com',
										//     'testo' => 'value2',
										// ]
										//
										// An aggregate index is needed to aggregate data from blocks with same name
										// avoiding data override
										//
										$aggregate_index = $block_index . '_' . $nested_child_field_index;
										$nested_values[$aggregate_index][$nested_child_field_name] = get_acpt_option_page_block_child_field([
											'option_page' => $block['option_page'],
											'box_name' => $block['box_name'],
											'field_name' => $nested_child_field_name,
											'parent_field_name' => $block['parent_field_name'],
											'block_name' => $block_name,
											'block_index' => $block_index,
											'index' => $nested_child_field_index,
										]);
									}
								}
							}
						}
					}
				}
			}
		}

		return $nested_values;
	}

	/**
	 * Get all fields supported and their contexts
	 *
	 * @return array
	 */
	private static function get_fields_by_context()
	{
		return [

			// Basic
			CustomPostTypeMetaBoxFieldModel::TEXT_TYPE             => [ self::CONTEXT_TEXT ],
			CustomPostTypeMetaBoxFieldModel::TEXTAREA_TYPE         => [ self::CONTEXT_TEXT ],
			CustomPostTypeMetaBoxFieldModel::NUMBER_TYPE           => [ self::CONTEXT_TEXT ],
			CustomPostTypeMetaBoxFieldModel::RANGE_TYPE            => [ self::CONTEXT_TEXT ],
			CustomPostTypeMetaBoxFieldModel::EMAIL_TYPE            => [ self::CONTEXT_TEXT, self::CONTEXT_LINK ],
			CustomPostTypeMetaBoxFieldModel::PHONE_TYPE            => [ self::CONTEXT_TEXT, self::CONTEXT_LINK ],
			CustomPostTypeMetaBoxFieldModel::URL_TYPE              => [ self::CONTEXT_TEXT, self::CONTEXT_LINK ],

			// Content
			CustomPostTypeMetaBoxFieldModel::IMAGE_TYPE            => [ self::CONTEXT_TEXT, self::CONTEXT_IMAGE ],
			CustomPostTypeMetaBoxFieldModel::GALLERY_TYPE          => [ self::CONTEXT_TEXT, self::CONTEXT_IMAGE ],
			CustomPostTypeMetaBoxFieldModel::FILE_TYPE             => [ self::CONTEXT_TEXT, self::CONTEXT_LINK, self::CONTEXT_VIDEO, self::CONTEXT_MEDIA ],
			CustomPostTypeMetaBoxFieldModel::EDITOR_TYPE           => [ self::CONTEXT_TEXT ],
			CustomPostTypeMetaBoxFieldModel::HTML_TYPE             => [ self::CONTEXT_TEXT ],
			CustomPostTypeMetaBoxFieldModel::EMBED_TYPE            => [ self::CONTEXT_TEXT, self::CONTEXT_LINK, self::CONTEXT_VIDEO, self::CONTEXT_MEDIA ],
			CustomPostTypeMetaBoxFieldModel::VIDEO_TYPE            => [ self::CONTEXT_TEXT, self::CONTEXT_LINK, self::CONTEXT_VIDEO, self::CONTEXT_MEDIA ],

			// Specialized fields
			CustomPostTypeMetaBoxFieldModel::ADDRESS_TYPE          => [ self::CONTEXT_TEXT ],
			CustomPostTypeMetaBoxFieldModel::WEIGHT_TYPE           => [ self::CONTEXT_TEXT ],
			CustomPostTypeMetaBoxFieldModel::LENGTH_TYPE           => [ self::CONTEXT_TEXT ],
			CustomPostTypeMetaBoxFieldModel::DATE_TYPE             => [ self::CONTEXT_TEXT ],
			CustomPostTypeMetaBoxFieldModel::DATE_RANGE_TYPE       => [ self::CONTEXT_TEXT ],
			CustomPostTypeMetaBoxFieldModel::TIME_TYPE             => [ self::CONTEXT_TEXT ],
			CustomPostTypeMetaBoxFieldModel::CURRENCY_TYPE         => [ self::CONTEXT_TEXT ],
			CustomPostTypeMetaBoxFieldModel::COLOR_TYPE            => [ self::CONTEXT_TEXT ],
			CustomPostTypeMetaBoxFieldModel::RATING_TYPE           => [ self::CONTEXT_TEXT ],

			// Choice
			CustomPostTypeMetaBoxFieldModel::SELECT_TYPE           => [ self::CONTEXT_TEXT ],
			CustomPostTypeMetaBoxFieldModel::SELECT_MULTI_TYPE     => [ self::CONTEXT_TEXT ],
			CustomPostTypeMetaBoxFieldModel::CHECKBOX_TYPE         => [ self::CONTEXT_TEXT ],
			CustomPostTypeMetaBoxFieldModel::RADIO_TYPE            => [ self::CONTEXT_TEXT ],
			CustomPostTypeMetaBoxFieldModel::TOGGLE_TYPE           => [ self::CONTEXT_TEXT ],

			// Loop
			CustomPostTypeMetaBoxFieldModel::LIST_TYPE             => [ self::CONTEXT_LOOP, self::CONTEXT_TEXT ],
			CustomPostTypeMetaBoxFieldModel::REPEATER_TYPE         => [ self::CONTEXT_LOOP ],
			CustomPostTypeMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE => [ self::CONTEXT_LOOP ],
		];
	}

	/**
	 * Set the loop query if exists
	 * This function is triggered on frontend when a loop tag (like a List, Flexible or Repeater field) is rendered
	 *
	 * @param array $results
	 * @param \Bricks\Query $query
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function set_loop_query( $results, $query )
	{
		if ( ! array_key_exists( $query->object_type, $this->loop_tags ) ) {
			return $results;
		}

		$tag_object = $this->loop_tags[ $query->object_type ];
		$looping_query_id = \Bricks\Query::is_any_looping();
		$field = isset($tag_object['field']) ? $tag_object['field'] : null;

		if ( $looping_query_id ) {

			$loop_query_object_type = \Bricks\Query::get_query_object_type( $looping_query_id );

			// Maybe it is a nested repeater
			if ( array_key_exists( $loop_query_object_type, $this->loop_tags ) ) {

				$loop_object = \Bricks\Query::get_loop_object( $looping_query_id );

				if ( is_array( $loop_object ) && array_key_exists( $field['name'], $loop_object ) ) {
					return $loop_object[ $field['name'] ];
				}
			}

			// Or maybe it is a post loop
			elseif ( $loop_query_object_type === 'post' ) {
				$acpt_object_id = get_the_ID();
			}
		}

		if ( ! isset( $acpt_object_id ) and $field !== null ) {
			// Get the $post_id or the template preview ID
			$post_id = \Bricks\Database::$page_data['preview_or_post_id'];
			$acpt_object_id = $this->get_object_id( $field, $post_id );
		}

		// Render blocks
		if(isset($tag_object['type']) and $tag_object['type'] === 'Block'){

			$post_id = isset( $loop_query_object_type ) && $loop_query_object_type === 'post' ? get_the_ID() : \Bricks\Database::$page_data['preview_or_post_id'];
			$tag_object['post_id'] = $post_id;

			return $this->get_acpt_block($tag_object);
		}

		// Check if it is a subfield of a group field (Repeater inside of a Group)
		if ( isset( $tag_object['parent']['type'] ) and $tag_object['parent']['type'] === CustomPostTypeMetaBoxFieldModel::REPEATER_TYPE ) {

			$post_id = isset( $loop_query_object_type ) && $loop_query_object_type === 'post' ? get_the_ID() : \Bricks\Database::$page_data['preview_or_post_id'];

			$group_value = get_acpt_field( [
				'box_name' => $tag_object['field']['box_name'],
				'field_name' => $tag_object['field']['parent_field_name'],
				'post_id' => $post_id,
			] );

			return isset( $group_value[ $tag_object['field']['field_name'] ] ) ? $group_value[ $tag_object['field']['field_name'] ] : [];

		} else {
			$field['post_id'] = $acpt_object_id;

			$results = $this->get_acpt_value($field);
		}

		return ! empty( $results ) ? $results : [];
	}

	/**
	 * Calculate the object ID to be used when fetching the field value
	 *
	 * @param $field
	 * @param $post_id
	 *
	 * @return mixed
	 */
	public function get_object_id( $field, $post_id )
	{
		if ( isset($field['object_type']) and \Bricks\Query::is_looping() ) {
			$object_type = $field['object_type'];
			$loop_type = \Bricks\Query::get_loop_object_type();
			$object_id = \Bricks\Query::get_loop_object_id();

			// loop type is the same as the field object type (term, user, post)
			if ( $loop_type == $object_type ) {
				return $object_id;
			}
		}

		return $post_id;
	}

	/**
	 * @param $attachment
	 *
	 * @return bool
	 */
	private function isAnAttachment($attachment)
	{
		return $attachment instanceof WPAttachment;
	}
}
