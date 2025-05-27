<?php
/**
 * Photo Stack integration
 */
namespace Happy_Addons\Elementor\Wpml;

defined( 'ABSPATH' ) || die();

class Photo_Stack_Image_List extends \WPML_Elementor_Module_With_Items  {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'image_list';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return [
			'link' => ['url'],
		];
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'url':
				return __( 'Photo Stack: Link', 'happy-elementor-addons' );
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
			case 'url':
				return 'LINK';
			default:
				return '';
		}
	}
}
