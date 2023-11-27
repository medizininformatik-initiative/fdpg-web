<?php

namespace ACPT\Admin;

use ACPT\Core\Generators\FieldBlockGenerator;
use ACPT\Core\Generators\RepeaterFieldGenerator;
use ACPT\Core\Helper\Strings;
use ACPT\Core\Helper\Uuid;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\ApiKey\ApiKeyModel;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxModel;
use ACPT\Core\Models\CustomPostType\CustomPostTypeModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldAdvancedOptionModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldBlockModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldOptionModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldRelationshipModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldVisibilityModel;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxModel;
use ACPT\Core\Models\OptionPage\OptionPageModel;
use ACPT\Core\Models\Settings\SettingsModel;
use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;
use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxModel;
use ACPT\Core\Models\Taxonomy\TaxonomyModel;
use ACPT\Core\Models\Template\TemplateModel;
use ACPT\Core\Models\User\UserMetaBoxFieldModel;
use ACPT\Core\Models\User\UserMetaBoxModel;
use ACPT\Core\Models\WooCommerce\WooCommerceProductDataFieldModel;
use ACPT\Core\Models\WooCommerce\WooCommerceProductDataFieldOptionModel;
use ACPT\Core\Models\WooCommerce\WooCommerceProductDataModel;
use ACPT\Core\Repository\ApiRepository;
use ACPT\Core\Repository\CustomPostTypeRepository;
use ACPT\Core\Repository\ImportRepository;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Core\Repository\OptionPageRepository;
use ACPT\Core\Repository\SettingsRepository;
use ACPT\Core\Repository\TaxonomyRepository;
use ACPT\Core\Repository\TemplateRepository;
use ACPT\Core\Repository\WooCommerceProductDataRepository;
use ACPT\Core\Validators\ImportFileValidator;
use ACPT\Core\Validators\VisibilityConditionValidator;
use ACPT\Costants\MetaTypes;
use ACPT\Includes\ACPT_DB;
use ACPT\Utils\Data\Sanitizer;
use ACPT\Utils\Data\WooCommerceNormalizer;
use ACPT\Utils\ExportCode\ExportCodeStrings;
use ACPT\Utils\License\LicenseApi;
use ACPT\Utils\PHP\Sluggify;
use ACPT\Utils\Wordpress\WPLinks;
use Sepia\PoParser\Parser;
use Sepia\PoParser\SourceHandler\FileSystem;

/**
 * The admin ajax handler
 *
 * @since      1.0.0
 * @package    advanced-custom-post-type
 * @subpackage advanced-custom-post-type/admin
 * @author     Mauro Cassani <maurocassani1978@gmail.com>
 */
class ACPT_Ajax
{
	public function assocPostTypeToTaxonomyAction()
	{
		if(isset($_POST['data'])) {
			$data = $this->sanitizeJsonData($_POST['data']);

			try {
				$taxonomyId = TaxonomyRepository::getId($data['taxonomy']);

				foreach ($data['postTypes'] as $customPostType){
					if($customPostType['checked']){
						TaxonomyRepository::assocToPostType($customPostType['id'], $taxonomyId);
					} else {
						TaxonomyRepository::removeAssocPost($customPostType['id'], $taxonomyId);
					}
				}

				$return = [
					'success' => true,
				];
			} catch (\Exception $exception){
				$return = [
					'success' => false,
					'error' => $exception->getMessage()
				];
			}

			return wp_send_json($return);
		}
	}

    public function assocTaxonomyToPostTypeAction()
    {
        if(isset($_POST['data'])) {
            $data = $this->sanitizeJsonData($_POST['data']);

            try {
                $postId = CustomPostTypeRepository::getId($data['postType']);

                foreach ($data['taxonomies'] as $taxonomy){
                    if($taxonomy['checked']){
                        TaxonomyRepository::assocToPostType($postId, $taxonomy['id']);
                    } else {
                        TaxonomyRepository::removeAssocPost($postId, $taxonomy['id']);
                    }
                }

                $return = [
                        'success' => true,
                ];
            } catch (\Exception $exception){
                $return = [
                        'success' => false,
                        'error' => $exception->getMessage()
                ];
            }

            return wp_send_json($return);
        }
    }

    /**
     * Check if a Custom post type exists
     *
     * @return mixed
     */
    public function checkPostTypeNameAction()
    {
        if(isset($_POST['data'])) {
            $data = $this->sanitizeJsonData($_POST['data']);

            if (!isset($data['postType'])) {
                return wp_send_json([
                        'success' => false,
                        'error' => 'Missing postType'
                ]);
            }

            $postType = $data['postType'];

            return wp_send_json([
                    'exists' => CustomPostTypeRepository::exists($postType)
            ]);
        }
    }

    /**
     * Check if a Taxonomy exists
     *
     * @return mixed
     */
    public function checkTaxonomySlugAction()
    {
        if(isset($_POST['data'])) {
            $data = $this->sanitizeJsonData($_POST['data']);

            if (!isset($data['slug'])) {
                return wp_send_json([
                        'success' => false,
                        'error' => 'Missing slug'
                ]);
            }

            $slug = $data['slug'];

            return wp_send_json([
                    'exists' => TaxonomyRepository::exists($slug)
            ]);
        }
    }

	/**
	 * copy meta field
	 *
	 * @return mixed
	 * @throws \Exception
	 */
    public function copyMetaFieldAction()
    {
	    if(isset($_POST['data'])) {
		    $data = $this->sanitizeJsonData($_POST['data']);

		    if (!isset($data['belongsTo']) or !isset($data['fieldId']) or !isset($data['targetBoxId'])) {
			    return wp_send_json([
				    'success' => false,
				    'error' => 'Missing mandatory params [belongsTo, fieldId, targetBoxId]'
			    ]);
		    }

		    $find = isset($data['find']) ? $data['find'] : null;
		    $belongsTo = $data['belongsTo'];
		    $fieldId = $data['fieldId'];
		    $targetBoxId = $data['targetBoxId'];
		    $delete = isset($data['delete']) ? $data['delete'] : false;

		    $metaBoxField = MetaRepository::getMetaField([
			    'belongsTo' => $belongsTo,
			    'id' => $fieldId,
		    ]);

		    if(empty($metaBoxField)){
			    return wp_send_json([
				    'success' => false,
				    'error' => 'Meta field was not found. If you haven\'t saved the field yet, please SAVE it and then try to copy.'
			    ]);
		    }

		    $duplicatedMetaField = $metaBoxField->duplicate();

		    $metaBox = @MetaRepository::get([
			    'belongsTo' => $belongsTo,
			    'find' => $find,
			    'id' => $targetBoxId,
		    ])[0];

		    if(empty($metaBox)){
			    return wp_send_json([
				    'success' => false,
				    'error' => 'Meta box was not found'
			    ]);
		    }

		    $duplicatedMetaField->changeMetaBox($metaBox);

			// avoid duplicated box/field names
		    $arrayOfFieldNames = [];

		    foreach ($metaBox->getFields() as $fieldModel){
			    $arrayOfFieldNames[] = $fieldModel->getName();
		    }

		    $duplicatedMetaField->changeName(Strings::getTheFirstAvailableName($duplicatedMetaField->getName(), $arrayOfFieldNames));

		    try {
			    ACPT_DB::startTransaction();

			    MetaRepository::saveMetaBoxField($duplicatedMetaField);

			    if($delete){
				    MetaRepository::deleteMetaField([
					    'belongsTo' => $belongsTo,
					    'metaBoxField' => $metaBoxField
				    ]);
			    }

			    ACPT_DB::commitTransaction();

			    return wp_send_json([
				    'success' => true,
			    ]);
		    } catch (\Exception $exception){
			    return wp_send_json([
				    'success' => false,
				    'error' => $exception->getMessage()
			    ]);
		    }
	    }
    }

	/**
	 * @throws \Exception
	 */
    public function copyMetaBlockAction()
    {
	    if(isset($_POST['data'])) {
		    $data = $this->sanitizeJsonData($_POST['data']);

		    if (
		    	!isset($data['belongsTo']) or
			    !isset($data['find']) or
			    !isset($data['blockId']) or
			    !isset($data['fieldId']) or
			    !isset($data['targetFind']) or
			    !isset($data['targetBoxId']) or
			    !isset($data['targetFieldId'])
		    ) {
			    return wp_send_json([
				    'success' => false,
				    'error' => 'Missing mandatory params [belongsTo, find, blockId, fieldId, targetFind, targetBoxId, targetFieldId]'
			    ]);
		    }

		    $belongsTo = $data['belongsTo'];
		    $find = $data['find'];
		    $fieldId = $data['fieldId'];
		    $blockId = $data['blockId'];
		    $targetFind = $data['targetFind'];
		    $targetBoxId = $data['targetBoxId'];
		    $targetFieldId = $data['targetFieldId'];
		    $delete = isset($data['delete']) ? $data['delete'] : false;

		    $blockModel = MetaRepository::getMetaBlockById([
			    'belongsTo' => $belongsTo,
			    'find' => $find,
		    	'id' => $blockId,
			    'lazy' => false,
		    ]);

		    if($blockModel === null){
			    return wp_send_json([
				    'success' => false,
				    'error' => "Block model not found"
			    ]);
		    }

		    $targetFieldModel = MetaRepository::getMetaField([
			    'belongsTo' => $belongsTo,
			    'find' => $targetFind,
			    'id' => $targetFieldId,
		    ]);

		    if($targetFieldModel === null){
			    return wp_send_json([
				    'success' => false,
				    'error' => "Target field not found"
			    ]);
		    }

		    $duplicatedMetaBlock = $blockModel->duplicateFrom($targetFieldModel);

		    try {
			    ACPT_DB::startTransaction();

			    MetaRepository::saveMetaBlock($duplicatedMetaBlock);

			    if($delete){
				    MetaRepository::deleteMetaBlock([
					    'belongsTo' => $belongsTo,
					    'metaBlockField' => $blockModel
				    ]);
			    }

			    ACPT_DB::commitTransaction();

			    return wp_send_json([
				    'success' => true,
			    ]);

		    } catch (\Exception $exception){
			    return wp_send_json([
				    'success' => false,
				    'error' => $exception->getMessage()
			    ]);
		    }
	    }
    }

	/**
	 * copy meta box
	 *
	 * @return mixed
	 * @throws \Exception
	 */
    public function copyMetaBoxAction()
    {
	    if(isset($_POST['data'])) {
		    $data = $this->sanitizeJsonData($_POST['data']);

		    if (!isset($data['target']) or !isset($data['belongsTo']) or !isset($data['boxId']) or !isset($data['find'])) {
			    return wp_send_json([
				    'success' => false,
				    'error' => 'Missing mandatory params [target, belongsTo, boxId]'
			    ]);
		    }

		    $find = $data['find'];
		    $target = $data['target'];
		    $boxId = $data['boxId'];
		    $belongsTo = $data['belongsTo'];
		    $delete = isset($data['delete']) ? $data['delete'] : false;

			$metaBox = @MetaRepository::get([
				'belongsTo' => $belongsTo,
				'find' => $find,
				'id' => $boxId,
			])[0];

			if(empty($metaBox)){
				return wp_send_json([
					'success' => false,
					'error' => 'Meta box was not found. If you haven\'t saved the box yet, please SAVE it and then try to copy.'
				]);
			}

		    $duplicatedMetaBox = $metaBox->duplicate();

			if($belongsTo === MetaTypes::CUSTOM_POST_TYPE){
				$duplicatedMetaBox->changePostType($target);
			}

		    if($belongsTo === MetaTypes::TAXONOMY){
			    $duplicatedMetaBox->changeTaxonomy($target);
		    }

		    if($belongsTo === MetaTypes::OPTION_PAGE){
			    $duplicatedMetaBox->changeOptionPage($target);
		    }

		    // avoid duplicated box/field names
		    $arrayOfBoxNames = [];
		    $arrayOfFieldNames = [];

		    $targetMetaBoxes = MetaRepository::get([
			    'belongsTo' => $belongsTo,
			    'find' => $target,
		    ]);

		    foreach ($targetMetaBoxes as $targetMetaBox){
			    $arrayOfBoxNames[] = $targetMetaBox->getName();
		    }

		    $duplicatedMetaBox->changeName(Strings::getTheFirstAvailableName($duplicatedMetaBox->getName(), $arrayOfBoxNames));

	        foreach ($duplicatedMetaBox->getFields() as $duplicatedMetaBoxFieldModel){
		        $duplicatedMetaBoxFieldModel->changeName(Strings::getTheFirstAvailableName($duplicatedMetaBoxFieldModel->getName(), $arrayOfFieldNames));
		        $arrayOfFieldNames[] = $duplicatedMetaBoxFieldModel->getName();
	        }

		    try {
			    ACPT_DB::startTransaction();

			    MetaRepository::saveMetaBox($duplicatedMetaBox);

			    if($delete){
				    MetaRepository::deleteMetaBoxById([
					    'belongsTo' => $belongsTo,
					    'find' => $find,
					    'id' => $boxId,
				    ]);
			    }

			    ACPT_DB::commitTransaction();

			    return wp_send_json([
				    'success' => true,
			    ]);
		    } catch (\Exception $exception){
			    return wp_send_json([
				    'success' => false,
				    'error' => $exception->getMessage()
			    ]);
		    }
	    }
    }

    /**
     * Delete API key
     *
     * @return mixed
     */
    public function deleteApiKeyAction()
    {
        if(isset($_POST['data'])){
            $data = $this->sanitizeJsonData($_POST['data']);

            if(!isset($data['id'])){
                return wp_send_json([
                        'success' => false,
                        'error' => 'Missing id'
                ]);
            }

            $id = $data['id'];

            try {
                ApiRepository::delete($id);

                $return = [
                        'success' => true,
                ];
            } catch (\Exception $exception){
                $return = [
                        'success' => false,
                        'error' => $exception->getMessage()
                ];
            }

            return wp_send_json($return);
        }

        return wp_send_json([
                'success' => false,
                'error' => 'no data was sent'
        ]);
    }

    /**
     * Delete custom post type
     *
     * @return mixed
     */
    public function deleteCustomPostTypeAction()
    {
        if(isset($_POST['data'])){
            $data = $this->sanitizeJsonData($_POST['data']);

            if(!isset($data['postType'])){
                return wp_send_json([
                        'success' => false,
                        'error' => 'Missing postType'
                ]);
            }

            $postType = $data['postType'];

            try {

                // Delete posts option
                $deletePosts = false;
                $deletePostsOption = SettingsRepository::getSingle(SettingsModel::DELETE_POSTS_KEY);

                if($deletePostsOption !== null and $deletePostsOption->getValue() == 1){
                    $deletePosts = true;
                }

                CustomPostTypeRepository::delete($postType, $deletePosts);
                unregister_post_type($postType);

                $return = [
                        'success' => true,
                ];
            } catch (\Exception $exception){
                $return = [
                        'success' => false,
                        'error' => $exception->getMessage()
                ];
            }

            return wp_send_json($return);
        }

        return wp_send_json([
                'success' => false,
                'error' => 'no postType was sent'
        ]);
    }

    /**
     * Delete all meta for a custom post type
     *
     * @return mixed
     */
    public function deleteMetaAction()
    {
        if(isset($_POST['data'])){
            $data = $this->sanitizeJsonData($_POST['data']);

	        $find = isset($data['find']) ? $data['find'] : $data['postType'];
            $belongsTo = isset($data['belongsTo']) ? $data['belongsTo'] : MetaTypes::CUSTOM_POST_TYPE;

            try {
                MetaRepository::deleteAll([
                    'belongsTo' => $belongsTo,
                    'find' => $find,
                ]);
                MetaRepository::removeOrphanRelationships();

                $return = [
                        'success' => true,
                ];
            } catch (\Exception $exception){
                $return = [
                        'success' => false,
                        'error' => $exception->getMessage()
                ];
            }

            return wp_send_json($return);
        }

        return wp_send_json([
                'success' => false,
                'error' => 'no postType was sent'
        ]);
    }

    public function deleteOptionPagesAction()
    {
	    try {
		    OptionPageRepository::deleteAll();

		    $return = [
			    'success' => true,
		    ];
	    } catch (\Exception $exception){
		    $return = [
			    'success' => false,
			    'error' => $exception->getMessage()
		    ];
	    }

	    return wp_send_json($return);
    }

    /**
     * Delete post type template
     *
     * @return mixed
     */
    public function deleteTemplateAction()
    {
        $data = $this->sanitizeJsonData($_POST['data']);

        if(!isset($data['belongsTo']) and !isset($data['templateType'])){
            return wp_send_json([
                    'success' => false,
                    'error' => 'Missing belongsTo and/or templateType'
            ]);
        }

        $belongsTo = $data['belongsTo'];
        $find = isset($data['find']) ? $data['find'] : null;
        $templateType = $data['templateType'];

        try {
            TemplateRepository::delete($belongsTo, $templateType, $find);

            $return = [
                    'success' => true,
            ];
        } catch (\Exception $exception){
            $return = [
                    'success' => false,
                    'error' => $exception->getMessage()
            ];
        }

        return wp_send_json($return);
    }

    /**
     * Delete a taxonomy
     *
     * @return mixed
     */
    public function deleteTaxonomyAction()
    {
        if(isset($_POST['data'])){
            $data = $this->sanitizeJsonData($_POST['data']);

            if(!isset($data['taxonomy'])){
                return wp_send_json([
                        'success' => false,
                        'error' => 'Missing taxonomy'
                ]);
            }

            $taxonomy = $data['taxonomy'];

            try {
                TaxonomyRepository::delete($taxonomy);
                unregister_taxonomy($taxonomy);

                $return = [
                        'success' => true,
                ];
            } catch (\Exception $exception){
                $return = [
                        'success' => false,
                        'error' => $exception->getMessage()
                ];
            }

            return wp_send_json($return);
        }

        return wp_send_json([
                'success' => false,
                'error' => 'no taxonomy was sent'
        ]);
    }

	/**
	 * Delete WC product data
	 */
    public function deleteWooCommerceProductDataAction()
    {
        if(isset($_POST['data'])){
            $data = $this->sanitizeJsonData($_POST['data']);

            if(!isset($data['id'])){
                return wp_send_json([
                        'success' => false,
                        'error' => 'Missing id'
                ]);
            }

            $id = $data['id'];

            try {
                WooCommerceProductDataRepository::delete($id);

                $return = [
                        'success' => true,
                ];
            } catch (\Exception $exception){
                $return = [
                        'success' => false,
                        'error' => $exception->getMessage()
                ];
            }

            return wp_send_json($return);
        }

        return wp_send_json([
                'success' => false,
                'error' => 'no WooCommerce product data was sent'
        ]);
    }

	/**
	 * Delete WC product data fields
	 */
    public function deleteWooCommerceProductDataFieldsAction()
    {
        if(isset($_POST['data'])){
            $data = $this->sanitizeJsonData($_POST['data']);

            if(!isset($data['id'])){
                return wp_send_json([
                        'success' => false,
                        'error' => 'Missing id'
                ]);
            }
            $id = $data['id'];

            try {
                WooCommerceProductDataRepository::deleteFields($id);

                $return = [
                        'success' => true,
                ];
            } catch (\Exception $exception){
                $return = [
                        'success' => false,
                        'error' => $exception->getMessage()
                ];
            }

            return wp_send_json($return);
        }

        return wp_send_json([
                'success' => false,
                'error' => 'no WooCommerce product data was sent'
        ]);
    }

    /**
     * @return mixed
     */
    public function doShortcodeAction()
    {
        $data = $this->sanitizeJsonData($_POST['data']);

        if(!isset($data['shortcode'])){
            return wp_send_json([
                    'success' => false,
                    'error' => 'Missing taxonomy'
            ]);
        }

        $shortcode = $data['shortcode'];

        return wp_send_json([
                'success' => true,
                'data' => do_shortcode($shortcode)
        ]);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function exportFileAction()
    {
        $data = $this->sanitizeJsonData($_POST['data']);
	    $items = [
		    MetaTypes::CUSTOM_POST_TYPE => [],
		    MetaTypes::TAXONOMY => [],
		    MetaTypes::OPTION_PAGE => [],
		    MetaTypes::USER => [],
	    ];

        foreach ($data as $datum){

            if($datum['type'] === MetaTypes::CUSTOM_POST_TYPE and $datum['checked'] === true){
	            /** @var CustomPostTypeModel $customPostTypeModel */
	            $customPostTypeModel = CustomPostTypeRepository::get([
		            'id' => $datum['id']
	            ])[0];

	            $items[MetaTypes::CUSTOM_POST_TYPE][] = $customPostTypeModel->arrayRepresentation();
            }

	        if($datum['type'] === MetaTypes::TAXONOMY and $datum['checked'] === true){
		        /** @var TaxonomyModel $taxonomyModel */
		        $taxonomyModel = TaxonomyRepository::get([
			        'id' => $datum['id']
		        ])[0];

		        $items[MetaTypes::TAXONOMY][] = $taxonomyModel->arrayRepresentation();
	        }

	        if($datum['type'] === MetaTypes::OPTION_PAGE and $datum['checked'] === true){
		        /** @var OptionPageModel $optionPageModel */
		        $optionPageModel = OptionPageRepository::getById($datum['id']);

		        $items[MetaTypes::OPTION_PAGE][] = $optionPageModel->arrayRepresentation();
	        }

	        if($datum['type'] === MetaTypes::USER and $datum['checked'] === true){
		        $userMeta = MetaRepository::get([
			       'belongsTo' => MetaTypes::USER
		        ]);

		        $items[MetaTypes::USER] = $userMeta;
	        }
        }

        return wp_send_json([
                'success' => true,
                'data' => $items
        ]);
    }

	/**
	 * @throws \Exception
	 */
    public function fetchMetaFieldsFromBelongsToAction()
    {
	    $data = $this->sanitizeJsonData($_POST['data']);

	    if(!isset($data['belongsTo']) and !isset($data['find'])){
		    return wp_send_json([
			    'success' => false,
			    'error' => 'Missing params (`belongsTo`)'
		    ]);
	    }

	    $belongsTo = $data['belongsTo'];
	    $find = $data['find'];

	    $data = [
	    	[
	    		"name" => "--Select--",
	    		"id" => null,
		    ]
	    ];

	    switch ($belongsTo){
		    case MetaTypes::CUSTOM_POST_TYPE:
		    	$data[] = ['name' => 'ID', 'id' => 'ID'];
		    	$data[] = ['name' => 'date', 'id' => 'date'];
		    	$data[] = ['name' => 'title', 'id' => 'title'];

		    	// fetch cpt meta fields
	            $metaFields = MetaRepository::getMetaFields([
	            	'belongsTo' => $belongsTo,
	            	'find' => $find,
		            'lazy' => true,
	            ]);

	            foreach ($metaFields as $metaField){
		            $data[]  = [
			            'id' => $metaField->getId(),
			            'name' => '['.$metaField->getMetaBox()->getPostType().']' . $metaField->getUiName(),
		            ];
	            }

		    	break;

		    case MetaTypes::TAXONOMY:
			    $data[] = ['name' => 'ID', 'id' => 'ID'];
			    $data[] = ['name' => 'name', 'id' => 'name'];
			    $data[] = ['name' => 'slug', 'id' => 'slug'];

			    // fetch tax meta fields
			    $metaFields = MetaRepository::getMetaFields([
				    'belongsTo' => $belongsTo,
				    'find' => $find,
				    'lazy' => true,
			    ]);

			    foreach ($metaFields as $metaField){
				    $data[]  = [
					    'id' => $metaField->getId(),
					    'name' => '['.$metaField->getMetaBox()->getTaxonomy().']' . $metaField->getUiName(),
				    ];
			    }

			    break;
	    }

	    return wp_send_json($data);
    }

	/**
	 * @throws \Exception
	 */
    public function fetchFindFromBelongsToAction()
    {
	    $data = $this->sanitizeJsonData($_POST['data']);

	    if(!isset($data['belongsTo'])){
		    return wp_send_json([
			    'success' => false,
			    'error' => 'Missing params (`belongsTo`)'
		    ]);
	    }

	    $belongsTo = $data['belongsTo'];
	    $data = [];

	    switch ($belongsTo){
		    case MetaTypes::CUSTOM_POST_TYPE:

		    	$customPostTypes = CustomPostTypeRepository::get([], true);

		    	foreach ($customPostTypes as $customPostType){
				    $data[]  = [
					    'id' => $customPostType->getName(),
					    'name' => $customPostType->getName(),
				    ];
			    }

		    	break;

		    case MetaTypes::TAXONOMY:

			    $taxonomies = TaxonomyRepository::get([], true);

			    foreach ($taxonomies as $taxonomy){
				    $data[]  = [
					    'id' => $taxonomy->getSlug(),
					    'name' => $taxonomy->getSlug(),
				    ];
			    }
			    break;

		    case 'flex_block':

		    	// return all blocks here
			    $meta = MetaRepository::getMetaFields([
				    'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
				    'types' => [
					    CustomPostTypeMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE,
				    ],
				    'lazy' => false,
			    ]);

			    foreach ($meta as $field){
			    	if(!empty($field->getBlocks())){
			    		foreach ($field->getBlocks() as $block){
							$data[]  = [
							    'id' => $block->getId(),
							    'name' => '['.$field->getMetaBox()->getPostType().']' . $block->getUiName(),
						    ];
					    }
				    }
			    }

			    $meta = MetaRepository::getMetaFields([
				    'belongsTo' => MetaTypes::OPTION_PAGE,
				    'types' => [
					    OptionPageMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE,
				    ],
				    'lazy' => false,
			    ]);

			    foreach ($meta as $field){
				    if(!empty($field->getBlocks())){
					    foreach ($field->getBlocks() as $block){
						    $data[]  = [
							    'id' => $block->getId(),
							    'name' => '['.$field->getMetaBox()->getOptionPage().']' . $block->getUiName(),
						    ];
					    }
				    }
			    }

		    	break;

		    case 'meta_field':

		    	$meta = MetaRepository::getMetaFields([
		    		'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
				    'types' => [
					    CustomPostTypeMetaBoxFieldModel::REPEATER_TYPE,
				    ],
				    'lazy' => true,
			    ]);

			    foreach ($meta as $field){
				    $data[]  = [
					    'id' => $field->getId(),
					    'name' => '['.$field->getMetaBox()->getPostType().']' . $field->getUiName(),
				    ];
			    }

			    // @TODO lo posso fare?
//			    $meta = MetaRepository::getMetaFields([
//				    'belongsTo' => MetaTypes::OPTION_PAGE,
//				    'types' => [
//					    OptionPageMetaBoxFieldModel::REPEATER_TYPE,
//				    ],
//				    'lazy' => false,
//			    ]);
//
//			    foreach ($meta as $field){
//				    $data[]  = [
//					    'id' => $field->getId(),
//					    'name' => '['.$field->getMetaBox()->getOptionPage().']' . $field->getUiName(),
//				    ];
//			    }

			    break;
	    }

	    return wp_send_json($data);
    }

	/**
	 * Fetch preview link
	 */
    public function fetchPreviewLinkAction()
    {
        $data = $this->sanitizeJsonData($_POST['data']);

        if(!isset($data['id']) and !isset($data['belongsTo']) and !isset($data['find']) and !isset($data['template']) ){
            return wp_send_json([
                    'success' => false,
                    'error' => 'Missing params (`id`, `belongsTo`, `find`, `template`)'
            ]);
        }

        $id = $data['id'];
        $find = $data['find'];
        $belongsTo = $data['belongsTo'];

        if($belongsTo === MetaTypes::CUSTOM_POST_TYPE){
            if($find === 'post'){
                $category = get_the_category($id);
                $archiveLink = get_category_link($category);
            } else {
                $archiveLink = get_post_type_archive_link($find);
            }

            return wp_send_json([
                'success' => true,
                'data' => [
                    'single_link' => get_the_permalink($id),
                    'archive_link' => $archiveLink
                ]
            ]);
        }

        if($belongsTo === MetaTypes::TAXONOMY){
            return wp_send_json([
                'success' => true,
                'data' => [
                    'single_link' => get_term_link($id),
                ]
            ]);
        }

        return wp_send_json([
            'success' => false,
        ]);
    }

    /**
     * Fetch custom post type meta
     *
     * @return mixed
     * @throws \Exception
     */
    public function fetchMetaAction()
    {
        $data = $this->sanitizeJsonData($_POST['data']);

        $belongsTo = isset($data['belongsTo']) ? $data['belongsTo'] : MetaTypes::CUSTOM_POST_TYPE;
        $find = isset($data['find']) ? $data['find'] : null;

        // OLD format, keep compatibility
        if($find === null){
            $find = $data['postType'];
        }

        if($belongsTo !== MetaTypes::USER and $find === null){
            return wp_send_json([
                'success' => false,
                'error' => 'No data sent'
            ]);
        }

        $options = [];

        if(isset($data['excludeField'])){
            $options['excludeFields'][] = $data['excludeField'];
        }

        return wp_send_json(MetaRepository::get(array_merge([
            'belongsTo' => $belongsTo,
            'find' => $find,
        ], $options)));
    }

    /**
     * Fetch meta field by id
     *
     * @return mixed
     * @throws \Exception
     */
    public function fetchMetaFieldAction()
    {
        $data = $this->sanitizeJsonData($_POST['data']);

        if(!isset($data['id'])){
            return wp_send_json([
                    'success' => false,
                    'error' => 'Missing id'
            ]);
        }

        $id = $data['id'];
        $lazy = isset($data ['lazy']) ? $data ['lazy'] : false;
        $belongsTo = isset($data['belongsTo']) ? $data['belongsTo'] : MetaTypes::CUSTOM_POST_TYPE;

        return wp_send_json(MetaRepository::getMetaField([
            'belongsTo' => $belongsTo,
            'id' => $id,
            'lazy' => $lazy,
        ]));
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function fetchTemplateAction()
    {
        $data = $this->sanitizeJsonData($_POST['data']);

        // json, postType, templateType
        if(!isset($data['belongsTo']) and !isset($data['templateType'])){
            return wp_send_json([
                    'success' => false,
                    'error' => 'Missing required arguments: [belongsTo, templateType]'
            ]);
        }

        $find = null;
        $metaFieldId = null;

        if(isset($data['find']) and '' !== $data['find']){
            $find = $data['find'];
        }

        if(isset($data['metaFieldId']) and '' !== $data['metaFieldId']){
            $metaFieldId = $data['metaFieldId'];
        }

        return wp_send_json(TemplateRepository::get(
            MetaTypes::CUSTOM_POST_TYPE,
            $data['templateType'],
            $find,
            $metaFieldId)
        );
    }

    /**
     * Fetch custom post types
     *
     * @return mixed
     * @throws \Exception
     */
    public function fetchCustomPostTypesAction()
    {
        $postType = null;
        if(isset($_POST['data'])){
            $data = $this->sanitizeJsonData($_POST['data']);
            $postType = isset($data['postType']) ? $data['postType'] : null;
            $page = isset($data['page']) ? $data['page'] : null;
            $perPage = isset($data['perPage']) ? $data['perPage'] : null;
        }

        if($postType){
            return wp_send_json(CustomPostTypeRepository::get([
                    'postType' => $postType
            ]));
        }

        return wp_send_json(CustomPostTypeRepository::get([
                'page' => isset($page) ? $page : 1,
                'perPage' => isset($perPage) ? $perPage : 20,
        ]));
    }

    /**
     * Fetch custom post types total count
     *
     * @return mixed
     */
    public function fetchCustomPostTypesCountAction()
    {
        return wp_send_json(CustomPostTypeRepository::count());
    }

    public function fetchHeadersAndFootersAction()
    {
        $directory = get_template_directory();

        $headers = array_merge(glob($directory."/header.php"), glob($directory."/header-*.php"));
        $footers = array_merge(glob($directory."/footer.php"), glob($directory."/footer-*.php"));

        foreach ($headers as $index => $header){
            $headers[$index] = $this->cleanHeadersAndFootersName($header);
        }

        foreach ($footers as $index => $footer){
            $footers[$index] = $this->cleanHeadersAndFootersName($footer);
        }

        return wp_send_json([
                'headers' => $headers,
                'footers' => $footers,
        ]);
    }

    /**
     * Check periodically the license validity
     */
    public static function checkLicensePeriodicallyAction()
    {
        $licenseActivation = ACPT_License_Manager::getLicense();

        if(!$licenseActivation){
            return wp_send_json([
                'status' => 1,
                'success' => false,
                'message' => 'No license activated',
            ]);
        }

        $ttl = 10800; // 3 hours
        $transientKey = 'acpt_plugin_check_license';
        $checkLicense = get_transient( $transientKey );

        if(false === $checkLicense){
            $activation = LicenseApi::call('/license/activation/fetch', [
                'id' => $licenseActivation['activation_id'],
            ]);

            if(!isset($activation['code'])){
                ACPT_License_Manager::destroy();
            }

            set_transient( $transientKey, $licenseActivation['activation_id'], $ttl );

            return wp_send_json([
                    'status' => 2,
                    'success' => false,
                    'error' => 'Error fetching license activation',
            ]);
        }

        return wp_send_json([
                'status' => 3,
                'success' => true,
                'message' => 'License activated',
        ]);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function fetchLicenseAction()
    {
        if(!ACPT_License_Manager::isLicenseValid()){
            return wp_send_json([
                    'error' => 'License is not valid'
            ]);
        }

        $licenseActivation = ACPT_License_Manager::getLicense();
        $activation = LicenseApi::call('/license/activation/fetch', [
            'id' => $licenseActivation['activation_id'],
        ]);

        if(!isset($activation['code'])){

            ACPT_License_Manager::destroy();

            return wp_send_json([
                    'error' => 'Error during fetching the license'
            ]);
        }

        $currentVersion = ACPT_PLUGIN_VERSION;

        $pluginFetch = LicenseApi::call('/plugin/fetch');
        $remoteVersion = $pluginFetch['version'];
        $remoteVersion = str_replace(['v.', 'v'], '', $remoteVersion);

        $outOfDate = version_compare( $remoteVersion, $currentVersion, 'gt' );

        $plugin_name = 'advanced-custom-post-type';
        $upgradeUrl = self_admin_url('update.php?action=upgrade-plugin&plugin=') . $plugin_name;
        $upgradeUrl = wp_nonce_url($upgradeUrl, 'upgrade-plugin_' . $plugin_name);
        $upgradeUrl = htmlspecialchars_decode($upgradeUrl);

        $versionInfo = [
            'currentVersion' => $currentVersion,
            'remoteVersion' => $remoteVersion,
            'outOfDate' => $outOfDate,
            'upgradeUrl' => $upgradeUrl,
        ];

        $response = array_merge($activation, $versionInfo);

        return wp_send_json($response);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function deactivateLicenseAction()
    {
        if(!ACPT_License_Manager::isLicenseValid()){
            return wp_send_json([
                    'error' => 'License is not valid'
            ]);
        }

        $licenseActivation = ACPT_License_Manager::getLicense();
        $deactivation = LicenseApi::call('/license/deactivate', [
                'id' => $licenseActivation['activation_id']
        ]);

        if(!isset($deactivation['id'])){
            return wp_send_json([
                    'error' => 'Error during fetching the license'
            ]);
        }

        ACPT_License_Manager::destroy();

        return wp_send_json($deactivation['id']);
    }

    /**
     * @param $string
     *
     * @return string|string[]
     */
    private function cleanHeadersAndFootersName( $string)
    {
        $directory = get_template_directory();

        return str_replace([$directory, '/', '.php'],'', $string);
    }

	/**
	 * @throws \Exception
	 */
    public function exportCodeAction()
    {
	    if(isset($_POST['data'])){
		    $data = $this->sanitizeJsonData($_POST['data']);

		    if(!isset($data['find']) and !isset($data['belongsTo'])){
			    return wp_send_json([
				    'success' => false,
				    'error' => 'Missing params (`find`, `belongsTo`)'
			    ]);
		    }

		    return wp_send_json(ExportCodeStrings::export($data['belongsTo'], $data['find']));
	    }

	    return wp_send_json([
		    'success' => false,
		    'error' => 'no data was sent'
	    ]);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function fetchSettingsAction()
    {
        return wp_send_json(SettingsRepository::get());
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function fetchWooCommerceProductDataAction()
    {
        if(isset($_POST['data'])){
            $data = $this->sanitizeJsonData($_POST['data']);

            return wp_send_json(WooCommerceProductDataRepository::get($data));
        }

        return wp_send_json([]);
    }

    public function fetchWooCommerceProductDataFieldsAction()
    {
        if(isset($_POST['data'])){
            $data = $this->sanitizeJsonData($_POST['data']);

            if(!isset($data['id'])){
                return wp_send_json([
                        'success' => false,
                        'error' => 'Missing post id'
                ]);
            }

            $id = $data['id'];

            try {
                $return = WooCommerceProductDataRepository::getFields($id);
            } catch (\Exception $exception){
                $return = [
                        'success' => false,
                        'error' => $exception->getMessage()
                ];
            }

            return wp_send_json($return);
        }

        return wp_send_json([
                'success' => false,
                'error' => 'no id was sent'
        ]);
    }

    /**
     * Fetch theme's registered sidebars
     *
     * @return mixed
     */
    public function fetchSidebarsAction()
    {
        global $wp_registered_sidebars;

        $sidebars = [];

        foreach ($wp_registered_sidebars as $sidebar){
            $sidebars[] = $sidebar;
        }

        return wp_send_json($sidebars);
    }

    /**
     * Fetch Api keys
     *
     * @return mixed
     * @throws \Exception
     */
    public function fetchApiKeysAction()
    {
        if(isset($_POST['data'])){
            $data = $this->sanitizeJsonData($_POST['data']);
            $page = isset($data['page']) ? $data['page'] : null;
            $perPage = isset($data['perPage']) ? $data['perPage'] : null;
        }

        return wp_send_json(ApiRepository::getPaginated([
                'uid' => get_current_user_id(),
                'page' => isset($page) ? $page : 1,
                'perPage' => isset($perPage) ? $perPage : 20,
        ]));
    }

	/**
	 * Fetch
	 */
    public function fetchOptionPagesCountAction()
    {
	    return wp_send_json(OptionPageRepository::count());
    }

	/**
	 * Fetch option page
	 *
	 * @throws \Exception
	 */
    public function fetchOptionPageAction()
    {
	    if(isset($_POST['data'])){
		    $data = $this->sanitizeJsonData($_POST['data']);

		    if(!isset($data['slug'])){
			    return wp_send_json([
				    'success' => false,
				    'error' => 'no slug were sent'
			    ]);
		    }

		    return wp_send_json(OptionPageRepository::getByMenuSlug($data['slug']));
	    }

	    return wp_send_json([
		    'success' => false,
		    'error' => 'no params were sent'
	    ]);
    }

	/**
	 * Fetch paginated option pages
	 *
	 * @throws \Exception
	 */
	public function fetchOptionPagesAction()
	{
		if(isset($_POST['data'])){
			$data = $this->sanitizeJsonData($_POST['data']);
			$page = isset($data['page']) ? $data['page'] : null;
			$perPage = isset($data['perPage']) ? $data['perPage'] : null;
		}

		return wp_send_json(OptionPageRepository::get([
			'page' => isset($page) ? $page : 1,
			'perPage' => isset($perPage) ? $perPage : 20,
		]));
	}

    /**
     * Fetch API key count (by uid)
     *
     * @return mixed
     */
    public function fetchApiKeysCountAction()
    {
        return wp_send_json(ApiRepository::count([
                'uid' => get_current_user_id(),
        ]));
    }

	/**
	 * fetch CPTs or Taxonomies
	 * (used by copy meta box/field UI)
	 *
	 * @return mixed
	 * @throws \Exception
	 */
    public function fetchElementsAction()
    {
	    if(isset($_POST['data'])){
		    $data = $this->sanitizeJsonData($_POST['data']);

		    if(!isset($data['belongsTo'])){
			    return wp_send_json([
				    'success' => false,
				    'error' => 'Missing belongsTo'
			    ]);
		    }

		    $result = [];
		    $belongsTo = $data['belongsTo'];
		    $exclude = (isset($data['exclude'])) ? $data['exclude'] : null;

		    if($belongsTo === MetaTypes::CUSTOM_POST_TYPE){
		    	$cpts = CustomPostTypeRepository::get([
		    		'exclude' => $exclude
			    ], true);

		        foreach ($cpts as $cpt){
			        $result[] = ['label' => $cpt->getName(), 'value' => $cpt->getName()];
		        }
		    }

		    if($belongsTo === MetaTypes::TAXONOMY){
			    $taxs = TaxonomyRepository::get([
				    'exclude' => $exclude
			    ], true);

			    foreach ($taxs as $tax){
				    $result[] = ['label' => $tax->getSlug(), 'value' => $tax->getSlug()];
			    }
		    }

		    if($belongsTo === MetaTypes::OPTION_PAGE){
			    $pages = OptionPageRepository::get([
			    ], true);

			    foreach ($pages as $page){
			    	if($page->getMenuSlug() !== $exclude){
					    $result[] = ['label' => $page->getPageTitle(), 'value' => $page->getMenuSlug()];
				    }

				    foreach ($page->getChildren() as $childPage){
					    if($childPage->getMenuSlug() !== $exclude){
						    $result[] = ['label' => $childPage->getPageTitle(), 'value' => $childPage->getMenuSlug()];
					    }
				    }
			    }
		    }

		    return wp_send_json($result);
	    }

	    return wp_send_json([
		    'success' => false,
		    'error' => 'no params were sent'
	    ]);
    }

	/**
	 * fetch boxIds or different elements
	 * (used by copy meta field UI)
	 *
	 * @return mixed
	 * @throws \Exception
	 */
    public function fetchBoxesAction()
    {
	    if(isset($_POST['data'])){
		    $data = $this->sanitizeJsonData($_POST['data']);

		    if(!isset($data['belongsTo'])){
			    return wp_send_json([
				    'success' => false,
				    'error' => 'Missing belongsTo or find'
			    ]);
		    }

		    $result = [];
		    $belongsTo = $data['belongsTo'];
		    $find = isset($data['find']) ? $data['find'] : null;

		    $meta = MetaRepository::get([
			    'belongsTo' => $belongsTo,
			    'find' => $find,
			    'lazy' => true,
		    ]);

			foreach ($meta as $metaBoxModel){
				$result[] = ['label' => $metaBoxModel->getName(), 'value' => $metaBoxModel->getId()];
			}

		    return wp_send_json($result);
	    }

	    return wp_send_json([
		    'success' => false,
		    'error' => 'no params were sent'
	    ]);
    }

	/**
	 * Fetch meta fields ids
	 * (used by copy meta block UI)
	 *
	 * @throws \Exception
	 * @return mixed
	 */
    public function fetchFieldsAction()
    {
	    if(isset($_POST['data'])){
		    $data = $this->sanitizeJsonData($_POST['data']);

		    if(!isset($data['belongsTo']) or !isset($data['find']) or !isset($data['boxId'])){
			    return wp_send_json([
				    'success' => false,
				    'error' => 'Missing belongsTo, find or BoxId'
			    ]);
		    }

		    $result = [];
		    $belongsTo = $data['belongsTo'];
		    $find = $data['find'];
		    $boxId = $data['boxId'];

		    $metaBoxes = MetaRepository::get([
			    'belongsTo' => $belongsTo,
			    'find' => $find,
			    'id' => $boxId,
		    ]);

		    foreach ($metaBoxes as $metaBoxModel){
		    	foreach ($metaBoxModel->getFields() as $fieldModel){
		    		if($fieldModel->getType() === AbstractMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE){
					    $result[] = ['label' => $fieldModel->getName(), 'value' => $fieldModel->getId()];
				    }
			    }
		    }

		    return wp_send_json($result);
	    }

	    return wp_send_json([
		    'success' => false,
		    'error' => 'no postType was sent'
	    ]);
    }

    /**
     * fetch post data from id
     *
     * @return mixed
     */
    public function fetchPostDataAction()
    {
        if(isset($_POST['data'])){
            $data = $this->sanitizeJsonData($_POST['data']);

            if(!isset($data['id'])){
                return wp_send_json([
                        'success' => false,
                        'error' => 'Missing post id'
                ]);
            }

            $postId = $data['id'];
            $post = get_post($postId, "ARRAY_A");

            $isWooCommerce = $post['post_type'] === 'product' and class_exists( 'woocommerce' );

            $post['thumbnail'] = [
                    'id' => get_post_thumbnail_id($postId),
                    'title' => get_post(get_post_thumbnail_id($postId))->post_title,
                    'excerpt' => get_post(get_post_thumbnail_id($postId))->post_excerpt,
                    'description' => get_post(get_post_thumbnail_id($postId))->post_content,
                    'url' => get_the_post_thumbnail_url($postId),
            ];
            $post['author'] = get_userdata($post['post_author']);
            $post['links'] =  [
                    'prev' => WPLinks::getPrevLink($postId),
                    'next' => WPLinks::getNextLink($postId),
            ];

            $post['taxonomies'] = WPLinks::getTaxonomiesLinks($postId, $post['post_type']);
            $post['isWooCommerce'] = $isWooCommerce;
            $post['WooCommerceData'] = ($isWooCommerce) ? WooCommerceNormalizer::objectToArray($postId) : [];

            return wp_send_json($post);
        }

        return wp_send_json([
                'success' => false,
                'error' => 'no postType was sent'
        ]);
    }

    /**
     * Fetch posts
     *
     * @return mixed
     * @throws \Exception
     */
    public function fetchPostsAction()
    {
        if(isset($_POST['data'])){
            $data = $this->sanitizeJsonData($_POST['data']);

            if(!isset($data['postType'])){
                return wp_send_json([
                        'success' => false,
                        'error' => 'Missing postType'
                ]);
            }

            $loop = (new ACPT_Hooks())->loop([
                    'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
                    'find' => $data['postType'],
                    'perPage' => isset($data['perPage']) ? $data['perPage'] : -1,
                    'sortOrder' => (isset($data['sortOrder'])) ? $data['sortOrder']: 'ASC',
                    'sortBy' => (isset($data['sortBy'])) ? $data['sortBy']: null,
            ]);

            while ( $loop->have_posts() ) : $loop->the_post();
                $return[] = [
                        'id' => get_the_ID(),
                        'title' => get_the_title(),
                ];
            endwhile;

            wp_reset_postdata();

            return wp_send_json($return);
        }

        return wp_send_json([
                'success' => false,
                'error' => 'no postType was sent'
        ]);
    }

    /**
     * @return mixed
     */
    public function fetchTaxonomiesCountAction()
    {
        return wp_send_json(TaxonomyRepository::count());
    }

    /**
     * Fetch taxonomies
     *
     * @return mixed
     * @throws \Exception
     */
    public function fetchTaxonomiesAction()
    {
        $taxonomy = null;
        if(isset($_POST['data'])){
            $data = $this->sanitizeJsonData($_POST['data']);
            $taxonomy = isset($data['taxonomy']) ? $data['taxonomy'] : null;
            $page = isset($data['page']) ? $data['page'] : null;
            $perPage = isset($data['perPage']) ? $data['perPage'] : null;
        }

        if($taxonomy){
            return wp_send_json(TaxonomyRepository::get([
                    'taxonomy' => $taxonomy
            ]));
        }

        return wp_send_json(TaxonomyRepository::get([
                'page' => isset($page) ? $page : 1,
                'perPage' => isset($perPage) ? $perPage : 20,
        ]));
    }

    /**
     * @return mixed
     */
    public function fetchTemplatesCountAction()
    {
        return wp_send_json(TemplateRepository::count());
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function fetchTermsAction()
    {
        if(isset($_POST['data'])){
            $data = $this->sanitizeJsonData($_POST['data']);

            if(!isset($data['taxonomy'])){
                return wp_send_json([
                    'success' => false,
                    'error' => 'Missing taxonomy'
                ]);
            }

            $terms = get_terms([
                'taxonomy' => $data['taxonomy'],
                'hide_empty' => false,
            ]);

            if(isset($data['format']) and $data['format'] === 'short'){

	            $data = [];

	            foreach ($terms as $term){
                    $data[] = ["label" => $term->name, "value" => (int)$term->term_id];
                }

	            return wp_send_json($data);
            }

            return wp_send_json($terms);
        }



        return wp_send_json([
            'success' => false,
            'error' => 'no data was sent'
        ]);
    }

    /**
     * Fetch taxonomies
     *
     * @return mixed
     * @throws \Exception
     */
    public function fetchTemplatesAction()
    {
        if(isset($_POST['data'])){
            $data = $this->sanitizeJsonData($_POST['data']);
            $page = isset($data['page']) ? $data['page'] : null;
            $perPage = isset($data['perPage']) ? $data['perPage'] : null;
        }

        return wp_send_json(TemplateRepository::getAll(
                isset($page) ? $page : 1,
                isset($perPage) ? $perPage : 20
        ));
    }

    /**
     * Generate an API key
     *
     * @return mixed
     * @throws \Exception
     */
    public function generateApiKeyAction()
    {
        $id = Uuid::v4();
        $uid =  get_current_user_id();
        $apiKey = Strings::randomString();
        $apiSecret = Strings::randomString();
        $now = new \DateTime();

        $apiKeyModel = new ApiKeyModel(
                $id,
                $uid,
                $apiKey,
                $apiSecret,
                $now
        );

        try {
            ApiRepository::save($apiKeyModel);

            return wp_send_json([
                    'success' => true,
                    'data' => [
                            'key' => $apiKey,
                            'secret' => $apiSecret,
                    ]
            ]);

        } catch (\Exception $exception){
            return wp_send_json([
                    'error' => $exception->getMessage()
            ]);
        }
    }

	/**
	 * @return mixed
	 */
    public function generateFlexibleBlockAction()
    {
	    if(isset($_POST['data'])) {
		    $data = $this->sanitizeJsonData($_POST['data']);

		    if(!isset($data['blockId']) and !isset($data['mediaType']) and !isset($data['index'])){
			    return wp_send_json([
				    'success' => false,
				    'error' => 'Missing `fieldId` or `blockId` or `index` or `mediaType` params'
			    ]);
		    }

		    $blockId = $data['blockId'];
		    $mediaType = $data['mediaType'];
		    $index = $data['index'];

		    try {
			    $metaBlock = MetaRepository::getMetaBlockById([
				    'belongsTo' => $mediaType,
				    'id' => $blockId,
			    ]);

			    if(null === $metaBlock){
				    return wp_send_json([
					    'success' => false,
					    'error' => 'No meta block found'
				    ]);
			    }

			    $repeaterFieldGenerator = new FieldBlockGenerator($metaBlock);

			    return wp_send_json([
				    'block' => $repeaterFieldGenerator->generate($index)
			    ]);

		    } catch (\Exception $exception){
			    return wp_send_json([
				    'success' => false,
				    'error' => $exception->getMessage()
			    ]);
		    }
	    }

	    return wp_send_json([
		    'success' => false,
		    'error' => 'no data was sent'
	    ]);
    }

	/**
	 * @return mixed
	 */
	public function generateFlexibleGroupedFieldsAction()
	{
		if(isset($_POST['data'])) {
			$data = $this->sanitizeJsonData($_POST['data']);

			if(!isset($data['blockId']) and !isset($data['mediaType']) and !isset($data['elementIndex']) and !isset($data['blockIndex'])){
				return wp_send_json([
					'success' => false,
					'error' => 'Missing `fieldId` or `blockId` or `index` or `mediaType` params'
				]);
			}

			$blockId = $data['blockId'];
			$mediaType = $data['mediaType'];
			$elementIndex = $data['elementIndex'];
			$blockIndex = $data['blockIndex'];

			try {
				$metaBlock = MetaRepository::getMetaBlockById([
					'belongsTo' => $mediaType,
					'id' => $blockId,
				]);

				if(null === $metaBlock){
					return wp_send_json([
						'success' => false,
						'error' => 'No meta block found'
					]);
				}

				$repeaterFieldGenerator = new FieldBlockGenerator($metaBlock);

				return wp_send_json([
					'fields' => $repeaterFieldGenerator->generateElement($elementIndex, $blockIndex)
				]);

			} catch (\Exception $exception){
				return wp_send_json([
					'success' => false,
					'error' => $exception->getMessage()
				]);
			}
		}

		return wp_send_json([
			'success' => false,
			'error' => 'no data was sent'
		]);
	}

    /**
     * @return mixed
     */
    public function generateGroupedFieldsAction()
    {
        if(isset($_POST['data'])) {
            $data = $this->sanitizeJsonData($_POST['data']);

            if(!isset($data['id']) and !isset($data['mediaType'])){
                return wp_send_json([
                        'success' => false,
                        'error' => 'Missing `id` or `mediaType` params'
                ]);
            }

            $id = $data['id'];
            $mediaType = $data['mediaType'];
            $index = $data['index'];

            try {
                $metaField = MetaRepository::getMetaField([
                    'belongsTo' => $mediaType,
                    'id' => $id,
                ]);

                if(null === $metaField){
                    return wp_send_json([
                            'success' => false,
                            'error' => 'No meta field found'
                    ]);
                }

                $repeaterFieldGenerator = new RepeaterFieldGenerator($metaField);

                return wp_send_json([
                        'fields' => $repeaterFieldGenerator->generate($index)
                ]);

            } catch (\Exception $exception){
                return wp_send_json([
                        'success' => false,
                        'error' => $exception->getMessage()
                ]);
            }
        }

        return wp_send_json([
            'success' => false,
            'error' => 'no data was sent'
        ]);
    }

    /**
     * @return mixed
     */
    public function importFileAction()
    {
        if(empty($_FILES)){
            return wp_send_json([
                    'error' => 'No files uploaded'
            ]);
        }

        $file = $_FILES['file'];

        // validate size
        if($file['size'] > 2097152){
            return wp_send_json([
                    'error' => 'File too large. Max size: 2Mb'
            ]);
        }

        // validate extension
        $path_parts = pathinfo($_FILES["file"]["name"]);
        $extension = $path_parts['extension'];

        if($extension !== 'acpt'){
            return wp_send_json([
                    'error' => 'Only .acpt format is allowed'
            ]);
        }

        // upload file
        $contentFileInfo = wp_handle_upload( $file, [
                'test_form' => false,
                'test_type' => false,
        ] );

        $content = json_decode(file_get_contents($contentFileInfo['file']), true);

        // validate content
        if(!ImportFileValidator::validate($content)){
            return wp_send_json([
                    'error' => 'Wrong data, not suitable for import'
            ]);
        }

        try {
            // import content
	        ImportRepository::import($content);

            return wp_send_json([
                    'success' => true,
                    'data' => $content
            ]);

        } catch (\Exception $exception){
            return wp_send_json([
                    'error' => (!empty($exception->getMessage())) ? $exception->getMessage() : 'Error during import occurred'
            ]);
        }
    }

	/**
	 * Return the string translations
	 * @throws \Exception
	 */
    public function languagesAction()
    {
	    $fileHandler = new FileSystem(__DIR__.'/../../i18n/languages/advanced-custom-post-type.pot');
	    $poParser = new Parser($fileHandler);
	    $catalog  = $poParser->parse();
		$entries = [];

		foreach( $catalog->getEntries() as $entry){
			$entries[$entry->getMsgId()] = esc_html__($entry->getMsgId(), ACPT_PLUGIN_NAME);
		}

	    return wp_send_json($entries);
    }

    /**
     * Reset all custom post type meta
     *
     * @return mixed
     */
    public function resetCustomPostTypesAction()
    {
        return wp_send_json([]);
    }

    /**
     * Reset all taxonomies
     *
     * @return mixed
     */
    public function resetTaxonomiesAction()
    {
        return wp_send_json([]);
    }

    /**
     * Reset all taxonomies
     *
     * @return mixed
     */
    public function resetWooCommerceProductDataAction()
    {
        return wp_send_json([]);
    }

    /**
     * Creates a custom post type
     */
    public function saveCustomPostTypeAction()
    {
        $data = $this->sanitizeJsonData($_POST['data']);

        $supports = [];

        if($data[1]["support_0"] !== false){ $supports[] = $data[1]["support_0"]; }
        if($data[1]["support_1"] !== false){ $supports[] = $data[1]["support_1"]; }
        if($data[1]["support_2"] !== false){ $supports[] = $data[1]["support_2"]; }
        if($data[1]["support_3"] !== false){ $supports[] = $data[1]["support_3"]; }
        if($data[1]["support_4"] !== false){ $supports[] = $data[1]["support_4"]; }
        if($data[1]["support_5"] !== false){ $supports[] = $data[1]["support_5"]; }
        if($data[1]["support_6"] !== false){ $supports[] = $data[1]["support_6"]; }
        if($data[1]["support_7"] !== false){ $supports[] = $data[1]["support_7"]; }
        if($data[1]["support_8"] !== false){ $supports[] = $data[1]["support_8"]; }
        if($data[1]["support_9"] !== false){ $supports[] = $data[1]["support_9"]; }
        if($data[1]["support_10"] !== false){ $supports[] = $data[1]["support_10"]; }

        // persist $model on DB
        try {
            $id = (CustomPostTypeRepository::exists($data[1]["post_name"])) ? CustomPostTypeRepository::getId($data[1]["post_name"]) : Uuid::v4();
            $model = CustomPostTypeModel::hydrateFromArray([
                    'id' => $id,
                    'name' => $data[1]["post_name"],
                    'singular' => $data[1]["singular_label"],
                    'plural' => $data[1]["plural_label"],
                    'icon' => (isset($data[1]["icon"]['value'])) ? $data[1]["icon"]['value']: $data[1]["icon"],
                    'native' => false,
                    'supports' => $supports,
                    'labels' => $data[2],
                    'settings' => $data[3]
            ]);

            CustomPostTypeRepository::save($model);
            $this->resetPermalinkStructure();

            $return = [
                    'success' => true
            ];
        } catch (\Exception $exception){
            $return = [
                    'success' => false,
                    'error' => $exception->getMessage()
            ];
        }

        return wp_send_json($return);
    }

    /**
     * Save custom post type template
     *
     * @return mixed
     */
    public function saveTemplateAction()
    {
        $data = $this->sanitizeJsonData($_POST['data']);

        // json, postType, templateType
        if(!isset($data['html']) and !isset($data['json']) and !isset($data['belongsTo']) and !isset($data['templateType'])){
            return wp_send_json([
                    'success' => false,
                    'error' => 'Missing required arguments: [html, json, belongsTo, templateType]'
            ]);
        }

        $metaFieldId = (isset($data['metaFieldId']) and $data['metaFieldId'] !== null) ? $data['metaFieldId'] : null;
        $find  = (isset($data['find']) and $data['find'] !== null) ? $data['find'] : null;

        // persist $model on DB
        try {
            $template = TemplateRepository::get($data['belongsTo'], $data['templateType'], $find, $metaFieldId);

            $newTemplate = TemplateModel::hydrateFromArray([
                    'id' => $template ? $template->getId() : Uuid::v4(),
                    'belongsTo' => $data['belongsTo'],
                    'templateType' =>  $data['templateType'],
                    'json' =>  $data['json'],
                    'html' =>  $data['html'],
                    'find' => $find,
                    'meta' =>  isset($data['meta']) ? $data['meta'] : [],
                    'metaFieldId' => $metaFieldId,
            ]);

            TemplateRepository::save($newTemplate);

            $return = [
                    'success' => true
            ];

        } catch (\Exception $exception){
            $return = [
                    'success' => false,
                    'error' => $exception->getMessage()
            ];
        }

        return wp_send_json($return);
    }

    /**
     * Saves meta
     */
    public function saveMetaAction()
    {
        $data = $this->sanitizeJsonData($_POST['data']);
        $ids = [];
        $arrayOfBoxNames = [];
        
        $belongsTo  = isset($data[0]) ? $data[0]['belongsTo'] : null;
        $find  = isset($data[0]) ? $data[0]['find'] : null;

        // OLD format, keep compatibility
        if($find === null){
            $find  = isset($data[0]) ? $data[0]['postType'] : null;
        }

        if($belongsTo !== MetaTypes::USER and $find === null){
            return wp_send_json([
                'success' => false,
                'error' => 'No data sent'
            ]);
        }

        $ids[$find] = [
            'boxes' => [],
            'fields' => [],
            'options' => [],
            'visibilityConditions' => [],
            'relations' => [],
            'blocks' => [],
        ];

        // for allowing self post relationships
        $data = self::normalizeDataWithRelations($data);

        // persist $model on DB
        try {
            foreach ($data as $boxIndex => $box ) {

                $boxModel = null;

                switch ($belongsTo){
                    case null:
                    case MetaTypes::CUSTOM_POST_TYPE:
                        $boxModel = CustomPostTypeMetaBoxModel::hydrateFromArray([
                            'id' => $box['id'],
                            'postType' => $find,
                            'name' =>  $box['title'],
                            'sort' =>  ($boxIndex+1)
                        ]);
                        break;

                    case MetaTypes::TAXONOMY:
                        $boxModel = TaxonomyMetaBoxModel::hydrateFromArray([
                            'id' => $box['id'],
                            'taxonomy' => $find,
                            'name' =>  $box['title'],
                            'sort' =>  ($boxIndex+1)
                        ]);
                        break;

	                case MetaTypes::OPTION_PAGE:
		                $boxModel = OptionPageMetaBoxModel::hydrateFromArray([
			                'id' => $box['id'],
			                'optionPage' => $find,
			                'name' =>  $box['title'],
			                'sort' =>  ($boxIndex+1)
		                ]);
		                break;

                    case MetaTypes::USER:
                        $boxModel = UserMetaBoxModel::hydrateFromArray([
                            'id' => $box['id'],
                            'name' =>  $box['title'],
                            'sort' =>  ($boxIndex+1)
                        ]);
                        break;
                }

                if($boxModel === null){
                    return wp_send_json([
                        'success' => false,
                        'error' => 'Cannot create $boxModel object'
                    ]);
                }

                if(isset($box['label'])){
	                $boxModel->changeLabel($box['label']);
                }

                $ids[$find]['boxes'][] = $box['id'];

                if(isset($box['fields'])){
                    $arrayOfFieldNames = [];

                    foreach ($box['fields'] as $fieldIndex => $field) {

                        $fieldModel = null;

                        switch ($belongsTo) {
                            case null:
                            case MetaTypes::CUSTOM_POST_TYPE:
                                $fieldModel = CustomPostTypeMetaBoxFieldModel::hydrateFromArray([
                                    'id' => $field['id'],
                                    'title' => $field['name'],
                                    'type' => $field['type'],
                                    'defaultValue' => isset($field['defaultValue']) ? $field['defaultValue'] : null,
                                    'description' => isset($field['description']) ? $field['description'] : null,
                                    'showInArchive' => isset($field['showInArchive']) ? $field['showInArchive'] : false,
                                    'required' => isset($field['isRequired']) ? $field['isRequired'] : false,
                                    'metaBox' => $boxModel,
                                    'sort' =>  ($fieldIndex+1)
                                ]);
                                break;

                            case MetaTypes::TAXONOMY:
                                $fieldModel = TaxonomyMetaBoxFieldModel::hydrateFromArray([
                                    'id' => $field['id'],
                                    'name' => $field['name'],
                                    'type' => $field['type'],
                                    'defaultValue' => isset($field['defaultValue']) ? $field['defaultValue'] : null,
                                    'description' => isset($field['description']) ? $field['description'] : null,
                                    'required' => isset($field['isRequired']) ? $field['isRequired'] : false,
                                    'metaBox' => $boxModel,
                                    'sort' =>  ($fieldIndex+1)
                                ]);
                                break;

	                        case MetaTypes::OPTION_PAGE:
		                        $fieldModel = OptionPageMetaBoxFieldModel::hydrateFromArray([
			                        'id' => $field['id'],
			                        'name' => $field['name'],
			                        'type' => $field['type'],
			                        'defaultValue' => isset($field['defaultValue']) ? $field['defaultValue'] : null,
			                        'description' => isset($field['description']) ? $field['description'] : null,
			                        'required' => isset($field['isRequired']) ? $field['isRequired'] : false,
			                        'metaBox' => $boxModel,
			                        'sort' =>  ($fieldIndex+1)
		                        ]);
		                        break;

                            case MetaTypes::USER:
                                $fieldModel = UserMetaBoxFieldModel::hydrateFromArray([
                                    'id' => $field['id'],
                                    'name' => $field['name'],
                                    'type' => $field['type'],
                                    'defaultValue' => isset($field['defaultValue']) ? $field['defaultValue'] : null,
                                    'description' => isset($field['description']) ? $field['description'] : null,
                                    'showInArchive' => isset($field['showInArchive']) ? $field['showInArchive'] : false,
                                    'required' => isset($field['isRequired']) ? $field['isRequired'] : false,
                                    'metaBox' => $boxModel,
                                    'sort' =>  ($fieldIndex+1)
                                ]);
                                break;
                        }

                        if($fieldModel === null){
                            return wp_send_json([
                                'success' => false,
                                'error' => 'Cannot create $fieldModel object'
                            ]);
                        }

	                    if(isset($field['quickEdit'])){
		                    $fieldModel->setQuickEdit($field['quickEdit']);
	                    }

	                    if(isset($field['filterableInAdmin'])){
		                    $fieldModel->setFilterableInAdmin($field['filterableInAdmin']);
	                    }

                        $ids[$find]['fields'][] = $field['id'];

                        $fieldModel->changeName(Strings::getTheFirstAvailableName($fieldModel->getName(), $arrayOfFieldNames));

                        if(isset($field['parentId']) and null !== $field['parentId']){
                            $fieldModel->setParentId($field['parentId']);
                        }

                        $arrayOfFieldNames[] = $fieldModel->getName();

	                    if(isset($field['blocks'])){
		                    foreach ($field['blocks'] as $blockIndex => $block) {
			                    $blockModel = MetaBoxFieldBlockModel::hydrateFromArray([
				                    'id' => $block['id'],
				                    'label' => $block['label'],
				                    'name' => $block['name'],
				                    'sort' => ($blockIndex+1),
				                    'metaBoxField' => $fieldModel,
			                    ]);

			                    if(isset($block['fields'])){
				                    foreach ($block['fields'] as $blockFieldIndex => $blockField) {

					                    $blockFieldModel = null;

					                    switch ($belongsTo) {
						                    case null:
						                    case MetaTypes::CUSTOM_POST_TYPE:
						                    $blockFieldModel = CustomPostTypeMetaBoxFieldModel::hydrateFromArray([
								                    'id' => $blockField['id'],
								                    'title' => $blockField['name'],
								                    'type' => $blockField['type'],
								                    'defaultValue' => isset($blockField['defaultValue']) ? $blockField['defaultValue'] : null,
								                    'description' => isset($blockField['description']) ? $blockField['description'] : null,
								                    'showInArchive' => isset($blockField['showInArchive']) ? $blockField['showInArchive'] : false,
								                    'required' => isset($blockField['isRequired']) ? $blockField['isRequired'] : false,
								                    'metaBox' => $boxModel,
								                    'sort' =>  ($blockFieldIndex+1)
							                    ]);
							                    break;

						                    case MetaTypes::TAXONOMY:
							                    $blockFieldModel = TaxonomyMetaBoxFieldModel::hydrateFromArray([
								                    'id' => $blockField['id'],
								                    'name' => $blockField['name'],
								                    'type' => $blockField['type'],
								                    'defaultValue' => isset($blockField['defaultValue']) ? $blockField['defaultValue'] : null,
								                    'description' => isset($blockField['description']) ? $blockField['description'] : null,
								                    'required' => isset($blockField['isRequired']) ? $blockField['isRequired'] : false,
								                    'metaBox' => $boxModel,
								                    'sort' =>  ($blockFieldIndex+1)
							                    ]);
							                    break;

						                    case MetaTypes::OPTION_PAGE:
							                    $blockFieldModel = OptionPageMetaBoxFieldModel::hydrateFromArray([
								                    'id' => $blockField['id'],
								                    'name' => $blockField['name'],
								                    'type' => $blockField['type'],
								                    'defaultValue' => isset($blockField['defaultValue']) ? $blockField['defaultValue'] : null,
								                    'description' => isset($blockField['description']) ? $blockField['description'] : null,
								                    'required' => isset($blockField['isRequired']) ? $blockField['isRequired'] : false,
								                    'metaBox' => $boxModel,
								                    'sort' =>  ($blockFieldIndex+1)
							                    ]);
							                    break;

						                    case MetaTypes::USER:
							                    $blockFieldModel = UserMetaBoxFieldModel::hydrateFromArray([
								                    'id' => $blockField['id'],
								                    'name' => $blockField['name'],
								                    'type' => $blockField['type'],
								                    'defaultValue' => isset($blockField['defaultValue']) ? $blockField['defaultValue'] : null,
								                    'description' => isset($blockField['description']) ? $blockField['description'] : null,
								                    'showInArchive' => isset($blockField['showInArchive']) ? $blockField['showInArchive'] : false,
								                    'required' => isset($blockField['isRequired']) ? $blockField['isRequired'] : false,
								                    'metaBox' => $boxModel,
								                    'sort' =>  ($blockFieldIndex+1)
							                    ]);
							                    break;
					                    }

					                    $ids[$find]['fields'][] = $blockFieldModel->getId();

					                    $blockFieldModel->setBlockId($blockModel->getId());

					                    if(isset($blockField['advancedOptions'])){
						                    foreach ($blockField['advancedOptions'] as $option) {
							                    $advancedOptionModel = MetaBoxFieldAdvancedOptionModel::hydrateFromArray([
								                    'id' => $option['id'],
								                    'key' => $option['key'],
								                    'value' => $option['value'],
								                    'metaBoxField' => $blockFieldModel,
							                    ]);

							                    $blockFieldModel->addAdvancedOption($advancedOptionModel);
						                    }
					                    }

					                    if(isset($blockField['options'])){
						                    foreach ($blockField['options'] as $optionIndex => $option) {
							                    $optionModel = MetaBoxFieldOptionModel::hydrateFromArray([
								                    'id' => $option['id'],
								                    'label' => $option['label'],
								                    'value' => $option['value'],
								                    'metaBoxField' => $blockFieldModel,
								                    'sort' =>  ($optionIndex+1)
							                    ]);

							                    $ids[$find]['options'][] = $option['id'];

							                    $blockFieldModel->addOption($optionModel);
						                    }
					                    }

					                    if(isset($blockField['visibilityConditions'])){

						                    VisibilityConditionValidator::validate($blockFieldModel, $blockField['visibilityConditions']);

						                    foreach ($blockField['visibilityConditions'] as $visibilityIndex => $visibility) {
							                    $visibilityConditionModel = MetaBoxFieldVisibilityModel::hydrateFromArray([
								                    'id' => $visibility['id'],
								                    'type' => $visibility['type'],
								                    'value' => $visibility['value'],
								                    'operator' => $visibility['operator'],
								                    'logic' => (isset($visibility['logic'])) ? $visibility['logic'] : null,
								                    'sort' => ($visibilityIndex+1),
								                    'metaBoxField' => $blockFieldModel
							                    ]);

							                    $ids[$find]['visibilityConditions'][] = $visibility['id'];

							                    $blockFieldModel->addVisibilityCondition($visibilityConditionModel);
						                    }
					                    }

					                    $blockModel->addField($blockFieldModel);
				                    }
			                    }

			                    $ids[$find]['blocks'][] = $blockModel->getId();

			                    $fieldModel->addBlock($blockModel);
		                    }
	                    }

                        if(isset($field['advancedOptions'])){
                            foreach ($field['advancedOptions'] as $option) {
                                $advancedOptionModel = MetaBoxFieldAdvancedOptionModel::hydrateFromArray([
                                    'id' => $option['id'],
                                    'key' => $option['key'],
                                    'value' => $option['value'],
                                    'metaBoxField' => $fieldModel,
                                ]);

                                $fieldModel->addAdvancedOption($advancedOptionModel);
                            }
                        }

                        if(isset($field['options'])){
                            foreach ($field['options'] as $optionIndex => $option) {
                                $optionModel = MetaBoxFieldOptionModel::hydrateFromArray([
                                    'id' => $option['id'],
                                    'label' => $option['label'],
                                    'value' => $option['value'],
                                    'metaBoxField' => $fieldModel,
                                    'sort' =>  ($optionIndex+1)
                                ]);

                                $ids[$find]['options'][] = $option['id'];

                                $fieldModel->addOption($optionModel);
                            }
                        }

                        if(isset($field['visibilityConditions'])){

                            VisibilityConditionValidator::validate($fieldModel, $field['visibilityConditions']);

                            foreach ($field['visibilityConditions'] as $visibilityIndex => $visibility) {
                                $visibilityConditionModel = MetaBoxFieldVisibilityModel::hydrateFromArray([
                                    'id' => $visibility['id'],
                                    'type' => $visibility['type'],
                                    'value' => $visibility['value'],
                                    'operator' => $visibility['operator'],
                                    'logic' => (isset($visibility['logic'])) ? $visibility['logic'] : null,
                                    'sort' => ($visibilityIndex+1),
                                    'metaBoxField' => $fieldModel
                                ]);

                                $ids[$find]['visibilityConditions'][] = $visibility['id'];

                                $fieldModel->addVisibilityCondition($visibilityConditionModel);
                            }
                        }

                        if(isset($field['relations'])){
                            foreach ($field['relations'] as $relationIndex => $relation) {

                                $relatedCustomPostType = CustomPostTypeRepository::get([
                                        'postType' => $relation['relatedPostType']
                                ], true)[0];

                                $relationModel = MetaBoxFieldRelationshipModel::hydrateFromArray([
                                    'id' => $relation['id'],
                                    'relationship' => $relation['type'],
                                    'relatedCustomPostType' => $relatedCustomPostType,
                                    'metaBoxField' => $fieldModel,
                                ]);

                                if(isset($relation['inversedFieldId'])){
                                    $inversedBy = MetaRepository::getMetaField([
                                        'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
                                        'id' => $relation['inversedFieldId'],
                                    ]);
                                    $relationModel->setInversedBy($inversedBy);
                                }

                                $ids[$find]['relations'][] = $relation['id'];

                                $fieldModel->removeRelation($relationModel);
                                $fieldModel->addRelation($relationModel);
                            }
                        }

                        $boxModel->addField($fieldModel);
                    }
                }

                $boxModel->changeName(Strings::getTheFirstAvailableName($boxModel->getName(), $arrayOfBoxNames));
                $arrayOfBoxNames[] = $boxModel->getName();

                MetaRepository::saveMetaBox($boxModel);
            }

            // remove orphans
            foreach ($ids as $find => $childrenIds){
                MetaRepository::removeMetaOrphans([
                    'belongsTo' => $belongsTo,
                    'find' => $find,
                    'ids' => $childrenIds,
                ]);
            }

	        // remove orphan blocks
	        MetaRepository::removeOrphanBlocks();

            // remove orphan visibility conditions
            MetaRepository::removeOrphanVisibilityConditions();

            // remove orphan relationships
            MetaRepository::removeOrphanRelationships();

            $return = [
                    'ids' => $ids,
                    'success' => true
            ];
        } catch (\Exception $exception){
            $return = [
                    'success' => false,
                    'error' => $exception->getMessage()
            ];
        }

        return wp_send_json($return);
    }

	/**
	 * @param $data
	 *
	 * @return mixed
	 */
	private function normalizeDataWithRelations($data)
	{
		foreach ($data as $boxIndex => $box){
			if(!empty($box['fields'])){
				foreach ($box['fields'] as $fieldIndex => $field){
					if($field['type'] === CustomPostTypeMetaBoxFieldModel::POST_TYPE and !empty($field['relations'])){
						$relation = $field['relations'][0];
						$inversedBoxId = $relation['inversedBoxId'];
						$inversedFieldId = $relation['inversedFieldId'];

						// find this inversed field and modify it
						if($inversedBoxId !== null and $inversedFieldId !== null){
							$inversedField = null;
							$inversedFieldBoxIndex = null;
							$inversedFieldIndex = null;

							foreach ($data as $bi => $b){
								foreach ($b['fields'] as $fi => $f){
									if($f['id'] === $inversedFieldId and $b['id'] === $inversedBoxId ) {
										$inversedField = $f;
										$inversedFieldBoxIndex = $bi;
										$inversedFieldIndex = $fi;
									}
								}
							}

							if($inversedField and $inversedField['type'] !== CustomPostTypeMetaBoxFieldModel::POST_TYPE){
								switch ($relation['type']) {
									case MetaBoxFieldRelationshipModel::ONE_TO_ONE_BI:
										$oppositeRelation = MetaBoxFieldRelationshipModel::ONE_TO_ONE_BI;
										break;

									case MetaBoxFieldRelationshipModel::ONE_TO_MANY_BI:
										$oppositeRelation = MetaBoxFieldRelationshipModel::MANY_TO_ONE_BI;
										break;

									case MetaBoxFieldRelationshipModel::MANY_TO_ONE_BI:
										$oppositeRelation = MetaBoxFieldRelationshipModel::ONE_TO_MANY_BI;
										break;

									case MetaBoxFieldRelationshipModel::MANY_TO_MANY_BI:
										$oppositeRelation = MetaBoxFieldRelationshipModel::MANY_TO_MANY_BI;
										break;
								}

								$inversedField['type'] = CustomPostTypeMetaBoxFieldModel::POST_TYPE;
								$inversedField['relations'][] = [
									'id' => Uuid::v4(),
									'boxId' => $inversedField['boxId'],
									'fieldId' => $inversedField['id'],
									'relatedPostType' => $relation['relatedPostType'],
									'type' => $oppositeRelation,
									'inversedBoxId' => $box['id'],
									'inversedBoxName' => $box['title'],
									'inversedFieldId' => $field['id'],
									'inversedFieldName' => $field['name'],
								];

								$data[$inversedFieldBoxIndex]['fields'][$inversedFieldIndex] = $inversedField;
							}
						}
					}
				}
			}
		}

		return $data;
	}

	/**
	 * Save option page
	 */
    public function saveOptionPagesAction()
    {
	    $data = $this->sanitizeJsonData($_POST['data']);
	    $pageSlugs = [];
		$ids = [];

	    try {
		    foreach ($data as $pageIndex => $page ) {

			    $pageModel = OptionPageModel::hydrateFromArray([
			    	'id' => $page['id'],
			    	'pageTitle' => $page['pageTitle'],
			    	'menuTitle' => $page['menuTitle'],
			    	'menuSlug' => $page['menuSlug'],
			    	'capability' => isset($page['capability']['value']) ? $page['capability']['value'] : $page['capability'],
			    	'icon' => isset($page['icon']['value']) ? $page['icon']['value'] : $page['icon'],
			    	'position' => $page['position'],
			    	'description' => $page['description'],
			    	'sort' => ($pageIndex+1),
			    ]);

			    $pageModel->setMenuSlug(Strings::getTheFirstAvailableName($pageModel->getMenuSlug(), $pageSlugs));
			    $ids[] = $pageModel->getId();
			    $pageSlugs[] = $pageModel->getMenuSlug();

				foreach ($page['children'] as $childIndex => $child){
					$childPageModel = OptionPageModel::hydrateFromArray([
						'id' => $child['id'],
						'parentId' => $pageModel->getId(),
						'pageTitle' => $child['pageTitle'],
						'menuTitle' => $child['menuTitle'],
						'menuSlug' => $child['menuSlug'],
						'capability' => isset($page['capability']['value']) ? $page['capability']['value'] : $page['capability'],
						'position' => $child['position'],
						'description' => $child['description'],
						'sort' => ($childIndex+1),
					]);

					$childPageModel->setMenuSlug(Strings::getTheFirstAvailableName($childPageModel->getMenuSlug(), $pageSlugs));
					$ids[] = $childPageModel->getId();
					$pageSlugs[] = $childPageModel->getMenuSlug();

					$pageModel->addChild($childPageModel);
				}

			    OptionPageRepository::save($pageModel);
		    }

			// get all ids
		    $savedIds = OptionPageRepository::getAllIds();

		    foreach ($savedIds as $savedId){
		    	if(!in_array($savedId, $ids)){
		    		$orphan = OptionPageRepository::getById($savedId);
				    OptionPageRepository::delete($orphan);
			    }
		    }

		    $return = [
			    'ids' => $ids,
			    'success' => true
		    ];
	    } catch (\Exception $exception){
		    $return = [
			    'success' => false,
			    'error' => $exception->getMessage()
		    ];
	    }

	    return wp_send_json($return);
    }

    /**
     * @return mixed
     */
    public function saveSettingsAction()
    {
        $data = $this->sanitizeJsonData($_POST['data']);

        // persist $model on DB
        try {
            foreach ($data as $key => $value){
                $id = (SettingsRepository::getSingle($key) !== null) ? SettingsRepository::getSingle($key)->getId() : Uuid::v4();
                $model = SettingsModel::hydrateFromArray([
                        'id' => $id,
                        'key' => $key,
                        'value' => $value
                ]);
                SettingsRepository::save($model);
            }

            $return = [
                    'success' => true
            ];
        } catch (\Exception $exception){
            $return = [
                    'success' => false,
                    'error' => $exception->getMessage()
            ];
        }

        return wp_send_json($return);
    }

    /**
     * Creates a taxonomy
     */
    public function saveTaxonomyAction()
    {
        $data = $this->sanitizeJsonData($_POST['data']);

        // persist $model on DB
        try {
            $settings = $data[3];

            if($settings["rewrite"] === true){
                $settings["rewrite"] = [];
                $settings["rewrite"]["slug"] = (isset($settings["custom_rewrite"]) and null !== $settings["custom_rewrite"]) ? strtolower($settings["custom_rewrite"]) : strtolower($data[1]["slug"]) ;
            }

            $settings['capabilities'] = [];

            if($settings['capabilities_0'] === 'manage_terms') { $settings['capabilities'][] = 'manage_terms'; }
            if($settings['capabilities_1'] === 'edit_terms') { $settings['capabilities'][] = 'edit_terms'; }
            if($settings['capabilities_2'] === 'delete_terms') { $settings['capabilities'][] = 'delete_terms'; }
            if($settings['capabilities_3'] === 'assign_terms') { $settings['capabilities'][] = 'assign_terms'; }

            unset($settings['capabilities_0']);
            unset($settings['capabilities_1']);
            unset($settings['capabilities_2']);
            unset($settings['capabilities_3']);

            $id = (TaxonomyRepository::exists($data[1]["slug"])) ? TaxonomyRepository::getId($data[1]["slug"]) : Uuid::v4();
            $model = TaxonomyModel::hydrateFromArray([
                    'id' => $id,
                    'slug' => $data[1]["slug"],
                    'singular' => $data[1]["singular_label"],
                    'plural' => $data[1]["plural_label"],
                    'labels' => $data[2],
                    'native' => false,
                    'settings' => $settings
            ]);

            TaxonomyRepository::save($model);
            $return = [
                    'success' => true
            ];
        } catch (\Exception $exception){
            $return = [
                    'success' => false,
                    'error' => $exception->getMessage()
            ];
        }

        return wp_send_json($return);
    }

    /**
     * Creates a product data
     */
    public function saveWooCommerceProductDataAction()
    {
        $data = $this->sanitizeJsonData($_POST['data']);
        $id = (isset($data['id']) and WooCommerceProductDataRepository::exists($data['id'])) ? $data['id'] : Uuid::v4();

        $model = new WooCommerceProductDataModel(
                $id,
                $data['product_data_name'],
                $data['icon'],
                $data['visibility'],
                $data['show_ui']
        );

        try {
            WooCommerceProductDataRepository::save($model);

            $return = [
                    'success' => true
            ];
        } catch (\Exception $exception){
            $return = [
                    'success' => false,
                    'error' => $exception->getMessage()
            ];
        }

        return wp_send_json($return);
    }

    /**
     * @return mixed
     */
    public function saveWooCommerceProductDataFieldsAction()
    {
        $data = $this->sanitizeJsonData($_POST['data']);
        $fields = [];
        $ids = [];

        // persist $model on DB
        try {
            foreach ($data as $fieldIndex => $field ) {

                $productData = WooCommerceProductDataRepository::get([
                        'id' => $field['postDataId']
                ])[0];

                $fieldModel = WooCommerceProductDataFieldModel::hydrateFromArray([
                        'id' => $field['id'],
                        'productDataModel' => $productData,
                        'name' => $field['name'],
                        'type' => $field['type'],
                        'defaultValue' => $field['defaultValue'],
                        'description' => $field['description'],
                        'required' => $field['isRequired'],
                        'sort' => ($fieldIndex+1),
                ]);

                $optionsIds = [];

                if(isset($field['options'])){
                    foreach ($field['options'] as $optionIndex => $option){
                        $optionModel = WooCommerceProductDataFieldOptionModel::hydrateFromArray([
                                'id' => $option['id'],
                                'productDataField' => $fieldModel,
                                'label' => $option['label'],
                                'value' => $option['value'],
                                'sort' => ($optionIndex+1),
                        ]);

                        $fieldModel->addOption($optionModel);
                        $optionsIds[] = $optionModel->getId();
                    }
                }

                $fields[] = $fieldModel;
                $ids[] = [
                        'product_data_id' => $fieldModel->getProductData()->getId(),
                        'field' => $fieldModel->getId(),
                        'options' => $optionsIds
                ];
            }

            WooCommerceProductDataRepository::saveFields($fields);

            // remove orphans
            WooCommerceProductDataRepository::removeFieldsOrphans($ids);

            $return = [
                    'ids' => $ids,
                    'success' => true
            ];
        } catch (\Exception $exception) {
            $return = [
                    'success' => false,
                    'error' => $exception->getMessage()
            ];
        }

        return wp_send_json($return);
    }

    /**
     * @return mixed
     */
    public function syncPostsAction()
    {
        try {
            ACPT_DB::sync();

            $return = [
                    'success' => true
            ];
        } catch (\Exception $exception){
            $return = [
                    'success' => false,
                    'error' => $exception->getMessage()
            ];
        }

        return wp_send_json($return);
    }

    /**
     * Sluggify a string
     *
     * @return mixed
     * @throws \Exception
     */
    public function sluggifyAction()
    {
        $string = null;
        $maxLength = 20;
        if(isset($_POST['data'])){
            $data = $this->sanitizeJsonData($_POST['data']);
            $string = isset($data['string']) ? $data['string'] : null;
            $maxLength = isset($data['maxLength']) ? $data['maxLength'] : 20;
        }

        if($string){
            return wp_send_json([
                    'string' => Sluggify::slug($string, $maxLength)
            ]);
        }

        return wp_send_json([
                'success' => false,
                'error' => 'no string was sent'
        ]);
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    private function sanitizeJsonData($data)
    {
        $jsonDecoded = json_decode(wp_unslash($data), true);

        return Sanitizer::recursiveSanitizeRawData($jsonDecoded);
    }

    /**
     * @return mixed
     */
    public function isWPGraphQLActiveAction()
    {
        return wp_send_json([
                'status' => is_plugin_active( 'wp-graphql/wp-graphql.php' ),
        ]);
    }

    /**
     * @return mixed
     */
    public function isOxygenBuilderActiveAction()
    {
        return wp_send_json([
                'status' => is_plugin_active( 'oxygen/functions.php' )
        ]);
    }

    /**
     * @return mixed
     */
    public function isBBThemeBuilderActiveAction()
    {
        return wp_send_json([
                'status' => is_plugin_active( 'bb-theme-builder/bb-theme-builder.php' )
        ]);
    }

    /**
     * @return mixed
     */
    public function fetchPostTypePostsAction()
    {
        if(isset($_POST['data'])) {
            $data = $this->sanitizeJsonData($_POST['data']);

            if (!isset($data['postType'])) {
                return wp_send_json([
                        'success' => false,
                        'error' => 'Missing postType'
                ]);
            }

            $postType = $data['postType'];

            global $wpdb;
            $rawData = $wpdb->prepare( "SELECT ID, post_title FROM $wpdb->posts WHERE post_type=%s AND post_status=%s ORDER BY post_title", [$postType, 'publish'] );

            $data = [];

            foreach ($wpdb->get_results($rawData) as $result){
                $data[] = ["label" => $result->post_title, "value" => (int)$result->ID];
            }

            return wp_send_json($data);
        }
    }

    /**
     * @return mixed
     */
    public function fetchPostTypeTaxonomiesAction()
    {
        if(isset($_POST['data'])) {
            $data = $this->sanitizeJsonData( $_POST[ 'data' ] );

            if ( !isset( $data[ 'postType' ] ) ) {
                return wp_send_json( [
                        'success' => false,
                        'error'   => 'Missing postType'
                ] );
            }

            $postType = $data[ 'postType' ];

            $data = [];

            $taxonomies = get_object_taxonomies($postType, 'objects');
            foreach ($taxonomies as $taxonomy){
                if($taxonomy->public === true){

                    $options = [];

                    if($postType === 'post'){
                        $terms = get_categories([
                            'taxonomy' => $taxonomy->name,
                            'hide_empty' => false,
                        ]);
                    } else {
                        $terms = get_terms([
                            'taxonomy' => $taxonomy->name,
                            'hide_empty' => false,
                        ]);
                    }

                    foreach ($terms as $term){
                        $options[] = [
                            'label' => isset($term->label) ? $term->label : $term->name,
                            'value' => $term->term_id
                        ];
                    }

                    $data[] = [
                        "label" => $taxonomy->label,
                        "options" => $options
                    ];
                }
            }

            return wp_send_json($data);
        }
    }

    /**
     * Reset the permalink structure
     */
    private function resetPermalinkStructure()
    {
        global $wp_rewrite;
        $wp_rewrite->set_permalink_structure('/%postname%/');
        $wp_rewrite->flush_rules();
    }
}