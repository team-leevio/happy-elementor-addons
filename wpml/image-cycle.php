<?php
/**
 * Image Cycle integration
 */
namespace Happy_Addons\Elementor\Wpml;

defined( 'ABSPATH' ) || die();

class Image_Cycle extends \WPML_Elementor_Module_With_Items  {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'ha_ic_images';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return [
			'ha_ic_link' => ['url']
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
				return __( 'Image Cycle: Image Link', 'happy-elementor-addons' );
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
