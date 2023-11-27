<?php

namespace ACPT\Core\Shortcodes\ACPT\Fields;

use ACPT\Core\Helper\Strings;
use ACPT\Utils\Wordpress\WPAttachment;

class GalleryField extends AbstractField
{
    public function render()
    {
        if(!$this->isFieldVisible()){
            return null;
        }

        $return = '';
        $this->enqueueAssets();

	    if($this->isABlockElement()){
		    @$groupRawValue = $this->fetchMeta($this->getKey());
		    $field = Strings::toDBFormat($this->payload->field);
		    $data = $this->getBlockElementValue($groupRawValue, $field);

		    if($data !== null and isset($data['value'])){
			    $images = $data['value'];
		    } else {
			    $images = [];
		    }
	    } elseif($this->isAChildElement()){
            @$groupRawValue = $this->fetchMeta($this->getKey());
            $field = Strings::toDBFormat($this->payload->field);

            if(isset($groupRawValue[$field]) and isset($groupRawValue[$field][$this->payload->index])){
                $data = $groupRawValue[$field][$this->payload->index];

                $images = $data['value'];
            } else {
                $images = [];
            }

        } else {
            $images = $this->fetchMeta($this->getKey());
        }

        $elements = ($this->payload->elements !== null) ? $this->payload->elements : 1;

        $style = 'margin:auto;';
        $style .= ($this->payload->width !== null) ? 'width: '. $this->payload->width .';' : 'width: 100%;';
        $style .= ($this->payload->height !== null) ? 'height: '. $this->payload->height .';' : 'height: 350px;';

        $width = ($this->payload->width !== null) ? $this->payload->width : '100%';
        $height = ($this->payload->height !== null) ? $this->payload->height : null;

        if(!empty($images)){
            $return .= '<div class="acpt-owl-carousel owl-carousel" data-carousel-options=\'{"items":'.(int)$elements.'}\'>';

            foreach ($images as $image){
            	// @TODO ID
                $wpAttachment = WPAttachment::fromUrl($image);

                $return .= '<div class="item" style="'.$style.'">';
                $return .= $this->addBeforeAndAfter('<img src="'. esc_url($image).'" width="'.esc_attr($width).'" height="'.esc_attr($height).'" title="'.$wpAttachment->getTitle().'" alt="'.$wpAttachment->getAlt().'" />');
                $return .= '</div>';
            }

            $return .= '</div>';
        }

        return $return;

    }

    /**
     * Enqueue necessary assets
     */
    public function enqueueAssets()
    {
        wp_enqueue_style( 'owl.carousel.min', plugin_dir_url( dirname( __FILE__ ) ) . '../../../../assets/vendor/owl.carousel/dist/assets/owl.carousel.min.css', array(), '2.2.0', 'all');
        wp_enqueue_style( 'owl.carousel.theme', plugin_dir_url( dirname( __FILE__ ) ) . '../../../../assets/vendor/owl.carousel/dist/assets/owl.theme.default.min.css', array(), '2.2.0', 'all');
        wp_enqueue_script( 'owl.carousel', plugin_dir_url( dirname( __FILE__ ) ) . '../../../../assets/vendor/owl.carousel/dist/owl.carousel.min.js', array ( 'jquery' ), '2.2.0', true);
        wp_enqueue_script( 'custom-owl.carousel', plugin_dir_url( dirname( __FILE__ ) ) . '../../../../assets/static/js/owl.carousel.js', array ( 'jquery' ), '2.2.0', true);
    }
}