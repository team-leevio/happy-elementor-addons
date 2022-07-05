<?php
namespace Happy_Addons\Elementor\Extension;

use \Elementor\Controls_Manager;


class Scroll_To_Top {


	public function __construct() {
		$feature_file = HAPPY_ADDONS_DIR_PATH . 'extensions/scroll-to-top-kit-settings.php';

		if ( is_readable( $feature_file ) ) {
			include_once $feature_file;
		}

		add_action( 'elementor/kit/register_tabs', [ $this, 'init_site_settings' ], 1, 40 );
		add_action( 'elementor/documents/register_controls', [$this, 'register_controls'], 10 );
		// add_action( 'elementor/documents/register_controls', [$this, 'register_hide_title_control'] );
		add_action( 'wp_footer', [$this, 'render_global_html'] );
	}

	public function register_controls( $element ) {

		$element->start_controls_section(
			'ha_scroll_to_top_single_section',
			[
				'label' => __( 'Scroll to Top', 'happy-elementor-addons' ) . ha_get_section_icon(),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);

		$element->add_control(
			'ha_scroll_to_top_single_disable',
			[
				'label'        => __( 'Disable Scroll to Top', 'happy-elementor-addons' ),
				'description'        => __( 'Disable Scroll to Top For This Page', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'happy-elementor-addons' ),
				'label_off'    => __( 'No', 'happy-elementor-addons' ),
				'return_value' => 'yes',
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
			'ha_scroll_to_top_single_disable',
			[
				'label'        => __( 'Disable Scroll to Top', 'happy-elementor-addons' ). ha_get_section_icon(),
				'description'        => __( 'Disable Scroll to Top For This Page', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'happy-elementor-addons' ),
				'label_off'    => __( 'No', 'happy-elementor-addons' ),
				'return_value' => 'yes',
			]
		);

		$document->end_injection();
	}

	public function render_global_html() {

		$post_id                = get_the_ID();
		$html                   = '';
		$global_settings        = $settings_data = $document = [];
		$document_settings_data = [];

		$document = \Elementor\Plugin::$instance->documents->get( $post_id, false );
		if ( isset( $document ) && is_object( $document ) ) {
			$document_settings_data = $document->get_settings();
		}

		// error_log( print_r( $document , 1 ) );
		// error_log( print_r( $document_settings_data , 1 ) );
		// if ( isset( $document_settings_data['ha_scroll_to_top_single_disable'] ) && $document_settings_data['ha_scroll_to_top_single_disable'] == 'yes' ) {

		// }
		$scroll_to_top_global = $this->elementor_get_setting( 'ha_scroll_to_top_global' );
		// error_log( print_r( $this->elementor_get_setting( 'ha_scroll_to_top_global' ), 1 ) );

		$scroll_to_top = false;

		if ( 'yes' == $scroll_to_top_global ) {
			$scroll_to_top = true;
		}

		if ( isset( $document_settings_data['ha_scroll_to_top_single_disable'] ) && 'yes' == $document_settings_data['ha_scroll_to_top_single_disable'] ) {
			$scroll_to_top = false;
		}

		error_log( print_r( $scroll_to_top , 1 ) );

		if ( $scroll_to_top ) {

			$scroll_to_top_icon = ! empty( $this->elementor_get_setting( 'ha_scroll_to_top_button_icon' ) ) ? $this->elementor_get_setting( 'ha_scroll_to_top_button_icon' )['value'] : '';
			$scroll_to_top_icon_html  = "<i class='$scroll_to_top_icon'></i>";

			$scroll_to_top_html = "<div class='ha-scroll-to-top-wrap ha-scroll-to-top-hide'><span class='ha-scroll-to-top-button'>$scroll_to_top_icon_html</span></div>";

			printf( '%1$s', $scroll_to_top_html );

			wp_add_inline_script(
				'happy-elementor-addons',
				';(function ($) {
					"use strict";
					$(function () {
					  var offset = 100;
					  var speed = 300;
					  var duration = 300;
					  if ($(this).scrollTop() > offset) {
						$(".ha-scroll-to-top-wrap").removeClass("ha-scroll-to-top-hide");
					  }
					  $(window).scroll(function () {
						if ($(this).scrollTop() < offset) {
						  $(".ha-scroll-to-top-wrap").fadeOut(duration);
						} else {
						  $(".ha-scroll-to-top-wrap").fadeIn(duration);
						}
					  });
					  $(".ha-scroll-to-top-wrap").on("click", function () {
						$("html, body").animate({ scrollTop: 0 }, speed);
						return false;
					  });
					});
				  })(jQuery);
				'
			);

			// wp_enqueue_script(
			// 	'happy-scroll-to-top',
			// 	HAPPY_ADDONS_ASSETS . 'js/scroll-to-top.js',
			// 	['happy-elementor-addons'],
			// 	HAPPY_ADDONS_VERSION,
			// 	true
			// );
		}
	}

	public function render_global_html_backup() {

		$post_id                = get_the_ID();
		$html                   = '';
		$global_settings        = $settings_data = $document = [];
		$document_settings_data = '';

		$document = \Elementor\Plugin::$instance->documents->get( $post_id, false );
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
			$kit                                      = \Elementor\Plugin::$instance->documents->get( \Elementor\Plugin::$instance->kits_manager->get_active_id(), false );
			$hello_elementor_settings['kit_settings'] = $kit->get_settings();
		}

		if ( isset( $hello_elementor_settings['kit_settings'][ $setting_id ] ) ) {
			$return = $hello_elementor_settings['kit_settings'][ $setting_id ];
		}

		return $return;
	}

	public function init_site_settings( \Elementor\Core\Kits\Documents\Kit $kit ) {
		$kit->register_tab( 'ha-scroll-to-top-kit-settings', Scroll_To_Top_Kit_Setings::class );
	}

}
new Scroll_To_Top();
