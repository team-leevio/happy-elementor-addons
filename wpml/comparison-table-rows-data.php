<?php
/**
 * Comparison Table integration
 */
namespace Happy_Addons\Elementor\Wpml;

defined( 'ABSPATH' ) || die();

class Comparison_Table_Rows_Data extends \WPML_Elementor_Module_With_Items  {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'rows_data';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return [
			'column_text',
			'row_content'
		];
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'column_text':
				return __( 'Comparison Table: Title', 'happy-elementor-addons' );
			case 'row_content':
				return __( 'Comparison Table: Show Content', 'happy-elementor-addons' );
			default:
				return '';
		}
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'column_text':
				return 'LINE';
			case 'row_content':
				return 'VISUAL';
			default:
				return '';
		}
	}
}

