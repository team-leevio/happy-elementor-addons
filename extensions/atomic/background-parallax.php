<?php
namespace Happy_Addons\Elementor\Extensions\Atomic;

defined( 'ABSPATH' ) || die();

require_once __DIR__ . '/atomic-feature-base.php';

/**
 * Background Parallax for the Atomic Flexbox (e-flexbox), Div Block (e-div-block) and Grid (e-grid)
 * The V4 counterpart of extensions/background-parallax.php.
 *
 * Same settings as Classic: Type, Direction and Speed (Auto Moving), Speed,
 * Enable on Android / iOS. The effect (Jarallax, or the auto moving background)
 * is run by happy-atomic-background-parallax.js, which gets the sanitized config
 * (frontend) or reads the editor settings (editor preview), so the Classic
 * "Apply" button is not needed.
 */
class Background_Parallax extends Atomic_Feature_Base {

	protected function prefix() {
		return 'ha_bgp_';
	}

	protected function target_type() {
		return [ 'e-flexbox', 'e-div-block', 'e-grid' ];
	}

	protected function target_types() {
		return [ 'e-flexbox', 'e-div-block', 'e-grid' ];
	}

	protected function feature_key() {
		return 'background-parallax';
	}

	protected function section_label() {
		return __( 'Background Parallax', 'happy-elementor-addons' );
	}

	protected function matches_schema( array $schema ) {
		return isset( $schema['classes'], $schema['tag'], $schema['link'], $schema['attributes'] )
			&& ! isset( $schema['title'], $schema['image'], $schema['text'], $schema['paragraph'] );
	}

	protected function fields() {
		$on     = [ 'switcher' => true ];
		$moving = $on + [ 'type' => 'automove' ];
		$scroll = $on + [ 'type' => [ 'scroll', 'scroll-opacity', 'opacity', 'scale', 'scale-opacity' ] ];

		return [
			'switcher'            => [ 'type' => 'bool', 'default' => false, 'label' => __( 'Enable BG Parallax', 'happy-elementor-addons' ) ],
			'type'                => [
				'type'    => 'enum',
				'default' => 'scroll',
				'label'   => __( 'Type', 'happy-elementor-addons' ),
				'options' => [
					'scroll'         => __( 'Scroll', 'happy-elementor-addons' ),
					'scroll-opacity' => __( 'Scroll with opacity', 'happy-elementor-addons' ),
					'opacity'        => __( 'Opacity', 'happy-elementor-addons' ),
					'scale'          => __( 'Scale', 'happy-elementor-addons' ),
					'scale-opacity'  => __( 'Scale with opacity', 'happy-elementor-addons' ),
					'automove'       => __( 'Auto Moving', 'happy-elementor-addons' ),
				],
				'show'    => $on,
			],
			'automove_direction'  => [
				'type'    => 'enum',
				'default' => 'left',
				'label'   => __( 'Direction', 'happy-elementor-addons' ),
				'options' => [
					'left'   => __( 'Left to Right', 'happy-elementor-addons' ),
					'right'  => __( 'Right to Left', 'happy-elementor-addons' ),
					'top'    => __( 'Top to Bottom', 'happy-elementor-addons' ),
					'bottom' => __( 'Bottom to Top', 'happy-elementor-addons' ),
				],
				'show'    => $moving,
			],
			'speed'               => [
				'type'    => 'slider',
				'default' => 0.5,
				'min'     => -1,
				'max'     => 2,
				'step'    => 0.1,
				'label'   => __( 'Speed', 'happy-elementor-addons' ),
				'show'    => $scroll,
			],
			'automove_speed'      => [
				'type'        => 'number',
				'default'     => 3,
				'min'         => 0,
				'max'         => 150,
				'step'        => 1,
				'label'       => __( 'Speed', 'happy-elementor-addons' ),
				'description' => __( 'Set the speed of background movement, default: 3', 'happy-elementor-addons' ),
				'show'        => $moving,
			],
			'enable_on_android'   => [ 'type' => 'bool', 'default' => false, 'label' => __( 'Enable on Android', 'happy-elementor-addons' ), 'show' => $scroll ],
			'enable_on_ios'       => [ 'type' => 'bool', 'default' => false, 'label' => __( 'Enable on iOS', 'happy-elementor-addons' ), 'show' => $scroll ],
		];
	}
}
