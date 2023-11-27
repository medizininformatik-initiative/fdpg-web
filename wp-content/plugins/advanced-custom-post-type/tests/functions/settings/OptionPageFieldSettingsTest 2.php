<?php

namespace ACPT\Tests;

use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;

class OptionPageFieldSettingsTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function return_false_when_add_a_meta_box_with_wrong_params()
    {
        $add_meta_box = add_acpt_option_page_meta_box('not-existing-page', 'box_name');

        $this->assertFalse($add_meta_box);
    }

    /**
     * @test
     */
    public function can_add_a_meta_box()
    {
	    $new_page = register_acpt_option_page([
		    'menu_slug' => 'new-page',
		    'page_title' => 'New page',
		    'menu_title' => 'New page menu title',
		    'icon' => 'admin-appearance',
		    'capability' => 'manage_options',
		    'description' => 'lorem ipsum',
		    'position' => 77,
	    ]);

	    $this->assertTrue($new_page);

        $add_meta_box = add_acpt_option_page_meta_box('new-page','box_name', 'Box label');

        $this->assertTrue($add_meta_box);
    }

    /**
     * @test
     */
    public function can_avoid_meta_box_name_duplicates()
    {
        $add_meta_box = add_acpt_option_page_meta_box('new-page', 'box_name');

        $this->assertTrue($add_meta_box);

        $add_meta_box = add_acpt_option_page_meta_box('new-page', 'box_name');

        $this->assertTrue($add_meta_box);

        // check for duplicates here
        $meta_box_object = get_acpt_option_page_box_object('new-page', 'box_name');
        $meta_box_1_object = get_acpt_option_page_box_object('new-page', 'box_name_1');
        $meta_box_2_object = get_acpt_option_page_box_object('new-page', 'box_name_2');

        $this->assertNotNull($meta_box_object);
        $this->assertNotNull($meta_box_1_object);
        $this->assertNotNull($meta_box_2_object);
    }

    /**
     * @test
     */
    public function return_false_when_add_a_meta_field_with_wrong_params()
    {
        $add_meta_field = add_acpt_option_page_meta_field(
            [
                'option_page' => 'new-page',
                'box_name' => 'box_name',
                'field_name' => 'field_name',
            ]
        );

        $this->assertFalse($add_meta_field);

        $add_meta_field = add_acpt_option_page_meta_field(
            [
                'option_page' => 'new-page',
                'box_name' => 'box_name',
                'field_name' => 'field_name',
                'field_type' => 'not-allowed',
                'required' => false,
            ]
        );

        $this->assertFalse($add_meta_field);
    }

    /**
     * @test
     */
    public function can_add_acpt_meta_field()
    {
        $add_meta_field = add_acpt_option_page_meta_field(
            [
	            'option_page' => 'new-page',
                'box_name' => 'new_box',
                'field_name' => 'field_name',
                'field_type' => OptionPageMetaBoxFieldModel::TEXT_TYPE,
                'required' => false,
            ]
        );

        $this->assertTrue($add_meta_field);
        $this->assertNotNull(get_acpt_option_page_field_object('new-page', 'new_box', 'field_name'));
    }

    /**
     * @test
     */
    public function can_add_acpt_meta_field_with_advanced_options()
    {
        $add_meta_field = add_acpt_option_page_meta_field(
            [
	            'option_page' => 'new-page',
                'box_name' => 'new_box',
                'field_name' => 'advanced_field',
                'field_type' => OptionPageMetaBoxFieldModel::TEXT_TYPE,
                'required' => false,
                'default_value' => "foo",
                'description' => "lorem ipsum dolor facium",
                'advanced_options' => [
                    [
                        'value' => '</p>',
                        'key' => 'after',
                    ],
                    [
                        'value' => '<p>',
                        'key' => 'before',
                    ],
                    [
                        'value' => '平仮名',
                        'key' => 'label',
                    ],
                ]
            ]
        );

        $this->assertTrue($add_meta_field);

        $object = get_acpt_option_page_field_object('new-page', 'new_box', 'advanced_field');

        $this->assertNotNull($object);
        $this->assertCount(3, $object->advanced_options);
    }

    /**
     * @test
     */
    public function can_add_acpt_meta_field_with_options()
    {
        $add_meta_field = add_acpt_option_page_meta_field(
            [
	            'option_page' => 'new-page',
                'box_name' => 'new_box',
                'field_name' => 'select_name',
                'field_type' => OptionPageMetaBoxFieldModel::SELECT_TYPE,
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

        $object = get_acpt_option_page_field_object('new-page', 'new_box', 'select_name');

        $this->assertNotNull($object);
    }

    /**
     * @test
     */
    public function can_add_acpt_meta_field_with_visibility_conditions()
    {
        $add_meta_field = add_acpt_option_page_meta_field(
            [
	            'option_page' => 'new-page',
                'box_name' => 'new_box',
                'field_name' => 'Field_with_visibility_conditions',
                'field_type' => OptionPageMetaBoxFieldModel::TEXT_TYPE,
                'required' => false,
                'visibility_conditions' => [
                    [
                        'type' => 'value',
                        'value' => 'ciao',
                    ],
                ]
            ]
        );

        $this->assertTrue($add_meta_field);

        $field_object = get_acpt_option_page_field_object('new-page', 'new_box', 'Field_with_visibility_conditions');

        $this->assertEquals('value', $field_object->visibility_conditions[0]->type);
        $this->assertEquals('=', $field_object->visibility_conditions[0]->operator);
        $this->assertEquals('ciao', $field_object->visibility_conditions[0]->value);
    }

	/**
	 * @test
	 */
	public function can_add_acpt_flexible_meta_field()
	{
		$add_meta_field = add_acpt_option_page_meta_field(
			[
				'option_page' => 'new-page',
				'box_name' => 'new_box',
				'field_name' => 'Flexible',
				'field_type' => OptionPageMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE,
				'required' => false,
				'blocks' => [
					[
						'block_name' => 'block',
						'block_label' => 'block label',
						'fields' => [
							[
								'field_name' => 'Text',
								'field_type' => OptionPageMetaBoxFieldModel::TEXT_TYPE,
								'required' => true,
							],
							[
								'field_name' => 'Url',
								'field_type' => OptionPageMetaBoxFieldModel::URL_TYPE,
								'required' => false,
							],
						],
					]
				],
			]
		);

		$this->assertTrue($add_meta_field);

		$object = get_acpt_option_page_field_object('new-page', 'new_box', 'Flexible');

		$this->assertCount(1, $object->blocks);
		$this->assertEquals("Text", $object->blocks[0]->fields[0]->field_name);
		$this->assertEquals("Url", $object->blocks[0]->fields[1]->field_name);

		$sub_object = get_acpt_option_page_field_block_object('new-page', 'new_box', 'Flexible', 'no-existing');

		$this->assertNull($sub_object);

		$sub_object = get_acpt_option_page_field_block_object('new-page', 'new_box', 'Flexible', 'block');

		$this->assertEquals("block", $sub_object->block_name);

		$sub_object = get_acpt_option_page_nested_field_row_object('new-page', 'new_box', 'Flexible', 'block', 'Text');

		$this->assertEquals("Text", $sub_object->field_name);
	}

    /**
     * @test
     */
    public function return_false_if_edit_meta_box_name_with_wrong_values()
    {
        $delete_acpt_meta_field = edit_acpt_option_page_meta_box('not-existing', 'not-existing', 'select_name');

        $this->assertFalse($delete_acpt_meta_field);
    }

    /**
     * @test
     */
    public function can_edit_meta_box_name()
    {

	    $new_page = register_acpt_option_page([
		    'menu_slug' => 'another-page',
		    'page_title' => 'Another page',
		    'menu_title' => 'Another page menu title',
		    'icon' => 'admin-appearance',
		    'capability' => 'manage_options',
		    'description' => 'lorem ipsum',
		    'position' => 78,
	    ]);

	    $this->assertTrue($new_page);

        $add_meta_field = add_acpt_option_page_meta_field(
            [
	            'option_page' => 'another-page',
                'box_name' => 'new_box',
                'field_name' => 'Field_with_visibility_conditions',
                'field_type' => OptionPageMetaBoxFieldModel::TEXT_TYPE,
                'required' => false,
            ]
        );

        $this->assertTrue($add_meta_field);

        $edit_meta_field = edit_acpt_option_page_meta_box('another-page', 'new_box', 'new_box modified');

        $this->assertTrue($edit_meta_field);
    }

    /**
     * @test
     */
    public function can_delete_meta_field()
    {
        $delete_acpt_meta_field = delete_acpt_option_page_meta_field('new-page', 'new_box', 'select_name');

        $this->assertTrue($delete_acpt_meta_field);
    }

    /**
     * @test
     */
    public function can_edit_acpt_meta_field()
    {
        $edit_meta_field = edit_acpt_option_page_meta_field(
            [
	            'option_page' => 'new-page',
                'box_name' => 'new_box',
                'old_field_name' => 'field_name',
                'field_name' => 'field_name_modified',
                'field_type' => OptionPageMetaBoxFieldModel::SELECT_TYPE,
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

        $field_object = get_acpt_option_page_field_object('new-page', 'new_box', 'field_name_modified');

        $this->assertCount(3, $field_object->options);
        $this->assertEquals('field_name_modified', $field_object->field_name);
        $this->assertEquals(OptionPageMetaBoxFieldModel::SELECT_TYPE, $field_object->field_type);
    }

    /**
     * @test
     */
    public function can_delete_meta_box()
    {
        $delete_acpt_meta_box = delete_acpt_option_page_meta_box('new-page', 'box_name');
        $delete_acpt_meta_box_2 = delete_acpt_option_page_meta_box('new-page', 'box_name_1');
        $delete_acpt_meta_box_3 = delete_acpt_option_page_meta_box('new-page', 'box_name_2');
        $delete_acpt_meta_box_4 = delete_acpt_option_page_meta_box('new-page', 'new_box');

        $this->assertTrue($delete_acpt_meta_box);
        $this->assertTrue($delete_acpt_meta_box_2);
        $this->assertTrue($delete_acpt_meta_box_3);
        $this->assertTrue($delete_acpt_meta_box_4);

        $delete_acpt_meta_box = delete_acpt_option_page_meta_box('another-page', 'new_box modified');

        $this->assertTrue($delete_acpt_meta_box);

	    $this->assertTrue(delete_acpt_option_page('new-page', true));
	    $this->assertTrue(delete_acpt_option_page('another-page', true));
    }
}