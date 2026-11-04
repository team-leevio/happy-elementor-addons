<?php
/**
 * Registers the Happy Addons components for Elementor V4 (Atomic).
 *
 * Independent from the V3 widgets/features: its own options, its own
 * element types (ha-a-*) and its own assets.
 */
namespace Happy_Addons\Elementor\Classes;

defined( 'ABSPATH' ) || die();

class Atomic_Manager {

	const OPTION_ENABLED         = 'happyaddons_atomic_integration_enabled';
	const OPTION_ACTIVE_WIDGETS  = 'happyaddons_active_atomic_widgets';
	const OPTION_ACTIVE_FEATURES = 'happyaddons_active_atomic_features';

	const SCRIPT_HANDLE        = 'happy-elementor-addons-atomic';
	const EDITOR_SCRIPT_HANDLE = 'happy-elementor-addons-atomic-editor';

	/**
	 * Per-request feature configs collected while elements render.
	 *
	 * @var array
	 */
	private static $frontend_config = [];

	private static $registered = [];

	private static $features_initialized = false;

	/**
	 * Global switch. Off by default.
	 */
	public static function is_enabled() {
		return 'on' === get_option( self::OPTION_ENABLED, 'off' );
	}

	/**
	 * Elementor 4+, Atomic classes available and the Atomic experiment active.
	 */
	public static function is_environment_supported() {
		if ( ! defined( 'ELEMENTOR_VERSION' ) || version_compare( ELEMENTOR_VERSION, '4.0.0', '<' ) ) {
			return false;
		}

		if ( ! class_exists( '\Elementor\Modules\AtomicWidgets\Elements\Base\Atomic_Widget_Base' )
			|| ! class_exists( '\Elementor\Modules\AtomicWidgets\Elements\Base\Atomic_Element_Base' ) ) {
			return false;
		}

		$experiments = ha_elementor()->experiments;

		return $experiments && $experiments->is_feature_active( 'e_atomic_elements' );
	}

	public static function is_supported() {
		return self::is_enabled() && self::is_environment_supported();
	}

	/**
	 * Active component keys. Falls back to the registry defaults until the
	 * user saves for the first time.
	 *
	 * @param string $type widgets|features
	 * @return string[]
	 */
	public static function get_active( $type ) {
		$option   = 'widgets' === $type ? self::OPTION_ACTIVE_WIDGETS : self::OPTION_ACTIVE_FEATURES;
		$saved    = get_option( $option, null );
		$registry = 'widgets' === $type ? Atomic_Registry::widgets() : Atomic_Registry::features();

		if ( ! is_array( $saved ) ) {
			return array_keys( array_filter( $registry, function ( $item ) {
				return ! empty( $item['is_active'] ) && ( empty( $item['is_pro'] ) || ha_has_pro() );
			} ) );
		}

		$active = array_values( array_intersect( $saved, array_keys( $registry ) ) );

		// Pro components are inactive while Happy Addons Pro is not active.
		if ( ! ha_has_pro() ) {
			$active = array_values( array_filter( $active, function ( $key ) use ( $registry ) {
				return empty( $registry[ $key ]['is_pro'] );
			} ) );
		}

		return $active;
	}

	/**
	 * Pro features only run while Happy Addons Pro is active.
	 */
	public static function is_feature_available( $key ) {
		$item = Atomic_Registry::features()[ $key ] ?? null;

		return $item && ( empty( $item['is_pro'] ) || ha_has_pro() );
	}

	public static function save_active( $type, $keys ) {
		$registry = 'widgets' === $type ? Atomic_Registry::widgets() : Atomic_Registry::features();
		$option   = 'widgets' === $type ? self::OPTION_ACTIVE_WIDGETS : self::OPTION_ACTIVE_FEATURES;

		update_option( $option, array_values( array_intersect( (array) $keys, array_keys( $registry ) ) ) );
	}

	public static function init() {
		if ( ! self::is_supported() ) {
			return;
		}

		add_action( 'elementor/elements/elements_registered', [ __CLASS__, 'register_elements' ] );
		add_action( 'elementor/widgets/register', [ __CLASS__, 'register_widgets' ] );
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'register_assets' ], 20 );
		add_action( 'elementor/editor/after_enqueue_scripts', [ __CLASS__, 'register_assets' ] );
		add_action( 'elementor/preview/enqueue_scripts', [ __CLASS__, 'enqueue_preview_assets' ] );
		add_action( 'elementor/preview/enqueue_styles', [ __CLASS__, 'enqueue_preview_styles' ] );
		add_action( 'wp_footer', [ __CLASS__, 'print_frontend_config' ], 1 );
		add_action( 'elementor/editor/v2/scripts/enqueue', [ __CLASS__, 'enqueue_editor_script' ] );

		self::init_features();
	}

	/**
	 * Leaf Atomic widgets (registered through the widgets manager).
	 */
	public static function register_widgets( $widgets_manager ) {
		self::register_components( $widgets_manager, 'widget' );
	}

	/**
	 * Container Atomic elements (registered through the elements manager).
	 */
	public static function register_elements( $elements_manager ) {
		self::register_components( $elements_manager, 'element' );
	}

	private static function register_components( $manager, $kind ) {
		if ( isset( self::$registered[ $kind ] ) ) {
			return;
		}

		self::$registered[ $kind ] = true;

		$active = self::get_active( 'widgets' );

		foreach ( Atomic_Registry::widgets() as $key => $item ) {
			if ( ! in_array( $key, $active, true ) || ( $item['kind'] ?? 'element' ) !== $kind ) {
				continue;
			}

			try {
				include_once HAPPY_ADDONS_DIR_PATH . $item['file'];

				$class = '\Happy_Addons\Elementor\Widget\Atomic\\' . $item['class'];

				if ( class_exists( $class ) ) {
					'widget' === $kind ? $manager->register( new $class() ) : $manager->register_element_type( new $class() );
				}
			} catch ( \Throwable $e ) {
				error_log( sprintf( '[Happy Addons] Atomic widget "%s" failed to register: %s', $key, $e->getMessage() ) );
			}
		}
	}

	/**
	 * Include a feature's file and return its class name ('' when missing).
	 * Add-on features give an absolute 'path' and a full 'fqcn'.
	 */
	private static function load_feature( array $item ) {
		if ( empty( $item['path'] ) && empty( $item['file'] ) ) {
			return '';
		}

		if ( ! empty( $item['path'] ) ) {
			$file  = $item['path'];
			$class = '\\' . ltrim( $item['fqcn'], '\\' );
		} else {
			$file  = HAPPY_ADDONS_DIR_PATH . $item['file'];
			$class = '\Happy_Addons\Elementor\Extensions\Atomic\\' . $item['class'];
		}

		if ( is_readable( $file ) ) {
			include_once $file;
		}

		return class_exists( $class ) ? $class : '';
	}

	public static function init_features() {
		if ( self::$features_initialized ) {
			return;
		}

		self::$features_initialized = true;

		$active = self::get_active( 'features' );

		foreach ( Atomic_Registry::features() as $key => $item ) {
			if ( ! in_array( $key, $active, true ) || ! self::is_feature_available( $key ) ) {
				continue;
			}

			try {
				$class = self::load_feature( $item );

				if ( $class ) {
					$class::instance()->init();
				}
			} catch ( \Throwable $e ) {
				error_log( sprintf( '[Happy Addons] Atomic feature "%s" failed to init: %s', $key, $e->getMessage() ) );
			}
		}
	}

	/**
	 * Register (never enqueue) the Atomic assets. Elements and features
	 * enqueue them only when something actually renders.
	 */
	public static function register_assets() {
		$suffix = ha_is_script_debug_enabled() ? '.' : '.min.';

		if ( ! wp_script_is( self::SCRIPT_HANDLE, 'registered' ) ) {
			wp_register_script(
				self::SCRIPT_HANDLE,
				HAPPY_ADDONS_ASSETS . 'js/happy-addons-atomic' . $suffix . 'js',
				[ 'gsap', 'scroll-trigger', 'split-text', 'imagesloaded' ],
				HAPPY_ADDONS_VERSION,
				true
			);
		}

		// Editor preview of the Background Hover Effect; the frontend is pure CSS from PHP.
		if ( ! wp_script_is( 'happy-atomic-container-hover', 'registered' ) ) {
			wp_register_script(
				'happy-atomic-container-hover',
				HAPPY_ADDONS_ASSETS . 'js/happy-atomic-container-hover' . $suffix . 'js',
				[ self::SCRIPT_HANDLE ],
				HAPPY_ADDONS_VERSION,
				true
			);
		}

		// Happy Mouse Cursor: runtime for the Atomic elements; the cursor is the shared MouseFollower.
		// The Atomic assets can be registered before the Classic ones, and a script whose dependency is missing is dropped.
		if ( ! wp_script_is( 'mouse-follower', 'registered' ) ) {
			wp_register_script( 'mouse-follower', HAPPY_ADDONS_ASSETS . 'vendor/mouse-follower/mouse-follower.min.js', [ 'gsap' ], HAPPY_ADDONS_VERSION, true );
		}

		if ( ! wp_style_is( 'mouse-follower', 'registered' ) ) {
			wp_register_style( 'mouse-follower', HAPPY_ADDONS_ASSETS . 'vendor/mouse-follower/mouse-follower.min.css', [], HAPPY_ADDONS_VERSION );
		}

		// The Atomic assets can be registered before the Classic ones; the cursor needs its library.
		if ( ! wp_script_is( 'mouse-follower', 'registered' ) ) {
			wp_register_script( 'mouse-follower', HAPPY_ADDONS_ASSETS . 'vendor/mouse-follower/mouse-follower.min.js', [ 'gsap' ], HAPPY_ADDONS_VERSION, true );
		}

		if ( ! wp_style_is( 'mouse-follower', 'registered' ) ) {
			wp_register_style( 'mouse-follower', HAPPY_ADDONS_ASSETS . 'vendor/mouse-follower/mouse-follower.min.css', [], HAPPY_ADDONS_VERSION );
		}

		if ( ! wp_script_is( 'happy-atomic-custom-mouse-cursor', 'registered' ) ) {
			wp_register_script(
				'happy-atomic-custom-mouse-cursor',
				HAPPY_ADDONS_ASSETS . 'js/happy-atomic-custom-mouse-cursor' . $suffix . 'js',
				[ self::SCRIPT_HANDLE, 'mouse-follower' ],
				HAPPY_ADDONS_VERSION,
				true
			);
		}

		if ( ! wp_style_is( 'happy-atomic-cmc', 'registered' ) ) {
			wp_register_style(
				'happy-atomic-cmc',
				HAPPY_ADDONS_ASSETS . 'css/widgets/custom-mouse-cursor.min.css',
				[ 'mouse-follower' ],
				HAPPY_ADDONS_VERSION
			);
		}

		// Background Parallax: Jarallax drives the Atomic Flexbox / Div Block. The Atomic assets can be registered before the Classic ones.
		if ( ! wp_script_is( 'jarallax-js', 'registered' ) ) {
			wp_register_script( 'jarallax-js', HAPPY_ADDONS_ASSETS . 'vendor/jarallax/jarallax.min.js', null, HAPPY_ADDONS_VERSION, true );
		}

		if ( ! wp_style_is( 'jarallax-css', 'registered' ) ) {
			wp_register_style( 'jarallax-css', HAPPY_ADDONS_ASSETS . 'vendor/jarallax/jarallax.min.css', [], HAPPY_ADDONS_VERSION );
		}

		if ( ! wp_script_is( 'happy-atomic-background-parallax', 'registered' ) ) {
			wp_register_script(
				'happy-atomic-background-parallax',
				HAPPY_ADDONS_ASSETS . 'js/happy-atomic-background-parallax' . $suffix . 'js',
				[ self::SCRIPT_HANDLE, 'jarallax-js' ],
				HAPPY_ADDONS_VERSION,
				true
			);
		}

		$styles = [
			'happy-atomic-image-cycle' => 'image-cycle',
			'happy-atomic-aia'         => 'appearing-image-animation',
			'happy-atomic-hta'         => 'heading-text-animation',
		];

		foreach ( $styles as $handle => $file ) {
			if ( ! wp_style_is( $handle, 'registered' ) ) {
				wp_register_style(
					$handle,
					HAPPY_ADDONS_ASSETS . 'css/widgets/atomic/' . $file . $suffix . 'css',
					[],
					HAPPY_ADDONS_VERSION
				);
			}
		}
	}

	/**
	 * The editor preview renders Atomic elements client side, so the assets
	 * of the active widgets must already be on the page.
	 */
	public static function enqueue_preview_assets() {
		self::register_assets();

		$defaults = self::editor_feature_defaults();

		if ( array_intersect( [ 'image-cycle' ], self::get_active( 'widgets' ) ) || $defaults ) {
			wp_enqueue_script( self::SCRIPT_HANDLE );
		}

		foreach ( array_keys( $defaults ) as $key ) {
			$script = Atomic_Registry::features()[ $key ]['script'] ?? '';

			if ( $script ) {
				wp_enqueue_script( $script );
			}
		}

		// Tells the preview which features run there and their default settings
		// (the preview reads the rest from the editor).
		if ( $defaults ) {
			wp_add_inline_script(
				self::SCRIPT_HANDLE,
				'window.HappyAtomic = window.HappyAtomic || {}; window.HappyAtomic.defaults = ' . wp_json_encode( $defaults ) . ';',
				'before'
			);
		}
	}

	/**
	 * Defaults of the active features that animate in the editor preview.
	 *
	 * @return array<string,array>
	 */
	private static function editor_feature_defaults() {
		$defaults = [];

		foreach ( Atomic_Registry::features() as $key => $item ) {
			$previewed = in_array( $key, [ 'appearing-image-animation', 'text-animation' ], true ) || ! empty( $item['editor_preview'] );

			if ( ! $previewed || ! self::is_feature_available( $key ) || ! in_array( $key, self::get_active( 'features' ), true ) ) {
				continue;
			}

			$class = self::load_feature( $item );

			if ( $class ) {
				$defaults[ $key ] = $class::instance()->defaults();
			}
		}

		return $defaults;
	}

	/**
	 * Editor panel controls of the Atomic components (field wrapper, slider,
	 * image items). Loaded with the other Elementor V2 editor packages.
	 */
	public static function enqueue_editor_script() {
		$suffix = ha_is_script_debug_enabled() ? '.' : '.min.';

		$deps = array_filter(
			[
				'elementor-v2-editor-editing-panel',
				'elementor-v2-editor-controls',
				'elementor-v2-editor-props',
				'elementor-v2-schema',
				'elementor-v2-ui',
				'elementor-v2-icons',
				'elementor-v2-editor-ui',
				'react',
			],
			function ( $handle ) {
				return wp_script_is( $handle, 'registered' );
			}
		);

		wp_enqueue_script(
			self::EDITOR_SCRIPT_HANDLE,
			HAPPY_ADDONS_ASSETS . 'js/happy-atomic-editor' . $suffix . 'js',
			array_values( $deps ),
			HAPPY_ADDONS_VERSION,
			true
		);
	}

	public static function enqueue_preview_styles() {
		self::register_assets();

		$styles = [
			'image-cycle' => 'happy-atomic-image-cycle',
		];

		foreach ( self::get_active( 'widgets' ) as $key ) {
			if ( isset( $styles[ $key ] ) ) {
				wp_enqueue_style( $styles[ $key ] );
			}
		}

		if ( in_array( 'appearing-image-animation', self::get_active( 'features' ), true ) ) {
			wp_enqueue_style( 'happy-atomic-aia' );
		}

		if ( in_array( 'text-animation', self::get_active( 'features' ), true ) ) {
			wp_enqueue_style( 'happy-atomic-hta' );
		}

		foreach ( Atomic_Registry::features() as $key => $item ) {
			if ( ! empty( $item['style'] ) && self::is_feature_available( $key ) && in_array( $key, self::get_active( 'features' ), true ) ) {
				wp_enqueue_style( $item['style'] );
			}
		}
	}

	/**
	 * Store a feature config for an element and make sure the assets load.
	 *
	 * @param string $feature appearing-image-animation|text-animation
	 * @param string $element_id
	 * @param array  $config
	 */
	public static function register_frontend_config( $feature, $element_id, array $config ) {
		self::register_assets();

		self::$frontend_config[ $feature ][ $element_id ] = $config;

		wp_enqueue_script( self::SCRIPT_HANDLE );

		$item  = Atomic_Registry::features()[ $feature ] ?? [];
		$style = $item['style'] ?? ( 'appearing-image-animation' === $feature ? 'happy-atomic-aia' : 'happy-atomic-hta' );

		wp_enqueue_style( $style );

		if ( ! empty( $item['script'] ) ) {
			wp_enqueue_script( $item['script'] );
		}
	}

	/**
	 * Emit all collected feature configs once, only when some exist.
	 */
	public static function print_frontend_config() {
		if ( empty( self::$frontend_config ) ) {
			return;
		}

		// Keep animated targets hidden until the script takes over (no flash of
		// the final state). The keyframe is a safety net if the script never runs.
		$ids = [];

		foreach ( self::$frontend_config as $feature => $elements ) {
			// A feature that works on a whole container can opt out ('prehide' => false).
			if ( false === ( Atomic_Registry::features()[ $feature ]['prehide'] ?? true ) ) {
				continue;
			}

			$ids = array_merge( $ids, array_keys( $elements ) );
		}

		if ( ! $ids ) {
			wp_add_inline_script(
				self::SCRIPT_HANDLE,
				'window.HappyAtomic = window.HappyAtomic || {}; window.HappyAtomic.features = ' . wp_json_encode( self::$frontend_config ) . ';',
				'before'
			);

			return;
		}

		$selectors = array_map( function ( $id ) {
			return '[data-id="' . preg_replace( '/[^a-zA-Z0-9_-]/', '', $id ) . '"]';
		}, $ids );

		echo '<style id="happy-atomic-prehide">@media (prefers-reduced-motion:no-preference){'
			. implode( ',', $selectors )
			. '{visibility:hidden;animation:ha-atomic-reveal 0s 3s forwards}}@keyframes ha-atomic-reveal{to{visibility:visible}}</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		wp_add_inline_script(
			self::SCRIPT_HANDLE,
			'window.HappyAtomic = window.HappyAtomic || {}; window.HappyAtomic.features = ' . wp_json_encode( self::$frontend_config ) . ';',
			'before'
		);
	}
}
