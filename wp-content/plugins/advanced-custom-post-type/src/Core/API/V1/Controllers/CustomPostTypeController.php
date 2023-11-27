<?php

namespace ACPT\Core\API\V1\Controllers;

use ACPT\Core\Helper\Uuid;
use ACPT\Core\JSON\CustomPostTypeSchema;
use ACPT\Core\Models\CustomPostType\CustomPostTypeModel;
use ACPT\Core\Repository\CustomPostTypeRepository;
use ACPT\Core\Repository\SettingsRepository;
use ACPT\Utils\Data\Sanitizer;

class CustomPostTypeController extends AbstractController
{
    /**
     * @param \WP_REST_Request $request
     *
     * @return mixed
     */
    public function getAll(\WP_REST_Request $request)
    {
        try {
            $count = CustomPostTypeRepository::count();
            $page = isset($request['page']) ? $request['page'] : 1;
            $perPage = isset($request['per_page']) ? $request['per_page'] : 20;
            $maxPages = ceil($count / $perPage);

            if($perPage > 100){
                $perPage = 100;
            }

            $records = CustomPostTypeRepository::get([
                'page' => $page,
                'perPage' => $perPage,
            ]);

            return $this->jsonPaginatedResponse($page, $maxPages, $perPage, $count, $records);

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
        try {
            $cpt = CustomPostTypeRepository::get([
                    'postType' => $request['slug']
            ]);

            if(count($cpt) === 1){
                return $this->jsonResponse($cpt[0]);
            }

            return $this->jsonNotFoundResponse('Not records found');

        } catch (\Exception $exception){
            return $this->jsonErrorResponse($exception);
        }
    }

    /**
     * @param \WP_REST_Request $request
     * @return mixed
     */
    public function create(\WP_REST_Request $request)
    {
        $data = $this->getDecodedRequest($request);

        if(empty($data)){
            return $this->jsonResponse([
                    'message' => 'empty request body'
            ], 500);
        }

        try {

            // validate data
            $this->validateJSONSchema($data, new CustomPostTypeSchema());

            // sanitize data
            $data = Sanitizer::recursiveSanitizeRawData($data);

            if(CustomPostTypeRepository::exists($data["post_name"])){
                return $this->jsonResponse([
                    'message' => $data["post_name"] . ' already exists'
                ], 500);
            }

            $id = Uuid::v4();

            $model = CustomPostTypeModel::hydrateFromArray([
                'id' => $id,
                'name' => $data["post_name"],
                'singular' => $data["singular_label"],
                'plural' => $data["plural_label"],
                'icon' => $data["icon"],
                'native' => false,
                'supports' => $data['supports'],
                'labels' =>  $data['labels'],
                'settings' =>  $data['settings'],
            ]);

            CustomPostTypeRepository::save($model);

            return $this->jsonResponse([
                'id' => $id
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
    public function update(\WP_REST_Request $request)
    {
        $slug = $request['slug'];
        $data = $this->getDecodedRequest($request);

        if(empty($data)){
            return $this->jsonResponse([
                    'message' => 'empty request body'
            ], 500);
        }

        if(!CustomPostTypeRepository::exists($slug)){
            return $this->jsonResponse([
                    'message' => $slug . ' does not exists'
            ], 500);
        }

        try {
            // validate data
            $this->validateJSONSchema($data, new CustomPostTypeSchema());

            // sanitize data
            $data = Sanitizer::recursiveSanitizeRawData($data);

            $id = CustomPostTypeRepository::getId($data["post_name"]);
            $model = CustomPostTypeModel::hydrateFromArray([
                    'id' => $id,
                    'name' => $slug,
                    'singular' => $data["singular_label"],
                    'plural' => $data["plural_label"],
                    'icon' => $data["icon"],
                    'native' => false,
                    'supports' => $data['supports'],
                    'labels' =>  $data['labels'],
                    'settings' =>  $data['settings'],
            ]);

            CustomPostTypeRepository::save($model);

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
    public function delete(\WP_REST_Request $request)
    {
        $slug = $request['slug'];

        try {
            $cpt = CustomPostTypeRepository::get([
                    'postType' => $slug
            ], true);

            if(count($cpt) === 1){

                $customPostTypeModel = $cpt[0];

                // Delete posts option
                $deletePosts = false;
                $deletePostsOption = SettingsRepository::getSingle($slug);

                if($deletePostsOption !== null and $deletePostsOption->getValue() == 1){
                    $deletePosts = true;
                }

                CustomPostTypeRepository::delete($slug, $deletePosts);

                return $this->jsonResponse([
                    'id' => $customPostTypeModel->getId()
                ], 200);
            }

            return $this->jsonNotFoundResponse('Not records found');

        } catch (\Exception $exception){
            return $this->jsonErrorResponse($exception);
        }
    }

//    /**
//     * @param $postType
//     * @param $ids
//     *
//     * @throws \Exception
//     */
//    public static function removeMetaOrphans($postType, $ids)
//    {
//        ACPT_DB::executeQueryOrThrowException("DELETE f FROM `".ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD."` f LEFT JOIN `".ACPT_DB::TABLE_CUSTOM_POST_TYPE_META_BOX."` b on b.id=f.meta_box_id WHERE b.post_type = '".$postType."' AND f.id NOT IN ('".implode("','",$ids['fields'])."');");
//        ACPT_DB::executeQueryOrThrowException("DELETE o FROM `".ACPT_DB::TABLE_CUSTOM_POST_TYPE_OPTION."` o LEFT JOIN `".ACPT_DB::TABLE_CUSTOM_POST_TYPE_META_BOX."` b on b.id=o.meta_box_id WHERE b.post_type = '".$postType."' AND o.id NOT IN ('".implode("','",$ids['options'])."');");
//        ACPT_DB::executeQueryOrThrowException("DELETE r FROM `".ACPT_DB::TABLE_CUSTOM_POST_TYPE_RELATION."` r LEFT JOIN `".ACPT_DB::TABLE_CUSTOM_POST_TYPE_META_BOX."` b on b.id=r.meta_box_id WHERE b.post_type = '".$postType."' AND r.id NOT IN ('".implode("','",$ids['relations'])."');");
//        ACPT_DB::executeQueryOrThrowException("DELETE FROM `".ACPT_DB::TABLE_CUSTOM_POST_TYPE_META_BOX."` WHERE post_type = '".$postType."' AND id NOT IN ('".implode("','",$ids['boxes'])."');");
//    }
//
//    /**
//     * @throws \Exception
//     */
//    public static function removeOrphanRelationships()
//    {
//        $query = "
//            SELECT f.`id`, r.`inversed_meta_field_id`, r.`relationship`
//            FROM `".ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD."` f
//            JOIN `" . ACPT_DB::TABLE_CUSTOM_POST_TYPE_RELATION . "` r ON r.meta_field_id = f.id
//            WHERE f.`field_type` = %s
//            AND r.`relationship` LIKE '%Bi'
//        ";
//
//        // set all orphan fields with a orphan relationship to TEXT
//        $results = ACPT_DB::getResults($query, [
//                CustomPostTypeMetaBoxFieldModel::POST_TYPE
//        ]);
//
//        if(count($results) > 0) {
//            foreach ( $results as $result ) {
//
//                $subquery = "
//                    SELECT f.id
//                    FROM `" . ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD . "` f
//                    WHERE f.`id` = %s
//                ";
//
//                $subResults = ACPT_DB::getResults( $subquery, [$result->inversed_meta_field_id] );
//
//                if ( count( $subResults ) === 0 ) {
//                    $sql = "DELETE FROM `" . ACPT_DB::TABLE_CUSTOM_POST_TYPE_RELATION . "` WHERE meta_field_id = %s;";
//                    self::executeQueryOrThrowException( $sql, [
//                            $result->id
//                    ] );
//
//                    $sql = "UPDATE `" . ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD . "` SET `field_type` = %s WHERE id = %s;";
//                    self::executeQueryOrThrowException( $sql, [
//                            CustomPostTypeMetaBoxFieldModel::TEXT_TYPE,
//                            $result->id
//                    ] );
//                }
//            }
//        }
//
//        // check if there are persisted relationship on a NON POST type field
//        $query = "
//            SELECT r.id
//            FROM `".ACPT_DB::TABLE_CUSTOM_POST_TYPE_RELATION."` r
//            JOIN `" . ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD . "` f ON f.id = r.meta_field_id
//            WHERE f.`field_type` != %s
//        ";
//
//        $results = ACPT_DB::getResults($query, [
//                CustomPostTypeMetaBoxFieldModel::POST_TYPE
//        ]);
//
//        if(count($results) > 0) {
//            foreach ( $results as $result ) {
//                $sql = "DELETE FROM `" . ACPT_DB::TABLE_CUSTOM_POST_TYPE_RELATION . "` WHERE id = %s;";
//                self::executeQueryOrThrowException( $sql, [
//                        $result->id
//                ] );
//            }
//        }
//    }
}