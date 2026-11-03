<?php
namespace Happy_Addons\Elementor\Extensions\Atomic;

defined( 'ABSPATH' ) || die();

require_once __DIR__ . '/atomic-feature-base.php';

/**
 * Fixed Size for the Atomic Button (e-button). The V4 counterpart of
 * extensions/fixed-size-button.php.
 *
 * Pure CSS. The CSS is printed per element on the frontend; the editor preview
 * builds the same rule in happy-atomic-fixed-size-button.js (keep them in sync).
 */
class Fixed_Size_Button extends Atomic_Feature_Base {

	/** @var array<string,bool> */
	private static $printed = [];

	protected function prefix() {
		return 'ha_fsb_';
	}

	protected function target_type() {
		return 'e-button';
	}

	protected function feature_key() {
		return 'fixed-size-button';
	}

	protected function section_label() {
		return __( 'Fixed Size', 'happy-elementor-addons' );
	}

	protected function matches_schema( array $schema ) {
		return isset( $schema['text'], $schema['link'] ) && ! isset( $schema['title'], $schema['image'], $schema['paragraph'] );
	}

	protected function fields() {
		$on = [ 'switcher' => true ];

		return [
			'switcher' => [
				'type'    => 'bool',
				'default' => false,
				'label'   => __( 'Fixed Size', 'happy-elementor-addons' ),
			],
			'height'   => [
				'type'    => 'slider',
				'default' => 0,
				'min'     => 0,
				'max'     => 500,
				'step'    => 1,
				'label'   => __( 'Height (px)', 'happy-elementor-addons' ),
				'show'    => $on,
			],
			'width'    => [
				'type'    => 'slider',
				'default' => 0,
				'min'     => 0,
				'max'     => 500,
				'step'    => 1,
				'label'   => __( 'Width (px)', 'happy-elementor-addons' ),
				'show'    => $on,
			],
			'align_x'  => [
				'type'    => 'enum',
				'default' => 'center',
				'label'   => __( 'Horizontal Align', 'happy-elementor-addons' ),
				'options' => [
					'left'   => __( 'Left', 'happy-elementor-addons' ),
					'center' => __( 'Center', 'happy-elementor-addons' ),
					'right'  => __( 'Right', 'happy-elementor-addons' ),
				],
				'icons'   => [
					'left'   => 'LayoutAlignLeftIcon',
					'center' => 'LayoutAlignCenterIcon',
					'right'  => 'LayoutAlignRightIcon',
				],
				'show'    => $on,
			],
			'align_y'  => [
				'type'    => 'enum',
				'default' => 'center',
				'label'   => __( 'Vertical Align', 'happy-elementor-addons' ),
				'options' => [
					'top'    => __( 'Top', 'happy-elementor-addons' ),
					'center' => __( 'Center', 'happy-elementor-addons' ),
					'bottom' => __( 'Bottom', 'happy-elementor-addons' ),
				],
				'icons'   => [
					'top'    => 'JustifyTopIcon',
					'center' => 'JustifyCenterIcon',
					'bottom' => 'JustifyBottomIcon',
				],
				'show'    => $on,
			],
		];
	}

	/**
	 * Prints the element's rule; nothing when the switcher is off.
	 */
	public function before_render( $element ) {
		if ( ! is_object( $element ) || ! method_exists( $element, 'get_atomic_settings' ) || ! method_exists( $element, 'get_element_type' ) || ! $this->is_target( $element ) ) {
			return;
		}

		$id = preg_replace( '/[^a-zA-Z0-9_-]/', '', (string) $element->get_id() );

		if ( '' === $id || isset( self::$printed[ $id ] ) ) {
			return;
		}

		$settings = $element->get_atomic_settings();

		if ( empty( $settings[ $this->prefix() . 'switcher' ] ) ) {
			return;
		}

		self::$printed[ $id ] = true;

		echo '<style id="ha-fsb-' . esc_attr( $id ) . '">' . self::build_css( self::selector( $id ), $this->sanitize_config( $settings ) ) . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- values are whitelisted/clamped in sanitize_config().
	}

	/**
	 * Atomic widgets sit in a `display: contents` wrapper (data-atomic) that has
	 * no box, so the size goes on the button's own element inside it.
	 */
	private static function selector( $id ) {
		return '[data-id="' . $id . '"]:not([data-atomic]),[data-id="' . $id . '"][data-atomic]>*';
	}

	/**
	 * @param string $s      Selector of the element.
	 * @param array  $config Sanitized config (prefix stripped).
	 */
	public static function build_css( $s, array $config ) {
		$x = [ 'left' => 'flex-start', 'center' => 'center', 'right' => 'flex-end' ];
		$y = [ 'top' => 'flex-start', 'center' => 'center', 'bottom' => 'flex-end' ];

		$css = 'display:inline-flex;box-sizing:border-box;'
			. 'justify-content:' . $x[ $config['align_x'] ] . ';'
			. 'align-items:' . $y[ $config['align_y'] ] . ';';

		if ( $config['height'] > 0 ) {
			$css .= 'height:' . $config['height'] . 'px;';
		}

		if ( $config['width'] > 0 ) {
			$css .= 'width:' . $config['width'] . 'px;';
		}

		return $s . '{' . $css . '}';
	}
}
