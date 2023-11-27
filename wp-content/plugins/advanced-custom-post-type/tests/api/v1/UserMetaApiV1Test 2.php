<?php

namespace ACPT\Tests;

class UserMetaApiV1Test extends RestApiV1TestCase
{
    /**
     * @test
     */
    public function raise_error_with_wrong_payload()
    {
        $response = $this->callAuthenticatedRestApi('POST', '/user-meta',  [
            [
                "title" => "box",
                "postType" => "post",
                "fields" => [
                    [
                        "name" => "string",
                        "type" => "foo",
                        "defaultValue" => "string",
                        "description" => "string",
                        "isRequired" => true,
                        "showInArchive" => true,
                        "options" => [],
                    ]
                ]
            ]
        ]);

        $this->assertEquals(500, $response['status']);
    }

    /**
     * @test
     */
    public function can_add_user_meta_definitions()
    {
        $response = $this->callAuthenticatedRestApi('POST', '/user-meta', [
            [
                "name" => "box",
                "fields" => [
                    [
                        "name" => "text",
                        "type" => "Text",
                        "defaultValue" => "string",
                        "description" => "string",
                        "isRequired" => true,
                        "showInArchive" => true,
                        "options" => []
                    ],
                    [
                        "name" => "select",
                        "type" => "Select",
                        "defaultValue" => "",
                        "description" => "",
                        "isRequired" => false,
                        "showInArchive" => false,
                        "options" => [
                            ["label" => "foo", "value" => "bar" ],
                            ["label" => "foo2", "value" => "bar2" ],
                            ["label" => "foo3", "value" => "bar3" ],
                        ]
                    ],
                ]
            ],
            [
                "name" => "another box",
                "label" => "Another Box",
                "fields" => [
                    [
                        "name" => "another url",
                        "type" => "Url",
                        "defaultValue" => "http://acpt.io",
                        "description" => "",
                        "isRequired" => true,
                        "showInArchive" => true,
                        "options" => []
                    ],
                    [
                        "name" => "another email",
                        "type" => "Email",
                        "defaultValue" => "info@acpt.io",
                        "description" => "Email description",
                        "isRequired" => false,
                        "showInArchive" => false,
                        "options" => []
                    ],
                    [
                        "name" => "another select",
                        "type" => "Select",
                        "defaultValue" => "",
                        "description" => "",
                        "isRequired" => false,
                        "showInArchive" => false,
                        "options" => [
                            ["label" => "foo", "value" => "bar" ],
                            ["label" => "foo2", "value" => "bar2" ],
                            ["label" => "foo3", "value" => "bar3" ],
                        ]
                    ],
                ]
            ]
        ]);

        $this->assertEquals(201, $response['status']);

        $response = json_decode($response['response'], true);

        $this->assertNotEmpty($response['ids']);
    }

    /**
     * @test
     */
    public function can_fetch_all_user_meta_definitions()
    {
        $response = $this->callAuthenticatedRestApi('GET', '/user-meta');

        $this->assertEquals(200, $response['status']);

        $response = json_decode($response['response'], true);

        $this->assertArrayHasKey('id', $response[0]);
        $this->assertArrayHasKey('title', $response[0]);
        $this->assertArrayHasKey('fields', $response[0]);
        $this->assertCount(2, $response[0]['fields']);
    }

    /**
     * @test
     */
    public function can_update_fetch_and_delete_single_meta()
    {
        $response = $this->callAuthenticatedRestApi('PUT', '/user-meta', [
            [
                "name" => "box",
                "fields" => [
                    [
                            "name" => "text modified",
                            "type" => "Text",
                            "defaultValue" => "string",
                            "description" => "string",
                            "isRequired" => true,
                            "showInArchive" => true,
                            "options" => []
                    ],
                    [
                            "name" => "select",
                            "type" => "Select",
                            "defaultValue" => "",
                            "description" => "",
                            "isRequired" => false,
                            "showInArchive" => false,
                            "options" => [
                                    ["label" => "foo", "value" => "bar" ],
                                    ["label" => "foo2", "value" => "bar2" ],
                                    ["label" => "foo3", "value" => "bar3" ],
                            ]
                    ],
                ]
            ],
            [
                    "name" => "another box",
                    "fields" => [
                            [
                                    "name" => "another url",
                                    "type" => "Url",
                                    "defaultValue" => "http://acpt.io",
                                    "description" => "",
                                    "isRequired" => true,
                                    "showInArchive" => true,
                                    "options" => []
                            ],
                            [
                                    "name" => "another email",
                                    "type" => "Email",
                                    "defaultValue" => "info@acpt.io",
                                    "description" => "Email description",
                                    "isRequired" => false,
                                    "showInArchive" => false,
                                    "options" => []
                            ],
                            [
                                    "name" => "another select",
                                    "type" => "Select",
                                    "defaultValue" => "",
                                    "description" => "",
                                    "isRequired" => false,
                                    "showInArchive" => false,
                                    "options" => [
                                            ["label" => "foo", "value" => "bar" ],
                                            ["label" => "foo2", "value" => "bar2" ],
                                            ["label" => "foo3", "value" => "bar3" ],
                                    ]
                            ],
                    ]
            ]
        ]);

        $this->assertEquals(200, $response['status']);

        $response = json_decode($response['response'], true);

        $this->assertNotEmpty($response['ids']);

        $box_id = $response['ids']['boxes'][0];

        $response = $this->callAuthenticatedRestApi('GET', '/user-meta/'.$box_id, []);

        $response = json_decode($response['response'], true);

        $this->assertEquals($response['id'], $box_id);

        $response = $this->callAuthenticatedRestApi('DELETE', '/user-meta/'.$box_id, []);

        $this->assertEquals(200, $response['status']);
    }

    /**
     * @test
     */
    public function can_delete_all_meta()
    {
        $response = $this->callAuthenticatedRestApi('DELETE', '/user-meta',  []);

        $this->assertEquals(200, $response['status']);
    }
}