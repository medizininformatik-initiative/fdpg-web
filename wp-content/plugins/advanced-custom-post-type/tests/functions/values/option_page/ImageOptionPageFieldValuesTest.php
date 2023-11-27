<?php

namespace ACPT\Tests;

use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;

class ImageOptionPageFieldValuesTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function can_add_acpt_option_page_meta_field_value()
    {
	    $new_page = register_acpt_option_page([
		    'menu_slug' => 'new-page',
		    'page_title' => 'New page',
		    'menu_title' => 'New page menu title',
		    'icon' => 'admin-appearance',
		    'capability' => 'manage_options',
		    'description' => 'lorem ipsum',
		    'position' => 77,
	    ]);

	    $this->assertTrue($new_page);

        add_acpt_option_page_meta_field(
            [
	            'option_page' => 'new-page',
                'box_name' => 'box_name',
                'field_name' => 'Image',
                'field_type' => OptionPageMetaBoxFieldModel::IMAGE_TYPE,

                'required' => false,
            ]
        );

        // not existent file
        $add_acpt_option_page_meta_field_value = add_acpt_option_page_meta_field_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Image',
            'value' => "http://xxxcfdsafds.com/not-existing.txt",
        ]);

        $this->assertFalse($add_acpt_option_page_meta_field_value);

        // not an image
        $video = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/video1.mp4');
        $videoUrl = $video['url'];

        $add_acpt_option_page_meta_field_value = add_acpt_option_page_meta_field_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Image',
            'value' => $videoUrl,
        ]);

        $this->assertFalse($add_acpt_option_page_meta_field_value);

        // image
        $image = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/image1.jpg');
        $imageUrl = $image['url'];

        $add_acpt_option_page_meta_field_value = add_acpt_option_page_meta_field_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Image',
            'value' => $imageUrl,
        ]);

        $this->assertTrue($add_acpt_option_page_meta_field_value);

        $acpt_option_page_field = get_acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Image',
        ]);

	    $this->assertNotEmpty(get_option('box_name_image_id'));
        $this->assertStringContainsString($acpt_option_page_field->getMetadata()['file'], $imageUrl);

        $this->deleteFile($imageUrl);
        $this->deleteFile($videoUrl);
    }

    /**
     * @depends can_add_acpt_option_page_meta_field_value
     * @test
     */
    public function can_edit_acpt_option_page_meta_field_value()
    {
        $image = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/image2.jpeg');
        $imageUrl = $image['url'];

        $edit_acpt_option_page_meta_field_value = edit_acpt_option_page_meta_field_value([
	        'option_page' => 'new-page',
                'box_name' => 'box_name',
                'field_name' => 'Image',
                'value' => $imageUrl,
        ]);

        $this->assertTrue($edit_acpt_option_page_meta_field_value);

        $acpt_option_page_field = get_acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Image',
        ]);

        $this->assertStringContainsString($acpt_option_page_field->getMetadata()['file'], $imageUrl);

        return $imageUrl;
    }

    /**
     * @depends can_edit_acpt_option_page_meta_field_value
     * @test
     */
    public function can_display_acpt_option_page_meta_field($url)
    {
        $acpt_option_page_field = acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Image',
        ]);

        $this->assertStringContainsString($url, $acpt_option_page_field);

        $this->deleteFile($url);
    }

    /**
     * @depends can_edit_acpt_option_page_meta_field_value
     * @test
     */
    public function can_delete_acpt_option_page_meta_field_value()
    {
        $delete_acpt_option_page_meta_field_value = delete_acpt_option_page_meta_field_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Image',
        ]);

        $this->assertTrue($delete_acpt_option_page_meta_field_value);

        $delete_acpt_option_page_meta_box = delete_acpt_option_page_meta_box('new-page', 'box_name');

        $this->assertTrue($delete_acpt_option_page_meta_box);

        $acpt_option_page_field = get_acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Image',
        ]);

        $this->assertNull($acpt_option_page_field);

	    $this->assertTrue(delete_acpt_option_page('new-page', true));
    }
}

