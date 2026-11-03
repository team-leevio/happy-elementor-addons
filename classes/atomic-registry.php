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
				'cat'       => 'creative',
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
	 * Pro features are listed here too (title, icon, 'is_pro') so the dashboard
	 * shows them, locked, without Happy Addons Pro, as in the Classic lists.
	 *
	 * Happy Addons Pro supplies the implementation through the
	 * "happyaddons_atomic_features" filter: the same entry plus 'path' (absolute
	 * file), 'fqcn' (feature class) and optionally 'script' / 'style' (registered
	 * handles enqueued with it) and 'editor_preview' (true when it also runs in
	 * the editor preview).
	 *
	 * @return array[]
	 */
	public static function features() {
		$features = [
			'global-animation'          => [
				'title'     => __( 'Animation', 'happy-elementor-addons' ),
				'icon'      => 'hm hm-alien',
				'is_active' => true,
				'is_pro'    => true,
				'is_gsap'   => true,
			],
			'appearing-image-animation' => [
				'title'     => __( 'Appearing Image Animation', 'happy-elementor-addons' ),
				'icon'      => 'huge huge-layer-mask-1',
				'is_active' => true,
				'is_pro'    => false,
				'is_gsap'   => true,
				'class'     => 'Appearing_Image_Animation',
				'file'      => 'extensions/atomic/appearing-image-animation.php',
			],
			'container-hover-effect'    => [
				'title'     => __( 'Background Hover Effect', 'happy-elementor-addons' ),
				'icon'      => 'hm hm-cursor-hover-click',
				'is_active' => true,
				'is_pro'    => false,
				'is_gsap'   => false,
				'class'     => 'Container_Hover_Effect',
				'file'      => 'extensions/atomic/container-hover-effect.php',
				'script'         => 'happy-atomic-container-hover',
				'editor_preview' => true,
			],
			'foreground-overlay'        => [
				'title'     => __( 'Foreground Overlay', 'happy-elementor-addons' ),
				'icon'      => 'hm hm-layer',
				'is_active' => true,
				'is_pro'    => false,
				'is_gsap'   => false,
				'class'     => 'Foreground_Overlay',
				'file'      => 'extensions/atomic/foreground-overlay.php',
				'script'         => 'happy-atomic-foreground-overlay',
				'editor_preview' => true,
			],
			'liquid-glass'              => [
				'title'     => __( 'Liquid Glass', 'happy-elementor-addons' ),
				'icon'      => 'hm hm-reading-glass',
				'is_active' => true,
				'is_pro'    => false,
				'is_gsap'   => false,
				'class'     => 'Liquid_Glass',
				'file'      => 'extensions/atomic/liquid-glass.php',
				'script'         => 'happy-atomic-liquid-glass',
				'editor_preview' => true,
			],
			'text-stroke'               => [
				'title'     => __( 'Text Stroke', 'happy-elementor-addons' ),
				'icon'      => 'hm hm-text-outline',
				'is_active' => true,
				'is_pro'    => false,
				'is_gsap'   => false,
				'class'     => 'Text_Stroke',
				'file'      => 'extensions/atomic/text-stroke.php',
				'script'         => 'happy-atomic-text-stroke',
				'editor_preview' => true,
			],
			'fixed-size-button'         => [
				'title'     => __( 'Fixed Size Button', 'happy-elementor-addons' ),
				'icon'      => 'hm hm-fixed-size-button',
				'is_active' => true,
				'is_pro'    => false,
				'is_gsap'   => false,
				'class'     => 'Fixed_Size_Button',
				'file'      => 'extensions/atomic/fixed-size-button.php',
				'script'         => 'happy-atomic-fixed-size-button',
				'editor_preview' => true,
			],
			'tooltip'                   => [
				'title'     => __( 'Happy Tooltip', 'happy-elementor-addons' ),
				'icon'      => 'hm hm-comment-square',
				'is_active' => true,
				'is_pro'    => false,
				'is_gsap'   => false,
				'class'     => 'Tooltip',
				'file'      => 'extensions/atomic/tooltip.php',
				'script'         => 'happy-atomic-tooltip',
				'style'          => 'happy-atomic-tooltip',
				'editor_preview' => true,
				'prehide'        => false,
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
			'infinite-marquee'          => [
				'title'     => __( 'Infinite Marquee', 'happy-elementor-addons' ),
				'icon'      => 'huge huge-infinity-02',
				'is_active' => true,
				'is_pro'    => true,
				'is_gsap'   => true,
			],
			'horizontal-scroll'         => [
				'title'     => __( 'Horizontal Scroll', 'happy-elementor-addons' ),
				'icon'      => 'huge huge-horizonal-scroll-point-round',
				'is_active' => true,
				'is_pro'    => true,
				'is_gsap'   => true,
			],
			'sticky-pin-element'        => [
				'title'     => __( 'Sticky Pin Element', 'happy-elementor-addons' ),
				'icon'      => 'hm hm-sticky',
				'is_active' => true,
				'is_pro'    => true,
				'is_gsap'   => true,
			],
		];

		$extra = apply_filters( 'happyaddons_atomic_features', [] );

		if ( is_array( $extra ) ) {
			foreach ( $extra as $key => $item ) {
				if ( ! is_array( $item ) || empty( $item['path'] ) || empty( $item['fqcn'] ) ) {
					continue;
				}

				// A listed (Pro) entry keeps its title and icon; the add-on adds the code.
				$features[ $key ] = isset( $features[ $key ] ) && empty( $features[ $key ]['file'] )
					? array_merge( $features[ $key ], $item )
					: ( $features[ $key ] ?? $item );
			}
		}

		return $features;
	}
}
