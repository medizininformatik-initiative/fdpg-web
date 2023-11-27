<?php

namespace ACPT\Tests;

use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;

class TaxonomyFieldSettingsTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function return_false_when_add_a_meta_box_with_wrong_params()
    {
        $add_meta_box = add_acpt_tax_meta_box('not-existing-category', 'box_name');

        $this->assertFalse($add_meta_box);
    }

    /**
     * @test
     */
    public function can_add_a_meta_box()
    {
        $add_meta_box = add_acpt_tax_meta_box('category','box_name', 'Box label èèè');

        $this->assertTrue($add_meta_box);
    }

    /**
     * @test
     */
    public function can_avoid_meta_box_name_duplicates()
    {
        $add_meta_box = add_acpt_tax_meta_box('category', 'box_name');

        $this->assertTrue($add_meta_box);

        $add_meta_box = add_acpt_tax_meta_box('category', 'box_name');

        $this->assertTrue($add_meta_box);

        // check for duplicates here
        $meta_box_object = get_acpt_tax_box_object('category', 'box_name');
        $meta_box_1_object = get_acpt_tax_box_object('category', 'box_name_1');
        $meta_box_2_object = get_acpt_tax_box_object('category', 'box_name_2');

        $this->assertNotNull($meta_box_object);
        $this->assertNotNull($meta_box_1_object);
        $this->assertNotNull($meta_box_2_object);
    }

    /**
     * @test
     */
    public function return_false_when_add_a_meta_field_with_wrong_params()
    {
        $add_meta_field = add_acpt_tax_meta_field(
            [
                'taxonomy' => 'category',
                'box_name' => 'box_name',
                'field_name' => 'field_name',
            ]
        );

        $this->assertFalse($add_meta_field);

        $add_meta_field = add_acpt_tax_meta_field(
            [
                'taxonomy' => 'category',
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
        $add_meta_field = add_acpt_tax_meta_field(
            [
                'taxonomy' => 'category',
                'box_name' => 'new_box',
                'field_name' => 'field_name',
                'field_type' => TaxonomyMetaBoxFieldModel::TEXT_TYPE,
                'required' => false,
            ]
        );

        $this->assertTrue($add_meta_field);
        $this->assertNotNull(get_acpt_tax_field_object('category', 'new_box', 'field_name'));
    }

    /**
     * @test
     */
    public function can_add_acpt_meta_field_with_advanced_options()
    {
        $add_meta_field = add_acpt_tax_meta_field(
            [
                'taxonomy' => 'category',
                'box_name' => 'new_box',
                'field_name' => 'advanced_field',
                'field_type' => TaxonomyMetaBoxFieldModel::TEXT_TYPE,
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

        $object = get_acpt_tax_field_object('category', 'new_box', 'advanced_field');

        $this->assertNotNull($object);
        $this->assertCount(3, $object->advanced_options);
    }

    /**
     * @test
     */
    public function can_add_acpt_meta_field_with_options()
    {
        $add_meta_field = add_acpt_tax_meta_field(
            [
                'taxonomy' => 'category',
                'box_name' => 'new_box',
                'field_name' => 'select_name',
                'field_type' => TaxonomyMetaBoxFieldModel::SELECT_TYPE,
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

        $object = get_acpt_tax_field_object('category', 'new_box', 'select_name');

        $this->assertNotNull($object);
    }

    /**
     * @test
     */
    public function can_add_acpt_meta_field_with_visibility_conditions()
    {
        $add_meta_field = add_acpt_tax_meta_field(
            [
                'taxonomy' => 'category',
                'box_name' => 'new_box',
                'field_name' => 'field_with_visibility_conditions',
                'field_type' => TaxonomyMetaBoxFieldModel::TEXT_TYPE,
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

        $field_object = get_acpt_tax_field_object('category', 'new_box', 'field_with_visibility_conditions');

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
        $add_meta_field = add_acpt_tax_meta_field(
            [
                'taxonomy' => 'category',
                'box_name' => 'other_meta_box',
                'field_name' => 'field_with_visibility_conditions',
                'field_type' => TaxonomyMetaBoxFieldModel::TEXT_TYPE,
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

        $field_object = get_acpt_tax_field_object('category', 'other_meta_box', 'field_with_visibility_conditions');

        $this->assertEquals('taxonomy', $field_object->visibility_conditions[0]->type);
        $this->assertEquals('Categories', $field_object->visibility_conditions[0]->tax_name);
        $this->assertEquals('in', $field_object->visibility_conditions[0]->operator);
        $this->assertEquals('1', $field_object->visibility_conditions[0]->value);
        $this->assertEquals( '', $field_object->visibility_conditions[0]->logic);
    }

    /**
     * @test
     */
    public function return_false_if_edit_meta_box_name_with_wrong_values()
    {
        $delete_acpt_meta_field = edit_acpt_tax_meta_box('not-existing', 'not-existing', 'select_name');

        $this->assertFalse($delete_acpt_meta_field);
    }

    /**
     * @test
     */
    public function can_edit_meta_box_name()
    {
        $add_meta_field = add_acpt_tax_meta_field(
            [
                'taxonomy' => 'post_tag',
                'box_name' => 'new_box',
                'field_name' => 'field_with_visibility_conditions',
                'field_type' => TaxonomyMetaBoxFieldModel::TEXT_TYPE,
                'required' => false,
            ]
        );

        $this->assertTrue($add_meta_field);

        $edit_meta_field = edit_acpt_tax_meta_box('post_tag', 'new_box', 'new_box modified');

        $this->assertTrue($edit_meta_field);
    }

    /**
     * @test
     */
    public function can_delete_meta_field()
    {
        $delete_acpt_meta_field = delete_acpt_tax_meta_field('category', 'new_box', 'select_name');

        $this->assertTrue($delete_acpt_meta_field);
    }

    /**
     * @test
     */
    public function can_edit_acpt_meta_field()
    {
        $edit_meta_field = edit_acpt_tax_meta_field(
            [
                'taxonomy' => 'category',
                'box_name' => 'new_box',
                'old_field_name' => 'field_name',
                'field_name' => 'field_name modified',
                'field_type' => TaxonomyMetaBoxFieldModel::SELECT_TYPE,
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

        $field_object = get_acpt_tax_field_object('category', 'new_box', 'field_name modified');

        $this->assertCount(3, $field_object->options);
        $this->assertEquals('field_name modified', $field_object->field_name);
        $this->assertEquals(TaxonomyMetaBoxFieldModel::SELECT_TYPE, $field_object->field_type);
    }

    /**
     * @test
     */
    public function can_delete_meta_box()
    {
        $delete_acpt_meta_box = delete_acpt_tax_meta_box('category', 'box_name');
        $delete_acpt_meta_box_2 = delete_acpt_tax_meta_box('category', 'box_name_1');
        $delete_acpt_meta_box_3 = delete_acpt_tax_meta_box('category', 'box_name_2');
        $delete_acpt_meta_box_4 = delete_acpt_tax_meta_box('category', 'new_box');
        $delete_acpt_meta_box_5 = delete_acpt_tax_meta_box('category', 'other_meta_box');

        $this->assertTrue($delete_acpt_meta_box);
        $this->assertTrue($delete_acpt_meta_box_2);
        $this->assertTrue($delete_acpt_meta_box_3);
        $this->assertTrue($delete_acpt_meta_box_4);
        $this->assertTrue($delete_acpt_meta_box_5);

        $delete_acpt_meta_box = delete_acpt_tax_meta_box('post_tag', 'new_box modified');

        $this->assertTrue($delete_acpt_meta_box);
    }
}