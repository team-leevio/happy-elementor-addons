<?php

namespace Happy_Addons\Elementor;

defined( 'ABSPATH' ) || die();

class Assets_Manager {

    /**
     * Bind hook and run internal methods here
     */
    public static function init() {
        // Frontend scripts
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'frontend_register' ] );
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'frontend_enqueue' ], 99 );

        // Edit and preview enqueue
        add_action( 'elementor/preview/enqueue_styles', [ __CLASS__, 'enqueue_preview_style' ] );

        add_action( 'elementor/editor/before_enqueue_scripts', [ __CLASS__, 'enqueue_editor_scripts' ] );

        // Placeholder image replacement
        add_filter( 'elementor/utils/get_placeholder_image_src', [ __CLASS__, 'set_placeholder_image' ] );
    }

    public static function set_placeholder_image() {
        return HAPPY_ADDONS_ASSETS . 'imgs/placeholder.jpg';
    }

    public static function frontend_register() {
        $suffix = ha_is_script_debug_enabled() ? '.' : '.min.';

        wp_register_style(
            'happy-icon',
            HAPPY_ADDONS_ASSETS . 'fonts/style.min.css',
            null,
            HAPPY_ADDONS_VERSION
        );

        /**
         * Image comparasion
         */
        wp_register_style(
            'twentytwenty',
            HAPPY_ADDONS_ASSETS . 'vendor/twentytwenty/css/twentytwenty.css',
            null,
            HAPPY_ADDONS_VERSION
        );

        wp_register_script(
            'jquery-event-move',
            HAPPY_ADDONS_ASSETS . 'vendor/twentytwenty/js/jquery.event.move.js',
            [ 'jquery' ],
            HAPPY_ADDONS_VERSION,
            true
        );

        wp_register_script(
            'jquery-twentytwenty',
            HAPPY_ADDONS_ASSETS . 'vendor/twentytwenty/js/jquery.twentytwenty.js',
            [ 'jquery-event-move' ],
            HAPPY_ADDONS_VERSION,
            true
        );

        /**
         * Justified Grid
         */
        wp_register_style(
            'justifiedGallery',
            HAPPY_ADDONS_ASSETS . 'vendor/justifiedGallery/css/justifiedGallery.min.css',
            null,
            HAPPY_ADDONS_VERSION
        );

        wp_register_script(
            'jquery-justifiedGallery',
            HAPPY_ADDONS_ASSETS . 'vendor/justifiedGallery/js/jquery.justifiedGallery.min.js',
            [ 'jquery' ],
            HAPPY_ADDONS_VERSION,
            true
        );

        /**
         * Carousel and Slider
         */
        wp_register_style(
            'slick',
            HAPPY_ADDONS_ASSETS . 'vendor/slick/slick.css',
            null,
            HAPPY_ADDONS_VERSION
        );

        wp_register_style(
            'slick-theme',
            HAPPY_ADDONS_ASSETS . 'vendor/slick/slick-theme.css',
            null,
            HAPPY_ADDONS_VERSION
        );

        wp_register_script(
            'jquery-slick',
            HAPPY_ADDONS_ASSETS . 'vendor/slick/slick' . $suffix . 'js',
            [ 'jquery' ],
            HAPPY_ADDONS_VERSION,
            true
        );

        /**
         * Masonry grid
         */
        wp_register_script(
            'jquery-isotope',
            HAPPY_ADDONS_ASSETS . 'vendor/jquery.isotope.js',
            [ 'jquery' ],
            HAPPY_ADDONS_VERSION,
            true
        );

        /**
         * Floating effects
         */
        wp_register_script(
            'anime',
            HAPPY_ADDONS_ASSETS . 'vendor/anime/lib/anime.min.js',
            null,
            HAPPY_ADDONS_VERSION,
            true
        );

        wp_register_style(
            'happy-elementor-addons',
            HAPPY_ADDONS_ASSETS . 'css/main' . $suffix . 'css',
            [ 'elementor-frontend' ],
            HAPPY_ADDONS_VERSION
        );

        // Happy addons script
        wp_register_script(
            'happy-elementor-addons',
            HAPPY_ADDONS_ASSETS . 'js/happy-addons' . $suffix . 'js',
            [ 'imagesloaded', 'jquery' ],
            HAPPY_ADDONS_VERSION,
            true
        );
    }

    public static function frontend_enqueue() {
        global $post;
        if ( is_null( $post ) || ! is_object( $post ) ) {
            return;
        }

        if ( ! isset( $post->ID ) || ! Cache_Manager::is_built_with_elementor( $post->ID ) ) {
            return;
        }

        $widgets_cache = new Widgets_Cache( $post->ID );

        $is_happy_mode_enabled = apply_filters( 'happyaddons_is_happy_mode_enabled', true );

        if ( $is_happy_mode_enabled && ! Cache_Manager::is_editing_mode() && $widgets_cache->has() ) {
            $assets_cache = new Assets_Cache( $post->ID );
            $assets_cache->enqueue_libraries();
            $assets_cache->enqueue();
            wp_enqueue_script( 'happy-elementor-addons' );
            return;
        }

        wp_enqueue_style( 'happy-icon' );

        wp_enqueue_style( 'twentytwenty' );
        wp_enqueue_script( 'jquery-event-move' );
        wp_enqueue_script( 'jquery-twentytwenty' );

        wp_enqueue_style( 'justifiedGallery' );
        wp_enqueue_script( 'jquery-justifiedGallery' );

        wp_enqueue_style( 'slick' );
        wp_enqueue_style( 'slick-theme' );
        wp_enqueue_script( 'jquery-slick' );

        wp_enqueue_script( 'jquery-isotope' );

        wp_enqueue_script( 'elementor-waypoints' );
        wp_enqueue_script( 'jquery-numerator' );

        wp_enqueue_script( 'anime' );

        // Self assets
        wp_enqueue_style( 'happy-elementor-addons' );
        wp_enqueue_script( 'happy-elementor-addons' );
    }

    public static function enqueue_editor_scripts() {
        wp_enqueue_style(
            'happy-icon',
            HAPPY_ADDONS_ASSETS . 'fonts/style.min.css',
            null,
            HAPPY_ADDONS_VERSION
        );

        wp_enqueue_style(
            'happy-elementor-addons-admin',
            HAPPY_ADDONS_ASSETS . 'admin/css/main.min.css',
            null,
            HAPPY_ADDONS_VERSION
        );

        wp_enqueue_script(
            'happy-elementor-addons-admin',
            HAPPY_ADDONS_ASSETS . 'admin/js/happy-addons.min.js',
            [ 'elementor-editor' ],
            HAPPY_ADDONS_VERSION,
            true
        );
    }

    public static function enqueue_preview_style() {
        if ( class_exists( 'WeForms' ) ) {
            wp_enqueue_style(
                'happy-elementor-weform-preview',
                plugins_url( '/weforms/assets/wpuf/css/frontend-forms.css', 'weforms' ),
                null,
                HAPPY_ADDONS_VERSION
            );
        }

        if ( class_exists( 'WPForms_Lite' ) ) {
            wp_enqueue_style(
                'happy-elementor-wpform-preview',
                plugins_url( '/wpforms-lite/assets/css/wpforms-full.css', 'wpforms-lite' ),
                null,
                HAPPY_ADDONS_VERSION
            );
        }

        if ( class_exists( 'Caldera_Forms' ) ) {
            wp_enqueue_style(
                'happy-elementor-caldera-preview',
                plugins_url( '/caldera-forms/assets/css/caldera-forms-front.css', 'caldera-forms' ),
                null,
                HAPPY_ADDONS_VERSION
            );
        }
    }
}
