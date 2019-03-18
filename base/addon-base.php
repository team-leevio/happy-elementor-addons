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

    protected function _register_controls() {
        $this->start_controls_section(
            '_design',
            [
                'label' => __( 'Design', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $skins = $this->get_skins();

        if ( empty( $skins ) ) {
            $this->add_control(
                'skin',
                [
                    'label' => __('Skin', 'elementor'),
                    'type' => 'select_preview',
                    'default' => 'default',
                    'options' => [
                        'default' => [
                            'title' => __( 'Default', 'happy_addons' ),
                            'src' => 'https://via.placeholder.com/300.png/02f/f2f',
                        ],
                        'default2' => [
                            'title' => __( 'Default 2', 'happy_addons' ),
                            'src' => 'https://via.placeholder.com/300.png/09f/fff',
                        ],
                    ],
//                    'render_type' => ''
                ]
            );
        }

        $this->end_controls_section();


//        $this->register_control();
    }

    protected function register_skin_control() {

    }

//    abstract protected function register_control();
}
