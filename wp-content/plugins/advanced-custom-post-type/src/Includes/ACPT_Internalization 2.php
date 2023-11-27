<?php

namespace ACPT\Includes;

use ACPT\Core\Repository\SettingsRepository;

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://acpt.io
 * @since      1.0.0
 *
 * @package    advanced-custom-post-type
 * @subpackage advanced-custom-post-type/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    advanced-custom-post-type
 * @subpackage advanced-custom-post-type/includes
 * @author     Mauro Cassani <maurocassani1978@gmail.com>
 */
class ACPT_Internalization
{
    /**
     * @var ACPT_Loader
     */
    private $loader;

    public function __construct( ACPT_Loader $loader)
    {
        $this->loader = $loader;
    }

	/**
	 * @return string
	 * @throws \Exception
	 */
    private function getLocale()
    {
	    $language = SettingsRepository::getSingle('language');

	    if($language !== null){
		    return $language->getValue();
	    }

	    return apply_filters( 'plugin_locale', determine_locale(), ACPT_PLUGIN_NAME );
    }

	/**
	 * Run localisation
	 */
    public function run()
    {
    	load_textdomain( ACPT_PLUGIN_NAME, __DIR__ . '/../../i18n/languages/'.$this->getLocale().'.mo');;
    }
}