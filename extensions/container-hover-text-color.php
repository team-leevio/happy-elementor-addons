<?php
/**
 * Elementor Container enhancements
 *
 * Adds extra hover controls to the Container widget's
 * Style > Background > Hover section:
 *  - Text Color (Heading & Text Editor widgets inside the container)
 *  - Hover Animation (Elementor's built-in hover animations)
 *  - CSS Filters (applied to the container on hover)
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

	/**
	 * Inject hover controls into the Container's Background > Hover tab,
	 * right after the transition control.
	 *
	 * Three controls are added; all default to "empty" so existing designs
	 * are unaffected until a value is chosen:
	 *
	 * 1) Text Color — applied to the Heading and Text Editor widgets found
	 *    inside the container (see the detailed selector notes below).
	 *
	 * 2) Hover Animation — uses Elementor's native HOVER_ANIMATION control.
	 *    `prefix_class` adds `elementor-animation-<value>` to the container
	 *    wrapper, and because the control type is `hover_animation`, the
	 *    page-assets loader (`Elements_Iteration_Actions\Assets`) automatically
	 *    enqueues the matching `e-animation-<value>` stylesheet, whose rules
	 *    target `.elementor-animation-<value>:hover`. No manual rendering or
	 *    asset registration is required.
	 *
	 * 3) CSS Filters — `Group_Control_Css_Filter` targeting `{{WRAPPER}}:hover`.
	 *    The container's background lives on the wrapper element itself (there
	 *    is no separate background layer), so the filter is applied to the
	 *    wrapper on hover. Note that `filter` is not background-scoped: it also
	 *    affects the container's child content. The group ships with neutral
	 *    defaults (brightness/contrast/saturation 100, blur/hue 0), so nothing
	 *    renders until a slider is changed.
	 *
	 * Text Color selector notes:
	 *
	 * The color is set directly on each widget's element
	 * (`.elementor-heading-title` and `.elementor-widget-text-editor`),
	 * because those are not direct children of the container, so `inherit`
	 * would not reach them. Targeting the Text Editor on its widget element
	 * (rather than `.elementor-text-editor`) keeps it working across Elementor
	 * markup variants where that inner wrapper may be absent.
	 *
	 * Each selector resolves to specificity (0,4,0), which is higher than a
	 * widget's own text color rule `{{WRAPPER}} .elementor-heading-title`
	 * (0,3,0) — so the text recolors on container hover even on the frontend,
	 * where parent styles print before child styles — yet lower than the
	 * Heading widget's link rule `{{WRAPPER}} .elementor-heading-title a:hover`
	 * (0,4,1), so links keep their own hover color.
	 *
	 * The Text Editor has one extra hurdle: a theme's bare `a { color }` rule
	 * (or the widget's `{{WRAPPER}} a`) sets color directly on links, which
	 * beats inheritance and keeps them from picking up the container color.
	 * A third rule targets those links on container hover but wraps both the
	 * widget class and the `a` in `:where()` so it adds zero specificity —
	 * landing at (0,3,0): above the theme `a` (0,0,1) and the widget's normal
	 * link rule (0,2,1), but below the widget's `a:hover`/`a:focus` (0,3,1),
	 * so hovering a link still shows the widget's own link color.
	 *
	 * @param Element_Base $element The Container element instance.
	 */
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
			'ha_hover_animation',
			[
				'label'        => __( 'Hover Animation', 'happy-elementor-addons' ) . '<i style="margin-left: 5px;" class="hm hm-happyaddons"></i>',
				'type'         => Controls_Manager::HOVER_ANIMATION,
				'prefix_class' => 'elementor-animation-',
				'label_block'  => true,
				'separator'    => 'before',
			]
		);
		$element->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'     => 'ha_hover_css_filters',
				'selector' => '{{WRAPPER}}:hover',
			]
		);

		$element->end_injection();
	}
}
