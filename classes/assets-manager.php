<?php

namespace Happy_Addons\Elementor;

defined( 'ABSPATH' ) || die();

class Assets_Manager {

    /**
     * Bind hook and run internal methods here
     */
    public static function init() {
        // Frontend scripts
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_3rd_party_dependencies' ] );
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_self_dependencies' ], 99 );

        // Dashboard scripts
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'dashboard_enqueue_scripts' ] );

        // Edit and preview enqueue
        add_action( 'elementor/preview/enqueue_styles', [ __CLASS__, 'enqueue_preview_style' ] );

        add_action( 'elementor/editor/before_enqueue_scripts', [ __CLASS__, 'enqueue_editor_scripts' ] );

        // Placeholder image replacement
        add_filter( 'elementor/utils/get_placeholder_image_src', [ __CLASS__, 'set_placeholder_image' ] );
    }

    public static function set_placeholder_image() {
        return HAPPY_ASSETS . 'imgs/placeholder.jpg';
    }

    public static function enqueue_3rd_party_dependencies() {
        $suffix = ha_is_script_debug_enabled() ? '.' : '.min.';

        wp_enqueue_style(
            'happy-icon',
            HAPPY_ASSETS . 'fonts/style.min.css',
            null,
            Base::VERSION
        );

        /**
         * Image comparasion
         */
        wp_register_style(
            'twentytwenty',
            HAPPY_ASSETS . 'vendor/twentytwenty/css/twentytwenty.css',
            null,
            Base::VERSION
        );

        wp_register_script(
            'jquery-event-move',
            HAPPY_ASSETS . 'vendor/twentytwenty/js/jquery.event.move.js',
            [ 'jquery' ],
            Base::VERSION,
            true
        );

        wp_register_script(
            'jquery-twentytwenty',
            HAPPY_ASSETS . 'vendor/twentytwenty/js/jquery.twentytwenty.js',
            [ 'jquery-event-move' ],
            Base::VERSION,
            true
        );

        /**
         * Justified Grid
         */
        wp_register_style(
            'justifiedGallery',
            HAPPY_ASSETS . 'vendor/justifiedGallery/css/justifiedGallery.min.css',
            null,
            Base::VERSION
        );

        wp_register_script(
            'jquery-justifiedGallery',
            HAPPY_ASSETS . 'vendor/justifiedGallery/js/jquery.justifiedGallery.min.js',
            [ 'jquery' ],
            Base::VERSION,
            true
        );

        /**
         * Carousel and Slider
         */
        wp_register_style(
            'slick',
            HAPPY_ASSETS . 'vendor/slick/slick.css',
            null,
            Base::VERSION
        );

        wp_register_style(
            'slick-theme',
            HAPPY_ASSETS . 'vendor/slick/slick-theme.css',
            null,
            Base::VERSION
        );

        wp_register_script(
            'jquery-slick',
            HAPPY_ASSETS . 'vendor/slick/slick' . $suffix . 'js',
            [ 'jquery' ],
            Base::VERSION,
            true
        );

        /**
         * Masonry grid
         */
        wp_register_script(
            'jquery-isotope',
            HAPPY_ASSETS . 'vendor/jquery.isotope.js',
            [ 'jquery' ],
            Base::VERSION,
            true
        );

        /**
         * Floating effects
         */
        wp_register_script(
            'anime',
            HAPPY_ASSETS . 'vendor/anime/lib/anime.min.js',
            null,
            Base::VERSION,
            true
        );

        // Load used libraries only on frontend
        if ( ! Assets_Cache_Manager::in_development() && Assets_Cache_Manager::should_start() ) {
            Assets_Cache_Manager::enqueue_libraries();
        } else {
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
        }
    }

    public static function enqueue_self_dependencies() {
        $suffix = ha_is_script_debug_enabled() ? '.' : '.min.';

        wp_register_style(
            'happy-elementor-addons',
            HAPPY_ASSETS . 'css/main' . $suffix . 'css',
            [ 'elementor-frontend' ],
            Base::VERSION
        );

        if ( ! Assets_Cache_Manager::in_development() && Assets_Cache_Manager::should_start() ) {
            Assets_Cache_Manager::enqueue();
        } else {
            wp_enqueue_style( 'happy-elementor-addons' );
        }

        // Happy addons script
        wp_enqueue_script(
            'happy-elementor-addons',
            HAPPY_ASSETS . 'js/happy-addons' . $suffix . 'js',
            [ 'imagesloaded', 'jquery' ],
            Base::VERSION,
            true
        );
    }

    public static function dashboard_enqueue_scripts() {
        $currentScreen = get_current_screen();
        if ( $currentScreen->id != "elementor_page_happy-settings" ) {
            return;
        }
        wp_enqueue_style(
            'happy-dashboard',
            HAPPY_ASSETS . 'admin/css/dashboard.css',
            null,
            Base::VERSION
        );
    }

    public static function enqueue_editor_scripts() {
        wp_enqueue_style(
            'happy-icon',
            HAPPY_ASSETS . 'fonts/style.min.css',
            null,
            Base::VERSION
        );

        wp_enqueue_style(
            'happy-elementor-addons-admin',
            HAPPY_ASSETS . 'admin/css/main.min.css',
            null,
            Base::VERSION
        );

        wp_enqueue_script(
            'happy-elementor-addons-admin',
            HAPPY_ASSETS . 'admin/js/happy-addons.min.js',
            [ 'elementor-editor' ],
            Base::VERSION,
            true
        );
    }

    public static function enqueue_preview_style() {
        if ( class_exists( 'WeForms' ) ) {
            wp_enqueue_style(
                'happy-elementor-weform-preview',
                plugins_url( '/weforms/assets/wpuf/css/frontend-forms.css', 'weforms' ),
                null,
                Base::VERSION
            );
        }

        if ( class_exists( 'WPForms_Lite' ) ) {
            wp_enqueue_style(
                'happy-elementor-wpform-preview',
                plugins_url( '/wpforms-lite/assets/css/wpforms-full.css', 'wpforms-lite' ),
                null,
                Base::VERSION
            );
        }

        if ( class_exists( 'Caldera_Forms' ) ) {
            wp_enqueue_style(
                'happy-elementor-caldera-preview',
                plugins_url( '/caldera-forms/assets/css/caldera-forms-front.css', 'caldera-forms' ),
                null,
                Base::VERSION
            );
        }
    }
}
