<?php

namespace ACPT\Core\ValueObjects;

use ACPT\Core\Models\CustomPostType\CustomPostTypeModel;
use ACPT\Core\Models\OptionPage\OptionPageModel;
use ACPT\Core\Models\Taxonomy\TaxonomyModel;
use ACPT\Costants\MetaTypes;

class RelatedEntityValueObject implements \JsonSerializable
{
	/**
	 * @var string
	 */
	private $type;

	/**
	 * @var mixed
	 */
	private $value;

	/**
	 * RelatedEntityValueObject constructor.
	 *
	 * @param $type
	 * @param $value
	 *
	 * @throws \Exception
	 */
	public function __construct($type, $value)
	{
		$this->setType($type);
		$this->setValue($value);
	}

	/**
	 * @param $type
	 *
	 * @throws \Exception
	 */
	private function setType($type)
	{
		$allowedTypes = [
			MetaTypes::CUSTOM_POST_TYPE,
			MetaTypes::TAXONOMY,
			MetaTypes::OPTION_PAGE,
			MetaTypes::USER,
		];

		if(!in_array($type, $allowedTypes)){
			throw new \Exception($type . ' is not a valid RelatedEntityValueObject type');
		}

		$this->type = $type;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param $value
	 *
	 * @throws \Exception
	 */
	private function setValue($value)
	{
		switch ($this->type){
			case MetaTypes::CUSTOM_POST_TYPE:
				if(!$value instanceof CustomPostTypeModel){
					throw new \Exception('Wrong RelatedEntityValueObject value (expecting CustomPostTypeModel instance)');
				}
				break;

			case MetaTypes::TAXONOMY:
				if(!$value instanceof TaxonomyModel){
					throw new \Exception('Wrong RelatedEntityValueObject value (expecting TaxonomyModel instance)');
				}

				break;

			case MetaTypes::OPTION_PAGE:
				if(!$value instanceof OptionPageModel){
					throw new \Exception('Wrong RelatedEntityValueObject value (expecting OptionPageModel instance)');
				}

				break;

			case MetaTypes::USER:
				if(!$value instanceof \WP_User){
					throw new \Exception('Wrong RelatedEntityValueObject value (expecting WP_User instance)');
				}

				break;
		}

		$this->value = $value;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @return int|string
	 */
	public function getHumanReadableValue()
	{
		switch ($this->type){
			case MetaTypes::CUSTOM_POST_TYPE:

				/** @var CustomPostTypeModel $value */
				$value = $this->value;

				return $value->getName();

			case MetaTypes::TAXONOMY:

				/** @var TaxonomyModel $value */
				$value = $this->value;

				return $value->getSlug();

			case MetaTypes::OPTION_PAGE:

				/** @var OptionPageModel $value */
				$value = $this->value;

				return $value->getMenuSlug();

			case MetaTypes::USER:

				/** @var \WP_User $value */
				$value = $this->value;

				return $value->ID;
		}
	}

	/**
	 * @return false|string
	 */
	public function humanReadableJsonFormat()
	{
		return json_encode([
			'type' => $this->type,
			'value' => $this->getHumanReadableValue(),
		]);
	}

	#[\ReturnTypeWillChange]
	public function jsonSerialize()
	{
		return [
			'type' => $this->type,
			'value' => $this->getHumanReadableValue(),
		];
	}
}