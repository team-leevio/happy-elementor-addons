<?php
namespace Happy_Addons\Elementor;

use Happy_Addons\Elementor\Assets\OnDemand_Loader;

defined( 'ABSPATH' ) || die();

class Admin {

    public static function init() {
        add_action( 'admin_bar_menu', [__CLASS__, 'add_toolbar_items'], 500 );
        add_action( 'wp_enqueue_scripts', [__CLASS__, 'enqueue_assets'] );
        add_action( 'wp_ajax_ha_clear_cache', [__CLASS__, 'clear_cache' ] );
    }

    public static function clear_cache() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( ! check_ajax_referer( 'ha_clear_cache', 'nonce' ) ) {
            wp_send_json_error();
        }
        $type = isset( $_POST['type'] ) ? $_POST['type'] : '';

        if ( $type === 'page' ) {
            $post_id = isset( $_POST['post_id'] ) ? $_POST['post_id'] : 0;
            OnDemand_Loader::clean_only_cache( $post_id );
        } elseif ( $type === 'all' ) {
            OnDemand_Loader::clean_all_cache();
        }
        wp_send_json_success();
    }

    public static function enqueue_assets() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        wp_enqueue_style(
            'happy-admin-bar',
            HAPPY_ASSETS . 'admin/css/admin-bar.css',
            null,
            Base::VERSION
        );

        wp_enqueue_script(
            'happy-admin-bar',
            HAPPY_ASSETS . 'admin/js/admin-bar.js',
            ['jquery'],
            Base::VERSION,
            true
        );

        wp_localize_script(
            'happy-admin-bar',
            'HappyAdmin',
            [
                'nonce' => wp_create_nonce( 'ha_clear_cache' ),
                'post_id' => get_queried_object_id(),
                'ajax_url' => admin_url( 'admin-ajax.php' ),
            ]
        );
    }

    public static function add_toolbar_items( $admin_bar ) {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $admin_bar->add_menu( array(
            'id'    => 'happy-addons',
            'title' => __( 'HappyAddons', 'happy-elementor-addons' ),
            'href'  => '#',
        ) );

        if ( is_singular() ) {
            $admin_bar->add_menu( array(
                'id'    => 'ha-clear-page-cache',
                'parent' => 'happy-addons',
                'title' => '<i class="dashicons dashicons-update-alt"></i> ' . __( 'Clear Page Cache', 'happy-elementor-addons' ),
                'href'  => '#',
                'meta' => [
                    'class' => 'hajs-clear-cache ha-clear-page-cache',
                ]
            ) );
        }

        $admin_bar->add_menu( array(
            'id'    => 'ha-clear-all-cache',
            'parent' => 'happy-addons',
            'title' => '<i class="dashicons dashicons-update-alt"></i> ' . __( 'Clear All Cache', 'happy-elementor-addons' ),
            'href'  => '#',
            'meta' => [
                'class' => 'hajs-clear-cache ha-clear-all-cache',
            ]
        ) );
    }
}
