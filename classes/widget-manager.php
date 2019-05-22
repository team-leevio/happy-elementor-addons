<?php
namespace Happy_Addons\Elementor;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Plugin as Elementor;
use Happy_Addons\Elementor\Widget\Base as Happy_Addon_Base;

defined( 'ABSPATH' ) || die();

class Widget_Manager {

    private static $instance = null;

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init() {
        // Add Plugin actions
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
        add_action( 'elementor/element/common/section_effects/after_section_start', [ $this, 'add_floating_effect_controls' ] );
    }

    public function add_floating_effect_controls( Element_Base $element ) {
        $element->add_control(
            'ha_floating_fx',
            [
                'label' => __( 'Floating Effects', 'plugin-domain' ),
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
            'hr',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );
    }

    /**
     * Init Widgets
     *
     * Include widgets files and register them
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function register_widgets() {
        require( HAPPY_DIR_PATH . 'base/widget-base.php' );

        $widgets = [
            'infobox',
            'card',
            'cf7',
            'icon-box',
            'member',
            'review',
            'image-compare',
            'justified-gallery',
            'image-grid',
            'slider',
            'carousel',
            'adcard',
            'skills',
            'gradient-heading',
			'wpform',
			'ninjaform',
			'calderaform',
			'weform',
			'logo-grid',
			'dual-button',
			'testimonial'
        ];

        foreach ( $widgets as $widget ) {
            require( HAPPY_DIR_PATH . 'widgets/' . $widget . '/widget.php' );

            $class_name = str_replace( '-', '_', $widget );
            $class_name = 'Happy_Addons\Elementor\Widget\\' . $class_name;
            Elementor::instance()->widgets_manager->register_widget_type( new $class_name() );
        }


    }

}
