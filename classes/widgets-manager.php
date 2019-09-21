<?php
namespace Happy_Addons\Elementor;

use Elementor\Element_Base;
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

    const WIDGETS_DB_KEY = 'happyaddons_inactive_widgets';

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
            if ( method_exists( $original_instance, 'get_html_wrapper_class' ) && strpos( $original_instance->get_data( 'widgetType' ), 'ha-' ) !== false ) {
                $widget->add_render_attribute( '_wrapper', [
                    'class' => $original_instance->get_html_wrapper_class(),
                ] );
            }
        }
    }

    public static function get_inactive_widgets() {
        return get_option( self::WIDGETS_DB_KEY, [] );
    }

    public static function save_inactive_widgets( $widgets = [] ) {
        update_option( self::WIDGETS_DB_KEY, $widgets );
    }

    public static function get_widgets_map() {
        $widgets_map = [
            self::get_base_widget_key() => [
                'css' => ['common', 'btn'],
                'js' => [],
                'vendor' => [
                    'js' => ['anime'],
                    'css' => ['happy-icons', 'font-awesome']
                ]
            ],
        ];

        $local_widgets_map = self::get_local_widgets_map();
        $widgets_map = array_merge( $widgets_map, $local_widgets_map );
        return apply_filters( 'happyaddons_get_widgets_map', $widgets_map );
    }

    /**
     * Get the pro widgets map for dashboard only
     *
     * @return array
     */
    public static function get_pro_widget_map() {
        return [
            'google-map' => [
                'title' => __( 'Google Map', 'happy-addons-pro' ),
                'icon' => 'hm hm-map-marker',
                'is_pro' => true,
            ],
            'advanced-heading' => [
                'title' => __( 'Advanced Heading', 'happy-addons-pro' ),
                'icon' => 'hm hm-advanced-heading',
                'is_pro' => true,
                'demo' => 'https://happyaddons.com/elementor-logo-grid-widget-demo/'
            ],
            'list-group' => [
                'title' => __( 'List Group', 'happy-addons-pro' ),
                'icon' => 'hm hm-list-group',
                'is_pro' => true,
            ],
            'hover-box' => [
                'title' => __( 'Hover Box', 'happy-addons-pro' ),
                'icon' => 'hm hm-finger-point',
                'is_pro' => true,
            ],
            'countdown' => [
                'title' => __( 'Countdown', 'happy-addons-pro' ),
                'icon' => 'hm hm-refresh-time',
                'is_pro' => true,
            ],
            'team-carousel' => [
                'title' => __( 'Team Carousel', 'happy-addons-pro' ),
                'icon' => 'hm hm-team-carousel',
                'is_pro' => true,
            ],
            'logo-carousel' => [
                'title' => __( 'Team Carousel', 'happy-addons-pro' ),
                'icon' => 'hm hm-team-carousel',
                'is_pro' => true,
            ],
            'source-code' => [
                'title' => __( 'Source Code', 'happy-addons-pro' ),
                'icon' => 'hm hm-code-browser',
                'is_pro' => true,
            ],

            'feature-list' => [
                'title' => __( 'Feature List', 'happy-addons-pro' ),
                'icon' => 'hm hm-list-2',
                'is_pro' => true,
            ],
            'testimonial-carousel' => [
                'title' => __( 'Testimonial Carousel', 'happy-addons-pro' ),
                'icon' => 'hm hm-testimonial-carousel',
                'is_pro' => true,
            ],
            'advanced-tabs' => [
                'title' => __( 'Advanced Tabs', 'happy-addons-pro' ),
                'icon' => 'hm hm-tab',
                'is_pro' => true,
            ],
            'animated-text' => [
                'title' => __( 'Animated Text', 'happy-addons-pro' ),
                'icon' => 'hm hm-text-animation',
                'is_pro' => true,
            ],
            'timeline' => [
                'title' => __( 'Timeline', 'happy-addons-pro' ),
                'icon' => 'hm hm-timeline',
                'is_pro' => true,
            ],
            'instagram-feed' => [
                'title' => __( 'Instagram Feed', 'happy-addons-pro' ),
                'icon' => 'hm hm-instagram',
                'is_pro' => true,
            ],
        ];
    }

    /**
     * Get the free widgets map
     *
     * @return array
     */
    public static function get_local_widgets_map() {
        return [
            // All the widgets are listed below with respective map
            'infobox' => [
                'demo' => 'https://happyaddons.com/go/demo-info-box',
                'title' => __( 'Info Box', 'happy-elementor-addons' ),
                'icon' => 'hm hm-blog-content',
                'class' => InfoBox::class,
                'css' => ['infobox'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'card' => [
                'demo' => 'https://happyaddons.com/go/demo-card',
                'title' => __( 'Card', 'happy-elementor-addons' ),
                'icon' => 'hm hm-card',
                'class' => Card::class,
                'css' => ['card', 'badge'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'cf7' => [
                'demo' => 'https://happyaddons.com/go/demo-contact-form7',
                'title' => __( 'Contact Form 7', 'happy-elementor-addons' ),
                'icon' => 'hm hm-form',
                'class' => CF7::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'icon-box' => [
                'demo' => 'https://happyaddons.com/go/demo-icon-box',
                'title' => __( 'Icon Box', 'happy-elementor-addons' ),
                'icon' => 'hm hm-icon-box',
                'class' => Icon_Box::class,
                'css' => ['icon-box', 'badge'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'member' => [
                'demo' => 'https://happyaddons.com/go/demo-team-member',
                'title' => __( 'Team Member', 'happy-elementor-addons' ),
                'icon' => 'hm hm-team-member',
                'class' => Member::class,
                'css' => ['member'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'review' => [
                'demo' => 'https://happyaddons.com/go/demo-review',
                'title' => __( 'Review', 'happy-elementor-addons' ),
                'icon' => 'hm hm-review',
                'class' => Review::class,
                'css' => ['review'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'image-compare' => [
                'demo' => 'https://happyaddons.com/go/demo-image-compare',
                'title' => __( 'Image Compare', 'happy-elementor-addons' ),
                'icon' => 'hm hm-image-compare',
                'class' => Image_Compare::class,
                'css' => ['image-comparison'],
                'js' => [],
                'vendor' => [
                    'css' => ['twentytwenty'],
                    'js' => ['jquery-event-move','jquery-twentytwenty'],
                ],
            ],
            'justified-gallery' => [
                'demo' => 'https://happyaddons.com/go/demo-justified-grid',
                'title' => __( 'Justified Grid', 'happy-elementor-addons' ),
                'icon' => 'hm hm-brick-wall',
                'class' => Justified_Gallery::class,
                'css' => ['justified-gallery', 'gallery-filter'],
                'js' => [],
                'vendor' => [
                    'css' => ['justifiedGallery', 'magnific-popup'],
                    'js' => ['jquery-justifiedGallery', 'jquery-magnific-popup'],
                ],
            ],
            'image-grid' => [
                'demo' => 'https://happyaddons.com/go/demo-image-grid',
                'title' => __( 'Image Grid', 'happy-elementor-addons' ),
                'icon' => 'hm hm-grid-even',
                'class' => Image_Grid::class,
                'css' => ['image-grid', 'gallery-filter'],
                'js' => [],
                'vendor' => [
                    'css' => ['magnific-popup'],
                    'js' => ['jquery-isotope', 'jquery-magnific-popup'],
                ],
            ],
            'slider' => [
                'demo' => 'https://happyaddons.com/go/demo-slider',
                'title' => __( 'Slider', 'happy-elementor-addons' ),
                'icon' => 'hm hm-image-slider',
                'class' => Slider::class,
                'css' => ['slider-carousel'],
                'js' => [],
                'vendor' => [
                    'css' => ['slick', 'slick-theme'],
                    'js' => ['jquery-slick'],
                ],
            ],
            'carousel' => [
                'title' => __( 'Carousel', 'happy-elementor-addons' ),
                'icon' => 'hm hm-carousal',
                'class' => Carousel::class,
                'css' => ['slider-carousel'],
                'js' => [],
                'vendor' => [
                    'css' => ['slick', 'slick-theme'],
                    'js' => ['jquery-slick'],
                ],
            ],
            'skills' => [
                'demo' => 'https://happyaddons.com/go/demo-skill-bar',
                'title' => __( 'Skill Bars', 'happy-elementor-addons' ),
                'icon' => 'hm hm-progress-bar',
                'class' => Skills::class,
                'css' => ['skills'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => ['elementor-waypoints', 'jquery-numerator'],
                ],
            ],
            'gradient-heading' => [
                'demo' => 'https://happyaddons.com/go/demo-gradient-heading',
                'title' => __( 'Gradient Heading', 'happy-elementor-addons' ),
                'icon' => 'hm hm-drag',
                'class' => Gradient_Heading::class,
                'css' => ['gradient-heading'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'wpform' => [
                'demo' => 'https://happyaddons.com/go/demo-wpforms',
                'title' => __( 'WPForms', 'happy-elementor-addons' ),
                'icon' => 'hm hm-form',
                'class' => WPForm::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'ninjaform' => [
                'demo' => 'https://happyaddons.com/go/demo-ninja-forms',
                'title' => __( 'Ninja Forms', 'happy-elementor-addons' ),
                'icon' => 'hm hm-form',
                'class' => NinjaForm::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'calderaform' => [
                'demo' => 'https://happyaddons.com/go/demo-caldera-forms',
                'title' => __( 'Caldera Forms', 'happy-elementor-addons' ),
                'icon' => 'hm hm-form',
                'class' => CalderaForm::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'weform' => [
                'demo' => 'https://happyaddons.com/go/demo-weforms',
                'title' => __( 'weForms', 'happy-elementor-addons' ),
                'icon' => 'hm hm-form',
                'class' => WeForm::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'logo-grid' => [
                'title' => __('Logo Grid', 'happy-elementor-addons'),
                'icon' => 'hm hm-logo-grid',
                'class' => Logo_Grid::class,
                'css' => ['logo-grid'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'dual-button' => [
                'demo' => 'https://happyaddons.com/go/demo-dual-button',
                'title' => __( 'Dual Button', 'happy-elementor-addons' ),
                'icon' => 'hm hm-accordion-horizontal',
                'class' => Dual_Button::class,
                'css' => ['dual-btn'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'testimonial' => [
                'demo' => 'https://happyaddons.com/go/demo-testimonial',
                'title' => __( 'Testimonial', 'happy-elementor-addons' ),
                'icon' => 'hm hm-testimonial',
                'class' => Testimonial::class,
                'css' => ['testimonial'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'number' => [
                'demo' => 'https://happyaddons.com/go/demo-number-widget',
                'title' => __( 'Number', 'happy-elementor-addons' ),
                'icon' => 'hm hm-madel',
                'class' => Number::class,
                'css' => ['number'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => ['elementor-waypoints', 'jquery-numerator'],
                ],
            ],
            'flip-box' => [
                'demo' => 'https://happyaddons.com/gp/demo-flip-box',
                'title' => __( 'Flip Box', 'happy-elementor-addons' ),
                'icon' => 'hm hm-flip-card1',
                'class' => Flip_Box::class,
                'css' => ['flip-box'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'calendly' => [
                'demo' => 'https://happyaddons.com/go/demo-calendly',
                'title' => __( 'Calendly', 'happy-elementor-addons' ),
                'icon' => 'hm hm-calendar',
                'class' => Calendly::class,
                'css' => [],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'pricing-table' => [
                'demo' => 'https://happyaddons.com/go/demo-pricing-table',
                'title' => __( 'Pricing Table', 'happy-elementor-addons' ),
                'icon' => 'hm hm-file-cabinet',
                'class' => Pricing_Table::class,
                'css' => ['pricing-table'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'step-flow' => [
                'demo' => 'https://happyaddons.com/go/demo-step-flow',
                'title' => __( 'Step Flow', 'happy-elementor-addons' ),
                'icon' => 'hm hm-step-flow',
                'class' => Step_Flow::class,
                'css' => ['steps-flow'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
        ];
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

        $inactive_widgets = self::get_inactive_widgets();

        foreach ( self::get_local_widgets_map() as $widget_key => $data ) {
            if ( ! empty( $data['class'] ) && ! in_array( $widget_key, $inactive_widgets ) ) {
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
