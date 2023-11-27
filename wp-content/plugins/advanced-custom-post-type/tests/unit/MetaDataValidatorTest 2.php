<?php

namespace ACPT\Tests;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Validators\MetaDataValidator;

class MetaDataValidatorTest extends AbstractTestCase
{
	/**
	 * @test
	 */
	public function can_validate_data()
	{
		$data = [
			[
				'type' => CustomPostTypeMetaBoxFieldModel::ADDRESS_TYPE,
				'value' => 'Via Roma 23, Milano'
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::COLOR_TYPE,
				'value' => '#dddddd'
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::CHECKBOX_TYPE,
				'value' => [
					'this is a value',
					'this is a another value',
					'this is a last value',
				],
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::CURRENCY_TYPE,
				'value' => '345.65'
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::DATE_TYPE,
				'value' => '2020-02-02'
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::EDITOR_TYPE,
				'value' => '<p>This is an HTML string</p>'
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::EMAIL_TYPE,
				'value' => 'maurocassani1978@gmail.com'
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::EMBED_TYPE,
				'value' => 'https://youtu.be/t8CYWZ2P8l8'
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::FILE_TYPE,
				'value' => 'https://acpt.io/wp-content/2020/03/sample.txt'
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::HTML_TYPE,
				'value' => '<p>This is an HTML string</p>'
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::GALLERY_TYPE,
				'value' => [
					'https://acpt.io/img/logo.png',
					'https://acpt.io/img/logo2.png',
					'https://acpt.io/img/logo3.png',
				]
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::IMAGE_TYPE,
				'value' => 'https://acpt.io/img/logo.png'
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::LENGTH_TYPE,
				'value' => '43'
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::LIST_TYPE,
				'value' => [
					'element1',
					'element2',
					'element3',
					'element4',
				]
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::NUMBER_TYPE,
				'value' => '545'
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::POST_TYPE,
				'value' => '545'
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::POST_TYPE,
				'value' => [
					'545',
					'45645',
					43,
				]
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::PHONE_TYPE,
				'value' => '+3978000000'
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::REPEATER_TYPE,
				'value' => [
					'text' => [
						[
							'type' => [
								CustomPostTypeMetaBoxFieldModel::TEXT_TYPE
							],
							'value' => [
								'This is a string'
							]
						],
						[
							'type' => [
								CustomPostTypeMetaBoxFieldModel::TEXT_TYPE,
								CustomPostTypeMetaBoxFieldModel::TEXT_TYPE,
							],
							'value' => [
								'This is a string 2',
								'This is a string 3',
							]
						],
					],
				],
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::RADIO_TYPE,
				'value' => 'this is radio value'
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::SELECT_TYPE,
				'value' => 'this is select value'
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::SELECT_MULTI_TYPE,
				'value' => [
					'this is a multiselect value',
					'this is a another multiselect value',
					'this is a last multiselect value',
				],
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::TEXT_TYPE,
				'value' => 'This is a text string'
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::TEXTAREA_TYPE,
				'value' => 'This is a string'
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::TIME_TYPE,
				'value' => '23:59'
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::TOGGLE_TYPE,
				'value' => '1'
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::VIDEO_TYPE,
				'value' => 'https://acpt.io/video/video.mp4'
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::WEIGHT_TYPE,
				'value' => 345
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::URL_TYPE,
				'value' => 'https://acpt.io'
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::USER_TYPE,
				'value' => '23'
			],
			[
				'type' => CustomPostTypeMetaBoxFieldModel::USER_MULTI_TYPE,
				'value' => [
					'23',
					434,
					'333',
				],
			],
		];

		foreach ($data as $datum){
			try {
				MetaDataValidator::validate($datum['type'], $datum['value']);

				$this->assertTrue(true);
			} catch (\Exception $exception){
				$this->fail('['.$datum['type'].']: ' . $exception->getMessage());
			}
		}
	}
}