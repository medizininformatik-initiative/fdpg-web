<?php

namespace ACPT\Tests;

use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;
use ACPT\Utils\Wordpress\WPAttachment;

class FileTaxonomyFieldValuesTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function can_add_acpt_tax_meta_field_value()
    {
        add_acpt_tax_meta_field(
                [
                        'taxonomy' => 'category',
                        'box_name' => 'box_name',
                        'field_name' => 'File',
                        'field_type' => TaxonomyMetaBoxFieldModel::FILE_TYPE,
                        'required' => false,
                ]
        );

        // not existent file
        $add_acpt_tax_meta_field_value = add_acpt_tax_meta_field_value([
                'term_id' => $this->oldest_category_id,
                'box_name' => 'box_name',
                'field_name' => 'File',
                'value' => [
                	'label' => 'label',
                	'url' => "http://xxxxxxx.com/uploads/not-existing.txt",
                ],
        ]);

        $this->assertFalse($add_acpt_tax_meta_field_value);

        // not a generic File
	    $image = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/image1.jpg');
	    $imageUrl = $image['url'];

        $add_acpt_tax_meta_field_value = add_acpt_tax_meta_field_value([
                'term_id' => $this->oldest_category_id,
                'box_name' => 'box_name',
                'field_name' => 'File',
                'value' => [
	                'label' => 'label',
	                'url' => $imageUrl
                ],
        ]);

        $this->assertFalse($add_acpt_tax_meta_field_value);

	    $this->deleteFile($imageUrl);

        $pdf = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/dummy.pdf');
        $url = $pdf['url'];

        $add_acpt_tax_meta_field_value = add_acpt_tax_meta_field_value([
                'term_id' => $this->oldest_category_id,
                'box_name' => 'box_name',
                'field_name' => 'File',
                'value' => [
	                'label' => 'label',
	                'url' =>  $url
                ],
        ]);

        $this->assertTrue($add_acpt_tax_meta_field_value);

        $acpt_tax_field = get_acpt_tax_field([
                'term_id' => $this->oldest_category_id,
                'box_name' => 'box_name',
                'field_name' => 'File',
        ]);

        /** @var WPAttachment $file */
        $file = $acpt_tax_field['file'];

        $this->assertNotNull($file);
	    $this->assertEquals('label', $acpt_tax_field['label']);

        $this->deleteFile($url);
    }

    /**
     * @depends can_add_acpt_tax_meta_field_value
     * @test
     */
    public function can_edit_acpt_tax_meta_field_value()
    {
        $txt = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/github.txt');
        $url = $txt['url'];

        $edit_acpt_tax_meta_field_value = edit_acpt_tax_meta_field_value([
                'term_id' => $this->oldest_category_id,
                'box_name' => 'box_name',
                'field_name' => 'File',
                'value' => [
	                'label' => 'label',
	                'url' => $url
                ],
        ]);

        $this->assertTrue($edit_acpt_tax_meta_field_value);

        $acpt_tax_field = get_acpt_tax_field([
                'term_id' => $this->oldest_category_id,
                'box_name' => 'box_name',
                'field_name' => 'File',
        ]);

	    /** @var WPAttachment $file */
	    $file = $acpt_tax_field['file'];

	    $this->assertNotNull($file);
	    $this->assertEquals('label', $acpt_tax_field['label']);

        return $url;
    }

    /**
     * @depends can_edit_acpt_tax_meta_field_value
     * @test
     */
    public function can_display_acpt_tax_meta_field($url)
    {
        $acpt_tax_field = acpt_tax_field([
                'term_id' => $this->oldest_category_id,
                'box_name' => 'box_name',
                'field_name' => 'File',
        ]);

        $this->assertStringContainsString($url, $acpt_tax_field);

        $this->deleteFile($url);
    }

    /**
     * @depends can_edit_acpt_tax_meta_field_value
     * @test
     */
    public function can_delete_acpt_tax_meta_field_value()
    {
        $delete_acpt_tax_meta_field_value = delete_acpt_tax_meta_field_value([
                'term_id' => $this->oldest_category_id,
                'box_name' => 'box_name',
                'field_name' => 'File',
        ]);

        $this->assertTrue($delete_acpt_tax_meta_field_value);

        $delete_acpt_tax_meta_box = delete_acpt_tax_meta_box('category', 'box_name');

        $this->assertTrue($delete_acpt_tax_meta_box);

        $acpt_tax_field = get_acpt_tax_field([
                'term_id' => $this->oldest_category_id,
                'box_name' => 'box_name',
                'field_name' => 'File',
        ]);

        $this->assertNull($acpt_tax_field);
    }
}