<?php

namespace ACPT\Tests;

use ACPT\Core\Data\Export\DTO\MetadataExportItemDto;
use ACPT\Core\Data\Export\MetadataExport;
use ACPT\Core\Data\Import\MetadataImport;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Costants\MetadataFormats;
use ACPT\Costants\MetaTypes;

class JsonTest extends AbstractTestCase
{
	/**
	 * @test
	 * @throws \Exception
	 */
	public function can_export_json_file()
	{
		$this->create_meta_field();
		$json = $this->export_json();

		$parsed = json_decode($json, true);

		$this->assertNotEmpty($parsed['acpt_meta']);

		$this->import_json($json);
		$this->delete_meta_field();
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	private function export_json()
	{
		$items = [];

		$item = new MetadataExportItemDto();
		$item->id = $this->oldest_page_id;
		$item->find = 'page';
		$item->belongsTo = MetaTypes::CUSTOM_POST_TYPE;
		$item->metaBoxes = MetaRepository::get([
			'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
			'find' => 'page',
		]);

		$items[] = $item;

		return MetadataExport::export(MetadataFormats::JSON_FORMAT, $items);
	}

	/**
	 * @param $json
	 *
	 * @throws \Exception
	 */
	private function import_json($json)
	{
		MetadataImport::import($this->second_oldest_page_id, MetadataFormats::JSON_FORMAT, $json);

		$originalValue = get_acpt_field([
			'post_id' => $this->oldest_page_id,
			'box_name' => 'box_name',
			'field_name' => 'field_name',
		]);

		$copiedValue = get_acpt_field([
			'post_id' => $this->second_oldest_page_id,
			'box_name' => 'box_name',
			'field_name' => 'field_name',
		]);

		$this->assertEquals($originalValue, $copiedValue);
	}

	/**
	 * create a meta field
	 */
	private function create_meta_field()
	{
		$postType = 'page';
		$boxName = 'box_name';
		$boxLabel = 'Box label';
		$fieldName = 'field_name';
		$value = 'text text';

		$add_meta_box = add_acpt_meta_box($postType, $boxName, $boxLabel);

		$this->assertTrue($add_meta_box);

		$add_meta_field = add_acpt_meta_field(
			[
				'post_type' => $postType,
				'box_name' => $boxName,
				'field_name' => $fieldName,
				'field_type' => CustomPostTypeMetaBoxFieldModel::TEXT_TYPE,
				'show_in_archive' => false,
				'required' => false,
			]
		);

		$this->assertTrue($add_meta_field);

		$add_acpt_meta_field_value = add_acpt_meta_field_value([
			'post_id' => $this->oldest_page_id,
			'box_name' => $boxName,
			'field_name' => $fieldName,
			'value' => $value,
		]);

		$this->assertTrue($add_acpt_meta_field_value);
	}

	/**
	 * delete the meta field
	 */
	private function delete_meta_field()
	{
		$delete_acpt_meta_box = delete_acpt_meta_box('page', 'box_name');

		$this->assertTrue($delete_acpt_meta_box);
	}
}