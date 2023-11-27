<?php

namespace ACPT\Tests;

use ACPT\Core\Models\User\UserMetaBoxFieldModel;

class NumberUserFieldValuesTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function can_add_acpt_user_meta_field_value()
    {
        add_acpt_user_meta_field(
            [
                'box_name' => 'box_name',
                'field_name' => 'Number',
                'field_type' => UserMetaBoxFieldModel::NUMBER_TYPE,
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

        $add_acpt_meta_field_value = add_acpt_user_meta_field_value([
            'box_name' => 'box_name',
            'field_name' => 'Number',
            'value' => 1123,
        ]);

        $this->assertFalse($add_acpt_meta_field_value);

	    $add_acpt_meta_field_value = add_acpt_user_meta_field_value([
		    'box_name' => 'box_name',
		    'field_name' => 'Number',
		    'value' => 123,
	    ]);

	    $this->assertTrue($add_acpt_meta_field_value);

        $acpt_field = get_acpt_user_field([
            'box_name' => 'box_name',
            'field_name' => 'Number',
        ]);

        $this->assertEquals(123, $acpt_field);
    }

    /**
     * @depends can_add_acpt_user_meta_field_value
     * @test
     */
    public function can_edit_acpt_user_meta_field_value()
    {
        $edit_acpt_meta_field_value = edit_acpt_user_meta_field_value([
            'box_name' => 'box_name',
            'field_name' => 'Number',
            'value' => 100,
        ]);

        $this->assertTrue($edit_acpt_meta_field_value);

        $acpt_field = get_acpt_user_field([
            'box_name' => 'box_name',
            'field_name' => 'Number',
        ]);

        $this->assertEquals(100, $acpt_field);
    }

    /**
     * @depends can_edit_acpt_user_meta_field_value
     * @test
     */
    public function can_delete_acpt_user_meta_field_value()
    {
        $delete_acpt_meta_field_value = delete_acpt_user_meta_field_value([
            'box_name' => 'box_name',
            'field_name' => 'Number',
        ]);

        $this->assertTrue($delete_acpt_meta_field_value);

        $delete_acpt_meta_box = delete_acpt_user_meta_box('box_name');

        $this->assertTrue($delete_acpt_meta_box);

        $acpt_field = get_acpt_user_field([
            'box_name' => 'box_name',
            'field_name' => 'Number',
        ]);

        $this->assertNull($acpt_field);
    }
}