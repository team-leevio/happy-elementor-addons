<?php
/**
 * Elementor Container enhancements
 *
 * Adds extra hover controls to the Container widget's
 * Style > Background > Hover section:
 *  - Text Color (Heading & Text Editor widgets inside the container)
 *  - Hover Animation (Elementor's built-in hover animations)
 *  - CSS Filters (applied to the container on hover)
 *  - Background Hover Effects: a switcher that reveals a second
 *    Animation + CSS Filters pair. The animation transforms a
 *    ::before pseudo-element layer (background: inherit), while the
 *    CSS filters run on a full-size ::after overlay through
 *    backdrop-filter, so the blur always covers 100% of the container
 *    and the content inside is never affected (see add_controls_section
 *    notes for the stacking details)
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Extensions;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Group_Control_Css_Filter;

defined('ABSPATH') || die();

class Container_Hover_Text_Color {

	private static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public static function add_controls_section( Element_Base $element ) {
		$element->start_injection( [
			'of' => 'background_hover_transition',
			'at' => 'after',
		] );

		$element->add_control(
			'ha_hover_text_color',
			[
				'label'     => __( 'Text Color', 'happy-elementor-addons' ). '<i style="margin-left: 5px;" class="hm hm-happyaddons"></i>',
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}}:hover .elementor-heading-title'      => 'color: {{VALUE}};',
					'{{WRAPPER}}:hover .elementor-widget-text-editor' => 'color: {{VALUE}};',
					'{{WRAPPER}}:hover :where(.elementor-widget-text-editor) :where(a)' => 'color: {{VALUE}};',
				],
			]
		);

		$element->add_control(
			'ha_hover_bg_effects',
			[
				'label'       => __( 'Background Hover Effects', 'happy-elementor-addons' ) . '<i style="margin-left: 5px;" class="hm hm-happyaddons"></i>',
			'description' => __( 'Apply the hover animation to a background layer (::before) and the CSS filters to a full-size overlay (::after) via backdrop-filter, so the blur covers 100% of the container while the content inside stays untouched. Do not use together with a Background Overlay.', 'happy-elementor-addons' ),
			'type'        => Controls_Manager::SWITCHER,
			'label_on'    => __( 'On', 'happy-elementor-addons' ),
			'label_off'   => __( 'Off', 'happy-elementor-addons' ),
			'return_value'=> 'yes',
			'default'     => '',
			'separator'   => 'before',
			'selectors'   => [
				'{{WRAPPER}}' => 'position: relative; overflow: hidden;',
				'{{WRAPPER}}::before' => 'content: ""; position: absolute; inset: 0; background: inherit; transition: transform var(--background-transition, .5s) ease; z-index: 1; pointer-events: none;',
				'{{WRAPPER}}::after' => 'content: ""; position: absolute; inset: 0; z-index: 1; pointer-events: none; transition: -webkit-backdrop-filter var(--background-transition, .5s) ease, backdrop-filter var(--background-transition, .5s) ease;',
				'{{WRAPPER}} > *:not(.elementor-element-overlay):not(.elementor-background-video-container):not(.elementor-background-slideshow):not(.elementor-motion-effects-container):not(.elementor-shape)' => 'z-index: 2;',
			],
			]
		);

		$element->add_control(
			'ha_hover_bg_animation',
			[
				'label'       => __( 'Background Animation', 'happy-elementor-addons' ) . '<i style="margin-left: 5px;" class="hm hm-happyaddons"></i>',
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => [
					''       => __( 'None', 'happy-elementor-addons' ),
					'grow'   => __( 'Grow', 'happy-elementor-addons' ),
					'shrink' => __( 'Shrink', 'happy-elementor-addons' ),
					'rotate' => __( 'Grow & Rotate', 'happy-elementor-addons' ),
				],
				'selectors_dictionary' => [
					'grow'   => 'scale(1.08)',
					'shrink' => 'scale(0.95)',
					'rotate' => 'scale(1.08) rotate(2deg)',
				],
				'selectors'   => [
					'{{WRAPPER}}:hover::before' => 'transform: {{VALUE}};',
				],
				'condition'   => [
					'ha_hover_bg_effects' => 'yes',
				],
			]
		);
		$element->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'      => 'ha_hover_bg_css_filters',
				'selector'  => '{{WRAPPER}}:hover::after',
				'condition' => [
					'ha_hover_bg_effects' => 'yes',
				],
				'fields_options' => [
					'css_filter' => [
						'label'       => __( 'Background CSS Filter', 'happy-elementor-addons' ) . '<i style="margin-left: 5px;" class="hm hm-happyaddons"></i>',
					],
					'blur' => [
						// The whole filter chain is emitted through the blur
						// sub-control (see Group_Control_Css_Filter::init_fields).
						// Re-target it from `filter` to `backdrop-filter` so the
						// filters run on the full-size ::after overlay instead of
						// the scaled ::before layer, keeping 100% coverage even
						// with the Shrink animation active.
						'selectors' => [
							'{{SELECTOR}}' => '-webkit-backdrop-filter: brightness( {{brightness.SIZE}}% ) contrast( {{contrast.SIZE}}% ) saturate( {{saturate.SIZE}}% ) blur( {{blur.SIZE}}px ) hue-rotate( {{hue.SIZE}}deg ); backdrop-filter: brightness( {{brightness.SIZE}}% ) contrast( {{contrast.SIZE}}% ) saturate( {{saturate.SIZE}}% ) blur( {{blur.SIZE}}px ) hue-rotate( {{hue.SIZE}}deg );',
						],
					],
				],
			]
		);

		$element->end_injection();
	}
}
