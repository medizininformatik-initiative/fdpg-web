<?php

namespace ACPT\Integrations\Zion\Provider\Fields;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Costants\MetaTypes;
use ACPT\Integrations\Zion\Provider\Constants\ZionConstants;
use ACPT\Utils\Wordpress\Translator;
use ZionBuilderPro\DynamicContent\BaseField;

class AcptFieldBase extends BaseField
{
	/**
	 * @inheritDoc
	 */
	public function get_category()
	{
		return self::CATEGORY_TEXT;
	}

	/**
	 * @inheritDoc
	 */
	public function get_group()
	{
		return ZionConstants::GROUP_NAME;
	}

	/**
	 * @return string
	 */
	public function get_id()
	{
		return 'acpt-field';
	}

	/**
	 * @return string
	 */
	public function get_name()
	{
		return Translator::translate( 'ACPT Field');
	}

	/**
	 * All derived classes MUST implement this method in order to register their supported types
	 * @return array
	 */
	public static function getSupportedFieldTypes()
	{
		return [];
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	public function get_options()
	{
		return [
			'field_name' => [
				'type'        => 'select',
				'title'       => esc_html__( 'Field to display', 'zionbuilder-pro' ),
				'description' => esc_html__( 'Select the desired field you want to display.', 'zionbuilder-pro' ),
				'placeholder' => esc_html__( 'Field to display', 'zionbuilder-pro' ),
				'filterable'  => true,
				'options'     => $this->getAcptFieldsOptionByType(),
				'filter_id'   => 'zionbuilderpro/dynamic_data/acpt/options',
			]
		];
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	public function getAcptFieldsOptionByType()
	{
		$options = [];

		$cptMetaBoxes = MetaRepository::get([ 'belongsTo' => MetaTypes::CUSTOM_POST_TYPE ]);
		$taxMetaBoxes = MetaRepository::get([ 'belongsTo' => MetaTypes::TAXONOMY ]);
		$optionPageMetaBoxes = MetaRepository::get([ 'belongsTo' => MetaTypes::OPTION_PAGE ]);

		$options = array_merge($options, $this->getAcptGroup($cptMetaBoxes, MetaTypes::CUSTOM_POST_TYPE));
		$options = array_merge($options, $this->getAcptGroup($taxMetaBoxes, MetaTypes::TAXONOMY));
		$options = array_merge($options, $this->getAcptGroup($optionPageMetaBoxes, MetaTypes::OPTION_PAGE));

		return $options;
	}

	/**
	 * @param AbstractMetaBoxModel[] $metaBoxes
	 * @param $belongsTo
	 *
	 * @return array
	 */
	private function getAcptGroup($metaBoxes, $belongsTo)
	{
		$options = [];
		$belongsToLabel = MetaTypes::label($belongsTo);

		foreach ($metaBoxes as $metaBox){

			$metaBoxOptions = [];

			foreach ($metaBox->getFields() as $field){
				if(in_array($field->getType(), static::getSupportedFieldTypes())){
					$metaBoxOptions[] = [
						'id'            => $belongsTo.ZionConstants::FIELD_KEY_SEPARATOR.$field->getId(),
						'name'          => '['.$field->getMetaBox()->getName().'] - '.$field->getName(),
						'is_group_item' => true,
					];
				}
			}

			if(!empty($metaBoxOptions)){
				$options[] = [
					'name'     => '['.$belongsToLabel.'] - ' . $metaBox->getName(),
					'is_label' => true,
				];

				$options = array_merge($options, $metaBoxOptions);
			}
		}

		return $options;
	}

	/**
	 * Make sure the provided type is supported
	 *
	 * @param string $type
	 *
	 * @return bool
	 */
	protected function isSupportedFieldType(string $type)
	{
		return in_array($type, $this->getSupportedFieldTypes());
	}

	/**
	 * Will load the field only if it passes the check
	 * @TODO is working for OP and TAX meta?????
	 *
	 * @return boolean
	 */
	public function can_load()
	{
		global $post;

		return ( $post ? true : false );
	}
}