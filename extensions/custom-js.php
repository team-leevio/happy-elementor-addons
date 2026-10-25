<?php
namespace Happy_Addons\Elementor\Extensions;

use \Elementor\Controls_Manager;


class Custom_Js {

	private static $instance = null;

	const META_KEY    = '_elementor_page_settings';
	const SETTING_KEY = 'ha_page_custom_js';

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		 return self::$instance;
	}


	public function init() {
		add_action( 'elementor/documents/register_controls', [$this, 'scroll_to_top_controls'], 10 );
		add_filter( 'elementor/document/save/data', [$this, 'before_save_data'], 10, 1 );
		add_filter( 'update_post_metadata', [$this, 'guard_page_settings_write'], 10, 5 );
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

		if ( current_user_can( 'unfiltered_html' ) ) {
			$element->add_control(
				self::SETTING_KEY,
				[
					'label' => __('Add your own custom JS here', 'happy-elementor-addons'),
					'show_label' => true,
					'type' => Controls_Manager::CODE,
					'language' => 'javascript',
				]
			);
		} else {
			$element->add_control(
				self::SETTING_KEY . '_admin_notice',
				[
					'type' => Controls_Manager::NOTICE,
					'notice_type' => 'warning',
					'dismissible' => false,
					'content' => __( 'You do not have permission to add or edit JavaScript code here', 'happy-elementor-addons' ),
				]
			);
		}

        $element->end_controls_section();
	}

	/**
	 * Elementor editor save path. Restore the previous (trusted) value or
	 * strip the key for users who may not author unfiltered JavaScript.
	 */
	public function before_save_data( $data ) {
		if ( ! current_user_can( 'unfiltered_html' ) && isset( $data['settings'][ self::SETTING_KEY ] ) ) {
			$page_setting = get_post_meta( get_the_ID(), self::META_KEY, true );
			if ( is_array( $page_setting ) && isset( $page_setting[ self::SETTING_KEY ] ) ) {
				$data['settings'][ self::SETTING_KEY ] = trim( $page_setting[ self::SETTING_KEY ] );
			} else {
				unset( $data['settings'][ self::SETTING_KEY ] );
			}
		}
		return $data;
	}

	/**
	 * Guard every write to the page-settings meta — including the WP REST
	 * post-meta route, XML-RPC and direct update_post_meta() calls — which
	 * the elementor/document/save/data filter never sees. Unprivileged
	 * users can never introduce or alter the stored custom JS.
	 */
	public function guard_page_settings_write( $check, $post_id, $meta_key, $value, $prev_value = '' ) {
		if ( null !== $check || self::META_KEY !== $meta_key ) {
			return $check;
		}

		if ( current_user_can( 'unfiltered_html' ) ) {
			return $check;
		}

		if ( ! is_array( $value ) || ! array_key_exists( self::SETTING_KEY, $value ) ) {
			return $check;
		}

		$stored = get_post_meta( $post_id, self::META_KEY, true );
		if ( is_array( $stored ) && array_key_exists( self::SETTING_KEY, $stored ) ) {
			$value[ self::SETTING_KEY ] = $stored[ self::SETTING_KEY ];
		} else {
			unset( $value[ self::SETTING_KEY ] );
		}

		remove_filter( 'update_post_metadata', [$this, 'guard_page_settings_write'], 10 );
		update_post_meta( $post_id, $meta_key, $value );
		add_filter( 'update_post_metadata', [$this, 'guard_page_settings_write'], 10, 5 );

		return true;
	}

	/**
	 * Render gate: never output the stored JS unless the user who last
	 * authored or edited the post may write unfiltered JavaScript.
	 */
	public function render_scroll_to_top_html() {
		$post_id = get_the_ID();
		if ( ! $post_id ) {
			return;
		}

		$page_setting = get_post_meta( $post_id, self::META_KEY, true );
		$custom_js    = ( is_array( $page_setting ) && isset( $page_setting[ self::SETTING_KEY ] ) ) ? trim( $page_setting[ self::SETTING_KEY ] ) : '';
		if ( ! $custom_js ) {
			return;
		}

		$trusted_author_id = (int) get_post_meta( $post_id, '_edit_last', true );
		if ( ! $trusted_author_id ) {
			$trusted_author_id = (int) get_post_field( 'post_author', $post_id );
		}

		if ( ! $trusted_author_id || ! user_can( $trusted_author_id, 'unfiltered_html' ) ) {
			return;
		}

		wp_add_inline_script( 'happy-elementor-addons', $custom_js );
	}
}
