<?php
namespace Happy_Addons\Elementor\Extensions;

// Elementor Classes.
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die();

class Heading_Text_Animation {

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

        // get Heading Widgets
        $headingTextWidgets = $this->get_heading_text_widgets();

        // Creates heading text animation tab at the end of EL Heading Widget.
        if ( $headingTextWidgets ) {
            foreach ( $headingTextWidgets as $items ) {

                add_action( 'elementor/element/' . $items['name'] . '/' . $items['section'] . '/after_section_end', [$this, 'register_controls'] );
            }
        }
    }

    // Register Scripts
    public function register_scripts() {
        $suffix = ha_is_script_debug_enabled() ? '.' : '.min.';

        wp_register_script(
            'happy-heading-text-animation',
            HAPPY_ADDONS_ASSETS . 'js/heading-text-animation' . $suffix . 'js',
            ['jquery', 'happy-elementor-addons'],
            HAPPY_ADDONS_VERSION,
            true
        );
    }

    // Register Styles
    public function register_styles() {
        $suffix = ha_is_script_debug_enabled() ? '.' : '.min.';
        wp_register_style(
            'happy-heading-text-animation',
            HAPPY_ADDONS_ASSETS . 'css/widgets/heading-text-animation.min.css',
            [],
            HAPPY_ADDONS_VERSION
        );
    }

    // Enqueue Preview Scripts
    public function enqueue_preview_scripts() {
        wp_enqueue_script( 'gsap' );
        wp_enqueue_script( 'scroll-trigger' );
        wp_enqueue_script( 'happy-heading-text-animation' );

        wp_enqueue_style( 'happy-heading-text-animation' );
    }

    public function register_controls( $element ) {

        $element->start_controls_section(
            '_ha_hta_section',
            [
                'label' => esc_html__( 'Text Animation', 'happy-elementor-addons' ) . ha_get_section_icon(),
                'tab'   => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->add_content_controls( $element );

        $element->end_controls_section();
    }

    public function add_content_controls( $element ) {

        $element->add_control(
            'ha_hta_switcher',
            [
                'label'              => __( 'Enable', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::SWITCHER,
                'prefix_class'       => 'ha-hta-',
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
                                        'name'     => 'ha_hta_switcher',
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
                                        'name'     => 'ha_hta_switcher',
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
                                        'name'     => 'ha_hta_switcher',
                                        'operator' => '===',
                                        'value'    => 'yes'
                                    ]
                                ]
                            ]
                        ],
                        [
                            'name'       => 'happy-heading-text-animation',
                            'conditions' => [
                                'terms' => [
                                    [
                                        'name'     => 'ha_hta_switcher',
                                        'operator' => '===',
                                        'value'    => 'yes'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'styles'  => [
                        [
                            'name'       => 'happy-heading-text-animation',
                            'conditions' => [
                                'terms' => [
                                    [
                                        'name'     => 'ha_hta_switcher',
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

        $element->add_control(
            'ha_hta_mode',
            [
                'label'              => __( 'Animation Mode', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::SELECT,
                'default'            => 'reveal',
                'options'            => [
                    'slide'  => __( 'Slide', 'happy-elementor-addons' ),
                    'reveal' => __( 'Text Reveal', 'happy-elementor-addons' ),
                    'scale'  => __( 'Scale', 'happy-elementor-addons' ),
                    'text_flip' => __( 'Text Flip', 'happy-elementor-addons' ),
                    // 'invert'    => __( 'Text Invert', 'happy-elementor-addons' ),
                    '3dspin'    => __( '3D Spin', 'happy-elementor-addons' ),
                ],
                'condition'          => [
                    'ha_hta_switcher' => 'yes'
                ],
                'render_type'        => 'template',
                'frontend_available' => true,
                'style_transfer'     => true
            ]
        );

        $element->add_control(
            'ha_hta_chars_words_mode',
            [
                'label'              => __( 'Slide Mode', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::SELECT,
                'default'            => 'chars',
                'options'            => [
                    'chars' => __( 'Characters', 'happy-elementor-addons' ),
                    'words' => __( 'Words', 'happy-elementor-addons' )
                ],
                'condition'          => [
                    'ha_hta_switcher' => 'yes',
                    'ha_hta_mode'     => 'slide'
                ],
                'render_type'        => 'template',
                'frontend_available' => true,
                'style_transfer'     => true
            ]
        );

        $element->add_control(
            'ha_hta_trigger_mode',
            [
                'label'              => __( 'Trigger Mode', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::SELECT,
                'default'            => 'scroll',
                'options'            => [
                    'scroll'         => __( 'On Appearing', 'happy-elementor-addons' ),
                    'playwithscroll' => __( 'On Scroll', 'happy-elementor-addons' ),
                    'hover'          => __( 'Hover in Element', 'happy-elementor-addons' )
                ],
                'condition'          => [
                    'ha_hta_switcher' => 'yes'
                ],
                'render_type'        => 'template',
                'frontend_available' => true,
                'style_transfer'     => true
            ]
        );

        $this->chars_mode_controls( $element );
        $this->words_mode_controls( $element );
        $this->chars_words_mode_common_controls( $element );
        $this->reveal_mode_controls( $element );
        $this->scale_mode_controls( $element );
        $this->text_flip_mode_controls( $element );
        $this->invert_mode_controls( $element );
        $this->three_d_mode_controls( $element );
        $this->common_controls( $element );
    }

    protected function chars_mode_controls( $element ) {
        // TODO:: Add Controls
    }

    protected function chars_words_mode_common_controls( $element ) {

        $element->add_control(
            'ha_hta_cw_transform_x',
            [
                'label'              => __( 'Transform X', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::NUMBER,
                'min'                => 0,
                'max'                => 500,
                'step'               => 1,
                'default'            => 25,
                'render_type'        => 'template',
                'frontend_available' => true,
                'style_transfer'     => true,
                'condition'          => [
                    'ha_hta_switcher' => 'yes',
                    'ha_hta_mode'     => 'slide'
                ]
            ]
        );

        $element->add_control(
            'ha_hta_cw_transform_y',
            [
                'label'              => __( 'Transform Y', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::NUMBER,
                'min'                => 0,
                'max'                => 500,
                'step'               => 1,
                'default'            => 0,
                'render_type'        => 'template',
                'frontend_available' => true,
                'style_transfer'     => true,
                'condition'          => [
                    'ha_hta_switcher' => 'yes',
                    'ha_hta_mode'     => 'slide'
                ]
            ]
        );
    }

    protected function words_mode_controls( $element ) {

        // TODO:: Add Controls
    }

    protected function text_flip_mode_controls( $element ) {

        $element->add_control(
            'ha_hta_tv_rotation_direction',
            [
                'label'              => __( 'Rotation Direction', 'happy-addons-pro' ),
                'type'               => Controls_Manager::SELECT,
                'default'            => 'vertical',
                'options'            => [
                    'horizontal' => __( 'Horizontal', 'happy-addons-pro' ),
                    'vertical'   => __( 'Vertical', 'happy-addons-pro' )
                ],
                'render_type'        => 'template',
                'toggle'             => false,
                'frontend_available' => true,
                'condition'          => [
                    'ha_hta_switcher' => 'yes',
                    'ha_hta_mode'     => ['text_flip']
                ]
            ]
        );

        $element->add_control(
            'ha_hta_tv_rotation_value',
            [
                'label'              => __( 'Rotation Value', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::NUMBER,
                'min'                => -500,
                'max'                => 500,
                'step'               => 1,
                'default'            => -80,
                'render_type'        => 'template',
                'frontend_available' => true,
                'style_transfer'     => true,
                'condition'          => [
                    'ha_hta_switcher' => 'yes',
                    'ha_hta_mode'     => ['text_flip']
                ]
            ]
        );
    
        $element->add_control(
            'ha_hta_tv_transform_origin',
            [
                'label'              => __('Transform Origin', 'happy-elementor-addons'),
                'type'               => Controls_Manager::TEXT,
                'default'            => 'top center -50',
                'placeholder'        => __('top center -50', 'happy-elementor-addons'),
                'description' => __('Set the pivot point for the flip animation. Exam: "top center -50", "center center 0", "bottom center -50" to adjust the 3D depth.', 'happy-elementor-addons'),
                'render_type'        => 'template',
                'frontend_available' => true,
                'style_transfer'     => true,
                'condition'          => [
                    'ha_hta_switcher' => 'yes',
                    'ha_hta_mode'     => ['text_flip'],
                ],
            ]
        );

    }

    protected function reveal_mode_controls( $element ) {
        $element->add_control(
            'ha_hta_tr_orientation',
            [
                'label'              => __( 'Orientation', 'happy-addons-pro' ),
                'type'               => Controls_Manager::CHOOSE,
                'default'            => 'bottom',
                'options'            => [
                    'top'    => [
                        'title' => __( 'From Top', 'happy-addons-pro' ),
                        'icon'  => 'eicon-v-align-bottom'
                    ],
                    'bottom' => [
                        'title' => __( 'From Bottom', 'happy-addons-pro' ),
                        'icon'  => 'eicon-v-align-top'
                    ]
                ],
                'render_type'        => 'template',
                'toggle'             => false,
                'frontend_available' => true,
                'style_transfer'     => true,
                'condition'          => [
                    'ha_hta_switcher' => 'yes',
                    'ha_hta_mode'     => ['reveal']
                ]
            ]
        );
    }

    protected function scale_mode_controls( $element ) {

        $element->add_control(
            'ha_hta_scale',
            [
                'label'              => __( 'Scale', 'happy-addons-pro' ),
                'type'               => Controls_Manager::NUMBER,
                'min'                => 0,
                'max'                => 10,
                'step'               => 0.1,
                'default'            => 1.5,
                'render_type'        => 'template',
                'frontend_available' => true,
                'style_transfer'     => true,
                'condition'          => [
                    'ha_hta_switcher' => 'yes',
                    'ha_hta_mode'     => ['scale']
                ]
            ]
        );

        $element->add_control(
            'ha_hta_scale_text_break',
            [
                'label'              => __( 'Text Break', 'happy-addons-pro' ),
                'type'               => Controls_Manager::SELECT,
                'default'            => 'lines',
                'options'            => [
                    'lines' => __( 'Lines', 'happy-addons-pro' ),
                    'words' => __( 'Words', 'happy-addons-pro' ),
                    'chars' => __( 'Chars', 'happy-addons-pro' )
                ],
                'render_type'        => 'template',
                'toggle'             => false,
                'frontend_available' => true,
                'condition'          => [
                    'ha_hta_switcher' => 'yes',
                    'ha_hta_mode'     => ['scale']
                ]
            ]
        );
    }

    protected function invert_mode_controls( $element ) {
        //TODO::Add Controls
    }

    protected function three_d_mode_controls( $element ) {
        // $element->add_control(
        //     'ha_hta_3d_text_color',
        //     [
        //         'label'              => __( 'Text Color', 'animation-addons-for-elementor-pro' ),
        //         'type'               => Controls_Manager::COLOR,
        //         'render_type'        => 'template',
        //         'toggle'             => false,
        //         'frontend_available' => true,
        //         'style_transfer'     => true,
        //         'condition'          => [
        //             'ha_hta_switcher' => 'yes',
        //             'ha_hta_mode'     => ['3dspin']
        //         ]
        //     ]
        // );
    }

    protected function common_controls( $element ) {
        $element->add_control(
            'ha_hta_trigger_point',
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
                'description'        => __( 'First value = element position, second = viewport position.',
                    'happy-elementor-addons' ),
                'condition'          => [
                    'ha_hta_switcher' => 'yes'
                ],
                'render_type'        => 'template',
                'frontend_available' => true,
                'style_transfer'     => true
            ]
        );

        $element->add_control(
            'ha_hta_custom_trigger_start',
            [
                'label'              => __( 'Custom Trigger(Start)', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::TEXT,
                'default'            => 'top 80%',
                'placeholder'        => __( 'e.g., top 80%', 'happy-elementor-addons' ),
                'render_type'        => 'template',
                'frontend_available' => true,
                'condition'          => [
                    'ha_hta_switcher'      => 'yes',
                    'ha_hta_trigger_point' => 'custom'

                ]
            ]
        );

        $element->add_control(
            'ha_hta_delay',
            [
                'label'              => __( 'Delay(s)', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::NUMBER,
                'min'                => 0.1,
                'max'                => 10,
                'step'               => 0.1,
                'default'            => 0.15,
                'render_type'        => 'template',
                'frontend_available' => true,
                'style_transfer'     => true,
                'condition'          => [
                    'ha_hta_switcher' => 'yes',
                    'ha_hta_mode!'    => ['invert']
                ]
            ]
        );

        $element->add_control(
            'ha_hta_duration',
            [
                'label'              => __( 'Duration(s)', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::NUMBER,
                'min'                => 0.1,
                'max'                => 5,
                'step'               => 0.1,
                'default'            => 0.8,
                'render_type'        => 'template',
                'frontend_available' => true,
                'style_transfer'     => true,
                'condition'          => [
                    'ha_hta_switcher' => 'yes',
                    'ha_hta_mode!'    => ['invert',]
                ]
            ]
        );

        $element->add_control(
            'ha_hta_stagger_delay',
            [
                'label'              => __( 'Stagger Delay(s)', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::NUMBER,
                'min'                => 0.01,
                'max'                => 3,
                'step'               => 0.01,
                'default'            => 0.05,
                'render_type'        => 'template',
                'frontend_available' => true,
                'style_transfer'     => true,
                'condition'          => [
                    'ha_hta_switcher' => 'yes',
                    'ha_hta_mode!'    => ['invert']
                ]
            ]
        );

        $element->add_control(
            'ha_hta_easing_function',
            [
                'label'              => __( 'Easing Functions', 'happy-elementor-addons' ),
                'type'               => Controls_Manager::SELECT,
                'default'            => 'power2.out',
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
                    'back'          => __( 'Back', 'happy-elementor-addons' ),
                    'back.in'       => __( 'Back In', 'happy-elementor-addons' ),
                    'back.out'      => __( 'Back Out', 'happy-elementor-addons' ),
                    'back.inOut'    => __( 'Back InOut', 'happy-elementor-addons' ),
                    'elastic.in'    => __( 'Elastic In', 'happy-elementor-addons' ),
                    'elastic.out'   => __( 'Elastic Out', 'happy-elementor-addons' ),
                    'elastic.inOut' => __( 'Elastic InOut', 'happy-elementor-addons' ),
                    'bounce.in'     => __( 'Bounce In', 'happy-elementor-addons' ),
                    'bounce.out'    => __( 'Bounce Out', 'happy-elementor-addons' ),
                    'bounce.inOut'  => __( 'Bounce InOut', 'happy-elementor-addons' ),
                    'circ.out'      => __( 'Circ Out', 'happy-elementor-addons' ),
                    'circ.in'       => __( 'Circ In', 'happy-elementor-addons' ),
                    'circ.inOut'    => __( 'Circ InOut', 'happy-elementor-addons' )
                ],
                'condition'          => [
                    'ha_hta_switcher' => 'yes',
                    'ha_hta_mode!'    => ['invert']
                ],
                'render_type'        => 'template',
                'frontend_available' => true,
                'style_transfer'     => true
            ]
        );
    }

    protected function get_heading_text_widgets() {
        return $headingWidgets = [
            [
                'name'    => 'heading',
                'section' => 'section_title'
            ],
            [
                'name'    => 'e-heading',
                'section' => 'section_title'
            ],
            [
                'name'    => 'text-editor',
                'section' => 'section_title'
            ]
        ];
    }
}
