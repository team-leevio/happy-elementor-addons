<?php
namespace Happy_Addons\Elementor\Extensions\Atomic;

use Elementor\Modules\AtomicWidgets\Controls\Section;
use Elementor\Modules\AtomicWidgets\Controls\Types\Select_Control;
use Elementor\Modules\AtomicWidgets\Controls\Types\Switch_Control;
use Elementor\Modules\AtomicWidgets\Controls\Types\Text_Control;
use Elementor\Modules\AtomicWidgets\PropDependencies\Manager as Dependency_Manager;
use Elementor\Modules\AtomicWidgets\PropTypes\Color_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Primitives\Boolean_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Primitives\Number_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Primitives\String_Prop_Type;
use Happy_Addons\Elementor\Classes\Atomic_Field_Control;
use Happy_Addons\Elementor\Classes\Atomic_Manager;

defined( 'ABSPATH' ) || die();

require_once HAPPY_ADDONS_DIR_PATH . 'classes/atomic-field-control.php';

/**
 * Shared plumbing for the Atomic features that extend a core Atomic element
 * (e-image, e-heading) through the props-schema / controls filters.
 *
 * A feature describes its settings in `fields()`; this class turns that into
 * props, editor controls, and the sanitized frontend config.
 *
 * Field spec: [ 'type' => bool|enum|number|string|color, 'default' => mixed,
 * 'label' => string, 'description' => string, 'options' => [value => label] (enum),
 * 'icons' => [value => @elementor/icons name] (enum shown as icon buttons),
 * 'min'/'max'/'step' => number, 'color' type = color picker, 'show' => [ other_key => value|value[] ] ]
 */
abstract class Atomic_Feature_Base {

	/** @var array<string,static> */
	private static $instances = [];

	public static function instance() {
		if ( ! isset( self::$instances[ static::class ] ) ) {
			self::$instances[ static::class ] = new static();
		}

		return self::$instances[ static::class ];
	}

	/** Prefix for every prop, e.g. "ha_aia_". */
	abstract protected function prefix();

	/** Core Atomic element type (or list of types) the feature extends. */
	abstract protected function target_type();

	/** Core Atomic element types the feature extends; defaults to the single target type. */
	protected function target_types() {
		return [ $this->target_type() ];
	}

	/** Registry key used as the frontend config bucket. */
	abstract protected function feature_key();

	/** Whether a props schema belongs to the target element. */
	abstract protected function matches_schema( array $schema );

	/** @return array<string,array> field specs keyed without the prefix. */
	abstract protected function fields();

	abstract protected function section_label();

	public function init() {
		add_filter( 'elementor/atomic-widgets/props-schema', [ $this, 'add_props' ] );
		add_filter( 'elementor/atomic-widgets/controls', [ $this, 'add_controls' ], 10, 2 );
		add_action( 'elementor/frontend/before_render', [ $this, 'before_render' ] );
	}

	/**
	 * Label of the panel section and the prop that turns the feature on, so the
	 * editor can light the section's indicator while the feature is enabled.
	 *
	 * @return array{label:string,switcher:string}
	 */
	public function editor_section() {
		return [
			'label'    => $this->section_label(),
			'switcher' => $this->prefix() . 'switcher',
		];
	}

	public function add_props( $schema ) {
		if ( ! is_array( $schema ) || ! $this->matches_schema( $schema ) ) {
			return $schema;
		}

		foreach ( $this->fields() as $key => $field ) {
			$prop = $this->prefix() . $key;

			switch ( $field['type'] ) {
				case 'bool':
					$type = Boolean_Prop_Type::make()->default( (bool) $field['default'] );
					break;
				case 'enum':
					$type = String_Prop_Type::make()
						->enum( array_map( 'strval', array_keys( $field['options'] ) ) )
						->default( (string) $field['default'] );
					break;
				case 'number':
				case 'slider':
					$type = Number_Prop_Type::make()->float()->default( $field['default'] );
					break;
				case 'color':
					$type = Color_Prop_Type::make();
					break;
				default:
					$type = String_Prop_Type::make()->default( (string) $field['default'] );
			}

			if ( ! empty( $field['show'] ) ) {
				$type->set_dependencies( $this->dependencies( $field['show'] ) );
			}

			$schema[ $prop ] = $type;
		}

		return $schema + $this->extra_props();
	}

	/**
	 * Props that are not a plain field (e.g. a repeater), keyed by full prop name.
	 *
	 * @return array<string,\Elementor\Modules\AtomicWidgets\PropTypes\Contracts\Prop_Type>
	 */
	protected function extra_props() {
		return [];
	}

	/**
	 * Panel controls for extra_props(), keyed by the field key they follow.
	 *
	 * @return array<string,object|object[]>
	 */
	protected function extra_controls() {
		return [];
	}

	public function add_controls( $controls, $element ) {
		if ( ! is_array( $controls ) || ! is_object( $element ) || ! method_exists( $element, 'get_element_type' ) ) {
			return $controls;
		}

		if ( ! in_array( $element::get_element_type(), $this->target_types(), true ) ) {
			return $controls;
		}

		$items = [];
		$extra = $this->extra_controls();

		foreach ( $this->fields() as $key => $field ) {
			$items[] = $this->control( $this->prefix() . $key, $field );

			if ( isset( $extra[ $key ] ) ) {
				$items = array_merge( $items, is_array( $extra[ $key ] ) ? $extra[ $key ] : [ $extra[ $key ] ] );
			}
		}

		$controls[] = Section::make()
			// The trailing run of zero-width characters (not used by Elementor) marks a Happy section: the editor script adds the Happy icon to it.
			->set_label( $this->section_label() . "\u{200B}\u{200C}\u{200B}" )
			->set_id( $this->prefix() . 'section' )
			->set_items( $items );

		return $controls;
	}

	protected function is_target( $element ) {
		return in_array( $element::get_element_type(), (array) $this->target_type(), true );
	}

	/**
	 * Editor control for a field. Fields with a helper text, a fractional step
	 * or icon buttons go through the Happy field wrapper.
	 */
	private function control( $prop, array $field ) {
		$label       = $field['label'];
		$description = $field['description'] ?? '';

		switch ( $field['type'] ) {
			case 'bool':
				$control = Switch_Control::bind_to( $prop );

				return '' === $description
					? $control->set_label( $label )
					: Atomic_Field_Control::wrap( $control, $label, $description );

			case 'enum':
				if ( ! empty( $field['icons'] ) ) {
					$choices = [];

					foreach ( $field['icons'] as $value => $icon ) {
						$choices[ $value ] = [
							'title' => $field['options'][ $value ],
							'icon'  => $icon,
						];
					}

					return Atomic_Field_Control::icon_choice( $prop, $label, $choices, $description );
				}

				$options = [];

				foreach ( $field['options'] as $value => $option_label ) {
					$options[] = [
						'value' => (string) $value,
						'label' => $option_label,
					];
				}

				$control = Select_Control::bind_to( $prop )->set_options( $options );

				return '' === $description
					? $control->set_label( $label )
					: Atomic_Field_Control::wrap( $control, $label, $description );

			case 'color':
				return Atomic_Field_Control::color( $prop, $label, $description );

			case 'number':
				return Atomic_Field_Control::number(
					$prop,
					$label,
					$field['min'] ?? null,
					$field['max'] ?? null,
					$field['step'] ?? 1,
					$description
				);

			case 'slider':
				return Atomic_Field_Control::slider(
					$prop,
					$label,
					$field['min'] ?? 0,
					$field['max'] ?? 100,
					$field['step'] ?? 1,
					$description
				);

			case 'color':
				return Atomic_Field_Control::color( $prop, $label, $description );

			default:
				$control = Text_Control::bind_to( $prop );

				return '' === $description
					? $control->set_label( $label )
					: Atomic_Field_Control::wrap( $control, $label, $description, false );
		}
	}

	/**
	 * Default value of every field keyed without the prefix, for the editor
	 * preview (it reads the settings from the editor, not from PHP).
	 */
	public function defaults() {
		$defaults = [];

		foreach ( $this->fields() as $key => $field ) {
			$defaults[ $key ] = $field['default'];
		}

		return $defaults;
	}

	public function before_render( $element ) {
		if ( ! is_object( $element ) || ! method_exists( $element, 'get_atomic_settings' ) || ! method_exists( $element, 'get_element_type' ) ) {
			return;
		}

		if ( ! in_array( $element::get_element_type(), $this->target_types(), true ) ) {
			return;
		}

		$settings = $element->get_atomic_settings();

		if ( empty( $settings[ $this->prefix() . 'switcher' ] ) ) {
			return;
		}

		Atomic_Manager::register_frontend_config( $this->feature_key(), $element->get_id(), $this->sanitize_config( $settings ) );
	}

	/**
	 * Whitelist enums, clamp numbers, and strip the prop prefix.
	 */
	protected function sanitize_config( array $settings ) {
		$config = [];

		foreach ( $this->fields() as $key => $field ) {
			$value = $settings[ $this->prefix() . $key ] ?? null;

			if ( null === $value || '' === $value ) {
				$value = $field['default'];
			}

			switch ( $field['type'] ) {
				case 'bool':
					$value = (bool) $value;
					break;
				case 'enum':
					$value = array_key_exists( (string) $value, $field['options'] ) ? (string) $value : (string) $field['default'];
					break;
				case 'color':
					$value = self::sanitize_color( $value );
					break;
				case 'number':
				case 'slider':
					$value = (float) $value;

					if ( isset( $field['min'] ) ) {
						$value = max( $field['min'], $value );
					}

					if ( isset( $field['max'] ) ) {
						$value = min( $field['max'], $value );
					}
					break;
				default: // string, color
					$value = sanitize_text_field( (string) $value );
			}

			$config[ $key ] = $value;
		}

		return $config;
	}

	/**
	 * Hex, rgb()/hsl() (with alpha) or a plain color keyword; anything else is dropped.
	 */
	protected static function sanitize_color( $value ) {
		$value = trim( (string) $value );

		if ( preg_match( '/^(#[0-9a-f]{3,8}|(rgb|hsl)a?\(\s*[0-9.%,\s\/-]+\)|[a-z]+)$/i', $value ) ) {
			return $value;
		}

		return '';
	}

	protected function dependencies( array $show ) {
		$manager = Dependency_Manager::make( Dependency_Manager::RELATION_AND );

		foreach ( $show as $key => $value ) {
			$manager->where( [
				'operator' => is_array( $value ) ? 'in' : 'eq',
				'path'     => [ $this->prefix() . $key ],
				'value'    => $value,
				'effect'   => 'hide',
			] );
		}

		return $manager->get();
	}

	protected static function trigger_points() {
		return [
			'top-top'       => __( 'Top - Top', 'happy-elementor-addons' ),
			'top-center'    => __( 'Top - Center', 'happy-elementor-addons' ),
			'top-bottom'    => __( 'Top - Bottom', 'happy-elementor-addons' ),
			'center-top'    => __( 'Center - Top', 'happy-elementor-addons' ),
			'center-center' => __( 'Center - Center', 'happy-elementor-addons' ),
			'center-bottom' => __( 'Center - Bottom', 'happy-elementor-addons' ),
			'bottom-top'    => __( 'Bottom - Top', 'happy-elementor-addons' ),
			'bottom-center' => __( 'Bottom - Center', 'happy-elementor-addons' ),
			'bottom-bottom' => __( 'Bottom - Bottom', 'happy-elementor-addons' ),
			'custom'        => __( 'Custom', 'happy-elementor-addons' ),
		];
	}

	protected static function easings( array $extra = [] ) {
		$easings = [];

		foreach ( [ 'power1', 'power2', 'power3', 'power4' ] as $power ) {
			$label = ucfirst( $power );
			$easings[ $power . '.inOut' ] = $label . ' InOut';
		}

		foreach ( [ 'in' => 'In', 'out' => 'Out' ] as $dir => $dir_label ) {
			foreach ( [ 'power1', 'power2', 'power3', 'power4' ] as $power ) {
				$easings[ $power . '.' . $dir ] = ucfirst( $power ) . ' ' . $dir_label;
			}
		}

		foreach ( [ 'back', 'elastic', 'bounce' ] as $family ) {
			$easings[ $family . '.in' ]    = ucfirst( $family ) . ' In';
			$easings[ $family . '.out' ]   = ucfirst( $family ) . ' Out';
			$easings[ $family . '.inOut' ] = ucfirst( $family ) . ' InOut';
		}

		return array_merge( $easings, $extra );
	}
}
