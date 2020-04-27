<?php
/**
 * Plugin base class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor;

use Elementor\Controls_Manager;
use Elementor\Elements_Manager;
use Elementor\Core\Common\Modules\Finder\Categories_Manager;

defined( 'ABSPATH' ) || die();

class Base {

    private static $instance = null;

    public $appsero = null;

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
            self::$instance->init();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'init', [ $this, 'i18n' ] );
    }

    public function i18n() {
        load_plugin_textdomain( 'happy-elementor-addons' );
    }

    public function init() {
        $this->include_files();

        // Register custom category
        add_action( 'elementor/elements/categories_registered', [ $this, 'add_category' ] );

        // Register custom controls
        add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );

        // Register finder category
		add_action( 'elementor/finder/categories/init', [ $this, 'register_finder' ] );

		add_action( 'wpml_loaded', [ $this, 'add_wpml_support' ] );

        Widgets_Manager::init();
        Assets_Manager::init();
        Cache_Manager::init();
        Icons_Manager::init();
        Extensions_Manager::init();
		Select2_Handler::init();

        $this->init_appsero_tracking();

        if ( is_user_logged_in() ) {
            Admin_Bar::init();
        }

        if ( is_admin() ) {
            Updater::init();
            Dashboard::init();
            Attention_Seeker::init();
        }

        do_action( 'happyaddons_loaded' );
	}

    /**
     * Initialize the tracker
     *
     * @return void
     */
    protected function init_appsero_tracking() {
        if ( ! class_exists( 'Happy_Addons\Appsero\Client' ) ) {
            include_once HAPPY_ADDONS_DIR_PATH . 'vendor/appsero/src/Client.php';
        }

        $this->appsero = new \Happy_Addons\Appsero\Client(
            '70b96801-94cc-4501-a005-8f9a4e20e152',
            'Happy Elementor Addons',
            HAPPY_ADDONS__FILE__
        );

        // Active insights
        $this->appsero->insights()
            ->add_extra([
                'pro_installed' => ha_has_pro() ? 'Yes' : 'No',
                'pro_version' => ha_has_pro() ? HAPPY_ADDONS_PRO_VERSION : '',
            ])
            ->init();
    }

    public function include_files() {
        include_once( HAPPY_ADDONS_DIR_PATH . 'inc/functions-forms.php' );

        include_once( HAPPY_ADDONS_DIR_PATH . 'classes/icons-manager.php' );
        include_once( HAPPY_ADDONS_DIR_PATH . 'classes/widgets-manager.php' );
        include_once( HAPPY_ADDONS_DIR_PATH . 'classes/assets-manager.php' );
        include_once( HAPPY_ADDONS_DIR_PATH . 'classes/cache-manager.php' );

        include_once( HAPPY_ADDONS_DIR_PATH . 'classes/widgets-cache.php' );
        include_once( HAPPY_ADDONS_DIR_PATH . 'classes/assets-cache.php' );
        include_once( HAPPY_ADDONS_DIR_PATH . 'classes/extensions-manager.php' );
		include_once( HAPPY_ADDONS_DIR_PATH . 'classes/select2-handler.php' );

        if ( is_admin() ) {
            include_once( HAPPY_ADDONS_DIR_PATH . 'classes/updater.php' );
            include_once( HAPPY_ADDONS_DIR_PATH . 'classes/dashboard.php' );
            include_once( HAPPY_ADDONS_DIR_PATH . 'classes/attention-seeker.php' );
        }

        if ( is_user_logged_in() ) {
            include_once( HAPPY_ADDONS_DIR_PATH . 'classes/admin-bar.php' );
            include_once( HAPPY_ADDONS_DIR_PATH . 'classes/clone-handler.php' );
		}
    }

    /**
     * Add custom category.
     *
     * @param $elements_manager
     */
    public function add_category( Elements_Manager $elements_manager ) {
        $elements_manager->add_category(
            'happy_addons_category',
            [
                'title' => __( 'Happy Addons', 'happy-elementor-addons' ),
                'icon' => 'fa fa-smile-o',
            ]
        );
    }

    /**
     * Register controls
     *
     * @param Controls_Manager $controls_Manager
     */
    public function register_controls( Controls_Manager $controls_Manager ) {
        include_once( HAPPY_ADDONS_DIR_PATH . 'controls/foreground.php' );
	    include_once( HAPPY_ADDONS_DIR_PATH . 'controls/select2.php' );
        $foreground = __NAMESPACE__ . '\Controls\Group_Control_Foreground';
	    $select2 = __NAMESPACE__ . '\Controls\Select2';

        $controls_Manager->add_group_control( $foreground::get_type(), new $foreground() );
	    ha_elementor()->controls_manager->register_control( $select2::TYPE, new $select2() );
    }

    /**
     * Register finder category and category items
     *
     * @param $categories_manager
     */
    public function register_finder( Categories_Manager $categories_manager ) {
        include_once( HAPPY_ADDONS_DIR_PATH . 'classes/finder.php' );
        include_once( HAPPY_ADDONS_DIR_PATH . 'classes/finder-edit.php' );
        // Add the category
        $categories_manager->add_category( Finder::SLUG, new Finder() );
        $categories_manager->add_category( Finder_Edit::SLUG, new Finder_Edit() );
	}

	/**
	 * Add wpml support
	 *
	 * @return void
	 */
	public function add_wpml_support() {
		include_once( HAPPY_ADDONS_DIR_PATH . 'classes/wpml-manager.php' );
		WPML_Manager::init();
	}
}
