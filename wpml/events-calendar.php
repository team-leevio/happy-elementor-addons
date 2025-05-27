<?php
/**
 * Event Calendar integration
 */
namespace Happy_Addons\Elementor\Wpml;

defined( 'ABSPATH' ) || die();

class Event_Calendar_Manual_Event_List extends \WPML_Elementor_Module_With_Items  {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'manual_event_list';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return [
			'title',
			'guest',
			'location',
			'details_link' => ['url'],
			'description'
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
				return __( 'Event Calendar: Title', 'happy-elementor-addons' );
			case 'guest':
				return __( 'Event Calendar: Guest/Speaker', 'happy-elementor-addons' );
			case 'location':
				return __( 'Event Calendar: Location', 'happy-elementor-addons' );
			case 'url':
				return __( 'Event Calendar: Details Link', 'happy-elementor-addons' );
			case 'description':
				return __( 'Event Calendar: Description', 'happy-elementor-addons' );
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
			case 'guest':
				return 'LINE';
			case 'location':
				return 'LINE';
			case 'url':
				return 'LINK';
			case 'description':
				return 'VISUAL';
			default:
				return '';
		}
	}
}
