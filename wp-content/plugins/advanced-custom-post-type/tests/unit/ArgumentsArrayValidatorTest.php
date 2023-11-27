<?php

namespace ACPT\Tests;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Validators\ArgumentsArrayValidator;

class ArgumentsArrayValidatorTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function can_validate_single_type()
    {
        $validator = new ArgumentsArrayValidator();

        $mandatory_keys = [
            'post_type' => [
                'required' => true,
                'type' => 'string',
            ],
            'box_name' => [
                'required' => true,
                'type' => 'array',
            ],
        ];

        $args = [
            'post_type' => 'post name',
            'box_name' => 'box name',
        ];

        $this->assertFalse($validator->validate($mandatory_keys, $args));

        $args = [
            'post_type' => 'post name',
            'ddddddd' => 'box name',
        ];

        $this->assertFalse($validator->validate($mandatory_keys, $args));

        $args = [
            'post_type',
            'box_name',
        ];

        $this->assertFalse($validator->validate($mandatory_keys, $args));
    }

    /**
     * @test
     */
    public function can_allow_multiple_type()
    {
        $validator = new ArgumentsArrayValidator();

        $mandatory_keys = [
            'value' => [
                'required' => true,
                'type' => 'integer|string',
            ],
        ];

        $args = [
                'value' => 'string',
        ];

        $args2 = [
                'value' => 123,
        ];

        $args3 = [
                'value' => true,
        ];

        $this->assertTrue($validator->validate($mandatory_keys, $args));
        $this->assertTrue($validator->validate($mandatory_keys, $args2));
        $this->assertFalse($validator->validate($mandatory_keys, $args3));
    }

    /**
     * @test
     */
    public function cannot_allow_not_existing_keys()
    {
        $validator = new ArgumentsArrayValidator();

        $mandatory_keys = [
            'value' => [
                'required' => false,
                'type' => 'integer|string',
            ],
        ];

        $args = [
            'not-allowed-value' => 'string',
        ];

        $this->assertFalse($validator->validate($mandatory_keys, $args));
    }

    /**
     * @test
     */
    public function can_validate_not_required_fields()
    {
        $validator = new ArgumentsArrayValidator();

        $mandatory_keys = [
                'post_type' => [
                        'required' => true,
                        'type' => 'string',
                ],
                'box_name' => [
                        'required' => true,
                        'type' => 'string',
                ],
                'field_name' => [
                        'required' => true,
                        'type' => 'string',
                ],
                'field_type' => [
                        'required' => true,
                        'type' => 'string',
                ],
                'show_in_archive' => [
                        'required' => true,
                        'type' => 'boolean',
                ],
                'required' => [
                        'required' => true,
                        'type' => 'boolean',
                ],
                'default_value' => [
                        'required' => false,
                        'type' => 'integer|string',
                ],
                'description' => [
                        'required' => false,
                        'type' => 'string',
                ],
        ];

        $args = [
            'post_type' => 'page',
            'box_name' => 'Box name',
            'field_name' => 'Field name',
            'field_type' => CustomPostTypeMetaBoxFieldModel::TEXT_TYPE,
            'show_in_archive' => false,
            'required' => false,
        ];

        $this->assertTrue($validator->validate($mandatory_keys, $args));
    }

    /**
     * @test
     */
    public function can_validate_with_enums()
    {
        $validator = new ArgumentsArrayValidator();

        $mandatory_keys = [
            'field_type' => [
                'required' => true,
                'type' => 'string',
                'enum' => [
                    'text',
                    'number',
                    'texarea'
                ]
            ],
        ];

        $args = [
            'field_type' => 'text',
        ];

        $args2 = [
            'field_type' => 'not-allowed',
        ];

        $this->assertTrue($validator->validate($mandatory_keys, $args));
        $this->assertFalse($validator->validate($mandatory_keys, $args2));
    }

    /**
     * @test
     */
    public function can_validate_with_object_values()
    {
        $validator = new ArgumentsArrayValidator();

        $mandatory_keys = [
            'object' => [
                'required' => true,
                'type' => 'object',
            ],
        ];

        $args = [
            'object' => new Dummy_Class(),
        ];

        $this->assertTrue($validator->validate($mandatory_keys, $args));
    }

    /**
     * @test
     */
    public function can_validate_instanceOf_with_object_values()
    {
        $validator = new ArgumentsArrayValidator();

        $mandatory_keys = [
            'object' => [
                'required' => true,
                'type' => 'object',
                'instanceOf' => Dummy_Class::class,
            ],
        ];

        $args = [
            'object' => new Dummy_Class(),
        ];

        $this->assertTrue($validator->validate($mandatory_keys, $args));
    }

    /**
     * @test
     */
    public function can_validate_not_valid_instanceOf_object_values()
    {
        $validator = new ArgumentsArrayValidator();

        $mandatory_keys = [
            'object' => [
                'required' => true,
                'type' => 'object',
                'instanceOf' => 'Not\\Existing\\ClassName',
            ],
        ];

        $args = [
            'object' => new Dummy_Class(),
        ];

        $this->assertFalse($validator->validate($mandatory_keys, $args));
    }
}

class Dummy_Class
{}