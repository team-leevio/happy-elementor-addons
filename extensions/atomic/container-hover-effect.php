<?php
namespace Happy_Addons\Elementor\Extensions\Atomic;

defined( 'ABSPATH' ) || die();

require_once __DIR__ . '/atomic-feature-base.php';

/**
 * Background Hover Effect for the Atomic Flexbox (e-flexbox) and Div Block
 * (e-div-block). The V4 counterpart of extensions/container-hover-text-color.php.
 *
 * Pure CSS: a ::before layer (background: inherit) takes the hover animation and
 * a ::after overlay applies the filters through backdrop-filter, both behind the
 * content (negative z-index inside an isolated stacking context), so the content
 * is never affected. The CSS is printed per element on the frontend; the editor
 * preview builds the same rules in happy-addons-atomic.js (keep them in sync).
 */
class Container_Hover_Effect extends Atomic_Feature_Base {

	/** @var array<string,bool> */
	private static $printed = [];

	protected function prefix() {
		return 'ha_che_';
	}

	protected function target_type() {
		return [ 'e-flexbox', 'e-div-block', 'e-grid' ];
	}

	protected function feature_key() {
		return 'container-hover-effect';
	}

	protected function section_label() {
		return __( 'Background Hover Effect', 'happy-elementor-addons' );
	}

	protected function matches_schema( array $schema ) {
		return isset( $schema['classes'], $schema['tag'], $schema['link'], $schema['attributes'] )
			&& ! isset( $schema['title'], $schema['image'], $schema['text'], $schema['paragraph'] );
	}

	protected function fields() {
		$on = [ 'effects' => true ];

		$num = function ( $default, $label, $min, $max, $step = 1 ) use ( $on ) {
			return [
				'type' => 'number', 'default' => $default, 'min' => $min, 'max' => $max, 'step' => $step,
				'label' => $label,
				'show'  => $on,
			];
		};

		return [
			'text_color' => [
				'type'    => 'color',
				'default' => '',
				'label'   => __( 'Hover Text Color', 'happy-elementor-addons' ),
				'description' => __( 'Applies to headings, paragraphs and links inside on hover.', 'happy-elementor-addons' ),
			],
			'effects'    => [ 'type' => 'bool', 'default' => false, 'label' => __( 'Effects', 'happy-elementor-addons' ) ],
			'animation'  => [
				'type'    => 'enum',
				'default' => 'grow',
				'label'   => __( 'Animation', 'happy-elementor-addons' ),
				'options' => [
					'none'   => __( 'None', 'happy-elementor-addons' ),
					'grow'   => __( 'Grow', 'happy-elementor-addons' ),
					'shrink' => __( 'Shrink', 'happy-elementor-addons' ),
					'rotate' => __( 'Grow & Rotate', 'happy-elementor-addons' ),
				],
				'show'    => $on,
			],
			'duration'   => $num( 0.5, __( 'Duration (s)', 'happy-elementor-addons' ), 0, 5, 0.1 ),
			'blur'       => $num( 0, __( 'Blur (px)', 'happy-elementor-addons' ), 0, 100, 0.1 ),
			'brightness' => $num( 100, __( 'Brightness (%)', 'happy-elementor-addons' ), 0, 200 ),
			'contrast'   => $num( 100, __( 'Contrast (%)', 'happy-elementor-addons' ), 0, 200 ),
			'saturate'   => $num( 100, __( 'Saturation (%)', 'happy-elementor-addons' ), 0, 200 ),
			'hue'        => $num( 0, __( 'Hue (deg)', 'happy-elementor-addons' ), 0, 360 ),
		];
	}

	/**
	 * Prints the element's rules; nothing when no hover option is set.
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

		echo '<style id="ha-che-' . esc_attr( $id ) . '">' . $css . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- values are whitelisted/clamped in sanitize_config().
	}

	/**
	 * @param string $s      Selector of the element.
	 * @param array  $config Sanitized config (prefix stripped).
	 */
	public static function build_css( $s, array $config ) {
		$css = '';

		if ( '' !== $config['text_color'] ) {
			$css .= $s . ':hover :where(h1,h2,h3,h4,h5,h6,p,a,span,li):not(.elementor-element-overlay *,.elementor-editor-element-settings,.elementor-editor-element-settings *){color:' . $config['text_color'] . ';}';
		}

		if ( ! $config['effects'] ) {
			return $css;
		}

		$time = $config['duration'] . 's';

		$css .= ':where(' . $s . '){position:relative;}';
		$css .= $s . '{overflow:hidden;isolation:isolate;}';
		$css .= $s . '::before{content:"";position:absolute;inset:0;border-radius:inherit;background:inherit;z-index:-1;pointer-events:none;transition:transform ' . $time . ' ease;}';
		$css .= $s . '::after{content:"";position:absolute;inset:0;border-radius:inherit;z-index:-1;pointer-events:none;transition:-webkit-backdrop-filter ' . $time . ' ease,backdrop-filter ' . $time . ' ease;}';

		$transforms = [
			'grow'   => 'scale(1.08)',
			'shrink' => 'scale(1)',
			'rotate' => 'scale(1.08) rotate(2deg)',
		];

		if ( isset( $transforms[ $config['animation'] ] ) ) {
			if ( 'shrink' === $config['animation'] ) {
				$css .= $s . '::before{transform:scale(1.08);}';
			}

			$css .= $s . ':hover::before{transform:' . $transforms[ $config['animation'] ] . ';}';
		}

		$filter = 'brightness(' . $config['brightness'] . '%) contrast(' . $config['contrast'] . '%) saturate(' . $config['saturate'] . '%) blur(' . $config['blur'] . 'px) hue-rotate(' . $config['hue'] . 'deg)';

		$css .= $s . ':hover::after{-webkit-backdrop-filter:' . $filter . ';backdrop-filter:' . $filter . ';}';

		return $css;
	}
}
