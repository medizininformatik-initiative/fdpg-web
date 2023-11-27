<?php

namespace ACPT\Tests;

use ACPT\Core\Models\User\UserMetaBoxFieldModel;

class ImageUserFieldValuesTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function can_add_acpt_user_meta_field_value()
    {
        add_acpt_user_meta_field(
            [
                'box_name' => 'box_name',
                'field_name' => 'Image',
                'field_type' => UserMetaBoxFieldModel::IMAGE_TYPE,
                'show_in_archive' => false,
                'required' => false,
            ]
        );

        // not existent file
        $add_acpt_meta_field_value = add_acpt_user_meta_field_value([
        	'user_id' => 2,
            'box_name' => 'box_name',
            'field_name' => 'Image',
            'value' => "http://localhost:83/wp-content/uploads/not-existing.txt",
        ]);

        $this->assertFalse($add_acpt_meta_field_value);

        // not an image
        $video = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/video1.mp4');
        $videoUrl = $video['url'];

        $add_acpt_meta_field_value = add_acpt_user_meta_field_value([
	            'user_id' => 2,
                'box_name' => 'box_name',
                'field_name' => 'Image',
                'value' => $videoUrl,
        ]);

        $this->assertFalse($add_acpt_meta_field_value);

        // image
        $image = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/image1.jpg');
        $imageUrl = $image['url'];

        $add_acpt_meta_field_value = add_acpt_user_meta_field_value([
	        'user_id' => 2,
            'box_name' => 'box_name',
            'field_name' => 'Image',
            'value' => $imageUrl,
        ]);

        $this->assertTrue($add_acpt_meta_field_value);

        $acpt_field = get_acpt_user_field([
	        'user_id' => 2,
            'box_name' => 'box_name',
            'field_name' => 'Image',
        ]);

        $this->assertNotEmpty(get_user_meta(2, 'box_name_image_id', true));
        $this->assertStringContainsString($acpt_field->getMetadata()['file'], $imageUrl);

        $this->deleteFile($imageUrl);
        $this->deleteFile($videoUrl);
    }

    /**
     * @depends can_add_acpt_user_meta_field_value
     * @test
     */
    public function can_edit_acpt_user_meta_field_value()
    {
        $image = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/image2.jpeg');
        $imageUrl = $image['url'];

        $edit_acpt_meta_field_value = edit_acpt_user_meta_field_value([
	        'user_id' => 2,
            'box_name' => 'box_name',
            'field_name' => 'Image',
            'value' => $imageUrl,
        ]);

        $this->assertTrue($edit_acpt_meta_field_value);

        $acpt_field = get_acpt_user_field([
	        'user_id' => 2,
            'box_name' => 'box_name',
            'field_name' => 'Image',
        ]);

        $this->assertStringContainsString($acpt_field->getMetadata()['file'], $imageUrl);

        return $imageUrl;
    }

    /**
     * @depends can_edit_acpt_user_meta_field_value
     * @test
     */
    public function can_delete_acpt_user_meta_field_value($url)
    {
        $delete_acpt_meta_field_value = delete_acpt_user_meta_field_value([
	        'user_id' => 2,
                'box_name' => 'box_name',
                'field_name' => 'Image',
        ]);

        $this->assertTrue($delete_acpt_meta_field_value);

        $delete_acpt_meta_box = delete_acpt_user_meta_box('box_name');

        $this->assertTrue($delete_acpt_meta_box);

        $acpt_field = get_acpt_user_field([
	        'user_id' => 2,
                'box_name' => 'box_name',
                'field_name' => 'Image',
        ]);

        $this->assertNull($acpt_field);

        $this->deleteFile($url);
    }
}

