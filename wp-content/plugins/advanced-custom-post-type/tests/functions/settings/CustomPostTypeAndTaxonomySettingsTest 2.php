<?php

namespace ACPT\Tests;

class CustomPostTypeAndTaxonomySettingsTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function dont_register_a_new_custom_post_type_with_missing_params()
    {
        $new_custom_post_type = register_acpt_post_type([
            'post_name' => 'new-cpt',
        ]);

        $this->assertNull($new_custom_post_type);
        $this->assertFalse(post_type_exists('new-cpt'));
    }

    /**
     * @test
     */
    public function can_register_a_new_custom_post_type()
    {
        $new_custom_post_type = register_acpt_post_type([
            'post_name' => 'new-cpt',
            'singular_label' => 'New CPT',
            'plural_label' => 'New CPTs',
            'icon' => 'admin-appearance',
            'supports' => [
                'title',
                'editor',
                'comments',
                'revisions',
                'trackbacks',
                'author',
                'excerpt',
            ],
            'labels' => [],
            'settings' => [],
        ]);

        $this->assertEquals($new_custom_post_type->name, 'new-cpt');
        $this->assertTrue(post_type_exists('new-cpt'));
    }

    /**
     * @test
     */
    public function can_throw_an_error_when_register_a_new_taxonomy()
    {
        $new_taxonomy = register_acpt_taxonomy([
            'slug' => 'new-tax',
        ]);

        $this->assertFalse($new_taxonomy);
        $this->assertFalse(taxonomy_exists('new-tax'));
    }

    /**
     * @test
     */
    public function can_register_a_new_taxonomy()
    {
        $new_taxonomy = register_acpt_taxonomy([
            'slug' => 'new-tax',
            'singular_label' => 'New Taxonomy',
            'plural_label' => 'New Taxonomies',
            'labels' => [],
            'settings' => [],
            'post_types' => [
                'new-cpt'
            ]
        ]);

        $this->assertTrue($new_taxonomy);
        $this->assertTrue(taxonomy_exists('new-tax'));

        $taxonomies = get_object_taxonomies([
            'post_type' => 'new-cpt'
        ]);

        $this->assertTrue(in_array('new-tax', array_values($taxonomies)));
    }

    /**
     * @test
     */
    public function can_assoc_a_taxonomy_with_post_type()
    {
        assoc_acpt_taxonomy_to_acpt_post('new-tax', 'page');

        $taxonomies = get_object_taxonomies([
            'post_type' => 'page'
        ]);

        $this->assertTrue(in_array('new-tax', array_values($taxonomies)));
    }

    /**
     * @test
     */
    public function can_remove_a_taxonomy_from_post_type()
    {
        remove_assoc_acpt_taxonomy_from_acpt_post('new-tax', 'new-cpt');
        remove_assoc_acpt_taxonomy_from_acpt_post('new-tax', 'page');

        $taxonomies = get_object_taxonomies([
            'post_type' => 'new-cpt'
        ]);

        $this->assertFalse(in_array('new-tax', array_values($taxonomies)));

        $taxonomies = get_object_taxonomies([
            'post_type' => 'page'
        ]);

        $this->assertFalse(in_array('new-tax', array_values($taxonomies)));
    }

    /**
     * @test
     */
    public function can_delete_a_custom_post_type()
    {
        $this->assertTrue(delete_acpt_post_type('new-cpt', true));
        $this->assertFalse(post_type_exists('new-cpt'));
    }

    /**
     * @test
     */
    public function can_delete_a_taxonomy()
    {
        $this->assertTrue(delete_acpt_taxonomy('new-tax'));
        $this->assertFalse(taxonomy_exists('new-tax'));
    }
}