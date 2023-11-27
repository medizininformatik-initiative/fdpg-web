<?php

namespace ACPT\Core\Generators;

use ACPT\Core\Generators\RepeaterFields\RepeaterFieldInterface;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Utils\Data\DataAggregator;
use ACPT\Utils\Wordpress\Translator;

class RepeaterFieldGenerator
{
    /**
     * @var AbstractMetaBoxFieldModel
     */
    private $parentFieldModel;

    /**
     * @var array
     */
    private $data;

    /**
     * @var int
     */
    private $dataId;

    /**
     * RepeaterFieldGenerator constructor.
     *
     * @param AbstractMetaBoxFieldModel $parentFieldModel
     */
    public function __construct(AbstractMetaBoxFieldModel $parentFieldModel)
    {
        $this->parentFieldModel = $parentFieldModel;
    }

    /**
     * @param array $data
     */
    public function setData( $data )
    {
        $this->data = $data;
    }

    /**
     * @param int $dataId
     */
    public function setDataId( $dataId )
    {
        $this->dataId = $dataId;
    }

    /**
     * @param null $generatedIndex
     *
     * @return string
     * @throws \Exception
     */
    public function generate($generatedIndex = null)
    {
        if(!empty($this->data)){

            $elements = '';

            foreach ( DataAggregator::aggregateNestedFieldsData($this->data) as $index => $data){
                $elements .= $this->generateElement($index, $data);
            }

            return $elements;
        }

        if(null === $generatedIndex){
            throw new \Exception('Missing generated index');
        }

        return $this->generateElement($generatedIndex, []);
    }

    /**
     * @param       $elementIndex
     * @param array $data
     *
     * @return string
     */
    private function generateElement($elementIndex, array $data = [])
    {
        $id = 'element-'.rand(999999,111111);

        $return = '<li id="'.$id.'" draggable="true" class="sortable-li">
                <div class="handle">
                    .<br/>.<br/>.
                </div>
                <div class="sortable-content">';

        foreach ($this->parentFieldModel->getChildren() as $index => $child){
            $value = $this->getDafaultValue($data, $child->getNormalizedName());
            $repeaterField = $this->getCustomPostTypeRepeaterField($child, $elementIndex, $value);
            $return .= $repeaterField->render();
        }

        $return .= '</div>
                <a class="button small button-danger remove-grouped-element" data-element="element" data-elements="elements" data-target-id="'.$id.'" href="#">'.Translator::translate('Remove element').'</a>
            </li>';

        return $return;
    }

    /**
     * @param $data
     * @param $key
     *
     * @return string
     */
    private function getDafaultValue($data, $key)
    {
        if(empty($data)){
            return null;
        }

        foreach ($data as $datum){
            if($key === $datum['key']){
                return $datum['value'];
            }
        }

        return null;
    }

    /**
     * @param AbstractMetaBoxFieldModel $fieldModel
     * @param int                       $index
     * @param null                      $value
     *
     * @return RepeaterFieldInterface
     */
    private function getCustomPostTypeRepeaterField(AbstractMetaBoxFieldModel $fieldModel, $index, $value = null)
    {
        $className = 'ACPT\\Core\\Generators\\RepeaterFields\\'.$fieldModel->getType().'Field';

        return new $className(
                $this->parentFieldModel,
                $fieldModel,
                $index,
                $this->dataId,
                $value
        );
    }
}