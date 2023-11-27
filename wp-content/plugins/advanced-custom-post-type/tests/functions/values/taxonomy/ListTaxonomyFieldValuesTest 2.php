<?php

namespace ACPT\Tests;

use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;

class ListTaxonomyFieldValuesTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function can_add_acpt_tax_meta_field_row_value()
    {
        $deleteFieldValue = delete_acpt_tax_meta_field_value([
            'term_id' => $this->oldest_category_id,
            'box_name' => 'box_name',
            'field_name' => 'List field',
        ]);

        $deleteMetaBox = delete_acpt_tax_meta_box('category', 'box_name');

        add_acpt_tax_meta_field(
            [
                'taxonomy' => 'category',
                'box_name' => 'box_name',
                'field_name' => 'List field',
                'field_type' => TaxonomyMetaBoxFieldModel::LIST_TYPE,

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

        $add_acpt_tax_meta_field_value = add_acpt_tax_meta_field_row_value([
            'term_id' => $this->oldest_category_id,
            'box_name' => 'box_name',
            'field_name' => 'List field',
            'value' => "text text",
        ]);

        $this->assertTrue($add_acpt_tax_meta_field_value);

        $add_acpt_tax_meta_field_value = add_acpt_tax_meta_field_row_value([
                'term_id' => $this->oldest_category_id,
                'box_name' => 'box_name',
                'field_name' => 'List field',
                'value' => "bla bla",
        ]);

        $this->assertTrue($add_acpt_tax_meta_field_value);

        $acpt_tax_field = get_acpt_tax_field([
            'term_id' => $this->oldest_category_id,
            'box_name' => 'box_name',
            'field_name' => 'List field',
        ]);

        $this->assertEquals($acpt_tax_field[0], '<p>text text</p>');
        $this->assertEquals($acpt_tax_field[1], '<p>bla bla</p>');

        $has_rows = acpt_tax_field_has_rows([
            'term_id' => $this->oldest_category_id,
            'box_name' => 'box_name',
            'field_name' => 'List field',
        ]);

        $this->assertTrue($has_rows);
    }

    /**
     * @depends can_add_acpt_tax_meta_field_row_value
     * @test
     */
    public function can_edit_acpt_tax_meta_field_row_value()
    {
        $edit_acpt_tax_meta_field_value = edit_acpt_tax_meta_field_row_value([
                'term_id' => $this->oldest_category_id,
                'box_name' => 'box_name',
                'field_name' => 'List field',
                'value' => "other value",
                'index' => 0
        ]);

        $this->assertTrue($edit_acpt_tax_meta_field_value);

        $acpt_tax_field = get_acpt_tax_field([
                'term_id' => $this->oldest_category_id,
                'box_name' => 'box_name',
                'field_name' => 'List field',
        ]);

        $this->assertEquals($acpt_tax_field[0], '<p>other value</p>');
        $this->assertEquals($acpt_tax_field[1], '<p>bla bla</p>');
    }

    /**
     * @depends can_edit_acpt_tax_meta_field_row_value
     * @test
     */
    public function can_display_acpt_tax_meta_field()
    {
        $acpt_tax_field = acpt_tax_field([
                'term_id' => $this->oldest_category_id,
                'box_name' => 'box_name',
                'field_name' => 'List field',
        ]);

        $this->assertStringContainsString('other value', $acpt_tax_field);
        $this->assertStringContainsString('bla bla', $acpt_tax_field);
    }

    /**
     * @depends can_edit_acpt_tax_meta_field_row_value
     * @test
     */
    public function can_delete_acpt_tax_meta_field_row_value()
    {
        $delete_acpt_tax_meta_field_value = delete_acpt_tax_meta_field_row_value([
            'term_id' => $this->oldest_category_id,
            'box_name' => 'box_name',
            'field_name' => 'List field',
            'index' => 0
        ]);

        $this->assertTrue($delete_acpt_tax_meta_field_value);

        $acpt_tax_field = get_acpt_tax_field([
                'term_id' => $this->oldest_category_id,
                'box_name' => 'box_name',
                'field_name' => 'List field',
        ]);

        $this->assertEquals("<p>bla bla</p>", $acpt_tax_field[0]);

        $delete_acpt_tax_meta_field_value = delete_acpt_tax_meta_field_value([
            'term_id' => $this->oldest_category_id,
            'box_name' => 'box_name',
            'field_name' => 'List field',
        ]);

        $this->assertTrue($delete_acpt_tax_meta_field_value);

        $delete_acpt_tax_meta_box = delete_acpt_tax_meta_box('category', 'box_name');

        $this->assertTrue($delete_acpt_tax_meta_box);

        $acpt_tax_field = get_acpt_tax_field([
                'term_id' => $this->oldest_category_id,
                'box_name' => 'box_name',
                'field_name' => 'List field',
        ]);

        $this->assertNull($acpt_tax_field);
    }
}