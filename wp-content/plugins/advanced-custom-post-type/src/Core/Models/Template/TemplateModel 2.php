<?php

namespace ACPT\Core\Models\Template;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\Abstracts\AbstractModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Costants\MetaTypes;

/**
 * TemplateModel
 *
 * @since      1.0.0
 * @package    advanced-custom-post-type
 * @subpackage advanced-custom-post-type/core
 * @author     Mauro Cassani <maurocassani1978@gmail.com>
 */
class TemplateModel extends AbstractModel implements \JsonSerializable
{
    /**
     * @var string
     */
    private $belongsTo;

    /**
     * @var string
     */
    private $find;

    /**
     * @var string
     */
    private $templateType;

    /**
     * @var string|null
     */
    private $metaFieldId;

    /**
     * @var string
     */
    private $json;

    /**
     * @var string
     */
    private $html;

    /**
     * @var array
     */
    private $meta = [];

    /**
     * TemplateModel constructor.
     *
     * @param string $id
     * @param string $belongsTo
     * @param string $templateType
     * @param string $json
     * @param string $html
     * @param null|string $find
     * @param array $meta
     * @param null|string $metaFieldId
     */
    public function __construct(
        $id,
        $belongsTo,
        $templateType,
        $json,
        $html,
        $find = null,
        array $meta = [],
        $metaFieldId = null
    ) {
        parent::__construct($id);
        $this->belongsTo     = $belongsTo;
        $this->templateType = $templateType;
        $this->json         = $json;
        $this->html         = $html;
        $this->meta         = $meta;
        $this->find         = $find;
        $this->metaFieldId  = $metaFieldId;
    }

    /**
     * @param string $belongsTo
     * @param string $templateType
     * @param string $json
     * @param string $html
     * @param null|string $find
     * @param array $meta
     * @param null|string $metaFieldId
     */
    public function modify(
        $belongsTo,
        $templateType,
        $json,
        $html,
        $find = null,
        array $meta = [],
        $metaFieldId = null
    )
    {
        $this->belongsTo     = $belongsTo;
        $this->templateType = $templateType;
        $this->json         = $json;
        $this->html         = $html;
        $this->meta         = $meta;
        $this->find         = $find;
        $this->metaFieldId  = $metaFieldId;
    }

    /**
     * @return string
     */
    public function getBelongsTo()
    {
        return $this->belongsTo;
    }

    /**
     * @return string
     */
    public function getFind()
    {
        return $this->find;
    }

    /**
     * @return string
     */
    public function getTemplateType()
    {
        return $this->templateType;
    }

    /**
     * @return string
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @return string
     */
    public function getDecodedHtml()
    {
        $decoded = json_decode($this->html, true);

        if($decoded === null){
            return $this->html;
        }

        return $decoded[0]['html'];
    }

    /**
     * @return string|null
     */
    public function getDecodedCss()
    {
        $decoded = json_decode($this->html, true);

        if($decoded === null){
            return null;
        }

        return  $decoded[0]['css'];
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return (!empty($this->meta)) ? $this->meta : [];
    }

    /**
     * @return string|null
     */
    public function getMetaFieldId()
    {
        return $this->metaFieldId;
    }

    /**
     * @return AbstractMetaBoxFieldModel|void|null
     * @throws \Exception
     */
    public function getMetaField()
    {
        if(isset($this->metaFieldId) and $this->metaFieldId !== null){
            return MetaRepository::getMetaField([
                'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
                'id' => $this->metaFieldId,
                'lazy' => true,
            ]);
        }

        return null;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        $link = '/template/'.$this->getBelongsTo().'/'.Strings::toSnakeCase($this->getTemplateType() . '/'.$this->getFind());

        if(isset($this->metaFieldId) and $this->metaFieldId !== null){
            $link .= '/'.$this->metaFieldId;
        }

        return $link;
    }

	#[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'belongsTo' => $this->belongsTo,
            'find' => $this->find,
            'metaFieldId' => $this->metaFieldId,
            'metaField' => $this->getMetaField(),
            'templateType' => $this->templateType,
            'json' => $this->json,
            'html' => $this->html,
            'meta' => $this->meta,
            'link' => $this->getLink()
        ];
    }
}