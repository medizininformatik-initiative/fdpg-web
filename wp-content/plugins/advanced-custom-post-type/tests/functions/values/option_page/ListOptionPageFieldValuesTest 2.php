<?php

namespace ACPT\Tests;

use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;

class ListOptionPageFieldValuesTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function can_add_acpt_option_page_meta_field_row_value()
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

        $deleteFieldValue = delete_acpt_option_page_meta_field_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'List field',
        ]);

        $deleteMetaBox = delete_acpt_option_page_meta_box('new-page', 'box_name');

        add_acpt_option_page_meta_field(
            [
	            'option_page' => 'new-page',
                'box_name' => 'box_name',
                'field_name' => 'List field',
                'field_type' => OptionPageMetaBoxFieldModel::LIST_TYPE,

                'required' => false,
                'advanced_options' => [
                    [
                        'key' => 'before',
                        'value' => '<p>',
                    ],
                    [
                        'key' => 'after',
                        'value' => '</p>',
                    ],
                ]
            ]
        );

        $add_acpt_option_page_meta_field_value = add_acpt_option_page_meta_field_row_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'List field',
            'value' => "text text",
        ]);

        $this->assertTrue($add_acpt_option_page_meta_field_value);

        $add_acpt_option_page_meta_field_value = add_acpt_option_page_meta_field_row_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'List field',
            'value' => "bla bla",
        ]);

        $this->assertTrue($add_acpt_option_page_meta_field_value);

        $acpt_option_page_field = get_acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'List field',
        ]);

        $this->assertEquals($acpt_option_page_field[0], '<p>text text</p>');
        $this->assertEquals($acpt_option_page_field[1], '<p>bla bla</p>');

        $has_rows = acpt_option_page_field_has_rows([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'List field',
        ]);

        $this->assertTrue($has_rows);
    }

    /**
     * @depends can_add_acpt_option_page_meta_field_row_value
     * @test
     */
    public function can_edit_acpt_option_page_meta_field_row_value()
    {
        $edit_acpt_option_page_meta_field_value = edit_acpt_option_page_meta_field_row_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'List field',
            'value' => "other value",
            'index' => 0
        ]);

        $this->assertTrue($edit_acpt_option_page_meta_field_value);

        $acpt_option_page_field = get_acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'List field',
        ]);

        $this->assertEquals($acpt_option_page_field[0], '<p>other value</p>');
        $this->assertEquals($acpt_option_page_field[1], '<p>bla bla</p>');
    }

    /**
     * @depends can_edit_acpt_option_page_meta_field_row_value
     * @test
     */
    public function can_display_acpt_option_page_meta_field()
    {
        $acpt_option_page_field = acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'List field',
        ]);

        $this->assertStringContainsString('other value', $acpt_option_page_field);
        $this->assertStringContainsString('bla bla', $acpt_option_page_field);
    }

    /**
     * @depends can_edit_acpt_option_page_meta_field_row_value
     * @test
     */
    public function can_delete_acpt_option_page_meta_field_row_value()
    {
        $delete_acpt_option_page_meta_field_value = delete_acpt_option_page_meta_field_row_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'List field',
            'index' => 0
        ]);

        $this->assertTrue($delete_acpt_option_page_meta_field_value);

        $acpt_option_page_field = get_acpt_option_page_field([
	        'option_page' => 'new-page',
                'box_name' => 'box_name',
                'field_name' => 'List field',
        ]);

        $this->assertEquals("<p>bla bla</p>", $acpt_option_page_field[0]);

        $delete_acpt_option_page_meta_field_value = delete_acpt_option_page_meta_field_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'List field',
        ]);

        $this->assertTrue($delete_acpt_option_page_meta_field_value);

        $delete_acpt_option_page_meta_box = delete_acpt_option_page_meta_box('new-page', 'box_name');

        $this->assertTrue($delete_acpt_option_page_meta_box);

        $acpt_option_page_field = get_acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'List field',
        ]);

        $this->assertNull($acpt_option_page_field);

	    $this->assertTrue(delete_acpt_option_page('new-page', true));
    }
}