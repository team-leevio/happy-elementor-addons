<?php
namespace Happy_Addons\Elementor;

use Elementor\Core\Files\CSS\Post as Post_CSS;

defined( 'ABSPATH' ) || die();

class Assets_Manager {

    /**
     * Bind hook and run internal methods here
     */
    public static function init() {
        // Frontend scripts
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'frontend_register' ] );
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'frontend_enqueue' ], 99 );
        add_action( 'elementor/css-file/post/enqueue', [ __CLASS__, 'frontend_elementor_enqueue' ] );

        // Edit and preview enqueue
        add_action( 'elementor/preview/enqueue_styles', [ __CLASS__, 'enqueue_preview_style' ] );

        add_action( 'elementor/editor/before_enqueue_scripts', [ __CLASS__, 'enqueue_editor_scripts' ] );

        // Placeholder image replacement
        add_filter( 'elementor/utils/get_placeholder_image_src', [ __CLASS__, 'set_placeholder_image' ] );

        // Paragraph toolbar registration
        add_filter( 'elementor/editor/localize_settings', [ __CLASS__, 'add_inline_editing_intermediate_toolbar' ] );
    }

    /**
     * Register inline editing paragraph toolbar
     *
     * @param array $config
     * @return array
     */
    public static function add_inline_editing_intermediate_toolbar( $config ) {
        $config['inlineEditing'] = [
            'toolbar' => [
                'intermediate' => [
                    'bold',
                    'underline',
                    'italic',
                    'createlink',
                ],
            ]
        ];
        return $config;
    }

    public static function set_placeholder_image() {
        return HAPPY_ADDONS_ASSETS . 'imgs/placeholder.jpg';
    }

    public static function frontend_register() {
        $suffix = ha_is_script_debug_enabled() ? '.' : '.min.';

        wp_register_style(
            'happy-icons',
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
            HAPPY_ADDONS_ASSETS . 'vendor/slick/slick.min.js',
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
         * Number animation
         */
        wp_register_script(
            'jquery-numerator',
            HAPPY_ADDONS_ASSETS . 'vendor/jquery-numerator/jquery-numerator.min.js',
            [ 'jquery' ],
            HAPPY_ADDONS_VERSION,
            true
        );

        /**
         * Magnific popup
         */
        wp_register_style(
            'magnific-popup',
            HAPPY_ADDONS_ASSETS . 'vendor/magnific-popup/magnific-popup.css',
            null,
            HAPPY_ADDONS_VERSION
        );

        wp_register_script(
            'jquery-magnific-popup',
            HAPPY_ADDONS_ASSETS . 'vendor/magnific-popup/jquery.magnific-popup.min.js',
            null,
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

        // Main assets
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

    /**
     * Enqueue elementor template cached stylesheet
     * when used in as shortcode in a page.
     *
     * This system depends of elementor cache
     *
     * @param Post_CSS $file
     */
    public static function frontend_elementor_enqueue( Post_CSS $file ) {
        if ( get_post_type( $file->get_post_id() ) === 'elementor_library' ) {
            $should_enqueue = Cache_Manager::should_enqueue( $file->get_post_id() );
            if ( ! $should_enqueue && $file->get_post_id() === get_the_ID() ) {
                Cache_Manager::enqueue_without_cache();
            } elseif ( $should_enqueue ) {
                Cache_Manager::enqueue( $file->get_post_id() );
            }
        }
    }

    public static function frontend_enqueue() {
        if ( Cache_Manager::should_enqueue( get_the_ID() ) ) {
            Cache_Manager::enqueue( get_the_ID() );
        } else {
            Cache_Manager::enqueue_without_cache();
        }
    }

    public static function enqueue_editor_scripts() {
        wp_enqueue_style(
            'happy-icons',
            HAPPY_ADDONS_ASSETS . 'fonts/style.min.css',
            null,
            HAPPY_ADDONS_VERSION
        );

        wp_enqueue_style(
            'happy-elementor-addons-editor',
            HAPPY_ADDONS_ASSETS . 'admin/css/editor.min.css',
            null,
            HAPPY_ADDONS_VERSION
        );

        wp_enqueue_script(
            'happy-elementor-addons-editor',
            HAPPY_ADDONS_ASSETS . 'admin/js/editor.min.js',
            [ 'elementor-editor' ],
            HAPPY_ADDONS_VERSION,
            true
        );

        wp_localize_script(
            'happy-elementor-addons-editor',
            'HappyAddonsEditor',
            [
                'editorPanelHomeLinkURL' => ha_get_dashboard_link(),
                'editorPanelHomeLinkTitle' => __( 'HappyAddons - Home', 'happy-elementor-addons' ),
                'editorPanelWidgetsLinkURL' => ha_get_dashboard_link( '#widgets' ),
                'editorPanelWidgetsLinkTitle' => __( 'HappyAddons - Widgets', 'happy-elementor-addons' ),
            ]
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
