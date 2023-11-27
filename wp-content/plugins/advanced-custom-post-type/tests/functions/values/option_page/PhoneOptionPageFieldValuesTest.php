<?php

namespace ACPT\Tests;

use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;

class PhoneOptionPageFieldValuesTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function can_add_acpt_option_page_meta_field_value()
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
                'field_name' => 'Phone',
                'field_type' => OptionPageMetaBoxFieldModel::PHONE_TYPE,
                'required' => false,
            ]
        );

        $add_acpt_option_page_meta_field_value = add_acpt_option_page_meta_field_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Phone',
            'value' => "+39 123456789",
        ]);

        $this->assertTrue($add_acpt_option_page_meta_field_value);

        $acpt_option_page_field = get_acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Phone',
        ]);

        $this->assertEquals('+39 123456789', $acpt_option_page_field);
    }

    /**
     * @depends can_add_acpt_option_page_meta_field_value
     * @test
     */
    public function can_edit_acpt_option_page_meta_field_value()
    {
        $edit_acpt_option_page_meta_field_value = edit_acpt_option_page_meta_field_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Phone',
            'value' => "+39 987654321",
        ]);

        $this->assertTrue($edit_acpt_option_page_meta_field_value);

        $acpt_option_page_field = get_acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Phone',
        ]);

        $this->assertEquals('+39 987654321', $acpt_option_page_field);
    }

    /**
     * @depends can_edit_acpt_option_page_meta_field_value
     * @test
     */
    public function can_display_acpt_option_page_meta_field()
    {
        $acpt_option_page_field = acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Phone',
        ]);

        $this->assertEquals('<a href="tel:+39 987654321" target="_blank">+39 987654321</a>', $acpt_option_page_field);
    }

    /**
     * @depends can_edit_acpt_option_page_meta_field_value
     * @test
     */
    public function can_delete_acpt_option_page_meta_field_value()
    {
        $delete_acpt_option_page_meta_field_value = delete_acpt_option_page_meta_field_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Phone',
        ]);

        $this->assertTrue($delete_acpt_option_page_meta_field_value);

        $delete_acpt_option_page_meta_box = delete_acpt_option_page_meta_box('new-page', 'box_name');

        $this->assertTrue($delete_acpt_option_page_meta_box);

        $acpt_option_page_field = get_acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Phone',
        ]);

        $this->assertNull($acpt_option_page_field);

	    $this->assertTrue(delete_acpt_option_page('new-page', true));
    }
}