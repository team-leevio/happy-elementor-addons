<?php
namespace Happy_Addons\Elementor\Extensions\Atomic;

defined( 'ABSPATH' ) || die();

require_once __DIR__ . '/atomic-feature-base.php';

/**
 * Floating Effects for the Atomic widgets (heading, image, button, ...), not
 * the containers (Flexbox, Div Block, Grid). The V4 counterpart of
 * extensions/floating-effects.php.
 *
 * The effect is an anime.js loop on the widget's own element (Atomic widgets sit
 * in a `display: contents` wrapper), run by
 * happy-atomic-floating-effects.js. The sanitized config is handed over through
 * Atomic_Manager::register_frontend_config(); the editor preview builds the same
 * config from the editor settings (keep both in sync).
 *
 * The V3 "from / to" range sliders become a pair of number fields.
 */
class Floating_Effects extends Atomic_Feature_Base {

	protected function prefix() {
		return 'ha_ffx_';
	}

	/** Not a fixed list: see targets_element(). */
	protected function target_type() {
		return [];
	}

	/** Atomic widgets only, like the V3 extension: the containers are left out. */
	protected function targets_element( $element ) {
		return $element instanceof \Elementor\Modules\AtomicWidgets\Elements\Base\Atomic_Widget_Base;
	}

	protected function is_target( $element ) {
		return $this->targets_element( $element );
	}

	protected function feature_key() {
		return 'floating-effects';
	}

	protected function section_label() {
		return __( 'Floating Effects', 'happy-elementor-addons' );
	}

	protected function matches_schema( array $schema ) {
		// Every Atomic element has the Classes prop.
		return isset( $schema['classes'] );
	}

	protected function fields() {
		$fields = [
			'switcher' => [
				'type'    => 'bool',
				'default' => false,
				'label'   => __( 'Enable Floating Effects?', 'happy-elementor-addons' ),
			],
		];

		$groups = [
			'translate' => [
				'label' => __( 'Translate', 'happy-elementor-addons' ),
				'axes'  => [ 'x' => [ 0, 5 ], 'y' => [ 0, 5 ] ],
				'min'   => -100,
				'max'   => 100,
				'step'  => 1,
				'unit'  => 'px',
			],
			'rotate'    => [
				'label' => __( 'Rotate', 'happy-elementor-addons' ),
				'axes'  => [ 'x' => [ 0, 45 ], 'y' => [ 0, 45 ], 'z' => [ 0, 45 ] ],
				'min'   => -180,
				'max'   => 180,
				'step'  => 1,
				'unit'  => 'deg',
			],
			'scale'     => [
				'label' => __( 'Scale', 'happy-elementor-addons' ),
				'axes'  => [ 'x' => [ 1, 1.2 ], 'y' => [ 1, 1.2 ] ],
				'min'   => 0,
				'max'   => 5,
				'step'  => 0.1,
				'unit'  => '',
			],
		];

		foreach ( $groups as $group => $spec ) {
			$fields[ $group ] = [
				'type'    => 'bool',
				'default' => false,
				'label'   => $spec['label'],
				'show'    => [ 'switcher' => true ],
			];

			$show = [ 'switcher' => true, $group => true ];

			foreach ( $spec['axes'] as $axis => $range ) {
				$name = strtoupper( $axis );
				$unit = '' !== $spec['unit'] ? ' (' . $spec['unit'] . ')' : '';

				foreach ( [ 'from' => $range[0], 'to' => $range[1] ] as $edge => $default ) {
					$edge_label = 'from' === $edge ? __( 'From', 'happy-elementor-addons' ) : __( 'To', 'happy-elementor-addons' );

					$fields[ $group . '_' . $axis . '_' . $edge ] = [
						'type'    => 'number',
						'default' => $default,
						'min'     => $spec['min'],
						'max'     => $spec['max'],
						'step'    => $spec['step'],
						/* translators: 1: effect (Translate), 2: axis (X), 3: From/To, 4: unit */
						'label'   => sprintf( __( '%1$s %2$s %3$s%4$s', 'happy-elementor-addons' ), $spec['label'], $name, $edge_label, $unit ),
						'show'    => $show,
					];
				}
			}

			$fields[ $group . '_duration' ] = [
				'type'    => 'number',
				'default' => 1000,
				'min'     => 0,
				'max'     => 10000,
				'step'    => 100,
				'label'   => sprintf( __( '%s Duration (ms)', 'happy-elementor-addons' ), $spec['label'] ),
				'show'    => $show,
			];

			$fields[ $group . '_delay' ] = [
				'type'    => 'number',
				'default' => 0,
				'min'     => 0,
				'max'     => 5000,
				'step'    => 100,
				'label'   => sprintf( __( '%s Delay (ms)', 'happy-elementor-addons' ), $spec['label'] ),
				'show'    => $show,
			];
		}

		return $fields;
	}
}
