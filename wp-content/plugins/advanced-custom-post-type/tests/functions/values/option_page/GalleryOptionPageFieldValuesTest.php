<?php

namespace ACPT\Tests;

use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;

class GalleryOptionPageFieldValuesTest extends AbstractTestCase
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
                'field_name' => 'Gallery',
                'field_type' => OptionPageMetaBoxFieldModel::GALLERY_TYPE,
                'required' => false,
            ]
        );

        // not existent file
        $add_acpt_option_page_meta_field_value = add_acpt_option_page_meta_field_value([
	        'option_page' => 'new-page',
                'box_name' => 'box_name',
                'field_name' => 'Gallery',
                'value' => [
                    "http://localhost:83/wp-content/uploads/not-existing.txt"
                ],
        ]);

        $this->assertFalse($add_acpt_option_page_meta_field_value);

        // not an Gallery
        $video = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/video1.mp4');
        $videoUrl = $video['url'];

        $add_acpt_option_page_meta_field_value = add_acpt_option_page_meta_field_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Gallery',
            'value' => [
                $videoUrl
            ],
        ]);

        $this->assertFalse($add_acpt_option_page_meta_field_value);

        // images
        $image1 = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/image1.jpg');
        $image1Url = $image1['url'];
        $image2 = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/image2.jpeg');
        $image2Url = $image2['url'];

        $add_acpt_option_page_meta_field_value = add_acpt_option_page_meta_field_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Gallery',
            'value' => [
                $image1Url,
                $image2Url,
            ],
        ]);

        $this->assertTrue($add_acpt_option_page_meta_field_value);

        $acpt_option_page_field = get_acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Gallery',
        ]);

        $this->assertNotEmpty(get_option('box_name_gallery_id'));
        $this->assertStringContainsString($acpt_option_page_field[0]->getMetadata()['file'], $image1Url);
        $this->assertStringContainsString($acpt_option_page_field[1]->getMetadata()['file'], $image2Url);

        $this->deleteFile($image1Url);
        $this->deleteFile($image2Url);
        $this->deleteFile($videoUrl);
    }

    /**
     * @depends can_add_acpt_option_page_meta_field_value
     * @test
     */
    public function can_edit_acpt_option_page_meta_field_value()
    {
        $image3 = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/image3.jpg');
        $image3Url = $image3['url'];
        $image4 = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/image4.jpeg');
        $image4Url = $image4['url'];
        $image5 = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/image5.jpg');
        $image5Url = $image5['url'];

        $edit_acpt_option_page_meta_field_value = edit_acpt_option_page_meta_field_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Gallery',
            'value' => [
                $image3Url,
                $image4Url,
                $image5Url,
            ],
        ]);

        $this->assertTrue($edit_acpt_option_page_meta_field_value);

        $acpt_option_page_field = get_acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Gallery',
        ]);

        $this->assertStringContainsString($acpt_option_page_field[0]->getMetadata()['file'], $image3Url);
        $this->assertStringContainsString($acpt_option_page_field[1]->getMetadata()['file'], $image4Url);
        $this->assertStringContainsString($acpt_option_page_field[2]->getMetadata()['file'], $image5Url);

        return [
            $image3Url,
            $image4Url,
            $image5Url,
        ];
    }

    /**
     * @depends can_edit_acpt_option_page_meta_field_value
     * @test
     */
    public function can_display_acpt_option_page_meta_field($urls)
    {
        $acpt_option_page_field = acpt_option_page_field([
	        'option_page' => 'new-page',
			'box_name' => 'box_name',
			'field_name' => 'Gallery',
        ]);

        foreach ($urls as $url){
            $this->assertStringContainsString($url, $acpt_option_page_field);

            $this->deleteFile($url);
        }
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
            'field_name' => 'Gallery',
        ]);

        $this->assertTrue($delete_acpt_option_page_meta_field_value);

        $delete_acpt_option_page_meta_box = delete_acpt_option_page_meta_box('new-page', 'box_name');

        $this->assertTrue($delete_acpt_option_page_meta_box);

        $acpt_option_page_field = get_acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'Gallery',
        ]);

        $this->assertNull($acpt_option_page_field);

	    $this->assertTrue(delete_acpt_option_page('new-page', true));
    }
}