<?php
/**
 * Image Stack Group integration
 */
namespace Happy_Addons\Elementor\Wpml;

defined( 'ABSPATH' ) || die();

class Image_Stack_Group_Images extends \WPML_Elementor_Module_With_Items  {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'images';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return [
			'tooltip',
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
			case 'tooltip':
				return __( 'Image Stack Group: Tooltip', 'happy-elementor-addons' );
			case 'url':
				return __( 'Image Stack Group: Link', 'happy-elementor-addons' );
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
			case 'tooltip':
				return 'LINE';
			case 'url':
				return 'LINK';
			default:
				return '';
		}
	}
}
