<?php
/**
 * Plugin base class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor;

use Elementor\Plugin as Elementor;

defined( 'ABSPATH' ) || die();

class Base {

    const VERSION = '0.0.1';

    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

    const MINIMUM_PHP_VERSION = '5.4';

    private static $instance = null;

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'init', [ $this, 'i18n' ] );
        add_action( 'plugins_loaded', [ $this, 'init' ] );
    }

    public function i18n() {
        load_plugin_textdomain( 'happy_addons' );
    }

    public function init() {
        // Check if Elementor installed and activated
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_elementor' ] );
            return;
        }

        // Check for required Elementor version
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
            return;
        }

        // Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
            return;
        }

        $this->include_files();

        // Register custom category
        add_action( 'elementor/elements/categories_registered', [ $this, 'add_category' ] );

        // Add Plugin actions
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );

        // Register custom controls
        add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );

        // Frontend scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

        add_filter( 'elementor/utils/get_placeholder_image_src', [ $this, 'set_placeholder_image' ] );
    }

    public function set_placeholder_image() {
        return HAPPY_ASSETS . 'imgs/placeholder.jpg';
    }

    public function include_files() {
        require( __DIR__ . '/inc/functions.php' );
    }

    /**
     * Enqueue frontend scripts
     */
    public function enqueue_scripts() {
        $suffix = ha_is_script_debug_enabled() ? '.min.' : '.';
        wp_enqueue_style(
            'happy-elementor-addons',
            HAPPY_ASSETS . 'css/main' . $suffix . 'css',
            ['elementor-frontend'],
            self::VERSION
        );

        wp_enqueue_style(
            'twentytwenty',
            HAPPY_ASSETS . 'vendor/twentytwenty/css/twentytwenty.css',
            null,
            self::VERSION
        );

        wp_enqueue_script(
            'jquery-event-move',
            HAPPY_ASSETS . 'vendor/twentytwenty/js/jquery.event.move.js',
            ['jquery'],
            self::VERSION,
            true
        );

        wp_enqueue_script(
            'jquery-twentytwenty',
            HAPPY_ASSETS . 'vendor/twentytwenty/js/jquery.twentytwenty.js',
            ['jquery-event-move'],
            self::VERSION,
            true
        );

        wp_enqueue_script(
            'happy-elementor-addons',
            HAPPY_ASSETS . 'js/happy-addons' . $suffix . 'js',
            ['jquery-twentytwenty'],
            self::VERSION,
            true
        );
    }

    /**
     * Add custom category.
     *
     * @param $elements_manager
     */
    public function add_category( $elements_manager ) {
        $elements_manager->add_category(
            'happy_addons',
            [
                'title' => __( 'Happy Addons', 'happy_addons' ),
                'icon' => 'fa fa-smile-o',
            ]
        );
    }

    /**
     * Admin notice.
     *
     * Warning when the site doesn't have Elementor installed or activated.
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_notice_missing_elementor() {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
        /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'happy_addons' ),
            '<strong>' . esc_html__( 'Happy Elementor Addons', 'happy_addons' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'happy_addons' ) . '</strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required Elementor version.
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_notice_minimum_elementor_version() {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
        /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'happy_addons' ),
            '<strong>' . esc_html__( 'Happy Elementor Addons', 'happy_addons' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'happy_addons' ) . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required PHP version.
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_notice_minimum_php_version() {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
        /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'happy_addons' ),
            '<strong>' . esc_html__( 'Happy Elementor Addons', 'happy_addons' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'happy_addons' ) . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    /**
     * Init Widgets
     *
     * Include widgets files and register them
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function register_widgets() {
        require( __DIR__ . '/base/widget-base.php' );

        $widgets = [
            'blurb',
            'card',
            'cf7',
            'icon-box',
            'member',
            'review',
            'image-comparison',
        ];

        foreach ( $widgets as $widget ) {
            require( __DIR__ . '/widgets/' . $widget . '/widget.php' );

            $class_name = str_replace( '-', '_', $widget );
            $class_name = __NAMESPACE__ . '\Widget\\' . $class_name;
            Elementor::instance()->widgets_manager->register_widget_type( new $class_name() );
        }
    }

    /**
     * Register custom controls
     *
     * Include custom controls file and register them
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function register_controls( $controls_manager ) {
        require( __DIR__ . '/controls/select-preview.php' );
        $class = __NAMESPACE__ . '\Controls\Select_Preview';
        $controls_manager->register_control( 'select_preview', new $class() );
    }
}
