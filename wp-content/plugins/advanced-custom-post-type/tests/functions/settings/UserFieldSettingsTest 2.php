<?php

namespace ACPT\Tests;

use ACPT\Core\Models\User\UserMetaBoxFieldModel;

class UserFieldSettingsTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function can_add_a_meta_box()
    {
        $add_meta_box = add_acpt_user_meta_box('box_name');

        $this->assertTrue($add_meta_box);
    }

    /**
     * @test
     */
    public function can_avoid_meta_box_name_duplicates()
    {
        $add_meta_box = add_acpt_user_meta_box( 'box_name');

        $this->assertTrue($add_meta_box);

        $add_meta_box = add_acpt_user_meta_box( 'box_name');

        $this->assertTrue($add_meta_box);

        // check for duplicates here
        $meta_box_object = get_acpt_user_box_object( 'box_name');
        $meta_box_1_object = get_acpt_user_box_object( 'box_name_1');
        $meta_box_2_object = get_acpt_user_box_object( 'box_name_2');

        $this->assertNotNull($meta_box_object);
        $this->assertNotNull($meta_box_1_object);
        $this->assertNotNull($meta_box_2_object);
    }

    /**
     * @test
     */
    public function return_false_when_add_a_meta_field_with_wrong_params()
    {
        $add_meta_field = add_acpt_user_meta_field(
                [
                        'box_name' => 'box_name',
                        'field_name' => 'Field name',
                ]
        );

        $this->assertFalse($add_meta_field);

        $add_meta_field = add_acpt_user_meta_field(
                [
                        'box_name' => 'box_name',
                        'field_name' => 'Field name',
                        'field_type' => 'not-allowed',
                        'show_in_archive' => false,
                        'required' => false,
                ]
        );

        $this->assertFalse($add_meta_field);
    }

    /**
     * @test
     */
    public function can_add_acpt_user_meta_field()
    {
        $add_meta_field = add_acpt_user_meta_field(
                [
                        'box_name' => 'New box',
                        'field_name' => 'Field name',
                        'field_type' => UserMetaBoxFieldModel::TEXT_TYPE,
                        'show_in_archive' => false,
                        'required' => false,
                ]
        );

        $this->assertTrue($add_meta_field);
        $this->assertNotNull(get_acpt_user_field_object('New box', 'Field name'));
    }

    /**
     * @test
     */
    public function can_add_acpt_user_meta_field_with_options()
    {
        $add_meta_field = add_acpt_user_meta_field(
                [
                        'box_name' => 'New box',
                        'field_name' => 'Select name',
                        'field_type' => UserMetaBoxFieldModel::SELECT_TYPE,
                        'show_in_archive' => false,
                        'required' => false,
                        'default_value' => "foo",
                        'description' => "lorem ipsum dolor facium",
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

        $this->assertTrue($add_meta_field);
        $this->assertNotNull(get_acpt_user_field_object('New box', 'Select name'));
    }

    /**
     * @test
     */
    public function return_false_if_edit_meta_box_name_with_wrong_values()
    {
        $delete_acpt_user_meta_field = edit_acpt_user_meta_box('not-existing', 'Select name');

        $this->assertFalse($delete_acpt_user_meta_field);
    }

    /**
     * @test
     */
    public function can_edit_meta_box_name()
    {
        $edit_meta_field = edit_acpt_user_meta_box('New box', 'New box modified');

        $this->assertTrue($edit_meta_field);
    }

    /**
     * @test
     */
    public function can_delete_meta_field()
    {
        $delete_acpt_user_meta_field = delete_acpt_user_meta_field('New box modified', 'Select name');

        $this->assertTrue($delete_acpt_user_meta_field);
    }

    /**
     * @test
     */
    public function can_edit_acpt_user_meta_field()
    {
        $edit_meta_field = edit_acpt_user_meta_field(
                [
                        'box_name' => 'New box modified',
                        'old_field_name' => 'Field name',
                        'field_name' => 'Field name modified',
                        'field_type' => UserMetaBoxFieldModel::SELECT_TYPE,
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

        $this->assertTrue($edit_meta_field);

        $field_object = get_acpt_user_field_object('New box modified', 'Field name modified');

        $this->assertCount(3, $field_object->options);
        $this->assertEquals('Field name modified', $field_object->field_name);
        $this->assertEquals(UserMetaBoxFieldModel::SELECT_TYPE, $field_object->field_type);
    }

    /**
     * @test
     */
    public function can_delete_meta_box()
    {
        $delete_acpt_user_meta_box = delete_acpt_user_meta_box( 'box_name');
        $delete_acpt_user_meta_box_2 = delete_acpt_user_meta_box( 'box_name_1');
        $delete_acpt_user_meta_box_3 = delete_acpt_user_meta_box( 'box_name_2');
        $delete_acpt_user_meta_box_4 = delete_acpt_user_meta_box( 'New box modified');

        $this->assertTrue($delete_acpt_user_meta_box);
        $this->assertTrue($delete_acpt_user_meta_box_2);
        $this->assertTrue($delete_acpt_user_meta_box_3);
        $this->assertTrue($delete_acpt_user_meta_box_4);
    }
}