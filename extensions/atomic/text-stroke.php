<?php
namespace Happy_Addons\Elementor\Extensions\Atomic;

defined( 'ABSPATH' ) || die();

require_once __DIR__ . '/atomic-feature-base.php';

/**
 * Text Stroke for the Atomic Heading (e-heading). The V4 counterpart of
 * extensions/text-stroke.php.
 *
 * Pure CSS (-webkit-text-stroke). The CSS is printed per element on the
 * frontend; the editor preview builds the same rule in
 * happy-atomic-text-stroke.js (keep them in sync).
 */
class Text_Stroke extends Atomic_Feature_Base {

	/** @var array<string,bool> */
	private static $printed = [];

	protected function prefix() {
		return 'ha_ts_';
	}

	protected function target_type() {
		return 'e-heading';
	}

	protected function feature_key() {
		return 'text-stroke';
	}

	protected function section_label() {
		return __( 'Text Stroke', 'happy-elementor-addons' );
	}

	protected function matches_schema( array $schema ) {
		return isset( $schema['title'], $schema['tag'], $schema['link'] ) && ! isset( $schema['image'], $schema['text'], $schema['paragraph'] );
	}

	protected function fields() {
		return [
			'width' => [
				'type'    => 'slider',
				'default' => 0,
				'min'     => 0,
				'max'     => 20,
				'step'    => 0.1,
				'label'   => __( 'Stroke Width (px)', 'happy-elementor-addons' ),
			],
			'color' => [
				'type'    => 'color',
				'default' => '#000000',
				'label'   => __( 'Stroke Color', 'happy-elementor-addons' ),
			],
		];
	}

	/**
	 * Prints the element's rule; nothing when no stroke width is set.
	 */
	public function before_render( $element ) {
		if ( ! is_object( $element ) || ! method_exists( $element, 'get_atomic_settings' ) || ! method_exists( $element, 'get_element_type' ) || ! $this->is_target( $element ) ) {
			return;
		}

		$id = preg_replace( '/[^a-zA-Z0-9_-]/', '', (string) $element->get_id() );

		if ( '' === $id || isset( self::$printed[ $id ] ) ) {
			return;
		}

		$css = self::build_css( self::selector( $id ), $this->sanitize_config( $element->get_atomic_settings() ) );

		if ( '' === $css ) {
			return;
		}

		self::$printed[ $id ] = true;

		echo '<style id="ha-ts-' . esc_attr( $id ) . '">' . $css . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- values are whitelisted/clamped in sanitize_config().
	}

	/**
	 * Atomic widgets sit in a `display: contents` wrapper (data-atomic) that has
	 * no box, so the stroke goes on the widget's own element inside it.
	 */
	private static function selector( $id ) {
		return '[data-id="' . $id . '"]:not([data-atomic]),[data-id="' . $id . '"][data-atomic]>*';
	}

	/**
	 * @param string $s      Selector of the element.
	 * @param array  $config Sanitized config (prefix stripped).
	 */
	public static function build_css( $s, array $config ) {
		if ( $config['width'] <= 0 || '' === $config['color'] ) {
			return '';
		}

		$stroke = $config['width'] . 'px ' . $config['color'];

		return $s . '{-webkit-text-stroke:' . $stroke . ';text-stroke:' . $stroke . ';}';
	}
}
