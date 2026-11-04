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
			'happy-particle-effects'  => [
				'title'     => __( 'Happy Particle Effects', 'happy-elementor-addons' ),
				'icon'      => 'hm hm-spark',
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
