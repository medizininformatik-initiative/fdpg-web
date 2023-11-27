<?php

namespace ACPT\Core\Generators\CustomPostTypeFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Generators\RepeaterFieldGenerator;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Costants\MetaTypes;
use ACPT\Utils\Wordpress\Translator;

class RepeaterField extends AbstractCustomPostTypeField implements MetaFieldInterface
{
	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
    public function render()
    {
        $this->enqueueAssets();

        $icon = 'bx:folder-plus';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.CustomPostTypeMetaBoxFieldModel::REPEATER_TYPE.'">';
        $field .= '<ul id="'.$this->id.'" class="acpt-sortable '.$this->getGridCssClass().'">';

        // render default data
        $defaultData = $this->getDefaultValue();

        if($defaultData and $defaultData !== '' and is_array($defaultData)){
            $metaField = MetaRepository::getMetaField([
                'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
                'id' => $this->id,
            ]);
            $repeaterFieldGenerator = new RepeaterFieldGenerator($metaField);
            $repeaterFieldGenerator->setDataId($this->postId);
            $repeaterFieldGenerator->setData($defaultData);

            $field .= $repeaterFieldGenerator->generate();
        } else {
	        $field .= '<p data-message-id="'.$this->id.'" class="update-nag notice notice-warning inline no-records">'.Translator::translate('No fields saved, generate the first one clicking on "Add element" button').'</p>';
        }

        $field .= '</ul>';
        $field .= '<a data-media-type="'.MetaTypes::CUSTOM_POST_TYPE.'" id="add-grouped-element" data-group-id="'.$this->id.'" href="#" class="button small">'.Translator::translate('Add element').'</a>';

        echo $this->renderField($icon, $field);
    }

	/**
	 * Enqueue necessary assets
	 * @throws \Exception
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
	 * @throws \Exception
	 */
    private function thereIsADaterangeField()
    {
    	$return = false;

	    $metaField = MetaRepository::getMetaField([
		    'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
		    'id' => $this->id,
	    ]);

	    foreach ($metaField->getChildren() as $child){
	    	if($child->getType() === CustomPostTypeMetaBoxFieldModel::DATE_RANGE_TYPE){
	    		return true;
		    }
	    }

    	return $return;
    }
}