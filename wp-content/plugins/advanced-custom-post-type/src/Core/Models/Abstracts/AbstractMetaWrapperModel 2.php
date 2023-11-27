<?php

namespace ACPT\Core\Models\Abstracts;

use ACPT\Core\Models\Template\TemplateModel;

/**
 * AbstractModel
 *
 * @since      1.0.140
 * @package    advanced-custom-post-type
 * @subpackage advanced-custom-post-type/core
 * @author     Mauro Cassani <maurocassani1978@gmail.com>
 */
abstract class AbstractMetaWrapperModel extends AbstractModel
{
    /**
     * @var AbstractMetaBoxModel[]
     */
    protected $metaBoxes = [];

    /**
     * @var TemplateModel[]
     */
    protected $templates = [];

    /**
     * @param TemplateModel $template
     */
    public function addTemplate(TemplateModel $template)
    {
        if(!$this->existsInCollection($template->getId(), $this->templates)){
            $this->templates[] = $template;
        }
    }

    /**
     * @param TemplateModel $template
     */
    public function removeTemplate(TemplateModel $template)
    {
        $this->removeFromCollection($template->getId(), $this->templates);
    }

    /**
     * @return TemplateModel[]
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * @return bool
     */
    public function hasArchiveTemplatePage()
    {
        foreach ($this->templates as $template){
            if($template->getTemplateType() === 'archive'){
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasSingleTemplatePage()
    {
        foreach ($this->templates as $template){
            if($template->getTemplateType() === 'single'){
                return true;
            }
        }

        return false;
    }

    /**
     * @return AbstractMetaBoxModel[]
     */
    public function getMetaBoxes()
    {
        return $this->metaBoxes;
    }

    /**
     * @param AbstractMetaBoxModel $metaBox
     */
    public function addMetaBox(AbstractMetaBoxModel $metaBox)
    {
        if(!$this->existsInCollection($metaBox->getId(), $this->metaBoxes)){
            $this->metaBoxes[] = $metaBox;
        }
    }

    /**
     * @param AbstractMetaBoxModel $metaBox
     */
    public function removeMetaBox(AbstractMetaBoxModel $metaBox)
    {
        $this->removeFromCollection($metaBox->getId(), $this->metaBoxes);
    }

	/**
	 * Used by export function
	 *
	 * @return array
	 */
    public abstract function arrayRepresentation();

    /**
     * @param string $format
     * @return array
     */
    protected function metaArrayRepresentation($format = 'full')
    {
        $metaArray = [];
        foreach ($this->getMetaBoxes() as $metaBoxModel){

            if($format === 'mini'){
                $metaArray[] = [
                    "name" => $metaBoxModel->getName(),
                    "count" => count($metaBoxModel->getFields()),
                ];
            }

            if($format === 'full'){
                $fieldsArray = [];

                foreach ($metaBoxModel->getFields() as $fieldModel){

                    $visibilityConditionsArray = [];
                    $optionsArray = [];
                    $relationsArray = [];

                    foreach ($fieldModel->getOptions() as $optionModel){
                        $optionsArray[] = [
                            'id' => $optionModel->getId(),
                            'label' => $optionModel->getLabel(),
                            'value' => $optionModel->getValue(),
                            'sort' => (int)$optionModel->getSort(),
                        ];
                    }

                    foreach ($fieldModel->getVisibilityConditions() as $visibilityCondition){
                        $visibilityConditionsArray[] = [
                            'id' => $visibilityCondition->getId(),
                            'type' => $visibilityCondition->getType(),
                            'operator' => $visibilityCondition->getOperator(),
                            'value' => $visibilityCondition->getValue(),
                            'logic' => $visibilityCondition->getLogic(),
                            'sort' => (int)$visibilityCondition->getSort(),
                        ];
                    }

                    foreach ($fieldModel->getRelations() as $relationModel){
                        $relationsArray[] = [
                            'id' => $relationModel->id,
                            'boxId' => $relationModel->getMetaBoxField()->getMetaBox()->getId(),
                            'fieldId' => $relationModel->getMetaBoxField()->getId(),
                            'type' => $relationModel->getRelationship(),
                            'relatedPostType' => ($relationModel->getRelatedCustomPostType() !== null) ? $relationModel->getRelatedCustomPostType()->getName() : null,
                            'inversedBoxId' => ($relationModel->getInversedBy() !== null) ? $relationModel->getInversedBy()->getMetaBox()->getId() : null,
                            'inversedBoxName' => ($relationModel->getInversedBy() !== null) ? $relationModel->getInversedBy()->getMetaBox()->getName() : null,
                            'inversedFieldName' => ($relationModel->getInversedBy() !== null) ? $relationModel->getInversedBy()->getName() : null,
                            'inversedFieldId' => ($relationModel->getInversedBy() !== null) ? $relationModel->getInversedBy()->getId() : null,
                        ];
                    }

                    $fieldsArray[] = [
                        'id' => $fieldModel->getId(),
                        'name' => $fieldModel->getName(),
                        'type' => $fieldModel->getType(),
                        'defaultValue' => $fieldModel->getDefaultValue(),
                        'description' => $fieldModel->getDescription(),
                        'showInArchive' => (bool)$fieldModel->isShowInArchive(),
                        'required' => (bool)$fieldModel->isRequired(),
                        'sort' => (int)$fieldModel->getSort(),
                        'options' => $optionsArray,
                        'relations' => $relationsArray,
                        'visibilityConditions' => $visibilityConditionsArray,
                    ];
                }

                if($metaBoxModel->belongsToTaxonomy()){
	                $metaArray[] = [
		                "id" => $metaBoxModel->getId(),
		                "taxonomy" => $metaBoxModel->getTaxonomy(),
		                "name" => $metaBoxModel->getName(),
		                "sort" => (int)$metaBoxModel->getSort(),
		                "fields" => $fieldsArray
	                ];
                }

	            if($metaBoxModel->belongsToOptionPage()){
		            $metaArray[] = [
			            "id" => $metaBoxModel->getId(),
			            "optionPage" => $metaBoxModel->getOptionPage(),
			            "name" => $metaBoxModel->getName(),
			            "sort" => (int)$metaBoxModel->getSort(),
			            "fields" => $fieldsArray
		            ];
	            }

	            if($metaBoxModel->belongsToCustomPostType()){
		            $metaArray[] = [
			            "id" => $metaBoxModel->getId(),
			            "postType" => $metaBoxModel->getPostType(),
			            "name" => $metaBoxModel->getName(),
			            "sort" => (int)$metaBoxModel->getSort(),
			            "fields" => $fieldsArray
		            ];
	            }

	            if($metaBoxModel->belongsToUser()){
		            $metaArray[] = [
			            "id" => $metaBoxModel->getId(),
			            "name" => $metaBoxModel->getName(),
			            "sort" => (int)$metaBoxModel->getSort(),
			            "fields" => $fieldsArray
		            ];
	            }
            }
        }

        return $metaArray;
    }
}