<?php

namespace ACPT\Core\Models\OptionPage;

use ACPT\Core\Helper\Icon;
use ACPT\Core\Models\Abstracts\AbstractMetaWrapperModel;

/**
 * OptionPageModel
 *
 * @since      1.0.150
 * @package    advanced-custom-post-type
 * @subpackage advanced-custom-post-type/core
 * @author     Mauro Cassani <maurocassani1978@gmail.com>
 */
class OptionPageModel extends AbstractMetaWrapperModel implements \JsonSerializable
{
	/**
	 * @var string
	 */
	private $pageTitle;

	/**
	 * @var string
	 */
	private $menuTitle;

	/**
	 * @var string
	 */
	private $capability;

	/**
	 * @var string
	 */
	private $menuSlug;

	/**
	 * @var string
	 */
	private $icon;

	/**
	 * @var string
	 */
	private $description;

	/**
	 * @var int
	 */
	private $sort;

	/**
	 * @var int
	 */
	private $position;

	/**
	 * @var string
	 */
	private $parentId;

	/**
	 * @var OptionPageModel[]
	 */
	private $children;

	/**
	 * OptionPageModel constructor.
	 *
	 * @param $id
	 * @param $pageTitle
	 * @param $menuTitle
	 * @param $capability
	 * @param $menuSlug
	 * @param $icon
	 * @param $sort
	 * @param $position
	 * @param null $description
	 * @param null $parentId
	 *
	 * @throws \Exception
	 */
	public function __construct(
		$id,
		$pageTitle,
		$menuTitle,
		$capability,
		$menuSlug,
		$sort,
		$position,
		$icon = null,
		$description = null,
		$parentId = null
	) {
		parent::__construct($id);
		$this->setMenuSlug($menuSlug);
		$this->setCapability($capability);
		$this->menuTitle    = $menuTitle;
		$this->pageTitle    = $pageTitle;
		$this->icon         = $icon;
		$this->description  = $description;
		$this->sort         = $sort;
		$this->position     = $position;
		$this->parentId     = $parentId;
		$this->children     = [];
	}

	/**
	 * @param $capability
	 *
	 * @throws \Exception
	 */
	private function setCapability($capability)
	{
		$allowedCapabilities = [
			'moderate_comments',
			'manage_options',
			'manage_categories',
			'manage_links',
			'unfiltered_html',
			'edit_others_posts',
			'edit_pages',
			'edit_others_pages',
			'edit_published_pages',
			'publish_pages',
			'delete_pages',
			'delete_others_pages',
			'delete_published_pages',
			'delete_others_posts',
			'delete_private_posts',
			'edit_private_posts',
			'read_private_posts',
			'delete_private_pages',
			'edit_private_pages',
			'read_private_pages',
		];

		if(!in_array($capability, $allowedCapabilities)){
			throw new \Exception($capability . ' is not a capability allowed');
		}

		$this->capability   = $capability;
	}

	/**
	 * @param $slug
	 */
	public function setMenuSlug($slug)
	{
		$size = strlen($slug);

		if($size > 32){
			throw new \DomainException( $slug . ' is too long [32 characters max]');
		}

		preg_match_all('/[a-z0-9_-]/u', $slug, $matches);

		if(empty($matches[0]) or $size !== count($matches[0])){
			throw new \DomainException('Allowed characters: [Lowercase alphanumeric characters, dashes, and underscores]');
		}

		$this->menuSlug = sanitize_key($slug);
	}

	/**
	 * @return string
	 */
	public function getPageTitle()
	{
		return $this->pageTitle;
	}

	/**
	 * @return string
	 */
	public function getMenuTitle()
	{
		return $this->menuTitle;
	}

	/**
	 * @return string
	 */
	public function getCapability()
	{
		return $this->capability;
	}

	/**
	 * @return string
	 */
	public function getMenuSlug()
	{
		return $this->menuSlug;
	}

	/**
	 * @return string
	 */
	public function getIcon()
	{
		return $this->icon;
	}

	/**
	 * @return string
	 */
	public function renderIcon()
	{
		return Icon::render($this->icon);
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @return string
	 */
	public function getParentId()
	{
		return $this->parentId;
	}

	/**
	 * @return int
	 */
	public function getSort()
	{
		return $this->sort;
	}

	/**
	 * @return int
	 */
	public function getPosition()
	{
		return $this->position;
	}

	/**
	 * @return bool
	 */
	public function hasChildren()
	{
		return !empty($this->children);
	}

	/**
	 * @param OptionPageModel $page
	 */
	public function addChild(OptionPageModel $page)
	{
		if(!$this->existsInCollection($page->getId(), $this->children)){
			$this->children[] = $page;
		}
	}

	/**
	 * @param OptionPageModel $page
	 */
	public function removeChild(OptionPageModel $page)
	{
		$this->removeFromCollection($page->getId(), $this->children);
	}

	/**
	 * Clear all children
	 */
	public function clearChildren()
	{
		$this->children = [];
	}

	/**
	 * @return OptionPageModel[]
	 */
	public function getChildren()
	{
		return $this->children;
	}

	/**
	 * @inheritDoc
	 */
	public function arrayRepresentation()
	{
		$metaArray = $this->metaArrayRepresentation();

		return [
			'id' => $this->id,
			'parentId' => $this->parentId,
			'pageTitle' => $this->pageTitle,
			'menuTitle' => $this->menuTitle,
			'capability' => $this->capability,
			'menuSlug' => $this->menuSlug,
			'icon' => $this->renderIcon(),
			'description' => $this->description,
			'sort' => $this->sort,
			'position' => $this->position,
			'meta' => $metaArray,
			'children' => $this->children,
			'templates' => $this->templates,
		];
	}

	#[\ReturnTypeWillChange]
	public function jsonSerialize()
	{
		$metaArray = $this->metaArrayRepresentation('mini');

		return [
			'id' => $this->id,
			'parentId' => $this->parentId,
			'pageTitle' => $this->pageTitle,
			'menuTitle' => $this->menuTitle,
			'capability' => $this->capability,
			'menuSlug' => $this->menuSlug,
			'icon' => $this->renderIcon(),
			'description' => $this->description,
			'sort' => $this->sort,
			'position' => $this->position,
			'meta' => $metaArray,
			'children' => $this->children,
			'templates' => $this->templates,
		];
	}
}