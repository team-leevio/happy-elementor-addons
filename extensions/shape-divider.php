<?php
namespace Happy_Addons\Elementor\Extension;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;

defined( 'ABSPATH' ) || die();

class Shape_Divider {

	public static function init() {
		add_filter( 'elementor/shapes/additional_shapes', [__CLASS__, 'additional_shape_divider'] );
		add_action( 'elementor/element/section/section_shape_divider/before_section_end', [__CLASS__, 'update_shape_list'] );
	}

	public static function update_shape_list( Element_Base $element ) {

		$elementor_shapes_options = [];
		$happy_shapes_options = [];

		foreach ( \Elementor\Shapes::get_shapes() as $shape_name => $shape_props ) {
			if ( ! isset( $shape_props['ha_shape'] ) ) {
				$elementor_shapes_options[ $shape_name ] = $shape_props['title'];
			}else{
				$happy_shapes_options[ $shape_name ] = $shape_props['title'];
			}
		}

		$element->update_control(
			'shape_divider_top',
			[
				'type' => Controls_Manager::SELECT,
				'groups' => [
					[
						'label' => __( 'None', 'happy-elementor-addons' ),
						'options' => [
							'' => __( 'None', 'happy-elementor-addons' ),
						],
					],
					[
						'label' => __( 'Elementor Shapes', 'happy-elementor-addons' ),
						'options' => $elementor_shapes_options,
					],
					[
						'label' => __( 'HappyAddon Shapes', 'happy-elementor-addons' ),
						'options' => $happy_shapes_options,
					],
				],
			]
		);
		$element->update_control(
			'shape_divider_bottom',
			[
				'type' => Controls_Manager::SELECT,
				'groups' => [
					[
						'label' => __( 'None', 'happy-elementor-addons' ),
						'options' => [
							'' => __( 'None', 'happy-elementor-addons' ),
						],
					],
					[
						'label' => __( 'Elementor Shapes', 'happy-elementor-addons' ),
						'options' => $elementor_shapes_options,
					],
					[
						'label' => __( 'HappyAddon Shapes', 'happy-elementor-addons' ),
						'options' => $happy_shapes_options,
					],
				],
			]
		);
	}

	public static function additional_shape_divider( $shape_list ) {
		$shape_list = [
			'abstruct-web' => [
				'title' => _x( 'Abstruct Web', 'Shapes', 'happy-elementor-addons' ),
				'path' => HAPPY_ADDONS_DIR_PATH . 'assets/imgs/shape-divider/dabstruct-web.svg',
				'url' => HAPPY_ADDONS_ASSETS . 'imgs/shape-divider/abstruct-web.svg',
				'has_flip' => true,
				'has_negative' => false,
				'ha_shape' => true,
			],
			'crossline' => [
				'title' => _x( 'Crossline', 'Shapes', 'happy-elementor-addons' ),
				'path' => HAPPY_ADDONS_DIR_PATH . 'assets/imgs/shape-divider/dcrossline.svg',
				'url' => HAPPY_ADDONS_ASSETS . 'imgs/shape-divider/crossline.svg',
				'has_flip' => true,
				'has_negative' => false,
				'ha_shape' => true,
			],
			'droplet' => [
				'title' => _x( 'Droplet', 'Shapes', 'happy-elementor-addons' ),
				'path' => HAPPY_ADDONS_DIR_PATH . 'assets/imgs/shape-divider/ddroplet.svg',
				'url' => HAPPY_ADDONS_ASSETS . 'imgs/shape-divider/droplet.svg',
				'has_flip' => true,
				'has_negative' => true,
				'ha_shape' => true,
			],
			'flame' => [
				'title' => _x( 'Flame', 'Shapes', 'happy-elementor-addons' ),
				'path' => HAPPY_ADDONS_DIR_PATH . 'assets/imgs/shape-divider/dflame.svg',
				'url' => HAPPY_ADDONS_ASSETS . 'imgs/shape-divider/flame.svg',
				'has_flip' => true,
				'has_negative' => false,
				'ha_shape' => true,
			],
			'frame' => [
				'title' => _x( 'Frame', 'Shapes', 'happy-elementor-addons' ),
				'path' => HAPPY_ADDONS_DIR_PATH . 'assets/imgs/shape-divider/dframe.svg',
				'url' => HAPPY_ADDONS_ASSETS . 'imgs/shape-divider/frame.svg',
				'has_flip' => true,
				'has_negative' => true,
				'ha_shape' => true,
			],
			'half-circle' => [
				'title' => _x( 'Half Circle', 'Shapes', 'happy-elementor-addons' ),
				'path' => HAPPY_ADDONS_DIR_PATH . 'assets/imgs/shape-divider/dhalf-circle.svg',
				'url' => HAPPY_ADDONS_ASSETS . 'imgs/shape-divider/half-circle.svg',
				'has_flip' => true,
				'has_negative' => true,
				'ha_shape' => true,
			],
			'multi-cloud' => [
				'title' => _x( 'Multi Cloud', 'Shapes', 'happy-elementor-addons' ),
				'path' => HAPPY_ADDONS_DIR_PATH . 'assets/imgs/shape-divider/dmulti-cloud.svg',
				'url' => HAPPY_ADDONS_ASSETS . 'imgs/shape-divider/multi-cloud.svg',
				'has_flip' => true,
				'has_negative' => false,
				'ha_shape' => true,
			],
			'multi-web' => [
				'title' => _x( 'Multi Web', 'Shapes', 'happy-elementor-addons' ),
				'path' => HAPPY_ADDONS_DIR_PATH . 'assets/imgs/shape-divider/dmulti-web.svg',
				'url' => HAPPY_ADDONS_ASSETS . 'imgs/shape-divider/multi-web.svg',
				'has_flip' => true,
				'has_negative' => false,
				'ha_shape' => true,
			],
			'smooth-zigzag' => [
				'title' => _x( 'Smooth Zigzag', 'Shapes', 'happy-elementor-addons' ),
				'path' => HAPPY_ADDONS_DIR_PATH . 'assets/imgs/shape-divider/dsmooth-zigzag.svg',
				'url' => HAPPY_ADDONS_ASSETS . 'imgs/shape-divider/smooth-zigzag.svg',
				'has_flip' => true,
				'has_negative' => false,
				'ha_shape' => true,
			],
			'splash' => [
				'title' => _x( 'Splash', 'Shapes', 'happy-elementor-addons' ),
				'path' => HAPPY_ADDONS_DIR_PATH . 'assets/imgs/shape-divider/dsplash.svg',
				'url' => HAPPY_ADDONS_ASSETS . 'imgs/shape-divider/splash.svg',
				'has_flip' => true,
				'has_negative' => true,
				'ha_shape' => true,
			],
			'splash2' => [
				'title' => _x( 'Splash 2', 'Shapes', 'happy-elementor-addons' ),
				'path' => HAPPY_ADDONS_DIR_PATH . 'assets/imgs/shape-divider/dsplash2.svg',
				'url' => HAPPY_ADDONS_ASSETS . 'imgs/shape-divider/splash2.svg',
				'has_flip' => true,
				'has_negative' => true,
				'ha_shape' => true,
			],
			'torn-paper' => [
				'title' => _x( 'Torn Paper', 'Shapes', 'happy-elementor-addons' ),
				'path' => HAPPY_ADDONS_DIR_PATH . 'assets/imgs/shape-divider/dtorn-paper.svg',
				'url' => HAPPY_ADDONS_ASSETS . 'imgs/shape-divider/torn-paper.svg',
				'has_flip' => true,
				'has_negative' => false,
				'ha_shape' => true,
			]
		];

		/* $shapes = [];
		foreach ( $shape_list as $list ){

			$shapes [ $list]  = [
					'title' => _x( $list , 'Shapes', 'happy-elementor-addons' ),
					'path' => HAPPY_ADDONS_DIR_PATH . 'assets/imgs/shape-divider/'.$list.'.svg',
					'url' => HAPPY_ADDONS_ASSETS . 'imgs/shape-divider/'.$list.'.svg',
					'has_flip' => true
					'has_negative' => false,
					'ha_shape' => true,

			];
		} */

		echo '<pre>';
		var_dump( $shape_list );
		echo '</pre>';
		/*
			svg path should contain elementor class to show in editor mode
		*/

		return $shape_list;
	}
}

Shape_Divider::init();
