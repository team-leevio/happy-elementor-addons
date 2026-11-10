<?php
namespace Happy_Addons\Elementor\Extensions\Atomic;

defined( 'ABSPATH' ) || die();

require_once __DIR__ . '/atomic-feature-base.php';

/**
 * Foreground Overlay for the Atomic Flexbox (e-flexbox), Div Block
 * (e-div-block) and Grid (e-grid). The V4 counterpart of
 * extensions/foreground-overlay.php.
 *
 * Pure CSS: the overlay is the element's ::after layer (classic color or the
 * 3 area gradient driven by the same --fg-* variables as the V3 container).
 * The CSS is printed per element on the frontend; the editor preview builds
 * the same rules in happy-atomic-foreground-overlay.js (keep them in sync).
 */
class Foreground_Overlay extends Atomic_Feature_Base {

	/** @var array<string,bool> */
	private static $printed = [];

	protected function prefix() {
		return 'ha_fo_';
	}

	protected function target_type() {
		return [ 'e-flexbox', 'e-div-block', 'e-grid' ];
	}

	protected function feature_key() {
		return 'foreground-overlay';
	}

	protected function section_label() {
		return __( 'Foreground Overlay', 'happy-elementor-addons' );
	}

	protected function matches_schema( array $schema ) {
		return isset( $schema['classes'], $schema['tag'], $schema['link'], $schema['attributes'] )
			&& ! isset( $schema['title'], $schema['image'], $schema['text'], $schema['paragraph'] );
	}

	/**
	 * No switch: the feature is on while a foreground type is chosen, so the panel indicator follows that.
	 */
	public function editor_section() {
		return [
			'label'    => $this->section_label(),
			'switcher' => [ $this->prefix() . 'type' ],
		];
	}

	protected function fields() {
		$classic  = [ 'type' => 'classic' ];
		$gradient = [ 'type' => 'gradient' ];
		$any      = [ 'type' => [ 'classic', 'gradient' ] ];

		$num = function ( $default, $label, $min, $max, $step, $show ) {
			return [
				'type' => 'number', 'default' => $default, 'min' => $min, 'max' => $max, 'step' => $step,
				'label' => $label,
				'show'  => $show,
			];
		};

		$slider = function ( $default, $label, $min, $max, $step, $show ) {
			return [
				'type' => 'slider', 'default' => $default, 'min' => $min, 'max' => $max, 'step' => $step,
				'label' => $label,
				'show'  => $show,
			];
		};

		$fields = [
			'type' => [
				'type'    => 'enum',
				'default' => 'none',
				'label'   => __( 'Foreground Type', 'happy-elementor-addons' ),
				'options' => [
					'none'     => __( 'None', 'happy-elementor-addons' ),
					'classic'  => __( 'Classic', 'happy-elementor-addons' ),
					'gradient' => __( 'Gradient', 'happy-elementor-addons' ),
				],
			],
			'color'   => [
				'type'    => 'color',
				'default' => '',
				'label'   => __( 'Color', 'happy-elementor-addons' ),
				'show'    => $classic,
			],
			'opacity' => $slider( 0.5, __( 'Opacity', 'happy-elementor-addons' ), 0, 1, 0.01, $classic ),
		];

		$areas = [
			'start'  => [ __( 'Start', 'happy-elementor-addons' ), '#ffffff', 0, 5, 1 ],
			'center' => [ __( 'Center', 'happy-elementor-addons' ), '#ffffff', 5, 95, 0 ],
			'end'    => [ __( 'End', 'happy-elementor-addons' ), '#ffffff', 95, 100, 1 ],
		];

		foreach ( $areas as $key => $area ) {
			$fields[ $key . '_color' ] = [
				'type'    => 'color',
				'default' => $area[1],
				/* translators: %s: Start, Center or End */
				'label'   => sprintf( __( '%s Color', 'happy-elementor-addons' ), $area[0] ),
				'show'    => $gradient,
			];
			/* translators: %s: Start, Center or End */
			$fields[ $key . '_from' ]    = $num( $area[2], sprintf( __( '%s Area From (%%)', 'happy-elementor-addons' ), $area[0] ), 0, 100, 1, $gradient );
			/* translators: %s: Start, Center or End */
			$fields[ $key . '_to' ]      = $num( $area[3], sprintf( __( '%s Area To (%%)', 'happy-elementor-addons' ), $area[0] ), 0, 100, 1, $gradient );
			/* translators: %s: Start, Center or End */
			$fields[ $key . '_opacity' ] = $slider( $area[4], sprintf( __( '%s Opacity', 'happy-elementor-addons' ), $area[0] ), 0, 1, 0.01, $gradient );
		}

		$fields['blend'] = $slider( 20, __( 'Blend Width (%)', 'happy-elementor-addons' ), 0, 50, 1, $gradient );
		$fields['blend']['description'] = __( '5% = subtle, 10% = normal, 20% = very smooth, 30% = very wide', 'happy-elementor-addons' );
		$fields['angle'] = $slider( 90, __( 'Angle (deg)', 'happy-elementor-addons' ), 0, 360, 1, $gradient );

		$fields['blur']       = $num( 0, __( 'Blur (px)', 'happy-elementor-addons' ), 0, 100, 0.1, $any );
		$fields['brightness'] = $num( 100, __( 'Brightness (%)', 'happy-elementor-addons' ), 0, 200, 1, $any );
		$fields['contrast']   = $num( 100, __( 'Contrast (%)', 'happy-elementor-addons' ), 0, 200, 1, $any );
		$fields['saturate']   = $num( 100, __( 'Saturation (%)', 'happy-elementor-addons' ), 0, 200, 1, $any );
		$fields['hue']        = $num( 0, __( 'Hue (deg)', 'happy-elementor-addons' ), 0, 360, 1, $any );

		$fields['blend_mode'] = [
			'type'    => 'enum',
			'default' => 'normal',
			'label'   => __( 'Blend Mode', 'happy-elementor-addons' ),
			'options' => [
				'normal'      => __( 'Normal', 'happy-elementor-addons' ),
				'multiply'    => 'Multiply',
				'screen'      => 'Screen',
				'overlay'     => 'Overlay',
				'darken'      => 'Darken',
				'lighten'     => 'Lighten',
				'color-dodge' => 'Color Dodge',
				'saturation'  => 'Saturation',
				'difference'  => 'Difference',
				'exclusion'   => 'Exclusion',
				'hue'         => 'Hue',
				'color'       => 'Color',
				'luminosity'  => 'Luminosity',
			],
			'show'    => $any,
		];

		return $fields;
	}

	/**
	 * Prints the element's rules; nothing when no foreground type is set.
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

		echo '<style id="ha-fo-' . esc_attr( $id ) . '">' . $css . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- values are whitelisted/clamped in sanitize_config().
	}

	private static function color_mix( $position, $opacity ) {
		return sprintf(
			'color-mix(in srgb, var(--fg-%1$s-color, #ffffff) calc(var(--fg-%1$s-opacity, %2$s) * 100%%), transparent)',
			$position,
			$opacity
		);
	}

	private static function gradient() {
		return sprintf(
			'linear-gradient( var(--fg-angle, 90deg), ' .
				'%1$s 0%%, ' .
				'%1$s clamp(0%%, (var(--fg-left-end, 5%%) + var(--fg-center-start, 5%%) - var(--fg-blend, 20%%)) / 2, 100%%), ' .
				'%2$s clamp(0%%, (var(--fg-left-end, 5%%) + var(--fg-center-start, 5%%) + var(--fg-blend, 20%%)) / 2, 100%%), ' .
				'%2$s clamp(0%%, (var(--fg-center-end, 95%%) + var(--fg-right-start, 95%%) - var(--fg-blend, 20%%)) / 2, 100%%), ' .
				'%3$s clamp(0%%, (var(--fg-center-end, 95%%) + var(--fg-right-start, 95%%) + var(--fg-blend, 20%%)) / 2, 100%%), ' .
				'%3$s 100%% )',
			self::color_mix( 'left', 1 ),
			self::color_mix( 'center', 0 ),
			self::color_mix( 'right', 1 )
		);
	}

	/**
	 * @param string $s      Selector of the element.
	 * @param array  $config Sanitized config (prefix stripped).
	 */
	public static function build_css( $s, array $config ) {
		if ( ! in_array( $config['type'], [ 'classic', 'gradient' ], true ) ) {
			return '';
		}

		$css  = ':where(' . $s . '){position:relative;}';
		$css .= $s . '{isolation:isolate;overflow:hidden;}';
		$css .= $s . '::after{content:"";position:absolute;inset:0;z-index:9999;pointer-events:none;display:block;border-radius:inherit;';

		if ( 'classic' === $config['type'] ) {
			if ( '' !== $config['color'] ) {
				$css .= 'background:' . $config['color'] . ';';
			}

			$css .= 'opacity:' . $config['opacity'] . ';';
		} else {
			$css .= 'background:' . self::gradient() . ';';
		}

		$filters = [];

		foreach ( [ 'blur' => [ 0, 'px' ], 'brightness' => [ 100, '%' ], 'contrast' => [ 100, '%' ], 'saturate' => [ 100, '%' ], 'hue' => [ 0, 'deg' ] ] as $key => $filter ) {
			if ( (float) $filter[0] !== (float) $config[ $key ] ) {
				$filters[] = ( 'hue' === $key ? 'hue-rotate' : $key ) . '(' . $config[ $key ] . $filter[1] . ')';
			}
		}

		if ( $filters ) {
			$css .= 'filter:' . implode( ' ', $filters ) . ';';
		}

		if ( 'normal' !== $config['blend_mode'] ) {
			$css .= 'mix-blend-mode:' . $config['blend_mode'] . ';';
		}

		$css .= '}';

		if ( 'gradient' === $config['type'] ) {
			$vars = '';

			foreach ( [ 'left' => 'start', 'center' => 'center', 'right' => 'end' ] as $position => $key ) {
				if ( '' !== $config[ $key . '_color' ] ) {
					$vars .= '--fg-' . $position . '-color:' . $config[ $key . '_color' ] . ';';
				}

				$vars .= '--fg-' . $position . '-opacity:' . $config[ $key . '_opacity' ] . ';';
				$vars .= '--fg-' . $position . '-start:' . $config[ $key . '_from' ] . '%;';
				$vars .= '--fg-' . $position . '-end:' . $config[ $key . '_to' ] . '%;';
			}

			$vars .= '--fg-blend:' . $config['blend'] . '%;--fg-angle:' . $config['angle'] . 'deg;';

			$css .= $s . '{' . $vars . '}';
		}

		return $css;
	}
}
