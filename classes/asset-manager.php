<?php
namespace Happy_Addons\Elementor\Manager;

use Happy_Addons\Elementor\Base;

defined( 'ABSPATH' ) || die();

class Assets {

    /**
     * Bind hook and run internal methods here
     */
    public static function init() {
        // Frontend scripts
        add_action( 'wp_enqueue_scripts', [__CLASS__, 'enqueue_frontend_scripts'] );

        // Dashboard scripts
        add_action( 'admin_enqueue_scripts', [__CLASS__, 'dashboard_enqueue_scripts'] );

        // Edit and preview enqueue
        add_action( 'elementor/preview/enqueue_styles', [__CLASS__, 'enqueue_preview_style'] );

		add_action( 'elementor/editor/before_enqueue_scripts', [__CLASS__, 'enqueue_editor_scripts'] );

        // Placeholder image replacement
        add_filter( 'elementor/utils/get_placeholder_image_src', [__CLASS__, 'set_placeholder_image'] );
    }

    public static function set_placeholder_image() {
        return HAPPY_ASSETS . 'imgs/placeholder.jpg';
    }

    /**
     * Enqueue frontend scripts
     */
    public static function enqueue_frontend_scripts() {
		$suffix = ha_is_script_debug_enabled() ? '.' : '.';

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
            ['elementor-editor'],
            Base::VERSION,
            true
		);

        wp_localize_script(
            'happy-elementor-addons-admin',
            'happy',
            [
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'ha_get_preset' ),
            ]
        );
	}

    public static function enqueue_preview_style() {
        if( class_exists( 'WeForms' ) ) {
            wp_enqueue_style(
                'happy-elementor-weform-preview',
                plugins_url( '/weforms/assets/wpuf/css/frontend-forms.css', 'weforms' ),
                null,
                Base::VERSION
            );
        }
        if( class_exists( 'WPForms_Lite' ) ) {
            wp_enqueue_style(
                'happy-elementor-wpform-preview',
                plugins_url( '/wpforms-lite/assets/css/wpforms-full.css', 'wpforms-lite' ),
                null,
                Base::VERSION
            );
		}
        if( class_exists( 'Caldera_Forms' ) ) {
            wp_enqueue_style(
                'happy-elementor-caldera-preview',
                plugins_url( '/caldera-forms/assets/css/caldera-forms-front.css', 'caldera-forms' ),
                null,
                Base::VERSION
            );
        }
    }
}
