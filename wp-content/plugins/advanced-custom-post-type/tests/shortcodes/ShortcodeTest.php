<?php

namespace ACPT\Tests;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class ShortcodeTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function can_display_a_list_with_before()
    {
        add_acpt_meta_field(
            [
                'post_type' => 'page',
                'box_name' => 'Box name',
                'field_name' => 'List field',
                'field_type' => CustomPostTypeMetaBoxFieldModel::LIST_TYPE,
                'show_in_archive' => false,
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

        add_acpt_meta_field_row_value([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'Box name',
                'field_name' => 'List field',
                'value' => "text text",
        ]);

        add_acpt_meta_field_row_value([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'Box name',
                'field_name' => 'List field',
                'value' => "bla bla",
        ]);

        $shortcode = do_shortcode('[acpt pid="'.$this->oldest_page_id.'" box="Box name" field="List field"]');

        $this->assertEquals($shortcode, "<ul><li><p>text text</p></li><li><p>bla bla</p></li></ul>");

        delete_acpt_meta_box('page', 'Box name');
    }

    /**
     * @test
     */
    public function can_display_a_select_multi_shortcode_with_before()
    {
        add_acpt_meta_field(
            [
                'post_type' => 'page',
                'box_name' => 'Box name',
                'field_name' => 'Select multi field',
                'field_type' => CustomPostTypeMetaBoxFieldModel::SELECT_MULTI_TYPE,
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
                ],
                'advanced_options' => [
                    [
                        'key' => 'before',
                        'value' => 'Element: ',
                    ],
                ]
            ]
        );

        add_acpt_meta_field_value([
            'post_id' => $this->oldest_page_id,
            'box_name' => 'Box name',
            'field_name' => 'Select multi field',
            'value' => [
                    "foo",
                    "fuzz",
            ],
        ]);

        $shortcode = do_shortcode('[acpt pid="'.$this->oldest_page_id.'" box="Box name" field="Select multi field"]');

        $this->assertEquals('Element: foo,Element: fuzz', $shortcode);

        delete_acpt_meta_box('page', 'Box name');
    }

	/**
	 * @test
	 */
	public function can_display_a_checkbox_shortcode_with_before()
	{
		add_acpt_meta_field(
			[
				'post_type' => 'page',
				'box_name' => 'Box name',
				'field_name' => 'Checkbox field',
				'field_type' => CustomPostTypeMetaBoxFieldModel::CHECKBOX_TYPE,
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
				],
				'advanced_options' => [
					[
						'key' => 'before',
						'value' => 'Element: ',
					],
				]
			]
		);

		add_acpt_meta_field_value([
			'post_id' => $this->oldest_page_id,
			'box_name' => 'Box name',
			'field_name' => 'Checkbox field',
			'value' => [
				"foo",
				"fuzz",
			],
		]);

		$shortcode = do_shortcode('[acpt pid="'.$this->oldest_page_id.'" box="Box name" field="Checkbox field"]');

		$this->assertEquals('Element: foo,Element: fuzz', $shortcode);

		delete_acpt_meta_box('page', 'Box name');
	}

    /**
     * @test
     */
    public function can_display_an_email_shortcode_with_before()
    {
        add_acpt_meta_field(
            [
                'post_type' => 'page',
                'box_name' => 'Box name',
                'field_name' => 'Field name',
                'field_type' => CustomPostTypeMetaBoxFieldModel::EMAIL_TYPE,
                'show_in_archive' => false,
                'required' => false,
                'advanced_options' => [
                    [
                        'key' => 'before',
                        'value' => 'Send email to: ',
                    ],
                ]
            ]
        );

        add_acpt_meta_field_value([
            'post_id' => $this->oldest_page_id,
            'box_name' => 'Box name',
            'field_name' => 'Field name',
            'value' => "info@acpt.io",
        ]);

        $shortcode = do_shortcode('[acpt pid="'.$this->oldest_page_id.'" box="Box name" field="Field name"]');

        $this->assertEquals('Send email to: <a href="mailto:info@acpt.io">info@acpt.io</a>', $shortcode);

        delete_acpt_meta_box('page', 'Box name');
    }

    /**
     * @test
     */
    public function can_display_a_text_shortcode_with_before_and_after()
    {
        add_acpt_meta_field(
            [
                'post_type' => 'page',
                'box_name' => 'Box name',
                'field_name' => 'Field name',
                'field_type' => CustomPostTypeMetaBoxFieldModel::TEXT_TYPE,
                'show_in_archive' => false,
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

        add_acpt_meta_field_value([
            'post_id' => $this->oldest_page_id,
            'box_name' => 'Box name',
            'field_name' => 'Field name',
            'value' => "text text",
        ]);

        $shortcode = do_shortcode('[acpt pid="'.$this->oldest_page_id.'" box="Box name" field="Field name"]');

        $this->assertEquals('<p>text text</p>', $shortcode);

        delete_acpt_meta_box('page', 'Box name');
    }
}