<?php

namespace Happy_Addons\Elementor\Extensions;

// Elementor Classes.
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;

defined('ABSPATH') || die();

class Background_Parallax {

	/**
	 * @var mixed
	 */
	private static $instance = null;

	/**
	 * @var mixed
	 */
	private $load_script = null;

	public static function instance() {
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function init() {

		add_action( 'elementor/frontend/before_register_scripts', [ $this, 'register_scripts' ] );
		add_action( 'elementor/frontend/before_register_styles', [ $this, 'register_styles' ] );
		add_action( 'elementor/preview/enqueue_scripts', [ $this, 'enqueue_preview_scripts' ] );

		add_action('elementor/element/section/section_layout/after_section_end', [ $this, 'register_controls' ], 10);
		add_action('elementor/element/container/section_layout/after_section_end', [ $this, 'register_controls' ], 10);

		add_action( 'elementor/frontend/section/before_render', [ $this, 'before_render' ], 10, 1 );
		add_action( 'elementor/frontend/container/before_render', [ $this, 'before_render' ], 10, 1 );

		add_action( 'elementor/section/print_template', array( $this, 'editor_template_print' ), 10, 2 );
		add_action( 'elementor/container/print_template', array( $this, 'editor_template_print' ), 10, 2 );
	}

	public function register_scripts() {
		$suffix = ha_is_script_debug_enabled() ? '.' : '.min.';

		wp_register_script(
			'jarallax-js',
			HAPPY_ADDONS_ASSETS . 'vendor/jarallax/jarallax.min.js',
			null,
			HAPPY_ADDONS_VERSION,
			true
		);

		wp_register_script(
			'happy-background-parallax',
			HAPPY_ADDONS_ASSETS . 'js/background-parallax' . $suffix . 'js',
			[ 'jquery', 'happy-elementor-addons' ],
			HAPPY_ADDONS_VERSION,
			true
		);

	}

	public function register_styles() {
		wp_register_style(
			'jarallax-css',
			HAPPY_ADDONS_ASSETS . 'vendor/jarallax/jarallax.min.css',
			[],
			HAPPY_ADDONS_VERSION
		);
	}

	public function enqueue_preview_scripts() {
		wp_enqueue_style( 'jarallax-css' );
		wp_enqueue_script( 'jarallax-js' );
		wp_enqueue_script( 'happy-background-parallax' );
	}

	/**
	 * Register Parallax controls.
	 */
	public function register_controls( $element ) {

		$element->start_controls_section(
			'section_ha_bg_parallax',
			[
				'label' => ( __( 'Background Parallax', 'happy-elementor-addons') ) . ha_get_section_icon(),
				'tab'   => Controls_Manager::TAB_LAYOUT,
			]
		);

		$element->add_control(
			'ha_bg_parallax_switcher',
			[
				'label'        => __( 'Enable BG Parallax', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'prefix_class' => 'ha-bg-parallax-',
				'render_type'  => 'template',
				'style_transfer' => false,
				'frontend_available' => true,
				'assets' => [
					'scripts' => [
						[
							'name' => 'jarallax-js',
							'conditions' => [
								'terms' => [
									[
										'name' => 'ha_bg_parallax_switcher',
										'operator' => '===',
										'value' => 'yes',
									],
								],
							],
						],
						[
							'name' => 'happy-background-parallax',
							'conditions' => [
								'terms' => [
									[
										'name' => 'ha_bg_parallax_switcher',
										'operator' => '===',
										'value' => 'yes',
									],
								],
							],
						]
					],
					'styles' => [
						[
							'name' => 'jarallax-css',
							'conditions' => [
								'terms' => [
									[
										'name' => 'ha_bg_parallax_switcher',
										'operator' => '===',
										'value' => 'yes',
									]
								]
							]
						]
					]
				],
			]
		);

		$element->add_control(
			'ha_bg_parallax_update',
			[
				'label' => __( 'Apply Button', 'happy-elementor-addons' ),
				'show_label' => false,
				'type'  => Controls_Manager::RAW_HTML,
				'raw' => '<div class="elementor-update-preview" style="margin: 0 0 8px 0"><div class="elementor-update-preview-title">' . __( 'Update changes to the page', 'happy-elementor-addons' ) . '</div><div class="elementor-update-preview-button-wrapper"><button class="elementor-update-preview-button elementor-button elementor-button-success" style="background-image: linear-gradient(90deg, #e2498a 0%, #562dd4 100%);">' . __( 'Apply', 'happy-elementor-addons' ) . '</button></div></div>',
                'condition' => [
                    'ha_bg_parallax_switcher' => 'yes',
                ],
			]
		);

		$element->add_control(
			'ha_bg_parallax_type',
			[
				'label'       => __( 'Type', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'scroll'         => __( 'Scroll', 'happy-elementor-addons' ),
					'scroll-opacity' => __( 'Scroll with opacity', 'happy-elementor-addons' ),
					'opacity'        => __( 'Opacity', 'happy-elementor-addons' ),
					'scale'          => __( 'Scale', 'happy-elementor-addons' ),
					'scale-opacity'  => __( 'Scale with opacity', 'happy-elementor-addons' ),
					'automove'       => __( 'Auto Moving', 'happy-elementor-addons' ),
				],
				'label_block' => 'true',
				'render_type' => 'template',
				'condition'   => [
					'ha_bg_parallax_switcher' => 'yes'
				],
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'ha_bg_parallax_automove_direction',
			[
				'label'              => __( 'Direction', 'happy-elementor-addons' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => [
					'left'   => __( 'Left to Right', 'happy-elementor-addons' ),
					'right'  => __( 'Right to Left', 'happy-elementor-addons' ),
					'top'    => __( 'Top to Bottom', 'happy-elementor-addons' ),
					'bottom' => __( 'Bottom to Top', 'happy-elementor-addons' )
				],
				'default'            => 'left',
				'condition'          => [
					'ha_bg_parallax_switcher' => 'yes',
					'ha_bg_parallax_type'     => 'automove'
				],
				'frontend_available' => true
			]
		);

		$element->add_control(
			'ha_bg_parallax_speed',
			[
				'label'     => __( 'Speed', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => -1,
						'max'  => 2,
						'step' => 0.1
					]
				],
				'condition' => [
					'ha_bg_parallax_switcher' => 'yes',
					'ha_bg_parallax_type!'    => 'automove'
				],
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'ha_bg_parallax_automove_speed',
			[
				'label'              => __( 'Speed', 'happy-elementor-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 3,
				'min'                => 0,
				'max'                => 150,
				'description'        => __( 'Set the speed of background movement, default: 3', 'happy-elementor-addons' ),
				'condition'          => [
					'ha_bg_parallax_switcher' => 'yes',
					'ha_bg_parallax_type'     => 'automove'
				],
				'frontend_available' => true
			]
		);

		$element->add_control(
			'ha_bg_parallax_enable_on_android',
			[
				'label'     => __( 'Enable on Android', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'ha_bg_parallax_switcher' => 'yes',
					'ha_bg_parallax_type!'    => 'automove'
				],
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'ha_bg_parallax_enable_on_ios',
			[
				'label'     => __( 'Enable on iOS', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'ha_bg_parallax_switcher' => 'yes',
					'ha_bg_parallax_type!'    => 'automove'
				],
				'frontend_available' => true,
			]
		);

		$element->end_controls_section();
	}

	/**
	 * Render Parallax output on the frontend.
	 */
	public function before_render( $element ) {
		$settings = $element->get_settings_for_display();
		$is_enable = 'yes' === $element->get_settings( 'ha_bg_parallax_switcher' ) ? true : false;
		$bg_parallax_settings = [
			'type' => isset( $settings['ha_bg_parallax_type'] ) ? $settings['ha_bg_parallax_type'] : ''
		];

		if ( isset( $settings['ha_bg_parallax_type'] ) && '' !== $settings['ha_bg_parallax_type'] && $is_enable ) {
			if ( 'automove' !== $bg_parallax_settings['type'] ) {
				$element->add_render_attribute( '_wrapper', 'class', 'ha-bg-parallax-wrap-hide' );
				$speed = ( isset( $settings['ha_bg_parallax_speed']['size'] ) && ! empty( $settings['ha_bg_parallax_speed']['size'] ) ) ? $settings['ha_bg_parallax_speed']['size'] : 0.5;
				$bg_parallax_settings['speed'] = $speed;
				$bg_parallax_settings['android'] ='yes' === $settings['ha_bg_parallax_enable_on_android'] ? 0 : 1;
				$bg_parallax_settings['ios'] = 'yes' === $settings['ha_bg_parallax_enable_on_ios'] ? 0 : 1;
				$bg_parallax_settings['size'] = isset( $settings['background_size'] ) ? $settings['background_size'] : 'cover';
				$bg_parallax_settings['repeat'] = isset( $settings['background_repeat'] ) ? $settings['background_repeat'] : 'no-repeat';

			} elseif ( 'automove' === $bg_parallax_settings['type'] ) {
				$bg_parallax_settings['speed'] = ! empty( $settings['ha_bg_parallax_automove_speed'] ) ? $settings['ha_bg_parallax_automove_speed'] : 3;
				$bg_parallax_settings['direction'] = ! empty( $settings['ha_bg_parallax_automove_direction'] ) ? $settings['ha_bg_parallax_automove_direction'] : 'left';

			}
			$element->add_render_attribute( '_wrapper', 'data-ha-bg-parallax', wp_json_encode( $bg_parallax_settings ) );

		}
	}

	public function editor_template_print( $template, $widget ) {

		if ( ! $template && 'widget' === $widget->get_type() ) {
			return;
		}

		$old_template = $template;
		ob_start();
		?>
		<#
		var isEnabled = 'yes' == settings.ha_bg_parallax_switcher ? true : false;
		if( isEnabled ) {
			var parallaxSettings = {};
			parallaxSettings.type = ( "undefined" !== typeof settings.ha_bg_parallax_type  && settings.ha_bg_parallax_type ) ? settings.ha_bg_parallax_type: '';
			if ( "undefined" !== typeof parallaxSettings.type && "automove" !== parallaxSettings.type ) {
				parallaxSettings.speed    = "" !== settings.ha_bg_parallax_speed.size ? settings.ha_bg_parallax_speed.size : 0.5;
				parallaxSettings.android  = "yes" === settings.ha_bg_parallax_enable_on_android ? 0 : 1;
				parallaxSettings.ios      = "yes" === settings.ha_bg_parallax_enable_on_ios ? 0 : 1;
				parallaxSettings.size     = settings.background_size;
				parallaxSettings.position = settings.background_position;
				parallaxSettings.repeat   = settings.background_repeat;
			} else {
				parallaxSettings.speed     = "" !== settings.ha_bg_parallax_automove_speed ? settings.ha_bg_parallax_automove_speed : 3 ;
				parallaxSettings.direction = "" !== settings.ha_bg_parallax_automove_direction ? settings.ha_bg_parallax_automove_direction : 'left';
			}

			view.addRenderAttribute( 'ha_bg_parallax_data', {
				'id': 'ha-bg-parallax-' + view.getID(),
				'data-ha-bg-parallax': JSON.stringify( parallaxSettings )
			});

		#>
			<div {{{ view.getRenderAttributeString( 'ha_bg_parallax_data' ) }}}></div>
		<# } #>
		<?php

		$parallax_content = ob_get_contents();
		ob_end_clean();
		$template = $parallax_content . $old_template;
		return $template;
	}
}
