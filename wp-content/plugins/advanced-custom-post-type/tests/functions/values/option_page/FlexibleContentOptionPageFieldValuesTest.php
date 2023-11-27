<?php

namespace ACPT\Tests;

use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;
use ACPT\Utils\Wordpress\WPAttachment;

class FlexibleContentOptionPageFieldValuesTest extends AbstractTestCase
{
	/**
	 * @test
	 */
	public function can_add_acpt_meta_field_row_value()
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

		$add_acpt_option_page_meta_field = add_acpt_option_page_meta_field([
			'option_page' => 'new-page',
			'box_name' => 'box_name',
			'field_name' => 'Flexible field',
			'field_type' => OptionPageMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE,
			'required' => false,
			'blocks' => [
				[
					'block_name' => 'block',
					'block_label' => 'block label',
					'fields' => [
						[
							'field_name' => 'Text',
							'field_type' => OptionPageMetaBoxFieldModel::TEXT_TYPE,
							'required' => true,
						],
						[
							'field_name' => 'Url',
							'field_type' => OptionPageMetaBoxFieldModel::URL_TYPE,
							'required' => false,
						],
						[
							'field_name' => 'Indirizzo',
							'field_type' => OptionPageMetaBoxFieldModel::ADDRESS_TYPE,
							'required' => false,
						],
						[
							'field_name' => 'Moneta',
							'field_type' => OptionPageMetaBoxFieldModel::CURRENCY_TYPE,
							'required' => false,
						],
						[
							'field_name' => 'Lunghezza',
							'field_type' => OptionPageMetaBoxFieldModel::LENGTH_TYPE,
							'required' => false,
						],
						[
							'field_name' => 'Peso',
							'field_type' => OptionPageMetaBoxFieldModel::WEIGHT_TYPE,
							'required' => false,
						],
						[
							'field_name' => 'Select',
							'field_type' => OptionPageMetaBoxFieldModel::SELECT_TYPE,
							'required' => false,
							'options' => [
								[
									'value' => 'foo',
									'label' => 'Label foo',
								],
								[
									'value' => 'bar',
									'label' => 'Label bar',
								],
								[
									'value' => 'fuzz',
									'label' => 'Label fuzz',
								],
							]
						],
					],
				]
			],
		]);

		$this->assertTrue($add_acpt_option_page_meta_field);

		$add_acpt_meta_field_value = add_acpt_option_page_meta_field_value([
			'option_page' => 'new-page',
			'box_name' => 'box_name',
			'field_name' => 'Flexible field',
			'value' => [
				"blocks" => [
					0 => [
						'block' => [
							[
								"Text" => "text",
								"Url" => [
									'url' => 'https://acpt.io',
									'label' => 'url label',
								],
								"Select" => "foo",
								"Indirizzo" => "Via Latina 94 00179 Roma",
								"Moneta" => [
									'amount' => 32,
									'unit' => 'EUR'
								],
								"Lunghezza" => [
									'length' => 32,
									'unit' => 'KILOMETER'
								],
								"Peso" => [
									'weight' => 32,
									'unit' => 'GRAM'
								],
							]
						],
					],
				],
			],
		]);

		$this->assertTrue($add_acpt_meta_field_value);

		$has_rows = acpt_option_page_field_has_blocks([
			'option_page' => 'new-page',
			'box_name' => 'box_name',
			'field_name' => 'Flexible field',
		]);

		$this->assertTrue($has_rows);

		$acpt_field = get_acpt_option_page_field([
			'option_page' => 'new-page',
			'box_name' => 'box_name',
			'field_name' => 'Flexible field',
		]);

		$this->assertNotEmpty($acpt_field['blocks']);
		$this->assertEquals($acpt_field['blocks'][0]['block']['Text'][0], 'text');
		$this->assertEquals($acpt_field['blocks'][0]['block']['Url'][0]['url'], 'https://acpt.io');
		$this->assertEquals($acpt_field['blocks'][0]['block']['Url'][0]['label'], 'url label');
		$this->assertEquals($acpt_field['blocks'][0]['block']['Select'][0], 'foo');
		$this->assertEquals($acpt_field['blocks'][0]['block']['Indirizzo'][0]['address'], 'Via Latina 94 00179 Roma');
		$this->assertEquals($acpt_field['blocks'][0]['block']['Moneta'][0]['amount'], 32);
		$this->assertEquals($acpt_field['blocks'][0]['block']['Moneta'][0]['unit'], 'EUR');
		$this->assertEquals($acpt_field['blocks'][0]['block']['Peso'][0]['weight'], 32);
		$this->assertEquals($acpt_field['blocks'][0]['block']['Peso'][0]['unit'], 'GRAM');
		$this->assertEquals($acpt_field['blocks'][0]['block']['Lunghezza'][0]['length'], 32);
		$this->assertEquals($acpt_field['blocks'][0]['block']['Lunghezza'][0]['unit'], 'KILOMETER');

		$add_acpt_option_page_meta_block_field_row_value = add_acpt_option_page_meta_block_field_row_value([
			'option_page' => 'new-page',
			'box_name' => 'box_name',
			'field_name' => 'Flexible field',
			'block_name' => 'block',
			'block_index' => 0,
			'value' => [
				"Text" => "Second text",
				"Url" => [
					'url' => 'https://appsumo.com',
					'label' => 'AppSumo',
				],
				"Select" => "bar",
				"Indirizzo" => "Via Macedonia 11 00179 Roma",
				"Moneta" => [
					'amount' => 132,
					'unit' => 'EUR'
				],
				"Lunghezza" => [
					'length' => 132,
					'unit' => 'KILOMETER'
				],
				"Peso" => [
					'weight' => 132,
					'unit' => 'GRAM'
				],
			],
		]);

		$this->assertTrue($add_acpt_option_page_meta_block_field_row_value);

		$acpt_field = get_acpt_option_page_field([
			'option_page' => 'new-page',
			'box_name' => 'box_name',
			'field_name' => 'Flexible field',
		]);

		$this->assertEquals($acpt_field['blocks'][0]['block']['Text'][1], 'Second text');
		$this->assertEquals($acpt_field['blocks'][0]['block']['Url'][1]['url'], 'https://appsumo.com');
		$this->assertEquals($acpt_field['blocks'][0]['block']['Url'][1]['label'], 'AppSumo');
		$this->assertEquals($acpt_field['blocks'][0]['block']['Select'][1], 'bar');
		$this->assertEquals($acpt_field['blocks'][0]['block']['Indirizzo'][1]['address'], 'Via Macedonia 11 00179 Roma');
		$this->assertEquals($acpt_field['blocks'][0]['block']['Moneta'][1]['amount'], 132);
		$this->assertEquals($acpt_field['blocks'][0]['block']['Moneta'][1]['unit'], 'EUR');
		$this->assertEquals($acpt_field['blocks'][0]['block']['Peso'][1]['weight'], 132);
		$this->assertEquals($acpt_field['blocks'][0]['block']['Peso'][1]['unit'], 'GRAM');
		$this->assertEquals($acpt_field['blocks'][0]['block']['Lunghezza'][1]['length'], 132);
		$this->assertEquals($acpt_field['blocks'][0]['block']['Lunghezza'][1]['unit'], 'KILOMETER');

		// Test get_acpt_option_page_block_child_field() function
		$nestedValue = get_acpt_option_page_block_child_field(
			[
				'option_page' => 'new-page',
				'box_name' => 'box_name',
				'field_name' => 'Text',
				'parent_field_name' => 'Flexible field',
				'block_name' => 'block',
				'block_index' => 0,
				'index' => 0,
			]
		);

		$this->assertEquals($nestedValue, 'text');

		// Test acpt_option_page_block_child_field() function
		$nested = acpt_option_page_block_child_field(
			[
				'option_page' => 'new-page',
				'box_name' => 'box_name',
				'field_name' => 'Text',
				'parent_field_name' => 'Flexible field',
				'block_name' => 'block',
				'block_index' => 0,
				'index' => 0,
			]
		);

		$this->assertEquals($nested, 'text');

		$nested = acpt_option_page_block_child_field(
			[
				'option_page' => 'new-page',
				'box_name' => 'box_name',
				'field_name' => 'Moneta',
				'parent_field_name' => 'Flexible field',
				'block_name' => 'block',
				'block_index' => 0,
				'index' => 0,
			]
		);

		$this->assertEquals($nested, '<span class="amount">32<span class="currency">€</span></span>');
	}

	/**
	 * @depends can_add_acpt_meta_field_row_value
	 * @test
	 */
	public function edit_acpt_option_page_meta_block_field_row_value()
	{
		$edit_acpt_option_page_meta_block_field_row_value = edit_acpt_option_page_meta_block_field_row_value([
			'option_page' => 'new-page',
			'box_name' => 'box_name',
			'field_name' => 'Flexible field',
			'block_name' => 'block',
			'block_index' => 0,
			'value' => [
				"Text" => "Another text",
				"Url" => [
					'url' => 'https://google.com',
					'label' => 'Google',
				],
				"Select" => "fuzz",
				"Indirizzo" => "Via Macedonia 11 00179 Roma",
				"Moneta" => [
					'amount' => 132,
					'unit' => 'EUR'
				],
				"Lunghezza" => [
					'length' => 132,
					'unit' => 'KILOMETER'
				],
				"Peso" => [
					'weight' => 132,
					'unit' => 'GRAM'
				],
			],
			'index' => 0,
		]);

		$this->assertTrue($edit_acpt_option_page_meta_block_field_row_value);

		$acpt_field = get_acpt_option_page_field([
			'option_page' => 'new-page',
			'box_name' => 'box_name',
			'field_name' => 'Flexible field',
		]);

		$this->assertEquals($acpt_field['blocks'][0]['block']['Text'][0], 'Another text');
		$this->assertEquals($acpt_field['blocks'][0]['block']['Url'][0]['url'], 'https://google.com');
		$this->assertEquals($acpt_field['blocks'][0]['block']['Url'][0]['label'], 'Google');
		$this->assertEquals($acpt_field['blocks'][0]['block']['Select'][0], 'fuzz');
		$this->assertEquals($acpt_field['blocks'][0]['block']['Indirizzo'][0]['address'], 'Via Macedonia 11 00179 Roma');
		$this->assertEquals($acpt_field['blocks'][0]['block']['Moneta'][0]['amount'], 132);
		$this->assertEquals($acpt_field['blocks'][0]['block']['Moneta'][0]['unit'], 'EUR');
		$this->assertEquals($acpt_field['blocks'][0]['block']['Peso'][0]['weight'], 132);
		$this->assertEquals($acpt_field['blocks'][0]['block']['Peso'][0]['unit'], 'GRAM');
		$this->assertEquals($acpt_field['blocks'][0]['block']['Lunghezza'][0]['length'], 132);
		$this->assertEquals($acpt_field['blocks'][0]['block']['Lunghezza'][0]['unit'], 'KILOMETER');
	}

	/**
	 *
	 * @test
	 */
	public function can_delete_acpt_meta_field_row_value() //@depends can_edit_acpt_meta_field_row_value
	{
		$delete_acpt_meta_block_field_row_value = delete_acpt_option_page_meta_block_field_row_value([
			'option_page' => 'new-page',
			'box_name' => 'box_name',
			'field_name' => 'Flexible field',
			'block_name' => 'block',
			'block_index' => 0,
			'index' => 0,
		]);

		$this->assertTrue($delete_acpt_meta_block_field_row_value);

		$acpt_field = get_acpt_option_page_field([
			'option_page' => 'new-page',
			'box_name' => 'box_name',
			'field_name' => 'Flexible field',
		]);

		$this->assertCount(1, $acpt_field['blocks'][0]['block']['Text']);

		$delete_acpt_meta_field_value = delete_acpt_option_page_field_value([
			'option_page' => 'new-page',
			'box_name' => 'box_name',
			'field_name' => 'Flexible field',
		]);

		$this->assertTrue($delete_acpt_meta_field_value);

		$delete_acpt_meta_box = delete_acpt_option_page_meta_box('new-page', 'box_name');

		$this->assertTrue($delete_acpt_meta_box);

		$acpt_field = get_acpt_option_page_field([
			'option_page' => 'new-page',
			'box_name' => 'box_name',
			'field_name' => 'Flexible field',
		]);

		$this->assertNull($acpt_field);
	}
}