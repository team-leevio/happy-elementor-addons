<?php
/**
 * Data Table
 */
namespace Happy_Addons\Elementor\Wpml;

defined( 'ABSPATH' ) || die();

class Data_Table_Column_Data extends \WPML_Elementor_Module_With_Items  {

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
				return __( 'Data Table: Column Name', 'happy-elementor-addons' );
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
			default:
				return '';
		}
	}
}
