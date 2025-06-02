<?php
namespace Happy_Addons\Elementor\Extensions;

use Elementor\Element_Base;
use Elementor\Controls_Manager;
use Happy_Addons\Elementor\Controls\Widget_List;

defined( 'ABSPATH' ) || die();

class Equal_Height {

	static $should_script_enqueue = false;

	private static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		 return self::$instance;
	}

	public static function enqueue_preview_scripts() {
		wp_enqueue_script( 'jquery-match-height' );
		wp_enqueue_script( 'happy-equal-height' );
	}


	public static function register_scripts() {
		$suffix = ha_is_script_debug_enabled() ? '.' : '.min.';
		// Equal Height
		wp_register_script(
			'happy-equal-height',
			HAPPY_ADDONS_ASSETS . 'js/extension-equal-height' . $suffix . 'js',
			// [ 'elementor-frontend' ],
			[ 'jquery' ],
			HAPPY_ADDONS_VERSION,
			true
		);
	}

	public static function register( Element_Base $element ) {
		$element->start_controls_section(
			'_section_ha_eqh',
			[
				'label' => __( 'Equal Height', 'happy-elementor-addons' ) . ha_get_section_icon(),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'_ha_eqh_enable',
			[
				'label'        => __( 'Enable', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => false,
				'return_value' => 'yes',
				'render_type'  => 'ui',
				'assets' => [
					'scripts' => [
						[
							'name' => 'jquery-match-height',
							'conditions' => [
								'terms' => [
									[
										'name' => '_ha_eqh_enable',
										'operator' => '===',
										'value' => 'yes',
									],
								],
							],
						],
						[
							'name' => 'happy-equal-height',
							'conditions' => [
								'terms' => [
									[
										'name' => '_ha_eqh_enable',
										'operator' => '===',
										'value' => 'yes',
									],
								],
							],
						],
					],
				],
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'_ha_eqh_to',
			[
				'label' => __( 'Apply To', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::SELECT,
				'options' => [
					'widget'   => __( 'Widgets', 'happy-elementor-addons' ),
				],
				'default' => 'widget',
				'condition' => [
					'_ha_eqh_enable' => 'yes',
				],
				'render_type'  => 'ui',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'_ha_eqh_widget',
			[
				'label' => __( 'Select Widgets', 'happy-elementor-addons' ),
				'label_block' => true,
				'description' => __( 'You can select multiple widgets from the dropdown and these widgets are only from the current selected section.', 'happy-elementor-addons' ),
				'type' => Widget_List::TYPE,
				'multiple' => true,
				'condition' => [
					'_ha_eqh_enable' => 'yes',
					'_ha_eqh_to' => 'widget'
				],
				'render_type' => 'ui',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'_ha_eqh_disable_on_tablet',
			[
				'label'        => __( 'Disable On Tablet', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
				'render_type'  => 'ui',
				'frontend_available' => true,
				'condition' => [
					'_ha_eqh_enable' => 'yes',
				],
			]
		);

		$element->add_control(
			'_ha_eqh_disable_on_mobile',
			[
				'label'        => __( 'Disable On Mobile', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'render_type'  => 'ui',
				'frontend_available' => true,
				'condition' => [
					'_ha_eqh_enable' => 'yes',
				],
			]
		);

		$element->end_controls_section();
	}
}
