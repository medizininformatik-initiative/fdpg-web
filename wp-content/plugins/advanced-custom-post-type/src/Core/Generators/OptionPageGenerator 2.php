<?php

namespace ACPT\Core\Generators;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;
use ACPT\Core\Models\OptionPage\OptionPageModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Core\Repository\OptionPageRepository;
use ACPT\Core\Validators\MetaDataValidator;
use ACPT\Costants\MetaTypes;
use ACPT\Includes\ACPT_Loader;
use ACPT\Utils\Data\Sanitizer;
use ACPT\Utils\PHP\Arrays;
use ACPT\Utils\Wordpress\Nonce;
use ACPT\Utils\Wordpress\Translator;

/**
 * *************************************************
 * OptionPageGenerator class
 * *************************************************
 *
 * @author Mauro Cassani
 * @link https://github.com/mauretto78/
 */
class OptionPageGenerator extends AbstractGenerator
{
	private const SESSION_KEY = 'option_page_save_outcome';

	/**
	 * @var OptionPageModel
	 */
	private OptionPageModel $optionPageModel;

	/**
	 * @var ACPT_Loader
	 */
	private ACPT_Loader $loader;

	/**
	 * OptionPageGenerator constructor.
	 *
	 * @param ACPT_Loader $loader
	 * @param OptionPageModel $optionPageModel
	 */
	public function __construct(ACPT_Loader $loader, OptionPageModel $optionPageModel)
	{
		$this->optionPageModel = $optionPageModel;
		$this->loader = $loader;
	}

	/**
	 * Register page
	 */
	public function registerPage()
	{
		$this->loader->addAction('admin_menu', $this, 'addMenuPage');
	}

	/**
	 * Call add_menu_page Wordpress function
	 */
	public function addMenuPage()
	{
		add_menu_page(
			$this->optionPageModel->getPageTitle(),
			$this->optionPageModel->getMenuTitle(),
			$this->optionPageModel->getCapability(),
			$this->optionPageModel->getMenuSlug(),
			function () {
				return $this->renderPage($this->optionPageModel);
			},
			$this->optionPageModel->renderIcon(),
			$this->optionPageModel->getPosition()
		);

		foreach ($this->optionPageModel->getChildren() as $childPageModal){
			add_submenu_page(
				$this->optionPageModel->getMenuSlug(),
				$childPageModal->getPageTitle(),
				$childPageModal->getMenuTitle(),
				$childPageModal->getCapability(),
				$childPageModal->getMenuSlug(),
				function () use ($childPageModal) {
					return $this->renderPage($childPageModal);
				},
				$childPageModal->getPosition()
			);
		}
	}

	/**
	 * @param OptionPageModel $optionPageModel
	 *
	 * @throws \Exception
	 */
	public function renderPage(OptionPageModel $optionPageModel)
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		wp_editor('', 'no-show'); // Hack for enqueuing WP Editor

		if(isset($_POST[$this->nonceAction()]) and Nonce::verify($_POST[$this->nonceAction()])){
			unset($_POST[$this->nonceAction()]);
			unset($_POST['_wp_http_referer']);
			$this->saveOptions($_POST);
			$this->safeRedirect($optionPageModel->getMenuSlug());
		}

		$return = '<div class="wrap">';
		$return .= '<div id="no-show"></div>'; // Hack for enqueuing WP Editor
		$return .= '<h1 class="wp-heading-inline">'.$optionPageModel->getPageTitle().'</h1>';

		if($optionPageModel->getDescription()){
			$return .= '<p>'.$optionPageModel->getDescription().'</p>';
		}

		// flush messages
		if(!empty($_SESSION[self::SESSION_KEY])){
			foreach ($_SESSION[self::SESSION_KEY] as $level => $messages) {
				foreach ($messages as $message){
					$return .= '<div class="notice notice-'.$level.'"><p>'.$message.'</p></div>';
				}
			}

			$_SESSION[self::SESSION_KEY] = [];
		}

		$boxes = MetaRepository::get([
			'belongsTo' => MetaTypes::OPTION_PAGE,
			'find' => $optionPageModel->getMenuSlug()
		]);

		if(count($boxes) > 0){
			$return .= '<div class="meta-box-sortables">';
			$return .= '<div class="metabox-holder">';
			$return .= '<form method="post" action="">';
			$return .= '<input type="hidden" name="option_page_id" value="'.$optionPageModel->getId().'">';

			foreach ($boxes as $boxModel){
				$boxGenerator = new OptionPageMetaBoxGenerator($boxModel);
				$return .= $boxGenerator->render();
			}

			$return .= '<button class="button button-primary">'.Translator::translate('Save').'</button>';
			$return .= Nonce::field($this->nonceAction());
			$return .= '</form>';
			$return .= '</div>';
			$return .= '</div>';
		} else {
			$return .= '<div class="notice notice-warning"><p>'.Translator::translate("No meta boxes aldready created.").' <a href="/wp-admin/admin.php?page=advanced-custom-post-type#/option-page-meta/'.$optionPageModel->getMenuSlug().'">'.Translator::translate("Create the first one").'</a></p></div>';
		}


		$return .= '</div>';

		echo $return;
	}

	/**
	 * @return string
	 */
	private function nonceAction()
	{
		return 'save-options_'.$this->optionPageModel->getId();
	}

	/**
	 * @param array $data
	 *
	 * @throws \Exception
	 */
	private function saveOptions($data = [])
	{
		if(!isset($data['option_page_id'])){
			$_SESSION[self::SESSION_KEY]['error'][] = 'Error: missing <code>option_page_id</code> param';

			return;
		}

		$optionPageModel = OptionPageRepository::getById($data['option_page_id']);

		if($optionPageModel === null){
			$_SESSION[self::SESSION_KEY]['error'][] = 'Error: wrong option_page_id.';

			return;
		}

		foreach ($optionPageModel->getMetaBoxes() as $boxModel) {
			foreach ($boxModel->getFields() as $fieldModel) {
				$idName = Strings::toDBFormat( $boxModel->getName() ) . '_' . Strings::toDBFormat( $fieldModel->getName() );

				if(isset($data[$idName])){
					$rawValue = $data[$idName];

					// validation
					try {
						MetaDataValidator::validate($fieldModel->getType(), $rawValue);
					} catch (\Exception $exception){
						//wp_die('There was an error during saving data. The error is: ' . $exception->getMessage());
						$_SESSION[self::SESSION_KEY]['error'][] = Translator::translate('There was an error during saving data. The error is:'). ' ' . $exception->getMessage();

						return;
					}

					$value = $rawValue;

					if($fieldModel->getType() === OptionPageMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE){
						$minimumBlocks = $fieldModel->getAdvancedOption('minimum_blocks');
						$maximumBlocks = $fieldModel->getAdvancedOption('maximum_blocks');
						$numberOfBlocks = count($value['blocks']);

						if($minimumBlocks and ($numberOfBlocks < $minimumBlocks )){
							//wp_die('There was an error during saving data. Minimum number of blocks is : ' . $minimumBlocks);
							$_SESSION[self::SESSION_KEY]['error'][] = Translator::translate('There was an error during saving data. Minimum number of blocks is:'). ' ' . $minimumBlocks;

							return;
						}

						if($maximumBlocks and ($numberOfBlocks > $maximumBlocks )){
							$_SESSION[self::SESSION_KEY]['error'][] = Translator::translate('There was an error during saving data. Maximum number of blocks is:'). ' ' . $maximumBlocks;

							return;
						}
					}

					if(is_array($value)){
						$value = Arrays::reindex($value);
					}

					update_option($idName, Sanitizer::sanitizeOptionPageRawData($fieldModel->getType(), $value));

					$extras = [
						'type',
						'label',
						'currency',
						'weight',
						'length',
						'lat',
						'lng',
					];

					foreach ($extras as $extra){
						if(isset($data[$idName.'_'.$extra])){
							update_option($idName.'_'.$extra, Sanitizer::sanitizeTaxonomyRawData(OptionPageMetaBoxFieldModel::TEXT_TYPE, $data[$idName.'_'.$extra] ) );
						}
					}
				}

				// fix for flexible/repeater field
				if(($fieldModel->getType() === OptionPageMetaBoxFieldModel::REPEATER_TYPE or $fieldModel->getType() === OptionPageMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE) and !isset($data[$idName])){
					delete_option($idName);
				}
			}
		}

		$_SESSION[self::SESSION_KEY]['success'][] = Translator::translate('Data saved correctly');
	}

	/**
	 * Safe redirect
	 *
	 * @param $menuSlug
	 */
	private function safeRedirect($menuSlug)
	{
		wp_safe_redirect(
			esc_url(
				site_url( '/wp-admin/admin.php?page=' . $menuSlug )
			)
		);

		exit();
	}
}