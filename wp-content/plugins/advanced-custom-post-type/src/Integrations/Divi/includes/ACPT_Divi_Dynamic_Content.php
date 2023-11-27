<?php

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Costants\MetaTypes;

class ACPT_Divi_Dynamic_Content
{
    const ALLOWED_TYPES = [
	    CustomPostTypeMetaBoxFieldModel::ADDRESS_TYPE,
        CustomPostTypeMetaBoxFieldModel::CHECKBOX_TYPE,
        CustomPostTypeMetaBoxFieldModel::COLOR_TYPE,
        CustomPostTypeMetaBoxFieldModel::CURRENCY_TYPE,
        CustomPostTypeMetaBoxFieldModel::DATE_TYPE,
        CustomPostTypeMetaBoxFieldModel::DATE_RANGE_TYPE,
        CustomPostTypeMetaBoxFieldModel::EDITOR_TYPE,
        CustomPostTypeMetaBoxFieldModel::EMAIL_TYPE,
        CustomPostTypeMetaBoxFieldModel::EMBED_TYPE,
        CustomPostTypeMetaBoxFieldModel::FILE_TYPE,
       // CustomPostTypeMetaBoxFieldModel::GALLERY_TYPE,
        CustomPostTypeMetaBoxFieldModel::HTML_TYPE,
        CustomPostTypeMetaBoxFieldModel::IMAGE_TYPE,
        CustomPostTypeMetaBoxFieldModel::LENGTH_TYPE,
        CustomPostTypeMetaBoxFieldModel::LIST_TYPE,
        CustomPostTypeMetaBoxFieldModel::NUMBER_TYPE,
        CustomPostTypeMetaBoxFieldModel::PHONE_TYPE,
        CustomPostTypeMetaBoxFieldModel::RADIO_TYPE,
        CustomPostTypeMetaBoxFieldModel::RANGE_TYPE,
        CustomPostTypeMetaBoxFieldModel::RATING_TYPE,
        CustomPostTypeMetaBoxFieldModel::SELECT_TYPE,
        CustomPostTypeMetaBoxFieldModel::SELECT_MULTI_TYPE,
        CustomPostTypeMetaBoxFieldModel::TEXT_TYPE,
        CustomPostTypeMetaBoxFieldModel::TEXTAREA_TYPE,
        CustomPostTypeMetaBoxFieldModel::TIME_TYPE,
        CustomPostTypeMetaBoxFieldModel::VIDEO_TYPE,
        CustomPostTypeMetaBoxFieldModel::WEIGHT_TYPE,
        CustomPostTypeMetaBoxFieldModel::URL_TYPE,
    ];

    /**
     * Add ACPT fields to dynamic content source data
     *
     * @param array $custom_fields
     * @param int $post_id
     * @param array $raw_custom_fields
     *
     * @return array
     */
    public static function get_fields($custom_fields, $post_id, $raw_custom_fields )
    {
        try {
            $custom_fields = [];

            $postType = get_post_type($post_id);
            $postMeta = MetaRepository::get([
            	'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
	            'find' => $postType,
            ]);

            foreach ($postMeta as $boxModel){
                foreach ($boxModel->getFields() as $fieldModel){

                    $fieldType = $fieldModel->getType();
                    $valueForPostId = self::get_textual_value_for_field($fieldModel, $post_id);

                    if(in_array($fieldType, self::ALLOWED_TYPES) and $valueForPostId !== null){
                        $custom_fields[$fieldModel->getId()] = [
                            'label'    => esc_html( $fieldModel->getName() ),
                            'type'     => self::get_type($fieldType),
                            'fields'   => [
                                'before' => [
                                    'label'   => esc_html__( 'Before', ACPT_EXT_TEXT_DOMAIN ),
                                    'type'    => 'text',
                                    'default' => '',
                                    'show_on' => 'text',
                                ],
                                'after'  => [
                                    'label'   => esc_html__( 'After', ACPT_EXT_TEXT_DOMAIN ),
                                    'type'    => 'text',
                                    'default' => '',
                                    'show_on' => 'text',
                                ],
                            ],
                            'meta_key' => $fieldModel->getId(),
                            'custom'   => true,
                            'group'    => 'ACPT: ' . $boxModel->getName(),
                        ];
                    }
                }
            }

            return $custom_fields;
        } catch (\Exception $exception){
            return $custom_fields;
        }
    }

    /**
     * It returns 'text', 'image', 'url' or 'any'
     *
     * @param string $fieldType
     *
     * @return string
     */
    private static function get_type($fieldType)
    {
        switch ($fieldType){
            case CustomPostTypeMetaBoxFieldModel::IMAGE_TYPE:
            //case CustomPostTypeMetaBoxFieldModel::GALLERY_TYPE:
                $type = 'image';
                break;

            case CustomPostTypeMetaBoxFieldModel::FILE_TYPE:
            case CustomPostTypeMetaBoxFieldModel::URL_TYPE:
            case CustomPostTypeMetaBoxFieldModel::VIDEO_TYPE:
                $type = 'url';
                break;

            case CustomPostTypeMetaBoxFieldModel::ADDRESS_TYPE:
            case CustomPostTypeMetaBoxFieldModel::COLOR_TYPE:
            case CustomPostTypeMetaBoxFieldModel::CURRENCY_TYPE:
            case CustomPostTypeMetaBoxFieldModel::DATE_TYPE:
            case CustomPostTypeMetaBoxFieldModel::DATE_RANGE_TYPE:
            case CustomPostTypeMetaBoxFieldModel::EDITOR_TYPE:
            case CustomPostTypeMetaBoxFieldModel::EMAIL_TYPE:
            case CustomPostTypeMetaBoxFieldModel::EMBED_TYPE:
            case CustomPostTypeMetaBoxFieldModel::HTML_TYPE:
            case CustomPostTypeMetaBoxFieldModel::LENGTH_TYPE:
            case CustomPostTypeMetaBoxFieldModel::LIST_TYPE:
            case CustomPostTypeMetaBoxFieldModel::NUMBER_TYPE:
            case CustomPostTypeMetaBoxFieldModel::PHONE_TYPE:
            case CustomPostTypeMetaBoxFieldModel::SELECT_TYPE:
            case CustomPostTypeMetaBoxFieldModel::SELECT_MULTI_TYPE:
            case CustomPostTypeMetaBoxFieldModel::RANGE_TYPE:
            case CustomPostTypeMetaBoxFieldModel::RATING_TYPE:
            case CustomPostTypeMetaBoxFieldModel::TEXT_TYPE:
            case CustomPostTypeMetaBoxFieldModel::TEXTAREA_TYPE:
            case CustomPostTypeMetaBoxFieldModel::TIME_TYPE:
            case CustomPostTypeMetaBoxFieldModel::WEIGHT_TYPE:
                $type = 'text';
                break;

            default:
                $type = 'any';
        }

        return $type;
    }

    /**
     * @param string $meta_value
     * @param string $meta_key
     * @param int $post_id
     *
     * @return string|null
     */
    public static function get_value($meta_value, $meta_key, $post_id )
    {
        try {
            $metaFieldModel = MetaRepository::getMetaField([
            	'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
            	'id' => $meta_key,
            ]);

            if($metaFieldModel === null){
                return null;
            }

            return self::get_textual_value_for_field($metaFieldModel, $post_id);
        } catch (\Exception $exception){
            return $meta_value;
        }
    }

    /**
     * Return a textual value for the field
     *
     * @param AbstractMetaBoxFieldModel $metaFieldModel
     * @param integer           $post_id
     *
     * @return string|array|null
     */
    private static function get_textual_value_for_field(AbstractMetaBoxFieldModel $metaFieldModel, $post_id)
    {
        $meta_value = get_acpt_field([
            'post_id' => (int)$post_id,
            'box_name' => $metaFieldModel->getMetaBox()->getName(),
            'field_name' => $metaFieldModel->getName(),
        ]);

        if(empty($meta_value)){
            return null;
        }

        $fieldType = $metaFieldModel->getType();

        switch ($fieldType){

            case CustomPostTypeMetaBoxFieldModel::ADDRESS_TYPE:
                return $meta_value['address'];

            case CustomPostTypeMetaBoxFieldModel::CURRENCY_TYPE:
                return $meta_value['amount']. ' ' . $meta_value['unit'];

//            case CustomPostTypeMetaBoxFieldModel::GALLERY_TYPE:
//                $ids = [];
//
//                foreach ($meta_value as $image){
//                    if(isset($image['id'])){
//                        $ids[] = $image['id'];
//                    }
//                }
//
//                return implode(',', $ids);

		    case CustomPostTypeMetaBoxFieldModel::DATE_RANGE_TYPE:
		    	return implode(' - ', $meta_value);

            case CustomPostTypeMetaBoxFieldModel::FILE_TYPE:
            case CustomPostTypeMetaBoxFieldModel::IMAGE_TYPE:
            case CustomPostTypeMetaBoxFieldModel::VIDEO_TYPE:
                return $meta_value['src'];

		    case CustomPostTypeMetaBoxFieldModel::RATING_TYPE:
			    return Strings::renderStars($meta_value);

            case CustomPostTypeMetaBoxFieldModel::WEIGHT_TYPE:
                return $meta_value['weight']. ' ' . $meta_value['unit'];

            case CustomPostTypeMetaBoxFieldModel::LENGTH_TYPE:
                return $meta_value['length']. ' ' . $meta_value['unit'];

            case CustomPostTypeMetaBoxFieldModel::LIST_TYPE:
                return implode(PHP_EOL, $meta_value);

            case CustomPostTypeMetaBoxFieldModel::CHECKBOX_TYPE:
            case CustomPostTypeMetaBoxFieldModel::SELECT_MULTI_TYPE:
                return (implode(",", $meta_value));

            case CustomPostTypeMetaBoxFieldModel::URL_TYPE:
                return $meta_value['url'];

            default:
                return $meta_value;
        }
    }

    /**
     * Add Dynamic Content support for Images field of Gallery module.
     *
     * @param array $modules Modules list.
     *
     * @return array Filtered modules list.
     */
    public static function add_dynamic_support_for_gallery_field( $modules )
    {
        if ( empty( $modules['et_pb_gallery'] ) ) {
            return $modules;
        }

        $module = $modules['et_pb_gallery'];

        if ( ! isset( $module->fields_unprocessed ) ) {
            return $modules;
        }

        if ( ! empty( $module->fields_unprocessed['gallery_ids'] ) ) {
            $module->fields_unprocessed['gallery_ids']['dynamic_content'] = 'image';
        }

        $modules['et_pb_gallery'] = $module;

        return $modules;
    }
}