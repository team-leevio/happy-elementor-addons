<?php
namespace Happy_Addons\Elementor\Manager;

use Elementor\Plugin as Elementor;

defined( 'ABSPATH' ) || die();

class Widgets {
    public static function init() {
        add_action( 'elementor/widgets/widgets_registered', [__CLASS__, 'register'] );
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
    public static function register() {
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
			'testimonial',
//            'heading-detail',
 	        'number',
//            'flip-box',
        ];

        foreach ( $widgets as $widget ) {
            require( HAPPY_DIR_PATH . 'widgets/' . $widget . '/widget.php' );

            $class_name = str_replace( '-', '_', $widget );
            $class_name = 'Happy_Addons\Elementor\Widget\\' . $class_name;
            Elementor::instance()->widgets_manager->register_widget_type( new $class_name() );
        }
    }
}
