<?php
namespace Happy_Addons\Elementor;

use Elementor\Element_Base;
use Elementor\Widget_Base;
use Happy_Addons\Elementor\Widget\Card;
use Happy_Addons\Elementor\Widget\CalderaForm;
use Happy_Addons\Elementor\Widget\Calendly;
use Happy_Addons\Elementor\Widget\Carousel;
use Happy_Addons\Elementor\Widget\CF7;
use Happy_Addons\Elementor\Widget\Dual_Button;
use Happy_Addons\Elementor\Widget\Flip_Box;
use Happy_Addons\Elementor\Widget\Gradient_Heading;
use Happy_Addons\Elementor\Widget\Icon_Box;
use Happy_Addons\Elementor\Widget\Image_Compare;
use Happy_Addons\Elementor\Widget\Image_Grid;
use Happy_Addons\Elementor\Widget\InfoBox;
use Happy_Addons\Elementor\Widget\Justified_Gallery;
use Happy_Addons\Elementor\Widget\Logo_Grid;
use Happy_Addons\Elementor\Widget\Member;
use Happy_Addons\Elementor\Widget\NinjaForm;
use Happy_Addons\Elementor\Widget\Number;
use Happy_Addons\Elementor\Widget\Pricing_Table;
use Happy_Addons\Elementor\Widget\Review;
use Happy_Addons\Elementor\Widget\Skills;
use Happy_Addons\Elementor\Widget\Slider;
use Happy_Addons\Elementor\Widget\Step_Flow;
use Happy_Addons\Elementor\Widget\Testimonial;
use Happy_Addons\Elementor\Widget\WeForm;
use Happy_Addons\Elementor\Widget\WPForm;

defined( 'ABSPATH' ) || die();

class Widgets_Manager {

    /**
     * Initialize
     */
    public static function init() {
        add_action( 'elementor/widgets/widgets_registered', [ __CLASS__, 'register' ] );
        add_action( 'elementor/frontend/before_render', [ __CLASS__, 'add_global_widget_render_attributes' ] );
    }

    public static function add_global_widget_render_attributes( Element_Base $widget ) {
        if ( $widget->get_data( 'widgetType' ) === 'global' && method_exists( $widget, 'get_original_element_instance' ) ) {
            $original_instance = $widget->get_original_element_instance();
            if ( strpos( $original_instance->get_data( 'widgetType' ), 'ha-' ) !== false ) {
                $widget->add_render_attribute( '_wrapper', [
                    'class' => $original_instance->get_html_wrapper_class(),
                ] );
            }
        }
    }

    public static function get_widgets_map() {
        $widgets_map = [
            // This is base for happy addons
            self::get_base_widget_key() => [
                'css' => ['common', 'btn'],
                'js' => [],
                'vendor' => [
                    'js' => ['anime'],
                    'css' => ['happy-icon', 'font-awesome']
                ]
            ],

            // All the widgets are listed below with respective map
            'infobox' => [
                'class' => InfoBox::class,
                'css' => ['infobox'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'card' => [
                'class' => Card::class,
                'css' => ['card', 'badge'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'cf7' => [
                'class' => CF7::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'icon-box' => [
                'class' => Icon_Box::class,
                'css' => ['icon-box', 'badge'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'member' => [
                'class' => Member::class,
                'css' => ['member'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'review' => [
                'class' => Review::class,
                'css' => ['review'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'image-compare' => [
                'class' => Image_Compare::class,
                'css' => ['image-comparison'],
                'js' => [],
                'vendor' => [
                    'css' => ['twentytwenty'],
                    'js' => ['jquery-event-move','jquery-twentytwenty'],
                ],
            ],
            'justified-gallery' => [
                'class' => Justified_Gallery::class,
                'css' => ['justified-gallery', 'gallery-filter'],
                'js' => [],
                'vendor' => [
                    'css' => ['justifiedGallery'],
                    'js' => ['jquery-justifiedGallery'],
                ],
            ],
            'image-grid' => [
                'class' => Image_Grid::class,
                'css' => ['image-grid', 'gallery-filter'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => ['jquery-isotope'],
                ],
            ],
            'slider' => [
                'class' => Slider::class,
                'css' => ['slider-carousel'],
                'js' => [],
                'vendor' => [
                    'css' => ['slick', 'slick-theme'],
                    'js' => ['jquery-slick'],
                ],
            ],
            'carousel' => [
                'class' => Carousel::class,
                'css' => ['slider-carousel'],
                'js' => [],
                'vendor' => [
                    'css' => ['slick', 'slick-theme'],
                    'js' => ['jquery-slick'],
                ],
            ],
            'skills' => [
                'class' => Skills::class,
                'css' => ['skills'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => ['elementor-waypoints', 'jquery-numerator'],
                ],
            ],
            'gradient-heading' => [
                'class' => Gradient_Heading::class,
                'css' => ['gradient-heading'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'wpform' => [
                'class' => WPForm::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'ninjaform' => [
                'class' => NinjaForm::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'calderaform' => [
                'class' => CalderaForm::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'weform' => [
                'class' => WeForm::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'logo-grid' => [
                'class' => Logo_Grid::class,
                'css' => ['logo-grid'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'dual-button' => [
                'class' => Dual_Button::class,
                'css' => ['dual-btn'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'testimonial' => [
                'class' => Testimonial::class,
                'css' => ['testimonial'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'number' => [
                'class' => Number::class,
                'css' => ['number'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => ['elementor-waypoints', 'jquery-numerator'],
                ],
            ],
            'flip-box' => [
                'class' => Flip_Box::class,
                'css' => ['flip-box'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'calendly' => [
                'class' => Calendly::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'pricing-table' => [
                'class' => Pricing_Table::class,
                'css' => ['pricing-table'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'step-flow' => [
                'class' => Step_Flow::class,
                'css' => ['steps-flow'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
        ];

        return apply_filters( 'happyaddons_widgets_map', $widgets_map );
    }

    public static function get_base_widget_key() {
        return apply_filters( 'happyaddons_get_base_widget_key', '_happyaddons_base' );
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
        require( HAPPY_ADDONS_DIR_PATH . 'base/widget-base.php' );

        foreach ( self::get_widgets_map() as $widget_key => $data ) {
            if ( $widget_key !== self::get_base_widget_key() && ! empty( $data['class'] ) && ( ! isset( $data['is_pro'] ) || ! $data['is_pro'] ) ) {
                self::register_widget( $widget_key, $data['class'] );
            }
        }
    }

    protected static function register_widget( $widget_key, $class ) {
        $widget_file = HAPPY_ADDONS_DIR_PATH . 'widgets/' . $widget_key . '/widget.php';
        if ( is_readable( $widget_file ) ) {
            include( $widget_file );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new $class );
        }
    }
}
