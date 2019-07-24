<?php
/**
 * Flip Box widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
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
        return 'hm hm-spark';
    }

    public function get_keywords() {
        return [ 'flip box', 'box', 'flip' ];
    }

    protected function register_content_controls() {
        $this->start_controls_section(
            '_section_front_',
            [
                'label' => __( 'Front Side', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'front_content_icon',
            [
                'label' => __( 'Icon', 'happy-elementor-addons' ),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'front_icon_type',
            [
                'label' => __( 'Icon Type', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none'	=> __( 'None', 'happy-elementor-addons' ),
                    'image'	=> __( 'Image', 'happy-elementor-addons' ),
                    'icon'	=> __( 'Icon', 'happy-elementor-addons' )
                ],
                'default' => 'icon',
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
            'front_icon_position',
            [
                'label' => __( 'Position', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'top',
                'options' => [
                    'top' => [
                        'title' => __( 'Top', 'happy-elementor-addons' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'bottom' => [
                        'title' => __( 'Bottom', 'happy-elementor-addons' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'condition' => [
                    'front_icon_type' => [ 'icon', 'image' ],
                ],
                'selectors_dictionary' => [
                    'top' => 'flex-direction: column',
                    'bottom' => 'flex-direction: column-reverse',
                ],
                'prefix_class' => 'ha-flip-icon--',
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-front-inner' => '{{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'front_icon_align',
            [
                'label' => __( 'Alignment', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'happy-elementor-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'happy-elementor-addons' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'happy-elementor-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors_dictionary' => [
                    'left' => 'justify-content: flex-start',
                    'center' => 'justify-content: center',
                    'right' => 'justify-content: flex-end',
                ],
                'condition' => [
                    'front_icon_type' => [ 'icon', 'image' ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-front-inner .icon-wrap' => '{{VALUE}}'
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
                'default' => __( 'Happy Addon Rocks', 'happy-elementor-addons' ),
                'placeholder' => __( 'Type Flip Box Title', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'front_description',
            [
                'label' => __( 'Description', 'happy-elementor-addons' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXTAREA,
                'default' => __( 'Type Detail Here', 'happy-elementor-addons' ),
                'placeholder' => __( 'Description', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'front_text_align',
            [
                'label' => __( 'Text Alignment', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'center',
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
            'back_content_icon',
            [
                'label' => __( 'Icon', 'happy-elementor-addons' ),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'back_icon_type',
            [
                'label' => __( 'Icon Type', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    'none'	=> __( 'None', 'happy-elementor-addons' ),
                    'image'	=> __( 'Image', 'happy-elementor-addons' ),
                    'icon'	=> __( 'Icon', 'happy-elementor-addons' )
                ],
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
                'default' => '',
                'return_value' => 'yes',
                'condition' => [
                        'back_icon_type' => 'icon',
                ],
                'options' => ha_get_happy_icons(),
            ]
        );

        $this->add_control(
            'back_icon_align',
            [
                'label' => __( 'Alignment', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => [
                    'top' => [
                        'title' => __( 'Left', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'bottom' => [
                        'title' => __( 'Right', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'condition' => [
                    'back_icon_type' => [ 'icon', 'image' ],
                ],
                'selectors_dictionary' => [
                    'top' => 'justify-content: flex-start',
                    'center' => 'justify-content: center',
                    'bottom' => 'justify-content: flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-back-inner .icon-wrap' => '{{VALUE}}'
                ]
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
                'default' => __( 'Happy Addon Rocks', 'happy-elementor-addons' ),
                'placeholder' => __( 'Type Flip Box Title', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'back_description',
            [
                'label' => __( 'Description', 'happy-elementor-addons' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXTAREA,
                'default' => __( 'Type Detail Here', 'happy-elementor-addons' ),
                'placeholder' => __( 'Description', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'back_text_align',
            [
                'label' => __( 'Text Alignment', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'center',
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
                    '{{WRAPPER}} .ha-flip-box-back-inner .ha-text' => 'text-align: {{VALUE}}',
                    '{{WRAPPER}} .ha-flip-box-back-inner .button-wrap' => 'text-align: {{VALUE}}',
                ]
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
            'back_icon_position',
            [
                'label' => __( 'Position', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'top',
                'options' => [
                    'top' => [
                        'title' => __( 'Top', 'happy-elementor-addons' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'bottom' => [
                        'title' => __( 'Bottom', 'happy-elementor-addons' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors_dictionary' => [
                    'top' => 'flex-direction: column',
                    'bottom' => 'flex-direction: column-reverse',
                ],
                'prefix_class' => 'ha-flip-back-icon--',
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-back-inner' => '{{VALUE}}'
                ]
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

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_control_',
            [
                'label' => __( 'Controls', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'flip_position',
            [
                'label' => __( 'Flip Direction', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'top-bottom',
                'label_block' => false,
                'options' => [
                    'top-bottom' => [
                        'title' => __( 'Top Bottom', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-arrows-v',
                    ],
                    'left-right' => [
                        'title' => __( 'Left Right', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-arrows-h',
                    ],
                ],
                'toggle' => false,
            ]
        );

        $this->end_controls_section();

    }

    protected function register_style_controls() {
        $this->start_controls_section(
            '_section_common_style_',
            [
                'label' => __( 'Common', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
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
            '_section_front_style_',
            [
                'label' => __( 'Front Side', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'front_content_area',
            [
                'label' => __( 'Content Area', 'happy-elementor-addons' ),
                'type' => Controls_Manager::HEADING,
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

        $this->add_control(
            'front_background_image',
            [
                'label' => __( 'Background', 'happy-elementor-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'front_background_type',
            [
                'label' => __( 'Type', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'color',
                'options' => [
                    'color' => [
                        'title' => __( 'Color', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-paint-brush',
                    ],
                    'image' => [
                        'title' => __( 'Image', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-image',
                    ],
                ],
            ]
        );

        $this->add_control(
            'front_image',
            [
                'label' => __( 'Image', 'happy-elementor-addons' ),
                'type' => Controls_Manager::MEDIA,
                'return_value' => 'yes',
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'front_background_type' => 'image'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'medium_large',
                'exclude' => [
                    'thumbnail',
                    'full',
                    'custom',
                    'shop_catalog',
                    'shop_single',
                    'shop_thumbnail'
                ],
                'condition' => [
                    'front_background_type' => 'image'
                ]
            ]
        );

        $this->add_control(
            'front_background_overlay',
            [
                'label' => __( 'Background Overlay', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.27)',
                'condition' => [
                    'front_background_type' => 'image'
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
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
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

        $this->end_controls_tab();
        $this->end_controls_tabs();

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
                'label' => __( 'Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}}.ha-flip-icon--top .ha-flip-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ha-flip-icon--bottom .ha-flip-icon' => 'margin-top: {{SIZE}}{{UNIT}};',
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
                'default' => [
                    'unit' => 'px',
                    'size' => 60,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-front-inner .icon-wrap .ha-flip-icon' => 'width: {{SIZE}}{{UNIT}};',
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
                'default' => [
                    'unit' => 'px',
                    'size' => 28,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-front-inner .ha-flip-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'front_icon_background_size',
            [
                'label' => __( 'Background Size', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'condition' => [
                    'front_icon_type' => 'icon'
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-front-inner .ha-flip-icon i' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'front_icon_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-front-inner .ha-flip-icon i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ha-flip-box-front-inner .ha-flip-icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .ha-flip-box-front-inner .ha-flip-icon i' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'front_icon_background_color',
            [
                'label' => __( 'Background Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'front_icon_type' => 'icon'
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-front-inner .ha-flip-icon i' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        // back side
        $this->start_controls_section(
            '_section_back_text_style_',
            [
                'label' => __( 'Back Side', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'back_content_area',
            [
                'label' => __( 'Content Area', 'happy-elementor-addons' ),
                'type' => Controls_Manager::HEADING,
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

        $this->add_control(
            'back_background_image',
            [
                'label' => __( 'Background', 'happy-elementor-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'back_background_type',
            [
                'label' => __( 'Type', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'color',
                'options' => [
                    'color' => [
                        'title' => __( 'Color', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-paint-brush',
                    ],
                    'image' => [
                        'title' => __( 'Image', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-image',
                    ],
                ],
            ]
        );

        $this->add_control(
            'back_image',
            [
                'label' => __( 'Image', 'happy-elementor-addons' ),
                'type' => Controls_Manager::MEDIA,
                'return_value' => 'yes',
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'back_background_type' => 'image'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'back_thumbnail',
                'default' => 'medium_large',
                'exclude' => [
                    'thumbnail',
                    'full',
                    'custom',
                    'shop_catalog',
                    'shop_single',
                    'shop_thumbnail'
                ],
                'condition' => [
                    'back_background_type' => 'image'
                ]
            ]
        );

        $this->add_control(
            'back_background_overlay',
            [
                'label' => __( 'Background Overlay', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.27)',
                'condition' => [
                    'back_background_type' => 'image'
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
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
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
                'default' => [
                    'unit' => 'px',
                    'size' => 60,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-back-inner .icon-wrap .ha-flip-icon' => 'width: {{SIZE}}{{UNIT}};',
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
                'default' => [
                    'unit' => 'px',
                    'size' => 25,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-back-inner .ha-flip-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'back_icon_padding',
            [
                'label' => __( 'Background Size', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'condition' => [
                    'back_icon_type' => 'icon'
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-back-inner .ha-flip-icon i' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'back_icon_spacing',
            [
                'label' => __( 'Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%'],
                'condition' => [
                    'back_icon_type' => [ 'icon', 'image' ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}}.ha-flip-back-icon--top .ha-flip-box-back-inner .ha-flip-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ha-flip-back-icon--bottom .ha-flip-box-back-inner .ha-flip-icon' => 'margin-top: {{SIZE}}{{UNIT}};'
                ],
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
                    '{{WRAPPER}} .ha-flip-box-back-inner .ha-flip-icon i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ha-flip-box-back-inner .ha-flip-icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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
                    'back_icon_type' => 'icon'
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-box-back-inner .ha-flip-icon i' => 'background: {{VALUE}}',
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
                'label' => __( 'Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%'],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}}.ha-flip-back-icon--top .ha-flip-box-back-inner .button-wrap' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ha-flip-back-icon--bottom .ha-flip-box-back-inner .button-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};'
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
                'selector' => '{{WRAPPER}} .ha-flip-btn',
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'separator' => 'after',
                'selector' => '{{WRAPPER}} .ha-flip-btn',
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
            'link_color',
            [
                'label' => __( 'Text Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => __( 'Background Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-btn' => 'background-color: {{VALUE}};',
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
                'label' => __( 'Text Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-btn:hover, {{WRAPPER}} .ha-flip-btn:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_bg_color',
            [
                'label' => __( 'Background Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-flip-btn:hover, {{WRAPPER}} .ha-flip-btn:focus' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .ha-flip-btn:hover, {{WRAPPER}} .ha-flip-btn:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typography',
                'selector' => '{{WRAPPER}} .ha-flip-btn',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // background images
        if ( $settings['front_image']['id'] && isset( $settings['front_image']['url'] ) ) {
            $front_image = wp_get_attachment_image_url( $settings['front_image']['id'], $settings['thumbnail_size'], false );
        } else {
           $front_image = $settings['front_image']['url'];
        }
        if ( $settings['back_image']['id'] && isset( $settings['back_image']['url'] ) ) {
            $back_image = wp_get_attachment_image_url( $settings['back_image']['id'], $settings['back_thumbnail_size'], false );
        } else {
            $back_image = $settings['back_image']['url'];
        }

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
        if ( $settings['flip_position'] === 'top-bottom' ) {
            $this->add_render_attribute( 'flip-position', 'class', 'ha-flip-box ha-flip-up' );
        } elseif ( $settings['flip_position'] === 'left-right' ) {
            $this->add_render_attribute( 'flip-position', 'class', 'ha-flip-box ha-flip-right' );
        }
        ?>

        <div <?php echo $this->get_render_attribute_string( 'display' ); ?>>

            <div <?php echo $this->get_render_attribute_string( 'flip-position' ); ?>>
                <div class="ha-flip-box-inner-wrapper">
                    <div class="ha-flip-box-front" style="background-image: url('<?php echo esc_url( $front_image ); ?>')">
                        <div class="ha-flip-box-front-inner">

                            <?php if ( $settings['front_icon'] ) : ?>
                                <div class="icon-wrap">
                                    <div class="ha-flip-icon">
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

                    <div class="ha-flip-box-back" style="background-image: url('<?php echo esc_url( $back_image ); ?>')">
                        <div class="ha-flip-box-back-inner">

                            <?php if ( $settings['back_icon_image'] || $settings['back_icon'] ) : ?>
                                <div class="icon-wrap">
                                    <div class="ha-flip-icon">
                                        <?php
                                        if ( $settings['back_icon'] ) : ?>

                                            <i class="<?php echo esc_attr( $settings['back_icon'] ); ?>"></i>

                                       <?php
                                        elseif ( $settings['back_icon_image'] ) :

                                            echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'back_icon_thumbnail', 'back_icon_image' );

                                        endif;
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="ha-text">
                                <?php if ( $settings['back_title'] ) : ?>
                                    <h3 <?php echo $this->get_render_attribute_string( 'back_title' ); ?>>
                                        <?php echo esc_html( $settings['back_title'] ); ?>
                                    </h3>
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