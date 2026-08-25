<?php
namespace Happy_Addons\Elementor\Extensions;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;

defined( 'ABSPATH' ) || die();

class Foreground_Overlay {

	private static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public static function register_scripts() {
		wp_register_script(
			'happy-foreground-overlay',
			HAPPY_ADDONS_ASSETS . 'js/extension-foreground-overlay.min.js',
			[ 'jquery', 'elementor-frontend' ],
			HAPPY_ADDONS_VERSION,
			true
		);
	}

	public static function preview_enqueue_scripts() {
		wp_enqueue_script( 'happy-foreground-overlay' );
	}

	public static function preview_enqueue_styles() {
		wp_register_style( 'happy-foreground-overlay', false, [], HAPPY_ADDONS_VERSION );
		wp_enqueue_style( 'happy-foreground-overlay' );
		wp_add_inline_style(
			'happy-foreground-overlay',
			'.elementor-element.ha-foreground-overlay-active > .elementor-element-overlay{z-index:10000;}'
		);
	}

	public static function before_render( Element_Base $element ) {
		if ( 'container' !== $element->get_name() ) {
			return;
		}

		$settings = $element->get_settings_for_display();

		if ( empty( $settings['_ha_foreground_overlay_type'] ) ) {
			return;
		}

		$element->add_render_attribute( '_wrapper', 'class', 'ha-foreground-overlay-active' );

		if ( 'gradient' !== $settings['_ha_foreground_overlay_type'] ) {
			return;
		}

		$areas = [
			'left'   => [ 'start', 0, 5 ],
			'center' => [ 'center', 5, 95 ],
			'right'  => [ 'end', 95, 100 ],
		];

		$style = '';

		foreach ( $areas as $position => $area ) {
			$value = $element->get_settings_for_display( '_ha_foreground_overlay_' . $area[0] . '_area' );

			$from = $area[1];
			$to   = $area[2];

			if ( is_array( $value ) && isset( $value['sizes']['from'] ) && '' !== $value['sizes']['from'] && null !== $value['sizes']['from'] ) {
				$from = $value['sizes']['from'];
			}

			if ( is_array( $value ) && isset( $value['sizes']['to'] ) && '' !== $value['sizes']['to'] && null !== $value['sizes']['to'] ) {
				$to = $value['sizes']['to'];
			}

			$style .= "--fg-{$position}-start: {$from}%; --fg-{$position}-end: {$to}%;";
		}

		$element->add_render_attribute( '_wrapper', 'style', $style );
	}

	private static function get_gradient_layer( $position, array $defaults ) {
		return sprintf(
			'linear-gradient( var(--fg-angle, 90deg), transparent calc(var(--fg-%1$s-start, %2$s%%) - var(--fg-blend, %3$s%%)), color-mix(in srgb, var(--fg-%1$s-color, #ffffff) calc(var(--fg-%1$s-opacity, %4$s) * 100%%), transparent) var(--fg-%1$s-start, %2$s%%), color-mix(in srgb, var(--fg-%1$s-color, #ffffff) calc(var(--fg-%1$s-opacity, %4$s) * 100%%), transparent) var(--fg-%1$s-end, %5$s%%), transparent calc(var(--fg-%1$s-end, %5$s%%) + var(--fg-blend, %3$s%%)), transparent 100%% )',
			$position,
			$defaults['start'],
			$defaults['blend'],
			$defaults['opacity'],
			$defaults['end']
		);
	}

	private static function add_area_control( Element_Base $element, $suffix, $from, $to ) {
		$element->add_control(
			'_ha_foreground_overlay_' . $suffix . '_area',
			[
				'label' => __( 'Area', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'sizes' => [
						'from' => $from,
						'to' => $to,
					],
					'unit' => '%',
				],
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'labels' => [
					__( 'From', 'happy-elementor-addons' ),
					__( 'To', 'happy-elementor-addons' ),
				],
				'scales' => 0,
				'handles' => 'range',
				'render_type' => 'none',
				'frontend_available' => true,
				'condition' => [
					'_ha_foreground_overlay_type' => 'gradient',
				],
			]
		);
	}

	public static function add_section( Element_Base $element ) {
		if ( 'container' !== $element->get_name() ) {
			return;
		}

		$gradient_css = implode( ', ', [
			self::get_gradient_layer( 'left', [ 'start' => 0, 'end' => 5, 'opacity' => 1, 'blend' => 20 ] ),
			self::get_gradient_layer( 'center', [ 'start' => 5, 'end' => 95, 'opacity' => 0, 'blend' => 20 ] ),
			self::get_gradient_layer( 'right', [ 'start' => 95, 'end' => 100, 'opacity' => 1, 'blend' => 20 ] ),
		] );

		$element->start_controls_section(
			'_ha_section_foreground_overlay',
			[
				'label' => __( 'Foreground Overlay', 'happy-elementor-addons' ) . ha_get_section_icon(),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$element->add_control(
			'_ha_foreground_overlay_type',
			[
				'label'   => __( 'Foreground Type', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::CHOOSE,
				'toggle'  => true,
				'default' => '',
				'options' => [
					'classic' => [
						'title' => __( 'Classic', 'happy-elementor-addons' ),
						'icon'  => 'eicon-paint-brush',
					],
					'gradient' => [
						'title' => __( 'Gradient', 'happy-elementor-addons' ),
						'icon'  => 'eicon-barcode',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'position: relative; isolation: isolate; overflow: hidden;',
					'{{WRAPPER}}::after' => 'content: ""; position: absolute; inset: 0; z-index: 9999; pointer-events: none; display: block;',
				],
				'assets' => [
					'scripts' => [
						[
							'name' => 'happy-foreground-overlay',
							'conditions' => [
								'terms' => [
									[
										'name' => '_ha_foreground_overlay_type',
										'operator' => '===',
										'value' => 'gradient',
									],
								],
							],
						],
					],
				],
			]
		);

		$element->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => '_ha_foreground_overlay',
				'selector'  => '{{WRAPPER}}::after',
				'types'     => [ 'classic' ],
				'condition' => [
					'_ha_foreground_overlay_type' => 'classic',
				],
				'fields_options' => [
					'background' => [
						'type'    => Controls_Manager::HIDDEN,
						'default' => 'classic',
					],
				],
			]
		);

		$element->add_control(
			'_ha_foreground_overlay_opacity',
			[
				'label'     => __( 'Opacity', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => .5,
				],
				'range'     => [
					'px' => [
						'max'  => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}}::after' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'_ha_foreground_overlay_type' => 'classic',
				],
			]
		);

		$element->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'      => '_ha_foreground_overlay_css_filters',
				'selector'  => '{{WRAPPER}}::after',
				'condition' => [
					'_ha_foreground_overlay_type' => 'classic',
				],
			]
		);

		$element->add_control(
			'_ha_foreground_overlay_gradient_css',
			[
				'label'     => __( 'Dependable css added', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => 'yes',
				'selectors' => [
					'{{WRAPPER}}::after' => 'background: ' . $gradient_css . ';',
				],
				'condition' => [
					'_ha_foreground_overlay_type' => 'gradient',
				],
			]
		);

		$element->add_control(
			'_ha_foreground_overlay_start_heading',
			[
				'label'     => __( 'Start Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'_ha_foreground_overlay_type' => 'gradient',
				],
			]
		);

		$element->add_control(
			'_ha_foreground_overlay_start_color',
			[
				'label'     => __( 'Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}}' => '--fg-left-color: {{VALUE}};',
				],
				'condition' => [
					'_ha_foreground_overlay_type' => 'gradient',
				],
			]
		);

		self::add_area_control( $element, 'start', 0, 5 );

		$element->add_control(
			'_ha_foreground_overlay_start_opacity',
			[
				'label'   => __( 'Opacity', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 1,
				],
				'range'     => [
					'px' => [
						'max'  => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--fg-left-opacity: {{SIZE}};',
				],
				'condition' => [
					'_ha_foreground_overlay_type' => 'gradient',
				],
			]
		);

		$element->add_control(
			'_ha_foreground_overlay_center_heading',
			[
				'label'     => __( 'Center Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'_ha_foreground_overlay_type' => 'gradient',
				],
			]
		);

		$element->add_control(
			'_ha_foreground_overlay_center_color',
			[
				'label'     => __( 'Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}}' => '--fg-center-color: {{VALUE}};',
				],
				'condition' => [
					'_ha_foreground_overlay_type' => 'gradient',
				],
			]
		);

		self::add_area_control( $element, 'center', 5, 95 );

		$element->add_control(
			'_ha_foreground_overlay_center_opacity',
			[
				'label'   => __( 'Opacity', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'max'  => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--fg-center-opacity: {{SIZE}};',
				],
				'condition' => [
					'_ha_foreground_overlay_type' => 'gradient',
				],
			]
		);

		$element->add_control(
			'_ha_foreground_overlay_end_heading',
			[
				'label'     => __( 'End Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'_ha_foreground_overlay_type' => 'gradient',
				],
			]
		);

		$element->add_control(
			'_ha_foreground_overlay_end_color',
			[
				'label'     => __( 'Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}}' => '--fg-right-color: {{VALUE}};',
				],
				'condition' => [
					'_ha_foreground_overlay_type' => 'gradient',
				],
			]
		);

		self::add_area_control( $element, 'end', 95, 100 );

		$element->add_control(
			'_ha_foreground_overlay_end_opacity',
			[
				'label'   => __( 'Opacity', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'max'  => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--fg-right-opacity: {{SIZE}};',
				],
				'condition' => [
					'_ha_foreground_overlay_type' => 'gradient',
				],
			]
		);

		$element->add_control(
			'_ha_foreground_overlay_blend',
			[
				'label'   => __( 'Blend Width', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 20,
					'unit' => '%',
				],
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
				],
				'description' => __( '5% = subtle, 10% = normal, 20% = very smooth, 30% = very wide', 'happy-elementor-addons' ),
				'separator'   => 'before',
				'selectors'   => [
					'{{WRAPPER}}' => '--fg-blend: {{SIZE}}%;',
				],
				'condition' => [
					'_ha_foreground_overlay_type' => 'gradient',
				],
			]
		);

		$element->add_control(
			'_ha_foreground_overlay_angle',
			[
				'label'   => __( 'Angle', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 90,
					'unit' => 'deg',
				],
				'size_units' => [ 'deg' ],
				'range' => [
					'deg' => [
						'min'  => 0,
						'max'  => 360,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--fg-angle: {{SIZE}}deg;',
				],
				'condition' => [
					'_ha_foreground_overlay_type' => 'gradient',
				],
			]
		);

		$element->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'      => '_ha_foreground_overlay_gradient_css_filters',
				'selector'  => '{{WRAPPER}}::after',
				'condition' => [
					'_ha_foreground_overlay_type' => 'gradient',
				],
			]
		);

		$element->add_control(
			'_ha_foreground_overlay_blend_mode',
			[
				'label'     => __( 'Blend Mode', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'separator' => 'before',
				'options'   => [
					''             => __( 'Normal', 'happy-elementor-addons' ),
					'overlay'      => 'Overlay',
					'saturation'   => 'Saturation',
				],
				'selectors' => [
					'{{WRAPPER}}::after' => 'mix-blend-mode: {{VALUE}}',
				],
				'condition' => [
					'_ha_foreground_overlay_type' => [ 'classic', 'gradient' ],
				],
			]
		);

		$element->end_controls_section();
	}
}
