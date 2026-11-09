<?php
namespace Happy_Addons\Elementor\Extensions\Atomic;

use Elementor\Shapes;

defined( 'ABSPATH' ) || die();

require_once __DIR__ . '/atomic-feature-base.php';
require_once HAPPY_ADDONS_DIR_PATH . 'extensions/shape-divider.php';

/**
 * Shape Divider for the Atomic Flexbox (e-flexbox), Div Block (e-div-block) and Grid (e-grid).
 * The V4 counterpart of extensions/shape-divider.php.
 *
 * Classic only adds the Happy shapes to the Shape Divider section Elementor
 * ships for sections / containers. Atomic elements have no such section, so this
 * feature carries the whole set of settings: Type (Elementor + Happy shapes),
 * Color, Width, Height, Flip, Invert and Bring to Front, for the top and the
 * bottom edge. V4 only adds an "Enable Shape Divider" switch on top.
 *
 * The shape (an inline SVG, like Classic) is drawn by happy-atomic-shape-divider.js,
 * which gets the sanitized config (frontend) or reads the editor settings (editor preview).
 */
class Shape_Divider extends Atomic_Feature_Base {

	/** @var array|null */
	private $shapes = null;

	protected function prefix() {
		return 'ha_sd_';
	}

	protected function target_type() {
		return [ 'e-flexbox', 'e-div-block', 'e-grid' ];
	}

	protected function target_types() {
		return [ 'e-flexbox', 'e-div-block', 'e-grid' ];
	}

	protected function feature_key() {
		return 'shape-divider';
	}

	protected function section_label() {
		return __( 'Shape Divider', 'happy-elementor-addons' );
	}

	protected function matches_schema( array $schema ) {
		return isset( $schema['classes'], $schema['tag'], $schema['link'], $schema['attributes'] )
			&& ! isset( $schema['title'], $schema['image'], $schema['text'], $schema['paragraph'] );
	}

	/**
	 * Every shape Elementor and Happy Addons provide, keyed by shape name.
	 *
	 * @return array<string,array>
	 */
	private function shapes() {
		if ( null !== $this->shapes ) {
			return $this->shapes;
		}

		$this->shapes = [];

		// The Happy shapes come from the Classic definition, so they are the same in both versions
		// (and are not duplicated when Classic's own filter has already merged them).
		$happy = \Happy_Addons\Elementor\Extensions\Shape_Divider::additional_shape_divider( [] );
		$all   = array_merge( Shapes::get_shapes(), $happy );

		foreach ( $all as $name => $props ) {
			$is_happy = isset( $happy[ $name ] );
			$url      = $props['image'] ?? ( $props['url'] ?? '' );

			if ( '' === $url ) {
				continue;
			}

			$has_negative = ! empty( $props['has_negative'] );
			$negative     = '';

			if ( $has_negative ) {
				// Happy shapes may not be in Elementor's list (Classic's filter off), so resolve the file here.
				$path          = $props['path'] ?? ELEMENTOR_PATH . 'assets/shapes/' . $name . '.svg';
				$negative_path = str_replace( '.svg', '-negative.svg', $path );

				if ( is_file( $negative_path ) ) {
					$negative = str_replace( '.svg', '-negative.svg', $url );
				} else {
					$has_negative = false;
				}
			}

			$this->shapes[ $name ] = [
				'title'       => $props['title'] ?? $name,
				'url'         => $url,
				'negative'    => $negative,
				'flip'        => ! empty( $props['has_flip'] ),
				'invert'      => $has_negative,
				'height_only' => ! empty( $props['height_only'] ),
				'happy'       => $is_happy,
				'bottom_only' => ! empty( $props['ha_shape_bottom'] ),
			];
		}

		return $this->shapes;
	}

	/**
	 * Shape names of one side whose settings are enabled by $capability
	 * (null = every shape the side offers).
	 */
	private function shape_names( $side, $capability = null ) {
		$names = [];

		foreach ( $this->shapes() as $name => $shape ) {
			// Like Classic, the shapes made for the bottom edge are not offered on top.
			if ( 'top' === $side && $shape['bottom_only'] ) {
				continue;
			}

			if ( null === $capability || ( 'width' === $capability ? ! $shape['height_only'] : $shape[ $capability ] ) ) {
				$names[] = (string) $name;
			}
		}

		return $names;
	}

	private function shape_options( $side ) {
		$options = [ '' => __( 'None', 'happy-elementor-addons' ) ];

		foreach ( $this->shape_names( $side ) as $name ) {
			$options[ $name ] = $this->shapes()[ $name ]['title'];
		}

		return $options;
	}

	protected function fields() {
		$on     = [ 'switcher' => true ];
		$fields = [
			'switcher' => [
				'type'    => 'bool',
				'default' => false,
				'label'   => __( 'Enable Shape Divider', 'happy-elementor-addons' ),
			],
		];

		foreach ( [
			'top'    => __( 'Top', 'happy-elementor-addons' ),
			'bottom' => __( 'Bottom', 'happy-elementor-addons' ),
		] as $side => $side_label ) {
			// Controls that need a chosen shape, or a shape that supports them: Classic's conditions.
			$any    = $on + [ $side . '_shape' => $this->shape_names( $side ) ];
			$width  = $on + [ $side . '_shape' => $this->shape_names( $side, 'width' ) ];
			$flip   = $on + [ $side . '_shape' => $this->shape_names( $side, 'flip' ) ];
			$invert = $on + [ $side . '_shape' => $this->shape_names( $side, 'invert' ) ];

			$fields[ $side . '_shape' ] = [
				'type'    => 'enum',
				'default' => '',
				/* translators: %s: Top / Bottom */
				'label'   => sprintf( __( '%s Shape', 'happy-elementor-addons' ), $side_label ),
				'options' => $this->shape_options( $side ),
				'show'    => $on,
			];
			$fields[ $side . '_color' ] = [
				'type'    => 'color',
				'default' => '',
				/* translators: %s: Top / Bottom */
				'label'   => sprintf( __( '%s Color', 'happy-elementor-addons' ), $side_label ),
				'show'    => $any,
			];
			$fields[ $side . '_width' ] = [
				'type'        => 'slider',
				'default'     => 100,
				'min'         => 100,
				'max'         => 300,
				'step'        => 1,
				/* translators: %s: Top / Bottom */
				'label'       => sprintf( __( '%s Width (%%)', 'happy-elementor-addons' ), $side_label ),
				'description' => __( 'Stretch the shape wider than the container. 100 fits the container.', 'happy-elementor-addons' ),
				'show'        => $width,
			];
			$fields[ $side . '_height' ] = [
				'type'        => 'slider',
				'default'     => 0,
				'min'         => 0,
				'max'         => 500,
				'step'        => 1,
				/* translators: %s: Top / Bottom */
				'label'       => sprintf( __( '%s Height (px)', 'happy-elementor-addons' ), $side_label ),
				'description' => __( '0 keeps the natural height of the shape.', 'happy-elementor-addons' ),
				'show'        => $any,
			];
			$fields[ $side . '_flip' ] = [
				'type'    => 'bool',
				'default' => false,
				/* translators: %s: Top / Bottom */
				'label'   => sprintf( __( '%s Flip', 'happy-elementor-addons' ), $side_label ),
				'show'    => $flip,
			];
			$fields[ $side . '_negative' ] = [
				'type'    => 'bool',
				'default' => false,
				/* translators: %s: Top / Bottom */
				'label'   => sprintf( __( '%s Invert', 'happy-elementor-addons' ), $side_label ),
				'show'    => $invert,
			];
			$fields[ $side . '_above_content' ] = [
				'type'    => 'bool',
				'default' => false,
				/* translators: %s: Top / Bottom */
				'label'   => sprintf( __( '%s Bring to Front', 'happy-elementor-addons' ), $side_label ),
				'show'    => $any,
			];
		}

		return $fields;
	}

	/**
	 * Feature defaults plus the shape catalog, which the editor preview needs to
	 * draw a shape chosen in the panel (it has no PHP render).
	 */
	public function defaults() {
		$catalog = [];

		foreach ( $this->shapes() as $name => $shape ) {
			$catalog[ $name ] = [
				'url'         => $shape['url'],
				'negative'    => $shape['negative'],
				'flip'        => $shape['flip'],
				'invert'      => $shape['invert'],
				'height_only' => $shape['height_only'],
			];
		}

		return parent::defaults() + [ 'shapes' => $catalog ];
	}

	protected function sanitize_config( array $settings ) {
		$config = parent::sanitize_config( $settings );
		$shapes = $this->shapes();

		foreach ( [ 'top', 'bottom' ] as $side ) {
			$name = $config[ $side . '_shape' ] ?? '';
			$info = $shapes[ $name ] ?? null;

			// A hidden setting may still hold an old value: keep only what the chosen shape supports.
			if ( ! $info || ( 'top' === $side && $info['bottom_only'] ) ) {
				foreach ( [ 'shape', 'color', 'width', 'height', 'flip', 'negative', 'above_content' ] as $key ) {
					unset( $config[ $side . '_' . $key ] );
				}

				continue;
			}

			$config[ $side . '_flip' ]     = $info['flip'] && ! empty( $config[ $side . '_flip' ] );
			$config[ $side . '_negative' ] = $info['invert'] && ! empty( $config[ $side . '_negative' ] );

			if ( $info['height_only'] ) {
				$config[ $side . '_width' ] = 100;
			}

			$config[ $side . '_url' ] = esc_url_raw( $config[ $side . '_negative' ] ? $info['negative'] : $info['url'] );
		}

		return $config;
	}
}
