<?php
/**
 * Elementor Container enhancements
 *
 * Adds a "Text Color" hover control to the Container widget's
 * Style > Background > Hover section.
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Extensions;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

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
	 * Inject a "Text Color" color control into the Container's
	 * Background > Hover tab, right after the transition control.
	 *
	 * The control is empty by default so existing designs are unaffected.
	 * On container hover, the chosen color is applied only to the Heading
	 * and Text Editor widgets found inside the container. The value is set
	 * directly on each widget's element (`.elementor-heading-title` and
	 * `.elementor-widget-text-editor`), because those are not direct children
	 * of the container, so `inherit` would not reach them. Targeting the
	 * Text Editor on its widget element (rather than `.elementor-text-editor`)
	 * keeps it working across Elementor markup variants where that inner
	 * wrapper may be absent.
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

		$element->end_injection();
	}
}
