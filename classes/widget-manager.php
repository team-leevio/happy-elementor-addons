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
            'floating_effects',
            [
                'label' => __( 'Floating Effects', 'plugin-domain' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
            ]
        );

        $element->add_control(
            'floating_effects_translate_toggle',
            [
                'label' => __( 'Translate', 'happy_addons' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'condition' => [
                    'floating_effects' => 'yes',
                ]
            ]
        );

        $element->start_popover();

        $element->add_control(
            'floating_effects_translate_x',
            [
                'label' => __( 'Translate X', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'condition' => [
                    'floating_effects_translate_toggle' => 'yes',
                    'floating_effects' => 'yes',
                ],
                'render_type' => 'ui'
            ]
        );

        $element->add_control(
            'floating_effects_translate_y',
            [
                'label' => __( 'Translate Y', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'condition' => [
                    'floating_effects_translate_toggle' => 'yes',
                    'floating_effects' => 'yes',
                ],
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
