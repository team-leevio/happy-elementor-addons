<?php
namespace Happy_Addons\Elementor\Extensions;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

defined('ABSPATH') || die();

class Wrapper_Link {

	private static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		 return self::$instance;
	}

	public static function add_controls_section( Element_Base $element) {
		$tabs = Controls_Manager::TAB_CONTENT;

		if ( 'section' === $element->get_name() || 'column' === $element->get_name()  || 'container' === $element->get_name() ) {
			$tabs = Controls_Manager::TAB_LAYOUT;
		}

		$element->start_controls_section(
			'_section_ha_wrapper_link',
			[
				'label' => __( 'Wrapper Link', 'happy-elementor-addons' ) . ha_get_section_icon(),
				'tab'   => $tabs,
			]
		);

		$element->add_control(
			'ha_element_link',
			[
				'label'       => __( 'Link', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => 'https://example.com',
			]
		);

		$element->end_controls_section();
	}

	public static function before_section_render( Element_Base $element ) {
		$link_settings = $element->get_settings_for_display( 'ha_element_link' );
		$link_settings['url'] = esc_url( $link_settings['url'] ?? '' );
		unset( $link_settings['custom_attributes'] );

		if ( $link_settings && ! empty( $link_settings['url'] ) ) {
			$element->add_render_attribute(
				'_wrapper',
				[
					'data-ha-element-link' => json_encode( $link_settings ),
					'style' => 'cursor: pointer'
				]
			);
		}
	}
}
