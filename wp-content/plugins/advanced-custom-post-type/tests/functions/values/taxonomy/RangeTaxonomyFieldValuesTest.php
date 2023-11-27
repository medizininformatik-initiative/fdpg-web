<?php

namespace ACPT\Tests;

use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;

class RangeTaxonomyFieldValuesTest extends AbstractTestCase
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
                'field_name' => 'Range',
                'field_type' => TaxonomyMetaBoxFieldModel::RANGE_TYPE,
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

	    $add_acpt_tax_meta_field_value = add_acpt_tax_meta_field_value([
		    'term_id' => $this->oldest_category_id,
		    'box_name' => 'box_name',
		    'field_name' => 'Range',
		    'value' => 1123,
	    ]);

	    $this->assertFalse($add_acpt_tax_meta_field_value);

        $add_acpt_tax_meta_field_value = add_acpt_tax_meta_field_value([
            'term_id' => $this->oldest_category_id,
            'box_name' => 'box_name',
            'field_name' => 'Range',
            'value' => 123,
        ]);

        $this->assertTrue($add_acpt_tax_meta_field_value);

        $acpt_tax_field = get_acpt_tax_field([
            'term_id' => $this->oldest_category_id,
            'box_name' => 'box_name',
            'field_name' => 'Range',
        ]);

        $this->assertEquals(123, $acpt_tax_field);
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
            'field_name' => 'Range',
            'value' => 100,
        ]);

        $this->assertTrue($edit_acpt_tax_meta_field_value);

        $acpt_tax_field = get_acpt_tax_field([
            'term_id' => $this->oldest_category_id,
            'box_name' => 'box_name',
            'field_name' => 'Range',
        ]);

        $this->assertEquals(100, $acpt_tax_field);
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
            'field_name' => 'Range',
        ]);

        $this->assertEquals(100, $acpt_tax_field);
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
            'field_name' => 'Range',
        ]);

        $this->assertTrue($delete_acpt_tax_meta_field_value);

        $delete_acpt_tax_meta_box = delete_acpt_tax_meta_box('category', 'box_name');

        $this->assertTrue($delete_acpt_tax_meta_box);

        $acpt_tax_field = get_acpt_tax_field([
            'term_id' => $this->oldest_category_id,
            'box_name' => 'box_name',
            'field_name' => 'Range',
        ]);

        $this->assertNull($acpt_tax_field);
    }
}