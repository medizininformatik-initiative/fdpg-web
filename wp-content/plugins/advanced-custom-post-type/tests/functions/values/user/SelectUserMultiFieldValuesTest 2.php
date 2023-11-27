<?php

namespace ACPT\Tests;

use ACPT\Core\Models\User\UserMetaBoxFieldModel;

class SelectUserMultiFieldValuesTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function can_add_acpt_user_meta_field_value()
    {
        add_acpt_user_meta_field(
            [
                'box_name' => 'box_name',
                'field_name' => 'Select multi field',
                'field_type' => UserMetaBoxFieldModel::SELECT_MULTI_TYPE,
                'show_in_archive' => false,
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

        $add_acpt_meta_field_wrong_value = add_acpt_user_meta_field_value([
            'box_name' => 'box_name',
            'field_name' => 'Select multi field',
            'value' => [
                "wrong value"
            ],
        ]);

        $this->assertFalse($add_acpt_meta_field_wrong_value);

        $add_acpt_meta_field_value = add_acpt_user_meta_field_value([
            'box_name' => 'box_name',
            'field_name' => 'Select multi field',
            'value' => [
                "foo",
                "fuzz",
            ],
        ]);

        $this->assertTrue($add_acpt_meta_field_value);

        $acpt_field = get_acpt_user_field([
                'box_name' => 'box_name',
                'field_name' => 'Select multi field',
        ]);

        $this->assertEquals([
            "foo",
            "fuzz",
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
                'field_name' => 'Select multi field',
                'value' => [
                    "bar"
                ],
        ]);

        $this->assertTrue($edit_acpt_meta_field_value);

        $acpt_field = get_acpt_user_field([
                'box_name' => 'box_name',
                'field_name' => 'Select multi field',
        ]);

        $this->assertEquals([
            "bar"
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
                'field_name' => 'Select multi field',
        ]);

        $this->assertTrue($delete_acpt_meta_field_value);

        $delete_acpt_meta_box = delete_acpt_user_meta_box('box_name');

        $this->assertTrue($delete_acpt_meta_box);

        $acpt_field = get_acpt_user_field([
                'box_name' => 'box_name',
                'field_name' => 'Select multi field',
        ]);

        $this->assertNull($acpt_field);
    }
}