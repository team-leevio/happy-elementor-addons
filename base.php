<?php
/**
 * Plugin base class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor;

defined( 'ABSPATH' ) || die();

class Base {

    const MINIMUM_ELEMENTOR_VERSION = '2.5.0';

    const MINIMUM_PHP_VERSION = '5.4';

    const ACTIVATION_FLAG_DB_KEY = 'happyaddons_do_activation_direct';

    private static $instance = null;

    public $appsero = null;

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'init', [ $this, 'i18n' ] );
        add_action( 'plugins_loaded', [ $this, 'init' ] );

        register_activation_hook( HAPPY_ADDONS__FILE__, [ $this, 'register_activation_hook' ] );
    }

    public function register_activation_hook() {
        add_option( self::ACTIVATION_FLAG_DB_KEY, true );
    }

    public function i18n() {
        load_plugin_textdomain( 'happy-elementor-addons' );
    }

    public function init() {
        // Check if Elementor installed and activated
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [$this, 'admin_notice_missing_elementor'] );
            return;
        }

        // Check for required Elementor version
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [$this, 'admin_notice_minimum_elementor_version'] );
            return;
        }

        // Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [$this, 'admin_notice_minimum_php_version'] );
            return;
        }

        $this->include_files();

        // Register custom category
        add_action( 'elementor/elements/categories_registered', [$this, 'add_category'] );

        // Register custom controls
        add_action( 'elementor/controls/controls_registered', [$this, 'register_controls'] );

        add_action( 'elementor/finder/categories/init', [ $this, 'register_finder' ] );

        Widgets_Manager::init();
        Assets_Manager::init();
        Extensions_Manager::init();
        Cache_Manager::init();
        Icons_Manager::init();

        $this->init_appsero_tracking();

        if ( is_user_logged_in() ) {
            Admin_Bar::init();
        }

        if ( is_admin() ) {
            Updater::init();
            Dashboard::init();
        }

        do_action( 'happyaddons_loaded' );
    }

    /**
     * Initialize the tracker
     *
     * @return void
     */
    protected function init_appsero_tracking() {
        if ( ! class_exists( 'Appsero\Client' ) ) {
            require_once HAPPY_ADDONS_DIR_PATH . 'vendor/appsero/src/Client.php';
        }

        $this->appsero = new \Appsero\Client(
            '70b96801-94cc-4501-a005-8f9a4e20e152',
            'Happy Elementor Addons',
            HAPPY_ADDONS__FILE__
        );

        // Active insights
        $this->appsero->insights()->init();
    }

    public function include_files() {
        require( HAPPY_ADDONS_DIR_PATH . 'inc/functions.php' );

        require( HAPPY_ADDONS_DIR_PATH . 'classes/icons-manager.php' );
        require( HAPPY_ADDONS_DIR_PATH . 'classes/widgets-manager.php' );
        require( HAPPY_ADDONS_DIR_PATH . 'classes/assets-manager.php' );
        require( HAPPY_ADDONS_DIR_PATH . 'classes/extensions-manager.php' );
        require( HAPPY_ADDONS_DIR_PATH . 'classes/cache-manager.php' );

        require( HAPPY_ADDONS_DIR_PATH . 'classes/widgets-cache.php' );
        require( HAPPY_ADDONS_DIR_PATH . 'classes/assets-cache.php' );

        if ( is_admin() ) {
            require( HAPPY_ADDONS_DIR_PATH . 'classes/class.communicator.php' );
            require( HAPPY_ADDONS_DIR_PATH . 'classes/updater.php' );
            require( HAPPY_ADDONS_DIR_PATH . 'classes/dashboard.php' );
        }

        if ( is_user_logged_in() ) {
            require( HAPPY_ADDONS_DIR_PATH . 'classes/admin-bar.php' );
        }
    }

    /**
     * Add custom category.
     *
     * @param $elements_manager
     */
    public function add_category( $elements_manager ) {
        $elements_manager->add_category(
            'happy_addons_category',
            [
                'title' => __( 'Happy Addons', 'happy-elementor-addons' ),
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
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'happy-elementor-addons' ),
            '<strong>' . esc_html__( 'Happy Elementor Addons', 'happy-elementor-addons' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'happy-elementor-addons' ) . '</strong>'
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
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'happy-elementor-addons' ),
            '<strong>' . esc_html__( 'Happy Elementor Addons', 'happy-elementor-addons' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'happy-elementor-addons' ) . '</strong>',
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
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'happy-elementor-addons' ),
            '<strong>' . esc_html__( 'Happy Elementor Addons', 'happy-elementor-addons' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'happy-elementor-addons' ) . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
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
    public function register_controls() {
        require( HAPPY_ADDONS_DIR_PATH . 'controls/foreground.php' );
        $foreground = __NAMESPACE__ . '\Controls\Group_Control_Foreground';

        \Elementor\Plugin::instance()->controls_manager->add_group_control( $foreground::get_type(), new $foreground() );
    }

    /**
     * Register elementor finder
     *
     * @param $categories_manager
     */
    public function register_finder( $categories_manager ) {
        // Include the Finder Category class file
        require( HAPPY_ADDONS_DIR_PATH . 'classes/finder.php' );

        // Add the category
        $categories_manager->add_category( 'happyaddons', new \Happy_Addons\Elementor\Finder() );
    }
}
