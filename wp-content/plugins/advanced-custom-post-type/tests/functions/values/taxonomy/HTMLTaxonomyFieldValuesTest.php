<?php

namespace ACPT\Tests;

use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;

class HTMLTaxonomyFieldValuesTest extends AbstractTestCase
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
                'field_name' => 'HTML field',
                'field_type' => TaxonomyMetaBoxFieldModel::HTML_TYPE,

                'required' => false,
            ]
        );

        $add_acpt_tax_meta_field_value = add_acpt_tax_meta_field_value([
            'term_id' => $this->oldest_category_id,
            'box_name' => 'box_name',
            'field_name' => 'HTML field',
            'value' => "<p>Standard p tag</p>",
        ]);

        $this->assertTrue($add_acpt_tax_meta_field_value);

        $acpt_tax_field = get_acpt_tax_field([
            'term_id' => $this->oldest_category_id,
            'box_name' => 'box_name',
            'field_name' => 'HTML field',
        ]);

        $this->assertEquals('<p>Standard p tag</p>', $acpt_tax_field);
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
            'field_name' => 'HTML field',
            'value' => "<h1>Standard h1 tag</h1>",
        ]);

        $this->assertTrue($edit_acpt_tax_meta_field_value);

        $acpt_tax_field = get_acpt_tax_field([
            'term_id' => $this->oldest_category_id,
            'box_name' => 'box_name',
            'field_name' => 'HTML field',
        ]);

        $this->assertEquals('<h1>Standard h1 tag</h1>', $acpt_tax_field);
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
            'field_name' => 'HTML field',
        ]);

        $this->assertEquals('<h1>Standard h1 tag</h1>', $acpt_tax_field);
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
            'field_name' => 'HTML field',
        ]);

        $this->assertTrue($delete_acpt_tax_meta_field_value);

        $delete_acpt_tax_meta_box = delete_acpt_tax_meta_box('category', 'box_name');

        $this->assertTrue($delete_acpt_tax_meta_box);

        $acpt_tax_field = get_acpt_tax_field([
            'term_id' => $this->oldest_category_id,
            'box_name' => 'box_name',
            'field_name' => 'HTML field',
        ]);

        $this->assertNull($acpt_tax_field);
    }
}