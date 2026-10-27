<?php
/**
 * Panel field for the Happy Atomic components.
 *
 * Wraps a core Atomic control (number, select, toggle...) so the panel can show
 * what Elementor's own controls cannot: fractional steps, a slider, icon
 * choices and the helper text under the field. It is rendered by the "ha-field"
 * control registered in happy-atomic-editor.js.
 */
namespace Happy_Addons\Elementor\Classes;

use Elementor\Modules\AtomicWidgets\Controls\Base\Atomic_Control_Base;
use Elementor\Modules\AtomicWidgets\Controls\Types\Toggle_Control;

defined( 'ABSPATH' ) || die();

class Atomic_Field_Control extends Atomic_Control_Base {

	private $child_type = 'text';

	private $child_props = [];

	private $inline = true;

	private $description_text = '';

	public function get_type(): string {
		return 'ha-field';
	}

	public function get_props(): array {
		return [
			'childControlType'  => $this->child_type,
			'childControlProps' => (object) $this->child_props,
			'inline'            => $this->inline,
			'description'       => $this->description_text,
		];
	}

	/**
	 * Wrap a core control.
	 *
	 * @param Atomic_Control_Base $control     Core control bound to the prop.
	 * @param string              $label
	 * @param string              $description
	 * @param bool                $inline      Label and field side by side.
	 */
	public static function wrap( Atomic_Control_Base $control, $label, $description = '', $inline = true ) {
		return static::make( $control->get_bind(), $control->get_type(), $control->get_props(), $label, $description, $inline );
	}

	/**
	 * Number field; unlike the core control, min, max and step may be fractions.
	 */
	public static function number( $bind, $label, $min, $max, $step, $description = '' ) {
		return static::make( $bind, 'number', [
			'min'            => $min,
			'max'            => $max,
			'step'           => $step,
			'shouldForceInt' => false,
		], $label, $description );
	}

	public static function slider( $bind, $label, $min, $max, $step, $description = '' ) {
		return static::make( $bind, 'ha-slider', [
			'min'  => $min,
			'max'  => $max,
			'step' => $step,
		], $label, $description, false );
	}

	/**
	 * Color picker (hex/rgba, swatch and picker), bound to a "color" prop.
	 */
	public static function color( $bind, $label, $description = '' ) {
		return static::make( $bind, 'ha-color', [], $label, $description, false );
	}

	/**
	 * Icon buttons, like the Classic "Choose" control.
	 *
	 * @param array $options value => [ 'title' => string, 'icon' => @elementor/icons name ]
	 */
	public static function icon_choice( $bind, $label, array $options, $description = '' ) {
		$config = [];

		foreach ( $options as $value => $option ) {
			$config[ $value ] = [
				'title'       => $option['title'],
				'atomic-icon' => $option['icon'],
			];
		}

		$toggle = Toggle_Control::bind_to( $bind )
			->add_options( $config )
			->set_exclusive( true )
			->set_convert_options( true )
			->set_size( 'tiny' );

		return static::wrap( $toggle, $label, $description );
	}

	private static function make( $bind, $child_type, array $child_props, $label, $description, $inline = true ) {
		$field = static::bind_to( $bind );

		$field->child_type        = $child_type;
		$field->child_props       = $child_props;
		$field->inline            = (bool) $inline;
		$field->description_text  = (string) $description;

		$field->set_label( $label );

		if ( '' !== $field->description_text ) {
			$field->set_description( $field->description_text );
		}

		return $field;
	}
}
