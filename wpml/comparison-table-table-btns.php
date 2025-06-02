<?php
/**
 * Comparison Table integration
 */
namespace Happy_Addons\Elementor\Wpml;

defined( 'ABSPATH' ) || die();

class Comparison_Table_Table_Btns extends \WPML_Elementor_Module_With_Items  {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'table_btns';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return [
			'btn_title',
			'link' => ['url']
		];
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'btn_title':
				return __( 'Comparison Table: Title', 'happy-elementor-addons' );
			case 'url':
				return __( 'Comparison Table: Link', 'happy-elementor-addons' );
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
			case 'btn_title':
				return 'LINE';
			case 'url':
				return 'LINK';
			default:
				return '';
		}
	}
}
