<?php

namespace ACPT\Core\Generators\UserFields;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxModel;
use ACPT\Utils\Data\Sanitizer;
use ACPT\Utils\Wordpress\WPAttachment;

abstract class AbstractUserField
{
    /**
     * @var int
     */
    protected $userId;

	/**
	 * @var AbstractMetaBoxModel
	 */
	protected $metaBoxModel;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $isRequired;

    /**
     * @var int
     */
    protected $sort;

    /**
     * @var null
     */
    protected $defaultValue;

    /**
     * @var null
     */
    protected $description;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var array
     */
    protected $advancedOptions;

    /**
     * AbstractField constructor.
     *
     * @param int    $userId
     * @param AbstractMetaBoxModel $metaBoxModel
     * @param string $name
     * @param int    $sort
     * @param bool   $isRequired
     * @param null   $defaultValue
     * @param null   $description
     * @param array  $options
     * @param array  $advancedOptions
     */
    public function __construct($userId, AbstractMetaBoxModel $metaBoxModel, $name, $sort, $isRequired = false, $defaultValue = null, $description = null, $options = [], $advancedOptions = [] )
    {
        $this->userId = $userId;
        $this->metaBoxModel = $metaBoxModel;
        $this->name = $name;
        $this->sort = (int)$sort;
        $this->isRequired = $isRequired;
        $this->defaultValue = $defaultValue;
        $this->description = $description;
        $this->options = $options;
        $this->advancedOptions = $advancedOptions;
    }

    /**
     * @return string
     */
    protected function getIdName()
    {
        $idName = Strings::toDBFormat($this->metaBoxModel->getName()) . '_' . Strings::toDBFormat($this->name);

        return esc_html($idName);
    }

    /**
     * @return mixed|null
     */
    protected function getDefaultValue()
    {
        $value =  get_the_author_meta( $this->getIdName(), $this->userId );

        return ($value !== null and $value !== '' ) ? $value : $this->defaultValue;
    }

	/**
	 * @return WPAttachment[]
	 */
	protected function getAttachments()
	{
		$attachments = [];
		$id = get_the_author_meta($this->getIdName().'_id', $this->userId);
		$url = get_the_author_meta($this->getIdName(), $this->userId);

		// from id
		if(!empty($id)){
			$ids =  explode(',', $id);

			foreach ($ids as $_id){
				$attachments[] = WPAttachment::fromId($_id);
			}

			return $attachments;
		}

		// from url
		if(!empty($url)){
			if(is_array($url)){
				foreach ($url as $_url){
					$attachments[] = WPAttachment::fromUrl($_url);
				}

				return $attachments;
			}

			$attachments[] = WPAttachment::fromUrl($url);
		}

		return $attachments;
	}

    /**
     * @return string
     */
    protected function required()
    {
        return ($this->isRequired) ? 'required="required"' : '';
    }

	/**
	 * @param $key
	 *
	 * @return mixed|null
	 */
	protected function getAdvancedOption($key)
	{
		foreach ($this->advancedOptions as $advancedOption){
			if ($advancedOption->getKey() === $key and $advancedOption->getValue() !== '') {
				return $advancedOption->getValue();
			}
		}

		return null;
	}

    /**
     * Render the field in the UI
     *
     * @param      $icon
     * @param      $field
     */
    protected function renderField($icon, $field)
    {
        ?>
        <tr>
            <th>
                <div class="acpt-admin-meta-label-wrapper">
                    <div class="acpt-admin-meta-icon">
                        <span class="icon">
                            <span class="iconify" style="color: white;" data-width="24" data-height="24" data-icon="<?php echo esc_attr($icon); ?>"></span>
                        </span>
                    </div>
                    <label for="<?php echo $this->getIdName() ?>">
                        <?php
                        echo esc_html($this->displayLabel());
                        echo ($this->isRequired) ? '<span class="required">*</span>': '';
                        ?>
                    </label>
                </div>
            </th>
            <td>
                <?php echo Sanitizer::escapeField($field); ?>
                <br>
                <?php if($this->description !== null and $this->description !== ''):?>
                    <span class="description">
                        <?php echo $this->description; ?>
                    </span>
                <?php endif; ?>
            </td>
        </tr>
        <?php
    }

	/**
	 * @return string
	 */
	protected function displayLabel()
	{
		foreach ($this->advancedOptions as $advancedOption){
			if ($advancedOption->getKey() === 'label' and $advancedOption->getValue() !== '') {
				return $advancedOption->getValue();
			}
		}

		return $this->name;
	}
}