<?php

namespace ACPT\Costants;

class MetaTypes
{
    const CUSTOM_POST_TYPE = 'customPostType';
    const OPTION_PAGE = 'optionPage';
    const TAXONOMY = 'taxonomy';
    const USER = 'user';

	/**
	 * @param $value
	 *
	 * @return string|null
	 */
    public static function label($value)
    {
    	$map = [
		    self::CUSTOM_POST_TYPE => 'CPT',
		    self::OPTION_PAGE => 'OP',
		    self::TAXONOMY => 'TAX',
		    self::USER => 'USER',
	    ];

    	if(!isset($map[$value])){
    		return null;
	    }

    	return $map[$value];
    }
}