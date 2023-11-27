<?php

namespace ACPT\Tests;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class WeightFieldValuesTest extends AbstractTestCase
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
                'field_name' => 'Weight field',
                'field_type' => CustomPostTypeMetaBoxFieldModel::WEIGHT_TYPE,
                'show_in_archive' => false,
                'required' => false,
            ]
        );

        $add_acpt_meta_field_value = add_acpt_meta_field_value([
            'post_id' => $this->oldest_page_id,
            'box_name' => 'box_name',
            'field_name' => 'Weight field',
            'value' => [
                'weight' => 32,
                'unit' => 'GRAM'
            ],
        ]);

        $this->assertTrue($add_acpt_meta_field_value);

        $acpt_field = get_acpt_field([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'Weight field',
        ]);

        $this->assertEquals([
            'weight' => 32,
            'unit' => 'GRAM'
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
                'field_name' => 'Weight field',
                'value' => [
                    'weight' => 1332,
                    'unit' => 'MILLIGRAM'
                ],
        ]);

        $this->assertTrue($edit_acpt_meta_field_value);

        $acpt_field = get_acpt_field([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'Weight field',
        ]);

        $this->assertEquals([
                'weight' => 1332,
                'unit' => 'MILLIGRAM'
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
                'field_name' => 'Weight field',
        ]);

        $this->assertEquals('<span class="amount">1332<span class="currency">mg</span></span>', $acpt_field);
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
                'field_name' => 'Weight field',
        ]);

        $this->assertTrue($delete_acpt_meta_field_value);

        $delete_acpt_meta_box = delete_acpt_meta_box('page', 'box_name');

        $this->assertTrue($delete_acpt_meta_box);

        $acpt_field = get_acpt_field([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'Weight field',
        ]);

        $this->assertNull($acpt_field);
    }
}