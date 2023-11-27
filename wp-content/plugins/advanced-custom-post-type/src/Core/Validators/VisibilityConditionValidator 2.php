<?php

namespace ACPT\Core\Validators;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Repository\MetaRepository;

class VisibilityConditionValidator
{
    /**
     * @param AbstractMetaBoxFieldModel $metaField
     * @param array $visibilityConditions
     *
     * @return bool
     * @throws \Exception
     */
    public static function validate(AbstractMetaBoxFieldModel $metaField, array $visibilityConditions = [])
    {
        if(empty($visibilityConditions)){
            return true;
        }

        $allowedValueTypes = [
            AbstractMetaBoxFieldModel::NUMBER_TYPE,
            AbstractMetaBoxFieldModel::TEXT_TYPE,
            AbstractMetaBoxFieldModel::TEXTAREA_TYPE,
            AbstractMetaBoxFieldModel::CHECKBOX_TYPE,
            AbstractMetaBoxFieldModel::RADIO_TYPE,
            AbstractMetaBoxFieldModel::SELECT_TYPE,
            AbstractMetaBoxFieldModel::SELECT_MULTI_TYPE,
            AbstractMetaBoxFieldModel::DATE_TYPE,
            AbstractMetaBoxFieldModel::TIME_TYPE,
            AbstractMetaBoxFieldModel::URL_TYPE,
            AbstractMetaBoxFieldModel::PHONE_TYPE,
            AbstractMetaBoxFieldModel::EMAIL_TYPE,
            AbstractMetaBoxFieldModel::COLOR_TYPE,
            AbstractMetaBoxFieldModel::CURRENCY_TYPE,
            AbstractMetaBoxFieldModel::WEIGHT_TYPE,
            AbstractMetaBoxFieldModel::LENGTH_TYPE,
            AbstractMetaBoxFieldModel::TOGGLE_TYPE,
        ];

        foreach ($visibilityConditions as $index => $visibilityCondition){

            $isLast = $index === (count($visibilityConditions)-1);
            $typeEnum = $visibilityCondition['type']['type'];
            $typeValue = $visibilityCondition['type']['value'];
            $operator = isset($visibilityCondition['operator']) ? $visibilityCondition['operator'] : '=';
            $value = $visibilityCondition['value'];

            $metaFieldPostType = null;
            $metaFieldTaxonomy = null;

            if($metaField->getMetaBox()->belongsToCustomPostType()){
                $metaFieldPostType = $metaField->getMetaBox()->getPostType();
            }

            if($metaField->getMetaBox()->belongsToTaxonomy()){
                $metaFieldTaxonomy = $metaField->getMetaBox()->getTaxonomy();
            }

            if(!$isLast and empty($visibilityCondition['logic'])){
                throw new \Exception('Missing logic [AND/OR]');
            }

            // validate VALUE
            if($typeEnum === 'VALUE'){
                self::validateValue($metaField, $operator, $allowedValueTypes);
            }

            // validate POST_ID
            if($typeEnum === 'POST_ID'){

                $values = trim($value);
                $values = explode(",", $values);

                $allowedOperators = [
                    '=',
                    '!=',
                    'IN',
                    'NOT_IN',
                ];

                if(!in_array($operator, $allowedOperators)){
                    throw new \Exception($operator . ' operator is not allowed for this field');
                }

                foreach ($values as $value){
                    if($metaField->getMetaBox()->belongsToCustomPostType() and $metaFieldPostType !== null){
                        $postType = get_post_type($value);

                        if($postType === null){
                            throw new \Exception($value . ' is not a valid post ID');
                        }

                        if($postType !== $metaFieldPostType){
                            throw new \Exception('The post with ID ' . $value . ' is not a `' . $postType . '` post type');
                        }
                    }
                }
            }

            // validate TERM_ID
	        if($typeEnum === 'TERM_ID'){

		        $values = trim($value);
		        $values = explode(",", $values);

		        $allowedOperators = [
			        '=',
			        '!=',
			        'IN',
			        'NOT_IN',
		        ];

		        if(!in_array($operator, $allowedOperators)){
			        throw new \Exception($operator . ' operator is not allowed for this field');
		        }

		        foreach ($values as $value){
			        if($metaField->getMetaBox()->belongsToTaxonomy() and $metaFieldTaxonomy !== null){
				        $term = get_term($value);

				        if($term === null){
					        throw new \Exception($value . ' is not a valid term ID');
				        }

				        if($term->taxonomy !== $metaFieldTaxonomy){
					        throw new \Exception('The term with ID ' . $value . ' is not a `' . $term->taxonomy . '` taxonomy');
				        }
			        }
		        }
	        }

            // validate TAXONOMY
            if($typeEnum === 'TAXONOMY'){

                $allowedOperators = [
                    'EMPTY',
                    'NOT_EMPTY',
                    'IN',
                    'NOT_IN',
                ];

                if(!in_array($operator, $allowedOperators)){
                    throw new \Exception($operator . ' operator is not allowed for this field');
                }

                $values = trim($value);
                $values = explode(",", $values);

                foreach ($values as $value){
                    $term = get_term($value);

                    if($term === null){
                        throw new \Exception($value . ' id not a valid term ID');
                    }

                    $termIds = [];

                    if($metaField->getMetaBox()->belongsToCustomPostType()){
                        // get taxonomy terms for this custom post type
                        $taxonomies = get_object_taxonomies($metaFieldPostType, 'objects');

                        if(empty($taxonomies)){
                            throw new \Exception($metaFieldPostType . ' does not have any associated taxonomy');
                        }

                        // get the terms for the taxonomy
                        foreach ($taxonomies as $taxonomy){
                        	if($taxonomy->public){
		                        if($metaFieldPostType === 'post'){
			                        $taxonomyTerms = get_categories([
				                        'taxonomy' => $taxonomy->name,
				                        'hide_empty' => false,
			                        ]);
		                        } else {
			                        $taxonomyTerms = get_terms([
				                        'taxonomy' => $taxonomy->name,
				                        'hide_empty' => false,
			                        ]);
		                        }

		                        foreach ($taxonomyTerms as $taxonomyTerm){
			                        $termIds[] = $taxonomyTerm->term_id;
		                        }

		                        if(!in_array($term->term_id, $termIds)){
			                        throw new \Exception($term->term_id . ' does not belong to a Taxonomy belonging to this Custom post type');
		                        }
	                        }
                        }
                    }

                    if($metaField->getMetaBox()->belongsToTaxonomy()){

                        if($metaFieldTaxonomy === 'category'){
                            $taxonomyTerms = get_categories([
                                'taxonomy' => $metaFieldTaxonomy,
                                'hide_empty' => false,
                            ]);
                        } else {
                            $taxonomyTerms = get_terms([
                                'taxonomy' => $metaFieldTaxonomy,
                                'hide_empty' => false,
                            ]);
                        }

                        foreach ($taxonomyTerms as $taxonomyTerm){
                            $termIds[] = $taxonomyTerm->term_id;
                        }

                        if(!in_array($term->term_id, $termIds)){
                            throw new \Exception($term->term_id . ' does not belong to a Taxonomy belonging to this Custom post type');
                        }
                    }
                }
            }

            // validate OTHER_FIELDS
            if($typeEnum === 'OTHER_FIELDS'){

                $otherMetaField = MetaRepository::getMetaField([
                    'belongsTo' => $metaField->getMetaBox()->metaType(),
                    'find' => ($metaFieldPostType) ? $metaFieldPostType : $metaFieldTaxonomy,
                    'id' => $typeValue,
                    'lazy' => true,
                ]);

                if($otherMetaField === null){
                    throw new \Exception('Related meta field not found');
                }

                self::validateValue($otherMetaField, $operator, $allowedValueTypes);
            }
        }
    }

    /**
     * @param AbstractMetaBoxFieldModel $metaField
     * @param string            $operator
     * @param array             $allowedValueTypes
     *
     * @throws \Exception
     */
    private static function validateValue(AbstractMetaBoxFieldModel $metaField, $operator, $allowedValueTypes)
    {
        $fieldType = $metaField->getType();

        $numericTypes = [
            AbstractMetaBoxFieldModel::NUMBER_TYPE,
            AbstractMetaBoxFieldModel::CURRENCY_TYPE,
            AbstractMetaBoxFieldModel::WEIGHT_TYPE,
            AbstractMetaBoxFieldModel::LENGTH_TYPE,
            AbstractMetaBoxFieldModel::DATE_TYPE,
            AbstractMetaBoxFieldModel::TIME_TYPE,
        ];

        $toggleTypes = [
            AbstractMetaBoxFieldModel::TOGGLE_TYPE,
        ];

        if(!in_array($fieldType, $allowedValueTypes)){
            throw new \Exception($fieldType . ' type is not allowed');
        }

        if(in_array($fieldType, $numericTypes)){
            $allowedOperators = [
                '=',
                '!=',
                '<',
                '>',
                '<=',
                '>=',
                'LIKE',
                'NOT_LIKE',
                'BLANK',
                'NOT_BLANK',
            ];
        } elseif (in_array($fieldType, $toggleTypes)) {
            $allowedOperators = [
                'CHECKED',
                'NOT_CHECKED',
            ];
        } else {
            $allowedOperators = [
                '=',
                '!=',
                'LIKE',
                'NOT_LIKE',
                'BLANK',
                'NOT_BLANK',
            ];
        }

        if(!in_array($operator, $allowedOperators)){
            throw new \Exception($operator . ' operator is not allowed for this field');
        }
    }
}