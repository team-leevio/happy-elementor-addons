<?php
namespace Happy_Addons\Elementor;

use Elementor\Plugin as Elementor;

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
			'dual-button'
        ];

        foreach ( $widgets as $widget ) {
            require( HAPPY_DIR_PATH . 'widgets/' . $widget . '/widget.php' );

            $class_name = str_replace( '-', '_', $widget );
            $class_name = 'Happy_Addons\Elementor\Widget\\' . $class_name;
            Elementor::instance()->widgets_manager->register_widget_type( new $class_name() );
        }


    }

}
