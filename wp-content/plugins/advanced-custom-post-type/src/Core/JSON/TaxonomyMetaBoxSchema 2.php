<?php

namespace ACPT\Core\JSON;

use ACPT\Costants\MetaTypes;

class TaxonomyMetaBoxSchema extends AbstractJSONSchema
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
                "taxonomy" => [
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
                    'items' => (new MetaBoxFieldSchema(MetaTypes::TAXONOMY))->toArray(),
                ]
            ],
            'required' => [
	            'title',
	            'taxonomy',
	            'fields',
            ],
        ];
    }
}