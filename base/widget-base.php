<?php
/**
 * Happy Addons widget base
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die();

abstract class Base extends Widget_Base {

    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        /**
         * Automatically generate widget name from class
         *
         * Card will be card
         * Blog_Card will be blog-card
         */
        $name = str_replace( strtolower(__NAMESPACE__), '', strtolower($this->get_class_name()) );
        $name = str_replace( '_', '-', $name );
        $name = ltrim( $name, '\\' );
        return 'ha-' . $name;
    }

    /**
     * Get widget categories.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return [ 'happy_addons_category' ];
    }

    /**
     * Override from addon to add custom wrapper class.
     *
     * @return string
     */
    protected function get_custom_wrapper_class() {
        return '';
    }

    /**
     * Overriding default function to add custom html class.
     *
     * @return string
     */
    public function get_html_wrapper_class() {
        $html_class = parent::get_html_wrapper_class();
        $html_class .= ' happy-addon';
        $html_class .= ' ' . $this->get_name();
        $html_class .= ' ' . $this->get_custom_wrapper_class();
        return rtrim( $html_class );
    }

    /**
     * Register widget controls
     */
    protected function _register_controls() {
        do_action( 'happyaddons_start_register_controls', $this );

        $this->register_content_controls();

        $this->register_style_controls();

        do_action( 'happyaddons_end_register_controls', $this );
    }

    /**
     * Register content controls
     *
     * @return void
     */
    abstract protected function register_content_controls();

    /**
     * Register style controls
     *
     * @return void
     */
    abstract protected function register_style_controls();

    /**
     * Fix for 2.6.*
     *
     * In 2.6.0 Elementor removed render_edit_tools method.
     */
    protected function render_edit_tools() {
        if ( ha_is_elementor_version( '<=', '2.5.16' ) ) {
            parent::render_edit_tools();
        }
    }

    /**
     * Overriding the parent method
     *
     * Add inline editing attributes.
     *
     * Define specific area in the element to be editable inline. The element can have several areas, with this method
     * you can set the area inside the element that can be edited inline. You can also define the type of toolbar the
     * user will see, whether it will be a basic toolbar or an advanced one.
     *
     * Note: When you use wysiwyg control use the advanced toolbar, with textarea control use the basic toolbar. Text
     * control should not have toolbar.
     *
     * PHP usage (inside `Widget_Base::render()` method):
     *
     *    $this->add_inline_editing_attributes( 'text', 'advanced' );
     *    echo '<div ' . $this->get_render_attribute_string( 'text' ) . '>' . $this->get_settings( 'text' ) . '</div>';
     *
     * @since 1.8.0
     * @access protected
     *
     * @param string $key     Element key.
     * @param string $toolbar Optional. Toolbar type. Accepted values are `advanced`, `basic` or `none`. Default is
     *                        `basic`.
     * @param string $setting_key Additional settings key in case $key != $setting_key
     */
    protected function add_inline_editing_attributes( $key, $toolbar = 'basic', $setting_key = '' ) {
        if ( ! ha_elementor()->editor->is_edit_mode() ) {
            return;
        }

        if ( empty( $setting_key ) ) {
            $setting_key = $key;
        }

        $this->add_render_attribute( $key, [
            'class' => 'elementor-inline-editing',
            'data-elementor-setting-key' => $setting_key,
        ] );

        if ( 'basic' !== $toolbar ) {
            $this->add_render_attribute( $key, [
                'data-elementor-inline-editing-toolbar' => $toolbar,
            ] );
        }
    }
}
