<?php

namespace ACPT\Tests;

use ACPT\Core\Models\User\UserMetaBoxFieldModel;

class TextUserFieldValuesTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function can_add_acpt_user_meta_field_value()
    {
        add_acpt_user_meta_field(
            [
                'box_name' => 'box_name',
                'field_name' => 'Field name',
                'field_type' => UserMetaBoxFieldModel::TEXT_TYPE,
                'show_in_archive' => false,
                'required' => false,
            ]
        );

        $add_acpt_user_meta_field_value = add_acpt_user_meta_field_value([
            'box_name' => 'box_name',
            'field_name' => 'Field name',
            'value' => "text text",
        ]);

        $this->assertTrue($add_acpt_user_meta_field_value);

        $acpt_field = get_acpt_user_field([
            'box_name' => 'box_name',
            'field_name' => 'Field name',
        ]);

        $this->assertEquals('text text', $acpt_field);
    }

    /**
     * @depends can_add_acpt_user_meta_field_value
     * @test
     */
    public function can_edit_acpt_user_meta_field_value()
    {
        $edit_acpt_user_meta_field_value = edit_acpt_user_meta_field_value([
            'box_name' => 'box_name',
            'field_name' => 'Field name',
            'value' => "other value",
        ]);

        $this->assertTrue($edit_acpt_user_meta_field_value);

        $acpt_field = get_acpt_user_field([
            'box_name' => 'box_name',
            'field_name' => 'Field name',
        ]);

        $this->assertEquals('other value', $acpt_field);
    }

    /**
     * @depends can_edit_acpt_user_meta_field_value
     * @test
     */
    public function can_delete_acpt_user_meta_field_value()
    {
        $delete_acpt_user_meta_field_value = delete_acpt_user_meta_field_value([
            'box_name' => 'box_name',
            'field_name' => 'Field name',
        ]);

        $this->assertTrue($delete_acpt_user_meta_field_value);

        $delete_acpt_user_meta_box = delete_acpt_user_meta_box( 'box_name');

        $this->assertTrue($delete_acpt_user_meta_box);

        $acpt_user_field = get_acpt_user_field([
            'box_name' => 'box_name',
            'field_name' => 'Field name',
        ]);

        $this->assertNull($acpt_user_field);
    }
}