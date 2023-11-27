<?php

namespace ACPT\Tests;

class OptionPageMetaApiV1Test extends RestApiV1TestCase
{
	/**
	 * @test
	 */
	public function raise_error_with_wrong_payload()
	{
		$response = $this->callAuthenticatedRestApi('POST', '/option-page-meta/not-existent-page',  [
			[
				"title" => "box",
				"page" => "not-existent-page",
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
	public function first_add_a_page()
	{
		$payload = [
			'pageTitle' => "Page from API",
			'menuTitle' => "Page from API",
			'capability' => "manage_options",
			'menuSlug' => "another-page-from-api",
			'position' => 77,
			'icon' => "menu",
			'description' => null,
			'parentId' => null,
		];

		$response = $this->callAuthenticatedRestApi('POST', '/option-page', $payload);

		$this->assertEquals(201, $response['status']);
	}

	/**
	 * @depends first_add_a_page
	 * @test
	 */
	public function can_add_a_very_simple_meta()
	{
		$response = $this->callAuthenticatedRestApi('POST', '/option-page-meta/another-page-from-api',  [
			[
				"title" => "box",
				"page" => "another-page-from-api",
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
					],
				]
			]
		]);

		$this->assertEquals(201, $response['status']);

		$response = json_decode($response['response'], true);

		$this->assertNotEmpty($response['ids']);
		$this->assertNotEmpty($response['ids']['another-page-from-api']['blocks']);

		$inversedFieldId = $response['ids']['another-page-from-api']['fields'][0];

		return $inversedFieldId;
	}

	/**
	 * @depends can_add_a_very_simple_meta
	 * @test
	 */
	public function can_update_a_very_simple_meta()
	{
		$response = $this->callAuthenticatedRestApi('PUT', '/option-page-meta/another-page-from-api',  [
			[
				"title" => "box",
				"page" => "another-page-from-api",
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
		$this->assertCount(1, $response['ids']['another-page-from-api']['boxes']);
		$this->assertCount(3, $response['ids']['another-page-from-api']['fields']);
	}

	/**
	 * @depends can_update_a_very_simple_meta
	 * @test
	 */
	public function can_add_a_more_complex_meta()
	{
		$response = $this->callAuthenticatedRestApi('PUT', '/option-page-meta/another-page-from-api',  [
			[
				"title" => "complex box",
				"page" => "another-page-from-api",
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

		$this->assertEquals(200, $response['status']);

		$response = json_decode($response['response'], true);

		$this->assertNotEmpty($response['ids']);
		$this->assertCount(2, $response['ids']['another-page-from-api']['fields']);
	}

	/**
	 * @depends can_add_a_more_complex_meta
	 * @test
	 */
	public function can_fetch_and_then_delete_single_meta()
	{
		$response = $this->callAuthenticatedRestApi('GET', '/option-page-meta/another-page-from-api',  []);

		$this->assertEquals(200, $response['status']);

		$response = json_decode($response['response'], true);
		$id = $response[0]['id'];

		$this->assertNotEmpty($id);

		$response = $this->callAuthenticatedRestApi('GET', '/option-page-meta/another-page-from-api/'.$id,  []);

		$this->assertEquals(200, $response['status']);

		$response = json_decode($response['response'], true);

		$this->assertEquals($id, $response['id']);

		$response = $this->callAuthenticatedRestApi('DELETE', '/option-page-meta/another-page-from-api/'.$id,  []);

		$this->assertEquals(200, $response['status']);
	}

	/**
	 * @depends can_fetch_and_then_delete_single_meta
	 * @test
	 */
	public function finally_can_delete_all_pages_and_meta()
	{
		$response = $this->callAuthenticatedRestApi('DELETE', '/option-page-meta/another-page-from-api',  []);
		$response2 = $this->callAuthenticatedRestApi('GET', '/option-page/another-page-from-api');

		$this->assertEquals(200, $response['status']);
		$this->assertEquals(200, $response2['status']);

		$response = $this->callAuthenticatedRestApi('DELETE', '/option-page/another-page-from-api');

		$this->assertEquals(200, $response['status']);

		$response = $this->callAuthenticatedRestApi('GET', '/option-page/another-page-from-api');

		$this->assertEquals(404, $response['status']);
	}
}