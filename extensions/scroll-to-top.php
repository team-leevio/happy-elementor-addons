<?php
namespace Happy_Addons\Elementor\Extension;

use \Elementor\Controls_Manager;


class Scroll_To_Top {


	public function __construct() {
		add_action( 'elementor/documents/register_controls', [$this, 'register_controls'], 10 );
	}

	public function register_controls( $element ) {

		$global_settings = get_option( 'eael_global_settings' );

		$element->start_controls_section(
			'eael_ext_scroll_to_top_section',
			[
				'label' => __( '<i class="eaicon-logo"></i> Scroll to Top', 'happy-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);

		$element->add_control(
			'eael_ext_scroll_to_top',
			[
				'label'        => __( 'Enable Scroll to Top', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'happy-elementor-addons' ),
				'label_off'    => __( 'No', 'happy-elementor-addons' ),
				'return_value' => 'yes',
			]
		);

		$element->add_control(
			'eael_ext_scroll_to_top_has_global',
			[
				'label'   => __( 'Enabled Globally?', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::HIDDEN,
				'default' => ( isset( $global_settings['eael_ext_scroll_to_top']['enabled'] ) ? $global_settings['eael_ext_scroll_to_top']['enabled'] : false ),
			]
		);

		if ( isset( $global_settings['eael_ext_scroll_to_top']['enabled'] ) && ( $global_settings['eael_ext_scroll_to_top']['enabled'] == true ) && get_the_ID() != $global_settings['eael_ext_scroll_to_top']['post_id'] && get_post_status( $global_settings['eael_ext_scroll_to_top']['post_id'] ) == 'publish' ) {
			$element->add_control(
				'eael_ext_scroll_to_top_global_warning_text',
				[
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => __( 'You can modify the Global Scroll to Top by <strong><a href="' . get_bloginfo( 'url' ) . '/wp-admin/post.php?post=' . $global_settings['eael_ext_scroll_to_top']['post_id'] . '&action=elementor">Clicking Here</a></strong>', 'happy-elementor-addons' ),
					'content_classes' => 'eael-warning',
					'separator'       => 'before',
					'condition'       => [
						'eael_ext_scroll_to_top' => 'yes',
					],
				]
			);
		} else {
			$element->add_control(
				'eael_ext_scroll_to_top_global',
				[
					'label'        => __( 'Enable Scroll to Top Globally', 'happy-elementor-addons' ),
					'description'  => __( 'Enabling this option will effect on entire site.', 'happy-elementor-addons' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'no',
					'label_on'     => __( 'Yes', 'happy-elementor-addons' ),
					'label_off'    => __( 'No', 'happy-elementor-addons' ),
					'return_value' => 'yes',
					'separator'    => 'before',
					'condition'    => [
						'eael_ext_scroll_to_top' => 'yes',
					],
				]
			);

			$element->add_control(
				'eael_ext_scroll_to_top_global_display_condition',
				[
					'label'     => __( 'Display On', 'happy-elementor-addons' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'all',
					'options'   => [
						'posts' => __( 'All Posts', 'happy-elementor-addons' ),
						'pages' => __( 'All Pages', 'happy-elementor-addons' ),
						'all'   => __( 'All Posts & Pages', 'happy-elementor-addons' ),
					],
					'condition' => [
						'eael_ext_scroll_to_top'        => 'yes',
						'eael_ext_scroll_to_top_global' => 'yes',
					],
					'separator' => 'before',
				]
			);
		}

		$element->add_control(
			'eael_ext_scroll_to_top_position_text',
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
					'eael_ext_scroll_to_top' => 'yes',
				],
			]
		);

		$element->add_control(
			'eael_ext_scroll_to_top_position_bottom',
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
					'.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button' => 'bottom: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'eael_ext_scroll_to_top' => 'yes',
				],
			]
		);

		$element->add_control(
			'eael_ext_scroll_to_top_position_left',
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
					'.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button' => 'left: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'eael_ext_scroll_to_top'               => 'yes',
					'eael_ext_scroll_to_top_position_text' => 'bottom-left',
				],
			]
		);

		$element->add_control(
			'eael_ext_scroll_to_top_position_right',
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
					'.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button' => 'right: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'eael_ext_scroll_to_top'               => 'yes',
					'eael_ext_scroll_to_top_position_text' => 'bottom-right',
				],
			]
		);

		$element->add_control(
			'eael_ext_scroll_to_top_button_width',
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
					'.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator'  => 'before',
				'condition'  => [
					'eael_ext_scroll_to_top' => 'yes',
				],
			]
		);

		$element->add_control(
			'eael_ext_scroll_to_top_button_height',
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
					'.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'eael_ext_scroll_to_top' => 'yes',
				],
			]
		);

		$element->add_control(
			'eael_ext_scroll_to_top_z_index',
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
					'.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button' => 'z-index: {{SIZE}}',
				],
				'condition'  => [
					'eael_ext_scroll_to_top' => 'yes',
				],
			]
		);

		$element->add_control(
			'eael_ext_scroll_to_top_button_opacity',
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
					'.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'eael_ext_scroll_to_top' => 'yes',
				],
			]
		);

		$element->add_control(
			'eael_ext_scroll_to_top_button_icon_image',
			[
				'label'     => esc_html__( 'Icon', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'fas fa-chevron-up',
					'library' => 'fa-solid',
				],
				'separator' => 'before',
				'condition' => [
					'eael_ext_scroll_to_top' => 'yes',
				],
			]
		);

		$element->add_control(
			'eael_ext_scroll_to_top_button_icon_size',
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
					'.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'eael_ext_scroll_to_top' => 'yes',
				],
			]
		);

		$element->add_control(
			'eael_ext_scroll_to_top_button_icon_svg_size',
			[
				'label'      => __( 'SVG Size', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'size' => 32,
					'unit' => 'px',
				],
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					],
				],
				'selectors'  => [
					'.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'eael_ext_scroll_to_top' => 'yes',
				],
			]
		);

		$element->add_control(
			'eael_ext_scroll_to_top_button_icon_color',
			[
				'label'     => __( 'Icon Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button i' => 'color: {{VALUE}}',
					'.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button svg' => 'fill: {{VALUE}}',
				],
				'condition' => [
					'eael_ext_scroll_to_top' => 'yes',
				],
			]
		);

		$element->add_control(
			'eael_ext_scroll_to_top_button_bg_color',
			[
				'label'     => __( 'Background Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => [
					'.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'eael_ext_scroll_to_top' => 'yes',
				],
			]
		);

		$element->add_control(
			'eael_ext_scroll_to_top_button_border_radius',
			[
				'label'      => __( 'Border Radius', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors'  => [
					'.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'eael_ext_scroll_to_top' => 'yes',
				],
			]
		);

		$element->end_controls_section();
	}
}
