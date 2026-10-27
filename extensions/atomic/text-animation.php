<?php
namespace Happy_Addons\Elementor\Extensions\Atomic;

defined( 'ABSPATH' ) || die();

require_once __DIR__ . '/atomic-feature-base.php';

/**
 * Text Animation for the Atomic Heading (e-heading).
 *
 * Settings are single valued (Atomic settings are not per-breakpoint). The
 * `{curly}` highlight syntax works only if the characters survive the
 * heading's HTML sanitizing; it is applied to the rendered text.
 */
class Text_Animation extends Atomic_Feature_Base {

	protected function prefix() {
		return 'ha_hta_';
	}

	protected function target_type() {
		return 'e-heading';
	}

	protected function feature_key() {
		return 'text-animation';
	}

	protected function section_label() {
		return __( 'Text Animation', 'happy-elementor-addons' );
	}

	protected function matches_schema( array $schema ) {
		return isset( $schema['title'], $schema['tag'], $schema['link'] ) && ! isset( $schema['image'], $schema['text'], $schema['paragraph'] );
	}

	protected function fields() {
		$on = [ 'switcher' => true ];

		$num = function ( $default, $label, $min, $max, $mode, $step = 1 ) use ( $on ) {
			return [
				'type' => 'number', 'default' => $default, 'min' => $min, 'max' => $max, 'step' => $step,
				'label' => $label,
				'show'  => empty( $mode ) ? $on : $on + [ 'mode' => $mode ],
			];
		};

		return [
			'switcher'                 => [ 'type' => 'bool', 'default' => false, 'label' => __( 'Enable Animation', 'happy-elementor-addons' ) ],
			'mode'                     => [
				'type'    => 'enum',
				'default' => 'reveal',
				'label'   => __( 'Animation Mode', 'happy-elementor-addons' ),
				'options' => [
					'slide'         => __( 'Slide', 'happy-elementor-addons' ),
					'reveal'        => __( 'Text Reveal', 'happy-elementor-addons' ),
					'smoky_reveal'  => __( 'Smoky Reveal', 'happy-elementor-addons' ),
					'alt_reveal'    => __( 'Alternative Reveal', 'happy-elementor-addons' ),
					'popup_reveal'  => __( 'Popup Reveal', 'happy-elementor-addons' ),
					'mixing_reveal' => __( 'Mixing Reveal', 'happy-elementor-addons' ),
					'scale'         => __( 'Scale', 'happy-elementor-addons' ),
					'text_flip'     => __( 'Text Flip', 'happy-elementor-addons' ),
					'invert'        => __( 'Text Invert', 'happy-elementor-addons' ),
				],
				'show'    => $on,
			],
			'chars_words_mode'         => [
				'type'    => 'enum',
				'default' => 'chars',
				'label'   => __( 'Slide Mode', 'happy-elementor-addons' ),
				'options' => [
					'chars' => __( 'Characters', 'happy-elementor-addons' ),
					'words' => __( 'Words', 'happy-elementor-addons' ),
				],
				'show'    => $on + [ 'mode' => 'slide' ],
			],
			'trigger_mode'             => [
				'type'    => 'enum',
				'default' => 'scroll',
				'label'   => __( 'Trigger Mode', 'happy-elementor-addons' ),
				'options' => [
					'scroll'         => __( 'On Appearance', 'happy-elementor-addons' ),
					'playwithscroll' => __( 'On Scroll', 'happy-elementor-addons' ),
				],
				'show'    => $on,
			],
			'cw_transform_x'           => $num( 25, __( 'Transform X', 'happy-elementor-addons' ), -500, 500, 'slide' ),
			'cw_transform_y'           => $num( 0, __( 'Transform Y', 'happy-elementor-addons' ), -500, 500, 'slide' ),
			'tv_rotation_direction'    => [
				'type'    => 'enum',
				'default' => 'vertical',
				'label'   => __( 'Rotation Direction', 'happy-elementor-addons' ),
				'options' => [
					'horizontal' => __( 'Horizontal', 'happy-elementor-addons' ),
					'vertical'   => __( 'Vertical', 'happy-elementor-addons' ),
				],
				'show'    => $on + [ 'mode' => 'text_flip' ],
			],
			'tv_rotation_value'        => $num( -80, __( 'Rotation Value', 'happy-elementor-addons' ), -360, 360, 'text_flip' ),
			'tv_transform_origin'      => [
				'type'    => 'enum',
				'default' => '50% 100%',
				'label'   => __( 'Transform Origin', 'happy-elementor-addons' ),
				'options' => [
					'0% 0%'     => __( 'Left - Top', 'happy-elementor-addons' ),
					'50% 0%'    => __( 'Center - Top', 'happy-elementor-addons' ),
					'100% 0%'   => __( 'Right - Top', 'happy-elementor-addons' ),
					'0% 50%'    => __( 'Left - Center', 'happy-elementor-addons' ),
					'50% 50%'   => __( 'Center - Center', 'happy-elementor-addons' ),
					'100% 50%'  => __( 'Right - Center', 'happy-elementor-addons' ),
					'0% 100%'   => __( 'Left - Bottom', 'happy-elementor-addons' ),
					'50% 100%'  => __( 'Center - Bottom', 'happy-elementor-addons' ),
					'100% 100%' => __( 'Right - Bottom', 'happy-elementor-addons' ),
					'custom'    => __( 'Custom', 'happy-elementor-addons' ),
				],
				'show'    => $on + [ 'mode' => 'text_flip' ],
			],
			'tv_transform_origin_custom' => [
				'type' => 'string', 'default' => 'bottom center -50',
				'label' => __( 'Custom Origin', 'happy-elementor-addons' ),
				'description' => __( 'Set the pivot point for the flip animation. Ex: "top center -50", "center center 0", "bottom center -50" to adjust the 3D depth.', 'happy-elementor-addons' ),
				'show'  => $on + [ 'mode' => 'text_flip', 'tv_transform_origin' => 'custom' ],
			],
			'tr_orientation'           => [
				'type'    => 'enum',
				'default' => 'bottom',
				'label'   => __( 'Orientation', 'happy-elementor-addons' ),
				'options' => [
					'top'    => __( 'From Top', 'happy-elementor-addons' ),
					'bottom' => __( 'From Bottom', 'happy-elementor-addons' ),
				],
				'show'    => $on + [ 'mode' => 'reveal' ],
			],
			'smoky_y_offset'           => $num( 80, __( 'Y Offset', 'happy-elementor-addons' ), -500, 500, 'smoky_reveal' ),
			'smoky_scale'              => $num( 2.5, __( 'Scale', 'happy-elementor-addons' ), 0, 10, 'smoky_reveal', 0.1 ),
			'smoky_blur'               => $num( 15, __( 'Blur (px)', 'happy-elementor-addons' ), 0, 100, 'smoky_reveal' ),
			'alt_even_reveal_y_offset' => $num( -60, __( 'Even Y Offset', 'happy-elementor-addons' ), -500, 500, 'alt_reveal' ),
			'alt_odd_reveal_y_offset'  => $num( 60, __( 'Odd Y Offset', 'happy-elementor-addons' ), -500, 500, 'alt_reveal' ),
			'popup_y_offset'           => $num( 50, __( 'Y Offset', 'happy-elementor-addons' ), -500, 500, 'popup_reveal' ),
			'popup_rotation_deg'       => $num( 40, __( 'Rotation (deg)', 'happy-elementor-addons' ), -360, 360, 'popup_reveal' ),
			'mixing_blur'              => $num( 4, __( 'Blur (px)', 'happy-elementor-addons' ), 0, 100, 'mixing_reveal' ),
			'stagger_from'             => [
				'type'    => 'enum',
				'default' => 'random',
				'label'   => __( 'Stagger From', 'happy-elementor-addons' ),
				'options' => [
					'start'  => __( 'Start', 'happy-elementor-addons' ),
					'end'    => __( 'End', 'happy-elementor-addons' ),
					'center' => __( 'Center', 'happy-elementor-addons' ),
					'random' => __( 'Random', 'happy-elementor-addons' ),
					'edges'  => __( 'Edges', 'happy-elementor-addons' ),
				],
				'show'    => $on + [ 'mode' => [ 'smoky_reveal', 'popup_reveal', 'mixing_reveal' ] ],
			],
			'scale'                    => $num( 1.5, __( 'Scale', 'happy-elementor-addons' ), 0, 10, 'scale', 0.1 ),
			'scale_text_break'         => [
				'type'    => 'enum',
				'default' => 'lines',
				'label'   => __( 'Text Break', 'happy-elementor-addons' ),
				'options' => [
					'lines' => __( 'Lines', 'happy-elementor-addons' ),
					'words' => __( 'Words', 'happy-elementor-addons' ),
					'chars' => __( 'Chars', 'happy-elementor-addons' ),
				],
				'show'    => $on + [ 'mode' => 'scale' ],
			],
			'invert_opacity'           => $num( 0.45, __( 'Inactive Opacity', 'happy-elementor-addons' ), 0, 1, 'invert', 0.01 ),
			'trigger_point'            => [
				'type'    => 'enum',
				'default' => 'top-center',
				'label'   => __( 'Trigger Point', 'happy-elementor-addons' ),
				'description' => __( 'First value = element position, second = viewport position.', 'happy-elementor-addons' ),
				'options' => self::trigger_points(),
				'show'    => $on,
			],
			'custom_trigger_start'     => [
				'type' => 'string', 'default' => 'top 80%',
				'label' => __( 'Custom Trigger (Start)', 'happy-elementor-addons' ),
				'show'  => $on + [ 'trigger_point' => 'custom' ],
			],
			'delay'                    => $num( 0.15, __( 'Delay (s)', 'happy-elementor-addons' ), 0, 10, [], 0.1 ),
			'duration'                 => $num( 0.8, __( 'Duration (s)', 'happy-elementor-addons' ), 0.1, 10, [], 0.1 ),
			'stagger_delay'            => $num( 0.05, __( 'Stagger (s)', 'happy-elementor-addons' ), 0, 2, [], 0.01 ),
			'easing_function'          => [
				'type'    => 'enum',
				'default' => 'power2.out',
				'label'   => __( 'Easing', 'happy-elementor-addons' ),
				'options' => self::easings( [
					'back'          => __( 'Back', 'happy-elementor-addons' ),
					'back.out(1.7)' => __( 'Back Out(1.7)', 'happy-elementor-addons' ),
					'circ.in'       => __( 'Circ In', 'happy-elementor-addons' ),
					'circ.out'      => __( 'Circ Out', 'happy-elementor-addons' ),
					'circ.inOut'    => __( 'Circ InOut', 'happy-elementor-addons' ),
				] ),
				'show'    => $on,
			],
			'highlight_color'          => [
				'type' => 'string', 'default' => '',
				'label' => __( 'Highlight Color ({text})', 'happy-elementor-addons' ),
				'description' => __( 'Color for text wrapped in {curly braces} ( e.g: Happy {Addons} ) in Content → Heading → Title.', 'happy-elementor-addons' ),
				'show'  => $on,
			],
			'enable_on_mobile'         => [
				'type' => 'bool', 'default' => false,
				'label' => __( 'Enable on Mobile', 'happy-elementor-addons' ),
				'description' => __( 'Animations are disabled on mobile by default. Enable to run the animation on mobile screens.', 'happy-elementor-addons' ),
				'show'  => $on,
			],
		];
	}

	/**
	 * Highlight color is a hex/rgb(a)/hsl(a) color or empty.
	 */
	protected function sanitize_config( array $settings ) {
		$config = parent::sanitize_config( $settings );
		$color  = trim( (string) $config['highlight_color'] );

		$valid = '' === $color
			|| sanitize_hex_color( $color )
			|| preg_match( '/^(rgb|hsl)a?\(\s*[\d.%\s,\/]+\)$/i', $color )
			|| preg_match( '/^var\(--[a-z0-9_-]+\)$/i', $color );

		$config['highlight_color'] = $valid ? $color : '';

		return $config;
	}
}
