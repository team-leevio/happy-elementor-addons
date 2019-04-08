<?php
namespace Happy_Addons\Elementor;

defined( 'ABSPATH' ) || die();

class Assets_Manager {

    private static $instance = null;

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init() {
        // Frontend scripts
        add_action( 'wp_enqueue_scripts', [$this, 'enqueue_frontend_scripts'] );

        // Placeholder image replacement
        add_filter( 'elementor/utils/get_placeholder_image_src', [$this, 'set_placeholder_image'] );
    }

    public function set_placeholder_image() {
        return HAPPY_ASSETS . 'imgs/placeholder.jpg';
    }

    /**
     * Enqueue frontend scripts
     */
    public function enqueue_frontend_scripts() {
        $suffix = ha_is_script_debug_enabled() ? '.min.' : '.';

        wp_enqueue_style(
            'twentytwenty',
            HAPPY_ASSETS . 'vendor/twentytwenty/css/twentytwenty.css',
            null,
            Base::VERSION
        );

        wp_enqueue_style(
            'justifiedGallery',
            HAPPY_ASSETS . 'vendor/justifiedGallery/css/justifiedGallery.min.css',
            null,
            Base::VERSION
        );

        wp_enqueue_style(
            'magnific-popup',
            HAPPY_ASSETS . 'vendor/magnific-popup/magnific-popup.css',
            null,
            Base::VERSION
        );

        wp_enqueue_style(
            'owl-carousel',
            HAPPY_ASSETS . 'vendor/owl-carousel/assets/owl.carousel.min.css',
            null,
            Base::VERSION
        );

        wp_enqueue_style(
            'owl-theme-default',
            HAPPY_ASSETS . 'vendor/owl-carousel/assets/owl.theme.default.min.css',
            null,
            Base::VERSION
        );

        wp_enqueue_style(
            'happy-elementor-addons',
            HAPPY_ASSETS . 'css/main' . $suffix . 'css',
            ['elementor-frontend'],
            Base::VERSION
        );

        // Scripts
        wp_enqueue_script(
            'jquery-event-move',
            HAPPY_ASSETS . 'vendor/twentytwenty/js/jquery.event.move.js',
            ['jquery'],
            Base::VERSION,
            true
        );

        wp_enqueue_script(
            'jquery-twentytwenty',
            HAPPY_ASSETS . 'vendor/twentytwenty/js/jquery.twentytwenty.js',
            ['jquery-event-move'],
            Base::VERSION,
            true
        );

        wp_enqueue_script(
            'jquery-justifiedGallery',
            HAPPY_ASSETS . 'vendor/justifiedGallery/js/jquery.justifiedGallery.min.js',
            ['jquery'],
            Base::VERSION,
            true
        );

        wp_enqueue_script(
            'jquery-magnific-popup',
            HAPPY_ASSETS . 'vendor/magnific-popup/jquery.magnific-popup.min.js',
            ['jquery'],
            Base::VERSION,
            true
        );

        wp_enqueue_script(
            'jquery-isotope',
            HAPPY_ASSETS . 'vendor/jquery.isotope.js',
            ['jquery'],
            Base::VERSION,
            true
        );

        wp_enqueue_script(
            'jquery-owl-carousel',
            HAPPY_ASSETS . 'vendor/owl-carousel/owl.carousel.min.js',
            ['jquery'],
            Base::VERSION,
            true
        );

        wp_enqueue_script(
            'happy-elementor-addons',
            HAPPY_ASSETS . 'js/happy-addons' . $suffix . 'js',
            ['jquery', 'imagesloaded'],
            Base::VERSION,
            true
        );
    }
}
