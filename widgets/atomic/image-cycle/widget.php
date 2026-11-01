<?php
namespace Happy_Addons\Elementor\Widget\Atomic;

use Elementor\Modules\AtomicWidgets\Controls\Types\Link_Control;
use Elementor\Modules\AtomicWidgets\Controls\Types\Repeatable_Control;
use Elementor\Modules\AtomicWidgets\Controls\Types\Switch_Control;
use Elementor\Modules\AtomicWidgets\Controls\Types\Text_Control;
use Elementor\Modules\AtomicWidgets\Elements\Base\Atomic_Widget_Base;
use Elementor\Modules\AtomicWidgets\Elements\Base\Has_Template;
use Elementor\Modules\AtomicWidgets\PropTypes\Attributes_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Classes_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Image_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Image_Src_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Url_Prop_Type;
use Elementor\Modules\AtomicWidgets\Controls\Section;
use Elementor\Modules\AtomicWidgets\Controls\Types\Select_Control;
use Elementor\Modules\AtomicWidgets\Utils\Image\Image_Sizes;
use Elementor\Modules\AtomicWidgets\Utils\Image\Placeholder_Image;
use Elementor\Modules\Components\PropTypes\Overridable_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropDependencies\Manager as Dependency_Manager;
use Elementor\Modules\AtomicWidgets\PropTypes\Primitives\Boolean_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Primitives\Number_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Primitives\String_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Size_Prop_Type;
use Elementor\Modules\AtomicWidgets\Styles\Style_Definition;
use Elementor\Modules\AtomicWidgets\Styles\Style_Variant;
use Happy_Addons\Elementor\Classes\Atomic_Field_Control;

defined( 'ABSPATH' ) || die();

require_once HAPPY_ADDONS_DIR_PATH . 'classes/atomic-field-control.php';
require_once __DIR__ . '/image-item-prop-types.php';

/**
 * Image Cycle (V4).
 *
 * A leaf Atomic widget: the images (a repeater of image, resolution and link),
 * headings and GSAP animation settings are all controls in the panel; the
 * animation runs from happy-addons-atomic.js.
 */
class Image_Cycle extends Atomic_Widget_Base {

	use Has_Template;

	const DEFAULT_IMAGES = 8;

	public static function get_element_type(): string {
		return 'ha-a-image-cycle';
	}

	public function get_title() {
		return esc_html__( 'Image Cycle', 'happy-elementor-addons' );
	}

	public function get_icon() {
		return 'hm hm-net';
	}

	public function get_keywords() {
		return [ 'ato', 'atom', 'atomic', 'happy', 'image', 'cycle', 'gsap', 'animation', 'gallery' ];
	}

	public function get_style_depends() {
		return [ 'happy-atomic-image-cycle' ];
	}

	public function get_script_depends() {
		return [ 'gsap', 'imagesloaded', 'happy-elementor-addons-atomic' ];
	}

	protected static function options( array $map ) {
		$options = [];

		foreach ( $map as $value => $label ) {
			$options[] = [
				'value' => (string) $value,
				'label' => $label,
			];
		}

		return $options;
	}

	protected static function select( $prop, $label, array $map ) {
		return Select_Control::bind_to( $prop )
			->set_label( $label )
			->set_options( static::options( $map ) );
	}

	protected static function section( $id, $label, array $items ) {
		return Section::make()
			->set_label( $label )
			->set_id( $id )
			->set_items( $items );
	}

	protected static function number( $default ) {
		return Number_Prop_Type::make()->float()->default( $default );
	}

	/**
	 * Placeholder image item, as the "ha-image-item" value the editor stores.
	 */
	protected static function placeholder_item() {
		return Image_Item_Prop_Type::generate( [
			'image' => Image_Prop_Type::generate( [
				'src'  => Image_Src_Prop_Type::generate( [
					'id'  => null,
					'url' => Url_Prop_Type::generate( Placeholder_Image::get_placeholder_image() ),
				] ),
				'size' => String_Prop_Type::generate( 'large' ),
			] ),
		] );
	}

	protected static function images_prop() {
		return Image_Items_Prop_Type::make()
			->default( array_fill( 0, self::DEFAULT_IMAGES, static::placeholder_item() ) );
	}

	protected static function define_props_schema(): array {
		$only_v1 = Dependency_Manager::make()
			->where( [
				'operator' => 'eq',
				'path'     => [ 'animation_mode' ],
				'value'    => 'variation-1',
				'effect'   => 'hide',
			] )
			->get();

		$only_v3 = Dependency_Manager::make()
			->where( [
				'operator' => 'eq',
				'path'     => [ 'animation_mode' ],
				'value'    => 'variation-3',
				'effect'   => 'hide',
			] )
			->get();

		$not_v3 = Dependency_Manager::make()
			->where( [
				'operator' => 'in',
				'path'     => [ 'animation_mode' ],
				'value'    => [ 'variation-1', 'variation-2' ],
				'effect'   => 'hide',
			] )
			->get();

		$tags = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' ];

		return array_merge(
			[
				'classes'    => Classes_Prop_Type::make()->default( [] ),
				'attributes' => Attributes_Prop_Type::make()->meta( Overridable_Prop_Type::ignore() ),
			],
			[
				'images'             => static::images_prop(),
				'animation_mode'     => String_Prop_Type::make()
					->enum( [ 'variation-1', 'variation-2', 'variation-3' ] )
					->default( 'variation-1' ),
				'direction'          => String_Prop_Type::make()
					->enum( [ 'bottom', 'left', 'top', 'right' ] )
					->default( 'bottom' )
					->set_dependencies( $only_v1 ),

				'title'              => String_Prop_Type::make()->default( __( 'Happy', 'happy-elementor-addons' ) ),
				'title_second'       => String_Prop_Type::make()->default( __( 'Addons', 'happy-elementor-addons' ) ),
				'subtitle'           => String_Prop_Type::make()->default( '2026' ),
				'title_tag'          => String_Prop_Type::make()->enum( $tags )->default( 'h2' ),
				'subtitle_tag'       => String_Prop_Type::make()->enum( $tags )->default( 'h5' ),

				'radius1'            => static::number( 50 ),
				'radius1_mobile'     => static::number( 50 ),
				'radius'             => static::number( 250 ),
				'radius_mobile'      => static::number( 180 ),
				'rotation_direction' => String_Prop_Type::make()
					->enum( [ 'clockwise', 'counter-clockwise' ] )
					->default( 'clockwise' ),
				'stagger'            => static::number( 0.1 )->set_dependencies( $not_v3 ),
				'stagger_v3'         => static::number( 0.15 )->set_dependencies( $only_v3 ),
				'entrance_duration'  => static::number( 0.5 ),
				'heading_duration'   => static::number( 1 ),
				'group_duration'     => static::number( 20 ),
				'flip_interval'      => static::number( 2 ),
				'initial_scale'      => static::number( 3 ),
				'initial_opacity'    => static::number( 0.8 ),
				'pause_on_hover'     => Boolean_Prop_Type::make()->default( false ),
				'play_on_appear'     => Boolean_Prop_Type::make()->default( false ),
			]
		);
	}

	protected function define_atomic_controls(): array {
		$tags = [
			'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6',
			'div' => 'div', 'span' => 'span', 'p' => 'p',
		];

		$field = function ( $control, $label, $description = '', $inline = true ) {
			return Atomic_Field_Control::wrap( $control, $label, $description, $inline );
		};

		$images = Repeatable_Control::bind_to( 'images' )
			->set_repeaterLabel( __( 'Images', 'happy-elementor-addons' ) )
			->set_child_control_type( 'ha-image-item' )
			->set_child_control_props( [
				'sizes'       => Image_Sizes::get_all(),
				'linkOptions' => Link_Control::bind_to( 'link' )->set_placeholder( 'https://your-link.com' )->get_props(),
			] )
			->set_initialValues( (object) [
				'image' => static::placeholder_item()['value']['image'],
				'link'  => null,
			] )
			->set_patternLabel( '${value.link.value.destination.value}' )
			->set_placeholder( __( 'Image', 'happy-elementor-addons' ) )
			->set_prop_key( 'ha-image-item-array' )
			->set_is_sortable( true )
			->hide_toggle();

		return [
			static::section( 'images', __( 'Images', 'happy-elementor-addons' ), [ $images ] ),
			static::section( 'content', __( 'Content', 'happy-elementor-addons' ), [
				Text_Control::bind_to( 'title' )->set_label( __( 'Title', 'happy-elementor-addons' ) ),
				Text_Control::bind_to( 'title_second' )->set_label( __( 'Second Title', 'happy-elementor-addons' ) ),
				Text_Control::bind_to( 'subtitle' )->set_label( __( 'Subtitle', 'happy-elementor-addons' ) ),
				static::select( 'title_tag', __( 'Title Tag', 'happy-elementor-addons' ), $tags ),
				static::select( 'subtitle_tag', __( 'Subtitle Tag', 'happy-elementor-addons' ), $tags ),
			] ),
			static::section( 'animation', __( 'Animation', 'happy-elementor-addons' ), [
				static::select( 'animation_mode', __( 'Animation Mode', 'happy-elementor-addons' ), [
					'variation-1' => __( 'Variation 1', 'happy-elementor-addons' ),
					'variation-2' => __( 'Variation 2', 'happy-elementor-addons' ),
					'variation-3' => __( 'Variation 3', 'happy-elementor-addons' ),
				] ),
				Atomic_Field_Control::icon_choice( 'direction', __( 'Entrance Direction', 'happy-elementor-addons' ), [
					'bottom' => [ 'title' => __( 'Bottom', 'happy-elementor-addons' ), 'icon' => 'ArrowUpIcon' ],
					'left'   => [ 'title' => __( 'Left', 'happy-elementor-addons' ), 'icon' => 'ArrowRightIcon' ],
					'top'    => [ 'title' => __( 'Top', 'happy-elementor-addons' ), 'icon' => 'ArrowDownIcon' ],
					'right'  => [ 'title' => __( 'Right', 'happy-elementor-addons' ), 'icon' => 'ArrowLeftIcon' ],
				] ),
				Atomic_Field_Control::icon_choice( 'rotation_direction', __( 'Rotation Direction', 'happy-elementor-addons' ), [
					'clockwise'         => [ 'title' => __( 'Clockwise', 'happy-elementor-addons' ), 'icon' => 'ArrowForwardUpIcon' ],
					'counter-clockwise' => [ 'title' => __( 'Counter Clockwise', 'happy-elementor-addons' ), 'icon' => 'ArrowBackUpIcon' ],
				], __( 'Rotation direction for the entrance animation and the continuous rotation.', 'happy-elementor-addons' ) ),
				Atomic_Field_Control::slider( 'radius', __( 'Radius (px)', 'happy-elementor-addons' ), 50, 800, 1, __( 'Distance from image center to screen center.', 'happy-elementor-addons' ) ),
				Atomic_Field_Control::slider( 'radius_mobile', __( 'Radius on Mobile (px)', 'happy-elementor-addons' ), 50, 800, 1, __( 'Distance from image center to screen center.', 'happy-elementor-addons' ) ),
				Atomic_Field_Control::number( 'radius1', __( 'Inner Radius (px)', 'happy-elementor-addons' ), 0, 800, 1, __( 'Circle size when the animation is appearing.', 'happy-elementor-addons' ) ),
				Atomic_Field_Control::number( 'radius1_mobile', __( 'Inner Radius on Mobile (px)', 'happy-elementor-addons' ), 0, 800, 1, __( 'Circle size when the animation is appearing.', 'happy-elementor-addons' ) ),
				Atomic_Field_Control::number( 'stagger', __( 'Stagger Delay', 'happy-elementor-addons' ), 0, 5, 0.01, __( 'Variation 1/2: 0.1, Variation 3: 0.15 by default.', 'happy-elementor-addons' ) ),
				Atomic_Field_Control::number( 'stagger_v3', __( 'Stagger Delay', 'happy-elementor-addons' ), 0, 5, 0.01, __( 'Variation 1/2: 0.1, Variation 3: 0.15 by default.', 'happy-elementor-addons' ) ),
				Atomic_Field_Control::number( 'entrance_duration', __( 'Entrance Duration (s)', 'happy-elementor-addons' ), 0.1, 10, 0.1 ),
				Atomic_Field_Control::number( 'heading_duration', __( 'Heading Duration (s)', 'happy-elementor-addons' ), 0.1, 10, 0.1 ),
				Atomic_Field_Control::number( 'group_duration', __( 'Rotation Duration (s)', 'happy-elementor-addons' ), 0.1, 60, 0.1 ),
				Atomic_Field_Control::number( 'flip_interval', __( 'Flip Interval (s)', 'happy-elementor-addons' ), 0.5, 10, 0.1 ),
				Atomic_Field_Control::number( 'initial_scale', __( 'Initial Scale', 'happy-elementor-addons' ), 1, 5, 0.1 ),
				Atomic_Field_Control::slider( 'initial_opacity', __( 'Initial Opacity', 'happy-elementor-addons' ), 0, 1, 0.05 ),
				$field(
					Switch_Control::bind_to( 'pause_on_hover' ),
					__( 'Pause Rotation on Hover', 'happy-elementor-addons' ),
					__( 'Stop the rotation while the mouse is over the images and start it again when the mouse moves out.', 'happy-elementor-addons' )
				),
				$field(
					Switch_Control::bind_to( 'play_on_appear' ),
					__( 'Play When Appearing', 'happy-elementor-addons' ),
					__( 'Play the animation when the widget reaches the center of the viewport while scrolling.', 'happy-elementor-addons' )
				),
			] ),
			static::section( 'settings', __( 'Settings', 'happy-elementor-addons' ), [
				Text_Control::bind_to( '_cssid' )
					->set_label( __( 'ID', 'happy-elementor-addons' ) )
					->set_meta( $this->get_css_id_control_meta() ),
			] ),
		];
	}

	protected function define_base_styles(): array {
		return [
			'base' => Style_Definition::make()
				->add_variant(
					Style_Variant::make()
						->add_prop( 'display', String_Prop_Type::generate( 'block' ) )
						->add_prop( 'height', Size_Prop_Type::generate( [
							'size' => 100,
							'unit' => 'vh',
						] ) )
						->add_prop( 'min-height', Size_Prop_Type::generate( [
							'size' => 70,
							'unit' => 'vh',
						] ) )
						->add_prop( 'overflow', String_Prop_Type::generate( 'hidden' ) )
				),
		];
	}

	protected function get_templates(): array {
		return [
			'happy-addons/elements/ha-a-image-cycle' => __DIR__ . '/image-cycle.html.twig',
		];
	}
}
