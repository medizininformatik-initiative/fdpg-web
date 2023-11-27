<?php

namespace ACPT\Tests;

use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;
use function Uschema\var_export_pretty;

class RatingOptionPageFieldValuesTest extends AbstractTestCase
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
                'field_name' => 'Rating',
                'field_type' => OptionPageMetaBoxFieldModel::RATING_TYPE,
                'required' => false,
            ]
        );

	    $this->assertTrue($add_acpt_option_page_meta_field);

	    $add_acpt_option_page_meta_field_value = add_acpt_option_page_meta_field_value([
		    'option_page' => 'new-page',
		    'box_name' => 'box_name',
		    'field_name' => 'Rating',
		    'value' => 'string',
	    ]);

	    $this->assertFalse($add_acpt_option_page_meta_field_value);

	    $add_acpt_option_page_meta_field_value = add_acpt_option_page_meta_field_value([
		    'option_page' => 'new-page',
		    'box_name' => 'box_name',
		    'field_name' => 'Rating',
		    'value' => 1123,
	    ]);

	    $this->assertFalse($add_acpt_option_page_meta_field_value);

        $add_acpt_option_page_meta_field_value = add_acpt_option_page_meta_field_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Rating',
            'value' => 9,
        ]);

        $this->assertTrue($add_acpt_option_page_meta_field_value);

        $acpt_option_page_field = get_acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Rating',
        ]);

        $this->assertEquals(9, $acpt_option_page_field);
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
            'field_name' => 'Rating',
            'value' => 7,
        ]);

        $this->assertTrue($edit_acpt_option_page_meta_field_value);

        $acpt_option_page_field = get_acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Rating',
        ]);

        $this->assertEquals(7, $acpt_option_page_field);
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
            'field_name' => 'Rating',
        ]);

        $expected = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 20 20"><path fill="currentColor" d="m10 1l3 6l6 .75l-4.12 4.62L16 19l-6-3l-6 3l1.13-6.63L1 7.75L7 7z"/></svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 20 20"><path fill="currentColor" d="m10 1l3 6l6 .75l-4.12 4.62L16 19l-6-3l-6 3l1.13-6.63L1 7.75L7 7z"/></svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 20 20"><path fill="currentColor" d="m10 1l3 6l6 .75l-4.12 4.62L16 19l-6-3l-6 3l1.13-6.63L1 7.75L7 7z"/></svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 20 20"><path fill="currentColor" d="M10 1L7 7l-6 .75l4.13 4.62L4 19l6-3l6 3l-1.12-6.63L19 7.75L13 7zm0 2.24l2.34 4.69l4.65.58l-3.18 3.56l.87 5.15L10 14.88V3.24z"/></svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 20 20"><path fill="currentColor" d="M10 1L7 7l-6 .75l4.13 4.62L4 19l6-3l6 3l-1.12-6.63L19 7.75L13 7zm0 2.24l2.34 4.69l4.65.58l-3.18 3.56l.87 5.15L10 14.88l-4.68 2.34l.87-5.15l-3.18-3.56l4.65-.58z"/></svg>';

        $this->assertEquals($expected, $acpt_option_page_field);
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
            'field_name' => 'Rating',
        ]);

        $this->assertTrue($delete_acpt_option_page_meta_field_value);

        $delete_acpt_option_page_meta_box = delete_acpt_option_page_meta_box('new-page', 'box_name');

        $this->assertTrue($delete_acpt_option_page_meta_box);

        $acpt_option_page_field = get_acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Rating',
        ]);

        $this->assertNull($acpt_option_page_field);

	    $this->assertTrue(delete_acpt_option_page('new-page', true));
    }
}