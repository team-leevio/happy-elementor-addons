<?php
namespace Happy_Addons\Elementor\Manager;

use Elementor\Plugin as Elementor;
use Happy_Addons\Elementor\Widget\Card;
use Happy_Addons\Elementor\Widget\AdCard;
use Happy_Addons\Elementor\Widget\CalderaForm;
use Happy_Addons\Elementor\Widget\Calendly;
use Happy_Addons\Elementor\Widget\Carousel;
use Happy_Addons\Elementor\Widget\CF7;
use Happy_Addons\Elementor\Widget\Dual_Button;
use Happy_Addons\Elementor\Widget\Feature_List;
use Happy_Addons\Elementor\Widget\Flip_Box;
use Happy_Addons\Elementor\Widget\Google_Map;
use Happy_Addons\Elementor\Widget\Gradient_Heading;
use Happy_Addons\Elementor\Widget\Hover_Box;
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

class Widgets {
    /**
     * Initialize
     */
    public static function init() {
        add_action( 'elementor/widgets/widgets_registered', [__CLASS__, 'register'] );
    }

    public static function get_widgets_map() {
        $widgets_map = [
            // This is base for happy addons
            self::get_base_widget_key() => [
                'css' => [],
                'js' => [],
                'vendor' => [
                    'js' => ['anime']
                ]
            ],

            // All the widgets are listed below with respective map
            'infobox' => [
                'class' => InfoBox::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'card' => [
                'class' => Card::class,
                'css' => [],
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
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'member' => [
                'class' => Member::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'review' => [
                'class' => Review::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'image-compare' => [
                'class' => Image_Compare::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => ['twentytwenty'],
                    'js' => ['jquery-event-move','jquery-twentytwenty'],
                ],
            ],
            'justified-gallery' => [
                'class' => Justified_Gallery::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => ['justifiedGallery'],
                    'js' => ['jquery-justifiedGallery'],
                ],
            ],
            'image-grid' => [
                'class' => Image_Grid::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => ['jquery-isotope'],
                ],
            ],
            'slider' => [
                'class' => Slider::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => ['slick', 'slick-theme'],
                    'js' => ['jquery-slick'],
                ],
            ],
            'carousel' => [
                'class' => Carousel::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => ['slick', 'slick-theme'],
                    'js' => ['jquery-slick'],
                ],
            ],
            'adcard' => [
                'class' => AdCard::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'skills' => [
                'class' => Skills::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => ['elementor-waypoints', 'jquery-numerator'],
                ],
            ],
            'gradient-heading' => [
                'class' => Gradient_Heading::class,
                'css' => [],
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
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'dual-button' => [
                'class' => Dual_Button::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'testimonial' => [
                'class' => Testimonial::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'number' => [
                'class' => Number::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => ['elementor-waypoints', 'jquery-numerator'],
                ],
            ],
            'flip-box' => [
                'class' => Flip_Box::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'hover-box' => [
                'class' => Hover_Box::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'google-map' => [
                'class' => Google_Map::class,
                'css' => [],
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
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'feature-list' => [
                'class' => Feature_List::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'step-flow' => [
                'class' => Step_Flow::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
        ];

        return apply_filters( 'happyaddons_widgets_map', $widgets_map );
    }

    /**
     * Check widget file and include
     *
     * @param $widget
     * @return bool
     */
    private static function is_registrable( $widget ) {
        $widget_file = HAPPY_DIR_PATH . 'widgets/' . $widget . '/widget.php';
        if ( $widget !== self::get_base_widget_key() && is_readable( $widget_file ) ) {
            include( $widget_file );
            return true;
        }
        return false;
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
        require( HAPPY_DIR_PATH . 'base/widget-base.php' );

        foreach ( self::get_widgets_map() as $widget => $data ) {
            if ( self::is_registrable( $widget ) && class_exists( $data['class'] ) ) {
                Elementor::instance()->widgets_manager->register_widget_type( new $data['class'] );
            }
        }
    }
}
