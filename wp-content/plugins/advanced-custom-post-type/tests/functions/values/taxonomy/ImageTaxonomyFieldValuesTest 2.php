<?php

namespace ACPT\Tests;

use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;

class ImageTaxonomyFieldValuesTest extends AbstractTestCase
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
                'field_name' => 'Image',
                'field_type' => TaxonomyMetaBoxFieldModel::IMAGE_TYPE,

                'required' => false,
            ]
        );

        // not existent file
        $add_acpt_tax_meta_field_value = add_acpt_tax_meta_field_value([
            'term_id' => $this->oldest_category_id,
            'box_name' => 'box_name',
            'field_name' => 'Image',
            'value' => "http://xxxxxx.com/uploads/not-existing.txt",
        ]);

        $this->assertFalse($add_acpt_tax_meta_field_value);

        // not an image
        $video = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/video1.mp4');
        $videoUrl = $video['url'];

        $add_acpt_tax_meta_field_value = add_acpt_tax_meta_field_value([
                'term_id' => $this->oldest_category_id,
                'box_name' => 'box_name',
                'field_name' => 'Image',
                'value' => $videoUrl,
        ]);

        $this->assertFalse($add_acpt_tax_meta_field_value);

        // image
        $image = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/image1.jpg');
        $imageUrl = $image['url'];

        $add_acpt_tax_meta_field_value = add_acpt_tax_meta_field_value([
            'term_id' => $this->oldest_category_id,
            'box_name' => 'box_name',
            'field_name' => 'Image',
            'value' => $imageUrl,
        ]);

        $this->assertTrue($add_acpt_tax_meta_field_value);

        $acpt_tax_field = get_acpt_tax_field([
            'term_id' => $this->oldest_category_id,
            'box_name' => 'box_name',
            'field_name' => 'Image',
        ]);

        $this->assertNotNull($acpt_tax_field);

        $this->deleteFile($imageUrl);
        $this->deleteFile($videoUrl);
    }

    /**
     * @depends can_add_acpt_tax_meta_field_value
     * @test
     */
    public function can_edit_acpt_tax_meta_field_value()
    {
        $image = $this->uploadFile(__DIR__.'/../../../../tests/_inc/support/files/image2.jpeg');
        $imageUrl = $image['url'];

        $edit_acpt_tax_meta_field_value = edit_acpt_tax_meta_field_value([
                'term_id' => $this->oldest_category_id,
                'box_name' => 'box_name',
                'field_name' => 'Image',
                'value' => $imageUrl,
        ]);

        $this->assertTrue($edit_acpt_tax_meta_field_value);

        $acpt_tax_field = get_acpt_tax_field([
                'term_id' => $this->oldest_category_id,
                'box_name' => 'box_name',
                'field_name' => 'Image',
        ]);

        $this->assertStringContainsString($acpt_tax_field->getMetadata()['file'], $imageUrl);

        return $imageUrl;
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
            'field_name' => 'Image',
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
                'field_name' => 'Image',
        ]);

        $this->assertTrue($delete_acpt_tax_meta_field_value);

        $delete_acpt_tax_meta_box = delete_acpt_tax_meta_box('category', 'box_name');

        $this->assertTrue($delete_acpt_tax_meta_box);

        $acpt_tax_field = get_acpt_tax_field([
                'term_id' => $this->oldest_category_id,
                'box_name' => 'box_name',
                'field_name' => 'Image',
        ]);

        $this->assertNull($acpt_tax_field);
    }
}

