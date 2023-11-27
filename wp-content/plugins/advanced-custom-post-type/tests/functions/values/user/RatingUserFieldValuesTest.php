<?php

namespace ACPT\Tests;

use ACPT\Core\Models\User\UserMetaBoxFieldModel;

class RatingUserFieldValuesTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function can_add_acpt_user_meta_field_value()
    {
        add_acpt_user_meta_field(
            [
                'box_name' => 'box_name',
                'field_name' => 'Rating',
                'field_type' => UserMetaBoxFieldModel::RATING_TYPE,
                'show_in_archive' => false,
                'required' => false,
            ]
        );

        $add_acpt_meta_field_wrong_value = add_acpt_user_meta_field_value([
                'box_name' => 'box_name',
                'field_name' => 'Rating',
                'value' => "wrong value",
        ]);

        $this->assertFalse($add_acpt_meta_field_wrong_value);

        $add_acpt_meta_field_value = add_acpt_user_meta_field_value([
                'box_name' => 'box_name',
                'field_name' => 'Rating',
                'value' => 9,
        ]);

        $this->assertTrue($add_acpt_meta_field_value);

        $acpt_field = get_acpt_user_field([
                'box_name' => 'box_name',
                'field_name' => 'Rating',
        ]);

        $this->assertEquals(9, $acpt_field);
    }

    /**
     * @depends can_add_acpt_user_meta_field_value
     * @test
     */
    public function can_edit_acpt_user_meta_field_value()
    {
        $edit_acpt_meta_field_value = edit_acpt_user_meta_field_value([
                'box_name' => 'box_name',
                'field_name' => 'Rating',
                'value' => 7,
        ]);

        $this->assertTrue($edit_acpt_meta_field_value);

        $acpt_field = get_acpt_user_field([
                'box_name' => 'box_name',
                'field_name' => 'Rating',
        ]);

        $this->assertEquals(7, $acpt_field);
    }

    /**
     * @depends can_edit_acpt_user_meta_field_value
     * @test
     */
    public function can_delete_acpt_user_meta_field_value()
    {
        $delete_acpt_meta_field_value = delete_acpt_user_meta_field_value([
                'box_name' => 'box_name',
                'field_name' => 'Rating',
        ]);

        $this->assertTrue($delete_acpt_meta_field_value);

        $delete_acpt_meta_box = delete_acpt_user_meta_box('box_name');

        $this->assertTrue($delete_acpt_meta_box);

        $acpt_field = get_acpt_user_field([
                'box_name' => 'box_name',
                'field_name' => 'Rating',
        ]);

        $this->assertNull($acpt_field);
    }
}