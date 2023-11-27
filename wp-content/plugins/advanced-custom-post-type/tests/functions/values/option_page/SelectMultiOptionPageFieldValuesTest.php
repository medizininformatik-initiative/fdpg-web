<?php

namespace ACPT\Tests;

use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;

class SelectMultiOptionPageFieldValuesTest extends AbstractTestCase
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
                'field_name' => 'Select multi field',
                'field_type' => OptionPageMetaBoxFieldModel::SELECT_MULTI_TYPE,

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
            ]
        );

        $add_acpt_option_page_meta_field_wrong_value = add_acpt_option_page_meta_field_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Select multi field',
            'value' => [
                "wrong value"
            ],
        ]);

        $this->assertFalse($add_acpt_option_page_meta_field_wrong_value);

        $add_acpt_option_page_meta_field_value = add_acpt_option_page_meta_field_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Select multi field',
            'value' => [
                "foo",
                "fuzz",
            ],
        ]);

        $this->assertTrue($add_acpt_option_page_meta_field_value);

        $acpt_option_page_field = get_acpt_option_page_field([
	        'option_page' => 'new-page',
                'box_name' => 'box_name',
                'field_name' => 'Select multi field',
        ]);

        $this->assertEquals([
            "foo",
            "fuzz",
        ], $acpt_option_page_field);
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
            'field_name' => 'Select multi field',
            'value' => [
                "bar"
            ],
        ]);

        $this->assertTrue($edit_acpt_option_page_meta_field_value);

        $acpt_option_page_field = get_acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Select multi field',
        ]);

        $this->assertEquals([
            "bar"
        ], $acpt_option_page_field);
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
            'field_name' => 'Select multi field',
        ]);

        $this->assertEquals("bar", $acpt_option_page_field);
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
            'field_name' => 'Select multi field',
        ]);

        $this->assertTrue($delete_acpt_option_page_meta_field_value);

        $delete_acpt_option_page_meta_box = delete_acpt_option_page_meta_box('new-page', 'box_name');

        $this->assertTrue($delete_acpt_option_page_meta_box);

        $acpt_option_page_field = get_acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Select multi field',
        ]);

        $this->assertNull($acpt_option_page_field);

	    $this->assertTrue(delete_acpt_option_page('new-page', true));
    }
}