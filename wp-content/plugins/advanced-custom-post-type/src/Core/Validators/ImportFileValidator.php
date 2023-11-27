<?php

namespace ACPT\Core\Validators;

use ACPT\Costants\MetaTypes;

class ImportFileValidator
{
	/**
	 * @TODO needs to be improved
	 * @param array $data
	 *
	 * @return bool
	 */
	public static function validate(array $data)
	{
		if(empty($data)){
			return false;
		}

		$allowedKeys = [
			MetaTypes::CUSTOM_POST_TYPE,
			MetaTypes::TAXONOMY,
			MetaTypes::USER,
		];

		if(!empty(array_diff($allowedKeys, array_keys($data)))){
			return false;
		}

		foreach ($data[MetaTypes::CUSTOM_POST_TYPE] as $item){
			return (
				isset($item['id']) and
				isset($item['name']) and
				isset($item['singular']) and
				isset($item['plural']) and
				isset($item['icon']) and
				isset($item['postCount']) and
				isset($item['supports']) and
				isset($item['labels']) and
				isset($item['settings']) and
				isset($item['templates']) and
				isset($item['meta'])
			);
		}

		foreach ($data[MetaTypes::TAXONOMY] as $item){
			return (
				isset($item['id']) and
				isset($item['slug']) and
				isset($item['singular']) and
				isset($item['plural']) and
				isset($item['icon']) and
				isset($item['postCount']) and
				isset($item['labels']) and
				isset($item['settings']) and
				isset($item['templates']) and
				isset($item['meta'])
			);
		}

		foreach ($data[MetaTypes::USER] as $item){
			return (
				isset($item['id']) and
				isset($item['belongsTo']) and
				isset($item['fields']) and
				isset($item['sort']) and
				isset($item['title'])
			);
		}

		return true;
	}
}