<?php

namespace ACPT\Core\Generators\CustomPostTypeFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldRelationshipModel;
use ACPT\Costants\MetaTypes;
use ACPT\Costants\RelationCostants;
use ACPT\Costants\Relationships;
use ACPT\Includes\ACPT_DB;
use ACPT\Utils\Wordpress\Translator;
use WP_Query;

class PostField extends AbstractCustomPostTypeField implements MetaFieldInterface
{
    const LABEL_SEPARATOR = '<-------->';
    const SEPARATOR = '_######SEP######';

    public function render()
    {
    	$display = $this->getAdvancedOption('related_post_selector_display');
        $relation = $this->relations[0];

        $icon = 'bx:bx-repost';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.CustomPostTypeMetaBoxFieldModel::POST_TYPE.'">';

        $postId = (isset($_GET['post'])) ? $_GET['post'] : null;
        $isMulti = $this->isMulti($relation['relationship']) ? 'multiple' : '';
        $options = $this->getOptions($relation['related_entity']['value'], $postId, $display);
        $fieldName = ($isMulti) ? esc_attr($this->getIdName()).'[]' : esc_attr($this->getIdName());
        $defaultValue = $this->getDefaultValueFromDB($this->isMulti($relation['relationship']));

        if(isset($relation['inversedBy']) and null !== $relation['inversedBy']){

            $inversedBy = $relation['inversedBy'];
            $inversedIdName = $this->getInversedIdName($inversedBy['box'], $inversedBy['field']);
            $defaultValues = $this->getDefaultValue();
	        $defaultValues = (is_array($defaultValues)) ? implode(',', $defaultValues) : $defaultValues;

            $field .= '<input type="hidden" name="meta_fields[]" value="'. esc_attr($inversedIdName).RelationCostants::RELATION_KEY.'">';
            $field .= '<input type="hidden" id="inversedBy" name="'. esc_attr($inversedIdName).RelationCostants::RELATION_KEY.'" value="'.esc_attr($defaultValues).'">';
            $field .= '<input type="hidden" id="inversedBy_original_values" name="'. esc_attr($inversedIdName).RelationCostants::RELATION_KEY.'_original_values" value="'.esc_attr($defaultValues).'">';
        }

	    switch ($display){
		    case 'radio_checkbox':
			    $field .= $this->renderAsCheckBoxOrRadio($isMulti, $fieldName, $options, $defaultValue);
			    break;

		    case 'search_box':
			    $field .= $this->renderAsSearchbox($isMulti, $fieldName, $options, $defaultValue);
			    break;

		    case 'select':
		    default:
			    $field .= $this->renderAsSelect($isMulti, $fieldName, $options, $defaultValue);
			    break;
	    }

        echo $this->renderField($icon, $field, $relation['relationship']);
    }

	/**
	 * Render the field as select
	 *
	 * @param $isMulti
	 * @param $fieldName
	 * @param $options
	 * @param $defaultValue
	 *
	 * @return string
	 */
	private function renderAsSearchbox($isMulti, $fieldName, $options, $defaultValue)
	{
		$field = '<select '.$isMulti.' '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. $fieldName.'" class="acpt-select2 post-relationship acpt-admin-meta-field-input">';
		$field .= '<option value="">'.Translator::translate("--Select--").'</option>';

		foreach ($options as $option){
			$selected = '';

			if($isMulti){
				if(is_array($defaultValue)){
					$selected = (in_array($option['value'], $defaultValue)) ? 'selected="selected"' : '';
				}
			} else {
				$selected = ($option['value'] == $defaultValue) ? 'selected="selected"' : '';
			}

			$field .= '<option '.$selected.' value="'.esc_attr($option['value']).'">'.esc_attr($option['label']).'</option>';
		}

		$field .= '</select>';

		return $field;
	}

	/**
	 * Render the field as select
	 *
	 * @param $isMulti
	 * @param $fieldName
	 * @param $options
	 * @param $defaultValue
	 *
	 * @return string
	 */
    private function renderAsSelect($isMulti, $fieldName, $options, $defaultValue)
    {
	    $field = '<select '.$isMulti.' '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. $fieldName.'" class="post-relationship acpt-admin-meta-field-input">';
	    $field .= '<option value="">'.Translator::translate("--Select--").'</option>';

	    foreach ($options as $option){
		    $selected = '';

		    if($isMulti){
			    if(is_array($defaultValue)){
				    $selected = (in_array($option['value'], $defaultValue)) ? 'selected="selected"' : '';
			    }
		    } else {
			    $selected = ($option['value'] == $defaultValue) ? 'selected="selected"' : '';
		    }

		    $field .= '<option '.$selected.' value="'.esc_attr($option['value']).'">'.esc_attr($option['label']).'</option>';
	    }

	    $field .= '</select>';

	    return $field;
    }

	/**
	 * @param $isMulti
	 * @param $fieldName
	 * @param $options
	 * @param $defaultValue
	 *
	 * @return string
	 */
    private function renderAsCheckBoxOrRadio($isMulti, $fieldName, $options, $defaultValue)
    {
	    $field = '<div class="acpt_checkboxes">';

	    foreach ($options as $optionIndex => $option){
		    $selected = '';

		    if($isMulti){
			    if(is_array($defaultValue)){
				    $selected = (in_array($option['value'], $defaultValue)) ? 'checked="checked"' : '';
			    }
		    } else {
			    $selected = ($option['value'] == $defaultValue) ? 'checked="checked"' : '';
		    }

		    $fieldId = $this->getIdName().'_'.$optionIndex;
		    $fieldName = $isMulti ? $fieldName.'[]' : $fieldName;
		    $inputType = $isMulti ? 'checkbox' : 'radio';
		    $label = explode(self::LABEL_SEPARATOR, $option['label']);

		    $field .= '<div class="item">
				<input id="'.esc_attr($fieldId).'" '.$this->required().' type="'.$inputType.'" name="'.$fieldName.'" '.$selected.' value="'.esc_attr($option['value']).'"/>
				<label for="'.esc_attr($fieldId).'"><span class="related-post"><span class="cpt">'.$label[0].'</span><span class="value">'.$label[1].'</span></span></label>
				</div>';
	    }

	    $field .= '</div>';

	    return $field;
    }

    /**
     * Get the real default value
     * (can be an array or a string)
     *
     * @param $isMulti
     *
     * @return mixed
     */
    private function getDefaultValueFromDB( $isMulti)
    {
        $defaultValue = $this->getDefaultValue();

        if( is_array( $defaultValue)  ){
            if($isMulti){
                return $defaultValue;
            }

            return $defaultValue[0];
        }

        return $defaultValue;
    }

    /**
     * @param $box
     * @param $field
     *
     * @return string
     */
    private function getInversedIdName($box, $field)
    {
        return Strings::toDBFormat($box) . self::SEPARATOR . Strings::toDBFormat($field);
    }

    /**
     * @param string $relationship
     *
     * @return bool
     */
    private function isMulti($relationship)
    {
        return (
            $relationship === Relationships::ONE_TO_MANY_UNI or
            $relationship === Relationships::ONE_TO_MANY_BI or
            $relationship === Relationships::MANY_TO_MANY_UNI or
            $relationship === Relationships::MANY_TO_MANY_BI
        );
    }

    /**
     * @param string $postType
     * @param null   $postId
     * @param null   $display
     *
     * @return array
     */
    private function getOptions($postType, $postId = null, $display = null)
    {
        $options = [];

        $queryArgs = [
	        'post_type' => $postType,
	        'post_status' => 'publish',
	        'orderby' => 'title',
	        'order' => 'ASC',
	        'posts_per_page' => -1
        ];

	    if($postId){
		    $queryArgs['post__not_in'] = [(int)$postId];
	    }

	    $query = new WP_Query($queryArgs);

	    while ( $query->have_posts() ) {
		    $query->the_post();

		    $id = get_the_ID();
		    $postTitle = get_the_title();
		    $label = $postTitle;

		    switch ($display){
			    case 'select':
				    $label = $postTitle;
				    break;

			    case 'radio_checkbox':
				    $label =  $postType . self::LABEL_SEPARATOR . $postTitle;
				    break;

			    case 'search_box':
				    $thumbnail = get_the_post_thumbnail_url($id);
				    $label =  $thumbnail . self::LABEL_SEPARATOR.$postType . self::LABEL_SEPARATOR . $postTitle;
				    break;
		    }

		    $options[] = [
			    'value' => $id,
			    'label' => $label,
		    ];

	    }

        return $options;
    }
}
