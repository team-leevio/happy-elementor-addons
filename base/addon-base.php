<?php
/**
 * Addon base
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Addons;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || die();

abstract class Addon_Base extends Widget_Base {

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
        return [ 'happy_addons' ];
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

    abstract protected function get_default_skin_preview();

    private function get_default_skin_args() {
        return [
            'title' => __( 'Default', 'happy_addons' ),
            'src' => esc_url( $this->get_default_skin_preview() ),
        ];
    }

    private function add_skin_control() {
        $skins = $this->get_skins();
        if ( ! empty( $skins ) ) {
            $skin_options = [];

            if ( $this->_has_template_content ) {
                $skin_options['_default'] = $this->get_default_skin_args();
            }

            foreach ( $skins as $skin_id => $skin ) {
                $skin_options[ $skin_id ] = [
                    'title' => $skin->get_title(),
                    'src' => $skin->get_preview_src()
                ];
            }

            // Get the first item for default value
            $default_value = array_keys( $skin_options );
            $default_value = array_shift( $default_value );

            $this->update_control(
                '_skin',
                [
                    'type' => 'select_preview',
                    'options' => $skin_options,
                    'default' => $default_value,
                ]
            );
        } else {
            $this->add_control(
                '_skin',
                [
                    'label' => __( 'Skin', 'happy_addons' ),
                    'type' => 'select_preview',
                    'options' => [
                        '_default' => $this->get_default_skin_args(),
                    ],
                    'default' => '_default',
                    'render_type' => 'ui'
                ]
            );
        }
    }

    private function add_design_panel() {
        $this->start_controls_section(
            '_design',
            [
                'label' => __( 'Design', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            '_design_notes',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => sprintf(
                    __( 'We know how bad you feel when you see the same design over and over again across the web. You don\'t have to feel that way anymore. %sJoin us and experience the exception.%s', 'plugin-name' ),
                    '<a href="https://fb.me/obiPlabon" target="_blank">',
                    '</a>'
                ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->add_skin_control();

        $this->end_controls_section();
    }

    protected function add_faq_panel() {
        $this->start_controls_section(
            '_faq',
            [
                'label' => __( 'FAQs', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            '_faq_notes',
            [
//                'label' => __( 'Frequelty', 'plugin-name' ),
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __( 'A very important message to show in the panel.', 'plugin-name' ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->end_controls_section();
    }

    protected function _register_controls() {
        $this->add_design_panel();

        $this->add_faq_panel();
    }

    protected function register_skin_control() {

    }

//    abstract protected function register_control();
}
