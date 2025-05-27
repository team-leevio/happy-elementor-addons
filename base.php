<?php
/**
 * Plugin base class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor;

use Elementor\Controls_Manager;
use Elementor\Elements_Manager;

use \Happy_Addons\Elementor\Classes as HappyAddons_Classes; // Code from autoloader

defined( 'ABSPATH' ) || die();

class Base {

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
		$this->run_autoload();
	}

	public function init() {
		$this->include_files();

		// Register custom category
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_category' ] );

		// Register custom controls
		add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );

		add_action( 'init', [ $this, 'include_on_init' ] );

		$this->init_appsero_tracking();

		do_action( 'happyaddons_loaded' );
	}

	public function i18n() { // Code from autoloader
		load_plugin_textdomain( 'happy-elementor-addons', false, dirname( plugin_basename( HAPPY_ADDONS__FILE__ ) ) . '/i18n/' );
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

		$this->appsero->set_textdomain( 'happy-elementor-addons' );

		// Active insights
		$this->appsero->insights()
			->add_plugin_data()
			->add_extra([
				'pro_installed' => ha_has_pro() ? 'Yes' : 'No',
				'pro_version' => ha_has_pro() ? HAPPY_ADDONS_PRO_VERSION : '',
			])
			->init();
	}

	public static function hook_manager() {

		/* Ajax_Handler */
		add_action( 'wp_ajax_ha_twitter_feed_action', [ HappyAddons_Classes\Ajax_Handler::class, 'twitter_feed_ajax' ] );
		add_action( 'wp_ajax_nopriv_ha_twitter_feed_action', [ HappyAddons_Classes\Ajax_Handler::class, 'twitter_feed_ajax' ] );

		add_action( 'wp_ajax_ha_post_tab_action', [ HappyAddons_Classes\Ajax_Handler::class, 'post_tab' ] );
		add_action( 'wp_ajax_nopriv_ha_post_tab_action', [ HappyAddons_Classes\Ajax_Handler::class, 'post_tab' ] );

		add_action( 'wp_ajax_ha_mailchimp_ajax', [HappyAddons_Classes\Ajax_Handler::class, 'mailchimp_prepare_ajax'] );
		add_action( 'wp_ajax_nopriv_ha_mailchimp_ajax', [HappyAddons_Classes\Ajax_Handler::class, 'mailchimp_prepare_ajax'] );

		/* Icons_Manager */
		add_filter( 'elementor/icons_manager/additional_tabs', [ HappyAddons_Classes\Icons_Manager::class, 'add_happy_icons_tab' ] );

		/* Widgets_Manager */
		// legacy support hook
		if( defined('HAPPY_ADDONS_PRO_VERSION') && HAPPY_ADDONS_PRO_VERSION <= '2.7.0' ) {
			add_action( 'elementor/widgets/widgets_registered', [ HappyAddons_Classes\Widgets_Manager::class, 'register'] );
		}
		// original hook for register widgets
		add_action('elementor/widgets/register', [ HappyAddons_Classes\Widgets_Manager::class, 'register'] );
		add_action('elementor/frontend/before_render', [ HappyAddons_Classes\Widgets_Manager::class, 'add_global_widget_render_attributes'] );

		/* Assets_Manager */
		// Frontend scripts
		add_action('wp_enqueue_scripts', [HappyAddons_Classes\Assets_Manager::class, 'frontend_register']);
		add_action('wp_enqueue_scripts', [HappyAddons_Classes\Assets_Manager::class, 'frontend_enqueue'], 100);
		add_action('elementor/css-file/post/enqueue', [HappyAddons_Classes\Assets_Manager::class, 'frontend_enqueue_exceptions']);
		// Edit and preview enqueue
		add_action('elementor/preview/enqueue_styles', [HappyAddons_Classes\Assets_Manager::class, 'enqueue_preview_styles']);
		// Enqueue editor & editorv2 scripts
		add_action('elementor/editor/after_enqueue_scripts', [HappyAddons_Classes\Assets_Manager::class, 'editor_enqueue']);
		// Paragraph toolbar registration
		add_filter('elementor/editor/localize_settings', [HappyAddons_Classes\Assets_Manager::class, 'add_inline_editing_intermediate_toolbar']);
		if (ha_has_pro() && version_compare(HAPPY_ADDONS_PRO_VERSION, '1.9.0', '<=')) {
			$callback = ['\Happy_Addons_Pro\Assets_Manager', 'frontend_register'];
			remove_action('wp_enqueue_scripts', $callback);
			add_action('wp_enqueue_scripts', $callback, 0);
		}

		/* Cache_Manager */
		add_action( 'elementor/editor/after_save', [ HappyAddons_Classes\Cache_Manager::class, 'cache_widgets' ], 10, 2 );
		add_action( 'after_delete_post', [ HappyAddons_Classes\Cache_Manager::class, 'delete_cache' ] );

		/* WPML_Manager */
		add_filter( 'wpml_elementor_widgets_to_translate', [ HappyAddons_Classes\WPML_Manager::class, 'add_widgets_to_translate' ] );
		add_action( 'wpml_translation_job_saved', [ HappyAddons_Classes\WPML_Manager::class, 'on_translation_job_saved' ], 10, 3 );

		/* Api_Handler */
		if(get_option('happy-elementor-addons_wizard_cache')){
            delete_option('happy-elementor-addons_wizard_cache');
        }
        add_action('rest_api_init', [HappyAddons_Classes\Api_Handler::class, 'ha_wizard_routes']);
        if(!get_option('happy-elementor-x98237938759348573')) {
            delete_option('happy-elementor-addons_wizard_cache_key');
            update_option('happy-elementor-x98237938759348573',1);
        }

		if ( is_admin() ) {
			/* Dashboard */
			add_action( 'admin_menu', [ HappyAddons_Classes\Dashboard::class, 'add_menu' ], 21 );
			add_action( 'admin_menu', [ HappyAddons_Classes\Dashboard::class, 'update_menu_items' ], 99 );
			add_action( 'admin_enqueue_scripts', [ HappyAddons_Classes\Dashboard::class, 'enqueue_scripts' ] );
			add_action( 'wp_ajax_ha_save_dashboard', [ HappyAddons_Classes\Dashboard::class, 'save_data' ] );
			add_action( 'admin_init', [ HappyAddons_Classes\Dashboard::class, 'activation_redirect' ] );
			add_filter( 'plugin_action_links_' . plugin_basename( HAPPY_ADDONS__FILE__ ), [ HappyAddons_Classes\Dashboard::class, 'add_action_links' ] );
			add_action( 'happyaddons_save_dashboard_data', [ HappyAddons_Classes\Dashboard::class, 'save_widgets_data' ], 1);
			add_action( 'happyaddons_save_dashboard_data', [ HappyAddons_Classes\Dashboard::class, 'save_features_data' ] );
			add_action( 'happyaddons_save_dashboard_data', [ HappyAddons_Classes\Dashboard::class, 'save_credentials_data' ] );
			add_action( 'happyaddons_save_dashboard_data', [ HappyAddons_Classes\Dashboard::class, 'disable_unused_widget' ], 10);
			add_action( 'in_admin_header', [ HappyAddons_Classes\Dashboard::class, 'remove_all_notices' ], PHP_INT_MAX );

			/* Attention_Seeker */
			add_action( 'admin_notices', [ HappyAddons_Classes\Attention_Seeker::class, 'seek_attention' ] );
			add_action( 'wp_ajax_ignore_attention_seeker', [ HappyAddons_Classes\Attention_Seeker::class, 'process_ignore_request' ] );
			add_action( 'admin_head', [ HappyAddons_Classes\Attention_Seeker::class, 'setup_environment' ] );

			/* Select2_Handler */
			add_action( 'wp_ajax_ha_process_dynamic_select', [ HappyAddons_Classes\Select2_Handler::class, 'process_request' ] );

			/* Dashboard_Widgets */
			add_action( 'wp_dashboard_setup', [ HappyAddons_Classes\Dashboard_Widgets::class, 'dashboard_widgets_handler' ], 9999 );
		}

		if ( is_user_logged_in() ) {
			/* Library_Manager */
			add_action( 'elementor/editor/footer', [ HappyAddons_Classes\Library_Manager::class, 'print_template_views' ] );
			add_action( 'elementor/ajax/register_actions', [ HappyAddons_Classes\Library_Manager::class, 'register_ajax_actions' ] );

			/* Review */
			add_action( 'admin_init', [HappyAddons_Classes\Review::class, 'ha_void_check_installation_time'] );
        	add_action( 'admin_init', [HappyAddons_Classes\Review::class, 'ha_void_spare_me'], 5 );

			/* Notice */
			if ( ! ( in_array( 'happy-elementor-addons-pro/happy-elementors-addons-pro.php', (array) get_option( 'active_plugins', [] ), true ) ) ) {
				add_action( 'admin_init', [HappyAddons_Classes\Notice::class, 'ha_void_check_installation_time'] );
				add_action( 'admin_init', [HappyAddons_Classes\Notice::class, 'ha_void_spare_me'], 5 );
			}

			/* Admin_Bar */
			if ( ha_is_adminbar_menu_enabled() ) {
				add_action( 'admin_bar_menu', [HappyAddons_Classes\Admin_Bar::class, 'add_toolbar_items'], 500 );
				add_action( 'wp_enqueue_scripts', [HappyAddons_Classes\Admin_Bar::class, 'enqueue_assets'] );
				add_action( 'admin_enqueue_scripts', [HappyAddons_Classes\Admin_Bar::class, 'enqueue_assets'] );
				add_action( 'wp_ajax_ha_clear_cache', [HappyAddons_Classes\Admin_Bar::class, 'clear_cache' ] );
			}

			/* Clone_Handler */
			if ( ha_is_happy_clone_enabled() ) {
				add_action( 'admin_action_ha_duplicate_thing', [ HappyAddons_Classes\Clone_Handler::class, 'duplicate_thing' ] );
				add_filter( 'post_row_actions', [ HappyAddons_Classes\Clone_Handler::class, 'add_row_actions' ], 10, 2 );
				add_filter( 'page_row_actions', [ HappyAddons_Classes\Clone_Handler::class, 'add_row_actions' ], 10, 2 );
			}
		}

	}

	public function include_files() {
		include_once( HAPPY_ADDONS_DIR_PATH . 'inc/functions-forms.php' );

		self::hook_manager();
		
		HappyAddons_Classes\Theme_Builder::instance();

		if ( is_admin() ) {
			HappyAddons_Classes\Updater::init();
		}
	}

	public function include_on_init() {
		HappyAddons_Classes\Condition_Manager::instance();
		HappyAddons_Classes\Extensions_Manager::init();
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
		$Foreground = __NAMESPACE__ . '\Controls\Group_Control_Foreground';
		$controls_Manager->add_group_control( $Foreground::get_type(), new $Foreground() );

		$Select2 = __NAMESPACE__ . '\Controls\Select2';
		ha_elementor()->controls_manager->register( new $Select2() );

		$Widget_List = __NAMESPACE__ . '\Controls\Widget_List';
		ha_elementor()->controls_manager->register( new $Widget_List() );

		$Text_Stroke = __NAMESPACE__ . '\Controls\Group_Control_Text_Stroke';
		$controls_Manager->add_group_control( $Text_Stroke::get_type(), new $Text_Stroke() );
	}

	protected static function init_classes_aliases() {
		return [
			'Widgets_Manager' => [
				'Happy_Addons\Elementor\Classes\Widgets_Manager', 'Happy_Addons\Elementor\Widgets_Manager',
			],
			'Widgets_Cache' => [
				'Happy_Addons\Elementor\Classes\Widgets_Cache', 'Happy_Addons\Elementor\Widgets_Cache',
			],
			'Assets_Cache' => [
				'Happy_Addons\Elementor\Classes\Assets_Cache', 'Happy_Addons\Elementor\Assets_Cache',
			]
		];
	}

	public static function get_class_name($class_str) {
		$last_slash_pos = strrpos($class_str, '\\');
		if ($last_slash_pos !== false) {
			$class_name = substr($class_str, $last_slash_pos + 1);
		} else {
			$class_name = $class_str; // Fallback if no backslash exists
		}
		return $class_name;
	}

	protected function autoload( $class_name ) {
		if ( 0 !== strpos( $class_name, __NAMESPACE__ ) ) {
			return;
		}

		$relative_class_name = self::get_class_name( $class_name );

		$file_name = strtolower(
			str_replace(
				[ __NAMESPACE__ . '\\', '_', '\\' ], // replace namespace, underscrore & backslash
				[ '', '-', '/' ],
				$class_name
			)
		);

		//For Classes folder class load
		if ( 0 === strpos( $class_name, 'Happy_Addons\Elementor\Classes\\' ) ) {
			$file = HAPPY_ADDONS_DIR_PATH . '/' . $file_name . '.php';
			if ( ! class_exists( $class_name ) && is_readable( $file ) ) {
				include_once $file;
			}
		}

		//For Controls folder class load
		if ( 0 === strpos( $class_name, 'Happy_Addons\Elementor\Controls\\' ) ) {
			$file = HAPPY_ADDONS_DIR_PATH . '/' . $file_name . '.php';
			if ( ! class_exists( $class_name ) && is_readable( $file ) ) {
				include_once $file;
			}
		}

		//For Extensions folder class load
		if ( 0 === strpos( $class_name, 'Happy_Addons\Elementor\Extensions\\' ) ) {
			$file = HAPPY_ADDONS_DIR_PATH . '/' . $file_name . '.php';
			if ( ! class_exists( $class_name ) && is_readable( $file ) ) {
				include_once $file;
			}
		}

		//For Traits folder class load
		if ( 0 === strpos( $class_name, 'Happy_Addons\Elementor\Traits\\' ) ) {
			$file = HAPPY_ADDONS_DIR_PATH . '/' . $file_name . '.php';
			if ( is_readable( $file ) ) {
				include_once $file;
			}
		}

		//For Widget class load
		if ( 0 === strpos( $class_name, __NAMESPACE__ . '\Widget\\' ) ) {
			$file = HAPPY_ADDONS_DIR_PATH . '/' . str_replace( 'widget', 'widgets', $file_name ) . '/widget.php';
			if ( ! class_exists( $class_name ) && is_readable( $file ) ) {
				include_once $file;
			}
		}

		//For WPML class load
		if ( 0 === strpos( $class_name, 'Happy_Addons\Elementor\Wpml') ) {
			$file = HAPPY_ADDONS_DIR_PATH . '/' . $file_name . '.php';
			if ( ! class_exists( $class_name ) && is_readable( $file ) ) {
				include_once $file;
			}
		}

		//For class aliases
		if ( array_key_exists( $relative_class_name, self::init_classes_aliases() ) ) {
			$aliases = self::init_classes_aliases();
			class_alias( $aliases[ $relative_class_name ][0], $aliases[ $relative_class_name ][1] );
		}

	}

	public function run_autoload() {
		spl_autoload_register( [ $this, 'autoload' ] );
	}
}
