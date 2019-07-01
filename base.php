<?php
/**
 * Plugin base class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor;

use Happy_Addons\Elementor\Manager\Assets;
use Happy_Addons\Elementor\Manager\Widgets;
use Happy_Addons\Elementor\Extension\Happy_Effects;

defined( 'ABSPATH' ) || die();

class Base {

    const VERSION = '1.0.4';

    const MINIMUM_ELEMENTOR_VERSION = '2.5.0';

    const MINIMUM_PHP_VERSION = '5.4';

    private static $instance = null;

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'init', [ $this, 'i18n' ] );
        add_action( 'plugins_loaded', [ $this, 'init' ] );
    }

    public function i18n() {
        load_plugin_textdomain( 'happy-elementor-addons' );
    }

    public function init() {
        // Check if Elementor installed and activated
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [$this, 'admin_notice_missing_elementor'] );
            return;
        }

        // Check for required Elementor version
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [$this, 'admin_notice_minimum_elementor_version'] );
            return;
        }

        // Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [$this, 'admin_notice_minimum_php_version'] );
            return;
        }

        $this->include_files();

        // Register custom category
        add_action( 'elementor/elements/categories_registered', [$this, 'add_category'] );

        // Register custom controls
        add_action( 'elementor/controls/controls_registered', [$this, 'register_controls'] );

        add_action( 'wp_ajax_ha_get_preset', [$this, 'handle_widget_preset_request'] );

        Widgets::init();
        Assets::init();
        Happy_Effects::init();
    }

    public function handle_widget_preset_request() {
        if ( ! check_ajax_referer( 'ha_get_preset', 'nonce' ) ) {
            return;
        }
        $payload = '{"badge_text":"","title":"sd","description":"Happy card description goes here","button_text":"Button Text","widgetType":"ha-card","elType":"widget","isInner":false,"_preset":"","image":{"url":"http://woofront.local/wp-content/plugins/happy-elementor-addons/assets/imgs/placeholder.jpg","id":""},"thumbnail_size":"large","thumbnail_custom_dimension":{"width":"","height":""},"image_position":"top","title_tag":"h2","align":"","align_tablet":"","align_mobile":"","button_link":{"url":"","is_external":"","nofollow":""},"button_icon":"","button_icon_position":"before","button_icon_spacing":{"unit":"px","size":"","sizes":[]},"image_width":{"unit":"px","size":277,"sizes":[]},"image_width_tablet":{"unit":"px","size":"","sizes":[]},"image_width_mobile":{"unit":"px","size":"","sizes":[]},"image_height":{"unit":"px","size":423,"sizes":[]},"image_height_tablet":{"unit":"px","size":"","sizes":[]},"image_height_mobile":{"unit":"px","size":"","sizes":[]},"offset_toggle":"","image_offset_x":{"unit":"px","size":1,"sizes":[]},"image_offset_x_tablet":{"unit":"px","size":"","sizes":[]},"image_offset_x_mobile":{"unit":"px","size":"","sizes":[]},"image_offset_y":{"unit":"px","size":1,"sizes":[]},"image_offset_y_tablet":{"unit":"px","size":"","sizes":[]},"image_offset_y_mobile":{"unit":"px","size":"","sizes":[]},"image_padding":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"image_padding_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"image_padding_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"image_border_border":"","image_border_width":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"image_border_width_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"image_border_width_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"image_border_color":"","image_border_radius":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"image_border_radius_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"image_border_radius_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"image_box_shadow_box_shadow_type":"","image_box_shadow_box_shadow":{"horizontal":0,"vertical":0,"blur":10,"spread":0,"color":"rgba(0,0,0,0.5)"},"badge_position":"top-right","badge_offset_toggle":"","badge_offset_x":{"unit":"px","size":1,"sizes":[]},"badge_offset_x_tablet":{"unit":"px","size":"","sizes":[]},"badge_offset_x_mobile":{"unit":"px","size":"","sizes":[]},"badge_offset_y":{"unit":"px","size":1,"sizes":[]},"badge_offset_y_tablet":{"unit":"px","size":"","sizes":[]},"badge_offset_y_mobile":{"unit":"px","size":"","sizes":[]},"badge_padding":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"badge_padding_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"badge_padding_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"badge_color":"","badge_bg_color":"","badge_border_border":"","badge_border_width":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"badge_border_width_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"badge_border_width_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"badge_border_color":"","badge_border_radius":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"badge_border_radius_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"badge_border_radius_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"badge_box_shadow_box_shadow_type":"","badge_box_shadow_box_shadow":{"horizontal":0,"vertical":0,"blur":10,"spread":0,"color":"rgba(0,0,0,0.5)"},"badge_typography_typography":"","badge_typography_font_family":"","badge_typography_font_size":{"unit":"px","size":"","sizes":[]},"badge_typography_font_size_tablet":{"unit":"px","size":"","sizes":[]},"badge_typography_font_size_mobile":{"unit":"px","size":"","sizes":[]},"badge_typography_font_weight":"","badge_typography_text_transform":"","badge_typography_font_style":"","badge_typography_text_decoration":"","badge_typography_letter_spacing":{"unit":"px","size":"","sizes":[]},"badge_typography_letter_spacing_tablet":{"unit":"px","size":"","sizes":[]},"badge_typography_letter_spacing_mobile":{"unit":"px","size":"","sizes":[]},"content_padding":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"content_padding_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"content_padding_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"title_spacing":{"unit":"px","size":"","sizes":[]},"title_spacing_tablet":{"unit":"px","size":"","sizes":[]},"title_spacing_mobile":{"unit":"px","size":"","sizes":[]},"title_color":"","title_typography_typography":"","title_typography_font_family":"","title_typography_font_size":{"unit":"px","size":"","sizes":[]},"title_typography_font_size_tablet":{"unit":"px","size":"","sizes":[]},"title_typography_font_size_mobile":{"unit":"px","size":"","sizes":[]},"title_typography_font_weight":"","title_typography_text_transform":"","title_typography_font_style":"","title_typography_text_decoration":"","title_typography_line_height":{"unit":"em","size":"","sizes":[]},"title_typography_line_height_tablet":{"unit":"em","size":"","sizes":[]},"title_typography_line_height_mobile":{"unit":"em","size":"","sizes":[]},"title_typography_letter_spacing":{"unit":"px","size":"","sizes":[]},"title_typography_letter_spacing_tablet":{"unit":"px","size":"","sizes":[]},"title_typography_letter_spacing_mobile":{"unit":"px","size":"","sizes":[]},"description_spacing":{"unit":"px","size":"","sizes":[]},"description_spacing_tablet":{"unit":"px","size":"","sizes":[]},"description_spacing_mobile":{"unit":"px","size":"","sizes":[]},"description_color":"","description_typography_typography":"","description_typography_font_family":"","description_typography_font_size":{"unit":"px","size":"","sizes":[]},"description_typography_font_size_tablet":{"unit":"px","size":"","sizes":[]},"description_typography_font_size_mobile":{"unit":"px","size":"","sizes":[]},"description_typography_font_weight":"","description_typography_text_transform":"","description_typography_font_style":"","description_typography_text_decoration":"","description_typography_line_height":{"unit":"em","size":"","sizes":[]},"description_typography_line_height_tablet":{"unit":"em","size":"","sizes":[]},"description_typography_line_height_mobile":{"unit":"em","size":"","sizes":[]},"description_typography_letter_spacing":{"unit":"px","size":"","sizes":[]},"description_typography_letter_spacing_tablet":{"unit":"px","size":"","sizes":[]},"description_typography_letter_spacing_mobile":{"unit":"px","size":"","sizes":[]},"button_padding":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"button_padding_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"button_padding_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"button_typography_typography":"","button_typography_font_family":"","button_typography_font_size":{"unit":"px","size":"","sizes":[]},"button_typography_font_size_tablet":{"unit":"px","size":"","sizes":[]},"button_typography_font_size_mobile":{"unit":"px","size":"","sizes":[]},"button_typography_font_weight":"","button_typography_text_transform":"","button_typography_font_style":"","button_typography_text_decoration":"","button_typography_line_height":{"unit":"em","size":"","sizes":[]},"button_typography_line_height_tablet":{"unit":"em","size":"","sizes":[]},"button_typography_line_height_mobile":{"unit":"em","size":"","sizes":[]},"button_typography_letter_spacing":{"unit":"px","size":"","sizes":[]},"button_typography_letter_spacing_tablet":{"unit":"px","size":"","sizes":[]},"button_typography_letter_spacing_mobile":{"unit":"px","size":"","sizes":[]},"button_border_border":"","button_border_width":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"button_border_width_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"button_border_width_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"button_border_color":"","button_border_radius":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"button_box_shadow_box_shadow_type":"","button_box_shadow_box_shadow":{"horizontal":0,"vertical":0,"blur":10,"spread":0,"color":"rgba(0,0,0,0.5)"},"button_box_shadow_box_shadow_position":" ","button_color":"","button_bg_color":"","button_hover_color":"","button_hover_bg_color":"","button_hover_border_color":"","_title":"","_margin":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"_margin_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"_margin_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"_padding":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"_padding_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"_padding_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"_z_index":"","_element_id":"","_css_classes":"","ha_floating_fx":"","ha_floating_fx_translate_toggle":"","ha_floating_fx_translate_x":{"unit":"px","size":"","sizes":[]},"ha_floating_fx_translate_y":{"unit":"px","size":"","sizes":[]},"ha_floating_fx_translate_duration":{"unit":"px","size":1000,"sizes":[]},"ha_floating_fx_translate_delay":{"unit":"px","size":"","sizes":[]},"ha_floating_fx_rotate_toggle":"","ha_floating_fx_rotate_x":{"unit":"px","size":"","sizes":[]},"ha_floating_fx_rotate_y":{"unit":"px","size":"","sizes":[]},"ha_floating_fx_rotate_z":{"unit":"px","size":"","sizes":[]},"ha_floating_fx_rotate_duration":{"unit":"px","size":1000,"sizes":[]},"ha_floating_fx_rotate_delay":{"unit":"px","size":"","sizes":[]},"ha_floating_fx_scale_toggle":"","ha_floating_fx_scale_x":{"unit":"px","size":"","sizes":[]},"ha_floating_fx_scale_y":{"unit":"px","size":"","sizes":[]},"ha_floating_fx_scale_duration":{"unit":"px","size":1000,"sizes":[]},"ha_floating_fx_scale_delay":{"unit":"px","size":"","sizes":[]},"_animation":"","_animation_tablet":"","_animation_mobile":"","animation_duration":"","_animation_delay":"","_background_background":"","_background_color":"","_background_color_stop":{"unit":"%","size":0,"sizes":[]},"_background_color_b":"#f2295b","_background_color_b_stop":{"unit":"%","size":100,"sizes":[]},"_background_gradient_type":"linear","_background_gradient_angle":{"unit":"deg","size":180,"sizes":[]},"_background_gradient_position":"center center","_background_image":{"url":"","id":""},"_background_image_tablet":{"url":"","id":""},"_background_image_mobile":{"url":"","id":""},"_background_position":"","_background_position_tablet":"","_background_position_mobile":"","_background_xpos":{"unit":"px","size":0,"sizes":[]},"_background_xpos_tablet":{"unit":"px","size":0,"sizes":[]},"_background_xpos_mobile":{"unit":"px","size":0,"sizes":[]},"_background_ypos":{"unit":"px","size":0,"sizes":[]},"_background_ypos_tablet":{"unit":"px","size":0,"sizes":[]},"_background_ypos_mobile":{"unit":"px","size":0,"sizes":[]},"_background_attachment":"","_background_repeat":"","_background_repeat_tablet":"","_background_repeat_mobile":"","_background_size":"","_background_size_tablet":"","_background_size_mobile":"","_background_bg_width":{"unit":"%","size":100,"sizes":[]},"_background_bg_width_tablet":{"unit":"px","size":"","sizes":[]},"_background_bg_width_mobile":{"unit":"px","size":"","sizes":[]},"_background_video_link":"","_background_video_start":"","_background_video_end":"","_background_video_fallback":{"url":"","id":""},"_background_hover_background":"","_background_hover_color":"","_background_hover_color_stop":{"unit":"%","size":0,"sizes":[]},"_background_hover_color_b":"#f2295b","_background_hover_color_b_stop":{"unit":"%","size":100,"sizes":[]},"_background_hover_gradient_type":"linear","_background_hover_gradient_angle":{"unit":"deg","size":180,"sizes":[]},"_background_hover_gradient_position":"center center","_background_hover_image":{"url":"","id":""},"_background_hover_image_tablet":{"url":"","id":""},"_background_hover_image_mobile":{"url":"","id":""},"_background_hover_position":"","_background_hover_position_tablet":"","_background_hover_position_mobile":"","_background_hover_xpos":{"unit":"px","size":0,"sizes":[]},"_background_hover_xpos_tablet":{"unit":"px","size":0,"sizes":[]},"_background_hover_xpos_mobile":{"unit":"px","size":0,"sizes":[]},"_background_hover_ypos":{"unit":"px","size":0,"sizes":[]},"_background_hover_ypos_tablet":{"unit":"px","size":0,"sizes":[]},"_background_hover_ypos_mobile":{"unit":"px","size":0,"sizes":[]},"_background_hover_attachment":"","_background_hover_repeat":"","_background_hover_repeat_tablet":"","_background_hover_repeat_mobile":"","_background_hover_size":"","_background_hover_size_tablet":"","_background_hover_size_mobile":"","_background_hover_bg_width":{"unit":"%","size":100,"sizes":[]},"_background_hover_bg_width_tablet":{"unit":"px","size":"","sizes":[]},"_background_hover_bg_width_mobile":{"unit":"px","size":"","sizes":[]},"_background_hover_video_link":"","_background_hover_video_start":"","_background_hover_video_end":"","_background_hover_video_fallback":{"url":"","id":""},"_background_hover_transition":{"unit":"px","size":"","sizes":[]},"_border_border":"","_border_width":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"_border_width_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"_border_width_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"_border_color":"","_border_radius":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"_border_radius_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"_border_radius_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"_box_shadow_box_shadow_type":"","_box_shadow_box_shadow":{"horizontal":0,"vertical":0,"blur":10,"spread":0,"color":"rgba(0,0,0,0.5)"},"_box_shadow_box_shadow_position":" ","_border_hover_border":"","_border_hover_width":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"_border_hover_width_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"_border_hover_width_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"_border_hover_color":"","_border_radius_hover":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"_border_radius_hover_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"_border_radius_hover_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"_box_shadow_hover_box_shadow_type":"","_box_shadow_hover_box_shadow":{"horizontal":0,"vertical":0,"blur":10,"spread":0,"color":"rgba(0,0,0,0.5)"},"_box_shadow_hover_box_shadow_position":" ","_border_hover_transition":{"unit":"px","size":"","sizes":[]},"_element_width":"","_element_width_tablet":"","_element_width_mobile":"","_element_custom_width":{"unit":"px","size":"","sizes":[]},"_element_custom_width_tablet":{"unit":"px","size":"","sizes":[]},"_element_custom_width_mobile":{"unit":"px","size":"","sizes":[]},"_element_vertical_align":"","_element_vertical_align_tablet":"","_element_vertical_align_mobile":"","_position":"","_offset_orientation_h":"start","_offset_x":{"unit":"px","size":"0","sizes":[]},"_offset_x_tablet":{"unit":"px","size":"","sizes":[]},"_offset_x_mobile":{"unit":"px","size":"","sizes":[]},"_offset_x_end":{"unit":"px","size":"0","sizes":[]},"_offset_x_end_tablet":{"unit":"px","size":"","sizes":[]},"_offset_x_end_mobile":{"unit":"px","size":"","sizes":[]},"_offset_orientation_v":"start","_offset_y":{"unit":"px","size":"0","sizes":[]},"_offset_y_tablet":{"unit":"px","size":"","sizes":[]},"_offset_y_mobile":{"unit":"px","size":"","sizes":[]},"_offset_y_end":{"unit":"px","size":"0","sizes":[]},"_offset_y_end_tablet":{"unit":"px","size":"","sizes":[]},"_offset_y_end_mobile":{"unit":"px","size":"","sizes":[]},"hide_desktop":"","hide_tablet":"","hide_mobile":""}';
        wp_send_json_success( $payload );
    }

    public function include_files() {
        require( __DIR__ . '/inc/functions.php' );
        require( __DIR__ . '/inc/happy-icons.php' );
        require( __DIR__ . '/classes/widget-manager.php' );
        require( __DIR__ . '/classes/asset-manager.php' );
        require( __DIR__ . '/classes/happy-effects.php' );
    }

    /**
     * Add custom category.
     *
     * @param $elements_manager
     */
    public function add_category( $elements_manager ) {
        $elements_manager->add_category(
            'happy_addons_category',
            [
                'title' => __( 'Happy Addons', 'happy-elementor-addons' ),
                'icon' => 'fa fa-smile-o',
            ]
        );
    }

    /**
     * Admin notice.
     *
     * Warning when the site doesn't have Elementor installed or activated.
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_notice_missing_elementor() {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
        /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'happy-elementor-addons' ),
            '<strong>' . esc_html__( 'Happy Elementor Addons', 'happy-elementor-addons' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'happy-elementor-addons' ) . '</strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required Elementor version.
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_notice_minimum_elementor_version() {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
        /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'happy-elementor-addons' ),
            '<strong>' . esc_html__( 'Happy Elementor Addons', 'happy-elementor-addons' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'happy-elementor-addons' ) . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required PHP version.
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_notice_minimum_php_version() {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
        /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'happy-elementor-addons' ),
            '<strong>' . esc_html__( 'Happy Elementor Addons', 'happy-elementor-addons' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'happy-elementor-addons' ) . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    /**
     * Register custom controls
     *
     * Include custom controls file and register them
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function register_controls() {
        require( __DIR__ . '/controls/select-preview.php' );
        require( __DIR__ . '/controls/foreground.php' );
        $select_preview = __NAMESPACE__ . '\Controls\Select_Preview';
        $foreground = __NAMESPACE__ . '\Controls\Group_Control_Foreground';
        \Elementor\Plugin::instance()->controls_manager->register_control( 'select_preview', new $select_preview() );
        \Elementor\Plugin::instance()->controls_manager->add_group_control( $foreground::get_type(), new $foreground() );
    }

}
