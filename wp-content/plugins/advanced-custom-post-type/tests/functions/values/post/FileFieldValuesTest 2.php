<?php

namespace ACPT\Tests;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class FileFieldValuesTest extends AbstractTestCase
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
                'field_name' => 'File',
                'field_type' => CustomPostTypeMetaBoxFieldModel::FILE_TYPE,
                'show_in_archive' => false,
                'required' => false,
            ]
        );

        // not existent file
        $add_acpt_meta_field_value = add_acpt_meta_field_value([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'File',
                'value' => [
                	'label' => 'This is a label',
                	'url' => "http://gfdgfdgdfgfdgfd.com/not-existing.txt",
                ],
        ]);

        $this->assertFalse($add_acpt_meta_field_value);

	    // not a generic File
	    $image = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/image1.jpg');
	    $imageUrl = $image['url'];

        $add_acpt_meta_field_value = add_acpt_meta_field_value([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'File',
                'value' => [
	                'label' => 'This is a label',
	                'url' => $imageUrl,
                ],
        ]);

        $this->assertFalse($add_acpt_meta_field_value);

	    $this->deleteFile($imageUrl);

        $pdf = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/dummy.pdf');
        $url = $pdf['url'];

        $add_acpt_meta_field_value = add_acpt_meta_field_value([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'File',
                'value' => [
	                'label' => 'This is a label',
	                'url' => $url
                ],
        ]);

        $this->assertTrue($add_acpt_meta_field_value);

        $acpt_field = get_acpt_field([
            'post_id' => $this->oldest_page_id,
            'box_name' => 'box_name',
            'field_name' => 'File',
        ]);

        $file = $acpt_field['file'];

        $this->assertNotNull($file);
        $this->assertEquals('This is a label', $acpt_field['label']);

        $this->deleteFile($url);
    }

    /**
     * @depends can_add_acpt_meta_field_value
     * @test
     */
    public function can_edit_acpt_meta_field_value()
    {
        $txt = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/github.txt');
        $url = $txt['url'];

        $edit_acpt_meta_field_value = edit_acpt_meta_field_value([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'File',
                'value' => [
	                'label' => 'This is a label',
	                'url' => $url
                ],
        ]);

        $this->assertTrue($edit_acpt_meta_field_value);

        $acpt_field = get_acpt_field([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'File',
        ]);

	    $file = $acpt_field['file'];

	    $this->assertNotNull($file);
	    $this->assertEquals('This is a label', $acpt_field['label']);

        return $url;
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
                'field_name' => 'File',
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
                'field_name' => 'File',
        ]);

        $this->assertTrue($delete_acpt_meta_field_value);

        $delete_acpt_meta_box = delete_acpt_meta_box('page', 'box_name');

        $this->assertTrue($delete_acpt_meta_box);

        $acpt_field = get_acpt_field([
                'post_id' => $this->oldest_page_id,
                'box_name' => 'box_name',
                'field_name' => 'File',
        ]);

        $this->assertNull($acpt_field);
    }
}