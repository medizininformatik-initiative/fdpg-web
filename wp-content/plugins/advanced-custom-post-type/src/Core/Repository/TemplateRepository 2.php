<?php

namespace ACPT\Core\Repository;

use ACPT\Core\Models\Template\TemplateModel;
use ACPT\Costants\MetaTypes;
use ACPT\Includes\ACPT_DB;

class TemplateRepository
{
    /**
     * @return int
     */
    public static function count()
    {
        $baseQuery = "
            SELECT 
                count(id) as count
            FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TEMPLATE)."`
            ";

        $results = ACPT_DB::getResults($baseQuery);

        return (int)$results[0]->count;
    }

    /**
     * Delete template
     *
     * @param $belongsTo
     * @param $templateType
     *
     * @param null $find
     * @throws \Exception
     */
    public static function delete($belongsTo, $templateType, $find = null)
    {
        $query = "DELETE FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TEMPLATE)."` WHERE belongs_to = %s AND template_type = %s";
        $args = [$belongsTo,  $templateType];

        if($find){
            $query .= " AND find = %s";
            $args[] = $find;
        }

        $query .= ";";

        ACPT_DB::executeQueryOrThrowException($query, $args);
    }
    
    /**
     * Delete templates
     *
     * @param $postType
     *
     * @throws \Exception
     */
    public static function deleteByPostType($postType)
    {
        ACPT_DB::executeQueryOrThrowException("DELETE FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TEMPLATE)."` WHERE belongs_to = %s and find = %s;", [MetaTypes::CUSTOM_POST_TYPE, $postType]);
    }

    /**
     * @param string $belongsTo
     * @param string $templateType
     * @param string|null $find
     * @param string|null $metaFieldId
     *
     * @return TemplateModel|null
     * @throws \Exception
     */
    public static function get($belongsTo, $templateType, $find = null, $metaFieldId = null)
    {
        $baseQuery = "
            SELECT 
                *
            FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TEMPLATE)."`
            WHERE 
                belongs_to = %s AND
                template_type = %s
            ";

        $params = [$belongsTo, $templateType];

        if($find !== null){
            $baseQuery .= ' AND find = %s';
            $params[] = $find;
        }

        if($metaFieldId !== null){
            $baseQuery .= ' AND meta_field_id = %s';
            $params[] = $metaFieldId;
        }

        $results = ACPT_DB::getResults($baseQuery, $params);

        if(count($results) === 1){
            return self::hydrateTemplateModel($results[0]);
        }

        return null;
    }

	/**
	 * @param $record
	 *
	 * @return TemplateModel
	 * @throws \Exception
	 */
    private static function hydrateTemplateModel($record)
    {
	    return TemplateModel::hydrateFromArray([
		    'id' => $record->id,
		    'belongsTo' => $record->belongs_to,
		    'templateType' =>  $record->template_type,
		    'json' =>  $record->json,
		    'html' =>  $record->html,
		    'meta' =>  ($record->meta) ? json_decode($record->meta, true) : [],
		    'metaFieldId' =>  (isset($results[0]->meta_field_id) and $record->meta_field_id !== null) ? $record->meta_field_id : null,
		    'find' =>  (isset($record->find) and $record->find !== null) ? $record->find : null,
	    ]);
    }

	/**
	 * @param $belongsTo
	 * @param $find
	 *
	 * @return TemplateModel[]
	 * @throws \Exception
	 */
	public static function getBy($belongsTo, $find)
	{
		$baseQuery = "
            SELECT 
                *
            FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TEMPLATE)."`
            WHERE 
                belongs_to = %s AND
                find = %s
            ";

		$results = ACPT_DB::getResults($baseQuery, [$belongsTo, $find]);

		$templates = [];

		foreach ($results as $result){
			$templates[] = self::hydrateTemplateModel($result);
		}

		return $templates;
	}

    /**
     * @param $page
     * @param $perPage
     *
     * @return TemplateModel[]
     * @throws \Exception
     */
    public static function getAll($page, $perPage)
    {
        $limit = $perPage;
        $offset = ($perPage * ($page - 1));

        $baseQuery = "
            SELECT 
                id, 
                belongs_to,
                template_type,
                meta_field_id,
                json,
                html,
                find,
                meta
            FROM `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TEMPLATE)."`  
            GROUP BY id 
            ORDER BY `belongs_to` ASC
            LIMIT ".$limit." OFFSET ".$offset."
        ;";

        $templates = ACPT_DB::getResults($baseQuery, []);

        $results = [];

        foreach ($templates as $template) {
            $results[] = TemplateModel::hydrateFromArray( [
                'id' => $template->id,
                'belongsTo' => $template->belongs_to,
                'templateType' => $template->template_type,
                'json' => $template->json,
                'html' => $template->html,
                'find' => $template->find,
                'meta' => json_decode($template->meta, true),
                'metaFieldId' =>  (isset($template->meta_field_id) and $template->meta_field_id !== null) ? $template->meta_field_id : null,
            ] );
        }

        return $results;
    }
    
    /**
     * Save template
     *
     * @param TemplateModel $model
     *
     * @throws \Exception
     */
    public static function save(TemplateModel $model)
    {
        $sql = "
            INSERT INTO `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TEMPLATE)."` 
            (`id`,
            `belongs_to` ,
            `template_type`,
            `meta_field_id`,
            `json`,
            `html`,
            `find`,
            `meta`
            ) VALUES (
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s,
                %s
            ) ON DUPLICATE KEY UPDATE 
                `belongs_to` = %s,
                `template_type` = %s,
                `meta_field_id` = %s,
                `json` = %s,
                `html` = %s,
                `find` = %s,
                `meta` = %s
        ;";

        ACPT_DB::executeQueryOrThrowException($sql, [
                $model->getId(),
                $model->getBelongsTo(),
                $model->getTemplateType(),
                $model->getMetaFieldId(),
                $model->getJson(),
                $model->getHtml(),
                $model->getFind(),
                json_encode($model->getMeta()),
                $model->getBelongsTo(),
                $model->getTemplateType(),
                $model->getMetaFieldId(),
                $model->getJson(),
                $model->getHtml(),
                $model->getFind(),
                json_encode($model->getMeta()),
        ]);
    }
}