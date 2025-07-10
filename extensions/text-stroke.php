<?php
/**
 * Elementor default widgets enhancements
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Extensions;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Happy_Addons\Elementor\Controls\Group_Control_Text_Stroke;

defined('ABSPATH') || die();

class Text_Stroke {

	private static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		 return self::$instance;
	}

	public static function add_text_stroke_old( Widget_Base $widget ) {
		$common = [
			'of'     => 'blend_mode',
			'target' => '.elementor-heading-title',
		];

		$map = [
			'heading'                   => $common,
			'theme-page-title'          => $common,
			'theme-site-title'          => $common,
			'theme-post-title'          => $common,
			'woocommerce-product-title' => $common,
			'animated-headline'         => [
				'of'     => 'title_color',
				'target' => '.elementor-headline',
			],
			'ha-gradient-heading'       => [
				'of'     => 'blend_mode',
				'target' => '.ha-gradient-heading',
			],
		];

		$of     = $map[ $widget->get_name() ]['of'];
		$target = $map[ $widget->get_name() ]['target'];

		if ( 'ha-gradient-heading' != $widget->get_name() ) {
			$widget->update_control(
				$of,
				[
					'control_type' => 'content',
				]
			);
		}

		$widget->start_injection( [
			'at' => 'after',
			'of' => $of,
		] );

		$widget->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name'     => 'text_stroke',
				'selector' => '{{WRAPPER}} ' . $target,
			]
		);

		$widget->end_injection();
	}

	public static function add_text_stroke( Widget_Base $widget ) {
		$common = [
			'target' => '.elementor-heading-title',
		];

		$map = [
			'heading'                   => $common,
			'theme-page-title'          => $common,
			'theme-site-title'          => $common,
			'theme-post-title'          => $common,
			'woocommerce-product-title' => $common,
			'animated-headline'         => [
				'target' => '.elementor-headline',
			],
			'ha-gradient-heading'       => [
				'target' => '.ha-gradient-heading',
			],
		];

		$target = $map[ $widget->get_name() ]['target'];

		if ( 'animated-headline' == $widget->get_name() ) {
			$widget->add_control(
				'ha_text_stroke_heading',
				[
					'label' => esc_html__( 'Whole Text', 'happy-elementor-addons' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);
		}

		$widget->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name'     => 'text_stroke',
				'selector' => '{{WRAPPER}} ' . $target,
			]
		);
	}
}
