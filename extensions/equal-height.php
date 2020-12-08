<?php
namespace Happy_Addons\Elementor\Extension;

use Elementor\Element_Base;
use Elementor\Controls_Manager;
use Happy_Addons\Elementor\Controls\Widget_List;

defined( 'ABSPATH' ) || die();

class Equal_Height {

	static $should_script_enqueue = false;

	public static function init() {
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ __CLASS__, 'register' ] );

		add_action( 'elementor/frontend/section/before_render', [ __CLASS__, 'should_script_enqueue' ] );

		add_action( 'elementor/preview/enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
	}

	public static function enqueue_scripts() {
		wp_enqueue_script(
			'jquery-match-height',
			HAPPY_ADDONS_ASSETS . 'vendor/jquery-match-height/jquery.matchHeight-min.js',
			[],
			HAPPY_ADDONS_VERSION,
			true
		);
	}

	/**
	 * Set should_script_enqueue based extension settings
	 *
	 * @param Element_Base $section
	 * @return void
	 */
	public static function should_script_enqueue( Element_Base $section ) {
		if ( self::$should_script_enqueue ) {
			return;
		}

		if ( 'yes' == $section->get_settings_for_display( '_ha_eqh_enable' ) ) {
			self::enqueue_scripts();

			self::$should_script_enqueue = true;

			remove_action( 'elementor/frontend/section/before_render', [ __CLASS__, 'should_script_enqueue' ] );
		}
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
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'_ha_eqh_to',
			[
				'label' => __( 'Apply To', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::SELECT,
				'options' => [
					'widget'   => __( 'Widget', 'happy-elementor-addons' ),
					'selector' => __( 'Custom Selector', 'happy-elementor-addons' ),
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
			'_ha_eqh_selector',
			[
				'label' => __( 'Add Selectors', 'happy-elementor-addons' ),
				'label_block' => true,
				'description' => __( 'You can use any CSS selector (eg. <b><code>.ha-card-text</code></b>) and you can group selectors with comma (eg. <b><code>.ha-card-title, .ha-card-text</code></b>).', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::TEXT,
				'rows' => 4,
				'condition' => [
					'_ha_eqh_enable' => 'yes',
					'_ha_eqh_to' => 'selector'
				],
				'render_type'  => 'ui',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'_ha_eqh_disable_on_tablet',
			[
				'label'        => __( 'Disable On Tablet', 'happy-elementor-addons' ),
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

Equal_Height::init();
