<?php

namespace ACPT\Core\Models\CustomPostType;

use ACPT\Core\Helper\Icon;
use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaWrapperModel;
use ACPT\Core\Models\Taxonomy\TaxonomyModel;
use ACPT\Core\Models\WooCommerce\WooCommerceProductDataModel;

/**
 * CustomPostTypeModel
 *
 * @since      1.0.0
 * @package    advanced-custom-post-type
 * @subpackage advanced-custom-post-type/core
 * @author     Mauro Cassani <maurocassani1978@gmail.com>
 */
class CustomPostTypeModel extends AbstractMetaWrapperModel implements \JsonSerializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $singular;

    /**
     * @var string
     */
    private $plural;

    /**
     * @var string
     */
    private $icon;

    /**
     * @var bool
     */
    private $native;

    /**
     * @var int
     */
    private $postCount;

    /**
     * @var array
     */
    private $supports = [];

    /**
     * @var array
     */
    private $labels = [];

    /**
     * @var array
     */
    private $settings = [];

    /**
     * @var TaxonomyModel[]
     */
    private $taxonomies = [];

    /**
     * @var WooCommerceProductDataModel
     */
    private $woocommerceProductData = [];

    /**
     * @var bool
     */
    private $existsArchivePageInTheme = false;

    /**
     * @var bool
     */
    private $existsSinglePageInTheme = false;

    /**
     * CustomPostTypeModel constructor.
     *
     * @param       $id
     * @param       $name
     * @param       $singular
     * @param       $plural
     * @param       $icon
     * @param       $native
     * @param array $supports
     * @param array $labels
     * @param array $settings
     */
    public function __construct(
        $id,
        $name,
        $singular,
        $plural,
        $icon,
        $native,
        array $supports,
        array $labels,
        array $settings
    ) {
        parent::__construct($id);
        $this->setName($name);
        $this->singular = $singular;
        $this->plural   = $plural;
        $this->icon     = $icon;
        $this->native   = $native;
        $this->supports = $supports;
        $this->labels   = $labels;
        $this->settings = $settings;
    }

    /**
     * Keys are used as internal identifiers. Lowercase alphanumeric characters, dashes, and underscores are allowed.
     * https://developer.wordpress.org/reference/functions/sanitize_key/
     *
     * @param $name
     */
    private function setName($name)
    {
        $size = strlen($name);

        if($size > 20){
            throw new \DomainException($name . ' is too long [20 characters max]');
        }

        preg_match_all('/[a-z0-9_-]/u', $name, $matches);

        if(empty($matches[0]) or $size !== count($matches[0])){
            throw new \DomainException('Allowed characters: [Lowercase alphanumeric characters, dashes, and underscores]');
        }

        $this->name = sanitize_key($name);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSingular()
    {
        return $this->singular;
    }

    /**
     * @return string
     */
    public function getPlural()
    {
        return $this->plural;
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
     * @return array
     */
    public function getSupports()
    {
        return $this->supports;
    }

    /**
     * @return array
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * @return array
     */
    public function getSettings()
    {
        return $this->normalizeSettings($this->settings);
    }

    /**
     * @param array $settings
     */
    public function modifySettings(array $settings)
    {
        $this->settings = $this->normalizeSettings($settings);
    }

	/**
	 * @param array $settings
	 *
	 * @return array
	 */
    private function normalizeSettings(array $settings)
    {
    	// menu_position MUST be integer to have effect
	    if(isset($settings['menu_position']) and $settings['menu_position'] !== null){
		    $settings['menu_position'] = (int)$settings['menu_position'];
	    }

	    return $settings;
    }

    /**
     * @param int $postCount
     */
    public function setPostCount($postCount)
    {
        $this->postCount = $postCount;
    }

    /**
     * @return int
     */
    public function getPostCount()
    {
        return $this->postCount;
    }

    /**
     * @return bool
     */
    public function isNative()
    {
        return $this->native;
    }

    /**
     * @param TaxonomyModel $taxonomyModel
     */
    public function addTaxonomy(TaxonomyModel $taxonomyModel)
    {
        if(!$this->existsInCollection($taxonomyModel->getId(), $this->taxonomies)){
            $this->taxonomies[] = $taxonomyModel;
        }
    }

    /**
     * @param TaxonomyModel $taxonomyModel
     */
    public function removeTaxonomy(TaxonomyModel $taxonomyModel)
    {
        $this->removeFromCollection($taxonomyModel->getId(), $this->taxonomies);
    }

    /**
     * @return TaxonomyModel[]
     */
    public function getTaxonomies()
    {
        return $this->taxonomies;
    }

    /**
     * @return WooCommerceProductDataModel
     */
    public function getWoocommerceProductData() {
        return $this->woocommerceProductData;
    }

    /**
     * @param WooCommerceProductDataModel $woocommerceProductDataModel
     */
    public function addWoocommerceProductData( WooCommerceProductDataModel $woocommerceProductDataModel )
    {
        if(!$this->existsInCollection($woocommerceProductDataModel->getId(), $this->woocommerceProductData)){
            $this->woocommerceProductData[] = $woocommerceProductDataModel;
        }
    }

    /**
     * @return bool
     */
    public function isExistsArchivePageInTheme()
    {
        return $this->existsArchivePageInTheme;
    }

    /**
     * @param bool $existsArchivePageInTheme
     */
    public function setExistsArchivePageInTheme($existsArchivePageInTheme)
    {
        $this->existsArchivePageInTheme = $existsArchivePageInTheme;
    }

    /**
     * @return bool
     */
    public function isExistsSinglePageInTheme()
    {
        return $this->existsSinglePageInTheme;
    }

    /**
     * @param bool $existsSinglePageInTheme
     */
    public function setExistsSinglePageInTheme($existsSinglePageInTheme)
    {
        $this->existsSinglePageInTheme = $existsSinglePageInTheme;
    }

    /**
     * Checks if 'product' if from WooCommerce
     *
     * @return bool
     */
    public function isWooCommerce()
    {
        return $this->name === 'product' and in_array( 'woocommerce/woocommerce.php',  get_option( 'active_plugins' )  );
    }

    /**
     * @return array
     */
    public function arrayRepresentation()
    {
        $metaArray = $this->metaArrayRepresentation();

        $taxonomyArray = [];
        foreach ($this->taxonomies as $taxonomy){
            $taxonomyArray[] = [
                'id' => $taxonomy->getId(),
                'slug' => $taxonomy->getSlug(),
                'singular' => $taxonomy->getSingular(),
                'plural' => $taxonomy->getPlural(),
                'labels' => $taxonomy->getLabels(),
                'settings' => $taxonomy->getSettings(),
                'postCount' => $taxonomy->getPostCount(),
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'singular' => $this->singular,
            'plural' => $this->plural,
            'icon' => $this->renderIcon(),
            'postCount' => (isset($this->postCount) and null !== $this->postCount) ? $this->postCount : 0,
            'supports' => $this->supports,
            'labels' => $this->labels,
            'settings' => $this->settings,
            'meta' => $metaArray,
            'taxonomies' => $taxonomyArray,
            'templates' => $this->templates,
            'existsArchivePageInTheme' => $this->existsArchivePageInTheme,
            'existsSinglePageInTheme' => $this->existsSinglePageInTheme,
        ];
    }

	#[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        $metaArray = $this->metaArrayRepresentation('mini');

        $taxonomyArray = [];
        foreach ($this->taxonomies as $taxonomy){
            $taxonomyArray[] = [
                'id' => $taxonomy->getId(),
                'slug' => $taxonomy->getSlug(),
                'singular' => $taxonomy->getSingular(),
                'plural' => $taxonomy->getPlural(),
                'labels' => $taxonomy->getLabels(),
                'settings' => $taxonomy->getSettings(),
                'postCount' => $taxonomy->getPostCount(),
                'isNative' => $taxonomy->isNative(),
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'singular' => $this->singular,
            'plural' => $this->plural,
            'icon' => $this->renderIcon(),
            'isNative' => $this->isNative(),
            'postCount' => (isset($this->postCount) and null !== $this->postCount) ? $this->postCount : 0,
            'supports' => $this->supports,
            'labels' => $this->labels,
            'settings' => $this->settings,
            'meta' => $metaArray,
            'taxonomies' => $taxonomyArray,
            'templates' => $this->templates,
            'existsArchivePageInTheme' => $this->existsArchivePageInTheme,
            'existsSinglePageInTheme' => $this->existsSinglePageInTheme,
            'isWooCommerce' => $this->isWooCommerce(),
            'woocommerceProductData' => $this->woocommerceProductData,
        ];
    }
}