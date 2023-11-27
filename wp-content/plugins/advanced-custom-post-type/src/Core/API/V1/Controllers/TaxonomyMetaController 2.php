<?php

namespace ACPT\Core\API\V1\Controllers;

use ACPT\Core\Helper\Uuid;
use ACPT\Core\JSON\TaxonomyMetaBoxSchema;
use ACPT\Core\Models\MetaField\MetaBoxFieldAdvancedOptionModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldOptionModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldVisibilityModel;
use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;
use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Core\Validators\VisibilityConditionValidator;
use ACPT\Costants\MetaTypes;
use ACPT\Utils\Data\Sanitizer;

class TaxonomyMetaController extends AbstractController
{
    /**
     * @param \WP_REST_Request $request
     * @return mixed
     */
    public function getByTaxonomySlug(\WP_REST_Request $request)
    {
        $slug = $request['slug'];

        try {
            $meta = MetaRepository::get([
                'belongsTo' => MetaTypes::TAXONOMY,
                'find' => $slug,
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
                'belongsTo' => MetaTypes::TAXONOMY,
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
                'belongsTo' => MetaTypes::TAXONOMY,
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
	    $this->validateJSONSchema($datum, new TaxonomyMetaBoxSchema());

	    // sanitize data
        $datum = Sanitizer::recursiveSanitizeRawData($datum);

        $id = isset($datum["id"]) ? $datum["id"] : Uuid::v4();

        $metaBoxModel = TaxonomyMetaBoxModel::hydrateFromArray([
            'id' => $id,
            'taxonomy' => $slug,
            'name' => $datum["title"],
            'sort' => ($index+1),
        ]);

	    if(isset($datum['label']) and !empty($datum['label'])){
		    $metaBoxModel->changeLabel($datum['label']);
	    }

        $ids[$slug]['boxes'][] = $id;

        foreach ($datum["fields"] as $fieldIndex => $field){

            $fieldId = isset($field["id"]) ? $field["id"] : Uuid::v4();

            $metaBoxFieldModel = TaxonomyMetaBoxFieldModel::hydrateFromArray([
                'id' => $fieldId,
                'metaBox' => $metaBoxModel,
                'name' => $field['name'],
                'type' => $field['type'],
                'required' => $field['isRequired'],
                'sort' => ($fieldIndex+1),
                'defaultValue' => $field['defaultValue'],
                'description' => $field['description'],
            ]);

            $ids[$slug]['fields'][] = $fieldId;

            if($field['type'] === TaxonomyMetaBoxFieldModel::REPEATER_TYPE and !empty($field['children'])){

                foreach ($field['children'] as $childIndex => $child){

                    $childFieldId = isset($child["id"]) ? $child["id"] : Uuid::v4();

                    $metaBoxChildFieldModel = TaxonomyMetaBoxFieldModel::hydrateFromArray([
                        'id' => $childFieldId,
                        'metaBox' => $metaBoxModel,
                        'name' => $child['name'],
                        'type' => $child['type'],
                        'required' => $child['isRequired'],
                        'sort' => ($childIndex+1),
                        'defaultValue' => $child['defaultValue'],
                        'description' => $child['description'],
                    ]);

                    if($child['type'] === TaxonomyMetaBoxFieldModel::SELECT_TYPE or $child['type'] === TaxonomyMetaBoxFieldModel::SELECT_MULTI_TYPE){
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

            if($field['type'] === TaxonomyMetaBoxFieldModel::SELECT_TYPE or $field['type'] === TaxonomyMetaBoxFieldModel::SELECT_MULTI_TYPE){
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

            $metaBoxModel->addField($metaBoxFieldModel);
        }

        MetaRepository::saveMetaBox($metaBoxModel);

        return $ids;
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
                'belongsTo' => MetaTypes::TAXONOMY,
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
                'belongsTo' => MetaTypes::TAXONOMY,
                'find' => $slug,
                'id' => $id,
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
                'belongsTo' => MetaTypes::TAXONOMY,
                'find' => $slug,
            ]);

            foreach ($meta as $metaBox){
                $ids[] = $metaBox->getId();
            }

            if(null === $meta){
                return $this->jsonNotFoundResponse('Not records found');
            }

            MetaRepository::deleteAll([
                'belongsTo' => MetaTypes::TAXONOMY,
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