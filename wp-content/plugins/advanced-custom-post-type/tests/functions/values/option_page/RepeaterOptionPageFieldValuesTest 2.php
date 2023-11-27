<?php

namespace ACPT\Tests;

use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;

class RepeaterOptionPageFieldValuesTest extends AbstractTestCase
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

	    add_acpt_option_page_meta_field(
            [
	            'option_page' => 'new-page',
                'box_name' => 'box_name',
                'field_name' => 'Repeater field',
                'field_type' => OptionPageMetaBoxFieldModel::REPEATER_TYPE,
                'required' => false,
                'children' => [
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
                ]
            ]
        );

        $add_acpt_meta_field_value = add_acpt_option_page_meta_field_row_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Repeater field',
            'value' => [
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
            ],
        ]);

        $this->assertTrue($add_acpt_meta_field_value);

        $add_acpt_meta_field_value = add_acpt_option_page_meta_field_row_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Repeater field',
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

        $this->assertTrue($add_acpt_meta_field_value);

        $has_rows = acpt_option_page_field_has_rows([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Repeater field',
        ]);

        $this->assertTrue($has_rows);

        $acpt_field = get_acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Repeater field',
        ]);

        $this->assertEquals($acpt_field[0]['Text'], 'text');
        $this->assertEquals($acpt_field[0]['Url']['url'], 'https://acpt.io');
        $this->assertEquals($acpt_field[0]['Url']['label'], 'url label');
        $this->assertEquals($acpt_field[0]['Select'], 'foo');
        $this->assertEquals($acpt_field[0]['Select'], 'foo');
        $this->assertEquals($acpt_field[0]['Indirizzo']['address'], 'Via Latina 94 00179 Roma');
        $this->assertEquals($acpt_field[0]['Moneta']['amount'], 32);
        $this->assertEquals($acpt_field[0]['Moneta']['unit'], 'EUR');
        $this->assertEquals($acpt_field[0]['Peso']['weight'], 32);
        $this->assertEquals($acpt_field[0]['Peso']['unit'], 'GRAM');
        $this->assertEquals($acpt_field[0]['Lunghezza']['length'], 32);
        $this->assertEquals($acpt_field[0]['Lunghezza']['unit'], 'KILOMETER');
        $this->assertEquals($acpt_field[1]['Text'], 'Second text');
        $this->assertEquals($acpt_field[1]['Url']['url'], 'https://appsumo.com');
        $this->assertEquals($acpt_field[1]['Url']['label'], 'AppSumo');
        $this->assertEquals($acpt_field[1]['Select'], 'bar');
        $this->assertEquals($acpt_field[1]['Indirizzo']['address'], 'Via Macedonia 11 00179 Roma');
        $this->assertEquals($acpt_field[1]['Moneta']['amount'], 132);
        $this->assertEquals($acpt_field[1]['Moneta']['unit'], 'EUR');
        $this->assertEquals($acpt_field[1]['Peso']['weight'], 132);
        $this->assertEquals($acpt_field[1]['Peso']['unit'], 'GRAM');
        $this->assertEquals($acpt_field[1]['Lunghezza']['length'], 132);
        $this->assertEquals($acpt_field[1]['Lunghezza']['unit'], 'KILOMETER');

	    // Test get_acpt_child_field() function
	    $nestedValue = get_acpt_option_page_child_field(
		    [
			    'option_page' => 'new-page',
			    'box_name' => 'box_name',
			    'field_name' => 'Text',
			    'parent_field_name' => 'Repeater field',
			    'index' => 0,
		    ]
	    );

	    $this->assertEquals($nestedValue, 'text');

        // Test acpt_child_field() function
        $nested = acpt_option_page_child_field(
            [
	            'option_page' => 'new-page',
                'box_name' => 'box_name',
                'field_name' => 'Text',
                'parent_field_name' => 'Repeater field',
                'index' => 0,
            ]
        );

        $this->assertEquals($nested, 'text');

        $nested = acpt_option_page_child_field(
            [
	            'option_page' => 'new-page',
                'box_name' => 'box_name',
                'field_name' => 'Moneta',
                'parent_field_name' => 'Repeater field',
                'index' => 0,
            ]
        );

        $this->assertEquals($nested['amount'], 32);
        $this->assertEquals($nested['unit'], 'EUR');
    }

    /**
     * @depends can_add_acpt_meta_field_row_value
     * @test
     */
    public function can_edit_acpt_meta_field_row_value()
    {
        $edit_acpt_meta_field_value = edit_acpt_option_page_meta_field_row_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Repeater field',
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
            'index' => 0
        ]);

        $this->assertTrue($edit_acpt_meta_field_value);

        $acpt_field = get_acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Repeater field',
        ]);

        $this->assertEquals($acpt_field[0]['Text'], 'Another text');
        $this->assertEquals($acpt_field[0]['Url']['url'], 'https://google.com');
        $this->assertEquals($acpt_field[0]['Url']['label'], 'Google');
        $this->assertEquals($acpt_field[0]['Select'], 'fuzz');
        $this->assertEquals($acpt_field[0]['Indirizzo']['address'], 'Via Macedonia 11 00179 Roma');
        $this->assertEquals($acpt_field[0]['Moneta']['amount'], 132);
        $this->assertEquals($acpt_field[0]['Moneta']['unit'], 'EUR');
        $this->assertEquals($acpt_field[0]['Peso']['weight'], 132);
        $this->assertEquals($acpt_field[0]['Peso']['unit'], 'GRAM');
        $this->assertEquals($acpt_field[0]['Lunghezza']['length'], 132);
        $this->assertEquals($acpt_field[0]['Lunghezza']['unit'], 'KILOMETER');
    }

    /**
     * @depends can_edit_acpt_meta_field_row_value
     * @test
     */
    public function can_delete_acpt_meta_field_row_value()
    {
        $delete_acpt_meta_field_value = delete_acpt_option_page_meta_field_row_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Repeater field',
            'index' => 0
        ]);

        $this->assertTrue($delete_acpt_meta_field_value);

        $delete_acpt_meta_field_value = delete_acpt_option_page_meta_field_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Repeater field',
        ]);

        $this->assertTrue($delete_acpt_meta_field_value);

        $delete_acpt_meta_box = delete_acpt_option_page_meta_box('new-page', 'box_name');

        $this->assertTrue($delete_acpt_meta_box);

        $acpt_field = get_acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Repeater field',
        ]);

        $this->assertNull($acpt_field);

	    $this->assertTrue(delete_acpt_option_page('new-page', true));
    }
}