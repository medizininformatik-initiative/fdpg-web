<?php

namespace ACPT\Core\Shortcodes\ACPT\Fields;

use ACPT\Core\Helper\Strings;

class CheckboxField extends AbstractField
{
	public function render()
	{
		if(!$this->isFieldVisible()){
			return null;
		}

		$render = $this->payload->render;

		if($this->isABlockElement()){
			@$groupRawValue = $this->fetchMeta($this->getKey());
			$field = Strings::toDBFormat($this->payload->field);
			$data = $this->getBlockElementValue($groupRawValue, $field);

			if($data !== null and isset($data['value'])){
				$list = $data['value'];

				return $this->renderList($list, $render);
			}

			return null;
		}

		if($this->isAChildElement()){
			@$groupRawValue = $this->fetchMeta($this->getKey());
			$field = Strings::toDBFormat($this->payload->field);

			if(isset($groupRawValue[$field]) and isset($groupRawValue[$field][$this->payload->index])){
				$data = $groupRawValue[$field][$this->payload->index];

				$list = $data['value'];

				return $this->renderList($list, $render);
			}

			return null;
		}

		$list = $this->fetchMeta($this->getKey());

		return $this->renderList($list, $render);
	}

	/**
	 * @param array $list
	 * @param string $render
	 *
	 * @return string
	 */
	private function renderList(array $list, $render = null)
	{
		$renderedList = [];

		foreach ($list as $item){
			$item = ($render === 'label') ? $this->metaBoxFieldModel->getOptionLabel($item) : $item;
			$renderedList[] = $this->addBeforeAndAfter($item);
		}

		return implode(',', $renderedList);
	}
}