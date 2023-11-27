<?php

namespace ACPT\Core\Models\CustomPostType;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxModel;
use ACPT\Costants\MetaTypes;

/**
 * MetaBoxModel
 *
 * @since      1.0.0
 * @package    advanced-custom-post-type
 * @subpackage advanced-custom-post-type/core
 * @author     Mauro Cassani <maurocassani1978@gmail.com>
 */
class CustomPostTypeMetaBoxModel extends AbstractMetaBoxModel implements \JsonSerializable
{
    /**
     * @var string
     */
    private $postType;

    /**
     * MetaBoxModel constructor.
     *
     * @param int    $id
     * @param string $postType
     * @param string $name
     * @param int    $sort
     */
    public function __construct(
        $id,
        $postType,
        $name,
        $sort
    )
    {
	    // @TODO Retro-compatibility issue. Too dangerous for now
//	    if(!Strings::alphanumericallyValid($name)){
//		    throw new \DomainException($name . ' is not valid name');
//	    }

        parent::__construct($id);
        $this->postType = $postType;
        $this->name     = $name;
        $this->sort     = $sort;
        $this->fields   = [];
    }

    /**
     * @param $postType
     * @param $name
     * @param $sort
     */
    public function edit(
        $postType,
        $name,
        $sort
    )
    {
	    // @TODO Retro-compatibility issue. Too dangerous for now
//	    if(!Strings::alphanumericallyValid($name)){
//		    throw new \DomainException($name . ' is not valid name');
//	    }

        $this->postType = $postType;
        $this->name     = $name;
        $this->sort     = $sort;
        $this->fields   = [];
    }

    /**
     * @return string
     */
    public function metaType()
    {
        return MetaTypes::CUSTOM_POST_TYPE;
    }

	/**
	 * @param $postType
	 */
	public function changePostType($postType)
	{
		$this->postType = $postType;
	}

    /**
     * @return string
     */
    public function getPostType()
    {
        return $this->postType;
    }

	#[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'title' => $this->name,
            'label' => $this->label,
            'find' => $this->postType,
            'sort' => (int)$this->sort,
            'belongsTo' => $this->metaType(),
            'fields' => $this->fields
        ];
    }
}