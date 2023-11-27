<?php

namespace ACPT\Integrations\Elementor\Widgets;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Shortcodes\ACPT\PostMetaShortcode;

class WidgetGenerator extends \Elementor\Widget_Base
{
    /**
     * @var CustomPostTypeMetaBoxFieldModel
     */
    private $boxFieldModel;

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        if(!isset($args['boxFieldModel'])){
            throw new \Exception('A boxFieldModel instance required to run this widget.');
        }

        $this->boxFieldModel = $args['boxFieldModel'];
    }

    /**
     * Get the widget name
     *
     * @return string
     */
    public function get_name()
    {
        return $this->boxFieldModel ? $this->boxFieldModel->getDbName() : 'undefined';
    }

    /**
     * get the UI title
     *
     * @return string
     */
    public function get_title()
    {
        $title = $this->boxFieldModel ? $this->boxFieldModel->getUiName() : 'undefined';

        return esc_html__( $title, 'elementor-addon' );
    }

    /**
     * get UI icon
     *
     * @return string
     */
    public function get_icon()
    {
        if( !$this->boxFieldModel ){
            return 'eicon-editor-code';
        }

        switch ($this->boxFieldModel->getType()){
            case CustomPostTypeMetaBoxFieldModel::ADDRESS_TYPE:
                return ' eicon-map-pin';

            case CustomPostTypeMetaBoxFieldModel::COLOR_TYPE:
                return 'eicon-paint-brush';

            case CustomPostTypeMetaBoxFieldModel::CURRENCY_TYPE:
                return ' eicon-bag-light';

            case CustomPostTypeMetaBoxFieldModel::DATE_TYPE:
                return 'eicon-date';

            case CustomPostTypeMetaBoxFieldModel::EDITOR_TYPE:
                return 'eicon-text-area';

            case CustomPostTypeMetaBoxFieldModel::EMAIL_TYPE:
                return 'eicon-mail';

            case CustomPostTypeMetaBoxFieldModel::EMBED_TYPE:
                return 'eicon-gallery-grid';

            case CustomPostTypeMetaBoxFieldModel::FILE_TYPE:
                return 'eicon-save-o';

            case CustomPostTypeMetaBoxFieldModel::HTML_TYPE:
                return 'eicon-editor-code';

            case CustomPostTypeMetaBoxFieldModel::GALLERY_TYPE:
                return 'eicon-photo-library';

            case CustomPostTypeMetaBoxFieldModel::IMAGE_TYPE:
                return 'eicon-image';

            case CustomPostTypeMetaBoxFieldModel::LENGTH_TYPE:
                return 'eicon-cursor-move';

            case CustomPostTypeMetaBoxFieldModel::LIST_TYPE:
                return 'eicon-bullet-list';

            case CustomPostTypeMetaBoxFieldModel::NUMBER_TYPE:
                return 'eicon-number-field';

            case CustomPostTypeMetaBoxFieldModel::POST_TYPE:
                return 'eicon-sync';

            case CustomPostTypeMetaBoxFieldModel::PHONE_TYPE:
                return 'eicon-tel-field';

            case CustomPostTypeMetaBoxFieldModel::SELECT_TYPE:
            case CustomPostTypeMetaBoxFieldModel::SELECT_MULTI_TYPE:
                return 'eicon-select';

            default:
            case CustomPostTypeMetaBoxFieldModel::TEXTAREA_TYPE:
            case CustomPostTypeMetaBoxFieldModel::TEXT_TYPE:
                return 'eicon-t-letter';

            case CustomPostTypeMetaBoxFieldModel::TIME_TYPE:
                return 'eicon-clock-o';

            case CustomPostTypeMetaBoxFieldModel::TOGGLE_TYPE:
                return 'eicon-toggle';

            case CustomPostTypeMetaBoxFieldModel::VIDEO_TYPE:
                return 'eicon-play';

            case CustomPostTypeMetaBoxFieldModel::WEIGHT_TYPE:
                return 'eicon-basket-medium';

            case CustomPostTypeMetaBoxFieldModel::URL_TYPE:
                return 'eicon-url';
        }
    }

    /**
     * widget categories
     *
     * @return array
     */
    public function get_categories()
    {
        return [ 'acpt' ];
    }

    /**
     * get widget keywords
     *
     * @return array
     */
    public function get_keywords()
    {
        return [ 'acpt', strtolower($this->boxFieldModel->getType()) ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_title',
            [
                'label' => esc_html__( 'ACPT shortcode generator', 'elementor' ),
            ]
        );

        $this->add_control(
            'acpt_shortcode',
            [
                'type' => 'acpt_shortcode',
                'description' => esc_html__( 'Here you can see the base ACPT shortcode.', 'elementor' ),
                'default' => $this->boxFieldModel ? '[acpt box="'.esc_attr($this->boxFieldModel->getMetaBox()->getName()).'" field="'.esc_attr($this->boxFieldModel->getName()).'"]' : null,
                'placeholder' => esc_html__( 'Enter your code', 'elementor' ),
            ]
        );

        $group1 = [CustomPostTypeMetaBoxFieldModel::ADDRESS_TYPE, CustomPostTypeMetaBoxFieldModel::IMAGE_TYPE, CustomPostTypeMetaBoxFieldModel::VIDEO_TYPE, CustomPostTypeMetaBoxFieldModel::COLOR_TYPE, CustomPostTypeMetaBoxFieldModel::TOGGLE_TYPE];
        $group2 = [CustomPostTypeMetaBoxFieldModel::URL_TYPE];
        $group3 = [CustomPostTypeMetaBoxFieldModel::DATE_TYPE];
        $group4 = [CustomPostTypeMetaBoxFieldModel::GALLERY_TYPE];

        // Group 1
        if(in_array($this->boxFieldModel->getType(), $group1)){
            $this->add_control(
            'acpt_width',
                [
                    'type' => 'acpt_width',
                    'description' => esc_html__( 'Width.', 'elementor' ),
                    'default' => '100%',
                    'placeholder' => esc_html__( 'Set the width (in pixels)', 'elementor' ),
                ]
            );

            $this->add_control(
            'acpt_height',
                [
                    'type' => 'acpt_height',
                    'description' => esc_html__( 'Height.', 'elementor' ),
                    'default' => '300',
                    'placeholder' => esc_html__( 'Set the height (in pixels)', 'elementor' ),
                ]
            );
        }

        // Group 2
        if(in_array($this->boxFieldModel->getType(), $group2)){
            $this->add_control(
                'acpt_target',
                [
                    'type' => 'acpt_target',
                    'description' => esc_html__( 'Link target.', 'elementor' ),
                    'default' => '_self',
                    'placeholder' => esc_html__( 'Select the link target', 'elementor' ),
                ]
            );
        }

        // Group 3
        if(in_array($this->boxFieldModel->getType(), $group3)){
            $this->add_control(
                'acpt_dateformat',
                [
                    'type' => 'acpt_dateformat',
                    'description' => esc_html__( 'Date format.', 'elementor' ),
                    'default' => 'd/m/Y',
                    'placeholder' => esc_html__( 'Select the date format', 'elementor' ),
                ]
            );
        }

        // Group 4
        if(in_array($this->boxFieldModel->getType(), $group4)){
            $this->add_control(
            'acpt_width',
                [
                    'type' => 'acpt_width',
                    'description' => esc_html__( 'Width (px).', 'elementor' ),
                    'default' => '100%',
                    'placeholder' => esc_html__( 'Set the width (in pixels)', 'elementor' ),
                ]
            );

            $this->add_control(
            'acpt_height',
                [
                    'type' => 'acpt_height',
                    'description' => esc_html__( 'Height (px).', 'elementor' ),
                    'default' => '300',
                    'placeholder' => esc_html__( 'Set the height (in pixels)', 'elementor' ),
                ]
            );

            $this->add_control(
            'acpt_elements',
                [
                    'type' => 'acpt_elements',
                    'description' => esc_html__( 'Number of elements.', 'elementor' ),
                    'default' => '2',
                    'placeholder' => esc_html__( 'Select the number of elements', 'elementor' ),
                ]
            );
        }

        $this->end_controls_section();
    }

    /**
     * Render the widget
     */
    protected function render() {

        $settings = $this->get_controls_settings();
        $box = esc_attr($this->boxFieldModel->getMetaBox()->getName());
        $field = esc_attr($this->boxFieldModel->getName());
        $width = (isset($settings['acpt_width'])) ? $settings['acpt_width'] : null;
        $height = (isset($settings['acpt_height'])) ? $settings['acpt_height'] : null;
        $elements = (isset($settings['acpt_elements'])) ? $settings['acpt_elements'] : null;
        $target = (isset($settings['acpt_target'])) ? $settings['acpt_target'] : null;
        $dateFormat = (isset($settings['acpt_dateformat'])) ? $settings['acpt_dateformat'] : null;

        $shortcodeString = $this->generateShortcodeString(
            $box,
            $field,
            $width,
            $height,
            $target,
            $dateFormat,
            $elements);

        if ($_SERVER['PHP_SELF'] === '/wp-admin/post.php' || $_SERVER['PHP_SELF'] === '/wp-admin/admin-ajax.php'){
            echo $this->renderShortcode(
                $box,
                $field,
                $width,
                $height,
                $target,
                $dateFormat,
                $elements
            );
        } else {
            echo $shortcodeString;
        }
    }

    /**
     * @param string $box
     * @param string $field
     * @param null   $width
     * @param null   $height
     * @param null   $target
     * @param null   $dateFormat
     * @param null   $elements
     *
     * @return string
     */
    private function generateShortcodeString($box, $field, $width = null, $height = null, $target = null, $dateFormat = null, $elements = null)
    {
        $shortcode = '[acpt box="'.$box.'" field="'.$field.'"';

        if($target){
            $shortcode .= ' target="'.$target.'"';
        }

        if($width){
            $shortcode .= ' width="'.$width.'"';
        }

        if($height){
            $shortcode .= ' height="'.$height.'"';
        }

        if($dateFormat){
            $shortcode .= ' date-format="'.$dateFormat.'"';
        }

        if($elements){
            $shortcode .= ' elements="'.$elements.'"';
        }

        $shortcode .= ']';

        return $shortcode;
    }

    /**
     * @param string $box
     * @param string $field
     * @param null   $width
     * @param null   $height
     * @param null   $target
     * @param null   $dateFormat
     * @param null   $elements
     *
     * @return string
     * @throws \Exception
     */
    private function renderShortcode($box, $field, $width = null, $height = null, $target = null, $dateFormat = null, $elements = null)
    {
        $postMetaShortcode = new PostMetaShortcode();
        $attr = [
            'pid' => isset($_GET['post']) ? $_GET['post'] : null,
            'box' => esc_attr($box),
            'field' => esc_attr($field),
            'width' => $width ? $width  : null,
            'height' => $height ? $height  : null,
            'target' => $target ? $target  : null,
            'dateFormat' => $dateFormat ? $dateFormat  : null,
            'elements' => $elements ? $elements  : null,
        ];

        return $postMetaShortcode->render($attr);
    }
}
