<?php

namespace ACPT\Tests;

use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;

class DateTaxonomyFieldValuesTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function can_add_acpt_tax_meta_field_value()
    {
        add_acpt_tax_meta_field(
            [
                'taxonomy' => 'category',
                'box_name' => 'box_name',
                'field_name' => 'Date field',
                'field_type' => TaxonomyMetaBoxFieldModel::DATE_TYPE,

                'required' => false,
            ]
        );

        $add_acpt_tax_meta_field_value = add_acpt_tax_meta_field_value([
            'term_id' => $this->oldest_category_id,
            'box_name' => 'box_name',
            'field_name' => 'Date field',
            'value' => "2022-06-30",
        ]);

        $this->assertTrue($add_acpt_tax_meta_field_value);

        $acpt_tax_field = get_acpt_tax_field([
            'term_id' => $this->oldest_category_id,
            'box_name' => 'box_name',
            'field_name' => 'Date field',
        ]);

        $this->assertEquals('2022-06-30', $acpt_tax_field);
    }

    /**
     * @depends can_add_acpt_tax_meta_field_value
     * @test
     */
    public function can_edit_acpt_tax_meta_field_value()
    {
        $edit_acpt_tax_meta_field_value = edit_acpt_tax_meta_field_value([
            'term_id' => $this->oldest_category_id,
            'box_name' => 'box_name',
            'field_name' => 'Date field',
            'value' => "2020-01-31",
        ]);

        $this->assertTrue($edit_acpt_tax_meta_field_value);

        $acpt_tax_field = get_acpt_tax_field([
            'term_id' => $this->oldest_category_id,
            'box_name' => 'box_name',
            'field_name' => 'Date field',
        ]);

        $this->assertEquals('2020-01-31', $acpt_tax_field);
    }

    /**
     * @depends can_edit_acpt_tax_meta_field_value
     * @test
     */
    public function can_display_acpt_tax_meta_field()
    {
        $acpt_tax_field = acpt_tax_field([
            'term_id' => $this->oldest_category_id,
            'box_name' => 'box_name',
            'field_name' => 'Date field',
        ]);

        $this->assertEquals('31/01/2020', $acpt_tax_field);

        $acpt_tax_field = acpt_tax_field([
            'term_id' => $this->oldest_category_id,
            'box_name' => 'box_name',
            'field_name' => 'Date field',
            'date_format' => 'Y-m-d'
        ]);

        $this->assertEquals('2020-01-31', $acpt_tax_field);
    }

    /**
     * @depends can_edit_acpt_tax_meta_field_value
     * @test
     */
    public function can_delete_acpt_tax_meta_field_value()
    {
        $delete_acpt_tax_meta_field_value = delete_acpt_tax_meta_field_value([
            'term_id' => $this->oldest_category_id,
            'box_name' => 'box_name',
            'field_name' => 'Date field',
        ]);

        $this->assertTrue($delete_acpt_tax_meta_field_value);

        $delete_acpt_tax_meta_box = delete_acpt_tax_meta_box('category', 'box_name');

        $this->assertTrue($delete_acpt_tax_meta_box);

        $acpt_tax_field = get_acpt_tax_field([
            'term_id' => $this->oldest_category_id,
            'box_name' => 'box_name',
            'field_name' => 'Date field',
        ]);

        $this->assertNull($acpt_tax_field);
    }
}