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
		add_action( 'elementor/documents/register_controls', [$this, 'scroll_to_top_controls'], 10 );
		add_action( 'wp_footer', [$this, 'render_scroll_to_top_html'] );
	}

	public function scroll_to_top_controls( $element ) {

		$scroll_to_top_global = $this->elementor_get_setting( 'ha_scroll_to_top_global' );
		if ( 'yes' !== $scroll_to_top_global ) {
			return;
		}

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

	public function render_scroll_to_top_html() {

		$post_id                = get_the_ID();
		$document = [];
		$document_settings_data = [];

		$document = \Elementor\Plugin::$instance->documents->get( $post_id, false );
		if ( isset( $document ) && is_object( $document ) ) {
			$document_settings_data = $document->get_settings();
		}

		$scroll_to_top_global = $this->elementor_get_setting( 'ha_scroll_to_top_global' );
		// error_log( print_r( $this->elementor_get_setting( 'ha_scroll_to_top_global' ), 1 ) );

		$scroll_to_top = false;

		if ( 'yes' == $scroll_to_top_global ) {
			$scroll_to_top = true;
		}

		if ( isset( $document_settings_data['ha_scroll_to_top_single_disable'] ) && 'yes' == $document_settings_data['ha_scroll_to_top_single_disable'] ) {
			$scroll_to_top = false;
		}

		//error_log( print_r( $scroll_to_top , 1 ) );

		if ( $scroll_to_top ) {

			$scroll_to_top_icon = ! empty( $this->elementor_get_setting( 'ha_scroll_to_top_button_icon' ) ) ? $this->elementor_get_setting( 'ha_scroll_to_top_button_icon' )['value'] : '';
			$scroll_to_top_icon_html  = "<i class='$scroll_to_top_icon'></i>";

			$scroll_to_top_html = "<div class='ha-scroll-to-top-wrap ha-scroll-to-top-hide'><span class='ha-scroll-to-top-button'>$scroll_to_top_icon_html</span></div>";

			printf( '%1$s', $scroll_to_top_html );

			wp_add_inline_script(
				'happy-elementor-addons',
				'!function(o){"use strict";o((function(){o(this).scrollTop()>100&&o(".ha-scroll-to-top-wrap").removeClass("ha-scroll-to-top-hide"),o(window).scroll((function(){o(this).scrollTop()<100?o(".ha-scroll-to-top-wrap").fadeOut(300):o(".ha-scroll-to-top-wrap").fadeIn(300)})),o(".ha-scroll-to-top-wrap").on("click",(function(){return o("html, body").animate({scrollTop:0},300),!1}))}))}(jQuery);'
			);
		}
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
