<?php
namespace Happy_Addons\Elementor\Extension;

use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Core\Responsive\Responsive;
use Elementor\Core\Kits\Documents\Tabs\Tab_Base;

class Scroll_To_Top_Kit_Setings extends Tab_Base {

	public function get_id() {
		return 'ha-scroll-to-top-kit-settings';
	}

	public function get_title() {
		return __( 'Scroll to Top', 'happy-elementor-addons' ) . '<span style="margin: 0 15px 0 0;display: inline-block;float: right;">'.ha_get_section_icon().'</spna>';
	}

	public function get_icon() {
		return 'hm hm-scroll-top';
	}

	public function get_help_url() {
		return '';
	}

	public function get_group() {
		return 'settings';
	}

	protected function register_tab_controls() {
		$this->start_controls_section(
			'ha_scroll_to_top_kit_section',
			[
				'tab' => 'ha-scroll-to-top-kit-settings',
				'label' => __( 'Scroll to Top', 'happy-elementor-addons' ),
			]
		);

		$this->add_control(
			'ha_scroll_to_top_global',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => __( 'Enable Scroll To Top', 'happy-elementor-addons' ),
				'default' => '',
				'label_on' => __( 'Show', 'happy-elementor-addons' ),
				'label_off' => __( 'Hide', 'happy-elementor-addons' ),
			]
		);

		$this->add_control(
			'ha_scroll_to_top_position_text',
			[
				'label'       => esc_html__( 'Position', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'bottom-right',
				'label_block' => false,
				'options'     => [
					'bottom-left'  => esc_html__( 'Bottom Left', 'happy-elementor-addons' ),
					'bottom-right' => esc_html__( 'Bottom Right', 'happy-elementor-addons' ),
				],
				'separator'   => 'before',
				'condition'   => [
					'ha_scroll_to_top_global' => 'yes',
				],
			]
		);

		$this->add_control(
			'ha_scroll_to_top_position_bottom',
			[
				'label'      => __( 'Bottom', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'em' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
					'%'  => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors'  => [
					'.ha-scroll-to-top-wrap .ha-scroll-to-top-button' => 'bottom: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'ha_scroll_to_top_global' => 'yes',
				],
			]
		);

		$this->add_control(
			'ha_scroll_to_top_position_left',
			[
				'label'      => __( 'Left', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'em' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
					'%'  => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors'  => [
					'.ha-scroll-to-top-wrap .ha-scroll-to-top-button' => 'left: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'ha_scroll_to_top_global'               => 'yes',
					'ha_scroll_to_top_position_text' => 'bottom-left',
				],
			]
		);

		$this->add_control(
			'ha_scroll_to_top_position_right',
			[
				'label'      => __( 'Right', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'em' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
					'%'  => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors'  => [
					'.ha-scroll-to-top-wrap .ha-scroll-to-top-button' => 'right: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'ha_scroll_to_top_global'               => 'yes',
					'ha_scroll_to_top_position_text' => 'bottom-right',
				],
			]
		);

		$this->add_control(
			'ha_scroll_to_top_button_width',
			[
				'label'      => __( 'Width', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors'  => [
					'.ha-scroll-to-top-wrap .ha-scroll-to-top-button' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator'  => 'before',
				'condition'  => [
					'ha_scroll_to_top_global' => 'yes',
				],
			]
		);

		$this->add_control(
			'ha_scroll_to_top_button_height',
			[
				'label'      => __( 'Height', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors'  => [
					'.ha-scroll-to-top-wrap .ha-scroll-to-top-button' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'ha_scroll_to_top_global' => 'yes',
				],
			]
		);

		$this->add_control(
			'ha_scroll_to_top_z_index',
			[
				'label'      => __( 'Z Index', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 9999,
						'step' => 10,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 9999,
				],
				'selectors'  => [
					'.ha-scroll-to-top-wrap .ha-scroll-to-top-button' => 'z-index: {{SIZE}}',
				],
				'condition'  => [
					'ha_scroll_to_top_global' => 'yes',
				],
			]
		);

		$this->add_control(
			'ha_scroll_to_top_button_opacity',
			[
				'label'     => __( 'Opacity', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 1,
						'step' => 0.01,
					],
				],
				'default'   => [
					'unit' => 'px',
					'size' => 0.7,
				],
				'selectors' => [
					'.ha-scroll-to-top-wrap .ha-scroll-to-top-button' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'ha_scroll_to_top_global' => 'yes',
				],
			]
		);

		$this->add_control(
			'ha_scroll_to_top_button_icon',
			[
				'label'     => esc_html__( 'Icon', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'fas fa-chevron-up',
					'library' => 'fa-solid',
				],
				'skin' => 'inline',
				'exclude_inline_options' => [ 'svg' ],
				'separator' => 'before',
				'condition' => [
					'ha_scroll_to_top_global' => 'yes',
				],
			]
		);

		$this->add_control(
			'ha_scroll_to_top_button_icon_size',
			[
				'label'      => __( 'Icon Size', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'size' => 16,
					'unit' => 'px',
				],
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'.ha-scroll-to-top-wrap .ha-scroll-to-top-button i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'ha_scroll_to_top_global' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'       => 'ha_scroll_to_top_button_border',
				'exclude'    => ['color'], //remove border color
				'selector'   => '{{WRAPPER}} .ha-scroll-to-top-wrap .ha-scroll-to-top-button',
				'condition' => [
					'ha_scroll_to_top_global' => 'yes',
				],
			]
		);

		$this->start_controls_tabs(
			'ha_scroll_to_top_tabs',
			[
				'separator' => 'before',
				'condition' => [
					'ha_scroll_to_top_global' => 'yes',
				],
			]
		);

		$this->start_controls_tab(
			'ha_scroll_to_top_tab_normal',
			[
				'label' => __( 'Normal', 'happy-elementor-addons' ),
				'condition' => [
					'ha_scroll_to_top_global' => 'yes',
				],
			]
		);

		$this->add_control(
			'ha_scroll_to_top_button_icon_color',
			[
				'label'     => __( 'Icon Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'.ha-scroll-to-top-wrap .ha-scroll-to-top-button i' => 'color: {{VALUE}}',
				],
				'condition' => [
					'ha_scroll_to_top_global' => 'yes',
				],
			]
		);

		$this->add_control(
			'ha_scroll_to_top_button_bg_color',
			[
				'label'     => __( 'Background Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#5636d1',
				'selectors' => [
					'.ha-scroll-to-top-wrap .ha-scroll-to-top-button' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'ha_scroll_to_top_global' => 'yes',
				],
			]
		);

		$this->add_control(
			'ha_scroll_to_top_button_border_color',
			[
				'label'     => __( 'Border Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.ha-scroll-to-top-wrap .ha-scroll-to-top-button' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'ha_scroll_to_top_global' => 'yes',
					'ha_scroll_to_top_button_border_border!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'ha_scroll_to_top_tab_hover',
			[
				'label' => __( 'Hover', 'happy-elementor-addons' ),
				'condition' => [
					'ha_scroll_to_top_global' => 'yes',
				],
			]
		);

		$this->add_control(
			'ha_scroll_to_top_button_icon_hvr_color',
			[
				'label'     => __( 'Icon Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'.ha-scroll-to-top-wrap .ha-scroll-to-top-button:hover i' => 'color: {{VALUE}}',
				],
				'condition' => [
					'ha_scroll_to_top_global' => 'yes',
				],
			]
		);

		$this->add_control(
			'ha_scroll_to_top_button_bg_hvr_color',
			[
				'label'     => __( 'Background Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#5636d1',
				'selectors' => [
					'.ha-scroll-to-top-wrap .ha-scroll-to-top-button:hover' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'ha_scroll_to_top_global' => 'yes',
				],
			]
		);

		$this->add_control(
			'ha_scroll_to_top_button_hvr_border_color',
			[
				'label'     => __( 'Border Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.ha-scroll-to-top-wrap .ha-scroll-to-top-button:hover' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'ha_scroll_to_top_global' => 'yes',
					'ha_scroll_to_top_button_border_border!' => '',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'ha_scroll_to_top_button_border_radius',
			[
				'label'      => __( 'Border Radius', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors'  => [
					'.ha-scroll-to-top-wrap .ha-scroll-to-top-button' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'ha_scroll_to_top_global' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}
}
