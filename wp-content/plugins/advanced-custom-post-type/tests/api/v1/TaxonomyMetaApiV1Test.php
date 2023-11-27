<?php

namespace ACPT\Tests;

class TaxonomyMetaApiV1Test extends RestApiV1TestCase
{
    /**
     * @test
     */
    public function raise_error_with_wrong_payload()
    {
        $response = $this->callAuthenticatedRestApi('POST', '/taxonomy-meta/category',  [
            [
                "title" => "box",
                "taxonomy" => "category",
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
    public function first_can_delete_all_meta()
    {
        $response = $this->callAuthenticatedRestApi('DELETE', '/taxonomy-meta/category',  []);
        $response2 = $this->callAuthenticatedRestApi('DELETE', '/taxonomy-meta/post_tag',  []);

        $this->assertEquals(200, $response['status']);
        $this->assertEquals(200, $response2['status']);
    }

    /**
     * @test
     */
    public function can_add_a_very_simple_meta()
    {
        $response = $this->callAuthenticatedRestApi('POST', '/taxonomy-meta/category',  [
            [
                "title" => "box",
                "taxonomy" => "category",
                "fields" => [
                    [
                        "name" => "string",
                        "type" => "Text",
                        "defaultValue" => "string",
                        "description" => "string",
                        "isRequired" => true,
                        "showInArchive" => true,
                        "options" => [],
                        "advancedOptions" => [
	                        [
		                        "key" => "max",
		                        "value" => "123"
	                        ],
	                        [
		                        "key" => "min",
		                        "value" => "12"
	                        ]
                        ],
                        "visibilityConditions" => [],
                        "relations" => [],
                        "hasChildren" => false,
                        "children" => []
                    ],
                    [
                        "name" => "select",
                        "type" => "Select",
                        "defaultValue" => "foo",
                        "description" => "bla bla",
                        "isRequired" => false,
                        "showInArchive" => false,
                        "options" => [
                            ["label" => "foo", "value" => 123],
                            ["label" => "foo2", "value" => 453],
                            ["label" => "foo3", "value" => "baz"],
                        ],
                        "visibilityConditions" => [],
                        "relations" => [],
                        "hasChildren" => false,
                        "children" => []
                    ]
                ]
            ]
        ]);

        $this->assertEquals(201, $response['status']);

        $response = json_decode($response['response'], true);

        $this->assertNotEmpty($response['ids']);

        $inversedFieldId = $response['ids']['category']['fields'][0];

        return $inversedFieldId;
    }

    /**
     * @test
     */
    public function can_update_a_very_simple_meta()
    {
        $response = $this->callAuthenticatedRestApi('PUT', '/taxonomy-meta/category',  [
            [
                "title" => "box",
                "taxonomy" => "category",
                "fields" => [
                    [
                        "name" => "string",
                        "type" => "Text",
                        "defaultValue" => "string",
                        "description" => "string",
                        "isRequired" => true,
                        "showInArchive" => true,
                        "options" => [],
                        "visibilityConditions" => [],
                        "relations" => [],
                        "hasChildren" => false,
                        "children" => []
                    ],
                    [
                        "name" => "select",
                        "type" => "Select",
                        "defaultValue" => "foo",
                        "description" => "bla bla",
                        "isRequired" => false,
                        "showInArchive" => false,
                        "options" => [
                            ["label" => "foo", "value" => 123],
                            ["label" => "foo2", "value" => 453],
                            ["label" => "foo3", "value" => "baz"],
                            ["label" => "foo4", "value" => "baz45"],
                            ["label" => "foo5", "value" => "baz3232"],
                        ],
                        "visibilityConditions" => [
                            [
                                "type" => [
                                    "type" => "VALUE",
                                    "value" => "VALUE",
                                ],
                                "operator" => "!=",
                                "value" => 453,
                            ],
                        ],
                        "relations" => [],
                        "hasChildren" => false,
                        "children" => []
                    ],
                    [
                        "name" => "url",
                        "type" => "Url",
                        "defaultValue" => "https://acpt.io",
                        "description" => "",
                        "isRequired" => true,
                        "showInArchive" => true,
                        "options" => [],
                        "visibilityConditions" => [],
                        "relations" => [],
                        "hasChildren" => false,
                        "children" => []
                    ],
                ]
            ]
        ]);

        $this->assertEquals(200, $response['status']);

        $response = json_decode($response['response'], true);

        $this->assertNotEmpty($response['ids']);
        $this->assertCount(1, $response['ids']['category']['boxes']);
        $this->assertCount(3, $response['ids']['category']['fields']);
    }

    /**
     * @test
     */
    public function can_add_a_more_complex_meta()
    {
        $response = $this->callAuthenticatedRestApi('POST', '/taxonomy-meta/post_tag',  [
            [
                "title" => "complex box",
                "taxonomy" => "post_tag",
                "fields" => [
                    [
                        "name" => "repeater",
                        "type" => "Repeater",
                        "defaultValue" => "",
                        "description" => "",
                        "isRequired" => true,
                        "showInArchive" => false,
                        "options" => [],
                        "visibilityConditions" => [],
                        "relations" => [],
                        "hasChildren" => true,
                        "children" => [
                            [
                                "name" => "text",
                                "type" => "Text",
                                "defaultValue" => "",
                                "description" => "",
                                "isRequired" => false,
                                "showInArchive" => false,
                                "options" => [],
                                "relations" => [],
                                "hasChildren" => false,
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals(201, $response['status']);

        $response = json_decode($response['response'], true);

        $this->assertNotEmpty($response['ids']);
        $this->assertCount(2, $response['ids']['post_tag']['fields']);
    }

    /**
     * @test
     */
    public function can_fetch_and_then_delete_single_meta()
    {
        $response = $this->callAuthenticatedRestApi('GET', '/taxonomy-meta/category',  []);

        $this->assertEquals(200, $response['status']);

        $response = json_decode($response['response'], true);
        $id = $response[0]['id'];

        $this->assertNotEmpty($id);

        $response = $this->callAuthenticatedRestApi('GET', '/taxonomy-meta/category/'.$id,  []);

        $this->assertEquals(200, $response['status']);

        $response = json_decode($response['response'], true);

        $this->assertEquals($id, $response['id']);

        $response = $this->callAuthenticatedRestApi('DELETE', '/taxonomy-meta/category/'.$id,  []);

        $this->assertEquals(200, $response['status']);
    }

    /**
     * @test
     */
    public function finally_can_delete_all_meta()
    {
        $response = $this->callAuthenticatedRestApi('DELETE', '/taxonomy-meta/category',  []);
        $response2 = $this->callAuthenticatedRestApi('DELETE', '/taxonomy-meta/post_tag',  []);

        $this->assertEquals(200, $response['status']);
        $this->assertEquals(200, $response2['status']);
    }
}