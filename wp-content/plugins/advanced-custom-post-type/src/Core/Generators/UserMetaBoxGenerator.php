<?php

namespace ACPT\Core\Generators;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\User\UserMetaBoxFieldModel;
use ACPT\Core\Models\User\UserMetaBoxModel;
use ACPT\Core\Validators\MetaDataValidator;
use ACPT\Utils\Data\Sanitizer;

/**
 * *************************************************
 * UserMetaBoxGenerator class
 * *************************************************
 *
 * @author Mauro Cassani
 * @link https://github.com/mauretto78/
 */
class UserMetaBoxGenerator extends AbstractGenerator
{
    /**
     * @var UserMetaBoxModel[] $boxes
     */
    private $boxes;

    /**
     * UserMetaBoxGenerator constructor.
     *
     * @param array $boxes
     */
    public function __construct( array $boxes)
    {
        $this->boxes = $boxes;
    }

    /**
     * Register all the functions here
     */
    public function generate()
    {
        add_action( 'show_user_profile', [$this, 'addMetaBoxes'] );
        add_action( 'edit_user_profile', [$this, 'addMetaBoxes'] );
        add_action( 'personal_options_update', [$this, 'saveData'] );
        add_action( 'edit_user_profile_update', [$this, 'saveData'] );
    }

    /**
     * Add custom meta boxes to the User.
     *
     * @param \WP_User $user
     */
    public function addMetaBoxes(\WP_User $user)
    {
        ?><h2>ACPT</h2><?php
        foreach ($this->boxes as $boxModel):
        ?>
            <div class="acpt-user-meta-box">
                <h3><?php echo (!empty($boxModel->getLabel())) ? $boxModel->getLabel() : $boxModel->getName(); ?></h3>
                <table class="form-table" id="user-meta-box-<?php echo $boxModel->getId(); ?>">
                    <?php
                    foreach ($boxModel->getFields() as $fieldModel) {
                        $userFieldGenerator = new UserMetaFieldGenerator($fieldModel, $user);
                        $userFieldGenerator->generate();
                    }
                    ?>
                </table>
            </div>
        <?php endforeach;
    }

    /**
     * Save data
     */
    public function saveData($user_id)
    {
        if ( !current_user_can( 'edit_user', $user_id ) ){
            return false;
        }

        foreach ($this->boxes as $boxModel){
            foreach ($boxModel->getFields() as $fieldModel){
                $idName = Strings::toDBFormat($boxModel->getName()) . '_' . Strings::toDBFormat($fieldModel->getName());

	            if(isset($_POST[$idName])){
		            $rawValue = $_POST[$idName];

		            // validation
		            try {
			            MetaDataValidator::validate($fieldModel->getType(), $rawValue);
		            } catch (\Exception $exception){
			            wp_die('There was an error during saving data. The error is: ' . $exception->getMessage());
		            }

		            update_user_meta( $user_id, $idName, Sanitizer::sanitizeRawData($fieldModel->getType(), $rawValue ) );

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
			            if(isset($_POST[$idName.'_'.$extra])){
				            update_user_meta( $user_id, $idName.'_'.$extra, Sanitizer::sanitizeRawData(UserMetaBoxFieldModel::TEXT_TYPE, $_POST[$idName.'_'.$extra] ) );
			            }
		            }
	            }
            }
        }
    }
}