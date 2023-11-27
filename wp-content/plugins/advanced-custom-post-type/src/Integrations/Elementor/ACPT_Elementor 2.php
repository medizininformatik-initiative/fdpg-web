<?php

namespace ACPT\Integrations\Elementor;

use ACPT\Core\Repository\CustomPostTypeRepository;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Costants\MetaTypes;
use ACPT\Integrations\AbstractIntegration;
use ACPT\Integrations\Elementor\Controls\DateFormatControl;
use ACPT\Integrations\Elementor\Controls\ElementsControl;
use ACPT\Integrations\Elementor\Controls\HeightControl;
use ACPT\Integrations\Elementor\Controls\ShortcodeControl;
use ACPT\Integrations\Elementor\Controls\TargetControl;
use ACPT\Integrations\Elementor\Controls\WidthControl;
use ACPT\Integrations\Elementor\Widgets\WidgetGenerator;

class ACPT_Elementor extends AbstractIntegration
{
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

    /**
     * @inheritDoc
     */
    protected function isActive()
    {
        return is_plugin_active( 'elementor/elementor.php' );
    }

    /**
     * @inheritDoc
     */
    protected function runIntegration()
    {
        if($this->checkIfElementorVersionIsCompatible()){
            add_action( 'elementor/controls/register', [$this, 'registerElementorControls'] );
            add_action( 'elementor/elements/categories_registered', [$this, 'addElementorWidgetCategory'] );
            add_action( 'elementor/widgets/register', [$this, 'registerElementorWidgets'] );
        }
    }

    /**
     * Register Elementor controls
     *
     * @param $controls_manager
     */
    function registerElementorControls( $controls_manager )
    {
        $controls_manager->register( new ShortcodeControl() );
        $controls_manager->register( new DateFormatControl() );
        $controls_manager->register( new ElementsControl() );
        $controls_manager->register( new WidthControl() );
        $controls_manager->register( new HeightControl() );
        $controls_manager->register( new TargetControl() );
    }

    /**
     * Add ACPT category to Elementor
     *
     * @param $elements_manager
     */
    public function addElementorWidgetCategory( $elements_manager )
    {
        $elements_manager->add_category(
            'acpt',
            [
                'title' => esc_html__( 'ACPT', ACPT_PLUGIN_NAME ),
                'icon' => 'fa fa-plug',
            ]
        );
    }

    /**
     * https://github.com/wpacademy/wpac-material-cards-elementor
     *
     * @param $widgets_manager
     * @throws \Exception
     * @since 1.0.3
     */
    public function registerElementorWidgets($widgets_manager)
    {
        $postType = (isset($_GET['post'])) ? get_post_type($_GET['post']) : null;
        $customPostTypeModels = CustomPostTypeRepository::get([
                'postType' => $postType
        ]);

        foreach ($customPostTypeModels as $customPostTypeModel){
            $metaBoxes = MetaRepository::get([
                'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
                'find' => $customPostTypeModel->getName(),
            ]);

            foreach ($metaBoxes as $metaBox){
                foreach ($metaBox->getFields() as $boxFieldModel){
                    $widgets_manager->register( new WidgetGenerator([], [
                            'boxFieldModel' => $boxFieldModel
                    ]));
                }
            }
        }
    }

    /**
     * @return bool
     */
    private function checkIfElementorVersionIsCompatible()
    {
        $elementorPlugin = __DIR__.'/../../../../elementor/elementor.php';

        if( !file_exists($elementorPlugin) ){
            return false;
        }

        $pluginData = get_plugin_data( $elementorPlugin );

        return version_compare( $pluginData['Version'], self::MINIMUM_ELEMENTOR_VERSION, '>=' );
    }
}
