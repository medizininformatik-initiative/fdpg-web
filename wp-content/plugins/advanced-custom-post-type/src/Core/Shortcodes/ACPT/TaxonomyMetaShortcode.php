<?php

namespace ACPT\Core\Shortcodes\ACPT;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Core\Shortcodes\ACPT\DTO\ShortcodePayload;
use ACPT\Costants\MetaTypes;

class TaxonomyMetaShortcode extends AbstractACPTShortcode
{
	/**
	 * @param $atts
	 *
	 * @return mixed|string|null
	 * @throws \Exception
	 */
    public function render($atts)
    {
        if(!isset($atts['box']) or !isset($atts['field'])){
            return '';
        }

        $tid = isset($atts['tid']) ? $atts['tid'] : null;

	    if($tid === null and isset($atts['term'])){
		    $pageFieldModel =  MetaRepository::getMetaFieldByName([
			    'boxName' => $atts['box'],
			    'fieldName' => $atts['field'],
			    'belongsTo' => MetaTypes::TAXONOMY,
		    ]);

		    if($pageFieldModel !== null){
			    $term = get_term_by('name' , $atts['term'], $pageFieldModel->getMetaBox()->getTaxonomy());

			    if($term){
				    $tid = $term->term_id;
			    }
		    }
	    }

        if($tid === null){
            $queriedObject = get_queried_object();

            if(!$queriedObject instanceof \WP_Term){
                return null;
            }

            $tid = $queriedObject->term_id;
        }

        $taxonomyObject = get_term($tid);
        $taxonomy = $taxonomyObject->taxonomy;

        $box = $atts['box'];
        $field = $atts['field'];
        $width = isset ($atts['width'] ) ? $atts['width'] : null;
        $height = isset ($atts['height'] ) ? $atts['height'] : null;
        $target = isset ($atts['target'] ) ? $atts['target'] : null;
        $dateFormat = isset ($atts['date-format'] ) ? $atts['date-format'] : null;
        $elements = isset ($atts['elements'] ) ? $atts['elements'] : null;
        $preview = (isset($atts['preview']) and $atts['preview'] === 'true') ? true : false;
        $parent = (isset($atts['parent'])) ? $atts['parent'] : null;
        $index = (isset($atts['index'])) ? $atts['index'] : null;
	    $render = (isset($atts['render'])) ? $atts['render'] : null;

        $key = Strings::toDBFormat($box).'_'.Strings::toDBFormat($field);
        $type = get_term_meta($tid, $key.'_type', true);
        $data = get_term_meta($tid, $key, true);

        if($data === null or $data === ''){
            return '';
        }

        if(!empty($type)){

            $payload = new ShortcodePayload();
            $payload->id = $tid;
            $payload->box = $box;
            $payload->field = $field;
            $payload->belongsTo = MetaTypes::TAXONOMY;
            $payload->find = $taxonomy;
            $payload->width = $width;
            $payload->height = $height;
            $payload->target = $target;
            $payload->dateFormat = $dateFormat;
            $payload->elements = $elements;
            $payload->preview = $preview;
            $payload->parent = $parent;
            $payload->index = $index;
	        $payload->render = $render;

            $field = self::getField($type, $payload);

	        if($field){
		        return $field->render();
	        }

	        return null;
        }

        return null;
    }
}

