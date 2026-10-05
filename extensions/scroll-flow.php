<?php

namespace Happy_Addons\Elementor\Extensions;

// Elementor Classes.
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die();

/**
 * Scroll Flow.
 *
 * Turns the direct children of an enabled container (or section) into
 * full-height panels that transition between each other on wheel / touch
 * using GSAP Observer.
 *
 * @since 3.50.0
 */
class Scroll_Flow {

	/**
	 * @var mixed
	 */
	private static $instance = null;

	/**
	 * @var mixed
	 */
	private $load_script = null;

	public static function instance() {
		if ( null === ( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function init() {

		// Enqueue the required JS file.
		add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'register_styles' ] );

		add_action( 'elementor/preview/enqueue_scripts', [ $this, 'enqueue_preview_scripts' ] );

		// Creates the Scroll Flow tab at the end of layout/content & advanced section tab.
		// Container.
		if ( defined( 'ELEMENTOR_VERSION' ) && ha_elementor()->experiments->is_feature_active( 'container' ) && ha_is_scroll_flow_enabled() ) {
			add_action( 'elementor/element/container/section_layout/after_section_end', [ $this, 'register_controls' ], 1 );
		}

		// Section (legacy).
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'register_controls' ], 1 );

		add_action( 'elementor/frontend/container/before_render', [ $this, 'before_render' ] );
		add_action( 'elementor/frontend/section/before_render', [ $this, 'before_render' ] );
	}

	/**
	 * Register scripts.
	 *
	 * @return void
	 */
	public function register_scripts() {
		$suffix = ha_is_script_debug_enabled() ? '.' : '.min.';

		wp_register_script(
			'happy-scroll-flow',
			HAPPY_ADDONS_ASSETS . 'js/scroll-flow' . $suffix . 'js',
			[ 'jquery', 'happy-elementor-addons', 'gsap', 'observer' ],
			HAPPY_ADDONS_VERSION,
			true
		);
	}

	/**
	 * Register styles.
	 *
	 * @return void
	 */
	public function register_styles() {
		wp_register_style(
			'happy-scroll-flow',
			HAPPY_ADDONS_ASSETS . 'css/widgets/scroll-flow.min.css',
			[],
			HAPPY_ADDONS_VERSION
		);
	}

	/**
	 * Enqueue scripts/styles inside the editor preview.
	 *
	 * @return void
	 */
	public function enqueue_preview_scripts() {
		wp_enqueue_script( 'gsap' );
		wp_enqueue_script( 'observer' );
		wp_enqueue_script( 'happy-scroll-flow' );

		wp_enqueue_style( 'happy-scroll-flow' );
	}

	/**
	 * Register the controls section.
	 *
	 * @param \Elementor\Element_Base $element Elementor element instance.
	 * @return void
	 */
	public function register_controls( $element ) {

		// Containers register their `.section_layout` in the Advanced tab (and
		// legacy sections their `.section_advanced`), so the panel must live in
		// the Advanced tab to appear right after it.
		$element->start_controls_section(
			'_ha_sf_section',
			[
				'label' => esc_html__( 'Scroll Flow', 'happy-elementor-addons' ) . ha_get_section_icon(),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'ha_sf_help_url_notice_box',
			[
				'type'        => Controls_Manager::NOTICE,
				'notice_type' => 'info',
				'content'     => sprintf(
					esc_html__( 'Need help? %s', 'happy-elementor-addons' ),
					'<a href="https://happyaddons.com/docs/happy-addons-for-elementor/happy-features/#/" target="_blank" rel="noopener noreferrer">'
					. esc_html__( 'Read Documentation', 'happy-elementor-addons' )
					. '</a>'
				),
			]
		);

		$this->add_content_controls( $element );
		$this->add_animation_controls( $element );

		$element->end_controls_section();
	}

	/**
	 * Primary controls.
	 *
	 * @param \Elementor\Element_Base $element Elementor element instance.
	 * @return void
	 */
	public function add_content_controls( $element ) {

		$element->add_control(
			'ha_sf_switcher',
			[
				'label'              => __( 'Enable', 'happy-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'prefix_class'       => 'ha-sf-',
				'render_type'        => 'none',
				'return_value'       => 'yes',
				'style_transfer'     => false,
				'frontend_available' => true,
				'description'        => __( 'Converts the direct child containers of this container into animated full-height panels.', 'happy-elementor-addons' ),
				'assets'             => [
					'scripts' => [
						[
							'name'       => 'elementor-frontend',
							'conditions' => $this->get_asset_condition(),
						],
						[
							'name'       => 'gsap',
							'conditions' => $this->get_asset_condition(),
						],
						[
							'name'       => 'observer',
							'conditions' => $this->get_asset_condition(),
						],
						[
							'name'       => 'happy-scroll-flow',
							'conditions' => $this->get_asset_condition(),
						],
					],
					'styles'  => [
						[
							'name'       => 'happy-scroll-flow',
							'conditions' => $this->get_asset_condition(),
						],
					],
				],
			]
		);

		$element->add_responsive_control(
			'ha_sf_mode',
			[
				'label'              => __( 'Mode', 'happy-elementor-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'slide',
				'options'            => [
					'slide' => __( 'Slide', 'happy-elementor-addons' ),
					'fade'  => __( 'Fade', 'happy-elementor-addons' ),
					'zoom'  => __( 'Zoom', 'happy-elementor-addons' ),
				],
				'condition'          => [ 'ha_sf_switcher' => 'yes' ],
				'render_type'        => 'none',
				'frontend_available' => true,
				'style_transfer'     => true,
			]
		);

		$element->add_control(
			'ha_sf_trigger_point',
			[
				'label'              => __( 'Trigger Point (px)', 'happy-elementor-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => -1000,
				'max'                => 2000,
				'step'               => 1,
				'default'            => 0,
				'description'        => __( 'Offset from the viewport top where Scroll Flow starts.', 'happy-elementor-addons' ),
				'condition'          => [ 'ha_sf_switcher' => 'yes' ],
				'render_type'        => 'none',
				'frontend_available' => true,
				'style_transfer'     => true,
			]
		);
	}

	/**
	 * Transition animation controls.
	 *
	 * @param \Elementor\Element_Base $element Elementor element instance.
	 * @return void
	 */
	protected function add_animation_controls( $element ) {

		$element->add_control(
			'ha_sf_duration',
			[
				'label'              => __( 'Duration (s)', 'happy-elementor-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0.1,
				'max'                => 5,
				'step'               => 0.05,
				'default'            => 1.25,
				'condition'          => [ 'ha_sf_switcher' => 'yes' ],
				'render_type'        => 'none',
				'frontend_available' => true,
				'style_transfer'     => true,
			]
		);

		$element->add_control(
			'ha_sf_bg_move',
			[
				'label'              => __( 'Background Move (%)', 'happy-elementor-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'max'                => 60,
				'step'               => 1,
				'default'            => 15,
				'description'        => __( 'Parallax distance of the panel background image during a transition. Set to 0 to disable parallax.', 'happy-elementor-addons' ),
				'condition'          => [ 'ha_sf_switcher' => 'yes' ],
				'render_type'        => 'none',
				'frontend_available' => true,
				'style_transfer'     => true,
			]
		);

        $element->add_control(
			'ha_sf_wheel_speed',
			[
				'label'              => __( 'Wheel Speed', 'happy-elementor-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => -3,
				'max'                => 3,
				'step'               => 1,
				'default'            => -1,
				'description'        => __( 'Native wheel delta multiplier. Negative values invert the wheel direction. Default: -1.', 'happy-elementor-addons' ),
				'condition'          => [ 'ha_sf_switcher' => 'yes' ],
				'render_type'        => 'none',
				'frontend_available' => true,
				'style_transfer'     => true,
			]
		);

		$element->add_control(
			'ha_sf_tolerance',
			[
				'label'              => __( 'Tolerance', 'happy-elementor-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'max'                => 50,
				'step'               => 1,
				'default'            => 10,
				'description'        => __( 'Minimum wheel/touch distance required before a transition fires. Higher means less sensitive.', 'happy-elementor-addons' ),
				'condition'          => [ 'ha_sf_switcher' => 'yes' ],
				'render_type'        => 'none',
				'frontend_available' => true,
				'style_transfer'     => true,
			]
		);

		$element->add_control(
			'ha_sf_loop',
			[
				'label'              => __( 'Loop', 'happy-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'yes',
				'default'            => 'no',
				'description'        => __( 'When enabled, scrolling past the last panel wraps to the first (and vice versa).', 'happy-elementor-addons' ),
				'condition'          => [ 'ha_sf_switcher' => 'yes' ],
				'render_type'        => 'none',
				'frontend_available' => true,
				'style_transfer'     => true,
			]
		);

		$element->add_control(
			'ha_sf_enable_on_mobile',
			[
				'label'              => __( 'Enable On Mobile', 'happy-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => __( 'Yes', 'happy-elementor-addons' ),
				'label_off'          => __( 'No', 'happy-elementor-addons' ),
				'return_value'       => 'yes',
				'default'            => 'no',
				'render_type'        => 'none',
				'frontend_available' => true,
				'description'        => __( 'When disabled, the Scroll Flow will not run on mobile devices.', 'happy-elementor-addons' ),
				'condition'          => [ 'ha_sf_switcher' => 'yes' ],
			]
		);

        $element->add_control(
			'ha_sf_easing_function',
			[
				'label'              => __( 'Easing Function', 'happy-elementor-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'power1.inOut',
				'options'            => $this->get_easing_options(),
				'condition'          => [ 'ha_sf_switcher' => 'yes' ],
				'render_type'        => 'none',
				'frontend_available' => true,
				'style_transfer'     => true,
			]
		);

	}

	/**
	 * Add the sanitized wrapper attributes on the frontend.
	 *
	 * @param \Elementor\Element_Base $element Elementor element instance.
	 * @return void
	 */
	public function before_render( $element ) {

		$settings = $element->get_settings_for_display();

		if ( empty( $settings['ha_sf_switcher'] ) || 'yes' !== $settings['ha_sf_switcher'] ) {
			return;
		}

		$element->add_render_attribute(
			'_wrapper',
			[
				'class'           => 'ha-sf-wrapper',
				'data-ha-sf'      => '1',
				'data-ha-sf-data' => wp_json_encode( $this->get_frontend_settings( $settings ) ),
			]
		);
	}

	/**
	 * Build the sanitized settings payload exposed to the frontend.
	 *
	 * @param array $settings Element settings.
	 * @return array
	 */
	protected function get_frontend_settings( $settings ) {

		$allowed_modes = [ 'slide', 'fade', 'zoom' ];
		$allowed_eases = array_keys( $this->get_easing_options() );

		$mode = isset( $settings['ha_sf_mode'] ) ? sanitize_key( $settings['ha_sf_mode'] ) : 'slide';

		if ( ! in_array( $mode, $allowed_modes, true ) ) {
			$mode = 'slide';
		}

		$ease = isset( $settings['ha_sf_easing_function'] ) ? sanitize_text_field( $settings['ha_sf_easing_function'] ) : 'power1.inOut';

		if ( ! in_array( $ease, $allowed_eases, true ) ) {
			$ease = 'power1.inOut';
		}

		return [
			'mode'           => $mode,
			'duration'       => $this->clamp_number( $settings['ha_sf_duration'] ?? 1.25, 0.1, 5, 1.25 ),
			'ease'           => $ease,
			'bgMove'         => $this->clamp_number( $settings['ha_sf_bg_move'] ?? 15, 0, 60, 15 ),
			'wheelSpeed'     => $this->clamp_number( $settings['ha_sf_wheel_speed'] ?? -1, -3, 3, -1 ),
			'tolerance'      => $this->clamp_number( $settings['ha_sf_tolerance'] ?? 10, 0, 50, 10 ),
			'triggerPoint'   => $this->clamp_number( $settings['ha_sf_trigger_point'] ?? 0, -1000, 2000, 0 ),
			'loop'           => ( ! isset( $settings['ha_sf_loop'] ) || 'yes' === $settings['ha_sf_loop'] ) ? 'yes' : 'no',
			'enableOnMobile' => ( isset( $settings['ha_sf_enable_on_mobile'] ) && 'yes' === $settings['ha_sf_enable_on_mobile'] ) ? 'yes' : 'no',
		];
	}

	/**
	 * Available GSAP easing curves.
	 *
	 * @return array
	 */
	protected function get_easing_options() {
		return [
			'none'          => __( 'None (Linear)', 'happy-elementor-addons' ),
			'power1.inOut'  => __( 'Power1 InOut', 'happy-elementor-addons' ),
			'power2.inOut'  => __( 'Power2 InOut', 'happy-elementor-addons' ),
			'power3.inOut'  => __( 'Power3 InOut', 'happy-elementor-addons' ),
			'power4.inOut'  => __( 'Power4 InOut', 'happy-elementor-addons' ),
			'power1.in'     => __( 'Power1 In', 'happy-elementor-addons' ),
			'power2.in'     => __( 'Power2 In', 'happy-elementor-addons' ),
			'power3.in'     => __( 'Power3 In', 'happy-elementor-addons' ),
			'power4.in'     => __( 'Power4 In', 'happy-elementor-addons' ),
			'power1.out'    => __( 'Power1 Out', 'happy-elementor-addons' ),
			'power2.out'    => __( 'Power2 Out', 'happy-elementor-addons' ),
			'power3.out'    => __( 'Power3 Out', 'happy-elementor-addons' ),
			'power4.out'    => __( 'Power4 Out', 'happy-elementor-addons' ),
			'sine.inOut'    => __( 'Sine InOut', 'happy-elementor-addons' ),
			'expo.inOut'    => __( 'Expo InOut', 'happy-elementor-addons' ),
			'circ.inOut'    => __( 'Circ InOut', 'happy-elementor-addons' ),
			'circ.in'       => __( 'Circ In', 'happy-elementor-addons' ),
			'circ.out'      => __( 'Circ Out', 'happy-elementor-addons' ),
			'back.in'       => __( 'Back In', 'happy-elementor-addons' ),
			'back.out'      => __( 'Back Out', 'happy-elementor-addons' ),
			'back.inOut'    => __( 'Back InOut', 'happy-elementor-addons' ),
			'elastic.in'    => __( 'Elastic In', 'happy-elementor-addons' ),
			'elastic.out'   => __( 'Elastic Out', 'happy-elementor-addons' ),
			'elastic.inOut' => __( 'Elastic InOut', 'happy-elementor-addons' ),
			'bounce.in'     => __( 'Bounce In', 'happy-elementor-addons' ),
			'bounce.out'    => __( 'Bounce Out', 'happy-elementor-addons' ),
			'bounce.inOut'  => __( 'Bounce InOut', 'happy-elementor-addons' ),
		];
	}

	/**
	 * Shared assets condition.
	 *
	 * @return array
	 */
	protected function get_asset_condition() {
		return [
			'terms' => [
				[
					'name'     => 'ha_sf_switcher',
					'operator' => '===',
					'value'    => 'yes',
				],
			],
		];
	}

	/**
	 * Clamp a numeric value between a minimum and maximum.
	 *
	 * @param mixed $value    Raw value.
	 * @param float $min      Minimum allowed value.
	 * @param float $max      Maximum allowed value.
	 * @param float $fallback Fallback when the value is not numeric.
	 * @return float
	 */
	protected function clamp_number( $value, $min, $max, $fallback ) {
		if ( ! is_numeric( $value ) ) {
			return $fallback;
		}

		return max( (float) $min, min( (float) $max, (float) $value ) );
	}
}
