<?php
/**
 * Comparison Table integration
 */
namespace Happy_Addons\Elementor\Wpml;

defined( 'ABSPATH' ) || die();

class Comparison_Table_Columns_Data extends \WPML_Elementor_Module_With_Items  {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'columns_data';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return [
			'column_name',
			'head_content'
		];
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'column_name':
				return __( 'Comparison Table: Title', 'happy-elementor-addons' );
			case 'head_content':
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
			case 'column_name':
				return 'LINE';
			case 'head_content':
				return 'VISUAL';
			default:
				return '';
		}
	}
}
