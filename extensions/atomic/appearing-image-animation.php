<?php
namespace Happy_Addons\Elementor\Extensions\Atomic;

defined( 'ABSPATH' ) || die();

require_once __DIR__ . '/atomic-feature-base.php';

/**
 * Appearing Image Animation for the Atomic Image (e-image).
 *
 * Settings are single valued: Atomic settings are not per-breakpoint, so the
 * V3 responsive overrides are replaced by the "Enable on Mobile" switch.
 */
class Appearing_Image_Animation extends Atomic_Feature_Base {

	protected function prefix() {
		return 'ha_aia_';
	}

	protected function target_type() {
		return 'e-image';
	}

	protected function feature_key() {
		return 'appearing-image-animation';
	}

	protected function section_label() {
		return __( 'Appearing Animation', 'happy-elementor-addons' );
	}

	protected function matches_schema( array $schema ) {
		return isset( $schema['image'], $schema['link'] ) && ! isset( $schema['title'], $schema['tag'], $schema['text'], $schema['paragraph'] );
	}

	protected function fields() {
		$directions = [
			'left'   => __( 'Left', 'happy-elementor-addons' ),
			'right'  => __( 'Right', 'happy-elementor-addons' ),
			'top'    => __( 'Top', 'happy-elementor-addons' ),
			'bottom' => __( 'Bottom', 'happy-elementor-addons' ),
		];

		return [
			'switcher'                   => [ 'type' => 'bool', 'default' => false, 'label' => __( 'Enable Animation', 'happy-elementor-addons' ) ],
			'mode'                       => [
				'type'    => 'enum',
				'default' => 'reveal',
				'label'   => __( 'Animation Mode', 'happy-elementor-addons' ),
				'options' => [
					'reveal'        => __( 'Reveal', 'happy-elementor-addons' ),
					'tiles-reveal'  => __( 'Tiles Reveal', 'happy-elementor-addons' ),
					'corner-reveal' => __( 'Corner Reveal', 'happy-elementor-addons' ),
					'scale'         => __( 'Scale', 'happy-elementor-addons' ),
					'stretch'       => __( 'Stretch', 'happy-elementor-addons' ),
				],
				'show'    => [ 'switcher' => true ],
			],
			'rs_direction'               => [
				'type'    => 'enum',
				'default' => 'left',
				'label'   => __( 'Direction', 'happy-elementor-addons' ),
				'options' => $directions,
				'icons'   => [
					'left'   => 'ArrowRightIcon',
					'right'  => 'ArrowLeftIcon',
					'top'    => 'ArrowDownIcon',
					'bottom' => 'ArrowUpIcon',
				],
				'show'    => [ 'switcher' => true, 'mode' => [ 'reveal', 'scale' ] ],
			],
			'tiles_count'                => [
				'type' => 'number', 'default' => 5, 'min' => 2, 'max' => 30, 'step' => 1,
				'label' => __( 'Tiles', 'happy-elementor-addons' ),
				'show'  => [ 'switcher' => true, 'mode' => 'tiles-reveal' ],
			],
			'tiles_orientation'          => [
				'type'    => 'enum',
				'default' => 'horizontal',
				'label'   => __( 'Tiles Orientation', 'happy-elementor-addons' ),
				'options' => [
					'horizontal' => __( 'Horizontal', 'happy-elementor-addons' ),
					'vertical'   => __( 'Vertical', 'happy-elementor-addons' ),
				],
				'show'    => [ 'switcher' => true, 'mode' => 'tiles-reveal' ],
			],
			'tiles_horizontal_direction' => [
				'type'    => 'enum',
				'default' => 'bottom-to-top',
				'label'   => __( 'Horizontal Direction', 'happy-elementor-addons' ),
				'options' => [
					'bottom-to-top' => __( 'Bottom to Top', 'happy-elementor-addons' ),
					'top-to-bottom' => __( 'Top to Bottom', 'happy-elementor-addons' ),
				],
				'icons'   => [
					'bottom-to-top' => 'JustifyTopIcon',
					'top-to-bottom' => 'JustifyBottomIcon',
				],
				'show'    => [ 'switcher' => true, 'mode' => 'tiles-reveal', 'tiles_orientation' => 'horizontal' ],
			],
			'tiles_vertical_direction'   => [
				'type'    => 'enum',
				'default' => 'left-to-right',
				'label'   => __( 'Vertical Direction', 'happy-elementor-addons' ),
				'options' => [
					'left-to-right' => __( 'Left to Right', 'happy-elementor-addons' ),
					'right-to-left' => __( 'Right to Left', 'happy-elementor-addons' ),
				],
				'icons'   => [
					'left-to-right' => 'LayoutAlignLeftIcon',
					'right-to-left' => 'LayoutAlignRightIcon',
				],
				'show'    => [ 'switcher' => true, 'mode' => 'tiles-reveal', 'tiles_orientation' => 'vertical' ],
			],
			'tiles_stagger_delay'        => [
				'type' => 'number', 'default' => 0.08, 'min' => 0, 'max' => 2, 'step' => 0.01,
				'label' => __( 'Tiles Stagger (s)', 'happy-elementor-addons' ),
				'show'  => [ 'switcher' => true, 'mode' => 'tiles-reveal' ],
			],
			'corner_direction'           => [
				'type'    => 'enum',
				'default' => 'top-right',
				'label'   => __( 'Corner', 'happy-elementor-addons' ),
				'options' => [
					'top-left'     => __( 'Top Left', 'happy-elementor-addons' ),
					'top-right'    => __( 'Top Right', 'happy-elementor-addons' ),
					'bottom-left'  => __( 'Bottom Left', 'happy-elementor-addons' ),
					'bottom-right' => __( 'Bottom Right', 'happy-elementor-addons' ),
					'center'       => __( 'Center', 'happy-elementor-addons' ),
				],
				'show'    => [ 'switcher' => true, 'mode' => 'corner-reveal' ],
			],
			'scale_from'                 => [
				'type' => 'number', 'default' => 0.5, 'min' => 0, 'max' => 5, 'step' => 0.1,
				'label' => __( 'Scale From', 'happy-elementor-addons' ),
				'show'  => [ 'switcher' => true, 'mode' => 'scale' ],
			],
			'scale_to'                   => [
				'type' => 'number', 'default' => 1, 'min' => 0, 'max' => 5, 'step' => 0.1,
				'label' => __( 'Scale To', 'happy-elementor-addons' ),
				'show'  => [ 'switcher' => true, 'mode' => 'scale' ],
			],
			'trigger_point'              => [
				'type'    => 'enum',
				'default' => 'top-center',
				'label'   => __( 'Trigger Point', 'happy-elementor-addons' ),
				'description' => __( 'First value is the element position, second is the viewport position.', 'happy-elementor-addons' ),
				'options' => self::trigger_points(),
				'show'    => [ 'switcher' => true, 'mode' => [ 'reveal', 'tiles-reveal', 'corner-reveal', 'scale' ] ],
			],
			'custom_trigger_start'       => [
				'type' => 'string', 'default' => 'top 80%',
				'label' => __( 'Custom Trigger (Start)', 'happy-elementor-addons' ),
				'show'  => [ 'switcher' => true, 'trigger_point' => 'custom' ],
			],
			'animation_duration'         => [
				'type' => 'number', 'default' => 1.2, 'min' => 0.1, 'max' => 10, 'step' => 0.1,
				'label' => __( 'Duration (s)', 'happy-elementor-addons' ),
				'show'  => [ 'switcher' => true, 'mode' => [ 'reveal', 'tiles-reveal', 'corner-reveal', 'scale' ] ],
			],
			'delay'                      => [
				'type' => 'number', 'default' => 0, 'min' => 0, 'max' => 10, 'step' => 0.1,
				'label' => __( 'Delay (s)', 'happy-elementor-addons' ),
				'show'  => [ 'switcher' => true, 'mode' => [ 'reveal', 'tiles-reveal', 'corner-reveal', 'scale' ] ],
			],
			'easing_function'            => [
				'type'    => 'enum',
				'default' => 'power4.inOut',
				'label'   => __( 'Easing', 'happy-elementor-addons' ),
				'options' => self::easings(),
				'show'    => [ 'switcher' => true, 'mode' => [ 'reveal', 'tiles-reveal', 'corner-reveal', 'scale' ] ],
			],
			'enable_on_mobile'           => [
				'type' => 'bool', 'default' => false,
				'label' => __( 'Enable on Mobile', 'happy-elementor-addons' ),
				'description' => __( 'When disabled, the animation will not run on mobile devices.', 'happy-elementor-addons' ),
				'show'  => [ 'switcher' => true ],
			],
		];
	}
}
