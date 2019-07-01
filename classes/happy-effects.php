<?php
namespace Happy_Addons\Elementor\Extension;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Core\Files\CSS\Post;

defined( 'ABSPATH' ) || die();

class Happy_Effects {

    public static function init() {
        add_action( 'elementor/element/common/_section_style/after_section_end', [__CLASS__, 'add_controls_section'] );
//        add_action( 'elementor/element/before_parse_css', [__CLASS__, 'remove_disabled_effects'], 10, 2 );
//        add_action( 'elementor/post-css-file/parse', [__CLASS__, 'remove_disabled_effects'], 100 );
//        add_action( 'elementor/document/before_save', [__CLASS__, 'remove_disabled_effects'], 10, 2 );
    }

//    public static function remove_disabled_effects( Post $post, Element_Base $element ) {
    public static function remove_disabled_effects( $e, $data ) {
//        $settings = $element->get_settings();
//        if ( isset( $settings['ha_transform_fx_translate_toggle'] ) && $settings['ha_transform_fx_translate_toggle'] !== 'yes' ) {
//            $element->delete_setting( 'ha_transform_fx_translate_x' );
//            $element->delete_setting( 'ha_transform_fx_translate_y' );
//        }
//        if ( isset( $settings['ha_transform_fx_rotate_toggle'] ) && $settings['ha_transform_fx_rotate_toggle'] !== 'yes' ) {
//            $element->delete_setting( 'ha_transform_fx_rotate_x' );
//            $element->delete_setting( 'ha_transform_fx_rotate_y' );
//            $element->delete_setting( 'ha_transform_fx_rotate_z' );
//        }
//        if ( isset( $settings['ha_transform_fx_scale_toggle'] ) && $settings['ha_transform_fx_scale_toggle'] !== 'yes' ) {
//            $element->delete_setting( 'ha_transform_fx_scale_x' );
//            $element->delete_setting( 'ha_transform_fx_scale_y' );
//        }
        file_put_contents( __DIR__ . '/data.txt', print_r( $data, 1 ) );
    }

    public static function add_controls_section( Element_Base $element ) {
        $element->start_controls_section(
            '_section_happy_effects',
            [
                'label' => __( 'Happy Effects', 'happy_addons' ),
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
                'label' => __( 'Floating Effects', 'happy_addons' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'frontend_available' => true,
            ]
        );

        $element->add_control(
            'ha_floating_fx_translate_toggle',
            [
                'label' => __( 'Translate', 'happy_addons' ),
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
                'label' => __( 'Translate X', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
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
                'label' => __( 'Translate Y', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
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
                'label' => __( 'Duration', 'happy_addons' ),
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
                'label' => __( 'Delay', 'happy_addons' ),
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
                'label' => __( 'Rotate', 'happy_addons' ),
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
                'label' => __( 'Rotate X', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 360,
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

        $element->add_control(
            'ha_floating_fx_rotate_y',
            [
                'label' => __( 'Rotate Y', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 360,
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

        $element->add_control(
            'ha_floating_fx_rotate_z',
            [
                'label' => __( 'Rotate Z', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 360,
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

        $element->add_control(
            'ha_floating_fx_rotate_duration',
            [
                'label' => __( 'Duration', 'happy_addons' ),
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
                'label' => __( 'Delay', 'happy_addons' ),
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
                'label' => __( 'Scale', 'happy_addons' ),
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
                'label' => __( 'Scale X', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => .1
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

        $element->add_control(
            'ha_floating_fx_scale_y',
            [
                'label' => __( 'Scale Y', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => .1
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

        $element->add_control(
            'ha_floating_fx_scale_duration',
            [
                'label' => __( 'Duration', 'happy_addons' ),
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
                'label' => __( 'Delay', 'happy_addons' ),
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
                'label' => __( 'CSS Transform', 'happy_addons' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
            ]
        );

        $element->add_control(
            'ha_transform_fx_translate_toggle',
            [
                'label' => __( 'Translate', 'happy_addons' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'condition' => [
                    'ha_transform_fx' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );

        $element->start_popover();

        $element->add_control(
            'ha_transform_fx_translate_x',
            [
                'label' => __( 'Translate X', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ]
                ],
                'condition' => [
                    'ha_transform_fx_translate_toggle' => 'yes',
                    'ha_transform_fx' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'ha_transform_fx_translate_y',
            [
                'label' => __( 'Translate Y', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ]
                ],
                'condition' => [
                    'ha_transform_fx_translate_toggle' => 'yes',
                    'ha_transform_fx' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'transform:'
                        . 'translate({{ha_transform_fx_translate_x.SIZE || 0}}px, {{ha_transform_fx_translate_y.SIZE || 0}}px);'
                ]
            ]
        );

        $element->end_popover();

        $element->add_control(
            'ha_transform_fx_rotate_toggle',
            [
                'label' => __( 'Rotate', 'happy_addons' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'condition' => [
                    'ha_transform_fx' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );

        $element->start_popover();

        $element->add_control(
            'ha_transform_fx_rotate_x',
            [
                'label' => __( 'Rotate X', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 360,
                    ]
                ],
                'condition' => [
                    'ha_transform_fx_rotate_toggle' => 'yes',
                    'ha_transform_fx' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'ha_transform_fx_rotate_y',
            [
                'label' => __( 'Rotate Y', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 360,
                    ]
                ],
                'condition' => [
                    'ha_transform_fx_rotate_toggle' => 'yes',
                    'ha_transform_fx' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'ha_transform_fx_rotate_z',
            [
                'label' => __( 'Rotate Z', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 360,
                    ]
                ],
                'condition' => [
                    'ha_transform_fx_rotate_toggle' => 'yes',
                    'ha_transform_fx' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'transform:'
                        . 'translate({{ha_transform_fx_translate_x.SIZE || 0}}px, {{ha_transform_fx_translate_y.SIZE || 0}}px) '
                        . 'rotateX({{ha_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z.SIZE || 0}}deg);'
                ]
            ]
        );

        $element->end_popover();

        $element->add_control(
            'ha_transform_fx_scale_toggle',
            [
                'label' => __( 'Scale', 'happy_addons' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'condition' => [
                    'ha_transform_fx' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );

        $element->start_popover();

        $element->add_control(
            'ha_transform_fx_scale_x',
            [
                'label' => __( 'Scale X', 'happy_addons' ),
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

        $element->add_control(
            'ha_transform_fx_scale_y',
            [
                'label' => __( 'Scale Y', 'happy_addons' ),
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
                    '{{WRAPPER}}' => 'transform:'
                        . 'translate({{ha_transform_fx_translate_x.SIZE || 0}}px, {{ha_transform_fx_translate_y.SIZE || 0}}px) '
                        . 'rotateX({{ha_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{ha_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{ha_transform_fx_rotate_z.SIZE || 0}}deg) '
                        . 'scaleX({{ha_transform_fx_scale_x.SIZE || 1}}) scaleY({{ha_transform_fx_scale_y.SIZE || 1}});'
                ]
            ]
        );

        $element->end_popover();
    }
}
