<?php

namespace ACPT\Tests;

use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;

class TextareaOptionPageFieldValuesTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function can_add_acpt_option_page_meta_field_value()
    {
	    $new_page = register_acpt_option_page([
		    'menu_slug' => 'a-very-special-page',
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
	            'option_page' => 'a-very-special-page',
                'box_name' => 'box_name',
                'field_name' => 'Field name',
                'field_type' => OptionPageMetaBoxFieldModel::TEXTAREA_TYPE,
                'required' => false,
            ]
        );

        $add_acpt_option_page_meta_field_value = add_acpt_option_page_meta_field_value([
	        'option_page' => 'a-very-special-page',
            'box_name' => 'box_name',
            'field_name' => 'Field name',
            'value' => "text text",
        ]);

        $this->assertTrue($add_acpt_option_page_meta_field_value);

        $acpt_option_page_field = get_acpt_option_page_field([
	        'option_page' => 'a-very-special-page',
            'box_name' => 'box_name',
            'field_name' => 'Field name',
        ]);

        $this->assertEquals('text text', $acpt_option_page_field);
    }

    /**
     * @depends can_add_acpt_option_page_meta_field_value
     * @test
     */
    public function can_edit_acpt_option_page_meta_field_value()
    {
        $edit_acpt_option_page_meta_field_value = edit_acpt_option_page_meta_field_value([
	        'option_page' => 'a-very-special-page',
            'box_name' => 'box_name',
            'field_name' => 'Field name',
            'value' => "other value",
        ]);

        $this->assertTrue($edit_acpt_option_page_meta_field_value);

        $acpt_option_page_field = get_acpt_option_page_field([
	        'option_page' => 'a-very-special-page',
            'box_name' => 'box_name',
            'field_name' => 'Field name',
        ]);

        $this->assertEquals('other value', $acpt_option_page_field);
    }

    /**
     * @depends can_edit_acpt_option_page_meta_field_value
     * @test
     */
    public function can_display_acpt_option_page_meta_field()
    {
        $acpt_option_page_field = acpt_option_page_field([
	        'option_page' => 'a-very-special-page',
            'box_name' => 'box_name',
            'field_name' => 'Field name',
        ]);

        $this->assertEquals('other value', $acpt_option_page_field);
    }

    /**
     * @depends can_edit_acpt_option_page_meta_field_value
     * @test
     */
    public function can_delete_acpt_option_page_meta_field_value()
    {
        $delete_acpt_option_page_meta_field_value = delete_acpt_option_page_meta_field_value([
	        'option_page' => 'a-very-special-page',
            'box_name' => 'box_name',
            'field_name' => 'Field name',
        ]);

        $this->assertTrue($delete_acpt_option_page_meta_field_value);

        $delete_acpt_option_page_meta_box = delete_acpt_option_page_meta_box('a-very-special-page', 'box_name');

        $this->assertTrue($delete_acpt_option_page_meta_box);

        $acpt_option_page_field = get_acpt_option_page_field([
	        'option_page' => 'a-very-special-page',
            'box_name' => 'box_name',
            'field_name' => 'Field name',
        ]);

        $this->assertNull($acpt_option_page_field);

	    $this->assertTrue(delete_acpt_option_page('a-very-special-page', true));
    }
}