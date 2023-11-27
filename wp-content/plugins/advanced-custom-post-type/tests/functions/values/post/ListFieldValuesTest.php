<?php

namespace ACPT\Tests;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class ListFieldValuesTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function can_add_acpt_meta_field_row_value()
    {
        $deleteFieldValue = delete_acpt_meta_field_value([
            'post_id' => $this->oldest_page_id,
            'box_name' => 'box_name',
            'field_name' => 'List field',
        ]);

        $deleteMetaBox = delete_acpt_meta_box('page', 'box_name');

	    $acpt_field = get_acpt_field([
		    'post_id' => $this->oldest_page_id,
		    'box_name' => 'box_name',
		    'field_name' => 'List field',
	    ]);

	    $this->assertNull($acpt_field);

        add_acpt_meta_field(
            [
                'post_type' => 'page',
                'box_name' => 'box_name',
                'field_name' => 'List field',
                'field_type' => CustomPostTypeMetaBoxFieldModel::LIST_TYPE,
                'show_in_archive' => false,
                'required' => false,
                'advanced_options' => [
                    [
                        'key' => 'before',
                        'value' => '<p>',
                    ],
                    [
                        'key' => 'after',
                        'value' => '</p>',
                    ],
                ]
            ]
        );

        $add_acpt_meta_field_value = add_acpt_meta_field_row_value([
            'post_id' => $this->oldest_page_id,
            'box_name' => 'box_name',
            'field_name' => 'List field',
            'value' => "text text",
        ]);

        $this->assertTrue($add_acpt_meta_field_value);

        $add_acpt_meta_field_value = add_acpt_meta_field_row_value([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'List field',
                'value' => "bla bla",
        ]);

        $this->assertTrue($add_acpt_meta_field_value);

        $acpt_field = get_acpt_field([
            'post_id' => $this->oldest_page_id,
            'box_name' => 'box_name',
            'field_name' => 'List field',
        ]);

        $this->assertEquals($acpt_field[0], '<p>text text</p>');
        $this->assertEquals($acpt_field[1], '<p>bla bla</p>');



        $has_rows = acpt_field_has_rows([
            'post_id' => $this->oldest_page_id,
            'box_name' => 'box_name',
            'field_name' => 'List field',
        ]);

        $this->assertTrue($has_rows);
    }

    /**
     * @depends can_add_acpt_meta_field_row_value
     * @test
     */
    public function can_edit_acpt_meta_field_row_value()
    {
        $edit_acpt_meta_field_value = edit_acpt_meta_field_row_value([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'List field',
                'value' => "other value",
                'index' => 0
        ]);

        $this->assertTrue($edit_acpt_meta_field_value);

        $acpt_field = get_acpt_field([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'List field',
        ]);

        $this->assertEquals($acpt_field[0], '<p>other value</p>');
        $this->assertEquals($acpt_field[1], '<p>bla bla</p>');
    }

    /**
     * @depends can_edit_acpt_meta_field_row_value
     * @test
     */
    public function can_display_acpt_meta_field()
    {
        $acpt_field = acpt_field([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'List field',
        ]);

        $this->assertStringContainsString('other value', $acpt_field);
        $this->assertStringContainsString('bla bla', $acpt_field);
    }

    /**
     * @depends can_display_acpt_meta_field
     * @test
     */
    public function can_delete_acpt_meta_field_row_value()
    {
        $delete_acpt_meta_field_value = delete_acpt_meta_field_row_value([
            'post_id' => $this->oldest_page_id,
            'box_name' => 'box_name',
            'field_name' => 'List field',
            'index' => 0
        ]);

        $this->assertTrue($delete_acpt_meta_field_value);

        $acpt_field = get_acpt_field([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'List field',
        ]);

        $this->assertEquals("<p>bla bla</p>", $acpt_field[0]);

	    // delete another row
	    $delete_acpt_meta_field_value = delete_acpt_meta_field_row_value([
		    'post_id' => $this->oldest_page_id,
		    'box_name' => 'box_name',
		    'field_name' => 'List field',
		    'index' => 0
	    ]);

	    $this->assertTrue($delete_acpt_meta_field_value);

	    $acpt_field = get_acpt_field([
		    'post_id' => $this->oldest_page_id,
		    'box_name' => 'box_name',
		    'field_name' => 'List field',
	    ]);

	    $this->assertEmpty($acpt_field);

	    $get_acpt_user_field_object = get_acpt_field_object('page','box_name', 'List field');

	    $this->assertNotEmpty($get_acpt_user_field_object);

        $delete_acpt_meta_box = delete_acpt_meta_box('page', 'box_name');

        $this->assertTrue($delete_acpt_meta_box);

        $acpt_field = get_acpt_field([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'List field',
        ]);

        $this->assertNull($acpt_field);
    }
}