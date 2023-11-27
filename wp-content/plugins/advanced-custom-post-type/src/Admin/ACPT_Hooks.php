<?php

namespace ACPT\Admin;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Models\Template\TemplateModel;
use ACPT\Core\Repository\CustomPostTypeRepository;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Costants\MetaTypes;
use ACPT\Utils\Data\DataAggregator;
use ACPT\Utils\PHP\Code;
use ACPT\Utils\PHP\PhpEval;
use ACPT\Utils\Wordpress\WPLinks;

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @package    advanced-custom-post-type
 * @subpackage advanced-custom-post-type/admin
 * @author     Mauro Cassani <maurocassani1978@gmail.com>
 */
class ACPT_Hooks
{
    /**
     * Display before main content
     */
    public function beforeMainContent()
    {
        echo '<!--========== START ACPT CONTENT ==========-->';
    }

    /**
     * Display after main content
     */
    public function afterMainContent()
    {
        echo '<!--========== END ACPT CONTENT ==========-->';
    }

    /**
     * Display breadcrumbs
     *
     * @param string $delimiter
     * @throws \Exception
     */
    public function breadcrumb($delimiter = null)
    {
        global $wp_query, $post;

        switch ($delimiter){

            case "/":
                $delimiter = "&raquo;";
                break;

            case "gt":
                $delimiter = "&gt;";
                break;

            case "raquo":
            default:
                $delimiter = "&raquo;";
        }

        echo '<div class="acpt-breadcrumb">';
        echo '<ul class="acpt-breadcrumb-list">';
        if (!is_home()) {
            echo '<li><a href="';
            echo get_option('home');
            echo '">';
            echo 'Home';
            echo "</a></li>";
            echo "<li class='delimiter'>".esc_html($delimiter)."</li>";

            if (is_single()) {

                foreach ( CustomPostTypeRepository::get([ 'postType' => $post->post_type]) as $postTypeModel) {
                    echo "<li>";
                    echo "<a href='".get_post_type_archive_link(esc_attr($postTypeModel->getName()))."'>";
                    echo esc_html($postTypeModel->getPlural());
                    echo '</a>';
                    echo "</li>";
                    echo "<li class='delimiter'>".esc_html($delimiter)."</li>";
                    echo "<li>";
                    the_title();
                    echo '</li>';
                }
            } elseif (is_archive()){
                foreach ( CustomPostTypeRepository::get([ 'postType' => $post->post_type]) as $postTypeModel) {
                    if (is_post_type_archive(esc_attr($postTypeModel->getName()))){
                        echo "<li>";
                        echo esc_html($postTypeModel->getPlural());
                        echo "</li><li>";
                    }
                }
            }
        }
        echo '</ul>';
        echo '</div>';
    }

    /**
     * @param $pixels
     *
     * @return string
     */
    private function convertToPixelString($pixels)
    {
        $explodedPixels = explode(',', $pixels);
        unset($explodedPixels[4]);

        return implode('px ', $explodedPixels).'px';
    }

    /**
     * Display thumbnail
     *
     * @param array $dimensions
     */
    public function thumbnail($dimensions = [])
    {
        $width = isset($dimensions['w']) ? ( Strings::contains('%', $dimensions['w']) ? $dimensions['w']  : $dimensions['w']  ) : null;
        $height = isset($dimensions['h']) ? ( Strings::contains('%', $dimensions['h']) ? $dimensions['h']  : $dimensions['h'] ) : null;

        global $post;

        if (has_post_thumbnail($post->ID)) {
            $post_thumbnail_id = get_post_thumbnail_id(get_the_ID());
            $thumbnail_url = wp_get_attachment_url($post_thumbnail_id, 'full');

            if($width and $height){
                echo '<img style="object-fit: cover; width: '.esc_attr($width).'; height: '.esc_attr($height).';" src="'.esc_url($thumbnail_url).'" alt="'.get_the_title().'" />';
            } else {
                echo '<img style="object-fit: cover; " src="'.esc_url($thumbnail_url).'" alt="'.get_the_title().'" />';
            }
        } else {

            $style = '';
            $style .= $width ? 'width: '.$width.';' : '';
            $style .= $height ? 'height: '.$height.';' : '';

            echo '<div class="acpt-img-placeholder" style="'.esc_attr($style).'"><span class="icon iconify" data-icon="bx:bx-image-alt" data-width="48" data-height="48"></span></div>';
        }
    }

    /**
     * Display template content
     *
     * @param TemplateModel $template
     */
    public function templateContent(TemplateModel $template)
    {
        $content = $template->getDecodedHtml();
        $content = Code::htmlToPhp($content);
        PhpEval::evaluate(do_shortcode($content));
    }

    /**
     * @param TemplateModel $template
     */
    public function customStyles(TemplateModel $template)
    {
        if($template->getDecodedCss() !== null){
            $decodedCss = Strings::minifyCss($template->getDecodedCss());
            $googleFontsImportUrl = $this->importGoogleFontsUrl($decodedCss);

            echo "<style>".$googleFontsImportUrl.$decodedCss."</style>";
        }

        echo null;
    }

    /**
     * @param $decodedCss
     *
     * @return string
     */
    private function importGoogleFontsUrl($decodedCss)
    {
        $googleFontsImportString = '';
        $usedGoogleFonts = [];

        $googleFonts = [
            'Lato' => 'Lato:ital,wght@0,400;0,700;0,900;1,400;1,700;1,900',
            'Montserrat' => 'Montserrat:ital,wght@0,400;0,800;1,400;1,800',
            'Open Sans' => 'Open+Sans:ital,wght@0,400;0,800;1,400;1,800',
            'Oswald' => 'Oswald:wght@400;700',
            'Poppins' => 'Poppins:ital,wght@0,400;0,800;1,400;1,800',
            'Raleway' => 'Poppins:ital,wght@0,400;0,800;1,400;1,800',
            'Roboto' => 'Roboto:ital,wght@0,400;0,700;1,400;1,700',
        ];

        foreach ($googleFonts as $googleFont => $string){
            if(Strings::contains($googleFont, $decodedCss)){
                $usedGoogleFonts[] = $string;
            }
        }

        if(!empty($usedGoogleFonts)){
            $googleFontsImportString .= "@import url('https://fonts.googleapis.com/css2";

            foreach ($usedGoogleFonts as $index => $usedGoogleFont){
                $googleFontsImportString .= ($index === 0) ? '?' : '&' ;
                $googleFontsImportString .= "family=".$usedGoogleFont;
            }

            $googleFontsImportString .= "&display=swap');";
        }

        return $googleFontsImportString;
    }

	/**
	 * @param array $args
	 *
	 * @return \WP_Term_Query|\WP_Query|array|null
	 * @throws \Exception
	 */
    public function loop($args = [])
    {
    	if(!Strings::exists($args['belongsTo'])){
    		return null;
	    }

    	switch ($args['belongsTo']){
		    case MetaTypes::CUSTOM_POST_TYPE:
		    	return $this->buildPostQuery($args);

		    case MetaTypes::TAXONOMY:
			    return $this->buildTermQuery($args);

		    case 'meta_field':
			    return $this->buildMetaFieldRepeaterQuery($args);

		    case 'flex_block':
		    	return $this->buildMetaFieldBlockQuery($args);
	    }
    }

	/**
	 * @param array $args
	 *
	 * @return \WP_Query
	 * @throws \Exception
	 */
    private function buildPostQuery($args = [])
    {
	    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

	    $queryArgs = [
		    'post_status' => 'publish',
		    'posts_per_page' => isset($args['perPage']) ? $args['perPage'] : -1,
		    'paged' => $paged,
	    ];

	    // $orderBy
	    $orderBy = 'title';

	    if(Strings::exists($args['orderBy'])){
		    if($args['orderBy'] === 'title'){
			    $orderBy = 'title';
		    } elseif($args['orderBy'] === 'date'){
			    $orderBy = 'publish_date';
		    } elseif($args['orderBy'] === 'ID'){
			    $orderBy = 'ID';
		    } else {
			    $field = MetaRepository::getMetaField([
				    'belongsTo' => $args['belongsTo'],
				    'id' => $args['orderBy'],
			    ]);
			    $orderBy = (CustomPostTypeMetaBoxFieldModel::NUMBER_TYPE === $field->getType()) ? 'meta_value_num': 'meta_value';
		    }
	    }

	    $postType = (isset($args['find']) and $args['find'] !== null) ? $args['find'] : null;
	    $taxonomy = (isset($args['taxonomy']) and $args['taxonomy'] !== null) ? $args['taxonomy'] : null;
	    $termId = (isset($args['term_id']) and $args['term_id'] !== null) ? $args['term_id'] : null;
	    $sortOrder = (isset($args['sortBy'])) ? $args['sortBy'] : 'ASC';

	    $queryArgs['orderby'] = $orderBy;
	    $queryArgs['order'] = $sortOrder;

	    if($postType !== null){
		    $queryArgs['post_type'] = $postType;
	    }

	    if($taxonomy !== null and $termId !== null){
		    $queryArgs['tax_query'] = [
			    [
				    'taxonomy' => $taxonomy,
				    'field' => 'term_id',
				    'terms' => $termId,
			    ]
		    ];
	    }

	    // meta keys
	    if(isset($args['sortBy']) and isset($field) and $field !== null){
		    $queryArgs['meta_key'] = $field->getDbName();

		    //'NUMERIC', 'BINARY', 'CHAR', 'DATE', 'DATETIME', 'DECIMAL', 'SIGNED', 'TIME', 'UNSIGNED'
		    if($field->getType() === CustomPostTypeMetaBoxFieldModel::NUMBER_TYPE){
			    $metaType = 'NUMERIC';
		    } elseif($field->getType() === CustomPostTypeMetaBoxFieldModel::DATE_TYPE){
			    $metaType = 'DATE';
		    } else {
			    $metaType = 'CHAR';
		    }

		    $queryArgs['meta_type'] = $metaType;
	    }

	    return new \WP_Query( $queryArgs );
    }

	/**
	 * @param array $args
	 *
	 * @return \WP_Term_Query
	 * @throws \Exception
	 */
    private function buildTermQuery($args = [])
    {
	    $paged = isset($_GET['pag']) ? $_GET['pag'] : 1;
	    $perPage = isset($args['perPage']) ? $args['perPage'] : 0;
	    $offset = ($paged - 1) * $perPage;

	    $queryArgs = [
		    'hide_empty' => false,
		    'fields' => 'all',
		    'number' => $perPage,
		    'offset' => $offset
	    ];

	    // $orderBy
	    $orderBy = 'name';

	    if(Strings::exists($args['orderBy'])){
		    if($args['orderBy'] === 'name'){
			    $orderBy = 'name';
		    } elseif($args['orderBy'] === 'date'){
			    $orderBy = 'publish_date';
		    } elseif($args['orderBy'] === 'ID'){
			    $orderBy = 'term_id';
		    } else {
			    $field = MetaRepository::getMetaField([
				    'belongsTo' => $args['belongsTo'],
				    'id' => $args['orderBy'],
			    ]);
			    $orderBy = (CustomPostTypeMetaBoxFieldModel::NUMBER_TYPE === $field->getType()) ? 'meta_value_num': 'meta_value';
		    }
	    }

	    $sortOrder = (isset($args['sortBy'])) ? $args['sortBy'] : 'ASC';

	    $queryArgs['orderby'] = $orderBy;
	    $queryArgs['order'] = $sortOrder;

	    $taxonomy = (isset($args['find']) and $args['find'] !== null) ? $args['find'] : null;

	    if($taxonomy !== null){
		    $queryArgs['taxonomy'] = $taxonomy;
	    }

	    // meta keys
	    if(isset($args['sortBy']) and $args['sortBy'] !== 'title' and $args['sortBy'] !== 'date' ){
		    $queryArgs['meta_key'] = $field->getDbName();

		    //'NUMERIC', 'BINARY', 'CHAR', 'DATE', 'DATETIME', 'DECIMAL', 'SIGNED', 'TIME', 'UNSIGNED'
		    if($field->getType() === CustomPostTypeMetaBoxFieldModel::NUMBER_TYPE){
			    $metaType = 'NUMERIC';
		    } elseif($field->getType() === CustomPostTypeMetaBoxFieldModel::DATE_TYPE){
			    $metaType = 'DATE';
		    } else {
			    $metaType = 'CHAR';
		    }

		    $queryArgs['meta_type'] = $metaType;
	    }

	    return new \WP_Term_Query( $queryArgs );
    }

	/**
	 * @param array $args
	 *
	 * @return CustomPostTypeMetaBoxFieldModel[]|array
	 * @throws \Exception
	 */
    private function buildMetaFieldRepeaterQuery($args = [])
    {
	    $belongsTo = MetaTypes::CUSTOM_POST_TYPE;
    	$field = MetaRepository::getMetaField([
    		'id' => $args['find'],
    		'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
	    ]);

	    if($field === null){
		    $belongsTo = MetaTypes::OPTION_PAGE;
		    $blockModel = MetaRepository::getMetaField([
			    'id' => $args['find'],
			    'belongsTo' => MetaTypes::OPTION_PAGE,
		    ]);
	    }

	    if(empty($blockModel)){
		    return [];
	    }

	    switch ($belongsTo){
		    case MetaTypes::CUSTOM_POST_TYPE:

			    if(!get_the_ID()){
				    return [];
			    }

			    if($field->hasChildren()){
				    $savedValues = get_acpt_field([
					    'box_name' => $field->getMetaBox()->getName(),
					    'field_name' => $field->getName(),
					    'post_id' => get_the_ID(),
				    ]);

				    if(is_array($savedValues) and !empty($savedValues)){
					    return $savedValues;
				    }

				    return [];
			    }

			    return [];

		    case MetaTypes::OPTION_PAGE:
			    // @TODO
			    return [
				    'fdsfddf',
				    'fdsfddf',
				    'fdsfddf',
				    'fdsfddf',
			    ];
	    }

    	return [];
    }

	/**
	 * @param array $args
	 *
	 * @return array
	 * @throws \Exception
	 */
    private function buildMetaFieldBlockQuery($args = [])
    {
    	$belongsTo = MetaTypes::CUSTOM_POST_TYPE;
    	$blockModel = MetaRepository::getMetaBlockById([
		    'id' => $args['find'],
		    'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
	    ]);

    	if($blockModel === null){
		    $belongsTo = MetaTypes::OPTION_PAGE;
		    $blockModel = MetaRepository::getMetaBlockById([
			    'id' => $args['find'],
			    'belongsTo' => MetaTypes::OPTION_PAGE,
		    ]);
	    }

    	if(empty($blockModel)){
    		return [];
	    }

		switch ($belongsTo){
			case MetaTypes::CUSTOM_POST_TYPE:

				if(!get_the_ID()){
					return [];
				}

				$idName = Strings::toDBFormat($blockModel->getMetaBoxField()->getMetaBox()->getName()) . '_' . Strings::toDBFormat($blockModel->getMetaBoxField()->getName());
				$savedValues = get_post_meta(get_the_ID(), $idName, true);

				$savedNestedFields = [];

				if(is_array($savedValues) and isset($savedValues['blocks']) and !empty($savedValues['blocks'])){
					foreach ($savedValues['blocks'] as $blockIndex => $savedBlock){
						foreach ($savedBlock as $blockName => $nestedFields){
							if($blockModel->getName() === $blockName){
								$savedNestedFields[$blockIndex] = DataAggregator::aggregateNestedFieldsData($nestedFields);
							}
						}
					}

					return $savedNestedFields;
				}

				return [];

			case MetaTypes::OPTION_PAGE:
				// @TODO
				return [
					'fdsfddf',
					'fdsfddf',
					'fdsfddf',
					'fdsfddf',
				];
		}

	    return [];
    }

    /**
     * Prev next links
     */
    public function prevNextLinks()
    {
        global $wp_query, $post;

        $prev = WPLinks::getPrevLink($post->ID);
        $next = WPLinks::getNextLink($post->ID);

        echo '<div class="acpt-prevNext">';
        echo '<div>';

        if($prev){
            echo '
                <a href="'.esc_url($prev['link']).'">
                    < '.esc_html($prev['title']).'
                </a>';
        }
        echo '</div>';
        echo '<div>';

        if($next){
            echo '
                <a href="'.esc_url($next['link']).'">
                    '.esc_html($next['title']).' >
                </a>';
        }

        echo '</div>';
        echo '</div>';
    }

    /**
     * @param $text
     *
     * @return string
     */
    private function displayPrevNextLabel( $text)
    {
        $return = '<div>';
        $return .= '<strong>';
        $return .= '<small>';
        $return .= __($text, ACPT_PLUGIN_NAME);
        $return .= '</small>';
        $return .= '</strong>';
        $return .= '</div>';

        return $return;
    }

    /**
     * Taxonomy links
     */
    public function taxonomyLinks()
    {
        global $post;

        $links = WPLinks::getTaxonomiesLinks($post->ID, $post->post_type);

        if(!empty($links)){

            echo '<span class="acpt-taxonomy-list">';

            foreach ($links as $link){
                echo '<a href="'.esc_url($link['link']).'">'.esc_html($link['name']).'</a>';
            }

            echo '</span>';
        }
    }

	/**
	 * Display pagination
	 *
	 * @param string $belongsTo
	 * @param string $find
	 * @param int $perPage
	 * @param string $format
	 *
	 * @return string
	 */
    public function pagination($belongsTo, $find, $perPage, $format = '/page/%#%')
    {
	    switch ($belongsTo){
		    case MetaTypes::CUSTOM_POST_TYPE:
			    $newArgs = ['post_type' => $find, 'posts_per_page' => -1];
			    $newLoop = new \WP_Query($newArgs);
			    $postsCount = $newLoop->post_count;
			    $totalPages = ($perPage > 0) ? ceil($postsCount / (int)$perPage) : 0;

			    return $this->renderPagination($totalPages, $format);

		    case MetaTypes::TAXONOMY:
			    $newArgs = [
			    	'taxonomy' => $find,
				    'hide_empty' => false,
				    'fields' => 'all',
				    'number' => 0,
			    ];
			    $newLoop = new \WP_Term_Query($newArgs);
			    $termsCount = count($newLoop->get_terms());
			    $totalPages = ($perPage > 0) ? ceil($termsCount / (int)$perPage) : 0;

			    return $this->renderPagination($totalPages, $format);
	    }

	    return '';
    }

	/**
	 * @param int $totalPages
	 * @param string $format
	 *
	 * @return string
	 */
    private function renderPagination($totalPages, $format = '/page/%#%')
    {
	    $return = '';

	    if ($totalPages > 1) {

	    	if(isset($_GET['pag'])){
			    $currentPage = $_GET['pag'];
			    $base = remove_query_arg('pag', get_pagenum_link(1) );
			    $base = add_query_arg('pag','%#%', $base);
		    } else {
			    $currentPage = max(1, get_query_var('paged'));
			    $base = get_pagenum_link(1) . '%_%';
		    }

		    $return .= '<div class="acpt-pagination">';
		    $pagination = paginate_links([
			    'base' => $base,
			    'format' => $format,
			    'current' => $currentPage,
			    'total' => $totalPages,
			    'prev_text' => '<',
			    'next_text' => '>',
			    'type' => 'array',
		    ]);

		    $return .= '<ul>';
		    foreach ($pagination as $item){
			    $return .= '<li>'.$item.'</li>';
		    }
		    $return .= '</ul>';
		    $return .= '</div>';
	    }

	    return $return;
    }
}
