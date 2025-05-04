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
	private static $widget_count = 0;

	public $appsero = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public static function instance_old() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->init();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'init', [ $this, 'i18n' ] );
		$this->run_autoload();
	}

	private function __construct__old() { // Code from autoloader
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

		// add_action( 'init', [ $this, 'i18n' ] );

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

	public function include_files() {
		include_once( HAPPY_ADDONS_DIR_PATH . 'inc/functions-forms.php' );

		include_once( HAPPY_ADDONS_DIR_PATH . 'classes/ajax-handler.php' );

		include_once( HAPPY_ADDONS_DIR_PATH . 'classes/icons-manager.php' );
		include_once( HAPPY_ADDONS_DIR_PATH . 'classes/widgets-manager.php' );
		include_once( HAPPY_ADDONS_DIR_PATH . 'classes/assets-manager.php' );
		include_once( HAPPY_ADDONS_DIR_PATH . 'classes/cache-manager.php' );

		include_once( HAPPY_ADDONS_DIR_PATH . 'classes/widgets-cache.php' );
		include_once( HAPPY_ADDONS_DIR_PATH . 'classes/assets-cache.php' );
		include_once( HAPPY_ADDONS_DIR_PATH . 'classes/wpml-manager.php' );

		if ( is_admin() ) {
			include_once( HAPPY_ADDONS_DIR_PATH . 'classes/updater.php' );
			include_once( HAPPY_ADDONS_DIR_PATH . 'classes/dashboard.php' );
			include_once( HAPPY_ADDONS_DIR_PATH . 'classes/attention-seeker.php' );
			include_once( HAPPY_ADDONS_DIR_PATH . 'classes/select2-handler.php' );
			include_once( HAPPY_ADDONS_DIR_PATH . 'classes/dashboard-widgets.php' );
		}

		if ( is_user_logged_in() ) {
			include_once( HAPPY_ADDONS_DIR_PATH . 'classes/library-manager.php' );
			include_once( HAPPY_ADDONS_DIR_PATH . 'classes/library-source.php' );
		}
		include_once( HAPPY_ADDONS_DIR_PATH . 'classes/api-handler.php' );
		include_once( HAPPY_ADDONS_DIR_PATH . 'classes/conditions-cache.php' );
		include_once( HAPPY_ADDONS_DIR_PATH . 'classes/theme-builder.php' );
		// include_once( HAPPY_ADDONS_DIR_PATH . 'classes/condition-manager.php' );

		include_once( HAPPY_ADDONS_DIR_PATH . 'classes/builder-compatibility/astra.php');
		include_once( HAPPY_ADDONS_DIR_PATH . 'classes/builder-compatibility/bbtheme.php');
		include_once( HAPPY_ADDONS_DIR_PATH . 'classes/builder-compatibility/generatepress.php');
		include_once( HAPPY_ADDONS_DIR_PATH . 'classes/builder-compatibility/genesis.php');
		include_once( HAPPY_ADDONS_DIR_PATH . 'classes/builder-compatibility/my-listing.php');
		include_once( HAPPY_ADDONS_DIR_PATH . 'classes/builder-compatibility/oceanwp.php');
		include_once( HAPPY_ADDONS_DIR_PATH . 'classes/builder-compatibility/twenty-nineteen.php');
		include_once( HAPPY_ADDONS_DIR_PATH . 'classes/builder-compatibility/theme-support.php');
	}

	public function include_files_old() { // Code from autoloader
		include_once HAPPY_ADDONS_DIR_PATH . 'inc/functions-forms.php';

		HappyAddons_Classes\Ajax_Handler::init();
		HappyAddons_Classes\Icons_Manager::init();
		HappyAddons_Classes\Widgets_Manager::init();
		HappyAddons_Classes\Assets_Manager::init();
		HappyAddons_Classes\Cache_Manager::init();
		HappyAddons_Classes\WPML_Manager::init();

		HappyAddons_Classes\Api_Handler::init();

		// include_once( HAPPY_ADDONS_DIR_PATH . 'classes/ajax-handler.php' );

		// include_once( HAPPY_ADDONS_DIR_PATH . 'classes/icons-manager.php' );
		// include_once( HAPPY_ADDONS_DIR_PATH . 'classes/widgets-manager.php' );
		// include_once( HAPPY_ADDONS_DIR_PATH . 'classes/assets-manager.php' );
		// include_once( HAPPY_ADDONS_DIR_PATH . 'classes/cache-manager.php' );

		// include_once( HAPPY_ADDONS_DIR_PATH . 'classes/widgets-cache.php' ); // assets-manager file has no init method
		// include_once( HAPPY_ADDONS_DIR_PATH . 'classes/assets-cache.php' ); // assets-manager file has no init method
		// include_once( HAPPY_ADDONS_DIR_PATH . 'classes/wpml-manager.php' );

		if ( is_admin() ) {
			HappyAddons_Classes\Updater::init();
			HappyAddons_Classes\Dashboard::init();
			HappyAddons_Classes\Attention_Seeker::init();
			HappyAddons_Classes\Select2_Handler::init();
			HappyAddons_Classes\Dashboard_Widgets::instance()->init();
			// include_once( HAPPY_ADDONS_DIR_PATH . 'classes/updater.php' );
			// include_once( HAPPY_ADDONS_DIR_PATH . 'classes/dashboard.php' );
			// include_once( HAPPY_ADDONS_DIR_PATH . 'classes/attention-seeker.php' );
			// include_once( HAPPY_ADDONS_DIR_PATH . 'classes/select2-handler.php' );
			// include_once( HAPPY_ADDONS_DIR_PATH . 'classes/dashboard-widgets.php' );
		}

		if ( is_user_logged_in() ) {
			HappyAddons_Classes\Library_Manager::init();
			// include_once( HAPPY_ADDONS_DIR_PATH . 'classes/library-manager.php' );
			// include_once( HAPPY_ADDONS_DIR_PATH . 'classes/library-source.php' );
		}
		// include_once( HAPPY_ADDONS_DIR_PATH . 'classes/api-handler.php' );

	}

	public function include_on_init() {
		HappyAddons_Classes\Condition_Manager::instance();

		HappyAddons_Classes\Extensions_Manager::init();
		HappyAddons_Classes\Credentials_Manager::init();
		// include_once( HAPPY_ADDONS_DIR_PATH . 'classes/extensions-manager.php' );
		// include_once( HAPPY_ADDONS_DIR_PATH . 'classes/credentials-manager.php' );

		if ( is_user_logged_in() ) {
			// include_once HAPPY_ADDONS_DIR_PATH . 'classes/review.php';
			HappyAddons_Classes\Review::init();
		}

		if ( is_user_logged_in() ) {
			// include_once HAPPY_ADDONS_DIR_PATH . 'classes/notice.php';
			HappyAddons_Classes\Notice::init();
		}

		if ( is_user_logged_in() && ha_is_adminbar_menu_enabled() ) {
			// include_once HAPPY_ADDONS_DIR_PATH . 'classes/admin-bar.php';
			HappyAddons_Classes\Admin_Bar::init();
		}

		if ( is_user_logged_in() && ha_is_happy_clone_enabled() ) {
			// include_once HAPPY_ADDONS_DIR_PATH . 'classes/clone-handler.php';
			HappyAddons_Classes\Clone_Handler::init();
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
		// include_once( HAPPY_ADDONS_DIR_PATH . 'controls/foreground.php' );
		// include_once( HAPPY_ADDONS_DIR_PATH . 'controls/select2.php' );
		// include_once( HAPPY_ADDONS_DIR_PATH . 'controls/widget-list.php' );
		// include_once( HAPPY_ADDONS_DIR_PATH . 'controls/text-stroke.php' );

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

		/* if( 'Happy_Addons\Elementor\Condition_Manager' == $class_name ) {
			$file = HAPPY_ADDONS_DIR_PATH . 'classes/' . $file_name . '.php';
			if ( ! class_exists( $class_name ) && is_readable( $file ) ) {
				include_once $file;
			}
		} */
	}

	public function run_autoload() {
		spl_autoload_register( [ $this, 'autoload_test' ] );
	}

	protected function autoload_test( $class_name ) {
		if ( 0 !== strpos( $class_name, __NAMESPACE__ ) ) {
			return;
		}

		$relative_class_name = self::get_class_name($class_name);

		$file_name = strtolower(
			str_replace(
				[ __NAMESPACE__ . '\\', '_', '\\' ], // replace namespace, underscrore & backslash
				[ '', '-', '/' ],
				$class_name
			)
		);

		// error_log( print_r( $class_name.' Class name' , 1 ) );

		//For Classes folder class load
		if ( 0 === strpos( $class_name, 'Happy_Addons\Elementor\Classes\\' ) ) {
			// error_log( print_r( $file_name.' Classes file name' , 1 ) );
			// error_log( print_r( $class_name.' Classes folder Class name' , 1 ) );
			$file = HAPPY_ADDONS_DIR_PATH . '/' . $file_name . '.php';

			if ( ! class_exists( $class_name ) && is_readable( $file ) ) {
				// error_log( print_r( $file.' File Path' , 1 ) );
				include_once $file;
				// error_log( print_r( '============================================' , 1 ) );
			}
		}

		//For Controls folder class load
		if ( 0 === strpos( $class_name, 'Happy_Addons\Elementor\Controls\\' ) ) {
			$file = HAPPY_ADDONS_DIR_PATH . '/' . $file_name . '.php';
			//error_log( print_r( $class_name.' Controls name' , 1 ) );
			if ( ! class_exists( $class_name ) && is_readable( $file ) ) {
				include_once $file;
			}
		}

		//For Extensions folder class load
		if ( 0 === strpos( $class_name, 'Happy_Addons\Elementor\Extensions\\' ) ) {
			// error_log( print_r( $file_name.' Extensions file name' , 1 ) );
			// error_log( print_r( $class_name.' Extensions Class name' , 1 ) );
			$file = HAPPY_ADDONS_DIR_PATH . '/' . $file_name . '.php';

			if ( ! class_exists( $class_name ) && is_readable( $file ) ) {
				// error_log( print_r( $file.' File Path' , 1 ) );
				self::$widget_count++;
				include_once $file;
				// error_log( print_r( self::$widget_count , 1 ) );
				// error_log( print_r( '============================================' , 1 ) );
			}
		}

		//For Traits folder class load
		if ( 0 === strpos( $class_name, 'Happy_Addons\Elementor\Traits\\' ) ) {
			$file = HAPPY_ADDONS_DIR_PATH . '/' . $file_name . '.php';
			//error_log( print_r( $file.' Traits name' , 1 ) );
			if ( is_readable( $file ) ) {
				include_once $file;
			}
		}

		//For Widget class load
		if ( 0 === strpos( $class_name, __NAMESPACE__ . '\Widget\\' ) ) {
			// error_log( print_r( $file_name.'/widget.php => Widget file name' , 1 ) );
			$file = HAPPY_ADDONS_DIR_PATH . '/' . str_replace( 'widget', 'widgets', $file_name ) . '/widget.php';
			if ( ! class_exists( $class_name ) && is_readable( $file ) ) {
				//error_log( print_r( $class_name.' Class name' , 1 ) );
				//self::$widget_count++;
				include_once $file;
				// error_log( print_r( self::$widget_count , 1 )
				// error_log( print_r( $class_name.' Class name ' . self::$widget_count , 1 ) );
			}
		}

		//For WPML class load
		if ( 0 === strpos( $class_name, 'Happy_Addons\Elementor\Wpml') ) {
			// error_log( print_r( $file_name.' file name' , 1 ) );
			$file = HAPPY_ADDONS_DIR_PATH . '/' . $file_name . '.php';
			if ( ! class_exists( $class_name ) && is_readable( $file ) ) {
				//error_log( print_r( $class_name.' Class name' , 1 ) );
				// error_log( print_r( $class_name.' Class not exist', 1 ) );
				include_once $file;
			}
		}

		//For class aliases
		if ( array_key_exists( $relative_class_name, self::init_classes_aliases() ) ) {
			$aliases = self::init_classes_aliases();
			error_log( print_r( $relative_class_name , 1 ) );
			error_log( print_r( $class_name.' Alice is loaded' , 1 ) );
			error_log( print_r( $aliases , 1 ) );
			// class_alias( $class_name, $aliases[ $class_name ] );
			class_alias( $aliases[ $relative_class_name ][0], $aliases[ $relative_class_name ][1] );
			// class_alias( 'Happy_Addons\Elementor\Classes\Widgets_Manager', 'Happy_Addons\Elementor\Widgets_Manager' );
		}

		/* if( 'Happy_Addons\Elementor\Classes\Condition_Manager' == $class_name ) {
			$file = HAPPY_ADDONS_DIR_PATH . 'classes/' . $file_name . '.php';
			if ( ! class_exists( $class_name ) && is_readable( $file ) ) {
				include_once $file;
			}
		} */
	}

	protected function autoload_old( $class_name ) {
		// Code from autoloader

		if ( 0 !== strpos( $class_name, __NAMESPACE__ ) ) {
			return;
		}

		$file_name = strtolower(
			str_replace(
				[ __NAMESPACE__ . '\\', '_', '\\' ], // replace namespace, underscrore & backslash
				[ '', '-', '/' ],
				$class_name
			)
		);
		// if ( 0 === strpos( $class_name, __NAMESPACE__ . '\Wpml\\' ) ){
		// 	$fileX = HAPPY_ADDONS_DIR_PATH . '/' . str_replace( 'wpml_', '', $file_name ) . '.php';
		// 	$fileY = HAPPY_ADDONS_DIR_PATH . '/' . $file_name . '.php';
		// 	error_log( print_r( $class_name.' Class name' , 1 ) );
		// 	// error_log( print_r( $fileX.' File name' , 1 ) );
		// 	// error_log( print_r( $fileY.' File name' , 1 ) );
		// 	// error_log( print_r( is_readable( $fileY ), 1 ) );
		// }
		// Happy_Addons\Elementor\Extensions\Column_Extended
		//Happy_Addons\Elementor\Widget
		//Happy_Addons\Elementor\Wpml
		// //For Widget class load
		if ( 0 === strpos( $class_name, __NAMESPACE__ . '\Widget\\' ) ) {
			//error_log( print_r( $file_name.'/widget.php => Widget file name' , 1 ) );
			$file = HAPPY_ADDONS_DIR_PATH . '/' . str_replace( 'widget', 'widgets', $file_name ) . '/widget.php';
		}
		else {
			//error_log( print_r( $file_name.'.php Others Classes file name' , 1 ) );
			$file = HAPPY_ADDONS_DIR_PATH . '/' . $file_name . '.php';
		}


		// elseif ( 0 === strpos( $class_name, __NAMESPACE__ . '\Wpml\\' ) ) {
		// 	//error_log( print_r( $file_name.'/widget.php => Widget file name' , 1 ) );
		// 	$file = HAPPY_ADDONS_DIR_PATH . '/' . str_replace( 'wpml-', '', $file_name ) . '.php';
		// }

		//Include called class
		if ( ! class_exists( $class_name ) && is_readable( $file ) ) {
			// error_log( print_r( $class_name.' Class name' , 1 ) );
			include_once $file;
		}

		// if ('Happy_Addons\Elementor\Classes\Widgets_Manager' == $class_name ){
		// 	error_log( print_r( $class_name.' Alice is loaded' , 1 ) );
		// 	class_alias( $class_name, 'Happy_Addons\Elementor\Widgets_Manager' );
		// }

		if ( array_key_exists( $class_name, self::init_classes_aliases() ) ) {
			$aliases = self::init_classes_aliases();
			// error_log( print_r( $class_name.' Alice is loaded' , 1 ) );
			class_alias( $class_name, $aliases[ $class_name ] );
		}

	}

	protected function autoload__2( $class_name ) {
		// Code from autoloader
		// error_log( print_r( $class_name.' Class name' , 1 ) );

		if ( 0 !== strpos( $class_name, __NAMESPACE__ ) ) {
			return;
		}
		// Happy_Addons\Elementor\Classes\Ajax_Handler
		// C:\laragon\www\happy-test\wp-content\plugins\happy-elementor-addons/
		// $file_name = strtolower(
		//     preg_replace(
		//         [ '/\b'.__NAMESPACE__.'\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
		//         [ '', '$1-$2', '-', DIRECTORY_SEPARATOR],
		//         $class_name
		//     )
		// );

		$file_name = str_replace( __NAMESPACE__ . '\\', '', $class_name );
		$file_name = str_replace( ['_', '\\'], ['-', '/'], strtolower( $file_name ) );
		error_log( print_r( $file_name . 'php Path name', 1 ) );
		$file = HAPPY_ADDONS_DIR_PATH . '/' . $file_name . '.php';
		if ( is_readable( $file ) ) {
			include_once $file;
		}

		//For Class folder class load
		if ( 0 === strpos( $class_name, 'Happy_Addons\Elementor\Classes\\' ) ) {
			$file_name = str_replace( 'Happy_Addons\Elementor\Classes\\', '', $class_name );
			$file_name = str_replace( '_', '-', strtolower( $file_name ) );
			// error_log( print_r( $file_name.' File name' , 1 ) );
			// error_log( print_r( $class_name.' Class name' , 1 ) );
			$widget_file = HAPPY_ADDONS_DIR_PATH . 'classes/' . $file_name . '.php';
			if ( is_readable( $widget_file ) ) {
				include_once $widget_file;
				// error_log( print_r( $class_name.' Class name' , 1 ) );
			}
		}

		//For Controls folder class load
		if ( 0 === strpos( $class_name, 'Happy_Addons\Elementor\Controls\\' ) ) {
			$file_name = str_replace( 'Happy_Addons\Elementor\Controls\\', '', $class_name );
			$file_name = str_replace( '_', '-', strtolower( $file_name ) );
			// error_log( print_r( $file_name.' Controls name' , 1 ) );
			// error_log( print_r( $class_name.' Class name' , 1 ) );
			$widget_file = HAPPY_ADDONS_DIR_PATH . 'controls/' . $file_name . '.php';
			if ( is_readable( $widget_file ) ) {
				include_once $widget_file;
				// error_log( print_r( $class_name.' Class name' , 1 ) );
			}
		}

		//For Extensions folder class load
		if ( 0 === strpos( $class_name, 'Happy_Addons\Elementor\Extensions\\' ) ) {
			$file_name = str_replace( 'Happy_Addons\Elementor\Extensions\\', '', $class_name );
			$file_name = str_replace( '_', '-', strtolower( $file_name ) );
			// error_log( print_r( $file_name.' Extensions name' , 1 ) );
			// error_log( print_r( $class_name.' Class name' , 1 ) );
			$widget_file = HAPPY_ADDONS_DIR_PATH . 'extensions/' . $file_name . '.php';
			if ( is_readable( $widget_file ) ) {
				include_once $widget_file;
				// error_log( print_r( $class_name.' Class name' , 1 ) );
			}
		}

		//For Traits folder class load
		if ( 0 === strpos( $class_name, 'Happy_Addons\Elementor\Traits\\' ) ) {
			$file_name = str_replace( 'Happy_Addons\Elementor\Traits\\', '', $class_name );
			$file_name = str_replace( '_', '-', strtolower( $file_name ) );
			// error_log( print_r( $file_name.' Traits name' , 1 ) );
			// error_log( print_r( $class_name.' Class name' , 1 ) );
			$widget_file = HAPPY_ADDONS_DIR_PATH . 'traits/' . $file_name . '.php';
			if ( is_readable( $widget_file ) ) {
				include_once $widget_file;
				// error_log( print_r( $class_name.' Class name' , 1 ) );
			}
		}

		//For Widget class load
		if ( 0 === strpos( $class_name, 'Happy_Addons\Elementor\Widget\\' ) ) {
			$folder_name = str_replace( 'Happy_Addons\Elementor\Widget\\', '', $class_name );
			$folder_name = str_replace( '_', '-', strtolower( $folder_name ) );
			// error_log( print_r( $folder_name.' Folder name' , 1 ) );
			$widget_file = HAPPY_ADDONS_DIR_PATH . 'widgets/' . $folder_name . '/widget.php';
			if ( is_readable( $widget_file ) ) {
				include_once $widget_file;
				// error_log( print_r( $class_name.' Class name' , 1 ) );
			}
		}

	}
}
