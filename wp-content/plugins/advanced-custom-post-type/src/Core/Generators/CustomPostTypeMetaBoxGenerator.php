<?php

namespace ACPT\Core\Generators;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxModel;
use ACPT\Utils\Wordpress\Nonce;

/**
 * *************************************************
 * MetaBoxGenerator class
 * *************************************************
 *
 * @author Mauro Cassani
 * @link https://github.com/mauretto78/
 */
class CustomPostTypeMetaBoxGenerator extends AbstractGenerator
{
    /**
     * Creates a new custom meta box in the $postTypeName page.
     *
     * @param AbstractMetaBoxModel $metaBoxModel
     * @param string $postTypeName
     * @param array  $formFields
     */
    public function addMetaBox(AbstractMetaBoxModel $metaBoxModel, $postTypeName, $formFields = []) {

        // end update_edit_form
        add_action('post_edit_form_tag', function() {
            echo ' enctype="multipart/form-data"';
        });

        $this->adminInit(function() use( $metaBoxModel, $formFields, $postTypeName ) {

	        $boxLabel = (!empty($metaBoxModel->getLabel())) ? $metaBoxModel->getLabel() : $metaBoxModel->getName();
			$idBox = 'acpt_metabox_'. Strings::toDBFormat($boxLabel);

            add_meta_box(
	            $idBox,
                $boxLabel,
                function($post, $data) use ($metaBoxModel) {

                    // add nonce here
                    Nonce::field();

                    // List of all the specified form fields
                    $inputs = $data['args'][0];

                    foreach ($inputs as $input) {
                        CustomPostTypeMetaBoxFieldGenerator::generate($post->ID, $metaBoxModel, $input);
                    }
                },
                strtolower($postTypeName),
                'advanced',
                'high',
                [$formFields]
            );
        });
    }
}

