<?php

namespace ACPT\Core\Generators\OptionPageFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Generators\RepeaterFieldGenerator;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Costants\MetaTypes;
use ACPT\Utils\Wordpress\Translator;

class RepeaterField extends AbstractOptionPageField implements MetaFieldInterface
{
	/**
	 * @return mixed|void
	 * @throws \Exception
	 */
    public function render()
    {
        $this->enqueueAssets();

        $icon = 'bx:folder-plus';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.OptionPageMetaBoxFieldModel::REPEATER_TYPE.'">';
        $field .= '<ul id="'.$this->fieldModel->getId().'" class="acpt-sortable '.$this->getGridCssClass().'">';

        // render default data
        $defaultData = $this->getDefaultValue();

        if($defaultData and $defaultData !== '' and is_array($defaultData)){
            $metaField = MetaRepository::getMetaField([
                'belongsTo' => MetaTypes::OPTION_PAGE,
                'id' => $this->fieldModel->getId(),
            ]);
            $repeaterFieldGenerator = new RepeaterFieldGenerator($metaField);
            $repeaterFieldGenerator->setData($defaultData);

            $field .= $repeaterFieldGenerator->generate();
        } else {
	        $field .= '<p data-message-id="'.$this->fieldModel->getId().'" class="update-nag notice notice-warning inline no-records">'.Translator::translate('No fields saved, generate the first one clicking on "Add element" button').'</p>';
        }

        $field .= '</ul>';
        $field .= '<a data-media-type="'.MetaTypes::OPTION_PAGE.'" id="add-grouped-element" data-group-id="'.$this->fieldModel->getId().'" href="#" class="button small">'.Translator::translate('Add element').'</a>';

	    return $this->renderField($icon, $field);
    }

    /**
     * Enqueue necessary assets
     */
    private function enqueueAssets()
    {
        wp_enqueue_script( 'html5sortable', plugin_dir_url( dirname( __FILE__ ) ) . '../../../assets/vendor/html5sortable/dist/html5sortable.min.js', [], '2.2.0', true);

	    // for date range
	    if($this->thereIsADaterangeField()){
		    wp_enqueue_script( 'momentjs', plugin_dir_url( dirname( __FILE__ ) ) . '../../../assets/vendor/moment/moment.min.js', [], '2.18.1', true);
		    wp_enqueue_script( 'daterangepicker-js', plugin_dir_url( dirname( __FILE__ ) ) . '../../../assets/vendor/daterangepicker/js/daterangepicker.min.js', [], '3.1.0', true);
		    wp_enqueue_style( 'daterangepicker-css', plugin_dir_url( dirname( __FILE__ ) ) . '../../../assets/vendor/daterangepicker/css/daterangepicker.min.css', [], '3.1.0', 'all');
		    wp_enqueue_script( 'custom-daterangepicker-js', plugin_dir_url( dirname( __FILE__ ) ) . '../../../assets/static/js/daterangepicker.js', [], '1.0.0', true);
	    }
    }

	/**
	 * @return bool
	 */
	private function thereIsADaterangeField()
	{
		$return = false;

		foreach ($this->fieldModel->getChildren() as $child){
			if($child->getType() === OptionPageMetaBoxFieldModel::DATE_RANGE_TYPE){
				return true;
			}
		}

		return $return;
	}
}