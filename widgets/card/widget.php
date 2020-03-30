<?php
/**
 * Card widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Group_Control_Css_Filter;
use Elementor\Scheme_Typography;
use Elementor\Utils;
use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || die();

class Card extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Card', 'happy-elementor-addons' );
    }

    public function get_custom_help_url() {
        return 'https://happyaddons.com/docs/happy-addons-for-elementor/widgets/card/';
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
        return 'hm hm-card';
    }

    public function get_keywords() {
        return [ 'card', 'blurb', 'infobox', 'content', 'block', 'box' ];
    }

	protected function register_content_controls() {
		$this->start_controls_section(
			'_section_image',
			[
				'label' => __( 'Image & Badge', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
            'image',
            [
                'label' => __( 'Image', 'happy-elementor-addons' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'large',
                'separator' => 'none',
            ]
        );

        $this->add_control(
            'image_position',
            [
                'label' => __( 'Image Position', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'happy-elementor-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'top' => [
                        'title' => __( 'Top', 'happy-elementor-addons' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'happy-elementor-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => false,
                'default' => 'top',
                'prefix_class' => 'ha-card--',
                'style_transfer' => true,
            ]
        );

        $this->add_control(
            'badge_text',
            [
                'label' => __( 'Badge Text', 'happy-elementor-addons' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => __( 'Badget Text', 'happy-elementor-addons' ),
                'placeholder' => __( 'Type badge text', 'happy-elementor-addons' ),
                'separator' => 'before',
                'description' => __( 'Set badget position and control all the style settings from Style tab', 'happy-elementor-addons' ),
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_title',
            [
                'label' => __( 'Title & Description', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'happy-elementor-addons' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Happy Card Title', 'happy-elementor-addons' ),
                'placeholder' => __( 'Type Card Title', 'happy-elementor-addons' ),
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __( 'Description', 'happy-elementor-addons' ),
                'description' => ha_get_allowed_html_desc( 'intermediate' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __( 'Happy card description goes here', 'happy-elementor-addons' ),
                'placeholder' => __( 'Type card description', 'happy-elementor-addons' ),
                'rows' => 5,
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => __( 'Title HTML Tag', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'h1'  => [
                        'title' => __( 'H1', 'happy-elementor-addons' ),
                        'icon' => 'eicon-editor-h1'
                    ],
                    'h2'  => [
                        'title' => __( 'H2', 'happy-elementor-addons' ),
                        'icon' => 'eicon-editor-h2'
                    ],
                    'h3'  => [
                        'title' => __( 'H3', 'happy-elementor-addons' ),
                        'icon' => 'eicon-editor-h3'
                    ],
                    'h4'  => [
                        'title' => __( 'H4', 'happy-elementor-addons' ),
                        'icon' => 'eicon-editor-h4'
                    ],
                    'h5'  => [
                        'title' => __( 'H5', 'happy-elementor-addons' ),
                        'icon' => 'eicon-editor-h5'
                    ],
                    'h6'  => [
                        'title' => __( 'H6', 'happy-elementor-addons' ),
                        'icon' => 'eicon-editor-h6'
                    ]
                ],
                'default' => 'h2',
                'toggle' => false,
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => __( 'Alignment', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
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
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_button',
            [
                'label' => __( 'Button', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __( 'Text', 'happy-elementor-addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Button Text',
                'placeholder' => __( 'Type button text here', 'happy-elementor-addons' ),
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $this->add_control(
            'button_link',
            [
                'label' => __( 'Link', 'happy-elementor-addons' ),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://happyaddons.com/',
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        if ( ha_is_elementor_version( '<', '2.6.0' ) ) {
            $this->add_control(
                'button_icon',
                [
                    'label' => __( 'Icon', 'happy-elementor-addons' ),
                    'label_block' => true,
                    'type' => Controls_Manager::ICON,
                    'options' => ha_get_happy_icons(),
                    'default' => 'fa fa-angle-right',
                ]
            );

            $condition = ['button_icon!' => ''];
        } else {
            $this->add_control(
                'button_selected_icon',
                [
                    'type' => Controls_Manager::ICONS,
                    'fa4compatibility' => 'button_icon',
                    'label_block' => true,
                ]
            );
            $condition = ['button_selected_icon[value]!' => ''];
        }

        $this->add_control(
            'button_icon_position',
            [
                'label' => __( 'Icon Position', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'before' => [
                        'title' => __( 'Before', 'happy-elementor-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'after' => [
                        'title' => __( 'After', 'happy-elementor-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'before',
                'toggle' => false,
                'condition' => $condition,
                'style_transfer' => true,
            ]
        );

        $this->add_control(
            'button_icon_spacing',
            [
                'label' => __( 'Icon Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'condition' => $condition,
                'selectors' => [
                    '{{WRAPPER}} .ha-btn--icon-before .ha-btn-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ha-btn--icon-after .ha-btn-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_controls() {
        $this->start_controls_section(
            '_section_style_image',
            [
                'label' => __( 'Image', 'happy-elementor-addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label' => __( 'Width', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%', 'px' ],
                'desktop_default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 50,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-card-figure' => 'flex: 0 0 {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ha-card--right .ha-card-body, {{WRAPPER}}.ha-card--left .ha-card-body' => 'flex: 0 0 calc(100% - {{SIZE || 50}}{{UNIT}}); max-width: calc(100% - {{SIZE || 50}}{{UNIT}});',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => __( 'Height', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-card-figure' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'offset_toggle',
            [
                'label' => __( 'Offset', 'happy-elementor-addons' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'label_off' => __( 'None', 'happy-elementor-addons' ),
                'label_on' => __( 'Custom', 'happy-elementor-addons' ),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_responsive_control(
            'image_offset_x',
            [
                'label' => __( 'Offset Left', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'condition' => [
                    'offset_toggle' => 'yes'
                ],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'render_type' => 'ui'
            ]
        );

        $this->add_responsive_control(
            'image_offset_y',
            [
                'label' => __( 'Offset Top', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'condition' => [
                    'offset_toggle' => 'yes'
                ],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    // Left image position styles
                    '(desktop){{WRAPPER}}.ha-card--left .ha-card-body' => 'margin-left: {{image_offset_x.SIZE || 0}}{{UNIT}}; flex: 0 0 calc((100% - {{image_width.SIZE || 50}}{{image_width.UNIT}}) + (-1 * {{image_offset_x.SIZE || 0}}{{UNIT}})); max-width: calc((100% - {{image_width.SIZE || 50}}{{image_width.UNIT}}) + (-1 * {{image_offset_x.SIZE || 0}}{{UNIT}}));',
                    '(tablet){{WRAPPER}}.ha-card--left .ha-card-body' => 'margin-left: {{image_offset_x_tablet.SIZE || 0}}{{UNIT}}; flex: 0 0 calc((100% - {{image_width_tablet.SIZE || 50}}{{image_width_tablet.UNIT}}) + (-1 * {{image_offset_x_tablet.SIZE || 0}}{{UNIT}})); max-width: calc((100% - {{image_width_tablet.SIZE || 50}}{{image_width_tablet.UNIT}}) + (-1 * {{image_offset_x_tablet.SIZE || 0}}{{UNIT}}));',
                    '(mobile){{WRAPPER}}.ha-card--left .ha-card-body' => 'margin-left: {{image_offset_x_mobile.SIZE || 0}}{{UNIT}}; flex: 0 0 calc((100% - {{image_width_mobile.SIZE || 50}}{{image_width_mobile.UNIT}}) + (-1 * {{image_offset_x_mobile.SIZE || 0}}{{UNIT}})); max-width: calc((100% - {{image_width_mobile.SIZE || 50}}{{image_width_mobile.UNIT}}) + (-1 * {{image_offset_x_mobile.SIZE || 0}}{{UNIT}}));',
                    // Image right position styles
                    '(desktop){{WRAPPER}}.ha-card--right .ha-card-body' => 'margin-right: calc(-1 * {{image_offset_x.SIZE || 0}}{{UNIT}}); flex: 0 0 calc((100% - {{image_width.SIZE || 50}}{{image_width.UNIT}}) + {{image_offset_x.SIZE || 0}}{{UNIT}}); max-width: calc((100% - {{image_width.SIZE || 50}}{{image_width.UNIT}}) + {{image_offset_x.SIZE || 0}}{{UNIT}});',
                    '(tablet){{WRAPPER}}.ha-card--right .ha-card-body' => 'margin-right: calc(-1 * {{image_offset_x_tablet.SIZE || 0}}{{UNIT}}); flex: 0 0 calc((100% - {{image_width_tablet.SIZE || 50}}{{image_width_tablet.UNIT}}) + {{image_offset_x_tablet.SIZE || 0}}{{UNIT}}); max-width: calc((100% - {{image_width_tablet.SIZE || 50}}{{image_width_tablet.UNIT}}) + {{image_offset_x_tablet.SIZE || 0}}{{UNIT}});',
                    '(mobile){{WRAPPER}}.ha-card--right .ha-card-body' => 'margin-right: calc(-1 * {{image_offset_x_mobile.SIZE || 0}}{{UNIT}}); flex: 0 0 calc((100% - {{image_width_mobile.SIZE || 50}}{{image_width_mobile.UNIT}}) + {{image_offset_x_mobile.SIZE || 0}}{{UNIT}}); max-width: calc((100% - {{image_width_mobile.SIZE || 50}}{{image_width_mobile.UNIT}}) + {{image_offset_x_mobile.SIZE || 0}}{{UNIT}});',
                    // Image translate styles
                    '(desktop){{WRAPPER}} .ha-card-figure' => '-ms-transform: translate({{image_offset_x.SIZE || 0}}{{UNIT}}, {{image_offset_y.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{image_offset_x.SIZE || 0}}{{UNIT}}, {{image_offset_y.SIZE || 0}}{{UNIT}}); transform: translate({{image_offset_x.SIZE || 0}}{{UNIT}}, {{image_offset_y.SIZE || 0}}{{UNIT}});',
                    '(tablet){{WRAPPER}} .ha-card-figure' => '-ms-transform: translate({{image_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{image_offset_y_tablet.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{image_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{image_offset_y_tablet.SIZE || 0}}{{UNIT}}); transform: translate({{image_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{image_offset_y_tablet.SIZE || 0}}{{UNIT}});',
                    '(mobile){{WRAPPER}} .ha-card-figure' => '-ms-transform: translate({{image_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{image_offset_y_mobile.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{image_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{image_offset_y_mobile.SIZE || 0}}{{UNIT}}); transform: translate({{image_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{image_offset_y_mobile.SIZE || 0}}{{UNIT}});',
                    // Card body styles
                    '{{WRAPPER}}.ha-card--top .ha-card-body' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_popover();

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => __( 'Padding', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-card-figure img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .ha-card-figure img',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-card-figure img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .ha-card-figure img',
                'separator' => 'after'
            ]
        );

        $this->start_controls_tabs(
            '_tabs_image_effects',
            [
                'separator' => 'before'
            ]
        );

        $this->start_controls_tab(
            '_tab_image_effects_normal',
            [
                'label' => __( 'Normal', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'image_opacity',
            [
                'label' => __( 'Opacity', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-card-figure img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'image_css_filters',
                'selector' => '{{WRAPPER}} .ha-card-figure img',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'hover',
            [
                'label' => __( 'Hover', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'image_opacity_hover',
            [
                'label' => __( 'Opacity', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-card-figure:hover img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'image_css_filters_hover',
                'selector' => '{{WRAPPER}} .ha-card-figure:hover img',
            ]
        );

        $this->add_control(
            'image_background_hover_transition',
            [
                'label' => __( 'Transition Duration', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-card-figure img' => 'transition-duration: {{SIZE}}s;',
                ],
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label' => __( 'Hover Animation', 'happy-elementor-addons' ),
                'type' => Controls_Manager::HOVER_ANIMATION,
                'label_block' => true,
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_style_badge',
            [
                'label' => __( 'Badge', 'happy-elementor-addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'badge_position',
            [
                'label' => __( 'Position', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'top-left'  => __( 'Top Left', 'happy-elementor-addons' ),
                    'top-center'  => __( 'Top Center', 'happy-elementor-addons' ),
                    'top-right'  => __( 'Top Right', 'happy-elementor-addons' ),
                    'middle-left'  => __( 'Middle Left', 'happy-elementor-addons' ),
                    'middle-center'  => __( 'Middle Center', 'happy-elementor-addons' ),
                    'middle-right'  => __( 'Middle Right', 'happy-elementor-addons' ),
                    'bottom-left'  => __( 'Bottom Left', 'happy-elementor-addons' ),
                    'bottom-center'  => __( 'Bottom Center', 'happy-elementor-addons' ),
                    'bottom-right'  => __( 'Bottom Right', 'happy-elementor-addons' ),
                ],
                'default' => 'top-right',
            ]
        );

        $this->add_control(
            'badge_offset_toggle',
            [
                'label' => __( 'Offset', 'happy-elementor-addons' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'label_off' => __( 'None', 'happy-elementor-addons' ),
                'label_on' => __( 'Custom', 'happy-elementor-addons' ),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_responsive_control(
            'badge_offset_x',
            [
                'label' => __( 'Offset Left', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'condition' => [
                    'badge_offset_toggle' => 'yes'
                ],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'render_type' => 'ui'
            ]
        );

        $this->add_responsive_control(
            'badge_offset_y',
            [
                'label' => __( 'Offset Top', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'condition' => [
                    'badge_offset_toggle' => 'yes'
                ],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '(desktop){{WRAPPER}} .ha-badge' => '-ms-transform: translate({{badge_offset_x.SIZE || 0}}{{UNIT}}, {{badge_offset_y.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{badge_offset_x.SIZE || 0}}{{UNIT}}, {{badge_offset_y.SIZE || 0}}{{UNIT}}); transform: translate({{badge_offset_x.SIZE || 0}}{{UNIT}}, {{badge_offset_y.SIZE || 0}}{{UNIT}});',
                    '(tablet){{WRAPPER}} .ha-badge' => '-ms-transform: translate({{badge_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{badge_offset_y_tablet.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{badge_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{badge_offset_y_tablet.SIZE || 0}}{{UNIT}}); transform: translate({{badge_offset_x_tablet.SIZE || 0}}{{UNIT}}, {{badge_offset_y_tablet.SIZE || 0}}{{UNIT}});',
                    '(mobile){{WRAPPER}} .ha-badge' => '-ms-transform: translate({{badge_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{badge_offset_y_mobile.SIZE || 0}}{{UNIT}}); -webkit-transform: translate({{badge_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{badge_offset_y_mobile.SIZE || 0}}{{UNIT}}); transform: translate({{badge_offset_x_mobile.SIZE || 0}}{{UNIT}}, {{badge_offset_y_mobile.SIZE || 0}}{{UNIT}});',
                ],
            ]
        );
        $this->end_popover();

        $this->add_responsive_control(
            'badge_padding',
            [
                'label' => __( 'Padding', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'badge_color',
            [
                'label' => __( 'Text Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_bg_color',
            [
                'label' => __( 'Background Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'badge_border',
                'selector' => '{{WRAPPER}} .ha-badge',
            ]
        );

        $this->add_responsive_control(
            'badge_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'badge_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .ha-badge',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'badge_typography',
                'label' => __( 'Typography', 'happy-elementor-addons' ),
                'exclude' => [
                    'line_height'
                ],
                'default' => [
                    'font_size' => ['']
                ],
                'selector' => '{{WRAPPER}} .ha-badge',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_style_content',
            [
                'label' => __( 'Title & Description', 'happy-elementor-addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __( 'Content Padding', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-card-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            '_heading_title',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Title', 'happy-elementor-addons' ),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __( 'Bottom Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-card-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Text Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-card-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __( 'Typography', 'happy-elementor-addons' ),
                'selector' => '{{WRAPPER}} .ha-card-title',
                'scheme' => Scheme_Typography::TYPOGRAPHY_2,
            ]
        );

        $this->add_control(
            '_heading_description',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Description', 'happy-elementor-addons' ),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'description_spacing',
            [
                'label' => __( 'Bottom Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-card-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __( 'Text Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-card-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => __( 'Typography', 'happy-elementor-addons' ),
                'selector' => '{{WRAPPER}} .ha-card-text',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_style_button',
            [
                'label' => __( 'Button', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __( 'Padding', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .ha-btn',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .ha-btn',
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .ha-btn',
            ]
        );

        $this->add_control(
            'hr',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
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
                'label' => __( 'Text Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ha-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => __( 'Background Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-btn' => 'background-color: {{VALUE}};',
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
            'button_hover_color',
            [
                'label' => __( 'Text Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-btn:hover, {{WRAPPER}} .ha-btn:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_bg_color',
            [
                'label' => __( 'Background Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-btn:hover, {{WRAPPER}} .ha-btn:focus' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .ha-btn:hover, {{WRAPPER}} .ha-btn:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

	protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_inline_editing_attributes( 'badge_text', 'none' );
        $this->add_render_attribute(
            'badge_text',
            'class',
            ['ha-badge', sprintf( 'ha-badge--%s', esc_attr( $settings['badge_position'] ) )]
        );

        $this->add_inline_editing_attributes( 'title', 'basic' );
        $this->add_render_attribute( 'title', 'class', 'ha-card-title' );

        $this->add_inline_editing_attributes( 'description', 'intermediate' );
        $this->add_render_attribute( 'description', 'class', 'ha-card-text' );

        $this->add_inline_editing_attributes( 'button_text', 'none' );
        $this->add_render_attribute( 'button_text', 'class', 'ha-btn-text' );

		$this->add_render_attribute( 'button', 'class', 'ha-btn' );

		$this->add_link_attributes( 'button', $settings['button_link'] );
        ?>

        <?php if ( $settings['image']['url'] || $settings['image']['id'] ) :
            $this->add_render_attribute( 'image', 'src', $settings['image']['url'] );
            $this->add_render_attribute( 'image', 'alt', Control_Media::get_image_alt( $settings['image'] ) );
            $this->add_render_attribute( 'image', 'title', Control_Media::get_image_title( $settings['image'] ) );
            ?>
            <figure class="ha-card-figure">
                <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' ); ?>
                <?php if ( $settings['badge_text'] ) : ?>
                    <div <?php echo $this->get_render_attribute_string( 'badge_text' ); ?>><?php echo esc_html( $settings['badge_text'] ); ?></div>
                <?php endif; ?>
            </figure>
        <?php endif; ?>

        <div class="ha-card-body">

            <?php
            if ( $settings['title' ] ) :
                printf( '<%1$s %2$s>%3$s</%1$s>',
                    tag_escape( $settings['title_tag'] ),
                    $this->get_render_attribute_string( 'title' ),
                    ha_kses_basic( $settings['title' ] )
                    );
            endif;
            ?>

            <?php if ( $settings['description'] ) : ?>
                <div <?php echo $this->get_render_attribute_string( 'description' ); ?>>
                    <p><?php echo ha_kses_intermediate( $settings['description'] ); ?></p>
                </div>
            <?php endif; ?>

            <?php
            if ( $settings['button_text'] && ( ( empty( $settings['button_selected_icon'] ) || empty( $settings['button_selected_icon']['value'] ) ) && empty( $settings['button_icon'] ) ) ) :
                printf( '<a %1$s>%2$s</a>',
                    $this->get_render_attribute_string( 'button' ),
                    sprintf( '<span %1$s>%2$s</span>', $this->get_render_attribute_string( 'button_text' ), esc_html( $settings['button_text'] ) )
                    );
            elseif ( empty( $settings['button_text'] ) && ( ! ( empty( $settings['button_selected_icon'] ) || empty( $settings['button_selected_icon']['value'] ) ) || ! empty( $settings['button_icon'] ) ) ) : ?>
                <a <?php $this->print_render_attribute_string( 'button' ); ?>><?php ha_render_icon( $settings, 'button_icon', 'button_selected_icon' ); ?></a>
            <?php elseif ( $settings['button_text'] && ( ! ( empty( $settings['button_selected_icon'] ) || empty( $settings['button_selected_icon']['value'] ) ) || ! empty( $settings['button_icon'] ) ) ) :
                if ( $settings['button_icon_position'] === 'before' ) :
                    $this->add_render_attribute( 'button', 'class', 'ha-btn--icon-before' );
                    $button_text = sprintf( '<span %1$s>%2$s</span>', $this->get_render_attribute_string( 'button_text' ), esc_html( $settings['button_text'] ) );
                    ?>
                    <a <?php $this->print_render_attribute_string( 'button' ); ?>><?php ha_render_icon( $settings, 'button_icon', 'button_selected_icon', ['class' => 'ha-btn-icon'] ); ?> <?php echo $button_text; ?></a>
                    <?php
                else :
                    $this->add_render_attribute( 'button', 'class', 'ha-btn--icon-after' );
                    $button_text = sprintf( '<span %1$s>%2$s</span>', $this->get_render_attribute_string( 'button_text' ), esc_html( $settings['button_text'] ) );
                    ?>
                    <a <?php $this->print_render_attribute_string( 'button' ); ?>><?php echo $button_text; ?> <?php ha_render_icon( $settings, 'button_icon', 'button_selected_icon', ['class' => 'ha-btn-icon'] ); ?></a>
                <?php
                endif;
            endif;
            ?>
        </div>
        <?php
    }

    public function _content_template() {
        ?>
        <#
        var btnIconHTML = btnMigrated = btnIcon = '';

        if ( ha_has_icon_library() ) {
            btnIconHTML = elementor.helpers.renderIcon( view, settings.button_selected_icon, { 'aria-hidden': true, 'class': 'ha-btn-icon' }, 'i' , 'object' ),
            btnMigrated = elementor.helpers.isIconMigrated( settings, 'button_selected_icon' );
        }

        view.addInlineEditingAttributes( 'badge_text', 'none' );
        view.addRenderAttribute(
            'badge_text',
            'class',
            ['ha-badge', 'ha-badge--' + settings.badge_position]
        );

        view.addInlineEditingAttributes( 'title', 'basic' );
        view.addRenderAttribute( 'title', 'class', 'ha-card-title' );

        view.addInlineEditingAttributes( 'description', 'intermediate' );
        view.addRenderAttribute( 'description', 'class', 'ha-card-text' );

        view.addInlineEditingAttributes( 'button_text', 'none' );
        view.addRenderAttribute( 'button_text', 'class', 'ha-btn-text' );

        view.addRenderAttribute( 'button', 'class', 'ha-btn' );
        view.addRenderAttribute( 'button', 'href', settings.button_link.url );

        if ( settings.image.url || settings.image.id ) {
            var image = {
                id: settings.image.id,
                url: settings.image.url,
                size: settings.thumbnail_size,
                dimension: settings.thumbnail_custom_dimension,
                model: view.getEditModel()
            };

            var image_url = elementor.imagesManager.getImageUrl( image ); #>
            <figure class="ha-card-figure">
                <img class="elementor-animation-{{settings.hover_animation}}" src="{{ image_url }}">
                <# if (settings.badge_text) { #>
                    <div {{{ view.getRenderAttributeString( 'badge_text' ) }}}>{{ settings.badge_text }}</div>
                <# } #>
            </figure>
        <# } #>

        <div class="ha-card-body">
            <# if (settings.title) { #>
                <{{ settings.title_tag }} {{{ view.getRenderAttributeString( 'title' ) }}}>{{{ settings.title }}}</{{ settings.title_tag }}>
            <# } #>

            <# if (settings.description) { #>
                <div {{{ view.getRenderAttributeString( 'description' ) }}}>
                    <p>{{{ settings.description }}}</p>
                </div>
            <# } #>

            <# if ( ( settings.button_selected_icon && settings.button_selected_icon.value ) || settings.button_icon ) {
                if ( ha_has_icon_library() && btnIconHTML && btnIconHTML.rendered && ( ! settings.button_icon || btnMigrated ) ) {
                    btnIcon = btnIconHTML.value;
                } else if ( settings.button_icon ) {
                    btnIcon = '<i class="ha-btn-icon ' + settings.button_icon + '" aria-hidden="true"></i>';
                }
            } #>

            <# if ( settings.button_text && ( ! ( settings.button_selected_icon && settings.button_selected_icon.value ) && ! settings.button_icon ) ) { #>
                <a {{{ view.getRenderAttributeString( 'button' ) }}}><span {{{ view.getRenderAttributeString( 'button_text' ) }}}>{{ settings.button_text }}</span></a>
            <# } else if ( ! settings.button_text && ( ( settings.button_selected_icon && settings.button_selected_icon.value ) || settings.button_icon ) ) { #>
                <a {{{ view.getRenderAttributeString( 'button' ) }}}>{{{ btnIcon }}}</a>
            <# } else if ( settings.button_text && ( ( settings.button_selected_icon && settings.button_selected_icon.value ) || settings.button_icon ) ) {
                if ( settings.button_icon_position === 'before' ) {
                    view.addRenderAttribute( 'button', 'class', 'ha-btn--icon-before' );
                    button_before = btnIcon;
                    button_after = '<span ' + view.getRenderAttributeString( 'button_text' ) + '>' + settings.button_text + '</span>';
                } else {
                    view.addRenderAttribute( 'button', 'class', 'ha-btn--icon-after' );
                    button_after = btnIcon;
                    button_before = '<span ' + view.getRenderAttributeString( 'button_text' ) + '>' + settings.button_text + '</span>';
                } #>
                <a {{{ view.getRenderAttributeString( 'button' ) }}}>{{{ button_before }}} {{{ button_after }}}</a>
            <# } #>
        </div>
        <?php
    }
}
