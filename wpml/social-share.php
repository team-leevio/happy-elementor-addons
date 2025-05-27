<?php
/**
 * Social Share integration
 */
namespace Happy_Addons\Elementor\Wpml;

defined( 'ABSPATH' ) || die();

class Social_Share extends \WPML_Elementor_Module_With_Items  {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'icon_list';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return [
			'hashtags',
			'share_title',
			'email_subject',
			'share_text',
			'custom_link' => ['url']
		];
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'hashtags':
				return __( 'Social Share: Hashtags', 'happy-elementor-addons' );
			case 'share_title':
				return __( 'Social Share: Custom Title', 'happy-elementor-addons' );
			case 'email_subject':
				return __( 'Social Share: Subject', 'happy-elementor-addons' );
			case 'share_text':
				return __( 'Social Share: Button Text', 'happy-elementor-addons' );
			case 'url':
				return __( 'Social Share: Custom Link', 'happy-elementor-addons' );
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
			case 'hashtags':
				return 'AREA';
			case 'share_title':
				return 'AREA';
			case 'email_subject':
				return 'LINE';
			case 'share_text':
				return 'LINE';
			case 'url':
				return 'LINK';
			default:
				return '';
		}
	}
}
