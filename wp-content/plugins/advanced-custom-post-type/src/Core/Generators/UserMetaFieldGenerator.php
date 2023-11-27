<?php

namespace ACPT\Core\Generators;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Generators\UserFields\UserMetaFieldInterface;
use ACPT\Core\Models\User\UserMetaBoxFieldModel;

class UserMetaFieldGenerator
{
    /**
     * @var UserMetaBoxFieldModel
     */
    private $userField;

    /**
     * @var \WP_User $user
     */
    private $user;

    /**
     * UserMetaFieldGenerator constructor.
     *
     * @param UserMetaBoxFieldModel $userField
     * @param \WP_User           $user
     */
    public function __construct(UserMetaBoxFieldModel $userField, \WP_User $user)
    {
        $this->userField = $userField;
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function generate()
    {
        $field = $this->getUserMetaField();

	    if($field){
		    return $field->render();
	    }

	    return null;
    }

    /**
     * @return MetaFieldInterface
     */
    private function getUserMetaField()
    {
        $className = 'ACPT\\Core\\Generators\\UserFields\\'.$this->userField->getType().'Field';

        if(class_exists($className)){
            return new $className(
                $this->user->ID,
                $this->userField->getMetaBox(),
                $this->userField->getName(),
                $this->userField->getSort(),
                $this->userField->isRequired(),
                $this->userField->getDefaultValue(),
                $this->userField->getDescription(),
                $this->userField->getOptions(),
	            $this->userField->getAdvancedOptions()
            );
        }

        return null;
    }
}