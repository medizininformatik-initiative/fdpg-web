<?php

namespace ACPT\Core\Models\OptionPage;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxModel;
use ACPT\Costants\MetaTypes;

/**
 * OptionPageMetaBoxModel
 *
 * @since      1.0.150
 * @package    advanced-custom-post-type
 * @subpackage advanced-custom-post-type/core
 * @author     Mauro Cassani <maurocassani1978@gmail.com>
 */
class OptionPageMetaBoxModel  extends AbstractMetaBoxModel implements \JsonSerializable
{
	/**
	 * @var string
	 */
	private $optionPage;

	/**
	 * OptionPageMetaBoxModel constructor.
	 *
	 * @param $id
	 * @param $optionPage
	 * @param $name
	 * @param $sort
	 */
	public function __construct(
		$id,
		$optionPage,
		$name,
		$sort
	)
	{
		// @TODO Retro-compatibility issue. Too dangerous for now
//	    if(!Strings::alphanumericallyValid($name)){
//		    throw new \DomainException($name . ' is not valid name');
//	    }

		parent::__construct($id);
		$this->optionPage = $optionPage;
		$this->name     = $name;
		$this->sort     = $sort;
		$this->fields   = [];
	}

	/**
	 * @param $optionPage
	 * @param $name
	 * @param $sort
	 */
	public function edit(
		$optionPage,
		$name,
		$sort
	)
	{
		// @TODO Retro-compatibility issue. Too dangerous for now
//	    if(!Strings::alphanumericallyValid($name)){
//		    throw new \DomainException($name . ' is not valid name');
//	    }

		$this->optionPage = $optionPage;
		$this->name     = $name;
		$this->sort     = $sort;
		$this->fields   = [];
	}

	/**
	 * @return string
	 */
	public function getOptionPage()
	{
		return $this->optionPage;
	}

	/**
	 * @param $optionPage
	 */
	public function changeOptionPage($optionPage)
	{
		$this->optionPage = $optionPage;
	}

	/**
	 * @inheritDoc
	 */
	public function metaType()
	{
		return MetaTypes::OPTION_PAGE;
	}

	#[\ReturnTypeWillChange]
	public function jsonSerialize()
	{
		return [
			'id' => $this->id,
			'title' => $this->name,
			'label' => $this->label,
			'find' => $this->optionPage,
			'sort' => (int)$this->sort,
			'belongsTo' => $this->metaType(),
			'fields' => $this->fields
		];
	}
}