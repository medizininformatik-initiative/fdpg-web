<?php

namespace ACPT\Core\Generators;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxModel;

/**
 * *************************************************
 * OptionPageMetaBoxGenerator class
 * *************************************************
 *
 * @author Mauro Cassani
 * @link https://github.com/mauretto78/
 */
class OptionPageMetaBoxGenerator extends AbstractGenerator
{
	/**
	 * @var AbstractMetaBoxModel
	 */
	private AbstractMetaBoxModel $boxModel;

	/**
	 * OptionPageMetaBoxGenerator constructor.
	 *
	 * @param AbstractMetaBoxModel $boxModel
	 */
	public function __construct(AbstractMetaBoxModel $boxModel)
	{
		$this->boxModel = $boxModel;
	}

	/**
	 * @return string
	 */
	public function render()
	{
		$boxLabel = (!empty($this->boxModel->getLabel())) ? $this->boxModel->getLabel() : $this->boxModel->getName();

		$return = '<div class="acpt-postbox postbox" id="'.$this->getIdName().'">';
		$return .= '<div class="postbox-header">';
		$return .= '<h2 class="hndle ui-sortable-handle">'.$boxLabel.'</h2>';
		$return .= '<div class="handle-actions hide-if-no-js">';
		$return .= '<button type="button" class="handlediv" aria-expanded="true">';
		$return .= '<span class="screen-reader-text">'.__('Activate/deactivate the panel', ACPT_PLUGIN_NAME).':</span>';
		$return .= '<span class="toggle-indicator acpt-toggle-indicator" data-target="'.$this->getIdName().'" aria-hidden="true"></span>';
		$return .= '</button>';
		$return .= '</div>';
		$return .= '</div>';
		$return .= '<div class="inside no-margin">';

		if(count($this->boxModel->getFields()) > 0) {
			$return .= '<div class="option-page-meta-fields-wrapper">';

			foreach ($this->boxModel->getFields() as $fieldModel){
				$fieldGenerator = new OptionPageMetaBoxFieldGenerator($fieldModel);
				$return .= $fieldGenerator->render();
			}

			$return .= '</div>';
		}

		$return .= '</div>';
		$return .= '</div>';

		return $return;
	}

	/**
	 * @return string
	 */
	protected function getIdName()
	{
		$idName = Strings::toDBFormat($this->boxModel->getName()).'_'.$this->boxModel->getId();

		return esc_html($idName);
	}
}