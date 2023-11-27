<?php

namespace ACPT\Tests;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class CustomPostTypeFieldSettingsTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function return_false_when_add_a_meta_box_with_wrong_params()
    {
        $add_meta_box = add_acpt_meta_box('not-existing-post', 'box_name');

        $this->assertFalse($add_meta_box);
    }

    /**
     * @test
     */
    public function can_add_a_meta_box()
    {
        $add_meta_box = add_acpt_meta_box('page','box_name');

        $this->assertTrue($add_meta_box);
    }

	/**
	 * @test
	 */
	public function can_add_a_meta_box_with_label()
	{
		$add_meta_box = add_acpt_meta_box('page','box_name', 'Box label');

		$this->assertTrue($add_meta_box);
	}

    /**
     * @test
     */
    public function can_avoid_meta_box_name_duplicates()
    {
        $add_meta_box = add_acpt_meta_box('page', 'box_name');

        $this->assertTrue($add_meta_box);

        $add_meta_box = add_acpt_meta_box('page', 'box_name');

        $this->assertTrue($add_meta_box);

        // check for duplicates here
        $meta_box_object = get_acpt_box_object('page', 'box_name');
        $meta_box_1_object = get_acpt_box_object('page', 'box_name_1');
        $meta_box_2_object = get_acpt_box_object('page', 'box_name_2');

        $this->assertNotNull($meta_box_object);
        $this->assertNotNull($meta_box_1_object);
        $this->assertNotNull($meta_box_2_object);
    }

    /**
     * @test
     */
    public function return_false_when_add_a_meta_field_with_wrong_params()
    {
        $add_meta_field = add_acpt_meta_field(
            [
                'post_type' => 'page',
                'box_name' => 'box_name',
                'field_name' => 'field_name',
            ]
        );

        $this->assertFalse($add_meta_field);

        $add_meta_field = add_acpt_meta_field(
            [
                'post_type' => 'page',
                'box_name' => 'box_name',
                'field_name' => 'field_name',
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
    public function can_add_acpt_meta_field()
    {
        $add_meta_field = add_acpt_meta_field(
            [
                'post_type' => 'page',
                'box_name' => 'new_box',
                'field_name' => 'field_name',
                'field_type' => CustomPostTypeMetaBoxFieldModel::TEXT_TYPE,
                'show_in_archive' => false,
                'required' => false,
            ]
        );

        $this->assertTrue($add_meta_field);
        $this->assertNotNull(get_acpt_field_object('page', 'new_box', 'field_name'));
    }

    /**
     * @test
     */
    public function can_add_acpt_meta_field_with_advanced_options()
    {
        $add_meta_field = add_acpt_meta_field(
            [
                'post_type' => 'page',
                'box_name' => 'new_box',
                'field_name' => 'advanced_field',
                'field_type' => CustomPostTypeMetaBoxFieldModel::TEXT_TYPE,
                'show_in_archive' => false,
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

        $object = get_acpt_field_object('page', 'new_box', 'advanced_field');

        $this->assertNotNull($object);
        $this->assertCount(3, $object->advanced_options);
    }

    /**
     * @test
     */
    public function can_add_acpt_meta_field_with_options()
    {
        $add_meta_field = add_acpt_meta_field(
            [
                'post_type' => 'page',
                'box_name' => 'new_box',
                'field_name' => 'select_name',
                'field_type' => CustomPostTypeMetaBoxFieldModel::SELECT_TYPE,
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

        $object = get_acpt_field_object('page', 'new_box', 'select_name');

        $this->assertNotNull($object);
    }

	/**
	 * @test
	 */
	public function can_add_acpt_flexible_meta_field()
	{
		$add_meta_field = add_acpt_meta_field(
			[
				'post_type' => 'page',
				'box_name' => 'new_box',
				'field_name' => 'Flexible',
				'field_type' => CustomPostTypeMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE,
				'show_in_archive' => false,
				'required' => false,
				'blocks' => [
					[
						'block_name' => 'block',
						'block_label' => 'block label',
						'fields' => [
							[
								'field_name' => 'Text',
								'field_type' => CustomPostTypeMetaBoxFieldModel::TEXT_TYPE,
								'required' => true,
							],
							[
								'field_name' => 'Url',
								'field_type' => CustomPostTypeMetaBoxFieldModel::URL_TYPE,
								'required' => false,
							],
						],
					]
				],
			]
		);

		$this->assertTrue($add_meta_field);

		$object = get_acpt_field_object('page', 'new_box', 'Flexible');

		$this->assertCount(1, $object->blocks);
		$this->assertEquals("Text", $object->blocks[0]->fields[0]->field_name);
		$this->assertEquals("Url", $object->blocks[0]->fields[1]->field_name);

		$sub_object = get_acpt_field_block_object('page', 'new_box', 'Flexible', 'no-existing');

		$this->assertNull($sub_object);

		$sub_object = get_acpt_field_block_object('page', 'new_box', 'Flexible', 'block');

		$this->assertEquals("block", $sub_object->block_name);

		$sub_object = get_acpt_nested_field_row_object('page', 'new_box', 'Flexible', 'block', 'Text');

		$this->assertEquals("Text", $sub_object->field_name);
	}

    /**
     * @test
     */
    public function can_add_acpt_repeater_meta_field()
    {
        $add_meta_field = add_acpt_meta_field(
            [
                'post_type' => 'page',
                'box_name' => 'new_box',
                'field_name' => 'Repeater',
                'field_type' => CustomPostTypeMetaBoxFieldModel::REPEATER_TYPE,
                'show_in_archive' => false,
                'required' => false,
                'children' => [
                    [
                        'field_name' => 'Text',
                        'field_type' => CustomPostTypeMetaBoxFieldModel::TEXT_TYPE,
                        'required' => true,
                    ],
                    [
                        'field_name' => 'Url',
                        'field_type' => CustomPostTypeMetaBoxFieldModel::URL_TYPE,
                        'required' => false,
                    ],
                    [
                        'field_name' => 'Select',
                        'field_type' => CustomPostTypeMetaBoxFieldModel::SELECT_TYPE,
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
                    ],
                ]
            ]
        );

        $this->assertTrue($add_meta_field);

        $object = get_acpt_field_object('page', 'new_box', 'Repeater');

        $this->assertCount(3, $object->children);
        $this->assertEquals("Text", $object->children[0]->field_name);
        $this->assertEquals("Url", $object->children[1]->field_name);
        $this->assertEquals("Select", $object->children[2]->field_name);

        $sub_object = get_acpt_field_row_object('page', 'new_box', 'Repeater', 'no-existing');

        $this->assertNull($sub_object);

        $sub_object = get_acpt_field_row_object('page', 'new_box', 'Repeater', 'Text');

        $this->assertEquals("Text", $sub_object->field_name);
    }

    /**
     * @test
     */
    public function can_add_acpt_meta_field_with_visibility_conditions()
    {
        $add_meta_field = add_acpt_meta_field(
            [
                'post_type' => 'page',
                'box_name' => 'new_box',
                'field_name' => 'Field_with_visibility_conditions',
                'field_type' => CustomPostTypeMetaBoxFieldModel::TEXT_TYPE,
                'show_in_archive' => false,
                'required' => false,
                'visibility_conditions' => [
                    [
                        'type' => 'value',
                        'value' => 'ciao',
                        'logic' => 'and'
                    ],
                    [
                        'type' => 'post_id',
                        'value' => get_post($this->oldest_page_id),
                        'operator' => '!=',
                        'logic' => 'and'
                    ],
                    [
                        'type' => 'other_fields',
                        'meta_field' => 'select_name',
                        'operator' => '!=',
                        'value' => 'fuzz',
                    ]
                ]
            ]
        );

        $this->assertTrue($add_meta_field);

        $field_object = get_acpt_field_object('page', 'new_box', 'Field_with_visibility_conditions');

        $this->assertEquals('value', $field_object->visibility_conditions[0]->type);
        $this->assertEquals('=', $field_object->visibility_conditions[0]->operator);
        $this->assertEquals('ciao', $field_object->visibility_conditions[0]->value);
        $this->assertEquals( 'and', $field_object->visibility_conditions[0]->logic);
        $this->assertEquals('post_id', $field_object->visibility_conditions[1]->type);
        $this->assertEquals('!=', $field_object->visibility_conditions[1]->operator);
        $this->assertEquals($this->oldest_page_id, $field_object->visibility_conditions[1]->value);
        $this->assertEquals( 'and', $field_object->visibility_conditions[1]->logic);
        $this->assertEquals('other_fields', $field_object->visibility_conditions[2]->type);
        $this->assertEquals('select_name', $field_object->visibility_conditions[2]->meta_field);
        $this->assertEquals('!=', $field_object->visibility_conditions[2]->operator);
        $this->assertEquals('fuzz', $field_object->visibility_conditions[2]->value);
        $this->assertEquals( '', $field_object->visibility_conditions[2]->logic);
    }

    /**
     * @test
     */
    public function more_tests_with_visibility_conditions()
    {
        $add_meta_field = add_acpt_meta_field(
            [
                'post_type' => 'post',
                'box_name' => 'new_box',
                'field_name' => 'Field_with_visibility_conditions',
                'field_type' => CustomPostTypeMetaBoxFieldModel::TEXT_TYPE,
                'show_in_archive' => false,
                'required' => false,
                'visibility_conditions' => [
                    [
                        'type' => 'taxonomy',
                        'tax_name' => 'Categories',
                        'value' => "1",
                        'operator' => 'in'
                    ],
                ]
            ]
        );

        $this->assertTrue($add_meta_field);

        $field_object = get_acpt_field_object('post', 'new_box', 'Field_with_visibility_conditions');

        $this->assertEquals('taxonomy', $field_object->visibility_conditions[0]->type);
        $this->assertEquals('Categories', $field_object->visibility_conditions[0]->tax_name);
        $this->assertEquals('in', $field_object->visibility_conditions[0]->operator);
        $this->assertEquals('1', $field_object->visibility_conditions[0]->value);
        $this->assertEquals( '', $field_object->visibility_conditions[0]->logic);
    }

    /**
     * @test
     */
    public function can_add_a_one_to_one_uni_relation()
    {
        $add_meta_field = add_acpt_meta_field(
            [
                'post_type' => 'post',
                'box_name' => 'new_box',
                'field_name' => 'Field_with_relation',
                'field_type' => CustomPostTypeMetaBoxFieldModel::POST_TYPE,
                'show_in_archive' => false,
                'required' => false,
                'relations' => [
                    [
                        'related_to' => [
                            'post_type' => 'page',
                        ],
                        'relation' => 'one_to_one_uni',
                    ],
                ]
            ]
        );

        $this->assertTrue($add_meta_field);

        $field_object = get_acpt_field_object('post', 'new_box', 'Field_with_relation');

        $this->assertEquals($field_object->relations[0]->relation, 'one_to_one_uni');
        $this->assertEquals($field_object->relations[0]->related_to->post_name, 'page');
    }

    /**
     * @test
     */
    public function can_add_a_one_to_one_bi_relation()
    {
        $add_meta_field = add_acpt_meta_field(
            [
                'post_type' => 'post',
                'box_name' => 'new_box',
                'field_name' => 'Another_field_with_relation',
                'field_type' => CustomPostTypeMetaBoxFieldModel::POST_TYPE,
                'show_in_archive' => false,
                'required' => false,
                'relations' => [
                    [
                        'related_to' => [
                            'post_type' => 'page',
                            'box_name' => 'new_box',
                            'field_name' => 'Field_with_visibility_conditions',
                        ],
                        'relation' => 'one_to_one_bi',
                    ],
                ]
            ]
        );

        $this->assertTrue($add_meta_field);

        $field_object = get_acpt_field_object('post', 'new_box', 'Another_field_with_relation');

        $this->assertEquals($field_object->relations[0]->relation, 'one_to_one_bi');
        $this->assertEquals($field_object->relations[0]->related_to->post_name, 'page');
        $this->assertEquals($field_object->relations[0]->related_to->box_name, 'new_box');
        $this->assertEquals($field_object->relations[0]->related_to->field_name, 'Field_with_visibility_conditions');
    }

    /**
     * @test
     */
    public function return_false_if_edit_meta_box_name_with_wrong_values()
    {
        $delete_acpt_meta_field = edit_acpt_meta_box('not-existing', 'not-existing', 'Select_name');

        $this->assertFalse($delete_acpt_meta_field);
    }

    /**
     * @test
     */
    public function can_edit_meta_box_name()
    {
        $edit_meta_field = edit_acpt_meta_box('post', 'new_box', 'new_box_modified');

        $this->assertTrue($edit_meta_field);

	    $meta_box_object = get_acpt_box_object('post', 'new_box_modified');

	    $this->assertNotNull($meta_box_object);
    }

    /**
     * @test
     */
    public function can_delete_meta_field()
    {
        $delete_acpt_meta_field = delete_acpt_meta_field('page', 'new_box', 'select_name');

        $this->assertTrue($delete_acpt_meta_field);
    }

    /**
     * @test
     */
    public function can_edit_acpt_meta_field()
    {
        $edit_meta_field = edit_acpt_meta_field(
            [
                'post_type' => 'page',
                'box_name' => 'new_box',
                'old_field_name' => 'field_name',
                'field_name' => 'field_name_modified',
                'field_type' => CustomPostTypeMetaBoxFieldModel::SELECT_TYPE,
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

        $field_object = get_acpt_field_object('page', 'new_box', 'field_name_modified');

        $this->assertCount(3, $field_object->options);
        $this->assertEquals('field_name_modified', $field_object->field_name);
        $this->assertEquals(CustomPostTypeMetaBoxFieldModel::SELECT_TYPE, $field_object->field_type);
    }

    /**
     * @test
     */
    public function can_delete_meta_box()
    {
        $delete_acpt_meta_box = delete_acpt_meta_box('page', 'box_name');
        $delete_acpt_meta_box_2 = delete_acpt_meta_box('page', 'box_name_1');
        $delete_acpt_meta_box_3 = delete_acpt_meta_box('page', 'box_name_2');
        $delete_acpt_meta_box_4 = delete_acpt_meta_box('page', 'new_box');

        $this->assertTrue($delete_acpt_meta_box);
        $this->assertTrue($delete_acpt_meta_box_2);
        $this->assertTrue($delete_acpt_meta_box_3);
        $this->assertTrue($delete_acpt_meta_box_4);

        $delete_acpt_meta_box = delete_acpt_meta_box('post', 'new_box_modified');

        $this->assertTrue($delete_acpt_meta_box);
    }
}