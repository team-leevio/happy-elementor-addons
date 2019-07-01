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
        $name = str_replace( __NAMESPACE__, '', $this->get_class_name() );
        $name = str_replace( '_', '-', $name );
        $name = ltrim( $name, '\\' );
        $name = strtolower( $name );
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
        return [ 'happy-elementor-addons' ];
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

    protected function get_presets() {
        return [];
    }

    /**
     * Register design controls
     *
     * Design controls are fixed for all widgets
     */
    private function register_design_controls() {
        $presets = $this->get_presets();
        if ( empty( $presets ) ) {
            return;
        }

        $this->start_controls_section(
            '_design',
            [
                'label' => __( 'Design', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            '_preset',
            [
                'label' => __( 'Preset', 'happy-elementor-addons' ),
                'type' => 'select',
                'options' => ['' => __( 'Default', 'happy-elementor-addons' )] + $presets,
                'default' => '',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register faq controls
     */
    protected function register_faq_controls() {
        $this->start_controls_section(
            '_faq',
            [
                'label' => __( 'FAQs', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            '_faq_notes',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __( 'A very important message to show in the panel.', 'plugin-name' ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register widget controls
     */
    protected function _register_controls() {
        $this->register_design_controls();

        $this->register_content_controls();

        $this->register_style_controls();
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
}
