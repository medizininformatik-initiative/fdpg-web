<?php

namespace ACPT\Tests;

use ACPT\Core\Shortcodes\ACPT\OptionPageMetaShortcode;
use ACPT\Core\Shortcodes\ACPT\PostMetaShortcode;
use ACPT\Core\Shortcodes\ACPT\TaxonomyMetaShortcode;
use ACPT\Core\Shortcodes\ACPT\UserMetaShortcode;
use ACPT\Utils\Wordpress\Files;
use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    /**
     * @var int
     */
    protected $oldest_page_id;

    /**
     * @var int
     */
    protected $second_oldest_page_id;

    /**
     * @var int
     */
    protected $oldest_post_id;

    /**
     * @var int
     */
    protected $oldest_category_id;

    /**
     * @var int
     */
    protected $oldest_post_tag_id;

    /**
     * set up the server
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->oldest_page_id = $this->getPostId('page', 0);
        $this->oldest_post_id = $this->getPostId('post', 0);
        $this->second_oldest_page_id = $this->getPostId('page', 1);
        $this->oldest_category_id = $this->getTermId('category', 0);
        $this->oldest_post_tag_id = $this->getTermId('post_tag', 0);

        $this->setCurrentUser();
        $this->initShortcode();
    }

    /**
     * Set the current user (just for test purpose)
     */
    private function setCurrentUser()
    {
        $users = get_users(['role__in' => ['administrator']]);

        wp_set_current_user($users[0]->ID);
    }

    /**
     * init the acpt shortcode
     */
    private function initShortcode()
    {
        add_shortcode('acpt', [new PostMetaShortcode(), 'render']);
        add_shortcode('acpt_tax', [new TaxonomyMetaShortcode(), 'render']);
        add_shortcode('acpt_user', [new UserMetaShortcode(), 'render']);
        add_shortcode('acpt_option', [new OptionPageMetaShortcode(), 'render']);
    }

    /**
     * @param string $postType
     * @param int    $index
     *
     * @return int|null
     */
    private function getPostId($postType, $index)
    {
        $numberposts = $index + 1;
        $oldest_id_query = get_posts("post_type=".$postType."&numberposts=".$numberposts."&order=ASC");

        if(empty($oldest_id_query) or empty($oldest_id_query[$index])){
           return null;
        }

        return $oldest_id_query[$index]->ID;
    }

    /**
     * @param $term
     * @param $index
     * @return mixed
     */
    private function getTermId($term, $index)
    {
        $terms = get_terms( $term, [
            'hide_empty' => false,
        ] );

        if(isset($terms[$index])){
            return $terms[$index]->term_id;
        }

        return null;
    }

    /**
     * @param $url
     * @return bool
     */
    protected function deleteFile($url)
    {
        return Files::deleteFile($url);
    }

    /**
     * @param $path
     * @param null $parentPostId
     * @return array
     */
    protected function uploadFile($path, $parentPostId = null)
    {
        return Files::uploadFile($path, $parentPostId);
    }
}