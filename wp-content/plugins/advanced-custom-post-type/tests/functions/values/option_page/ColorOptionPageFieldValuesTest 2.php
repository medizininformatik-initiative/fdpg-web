<?php

namespace ACPT\Tests;

use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;

class ColorOptionPageFieldValuesTest extends AbstractTestCase
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

	    $add_acpt_option_page_meta_field = add_acpt_option_page_meta_field(
		    [
			    'option_page' => 'new-page',
			    'box_name' => 'box_name',
			    'field_name' => 'color_field',
			    'field_type' => OptionPageMetaBoxFieldModel::COLOR_TYPE,
			    'required' => false,
		    ]
	    );

	    $this->assertTrue($add_acpt_option_page_meta_field);

	    $add_acpt_option_page_meta_field_value = add_acpt_option_page_meta_field_value([
		    'option_page' => 'new-page',
		    'box_name' => 'box_name',
		    'field_name' => 'color_field',
		    'value' => "#676767",
	    ]);

	    $this->assertTrue($add_acpt_option_page_meta_field_value);

	    $acpt_field = get_acpt_option_page_field([
		    'option_page' => 'new-page',
		    'box_name' => 'box_name',
		    'field_name' => 'color_field',
	    ]);

	    $this->assertEquals('#676767', $acpt_field);
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
            'field_name' => 'color_field',
            'value' => "#333",
        ]);

        $this->assertTrue($edit_acpt_option_page_meta_field_value);

        $acpt_field = get_acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'color_field',
        ]);

        $this->assertEquals('#333', $acpt_field);
    }

    /**
     * @depends can_edit_acpt_option_page_meta_field_value
     * @test
     */
    public function can_display_acpt_option_page_meta_field()
    {
        $acpt_field = acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'color_field',
        ]);

        $this->assertEquals('<span class="acpt-color-placeholder" style="width: 36px; height: 36px; background-color: #333"></span>', $acpt_field);
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
            'field_name' => 'color_field',
        ]);

        $this->assertTrue($delete_acpt_option_page_meta_field_value);

	    $delete_acpt_option_page_meta_box = delete_acpt_option_page_meta_box('new-page', 'box_name');

	    $this->assertTrue($delete_acpt_option_page_meta_box);

	    $acpt_option_page_field = get_acpt_option_page_field([
		    'option_page' => 'new-page',
		    'box_name' => 'box_name',
		    'field_name' => 'color_field',
	    ]);

	    $this->assertNull($acpt_option_page_field);
	    $this->assertTrue(delete_acpt_option_page('new-page', true));
    }
}