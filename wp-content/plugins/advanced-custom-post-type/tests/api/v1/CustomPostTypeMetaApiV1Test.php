<?php

namespace ACPT\Tests;

class CustomPostTypeMetaApiV1Test extends RestApiV1TestCase
{
    /**
     * @test
     */
    public function raise_error_with_wrong_payload()
    {
        $response = $this->callAuthenticatedRestApi('POST', '/meta/page',  [
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
                        "visibilityConditions" => [],
                        "relations" => [],
                        "hasChildren" => true,
                        "children" => [],
	                    "blocks" => [],
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
        $response = $this->callAuthenticatedRestApi('DELETE', '/meta/page',  []);
        $response2 = $this->callAuthenticatedRestApi('DELETE', '/meta/post',  []);

        $this->assertEquals(200, $response['status']);
        $this->assertEquals(200, $response2['status']);
    }

    /**
     * @test
     */
    public function can_add_a_very_simple_meta()
    {
        $response = $this->callAuthenticatedRestApi('POST', '/meta/page',  [
            [
                "title" => "box",
                "postType" => "post",
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
		                        "value" => "2"
	                        ]
                        ],
                        "visibilityConditions" => [],
                        "relations" => [],
                        "hasChildren" => false,
                        "children" => [],
                        "blocks" => [],
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
                        "children" => [],
	                    "blocks" => [],
                    ],
	                [
		                "name" => "Flex",
		                "type" => "FlexibleContent",
		                "defaultValue" => null,
		                "description" => "bla bla",
		                "isRequired" => false,
		                "showInArchive" => false,
		                "options" => [],
		                "visibilityConditions" => [],
		                "relations" => [],
		                "hasChildren" => false,
		                "children" => [],
		                "blocks" => [
			                [
				                "name" => "block",
				                "label" => "Block label",
				                "fields" => [
					                [
						                "name" => "Nested",
						                "type" => "Text",
						                "defaultValue" => "string",
						                "description" => "string",
						                "isRequired" => true,
						                "options" => [],
						                "advancedOptions" => [],
						                "visibilityConditions" => [],
					                ],
				                ]
			                ],
			                [
				                "name" => "second_block",
				                "label" => "Second block",
				                "fields" => [
					                [
						                "name" => "Nested",
						                "type" => "Text",
						                "defaultValue" => "string",
						                "description" => "string",
						                "isRequired" => true,
						                "options" => [],
						                "advancedOptions" => [],
						                "visibilityConditions" => [],
					                ],
				                ]
			                ]
		                ],
	                ]
                ]
            ]
        ]);

        $this->assertEquals(201, $response['status']);

        $response = json_decode($response['response'], true);

        $this->assertNotEmpty($response['ids']);
        $this->assertNotEmpty($response['ids']['page']['blocks']);

        $inversedFieldId = $response['ids']['page']['fields'][0];

        return $inversedFieldId;
    }

    /**
     * @test
     */
    public function can_update_a_very_simple_meta()
    {
        $response = $this->callAuthenticatedRestApi('PUT', '/meta/page',  [
            [
                "title" => "box",
                "postType" => "post",
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
        $this->assertCount(1, $response['ids']['page']['boxes']);
        $this->assertCount(3, $response['ids']['page']['fields']);
    }

    /**
     * @test
     */
    public function can_add_a_more_complex_meta()
    {
        $response = $this->callAuthenticatedRestApi('POST', '/meta/post',  [
            [
                "title" => "complex box",
                "postType" => "post",
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
        $this->assertCount(2, $response['ids']['post']['fields']);
    }

    /**
     * @depends can_add_a_very_simple_meta
     * @test
     *
     * @param $inversedFieldId
     *
     * @throws \Exception
     */
    public function can_add_a_meta_with_relationships($inversedFieldId)
    {
        $response = $this->callAuthenticatedRestApi('PUT', '/meta/post',  [
            [
                "title" => "new box",
                "postType" => "post",
                "fields" => [
                    [
                        "name" => "related",
                        "type" => "Post",
                        "defaultValue" => "",
                        "description" => "",
                        "isRequired" => true,
                        "showInArchive" => false,
                        "options" => [],
                        "visibilityConditions" => [],
                        "relations" => [
                            [
                                "type" => "OneToOneBi",
                                "relatedPostType" => "page",
                                "inversedFieldId" => $inversedFieldId,
                            ]
                        ],
                        "hasChildren" => false,
                    ]
                ]
            ]
        ]);

        $this->assertEquals(200, $response['status']);

        $response = json_decode($response['response'], true);

        $this->assertNotEmpty($response['ids']);
        $this->assertCount(1, $response['ids']['post']['boxes']);
        $this->assertCount(1, $response['ids']['post']['fields']);
    }

    /**
     * @test
     */
    public function can_fetch_and_then_delete_single_meta()
    {
        $response = $this->callAuthenticatedRestApi('GET', '/meta/page',  []);

        $this->assertEquals(200, $response['status']);

        $response = json_decode($response['response'], true);
        $id = $response[0]['id'];

        $this->assertNotEmpty($id);

        $response = $this->callAuthenticatedRestApi('GET', '/meta/page/'.$id,  []);

        $this->assertEquals(200, $response['status']);

        $response = json_decode($response['response'], true);

        $this->assertEquals($id, $response['id']);

        $response = $this->callAuthenticatedRestApi('DELETE', '/meta/page/'.$id,  []);

        $this->assertEquals(200, $response['status']);
    }

    /**
     * @test
     */
    public function finally_can_delete_all_meta()
    {
        $response = $this->callAuthenticatedRestApi('DELETE', '/meta/page',  []);
        $response2 = $this->callAuthenticatedRestApi('DELETE', '/meta/post',  []);

        $this->assertEquals(200, $response['status']);
        $this->assertEquals(200, $response2['status']);
    }
}