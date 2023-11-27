<?php

namespace ACPT\Core\Shortcodes\ACPT\Fields;

use ACPT\Core\Models\Template\TemplateModel;
use ACPT\Core\Repository\TemplateRepository;
use ACPT\Costants\MetaTypes;
use ACPT\Utils\PHP\Code;
use ACPT\Utils\PHP\PhpEval;

class PostField extends AbstractField
{
    public function render()
    {
        @$posts = $this->fetchMeta($this->getKey());

        if(empty($posts)){
            return null;
        }

        $postIds = (is_array($posts)) ? $posts : [(int)$posts];
        $relatedPostId = (is_array($posts)) ? $posts[0] : $posts;
        $relatedPostType = get_post_type($relatedPostId);

        try {
            $template = TemplateRepository::get(MetaTypes::CUSTOM_POST_TYPE, 'related', $relatedPostType);

            if(!empty($template) and $this->payload->preview === false){
                return $this->renderPostsTemplate($postIds, $template);
            }

            return $this->renderPreview($posts);
        } catch (\Exception $exception){
            return null;
        }
    }

    /**
     * @param array $ids
     * @param TemplateModel $templateModel
     *
     * @return string
     */
    private function renderPostsTemplate(array $ids, TemplateModel $templateModel)
    {
        add_action('wp_head', function () use ($templateModel) {
            do_action( 'acpt_custom_styles', $templateModel );
        }, 100);

        $meta = $templateModel->getMeta();
        $perRow = $this->payload->elements ? $this->payload->elements : 1;
        $gap = $meta['gap'];

        $args = [
            'post_type' => $templateModel->getPostType(),
            'post__in' => $ids
        ];

        $the_query = new \WP_Query( $args );

        if ( $the_query->have_posts() ) {

            echo '<div class="acpt-grid col-'.$perRow.'" style="gap:'.$gap.'px">';

            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                $content = $templateModel->getDecodedHtml();
                $content = Code::htmlToPhp($content);
                PhpEval::evaluate($content);
            }

            echo '</div>';
        }
    }

    /**
     * @param $posts
     *
     * @return string
     */
    private function renderPreview($posts)
    {
        if(is_array($posts)){
            return $this->returnAsList($posts);
        }

        // render as string
        return $this->returnPostLink($posts);
    }

    /**
     * Return post links list
     *
     * @param array $posts
     *
     * @return string
     */
    private function returnAsList(array $posts)
    {
        $return = '<ul>';

        foreach ($posts as $postId){
            $return .= '<li>'.$this->returnPostLink($postId).'</li>';
        }

        $return .= '</ul>';

        return $return;
    }

    /**
     * Return the post link
     *
     * @param $postId
     *
     * @return string
     */
    private function returnPostLink($postId)
    {
        $post = get_post((int)$postId);

        return '<a href="'.$post->guid.'" target="_blank">'.$post->post_title.'</a>';
    }
}