<?php
/**
 * Sperse Form Fields
 *
 * @author      sperse
 * @category    Core
 * @package     Automate\Includes\Form Fields
 * @version     1.2.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'AWP_Form_Fields' ) ) {


	class AWP_Form_Fields {
		
		// Form name
		public $form_name = null;
		// Form ID
		public $form_id = null;
		
		public $form_action = '';

		protected $form_method = 'post';		
		
		public function __construct($app_name) {

			$this->form_name = $app_name.'_save_form';
			$this->form_id = $app_name.'_save_form';

		}

		public function set_form_method( $method ) {
			$this->form_method = $method;
		}

		public function set_form_action( $action ) {
			$this->form_action = $action;
		}

		/**
		 * Form header getter.
		 *
		 * @return html Generate form header html.
		 */
		public function get_form_header() {
			$this->form_action = admin_url('admin-post.php');
			$form_header = '<form enctype="multipart/form-data" method="' . esc_attr($this->form_method) . '" action="' . esc_url( $this->form_action ) . '" ';			
			if ( isset( $this->form_name ) && ! empty( $this->form_name ) ) {
				$form_header .= ' name="' . esc_attr( $this->form_name ) . '" '; }
			if ( isset( $this->form_id ) && ! empty( $this->form_id ) ) {
				$form_header .= ' id="' . esc_attr( $this->form_id ) . '" '; }
			$form_header .= '>';
			$form_header .= '<table class="form-table">';
			return $form_header;

		}
		
		/**
		 * Form footer getter.
		 *
		 * @return html Generate form footer html.
		 */
		public function get_form_footer() {

			$form_footer = '</table>';
			$form_footer .='<div class="submit-button-plugin"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></div>';
			$form_footer .= '</form>';
			return $form_footer;
		}
		/**
		 * echo  form html
		 */
		public function render($fields_markup) {
			$form_output = '';
			if(empty($fields_markup)){
				$form_output .= 'Please add form field first!';
			}
			$form_header = $this->get_form_header();
			$form_html = $form_header . $fields_markup . $this->get_form_footer();
			$form_output .= $form_html;
			$form_output = balanceTags( $form_output );
			echo $form_output;

			
		}

		/**
		 * Output a text input box.
		 *
		 * @param array $field
		 */
		function awp_wp_text_input( $field ) {
			global $post;

			$field['placeholder']   = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
			$field['class']         = isset( $field['class'] ) ? $field['class'].' basic-text' : 'short basic-text';
			$field['style']         = isset( $field['style'] ) ? $field['style'] : '';
			$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
			$field['value']         = isset( $field['value'] ) ? $field['value'] : '';
			$field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];
			$field['type']          = isset( $field['type'] ) ? $field['type'] : 'text';
			$field['desc_tip']      = isset( $field['desc_tip'] ) ? $field['desc_tip'] : false;
			$field['show_copy_icon']      = isset( $field['show_copy_icon'] ) ? $field['show_copy_icon'] : false;

			$data_type              = empty( $field['data_type'] ) ? '' : $field['data_type'];

			switch ( $data_type ) {
				case 'url':
					$field['class'] .= ' wc_input_url';
					$field['value']  = esc_url( $field['value'] );
					break;

				default:
					break;
			}

			// Custom attribute handling
			$custom_attributes = array();

			if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {

				foreach ( $field['custom_attributes'] as $attribute => $value ) {
					$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
				}
			}


			$form_element = '<tr valign="top" class="' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '">';

			$form_element .=  '<th scope="row">' . esc_attr( $field['label'] ) . '</th>';
			$form_element .= '   <td>
			<div class="form-table__input-wrap">';

			$copy_icon =  AWP_ASSETS.'/images/copy.png';

			$form_element .= '<input type="' . esc_attr( $field['type'] ) . '" class="' . esc_attr( $field['class'] ) . '" style="' . esc_attr( $field['style'] ) . '" name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['value'] ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" ' . implode( ' ', $custom_attributes ) . ' /> ';
			if($field['show_copy_icon'] ){
				$form_element .= '<span class="spci_btn" data-clipboard-action="copy" data-clipboard-target="#'.esc_attr( $field['id'] ).'"><img src="'.esc_url($copy_icon).'" alt="Copy to clipboard"></span>';
			}
			$form_element .= '</div>';
			if ( ! empty( $field['description'] ) && false !== $field['desc_tip'] ) {
				$form_element .= '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
			}
			$form_element .= '</td>';
			$form_element .= '</tr>';

			return $form_element;
		}

		/**
		 * Output a hidden input box.
		 *
		 * @param array $field
		 */
		function awp_wp_hidden_input( $field ) {
			global $post;

			
			$field['value'] = isset( $field['value'] ) ? $field['value'] : '';
			$field['class'] = isset( $field['class'] ) ? $field['class'] : '';
			$field['id'] = isset( $field['id'] ) ? $field['id'] : '';

			return '<input type="hidden" class="' . esc_attr( $field['class'] ) . '" name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['value'] ) . '" /> ';
		}

		/**
		 * Output a textarea input box.
		 *
		 * @param array $field
		 */
		function awp_wp_textarea_input( $field ) {
			global $post;


			$field['placeholder']   = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
			$field['class']         = isset( $field['class'] ) ? $field['class'] : 'short';
			$field['style']         = isset( $field['style'] ) ? $field['style'] : '';
			$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
			$field['value']         = isset( $field['value'] ) ? $field['value'] : '';
			$field['desc_tip']      = isset( $field['desc_tip'] ) ? $field['desc_tip'] : false;
			$field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];
			$field['rows']          = isset( $field['rows'] ) ? $field['rows'] : 2;
			$field['cols']          = isset( $field['cols'] ) ? $field['cols'] : 20;

			// Custom attribute handling
			$custom_attributes = array();

			if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {

				foreach ( $field['custom_attributes'] as $attribute => $value ) {
					$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
				}
			}

			$element = '';

			$element = '<p class="form-field ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '">
				<label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label>';

			if ( ! empty( $field['description'] ) && false !== $field['desc_tip'] ) {
				//$element .= ' wc_help_tip( $field['description'] );
			}

			$element .=  '<textarea class="' . esc_attr( $field['class'] ) . '" style="' . esc_attr( $field['style'] ) . '"  name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" rows="' . esc_attr( $field['rows'] ) . '" cols="' . esc_attr( $field['cols'] ) . '" ' . implode( ' ', $custom_attributes ) . '>' . esc_textarea( $field['value'] ) . '</textarea> ';

			if ( ! empty( $field['description'] ) && false === $field['desc_tip'] ) {
				$element .=  '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
			}

			$element .=  '</p>';
		}

		/**
		 * Output a checkbox input box.
		 *
		 * @param array $field
		 */
		function awp_wp_checkbox( $field ) {
			global  $post;

	
			$field['class']         = isset( $field['class'] ) ? $field['class'] : 'checkbox';
			$field['style']         = isset( $field['style'] ) ? $field['style'] : '';
			$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
			$field['value']         = isset( $field['value'] ) ? $field['value'] : '';
			$field['cbvalue']       = isset( $field['cbvalue'] ) ? $field['cbvalue'] : 'yes';
			$field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];
			$field['desc_tip']      = isset( $field['desc_tip'] ) ? $field['desc_tip'] : false;

			// Custom attribute handling
			$custom_attributes = array();

			if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {

				foreach ( $field['custom_attributes'] as $attribute => $value ) {
					$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
				}
			}

			$element = '';

			$element .=  '<tr valign="top" class=" ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '">
			<th scope="row"><label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label></th>';

			$element .= '<td><input type="checkbox" class="' . esc_attr( $field['class'] ) . '" style="' . esc_attr( $field['style'] ) . '" name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['cbvalue'] ) . '" ' . checked( $field['value'], $field['cbvalue'], false ) . '  ' . implode( ' ', $custom_attributes ) . '/> ';

			if ( ! empty( $field['description'] ) && false === $field['desc_tip'] ) {
				$element .= '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
			}

			$element .= '</td></tr>';
			return $element;
		}

		function awp_implode_html_attributes( $raw_attributes ) {
			$attributes = array();
			foreach ( $raw_attributes as $name => $value ) {
				$attributes[] = esc_attr( $name ) . '="' . esc_attr( $value ) . '"';
			}
			return implode( ' ', $attributes );
		}


		/**
		 * Output a select input box.
		 *
		 * @param array $field Data about the field to render.
		 */
		function awp_wp_select( $field ) {
			global  $post;
			
			$field['value']         = isset( $field['value'] ) ? $field['value'] : '';
			$field     = wp_parse_args(
				$field, array(
					'class'             => 'select short',
					'style'             => '',
					'wrapper_class'     => '',
					'value'             => $field['value'],
					'name'              => $field['id'],
					'desc_tip'          => false,
					'custom_attributes' => array(),
				)
			);

			$wrapper_attributes = array(
				'class' => $field['wrapper_class'] . " form-field {$field['id']}_field",
			);

			$label_attributes = array(
				'for' => $field['id'],
			);

			$field_attributes          = (array) $field['custom_attributes'];
			$field_attributes['style'] = $field['style'];
			$field_attributes['id']    = $field['id'];
			$field_attributes['name']  = $field['name'];
			$field_attributes['class'] = $field['class'];

			$tooltip     = ! empty( $field['description'] ) && false !== $field['desc_tip'] ? $field['description'] : '';
			$description = ! empty( $field['description'] ) && false === $field['desc_tip'] ? $field['description'] : '';
			
			$element = '<tr valign="top" '.$this->awp_implode_html_attributes( $wrapper_attributes ).'>
			<th scope="row"><label '.$this->awp_implode_html_attributes( $label_attributes ).'>'.wp_kses_post( $field['label'] ).'</label></th>';

			$element .= '<td><div class="form-table__input-wrap"><select '.$this->awp_implode_html_attributes( $field_attributes ).'>';		
			foreach ( $field['options'] as $key => $value ) {
				$element .= '<option value="' . esc_attr( $key ) . '"' . wc_selected( $key, $field['value'] ) . '>' . esc_html( $value ) . '</option>';
			}
			$element .= '</select>';
			if ( $description ) : 
				$element .= '<span class="description">'.wp_kses_post( $description ).'</span>';
			endif; 
			$element .= ' </div>
			</td></tr>';

			return $element;

		}

	/**
	 * Output a radio input box.
	 *
	 * @param array $field
	 */
	function awp_wp_radio( $field ) {
		global $post;

	
		$field['class']         = isset( $field['class'] ) ? $field['class'] : 'select short';
		$field['style']         = isset( $field['style'] ) ? $field['style'] : '';
		$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
		$field['value']         = isset( $field['value'] ) ? $field['value'] : '';
		$field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];
		$field['desc_tip']      = isset( $field['desc_tip'] ) ? $field['desc_tip'] : false;
		$elements = '<tr valign="top" class="form-field ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '"><fieldset ><th scope="row">' . wp_kses_post( $field['label'] ) . '</th>';

		// if ( ! empty( $field['description'] ) && false !== $field['desc_tip'] ) {
		// 	echo wc_help_tip( $field['description'] );
		// }

			$elements .=  '<td><ul class="wc-radios">';

			foreach ( $field['options'] as $key => $value ) {

				$elements .=  '<li><label><input
						name="' . esc_attr( $field['name'] ) . '"
						value="' . esc_attr( $key ) . '"
						type="radio"
						class="' . esc_attr( $field['class'] ) . '"
						style="' . esc_attr( $field['style'] ) . '"
						' . checked( esc_attr( $field['value'] ), esc_attr( $key ), false ) . '
						/> ' . esc_html( $value ) . '</label>
				</li>';
			}
			$elements .=  '</ul>';

			if ( ! empty( $field['description'] ) && false === $field['desc_tip'] ) {
				$elements .=  '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
			}

			$elements .=  '
			</td></fieldset></tr>';
			return $elements;
		}

	
		
	}
}










