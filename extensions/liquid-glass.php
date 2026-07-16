<?php

namespace Happy_Addons\Elementor\Extensions;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

defined('ABSPATH') || die();

class Liquid_Glass {

	private static $instance = null;

	public static function instance() {
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function init() {
		add_action('elementor/frontend/before_register_scripts', [$this, 'register_scripts']);
		add_action('elementor/frontend/before_register_styles', [$this, 'register_styles']);
		add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_scripts']);

		add_action('elementor/element/section/section_advanced/after_section_end', [$this, 'register_controls'], 10);
		add_action('elementor/element/column/section_advanced/after_section_end', [$this, 'register_controls'], 10);
		add_action('elementor/element/common/_section_style/after_section_end', [$this, 'register_controls'], 10);

		if (defined('ELEMENTOR_VERSION') && ha_elementor()->experiments->is_feature_active('container')) {
			add_action('elementor/element/container/section_layout/after_section_end', [$this, 'register_controls'], 10);
		}
	}

	public function register_scripts() {
		$suffix = ha_is_script_debug_enabled() ? '.' : '.min.';

		wp_register_script(
			'happy-liquid-glass',
			HAPPY_ADDONS_PRO_ASSETS . 'js/liquid-glass' . $suffix . 'js',
			['jquery', 'happy-elementor-addons'],
			HAPPY_ADDONS_PRO_VERSION,
			true
		);
	}

	public function register_styles() {
		$suffix = ha_is_script_debug_enabled() ? '.' : '.min.';

		wp_register_style(
			'happy-liquid-glass',
			HAPPY_ADDONS_PRO_ASSETS . 'css/widgets/liquid-glass.min.css',
			[],
			HAPPY_ADDONS_PRO_VERSION
		);
	}

	public function enqueue_preview_scripts() {
		wp_enqueue_script('happy-liquid-glass');
	}

	public function register_controls($element) {

		$tab = Controls_Manager::TAB_ADVANCED;
		if ('common' === $element->get_name()) {
			$tab = Controls_Manager::TAB_ADVANCED;
		}

		$element->start_controls_section(
			'_ha_liquid_glass_section',
			[
				'label' => esc_html__('Happy Liquid Glass', 'happy-addons-pro') . ha_get_section_icon(),
				'tab'   => $tab,
			]
		);

		$this->add_content_controls($element);
		$this->add_style_controls($element);

		$element->end_controls_section();
	}

	public function add_content_controls($element) {

		$element->add_control(
			'ha_lg_switcher',
			[
				'label'        => __('Enable Happy Liquid Glass', 'happy-addons-pro'),
				'type'         => Controls_Manager::SWITCHER,
				'prefix_class' => 'ha-lg-',
				'render_type'  => 'template',
				'style_transfer' => false,
				'assets' => [
					'scripts' => [
						[
							'name' => 'happy-liquid-glass',
							'conditions' => [
								'terms' => [
									[
										'name' => 'ha_lg_switcher',
										'operator' => '===',
										'value' => 'yes',
									],
								],
							],
						],
					],
					'styles' => [
						[
							'name' => 'happy-liquid-glass',
							'conditions' => [
								'terms' => [
									[
										'name' => 'ha_lg_switcher',
										'operator' => '===',
										'value' => 'yes',
									],
								],
							],
						],
					],
				],
			]
		);

		$element->add_control(
			'ha_lg_preset',
			[
				'label'       => __('Effect Preset', 'happy-addons-pro'),
				'description' => __('Tip: Use a semi-transparent background to see the effect clearly.', 'happy-addons-pro'),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'abyss-glass',
				'options' => [
					'abyss-glass'       => __('Abyss Glass', 'happy-addons-pro'),
					'aqua-warp'         => __('Aqua Warp', 'happy-addons-pro'),
					'bubble-glass'      => __('Bubble Glass', 'happy-addons-pro'),
					'bubble-lens'       => __('Bubble Lens', 'happy-addons-pro'),
					'cascade-ripple'    => __('Cascade Ripple', 'happy-addons-pro'),
					
					
					'diamond-dust'      => __('Diamond Dust', 'happy-addons-pro'),
					'drop-water'        => __('Drop Water', 'happy-addons-pro'),
					
					'distorted-prism'   => __('Distorted Prism', 'happy-addons-pro'),
					'fine-crystal'      => __('Fine Crystal', 'happy-addons-pro'),
	// 'fog-drift'         => __('Fog Drift', 'happy-addons-pro'),
					'frozen-glass'      => __('Frozen Glass', 'happy-addons-pro'),
	// 'frozen-window'     => __('Frozen Window', 'happy-addons-pro'),
					'gentle-wave'       => __('Gentle Wave', 'happy-addons-pro'),
					'glass-fiber'       => __('Glass Fiber', 'happy-addons-pro'),
	// 'goo'               => __('Goo', 'happy-addons-pro'),
	// 'gooey'             => __('Gooey', 'happy-addons-pro'),
					'holographic-glass' => __('Holographic Glass', 'happy-addons-pro'),
					'ice-crystal'       => __('Ice Crystal', 'happy-addons-pro'),
					'jelly-blob'        => __('Jelly Blob', 'happy-addons-pro'),
					'jelly-surface'     => __('Jelly Surface', 'happy-addons-pro'),
					'lateral-surge'     => __('Lateral Surge', 'happy-addons-pro'),
					'lava-flow'         => __('Lava Flow', 'happy-addons-pro'),
	// 'lens-glass'        => __('Lens Glass', 'happy-addons-pro'),
					'liquid-lens'       => __('Liquid Lens', 'happy-addons-pro'),
					'macos-glass'       => __('macOS Glass', 'happy-addons-pro'),
					'magnifier-lens'    => __('Magnifier Lens', 'happy-addons-pro'),
					'melted-glass'      => __('Melted Glass', 'happy-addons-pro'),
	// 'mercury-liquid'    => __('Mercury Liquid', 'happy-addons-pro'),
					'micro-frost'       => __('Micro Frost', 'happy-addons-pro'),
	// 'neutral-frost'     => __('Neutral Frost', 'happy-addons-pro'),
					'ocean-current'     => __('Ocean Current', 'happy-addons-pro'),
					'prism-cut'         => __('Prism Cut', 'happy-addons-pro'),
	// 'quartz-stream'     => __('Quartz Stream', 'happy-addons-pro'),
					'rain-streak'       => __('Rain Streak', 'happy-addons-pro'),
				'tempest-glass'     => __('Tempest Glass', 'happy-addons-pro'),
				'thai-frost'        => __('Thai Frost', 'happy-addons-pro'),
				'thai-frost-2'      => __('Thai Frost 2', 'happy-addons-pro'),
				'turbulent-warp'    => __('Turbulent Warp', 'happy-addons-pro'),
					'ultra-lens'        => __('Ultra Lens', 'happy-addons-pro'),
	// 'vapor-lens'        => __('Vapor Lens', 'happy-addons-pro'),
					'viscous-gel'       => __('Viscous Gel', 'happy-addons-pro'),
	// 'visionos-glass'    => __('VisionOS Glass', 'happy-addons-pro'),
	//'water-ripple'      => __('Water Ripple', 'happy-addons-pro'),
					'wavy-water'        => __('Wavy Water', 'happy-addons-pro'),
	// 'wind-flow'         => __('Wind Flow', 'happy-addons-pro'),
					'custom'            => __('Custom', 'happy-addons-pro'),
				],
				'prefix_class' => 'ha-lg-',
				'render_type'  => 'template',
				'style_transfer' => true,
				'condition'    => [
					'ha_lg_switcher' => 'yes',
				],
			]
		);

		$element->add_control(
			'ha_lg_frequency_x',
			[
				'label' => __('Frequency X', 'happy-addons-pro'),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.006,
				'step' => 0.001,
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}}' => '--ha-lg-frequency-x: {{VALUE}};',
				],
				'condition' => [
					'ha_lg_switcher' => 'yes',
					'ha_lg_preset' => 'custom',
				],
			]
		);

		$element->add_control(
			'ha_lg_frequency_y',
			[
				'label' => __('Frequency Y', 'happy-addons-pro'),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.006,
				'step' => 0.001,
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}}' => '--ha-lg-frequency-y: {{VALUE}};',
				],
				'condition' => [
					'ha_lg_switcher' => 'yes',
					'ha_lg_preset' => 'custom',
				],
			]
		);

		$element->add_control(
			'ha_lg_scale',
			[
				'label' => __('Distortion Scale', 'happy-addons-pro'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 80,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}}' => '--ha-lg-scale: {{SIZE}};',
				],
				'condition' => [
					'ha_lg_switcher' => 'yes',
					'ha_lg_preset' => 'custom',
				],
			]
		);

		$element->add_control(
			'ha_lg_octaves',
			[
				'label' => __('Noise Octaves', 'happy-addons-pro'),
				'type' => Controls_Manager::NUMBER,
				'default' => 2,
				'min' => 1,
				'max' => 10,
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}}' => '--ha-lg-octaves: {{VALUE}};',
				],
				'condition' => [
					'ha_lg_switcher' => 'yes',
					'ha_lg_preset' => 'custom',
				],
			]
		);

		$element->add_control(
			'ha_lg_seed',
			[
				'label' => __('Random Seed', 'happy-addons-pro'),
				'type' => Controls_Manager::NUMBER,
				'default' => 92,
				'min' => 1,
				'max' => 9999,
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}}' => '--ha-lg-seed: {{VALUE}};',
				],
				'condition' => [
					'ha_lg_switcher' => 'yes',
					'ha_lg_preset' => 'custom',
				],
			]
		);

		$element->add_control(
			'ha_lg_x_channel',
			[
				'label' => __('X Channel', 'happy-addons-pro'),
				'type' => Controls_Manager::SELECT,
				'default' => 'R',
				'options' => [
					'R' => 'Red',
					'G' => 'Green',
					'B' => 'Blue',
					'A' => 'Alpha',
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}}' => '--ha-lg-x-channel: {{VALUE}};',
				],
				'condition' => [
					'ha_lg_switcher' => 'yes',
					'ha_lg_preset' => 'custom',
				],
			]
		);

		$element->add_control(
			'ha_lg_y_channel',
			[
				'label' => __('Y Channel', 'happy-addons-pro'),
				'type' => Controls_Manager::SELECT,
				'default' => 'G',
				'options' => [
					'R' => 'Red',
					'G' => 'Green',
					'B' => 'Blue',
					'A' => 'Alpha',
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}}' => '--ha-lg-y-channel: {{VALUE}};',
				],
				'condition' => [
					'ha_lg_switcher' => 'yes',
					'ha_lg_preset' => 'custom',
				],
			]
		);

		$element->add_control(
			'ha_lg_std_deviation',
			[
				'label' => __('Blur Standard Deviation', 'happy-addons-pro'),
				'description' => __('Controls the Gaussian blur applied to the noise before displacement.', 'happy-addons-pro'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 2,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
						'step' => 0.1,
					],
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}}' => '--ha-lg-std-deviation: {{SIZE}};',
				],
				'condition' => [
					'ha_lg_switcher' => 'yes',
					'ha_lg_preset' => 'custom',
				],
			]
		);
	}

	public function add_style_controls($element) {

		$element->add_control(
			'ha_lg_blur',
			[
				'label'       => __('Blur Strength', 'happy-addons-pro'),
				'description' => __('Leave empty to use the default value of the selected preset.', 'happy-addons-pro'),
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
				],
				'selectors'   => [
					'{{WRAPPER}}' => '--ha-lg-blur: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'ha_lg_switcher' => 'yes',
				],
			]
		);

		$element->add_control(
			'ha_lg_shadow',
			[
				'label'       => __('Shadow', 'happy-addons-pro'),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'soft-glow',
				'options'     => [
					'none'         => __('None', 'happy-addons-pro'),
					'soft-glow'    => __('Soft Glow', 'happy-addons-pro'),
					'warm-glow'    => __('Warm Glow', 'happy-addons-pro'),
					'bright-glow'  => __('Bright Glow', 'happy-addons-pro'),
					'wide-bloom'   => __('Wide Bloom', 'happy-addons-pro'),
					'deep-bloom'   => __('Deep Bloom', 'happy-addons-pro'),
					'top-light'    => __('Top Light', 'happy-addons-pro'),
					'bottom-light' => __('Bottom Light', 'happy-addons-pro'),
					'top-fade'     => __('Top Fade', 'happy-addons-pro'),
					'bottom-fade'  => __('Bottom Fade', 'happy-addons-pro'),
					'top-edge'     => __('Top Edge', 'happy-addons-pro'),
					'rim-light'    => __('Rim Light', 'happy-addons-pro'),
					'inner-shadow' => __('Inner Shadow', 'happy-addons-pro'),
					'spotlight'    => __('Spotlight', 'happy-addons-pro'),
					'frost-edge'   => __('Frost Edge', 'happy-addons-pro'),
					'vignette'     => __('Vignette', 'happy-addons-pro'),
					'customs'       => __('Custom Shadow', 'happy-addons-pro'),
				],
				'prefix_class' => 'ha-lg-',
				'condition'    => [
					'ha_lg_switcher' => 'yes',
				],
				'selectors_dictionary' => [
					'none'         => '',
					'soft-glow'    => 'box-shadow: 0 0 15px 0 rgba(255, 255, 255, 0.6) inset;',
					'warm-glow'    => 'box-shadow: 0 0 20px 0 rgba(255, 255, 255, 0.65) inset;',
					'bright-glow'  => 'box-shadow: 0 0 15px 0 rgba(255, 255, 255, 0.7) inset;',
					'wide-bloom'   => 'box-shadow: 0 0 30px 1px rgba(255, 255, 255, 0.7) inset;',
					'deep-bloom'   => 'box-shadow: 0 0 40px 5px rgba(255, 255, 255, 0.6) inset;',
					'top-light'    => 'box-shadow: 0 20px 15px -5px rgba(255, 255, 255, 0.5) inset;',
					'bottom-light' => 'box-shadow: 0 -20px 25px -15px rgba(255, 255, 255, 0.5) inset;',
					'top-fade'     => 'box-shadow: 0 10px 25px -10px rgba(255, 255, 255, 0.4) inset;',
					'bottom-fade'  => 'box-shadow: 0 -10px 20px -5px rgba(255, 255, 255, 0.55) inset;',
					'top-edge'     => 'box-shadow: 0 15px 15px -10px rgba(255, 255, 255, 0.45) inset;',
					'rim-light'    => 'box-shadow: 0 0 10px 0 rgba(255, 255, 255, 0.8) inset, 0 0 2px 0 rgba(255, 255, 255, 0.9) inset;',
					'inner-shadow' => 'box-shadow: inset 0 2px 8px 0 rgba(0, 0, 0, 0.15), inset 0 0 12px 0 rgba(255, 255, 255, 0.3);',
					'spotlight'    => 'box-shadow: 0 -5px 30px -10px rgba(255, 255, 255, 0.7) inset;',
					'frost-edge'   => 'box-shadow: 0 0 6px 0 rgba(255, 255, 255, 0.9) inset, 0 0 20px -5px rgba(200, 220, 255, 0.4) inset;',
					'vignette'     => 'box-shadow: inset 0 0 50px 10px rgba(0, 0, 0, 0.08);',
					'customs'       => '',
				],
				'selectors' => [
					'{{WRAPPER}}' => '{{VALUE}};',
				],
				'style_transfer' => true,
			]
		);
		$element->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'ha_lg_shadow_custom',
				'label'     => __('Custom Shadow', 'happy-addons-pro'),
				'fields_options' => [
					'box_shadow' => [
						'default' => [
							'color'      => 'rgba(255, 255, 255, 0.5)',
							'horizontal' => 0,
							'vertical'   => 0,
							'blur'       => 15,
							'spread'     => 0,
							'position'   => 'inset',
						],
					],
				],
				'condition' => [
					'ha_lg_switcher' => 'yes',
					'ha_lg_shadow'   => 'customs',
				],
				'selector' => '{{WRAPPER}}',
			]
		);

		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'ha_lg_border',
				'label'    => __('Border', 'happy-addons-pro'),
				'selector' => '{{WRAPPER}}',
				'condition' => [
					'ha_lg_switcher' => 'yes',
				],
			]
		);

		$element->add_responsive_control(
			'ha_lg_border_radius',
			[
				'label'      => __('Border Radius', 'happy-addons-pro'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'ha_lg_switcher' => 'yes',
				],
			]
		);

		
	}
}
