<?php
namespace Happy_Addons\Elementor\Extensions\Atomic;

use Elementor\Modules\AtomicWidgets\Controls\Section;
use Happy_Addons\Elementor\Classes\Atomic_Tabs_Control;

defined( 'ABSPATH' ) || die();

require_once __DIR__ . '/atomic-feature-base.php';
require_once HAPPY_ADDONS_DIR_PATH . 'classes/atomic-tabs-control.php';

/**
 * CSS Transform for the Atomic containers (e-flexbox, e-div-block, e-grid) and
 * the Atomic Heading, Paragraph, Button, Image, SVG, Divider and YouTube.
 * The V4 counterpart of extensions/css-transform.php.
 *
 * Same settings as Classic, for the Normal and the Hover state: Translate,
 * Rotate and Scale (Compact / Loose mode), Skew, plus the Transition Duration.
 * Pure CSS, like Classic: the rules are printed per element on the frontend; the
 * editor preview builds the same rules in happy-atomic-css-transform.js (keep
 * them in sync).
 *
 * Atomic settings are not per breakpoint, so the Classic responsive values are
 * a single value here.
 */
class CSS_Transform extends Atomic_Feature_Base {

	/** @var array<string,bool> */
	private static $printed = [];

	protected function prefix() {
		return 'ha_tfx_';
	}

	protected function target_type() {
		return self::TYPES;
	}

	protected function target_types() {
		return self::TYPES;
	}

	const TYPES = [ 'e-flexbox', 'e-div-block', 'e-grid', 'e-heading', 'e-paragraph', 'e-button', 'e-image', 'e-svg', 'e-divider', 'e-youtube' ];

	protected function feature_key() {
		return 'css-transform';
	}

	protected function section_label() {
		return __( 'CSS Transform', 'happy-elementor-addons' );
	}

	protected function matches_schema( array $schema ) {
		return isset( $schema['classes'] );
	}

	protected function fields() {
		$fields = [
			'switcher' => [
				'type'    => 'bool',
				'default' => false,
				'label'   => __( 'Enable', 'happy-elementor-addons' ),
			],
		];

		$on = [ 'switcher' => true ];

		foreach ( [ '', '_hover' ] as $suffix ) {
			// Empty (null default) falls back to the Normal value in the Hover state, like Classic.
			$num = function ( $label, $min, $max, $toggle, $extra = [] ) use ( $on ) {
				return [
					'type'    => 'slider',
					'default' => null,
					'min'     => $min,
					'max'     => $max,
					'step'    => 1,
					'label'   => $label,
					'show'    => $on + [ $toggle => true ] + $extra,
				];
			};
			$toggle = function ( $label ) use ( $on ) {
				return [ 'type' => 'bool', 'default' => false, 'label' => $label, 'show' => $on ];
			};
			$mode = function ( $toggle_key ) use ( $on ) {
				return [
					'type'    => 'enum',
					'default' => 'loose',
					'label'   => __( 'Mode', 'happy-elementor-addons' ),
					'options' => [
						'compact' => __( 'Compact', 'happy-elementor-addons' ),
						'loose'   => __( 'Loose', 'happy-elementor-addons' ),
					],
					'icons'   => [ 'compact' => 'CirclePlusIcon', 'loose' => 'CircleMinusIcon' ],
					'show'    => $on + [ $toggle_key => true ],
				];
			};
			$scale = function ( $label, $toggle_key, $extra = [] ) use ( $on ) {
				return [
					'type'    => 'slider',
					'default' => 1,
					'min'     => 0,
					'max'     => 5,
					'step'    => 0.1,
					'label'   => $label,
					'show'    => $on + [ $toggle_key => true ] + $extra,
				];
			};

			$t       = 'translate_toggle' . $suffix;
			$r       = 'rotate_toggle' . $suffix;
			$s       = 'scale_toggle' . $suffix;
			$k       = 'skew_toggle' . $suffix;
			$loose_r = [ 'rotate_mode' . $suffix => 'loose' ];

			$fields[ $t ]                      = $toggle( __( 'Translate', 'happy-elementor-addons' ) );
			$fields[ 'translate_x' . $suffix ] = $num( __( 'Translate X', 'happy-elementor-addons' ), -1000, 1000, $t );
			$fields[ 'translate_y' . $suffix ] = $num( __( 'Translate Y', 'happy-elementor-addons' ), -1000, 1000, $t );
			$fields[ $r ]                      = $toggle( __( 'Rotate', 'happy-elementor-addons' ) );
			$fields[ 'rotate_mode' . $suffix ] = $mode( $r );
			$fields[ 'rotate_x' . $suffix ]    = $num( __( 'Rotate X', 'happy-elementor-addons' ), -180, 180, $r, $loose_r );
			$fields[ 'rotate_y' . $suffix ]    = $num( __( 'Rotate Y', 'happy-elementor-addons' ), -180, 180, $r, $loose_r );
			$fields[ 'rotate_z' . $suffix ]    = $num( __( 'Rotate (Z)', 'happy-elementor-addons' ), -180, 180, $r );
			$fields[ $s ]                      = $toggle( __( 'Scale', 'happy-elementor-addons' ) );
			$fields[ 'scale_mode' . $suffix ]  = $mode( $s );
			$fields[ 'scale_x' . $suffix ]     = $scale( __( 'Scale (X)', 'happy-elementor-addons' ), $s );
			$fields[ 'scale_y' . $suffix ]     = $scale( __( 'Scale Y', 'happy-elementor-addons' ), $s, [ 'scale_mode' . $suffix => 'loose' ] );
			$fields[ $k ]                      = $toggle( __( 'Skew', 'happy-elementor-addons' ) );
			$fields[ 'skew_x' . $suffix ]      = $num( __( 'Skew X', 'happy-elementor-addons' ), -180, 180, $k );
			$fields[ 'skew_y' . $suffix ]      = $num( __( 'Skew Y', 'happy-elementor-addons' ), -180, 180, $k );
		}

		$fields['transition_duration'] = [
			'type'    => 'slider',
			'default' => 0.2,
			'min'     => 0,
			'max'     => 3,
			'step'    => 0.1,
			'label'   => __( 'Transition Duration', 'happy-elementor-addons' ),
			'show'    => $on,
		];

		return $fields;
	}

	/**
	 * Panel: the Enable switch, then Normal / Hover tabs whose Translate, Rotate,
	 * Scale and Skew groups open as popovers (rendered by the "ha-tabs" control).
	 */
	public function add_controls( $controls, $element ) {
		if ( ! is_array( $controls ) || ! is_object( $element ) || ! method_exists( $element, 'get_element_type' ) || ! in_array( $element::get_element_type(), $this->target_types(), true ) ) {
			return $controls;
		}

		$fields = $this->fields();
		$prefix = $this->prefix();
		$build  = function ( $key ) use ( $fields, $prefix ) {
			return $this->control( $prefix . $key, $fields[ $key ] )->jsonSerialize();
		};
		$groups = [
			'translate' => [ 'translate_x', 'translate_y' ],
			'rotate'    => [ 'rotate_mode', 'rotate_x', 'rotate_y', 'rotate_z' ],
			'scale'     => [ 'scale_mode', 'scale_x', 'scale_y' ],
			'skew'      => [ 'skew_x', 'skew_y' ],
		];
		$tabs   = [];

		foreach ( [
			''       => __( 'Normal', 'happy-elementor-addons' ),
			'_hover' => __( 'Hover', 'happy-elementor-addons' ),
		] as $suffix => $tab_label ) {
			$items = [];

			foreach ( $groups as $group => $keys ) {
				$items[] = [
					'kind'     => 'popover',
					'label'    => $fields[ $group . '_toggle' . $suffix ]['label'],
					'toggle'   => $prefix . $group . '_toggle' . $suffix,
					'controls' => array_map( function ( $key ) use ( $build, $suffix ) {
						return $build( $key . $suffix );
					}, $keys ),
				];
			}

			if ( '_hover' === $suffix ) {
				$items[] = [ 'kind' => 'control', 'control' => $build( 'transition_duration' ) ];
			}

			$tabs[] = [ 'label' => $tab_label, 'items' => $items ];
		}

		$controls[] = Section::make()
			// The trailing run of zero-width characters marks a Happy section: the editor script adds the Happy icon to it.
			->set_label( $this->section_label() . "\u{200B}\u{200C}\u{200B}" )
			->set_id( $prefix . 'section' )
			->set_items( [
				$this->control( $prefix . 'switcher', $fields['switcher'] ),
				Atomic_Tabs_Control::bind_to( $prefix . 'switcher' )->set_tabs( $tabs ),
			] );

		return $controls;
	}

	/**
	 * Prints the element's rules when the transform is enabled.
	 */
	public function before_render( $element ) {
		if ( ! is_object( $element ) || ! method_exists( $element, 'get_atomic_settings' ) || ! method_exists( $element, 'get_element_type' ) || ! $this->is_target( $element ) ) {
			return;
		}

		$id = preg_replace( '/[^a-zA-Z0-9_-]/', '', (string) $element->get_id() );

		if ( '' === $id || isset( self::$printed[ $id ] ) ) {
			return;
		}

		$config = $this->sanitize_config( $element->get_atomic_settings() );
		$css    = self::build_css( '[data-id="' . $id . '"]', $config );

		if ( '' === $css ) {
			return;
		}

		self::$printed[ $id ] = true;

		echo '<style id="ha-tfx-' . esc_attr( $id ) . '">' . $css . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- values are whitelisted/clamped in sanitize_config().
	}

	private static function number( $value ) {
		return rtrim( rtrim( number_format( (float) $value, 4, '.', '' ), '0' ), '.' );
	}

	/**
	 * @param string $s      Selector of the element.
	 * @param array  $config Sanitized config (prefix stripped).
	 */
	public static function build_css( $s, array $config ) {
		if ( empty( $config['switcher'] ) ) {
			return '';
		}

		$vars = '';

		foreach ( [ '' => '', '_hover' => '-hover' ] as $suffix => $css_suffix ) {
			$set = function ( $name, $key, $unit ) use ( &$vars, $config, $suffix, $css_suffix ) {
				$value = $config[ $key . $suffix ] ?? '';

				if ( '' !== $value && null !== $value ) {
					$vars .= '--ha-tfx-' . $name . $css_suffix . ':' . self::number( $value ) . $unit . ';';
				}
			};

			if ( ! empty( $config[ 'translate_toggle' . $suffix ] ) ) {
				$set( 'translate-x', 'translate_x', 'px' );
				$set( 'translate-y', 'translate_y', 'px' );
			}

			if ( ! empty( $config[ 'rotate_toggle' . $suffix ] ) ) {
				if ( 'loose' === $config[ 'rotate_mode' . $suffix ] ) {
					$set( 'rotate-x', 'rotate_x', 'deg' );
					$set( 'rotate-y', 'rotate_y', 'deg' );
				}

				$set( 'rotate-z', 'rotate_z', 'deg' );
			}

			if ( ! empty( $config[ 'scale_toggle' . $suffix ] ) ) {
				// Compact scales both axes with the X value.
				$x = self::number( $config[ 'scale_x' . $suffix ] );

				$vars .= '--ha-tfx-scale-x' . $css_suffix . ':' . $x . ';--ha-tfx-scale-y' . $css_suffix . ':' . $x . ';';

				if ( 'loose' === $config[ 'scale_mode' . $suffix ] ) {
					$vars .= '--ha-tfx-scale-y' . $css_suffix . ':' . self::number( $config[ 'scale_y' . $suffix ] ) . ';';
				}
			}

			if ( ! empty( $config[ 'skew_toggle' . $suffix ] ) ) {
				$set( 'skew-x', 'skew_x', 'deg' );
				$set( 'skew-y', 'skew_y', 'deg' );
			}
		}

		$vars .= '--ha-tfx-transition-duration:' . self::number( $config['transition_duration'] ) . 's;';

		$transform = static function ( $h ) {
			$v = static function ( $name, $default ) use ( $h ) {
				return 'var(--ha-tfx-' . $name . $h . ',var(--ha-tfx-' . $name . ',' . $default . '))';
			};

			if ( '' === $h ) {
				$v = static function ( $name, $default ) {
					return 'var(--ha-tfx-' . $name . ',' . $default . ')';
				};
			}

			return 'translate(' . $v( 'translate-x', 0 ) . ',' . $v( 'translate-y', 0 ) . ') scale(' . $v( 'scale-x', 1 ) . ',' . $v( 'scale-y', 1 ) . ') skew(' . $v( 'skew-x', 0 ) . ',' . $v( 'skew-y', 0 ) . ') rotateX(' . $v( 'rotate-x', 0 ) . ') rotateY(' . $v( 'rotate-y', 0 ) . ') rotateZ(' . $v( 'rotate-z', 0 ) . ')';
		};

		return $s . '{' . $vars . 'transform:' . $transform( '' ) . ';transition-property:transform;transition-duration:var(--ha-tfx-transition-duration,.2s);}'
			. $s . ':hover{transform:' . $transform( '-hover' ) . ';}';
	}
}
