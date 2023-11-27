<?php

namespace ACPT\Tests;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class DateRangeFieldValuesTest extends AbstractTestCase
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
                'field_name' => 'Date field',
                'field_type' => CustomPostTypeMetaBoxFieldModel::DATE_RANGE_TYPE,
                'show_in_archive' => false,
                'required' => false,
            ]
        );

        $add_acpt_meta_field_value = add_acpt_meta_field_value([
            'post_id' => $this->oldest_page_id,
            'box_name' => 'box_name',
            'field_name' => 'Date field',
            'value' => [
	            "2022-06-30",
	            "2022-07-30",
            ],
        ]);

        $this->assertTrue($add_acpt_meta_field_value);

        $acpt_field = get_acpt_field([
            'post_id' => $this->oldest_page_id,
            'box_name' => 'box_name',
            'field_name' => 'Date field',
        ]);

        $this->assertEquals([
	        "2022-06-30",
	        "2022-07-30",
        ], $acpt_field);
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
            'field_name' => 'Date field',
            'value' => [
	            "2022-06-30",
	            "2023-01-31",
            ],
        ]);

        $this->assertTrue($edit_acpt_meta_field_value);

        $acpt_field = get_acpt_field([
            'post_id' => $this->oldest_page_id,
            'box_name' => 'box_name',
            'field_name' => 'Date field',
        ]);

        $this->assertEquals([
	        "2022-06-30",
	        "2023-01-31",
        ], $acpt_field);
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
            'field_name' => 'Date field',
        ]);

        $this->assertEquals('30/06/2022 - 31/01/2023', $acpt_field);

        $acpt_field = acpt_field([
            'post_id' => $this->oldest_page_id,
            'box_name' => 'box_name',
            'field_name' => 'Date field',
            'date_format' => 'Y-m-d'
        ]);

        $this->assertEquals('2022-06-30 - 2023-01-31', $acpt_field);
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
            'field_name' => 'Date field',
        ]);

        $this->assertTrue($delete_acpt_meta_field_value);

        $delete_acpt_meta_box = delete_acpt_meta_box('page', 'box_name');

        $this->assertTrue($delete_acpt_meta_box);

        $acpt_field = get_acpt_field([
            'post_id' => $this->oldest_page_id,
            'box_name' => 'box_name',
            'field_name' => 'Date field',
        ]);

        $this->assertNull($acpt_field);
    }
}