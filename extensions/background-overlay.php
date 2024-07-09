<?php
namespace Happy_Addons\Elementor\Extension;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;

defined( 'ABSPATH' ) || die();

class Background_Overlay {

	public static function init() {
		add_action( 'elementor/element/common/_section_background/after_section_end', [__CLASS__, 'add_section'] );
	}

	public static function add_section( Element_Base $element ) {
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
				'selector' => '{{WRAPPER}}.ha-has-bg-overlay > .elementor-widget-container:before',
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
					'{{WRAPPER}}.ha-has-bg-overlay > .elementor-widget-container:before' => 'opacity: {{SIZE}};',
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
				'selector' => '{{WRAPPER}}.ha-has-bg-overlay > .elementor-widget-container:before',
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
					'{{WRAPPER}}.ha-has-bg-overlay > .elementor-widget-container:before' => 'mix-blend-mode: {{VALUE}}',
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
				'selector' => '{{WRAPPER}}.ha-has-bg-overlay:hover > .elementor-widget-container:before',
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
					'{{WRAPPER}}.ha-has-bg-overlay:hover > .elementor-widget-container:before' => 'opacity: {{SIZE}};',
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
				'selector' => '{{WRAPPER}}.ha-has-bg-overlay:hover > .elementor-widget-container:before',
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
					'{{WRAPPER}}.ha-has-bg-overlay > .elementor-widget-container:before' => 'transition: background {{SIZE}}s;',
				]
			]
		);

		$element->end_controls_tab();

		$element->end_controls_tabs();

		$element->end_controls_section();
	}
}

Background_Overlay::init();
