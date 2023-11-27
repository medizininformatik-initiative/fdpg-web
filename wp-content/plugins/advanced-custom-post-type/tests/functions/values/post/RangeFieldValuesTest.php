<?php

namespace ACPT\Tests;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class RangeFieldValuesTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function can_add_acpt_meta_field_value()
    {
        add_acpt_meta_field(
            [
                'post_type' => 'page',
                'box_name' => 'box_name',
                'field_name' => 'Range',
                'field_type' => CustomPostTypeMetaBoxFieldModel::RANGE_TYPE,
                'show_in_archive' => false,
                'required' => false,
	            'advanced_options' => [
	            	[
	            		'key' => 'min',
			            'value' => 33
		            ],
		            [
			            'key' => 'max',
			            'value' => 133
		            ]
	            ]
            ]
        );

	    $add_acpt_meta_field_value = add_acpt_meta_field_value([
		    'post_id' => $this->oldest_page_id,
		    'box_name' => 'box_name',
		    'field_name' => 'Range',
		    'value' => 1123,
	    ]);

	    $this->assertFalse($add_acpt_meta_field_value);

        $add_acpt_meta_field_value = add_acpt_meta_field_value([
            'post_id' => $this->oldest_page_id,
            'box_name' => 'box_name',
            'field_name' => 'Range',
            'value' => 123,
        ]);

        $this->assertTrue($add_acpt_meta_field_value);

        $acpt_field = get_acpt_field([
            'post_id' => $this->oldest_page_id,
            'box_name' => 'box_name',
            'field_name' => 'Range',
        ]);

        $this->assertEquals(123, $acpt_field);
    }

    /**
     * @depends can_add_acpt_meta_field_value
     * @test
     */
    public function can_edit_acpt_meta_field_value()
    {
        $edit_acpt_meta_field_value = edit_acpt_meta_field_value([
            'post_id' => $this->oldest_page_id,
            'box_name' => 'box_name',
            'field_name' => 'Range',
            'value' => 100,
        ]);

        $this->assertTrue($edit_acpt_meta_field_value);

        $acpt_field = get_acpt_field([
            'post_id' => $this->oldest_page_id,
            'box_name' => 'box_name',
            'field_name' => 'Range',
        ]);

        $this->assertEquals(100, $acpt_field);
    }

    /**
     * @depends can_edit_acpt_meta_field_value
     * @test
     */
    public function can_display_acpt_meta_field()
    {
        $acpt_field = acpt_field([
            'post_id' => $this->oldest_page_id,
            'box_name' => 'box_name',
            'field_name' => 'Range',
        ]);

        $this->assertEquals(100, $acpt_field);
    }

    /**
     * @depends can_edit_acpt_meta_field_value
     * @test
     */
    public function can_delete_acpt_meta_field_value()
    {
        $delete_acpt_meta_field_value = delete_acpt_meta_field_value([
            'post_id' => $this->oldest_page_id,
            'box_name' => 'box_name',
            'field_name' => 'Range',
        ]);

        $this->assertTrue($delete_acpt_meta_field_value);

        $delete_acpt_meta_box = delete_acpt_meta_box('page', 'box_name');

        $this->assertTrue($delete_acpt_meta_box);

        $acpt_field = get_acpt_field([
            'post_id' => $this->oldest_page_id,
            'box_name' => 'box_name',
            'field_name' => 'Range',
        ]);

        $this->assertNull($acpt_field);
    }
}