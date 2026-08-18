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
 *    Animation + CSS Filters pair whose effects are applied to a
 *    ::before pseudo-element layer instead of the container itself,
 *    so the content inside is never affected (see add_controls_section
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

	/**
	 * Inject hover controls into the Container's Background > Hover tab,
	 * right after the transition control.
	 *
	 * All controls default to "empty" so existing designs are unaffected
	 * until a value is chosen.
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
	 * 4) Background Hover Effects — switcher. When on, it shows the Background
	 *    Animation select and the Background CSS Filter group (below) and
	 *    emits the "hover card" styles on the container:
	 *
	 *    {{WRAPPER}}         -> position: relative; overflow: hidden;
	 *    {{WRAPPER}}::before -> the background layer:
	 *        content: ""; position: absolute; inset: 0;
	 *        background-image: inherit; cover; center;
	 *        transition: filter/transform/opacity .5s ease;
	 *        z-index: 1; pointer-events: none;
	 *    {{WRAPPER}} > *     -> position: relative; z-index: 2;
	 *
	 *    The layer copies the container's background image (`inherit` picks up
	 *    the hover background too, since the :hover rule changes the parent's
	 *    computed background-image the pseudo inherits from) and the hover
	 *    transform/filter apply to `{{WRAPPER}}:hover::before` only — the
	 *    content inside the container is never blurred or scaled.
	 *
	 *    Stacking: the layer sits at z-index 1, above the container's own
	 *    background, and every direct child is lifted to z-index 2 so all
	 *    widgets stay above it. The child lift is essential for boxed
	 *    containers — their .e-con-inner wrapper is NOT positioned, so
	 *    without it the absolutely-positioned layer would paint over the
	 *    content. Caveat: the lift overrides a widget's own Advanced >
	 *    Z-Index inside this container (same trade-off the reference
	 *    hover-card CSS makes). overflow: hidden clips the scaled/blurred
	 *    layer to the container bounds.
	 *
	 *    Note: the container ::before is also Elementor's Background Overlay
	 *    slot — do not combine this switcher with a container overlay.
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
			'ha_hover_bg_effects',
			[
				'label'       => __( 'Background Hover Effects', 'happy-elementor-addons' ) . '<i style="margin-left: 5px;" class="hm hm-happyaddons"></i>',
				'description' => __( 'Apply the hover animation and CSS filters to a background layer (::before) instead of the container, so the content inside stays untouched. Do not use together with a Background Overlay.', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => __( 'On', 'happy-elementor-addons' ),
				'label_off'   => __( 'Off', 'happy-elementor-addons' ),
				'return_value'=> 'yes',
				'default'     => '',
				'separator'   => 'before',
				'selectors'   => [
					'{{WRAPPER}}' => 'position: relative; overflow: hidden;',
					'{{WRAPPER}}::before' => 'content: ""; position: absolute; inset: 0; background-image: inherit; background-size: cover; background-position: center; transition: filter .5s ease, transform .5s ease, opacity .5s ease; z-index: 1; pointer-events: none;',
					'{{WRAPPER}} > *' => 'position: relative; z-index: 2;',
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
					'zoom'   => __( 'Zoom In', 'happy-elementor-addons' ),
					'rotate' => __( 'Grow & Rotate', 'happy-elementor-addons' ),
				],
				'selectors_dictionary' => [
					'grow'   => 'scale(1.08)',
					'shrink' => 'scale(0.95)',
					'zoom'   => 'scale(1.2)',
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
				'selector'  => '{{WRAPPER}}:hover::before',
				'condition' => [
					'ha_hover_bg_effects' => 'yes',
				],
				'fields_options' => [
					'css_filter' => [
						'label' => __( 'Background CSS Filter', 'happy-elementor-addons' ),
					],
				],
			]
		);

		// $element->add_control(
		// 	'ha_hover_animation',
		// 	[
		// 		'label'        => __( 'Hover Animation', 'happy-elementor-addons' ) . '<i style="margin-left: 5px;" class="hm hm-happyaddons"></i>',
		// 		'type'         => Controls_Manager::HOVER_ANIMATION,
		// 		'prefix_class' => 'elementor-animation-',
		// 		'label_block'  => true,
		// 		'separator'    => 'before',
		// 	]
		// );
		// $element->add_group_control(
		// 	Group_Control_Css_Filter::get_type(),
		// 	[
		// 		'name'     => 'ha_hover_css_filters',
		// 		'selector' => '{{WRAPPER}}:hover',
		// 	]
		// );

		$element->end_injection();
	}
}
