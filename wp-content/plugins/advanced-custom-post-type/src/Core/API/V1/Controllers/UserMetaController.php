<?php

namespace ACPT\Core\API\V1\Controllers;

use ACPT\Core\Helper\Uuid;
use ACPT\Core\JSON\UserMetaBoxSchema;
use ACPT\Core\Models\MetaField\MetaBoxFieldOptionModel;
use ACPT\Core\Models\User\UserMetaBoxFieldModel;
use ACPT\Core\Models\User\UserMetaBoxModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Costants\MetaTypes;
use ACPT\Utils\Data\Sanitizer;

class UserMetaController extends AbstractController
{
    /**
     * @param \WP_REST_Request $request
     * @return mixed
     */
    public function create(\WP_REST_Request $request)
    {
        $ids = [];
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
                'belongsTo' => MetaTypes::USER,
            ]);

            if(!empty($meta)){
                return $this->jsonResponse([
                        'message' => 'The user already has associated meta boxes'
                ], 500);
            }

            foreach ($data as $index => $datum){
                $this->saveMetaBox($index, $datum, $ids);
            }

            return $this->jsonResponse([
                    'ids' => $ids
            ], 201);

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

        try {
            MetaRepository::deleteMetaBoxById([
                'belongsTo' => MetaTypes::USER,
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
        $ids = [];

        try {
            $meta = MetaRepository::get([
                'belongsTo' => MetaTypes::USER,
            ]);

            foreach ($meta as $metaBox){
                $ids[] = $metaBox->getId();
            }

            if(empty($ids)){
                return $this->jsonNotFoundResponse('Not records found');
            }

            MetaRepository::deleteAll([
                'belongsTo' => MetaTypes::USER,
            ]);

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
    public function get(\WP_REST_Request $request)
    {
        $id = $request['id'];

        try {
            $meta = MetaRepository::get([
                'belongsTo' => MetaTypes::USER,
                'id' => $id
            ]);

            if(empty($meta)){
                return $this->jsonNotFoundResponse('Not records found');
            }

            return $this->jsonResponse($meta[0]);

        } catch (\Exception $exception){
            return $this->jsonErrorResponse($exception);
        }
    }

    /**
     * @param \WP_REST_Request $request
     *
     * @return mixed
     */
    public function getAll(\WP_REST_Request $request)
    {
        try {
            $meta = MetaRepository::get([
                'belongsTo' => MetaTypes::USER,
            ]);

            return $this->jsonResponse($meta);

        } catch (\Exception $exception){
            return $this->jsonErrorResponse($exception);
        }
    }

    /**
     * @param \WP_REST_Request $request
     *
     * @return mixed
     */
    public function update(\WP_REST_Request $request)
    {
        $ids = [];
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
                'belongsTo' => MetaTypes::USER,
            ]);

            if(empty($meta)){
                return $this->jsonResponse([
                        'message' => 'The user has not associated meta boxes'
                ], 500);
            }

            foreach ($data as $index => $datum){
                $this->saveMetaBox($index, $datum,  $ids);
            }

            MetaRepository::removeMetaOrphans([
                'belongsTo' => MetaTypes::USER,
                'ids' => $ids
            ]);

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
     * @param $ids
     * @return mixed
     * @throws \Exception
     */
    private function saveMetaBox($index, $datum, &$ids)
    {
        // validate data
        $this->validateJSONSchema($datum, new UserMetaBoxSchema());

        // sanitize data
        $datum = Sanitizer::recursiveSanitizeRawData($datum);

        $id = isset($datum["id"]) ? $datum["id"] : Uuid::v4();

        $metaBoxModel = UserMetaBoxModel::hydrateFromArray([
                'id' => $id,
                'name' => $datum["name"],
                'sort' => ($index+1),
        ]);

        if(isset($datum['label']) and !empty($datum['label'])){
	        $metaBoxModel->changeLabel($datum['label']);
        }

        $ids['boxes'][] = $id;

        foreach ($datum["fields"] as $fieldIndex => $field){

            $fieldId = isset($field["id"]) ? $field["id"] : Uuid::v4();

            $metaBoxFieldModel = UserMetaBoxFieldModel::hydrateFromArray([
                    'id' => $fieldId,
                    'metaBox' => $metaBoxModel,
                    'name' => $field['name'],
                    'type' => $field['type'],
                    'showInArchive' => $field['showInArchive'],
                    'required' => $field['isRequired'],
                    'sort' => ($fieldIndex+1),
                    'defaultValue' => $field['defaultValue'],
                    'description' => $field['description'],
            ]);

            $ids['fields'][] = $fieldId;

            if($field['type'] === UserMetaBoxFieldModel::SELECT_TYPE or $field['type'] === UserMetaBoxFieldModel::SELECT_MULTI_TYPE){
                foreach ($field['options'] as $optionIndex => $option){

                    $optionId = isset($option["id"]) ? $option["id"] : Uuid::v4();

                    $metaBoxFieldOptionModel = MetaBoxFieldOptionModel::hydrateFromArray([
                            'id' => $optionId,
                            'metaBoxField' => $metaBoxFieldModel,
                            'label' => $option['label'],
                            'value' => $option['value'],
                            'sort' => ($optionIndex+1)
                    ]);

                    $ids['options'][] = $optionId;
                    $metaBoxFieldModel->addOption($metaBoxFieldOptionModel);
                }
            }

            $metaBoxModel->addField($metaBoxFieldModel);
        }

        MetaRepository::saveMetaBox($metaBoxModel);

        return $ids;
    }
}