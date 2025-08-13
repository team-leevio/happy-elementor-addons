<?php
namespace Happy_Addons\Elementor\Extensions;

use \Elementor\Controls_Manager;


class Custom_Js {

	private static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		 return self::$instance;
	}


	public function init() {
		add_action( 'elementor/documents/register_controls', [$this, 'scroll_to_top_controls'], 10 );
		add_filter( 'elementor/document/save/data', [$this, 'before_save_data'], 10, 1 );
		add_action( 'wp_footer', [$this, 'render_scroll_to_top_html'] );
	}

	public function scroll_to_top_controls( $element ) {

		$element->start_controls_section(
            'ha_page_custom_js_section',
            [
                'label' => __( 'Custom JS', 'happy-elementor-addons' ) . ha_get_section_icon(),
                'tab'   => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $element->add_control(
            'ha_page_custom_js',
            [
                'label' => __('Add your own custom JS here', 'happy-elementor-addons'),
                'show_label' => true,
                'type' => Controls_Manager::CODE,
                'language' => 'javascript',
            ]
        );

	    if ( ! current_user_can( 'administrator' ) ) {
			$element->add_control(
				'ha_page_custom_js_admin_notice',
				[
					'type' => Controls_Manager::NOTICE,
					'notice_type' => 'warning',
					'dismissible' => false,
					'content' => __( 'Only the Administrator can add or edit JavaScript code from here', 'happy-elementor-addons' ),
				]
			);
	    }

        $element->end_controls_section();
	}

	public function before_save_data( $data ) {
		if ( ! current_user_can( 'administrator' ) ) {
			$page_setting = get_post_meta( get_the_ID(), '_elementor_page_settings', true );
			if ( isset( $data['settings']['ha_page_custom_js'] ) && isset( $page_setting['ha_page_custom_js'] ) ) {
				$prev_js = isset( $page_setting['ha_page_custom_js'] ) ? trim( $page_setting['ha_page_custom_js'] ) : '';
				$data['settings']['ha_page_custom_js'] = $prev_js;
			}
		}
		return $data;
	}

	public function render_scroll_to_top_html() {
		$post_id                = get_the_ID();
		$page_setting = get_post_meta( $post_id, '_elementor_page_settings', true );
		$custom_js    = isset( $page_setting['ha_page_custom_js'] ) ? trim( $page_setting['ha_page_custom_js'] ) : '';
		if ( $custom_js ) {
			wp_add_inline_script( 'happy-elementor-addons', $custom_js );
		}
	}
}
