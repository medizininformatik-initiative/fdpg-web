<?php

namespace ACPT\Core\Shortcodes\ACPT\Fields;

use ACPT\Core\Helper\Strings;

class RadioField extends AbstractField
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
				return $this->addBeforeAndAfter($this->renderItem($data['value'], $render));
			}

			return null;
		}

		if($this->isAChildElement()){
			@$groupRawValue = $this->fetchMeta($this->getKey());
			$field = Strings::toDBFormat($this->payload->field);

			if(isset($groupRawValue[$field]) and isset($groupRawValue[$field][$this->payload->index])){
				$data = $groupRawValue[$field][$this->payload->index];

				return $this->addBeforeAndAfter($this->renderItem($data['value'], $render));
			}

			return null;
		}

		$value = $this->fetchMeta($this->getKey());

		return $this->addBeforeAndAfter($this->renderItem($value, $render));
	}

	/**
	 * @param $value
	 * @param null $render
	 *
	 * @return string|null
	 */
	private function renderItem($value, $render = null)
	{
		if($render === 'label'){
			return $this->metaBoxFieldModel->getOptionLabel($value);
		}

		return $value;
	}
}