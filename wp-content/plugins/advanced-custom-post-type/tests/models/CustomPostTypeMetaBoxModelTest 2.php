<?php

namespace ACPT\Tests;

use ACPT\Core\Helper\Uuid;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxModel;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldAdvancedOptionModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldOptionModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldVisibilityModel;

class CustomPostTypeMetaBoxModelTest extends AbstractTestCase
{
	/**
	 * @test
	 */
	public function can_duplicate_a_basic_cpt_mb()
	{
		$boxModel = $this->getDummyMetaBoxModel();

		$boxModelField = new CustomPostTypeMetaBoxFieldModel(
			Uuid::v4(),
			$boxModel,
			'field',
			AbstractMetaBoxFieldModel::TEXT_TYPE,
			false,
			false,
			1,
		);

		$boxModel->addField($boxModelField);


		$duplicateModel = $boxModel->duplicate();

		$this->runTests($boxModel, $duplicateModel);
	}

	/**
	 * @test
	 */
	public function can_duplicate_a_cpt_mb_with_options()
	{
		$boxModel = $this->getDummyMetaBoxModel();

		$boxModelField = new CustomPostTypeMetaBoxFieldModel(
			Uuid::v4(),
			$boxModel,
			'field2',
			AbstractMetaBoxFieldModel::SELECT_TYPE,
			false,
			false,
			1,
		);

		$boxModelOption1 = new MetaBoxFieldOptionModel(
			Uuid::v4(),
			$boxModelField,
			'label',
			'value',
			1,
		);

		$boxModelOption2 = new MetaBoxFieldOptionModel(
			Uuid::v4(),
			$boxModelField,
			'label2',
			'value2',
			2,
		);

		$boxModelField->addOption($boxModelOption1);
		$boxModelField->addOption($boxModelOption2);

		$boxModel->addField($boxModelField);

		$duplicateModel = $boxModel->duplicate();

		$this->runTests($boxModel, $duplicateModel);
	}

	/**
	 * @test
	 */
	public function can_duplicate_a_cpt_mb_with_advanced_options()
	{
		$boxModel = $this->getDummyMetaBoxModel();

		$boxModelField = new CustomPostTypeMetaBoxFieldModel(
			Uuid::v4(),
			$boxModel,
			'field2',
			AbstractMetaBoxFieldModel::TEXT_TYPE,
			false,
			false,
			1,
		);

		$boxModelOption1 = new MetaBoxFieldAdvancedOptionModel(
			Uuid::v4(),
			$boxModelField,
			'key',
			'value',
		);

		$boxModelOption2 = new MetaBoxFieldAdvancedOptionModel(
			Uuid::v4(),
			$boxModelField,
			'key2',
			'value2',
		);

		$boxModelField->addAdvancedOption($boxModelOption1);
		$boxModelField->addAdvancedOption($boxModelOption2);

		$boxModel->addField($boxModelField);

		$duplicateModel = $boxModel->duplicate();

		$this->runTests($boxModel, $duplicateModel);
	}

	/**
	 * @test
	 * @throws \Exception
	 */
	public function can_duplicate_a_cpt_mb_with_visibility_conditions()
	{
		$boxModel = $this->getDummyMetaBoxModel();

		$boxModelField = new CustomPostTypeMetaBoxFieldModel(
			Uuid::v4(),
			$boxModel,
			'field2',
			AbstractMetaBoxFieldModel::TEXT_TYPE,
			false,
			false,
			1,
		);

		$condition1 = new MetaBoxFieldVisibilityModel(
			Uuid::v4(),
			$boxModelField,
			[
				'type' => 'VALUE'
			],
			'=',
			'123',
			1,
		);

		$condition2 = new MetaBoxFieldVisibilityModel(
			Uuid::v4(),
			$boxModelField,
			[
				'type' => 'VALUE'
			],
			'!=',
			'123',
			2,
		);

		$boxModelField->addVisibilityCondition($condition1);
		$boxModelField->addVisibilityCondition($condition2);

		$boxModel->addField($boxModelField);

		$duplicateModel = $boxModel->duplicate();

		$this->runTests($boxModel, $duplicateModel);
	}

	/**
	 * @test
	 * @throws \Exception
	 */
	public function can_duplicate_a_cpt_mb_with_children()
	{
		$boxModel = $this->getDummyMetaBoxModel();

		$boxModelField = new CustomPostTypeMetaBoxFieldModel(
			Uuid::v4(),
			$boxModel,
			'field2',
			AbstractMetaBoxFieldModel::REPEATER_TYPE,
			false,
			false,
			1,
		);

		$child1 = new CustomPostTypeMetaBoxFieldModel(
			Uuid::v4(),
			$boxModel,
			'field2',
			AbstractMetaBoxFieldModel::TEXT_TYPE,
			false,
			false,
			1,
		);

		$child2 = new CustomPostTypeMetaBoxFieldModel(
			Uuid::v4(),
			$boxModel,
			'field33',
			AbstractMetaBoxFieldModel::TEXT_TYPE,
			false,
			false,
			1,
		);

		$boxModelField->addChild($child1);
		$boxModelField->addChild($child2);

		$boxModel->addField($boxModelField);

		$duplicateModel = $boxModel->duplicate();

		$this->runTests($boxModel, $duplicateModel);
	}

	/**
	 * @return CustomPostTypeMetaBoxModel
	 */
	private function getDummyMetaBoxModel()
	{
		return new CustomPostTypeMetaBoxModel(
			Uuid::v4(),
			'page',
			'test',
			1
		);
	}

	/**
	 * @param AbstractMetaBoxModel $boxModel
	 * @param AbstractMetaBoxModel $duplicateModel
	 */
	protected function runTests(AbstractMetaBoxModel $boxModel, AbstractMetaBoxModel $duplicateModel)
	{
		$this->assertNotEquals($duplicateModel->getId(), $boxModel->getId());

		foreach ($duplicateModel->getFields() as $i => $duplicatedFieldModel){

			$originalFieldModel = $boxModel->getFields()[$i];

			$this->assertNotEquals($duplicatedFieldModel->getId(), $originalFieldModel->getId());
			$this->assertNotEquals($duplicatedFieldModel->getMetaBox()->getId(), $originalFieldModel->getMetaBox()->getId());

			foreach ($duplicatedFieldModel->getOptions() as $k => $duplicatedOptionModel){

				$originalOptionModel = $originalFieldModel->getOptions()[$k];

				$this->assertNotEquals($duplicatedOptionModel->getId(), $originalOptionModel->getId());
				$this->assertNotEquals($duplicatedOptionModel->getMetaBoxField()->getId(), $originalOptionModel->getMetaBoxField()->getId());
			}

			foreach ($duplicatedFieldModel->getAdvancedOptions() as $a => $duplicatedAdvancedOptionModel){

				$originalAdvancedOptionModel = $originalFieldModel->getAdvancedOptions()[$a];

				$this->assertNotEquals($duplicatedAdvancedOptionModel->getId(), $originalAdvancedOptionModel->getId());
				$this->assertNotEquals($duplicatedAdvancedOptionModel->getMetaBoxField()->getId(), $originalAdvancedOptionModel->getMetaBoxField()->getId());
			}

			foreach ($duplicatedFieldModel->getVisibilityConditions() as $v => $duplicatedVisibilityConditionModel){

				$originalVisibilityConditionModel = $originalFieldModel->getVisibilityConditions()[$v];

				$this->assertNotEquals($duplicatedVisibilityConditionModel->getId(), $originalVisibilityConditionModel->getId());
				$this->assertNotEquals($duplicatedVisibilityConditionModel->getMetaBoxField()->getId(), $originalVisibilityConditionModel->getMetaBoxField()->getId());
			}

			foreach ($duplicatedFieldModel->getChildren() as $c => $duplicatedChildModel){

				$originalChildModel = $originalFieldModel->getChildren()[$c];

				$this->assertNotEquals($duplicatedChildModel->getId(), $originalChildModel->getId());
				$this->assertNotEquals($duplicatedChildModel->getParentId(), $originalChildModel->getParentId());
			}
		}
	}
}