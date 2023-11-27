<?php

namespace ACPT\Tests;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class VideoFieldValuesTest extends AbstractTestCase
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
                'field_name' => 'Video',
                'field_type' => CustomPostTypeMetaBoxFieldModel::VIDEO_TYPE,
                'show_in_archive' => false,
                'required' => false,
            ]
        );

        // not existent file
        $add_acpt_meta_field_value = add_acpt_meta_field_value([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'Video',
                'value' => "http://localhost:83/wp-content/uploads/not-existing.txt",
        ]);

        $this->assertFalse($add_acpt_meta_field_value);

        // not a video
        $image = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/image1.jpg');
        $imageUrl = $image['url'];

        $add_acpt_meta_field_value = add_acpt_meta_field_value([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'Video',
                'value' => $imageUrl,
        ]);

        $this->assertFalse($add_acpt_meta_field_value);

        // video
        $video = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/video1.mp4');
        $videoUrl = $video['url'];

        $add_acpt_meta_field_value = add_acpt_meta_field_value([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'Video',
                'value' => $videoUrl,
        ]);

        $this->assertTrue($add_acpt_meta_field_value);

        $acpt_field = get_acpt_field([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'Video',
        ]);

	    $this->assertNotEmpty(get_post_meta($this->oldest_page_id, 'box_name_video_id', true));
        $this->assertNotNull($acpt_field->getSrc());

        $this->deleteFile($imageUrl);
        $this->deleteFile($videoUrl);
    }

    /**
     * @depends can_add_acpt_meta_field_value
     * @test
     */
    public function can_edit_acpt_meta_field_value()
    {
        $video = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/video2.mp4');
        $videoUrl = $video['url'];

        $edit_acpt_meta_field_value = edit_acpt_meta_field_value([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'Video',
                'value' => $videoUrl,
        ]);

        $this->assertTrue($edit_acpt_meta_field_value);

        $acpt_field = get_acpt_field([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'Video',
        ]);

        $this->assertStringContainsString($acpt_field->getSrc(), $videoUrl);

        return $videoUrl;
    }

    /**
     * @depends can_edit_acpt_meta_field_value
     * @test
     */
    public function can_display_acpt_meta_field($url)
    {
        $acpt_field = acpt_field([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'Video',
        ]);

        $this->assertStringContainsString($url, $acpt_field);

        $this->deleteFile($url);
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
                'field_name' => 'Video',
        ]);

        $this->assertTrue($delete_acpt_meta_field_value);

        $delete_acpt_meta_box = delete_acpt_meta_box('page', 'box_name');

        $this->assertTrue($delete_acpt_meta_box);

        $acpt_field = get_acpt_field([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'Video',
        ]);

        $this->assertNull($acpt_field);
    }
}