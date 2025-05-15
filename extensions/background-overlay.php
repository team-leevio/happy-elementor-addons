<?php
namespace Happy_Addons\Elementor\Extensions;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;

defined( 'ABSPATH' ) || die();

class Background_Overlay {

	private static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		 return self::$instance;
	}

	public static function add_section( Element_Base $element ) {
		$is_e_optimized_markup = ( ha_elementor()->experiments->is_feature_active( 'e_optimized_markup' ) && '{{WRAPPER}}' === $element::WRAPPER_SELECTOR );

		$normal_selector = $is_e_optimized_markup
			? '{{WRAPPER}}.ha-has-bg-overlay::before'
			: '{{WRAPPER}}.ha-has-bg-overlay > .elementor-widget-container::before';
		$hover_selector = $is_e_optimized_markup
			? '{{WRAPPER}}.ha-has-bg-overlay:hover::before'
			: '{{WRAPPER}}.ha-has-bg-overlay:hover > .elementor-widget-container::before';

		$element->start_controls_section(
			'_ha_section_background_overlay',
			[
				'label' => __( 'Background Overlay', 'happy-elementor-addons' ) . ha_get_section_icon(),
				'tab' => Controls_Manager::TAB_ADVANCED,
				'condition' => [
					'_background_background' => [ 'classic', 'gradient' ],
				],
			]
		);

		$element->add_control(
			'_ha_background_overlay_cls_added',
			[
				'label'        => __( 'Extra class added', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::HIDDEN,
				'default'      => 'overlay',
				'prefix_class' => 'ha-has-bg-',
			]
		);

		if ( false && $is_e_optimized_markup ) {
			$element->add_control(
				'_ha_background_overlay_css_added_for_optimized_markup',
				[
					'label'        => __( 'Dependable css added', 'happy-elementor-addons' ),
					'type'         => Controls_Manager::HIDDEN,
					'default'      => 'overlay',
					'selectors' => [
						'{{WRAPPER}}.ha-has-bg-overlay' => 'z-index: 1; position: relative;',
						'{{WRAPPER}}.ha-has-bg-overlay::before' => 'content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;',
					],
				]
			);
		}

		$element->start_controls_tabs( '_ha_tabs_background_overlay' );

		$element->start_controls_tab(
			'_ha_tab_background_overlay_normal',
			[
				'label' => __( 'Normal', 'happy-elementor-addons' ),
			]
		);

		$element->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => '_ha_background_overlay',
				'selector' => $normal_selector,
			]
		);

		$element->add_control(
			'_ha_background_overlay_opacity',
			[
				'label' => __( 'Opacity', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => .5,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					$normal_selector => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'_ha_background_overlay_background' => [ 'classic', 'gradient' ],
				],
			]
		);

		$element->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => '_ha_css_filters',
				'selector' => $normal_selector,
			]
		);

		$element->add_control(
			'_ha_overlay_blend_mode',
			[
				'label' => __( 'Blend Mode', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Normal', 'happy-elementor-addons' ),
					'multiply' => 'Multiply',
					'screen' => 'Screen',
					'overlay' => 'Overlay',
					'darken' => 'Darken',
					'lighten' => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation' => 'Saturation',
					'color' => 'Color',
					'luminosity' => 'Luminosity',
				],
				'selectors' => [
					$normal_selector => 'mix-blend-mode: {{VALUE}}',
				],
			]
		);

		$element->end_controls_tab();

		$element->start_controls_tab(
			'_ha_tab_background_overlay_hover',
			[
				'label' => __( 'Hover', 'happy-elementor-addons' ),
			]
		);

		$element->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => '_ha_background_overlay_hover',
				'selector' => $hover_selector,
			]
		);

		$element->add_control(
			'_ha_background_overlay_hover_opacity',
			[
				'label' => __( 'Opacity', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => .5,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					$hover_selector => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'_ha_background_overlay_hover_background' => [ 'classic', 'gradient' ],
				],
			]
		);

		$element->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => '_ha_css_filters_hover',
				'selector' => $hover_selector,
			]
		);

		$element->add_control(
			'_ha_background_overlay_hover_transition',
			[
				'label' => __( 'Transition Duration', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.3,
				],
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'separator' => 'before',
				'selectors' => [
					$hover_selector => 'transition: background {{SIZE}}s;',
				]
			]
		);

		$element->end_controls_tab();

		$element->end_controls_tabs();

		$element->end_controls_section();
	}
}
