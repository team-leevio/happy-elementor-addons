<?php
namespace Happy_Addons\Elementor\Extensions\Atomic;

use Happy_Addons\Elementor\Classes\Atomic_Manager;

defined( 'ABSPATH' ) || die();

require_once __DIR__ . '/atomic-feature-base.php';

/**
 * Wrapper Link for the Atomic containers (e-flexbox, e-div-block, e-grid) and
 * the Atomic Heading, Paragraph, Button and Image. The V4 counterpart of
 * extensions/wrapper-link.php.
 *
 * Same settings as Classic: Link, open in a new window, nofollow. The element
 * becomes clickable (happy-atomic-wrapper-link.js) on the frontend only, so it
 * can still be edited in the editor. There is no switch: like Classic, the
 * feature is on as soon as the element has a link.
 */
class Wrapper_Link extends Atomic_Feature_Base {

	protected function prefix() {
		return 'ha_wl_';
	}

	protected function target_type() {
		return 'e-flexbox';
	}

	protected function target_types() {
		return [ 'e-flexbox', 'e-div-block', 'e-grid', 'e-heading', 'e-paragraph', 'e-button', 'e-image' ];
	}

	protected function feature_key() {
		return 'wrapper-link';
	}

	protected function section_label() {
		return __( 'Wrapper Link', 'happy-elementor-addons' );
	}

	protected function matches_schema( array $schema ) {
		return isset( $schema['classes'], $schema['link'] ) && ! isset( $schema['svg'] );
	}

	/**
	 * No switch: the feature is on while the URL is set, so the panel indicator follows it.
	 */
	public function editor_section() {
		return [
			'label'    => $this->section_label(),
			'switcher' => $this->prefix() . 'url',
		];
	}

	protected function fields() {
		return [
			'url'         => [
				'type'        => 'string',
				'default'     => '',
				'label'       => __( 'Link', 'happy-elementor-addons' ),
				'description' => __( 'Make the whole element clickable. Example: https://example.com', 'happy-elementor-addons' ),
			],
			'is_external' => [
				'type'    => 'bool',
				'default' => false,
				'label'   => __( 'Open in new window', 'happy-elementor-addons' ),
			],
			'nofollow'    => [
				'type'    => 'bool',
				'default' => false,
				'label'   => __( 'Add nofollow', 'happy-elementor-addons' ),
			],
		];
	}

	public function before_render( $element ) {
		if ( ! is_object( $element ) || ! method_exists( $element, 'get_atomic_settings' ) || ! method_exists( $element, 'get_element_type' ) ) {
			return;
		}

		if ( ! in_array( $element::get_element_type(), $this->target_types(), true ) ) {
			return;
		}

		$settings = $element->get_atomic_settings();
		$url      = esc_url_raw( trim( (string) ( $settings[ $this->prefix() . 'url' ] ?? '' ) ) );

		if ( '' === $url ) {
			return;
		}

		Atomic_Manager::register_frontend_config( $this->feature_key(), $element->get_id(), [
			'url'         => $url,
			'is_external' => ! empty( $settings[ $this->prefix() . 'is_external' ] ),
			'nofollow'    => ! empty( $settings[ $this->prefix() . 'nofollow' ] ),
		] );
	}
}
