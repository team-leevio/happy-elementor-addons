<?php
/**
 * Flip Box widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class Flip_box extends Base {
    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Flip Box', 'happy-elementor-addons' );
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
        return 'hm hm-flip-card1';
    }

    public function get_keywords() {
        return [ 'flip box', 'box', 'flip' ];
    }

    protected function register_content_controls() {
        $this->start_controls_section(
            '_section_front',
            [
                'label' => __( 'Front Side', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'front_icon_type',
            [
                'label' => __( 'Icon Type', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'icon',
                'options' => [
                    'none' => [
                        'title' => __( 'None', 'happy-elementor-addons' ),
                        'icon' => 'eicon-close',
                    ],
                    'icon' => [
                        'title' => __( 'Icon', 'happy-elementor-addons' ),
                        'icon' => 'eicon-star',
                    ],
                    'image' => [
                        'title' => __( 'Image', 'happy-elementor-addons' ),
                        'icon' => 'eicon-image',
                    ],
                ],
                'toggle' => false,
            ]
        );

        $this->add_control(
            'front_icon',
            [
                'label' => __( 'Icon', 'happy-elementor-addons' ),
                'type' => Controls_Manager::ICON,
                'default' => 'fa fa-smile-o',
                'condition' => [
                  'front_icon_type' => 'icon'
                ],
                'options' => ha_get_happy_icons(),
            ]
        );

        $this->add_control(
            'front_icon_image',
            [
                'label' => __( 'Image', 'happy-elementor-addons' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'front_icon_type' => 'image'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'front_icon_thumbnail',
                'default' => 'thumbnail',
                'exclude' => [
                    'full',
                    'shop_catalog',
                    'shop_single',
                ],
                'condition' => [
                    'front_icon_type' => 'image'
                ]
            ]
        );

        $this->add_control(
            'front_content_text',
            [
                'label' => __( 'Title & Description', 'happy-elementor-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'front_title',
            [
                'label' => __( 'Title', 'happy-elementor-addons' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Happy Addons Rock', 'happy-elementor-addons' ),
                'placeholder' => __( 'Type Flip Box Title', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'front_description',
            [
                'label' => __( 'Description', 'happy-elementor-addons' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXTAREA,
                'default' => __( 'Best Elementor Addons', 'happy-elementor-addons' ),
                'placeholder' => __( 'Description', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'front_text_align',
            [
                'label' => __( 'Alignment', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'separator' => 'before',
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-front-inner .icon-wrap' => 'text-align: {{VALUE}}',
                    '{{WRAPPER}} .ha-flip-box-front-inner .ha-text' => 'text-align: {{VALUE}}'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_back',
            [
                'label' => __( 'Back Side', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'back_icon_type',
            [
                'label' => __( 'Icon Type', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'none',
                'options' => [
                    'none' => [
                        'title' => __( 'None', 'happy-elementor-addons' ),
                        'icon' => 'eicon-close',
                    ],
                    'icon' => [
                        'title' => __( 'Icon', 'happy-elementor-addons' ),
                        'icon' => 'eicon-star',
                    ],
                    'image' => [
                        'title' => __( 'Image', 'happy-elementor-addons' ),
                        'icon' => 'eicon-image',
                    ],
                ],
                'toggle' => false,
            ]
        );

        $this->add_control(
            'back_icon_image',
            [
                'label' => __( 'Image', 'happy-elementor-addons' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'back_icon_type' => 'image'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'back_icon_thumbnail',
                'default' => 'thumbnail',
                'exclude' => [
                    'full',
                    'shop_catalog',
                    'shop_single',
                ],
                'condition' => [
                    'back_icon_type' => 'image'
                ]
            ]
        );

        $this->add_control(
            'back_icon',
            [
                'label' => __( 'Icon', 'happy-elementor-addons' ),
                'type' => Controls_Manager::ICON,
                'return_value' => 'yes',
                'condition' => [
                    'back_icon_type' => 'icon',
                ],
                'options' => ha_get_happy_icons(),
            ]
        );

        $this->add_control(
            'back_content_text',
            [
                'label' => __( 'Title & Description', 'happy-elementor-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'back_title',
            [
                'label' => __( 'Title', 'happy-elementor-addons' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Happy Addons Rock', 'happy-elementor-addons' ),
                'placeholder' => __( 'Type Flip Box Title', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'back_description',
            [
                'label' => __( 'Description', 'happy-elementor-addons' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXTAREA,
                'default' => __( 'Best Elementor Addons', 'happy-elementor-addons' ),
                'placeholder' => __( 'Description', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'back_button_heading',
            [
                'label' => __( 'Button', 'happy-elementor-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __( 'Button Text', 'happy-elementor-addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Button Text', 'happy-elementor-addons' ),
                'placeholder' => __( 'Type button text here', 'happy-elementor-addons' ),
                'description' => __( 'Keep it blank, if you want to remove the button', 'happy-elementor-addons' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'button_link',
            [
                'label' => __( 'Link', 'happy-elementor-addons' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://example.com/', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'back_text_align',
            [
                'label' => __( 'Alignment', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'separator' => 'before',
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-back-inner .icon-wrap' => 'text-align: {{VALUE}}',
                    '{{WRAPPER}} .ha-flip-box-back-inner .ha-text' => 'text-align: {{VALUE}}',
                    '{{WRAPPER}} .ha-flip-box-back-inner .button-wrap' => 'text-align: {{VALUE}}',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_control_',
            [
                'label' => __( 'Settings', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'flip_position',
            [
                'label' => __( 'Flip Direction', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'right',
                'label_block' => false,
                'options' => [
                    'up' => [
                        'title' => __( 'Bottom To Top', 'happy-elementor-addons' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'right' => [
                        'title' => __( 'Left To Right', 'happy-elementor-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'down' => [
                        'title' => __( 'Top To Bottom', 'happy-elementor-addons' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'left' => [
                        'title' => __( 'Right To Left', 'happy-elementor-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                ],
                'toggle' => false,
            ]
        );

        $this->end_controls_section();

    }

    protected function register_style_controls() {
        $this->start_controls_section(
            '_section_common_style',
            [
                'label' => __( 'Common', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'height',
            [
                'label' => __( 'Height', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-front' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ha-flip-box-back' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'content_area_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'separator' => 'after',
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-front' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ha-flip-box-front:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ha-flip-box-back' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ha-flip-box-back:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // front side
        $this->start_controls_section(
            '_section_front_style',
            [
                'label' => __( 'Front Side', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'front_content_padding',
            [
                'label' => __( 'Padding', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-front' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'front_border',
                'selector' => '{{WRAPPER}} .ha-flip-box-front',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'front_box_shadow',
                'selector' => '{{WRAPPER}} .ha-flip-box-front',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'front_background_image',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ha-flip-box-front',
            ]
        );

        $this->add_control(
            'front_background_overlay',
            [
                'label' => __( 'Background Overlay', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'front_background_image_image[url]!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-wrap .ha-flip-box .ha-flip-box-front:before' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'front_background_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'condition' => [
                    'front_background_type' => 'color',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-front' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'front_icon_heading',
            [
                'label' => __( 'Icon Type - Icon', 'happy-elementor-addons' ),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'front_icon_type' => 'icon'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'front_icon_heading_image',
            [
                'label' => __( 'Icon Type - Image', 'happy-elementor-addons' ),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'front_icon_type' => 'image'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'front_icon_spacing',
            [
                'label' => __( 'Bottom Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-front .ha-flip-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'front_icon_image_size',
            [
                'label' => __( 'Resize Image', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'condition' => [
                    'front_icon_type' => 'image'
                ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 500,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-front-inner .icon-wrap .ha-flip-icon img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'front_icon_image_fit',
            [
                'label' => __( 'Image Fit', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'contain'  => __( 'Contain', 'happy-elementor-addons' ),
                    'cover' => __( 'Cover', 'happy-elementor-addons' ),
                ],
                'condition' => [
                    'front_icon_type' => 'image'
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-front-inner .icon-wrap .ha-flip-icon img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'front_icon_font_size',
            [
                'label' => __( 'Font Size', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'condition' => [
                    'front_icon_type' => 'icon'
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-front-inner .ha-flip-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'front_icon_background_size',
            [
                'label' => __( 'Padding', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'condition' => [
                    'front_icon_type' => [ 'icon', 'image' ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-front-inner .icon-wrap .ha-flip-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'front_icon_border',
                'condition' => [
                    'front_icon_type' => [ 'icon', 'image' ],
                ],
                'selector' => '{{WRAPPER}} .ha-flip-box-front-inner .icon-wrap .ha-flip-icon',
            ]
        );

        $this->add_control(
            'front_icon_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'condition' => [
                    'front_icon_type' => [ 'icon', 'image' ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-front-inner .icon-wrap .ha-flip-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ha-flip-box-front-inner .ha-flip-icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'front_icon_box_shadow',
                'selector' => '{{WRAPPER}} .ha-flip-box-front-inner .ha-flip-icon',
                'condition' => [
                    'front_icon_type' => [ 'icon', 'image' ],
                ],
            ]
        );

        $this->add_control(
            'front_icon_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'front_icon_type' => 'icon'
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-front-inner .icon-wrap .ha-flip-icon i' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'front_icon_background_color',
            [
                'label' => __( 'Background Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'front_icon_type' => [ 'icon', 'image' ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-front-inner .icon-wrap .ha-flip-icon' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'front_text',
            [
                'label' => __( 'Title & Description', 'happy-elementor-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs( '_tabs_front_text' );
        $this->start_controls_tab(
            '_tab_front_title',
            [
                'label' => __( 'Title', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'front_title_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-front-inner .ha-flip-box-heading' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'front_title_typography',
                'label' => __( 'Typography', 'happy-elementor-addons' ),
                'selector' => '{{WRAPPER}} .ha-flip-box-front-inner .ha-flip-box-heading',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'front_title_text_shadow',
                'label' => __( 'Text Shadow', 'happy-elementor-addons' ),
                'selector' => '{{WRAPPER}} .ha-flip-box-front-inner .ha-flip-box-heading',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_front_description',
            [
                'label' => __( 'Description', 'happy-elementor-addons' ),
            ]
        );

        $this->add_responsive_control(
            'front_description_space',
            [
                'label' => __( 'Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-front-inner .ha-text p' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'front_description_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-front-inner .ha-text p' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'front_description_typography',
                'label' => __( 'Typography', 'happy-elementor-addons' ),
                'selector' => '{{WRAPPER}} .ha-flip-box-front-inner .ha-text p',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'front_description_text_shadow',
                'label' => __( 'Text Shadow', 'happy-elementor-addons' ),
                'selector' => '{{WRAPPER}} .ha-flip-box-front-inner .ha-text p',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        // back side
        $this->start_controls_section(
            '_section_back_text_style',
            [
                'label' => __( 'Back Side', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'back_content_padding',
            [
                'label' => __( 'Padding', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-back' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'back_border',
                'selector' => '{{WRAPPER}} .ha-flip-box-back',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'back_box_shadow',
                'selector' => '{{WRAPPER}} .ha-flip-box-back',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'back_background_image',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ha-flip-box-back',
            ]
        );

        $this->add_control(
            'back_background_overlay',
            [
                'label' => __( 'Background Overlay', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.27)',
                'condition' => [
                    'back_background_image_background' => 'classic'
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-wrap .ha-flip-box .ha-flip-box-back:before' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'back_background_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#27374c',
                'condition' => [
                    'back_background_type' => 'color'
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-back' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'back_text',
            [
                'label' => __( 'Title & Description', 'happy-elementor-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs( '_tabs_back_text' );
        $this->start_controls_tab(
            '_tab_back_title',
            [
                'label' => __( 'Title', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'back_title_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-back-inner .ha-flip-box-heading-back' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'back_title_typography',
                'label' => __( 'Typography', 'happy-elementor-addons' ),
                'selector' => '{{WRAPPER}} .ha-flip-box-back-inner .ha-flip-box-heading-back',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'back_title_shadow',
                'label' => __( 'Text Shadow', 'happy-elementor-addons' ),
                'selector' => '{{WRAPPER}} .ha-flip-box-back-inner .ha-flip-box-heading-back',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_back_description',
            [
                'label' => __( 'Description', 'happy-elementor-addons' ),
            ]
        );

        $this->add_responsive_control(
            'back_description_space',
            [
                'label' => __( 'Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-back-inner .ha-text p' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'back_description_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-back-inner .ha-text p' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'back_description_typography',
                'label' => __( 'Typography', 'happy-elementor-addons' ),
                'selector' => '{{WRAPPER}} .ha-flip-box-back-inner .ha-text p',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'back_description_text_shadow',
                'label' => __( 'Text Shadow', 'happy-elementor-addons' ),
                'selector' => '{{WRAPPER}} .ha-flip-box-back-inner .ha-text p',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'back_icon_heading',
            [
                'label' => __( 'Icon Type - Icon', 'happy-elementor-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'back_icon_type' => 'icon'
                ],
            ]
        );

        $this->add_control(
            'back_icon_heading_image',
            [
                'label' => __( 'Icon Type - Image', 'happy-elementor-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'back_icon_type' => 'image'
                ],
            ]
        );

        $this->add_responsive_control(
            'back_icon_spacing',
            [
                'label' => __( 'Bottom Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%'],
                'condition' => [
                    'back_icon_type' => [ 'icon', 'image' ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-back-inner .ha-flip-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'back_icon_image_size',
            [
                'label' => __( 'Resize Image', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'condition' => [
                    'back_icon_type' => 'image'
                ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 500,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-back-inner .ha-flip-icon img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'back_icon_image_fit',
            [
                'label' => __( 'Image Fit', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'contain'  => __( 'Contain', 'happy-elementor-addons' ),
                    'cover' => __( 'Cover', 'happy-elementor-addons' ),
                ],
                'condition' => [
                    'back_icon_type' => 'image'
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-back-inner .icon-wrap .ha-flip-icon img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'back_icon_font_size',
            [
                'label' => __( 'Font Size', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'condition' => [
                    'back_icon_type' => 'icon'
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-back-inner .ha-flip-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'back_icon_padding',
            [
                'label' => __( 'Padding', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'condition' => [
                    'back_icon_type' => [ 'icon', 'image' ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-back-inner .icon-wrap .ha-flip-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'back_icon_border',
                'condition' => [
                    'back_icon_type' => [ 'icon', 'image' ],
                ],
                'selector' => '{{WRAPPER}} .ha-flip-box-back-inner .icon-wrap .ha-flip-icon',
            ]
        );

        $this->add_control(
            'back_icon_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'condition' => [
                    'back_icon_type' => [ 'icon', 'image']
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-back-inner .icon-wrap .ha-flip-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ha-flip-box-back-inner .ha-flip-icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'back_icon_box_shadow',
                'condition' => [
                    'back_icon_type' => [ 'icon', 'image']
                ],
                'selector' => '{{WRAPPER}} .ha-flip-box-back-inner .ha-flip-icon',
            ]
        );

        $this->add_control(
            'back_icon_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'back_icon_type' => 'icon'
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-back-inner .ha-flip-icon i' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'back_icon_background_color',
            [
                'label' => __( 'Background Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'back_icon_type' => [ 'icon', 'image' ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-back-inner .icon-wrap .ha-flip-icon' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'back_button',
            [
                'label' => __( 'Button', 'happy-elementor-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'back_button_spacing',
            [
                'label' => __( 'Top Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-back-inner .button-wrap' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'back_button_padding',
            [
                'label' => __( 'Padding', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .ha-flip-box-back-inner .button-wrap .ha-flip-btn',
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-back-inner .button-wrap .ha-flip-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'separator' => 'after',
                'selector' => '{{WRAPPER}} .ha-flip-box-back-inner .button-wrap .ha-flip-btn',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typography',
                'selector' => '{{WRAPPER}} .ha-flip-box-back-inner .button-wrap .ha-flip-btn',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
            ]
        );

        $this->start_controls_tabs( '_tabs_button' );

        $this->start_controls_tab(
            '_tab_button_normal',
            [
                'label' => __( 'Normal', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-wrap .ha-flip-box-back-inner .button-wrap .ha-flip-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => __( 'Background Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-wrap .ha-flip-box-back .ha-flip-box-back-inner .button-wrap .ha-flip-btn' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_button_hover',
            [
                'label' => __( 'Hover', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'link_hover_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-wrap .ha-flip-box-back .ha-flip-box-back-inner .button-wrap .ha-flip-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_bg_color',
            [
                'label' => __( 'Background Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-wrap .ha-flip-box-back .ha-flip-box-back-inner .button-wrap .ha-flip-btn:hover' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label' => __( 'Border Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'button_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-wrap .ha-flip-box-back .ha-flip-box-back-inner .button-wrap .ha-flip-btn:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // icon/image
        if ( $settings['front_icon_image']['id'] && isset( $settings['front_icon_image']['url'] ) ) {
            $this->add_render_attribute( 'front_icon_image', 'src', $settings['front_icon_image']['url'] );
            $this->add_render_attribute( 'front_icon_image', 'alt', Control_Media::get_image_alt( $settings['front_icon_image'] ) );
            $this->add_render_attribute( 'front_icon_image', 'title', Control_Media::get_image_title( $settings['front_icon_image'] ) );
        }

        // title & description
        $this->add_render_attribute( 'front_title', 'class', 'ha-flip-box-heading' );
        $this->add_render_attribute( 'back_title', 'class', 'ha-flip-box-heading-back' );
        $this->add_render_attribute( 'front_description', 'class', 'ha-desc' );
        $this->add_render_attribute( 'back_description', 'class', 'ha-desc' );
        $this->add_inline_editing_attributes( 'back_description', 'basic' );

        // button
        $this->add_render_attribute( 'button', 'class', 'ha-flip-btn' );
        $this->add_render_attribute( 'button', 'href', esc_url( $settings['button_link']['url'] ) );
        if ( ! empty( $settings['button_link']['is_external'] ) ) {
            $this->add_render_attribute( 'button', 'target', '_blank' );
        }
        if ( ! empty( $settings['button_link']['nofollow'] ) ) {
            $this->set_render_attribute( 'button', 'rel', 'nofollow' );
        }

        // display type
        $this->add_render_attribute( 'display', 'class', 'ha-flip-wrap ha-flip-effect-classic' );

        // flip position
        if ( $settings['flip_position'] === 'up' ) {
            $this->add_render_attribute( 'flip-position', 'class', 'ha-flip-box ha-flip-up' );
        } elseif ( $settings['flip_position'] === 'right' ) {
            $this->add_render_attribute( 'flip-position', 'class', 'ha-flip-box ha-flip-right' );
        } elseif ( $settings['flip_position'] === 'down' ) {
            $this->add_render_attribute( 'flip-position', 'class', 'ha-flip-box ha-flip-down' );
        } elseif ( $settings['flip_position'] === 'left' ) {
            $this->add_render_attribute( 'flip-position', 'class', 'ha-flip-box ha-flip-left' );
        }
        ?>

        <div <?php echo $this->get_render_attribute_string( 'display' ); ?>>

            <div <?php echo $this->get_render_attribute_string( 'flip-position' ); ?>>
                <div class="ha-flip-box-inner-wrapper">
                    <div class="ha-flip-box-front">
                        <div class="ha-flip-box-front-inner">

                            <?php if ( $settings['front_icon'] ) : ?>
                                <div class="icon-wrap">
                                    <div class="ha-flip-icon icon">
                                        <i class="<?php echo esc_attr( $settings['front_icon'] ); ?>"></i>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ( $settings['front_icon_image'] ) : ?>
                                <div class="icon-wrap">
                                    <div class="ha-flip-icon">
                                        <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'front_icon_thumbnail', 'front_icon_image' ); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="ha-text">
                                <?php if ( $settings['front_title'] ) : ?>
                                    <h2 <?php echo $this->get_render_attribute_string( 'front_title' ); ?>>
                                        <?php echo esc_html( $settings['front_title'] ); ?>
                                    </h2>
                                <?php endif; ?>

                                <?php if ( $settings['front_description'] ) : ?>
                                    <p <?php echo $this->get_render_attribute_string( 'front_description' ); ?>>
                                        <?php echo esc_html( $settings['front_description'] ); ?>
                                    </p>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>

                    <div class="ha-flip-box-back">
                        <div class="ha-flip-box-back-inner">
                            <?php if ( $settings['back_icon'] ) : ?>
                                <div class="icon-wrap">
                                    <div class="ha-flip-icon">
                                        <i class="<?php echo esc_attr( $settings['back_icon'] ); ?>"></i>
                                    </div>
                                </div>
                            <?php endif; ?>

                           <?php if ( $settings['back_icon_image'] ) : ?>
                             <div class="icon-wrap">
                                <div class="ha-flip-icon">
                                    <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'back_icon_thumbnail', 'back_icon_image' ); ?>
                                </div>
                             </div>
                            <?php endif; ?>

                            <div class="ha-text">
                                <?php if ( $settings['back_title'] ) : ?>
                                    <h2 <?php echo $this->get_render_attribute_string( 'back_title' ); ?>>
                                        <?php echo esc_html( $settings['back_title'] ); ?>
                                    </h2>
                                <?php endif; ?>

                                <?php if ( $settings['back_description'] ) : ?>
                                    <p <?php echo $this->get_render_attribute_string( 'back_description' ) ?>>
                                        <?php echo $settings['back_description'] ?>
                                    </p>
                                <?php endif; ?>
                            </div>

                            <?php if ( $settings['button_text'] ) : ?>
                                <div class="button-wrap">
                                    <a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
                                        <?php echo esc_html( $settings['button_text'] ); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    <?php
    }

//    public function _content_template() {}

}
