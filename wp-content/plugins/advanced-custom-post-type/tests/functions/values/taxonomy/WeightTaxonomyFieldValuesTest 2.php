<?php

namespace ACPT\Tests;

use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;

class WeightTaxonomyFieldValuesTest extends AbstractTestCase
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
                'field_name' => 'Weight field',
                'field_type' => TaxonomyMetaBoxFieldModel::WEIGHT_TYPE,

                'required' => false,
            ]
        );

        $add_acpt_tax_meta_field_value = add_acpt_tax_meta_field_value([
            'term_id' => $this->oldest_category_id,
            'box_name' => 'box_name',
            'field_name' => 'Weight field',
            'value' => [
                'weight' => 32,
                'unit' => 'GRAM'
            ],
        ]);

        $this->assertTrue($add_acpt_tax_meta_field_value);

        $acpt_tax_field = get_acpt_tax_field([
                'term_id' => $this->oldest_category_id,
                'box_name' => 'box_name',
                'field_name' => 'Weight field',
        ]);

        $this->assertEquals([
            'weight' => 32,
            'unit' => 'GRAM'
        ], $acpt_tax_field);
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
                'field_name' => 'Weight field',
                'value' => [
                    'weight' => 1332,
                    'unit' => 'MILLIGRAM'
                ],
        ]);

        $this->assertTrue($edit_acpt_tax_meta_field_value);

        $acpt_tax_field = get_acpt_tax_field([
                'term_id' => $this->oldest_category_id,
                'box_name' => 'box_name',
                'field_name' => 'Weight field',
        ]);

        $this->assertEquals([
                'weight' => 1332,
                'unit' => 'MILLIGRAM'
        ], $acpt_tax_field);
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
                'field_name' => 'Weight field',
        ]);

        $this->assertEquals('<span class="amount">1332<span class="currency">mg</span></span>', $acpt_tax_field);
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
                'field_name' => 'Weight field',
        ]);

        $this->assertTrue($delete_acpt_tax_meta_field_value);

        $delete_acpt_tax_meta_box = delete_acpt_tax_meta_box('category', 'box_name');

        $this->assertTrue($delete_acpt_tax_meta_box);

        $acpt_tax_field = get_acpt_tax_field([
                'term_id' => $this->oldest_category_id,
                'box_name' => 'box_name',
                'field_name' => 'Weight field',
        ]);

        $this->assertNull($acpt_tax_field);
    }
}