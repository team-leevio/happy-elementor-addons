<?php
namespace Happy_Addons\Elementor\Extensions\Atomic;

use Elementor\Modules\AtomicWidgets\Controls\Types\Image_Control;
use Elementor\Modules\AtomicWidgets\Controls\Types\Svg_Control;
use Elementor\Modules\AtomicWidgets\Controls\Types\Video_Control;
use Elementor\Modules\AtomicWidgets\PropTypes\Border_Radius_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Border_Width_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Dimensions_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Font_Family_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Icon_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Base\Object_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Image_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Image_Src_Prop_Type;
use Elementor\Modules\AtomicWidgets\Utils\Image\Image_Sizes;
use Elementor\Modules\AtomicWidgets\PropTypes\Size_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Svg_Src_Prop_Type;
use Elementor\Modules\AtomicWidgets\Styles\Size_Constants;
use Elementor\Modules\AtomicWidgets\PropTypes\Union_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Primitives\String_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Video_Src_Prop_Type;
use Happy_Addons\Elementor\Classes\Atomic_Field_Control;

defined( 'ABSPATH' ) || die();

require_once __DIR__ . '/atomic-feature-base.php';

/**
 * Multi-size props that accept sides left empty: the native control leaves the
 * sides the user did not touch empty, which the core prop types reject when the
 * settings are saved ("invalid_value").
 */
trait Lenient_Sides {
	/**
	 * Drops the sides that are not a valid size (empty, half edited).
	 */
	private function without_invalid_sides( $value ) {
		if ( ! is_array( $value ) ) {
			return $value;
		}

		foreach ( $this->get_shape() as $side => $prop_type ) {
			if ( isset( $value[ $side ] ) && ! $prop_type->validate( $value[ $side ] ) ) {
				unset( $value[ $side ] );
			}
		}

		return $value;
	}

	protected function validate_value( $value ): bool {
		return parent::validate_value( $this->without_invalid_sides( $value ) );
	}

	public function sanitize_value( $value ) {
		return parent::sanitize_value( $this->without_invalid_sides( $value ) );
	}
}

/**
 * The image control writes an empty source (no id, no url) when it is opened or
 * cleared, and no size; the core image prop type rejects both.
 */
class Lenient_Image_Src extends Image_Src_Prop_Type {
	protected function validate_value( $value ): bool {
		return Object_Prop_Type::validate_value( $value );
	}
}

class Lenient_Image extends Image_Prop_Type {
	protected function define_shape(): array {
		return [
			'src'  => Lenient_Image_Src::make(),
			'size' => String_Prop_Type::make()->enum( Image_Sizes::get_keys() ),
		];
	}
}

class Lenient_Border_Radius extends Border_Radius_Prop_Type {
	use Lenient_Sides;
}

class Lenient_Border_Width extends Border_Width_Prop_Type {
	use Lenient_Sides;
}

class Lenient_Dimensions extends Dimensions_Prop_Type {
	use Lenient_Sides;
}

/**
 * Happy Mouse Cursor for the Atomic elements. The V4 counterpart of
 * extensions/custom-mouse-cursor.php.
 *
 * Atomic settings are single valued (not per-breakpoint), so the Classic
 * responsive width / height / padding controls are one value here. The Classic
 * Icon (an icon library glyph) is an SVG, which the Atomic panel supports
 * natively. Everything is rendered by happy-atomic-custom-mouse-cursor.js, which
 * builds the per-element rules from the config (frontend) or from the editor
 * settings (editor preview).
 */
class Custom_Mouse_Cursor extends Atomic_Feature_Base {

	/** Raw (unresolved) settings of the element being rendered. */
	private $raw = [];

	protected function prefix() {
		return 'ha_cmc_';
	}

	protected function target_type() {
		return 'e-flexbox';
	}

	protected function target_types() {
		return [ 'e-flexbox', 'e-div-block', 'e-heading', 'e-paragraph', 'e-image', 'e-button', 'e-divider', 'e-svg' ];
	}

	protected function feature_key() {
		return 'custom-mouse-cursor';
	}

	protected function section_label() {
		return __( 'Happy Mouse Cursor', 'happy-elementor-addons' );
	}

	/**
	 * Every Atomic element has these props; the element types decide where the
	 * controls show (target_types()).
	 */
	protected function matches_schema( array $schema ) {
		return isset( $schema['classes'], $schema['attributes'] );
	}

	protected function is_target( $element ) {
		return in_array( $element::get_element_type(), $this->target_types(), true );
	}

	protected function fields() {
		$on     = [ 'switcher' => true ];
		$text   = $on + [ 'type' => 'text' ];
		$icon   = $on + [ 'type' => 'icon' ];
		$image  = $on + [ 'type' => 'image' ];
		$video  = $on + [ 'type' => 'video' ];
		$bg     = $on + [ 'type' => [ 'text', 'color', 'icon' ] ];
		$blur   = $bg + [ 'blur_switcher' => true ];
		$no_blr = $bg + [ 'blur_switcher' => false ];
		$shadow = $on + [ 'type' => [ 'text', 'color', 'icon', 'image' ], 'shadow' => true ];

		$number = function ( $default, $label, $min, $max, $show, $step = 1 ) {
			return [ 'type' => 'number', 'default' => $default, 'min' => $min, 'max' => $max, 'step' => $step, 'label' => $label, 'show' => $show ];
		};
		$slider = function ( $default, $label, $min, $max, $show, $step = 1 ) {
			return [ 'type' => 'slider', 'default' => $default, 'min' => $min, 'max' => $max, 'step' => $step, 'label' => $label, 'show' => $show ];
		};
		$color  = function ( $default, $label, $show ) {
			return [ 'type' => 'color', 'default' => $default, 'label' => $label, 'show' => $show ];
		};
		$toggle = function ( $label, $show, $default = false ) {
			return [ 'type' => 'bool', 'default' => $default, 'label' => $label, 'show' => $show ];
		};
		$enum   = function ( $default, $label, $options, $show ) {
			return [ 'type' => 'enum', 'default' => $default, 'label' => $label, 'options' => $options, 'show' => $show ];
		};

		$border_styles = [
			'none'   => __( 'None', 'happy-elementor-addons' ),
			'solid'  => __( 'Solid', 'happy-elementor-addons' ),
			'double' => __( 'Double', 'happy-elementor-addons' ),
			'dotted' => __( 'Dotted', 'happy-elementor-addons' ),
			'dashed' => __( 'Dashed', 'happy-elementor-addons' ),
			'groove' => __( 'Groove', 'happy-elementor-addons' ),
		];
		$units         = [
			'px' => 'px',
			'%'  => '%',
		];
		$fit           = [
			'contain' => __( 'Contain', 'happy-elementor-addons' ),
			'cover'   => __( 'Cover', 'happy-elementor-addons' ),
			'fill'    => __( 'Fill', 'happy-elementor-addons' ),
		];

		$fields = [
			'switcher'       => [ 'type' => 'bool', 'default' => false, 'label' => __( 'Enable Happy Mouse Cursor', 'happy-elementor-addons' ) ],
			'default_cursor' => $enum( 'default', __( 'Default Cursor', 'happy-elementor-addons' ), $this->default_cursors(), $on ),
			'type'           => $enum(
				'text',
				__( 'Cursor Type', 'happy-elementor-addons' ),
				[
					'text'  => __( 'Text', 'happy-elementor-addons' ),
					'color' => __( 'Color', 'happy-elementor-addons' ),
					'icon'  => __( 'Icon', 'happy-elementor-addons' ),
					'image' => __( 'Image', 'happy-elementor-addons' ),
					'video' => __( 'Video', 'happy-elementor-addons' ),
				],
				$on
			),

			// Icon (the SVG control follows the type, see extra_controls()).
			'icon_color'     => $color( '#FFFFFF', __( 'Icon Color', 'happy-elementor-addons' ), $icon ),
			'icon_size'      => $slider( 22, __( 'Icon Size (px)', 'happy-elementor-addons' ), 1, 200, $icon ),

			// Text.
			'text'                => [ 'type' => 'string', 'default' => 'Happy Addons', 'label' => __( 'Cursor Text', 'happy-elementor-addons' ), 'show' => $text ],
			'text_color'          => $color( '#FFFFFF', __( 'Text Color', 'happy-elementor-addons' ), $text ),
			'text_font_size'      => $number( 0, __( 'Font Size (px)', 'happy-elementor-addons' ), 0, 200, $text ),
			'text_font_weight'    => $enum(
				'default',
				__( 'Font Weight', 'happy-elementor-addons' ),
				[
					'default' => __( 'Default', 'happy-elementor-addons' ),
					'100'     => '100',
					'200'     => '200',
					'300'     => '300',
					'400'     => '400',
					'500'     => '500',
					'600'     => '600',
					'700'     => '700',
					'800'     => '800',
					'900'     => '900',
				],
				$text
			),
			'text_transform'      => $enum(
				'none',
				__( 'Transform', 'happy-elementor-addons' ),
				[
					'none'       => __( 'Default', 'happy-elementor-addons' ),
					'uppercase'  => __( 'Uppercase', 'happy-elementor-addons' ),
					'lowercase'  => __( 'Lowercase', 'happy-elementor-addons' ),
					'capitalize' => __( 'Capitalize', 'happy-elementor-addons' ),
				],
				$text
			),
			'text_line_height'    => $number( 0, __( 'Line Height', 'happy-elementor-addons' ), 0, 10, $text, 0.1 ),
			'text_letter_spacing' => $number( 0, __( 'Letter Spacing (px)', 'happy-elementor-addons' ), -20, 50, $text, 0.1 ),

			// Image.
			'img_fit'              => $enum( 'cover', __( 'Object Fit', 'happy-elementor-addons' ), $fit, $image ),
			'img_width'            => $slider( 150, __( 'Width (px)', 'happy-elementor-addons' ), 0, 500, $image ),
			'img_height'           => $slider( 150, __( 'Height (px)', 'happy-elementor-addons' ), 0, 500, $image ),
			'img_border_style'     => $enum( 'none', __( 'Border Type', 'happy-elementor-addons' ), $border_styles, $image ),
			'img_border_color'     => $color( '#000000', __( 'Border Color', 'happy-elementor-addons' ), $image + [ 'img_border_style' => [ 'solid', 'double', 'dotted', 'dashed', 'groove' ] ] ),
			'img_rotate'           => $number( 0, __( '360 Rotate', 'happy-elementor-addons' ), 0, 360, $image ),
			'img_opacity'          => $number( 1, __( 'Opacity', 'happy-elementor-addons' ), 0, 1, $image, 0.1 ),
			'img_rotation'         => $toggle( __( 'Enable Rotation', 'happy-elementor-addons' ), $image ),
			'img_rotation_delay'   => $slider( 3, __( 'Delay (s)', 'happy-elementor-addons' ), 1, 50, $image + [ 'img_rotation' => true ], 0.1 ),
			'img_rotation_direction' => [
				'type'    => 'enum',
				'default' => 'clockwise',
				'label'   => __( 'Direction', 'happy-elementor-addons' ),
				'options' => [
					'anti-clockwise' => __( 'Anti Clockwise', 'happy-elementor-addons' ),
					'clockwise'      => __( 'Clockwise', 'happy-elementor-addons' ),
				],
				'icons'   => [
					'anti-clockwise' => 'ChevronLeftIcon',
					'clockwise'      => 'ChevronRightIcon',
				],
				'show'    => $image + [ 'img_rotation' => true ],
			],
			'img_offset'           => $toggle( __( 'Offset', 'happy-elementor-addons' ), $image ),
			'img_offset_x'         => $slider( 0, __( 'Offset X (px)', 'happy-elementor-addons' ), -1000, 1000, $image + [ 'img_offset' => true ] ),
			'img_offset_y'         => $slider( 0, __( 'Offset Y (px)', 'happy-elementor-addons' ), -1000, 1000, $image + [ 'img_offset' => true ] ),
			'img_blur'             => $number( 0, __( 'Filter Blur (px)', 'happy-elementor-addons' ), 0, 100, $image, 0.1 ),
			'img_brightness'       => $number( 100, __( 'Filter Brightness (%)', 'happy-elementor-addons' ), 0, 200, $image ),
			'img_contrast'         => $number( 100, __( 'Filter Contrast (%)', 'happy-elementor-addons' ), 0, 200, $image ),
			'img_saturate'         => $number( 100, __( 'Filter Saturation (%)', 'happy-elementor-addons' ), 0, 200, $image ),
			'img_hue'              => $number( 0, __( 'Filter Hue (deg)', 'happy-elementor-addons' ), 0, 360, $image ),

			// Video.
			'video_fit'            => $enum(
				'cover',
				__( 'Object Fit', 'happy-elementor-addons' ),
				[
					'cover' => __( 'Cover', 'happy-elementor-addons' ),
					'fill'  => __( 'Fill', 'happy-elementor-addons' ),
				],
				$video
			),
			'video_width'          => $slider( 150, __( 'Width (px)', 'happy-elementor-addons' ), 0, 500, $video ),
			'video_height'         => $slider( 150, __( 'Height (px)', 'happy-elementor-addons' ), 0, 500, $video ),
			'video_border_style'   => $enum( 'none', __( 'Border Type', 'happy-elementor-addons' ), $border_styles, $video ),
			'video_border_color'   => $color( '#000000', __( 'Border Color', 'happy-elementor-addons' ), $video + [ 'video_border_style' => [ 'solid', 'double', 'dotted', 'dashed', 'groove' ] ] ),
			'video_offset'         => $toggle( __( 'Offset', 'happy-elementor-addons' ), $video ),
			'video_offset_x'       => $slider( 0, __( 'Offset X (px)', 'happy-elementor-addons' ), -1000, 1000, $video + [ 'video_offset' => true ] ),
			'video_offset_y'       => $slider( 0, __( 'Offset Y (px)', 'happy-elementor-addons' ), -1000, 1000, $video + [ 'video_offset' => true ] ),
			'video_opacity'        => $number( 1, __( 'Opacity', 'happy-elementor-addons' ), 0, 1, $video, 0.1 ),

			// Background style (text, color and icon cursors).
			'blur_switcher'        => $toggle( __( 'Enable Blur', 'happy-elementor-addons' ), $bg ),
			'blur'                 => $slider( 6, __( 'Blur (px)', 'happy-elementor-addons' ), 1, 100, $blur ),
			'blur_bg'              => $color( '#FFFFFF33', __( 'Background Color', 'happy-elementor-addons' ), $blur ),
			'bg'                   => $color( '#000000', __( 'Background Color', 'happy-elementor-addons' ), $no_blr ),
			'box_width'            => $slider( 80, __( 'Width (px)', 'happy-elementor-addons' ), 0, 500, $bg ),
			'box_height'           => $slider( 80, __( 'Height (px)', 'happy-elementor-addons' ), 0, 500, $bg ),
			'shadow'               => $toggle( __( 'Box Shadow', 'happy-elementor-addons' ), $on + [ 'type' => [ 'text', 'color', 'icon', 'image' ] ] ),
			'shadow_color'         => $color( 'rgba(0,0,0,0.5)', __( 'Shadow Color', 'happy-elementor-addons' ), $shadow ),
			'shadow_x'             => $number( 0, __( 'Shadow Horizontal (px)', 'happy-elementor-addons' ), -200, 200, $shadow ),
			'shadow_y'             => $number( 0, __( 'Shadow Vertical (px)', 'happy-elementor-addons' ), -200, 200, $shadow ),
			'shadow_blur'          => $number( 10, __( 'Shadow Blur (px)', 'happy-elementor-addons' ), 0, 200, $shadow ),
			'shadow_spread'        => $number( 0, __( 'Shadow Spread (px)', 'happy-elementor-addons' ), -200, 200, $shadow ),
			'border_style'         => $enum( 'none', __( 'Border Type', 'happy-elementor-addons' ), $border_styles, $bg ),
			'border_color'         => $color( '#000000', __( 'Border Color', 'happy-elementor-addons' ), $bg + [ 'border_style' => [ 'solid', 'double', 'dotted', 'dashed', 'groove' ] ] ),
		];

		// Switch, cursor and its content first; everything else is "Layout & Style".
		$head = array_intersect_key( $fields, array_flip( [ 'switcher', 'default_cursor', 'type', 'text' ] ) );

		$fields = $head + $fields;

		return $fields;
	}

	/**
	 * Like the Style tab, the linked mode of the native controls stores one plain
	 * size for all sides, so every multi-size prop is a union with a size.
	 */
	private static function sides( $sides, array $units ) {
		return Union_Prop_Type::create_from( $sides )->add_prop_type( Size_Prop_Type::make()->units( $units ) );
	}

	/** Multi-size / font props, keyed by field key (without prefix). */
	private function native_props() {
		$on    = [ 'switcher' => true ];
		$edges = [ 'solid', 'double', 'dotted', 'dashed', 'groove' ];
		$bg    = $on + [ 'type' => [ 'text', 'color', 'icon' ] ];
		$image = $on + [ 'type' => 'image' ];
		$video = $on + [ 'type' => 'video' ];

		return [
			'style_heading'      => [ String_Prop_Type::make(), $on ],
			'media_svg'          => [ Union_Prop_Type::create_from( Svg_Src_Prop_Type::make() )->add_prop_type( Icon_Prop_Type::make() ), $on + [ 'type' => 'icon' ] ],
			'media_image'        => [ Lenient_Image::make(), $image ],
			'media_video'        => [ Video_Src_Prop_Type::make(), $video ],
			'text_font'          => [ Font_Family_Prop_Type::make(), $on + [ 'type' => 'text' ] ],
			'img_border_sizes'   => [ self::sides( Lenient_Border_Width::make(), Size_Constants::border() ), $image + [ 'img_border_style' => $edges ] ],
			'img_radius_sizes'   => [ self::sides( Lenient_Border_Radius::make(), Size_Constants::border() ), $image ],
			'video_border_sizes' => [ self::sides( Lenient_Border_Width::make(), Size_Constants::border() ), $video + [ 'video_border_style' => $edges ] ],
			'video_radius_sizes' => [ self::sides( Lenient_Border_Radius::make(), Size_Constants::border() ), $video ],
			'padding_sizes'      => [ self::sides( Lenient_Dimensions::make_with_units( Size_Constants::spacing() ), Size_Constants::spacing() ), $on ],
			'margin_sizes'       => [ self::sides( Lenient_Dimensions::make_with_units( Size_Constants::spacing_margin() ), Size_Constants::spacing_margin() ), $on ],
			'border_sizes'       => [ self::sides( Lenient_Border_Width::make(), Size_Constants::border() ), $bg + [ 'border_style' => $edges ] ],
			'radius_sizes'       => [ self::sides( Lenient_Border_Radius::make(), Size_Constants::border() ), $bg ],
		];
	}

	/**
	 * Media, font and the native spacing / border props.
	 */
	protected function extra_props() {
		$props = [];

		foreach ( $this->native_props() as $key => $item ) {
			list( $type, $show ) = $item;

			$type->set_dependencies( $this->dependencies( $show ) );

			$props[ $this->prefix() . $key ] = $type;
		}

		return $props;
	}

	protected function extra_controls() {
		$prop = $this->prefix();

		$spacing = function ( $key, $label, $child, $child_props = [] ) use ( $prop ) {
			return Atomic_Field_Control::custom( $prop . $key, $child, $child_props + [ 'label' => $label ] );
		};

		/** @var Svg_Control $icon */
		$icon = Svg_Control::bind_to( $prop . 'media_svg' );
		$icon->set_label( __( 'Choose Icon', 'happy-elementor-addons' ) );
		// Not available in every Elementor version.
		if ( method_exists( $icon, 'set_show_icon_library' ) ) {
			$icon->set_show_icon_library( true );
		}

		return [
			'type'              => [
				$icon,
				Image_Control::bind_to( $prop . 'media_image' )->set_label( __( 'Choose Image', 'happy-elementor-addons' ) ),
				Video_Control::bind_to( $prop . 'media_video' )->set_label( __( 'Choose Video', 'happy-elementor-addons' ) ),
			],
			'text'              => [
				Atomic_Field_Control::custom( $prop . 'style_heading', 'ha-heading', [ 'text' => __( 'Layout & Style', 'happy-elementor-addons' ) ] ),
			],
			'text_color'        => [
				Atomic_Field_Control::custom( $prop . 'text_font', 'ha-font-family', [], __( 'Font Family', 'happy-elementor-addons' ) ),
			],
			'img_border_color'  => [
				$spacing( 'img_border_sizes', __( 'Border Width', 'happy-elementor-addons' ), 'ha-border-width' ),
			],
			'img_rotate'        => [
				$spacing( 'img_radius_sizes', __( 'Border Radius', 'happy-elementor-addons' ), 'ha-border-radius' ),
			],
			'video_border_color' => [
				$spacing( 'video_border_sizes', __( 'Border Width', 'happy-elementor-addons' ), 'ha-border-width' ),
				$spacing( 'video_radius_sizes', __( 'Border Radius', 'happy-elementor-addons' ), 'ha-border-radius' ),
			],
			'box_height'        => [
				$spacing( 'padding_sizes', __( 'Padding', 'happy-elementor-addons' ), 'ha-dimensions' ),
				$spacing( 'margin_sizes', __( 'Margin', 'happy-elementor-addons' ), 'ha-dimensions', [ 'min' => -1000 ] ),
			],
			'border_color'      => [
				$spacing( 'border_sizes', __( 'Border Width', 'happy-elementor-addons' ), 'ha-border-width' ),
				$spacing( 'radius_sizes', __( 'Border Radius', 'happy-elementor-addons' ), 'ha-border-radius' ),
			],
		];
	}

	public function defaults() {
		$defaults = parent::defaults();

		foreach ( array_keys( $this->native_props() ) as $key ) {
			$defaults[ $key ] = '';
		}

		return $defaults;
	}

	public function before_render( $element ) {
		$this->raw = is_object( $element ) && method_exists( $element, 'get_settings' ) ? (array) $element->get_settings() : [];

		parent::before_render( $element );
	}

	protected function sanitize_config( array $settings ) {
		$config = parent::sanitize_config( $settings );
		$prefix = $this->prefix();

		foreach ( [ 'media_image', 'media_video', 'media_svg' ] as $media ) {
			$config[ $media ] = self::media_url( $settings[ $prefix . $media ] ?? null );
		}

		// A library icon resolves to ready SVG markup (sanitized by Elementor).
		$icon                = $settings[ $prefix . 'media_svg' ] ?? null;
		$config['icon_html'] = is_array( $icon ) && ! empty( $icon['html'] ) && is_string( $icon['html'] ) ? $icon['html'] : '';

		$font                = self::unwrap( $this->raw[ $prefix . 'text_font' ] ?? '' );
		$config['text_font'] = is_string( $font ) ? preg_replace( "/[^a-zA-Z0-9 ,'_-]/", '', $font ) : '';

		$edges   = [ 'block-start', 'inline-end', 'block-end', 'inline-start' ];
		$corners = [ 'start-start', 'start-end', 'end-end', 'end-start' ];

		foreach ( [
			'img_border_sizes'   => $edges,
			'video_border_sizes' => $edges,
			'border_sizes'       => $edges,
			'padding_sizes'      => $edges,
			'margin_sizes'       => $edges,
			'img_radius_sizes'   => $corners,
			'video_radius_sizes' => $corners,
			'radius_sizes'       => $corners,
		] as $key => $binds ) {
			$config[ $key ] = self::lengths( $this->raw[ $prefix . $key ] ?? null, $binds );
		}

		return $config;
	}

	/**
	 * Strips the $$type wrappers of a raw Atomic prop.
	 */
	private static function unwrap( $value ) {
		while ( is_array( $value ) && array_key_exists( '$$type', $value ) ) {
			$value = $value['value'] ?? null;
		}

		return $value;
	}

	/**
	 * The four CSS lengths (shorthand order) of a raw multi-size prop; [] when none is set.
	 */
	private static function lengths( $raw, array $binds ) {
		$raw = self::unwrap( $raw );

		if ( ! is_array( $raw ) ) {
			return [];
		}

		// Linked mode: one size for every side.
		if ( isset( $raw['size'] ) && is_numeric( self::unwrap( $raw['size'] ) ) ) {
			$unit = self::unwrap( $raw['unit'] ?? 'px' );
			$one  = (float) self::unwrap( $raw['size'] ) . ( in_array( $unit, [ 'px', 'em', 'rem', '%', 'vw', 'vh' ], true ) ? $unit : 'px' );

			return [ $one, $one, $one, $one ];
		}

		$any = false;
		$out = [];

		foreach ( $binds as $bind ) {
			$side = self::unwrap( $raw[ $bind ] ?? null );

			if ( is_array( $side ) && isset( $side['size'] ) && is_numeric( self::unwrap( $side['size'] ) ) ) {
				$unit  = self::unwrap( $side['unit'] ?? 'px' );
				$out[] = (float) self::unwrap( $side['size'] ) . ( in_array( $unit, [ 'px', 'em', 'rem', '%', 'vw', 'vh' ], true ) ? $unit : 'px' );
				$any   = true;
			} else {
				$out[] = '0';
			}
		}

		return $any ? $out : [];
	}

	/**
	 * URL of a resolved media prop: a string, or an array holding a url / src /
	 * attachment id.
	 */
	private static function media_url( $value ) {
		if ( is_string( $value ) ) {
			return esc_url_raw( $value );
		}

		if ( ! is_array( $value ) ) {
			return '';
		}

		foreach ( [ 'url', 'src' ] as $key ) {
			if ( ! empty( $value[ $key ] ) && $url = self::media_url( $value[ $key ] ) ) {
				return $url;
			}
		}

		if ( ! empty( $value['id'] ) && is_numeric( $value['id'] ) ) {
			return (string) esc_url_raw( (string) wp_get_attachment_url( (int) $value['id'] ) );
		}

		return '';
	}

	private function default_cursors() {
		$cursors = [
			'default'      => __( 'Default', 'happy-elementor-addons' ),
			'none'         => __( 'None', 'happy-elementor-addons' ),
		];

		foreach ( [
			'alias', 'all-scroll', 'auto', 'cell', 'col-resize', 'context-menu', 'copy', 'crosshair', 'e-resize', 'ew-resize',
			'grab', 'help', 'move', 'n-resize', 'ne-resize', 'nesw-resize', 'ns-resize', 'nw-resize', 'nwse-resize', 'no-drop',
			'not-allowed', 'pointer', 'progress', 'row-resize', 's-resize', 'se-resize', 'sw-resize', 'text', 'w-resize', 'wait',
			'zoom-in', 'zoom-out',
		] as $cursor ) {
			$cursors[ $cursor ] = $cursor;
		}

		return $cursors;
	}
}
