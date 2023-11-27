<?php

namespace ACPT\Integrations\Oxygen\Provider;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Repository\OptionPageRepository;
use ACPT\Costants\MetaTypes;
use ACPT\Integrations\Oxygen\Provider\Helper\OxygenDataKey;
use ACPT\Utils\Wordpress\WPAttachment;

class OxygenDataProvider
{
	/**
	 * @param $dynamicData
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function initDynamicData($dynamicData)
	{
		global $post;

		$postType = isset($post->post_type) ? $post->post_type : false;
		$templateType = isset($post->ID) ? get_post_meta($post->ID, 'ct_template_type', true) : false;
		$queriedObject = get_queried_object();

		$fields = [];

		if( 'ct_template' === $postType && $templateType != 'reusable_part'){
			$fields = $this->getMetaForTemplate($post->ID, 'ct_template_post_types', $fields);
			$fields = $this->getMetaForTemplate($post->ID, 'ct_template_archive_post_types', $fields);
			$fields = $this->getMetaForTemplate($post->ID, 'ct_template_archive_among_taxonomies', $fields, MetaTypes::TAXONOMY);
		} elseif($queriedObject instanceof \WP_Term){
			$fields = array_merge( $fields,  get_acpt_tax_meta_objects($queriedObject->taxonomy));
		} else {
			$fields = array_merge( $fields,  get_acpt_meta_objects($post->post_type));
		}

		// add option page fields
		$optionPages = OptionPageRepository::get([]);
		foreach ($optionPages as $optionPage){
			$fields = array_merge( $fields,  get_acpt_option_page_meta_objects($optionPage->getMenuSlug()));

			foreach ($optionPage->getChildren() as $childPage){
				$fields = array_merge( $fields,  get_acpt_option_page_meta_objects($childPage->getMenuSlug()));
			}
		}

		$fields = array_unique( $fields, SORT_REGULAR );

		// Generate the settings for each field type
		$allOptions = array_reduce( $fields, [$this, "addButton"], [] );

		if( count( $allOptions ) > 0 ) {
			array_unshift( $allOptions, [
				'name' => __( 'Select the ACPT meta field', 'oxygen-acpt' ),
				'type' => 'heading'
			] );

			$dynamicData[] = [
				'name'       => __( 'ACPT Field', 'oxygen-acpt' ),
				'mode'       => 'content',
				'position'   => 'Post',
				'data'       => 'acpt_content',
				'handler'    => [$this, 'dynamicDataContentHandler'],
				'properties' => $allOptions,
			];
		}

		$optionsForUrl = array_reduce( $fields, [$this, "addUrlButton" ], [] );

		if( count( $optionsForUrl ) > 0 ) {
			$image = [
				'name' => __( 'ACPT field', 'oxygen-acpt' ),
				'mode' => 'image',
				'position' => 'Post',
				'data' => 'acpt_image',
				'handler' => [$this, 'dynamicDataUrlHandler'],
				'properties' => $optionsForUrl
			];

			$dynamicData[] = $image;

			$link = [
				'name' => __( 'ACPT field', 'oxygen-acpt' ),
				'mode' => 'link',
				'position' => 'Post',
				'data' => 'acpt_link',
				'handler' => [$this, 'dynamicDataUrlHandler'],
				'properties' => $optionsForUrl
			];
			
			$dynamicData[] = $link;

			$customField = [
				'name' => __( 'ACPT field', 'oxygen-acpt' ),
				'mode' => 'custom-field',
				'position' => 'Post',
				'data' => 'acpt_custom_field',
				'handler' => [$this, 'dynamicDataUrlHandler'],
				'properties' => $optionsForUrl
			];
			
			$dynamicData[] = $customField;
		}

		$optionsForImageId = array_reduce( $fields, [$this, "addImageIdButton"], [] );

		if( count( $optionsForImageId ) > 0 ) {
			$imageIdField = [
				'name' => __( 'ACPT field', 'oxygen' ),
				'mode' => 'image-id',
				'position' => 'Post',
				'data' => 'acpt_image_id',
				'handler' => [$this, 'dynamicDataImageIdHandler'],
				'properties' => $optionsForImageId
			];

			$dynamicData[] = $imageIdField;
		}

		return $dynamicData;
	}

	/**
	 * @param $postId
	 * @param $template
	 * @param $fields
	 * @param $belongsTo
	 *
	 * @return array
	 */
	private function getMetaForTemplate($postId, $template, &$fields, $belongsTo = MetaTypes::CUSTOM_POST_TYPE)
	{
		$records = get_post_meta( $postId, $template, true );

		if( is_array( $records ) and !empty($records) ) {
			foreach ( $records as $record ) {
				switch ($belongsTo){

					case MetaTypes::TAXONOMY:
						$taxonomy = get_term($record);
						if($taxonomy){
							$fields = array_merge( $fields, get_acpt_tax_meta_objects($taxonomy->taxonomy) );
						}
						break;

					default:
					case MetaTypes::CUSTOM_POST_TYPE:
						$fields = array_merge( $fields, get_acpt_meta_objects($record) );
						break;
				}
			}
		}

		return $fields;
	}

	/**
	 * @param array $result
	 * @param \stdClass $metaBox
	 *
	 * @return array
	 */
	public function addButton( $result, $metaBox )
	{
		$invalidFieldTypes = [
			CustomPostTypeMetaBoxFieldModel::REPEATER_TYPE,
			CustomPostTypeMetaBoxFieldModel::POST_TYPE,
		];

		$settingsPage = (isset($metaBox->option_page)) ? $metaBox->option_page : null;

		foreach ($metaBox->fields as $field){

			$properties = [];

			// $properties
			switch ($field->field_type){

				case CustomPostTypeMetaBoxFieldModel::CHECKBOX_TYPE:
				case CustomPostTypeMetaBoxFieldModel::SELECT_MULTI_TYPE:
				case CustomPostTypeMetaBoxFieldModel::LIST_TYPE:
					$properties[] = [
						'name'      => __( 'How the list should be displayed?', 'oxygen-acpt' ),
						'data'      => 'list_type',
						'type'      => 'select',
						'options'   => [
							__( 'List', 'oxygen-acpt' ) =>'list',
							__( 'Comma separated', 'oxygen-acpt' ) => 'comma_separated',
						],
						'nullval'   => 'list'
					];
					break;

				case CustomPostTypeMetaBoxFieldModel::DATE_RANGE_TYPE:
				case CustomPostTypeMetaBoxFieldModel::DATE_TYPE:
					if( !isset( $label ) ) $label = __( 'PHP Date Format. Defaults to Y-m-d', 'oxygen-acpt' );
					$properties[] = [
						'name' => $label,
						'data' => 'format',
						'type' => 'text'
					];
					break;

				case CustomPostTypeMetaBoxFieldModel::EMAIL_TYPE:
					$properties[] = [
						'name'      => __( 'Please select what you want to insert', 'oxygen-acpt' ),
						'data'      => 'insert_type',
						'type'      => 'select',
						'options'   => [
							__( 'Email link', 'oxygen-acpt' ) =>'email_link',
							__( 'Email URL', 'oxygen-acpt' ) => 'email_url',
						],
						'nullval'   => 'email_link'
					];
					break;

				case CustomPostTypeMetaBoxFieldModel::EMBED_TYPE:
					$properties[] = [
						'name' => __( 'Width', 'oxygen-acpt' ),
						'data' => 'width',
						'type' => 'text',
					];
					$properties[] = [
						'name' => __( 'Height', 'oxygen-acpt' ),
						'data' => 'height',
						'type' => 'text',
					];
					break;

				case CustomPostTypeMetaBoxFieldModel::FILE_TYPE:
				case CustomPostTypeMetaBoxFieldModel::URL_TYPE:
					$properties[] = [
						'name'      => __( 'Please select the target link', 'oxygen-acpt' ),
						'data'      => 'target_link',
						'type'      => 'select',
						'options'   => [
							__( 'Opens in a new window or tab', 'oxygen-acpt' ) =>'_blank',
							__( 'Opens in the same frame as it was clicked', 'oxygen-acpt' ) => '_self',
							__( 'Opens in the parent frame', 'oxygen-acpt' ) => '_parent',
							__( 'Opens in the full body of the window', 'oxygen-acpt' ) => '_top',
						],
						'nullval'   => '_blank'
					];
					break;

				case CustomPostTypeMetaBoxFieldModel::GALLERY_TYPE:
					$properties[] = [
						'name' => __( 'Output type', 'oxygen-acpt' ),
						'data' => 'output_type',
						'type' => 'select',
						'options'=> [
							__( 'Images ID list', 'oxygen-acpt' ) => 'images_id_list',
							__( 'Gallery', 'oxygen-acpt' ) => 'gallery',
						],
						'nullval' => 'gallery'
					];
					$properties[] = [
						'name' => __( 'Elements per row', 'oxygen-acpt' ),
						'data' => 'per_row',
						'type' => 'select',
						'options'=> [
							"1" => "1",
							"2" => "2",
							"3" => "3",
							"4" => "4",
							"6" => "6",
						],
						'show_condition' => 'dynamicDataModel.output_type == \'gallery\''
					];
					$properties[] = [
						'name' => __( 'Separator', 'oxygen-acpt' ),
						'data' => 'separator',
						'type' => 'text',
						'show_condition' => 'dynamicDataModel.output_type == \'images_id_list\''
					];
					break;

				case CustomPostTypeMetaBoxFieldModel::IMAGE_TYPE:
					$properties[] = [
						'name'     => __( 'Please select what you want to insert', 'oxygen-acpt' ),
						'data'      => 'insert_type',
						'type'      => 'select',
						'options'   => [
							__( 'Image element', 'oxygen-acpt' ) =>'image_element',
							__( 'Image URL', 'oxygen-acpt' ) => 'image_url',
							__( 'Image Title', 'oxygen-acpt' ) => 'image_title',
							__( 'Image Caption', 'oxygen-acpt' ) => 'image_caption'
						],
						'nullval'   => 'image_element'
					];
					$properties[] = [
						'name'=> __( 'Size', 'oxygen-acpt' ),
						'data'=> 'size',
						'type'=> 'select',
						'options'=> [
							__( 'Thumbnail', 'oxygen-acpt' ) => 'thumbnail',
							__( 'Medium', 'oxygen-acpt' ) => 'medium',
							__( 'Medium Large', 'oxygen-acpt' ) => 'medium_large',
							__( 'Large', 'oxygen-acpt' ) => 'large',
							__( 'Original', 'oxygen-acpt' ) => 'full'
						],
						'nullval' => 'medium',
						'change'=> 'scope.dynamicDataModel.width = ""; scope.dynamicDataModel.height = ""',
						'show_condition' => "dynamicDataModel.insert_type == 'image_element'"
					];
					$properties[] = [
						'name' => __( 'or', 'oxygen-acpt' ),
						'type' => 'label',
						'show_condition' => 'dynamicDataModel.insert_type == \'image_element\''
					];
					$properties[] = [
						'name' => __( 'Width', 'oxygen-acpt' ),
						'data' => 'width',
						'type' => 'text',
						'helper'=> true,
						'change' => "scope.dynamicDataModel.size = scope.dynamicDataModel.width+'x'+scope.dynamicDataModel.height",
						'show_condition' => "dynamicDataModel.insert_type == 'image_element'"
					];
					$properties[] = [
						'name' => __( 'Height', 'oxygen-acpt' ),
						'data' => 'height',
						'type' => 'text',
						'helper' => true,
						'change' => "scope.dynamicDataModel.size = scope.dynamicDataModel.width+'x'+scope.dynamicDataModel.height",
						'show_condition' => 'dynamicDataModel.insert_type == \'image_element\''
					];
					break;

				case CustomPostTypeMetaBoxFieldModel::PHONE_TYPE:
					$properties[] = [
						'name'      => __( 'How the phone should be displayed?', 'oxygen-acpt' ),
						'data'      => 'phone_type',
						'type'      => 'select',
						'options'   => [
							__( 'Link', 'oxygen-acpt' ) =>'link',
							__( 'Text', 'oxygen-acpt' ) => 'text',
						],
						'nullval'   => 'text'
					];
					break;

				case CustomPostTypeMetaBoxFieldModel::VIDEO_TYPE:
					$properties[] = [
						'name'     => __( 'Please select what you want to insert', 'oxygen-acpt' ),
						'data'      => 'insert_type',
						'type'      => 'select',
						'options'   => [
							__( 'Video element', 'oxygen-acpt' ) =>'video_element',
							__( 'Video URL', 'oxygen-acpt' ) => 'video_url',
							__( 'Video Title', 'oxygen-acpt' ) => 'video_title',
							__( 'Video Caption', 'oxygen-acpt' ) => 'video_caption'
						],
						'nullval'   => 'video_element'
					];
					$properties[] = [
						'name' => __( 'Width', 'oxygen-acpt' ),
						'data' => 'width',
						'type' => 'text',
						'change' => "scope.dynamicDataModel.size = scope.dynamicDataModel.width+'x'+scope.dynamicDataModel.height",
						'show_condition' => "dynamicDataModel.insert_type == 'video_element'"
					];
					$properties[] = [
						'name' => __( 'Height', 'oxygen-acpt' ),
						'data' => 'height',
						'type' => 'text',
						'change' => "scope.dynamicDataModel.size = scope.dynamicDataModel.width+'x'+scope.dynamicDataModel.height",
						'show_condition' => 'dynamicDataModel.insert_type == \'video_element\''
					];
					break;

				default:
					$properties[] = [
						'name' => __( 'Include prepend and append text (if configured)', 'oxygen-acpt' ),
						'data' => 'include_prepend_append',
						'type' => 'checkbox',
						'value' => 'yes'
					];
					break;
			}

			if( !in_array( $field->field_type, $invalidFieldTypes ) ) {

				$args = [
					'name' => '['.$this->getMetaBoxParentName($metaBox) . '] ' . $metaBox->box_name . ' ' . $field->field_name,
					'data' => OxygenDataKey::encode($metaBox->belongs_to, $this->getMetaBoxParentName($metaBox), $metaBox->box_name, $field->field_name),
					'type' => 'button',
					'properties' => $properties,
				];

				if($settingsPage !== null){
					$args['settings_page'] = $settingsPage;
				}

				$result[] = $args;
			}
		}

		return $result;
	}

	/**
	 * @param $metaBox
	 *
	 * @return string
	 */
	private function getMetaBoxParentName($metaBox)
	{
		if(isset($metaBox->post_type)){
			return $metaBox->post_type;
		}

		if(isset($metaBox->taxonomy)){
			return $metaBox->taxonomy;
		}

		return $metaBox->option_page;
	}

	/**
	 * This function returns the meta field output
	 *
	 * @param $atts
	 *
	 * @return string|null
	 * @throws \Exception
	 */
	public function dynamicDataContentHandler($atts)
	{
		global $wpdb;

		$rawMetaValue = $this->getRawMetaValue($atts);

		if($rawMetaValue === null){
			return null;
		}

		$fieldObject = $rawMetaValue['fieldObject'];
		$rawValue =  $rawMetaValue['rawValue'];

		if(empty($rawValue) or $rawValue === null){
			return null;
		}

		switch ($fieldObject->field_type){

			case CustomPostTypeMetaBoxFieldModel::CHECKBOX_TYPE:
			case CustomPostTypeMetaBoxFieldModel::LIST_TYPE:
			case CustomPostTypeMetaBoxFieldModel::SELECT_MULTI_TYPE:

				if($rawValue === null or $rawValue === '' or empty($rawValue)){
					return null;
				}

				$listType = isset($atts['list_type']) ? $atts['list_type'] : 'list';

				switch( $listType ){
					case "comma_separated":
						return implode(", ", $rawValue);

					default:
					case "list":

						$output = "<ul>";
						foreach ($rawValue as $item){
							$output .= "<li>".$item."</li>";
						}
						$output .= "</ul>";

						return $output;
				}

			case CustomPostTypeMetaBoxFieldModel::CURRENCY_TYPE:

				if(!isset($rawValue['amount']) and !isset($rawValue['unit'])){
					return null;
				}

				return $rawValue['amount'] .' '. $rawValue['unit'];

			case CustomPostTypeMetaBoxFieldModel::DATE_TYPE:

				$defaultFormat = 'Y-m-d';
				$date = date_create_from_format( $defaultFormat, $rawValue );

				if ($date) {
					$format = empty( $atts[ 'format' ] ) ? $defaultFormat : $atts[ 'format' ];

					return $date->format( $format );
				}

				return null;

			case CustomPostTypeMetaBoxFieldModel::DATE_RANGE_TYPE:

				$defaultFormat = 'Y-m-d';

				if(empty($rawValue) or $rawValue === null){
					return null;
				}

				$format = empty( $atts[ 'format' ] ) ? $defaultFormat : $atts[ 'format' ];
				$dateStart = date_create_from_format( $defaultFormat, $rawValue[0] );
				$dateEnd = date_create_from_format( $defaultFormat, $rawValue[1] );

				return $dateStart->format($format) . ' - '. $dateEnd->format($format);

			case CustomPostTypeMetaBoxFieldModel::EDITOR_TYPE:
				return do_shortcode($rawValue);

			case CustomPostTypeMetaBoxFieldModel::EMAIL_TYPE:

				$insertType = isset($atts['insert_type']) ? $atts['insert_type'] : 'email_url';

				switch( $insertType ){
					case "email_link":
						return "<a href='mailto:".$rawValue."'>".$rawValue."</a>";

					default:
					case "email_url":
						return $rawValue;
				}

			case CustomPostTypeMetaBoxFieldModel::EMBED_TYPE:

				$width = isset($atts['width']) ? $atts['width'] : "100%";
				$height = isset($atts['height']) ? $atts['height'] : null;

				return (new \WP_Embed())->shortcode([
					'width' => $width,
					'height' => $height,
				], $rawValue);

			case CustomPostTypeMetaBoxFieldModel::FILE_TYPE:

				if(!isset($rawValue['file']) or !$rawValue['file'] instanceof WPAttachment){
					return null;
				}

				if($rawValue['file']->isEmpty()){
					return null;
				}

				$url = $rawValue['file']->getSrc();
				$label = (isset($rawValue['label']) and !empty($rawValue['label'])) ? $rawValue['label'] : $url;

				return "<a href='".$url."' target='".$atts['target_link']."'>".$label."</a>";

			case CustomPostTypeMetaBoxFieldModel::IMAGE_TYPE:

				if($rawValue === null or !$rawValue instanceof WPAttachment){
					return null;
				}

				if($rawValue->isEmpty()){
					return null;
				}

				$imageSize = explode( 'x', empty($atts['size']) ? '' : strtolower($atts['size']) );

				if( count($imageSize) == 1 ){
					$imageSize = $imageSize[0];
				} else{
					$imageSize = array_map( 'intval', $imageSize );
				}

				if( empty( $imageSize ) ) $imageSize = "medium";

				$imageId = $rawValue->getId();
				$imageUrl = wp_get_attachment_image_src( $imageId, $imageSize )[0];
				$imageAttachment = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE ID='%d';", $imageId ),ARRAY_A );

				if( empty( $atts['insert_type'] ) ) $atts['insert_type'] = 'image_element';

				$insertType = isset($atts['insert_type']) ? $atts['insert_type'] : 'image_element';

				switch( $insertType ){
					case "image_element":
						return wp_get_attachment_image($imageId, $imageSize);

					case "image_url":
						return $imageUrl;

					case "image_title":
						return $imageAttachment['post_title'];

					case "image_caption":
						return $imageAttachment['post_excerpt'];

				}

				return null;

			case CustomPostTypeMetaBoxFieldModel::GALLERY_TYPE:

				wp_enqueue_style( 'acpt.oxigen', plugin_dir_url( dirname( __FILE__ ) ) . '../../../assets/static/css/acpt-oxigen.css', array(), '2.2.0', 'all');
				$outputType = isset($atts['output_type']) ? $atts['output_type'] : 'acpt_gallery';
				$separator = isset($atts['separator']) ? $atts['separator'] : ',';
				$perRow = isset($atts['per_row']) ? $atts['per_row'] : 1;

				switch ($outputType){
					case "images_id_list":

						$imageIds = [];
						foreach ($rawValue as $item){
							if($item instanceof WPAttachment){
								$imageIds[] = $item->getId();
							}
						}

						return implode($separator, $imageIds);

					case "acpt_gallery":
					default:
						$output = '<div class="acpt-grid-'.$perRow.'">';
						foreach ($rawValue as $item){
							if($item instanceof WPAttachment){
								$output .= '<div class="item"><img src="'.$item->getSrc().'" alt="'.$item->getAlt().'" /></div>';
							}
						}
						$output .= '</div>';

						return $output;
				}

			case CustomPostTypeMetaBoxFieldModel::LENGTH_TYPE:

				if(!isset($rawValue['length']) and !isset($rawValue['unit'])){
					return null;
				}

				return $rawValue['length'] .' '. $rawValue['unit'];

			case CustomPostTypeMetaBoxFieldModel::NUMBER_TYPE:
				return (int)$rawValue;

			case CustomPostTypeMetaBoxFieldModel::PHONE_TYPE:

				$phoneType = isset($atts['phone_type']) ? $atts['phone_type'] : 'text';

				switch ($phoneType){
					case "link":
						return "<a href='tel:".$rawValue."' target='_blank'>".$rawValue."</a>";

					case "text":
					default:
						return $rawValue;
				}

			case CustomPostTypeMetaBoxFieldModel::URL_TYPE:

				if(!isset($rawValue['url'])){
					return null;
				}

				$url = $rawValue['url'];
				$label = (isset($rawValue['label']) and !empty($rawValue['label'])) ? $rawValue['label'] : $rawValue['url'];

				return "<a href='".$url."' target='".$atts['target_link']."'>".$label."</a>";

			case CustomPostTypeMetaBoxFieldModel::VIDEO_TYPE:

				if($rawValue === null or !$rawValue instanceof WPAttachment){
					return null;
				}

				if($rawValue->isEmpty()){
					return null;
				}

				if( empty( $atts['insert_type'] ) ) $atts['insert_type'] = 'video_element';

				$videoUrl = $rawValue->getSrc();
				$insertType = isset($atts['insert_type']) ? $atts['insert_type'] : 'image_element';

				switch( $insertType ){
					case "video_element":

						$width = isset($atts['width']) ? $atts['width'] : "100%";
						$height = isset($atts['height']) ? $atts['height'] : null;

						return '<video width="'.$width.'" height="'.$height.'" controls>
		                            <source src="'.$videoUrl.'" type="video/mp4">
		                            Your browser does not support the video tag.
		                        </video>';

					case "video_url":
						return $videoUrl;

					case "video_title":
						return $rawValue->getTitle();

					case "video_caption":
						return $rawValue->getCaption();
				}

				return null;

			case CustomPostTypeMetaBoxFieldModel::WEIGHT_TYPE:

				if(!isset($rawValue['weight']) and !isset($rawValue['unit'])){
					return null;
				}

				return $rawValue['weight'] .' '. $rawValue['unit'];

			default:
				return $rawValue;
		}
	}

	/**
	 * @param array $atts
	 *
	 * @return array|null
	 * @throws \Exception
	 */
	private function getRawMetaValue($atts)
	{
		$path = OxygenDataKey::decode($atts['settings_path']);

		if(empty($path)){
			return null;
		}

		$belongsTo = $path['belongs_to'];
		$find = $path['find'];
		$boxName = $path['box_name'];
		$fieldName = $path['field_name'];
		$rawValue = null;

		switch ($belongsTo){
			case MetaTypes::OPTION_PAGE:
				$fieldObject = get_acpt_option_page_field_object($find, $boxName, $fieldName);

				if(is_acpt_option_page_field_visible([
					'option_page' => $find,
					'box_name' => $boxName,
					'field_name' => $fieldName,
				])){
					$rawValue = get_acpt_option_page_field([
						'option_page' => $find,
						'box_name' => $boxName,
						'field_name' => $fieldName,
					]);
				}
				break;

			case MetaTypes::TAXONOMY:
				$fieldObject = get_acpt_tax_field_object($find, $boxName, $fieldName);
				$queriedObject = get_queried_object();

				if(is_acpt_tax_field_visible([
					'term_id' => $queriedObject->term_id,
					'box_name' => $boxName,
					'field_name' => $fieldName,
				])){
					$rawValue = get_acpt_tax_field([
						'term_id' => $queriedObject->term_id,
						'box_name' => $boxName,
						'field_name' => $fieldName,
					]);
				}
				break;

			default:
			case MetaTypes::CUSTOM_POST_TYPE:
				global $post;
				$postType = get_post_type($post->ID);
				$fieldObject = get_acpt_field_object($postType, $boxName, $fieldName);

				if(is_acpt_field_visible([
					'post_id' => $post->ID,
					'box_name' => $boxName,
					'field_name' => $fieldName,
				])){
					$rawValue = get_acpt_field([
						'post_id' => $post->ID,
						'box_name' => $boxName,
						'field_name' => $fieldName,
					]);
				}
				break;
		}

		return [
			'fieldObject' => $fieldObject,
			'rawValue' => $rawValue,
		];
	}

	/**
	 * @param $result
	 * @param $metaBox
	 *
	 * @return array
	 */
	public function addUrlButton( $result, $metaBox )
	{
		$validFieldTypes = [
			CustomPostTypeMetaBoxFieldModel::IMAGE_TYPE,
			CustomPostTypeMetaBoxFieldModel::TEXT_TYPE,
			CustomPostTypeMetaBoxFieldModel::FILE_TYPE,
			CustomPostTypeMetaBoxFieldModel::URL_TYPE,
			CustomPostTypeMetaBoxFieldModel::EMAIL_TYPE,
			CustomPostTypeMetaBoxFieldModel::PHONE_TYPE,
		];

		foreach ($metaBox->fields as $field) {

			$properties = [];
			$settingsPage = (isset($metaBox->option_page)) ? $metaBox->option_page : null;

			// $properties
			switch ($field->field_type){
				case CustomPostTypeMetaBoxFieldModel::IMAGE_TYPE:
					$properties[] = [
						'name'=> __( 'Size', 'oxygen-acpt' ),
						'data'=> 'size',
						'type'=> 'select',
						'options'=> [
							__( 'Thumbnail', 'oxygen-acpt' ) => 'thumbnail',
							__( 'Medium', 'oxygen-acpt' ) => 'medium',
							__( 'Medium Large', 'oxygen-acpt' ) => 'medium_large',
							__( 'Large', 'oxygen-acpt' ) => 'large',
							__( 'Original', 'oxygen-acpt' ) => 'full'
						],
						'nullval' => 'medium',
						'change'=> 'scope.dynamicDataModel.width = ""; scope.dynamicDataModel.height = ""'
					];
					$properties[] = [
						'name' => __( 'or', 'oxygen-acpt' ),
						'type' => 'label',
						'show_condition' => 'dynamicDataModel.insert_type == \'image_element\''
					];
					$properties[] = [
						'name' => __( 'Width', 'oxygen-acpt' ),
						'data' => 'width',
						'type' => 'text',
						'helper'=> true,
						'change' => "scope.dynamicDataModel.size = scope.dynamicDataModel.width+'x'+scope.dynamicDataModel.height",
						'show_condition' => "dynamicDataModel.insert_type == 'image_element'"
					];
					$properties[] = [
						'name' => __( 'Height', 'oxygen-acpt' ),
						'data' => 'height',
						'type' => 'text',
						'helper' => true,
						'change' => "scope.dynamicDataModel.size = scope.dynamicDataModel.width+'x'+scope.dynamicDataModel.height",
						'show_condition' => 'dynamicDataModel.insert_type == \'image_element\''
					];
					break;
			}

			if( in_array( $field->field_type, $validFieldTypes ) ) {

				$args = [
					'name' => '['.$this->getMetaBoxParentName($metaBox) . '] ' . $metaBox->box_name . ' ' . $field->field_name,
					'data' => OxygenDataKey::encode($metaBox->belongs_to, $this->getMetaBoxParentName($metaBox), $metaBox->box_name, $field->field_name),
					'type' => 'button',
					'properties' => $properties,
				];

				if($settingsPage !== null){
					$args['settings_page'] = $settingsPage;
				}

				$result[] = $args;
			}
		}

		return $result;
	}

	/**
	 * This function ALWAYS returns a url
	 *
	 * @param $atts
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function dynamicDataUrlHandler($atts)
	{
		$rawMetaValue = $this->getRawMetaValue($atts);

		if($rawMetaValue === null){
			return null;
		}

		$fieldObject = $rawMetaValue['fieldObject'];
		$rawValue =  $rawMetaValue['rawValue'];

		if(empty($rawValue) or $rawValue === null){
			return null;
		}

		switch ($fieldObject->field_type){
			case CustomPostTypeMetaBoxFieldModel::EMAIL_TYPE:
				return 'mailto:'.$rawValue;

			case CustomPostTypeMetaBoxFieldModel::IMAGE_TYPE:

				if($rawValue === null or !$rawValue instanceof WPAttachment){
					return null;
				}

				if($rawValue->isEmpty()){
					return null;
				}

				$imageSize = explode( 'x', empty($atts['size']) ? '' : strtolower($atts['size']) );

				if( count($imageSize) == 1 ){
					$imageSize = $imageSize[0];
				} else{
					$imageSize = array_map( 'intval', $imageSize );
				}

				if( empty( $imageSize ) ) $imageSize = "medium";

				$imageId = $rawValue->getId();

				return wp_get_attachment_image_src( $imageId, $imageSize )[0];

			case CustomPostTypeMetaBoxFieldModel::FILE_TYPE:
				if(!isset($rawValue['file'])){
					return null;
				}

				if(!$rawValue['file'] instanceof WPAttachment){
					return null;
				}

				return $rawValue['file']->getSrc();

			case CustomPostTypeMetaBoxFieldModel::URL_TYPE:

				if(!isset($rawValue['url'])){
					return null;
				}

				return $rawValue['url'];

			default:
				return $rawValue;
		}
	}

	/**
	 * @param $result
	 * @param $metaBox
	 *
	 * @return array
	 */
	public function addImageIdButton( $result, $metaBox )
	{
		$validFieldTypes = [
			CustomPostTypeMetaBoxFieldModel::IMAGE_TYPE,
		];

		$settingsPage =  (isset($metaBox->option_page)) ? $metaBox->option_page : null;

		foreach ($metaBox->fields as $field) {
			if( in_array( $field->field_type, $validFieldTypes ) ) {

				$args = [
					'name' => '['.$this->getMetaBoxParentName($metaBox) . '] ' . $metaBox->box_name . ' ' . $field->field_name,
					'data' => OxygenDataKey::encode($metaBox->belongs_to, $this->getMetaBoxParentName($metaBox), $metaBox->box_name, $field->field_name),
					'type' => 'button',
					'properties' => [],
				];

				if($settingsPage !== null){
					$args['settings_page'] = $settingsPage;
				}

				$result[] = $args;
			}
		}

		return $result;
	}

	/**
	 * @param $atts
	 *
	 * @return mixed|null
	 * @throws \Exception
	 */
	public function dynamicDataImageIdHandler($atts)
	{
		$rawMetaValue = $this->getRawMetaValue($atts);

		if($rawMetaValue === null){
			return null;
		}

		$fieldObject = $rawMetaValue['fieldObject'];
		$rawValue =  $rawMetaValue['rawValue'];

		if(empty($rawValue) or $rawValue === null){
			return null;
		}

		switch ($fieldObject->field_type){
			case CustomPostTypeMetaBoxFieldModel::IMAGE_TYPE:

				if($rawValue === null or !$rawValue instanceof WPAttachment){
					return null;
				}

				if($rawValue->isEmpty()){
					return null;
				}

				return $rawValue->getId();
		}
	}
}