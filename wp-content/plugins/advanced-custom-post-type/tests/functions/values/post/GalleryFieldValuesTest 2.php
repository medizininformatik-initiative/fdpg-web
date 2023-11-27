<?php

namespace ACPT\Tests;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class GalleryFieldValuesTest extends AbstractTestCase
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
                'field_name' => 'Gallery',
                'field_type' => CustomPostTypeMetaBoxFieldModel::GALLERY_TYPE,
                'show_in_archive' => false,
                'required' => false,
            ]
        );

        // not existent file
        $add_acpt_meta_field_value = add_acpt_meta_field_value([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'Gallery',
                'value' => [
                    "http://fdsfdsfdsfdsfds.com/fdsfdsfds/not-existing.txt"
                ],
        ]);

        $this->assertFalse($add_acpt_meta_field_value);

        // not an Gallery
        $video = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/video1.mp4');
        $videoUrl = $video['url'];

        $add_acpt_meta_field_value = add_acpt_meta_field_value([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'Gallery',
                'value' => [
                    $videoUrl
                ],
        ]);

        $this->assertFalse($add_acpt_meta_field_value);

        // images
        $image1 = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/image1.jpg');
        $image1Url = $image1['url'];
        $image2 = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/image2.jpeg');
        $image2Url = $image2['url'];

        $add_acpt_meta_field_value = add_acpt_meta_field_value([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'Gallery',
                'value' => [
                    $image1Url,
                    $image2Url,
                ],
        ]);

        $this->assertTrue($add_acpt_meta_field_value);

        $acpt_field = get_acpt_field([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'Gallery',
        ]);

        $this->assertStringContainsString($acpt_field[0]->getMetadata()['file'], $image1Url);
        $this->assertStringContainsString($acpt_field[1]->getMetadata()['file'], $image2Url);

        $this->deleteFile($image1Url);
        $this->deleteFile($image2Url);
        $this->deleteFile($videoUrl);
    }

    /**
     * @depends can_add_acpt_meta_field_value
     * @test
     */
    public function can_edit_acpt_meta_field_value()
    {
        $image3 = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/image3.jpg');
        $image3Url = $image3['url'];
        $image4 = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/image4.jpeg');
        $image4Url = $image4['url'];
        $image5 = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/image5.jpg');
        $image5Url = $image5['url'];

        $edit_acpt_meta_field_value = edit_acpt_meta_field_value([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'Gallery',
                'value' => [
                    $image3Url,
                    $image4Url,
                    $image5Url,
                ],
        ]);

        $this->assertTrue($edit_acpt_meta_field_value);

        $acpt_field = get_acpt_field([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'Gallery',
        ]);

        $this->assertStringContainsString($acpt_field[0]->getMetadata()['file'], $image3Url);
        $this->assertStringContainsString($acpt_field[1]->getMetadata()['file'], $image4Url);
        $this->assertStringContainsString($acpt_field[2]->getMetadata()['file'], $image5Url);

        return [
            $image3Url,
            $image4Url,
            $image5Url,
        ];
    }

    /**
     * @depends can_edit_acpt_meta_field_value
     * @test
     */
    public function can_display_acpt_meta_field($urls)
    {
        $acpt_field = acpt_field([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'Gallery',
        ]);

        foreach ($urls as $url){
            $this->assertStringContainsString($url, $acpt_field);

            $this->deleteFile($url);
        }
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
                'field_name' => 'Gallery',
        ]);

        $this->assertTrue($delete_acpt_meta_field_value);

        $delete_acpt_meta_box = delete_acpt_meta_box('page', 'box_name');

        $this->assertTrue($delete_acpt_meta_box);

        $acpt_field = get_acpt_field([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'Gallery',
        ]);

        $this->assertNull($acpt_field);
    }
}