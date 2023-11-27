<?php

namespace ACPT\Core\Shortcodes\ACPT\DTO;

class ShortcodePayload
{
	public $box;
	public $field;
	public $belongsTo;
	public $id;
	public $find = null;
    public $width = null;
    public $height = null;
    public $target = null;
    public $dateFormat = null;
    public $elements = null;
    public $preview = false;
    public $parent = null;
    public $index = null;
    public $blockName = null;
    public $blockIndex = null;
    public $render = null;
}