<?php
/**
 * Feature List widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class Feature_List extends Base {
    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Feature List', 'happy-elementor-addons' );
    }

    /**
     * Get widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'hm hm-spark';
    }

    public function get_keywords() {
        return [ 'list', 'feature list' ];
    }

    protected function register_content_controls() {
        $this->start_controls_section(
            '_section_lists',
            [
                'label' => __( 'Feature List', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'icon_type',
            [
                'label' => __( 'Icon Type', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'icon'	=> __( 'Icon', 'happy-elementor-addons' ),
                    'number' => __( 'Number', 'happy-elementor-addons' ),
                    'image'	=> __( 'Image', 'happy-elementor-addons' ),
                ],
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => __( 'Icon', 'happy-elementor-addons' ),
                'type' => Controls_Manager::ICON,
                'default' => 'fa fa-smile-o',
                'condition' => [
                    'icon_type' => 'icon'
                ],
                'options' => ha_get_happy_icons(),
            ]
        );

        $repeater->add_control(
            'number',
            [
                'label' => __( 'Item Number', 'happy-elementor-addons' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => __( 'List Item Number', 'happy-elementor-addons' ),
                'default' => __( '1', 'happy-elementor-addons' ),
                'condition' => [
                    'icon_type' => 'icon'
                ],
            ]
        );

        $repeater->add_control(
            'icon_image',
            [
                'label' => __( 'Image', 'happy-elementor-addons' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'icon_type' => 'image'
                ]
            ]
        );

        $repeater->add_control(
            'text',
            [
                'label' => __( 'Text', 'happy-elementor-addons' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => __( 'List Item', 'happy-elementor-addons' ),
                'default' => __( 'List Item', 'happy-elementor-addons' ),
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => __( 'Link', 'happy-elementor-addons' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://example.com', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'list_item',
            [
                'show_label' => false,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ text }}}',
                'default' => [
                    [
                        'text' => __( 'List Item', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-smile-o',
                    ],
                    [
                        'text' => __( 'List Item', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-smile-o',
                    ],
                    [
                        'text' => __( 'List Item', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-smile-o',
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_common',
            [
                'label' => __( 'Lists', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'thumbnail',
                'exclude' => [
                    'full',
                    'shop_catalog',
                    'shop_single',
                ],
                'condition' => [
                    'icon_type' => 'image'
                ]
            ]
        );

        $this->end_controls_section();

    }

    protected function register_style_controls() {
        $this->start_controls_section(
            '_section_common_style',
            [
                'label' => __( 'Style', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {
//        $settings = $this->get_settings_for_display();
        ?>



        <?php
    }

//    public function _content_template() {}

}