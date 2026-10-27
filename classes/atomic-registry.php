<?php
/**
 * Catalog of Happy Addons components available for Elementor V4 (Atomic).
 *
 * A plain static list: nothing is instantiated and labels are translated lazily.
 */
namespace Happy_Addons\Elementor\Classes;

defined( 'ABSPATH' ) || die();

class Atomic_Registry {

	/**
	 * @return array[]
	 */
	public static function widgets() {
		return [
			'image-cycle' => [
				'title'     => __( 'Image Cycle', 'happy-elementor-addons' ),
				'icon'      => 'hm hm-net',
				'is_active' => true,
				'is_pro'    => false,
				'is_gsap'   => true,
				'class'     => 'Image_Cycle',
				'kind'      => 'widget',
				'file'      => 'widgets/atomic/image-cycle/widget.php',
			],
		];
	}

	/**
	 * @return array[]
	 */
	public static function features() {
		return [
			'appearing-image-animation' => [
				'title'     => __( 'Appearing Image Animation', 'happy-elementor-addons' ),
				'icon'      => 'huge huge-layer-mask-1',
				'is_active' => true,
				'is_pro'    => false,
				'is_gsap'   => true,
				'class'     => 'Appearing_Image_Animation',
				'file'      => 'extensions/atomic/appearing-image-animation.php',
			],
			'text-animation'            => [
				'title'     => __( 'Text Animation', 'happy-elementor-addons' ),
				'icon'      => 'hm hm-text-outline',
				'is_active' => true,
				'is_pro'    => false,
				'is_gsap'   => true,
				'class'     => 'Text_Animation',
				'file'      => 'extensions/atomic/text-animation.php',
			],
		];
	}
}
