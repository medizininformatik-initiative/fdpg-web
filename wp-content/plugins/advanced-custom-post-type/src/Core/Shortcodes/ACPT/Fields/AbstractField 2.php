<?php

namespace ACPT\Core\Shortcodes\ACPT\Fields;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Core\Shortcodes\ACPT\DTO\ShortcodePayload;
use ACPT\Utils\Checker\FieldVisibilityChecker;
use ACPT\Utils\Data\Meta;

abstract class AbstractField
{
    /**
     * @var ShortcodePayload
     */
    protected $payload;

    /**
     * @var AbstractMetaBoxFieldModel|null
     */
    protected $metaBoxFieldModel;

    /**
     * AbstractField constructor.
     * @param ShortcodePayload $payload
     * @throws \Exception
     */
    public function __construct(ShortcodePayload $payload)
    {
        $this->payload = $payload;

        $this->metaBoxFieldModel = MetaRepository::getMetaFieldByName([
            'belongsTo' => $payload->belongsTo,
            'find' => ($payload->find !== null) ? $payload->find : null,
            'boxName' => $this->payload->box,
            'fieldName' => $this->payload->field,
        ]);
    }

    /**
     * @return string
     */
    protected function getKey()
    {
        if($this->payload->parent){
            return Strings::toDBFormat($this->payload->box).'_'.Strings::toDBFormat($this->payload->parent);
        }

        return Strings::toDBFormat($this->payload->box).'_'.Strings::toDBFormat($this->payload->field);
    }

    /**
     * @return bool
     */
    protected function isAChildElement()
    {
        return $this->payload->parent !== null and $this->payload->index !== null;
    }

	/**
	 * @return bool
	 */
    protected function isABlockElement()
    {
	    return $this->isAChildElement() and $this->payload->blockName !== null and $this->payload->blockIndex !== null;
    }

	/**
	 * @param $groupRawValue
	 * @param $field
	 *
	 * @return mixed|null
	 */
    protected function getBlockElementValue($groupRawValue, $field)
    {
    	if(empty($groupRawValue)){
    		return null;
	    }

	    if(isset($groupRawValue['blocks']) and
	       isset($groupRawValue['blocks'][$this->payload->blockIndex]) and
	       isset($groupRawValue['blocks'][$this->payload->blockIndex][$this->payload->blockName]) and
	       isset($groupRawValue['blocks'][$this->payload->blockIndex][$this->payload->blockName][Strings::toDBFormat($field)]) and
	       isset($groupRawValue['blocks'][$this->payload->blockIndex][$this->payload->blockName][Strings::toDBFormat($field)][$this->payload->index])
	    ){
		    return $groupRawValue['blocks'][$this->payload->blockIndex][$this->payload->blockName][Strings::toDBFormat($field)][$this->payload->index];
	    }

	    return null;
    }

    /**
     * @return array
     */
    protected function getBeforeAndAfter()
    {
        $before = null;
        $after  = null;

        if($this->metaBoxFieldModel){
            $advanced_options = $this->metaBoxFieldModel->getAdvancedOptions();

            foreach ($advanced_options as $advanced_option){
                if($advanced_option->getKey() === 'after'){
                    $after = $advanced_option->getValue();
                }

                if($advanced_option->getKey() === 'before'){
                    $before = $advanced_option->getValue();
                }
            }
        }

        return [
            'after' => $after,
            'before' => $before,
        ];
    }

    /**
     * @return bool
     */
    protected function isFieldVisible()
    {
        if($this->payload->preview){
            return true;
        }

        if($this->metaBoxFieldModel !== null and $this->payload->id !== null){
	        return FieldVisibilityChecker::isFieldVisible($this->payload->id, $this->metaBoxFieldModel);
        }

        return true;
    }

    /**
     * @param $value
     *
     * @return string
     */
    protected function addBeforeAndAfter($value)
    {
        $beforeAndAfter = $this->getBeforeAndAfter();

        return $beforeAndAfter['before'].$value.$beforeAndAfter['after'];
    }

    /**
     * @param $key
     * @param bool $single
     *
     * @return array|string|null
     */
    protected function fetchMeta($key, $single = true)
    {
    	return Meta::fetch($this->payload->id, $this->payload->belongsTo, $key, $single);
    }

    /**
     * Method for rendering the shortcode
     *
     * @return mixed
     */
    abstract public function render();
}