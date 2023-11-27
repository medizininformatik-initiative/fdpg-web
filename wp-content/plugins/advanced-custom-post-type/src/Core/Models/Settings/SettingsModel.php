<?php

namespace ACPT\Core\Models\Settings;

use ACPT\Core\Models\Abstracts\AbstractModel;

/**
 * SettingsModel
 *
 * @since      1.0.0
 * @package    advanced-custom-post-type
 * @subpackage advanced-custom-post-type/core
 * @author     Mauro Cassani <maurocassani1978@gmail.com>
 */
class SettingsModel extends AbstractModel implements \JsonSerializable
{
	// option keys
	const LANGUAGE_KEY = 'language';
	const RECORDS_PER_PAGE_KEY = 'records_per_page';
	const GOOGLE_MAPS_API_KEY_KEY = 'google_maps_api_key';
	const ENABLE_VISUAL_EDITOR_KEY = 'enable_visual_editor';
	const DELETE_TABLES_WHEN_DEACTIVATE_KEY = 'delete_tables_when_deactivate';
	const DELETE_POSTS_KEY = 'delete_posts';
	const DELETE_POSTMETA_KEY = 'delete_metadata';

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $value;

    /**
     * SettingsModel constructor.
     *
     * @param $id
     * @param $key
     * @param $value
     */
    public function __construct(
        $id,
        $key,
        $value
    ) {
        parent::__construct($id);
        $this->key   = $key;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

	#[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'key' => $this->getKey(),
            'value' => $this->getValue(),
        ];
    }
}