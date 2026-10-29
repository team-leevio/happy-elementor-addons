<?php
namespace Happy_Addons\Elementor\Extensions\Atomic;

defined( 'ABSPATH' ) || die();

require_once __DIR__ . '/atomic-feature-base.php';

/**
 * Liquid Glass for every Atomic element: the containers (Flexbox, Div Block,
 * Grid) and the Atomic widgets (heading, image, button, ...). The V4
 * counterpart of extensions/liquid-glass.php.
 *
 * Pure CSS + an inline SVG filter: a backdrop-filter (blur + the preset's SVG
 * displacement filter), the inset shadow, border and radius are printed per
 * element on the frontend. The editor preview builds the same rules in
 * happy-atomic-liquid-glass.js from the preset data printed by
 * print_editor_presets() (keep both in sync).
 */
class Liquid_Glass extends Atomic_Feature_Base {

	/** @var array<string,bool> */
	private static $printed = [];

	/** @var array<string,bool> */
	private static $printed_svg = [];

	public function init() {
		parent::init();

		add_action( 'elementor/preview/enqueue_scripts', [ $this, 'print_editor_presets' ], 20 );
	}

	protected function prefix() {
		return 'ha_lg_';
	}

	/** Not a fixed list: see targets_element(). */
	protected function target_type() {
		return [];
	}

	protected function targets_element( $element ) {
		return $element instanceof \Elementor\Modules\AtomicWidgets\Elements\Base\Atomic_Widget_Base
			|| $element instanceof \Elementor\Modules\AtomicWidgets\Elements\Base\Atomic_Element_Base;
	}

	protected function is_target( $element ) {
		return $this->targets_element( $element );
	}

	protected function feature_key() {
		return 'liquid-glass';
	}

	protected function section_label() {
		return __( 'Liquid Glass', 'happy-elementor-addons' );
	}

	protected function matches_schema( array $schema ) {
		// Every Atomic element has the Classes prop.
		return isset( $schema['classes'] );
	}

	/**
	 * name => [ label, default blur (px), frequency, scale ] for the generic
	 * displacement filter, or [ label, default blur, markup ] for the
	 * hand-written ones.
	 */
	private static function presets() {
		$generic = function ( $label, $blur, $frequency, $scale ) {
			return [ 'label' => $label, 'blur' => $blur, 'frequency' => $frequency, 'scale' => $scale ];
		};

		$markup = function ( $label, $blur, $inner ) {
			return [ 'label' => $label, 'blur' => $blur, 'markup' => $inner ];
		};

		return [
			'abyss-glass'       => $generic( __( 'Abyss Glass', 'happy-elementor-addons' ), 8, '0.003 0.005', 135 ),
			'aqua-warp'         => $generic( __( 'Aqua Warp', 'happy-elementor-addons' ), 10, '0.009 0.018', 150 ),
			'bubble-glass'      => $markup( __( 'Bubble Glass', 'happy-elementor-addons' ), 12,
				'<feTurbulence type="fractalNoise" baseFrequency="0.002" numOctaves="1" seed="15" result="noise"></feTurbulence>' .
				'<feGaussianBlur in="noise" stdDeviation="4" result="blur"></feGaussianBlur>' .
				'<feDisplacementMap in="SourceGraphic" in2="blur" scale="250"></feDisplacementMap>' ),
			'bubble-lens'       => $generic( __( 'Bubble Lens', 'happy-elementor-addons' ), 14, '0.002 0.002', 280 ),
			'cascade-ripple'    => $generic( __( 'Cascade Ripple', 'happy-elementor-addons' ), 18, '0.002 0.009', 148 ),
			'diamond-dust'      => $generic( __( 'Diamond Dust', 'happy-elementor-addons' ), 4, '0.05 0.05', 12 ),
			'drop-water'        => $markup( __( 'Drop Water', 'happy-elementor-addons' ), 8,
				'<feTurbulence type="fractalNoise" baseFrequency="0.003 0.007" numOctaves="1" result="turbulence"></feTurbulence>' .
				'<feDisplacementMap in="SourceGraphic" in2="turbulence" scale="200" xChannelSelector="R" yChannelSelector="G"></feDisplacementMap>' ),
			'distorted-prism'   => $markup( __( 'Distorted Prism', 'happy-elementor-addons' ), 8,
				'<feTurbulence type="fractalNoise" baseFrequency="0.03" numOctaves="2" seed="13" result="noise"></feTurbulence>' .
				'<feDisplacementMap in="SourceGraphic" in2="noise" scale="40"></feDisplacementMap>' .
				'<feColorMatrix type="saturate" values="2"></feColorMatrix>' ),
			'fine-crystal'      => $generic( __( 'Fine Crystal', 'happy-elementor-addons' ), 4, '0.03 0.03', 25 ),
			'frozen-glass'      => $generic( __( 'Frozen Glass', 'happy-elementor-addons' ), 10, '0.015 0.008', 135 ),
			'gentle-wave'       => $generic( __( 'Gentle Wave', 'happy-elementor-addons' ), 6, '0.018 0.022', 75 ),
			'glass-fiber'       => $generic( __( 'Glass Fiber', 'happy-elementor-addons' ), 6, '0.01 0.04', 70 ),
			'holographic-glass' => $markup( __( 'Holographic Glass', 'happy-elementor-addons' ), 10,
				'<feTurbulence type="fractalNoise" baseFrequency="0.012" numOctaves="3" seed="11" result="noise"></feTurbulence>' .
				'<feDisplacementMap in="SourceGraphic" in2="noise" scale="30"></feDisplacementMap>' .
				'<feColorMatrix type="saturate" values="1.8"></feColorMatrix>' ),
			'ice-crystal'       => $markup( __( 'Ice Crystal', 'happy-elementor-addons' ), 6,
				'<feTurbulence type="fractalNoise" baseFrequency="0.035" numOctaves="6" seed="9" result="noise"></feTurbulence>' .
				'<feDisplacementMap in="SourceGraphic" in2="noise" scale="18"></feDisplacementMap>' .
				'<feGaussianBlur stdDeviation="0.2"></feGaussianBlur>' ),
			'jelly-blob'        => $generic( __( 'Jelly Blob', 'happy-elementor-addons' ), 14, '0.002 0.006', 240 ),
			'jelly-surface'     => $markup( __( 'Jelly Surface', 'happy-elementor-addons' ), 10,
				'<feTurbulence type="fractalNoise" baseFrequency="0.003" numOctaves="2" seed="4" result="noise"></feTurbulence>' .
				'<feGaussianBlur in="noise" stdDeviation="5" result="goo"></feGaussianBlur>' .
				'<feDisplacementMap in="SourceGraphic" in2="goo" scale="180"></feDisplacementMap>' ),
			'lateral-surge'     => $generic( __( 'Lateral Surge', 'happy-elementor-addons' ), 16, '0.009 0.002', 125 ),
			'lava-flow'         => $generic( __( 'Lava Flow', 'happy-elementor-addons' ), 20, '0.004 0.018', 170 ),
			'liquid-lens'       => $generic( __( 'Liquid Lens', 'happy-elementor-addons' ), 12, '0.0018 0.0018', 220 ),
			'macos-glass'       => $markup( __( 'macOS Glass', 'happy-elementor-addons' ), 14,
				'<feTurbulence type="fractalNoise" baseFrequency="0.004" numOctaves="2" seed="10" result="noise"></feTurbulence>' .
				'<feGaussianBlur in="noise" stdDeviation="0.6" result="blur"></feGaussianBlur>' .
				'<feDisplacementMap in="SourceGraphic" in2="blur" scale="12"></feDisplacementMap>' .
				'<feGaussianBlur stdDeviation="0.3"></feGaussianBlur>' ),
			'magnifier-lens'    => $generic( __( 'Magnifier Lens', 'happy-elementor-addons' ), 10, '0.001 0.001', 220 ),
			'melted-glass'      => $markup( __( 'Melted Glass', 'happy-elementor-addons' ), 8,
				'<feGaussianBlur in="SourceGraphic" stdDeviation="4" result="blur"></feGaussianBlur>' .
				'<feColorMatrix in="blur" type="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 25 -12"></feColorMatrix>' ),
			'micro-frost'       => $generic( __( 'Micro Frost', 'happy-elementor-addons' ), 6, '0.025 0.025', 45 ),
			'ocean-current'     => $generic( __( 'Ocean Current', 'happy-elementor-addons' ), 10, '0.018 0.009', 150 ),
			'prism-cut'         => $generic( __( 'Prism Cut', 'happy-elementor-addons' ), 6, '0.04 0.01', 70 ),
			'rain-streak'       => $generic( __( 'Rain Streak', 'happy-elementor-addons' ), 12, '0.002 0.012', 180 ),
			'tempest-glass'     => $generic( __( 'Tempest Glass', 'happy-elementor-addons' ), 14, '0.014 0.022', 130 ),
			'thai-frost'        => $markup( __( 'Thai Frost', 'happy-elementor-addons' ), 8,
				'<feTurbulence type="fractalNoise" baseFrequency="0.3 0.3" numOctaves="2" seed="92" result="noise"></feTurbulence>' .
				'<feGaussianBlur in="noise" stdDeviation="0.02" result="blur"></feGaussianBlur>' .
				'<feDisplacementMap in="SourceGraphic" in2="blur" scale="50" xChannelSelector="R" yChannelSelector="G"></feDisplacementMap>' ),
			'thai-frost-2'      => $markup( __( 'Thai Frost 2', 'happy-elementor-addons' ), 8,
				'<feTurbulence type="fractalNoise" baseFrequency="1.012 1.012" numOctaves="1" seed="9000" result="noise"></feTurbulence>' .
				'<feGaussianBlur in="noise" stdDeviation="0.1" result="blurred"></feGaussianBlur>' .
				'<feDisplacementMap in="SourceGraphic" in2="blurred" scale="77" xChannelSelector="R" yChannelSelector="G"></feDisplacementMap>' ),
			'turbulent-warp'    => $generic( __( 'Turbulent Warp', 'happy-elementor-addons' ), 24, '0.018 0.012', 195 ),
			'ultra-lens'        => $generic( __( 'Ultra Lens', 'happy-elementor-addons' ), 16, '0.0015 0.0015', 320 ),
			'viscous-gel'       => $generic( __( 'Viscous Gel', 'happy-elementor-addons' ), 14, '0.006 0.002', 240 ),
			'wavy-water'        => $markup( __( 'Wavy Water', 'happy-elementor-addons' ), 8,
				'<feTurbulence type="turbulence" baseFrequency="0.008" numOctaves="4" seed="18" result="noise"></feTurbulence>' .
				'<feDisplacementMap in="SourceGraphic" in2="noise" scale="50"></feDisplacementMap>' ),
		];
	}

	/** Inset glow of the "Shadow" select; same values as the V3 selectors_dictionary. */
	private static function shadows() {
		return [
			'none'         => '',
			'soft-glow'    => '0 0 15px 0 rgba(255, 255, 255, 0.6) inset',
			'warm-glow'    => '0 0 20px 0 rgba(255, 255, 255, 0.65) inset',
			'bright-glow'  => '0 0 15px 0 rgba(255, 255, 255, 0.7) inset',
			'wide-bloom'   => '0 0 30px 1px rgba(255, 255, 255, 0.7) inset',
			'deep-bloom'   => '0 0 40px 5px rgba(255, 255, 255, 0.6) inset',
			'top-light'    => '0 20px 15px -5px rgba(255, 255, 255, 0.5) inset',
			'bottom-light' => '0 -20px 25px -15px rgba(255, 255, 255, 0.5) inset',
			'top-fade'     => '0 10px 25px -10px rgba(255, 255, 255, 0.4) inset',
			'bottom-fade'  => '0 -10px 20px -5px rgba(255, 255, 255, 0.55) inset',
			'top-edge'     => '0 15px 15px -10px rgba(255, 255, 255, 0.45) inset',
			'rim-light'    => '0 0 10px 0 rgba(255, 255, 255, 0.8) inset, 0 0 2px 0 rgba(255, 255, 255, 0.9) inset',
			'inner-shadow' => 'inset 0 2px 8px 0 rgba(0, 0, 0, 0.15), inset 0 0 12px 0 rgba(255, 255, 255, 0.3)',
			'spotlight'    => '0 -5px 30px -10px rgba(255, 255, 255, 0.7) inset',
			'frost-edge'   => '0 0 6px 0 rgba(255, 255, 255, 0.9) inset, 0 0 20px -5px rgba(200, 220, 255, 0.4) inset',
			'vignette'     => 'inset 0 0 50px 10px rgba(0, 0, 0, 0.08)',
			'customs'      => '',
		];
	}

	protected function fields() {
		$on     = [ 'switcher' => true ];
		$custom = [ 'switcher' => true, 'preset' => 'custom' ];

		$num = function ( $default, $label, $min, $max, $step, $show, $description = '' ) {
			return [
				'type' => 'number', 'default' => $default, 'min' => $min, 'max' => $max, 'step' => $step,
				'label' => $label,
				'description' => $description,
				'show'  => $show,
			];
		};

		$presets = [];

		foreach ( self::presets() as $name => $preset ) {
			$presets[ $name ] = $preset['label'];
		}

		$presets['custom'] = __( 'Custom', 'happy-elementor-addons' );

		$channels = [
			'R' => __( 'Red', 'happy-elementor-addons' ),
			'G' => __( 'Green', 'happy-elementor-addons' ),
			'B' => __( 'Blue', 'happy-elementor-addons' ),
			'A' => __( 'Alpha', 'happy-elementor-addons' ),
		];

		$shadows = [
			'none'         => __( 'None', 'happy-elementor-addons' ),
			'soft-glow'    => __( 'Soft Glow', 'happy-elementor-addons' ),
			'warm-glow'    => __( 'Warm Glow', 'happy-elementor-addons' ),
			'bright-glow'  => __( 'Bright Glow', 'happy-elementor-addons' ),
			'wide-bloom'   => __( 'Wide Bloom', 'happy-elementor-addons' ),
			'deep-bloom'   => __( 'Deep Bloom', 'happy-elementor-addons' ),
			'top-light'    => __( 'Top Light', 'happy-elementor-addons' ),
			'bottom-light' => __( 'Bottom Light', 'happy-elementor-addons' ),
			'top-fade'     => __( 'Top Fade', 'happy-elementor-addons' ),
			'bottom-fade'  => __( 'Bottom Fade', 'happy-elementor-addons' ),
			'top-edge'     => __( 'Top Edge', 'happy-elementor-addons' ),
			'rim-light'    => __( 'Rim Light', 'happy-elementor-addons' ),
			'inner-shadow' => __( 'Inner Shadow', 'happy-elementor-addons' ),
			'spotlight'    => __( 'Spotlight', 'happy-elementor-addons' ),
			'frost-edge'   => __( 'Frost Edge', 'happy-elementor-addons' ),
			'vignette'     => __( 'Vignette', 'happy-elementor-addons' ),
			'customs'      => __( 'Custom Shadow', 'happy-elementor-addons' ),
		];

		$custom_shadow = [ 'switcher' => true, 'shadow' => 'customs' ];
		$has_border    = [ 'switcher' => true, 'border_style' => [ 'solid', 'dashed', 'dotted', 'double', 'groove' ] ];

		return [
			'switcher'      => [ 'type' => 'bool', 'default' => false, 'label' => __( 'Enable Liquid Glass', 'happy-elementor-addons' ) ],
			'preset'        => [
				'type'        => 'enum',
				'default'     => 'abyss-glass',
				'label'       => __( 'Effect Preset', 'happy-elementor-addons' ),
				'description' => __( 'Tip: Use a semi-transparent background to see the effect clearly.', 'happy-elementor-addons' ),
				'options'     => $presets,
				'show'        => $on,
			],
			'frequency_x'   => $num( 0.006, __( 'Frequency X', 'happy-elementor-addons' ), 0, 5, 0.001, $custom ),
			'frequency_y'   => $num( 0.006, __( 'Frequency Y', 'happy-elementor-addons' ), 0, 5, 0.001, $custom ),
			'scale'         => [
				'type' => 'slider', 'default' => 80, 'min' => 0, 'max' => 500, 'step' => 1,
				'label' => __( 'Distortion Scale', 'happy-elementor-addons' ),
				'show'  => $custom,
			],
			'octaves'       => $num( 2, __( 'Noise Octaves', 'happy-elementor-addons' ), 1, 10, 1, $custom ),
			'seed'          => $num( 92, __( 'Random Seed', 'happy-elementor-addons' ), 1, 9999, 1, $custom ),
			'x_channel'     => [ 'type' => 'enum', 'default' => 'R', 'label' => __( 'X Channel', 'happy-elementor-addons' ), 'options' => $channels, 'show' => $custom ],
			'y_channel'     => [ 'type' => 'enum', 'default' => 'G', 'label' => __( 'Y Channel', 'happy-elementor-addons' ), 'options' => $channels, 'show' => $custom ],
			'std_deviation' => [
				'type' => 'slider', 'default' => 2, 'min' => 0, 'max' => 20, 'step' => 0.1,
				'label' => __( 'Blur Standard Deviation', 'happy-elementor-addons' ),
				'description' => __( 'Controls the Gaussian blur applied to the noise before displacement.', 'happy-elementor-addons' ),
				'show'  => $custom,
			],
			'blur'          => $num( 0, __( 'Blur Strength (px)', 'happy-elementor-addons' ), 0, 50, 1, $on, __( '0 uses the default value of the selected preset.', 'happy-elementor-addons' ) ),
			'shadow'        => [
				'type'    => 'enum',
				'default' => 'soft-glow',
				'label'   => __( 'Shadow', 'happy-elementor-addons' ),
				'options' => $shadows,
				'show'    => $on,
			],
			'shadow_color'  => [ 'type' => 'color', 'default' => 'rgba(255, 255, 255, 0.5)', 'label' => __( 'Shadow Color', 'happy-elementor-addons' ), 'show' => $custom_shadow ],
			'shadow_x'      => $num( 0, __( 'Shadow Horizontal (px)', 'happy-elementor-addons' ), -200, 200, 1, $custom_shadow ),
			'shadow_y'      => $num( 0, __( 'Shadow Vertical (px)', 'happy-elementor-addons' ), -200, 200, 1, $custom_shadow ),
			'shadow_blur'   => $num( 15, __( 'Shadow Blur (px)', 'happy-elementor-addons' ), 0, 200, 1, $custom_shadow ),
			'shadow_spread' => $num( 0, __( 'Shadow Spread (px)', 'happy-elementor-addons' ), -200, 200, 1, $custom_shadow ),
			'shadow_inset'  => [ 'type' => 'bool', 'default' => true, 'label' => __( 'Shadow Inset', 'happy-elementor-addons' ), 'show' => $custom_shadow ],
			'border_style'  => [
				'type'    => 'enum',
				'default' => 'none',
				'label'   => __( 'Border Type', 'happy-elementor-addons' ),
				'options' => [
					'none'   => __( 'None', 'happy-elementor-addons' ),
					'solid'  => __( 'Solid', 'happy-elementor-addons' ),
					'double' => __( 'Double', 'happy-elementor-addons' ),
					'dotted' => __( 'Dotted', 'happy-elementor-addons' ),
					'dashed' => __( 'Dashed', 'happy-elementor-addons' ),
					'groove' => __( 'Groove', 'happy-elementor-addons' ),
				],
				'show'    => $on,
			],
			'border_width'  => $num( 1, __( 'Border Width (px)', 'happy-elementor-addons' ), 0, 50, 1, $has_border ),
			'border_color'  => [ 'type' => 'color', 'default' => '', 'label' => __( 'Border Color', 'happy-elementor-addons' ), 'show' => $has_border ],
			'radius'        => [
				'type'        => 'string',
				'default'     => '',
				'label'       => __( 'Border Radius', 'happy-elementor-addons' ),
				'description' => __( 'e.g. 20px, 50% or 10px 20px 30px 40px.', 'happy-elementor-addons' ),
				'show'        => $on,
			],
		];
	}

	/**
	 * Prints the element's rules (and the preset's SVG filter); nothing unless enabled.
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
		$css    = self::build_css( self::selector( $id ), $config, $id );

		if ( '' === $css ) {
			return;
		}

		self::$printed[ $id ] = true;

		echo '<style id="ha-lg-' . esc_attr( $id ) . '">' . $css . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- values are whitelisted/clamped in sanitize_config().

		$svg = self::svg( $config, $id );

		if ( '' !== $svg ) {
			echo $svg . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup is built from the preset table and clamped numbers.
		}
	}

	/**
	 * Preset data for the editor preview (see happy-atomic-liquid-glass.js).
	 */
	public function print_editor_presets() {
		$data = [];

		foreach ( self::presets() as $name => $preset ) {
			$data[ $name ] = [
				'blur'   => $preset['blur'],
				'markup' => self::preset_markup( $name ),
			];
		}

		wp_add_inline_script(
			'happy-atomic-liquid-glass',
			'window.HappyAtomic = window.HappyAtomic || {}; window.HappyAtomic.liquidGlass = ' . wp_json_encode( $data ) . ';',
			'before'
		);
	}

	/**
	 * Atomic widgets sit in a `display: contents` wrapper (data-atomic) that has
	 * no box, so the effect goes on the widget's own element inside it.
	 * Containers carry data-id on the box itself.
	 */
	private static function selector( $id ) {
		return '[data-id="' . $id . '"]:not([data-atomic]),[data-id="' . $id . '"][data-atomic]>*';
	}

	private static function preset_markup( $name ) {
		$preset = self::presets()[ $name ] ?? null;

		if ( ! $preset ) {
			return '';
		}

		if ( isset( $preset['markup'] ) ) {
			return $preset['markup'];
		}

		return '<feTurbulence type="fractalNoise" baseFrequency="' . $preset['frequency'] . '" numOctaves="2" seed="92" result="noise" />' .
			'<feGaussianBlur in="noise" stdDeviation="2" result="blurred" />' .
			'<feDisplacementMap in="SourceGraphic" in2="blurred" scale="' . $preset['scale'] . '" xChannelSelector="R" yChannelSelector="G" />';
	}

	private static function filter_id( array $config, $id ) {
		return 'custom' === $config['preset'] ? 'ha-glass-distortion-custom-' . $id : 'ha-glass-distortion-' . $config['preset'];
	}

	/**
	 * The hidden SVG holding the displacement filter. Presets share one filter
	 * per page, the custom preset gets its own per element.
	 */
	private static function svg( array $config, $id ) {
		$custom = 'custom' === $config['preset'];
		$name   = $custom ? 'custom-' . $id : $config['preset'];

		if ( isset( self::$printed_svg[ $name ] ) ) {
			return '';
		}

		if ( $custom ) {
			$inner = '<feTurbulence type="fractalNoise" baseFrequency="' . $config['frequency_x'] . ' ' . $config['frequency_y'] . '" numOctaves="' . $config['octaves'] . '" seed="' . $config['seed'] . '" result="noise" />' .
				'<feGaussianBlur in="noise" stdDeviation="' . $config['std_deviation'] . '" result="blurred" />' .
				'<feDisplacementMap in="SourceGraphic" in2="blurred" scale="' . $config['scale'] . '" xChannelSelector="' . $config['x_channel'] . '" yChannelSelector="' . $config['y_channel'] . '" />';
		} else {
			$inner = self::preset_markup( $config['preset'] );
		}

		if ( '' === $inner ) {
			return '';
		}

		self::$printed_svg[ $name ] = true;

		return '<svg class="ha-lg-svg-' . $name . '" xmlns="http://www.w3.org/2000/svg" width="0" height="0" style="position:absolute;overflow:hidden">' .
			'<defs><filter id="' . self::filter_id( $config, $id ) . '" x="0%" y="0%" width="100%" height="100%">' . $inner . '</filter></defs></svg>';
	}

	/**
	 * @param string $s      Selector of the element.
	 * @param array  $config Sanitized config (prefix stripped).
	 * @param string $id     Element id (custom filter id).
	 */
	public static function build_css( $s, array $config, $id ) {
		if ( ! $config['switcher'] ) {
			return '';
		}

		$presets = self::presets();
		$custom  = 'custom' === $config['preset'];
		$blur    = (float) $config['blur'] > 0 ? $config['blur'] : ( $custom ? 8 : ( $presets[ $config['preset'] ]['blur'] ?? 8 ) );
		$filter  = 'blur(' . $blur . 'px)' . ( $custom || isset( $presets[ $config['preset'] ] ) ? ' url(#' . self::filter_id( $config, $id ) . ')' : '' );

		$rules = '-webkit-backdrop-filter:' . $filter . ';backdrop-filter:' . $filter . ';';

		if ( 'customs' === $config['shadow'] ) {
			$rules .= 'box-shadow:' . ( $config['shadow_inset'] ? 'inset ' : '' ) . $config['shadow_x'] . 'px ' . $config['shadow_y'] . 'px ' . $config['shadow_blur'] . 'px ' . $config['shadow_spread'] . 'px ' . $config['shadow_color'] . ';';
		} elseif ( '' !== ( self::shadows()[ $config['shadow'] ] ?? '' ) ) {
			$rules .= 'box-shadow:' . self::shadows()[ $config['shadow'] ] . ';';
		}

		if ( 'none' !== $config['border_style'] ) {
			$rules .= 'border:' . $config['border_width'] . 'px ' . $config['border_style'] . ( '' !== $config['border_color'] ? ' ' . $config['border_color'] : '' ) . ';';
		}

		$radius = self::radius( $config['radius'] );

		if ( '' !== $radius ) {
			$rules .= 'border-radius:' . $radius . ';';
		}

		return $s . '{' . $rules . '}';
	}

	/**
	 * 1 to 4 lengths (number = px) or percentages; anything else is dropped.
	 */
	private static function radius( $value ) {
		$parts = preg_split( '/\s+/', trim( (string) $value ), -1, PREG_SPLIT_NO_EMPTY );

		if ( ! $parts || count( $parts ) > 4 ) {
			return '';
		}

		foreach ( $parts as $i => $part ) {
			if ( ! preg_match( '/^\d*\.?\d+(px|%|em|rem)?$/', $part ) ) {
				return '';
			}

			$parts[ $i ] = is_numeric( $part ) ? $part . 'px' : $part;
		}

		return implode( ' ', $parts );
	}
}
