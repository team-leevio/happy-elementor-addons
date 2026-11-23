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
		return [];
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
				'title'     => __( 'Global Animation', 'happy-elementor-addons' ),
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
				'is_extension' => true,
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
				'is_extension' => true,
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
				'is_extension' => true,
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
				'is_extension' => true,
				'class'     => 'Fixed_Size_Button',
				'file'      => 'extensions/atomic/fixed-size-button.php',
				'script'         => 'happy-atomic-fixed-size-button',
				'editor_preview' => true,
			],
			'floating-effects'          => [
				'title'     => __( 'Floating Effects', 'happy-elementor-addons' ),
				'icon'      => 'hm hm-weather-flood',
				'is_active' => true,
				'is_pro'    => false,
				'is_gsap'   => false,
				'class'     => 'Floating_Effects',
				'file'      => 'extensions/atomic/floating-effects.php',
				'script'         => 'happy-atomic-floating-effects',
				'style'          => '',
				'editor_preview' => true,
				'prehide'        => false,
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
			'custom-mouse-cursor'       => [
				'title'          => __( 'Happy Mouse Cursor', 'happy-elementor-addons' ),
				'icon'           => 'hm hm-cursor-hover-click',
				'is_active'      => true,
				'is_pro'         => false,
				'is_gsap'        => true,
				'class'          => 'Custom_Mouse_Cursor',
				'file'           => 'extensions/atomic/custom-mouse-cursor.php',
				'script'         => 'happy-atomic-custom-mouse-cursor',
				'style'          => 'happy-atomic-cmc',
				'editor_preview' => true,
				'prehide'        => false,
			],
			'background-parallax'     => [
				'title'          => __( 'Background Parallax', 'happy-elementor-addons' ),
				'icon'           => 'hm hm-scrolling-image',
				'is_active'      => true,
				'is_pro'         => false,
				'is_gsap'        => false,
				'class'          => 'Background_Parallax',
				'file'           => 'extensions/atomic/background-parallax.php',
				'script'         => 'happy-atomic-background-parallax',
				'style'          => 'jarallax-css',
				'editor_preview' => true,
				'prehide'        => false,
			],
			// 'shared' features keep their Classic code, which already runs in V4; the entry only drives the dashboard on/off switch.
			'scroll-to-top'             => [
				'title'     => __( 'Scroll To Top', 'happy-elementor-addons' ),
				'icon'      => 'hm hm-scroll-top',
				'is_active' => true,
				'is_pro'    => false,
				'is_gsap'   => false,
				'shared'    => true,
			],
			'reading-progress-bar'      => [
				'title'     => __( 'Reading Progress Bar', 'happy-elementor-addons' ),
				'icon'      => 'hm hm-reading-glass-alt',
				'is_active' => true,
				'is_pro'    => false,
				'is_gsap'   => false,
				'shared'    => true,
			],
			'custom-js'                 => [
				'title'     => __( 'Custom JS', 'happy-elementor-addons' ),
				'icon'      => 'huge huge-code',
				'is_active' => true,
				'is_pro'    => false,
				'is_gsap'   => false,
				'shared'    => true,
			],
			'happy-particle-effects'  => [
				'title'     => __( 'Happy Particle Effects', 'happy-elementor-addons' ),
				'icon'      => 'hm hm-spark',
				'is_active' => true,
				'is_pro'    => true,
				'is_gsap'   => false,
			],
			'multi-layer-parallax'    => [
				'title'     => __( 'Multi Layer Parallax', 'happy-elementor-addons' ),
				'icon'      => 'huge huge-layers-02',
				'is_active' => true,
				'is_pro'    => true,
				'is_gsap'   => false,
			],
			'wrapper-link'             => [
				'title'     => __( 'Wrapper Link', 'happy-elementor-addons' ),
				'icon'      => 'hm hm-section-link',
				'is_active' => true,
				'is_pro'    => false,
				'is_gsap'   => false,
				'class'     => 'Wrapper_Link',
				'file'      => 'extensions/atomic/wrapper-link.php',
				'script'    => 'happy-atomic-wrapper-link',
				'prehide'   => false,
			],
			'shape-divider'            => [
				'title'          => __( 'Shape Divider', 'happy-elementor-addons' ),
				'icon'           => 'hm hm-map',
				'is_active'      => true,
				'is_pro'         => false,
				'is_gsap'        => false,
				'class'          => 'Shape_Divider',
				'file'           => 'extensions/atomic/shape-divider.php',
				'script'         => 'happy-atomic-shape-divider',
				'style'          => 'happy-atomic-shape-divider',
				'editor_preview' => true,
				'prehide'        => false,
			],
			'css-transform'            => [
				'title'          => __( 'CSS Transform', 'happy-elementor-addons' ),
				'icon'           => 'hm hm-3d-rotate',
				'is_active'      => true,
				'is_pro'         => false,
				'is_gsap'        => false,
				'class'          => 'CSS_Transform',
				'file'           => 'extensions/atomic/css-transform.php',
				'script'         => 'happy-atomic-css-transform',
				'editor_preview' => true,
				'prehide'        => false,
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
			'global-badge'              => [
				'title'     => __( 'Global Badge', 'happy-elementor-addons' ),
				'icon'      => 'hm hm-global-badge',
				'is_active' => true,
				'is_pro'    => true,
				'is_gsap'   => false,
			],
			'display-condition'         => [
				'title'     => __( 'Display Condition', 'happy-elementor-addons' ),
				'icon'      => 'hm hm-display-condition',
				'is_active' => true,
				'is_pro'    => true,
				'is_gsap'   => false,
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
