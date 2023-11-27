<?php

namespace ACPT\Tests;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class CurrencyFieldValuesTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function can_add_acpt_meta_field_value()
    {
        add_acpt_meta_field(
                [
                        'post_type' => 'page',
                        'box_name' => 'box_name',
                        'field_name' => 'Currency field',
                        'field_type' => CustomPostTypeMetaBoxFieldModel::CURRENCY_TYPE,
                        'show_in_archive' => false,
                        'required' => false,
                ]
        );

        $add_acpt_meta_field_value = add_acpt_meta_field_value([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'Currency field',
                'value' => [
                        'amount' => 32,
                        'unit' => 'EUR'
                ],
        ]);

        $this->assertTrue($add_acpt_meta_field_value);

        $acpt_field = get_acpt_field([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'Currency field',
        ]);

        $this->assertEquals([
                'amount' => 32,
                'unit' => 'EUR'
        ], $acpt_field);
    }

    /**
     * @depends can_add_acpt_meta_field_value
     * @test
     */
    public function can_edit_acpt_meta_field_value()
    {
        $edit_acpt_meta_field_value = edit_acpt_meta_field_value([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'Currency field',
                'value' => [
                        'amount' => 456,
                        'unit' => 'USD'
                ],
        ]);

        $this->assertTrue($edit_acpt_meta_field_value);

        $acpt_field = get_acpt_field([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'Currency field',
        ]);

        $this->assertEquals([
                'amount' => 456,
                'unit' => 'USD'
        ], $acpt_field);
    }

    /**
     * @depends can_edit_acpt_meta_field_value
     * @test
     */
    public function can_display_acpt_meta_field()
    {
        $acpt_field = acpt_field([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'Currency field',
        ]);

        $this->assertEquals('<span class="amount">456<span class="currency">$</span></span>', $acpt_field);
    }

    /**
     * @depends can_edit_acpt_meta_field_value
     * @test
     */
    public function can_delete_acpt_meta_field_value()
    {
        $delete_acpt_meta_field_value = delete_acpt_meta_field_value([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'Currency field',
        ]);

        $this->assertTrue($delete_acpt_meta_field_value);

        $delete_acpt_meta_box = delete_acpt_meta_box('page', 'box_name');

        $this->assertTrue($delete_acpt_meta_box);

        $acpt_field = get_acpt_field([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'Currency field',
        ]);

        $this->assertNull($acpt_field);
    }
}