<?php

namespace ACPT\Core\Generators;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;

/**
 * *************************************************
 * OptionPageMetaBoxGenerator class
 * *************************************************
 *
 * @author Mauro Cassani
 * @link https://github.com/mauretto78/
 */
class OptionPageMetaBoxFieldGenerator extends AbstractGenerator
{
	/**
	 * @var AbstractMetaBoxFieldModel
	 */
	private AbstractMetaBoxFieldModel $fieldModel;

	/**
	 * OptionPageMetaBoxFieldGenerator constructor.
	 *
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 */
	public function __construct(AbstractMetaBoxFieldModel $fieldModel)
	{
		$this->fieldModel = $fieldModel;
	}

	/**
	 * @return string
	 */
	public function render()
	{
		$field = $this->getOptionPageField();

		if($field !== null){
			return $field->render();
		}

		return null;
	}

	/**
	 * @return MetaFieldInterface
	 */
	private function getOptionPageField()
	{
		$className = 'ACPT\\Core\\Generators\\OptionPageFields\\'.$this->fieldModel->getType().'Field';

		if(class_exists($className)){
			return new $className($this->fieldModel);
		}

		return null;
	}
}