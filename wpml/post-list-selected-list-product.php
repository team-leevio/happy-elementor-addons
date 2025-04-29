<?php
/**
 * Post List integration
 */
namespace Happy_Addons\Elementor\Wpml;

defined( 'ABSPATH' ) || die();

class Post_List_Selected_List_Product extends \WPML_Elementor_Module_With_Items  {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'selected_list_product';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return [
			'title',
		];
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'title':
				return __( 'Post List: Title', 'happy-elementor-addons' );
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
			case 'title':
				return 'LINE';
			default:
				return '';
		}
	}
}
