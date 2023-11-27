<?php

namespace ACPT\Admin;

use ACPT\Core\Generators\CustomPostTypeGenerator;
use ACPT\Core\Generators\CustomPostTypeMetaBoxGenerator;
use ACPT\Core\Generators\OptionPageGenerator;
use ACPT\Core\Generators\TaxonomyMetaBoxGenerator;
use ACPT\Core\Generators\UserMetaBoxGenerator;
use ACPT\Core\Generators\WooCommerceProductDataGenerator;
use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxModel;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Models\Settings\SettingsModel;
use ACPT\Core\Repository\CustomPostTypeRepository;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Core\Repository\OptionPageRepository;
use ACPT\Core\Repository\SettingsRepository;
use ACPT\Core\Repository\TaxonomyRepository;
use ACPT\Core\Repository\WooCommerceProductDataRepository;
use ACPT\Core\Shortcodes\ACPT\OptionPageMetaShortcode;
use ACPT\Core\Shortcodes\ACPT\PostMetaShortcode;
use ACPT\Core\Shortcodes\ACPT\TaxonomyMetaShortcode;
use ACPT\Core\Shortcodes\ACPT\UserMetaShortcode;
use ACPT\Core\Shortcodes\Loop\BlockLoopShortcode;
use ACPT\Core\Shortcodes\Loop\CustomPostTypeLoopShortcode;
use ACPT\Core\Shortcodes\Loop\RepeaterFieldShortcode;
use ACPT\Core\Shortcodes\Loop\TaxonomyLoopShortcode;
use ACPT\Core\Shortcodes\WP\AuthorShortcode;
use ACPT\Core\Shortcodes\WP\ContentShortcode;
use ACPT\Core\Shortcodes\WP\DateShortcode;
use ACPT\Core\Shortcodes\WP\ExcerptShortcode;
use ACPT\Core\Shortcodes\WP\PermalinkShortcode;
use ACPT\Core\Shortcodes\WP\TermDescriptionShortcode;
use ACPT\Core\Shortcodes\WP\TermNameShortcode;
use ACPT\Core\Shortcodes\WP\ThumbnailShortcode;
use ACPT\Core\Shortcodes\WP\TitleShortcode;
use ACPT\Costants\MetaTypes;
use ACPT\Includes\ACPT_Loader;
use ACPT\Integrations\AbstractIntegration;
use ACPT\Integrations\Breakdance\ACPT_Breakdance;
use ACPT\Integrations\Bricks\ACPT_Bricks;
use ACPT\Integrations\Divi\ACPT_Divi;
use ACPT\Integrations\Elementor\ACPT_Elementor;
use ACPT\Integrations\Oxygen\ACPT_Oxygen;
use ACPT\Integrations\WPAllExport\ACPT_WPAllExport;
use ACPT\Integrations\WPAllImport\ACPT_WPAllImport;
use ACPT\Integrations\WPGraphQL\ACPT_WPGraphQL;
use ACPT\Integrations\Zion\ACPT_Zion;
use ACPT\Utils\Wordpress\Nonce;
use ACPT\Utils\Wordpress\Translator;
use ACPT\Utils\Wordpress\WPUtils;

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @package    advanced-custom-post-type
 * @subpackage advanced-custom-post-type/admin
 * @author     Mauro Cassani <maurocassani1978@gmail.com>
 */
class ACPT_Admin
{
    /**
     * @var ACPT_Loader
     */
    private $loader;

    /**
     * @var ACPT_Ajax
     */
    private $ajax;

    /**
     * @var array
     */
    private $pages = [];

    /**
     * @var array
     */
    private $ajaxActions = [];

    /**
     * @var array
     */
    private $staticCssAssets = [];

    /**
     * @var array
     */
    private $staticJsAssets = [];

	/**
	 * @var string
	 */
    private $googleMapsApiKey;

	/**
	 * ACPT_Admin constructor.
	 *
	 * @param ACPT_Loader $loader
	 * @param ACPT_Ajax $ajax
	 *
	 * @throws \Exception
	 */
    public function __construct( ACPT_Loader $loader, ACPT_Ajax $ajax)
    {
        $this->ajax = $ajax;
        $this->loader = $loader;
        $this->setGoogleMapsApiKey();
        $this->setStaticCssAssets();
        $this->setStaticJsAssets();
        $this->setAjaxActions();
        $this->setPages();
    }

	/**
	 * @throws \Exception
	 */
    private function setGoogleMapsApiKey()
    {
	    $googleMapKey = SettingsRepository::getSingle(SettingsModel::GOOGLE_MAPS_API_KEY_KEY);
	    $this->googleMapsApiKey = ($googleMapKey !== null) ? $googleMapKey->getValue() : null;
    }

    /**
     * Define static CSS assets
     */
    private function setStaticCssAssets()
    {
        $this->staticCssAssets = [
            'admin_selectize_css' => plugin_dir_url( dirname( __FILE__ ) ) . '../assets/static/css/selectize/selectize.default.min.css',
            'admin_css' => plugin_dir_url( dirname( __FILE__ ) ) . '../assets/static/css/admin.css',
            'block_css' => plugin_dir_url( dirname( __FILE__ ) ) . '../assets/build/block.min.css',
        ];
    }

    /**
     * Define static JS assets
     */
    private function setStaticJsAssets()
    {
    	$jsAssets = [
		    'admin_selectize_js' => [
			    'path' => plugin_dir_url( dirname( __FILE__ ) ) . '../assets/static/js/selectize/selectize.min.js',
			    'dep'  => ['jquery'],
		    ],
		    'admin_js' => [
			    'path' => plugin_dir_url( dirname( __FILE__ ) ) . '../assets/static/js/admin.js',
			    'dep'  => ['jquery'],
		    ],
		    'block_js' => [
			    'path' => plugin_dir_url( dirname( __FILE__ ) ) . '../assets/build/block.min.js',
			    'dep'  => ['wp-blocks', 'wp-element'],
		    ],
	    ];

    	if(!empty($this->googleMapsApiKey)){
		    $jsAssets['admin_google_maps_js'] = [
			    'path' => plugin_dir_url( dirname( __FILE__ ) ) . '../assets/static/js/google-maps.js',
			    'dep'  => ['jquery'],
		    ];
	    }

        $this->staticJsAssets = $jsAssets;
    }

    /**
     * Define ajax actions
     */
    private function setAjaxActions()
    {
        $this->ajaxActions = [
            'wp_ajax_assocPostTypeToTaxonomyAction' => 'assocPostTypeToTaxonomyAction',
            'wp_ajax_assocTaxonomyToPostTypeAction' => 'assocTaxonomyToPostTypeAction',
            'wp_ajax_checkPostTypeNameAction' => 'checkPostTypeNameAction',
            'wp_ajax_checkTaxonomySlugAction' => 'checkTaxonomySlugAction',
            'wp_ajax_copyMetaBoxAction' => 'copyMetaBoxAction',
            'wp_ajax_copyMetaBlockAction' => 'copyMetaBlockAction',
            'wp_ajax_copyMetaFieldAction' => 'copyMetaFieldAction',
            'wp_ajax_deactivateLicenseAction' => 'deactivateLicenseAction',
            'wp_ajax_deleteApiKeyAction' => 'deleteApiKeyAction',
            'wp_ajax_deleteCustomPostTypeAction' => 'deleteCustomPostTypeAction',
            'wp_ajax_deleteMetaAction' => 'deleteMetaAction',
            'wp_ajax_deleteOptionPagesAction' => 'deleteOptionPagesAction',
            'wp_ajax_deleteTemplateAction' => 'deleteTemplateAction',
            'wp_ajax_deleteTaxonomyAction' => 'deleteTaxonomyAction',
            'wp_ajax_deleteWooCommerceProductDataAction' => 'deleteWooCommerceProductDataAction',
            'wp_ajax_deleteWooCommerceProductDataFieldsAction' => 'deleteWooCommerceProductDataFieldsAction',
            'wp_ajax_deleteUserMetaAction' => 'deleteUserMetaAction',
            'wp_ajax_doShortcodeAction' => 'doShortcodeAction',
            'wp_ajax_exportFileAction' => 'exportFileAction',
            'wp_ajax_fetchApiKeysAction' => 'fetchApiKeysAction',
            'wp_ajax_fetchApiKeysCountAction' => 'fetchApiKeysCountAction',
            'wp_ajax_fetchFindFromBelongsToAction' => 'fetchFindFromBelongsToAction',
            'wp_ajax_fetchElementsAction' => 'fetchElementsAction',
            'wp_ajax_fetchBoxesAction' => 'fetchBoxesAction',
            'wp_ajax_fetchFieldsAction' => 'fetchFieldsAction',
            'wp_ajax_fetchPostTypeTaxonomiesAction' => 'fetchPostTypeTaxonomiesAction',
            'wp_ajax_fetchMetaFieldsFromBelongsToAction' => 'fetchMetaFieldsFromBelongsToAction',
            'wp_ajax_fetchPostTypePostsAction' => 'fetchPostTypePostsAction',
            'wp_ajax_fetchMetaAction' => 'fetchMetaAction',
            'wp_ajax_fetchTemplateAction' => 'fetchTemplateAction',
            'wp_ajax_fetchCustomPostTypesAction' => 'fetchCustomPostTypesAction',
            'wp_ajax_fetchCustomPostTypesCountAction' => 'fetchCustomPostTypesCountAction',
            'wp_ajax_fetchMetaFieldAction' => 'fetchMetaFieldAction',
            'wp_ajax_fetchMetaFieldRelationshipAction' => 'fetchMetaFieldRelationshipAction',
            'wp_ajax_fetchHeadersAndFootersAction' => 'fetchHeadersAndFootersAction',
            'wp_ajax_checkLicensePeriodicallyAction' => 'checkLicensePeriodicallyAction',
            'wp_ajax_exportCodeAction' => 'exportCodeAction',
            'wp_ajax_languagesAction' => 'languagesAction',
            'wp_ajax_fetchLicenseAction' => 'fetchLicenseAction',
            'wp_ajax_fetchOptionPageAction' => 'fetchOptionPageAction',
            'wp_ajax_fetchOptionPagesAction' => 'fetchOptionPagesAction',
            'wp_ajax_fetchOptionPagesCountAction' => 'fetchOptionPagesCountAction',
            'wp_ajax_fetchPostDataAction' => 'fetchPostDataAction',
            'wp_ajax_fetchPostsAction' => 'fetchPostsAction',
            'wp_ajax_fetchPreviewLinkAction' => 'fetchPreviewLinkAction',
            'wp_ajax_fetchSettingsAction' => 'fetchSettingsAction',
            'wp_ajax_fetchSidebarsAction' => 'fetchSidebarsAction',
            'wp_ajax_fetchTaxonomiesAction' => 'fetchTaxonomiesAction',
            'wp_ajax_fetchTaxonomiesCountAction' => 'fetchTaxonomiesCountAction',
            'wp_ajax_fetchTemplatesAction' => 'fetchTemplatesAction',
            'wp_ajax_fetchTemplatesCountAction' => 'fetchTemplatesCountAction',
            'wp_ajax_fetchTermsAction' => 'fetchTermsAction',
            'wp_ajax_fetchWooCommerceProductDataAction' => 'fetchWooCommerceProductDataAction',
            'wp_ajax_fetchWooCommerceProductDataFieldsAction' => 'fetchWooCommerceProductDataFieldsAction',
            'wp_ajax_fetchUserMetaAction' => 'fetchUserMetaAction',
            'wp_ajax_generateApiKeyAction' => 'generateApiKeyAction',
            'wp_ajax_generateGroupedFieldsAction' => 'generateGroupedFieldsAction',
            'wp_ajax_generateFlexibleBlockAction' => 'generateFlexibleBlockAction',
            'wp_ajax_generateFlexibleGroupedFieldsAction' => 'generateFlexibleGroupedFieldsAction',
            'wp_ajax_importFileAction' => 'importFileAction',
            'wp_ajax_resetCustomPostTypesAction' => 'resetCustomPostTypesAction',
            'wp_ajax_resetTaxonomiesAction' => 'resetTaxonomiesAction',
            'wp_ajax_resetWooCommerceProductDataAction' => 'resetWooCommerceProductDataAction',
            'wp_ajax_saveCustomPostTypeAction' => 'saveCustomPostTypeAction',
            'wp_ajax_saveTemplateAction' => 'saveTemplateAction',
            'wp_ajax_saveMetaAction' => 'saveMetaAction',
            'wp_ajax_saveOptionPagesAction' => 'saveOptionPagesAction',
            'wp_ajax_saveSettingsAction' => 'saveSettingsAction',
            'wp_ajax_saveTaxonomyAction' => 'saveTaxonomyAction',
            'wp_ajax_saveWooCommerceProductDataAction' => 'saveWooCommerceProductDataAction',
            'wp_ajax_saveWooCommerceProductDataFieldsAction' => 'saveWooCommerceProductDataFieldsAction',
            'wp_ajax_saveUserMetaAction' => 'saveUserMetaAction',
            'wp_ajax_syncPostsAction' => 'syncPostsAction',
            'wp_ajax_sluggifyAction' => 'sluggifyAction',
            'wp_ajax_isWPGraphQLActiveAction' => 'isWPGraphQLActiveAction',
            'wp_ajax_isOxygenBuilderActiveAction' => 'isOxygenBuilderActiveAction',
            'wp_ajax_isBBThemeBuilderActiveAction' => 'isBBThemeBuilderActiveAction',
        ];
    }

	/**
	 * Define admin pages
	 *
	 * @throws \Exception
	 */
    private function setPages()
    {
        if(ACPT_License_Manager::isLicenseValid()){
            $pages = [
                [
                    'pageTitle' => 'Advanced Custom Post Types',
                    'menuTitle' => 'ACPT',
                    'capability' => 'read',
                    'menuSlug' => ACPT_PLUGIN_NAME,
                    'template' => 'app',
                    'iconUrl' => plugin_dir_url( dirname( __FILE__ ) ) . '../assets/static/img/advanced-custom-post-type-icon.svg',
                    'position' => 50,
                    'assets' => [
                            'css' => [
                                    plugin_dir_url( dirname( __FILE__ ) ) . '../assets/build/app.min.css'
                            ],
                            'react' => [
                                    plugin_dir_url( dirname( __FILE__ ) ) . '../assets/build/app.min.js'
                            ],
                    ],
                ],
	            [
		            'parentSlug' => ACPT_PLUGIN_NAME,
		            'pageTitle' => translate('Custom Post Types', ACPT_PLUGIN_NAME),
		            'menuTitle' => translate('Custom Post Types', ACPT_PLUGIN_NAME),
		            'capability' => 'manage_options',
		            'menuSlug' => ACPT_PLUGIN_NAME,
		            'template' => 'app',
		            'position' => 52,
		            'assets' => [
			            'css' => [
				            plugin_dir_url( dirname( __FILE__ ) ) . '../assets/build/app.min.css'
			            ],
			            'react' => [
				            plugin_dir_url( dirname( __FILE__ ) ) . '../assets/build/app.min.js'
			            ],
		            ],
	            ],
                [
                        'parentSlug' => ACPT_PLUGIN_NAME,
                        'pageTitle' => translate('Taxonomies', ACPT_PLUGIN_NAME),
                        'menuTitle' => translate('Taxonomies', ACPT_PLUGIN_NAME),
                        'capability' => 'manage_options',
                        'menuSlug' => ACPT_PLUGIN_NAME . '#/taxonomies',
                        'template' => 'app',
                        'position' => 52,
                        'assets' => [
                                'css' => [
                                        plugin_dir_url( dirname( __FILE__ ) ) . '../assets/build/app.min.css'
                                ],
                                'react' => [
                                        plugin_dir_url( dirname( __FILE__ ) ) . '../assets/build/app.min.js'
                                ],
                        ],
                ],
                [
                        'parentSlug' => ACPT_PLUGIN_NAME,
                        'pageTitle' => translate('User meta', ACPT_PLUGIN_NAME),
                        'menuTitle' => translate('User meta', ACPT_PLUGIN_NAME),
                        'capability' => 'manage_options',
                        'menuSlug' => ACPT_PLUGIN_NAME . '#/user-meta',
                        'template' => 'app',
                        'position' => 53,
                        'assets' => [
                                'css' => [
                                        plugin_dir_url( dirname( __FILE__ ) ) . '../assets/build/app.min.css'
                                ],
                                'react' => [
                                        plugin_dir_url( dirname( __FILE__ ) ) . '../assets/build/app.min.js'
                                ],
                        ],
                ],
	            [
		            'parentSlug' => ACPT_PLUGIN_NAME,
		            'pageTitle' => translate('Option pages', ACPT_PLUGIN_NAME),
		            'menuTitle' => translate('Option pages', ACPT_PLUGIN_NAME),
		            'capability' => 'manage_options',
		            'menuSlug' => ACPT_PLUGIN_NAME . '#/option-pages',
		            'template' => 'app',
		            'position' => 54,
		            'assets' => [
			            'css' => [
				            plugin_dir_url( dirname( __FILE__ ) ) . '../assets/build/app.min.css'
			            ],
			            'react' => [
				            plugin_dir_url( dirname( __FILE__ ) ) . '../assets/build/app.min.js'
			            ],
		            ],
	            ],
            ];

            if($this->isEnabledVisualEditor()){
            	$pages[] = [
		            'parentSlug' => ACPT_PLUGIN_NAME,
		            'pageTitle' => translate('Templates', ACPT_PLUGIN_NAME),
		            'menuTitle' => translate('Templates', ACPT_PLUGIN_NAME),
		            'capability' => 'manage_options',
		            'menuSlug' => ACPT_PLUGIN_NAME . '#/templates',
		            'template' => 'app',
		            'position' => 55,
		            'assets' => [
			            'css' => [
				            plugin_dir_url( dirname( __FILE__ ) ) . '../assets/build/app.min.css'
			            ],
			            'react' => [
				            plugin_dir_url( dirname( __FILE__ ) ) . '../assets/build/app.min.js'
			            ],
		            ],
	            ];
            }

            $pages[] = [
		        'parentSlug' => ACPT_PLUGIN_NAME,
		        'pageTitle' => translate('Tools', ACPT_PLUGIN_NAME),
		        'menuTitle' => translate('Tools', ACPT_PLUGIN_NAME),
		        'capability' => 'manage_options',
		        'menuSlug' => ACPT_PLUGIN_NAME . '#/tools',
		        'template' => 'app',
		        'position' => 56,
		        'assets' => [
			        'css' => [
				        plugin_dir_url( dirname( __FILE__ ) ) . '../assets/build/app.min.css'
			        ],
			        'react' => [
				        plugin_dir_url( dirname( __FILE__ ) ) . '../assets/build/app.min.js'
			        ],
		        ],
	        ];

	        $pages[] = [
		        'parentSlug' => ACPT_PLUGIN_NAME,
		        'pageTitle' => translate('Settings', ACPT_PLUGIN_NAME),
		        'menuTitle' => translate('Settings', ACPT_PLUGIN_NAME),
		        'capability' => 'manage_options',
		        'menuSlug' => ACPT_PLUGIN_NAME . '#/settings',
		        'template' => 'app',
		        'position' => 59,
		        'assets' => [
			        'css' => [
				        plugin_dir_url( dirname( __FILE__ ) ) . '../assets/build/app.min.css'
			        ],
			        'react' => [
				        plugin_dir_url( dirname( __FILE__ ) ) . '../assets/build/app.min.js'
			        ],
		        ],
	        ];

            $pages[] = [
	            'parentSlug' => ACPT_PLUGIN_NAME,
	            'pageTitle' => translate('License', ACPT_PLUGIN_NAME),
	            'menuTitle' => translate('License', ACPT_PLUGIN_NAME),
	            'capability' => 'manage_options',
	            'menuSlug' => ACPT_PLUGIN_NAME . '#/license',
	            'template' => 'app',
	            'position' => 60,
	            'assets' => [
		            'css' => [
			            plugin_dir_url( dirname( __FILE__ ) ) . '../assets/build/app.min.css'
		            ],
		            'react' => [
			            plugin_dir_url( dirname( __FILE__ ) ) . '../assets/build/app.min.js'
		            ],
	            ],
            ];

        } else {
            $pages = [
                    [
                        'pageTitle' => 'Advanced Custom Post Types',
                        'menuTitle' => 'ACPT',
                        'capability' => 'manage_options',
                        'menuSlug' => ACPT_PLUGIN_NAME,
                        'template' => 'activate_license',
                        'iconUrl' => plugin_dir_url( dirname( __FILE__ ) ) . '../assets/static/img/advanced-custom-post-type-icon.svg',
                        'position' => 50,
                        'assets' => [
                            'css' => [
                                plugin_dir_url( dirname( __FILE__ ) ) . '../assets/build/app.min.css'
                            ],
                        ],
                    ],
            ];
        }

        $this->pages = $pages;
    }

	/**
	 * @return bool
	 * @throws \Exception
	 */
    private function isEnabledVisualEditor()
    {
	    $enableVisualEditorKey = SettingsRepository::getSingle(SettingsModel::ENABLE_VISUAL_EDITOR_KEY);
	    $enableVisualEditor = ($enableVisualEditorKey !== null) ? $enableVisualEditorKey->getValue() : null;

	    $isOxygenActive = is_plugin_active( 'oxygen/functions.php' );
	    $isBBThemeActive = is_plugin_active( 'bb-theme-builder/bb-theme-builder.php' );

	    return (
	        $enableVisualEditor == 1 and
	        $isOxygenActive === false and
	        $isBBThemeActive === false
	    );
    }

    /**
     * Add pages to to admin panel
     */
    public function addPages()
    {
        foreach ($this->pages as $page){
            $this->addPage(
                $page['pageTitle'],
                $page['menuTitle'],
                $page['capability'],
                $page['menuSlug'],
                $page['template'],
                (isset($page['iconUrl'])) ? $page['iconUrl'] : null,
                (isset($page['position'])) ? $page['position'] : null,
                (isset($page['parentSlug'])) ? $page['parentSlug'] : null
            );
        }
    }

    /**
     * Add a single page to admin panel
     *
     * @param string $pageTitle
     * @param string $menuTitle
     * @param string $capability
     * @param string $menuSlug
     * @param string $template
     * @param string $iconUrl
     * @param null   $position
     * @param null   $parentSlug
     */
    private function addPage($pageTitle, $menuTitle, $capability, $menuSlug, $template, $iconUrl = '', $position = null, $parentSlug = null)
    {
        if(isset($parentSlug)){
            add_submenu_page(
                $parentSlug,
                $pageTitle,
                $menuTitle,
                $capability,
                $menuSlug,
                function () use($template) {
                    require_once plugin_dir_path( dirname( __FILE__ ) ) . '../admin/templates/'.$template.'.php';
                },
                $position
            );
        } else {
            add_menu_page(
                $pageTitle,
                $menuTitle,
                $capability,
                $menuSlug,
                function () use($template) {
                    require_once plugin_dir_path( dirname( __FILE__ ) ) . '../admin/templates/'.$template.'.php';
                },
                $iconUrl,
                $position
            );
        }
    }

    /**
     * Enqueue admin assets
     */
    public function enqueueAssets()
    {
        global $pagenow;

        // Global assets
        foreach ($this->pages as $page){
            if(isset($_GET['page']) and $page['menuSlug'] === $_GET['page'] and isset($page['assets'])){
                $pageAssets = $page['assets'];
                foreach ($pageAssets as $key => $assets){
                    // css
                    if($key === 'css'){
                        foreach ($assets as $asset){
                            wp_enqueue_style( dirname( __FILE__ ).'__'.$key, $asset, [], ACPT_PLUGIN_VERSION, 'all');
                        }
                        // js
                    } elseif($key === 'js'){
                        foreach ($assets as $asset){
                            wp_enqueue_script( dirname( __FILE__ ).'__'.$key, $asset, ['jquery'], ACPT_PLUGIN_VERSION, true);
                        }
                        // react
                    } elseif($key === 'react'){
                        foreach ($assets as $asset){
                            wp_enqueue_script( dirname( __FILE__ ).'__'.$key, $asset, ['wp-element'], ACPT_PLUGIN_VERSION, true);
                        }
                    }
                }
            }
        }

        // Quick-edit assets
        if($pagenow === 'edit.php'){
	        wp_enqueue_script( dirname( __FILE__ ).'__quick_edit_js', plugin_dir_url( dirname( __FILE__ ) ) . '../assets/static/js/quick_edit.js', ['jquery'], ACPT_PLUGIN_VERSION, true);
        }

        // Assets for create/edit post profile/user meta
        if(
            $pagenow === 'post-new.php' or
            $pagenow === 'post.php' or
            $pagenow === 'profile.php' or
            $pagenow === 'user-edit.php' or
            $pagenow === 'edit-tags.php' or
            $pagenow === 'term.php' or
            $pagenow === 'admin.php'
        ) {

            // other static assets here
            foreach ($this->staticCssAssets as $key => $asset){
                wp_enqueue_style( dirname( __FILE__ ).'__'.$key, $asset, [], ACPT_PLUGIN_VERSION, 'all');
            }

            foreach ($this->staticJsAssets as $key => $asset){
                wp_enqueue_script( dirname( __FILE__ ).'__'.$key, $asset['path'], isset($asset['dep']) ? $asset['dep'] : [], ACPT_PLUGIN_VERSION, true);
            }

            //
            // =================================
            // WP DEFAULT UTILITIES
            // =================================
            //

            // color picker
            wp_enqueue_style( 'wp-color-picker' );
            //wp_enqueue_script( 'my-script-handle', plugins_url( __FILE__, 'my-script.js' ), array( 'wp-color-picker' ), false, true );
            wp_enqueue_script( 'my-script-handle', plugin_dir_url( dirname( __FILE__ ) ) . '../assets/static/js/wp-color-picker.js', ['wp-color-picker'], false, true );

            // codemirror
            $cm_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'text/html'));
            wp_localize_script('jquery', 'cm_settings', $cm_settings);
            wp_enqueue_script('wp-theme-plugin-editor');
            wp_enqueue_style('wp-codemirror');

            // media
	        wp_enqueue_media();

            //
            // =================================
            // ICONIFY
            // =================================
            //
            wp_register_script('iconify',  plugin_dir_url( dirname( __FILE__ ) ) . '../assets/static/js/iconify/iconify.min.js' );
            wp_enqueue_script('iconify');

            //
            // =================================
            // GOOGLE MAPS
            // =================================
            //

	        if(!empty($this->googleMapsApiKey)){
		        wp_register_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key='.$this->googleMapsApiKey.'&libraries=places&callback=initAutocomplete', false, '3', true);
		        wp_enqueue_script('google-maps');
	        }
        }
    }

    /**
     * Add filters here
     */
    public function addFilters()
    {
        add_filter('script_loader_tag', [$this, 'addAsyncDeferAttribute'], 10, 2);
        add_filter('block_categories_all', [$this, 'addGutenbergBlocks'], 10, 2 );
    }

    /**
     * Register custom Gutember
     *
     * @param $block_categories
     * @param $block_editor_context
     *
     * @return array
     */
    public function addGutenbergBlocks($block_categories, $block_editor_context)
    {
        $category_slugs = wp_list_pluck( $block_categories, 'slug' );

        return in_array( 'advanced-custom-post-type-blocks', $category_slugs, true ) ? $block_categories : array_merge(
                $block_categories,
                [
                    [
                        'slug'  => 'advanced-custom-post-type-blocks',
                        'title' => __( 'ACPT Blocks', 'advanced-custom-post-type-blocks' ),
                        'icon'  => null,
                    ]
                ]
        );
    }

    /**
     * Async script load
     *
     * @param $tag
     * @param $handle
     *
     * @return string|string[]
     */
    public function addAsyncDeferAttribute($tag, $handle)
    {
        if ( 'google-maps' !== $handle ){
            return $tag;
        }

        return str_replace( ' src', ' async defer src', $tag );
    }

    /**
     * Add shortcodes
     */
    private function addShortcodes()
    {
        add_shortcode('acpt', [new PostMetaShortcode(), 'render']);
        add_shortcode('acpt_user', [new UserMetaShortcode(), 'render']);
        add_shortcode('acpt_tax', [new TaxonomyMetaShortcode(), 'render']);
        add_shortcode('acpt_option', [new OptionPageMetaShortcode(), 'render']);
        add_shortcode('acpt_loop', [new CustomPostTypeLoopShortcode(), 'render']);
        add_shortcode('acpt_tax_loop', [new TaxonomyLoopShortcode(), 'render']);
        add_shortcode('acpt_field_loop', [new RepeaterFieldShortcode(), 'render']);
        add_shortcode('acpt_block_loop', [new BlockLoopShortcode(), 'render']);
        add_shortcode('wp_term_description', [new TermDescriptionShortcode(), 'render']);
        add_shortcode('wp_term_name', [new TermNameShortcode(), 'render']);
        add_shortcode('wp_title', [new TitleShortcode(), 'render']);
        add_shortcode('wp_content', [new ContentShortcode(), 'render']);
        add_shortcode('wp_excerpt', [new ExcerptShortcode(), 'render']);
        add_shortcode('wp_author', [new AuthorShortcode(), 'render']);
        add_shortcode('wp_date', [new DateShortcode(), 'render']);
        add_shortcode('wp_date', [new DateShortcode(), 'render']);
        add_shortcode('wp_permalink', [new PermalinkShortcode(), 'render']);
        add_shortcode('wp_thumbnail', [new ThumbnailShortcode(), 'render']);
    }

    /**
     * @throws \Exception
     */
    private function registerCustomPostTypesAndTaxonomies()
    {
        // add meta box/taxonomies for CPT
        foreach (CustomPostTypeRepository::get() as $postTypeModel){

			// register CPTs and Taxonomy here
            $customPostType = new CustomPostTypeGenerator(
                $postTypeModel->getName(),
                $postTypeModel->isNative(),
                array_merge(
                    [
                        'supports' => $postTypeModel->getSupports(),
                        'label' => $postTypeModel->getPlural(),
                        'labels' => $postTypeModel->getLabels(),
                        "menu_icon" => $postTypeModel->renderIcon(),
                    ],
                    $postTypeModel->getSettings()
                )
            );

            // add meta boxes
	        $customPostTypeMetaBoxGenerator = new CustomPostTypeMetaBoxGenerator();

            foreach ($postTypeModel->getMetaBoxes() as $metaBoxModel){
                $this->generateMetaBoxes($postTypeModel->getName(), $metaBoxModel, $customPostTypeMetaBoxGenerator);
            }
        }
    }

    /**
     * @param string               $postTypeName
     * @param AbstractMetaBoxModel $metaBoxModel
     * @param CustomPostTypeMetaBoxGenerator     $metaBoxGenerator
     */
    private function generateMetaBoxes($postTypeName, AbstractMetaBoxModel $metaBoxModel, CustomPostTypeMetaBoxGenerator $metaBoxGenerator)
    {
        $metaFields = [];

        foreach ($metaBoxModel->getFields() as $fieldModel){
            $metaFields[] = $this->generateMetaBoxFieldArray($fieldModel);
        }

        $metaBoxGenerator->addMetaBox($metaBoxModel, $postTypeName, $metaFields);
    }

    /**
     * @param CustomPostTypeMetaBoxFieldModel $fieldModel
     *
     * @return array
     */
    protected function generateMetaBoxFieldArray(CustomPostTypeMetaBoxFieldModel $fieldModel)
    {
        $options = [];

        foreach ($fieldModel->getOptions() as $optionModel){
            $options[] = [
                    'label' => $optionModel->getLabel(),
                    'value' => $optionModel->getValue(),
            ];
        }

        $relations = [];

        foreach ($fieldModel->getRelations() as $relationshipModel){

            $inversedBy = null;

            if($relationshipModel->isBidirectional() and $relationshipModel->getInversedBy() !== null){
                $inversedBy = [
                        'id' => $relationshipModel->getInversedBy()->getId(),
                        'box' => $relationshipModel->getInversedBy()->getMetaBox()->getName(),
                        'field' => $relationshipModel->getInversedBy()->getName(),
                ];
            }

            $relations[] = [
                'relationship' => $relationshipModel->getRelationship(),
                'related_entity' => [
                    'type' =>  $relationshipModel->getRelatedEntity()->getType(),
                    'value' => $relationshipModel->getRelatedEntity()->getValue()->getName()
                ],
                'inversedBy' => $inversedBy
            ];
        }

        $children = [];

        if($fieldModel->hasChildren()){
            foreach ($fieldModel->getChildren() as $childFieldModel){
                $children[] = $this->generateMetaBoxFieldArray($childFieldModel);
            }
        }

        $advancedOptions = [];

        foreach ($fieldModel->getAdvancedOptions() as $advancedOptionModel){
            $advancedOptions[] = [
                'key' => $advancedOptionModel->getKey(),
                'value' => $advancedOptionModel->getValue(),
            ];
        }

	    $blocks = [];

	    foreach ($fieldModel->getBlocks() as $blockModel){

	    	$nestedFields = [];

	    	foreach ($blockModel->getFields() as $nestedFieldModel){
			    $nestedFields[] = $this->generateMetaBoxFieldArray($nestedFieldModel);
		    }

		    $blocks[] = [
			    'id' => $blockModel->getId(),
		    	'name' => $blockModel->getName(),
		    	'label' => $blockModel->getLabel(),
			    'fields' => $nestedFields
		    ];
	    }

        return [
            'id' => $fieldModel->getId(),
            'type' => $fieldModel->getType(),
            'name' => $fieldModel->getName(),
            'defaultValue' => $fieldModel->getDefaultValue(),
            'description' => $fieldModel->getDescription(),
            'isRequired' => $fieldModel->isRequired(),
            'isShowInArchive' => $fieldModel->isShowInArchive(),
            'sort' => $fieldModel->getSort(),
            'options' => $options,
            'relations' => $relations,
            'children' => $children,
            'advancedOptions' => $advancedOptions,
            'blocks' => $blocks,
        ];
    }

    /**
     * @throws \Exception
     */
    private function registerTaxonomyMeta()
    {
        $taxonomyMetaBoxGenerator = new TaxonomyMetaBoxGenerator();
        $taxonomyMetaBoxGenerator->generate();
    }

    /**
     * @throws \Exception
     */
    public function addWooCommerceProductData()
    {
        $WooCommerceProductData = WooCommerceProductDataRepository::get([]);

        if(!empty($WooCommerceProductData)){
            $wooCommerceProductDataGenerator = new WooCommerceProductDataGenerator($WooCommerceProductData);
            $wooCommerceProductDataGenerator->generate();
        }
    }

    /**
     * Add CPT columns to the admin panel
     * (including quick edit and filter capabilities)
     *
     * @throws \Exception
     */
    private function addColumnsToAdminPanel()
    {
        foreach (CustomPostTypeRepository::get() as $postTypeModel){

            $manageEditAction = 'manage_edit-'.$postTypeModel->getName().'_columns';
            $manageEditSortAction = 'manage_edit-'.$postTypeModel->getName().'_sortable_columns';
            $customColumnsAction = 'manage_'.$postTypeModel->getName().'_posts_custom_column';

            // add columns to show
            add_filter($manageEditAction, function($columns) use ($postTypeModel) {
                foreach ($postTypeModel->getMetaBoxes() as $metaBoxModel){
                    foreach ($metaBoxModel->getFields() as $metaBoxFieldModel){
                        if ($metaBoxFieldModel->isShowInArchive()){
                            $key = Strings::toDBFormat($metaBoxModel->getName()).'_'.Strings::toDBFormat($metaBoxFieldModel->getName());
                            $value = Strings::toHumanReadableFormat($metaBoxFieldModel->getName());

                            $columns[$key] = $value;
                        }
                    }
                }

                return $columns;
            });

            // add sortable columns
	        add_filter( $manageEditSortAction, function($columns) use ($postTypeModel){
		        foreach ($postTypeModel->getMetaBoxes() as $metaBoxModel){
			        foreach ($metaBoxModel->getFields() as $metaBoxFieldModel){
				        if ($metaBoxFieldModel->isShowInArchive() and $metaBoxFieldModel->isFilterable() and $metaBoxFieldModel->isFilterableInAdmin()){
					        $key = Strings::toDBFormat($metaBoxModel->getName()).'_'.Strings::toDBFormat($metaBoxFieldModel->getName());
					        $value = Strings::toHumanReadableFormat($metaBoxFieldModel->getName());

					        $columns[$key] = $value;
				        }
			        }
		        }

		        return $columns;
            } );

	        // add filterable columns
	        add_action( 'restrict_manage_posts', function($post_type) use ($postTypeModel) {

		        if($post_type !== $postTypeModel->getName()){
			        return;
		        }

		        foreach ($postTypeModel->getMetaBoxes() as $metaBoxModel){
			        foreach ($metaBoxModel->getFields() as $metaBoxFieldModel){
				        if ($metaBoxFieldModel->isShowInArchive() and $metaBoxFieldModel->isFilterable() and $metaBoxFieldModel->isFilterableInAdmin()){

					        //get unique values of the meta field to filer by.
					        $metaKey = Strings::toDBFormat($metaBoxModel->getName()).'_'.Strings::toDBFormat($metaBoxFieldModel->getName());
					        $metaLabel = ($metaBoxFieldModel->getAdvancedOption('label')) ? $metaBoxFieldModel->getAdvancedOption('label') : $metaBoxFieldModel->getName();

					        $selected = '';
					        if ( isset($_REQUEST[$metaKey]) ) {
						        $selected = $_REQUEST[$metaKey];
					        }

					        global $wpdb;

					        $results = $wpdb->get_results(
						        $wpdb->prepare( "
                                    SELECT DISTINCT pm.meta_value, pm.meta_id FROM {$wpdb->postmeta} pm
                                    LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
                                    WHERE pm.meta_key = '%s' 
                                    AND p.post_status IN ('publish', 'draft')
                                    ORDER BY pm.meta_value",
							        $metaKey
						        )
					        );

					        echo '<select id="'.$metaKey.'" name="'.$metaKey.'">';
					        echo '<option value="0">' . Translator::translate('Select') . ' ' . $metaLabel .' </option>';

					        foreach($results as $result){
						        $select = ($result->meta_id == $selected) ? ' selected="selected"':'';
						        echo '<option value="'.$result->meta_id.'"'.$select.'>' . $result->meta_value . ' </option>';
					        }

					        echo '</select>';
				        }
			        }
		        }
	        });

	        add_action('pre_get_posts', function ($query) use ($postTypeModel) {
		        if ( is_admin() && $query->is_main_query() ) {
			        $scr = get_current_screen();

			        if ( $scr->base !== 'edit' && $scr->post_type !== 'events' ) {
				        return;
                    }

			        foreach ($postTypeModel->getMetaBoxes() as $metaBoxModel){
				        foreach ($metaBoxModel->getFields() as $metaBoxFieldModel){
					        if ($metaBoxFieldModel->isShowInArchive() and $metaBoxFieldModel->isFilterable() and $metaBoxFieldModel->isFilterableInAdmin()){

						        $metaKey = Strings::toDBFormat($metaBoxModel->getName()).'_'.Strings::toDBFormat($metaBoxFieldModel->getName());

						        if (isset($_GET[$metaKey]) && $_GET[$metaKey] != 0) {

							        $meta = get_post_meta_by_id($_GET[$metaKey]);
							        $query->set('meta_query', [
								        [
									        'key' => $metaKey,
									        'value' => sanitize_text_field($meta->meta_value),
									        'compare' => '=',
									        'type' => 'CHAR'
								        ]
                                    ]);
						        }
					        }
				        }
			        }
		        }
            });

	        // quick edit
	        add_action( 'quick_edit_custom_box', function($column_name) use ($postTypeModel) {
		        global $post;

		        foreach ($postTypeModel->getMetaBoxes() as $metaBoxModel){
			        foreach ($metaBoxModel->getFields() as $metaBoxFieldModel){
				        if (
				                $metaBoxFieldModel->isShowInArchive() and
                                $metaBoxFieldModel->isForQuickEdit() and
                                $metaBoxFieldModel->isATextualField()
                        ){
					        $key = Strings::toDBFormat($metaBoxModel->getName()).'_'.Strings::toDBFormat($metaBoxFieldModel->getName());
					        $key = esc_html($key);
					        $label = Strings::toHumanReadableFormat($metaBoxFieldModel->getName());
					        $value = get_post_meta( $post->ID, $key, true );
					        if( $column_name === $key ):
						        Nonce::field();
						        ?>
						        <fieldset class="inline-edit-col-right" id="#edit-<?php echo $key; ?>">
							        <input type="hidden" name="meta_fields[]" value="<?php echo $key; ?>">
							        <input type="hidden" name="meta_fields[]" value="<?php echo $key; ?>_type">
							        <input type="hidden" name="<?php echo $key; ?>_type" value="<?php echo $metaBoxFieldModel->getType(); ?>">
							        <input type="hidden" name="<?php echo $key; ?>_required" value="<?php echo $metaBoxFieldModel->isRequired(); ?>">
							        <div class="inline-edit-col">
								        <label>
									        <span class="title"><?php echo $label; ?></span>
									        <span class="input-text-wrap">
                                                <?php if($metaBoxFieldModel->getType() === AbstractMetaBoxFieldModel::EMAIL_TYPE): ?>
										            <input type="email" name="<?php echo $key; ?>" data-acpt-column="column-<?php echo $key; ?>" class="inline-edit-menu-order-input" value="<?php echo $value; ?>">
                                                <?php elseif($metaBoxFieldModel->getType() === AbstractMetaBoxFieldModel::TEXTAREA_TYPE): ?>
                                                    <textarea name="<?php echo $key; ?>" data-acpt-column="column-<?php echo $key; ?>" class="inline-edit-menu-order-input" rows="5"><?php echo $value; ?>"</textarea>
					                            <?php elseif($metaBoxFieldModel->getType() === AbstractMetaBoxFieldModel::NUMBER_TYPE): ?>
                                                    <input type="number" name="<?php echo $key; ?>" data-acpt-column="column-<?php echo $key; ?>" class="inline-edit-menu-order-input" value="<?php echo $value; ?>">
                                                <?php else: ?>
                                                    <input type="text" name="<?php echo $key; ?>" data-acpt-column="column-<?php echo $key; ?>" class="inline-edit-menu-order-input" value="<?php echo $value; ?>">
                                                <?php endif; ?>
									        </span>
								        </label>
							        </div>
						        </fieldset>
						        <?php endif;
				        }
			        }
		        }
	        } );

	        // display value on columns to show
            add_action($customColumnsAction, function($name) use ($postTypeModel) {
                global $post;

                foreach ($postTypeModel->getMetaBoxes() as $metaBoxModel){
                    foreach ($metaBoxModel->getFields() as $metaBoxFieldModel){
                        if ($metaBoxFieldModel->isShowInArchive()){
                            $key = Strings::toDBFormat($metaBoxModel->getName()).'_'.Strings::toDBFormat($metaBoxFieldModel->getName());

                            if($key === $name){
                                echo do_shortcode('[acpt preview="true" pid="'.$post->ID.'" box="'.esc_attr($metaBoxModel->getName()).'" field="'.esc_attr($metaBoxFieldModel->getName()).'"]');
                            }
                        }
                    }
                }
            });
        }
    }

    /**
     * @throws \Exception
     */
    private function registerUserMeta()
    {
        $boxes = MetaRepository::get([
            'belongsTo' => MetaTypes::USER,
        ]);

        if(!empty($boxes)){
            $generator = new UserMetaBoxGenerator($boxes);
            $generator->generate();
        }
    }

    /**
     * Add User meta columns to show in the admin panel
     */
    private function addUserMetaColumnsToShow()
    {
        add_filter( 'manage_users_columns', function ($column) {

            $boxes = MetaRepository::get([
                'belongsTo' => MetaTypes::USER,
            ]);

            foreach ($boxes as $boxModel){
                foreach ($boxModel->getFields() as $fieldModel){
                    if($fieldModel->isShowInArchive()){
                        $key = Strings::toDBFormat($boxModel->getName()).'_'.Strings::toDBFormat($fieldModel->getName());
                        $value = Strings::toHumanReadableFormat($fieldModel->getName());
                        $column[$key] = $value;
                    }
                }
            }

            return $column;
        } );

        add_filter( 'manage_users_custom_column', function ( $val, $columnName, $userId ) {

            $boxes = MetaRepository::get([
                'belongsTo' => MetaTypes::USER,
            ]);

            foreach ($boxes as $boxModel){
                foreach ($boxModel->getFields() as $fieldModel){
                    if($fieldModel->isShowInArchive()){
                        $key = Strings::toDBFormat($boxModel->getName()).'_'.Strings::toDBFormat($fieldModel->getName());

                        if($key === $columnName){
                            return do_shortcode( '[acpt_user uid="'.$userId.'" box="'.esc_attr($boxModel->getName()).'" field="'.esc_attr($fieldModel->getName()).'"]');
                        }
                    }
                }
            }
        }, 10, 3 );
    }

    /**
     * Dynamically add template (single and archive)
     * for registered custom post types and taxonomies
     *
     * @throws \Exception
     */
    private function registerTemplates()
    {
    	// allow template only if the visual builder is enabled
	    $enableVisualEditorKey = SettingsRepository::getSingle(SettingsModel::ENABLE_VISUAL_EDITOR_KEY);
	    $enableVisualEditor = ($enableVisualEditorKey !== null) ? $enableVisualEditorKey->getValue() : null;

	    if($enableVisualEditor != 1){
	    	return;
	    }

        // If Oxygen builder is active return
        if(is_plugin_active( 'oxygen/functions.php' )){
            return;
        }

        // If BB theme builder is active return
        if(is_plugin_active( 'bb-theme-builder/bb-theme-builder.php' )){
            return;
        }

        foreach ( CustomPostTypeRepository::get() as $postTypeModel){
            $postName = $postTypeModel->getName();
            $templates = $postTypeModel->getTemplates();

            if(!empty($templates)){

                add_filter('template_include', function($template) use ($postTypeModel, $postName) {

                    $hasArchiveTemplatePage = $postTypeModel->hasArchiveTemplatePage();
                    $hasSingleTemplatePage = $postTypeModel->hasSingleTemplatePage();

                    // WooCommerce single product
                    if($postTypeModel->isWooCommerce() and is_singular('product') ){

                        if($hasSingleTemplatePage){
                            $this->enqueueFrontEndScripts();
                        }

                        $themeFiles = ['acpt/woocommerce/single-product.php', 'woocommerce/single-product.php', 'single-product.php', ];
                        $existsInTheme = WPUtils::locateTemplate($themeFiles, false);

                        if ( $existsInTheme != '' ) {
                            return $existsInTheme;
                        }

                        $existsTemplate = $hasSingleTemplatePage;
                        if(!$existsTemplate){

                            $existsInTheme = WPUtils::locateTemplate(['woocommerce/single-product.php'], false);
                            if ( $existsInTheme != '' ) {
                                return $existsInTheme;
                            }

                            return WP_PLUGIN_DIR . '/woocommerce/templates/single-product.php';
                        }

                        return plugin_dir_path(__FILE__) . '../../templates/woocommerce/single-product.php';
                    }

                    if ( ($hasArchiveTemplatePage and is_post_type_archive($postName))
                            or ( $hasSingleTemplatePage and is_page() and is_singular('page') )
                            or ( $hasSingleTemplatePage and is_single() and is_singular($postName) )
                    ) {
                        $this->enqueueFrontEndScripts();
                    }

                    $categoryObject = get_queried_object();

                    // post category
                    if( is_category() and $categoryObject === null ){

                        $themeFiles = ['acpt/category.php', 'category.php',];
                        $existsInTheme = WPUtils::locateTemplate($themeFiles, false);

                        if ( $existsInTheme != '' ) {
                            return $existsInTheme;
                        }

                        $existsTemplate = $hasArchiveTemplatePage;
                        if(!$existsTemplate){
                            return WPUtils::locateTemplate(['category.php', 'index.php'], false);
                        }

                        return plugin_dir_path(__FILE__) . '../../templates/archive-template.php';
                    }

                    // other cpt archive
                    if ( is_post_type_archive($postName) ) {
                        $themeFiles = ['acpt/archive-'.$postName.'.php', 'archive-'.$postName.'.php', ];
                        $existsInTheme = WPUtils::locateTemplate($themeFiles, false);
                        if ( $existsInTheme != '' ) {
                            return $existsInTheme;
                        }

                        $existsTemplate = $hasArchiveTemplatePage;
                        if(!$existsTemplate){
                            return WPUtils::locateTemplate(['archive-'.$postName.'.php', 'archive.php', 'index.php'], false);
                        }

                        return plugin_dir_path(__FILE__) . '../../templates/archive-template.php';
                    }

                    // page
                    if(is_page() and !is_page_template()){

                        $themeFiles = ['acpt/page.php', 'page.php', ];
                        $existsInTheme = WPUtils::locateTemplate($themeFiles, false);
                        if ( $existsInTheme != '' ) {
                            return $existsInTheme;
                        }

                        $existsTemplate = $hasSingleTemplatePage;
                        if(!$existsTemplate){
                            return WPUtils::locateTemplate(['page.php', 'index.php'], false);
                        }

                        return plugin_dir_path(__FILE__) . '../../templates/single-template.php';
                    }

                    // single post
                    if ( is_single() and is_singular($postName) and $postName === 'post' ) {
                        $themeFiles = ['acpt/single.php', 'acpt/single-'.$postName.'.php', 'single.php', ];
                        $existsInTheme = WPUtils::locateTemplate($themeFiles, false);
                        if ( $existsInTheme != '' ) {
                            return $existsInTheme;
                        }

                        $existsTemplate = $hasSingleTemplatePage;
                        if(!$existsTemplate){
                            return WPUtils::locateTemplate(['single-'.$postName.'.php', 'single.php', 'index.php'], false);
                        }

                        return plugin_dir_path(__FILE__) . '../../templates/single-template.php';
                    }

                    // other cpt
                    if ( is_single() and is_singular($postName) and $postName !== 'post' ) {

                        $themeFiles = [ 'acpt/single-'.$postName.'.php', 'single-'.$postName.'.php', 'acpt/single-'.$postName.'.php', ];
                        $existsInTheme = WPUtils::locateTemplate($themeFiles, false);
                        if ( $existsInTheme != '' ) {
                            return $existsInTheme;
                        }

                        $existsTemplate = $hasSingleTemplatePage;
                        if(!$existsTemplate){
                            return WPUtils::locateTemplate(['acpt/single-'.$postName.'.php', 'single-'.$postName.'.php', 'single.php', 'index.php'], false);
                        }

                        return plugin_dir_path(__FILE__) . '../../templates/single-template.php';
                    }

                    return $template;
                }, 1000000000, 1);
            }
        }

        foreach (TaxonomyRepository::get() as $taxonomyModel){
            $slug = $taxonomyModel->getSlug();
            $templates = $taxonomyModel->getTemplates();

            if(!empty($templates)){

                add_filter('template_include', function($template) use ($taxonomyModel, $slug) {

                    $categoryObject = get_queried_object();

                    if( is_category() and $categoryObject !== null and is_main_query() ){
                        $themeFiles = ['acpt/category.php', 'category.php',];
                        $existsInTheme = WPUtils::locateTemplate($themeFiles, false);
                        if ( $existsInTheme != '' ) {
                            return $existsInTheme;
                        }

                        return plugin_dir_path(__FILE__) . '../../templates/taxonomy-template.php';
                    }

                    if( is_tax($slug) and $categoryObject !== null and is_main_query() ){
                        $themeFiles = ['acpt/taxonomy-'.$slug.'.php', 'taxonomy-'.$slug.'php', 'acpt/taxonomy.php', 'taxonomy.php',];
                        $existsInTheme = WPUtils::locateTemplate($themeFiles, false);
                        if ( $existsInTheme != '' ) {
                            return $existsInTheme;
                        }

                        return plugin_dir_path(__FILE__) . '../../templates/taxonomy-template.php';
                    }

                    return $template;
                }, 1000000000, 1);
            }
        }


    }

	/**
	 * Register option pages
	 *
	 * @throws \Exception
	 */
    private function registerOptionPages()
    {
    	$optionPages = OptionPageRepository::get([]);

    	foreach ($optionPages as $optionPage){
    	    $optionPageGenerator = new OptionPageGenerator($this->loader, $optionPage);
		    $optionPageGenerator->registerPage();
	    }
    }

    /**
     * Enqueue front end scripts (CSS and JS)
     */
    private function enqueueFrontEndScripts()
    {
        wp_enqueue_style( dirname( __FILE__ ).'__product' , plugin_dir_url( dirname( __FILE__ ) ) . '../assets/build/theme.min.css', [], ACPT_PLUGIN_VERSION, 'all');
        wp_register_script('iconify',  plugin_dir_url( dirname( __FILE__ ) ) . '../assets/static/js/iconify/iconify.min.js' );
        wp_enqueue_script('iconify');
    }

    /**
     * Register hooks for the theme UI
     */
    private function registerHooks()
    {
        $this->loader->addAction( 'acpt_after_main_content', new ACPT_Hooks(), 'afterMainContent' );
        $this->loader->addAction( 'acpt_before_main_content', new ACPT_Hooks(), 'beforeMainContent' );
        $this->loader->addAction( 'acpt_breadcrumb', new ACPT_Hooks(), 'breadcrumb' );
        $this->loader->addAction( 'acpt_thumbnail', new ACPT_Hooks(), 'thumbnail' );
        $this->loader->addAction( 'acpt_template_content', new ACPT_Hooks(), 'templateContent' );
        $this->loader->addAction( 'acpt_archive_title', new ACPT_Hooks(), 'archiveTitle' );
        $this->loader->addAction( 'acpt_loop', new ACPT_Hooks(), 'loop' );
        $this->loader->addAction( 'acpt_archive_pagination', new ACPT_Hooks(), 'pagination' );
        $this->loader->addAction( 'acpt_prev_next_links', new ACPT_Hooks(), 'prevNextLinks' );
        $this->loader->addAction( 'acpt_taxonomy_links', new ACPT_Hooks(), 'taxonomyLinks' );
        $this->loader->addAction( 'acpt_custom_styles', new ACPT_Hooks(), 'customStyles' );
    }

    /**
     * Register API fields
     */
    private function registerRestFields()
    {
        $this->loader->addAction( 'rest_api_init', new ACPT_Api_Rest_Fields(), 'registerRestFields' );
    }

    /**
     * Register API endpoints
     */
    private function registerRestEndpoint()
    {
        $this->loader->addAction( 'rest_api_init', new ACPT_Api_V1(), 'registerRestRoutes' );
    }

    /**
     * Include PHP functions
     */
    private function includeFunctions()
    {
        require_once __DIR__.'/../../functions/acpt_functions.php';
    }

    /**
     * Run integrations
     */
    private function runIntegrations()
    {
        $integrations = [
            ACPT_Elementor::class,
            ACPT_WPGraphQL::class,
            ACPT_Divi::class,
            ACPT_Bricks::class,
	        ACPT_Oxygen::class,
	        ACPT_Breakdance::class,
            ACPT_WPAllExport::class,
            ACPT_WPAllImport::class,
            ACPT_Zion::class,
        ];

        foreach ($integrations as $integration){
            /** @var AbstractIntegration $instance */
            $instance = new $integration;
            $instance->run();
        }
    }

	/**
	 * Run admin scripts
	 *
	 * @throws \Exception
	 */
    public function run()
    {
        // filters
        $this->addFilters();

        // pages and assets
        $this->loader->addAction( 'admin_menu', $this, 'addPages' );
        $this->loader->addAction( 'admin_enqueue_scripts', $this, 'enqueueAssets' );

        // ajax calls
        foreach ($this->ajaxActions as $action => $callback){
            $this->loader->addAction($action, $this->ajax, $callback);
        }

        // shortcodes
        $this->addShortcodes();

        // register custom post types and taxonomies
        $this->registerCustomPostTypesAndTaxonomies();

        // register taxonomy meta
        $this->registerTaxonomyMeta();

        // WooCommerce product data
        $this->addWooCommerceProductData();

        // add columns to show in the list panel
        $this->addColumnsToAdminPanel();

        // register user meta
        $this->registerUserMeta();

        // add user meta columns to show in the admin panel
        $this->addUserMetaColumnsToShow();

        // add templates show in the list panel
        $this->registerTemplates();

        // add option pages
	    $this->registerOptionPages();

        // register hooks
        $this->registerHooks();

        // API REST
        $this->registerRestFields();
        $this->registerRestEndpoint();

        // functions and hooks
        $this->includeFunctions();

        // run integrations
        $this->runIntegrations();
    }
}
