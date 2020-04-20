<?php
namespace Happy_Addons\Elementor\Extension;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

defined( 'ABSPATH' ) || die();

class Happy_Effects {

    public static function init() {
        add_action( 'elementor/element/common/_section_style/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
    }

    public static function add_controls_section( Element_Base $element ) {
        $element->start_controls_section(
            '_section_happy_effects',
            [
                'label' => __( 'Happy Effects', 'happy-elementor-addons' ) . ha_get_section_icon(),
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        self::add_floating_effects( $element );
        self::add_css_effects( $element );

        $element->end_controls_section();
    }

    public static function add_floating_effects( Element_Base $element ) {
        $element->add_control(
            'ha_floating_fx',
            [
                'label' => __( 'Floating Effects', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'frontend_available' => true,
            ]
        );

        $element->add_control(
            'ha_floating_fx_translate_toggle',
            [
                'label' => __( 'Translate', 'happy-elementor-addons' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'ha_floating_fx' => 'yes',
                ]
            ]
        );

        $element->start_popover();

        $element->add_control(
            'ha_floating_fx_translate_x',
            [
                'label' => __( 'Translate X', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'sizes' => [
                        'from' => 0,
                        'to' => 5,
                    ],
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ]
                ],
                'labels' => [
                    __( 'From', 'happy-elementor-addons' ),
                    __( 'To', 'happy-elementor-addons' ),
                ],
                'scales' => 1,
                'handles' => 'range',
                'condition' => [
                    'ha_floating_fx_translate_toggle' => 'yes',
                    'ha_floating_fx' => 'yes',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $element->add_control(
            'ha_floating_fx_translate_y',
            [
                'label' => __( 'Translate Y', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'sizes' => [
                        'from' => 0,
                        'to' => 5,
                    ],
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ]
                ],
                'labels' => [
                    __( 'From', 'happy-elementor-addons' ),
                    __( 'To', 'happy-elementor-addons' ),
                ],
                'scales' => 1,
                'handles' => 'range',
                'condition' => [
                    'ha_floating_fx_translate_toggle' => 'yes',
                    'ha_floating_fx' => 'yes',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $element->add_control(
            'ha_floating_fx_translate_duration',
            [
                'label' => __( 'Duration', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10000,
                        'step' => 100
                    ]
                ],
                'default' => [
                    'size' => 1000,
                ],
                'condition' => [
                    'ha_floating_fx_translate_toggle' => 'yes',
                    'ha_floating_fx' => 'yes',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $element->add_control(
            'ha_floating_fx_translate_delay',
            [
                'label' => __( 'Delay', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 5000,
                        'step' => 100
                    ]
                ],
                'condition' => [
                    'ha_floating_fx_translate_toggle' => 'yes',
                    'ha_floating_fx' => 'yes',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $element->end_popover();

        $element->add_control(
            'ha_floating_fx_rotate_toggle',
            [
                'label' => __( 'Rotate', 'happy-elementor-addons' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'ha_floating_fx' => 'yes',
                ]
            ]
        );

        $element->start_popover();

        $element->add_control(
            'ha_floating_fx_rotate_x',
            [
                'label' => __( 'Rotate X', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'sizes' => [
                        'from' => 0,
                        'to' => 45,
                    ],
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => -180,
                        'max' => 180,
                    ]
                ],
                'labels' => [
                    __( 'From', 'happy-elementor-addons' ),
                    __( 'To', 'happy-elementor-addons' ),
                ],
                'scales' => 1,
                'handles' => 'range',
                'condition' => [
                    'ha_floating_fx_rotate_toggle' => 'yes',
                    'ha_floating_fx' => 'yes',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $element->add_control(
            'ha_floating_fx_rotate_y',
            [
                'label' => __( 'Rotate Y', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'sizes' => [
                        'from' => 0,
                        'to' => 45,
                    ],
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => -180,
                        'max' => 180,
                    ]
                ],
                'labels' => [
                    __( 'From', 'happy-elementor-addons' ),
                    __( 'To', 'happy-elementor-addons' ),
                ],
                'scales' => 1,
                'handles' => 'range',
                'condition' => [
                    'ha_floating_fx_rotate_toggle' => 'yes',
                    'ha_floating_fx' => 'yes',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $element->add_control(
            'ha_floating_fx_rotate_z',
            [
                'label' => __( 'Rotate Z', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'sizes' => [
                        'from' => 0,
                        'to' => 45,
                    ],
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => -180,
                        'max' => 180,
                    ]
                ],
                'labels' => [
                    __( 'From', 'happy-elementor-addons' ),
                    __( 'To', 'happy-elementor-addons' ),
                ],
                'scales' => 1,
                'handles' => 'range',
                'condition' => [
                    'ha_floating_fx_rotate_toggle' => 'yes',
                    'ha_floating_fx' => 'yes',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $element->add_control(
            'ha_floating_fx_rotate_duration',
            [
                'label' => __( 'Duration', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10000,
                        'step' => 100
                    ]
                ],
                'default' => [
                    'size' => 1000,
                ],
                'condition' => [
                    'ha_floating_fx_rotate_toggle' => 'yes',
                    'ha_floating_fx' => 'yes',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $element->add_control(
            'ha_floating_fx_rotate_delay',
            [
                'label' => __( 'Delay', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 5000,
                        'step' => 100
                    ]
                ],
                'condition' => [
                    'ha_floating_fx_rotate_toggle' => 'yes',
                    'ha_floating_fx' => 'yes',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $element->end_popover();

        $element->add_control(
            'ha_floating_fx_scale_toggle',
            [
                'label' => __( 'Scale', 'happy-elementor-addons' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'ha_floating_fx' => 'yes',
                ]
            ]
        );

        $element->start_popover();

        $element->add_control(
            'ha_floating_fx_scale_x',
            [
                'label' => __( 'Scale X', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'sizes' => [
                        'from' => 1,
                        'to' => 1.2,
                    ],
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => .1
                    ]
                ],
                'labels' => [
                    __( 'From', 'happy-elementor-addons' ),
                    __( 'To', 'happy-elementor-addons' ),
                ],
                'scales' => 1,
                'handles' => 'range',
                'condition' => [
                    'ha_floating_fx_scale_toggle' => 'yes',
                    'ha_floating_fx' => 'yes',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $element->add_control(
            'ha_floating_fx_scale_y',
            [
                'label' => __( 'Scale Y', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'sizes' => [
                        'from' => 1,
                        'to' => 1.2,
                    ],
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => .1
                    ]
                ],
                'labels' => [
                    __( 'From', 'happy-elementor-addons' ),
                    __( 'To', 'happy-elementor-addons' ),
                ],
                'scales' => 1,
                'handles' => 'range',
                'condition' => [
                    'ha_floating_fx_scale_toggle' => 'yes',
                    'ha_floating_fx' => 'yes',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $element->add_control(
            'ha_floating_fx_scale_duration',
            [
                'label' => __( 'Duration', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10000,
                        'step' => 100
                    ]
                ],
                'default' => [
                    'size' => 1000,
                ],
                'condition' => [
                    'ha_floating_fx_scale_toggle' => 'yes',
                    'ha_floating_fx' => 'yes',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $element->add_control(
            'ha_floating_fx_scale_delay',
            [
                'label' => __( 'Delay', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 5000,
                        'step' => 100
                    ]
                ],
                'condition' => [
                    'ha_floating_fx_scale_toggle' => 'yes',
                    'ha_floating_fx' => 'yes',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $element->end_popover();

        $element->add_control(
            'ha_hr',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );
    }

    public static function add_css_effects( Element_Base $element ) {
        $element->add_control(
            'ha_transform_fx',
            [
                'label' => __( 'CSS Transform', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
            ]
        );

        $element->add_control(
            'ha_transform_fx_translate_toggle',
            [
                'label' => __( 'Translate', 'happy-elementor-addons' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'condition' => [
                    'ha_transform_fx' => 'yes',
                ],
            ]
        );

        $element->start_popover();

        $element->add_responsive_control(
            'ha_transform_fx_translate_x',
            [
                'label' => __( 'Translate X', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ]
                ],
                'condition' => [
                    'ha_transform_fx_translate_toggle' => 'yes',
                    'ha_transform_fx' => 'yes',
                ],
            ]
        );

        $element->add_responsive_control(
            'ha_transform_fx_translate_y',
            [
                'label' => __( 'Translate Y', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ]
                ],
                'condition' => [
                    'ha_transform_fx_translate_toggle' => 'yes',
                    'ha_transform_fx' => 'yes',
                ],
                'selectors' => [
                    '(desktop){{WRAPPER}}' =>
                        '-ms-transform:'
                            . 'translate({{ha_transform_fx_translate_x.SIZE || 0}}px, {{ha_transform_fx_translate_y.SIZE || 0}}px);'
                        . '-webkit-transform:'
                            . 'translate({{ha_transform_fx_translate_x.SIZE || 0}}px, {{ha_transform_fx_translate_y.SIZE || 0}}px);'
                        . 'transform:'
                            . 'translate({{ha_transform_fx_translate_x.SIZE || 0}}px, {{ha_transform_fx_translate_y.SIZE || 0}}px);',
                    '(tablet){{WRAPPER}}' =>
                        '-ms-transform:'
                            . 'translate({{ha_transform_fx_translate_x_tablet.SIZE || 0}}px, {{ha_transform_fx_translate_y_tablet.SIZE || 0}}px);'
                        . '-webkit-transform:'
                            . 'translate({{ha_transform_fx_translate_x_tablet.SIZE || 0}}px, {{ha_transform_fx_translate_y_tablet.SIZE || 0}}px);'
                        . 'transform:'
                            . 'translate({{ha_transform_fx_translate_x_tablet.SIZE || 0}}px, {{ha_transform_fx_translate_y_tablet.SIZE || 0}}px);',
                    '(mobile){{WRAPPER}}' =>
                        '-ms-transform:'
                            . 'translate({{ha_transform_fx_translate_x_mobile.SIZE || 0}}px, {{ha_transform_fx_translate_y_mobile.SIZE || 0}}px);'
                        . '-webkit-transform:'
                            . 'translate({{ha_transform_fx_translate_x_mobile.SIZE || 0}}px, {{ha_transform_fx_translate_y_mobile.SIZE || 0}}px);'
                        . 'transform:'
                            . 'translate({{ha_transform_fx_translate_x_mobile.SIZE || 0}}px, {{ha_transform_fx_translate_y_mobile.SIZE || 0}}px);',
                ]
            ]
        );

        $element->end_popover();

        $element->add_control(
            'ha_transform_fx_rotate_toggle',
            [
                'label' => __( 'Rotate', 'happy-elementor-addons' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'condition' => [
                    'ha_transform_fx' => 'yes',
                ],
            ]
        );

        $element->start_popover();

        $element->add_responsive_control(
            'ha_transform_fx_rotate_x',
            [
                'label' => __( 'Rotate X', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -180,
                        'max' => 180,
                    ]
                ],
                'condition' => [
                    'ha_transform_fx_rotate_toggle' => 'yes',
                    'ha_transform_fx' => 'yes',
                ],
            ]
        );

        $element->add_responsive_control(
            'ha_transform_fx_rotate_y',
            [
                'label' => __( 'Rotate Y', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -180,
                        'max' => 180,
                    ]
                ],
                'condition' => [
                    'ha_transform_fx_rotate_toggle' => 'yes',
                    'ha_transform_fx' => 'yes',
                ],
            ]
        );

        $element->add_responsive_control(
            'ha_transform_fx_rotate_z',
            [
                'label' => __( 'Rotate Z', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -180,
                        'max' => 180,
                    ]
                ],
                'condition' => [
                    'ha_transform_fx_rotate_toggle' => 'yes',
                    'ha_transform_fx' => 'yes',
                ],
                'selectors' => [
                    '(desktop){{WRAPPER}}' =>
                        '-ms-transform:'
                            . 'translate({{ha_transform_fx_translate_x.SIZE || 0}}px, {{ha_transform_fx_translate_y.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z.SIZE || 0}}deg);'
                        . '-webkit-transform:'
                            . 'translate({{ha_transform_fx_translate_x.SIZE || 0}}px, {{ha_transform_fx_translate_y.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z.SIZE || 0}}deg);'
                        . 'transform:'
                            . 'translate({{ha_transform_fx_translate_x.SIZE || 0}}px, {{ha_transform_fx_translate_y.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z.SIZE || 0}}deg);',
                    '(tablet){{WRAPPER}}' =>
                        '-ms-transform:'
                            . 'translate({{ha_transform_fx_translate_x_tablet.SIZE || 0}}px, {{ha_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z_tablet.SIZE || 0}}deg);'
                        . '-webkit-transform:'
                            . 'translate({{ha_transform_fx_translate_x_tablet.SIZE || 0}}px, {{ha_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z_tablet.SIZE || 0}}deg);'
                        . 'transform:'
                            . 'translate({{ha_transform_fx_translate_x_tablet.SIZE || 0}}px, {{ha_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z_tablet.SIZE || 0}}deg);',
                    '(mobile){{WRAPPER}}' =>
                        '-ms-transform:'
                            . 'translate({{ha_transform_fx_translate_x_mobile.SIZE || 0}}px, {{ha_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z_mobile.SIZE || 0}}deg);'
                        . '-webkit-transform:'
                            . 'translate({{ha_transform_fx_translate_x_mobile.SIZE || 0}}px, {{ha_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z_mobile.SIZE || 0}}deg);'
                        . 'transform:'
                            . 'translate({{ha_transform_fx_translate_x_mobile.SIZE || 0}}px, {{ha_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z_mobile.SIZE || 0}}deg);'
                ]
            ]
        );

        $element->end_popover();

        $element->add_control(
            'ha_transform_fx_scale_toggle',
            [
                'label' => __( 'Scale', 'happy-elementor-addons' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'condition' => [
                    'ha_transform_fx' => 'yes',
                ],
            ]
        );

        $element->start_popover();

        $element->add_responsive_control(
            'ha_transform_fx_scale_x',
            [
                'label' => __( 'Scale X', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
                    'size' => 1
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => .1
                    ]
                ],
                'condition' => [
                    'ha_transform_fx_scale_toggle' => 'yes',
                    'ha_transform_fx' => 'yes',
                ],
            ]
        );

        $element->add_responsive_control(
            'ha_transform_fx_scale_y',
            [
                'label' => __( 'Scale Y', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
                    'size' => 1
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => .1
                    ]
                ],
                'condition' => [
                    'ha_transform_fx_scale_toggle' => 'yes',
                    'ha_transform_fx' => 'yes',
                ],
                'selectors' => [
                    '(desktop){{WRAPPER}}' =>
                        '-ms-transform:'
                            . 'translate({{ha_transform_fx_translate_x.SIZE || 0}}px, {{ha_transform_fx_translate_y.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z.SIZE || 0}}deg) '
                            . 'scaleX({{ha_transform_fx_scale_x.SIZE || 1}}) scaleY({{ha_transform_fx_scale_y.SIZE || 1}});'
                        . '-webkit-transform:'
                            . 'translate({{ha_transform_fx_translate_x.SIZE || 0}}px, {{ha_transform_fx_translate_y.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z.SIZE || 0}}deg) '
                            . 'scaleX({{ha_transform_fx_scale_x.SIZE || 1}}) scaleY({{ha_transform_fx_scale_y.SIZE || 1}});'
                        . 'transform:'
                            . 'translate({{ha_transform_fx_translate_x.SIZE || 0}}px, {{ha_transform_fx_translate_y.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z.SIZE || 0}}deg) '
                            . 'scaleX({{ha_transform_fx_scale_x.SIZE || 1}}) scaleY({{ha_transform_fx_scale_y.SIZE || 1}});',
                    '(tablet){{WRAPPER}}' =>
                        '-ms-transform:'
                            . 'translate({{ha_transform_fx_translate_x_tablet.SIZE || 0}}px, {{ha_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z_tablet.SIZE || 0}}deg) '
                            . 'scaleX({{ha_transform_fx_scale_x_tablet.SIZE || 1}}) scaleY({{ha_transform_fx_scale_y_tablet.SIZE || 1}});'
                        . '-webkit-transform:'
                            . 'translate({{ha_transform_fx_translate_x_tablet.SIZE || 0}}px, {{ha_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z_tablet.SIZE || 0}}deg) '
                            . 'scaleX({{ha_transform_fx_scale_x_tablet.SIZE || 1}}) scaleY({{ha_transform_fx_scale_y_tablet.SIZE || 1}});'
                        . 'transform:'
                            . 'translate({{ha_transform_fx_translate_x_tablet.SIZE || 0}}px, {{ha_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z_tablet.SIZE || 0}}deg) '
                            . 'scaleX({{ha_transform_fx_scale_x_tablet.SIZE || 1}}) scaleY({{ha_transform_fx_scale_y_tablet.SIZE || 1}});',
                    '(mobile){{WRAPPER}}' =>
                        '-ms-transform:'
                            . 'translate({{ha_transform_fx_translate_x_mobile.SIZE || 0}}px, {{ha_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z_mobile.SIZE || 0}}deg) '
                            . 'scaleX({{ha_transform_fx_scale_x_mobile.SIZE || 1}}) scaleY({{ha_transform_fx_scale_y_mobile.SIZE || 1}});'
                        . '-webkit-transform:'
                            . 'translate({{ha_transform_fx_translate_x_mobile.SIZE || 0}}px, {{ha_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z_mobile.SIZE || 0}}deg) '
                            . 'scaleX({{ha_transform_fx_scale_x_mobile.SIZE || 1}}) scaleY({{ha_transform_fx_scale_y_mobile.SIZE || 1}});'
                        . 'transform:'
                            . 'translate({{ha_transform_fx_translate_x_mobile.SIZE || 0}}px, {{ha_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z_mobile.SIZE || 0}}deg) '
                            . 'scaleX({{ha_transform_fx_scale_x_mobile.SIZE || 1}}) scaleY({{ha_transform_fx_scale_y_mobile.SIZE || 1}});'
                ]
            ]
        );

        $element->end_popover();

        $element->add_control(
            'ha_transform_fx_skew_toggle',
            [
                'label' => __( 'Skew', 'happy-elementor-addons' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'condition' => [
                    'ha_transform_fx' => 'yes',
                ],
            ]
        );

        $element->start_popover();

        $element->add_responsive_control(
            'ha_transform_fx_skew_x',
            [
                'label' => __( 'Skew X', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['deg'],
                'range' => [
                    'px' => [
                        'min' => -180,
                        'max' => 180,
                    ]
                ],
                'condition' => [
                    'ha_transform_fx_skew_toggle' => 'yes',
                    'ha_transform_fx' => 'yes',
                ],
            ]
        );

        $element->add_responsive_control(
            'ha_transform_fx_skew_y',
            [
                'label' => __( 'Skew Y', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['deg'],
                'range' => [
                    'px' => [
                        'min' => -180,
                        'max' => 180,
                    ]
                ],
                'condition' => [
                    'ha_transform_fx_skew_toggle' => 'yes',
                    'ha_transform_fx' => 'yes',
                ],
                'selectors' => [
                    '(desktop){{WRAPPER}}' =>
                        '-ms-transform:'
                            . 'translate({{ha_transform_fx_translate_x.SIZE || 0}}px, {{ha_transform_fx_translate_y.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z.SIZE || 0}}deg) '
                            . 'scaleX({{ha_transform_fx_scale_x.SIZE || 1}}) scaleY({{ha_transform_fx_scale_y.SIZE || 1}}) '
                            . 'skew({{ha_transform_fx_skew_x.SIZE || 0}}deg, {{ha_transform_fx_skew_y.SIZE || 0}}deg);'
                        . '-webkit-transform:'
                            . 'translate({{ha_transform_fx_translate_x.SIZE || 0}}px, {{ha_transform_fx_translate_y.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z.SIZE || 0}}deg) '
                            . 'scaleX({{ha_transform_fx_scale_x.SIZE || 1}}) scaleY({{ha_transform_fx_scale_y.SIZE || 1}}) '
                            . 'skew({{ha_transform_fx_skew_x.SIZE || 0}}deg, {{ha_transform_fx_skew_y.SIZE || 0}}deg);'
                        . 'transform:'
                            . 'translate({{ha_transform_fx_translate_x.SIZE || 0}}px, {{ha_transform_fx_translate_y.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z.SIZE || 0}}deg) '
                            . 'scaleX({{ha_transform_fx_scale_x.SIZE || 1}}) scaleY({{ha_transform_fx_scale_y.SIZE || 1}}) '
                            . 'skew({{ha_transform_fx_skew_x.SIZE || 0}}deg, {{ha_transform_fx_skew_y.SIZE || 0}}deg);',
                    '(tablet){{WRAPPER}}' =>
                        '-ms-transform:'
                            . 'translate({{ha_transform_fx_translate_x_tablet.SIZE || 0}}px, {{ha_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z_tablet.SIZE || 0}}deg) '
                            . 'scaleX({{ha_transform_fx_scale_x_tablet.SIZE || 1}}) scaleY({{ha_transform_fx_scale_y_tablet.SIZE || 1}}) '
                            . 'skew({{ha_transform_fx_skew_x_tablet.SIZE || 0}}deg, {{ha_transform_fx_skew_y_tablet.SIZE || 0}}deg);'
                        . '-webkit-transform:'
                            . 'translate({{ha_transform_fx_translate_x_tablet.SIZE || 0}}px, {{ha_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z_tablet.SIZE || 0}}deg) '
                            . 'scaleX({{ha_transform_fx_scale_x_tablet.SIZE || 1}}) scaleY({{ha_transform_fx_scale_y_tablet.SIZE || 1}}) '
                            . 'skew({{ha_transform_fx_skew_x_tablet.SIZE || 0}}deg, {{ha_transform_fx_skew_y_tablet.SIZE || 0}}deg);'
                        . 'transform:'
                            . 'translate({{ha_transform_fx_translate_x_tablet.SIZE || 0}}px, {{ha_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z_tablet.SIZE || 0}}deg) '
                            . 'scaleX({{ha_transform_fx_scale_x_tablet.SIZE || 1}}) scaleY({{ha_transform_fx_scale_y_tablet.SIZE || 1}}) '
                            . 'skew({{ha_transform_fx_skew_x_tablet.SIZE || 0}}deg, {{ha_transform_fx_skew_y_tablet.SIZE || 0}}deg);',
                    '(mobile){{WRAPPER}}' =>
                        '-ms-transform:'
                            . 'translate({{ha_transform_fx_translate_x_mobile.SIZE || 0}}px, {{ha_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z_mobile.SIZE || 0}}deg) '
                            . 'scaleX({{ha_transform_fx_scale_x_mobile.SIZE || 1}}) scaleY({{ha_transform_fx_scale_y_mobile.SIZE || 1}}) '
                            . 'skew({{ha_transform_fx_skew_x_mobile.SIZE || 0}}deg, {{ha_transform_fx_skew_y_mobile.SIZE || 0}}deg);'
                        . '-webkit-transform:'
                            . 'translate({{ha_transform_fx_translate_x_mobile.SIZE || 0}}px, {{ha_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z_mobile.SIZE || 0}}deg) '
                            . 'scaleX({{ha_transform_fx_scale_x_mobile.SIZE || 1}}) scaleY({{ha_transform_fx_scale_y_mobile.SIZE || 1}}) '
                            . 'skew({{ha_transform_fx_skew_x_mobile.SIZE || 0}}deg, {{ha_transform_fx_skew_y_mobile.SIZE || 0}}deg);'
                        . 'transform:'
                            . 'translate({{ha_transform_fx_translate_x_mobile.SIZE || 0}}px, {{ha_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                            . 'rotateX({{ha_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z_mobile.SIZE || 0}}deg) '
                            . 'scaleX({{ha_transform_fx_scale_x_mobile.SIZE || 1}}) scaleY({{ha_transform_fx_scale_y_mobile.SIZE || 1}}) '
                            . 'skew({{ha_transform_fx_skew_x_mobile.SIZE || 0}}deg, {{ha_transform_fx_skew_y_mobile.SIZE || 0}}deg);'
                ]
            ]
        );

        $element->end_popover();
    }
}
