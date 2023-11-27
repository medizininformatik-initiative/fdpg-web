<?php

namespace ACPT\Tests;

use ACPT\Core\Models\User\UserMetaBoxFieldModel;

class WeightUserFieldValuesTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function can_add_acpt_user_meta_field_value()
    {
        add_acpt_user_meta_field(
            [
                'box_name' => 'box_name',
                'field_name' => 'Weight field',
                'field_type' => UserMetaBoxFieldModel::WEIGHT_TYPE,
                'show_in_archive' => false,
                'required' => false,
            ]
        );

        $add_acpt_meta_field_value = add_acpt_user_meta_field_value([
            'box_name' => 'box_name',
            'field_name' => 'Weight field',
            'value' => [
                'weight' => 32,
                'unit' => 'GRAM'
            ],
        ]);

        $this->assertTrue($add_acpt_meta_field_value);

        $acpt_field = get_acpt_user_field([
                'box_name' => 'box_name',
                'field_name' => 'Weight field',
        ]);

        $this->assertEquals([
            'weight' => 32,
            'unit' => 'GRAM'
        ], $acpt_field);
    }

    /**
     * @depends can_add_acpt_user_meta_field_value
     * @test
     */
    public function can_edit_acpt_user_meta_field_value()
    {
        $edit_acpt_meta_field_value = edit_acpt_user_meta_field_value([
                'box_name' => 'box_name',
                'field_name' => 'Weight field',
                'value' => [
                    'weight' => 1332,
                    'unit' => 'MILLIGRAM'
                ],
        ]);

        $this->assertTrue($edit_acpt_meta_field_value);

        $acpt_field = get_acpt_user_field([
                'box_name' => 'box_name',
                'field_name' => 'Weight field',
        ]);

        $this->assertEquals([
                'weight' => 1332,
                'unit' => 'MILLIGRAM'
        ], $acpt_field);
    }

    /**
     * @depends can_edit_acpt_user_meta_field_value
     * @test
     */
    public function can_delete_acpt_user_meta_field_value()
    {
        $delete_acpt_meta_field_value = delete_acpt_user_meta_field_value([
                'box_name' => 'box_name',
                'field_name' => 'Weight field',
        ]);

        $this->assertTrue($delete_acpt_meta_field_value);

        $delete_acpt_meta_box = delete_acpt_user_meta_box( 'box_name');

        $this->assertTrue($delete_acpt_meta_box);

        $acpt_field = get_acpt_user_field([
                'box_name' => 'box_name',
                'field_name' => 'Weight field',
        ]);

        $this->assertNull($acpt_field);
    }
}