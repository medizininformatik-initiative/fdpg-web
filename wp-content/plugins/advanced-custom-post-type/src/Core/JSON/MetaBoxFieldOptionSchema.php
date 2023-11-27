<?php

namespace ACPT\Core\JSON;

class MetaBoxFieldOptionSchema extends AbstractJSONSchema
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
                'boxId' => [
                    'type' => 'string',
                    'format' => 'uuid',
                    'readOnly' => true,
                ],
                'fieldId' => [
                    'type' => 'string',
                    'format' => 'uuid',
                    'readOnly' => true,
                ],
                'label' => [
                    'type' => 'string',
                ],
                'value' => [
                    'type' => ["integer", "string"],
                ],
                "sort" => [
                    'type' => 'integer',
                    'example' => 1,
                    'readOnly' => true,
                ],
            ],
            'required' => [
                'label',
                'value',
            ]
        ];
    }
}
