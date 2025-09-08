<?php
namespace Happy_Addons\Elementor\Classes;

defined( 'ABSPATH' ) || die();

use Happy_Addons\Elementor\Extensions as Features;

class Extensions_Manager {
	const FEATURES_DB_KEY = 'happyaddons_inactive_features';

	/**
	 * Initialize
	 */
	public static function init() {

		add_action( 'elementor/element/button/section_style/after_section_start', [ Features\Fixed_Size_Button::class, 'add_button_controls' ] );

		$inactive_features = self::get_inactive_features();

		foreach ( self::get_local_features_map() as $feature_key => $data ) {
			if ( ! in_array( $feature_key, $inactive_features ) ) {
				self::enable_feature( $feature_key );
			}
		}

		foreach ( self::get_pro_features_map() as $feature_key => $data ) {
			if ( in_array( $feature_key, $inactive_features ) ) {
				self::disable_pro_feature( $feature_key );
			}
		}
	}

	public static function get_features_map() {
		$features_map = [];

		$local_features_map = self::get_local_features_map();
		$features_map = array_merge( $features_map, $local_features_map );

		return apply_filters( 'happyaddons_get_features_map', $features_map );
	}

	public static function get_inactive_features() {
		return get_option( self::FEATURES_DB_KEY, [] );
	}

	public static function save_inactive_features( $features = [] ) {
		update_option( self::FEATURES_DB_KEY, $features );
	}

	/**
	 * Get the pro features map for dashboard only
	 *
	 * @return array
	 */
	public static function get_pro_features_map() {
		$pro_features_map = [
			'display-conditions' => [
				'title' => __( 'Display Condition', 'happy-elementor-addons' ),
				'icon' => 'hm hm-display-condition',
				'demo' => 'https://happyaddons.com/display-condition/',
				'is_pro' => true,
			],
			'image-masking' => [
				'title' => __( 'Image Masking', 'happy-elementor-addons' ),
				'icon' => 'hm hm-image-masking',
				'demo' => 'https://happyaddons.com/image-masking-demo/',
				'is_pro' => true,
			],
			'happy-particle-effects' => [
				'title' => __( 'Happy Particle Effects', 'happy-elementor-addons' ),
				'icon' => 'hm hm-spark',
				'demo' => 'https://happyaddons.com/happy-particle-effect/',
				'is_pro' => true,
			],
			'happy-preset' => [
				'title' => __( 'Preset', 'happy-elementor-addons' ),
				'icon' => 'hm hm-color-card',
				'demo' => 'https://happyaddons.com/presets-demo/',
				'is_pro' => true,
			],
			'global-badge' => [
				'title' => __( 'Global Badge', 'happy-elementor-addons' ),
				'icon' => 'hm hm-global-badge',
				'demo' => 'https://happyaddons.com/global-badge/',
				'is_pro' => true,
			],
		];

		return apply_filters( 'happyaddons_get_pro_features_map', $pro_features_map );
	}

	/**
	 * Get the free features map
	 *
	 * @return array
	 */
	public static function get_local_features_map() {
		return [
			'background-overlay' => [
				'title' => __( 'Background Overlay', 'happy-elementor-addons' ),
				'icon' => 'hm hm-layer',
				'demo' => 'https://happyaddons.com/background-overlay-demo/',
				'is_pro' => false,
			],
			'grid-layer' => [
				'title' => __( 'Grid Layer', 'happy-elementor-addons' ),
				'icon' => 'hm hm-grid',
				'demo' => 'https://happyaddons.com/happy-grid-layout-demo/',
				'is_pro' => false,
			],
			'floating-effects' => [
				'title' => __( 'Floating Effects', 'happy-elementor-addons' ),
				'icon' => 'hm hm-weather-flood',
				'demo' => 'https://happyaddons.com/elementor-floating-effect-demo-2/',
				'is_pro' => false,
			],
			'wrapper-link' => [
				'title' => __( 'Wrapper Link', 'happy-elementor-addons' ),
				'icon' => 'hm hm-section-link',
				'demo' => 'https://happyaddons.com/wrapper-link-feature-demo/',
				'is_pro' => false,
			],
			'css-transform' => [
				'title' => __( 'CSS Transform', 'happy-elementor-addons' ),
				'icon' => 'hm hm-3d-rotate',
				'demo' => 'https://happyaddons.com/elementor-css-transform-demo-3/',
				'is_pro' => false,
			],
			'equal-height' => [
				'title' => __( 'Equal Height Column', 'happy-elementor-addons' ),
				'icon' => 'hm hm-grid-layout',
				'demo' => 'https://happyaddons.com/equal-height-feature/',
				'is_pro' => false,
			],
			'shape-divider' => [
				'title' => __( 'Shape Divider', 'happy-elementor-addons' ),
				'icon' => 'hm hm-map',
				'demo' => 'https://happyaddons.com/happy-shape-divider/',
				'is_pro' => false,
			],
			'column-extended' => [
				'title' => __( 'Column Order & Extension', 'happy-elementor-addons' ),
				'icon' => 'hm hm-flip-card2',
				'demo' => 'https://happyaddons.com/happy-column-control/',
				'is_pro' => false,
			],
			'advanced-tooltip' => [
				'title' => __( 'Happy Tooltip', 'happy-elementor-addons' ),
				'icon' => 'hm hm-comment-square',
				'demo' => 'https://happyaddons.com/happy-tooltip/',
				'is_pro' => false,
			],
			'text-stroke' => [
				'title' => __( 'Text Stroke', 'happy-elementor-addons' ),
				'icon' => 'hm hm-text-outline',
				'demo' => 'https://happyaddons.com/text-stroke/',
				'is_pro' => false,
			],
			'scroll-to-top' => [
				'title' => __( 'Scroll To Top', 'happy-elementor-addons' ),
				'icon' => 'hm hm-scroll-top',
				// 'demo' => 'https://happyaddons.com/scroll-to-top/',
				'is_pro' => false,
			],
			'reading-progress-bar' => [
				'title' => __( 'Reading Progress Bar', 'happy-elementor-addons' ),
				'icon' => 'hm hm-reading-glass-alt',
				'demo' => 'https://happyaddons.com/reading-progress-bar/',
				'is_pro' => false,
			],
			'custom-mouse-cursor' => [
				'title' => __( 'Happy Mouse Cursor', 'happy-elementor-addons' ),
				'icon' => 'hm hm-cursor-hover-click',
				'demo' => 'https://demo-x.happyaddons.com/custom-mouse-cursor-feature-demo/',
				'is_pro' => false,
			],
			'custom-js' => [
				'title' => __( 'Custom JS', 'happy-elementor-addons' ),
				'icon' => 'huge huge-code',
				// 'demo' => 'https://happyaddons.com/custom-js/',
				'is_pro' => false,
			],
			'background-parallax' => [
				'title' => __( 'Background Parallax', 'happy-addons-pro' ),
				'icon' => 'huge huge-web-design-02',
				'demo' => 'https://happyaddons.com/docs/happy-addons-for-elementor/happy-features/background-parallax/',
				'is_pro' => false,
			]
		];
	}

	protected static function enable_feature( $feature_key ) {

		switch ($feature_key) {
			case 'background-overlay':
				add_action( 'elementor/element/common/_section_background/after_section_end', [Features\Background_Overlay::class, 'add_section'] );
				break;

			case 'grid-layer':
				add_action('elementor/documents/register_controls', [Features\Grid_Layer::class, 'add_controls_section'] , 1 , 1 );
				break;

			case 'floating-effects':
				add_action( 'elementor/element/common/_section_style/after_section_end', [ Features\Floating_Effects::class, 'register' ], 1 );
				add_action( 'elementor/frontend/before_register_scripts', [ Features\Floating_Effects::class, 'register_scripts' ] );
				add_action( 'elementor/preview/enqueue_scripts', [ Features\Floating_Effects::class, 'preview_enqueue_scripts' ] );
				break;

			case 'wrapper-link':
				add_action( 'elementor/element/container/section_layout/after_section_end', [ Features\Wrapper_Link::class, 'add_controls_section' ], 1 );
				add_action( 'elementor/element/column/section_advanced/after_section_end', [ Features\Wrapper_Link::class, 'add_controls_section' ], 1 );
				add_action( 'elementor/element/section/section_advanced/after_section_end', [ Features\Wrapper_Link::class, 'add_controls_section' ], 1 );
				add_action( 'elementor/element/common/_section_style/after_section_end', [ Features\Wrapper_Link::class, 'add_controls_section' ], 1 );
				add_action( 'elementor/frontend/before_render', [ Features\Wrapper_Link::class, 'before_section_render' ], 1 );
				break;

			case 'css-transform':
				add_action( 'elementor/element/common/_section_style/after_section_end', [ Features\CSS_Transform::class, 'register' ], 1 );
				break;

			case 'equal-height':
				add_action( 'elementor/element/container/section_layout/after_section_end', [ Features\Equal_Height::class, 'register' ], 1 );
				add_action( 'elementor/element/section/section_advanced/after_section_end', [ Features\Equal_Height::class, 'register' ], 1 );
				add_action( 'elementor/frontend/before_register_scripts', [ Features\Equal_Height::class, 'register_scripts' ] );
				add_action( 'elementor/preview/enqueue_scripts', [ Features\Equal_Height::class, 'enqueue_preview_scripts' ] );
				break;

			case 'shape-divider':
				add_filter( 'elementor/shapes/additional_shapes', [Features\Shape_Divider::class, 'additional_shape_divider'] );
				add_action( 'elementor/element/section/section_shape_divider/before_section_end', [Features\Shape_Divider::class, 'update_shape_list'] );
				add_action( 'elementor/element/container/section_shape_divider/before_section_end', [Features\Shape_Divider::class, 'update_shape_list'] );
				break;

			case 'column-extended':
				add_action( 'elementor/element/column/layout/before_section_end', [ Features\Column_Extended::class, 'add_controls' ] );
				break;

			case 'advanced-tooltip':
				add_action('elementor/element/common/_section_style/after_section_end', [Features\Advanced_Tooltip::class, 'add_controls_section'], 1);
				add_action( 'elementor/frontend/before_register_scripts', [ Features\Advanced_Tooltip::class, 'register_scripts' ] );
				add_action( 'elementor/preview/enqueue_scripts', [ Features\Advanced_Tooltip::class, 'enqueue_preview_scripts' ] );
				break;

			case 'text-stroke':
				if( ! in_array( 'text-stroke', ha_get_inactive_features() ) ) {
					add_action( 'elementor/element/heading/section_title_style/before_section_end', [ Features\Text_Stroke::class, 'add_text_stroke' ] );
					add_action( 'elementor/element/theme-page-title/section_title_style/before_section_end', [ Features\Text_Stroke::class, 'add_text_stroke' ] );
					add_action( 'elementor/element/theme-site-title/section_title_style/before_section_end', [ Features\Text_Stroke::class, 'add_text_stroke' ] );
					add_action( 'elementor/element/theme-post-title/section_title_style/before_section_end', [ Features\Text_Stroke::class, 'add_text_stroke' ] );
					add_action( 'elementor/element/woocommerce-product-title/section_title_style/before_section_end', [ Features\Text_Stroke::class, 'add_text_stroke' ] );
					add_action( 'elementor/element/animated-headline/section_style_text/before_section_end', [ Features\Text_Stroke::class, 'add_text_stroke' ] );
					add_action( 'elementor/element/ha-gradient-heading/_section_style_title/before_section_end', [ Features\Text_Stroke::class, 'add_text_stroke' ] );
				}
				break;

			// case 'custom-js':
			// 	add_action( 'elementor/documents/register_controls', [Features\Custom_Js::class, 'scroll_to_top_controls'], 10 );
			// 	add_action( 'wp_footer', [Features\Custom_Js::class, 'render_scroll_to_top_html'] );
			// 	break;

			case 'scroll-to-top':
			case 'reading-progress-bar':
			case 'custom-mouse-cursor':
			case 'custom-js':
			case 'background-parallax':
				$cls_name = ucwords( str_replace( '-', ' ', $feature_key ) ); //remove ' - ' & uc first later
				$cls_name = '\Happy_Addons\Elementor\Extensions\\' . str_replace( ' ', '_', $cls_name );
				$cls_name::instance()->init();
				break;
		}
	}

	protected static function disable_pro_feature( $feature_key ) {
		switch ($feature_key) {
			case 'display-conditions':
				add_filter( 'happyaddons/extensions/display_condition', '__return_false' );
				break;

			case 'image-masking':
				add_filter( 'happyaddons/extensions/image_masking', '__return_false' );
				break;

			case 'happy-particle-effects':
				add_filter( 'happyaddons/extensions/happy_particle_effects', '__return_false' );
				break;

			case 'global-badge':
				add_filter( 'happyaddons/extensions/happy_global_badge', '__return_false' );
				break;

			// case 'happy-preset':
			// 	add_filter( 'happyaddons/extensions/happy_preset', '__return_false' );
			// 	break;
		}
	}
}
