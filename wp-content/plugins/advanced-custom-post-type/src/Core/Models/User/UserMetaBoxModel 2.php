<?php

namespace ACPT\Core\Models\User;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxModel;
use ACPT\Costants\MetaTypes;

/**
 * MetaBoxModel
 *
 * @since      1.0.60
 * @package    advanced-custom-post-type
 * @subpackage advanced-custom-post-type/core
 * @author     Mauro Cassani <maurocassani1978@gmail.com>
 */
class UserMetaBoxModel extends AbstractMetaBoxModel implements \JsonSerializable
{
    /**
     * UserMetaBoxModel constructor.
     *
     * @param $id
     * @param $name
     * @param $sort
     */
    public function __construct(
        $id,
        $name,
        $sort
    )
    {
	    // @TODO Retro-compatibility issue. Too dangerous for now
//	    if(!Strings::alphanumericallyValid($name)){
//		    throw new \DomainException($name . ' is not valid name');
//	    }

        parent::__construct($id);
        $this->name     = $name;
        $this->sort     = $sort;
        $this->fields   = [];
    }

    /**
     * @param $name
     * @param $sort
     */
    public function edit(
        $name,
        $sort
    )
    {
	    // @TODO Retro-compatibility issue. Too dangerous for now
//	    if(!Strings::alphanumericallyValid($name)){
//		    throw new \DomainException($name . ' is not valid name');
//	    }

        $this->name     = $name;
        $this->sort     = $sort;
        $this->fields   = [];
    }

    /**
     * @return string
     */
    public function metaType()
    {
        return MetaTypes::USER;
    }

	#[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'title' => $this->name,
            'sort' => (int)$this->sort,
            'belongsTo' => $this->metaType(),
            'fields' => $this->fields
        ];
    }
}