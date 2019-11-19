<?php
/**
 * Column Option Enhance functions
 *
 * @package Happy_Addons
 */

defined('ABSPATH') || die();

add_action('elementor/element/column/layout/before_section_end', 'ha_column_layout_option_enhance', 10, 2);
function ha_column_layout_option_enhance  ($element, $args) {

	$element->add_responsive_control(
		'_ha_column_width',
		[
			'label' => __( 'Custom Column Width', 'happy-elementor-addons' ),
			'type' => \Elementor\Controls_Manager::TEXT,
			'label_block' => true,
			'description' => __( 'e.g 250px, 50%, calc(100% - 250px)', 'happy-elementor-addons' ),
			'selectors' => [
				'{{WRAPPER}}.elementor-column' => 'width: {{VALUE}}',
			],
		]
	);

	$element->add_responsive_control(
		'_ha_column_order',
		[
			'label' => __('Column Order', 'happy-elementor-addons'),
			'type' => \Elementor\Controls_Manager::NUMBER,
			'dynamic' => [
				'active' => true,
			],
			'style_transfer' => true,
			'selectors' => [
				'{{WRAPPER}}.elementor-column' => '-webkit-box-ordinal-group: calc({{VALUE}} + 1 ); -ms-flex-order:{{VALUE}}; order: {{VALUE}}',
			],
		]
	);
}
