<?php

namespace ACPT\Integrations\Zion\Provider\Repeater;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Costants\MetaTypes;
use ACPT\Integrations\Zion\Provider\Constants\ZionConstants;
use ACPT\Integrations\Zion\Provider\Utils\FieldSettings;
use ACPT\Integrations\Zion\Provider\Utils\FieldValue;
use ZionBuilder\Options\Option;
use ZionBuilderPro\Repeater\RepeaterProvider;
use ZionBuilder\Options\Options;

class ACPTRepeaterProvider extends RepeaterProvider
{
	/**
	 * @return string
	 */
	public static function get_id()
	{
		return 'acpt_repeater';
	}

	/**
	 * @return string
	 */
	public static function get_name()
	{
		return esc_html__( 'ACPT Repeater', 'zionbuilder-pro' );
	}

	/**
	 * @param null $index
	 */
	public function the_item($index = null)
	{
		$current_item = $this->get_active_item();
		$config = isset( $this->config['config'] ) ? $this->config['config'] : [];

		if($current_item and isset($config['repeater_field'])) {
			$real_index = null === $index ? $this->get_real_index() : $index;
			// @TODO update loop index????
		}
	}

	/**
	 * @return array|void
	 * @throws \Exception
	 */
	public function perform_query()
	{
		$config = isset( $this->config['config'] ) ? $this->config['config'] : [];

		$this->query = [
			'query' => null,
			'items' => [],
		];

		if (isset( $config['repeater_field'])) {

			$fieldSettings = FieldSettings::get($config[ 'repeater_field' ]);

			if($fieldSettings === false or empty($fieldSettings)){
				return;
			}

			/** @var AbstractMetaBoxFieldModel $metaFieldModel */
			$metaFieldModel = $fieldSettings['model'];
			$belongsTo = $fieldSettings['belongsTo'];

			$rawValue = FieldValue::raw($belongsTo, $metaFieldModel);

			$this->query = [
				'query' => [],
				'items' => is_array($rawValue) ? $rawValue : [],
			];

			return;
		}
	}

	public function reset_query()
	{
//		\acf_remove_loop('active');
	}

	/**
	 * @return array|Option[]
	 * @throws \Exception
	 */
	public function get_schema()
	{
		$optionsSchema = new Options( 'zionbuilderpro/repeater_provider/acpt_repeater' );
		$optionsSchema->add_option(
			'repeater_field',
			[
				'type'        => 'select',
				'title'       => esc_html__( 'Repeater field', 'zionbuilder-pro' ),
				'placeholder' => esc_html__( 'Select repeater field', 'zionbuilder-pro' ),
				'options' => $this->getRepeaterOptionsForSelect(),
				'filterable'  => true,
				'filter_id' => 'zionbuilderpro/repeater/acpt/fields'
			]
		);

		return $optionsSchema->get_schema();
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	public function getRepeaterOptionsForSelect()
	{
		$repeaterOptions = [];

		$cptMetaBoxes = MetaRepository::get([ 'belongsTo' => MetaTypes::CUSTOM_POST_TYPE ]);
		$optionPageMetaBoxes = MetaRepository::get([ 'belongsTo' => MetaTypes::OPTION_PAGE ]);

		$repeaterOptions = array_merge($repeaterOptions, $this->getAcptRepeaterGroup($cptMetaBoxes, MetaTypes::CUSTOM_POST_TYPE));
		$repeaterOptions = array_merge($repeaterOptions, $this->getAcptRepeaterGroup($optionPageMetaBoxes, MetaTypes::OPTION_PAGE));

		return $repeaterOptions;
	}

	/**
	 * @param AbstractMetaBoxModel[] $metaBoxes
	 * @param string $belongsTo
	 * @param bool $parent
	 *
	 * @return array
	 */
	private function getAcptRepeaterGroup($metaBoxes, $belongsTo, $parent = false)
	{
		$options = [];
		$belongsToLabel = MetaTypes::label($belongsTo);

		foreach ($metaBoxes as $metaBox){

			$metaBoxOptions = array_merge([], $this->getAcptRepeaterGroupFields($metaBox->getFields(), $belongsTo, $parent));

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
	 * @param AbstractMetaBoxFieldModel[] $metaFields
	 * @param string $belongsTo
	 * @param bool $parent
	 *
	 * @return array
	 */
	private function getAcptRepeaterGroupFields($metaFields, $belongsTo, $parent = false)
	{
		$options = [];

		foreach ($metaFields as $field){

			// @TODO FLEXIBLE ?????

			if($field->getType() === AbstractMetaBoxFieldModel::REPEATER_TYPE ){
				$options = array_merge( $options, $this->getRepeaterChilds($field, $belongsTo, $parent));
			}
		}

		return $options;
	}

	/**
	 * @param AbstractMetaBoxFieldModel $field
	 * @param $belongsTo
	 * @param bool $parent
	 *
	 * @return array
	 */
	private function getRepeaterChilds(AbstractMetaBoxFieldModel $field, $belongsTo, $parent = false)
	{
		$options = [];

		if($field->hasChildren()) {
			$options = array_merge($options, $this->getAcptRepeaterGroupFields($field->getChildren(), $belongsTo,  $field->getId()));
		}

		$id = ($field->hasChildren()) ?  $belongsTo.ZionConstants::FIELD_KEY_SEPARATOR.$field->getId() :  $belongsTo.ZionConstants::FIELD_KEY_SEPARATOR.$field->getParentId().ZionConstants::FIELD_KEY_SEPARATOR.$field->getId();
		$name = ($field->hasChildren()) ? '['.$field->getMetaBox()->getName().'] - '.$field->getName() : '['.$field->getParentField()->getName().'] - '.$field->getName();

		$options[] = [
			'id'          => $id,
			'name'        => $name,
			'acpt_parent' => $parent,
		];

		return $options;
	}
}