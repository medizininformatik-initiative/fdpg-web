<?php

namespace ACPT\Core\JSON;

use ACPT\Core\Models\MetaField\MetaBoxFieldRelationshipModel;

class MetaBoxFieldRelationSchema extends AbstractJSONSchema
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
                'type' => [
                    'type' => 'string',
                    'enum' => [
                        MetaBoxFieldRelationshipModel::ONE_TO_ONE_UNI,
                        MetaBoxFieldRelationshipModel::ONE_TO_ONE_BI,
                        MetaBoxFieldRelationshipModel::ONE_TO_MANY_UNI,
                        MetaBoxFieldRelationshipModel::ONE_TO_MANY_BI,
                        MetaBoxFieldRelationshipModel::MANY_TO_ONE_UNI,
                        MetaBoxFieldRelationshipModel::MANY_TO_ONE_BI,
                        MetaBoxFieldRelationshipModel::MANY_TO_MANY_UNI,
                        MetaBoxFieldRelationshipModel::MANY_TO_MANY_BI,
                    ],
                ],
                'relatedPostType' => [
                    'type' => 'string',
                ],
                'inversedBoxId' => [
                    'type' => 'string',
                    'format' => 'uuid',
                    'readOnly' => true,
                ],
                'inversedBoxName' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
                'inversedFieldName' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
                'inversedFieldId' => [
                    'type' => 'string',
                    'format' => 'uuid',
                ],
            ],
            'required' => [
                'type',
                'relatedPostType',
                'inversedFieldId',
            ]
        ];
    }
}
