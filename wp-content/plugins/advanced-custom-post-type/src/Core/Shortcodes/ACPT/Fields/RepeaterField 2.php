<?php

namespace ACPT\Core\Shortcodes\ACPT\Fields;

use ACPT\Core\Models\Template\TemplateModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Core\Repository\TemplateRepository;
use ACPT\Costants\MetaTypes;
use ACPT\Utils\PHP\Code;
use ACPT\Utils\PHP\PhpEval;

class RepeaterField extends AbstractField
{
    public function render()
    {
        $postType = get_post_type($this->payload->id);
        @$groupValue = $this->fetchMeta($this->getKey());

        $numberOfElements = count($groupValue[array_keys($groupValue)[0]]);

        if(empty($groupValue)){
            return null;
        }

        try {
            $metaBoxFieldModel = MetaRepository::getMetaFieldByName([
                'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
                'find' => $postType,
                'boxName' => $this->payload->box,
                'fieldName' => $this->payload->field,
            ]);

            if($metaBoxFieldModel === null){
                return null;
            }

            $childrenCount = count($metaBoxFieldModel->getChildren());

            if($this->payload->preview){
                return $this->renderPreview($childrenCount);
            }

            return $this->renderShortcode($postType, $metaBoxFieldModel->getId(), $numberOfElements);
        } catch (\Exception $exception){
            return 'Exception grabbing post meta data: ' . $exception->getMessage();
        }
    }

    /**
     * Render the preview
     *
     * @param $childrenCount
     *
     * @return string
     */
    private function renderPreview($childrenCount)
    {
        if($childrenCount === 0){
            return 'No children fields present';
        }

        if($childrenCount === 1){
            return 'One child field';
        }

        return $childrenCount . ' children fields';
    }

    /**
     * Render the shortcode from template
     *
     * @param $postType
     * @param $metaFieldId
     * @param $numberOfElements
     *
     * @return string|null
     * @throws \Exception
     */
    private function renderShortcode($postType, $metaFieldId, $numberOfElements)
    {
        // check template
        $templateModel = TemplateRepository::get(MetaTypes::CUSTOM_POST_TYPE, 'metaField', $postType, $metaFieldId);

        if($templateModel === null){
            return null;
        }
        $meta = $templateModel->getMeta();
        $perRow = (isset($this->payload->elements) and $this->payload->elements !== null) ? $this->payload->elements : 1 ;
        $gap = (isset($meta['gap'])) ? $meta['gap'] : 20;

        $return = '<div class="acpt-theme-container">';
        $return .= '<div class="content-area">';
        $return .= '<div class="acpt-grid col-'.$perRow.'" style="gap:'.$gap.'px">';

        for ($i=0; $i<$numberOfElements; $i++){
            $return .= $this->renderTemplate($i, $templateModel);
        }

        $return .= '</div>';
        $return .= '</div>';
        $return .= '</div>';

        return $return;
    }

    /**
     * @param $index
     * @param TemplateModel $templateModel
     *
     * @return false|string
     */
    private function renderTemplate($index, TemplateModel $templateModel)
    {
        ob_start();

        $content = $templateModel->getDecodedHtml();
        $content =  Code::htmlToPhp($content);
        $content = $this->addIndexToACPTShortcodes($content, $index);
        PhpEval::evaluate($content);

        return ob_get_clean();
    }

    /**
     * @param $content
     * @param $index
     *
     * @return string|string[]
     */
    protected function addIndexToACPTShortcodes($content, $index)
    {
        if ( preg_match_all('/\[acpt(.*?)\]/', $content, $matches ) ) {
            foreach ($matches[0] as $shortcode){
                $modifiedTag = str_replace(']', ' index="'.$index.'"]', $shortcode);
                $content = str_replace($shortcode, $modifiedTag, $content);
            }
        }

        return $content;
    }
}