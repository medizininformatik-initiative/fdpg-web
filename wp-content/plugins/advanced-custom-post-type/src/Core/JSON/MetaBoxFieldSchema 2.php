<?php

namespace ACPT\Core\JSON;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Costants\MetaTypes;

class MetaBoxFieldSchema extends AbstractJSONSchema
{
    /**
     * @var bool
     */
    private $showChildren;

	/**
	 * @var bool
	 */
    private $showBlocks;

	/**
	 * @var string
	 */
    private $belongsTo;

	/**
	 * MetaBoxFieldSchema constructor.
	 *
	 * @param $belongsTo
	 * @param bool $showChildren
	 * @param bool $showBlocks
	 */
    public function __construct($belongsTo, $showChildren = true, $showBlocks = true)
    {
        $this->belongsTo = $belongsTo;
        $this->showChildren = $showChildren;
	    $this->showBlocks = $showBlocks;
    }

    /**
     * @inheritDoc
     */
    function toArray()
    {
        $return = [
            'type' => 'object',
            'additionalProperties' => false,
            'properties' => [
                'id' => [
                    'type' => 'string',
                    'format' => 'uuid',
                    'readOnly' => true,
                ],
                'boxId' => [
                    'type' => 'string',
                    'format' => 'uuid',
                    'readOnly' => true,
                ],
                'db_name' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
                'ui_name' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
                'name' => [
                    'type' => 'string',
                ],
                'type' => [
                    'type' => 'string',
                    'enum' => [
                        AbstractMetaBoxFieldModel::ADDRESS_TYPE,
                        AbstractMetaBoxFieldModel::CHECKBOX_TYPE,
                        AbstractMetaBoxFieldModel::COLOR_TYPE,
                        AbstractMetaBoxFieldModel::CURRENCY_TYPE,
                        AbstractMetaBoxFieldModel::DATE_TYPE,
                        AbstractMetaBoxFieldModel::EDITOR_TYPE,
                        AbstractMetaBoxFieldModel::EMAIL_TYPE,
                        AbstractMetaBoxFieldModel::EMBED_TYPE,
                        AbstractMetaBoxFieldModel::FILE_TYPE,
                        AbstractMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE,
                        AbstractMetaBoxFieldModel::HTML_TYPE,
                        AbstractMetaBoxFieldModel::GALLERY_TYPE,
                        AbstractMetaBoxFieldModel::IMAGE_TYPE,
                        AbstractMetaBoxFieldModel::LENGTH_TYPE,
                        AbstractMetaBoxFieldModel::LIST_TYPE,
                        AbstractMetaBoxFieldModel::NUMBER_TYPE,
                        AbstractMetaBoxFieldModel::POST_TYPE,
                        AbstractMetaBoxFieldModel::PHONE_TYPE,
                        AbstractMetaBoxFieldModel::RADIO_TYPE,
                        AbstractMetaBoxFieldModel::REPEATER_TYPE,
                        AbstractMetaBoxFieldModel::SELECT_TYPE,
                        AbstractMetaBoxFieldModel::SELECT_MULTI_TYPE,
                        AbstractMetaBoxFieldModel::TEXT_TYPE,
                        AbstractMetaBoxFieldModel::TEXTAREA_TYPE,
                        AbstractMetaBoxFieldModel::TIME_TYPE,
                        AbstractMetaBoxFieldModel::TOGGLE_TYPE,
                        AbstractMetaBoxFieldModel::VIDEO_TYPE,
                        AbstractMetaBoxFieldModel::WEIGHT_TYPE,
                        AbstractMetaBoxFieldModel::URL_TYPE,
                    ],
                    'example' => AbstractMetaBoxFieldModel::TEXT_TYPE
                ],
                'defaultValue' => [
	                'type' => ['string', 'null'],
	                'example' => 'Default value',
	                'nullable' => true,
                ],
                'description' => [
                    'type' => 'string',
                ],
                'isRequired' => [
                    'type' => 'boolean',
                ],
                'showInArchive' => [
                    'type' => 'boolean',
                ],
                'sort' => [
                    'type' => 'integer',
                    'example' => 1,
                    'readOnly' => true,
                ],
                'options' => [
                    'type' => 'array',
                    'items' => (new MetaBoxFieldOptionSchema())->toArray(),
                    'default'=> [],
                ],
                'visibilityConditions' => [
                    'type' => 'array',
                    'items' => (new MetaBoxFieldvisibilityConditionSchema())->toArray(),
                    'default'=> [],
                ],
                'advancedOptions' => [
	                'type' => 'array',
	                'items' => (new MetaBoxFieldAdvancedOptionSchema())->toArray(),
	                'default'=> [],
                ],
                'relations' => [
                    'type' => 'array',
                    'items' => (new MetaBoxFieldRelationSchema())->toArray(),
                    'default'=> [],
                ],
                'hasChildren' => [
                    'type' => 'boolean',
                ],
            ],
            'required' => [
                'name',
                'type',
                'isRequired',
            ]
        ];

        if($this->showBlocks){
	        $return['properties']['blocks'] = [
		        'type' => 'array',
		        'items' => (new MetaBoxFieldBlockSchema($this->belongsTo))->toArray(),
		        'default'=> [],
	        ];
        }

        if($this->showChildren){
            $return['properties']['children'] = [
                'type' => 'array',
                'items' => (new MetaBoxFieldSchema($this->belongsTo, false, false))->toArray(),
                'default'=> [],
            ];
        }

        return $return;
    }
}
