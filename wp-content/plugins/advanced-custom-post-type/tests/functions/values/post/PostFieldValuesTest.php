<?php

namespace ACPT\Tests;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class PostFieldValuesTest extends AbstractTestCase
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
                'field_name' => 'field_name',
                'field_type' => CustomPostTypeMetaBoxFieldModel::TEXT_TYPE,
                'show_in_archive' => false,
                'required' => false,
            ]
        );

        add_acpt_meta_field(
            [
                'post_type' => 'post',
                'box_name' => 'box_name',
                'field_name' => 'post_relation',
                'field_type' => CustomPostTypeMetaBoxFieldModel::POST_TYPE,
                'show_in_archive' => false,
                'required' => false,
                'relations' => [
                    [
                        'related_to' => [
                            'post_type' => 'page',
                            'box_name' => 'box_name',
                            'field_name' => 'field_name',
                        ],
                        'relation' => 'one_to_one_bi',
                    ],
                ]
            ]
        );

        $add_acpt_meta_field_value = add_acpt_meta_field_value([
            'post_id' => $this->oldest_post_id,
            'box_name' => 'box_name',
            'field_name' => 'post_relation',
            'value' => $this->oldest_page_id,
        ]);

        $this->assertTrue($add_acpt_meta_field_value);

        $acpt_field = get_acpt_field([
                'post_id' => $this->oldest_post_id,
                'box_name' => 'box_name',
                'field_name' => 'post_relation',
        ]);

        $inversed_acpt_field = get_acpt_field([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'field_name',
        ]);

        /** @var \WP_Post $a */
        $a = $acpt_field[0];

        /** @var \WP_Post $b */
	    $b = $inversed_acpt_field[0];

        $this->assertEquals($this->oldest_page_id, $a->ID);
        $this->assertEquals($this->oldest_post_id, $b->ID);
    }

    /**
     * @depends can_add_acpt_meta_field_value
     * @test
     */
    public function can_edit_acpt_meta_field_value()
    {
        $edit_acpt_meta_field_value = edit_acpt_meta_field_value([
            'post_id' => $this->oldest_post_id,
            'box_name' => 'box_name',
            'field_name' => 'post_relation',
            'value' => $this->second_oldest_page_id,
        ]);

        $this->assertTrue($edit_acpt_meta_field_value);

        $acpt_field = get_acpt_field([
                'post_id' => $this->oldest_post_id,
                'box_name' => 'box_name',
                'field_name' => 'post_relation',
        ]);

	    /** @var \WP_Post $a */
	    $a = $acpt_field[0];

        $this->assertEquals($this->second_oldest_page_id, $a->ID);
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
                'field_name' => 'field_name',
        ]);

        $this->assertTrue($delete_acpt_meta_field_value);

        $delete_acpt_meta_field_value = delete_acpt_meta_field_value([
                'post_id' => $this->oldest_post_id,
                'box_name' => 'box_name',
                'field_name' => 'post_relation',
        ]);

        $this->assertTrue($delete_acpt_meta_field_value);

        $delete_acpt_meta_box = delete_acpt_meta_box('page', 'box_name');

        $this->assertTrue($delete_acpt_meta_box);

        $delete_acpt_meta_box = delete_acpt_meta_box('post', 'box_name');

        $this->assertTrue($delete_acpt_meta_box);

        $acpt_field = get_acpt_field([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'field_name',
        ]);

        $this->assertNull($acpt_field);
    }
}