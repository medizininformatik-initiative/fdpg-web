<?php

namespace ACPT\Tests;

use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;
use ACPT\Utils\Wordpress\WPAttachment;

class FileOptionPageFieldValuesTest extends AbstractTestCase
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
                'field_name' => 'File',
                'field_type' => OptionPageMetaBoxFieldModel::FILE_TYPE,
                'required' => false,
            ]
        );

        // not existent file
        $add_acpt_option_page_meta_field_value = add_acpt_option_page_meta_field_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'File',
            'value' => [
                'label' => 'label',
                'url' => "http:/xxxxxx.com/uploads/not-existing.txt",
            ],
        ]);

        $this->assertFalse($add_acpt_option_page_meta_field_value);

	    // not a generic File
	    $image = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/image1.jpg');
	    $imageUrl = $image['url'];

        $add_acpt_option_page_meta_field_value = add_acpt_option_page_meta_field_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'File',
            'value' => [
                'label' => 'label',
                'url' => $imageUrl
            ],
        ]);

        $this->assertFalse($add_acpt_option_page_meta_field_value);

	    $this->deleteFile($imageUrl);

        $pdf = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/dummy.pdf');
        $url = $pdf['url'];

        $add_acpt_option_page_meta_field_value = add_acpt_option_page_meta_field_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'File',
            'value' => [
                'label' => 'label',
                'url' =>  $url
            ],
        ]);

        $this->assertTrue($add_acpt_option_page_meta_field_value);

        $acpt_option_page_field = get_acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'File',
        ]);

        /** @var WPAttachment $file */
        $file = $acpt_option_page_field['file'];

	    $this->assertNotEmpty(get_option('box_name_file_id'));
        $this->assertNotNull($file);
        $this->assertEquals('label', $acpt_option_page_field['label']);

        $this->deleteFile($url);
    }

    /**
     * @depends can_add_acpt_option_page_meta_field_value
     * @test
     */
    public function can_edit_acpt_option_page_meta_field_value()
    {
        $txt = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/github.txt');
        $url = $txt['url'];

        $edit_acpt_option_page_meta_field_value = edit_acpt_option_page_meta_field_value([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'File',
            'value' => [
                'label' => 'label',
                'url' => $url
            ],
        ]);

        $this->assertTrue($edit_acpt_option_page_meta_field_value);

        $acpt_option_page_field = get_acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'File',
        ]);

	    /** @var WPAttachment $file */
	    $file = $acpt_option_page_field['file'];

	    $this->assertNotNull($file);
	    $this->assertEquals('label', $acpt_option_page_field['label']);

        return $url;
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
            'field_name' => 'File',
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
            'field_name' => 'File',
        ]);

        $this->assertTrue($delete_acpt_option_page_meta_field_value);

        $delete_acpt_option_page_meta_box = delete_acpt_option_page_meta_box('new-page', 'box_name');

        $this->assertTrue($delete_acpt_option_page_meta_box);

        $acpt_option_page_field = get_acpt_option_page_field([
	        'option_page' => 'new-page',
            'box_name' => 'box_name',
            'field_name' => 'File',
        ]);

        $this->assertNull($acpt_option_page_field);

	    $this->assertTrue(delete_acpt_option_page('new-page', true));
    }
}