<?php
namespace Happy_Addons\Elementor\Extension;

use \Elementor\Controls_Manager;


class Scroll_To_Top {


	public function __construct() {
		$feature_file = HAPPY_ADDONS_DIR_PATH . 'extensions/scroll-to-top-kit-settings.php';

		if ( is_readable( $feature_file ) ) {
			include_once( $feature_file );
		}

		add_action( 'elementor/kit/register_tabs', [ $this, 'init_site_settings' ], 1, 40 );
		add_action( 'elementor/documents/register_controls', [$this, 'register_controls'], 10 );
		// add_action( 'elementor/documents/register_controls', [$this, 'register_hide_title_control'] );
		add_action( 'wp_footer', [$this, 'render_global_html'] );
	}

	public function register_controls( $element ) {

		$global_settings = get_option( 'ha_global_settings' );

		$element->start_controls_section(
			'ha_ext_scroll_to_top_section',
			[
				'label' => __( 'Scroll to Top', 'happy-elementor-addons' ) . ha_get_section_icon(),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);

		$element->add_control(
			'ha_ext_scroll_to_top',
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
			'ha_ext_scroll_to_top_has_global',
			[
				'label'   => __( 'Enabled Globally?', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::HIDDEN,
				'default' => ( isset( $global_settings['ha_ext_scroll_to_top']['enabled'] ) ? $global_settings['ha_ext_scroll_to_top']['enabled'] : false ),
			]
		);

		if ( isset( $global_settings['ha_ext_scroll_to_top']['enabled'] ) && ( $global_settings['ha_ext_scroll_to_top']['enabled'] == true ) && get_the_ID() != $global_settings['ha_ext_scroll_to_top']['post_id'] && get_post_status( $global_settings['ha_ext_scroll_to_top']['post_id'] ) == 'publish' ) {
			$element->add_control(
				'ha_ext_scroll_to_top_global_warning_text',
				[
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => __( 'You can modify the Global Scroll to Top by <strong><a href="' . get_bloginfo( 'url' ) . '/wp-admin/post.php?post=' . $global_settings['ha_ext_scroll_to_top']['post_id'] . '&action=elementor">Clicking Here</a></strong>', 'happy-elementor-addons' ),
					'content_classes' => 'eael-warning',
					'separator'       => 'before',
					'condition'       => [
						'ha_ext_scroll_to_top' => 'yes',
					],
				]
			);
		} else {
			$element->add_control(
				'ha_ext_scroll_to_top_global',
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
						'ha_ext_scroll_to_top' => 'yes',
					],
				]
			);

			$element->add_control(
				'ha_ext_scroll_to_top_global_display_condition',
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
						'ha_ext_scroll_to_top'        => 'yes',
						'ha_ext_scroll_to_top_global' => 'yes',
					],
					'separator' => 'before',
				]
			);
		}

		$element->add_control(
			'ha_ext_scroll_to_top_position_text',
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
					'ha_ext_scroll_to_top' => 'yes',
				],
			]
		);

		$element->add_control(
			'ha_ext_scroll_to_top_position_bottom',
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
					'ha_ext_scroll_to_top' => 'yes',
				],
			]
		);

		$element->add_control(
			'ha_ext_scroll_to_top_position_left',
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
					'ha_ext_scroll_to_top'               => 'yes',
					'ha_ext_scroll_to_top_position_text' => 'bottom-left',
				],
			]
		);

		$element->add_control(
			'ha_ext_scroll_to_top_position_right',
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
					'ha_ext_scroll_to_top'               => 'yes',
					'ha_ext_scroll_to_top_position_text' => 'bottom-right',
				],
			]
		);

		$element->add_control(
			'ha_ext_scroll_to_top_button_width',
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
					'ha_ext_scroll_to_top' => 'yes',
				],
			]
		);

		$element->add_control(
			'ha_ext_scroll_to_top_button_height',
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
					'ha_ext_scroll_to_top' => 'yes',
				],
			]
		);

		$element->add_control(
			'ha_ext_scroll_to_top_z_index',
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
					'ha_ext_scroll_to_top' => 'yes',
				],
			]
		);

		$element->add_control(
			'ha_ext_scroll_to_top_button_opacity',
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
					'ha_ext_scroll_to_top' => 'yes',
				],
			]
		);

		$element->add_control(
			'ha_ext_scroll_to_top_button_icon_image',
			[
				'label'     => esc_html__( 'Icon', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'fas fa-chevron-up',
					'library' => 'fa-solid',
				],
				'separator' => 'before',
				'condition' => [
					'ha_ext_scroll_to_top' => 'yes',
				],
			]
		);

		$element->add_control(
			'ha_ext_scroll_to_top_button_icon_size',
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
					'ha_ext_scroll_to_top' => 'yes',
				],
			]
		);

		$element->add_control(
			'ha_ext_scroll_to_top_button_icon_svg_size',
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
					'ha_ext_scroll_to_top' => 'yes',
				],
			]
		);

		$element->add_control(
			'ha_ext_scroll_to_top_button_icon_color',
			[
				'label'     => __( 'Icon Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button i' => 'color: {{VALUE}}',
					'.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button svg' => 'fill: {{VALUE}}',
				],
				'condition' => [
					'ha_ext_scroll_to_top' => 'yes',
				],
			]
		);

		$element->add_control(
			'ha_ext_scroll_to_top_button_bg_color',
			[
				'label'     => __( 'Background Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => [
					'.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'ha_ext_scroll_to_top' => 'yes',
				],
			]
		);

		$element->add_control(
			'ha_ext_scroll_to_top_button_border_radius',
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
					'ha_ext_scroll_to_top' => 'yes',
				],
			]
		);

		$element->end_controls_section();
	}


	public function register_hide_title_control( $document ) {
		$document->start_injection(
			[
				'of'       => 'post_status',
				'fallback' => [
					'of' => 'post_title',
				],
			]
		);

		$document->add_control(
			'ha_ext_scroll_to_top_2',
			[
				'label'        => __( 'Enable Scroll to Top', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'happy-elementor-addons' ),
				'label_off'    => __( 'No', 'happy-elementor-addons' ),
				'return_value' => 'yes',
			]
		);

		$document->end_injection();
	}

	public function render_global_html() {

		$post_id         = get_the_ID();
		$html            = '';
		$global_settings = $settings_data = $document = [];
		$document_settings_data = '';

		$document        = \Elementor\Plugin::$instance->documents->get( $post_id, false );
		if ( isset( $document ) && is_object( $document ) ) {
			$document_settings_data = $document->get_settings();
		}

		// error_log( print_r( $document , 1 ) );
		// error_log( print_r( $document_settings_data , 1 ) );
		// error_log( print_r( $this->elementor_get_setting('hello_header_logo_display') , 1 ) );

		return;

		//Scroll to Top
		// if ( $this->get_settings( 'scroll-to-top' ) == true ) {

			$scroll_to_top_status = $scroll_to_top_status_global = false;

			if ( isset( $document_settings_data['ha_ext_scroll_to_top'] ) && $document_settings_data['ha_ext_scroll_to_top'] == 'yes' ) {
				$scroll_to_top_status        = true;
				$settings_data_scroll_to_top = $document_settings_data;
			} elseif ( isset( $global_settings['ha_ext_scroll_to_top']['enabled'] ) && $global_settings['ha_ext_scroll_to_top']['enabled'] ) {
				$scroll_to_top_status        = true;
				$scroll_to_top_status_global = true;
				$settings_data_scroll_to_top = $global_settings['ha_ext_scroll_to_top'];
			}

			if ( $scroll_to_top_status ) {
				if ( $scroll_to_top_status_global ) {
					//global status is true only when locally scroll to top is disabled.
					$this->scroll_to_top_global_css( $global_settings );
				}
				$scroll_to_top_icon_image = ! empty( $settings_data_scroll_to_top['ha_ext_scroll_to_top_button_icon_image'] )
											? $settings_data_scroll_to_top['ha_ext_scroll_to_top_button_icon_image']['value'] : '';

				if ( isset( $scroll_to_top_icon_image['url'] ) ) {
					ob_start();
					Icons_Manager::render_icon( $settings_data_scroll_to_top['ha_ext_scroll_to_top_button_icon_image'], [ 'aria-hidden' => 'true' ] );
					$scroll_to_top_icon_html = ob_get_clean();
				} else {
					$scroll_to_top_icon_html = "<i class='$scroll_to_top_icon_image'></i>";
				}

				$scroll_to_top_html = "<div class='eael-ext-scroll-to-top-wrap scroll-to-top-hide'><span class='eael-ext-scroll-to-top-button'>$scroll_to_top_icon_html</span></div>";

				$scroll_to_top_global_display_condition = isset( $settings_data_scroll_to_top['ha_ext_scroll_to_top_global_display_condition'] ) ? $settings_data_scroll_to_top['ha_ext_scroll_to_top_global_display_condition'] : 'all';

				if ( isset( $settings_data_scroll_to_top['post_id'] ) && $settings_data_scroll_to_top['post_id'] != get_the_ID() ) {
					if ( get_post_status( $settings_data_scroll_to_top['post_id'] ) != 'publish' ) {
						$scroll_to_top_html = '';
					} elseif ( $scroll_to_top_global_display_condition == 'pages' && ! is_page() ) {
							$scroll_to_top_html = '';
					} elseif ( $scroll_to_top_global_display_condition == 'posts' && ! is_single() ) {
							$scroll_to_top_html = '';
					}
				}

				if ( ! empty( $scroll_to_top_html ) ) {
					// wp_enqueue_script( 'eael-scroll-to-top' );
					// wp_enqueue_style( 'eael-scroll-to-top' );

					$html .= $scroll_to_top_html;
				}
			}
		// }
		printf( '%1$s', $html );
	}

	public function elementor_get_setting( $setting_id ) {
		$hello_elementor_setting = [];

		$return = '';

		if ( ! isset( $hello_elementor_settings['kit_settings'] ) ) {
			$kit = \Elementor\Plugin::$instance->documents->get( \Elementor\Plugin::$instance->kits_manager->get_active_id(), false );
			$hello_elementor_settings['kit_settings'] = $kit->get_settings();
		}

		if ( isset( $hello_elementor_settings['kit_settings'][ $setting_id ] ) ) {
			$return = $hello_elementor_settings['kit_settings'][ $setting_id ];
		}

		return $return;
	}

	public function init_site_settings( \Elementor\Core\Kits\Documents\Kit $kit ) {
		$kit->register_tab( 'hello-settings-header-2', Scroll_To_Top_Kit_Setings::class );
	}

}
new Scroll_To_Top();
