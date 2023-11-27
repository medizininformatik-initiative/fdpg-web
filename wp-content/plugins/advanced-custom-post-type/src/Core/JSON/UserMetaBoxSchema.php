<?php

namespace ACPT\Core\JSON;

use ACPT\Costants\MetaTypes;

class UserMetaBoxSchema extends AbstractJSONSchema
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
                "name" => [
                    'type' => 'string',
                    'example' => 'box',
                ],
                "label" => [
	                'type' => ['string', 'null'],
	                'example' => 'Box label',
	                'nullable' => true,
                ],
                "sort" => [
                    'type' => 'integer',
                    'example' => 1,
                    'readOnly' => true,
                ],
                "fields" => [
                    'type' => 'array',
                    'items' => (new MetaBoxFieldSchema(MetaTypes::USER))->toArray(),
                ],
            ],
            'required' => [
	            'name',
	            'fields',
            ],
        ];
    }
}