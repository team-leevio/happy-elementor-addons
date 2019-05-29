<?php
namespace Happy_Addons\Elementor;

defined( 'ABSPATH' ) || die();

class Asset_Manager {

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

		add_action( 'elementor/editor/before_enqueue_scripts', [$this, 'enqueue_editor_scripts'] );

        add_action( 'elementor/preview/enqueue_styles', [$this, 'enqueue_preview_style'] );


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
            'happy-icon',
            HAPPY_ASSETS . 'fonts/style.min.css',
            null,
            Base::VERSION
        );

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
            'slick',
            HAPPY_ASSETS . 'vendor/slick/slick.css',
            null,
            Base::VERSION
        );

        wp_enqueue_style(
            'slick-theme',
            HAPPY_ASSETS . 'vendor/slick/slick-theme.css',
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
            'jquery-slick',
            HAPPY_ASSETS . 'vendor/slick/slick' . $suffix . 'js',
            ['jquery'],
            Base::VERSION,
            true
        );

        wp_enqueue_script(
            'anime',
            HAPPY_ASSETS . 'vendor/anime/lib/anime.min.js',
            null,
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

    public function enqueue_editor_scripts() {
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
            ['elementor-editor'],
            Base::VERSION,
            true
		);

	}

    public function enqueue_preview_style() {
        if( class_exists( 'WeForms' ) ) {
            wp_enqueue_style(
                'happy-elementor-weform-preview',
                plugins_url( '/weforms/assets/wpuf/css/frontend-forms.css', 'weforms' ),
                null,
                Base::VERSION
            );
        }
    }

}
