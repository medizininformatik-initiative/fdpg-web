<?php

namespace ACPT\Tests;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;

class VisibilityConditionsTest extends AbstractTestCase
{
	/**
	 * @test
	 * @throws \Exception
	 */
	public function a_complete_test_for_cpts()
	{
		$add_meta_field = add_acpt_meta_field(
			[
				'post_type' => 'page',
				'box_name' => 'new_box',
				'field_name' => 'field_with_visibility_conditions',
				'field_type' => CustomPostTypeMetaBoxFieldModel::TEXT_TYPE,
				'required' => false,
				'show_in_archive' => false,
				'visibility_conditions' => [
					[
						'type' => 'value',
						'value' => 'ciao',
						'logic' => 'and'
					],
				]
			]
		);

		$this->assertTrue($add_meta_field);

		$add_acpt_meta_field_value = add_acpt_meta_field_value([
			'post_id' => $this->oldest_page_id,
			'box_name' => 'new_box',
			'field_name' => 'field_with_visibility_conditions',
			'value' => "text text",
		]);

		$this->assertTrue($add_acpt_meta_field_value);

		$is_acpt_field_visible = is_acpt_field_visible([
			'post_id' => $this->oldest_page_id,
			'box_name' => 'new_box',
			'field_name' => 'field_with_visibility_conditions',
		]);

		$this->assertFalse($is_acpt_field_visible);

		$add_acpt_meta_field_value2 = add_acpt_meta_field_value([
			'post_id' => $this->oldest_page_id,
			'box_name' => 'new_box',
			'field_name' => 'field_with_visibility_conditions',
			'value' => "ciao",
		]);

		$this->assertTrue($add_acpt_meta_field_value2);

		$is_acpt_field_visible = is_acpt_field_visible([
			'post_id' => $this->oldest_page_id,
			'box_name' => 'new_box',
			'field_name' => 'field_with_visibility_conditions',
		]);

		$this->assertTrue($is_acpt_field_visible);

		$delete_acpt_meta_field_value = delete_acpt_meta_field_value([
			'post_id' => $this->oldest_page_id,
			'box_name' => 'new_box',
			'field_name' => 'field_with_visibility_conditions',
		]);

		$this->assertTrue($delete_acpt_meta_field_value);

		$delete_acpt_meta_box = delete_acpt_meta_box('page', 'new_box');

		$this->assertTrue($delete_acpt_meta_box);
	}

	/**
	 * @test
	 * @throws \Exception
	 */
	public function a_complete_test_for_taxonomies()
	{
		$add_meta_field = add_acpt_tax_meta_field(
			[
				'taxonomy' => 'category',
				'box_name' => 'new_box',
				'field_name' => 'field_with_visibility_conditions',
				'field_type' => CustomPostTypeMetaBoxFieldModel::TEXT_TYPE,
				'required' => false,
				'visibility_conditions' => [
					[
						'type' => 'value',
						'value' => 'ciao',
						'logic' => 'and'
					],
				]
			]
		);

		$this->assertTrue($add_meta_field);

		$add_acpt_tax_meta_field_value = add_acpt_tax_meta_field_value([
			'term_id' => $this->oldest_category_id,
			'box_name' => 'new_box',
			'field_name' => 'field_with_visibility_conditions',
			'value' => "text text",
		]);

		$this->assertTrue($add_acpt_tax_meta_field_value);

		$is_acpt_tax_field_visible = is_acpt_tax_field_visible([
			'term_id' => $this->oldest_category_id,
			'box_name' => 'new_box',
			'field_name' => 'field_with_visibility_conditions',
		]);

		$this->assertFalse($is_acpt_tax_field_visible);

		$add_acpt_tax_meta_field_value2 = add_acpt_tax_meta_field_value([
			'term_id' => $this->oldest_category_id,
			'box_name' => 'new_box',
			'field_name' => 'field_with_visibility_conditions',
			'value' => "ciao",
		]);

		$this->assertTrue($add_acpt_tax_meta_field_value2);

		$is_acpt_tax_field_visible = is_acpt_tax_field_visible([
			'term_id' => $this->oldest_category_id,
			'box_name' => 'new_box',
			'field_name' => 'field_with_visibility_conditions',
		]);

		$this->assertTrue($is_acpt_tax_field_visible);

		$delete_acpt_tax_meta_field_value = delete_acpt_tax_meta_field_value([
			'term_id' => $this->oldest_category_id,
			'box_name' => 'new_box',
			'field_name' => 'field_with_visibility_conditions',
		]);

		$this->assertTrue($delete_acpt_tax_meta_field_value);

		$delete_acpt_meta_box = delete_acpt_tax_meta_box('category', 'new_box');

		$this->assertTrue($delete_acpt_meta_box);
	}

	/**
	 * @test
	 * @throws \Exception
	 */
	public function a_complete_test_for_option_pages()
	{
		$new_page = register_acpt_option_page([
			'menu_slug' => 'new-page',
			'page_title' => 'New page',
			'menu_title' => 'New page menu title',
			'icon' => 'admin-appearance',
			'capability' => 'manage_options',
			'description' => 'lorem ipsum',
			'position' => 77,
		]);

		$this->assertTrue($new_page);


		$add_meta_field = add_acpt_option_page_meta_field(
			[
				'option_page' => 'new-page',
				'box_name' => 'new_box',
				'field_name' => 'field_with_visibility_conditions',
				'field_type' => OptionPageMetaBoxFieldModel::TEXT_TYPE,
				'required' => false,
				'visibility_conditions' => [
					[
						'type' => 'value',
						'value' => 'ciao',
						'logic' => 'and'
					],
				]
			]
		);

		$this->assertTrue($add_meta_field);

		$add_acpt_option_page_meta_field_value = add_acpt_option_page_meta_field_value([
			'option_page' => 'new-page',
			'box_name' => 'new_box',
			'field_name' => 'field_with_visibility_conditions',
			'value' => "text text",
		]);

		$this->assertTrue($add_acpt_option_page_meta_field_value);

		$is_acpt_option_page_field_visible = is_acpt_option_page_field_visible([
			'option_page' => 'new-page',
			'box_name' => 'new_box',
			'field_name' => 'field_with_visibility_conditions',
		]);

		$this->assertFalse($is_acpt_option_page_field_visible);

		$add_acpt_option_page_meta_field_value2 = add_acpt_option_page_meta_field_value([
			'option_page' => 'new-page',
			'box_name' => 'new_box',
			'field_name' => 'field_with_visibility_conditions',
			'value' => "ciao",
		]);

		$this->assertTrue($add_acpt_option_page_meta_field_value2);

		$is_acpt_option_page_field_visible = is_acpt_option_page_field_visible([
			'option_page' => 'new-page',
			'box_name' => 'new_box',
			'field_name' => 'field_with_visibility_conditions',
		]);

		$this->assertTrue($is_acpt_option_page_field_visible);

		$delete_acpt_option_page_meta_field_value = delete_acpt_option_page_meta_field_value([
			'option_page' => 'new-page',
			'box_name' => 'new_box',
			'field_name' => 'field_with_visibility_conditions',
		]);

		$this->assertTrue($delete_acpt_option_page_meta_field_value);

		$delete_acpt_meta_box = delete_acpt_option_page_meta_box('new-page', 'new_box');

		$this->assertTrue($delete_acpt_meta_box);

		$this->assertTrue(delete_acpt_option_page('new-page', true));
	}
}