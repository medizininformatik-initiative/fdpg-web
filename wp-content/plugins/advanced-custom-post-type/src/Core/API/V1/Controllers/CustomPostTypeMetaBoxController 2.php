<?php

namespace ACPT\Core\API\V1\Controllers;

use ACPT\Core\Helper\Uuid;
use ACPT\Core\JSON\CustomPostTypeMetaBoxSchema;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldAdvancedOptionModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldBlockModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldOptionModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldRelationshipModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldVisibilityModel;
use ACPT\Core\Repository\CustomPostTypeRepository;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Core\Validators\VisibilityConditionValidator;
use ACPT\Costants\MetaTypes;
use ACPT\Utils\Data\Sanitizer;

class CustomPostTypeMetaBoxController extends AbstractController
{
    /**
     * @param \WP_REST_Request $request
     * @return mixed
     */
    public function getByPostSlug(\WP_REST_Request $request)
    {
        $slug = $request['slug'];

        try {
            $meta = MetaRepository::get([
                'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
                'find' => $slug
            ]);

            if(null === $meta){
                return $this->jsonNotFoundResponse('Not records found');
            }

            return $this->jsonResponse($meta);

        } catch (\Exception $exception){
            return $this->jsonErrorResponse($exception);
        }
    }

    /**
     * @param \WP_REST_Request $request
     * @return array|mixed
     */
    public function get(\WP_REST_Request $request)
    {
        $id = $request['id'];
        $slug = $request['slug'];

        try {
            $meta = MetaRepository::get([
                'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
                'find' => $slug,
                'id' => $id,
            ]);

            if(null === $meta){
                return $this->jsonNotFoundResponse('Not records found');
            }
            return $this->jsonResponse($meta[0]);

        } catch (\Exception $exception){
            return $this->jsonErrorResponse($exception);
        }
    }

    /**
     * Create meta boxes
     *
     * @param \WP_REST_Request $request
     * @return mixed
     */
    public function create(\WP_REST_Request $request)
    {
        $ids = [];
        $slug = $request['slug'];
        $data = $this->getDecodedRequest($request);

        if(empty($data)){
            return $this->jsonResponse([
                'message' => 'empty request body'
            ], 500);
        }

        if(!is_array($data)){
            return $this->jsonResponse([
                'message' => 'data is not an array'
            ], 500);
        }

        try {
            $meta = MetaRepository::get([
                'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
                'find' => $slug,
            ]);

            if(!empty($meta)){
                return $this->jsonResponse([
                    'message' => 'This post type already has associated meta boxes'
                ], 500);
            }

            foreach ($data as $index => $datum){
                $this->saveMetaBox($index, $datum, $slug, $ids);
            }

            return $this->jsonResponse([
                'ids' => $ids
            ], 201);

        } catch (\Exception $exception){
            return $this->jsonErrorResponse($exception);
        }
    }

    /**
     * Save meta boxes
     *
     * @param \WP_REST_Request $request
     * @return mixed
     */
    public function update(\WP_REST_Request $request)
    {
        $ids = [];
        $slug = $request['slug'];
        $data = $this->getDecodedRequest($request);

        if(empty($data)){
            return $this->jsonResponse([
                'message' => 'empty request body'
            ], 500);
        }

        if(!is_array($data)){
            return $this->jsonResponse([
                'message' => 'data is not an array'
            ], 500);
        }

        try {
            $meta = MetaRepository::get([
                'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
                'find' => $slug,
            ]);

            if(empty($meta)){
                return $this->jsonResponse([
                    'message' => 'This post type has not associated meta boxes'
                ], 500);
            }

            foreach ($data as $index => $datum){
                $this->saveMetaBox($index, $datum, $slug, $ids);
            }

            return $this->jsonResponse([
                'ids' => $ids
            ], 200);

        } catch (\Exception $exception){
            return $this->jsonErrorResponse($exception);
        }
    }

    /**
     * @param $index
     * @param $datum
     * @param $slug
     * @param $ids
     * @return mixed
     * @throws \Exception
     */
    private function saveMetaBox($index, $datum, $slug, &$ids)
    {
        // validate data
        $this->validateJSONSchema($datum, new CustomPostTypeMetaBoxSchema());

        // sanitize data
        $datum = Sanitizer::recursiveSanitizeRawData($datum);

        $id = isset($datum["id"]) ? $datum["id"] : Uuid::v4();

        $metaBoxModel = CustomPostTypeMetaBoxModel::hydrateFromArray([
            'id' => $id,
            'postType' => $slug,
            'name' => $datum["title"],
            'sort' => ($index+1),
        ]);

	    if(isset($datum['label']) and !empty($datum['label'])){
		    $metaBoxModel->changeLabel($datum['label']);
	    }

        $ids[$slug]['boxes'][] = $id;

        foreach ($datum["fields"] as $fieldIndex => $field){

            $fieldId = isset($field["id"]) ? $field["id"] : Uuid::v4();

            $metaBoxFieldModel = CustomPostTypeMetaBoxFieldModel::hydrateFromArray([
                'id' => $fieldId,
                'metaBox' => $metaBoxModel,
                'title' => $field['name'],
                'type' => $field['type'],
                'showInArchive' => $field['showInArchive'],
                'required' => $field['isRequired'],
                'sort' => ($fieldIndex+1),
                'defaultValue' => $field['defaultValue'],
                'description' => $field['description'],
            ]);

            $ids[$slug]['fields'][] = $fieldId;

            if($field['type'] === CustomPostTypeMetaBoxFieldModel::REPEATER_TYPE and !empty($field['children'])){

                foreach ($field['children'] as $childIndex => $child){

                    $childFieldId = isset($child["id"]) ? $child["id"] : Uuid::v4();

                    $metaBoxChildFieldModel = CustomPostTypeMetaBoxFieldModel::hydrateFromArray([
                        'id' => $childFieldId,
                        'metaBox' => $metaBoxModel,
                        'title' => $child['name'],
                        'type' => $child['type'],
                        'showInArchive' => $child['showInArchive'],
                        'required' => $child['isRequired'],
                        'sort' => ($childIndex+1),
                        'defaultValue' => $child['defaultValue'],
                        'description' => $child['description'],
                    ]);

                    if($child['type'] === CustomPostTypeMetaBoxFieldModel::SELECT_TYPE or $child['type'] === CustomPostTypeMetaBoxFieldModel::SELECT_MULTI_TYPE){
                        foreach ($child['options'] as $optionIndex => $option){

                            $optionId = isset($option["id"]) ? $option["id"] : Uuid::v4();

                            $metaBoxFieldOptionModel = MetaBoxFieldOptionModel::hydrateFromArray([
                                    'id' => $optionId,
                                    'metaBoxField' => $metaBoxFieldModel,
                                    'label' => $option['label'],
                                    'value' => $option['value'],
                                    'sort' => ($optionIndex+1)
                            ]);

                            $ids[$slug]['options'][] = $optionId;
                            $metaBoxChildFieldModel->addOption($metaBoxFieldOptionModel);
                        }
                    }

                    $metaBoxChildFieldModel->setParentId($fieldId);
                    $metaBoxFieldModel->addChild($metaBoxChildFieldModel);

                    $ids[$slug]['fields'][] = $childFieldId;
                }
            }

	        if($field['type'] === CustomPostTypeMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE and isset($field['blocks'])){
		        foreach ($field['blocks'] as $blockIndex => $block){
			        $blockId = isset($block["id"]) ? $block["id"] : Uuid::v4();

			        $metaBoxFieldBlockModel = MetaBoxFieldBlockModel::hydrateFromArray([
				        'id' => $blockId,
				        'metaBoxField' => $metaBoxFieldModel,
				        'label' => $block['label'],
				        'name' => $block['name'],
				        'sort' => ($blockIndex+1)
			        ]);

			        // add fields
			        if(isset($block['fields'])){
			        	foreach ($block['fields'] as $nestedFieldIndex => $nestedField){

					        $nestedFieldId = isset($nestedField["id"]) ? $nestedField["id"] : Uuid::v4();

					        $metaBoxNestedFieldModel = CustomPostTypeMetaBoxFieldModel::hydrateFromArray([
						        'id' => $nestedFieldId,
						        'metaBox' => $metaBoxModel,
						        'title' => $nestedField['name'],
						        'type' => $nestedField['type'],
						        'showInArchive' => false,
						        'required' => $nestedField['isRequired'],
						        'sort' => ($nestedFieldIndex+1),
						        'defaultValue' => $nestedField['defaultValue'],
						        'description' => $nestedField['description'],
					        ]);

					        if($nestedField['type'] === CustomPostTypeMetaBoxFieldModel::SELECT_TYPE or $nestedField['type'] === CustomPostTypeMetaBoxFieldModel::SELECT_MULTI_TYPE){
						        foreach ($nestedField['options'] as $optionIndex => $option){

							        $optionId = isset($option["id"]) ? $option["id"] : Uuid::v4();

							        $metaBoxFieldOptionModel = MetaBoxFieldOptionModel::hydrateFromArray([
								        'id' => $optionId,
								        'metaBoxField' => $metaBoxFieldModel,
								        'label' => $option['label'],
								        'value' => $option['value'],
								        'sort' => ($optionIndex+1)
							        ]);

							        $ids[$slug]['options'][] = $optionId;
							        $metaBoxNestedFieldModel->addOption($metaBoxFieldOptionModel);
						        }
					        }

					        if(isset($nestedField['visibilityConditions'])){
						        VisibilityConditionValidator::validate($metaBoxFieldModel, $nestedField['visibilityConditions']);

						        foreach ($nestedField['visibilityConditions'] as $visibilityIndex => $visibility) {

							        $visibilityId = isset($visibility["id"]) ? $visibility["id"] : Uuid::v4();

							        $visibilityConditionModel = MetaBoxFieldVisibilityModel::hydrateFromArray([
								        'id' => $visibilityId,
								        'type' => $visibility['type'],
								        'value' => $visibility['value'],
								        'operator' => $visibility['operator'],
								        'logic' => (isset($visibility['logic'])) ? $visibility['logic'] : null,
								        'sort' => ($visibilityIndex+1),
								        'metaBoxField' => $metaBoxFieldModel
							        ]);

							        $ids[$slug]['visibilityConditions'][] = $visibilityId;
							        $metaBoxNestedFieldModel->addVisibilityCondition($visibilityConditionModel);
						        }
					        }

					        if(isset($nestedField['advancedOptions'])){
						        foreach ($nestedField['advancedOptions'] as $advancedOptionIndex => $advancedOption) {
							        $advancedOptionId = isset($advancedOption["id"]) ? $advancedOption["id"] : Uuid::v4();

							        $advancedOptionModel = MetaBoxFieldAdvancedOptionModel::hydrateFromArray([
								        'id' => $advancedOptionId,
								        'metaBoxField' => $metaBoxFieldModel,
								        'key' => $advancedOption['key'],
								        'value' => $advancedOption['value'],
							        ]);

							        $ids[$slug]['advancedOptions'][] = $advancedOptionModel;
							        $metaBoxNestedFieldModel->addAdvancedOption($advancedOptionModel);
						        }
					        }

					        $ids[$slug]['fields'][] = $nestedFieldId;
					        $metaBoxFieldBlockModel->addField($metaBoxNestedFieldModel);
				        }
			        }

			        $ids[$slug]['blocks'][] = $blockId;
			        $metaBoxFieldModel->addBlock($metaBoxFieldBlockModel);
		        }
	        }

            if($field['type'] === CustomPostTypeMetaBoxFieldModel::SELECT_TYPE or $field['type'] === CustomPostTypeMetaBoxFieldModel::SELECT_MULTI_TYPE){
                foreach ($field['options'] as $optionIndex => $option){

                    $optionId = isset($option["id"]) ? $option["id"] : Uuid::v4();

                    $metaBoxFieldOptionModel = MetaBoxFieldOptionModel::hydrateFromArray([
                        'id' => $optionId,
                        'metaBoxField' => $metaBoxFieldModel,
                        'label' => $option['label'],
                        'value' => $option['value'],
                        'sort' => ($optionIndex+1)
                    ]);

                    $ids[$slug]['options'][] = $optionId;
                    $metaBoxFieldModel->addOption($metaBoxFieldOptionModel);
                }
            }

            if(isset($field['visibilityConditions'])){
	            VisibilityConditionValidator::validate($metaBoxFieldModel, $field['visibilityConditions']);

	            foreach ($field['visibilityConditions'] as $visibilityIndex => $visibility) {

		            $visibilityId = isset($visibility["id"]) ? $visibility["id"] : Uuid::v4();

		            $visibilityConditionModel = MetaBoxFieldVisibilityModel::hydrateFromArray([
			            'id' => $visibilityId,
			            'type' => $visibility['type'],
			            'value' => $visibility['value'],
			            'operator' => $visibility['operator'],
			            'logic' => (isset($visibility['logic'])) ? $visibility['logic'] : null,
			            'sort' => ($visibilityIndex+1),
			            'metaBoxField' => $metaBoxFieldModel
		            ]);

		            $ids[$slug]['visibilityConditions'][] = $visibilityId;
		            $metaBoxFieldModel->addVisibilityCondition($visibilityConditionModel);
	            }
            }

            if(isset($field['advancedOptions'])){
	            foreach ($field['advancedOptions'] as $advancedOptionIndex => $advancedOption) {
		            $advancedOptionId = isset($advancedOption["id"]) ? $advancedOption["id"] : Uuid::v4();

		            $advancedOptionModel = MetaBoxFieldAdvancedOptionModel::hydrateFromArray([
			            'id' => $advancedOptionId,
			            'metaBoxField' => $metaBoxFieldModel,
			            'key' => $advancedOption['key'],
			            'value' => $advancedOption['value'],
		            ]);

		            $ids[$slug]['advancedOptions'][] = $advancedOptionModel;
		            $metaBoxFieldModel->addAdvancedOption($advancedOptionModel);
	            }
            }

            if($field['type'] === CustomPostTypeMetaBoxFieldModel::POST_TYPE) {
                foreach ($field['relations'] as $relation){
                    $relationId = isset($relation["id"]) ? $relation["id"] : Uuid::v4();

                    $relatedCustomPostType = CustomPostTypeRepository::get([
                        'postType' => $relation['relatedPostType'],
                    ], true)[0];

                    $relationModel = MetaBoxFieldRelationshipModel::hydrateFromArray([
                        'id' => $relationId,
                        'relationship' => $relation['type'],
                        'relatedCustomPostType' => $relatedCustomPostType,
                        'metaBoxField' => $metaBoxFieldModel,
                    ]);

                    if(isset($relation['inversedFieldId'])){

                        $inversedBy = MetaRepository::getMetaField([
                            'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
                            'find' => $relation['relatedPostType'],
                            'id' => $relation['inversedFieldId'],
                        ]);

                        $relationModel->setInversedBy($inversedBy);
                    }

                    $ids[$slug]['relations'][] = $relationId;
                    $metaBoxFieldModel->removeRelation($relationModel);
                    $metaBoxFieldModel->addRelation($relationModel);
                }
            }

            $metaBoxModel->addField($metaBoxFieldModel);
        }

        MetaRepository::saveMetaBox($metaBoxModel);

        return $ids;
    }

    /**
     * @param \WP_REST_Request $request
     *
     * @return mixed
     */
    public function delete(\WP_REST_Request $request)
    {
        $id = $request['id'];
        $slug = $request['slug'];

        try {
            MetaRepository::deleteMetaBoxById([
                'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
                'find' => $slug,
                'id' => $id
            ]);

            return $this->jsonResponse([
                'id' => $id
            ], 200);

        } catch (\Exception $exception){
            return $this->jsonErrorResponse($exception);
        }
    }

    /**
     * @param \WP_REST_Request $request
     *
     * @return mixed
     */
    public function deleteAll(\WP_REST_Request $request)
    {
        $slug = $request['slug'];
        $ids = [];

        try {
            $meta = MetaRepository::get([
                'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
                'find' => $slug,
            ]);

            foreach ($meta as $metaBox){
                $ids[] = $metaBox->getId();
            }

            if(null === $meta){
                return $this->jsonNotFoundResponse('Not records found');
            }

            MetaRepository::deleteAll([
                'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
                'find' => $slug,
            ]);

            return $this->jsonResponse([
                'ids' => $ids
            ], 200);

        } catch (\Exception $exception){
            return $this->jsonErrorResponse($exception);
        }
    }
}