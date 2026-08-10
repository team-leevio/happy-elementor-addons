<?php

namespace Happy_Addons\Elementor\Extensions;

// Elementor Classes.
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die();

class Appearing_Image_Animation {

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
        add_action( 'wp_enqueue_scripts', [$this, 'register_scripts'] );
        add_action( 'wp_enqueue_scripts', [$this, 'register_styles'] );

        add_action( 'elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_scripts'] );

        // get Image Widgets
        $imageWidgets = $this->get_image_widgets();

        // Creates image reveal tab at the end of EL Image Widget.
        if ( $imageWidgets ) {
            foreach ( $imageWidgets as $items ) {

                add_action( 'elementor/element/' . $items['name'] . '/' . $items['section'] . '/after_section_end', [$this, 'register_controls'] );
            }
        }
    }

    // Register Scripts
    public function register_scripts() {
        $suffix = ha_is_script_debug_enabled() ? '.' : '.min.';

        wp_register_script(
            'happy-appearing-image-animation',
            HAPPY_ADDONS_ASSETS . 'js/appearing-image-animation' . $suffix . 'js',
            ['jquery', 'happy-elementor-addons'],
            HAPPY_ADDONS_VERSION,
            true
        );
    }

    // Register Styles
    public function register_styles() {
        $suffix = ha_is_script_debug_enabled() ? '.' : '.min.';
        wp_register_style(
            'happy-appearing-image-animation',
            HAPPY_ADDONS_ASSETS . 'css/widgets/appearing-image-animation.min.css',
            [],
            HAPPY_ADDONS_VERSION
        );
    }

    // Enqueue Preview Scripts
    public function enqueue_preview_scripts() {
        wp_enqueue_script( 'gsap' );
        wp_enqueue_script( 'scroll-trigger' );
        wp_enqueue_script( 'happy-appearing-image-animation' );

        wp_enqueue_style( 'happy-appearing-image-animation' );
    }

    public function register_controls( $element ) {

        $element->start_controls_section(
            '_ha_aia_section',
            [
                'label' => esc_html__( 'Appearing Animation', 'happy-elementor-addons' ) . ha_get_section_icon(),
                'tab'   => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->add_content_controls( $element );

        $element->end_controls_section();
    }

    public function add_content_controls( $element ) {

        $element->add_control(
            'ha_aia_switcher',
            [
                'label'              => __( 'Enable', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::SWITCHER,
                'prefix_class'       => 'ha-aia-',
                'render_type'        => 'template',
                'return_value'       => 'yes',
                'style_transfer'     => false,
                'frontend_available' => true,
                'assets'             => [
                    'scripts' => [
                        [
                            'name'       => 'elementor-frontend',
                            'conditions' => [
                                'terms' => [
                                    [
                                        'name'     => 'ha_aia_switcher',
                                        'operator' => '===',
                                        'value'    => 'yes'
                                    ]
                                ]
                            ]
                        ],
                        [
                            'name'       => 'gsap',
                            'conditions' => [
                                'terms' => [
                                    [
                                        'name'     => 'ha_aia_switcher',
                                        'operator' => '===',
                                        'value'    => 'yes'
                                    ]
                                ]
                            ]
                        ],
                        [
                            'name'       => 'scroll-trigger',
                            'conditions' => [
                                'terms' => [
                                    [
                                        'name'     => 'ha_aia_switcher',
                                        'operator' => '===',
                                        'value'    => 'yes'
                                    ]
                                ]
                            ]
                        ],
                        [
                            'name'       => 'happy-appearing-image-animation',
                            'conditions' => [
                                'terms' => [
                                    [
                                        'name'     => 'ha_aia_switcher',
                                        'operator' => '===',
                                        'value'    => 'yes'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'styles'  => [
                        [
                            'name'       => 'happy-appearing-image-animation',
                            'conditions' => [
                                'terms' => [
                                    [
                                        'name'     => 'ha_aia_switcher',
                                        'operator' => '===',
                                        'value'    => 'yes'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );

        $element->add_responsive_control(
            'ha_aia_mode',
            [
                'label'              => __( 'Mode', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::SELECT,
                'default'            => 'reveal',
                'options'            => [
                    'reveal'        => __( 'Reveal', 'happy-elementor-addons' ),
                    'tiles-reveal'  => __( 'Tiles Reveal', 'happy-elementor-addons' ),
                    'corner-reveal' => __( 'Corner Reveal', 'happy-elementor-addons' ),
                    'scale'         => __( 'Scale', 'happy-elementor-addons' ),
                    'stretch'       => __( 'Stretch', 'happy-elementor-addons' )
                ],
                'condition'          => [
                    'ha_aia_switcher' => 'yes'
                ],
                'render_type'        => 'template',
                'frontend_available' => true,
                'style_transfer'     => true
            ]
        );

        $element->add_responsive_control(
            'ha_aia_rs_direction',
            [
                'label'              => __( 'Direction', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::CHOOSE,
                'default'            => 'left',
                'options'            => [
                    'left'   => [
                        'title' => __( 'Left', 'happy-elementor-addons' ),
                        'icon'  => 'eicon-h-align-left'
                    ],
                    'top'    => [
                        'title' => __( 'Top', 'happy-elementor-addons' ),
                        'icon'  => 'eicon-v-align-bottom'
                    ],
                    'right'  => [
                        'title' => __( 'Right', 'happy-elementor-addons' ),
                        'icon'  => 'eicon-h-align-right'
                    ],
                    'bottom' => [
                        'title' => __( 'Bottom', 'happy-elementor-addons' ),
                        'icon'  => 'eicon-v-align-top'
                    ]
                ],
                'condition'          => [
                    'ha_aia_switcher' => 'yes',
                    // 'ha_aia_mode'     => ['reveal', 'stretch']
                    'ha_aia_mode'     => ['reveal']
                ],

                'toggle'             => false,
                'render_type'        => 'template',
                'frontend_available' => true,
                'style_transfer'     => true,
            ]
        );

        $this->tiles_mode_controls( $element );
        $this->corner_mode_controls( $element );
        $this->scale_mode_controls( $element );
        $this->common_controls( $element );
    }

    protected function tiles_mode_controls( $element ) {

        $element->add_responsive_control(
            'ha_aia_tiles_count',
            [
                'label'              => __( 'Number of Tiles', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::NUMBER,
                'min'                => 2,
                'max'                => 50,
                'step'               => 1,
                'default'            => 5,
                'render_type'        => 'template',
                'frontend_available' => true,
                'style_transfer'     => true,
                'condition'          => [
                    'ha_aia_switcher' => 'yes',
                    'ha_aia_mode'     => 'tiles-reveal'
                ]
            ]
        );

        /*$element->add_control(
        'ha_aia_tiles_color',
        [
        'label'              => __( 'Tiles Color', 'happy-elementor-addons' ),
        'type'               => Controls_Manager::COLOR,
        'default'            => 'transparent',
        'condition'          => [
        'ha_aia_switcher' => 'yes',
        'ha_aia_mode'     => 'tiles-reveal'
        ],
        'render_type'        => 'template',
        'toggle'             => false,
        'frontend_available' => true
        ]
        );*/

        $element->add_responsive_control(
            'ha_aia_tiles_orientation',
            [
                'label'              => __( 'Orientation', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::SELECT,
                'default'            => 'horizontal',
                'options'            => [
                    'horizontal' => __( 'Horizontal', 'happy-elementor-addons' ),
                    'vertical'   => __( 'Vertical', 'happy-elementor-addons' )
                ],
                'condition'          => [
                    'ha_aia_switcher' => 'yes',
                    'ha_aia_mode'     => 'tiles-reveal'
                ],
                'render_type'        => 'template',
                'toggle'             => false,
                'frontend_available' => true
            ]
        );

        $element->add_responsive_control(
            'ha_aia_tiles_horizontal_direction',
            [
                'label'              => __( 'Direction', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::CHOOSE,
                'default'            => 'bottom-to-top',
                'options'            => [
                    'bottom-to-top' => [
                        'title' => __( 'Bottom to Top', 'happy-elementor-addons' ),
                        'icon'  => 'eicon-v-align-top'
                    ],
                    'top-to-bottom' => [
                        'title' => __( 'Top to Bottom', 'happy-elementor-addons' ),
                        'icon'  => 'eicon-v-align-bottom'
                    ]
                ],
                'condition'          => [
                    'ha_aia_switcher'          => 'yes',
                    'ha_aia_mode'              => 'tiles-reveal',
                    'ha_aia_tiles_orientation' => 'horizontal'
                ],
                'toggle'             => false,
                'frontend_available' => true,
                'render_type'        => 'template'
            ]
        );

        $element->add_responsive_control(
            'ha_aia_tiles_vertical_direction',
            [
                'label'              => __( 'Direction', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::CHOOSE,
                'default'            => 'left-to-right',
                'options'            => [
                    'left-to-right' => [
                        'title' => __( 'Left to Right', 'happy-elementor-addons' ),
                        'icon'  => 'eicon-h-align-left'
                    ],
                    'right-to-left' => [
                        'title' => __( 'Right to Left', 'happy-elementor-addons' ),
                        'icon'  => 'eicon-h-align-right'
                    ]

                ],
                'condition'          => [
                    'ha_aia_switcher'          => 'yes',
                    'ha_aia_mode'              => 'tiles-reveal',
                    'ha_aia_tiles_orientation' => 'vertical'
                ],
                'toggle'             => false,
                'frontend_available' => true,
                'render_type'        => 'template'
            ]
        );

        $element->add_responsive_control(
            'ha_aia_tiles_stagger_delay',
            [
            'label'              => __( 'Stagger Delay(s)', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::NUMBER,
                'min'                => 0.01,
                'max'                => 5,
                'step'               => 0.01,
                'default'            => 0.08,
                'render_type'        => 'template',
                'frontend_available' => true,
                'style_transfer'     => true,
                'condition'          => [
                    'ha_aia_switcher' => 'yes',
                    'ha_aia_mode'     => 'tiles-reveal'
                ]
            ]
        );
    }

    protected function corner_mode_controls( $element ) {

        $element->add_responsive_control(
            'ha_aia_corner_direction',
            [
                'label'              => __( 'Direction', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::SELECT,
                'default'            => 'top-right',
                'options'            => [
                    'top-left'     => __( 'Top Left', 'happy-elementor-addons' ),
                    'top-right'    => __( 'Top Right', 'happy-elementor-addons' ),
                    'center'       => __( 'Center', 'happy-elementor-addons' ),
                    'bottom-left'  => __( 'Bottom Left', 'happy-elementor-addons' ),
                    'bottom-right' => __( 'Bottom Right', 'happy-elementor-addons' )
                ],
                'condition'          => [
                    'ha_aia_switcher' => 'yes',
                    'ha_aia_mode'     => ['corner-reveal']
                ],
                'render_type'        => 'template',
                'frontend_available' => true,
                'style_transfer'     => true
            ]
        );

    }

    protected function scale_mode_controls( $element ) {

        $element->add_responsive_control(
            'ha_aia_scale_from',
            [
                'label'              => __( 'Scale From', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::NUMBER,
                'min'                => 0,
                'max'                => 10,
                'step'               => 0.1,
                'default'            => 0.5,
                'render_type'        => 'template',
                'frontend_available' => true,
                'style_transfer'     => true,
                'condition'          => [
                    'ha_aia_switcher' => 'yes',
                    'ha_aia_mode'     => 'scale'
                ]
            ]
        );

        $element->add_responsive_control(
            'ha_aia_scale_to',
            [
                'label'              => __( 'Scale To', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::NUMBER,
                'min'                => 0,
                'max'                => 10,
                'step'               => 0.1,
                'default'            => 1,
                'render_type'        => 'template',
                'frontend_available' => true,
                'style_transfer'     => true,
                'condition'          => [
                    'ha_aia_switcher' => 'yes',
                    'ha_aia_mode'     => 'scale'
                ]
            ]
        );
    }

    protected function common_controls( $element ) {
        $element->add_responsive_control(
            'ha_aia_trigger_point',
            [
                'label'              => __( 'Trigger Point', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::SELECT,
                'default'            => 'top-center',
                'options'            => [
                    'top-top'       => __( 'Top - Top', 'happy-elementor-addons' ),
                    'top-center'    => __( 'Top - Center', 'happy-elementor-addons' ),
                    'top-bottom'    => __( 'Top - Bottom', 'happy-elementor-addons' ),
                    'center-top'    => __( 'Center - Top', 'happy-elementor-addons' ),
                    'center-center' => __( 'Center - Center', 'happy-elementor-addons' ),
                    'center-bottom' => __( 'Center - Bottom', 'happy-elementor-addons' ),
                    'bottom-top'    => __( 'Bottom - Top', 'happy-elementor-addons' ),
                    'bottom-center' => __( 'Bottom - Center', 'happy-elementor-addons' ),
                    'bottom-bottom' => __( 'Bottom - Bottom', 'happy-elementor-addons' ),
                    'custom'        => __( 'Custom', 'happy-elementor-addons' )
                ],
                'description'        => __(
                    'First value is the element position, second is the viewport position.',
                    'happy-elementor-addons'
                ),
                'condition'          => [
                    'ha_aia_switcher' => 'yes',
                    'ha_aia_mode!'     => ['stretch']
                ],
                'render_type'        => 'template',
                'frontend_available' => true,
                'style_transfer'     => true
            ]
        );

        $element->add_responsive_control(
            'ha_aia_custom_trigger_start',
            [
                'label'              => __( 'Custom Trigger(Start)', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::TEXT,
                'default'            => 'top 80%',
                'placeholder'        => __( 'e.g., top 80%', 'happy-elementor-addons' ),
                'render_type'        => 'template',
                'frontend_available' => true,
                'condition'          => [
                    'ha_aia_switcher'      => 'yes',
                    'ha_aia_trigger_point' => 'custom'

                ]
            ]
        );

        $element->add_responsive_control(
            'ha_aia_animation_duration',
            [
                'label'              => __( 'Duration(s)', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::NUMBER,
                'min'                => 0.1,
                'max'                => 10,
                'step'               => 0.1,
                'default'            => 1.2,
                'render_type'        => 'template',
                'frontend_available' => true,
                'style_transfer'     => true,
                'condition'          => [
                    'ha_aia_switcher' => 'yes',
                    'ha_aia_mode!'     => ['stretch']
                ]
            ]
        );

        $element->add_responsive_control(
            'ha_aia_delay',
            [
                'label'              => __( 'Delay(s)', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::NUMBER,
                'min'                => 0,
                'max'                => 10,
                'step'               => 0.1,
                'default'            => 0,
                'render_type'        => 'template',
                'frontend_available' => true,
                'style_transfer'     => true,
                'condition'          => [
                    'ha_aia_switcher' => 'yes',
                    'ha_aia_mode!'     => ['stretch']
                ]
            ]
        );

        /*$element->add_control(
        'ha_aia_overlay_color',
        [
        'label'              => __( 'Overlay Color', 'happy-elementor-addons' ),
        'type'               => Controls_Manager::COLOR,
        'render_type'        => 'template',
        'frontend_available' => true,
        'style_transfer'     => true,
        'condition'          => [
        'ha_aia_switcher' => 'yes',
        'ha_aia_mode!'    => 'tiles-reveal'
        ]
        ]
        );*/

        $element->add_responsive_control(
            'ha_aia_easing_function',
            [
                'label'              => __( 'Easing Functions', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::SELECT,
                'default'            => 'power4.inOut',
                'options'            => [
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
                    'back.in'       => __( 'Back In', 'happy-elementor-addons' ),
                    'back.out'      => __( 'Back Out', 'happy-elementor-addons' ),
                    'back.inOut'    => __( 'Back InOut', 'happy-elementor-addons' ),
                    'elastic.in'    => __( 'Elastic In', 'happy-elementor-addons' ),
                    'elastic.out'   => __( 'Elastic Out', 'happy-elementor-addons' ),
                    'elastic.inOut' => __( 'Elastic InOut', 'happy-elementor-addons' ),
                    'bounce.in'     => __( 'Bounce In', 'happy-elementor-addons' ),
                    'bounce.out'    => __( 'Bounce Out', 'happy-elementor-addons' ),
                    'bounce.inOut'  => __( 'Bounce InOut', 'happy-elementor-addons' )
                ],
                'condition'          => [
                    'ha_aia_switcher' => 'yes',
                    'ha_aia_mode!'     => ['stretch']
                ],
                'render_type'        => 'template',
                'frontend_available' => true,
                'style_transfer'     => true
            ]
        );

        $element->add_control(
            'ha_aia_enable_on_mobile',
            [
                'label'              => __( 'Enable On Mobile', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::SWITCHER,
                'label_on'           => __( 'Yes', 'happy-elementor-addons' ),
                'label_off'          => __( 'No', 'happy-elementor-addons' ),
                'return_value'       => 'yes',
                'default'            => 'no',
                'render_type'        => 'template',
                'frontend_available' => true,
                'condition'          => [
                    'ha_aia_switcher' => 'yes',
                ],
                'description'        => __( 'When disabled, the animation will not run on mobile devices.', 'happy-elementor-addons' ),
            ]
        );
    }

    protected function get_image_widgets() {
        return $imageWidgets = [
            [
                'name'    => 'image',
                'section' => 'section_image'
            ]
        ];
    }
}