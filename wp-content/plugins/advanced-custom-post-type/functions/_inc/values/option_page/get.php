<?php

use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Core\Validators\ArgumentsArrayValidator;
use ACPT\Core\Values\OptionPageMetaValue;
use ACPT\Costants\MetaTypes;
use ACPT\Utils\Checker\FieldVisibilityChecker;

if(!function_exists('get_acpt_option_page_fields'))
{
	/**
	 * @param array $args
	 *
	 * @return array|null
	 */
	function get_acpt_option_page_fields(array $args = [])
	{
		try {
			// validate array
			$mandatory_keys = [
				'option_page' => [
					'required' => true,
					'type' => 'string',
				],
				'box_name' => [
					'required' => true,
					'type' => 'string',
				],
			];

			$validator = new ArgumentsArrayValidator();

			if(!$validator->validate($mandatory_keys, $args)){
				return null;
			}

			$meta_box_model = MetaRepository::getMetaBoxByName([
				'belongsTo' => MetaTypes::OPTION_PAGE,
				'find' => $args['option_page'],
				'boxName' => $args['box_name'],
			]);

			$values = [];

			foreach ($meta_box_model->getFields() as $meta_field_model){
				$post_meta_value = new OptionPageMetaValue($meta_field_model);
				$values[$meta_field_model->getName()] = $post_meta_value->getValue();
			}

			return $values;

		} catch (\Exception $exception){
			return [];
		}
	}
}

if(!function_exists('get_acpt_option_page_field'))
{
	/**
	 * @param array $args
	 *
	 * @return mixed|null
	 */
	function get_acpt_option_page_field(array $args = [])
	{
		try {
			// validate array
			$mandatory_keys = [
				'option_page' => [
					'required' => false,
					'type' => 'string',
				],
				'box_name' => [
					'required' => true,
					'type' => 'string',
				],
				'field_name' => [
					'required' => true,
					'type' => 'string',
				],
			];

			$validator = new ArgumentsArrayValidator();

			if(!$validator->validate($mandatory_keys, $args)){
				return null;
			}

			$option_page = isset($args['option_page']) ? $args['option_page'] : null;

			$meta_field_model = MetaRepository::getMetaFieldByName([
				'belongsTo' => MetaTypes::OPTION_PAGE,
				'find' => $option_page,
				'boxName' => $args['box_name'],
				'fieldName' => $args['field_name']
			]);

			if($meta_field_model === null){
				return null;
			}

			$post_meta_value = new OptionPageMetaValue($meta_field_model);

			return $post_meta_value->getValue();

		} catch (\Exception $exception){
			return null;
		}
	}
}

if(!function_exists('acpt_option_page_field_has_rows'))
{
	function acpt_option_page_field_has_rows(array $args = [])
	{
		try {
			$get_acpt_option_page_field = get_acpt_option_page_field($args);

			$meta_field_model = MetaRepository::getMetaFieldByName([
				'belongsTo' => MetaTypes::OPTION_PAGE,
				'find' => $args['option_page'],
				'boxName' => $args['box_name'],
				'fieldName' => $args['field_name']
			]);

			if($meta_field_model->getType() !== OptionPageMetaBoxFieldModel::LIST_TYPE and $meta_field_model->getType() !== OptionPageMetaBoxFieldModel::REPEATER_TYPE){
				return false;
			}

			if(!is_array($get_acpt_option_page_field)){
				return false;
			}

			return !empty($get_acpt_option_page_field);

		} catch (\Exception $exception){
			return false;
		}
	}
}

if( !function_exists('get_acpt_option_page_block') ){

	function get_acpt_option_page_block(array $args = [])
	{
		// validate array
		$mandatory_keys = [
			'option_page' => [
				'required' => true,
				'type' => 'string',
			],
			'box_name' => [
				'required' => true,
				'type' => 'string',
			],
			'parent_field_name' => [
				'required' => true,
				'type' => 'string',
			],
			'block_name' => [
				'required' => true,
				'type' => 'string',
			],
		];

		$validator = new ArgumentsArrayValidator();

		if(!$validator->validate($mandatory_keys, $args)){
			return null;
		}

		$parent_field = get_acpt_option_page_field([
			'post_id' => $args['option_page'],
			'box_name' => $args['box_name'],
			'field_name' => $args['parent_field_name'],
		]);

		if(!isset($parent_field['blocks'])){
			return null;
		}

		$blocks = [];

		foreach ($parent_field['blocks'] as $block){
			foreach (array_keys($block) as $blockName){
				if($blockName === $args['block_name']){
					$blocks[] = $block;
				}
			}
		}

		return $blocks;
	}
}

if(!function_exists('get_acpt_option_page_child_field'))
{
	function get_acpt_option_page_child_field(array $args = [])
	{
		// validate array
		$mandatory_keys = [
			'option_page' => [
				'required' => true,
				'type' => 'string',
			],
			'box_name' => [
				'required' => true,
				'type' => 'string',
			],
			'field_name' => [
				'required' => true,
				'type' => 'string',
			],
			'parent_field_name' => [
				'required' => true,
				'type' => 'string',
			],
			'index' => [
				'required' => true,
				'type' => 'string|integer',
			],
		];

		$validator = new ArgumentsArrayValidator();

		if(!$validator->validate($mandatory_keys, $args)){
			return null;
		}

		$parent_field = get_acpt_option_page_field([
			'option_page' => $args['option_page'],
			'box_name' => $args['box_name'],
			'field_name' => $args['parent_field_name'],
		]);

		return (isset($parent_field[$args['index']][$args['field_name']])) ? $parent_field[$args['index']][$args['field_name']] : null;
	}
}

if(!function_exists('acpt_option_page_child_field'))
{
	function acpt_option_page_child_field(array $args = [])
	{
		// validate array
		$mandatory_keys = [
			'option_page' => [
				'required' => true,
				'type' => 'string',
			],
			'box_name' => [
				'required' => true,
				'type' => 'string',
			],
			'field_name' => [
				'required' => true,
				'type' => 'string',
			],
			'parent_field_name' => [
				'required' => true,
				'type' => 'string',
			],
			'index' => [
				'required' => true,
				'type' => 'string|integer',
			],
			'date_format' => [
				'required' => false,
				'type' => 'string',
			],
			'width' => [
				'required' => false,
				'type' => 'string|integer',
			],
			'height' => [
				'required' => false,
				'type' => 'string|integer',
			],
			'target' => [
				'required' => false,
				'type' => 'string',
				'enum' => [
					'_blank',
					'_self',
					'_parent',
					'_top',
				],
			],
			'elements' => [
				'required' => false,
				'type' => 'integer',
			],
			'render' => [
				'required' => false,
				'type' => 'string',
			],
		];

		$validator = new ArgumentsArrayValidator();

		if(!$validator->validate($mandatory_keys, $args)){
			return null;
		}

		$parent_field = get_acpt_option_page_field([
			'option_page' => $args['option_page'],
			'box_name' => $args['box_name'],
			'field_name' => $args['parent_field_name'],
		]);

		return (isset($parent_field[$args['index']][$args['field_name']])) ? $parent_field[$args['index']][$args['field_name']] : null;
	}
}

if(!function_exists('acpt_option_page_field'))
{
	/**
	 * @param array $args
	 *
	 * @return string|null
	 */
	function acpt_option_page_field(array $args = [])
	{
		// validate array
		$mandatory_keys = [
			'option_page' => [
				'required' => true,
				'type' => 'string',
			],
			'box_name' => [
				'required' => true,
				'type' => 'string',
			],
			'field_name' => [
				'required' => true,
				'type' => 'string',
			],
			'date_format' => [
				'required' => false,
				'type' => 'string',
			],
			'width' => [
				'required' => false,
				'type' => 'string|integer',
			],
			'height' => [
				'required' => false,
				'type' => 'string|integer',
			],
			'target' => [
				'required' => false,
				'type' => 'string',
				'enum' => [
					'_blank',
					'_self',
					'_parent',
					'_top',
				],
			],
			'elements' => [
				'required' => false,
				'type' => 'integer',
			],
			'render' => [
				'required' => false,
				'type' => 'string',
			],
		];

		$validator = new ArgumentsArrayValidator();

		if(!$validator->validate($mandatory_keys, $args)){
			return null;
		}

		$shortcode = '[acpt_option page="'.$args['option_page'].'" box="'.$args['box_name'].'" field="'.$args['field_name'].'"';

		if(isset($args['date_format'])){
			$shortcode .= ' date-format="'.$args['date_format'].'"';
		}

		if(isset($args['width'])){
			$shortcode .= ' width="'.$args['width'].'"';
		}

		if(isset($args['height'])){
			$shortcode .= ' height="'.$args['height'].'"';
		}

		if(isset($args['target'])){
			$shortcode .= ' target="'.$args['target'].'"';
		}

		if(isset($args['elements'])){
			$shortcode .= ' elements="'.$args['elements'].'"';
		}

		if(isset($args['render'])){
			$shortcode .= ' render="'.$args['render'].'"';
		}

		$shortcode .= ']';

		return do_shortcode($shortcode);
	}
}

if(!function_exists('acpt_option_page_field_has_blocks')){

	function acpt_option_page_field_has_blocks(array $args = [])
	{
		try {
			$get_acpt_field = get_acpt_option_page_field($args);
			$option_page = isset($args['option_page']) ? $args['option_page'] : null;

			$meta_field_model = MetaRepository::getMetaFieldByName([
				'belongsTo' => MetaTypes::OPTION_PAGE,
				'find' => $option_page,
				'boxName' => $args['box_name'],
				'fieldName' => $args['field_name']
			]);

			if($meta_field_model->getType() !== OptionPageMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE){
				return false;
			}

			if(!is_array($get_acpt_field)){
				return false;
			}

			if(!isset($get_acpt_field['blocks'])){
				return false;
			}

			return !empty($get_acpt_field['blocks']);

		} catch (\Exception $exception){
			return false;
		}
	}
}

if(!function_exists('get_acpt_option_page_block_child_field')){

	function get_acpt_option_page_block_child_field(array $args = [])
	{
		// validate array
		$mandatory_keys = [
			'option_page' => [
				'required' => false,
				'type' => 'string',
			],
			'box_name' => [
				'required' => true,
				'type' => 'string',
			],
			'field_name' => [
				'required' => true,
				'type' => 'string',
			],
			'parent_field_name' => [
				'required' => true,
				'type' => 'string',
			],
			'index' => [
				'required' => true,
				'type' => 'string|integer',
			],
			'block_name' => [
				'required' => false,
				'type' => 'string',
			],
			'block_index' => [
				'required' => false,
				'type' => 'string|integer',
			],
		];

		$validator = new ArgumentsArrayValidator();

		if(!$validator->validate($mandatory_keys, $args)){
			return null;
		}

		$parent_field = get_acpt_option_page_field([
			'option_page' => $args['option_page'],
			'box_name' => $args['box_name'],
			'field_name' => $args['parent_field_name'],
		]);

		if(!isset($parent_field['blocks'])){
			return null;
		}

		if(!isset($parent_field['blocks'][$args['block_index']])){
			return null;
		}

		if(!isset($parent_field['blocks'][$args['block_index']][$args['block_name']])){
			return null;
		}

		if(!isset($parent_field['blocks'][$args['block_index']][$args['block_name']])){
			return null;
		}

		return (isset($parent_field['blocks'][$args['block_index']][$args['block_name']][$args['field_name']][$args['index']])) ? $parent_field['blocks'][$args['block_index']][$args['block_name']][$args['field_name']][$args['index']] : null;
	}
}

if(!function_exists('acpt_option_page_block_child_field')){

	function acpt_option_page_block_child_field(array $args = [])
	{
		// validate array
		$mandatory_keys = [
			'option_page' => [
				'required' => true,
				'type' => 'string',
			],
			'box_name' => [
				'required' => true,
				'type' => 'string',
			],
			'field_name' => [
				'required' => true,
				'type' => 'string',
			],
			'parent_field_name' => [
				'required' => true,
				'type' => 'string',
			],
			'index' => [
				'required' => true,
				'type' => 'string|integer',
			],
			'date_format' => [
				'required' => false,
				'type' => 'string',
			],
			'width' => [
				'required' => false,
				'type' => 'string|integer',
			],
			'height' => [
				'required' => false,
				'type' => 'string|integer',
			],
			'target' => [
				'required' => false,
				'type' => 'string',
				'enum' => [
					'_blank',
					'_self',
					'_parent',
					'_top',
				],
			],
			'elements' => [
				'required' => false,
				'type' => 'integer',
			],
			'block_name' => [
				'required' => false,
				'type' => 'string',
			],
			'block_index' => [
				'required' => false,
				'type' => 'string|integer',
			],
			'render' => [
				'required' => false,
				'type' => 'string',
			],
		];

		$validator = new ArgumentsArrayValidator();

		if(!$validator->validate($mandatory_keys, $args)){
			return null;
		}

		$shortcode = '[acpt_option page="'.$args['option_page'].'" box="'.$args['box_name'].'" parent="'.$args['parent_field_name'].'" block_name="'.$args['block_name'].'" block_index="'.$args['block_index'].'" index="'.$args['index'].'" field="'.$args['field_name'].'"';

		if(isset($args['date_format'])){
			$shortcode .= ' date-format="'.$args['date_format'].'"';
		}

		if(isset($args['width'])){
			$shortcode .= ' width="'.$args['width'].'"';
		}

		if(isset($args['height'])){
			$shortcode .= ' height="'.$args['height'].'"';
		}

		if(isset($args['target'])){
			$shortcode .= ' target="'.$args['target'].'"';
		}

		if(isset($args['elements'])){
			$shortcode .= ' elements="'.$args['elements'].'"';
		}

		if(isset($args['render'])){
			$shortcode .= ' render="'.$args['render'].'"';
		}

		$shortcode .= ']';

		return do_shortcode($shortcode);
	}
}

if( !function_exists('is_acpt_option_page_field_visible') ){

	/**
	 * Check if field is visible or not
	 * (depending on conditional logic)
	 *
	 * @param array $args
	 *
	 * @return bool
	 * @throws Exception
	 */
	function is_acpt_option_page_field_visible(array $args = [])
	{
		$mandatory_keys = [
			'option_page' => [
				'required' => true,
				'type' => 'string',
			],
			'box_name' => [
				'required' => true,
				'type' => 'string',
			],
			'field_name' => [
				'required' => true,
				'type' => 'string',
			],
			'parent_field_name' => [
				'required' => false,
				'type' => 'string',
			],
			'index' => [
				'required' => false,
				'type' => 'string|integer',
			],
			'block_name' => [
				'required' => false,
				'type' => 'string',
			],
			'block_index' => [
				'required' => false,
				'type' => 'string|integer',
			],
		];

		$validator = new ArgumentsArrayValidator();

		if(!$validator->validate($mandatory_keys, $args)){
			return false;
		}

		$meta_field_model = null;

		// fields nested in a Flexible
		if(isset($args['parent_field_name']) and isset($args['block_name']) and isset($args['block_index'])){
			$parent_meta_field_model = MetaRepository::getMetaFieldByName([
				'belongsTo' => MetaTypes::OPTION_PAGE,
				'find' => $args['option_page'],
				'boxName' => $args['box_name'],
				'fieldName' => $args['parent_field_name']
			]);

			if($parent_meta_field_model !== null){
				foreach ($parent_meta_field_model->getBlocks() as $block_model){
					foreach ($block_model->getFields() as $nested_field_model){
						if($nested_field_model->getName() ===  $args['field_name']){
							$meta_field_model = $nested_field_model;
						}
					}
				}
			}

		} elseif(isset($args['parent_field_name']) and isset($args['index'])){ // fields nested in a Repeater
			$parent_meta_field_model = MetaRepository::getMetaFieldByName([
				'belongsTo' => MetaTypes::OPTION_PAGE,
				'find' => $args['option_page'],
				'boxName' => $args['box_name'],
				'fieldName' => $args['parent_field_name']
			]);

			if($parent_meta_field_model !== null){
				foreach ($parent_meta_field_model->getChildren() as $child_field_model){
					if($child_field_model->getName() ===  $args['field_name']){
						$meta_field_model = $child_field_model;
					}
				}
			}

		} else {
			$meta_field_model = MetaRepository::getMetaFieldByName([
				'belongsTo' => MetaTypes::OPTION_PAGE,
				'find' => $args['option_page'],
				'boxName' => $args['box_name'],
				'fieldName' => $args['field_name']
			]);
		}

		if($meta_field_model === null){
			return false;
		}

		$index = (isset($args['index'])) ? $args['index'] : null;
		$block_name = (isset($args['block_name'])) ? $args['block_name'] : null;
		$block_index = (isset($args['block_index'])) ? $args['block_index'] : null;

		return FieldVisibilityChecker::isFieldVisible($args['option_page'], $meta_field_model, $index, $block_name, $block_index);
	}
}