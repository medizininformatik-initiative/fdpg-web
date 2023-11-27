<?php

namespace ACPT\Tests;

use ACPT\Core\Models\User\UserMetaBoxFieldModel;
use ACPT\Utils\Wordpress\WPAttachment;

class FileUserFieldValuesTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function can_add_acpt_user_meta_field_value()
    {
        add_acpt_user_meta_field(
                [
                        'box_name' => 'box_name',
                        'field_name' => 'File',
                        'field_type' => UserMetaBoxFieldModel::FILE_TYPE,
                        'show_in_archive' => false,
                        'required' => false,
                ]
        );

        // not existent file
        $add_acpt_meta_field_value = add_acpt_user_meta_field_value([
                'box_name' => 'box_name',
                'field_name' => 'File',
                'value' => [
	                'label' => 'label',
	                'url' => "http://xxxxx.com/not-existing.txt"
                ],
        ]);

        $this->assertFalse($add_acpt_meta_field_value);

        // not a generic File
	    $image = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/image1.jpg');
	    $imageUrl = $image['url'];

        $add_acpt_meta_field_value = add_acpt_user_meta_field_value([
                'box_name' => 'box_name',
                'field_name' => 'File',
                'value' => [
	                'label' => 'label',
	                'url' => $imageUrl
                ],
        ]);

        $this->assertFalse($add_acpt_meta_field_value);

	    $this->deleteFile($imageUrl);

        $pdf = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/dummy.pdf');
        $url = $pdf['url'];

        $add_acpt_meta_field_value = add_acpt_user_meta_field_value([
                'box_name' => 'box_name',
                'field_name' => 'File',
                'value' => [
	                'label' => 'label',
	                'url' => $url
                ],
        ]);

        $this->assertTrue($add_acpt_meta_field_value);

        $acpt_field = get_acpt_user_field([
                'box_name' => 'box_name',
                'field_name' => 'File',
        ]);

        /** @var WPAttachment $file */
        $file = $acpt_field['file'];

	    $this->assertNotEmpty(get_user_meta(1, 'box_name_file_id', true));
        $this->assertNotNull($file);
        $this->assertEquals('label', $acpt_field['label']);

        $this->deleteFile($url);
    }

    /**
     * @depends can_add_acpt_user_meta_field_value
     * @test
     */
    public function can_edit_acpt_user_meta_field_value()
    {
        $txt = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/github.txt');
        $url = $txt['url'];

        $edit_acpt_meta_field_value = edit_acpt_user_meta_field_value([
                'box_name' => 'box_name',
                'field_name' => 'File',
                'value' => [
	                'label' => 'label',
	                'url' => $url
                ],
        ]);

        $this->assertTrue($edit_acpt_meta_field_value);

        $acpt_field = get_acpt_user_field([
                'box_name' => 'box_name',
                'field_name' => 'File',
        ]);

	    /** @var WPAttachment $file */
	    $file = $acpt_field['file'];

	    $this->assertNotNull($file);
	    $this->assertEquals('label', $acpt_field['label']);

        $this->deleteFile($url);
    }

    /**
     * @depends can_edit_acpt_user_meta_field_value
     * @test
     */
    public function can_delete_acpt_user_meta_field_value()
    {
        $delete_acpt_meta_field_value = delete_acpt_user_meta_field_value([
                'box_name' => 'box_name',
                'field_name' => 'File',
        ]);

        $this->assertTrue($delete_acpt_meta_field_value);

        $delete_acpt_meta_box = delete_acpt_user_meta_box('box_name');

        $this->assertTrue($delete_acpt_meta_box);

        $acpt_field = get_acpt_user_field([
                'box_name' => 'box_name',
                'field_name' => 'File',
        ]);

        $this->assertNull($acpt_field);
    }
}