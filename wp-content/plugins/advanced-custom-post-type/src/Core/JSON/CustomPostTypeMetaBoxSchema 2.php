<?php

namespace ACPT\Core\JSON;

use ACPT\Costants\MetaTypes;

class CustomPostTypeMetaBoxSchema extends AbstractJSONSchema
{
    /**
     * @inheritDoc
     */
    function toArray()
    {
        return [
            'type' => 'object',
            'additionalProperties' => false,
            'properties' => [
                'id' => [
                    'type' => 'string',
                    'format' => 'uuid',
                    'readOnly' => true,
                ],
                "title" => [
                    'type' => 'string',
                    'example' => 'box',
                ],
                "label" => [
	                'type' => ['string', 'null'],
	                'example' => 'Box label',
	                'nullable' => true,
                ],
                "postType" => [
                    'type' => 'string',
                    'example' => 'post',
                ],
                "sort" => [
                    'type' => 'integer',
                    'example' => 1,
                    'readOnly' => true,
                ],
                "fields" => [
                    'type' => 'array',
                    'items' => (new MetaBoxFieldSchema(MetaTypes::CUSTOM_POST_TYPE))->toArray(),
                ]
            ],
            'required' => [
	            'title',
	            'postType',
	            'fields',
            ],
        ];
    }
}