<?php
    /**
     * Image Cycle widget class
     *
     * @package Happy_Addons
     */
    namespace Happy_Addons\Elementor\Widget;

    use Elementor\Utils;
    use Elementor\Repeater;
    use Elementor\Controls_Manager;
    use Elementor\Group_Control_Border;
    use Elementor\Group_Control_Background;
    use Elementor\Group_Control_Typography;
    use Elementor\Group_Control_Box_Shadow;
    use Elementor\Group_Control_Text_Shadow;
    use Elementor\Group_Control_Image_Size;
    use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
    use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

    defined( 'ABSPATH' ) || die();

    class Image_Cycle extends Base {

    /**
     * Get widget title.
     *
     * @access public
     * @since 1.0.0
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Image Cycle', 'happy-elementor-addons' );
    }

    /**
     * Get widget user document.
     *
     * @access public
     * @since 1.0.0
     *
     * @return string Widget document.
     */
    public function get_custom_help_url() {
        return 'https://happyaddons.com/docs/happy-addons-for-elementor/widgets/#/';
    }

    /**
     * Get widget icon.
     *
     * @access public
     * @since 1.0.0
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'hm hm-net';
    }

    public function get_keywords() {
        return ['image', 'cycle', 'slider', 'gallery', 'image-slider', 'cycle-gallery', 'image-cycle', 'image-gallery', 'image-carousel', 'carousel', 'image cycle'];
    }

    /**
     * Register widget content controls
     */
    protected function register_content_controls() {
        $this->__ic_content_controls();
        $this->__ic_physics_settings_controls();
    }

    protected function __ic_content_controls() {

        $this->start_controls_section(
            '_section_ic_contents',
            [
                'label' => __( 'Image Cycle Content', 'happy-elementor-addons' ),
                'tab'   => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->add_control(
            'ha_ic_animation_mode',
            [
                'label' => __( 'Animation Mode', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'variation-1',
                'options' => [
                    'variation-1' => __( 'Variation 1', 'happy-elementor-addons' ),
                    'variation-2' => __( 'Variation 2', 'happy-elementor-addons' ),
                    'variation-3' => __( 'Variation 3', 'happy-elementor-addons' ),
                ],
                'frontend_available' => true,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'ha_ic_image',
            [
                'label' => __( 'Image', 'happy-elementor-addons' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'ha_ic_thumbnail',
                'default' => 'large',
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'ha_ic_link',
            [
                'label' => __( 'Link', 'happy-elementor-addons' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'happy-elementor-addons' ),
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'ha_ic_images',
            [
                'label' => __( 'Images', 'happy-elementor-addons' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'ha_ic_image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
                    [ 'ha_ic_image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
                    [ 'ha_ic_image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
                    [ 'ha_ic_image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
                    [ 'ha_ic_image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
                    [ 'ha_ic_image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
                    [ 'ha_ic_image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
                    [ 'ha_ic_image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
                ],
                'title_field' => '{{{ ha_ic_link.url }}}',
            ]
        );

        $this->add_control(
            'ha_ic_images_note',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __( 'Add up to 10 images. 8 images are set by default.', 'happy-elementor-addons' ),
                'content_classes' => 'elementor-descriptor',
            ]
        );

        $this->add_control(
            'ha_ic_heading_divider',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'ha_ic_title',
            [
                'label' => __( 'Title', 'happy-elementor-addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Astral', 'happy-elementor-addons' ),
                'placeholder' => __( 'First Title Line', 'happy-elementor-addons' ),
                'label_block' => true,
                'dynamic' => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'ha_ic_title_second',
            [
                'label' => __( 'Title Second Line', 'happy-elementor-addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Aesthetics', 'happy-elementor-addons' ),
                'placeholder' => __( 'Second Title Line', 'happy-elementor-addons' ),
                'label_block' => true,
                'dynamic' => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'ha_ic_subtitle',
            [
                'label' => __( 'Subtitle', 'happy-elementor-addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( '2024', 'happy-elementor-addons' ),
                'placeholder' => __( 'Subtitle', 'happy-elementor-addons' ),
                'label_block' => true,
                'dynamic' => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'ha_ic_title_tag',
            [
                'label' => __( 'Title HTML Tag', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h2',
            ]
        );

        $this->add_control(
            'ha_ic_subtitle_tag',
            [
                'label' => __( 'Subtitle HTML Tag', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h5',
            ]
        );

        $this->end_controls_section();
    }

    protected function __ic_physics_settings_controls() {

        $this->start_controls_section(
            '_section_ic_settings',
            [
                'label' => __( 'Physics Settings', 'happy-elementor-addons' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'ha_ic_radius',
            [
                'label' => __( 'Radius', 'happy-elementor-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 50,
                'max' => 800,
                'step' => 1,
                'default' => [
                    'unit' => 'px',
                    'size' => 250,
                ],
                'tablet_default' => [
                    'unit' => 'px',
                    'size' => 180,
                ],
                'mobile_default' => [
                    'unit' => 'px',
                    'size' => 180,
                ],
                'description' => __( 'Distance from image center to screen center. Desktop default 250, tablet/mobile default 180.', 'happy-elementor-addons' ),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'ha_ic_stagger',
            [
                'label' => __( 'Stagger Delay', 'happy-elementor-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 1,
                'step' => 0.01,
                'default' => 0.1,
                'description' => __( 'Variation 1/2: 0.1, Variation 3: 0.15 by default.', 'happy-elementor-addons' ),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'ha_ic_stagger_v3',
            [
                'label' => __( 'Stagger (Variation 3)', 'happy-elementor-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 1,
                'step' => 0.01,
                'default' => 0.15,
                'condition' => [
                    'ha_ic_animation_mode' => 'variation-3',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'ha_ic_entrance_duration',
            [
                'label' => __( 'Entrance Duration (s)', 'happy-elementor-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0.1,
                'max' => 5,
                'step' => 0.1,
                'default' => 0.5,
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'ha_ic_heading_duration',
            [
                'label' => __( 'Heading Fade Duration (s)', 'happy-elementor-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0.1,
                'max' => 5,
                'step' => 0.1,
                'default' => 1,
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'ha_ic_group_duration',
            [
                'label' => __( 'Group Rotation Duration (s)', 'happy-elementor-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 5,
                'max' => 60,
                'step' => 1,
                'default' => 20,
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'ha_ic_flip_interval',
            [
                'label' => __( 'Card Flip Interval (s)', 'happy-elementor-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0.5,
                'max' => 10,
                'step' => 0.1,
                'default' => 2,
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'ha_ic_initial_scale',
            [
                'label' => __( 'Initial Scale', 'happy-elementor-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 0.5,
                'default' => 3,
                'condition' => [
                    'ha_ic_animation_mode!' => 'variation-3',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'ha_ic_v2_scale',
            [
                'label' => __( 'Initial Scale (Variation 2)', 'happy-elementor-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 0.5,
                'default' => 5,
                'condition' => [
                    'ha_ic_animation_mode' => 'variation-2',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'ha_ic_initial_opacity',
            [
                'label' => __( 'Initial Opacity', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.05,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0.8,
                ],
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_controls() {
        $this->__ic_layout_style_controls();
        $this->__ic_content_style_controls();
        $this->__ic_image_style_controls();
        $this->__ic_heading_style_controls();
    }

    protected function __ic_layout_style_controls() {
        $this->start_controls_section(
            '__ha_ic_layout_style',
            [
                'label' => __( 'Layout', 'happy-elementor-addons' ),
                'tab'   => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'ha_ic_wrapper_bg',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ha-ic-wrapper',
            ]
        );

        $this->add_responsive_control(
            'ha_ic_wrapper_padding',
            [
                'label' => __( 'Padding', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-ic-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ha_ic_wrapper_height',
            [
                'label' => __( 'Height', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh', 'em' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 2000 ],
                    'vh' => [ 'min' => 0, 'max' => 300 ],
                    'em' => [ 'min' => 0, 'max' => 100 ],
                ],
                'default' => [
                    'unit' => 'vh',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-ic-wrapper' => 'height: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ha_ic_wrapper_min_height',
            [
                'label' => __( 'Min Height', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh', 'em' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 2000 ],
                    'vh' => [ 'min' => 0, 'max' => 300 ],
                ],
                'default' => [
                    'unit' => 'vh',
                    'size' => 70,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-ic-wrapper' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ha_ic_wrapper_border',
                'selector' => '{{WRAPPER}} .ha-ic-wrapper',
            ]
        );

        $this->add_responsive_control(
            'ha_ic_wrapper_radius',
            [
                'label' => __( 'Border Radius', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-ic-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'ha_ic_wrapper_shadow',
                'selector' => '{{WRAPPER}} .ha-ic-wrapper',
            ]
        );

        $this->add_control(
            'ha_ic_wrapper_overflow',
            [
                'label' => __( 'Overflow', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'hidden' => __( 'Hidden', 'happy-elementor-addons' ),
                    'visible' => __( 'Visible', 'happy-elementor-addons' ),
                ],
                'default' => 'hidden',
                'selectors' => [
                    '{{WRAPPER}} .ha-ic-wrapper' => 'overflow: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function __ic_content_style_controls() {
        $this->start_controls_section(
            '__ha_ic_content_style',
            [
                'label' => __( 'Content', 'happy-elementor-addons' ),
                'tab'   => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_responsive_control(
            'ha_ic_headings_padding',
            [
                'label' => __( 'Headings Padding', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-ic-headings' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ha_ic_headings_margin',
            [
                'label' => __( 'Headings Margin', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-ic-headings' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ha_ic_headings_bg',
            [
                'label' => __( 'Headings Background', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-ic-headings' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ha_ic_headings_alignment',
            [
                'label' => __( 'Alignment', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'happy-elementor-addons' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'happy-elementor-addons' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'happy-elementor-addons' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ha-ic-headings' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ha_ic_headings_gap',
            [
                'label' => __( 'Gap', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-ic-headings' => 'display:flex; flex-direction:column; gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function __ic_image_style_controls() {
        $this->start_controls_section(
            '__ha_ic_image_style',
            [
                'label' => __( 'Image', 'happy-elementor-addons' ),
                'tab'   => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_responsive_control(
            'ha_ic_image_width',
            [
                'label' => __( 'Width', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [ 'min' => 40, 'max' => 500 ],
                    'em' => [ 'min' => 1, 'max' => 22 ],
                ],
                'default' => [
                    'unit' => 'em',
                    'size' => 5,
                ],
                'desktop_default' => [
                    'unit' => 'em',
                    'size' => 6,
                ],
                'tablet_default' => [
                    'unit' => 'em',
                    'size' => 5,
                ],
                'mobile_default' => [
                    'unit' => 'em',
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-ic-card__img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ha_ic_image_radius',
            [
                'label' => __( 'Border Radius', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'default' => [
                    'top' => 5,
                    'right' => 5,
                    'bottom' => 5,
                    'left' => 5,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-ic-card__img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ha_ic_image_border',
                'selector' => '{{WRAPPER}} .ha-ic-card__img',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'ha_ic_image_shadow',
                'selector' => '{{WRAPPER}} .ha-ic-card__img',
            ]
        );

        $this->add_control(
            'ha_ic_image_fit',
            [
                'label' => __( 'Object Fit', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'cover' => __( 'Cover', 'happy-elementor-addons' ),
                    'contain' => __( 'Contain', 'happy-elementor-addons' ),
                    'fill' => __( 'Fill', 'happy-elementor-addons' ),
                ],
                'default' => 'cover',
                'selectors' => [
                    '{{WRAPPER}} .ha-ic-card__img' => 'background-size: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function __ic_heading_style_controls() {
        $this->start_controls_section(
            '__ha_ic_heading_style',
            [
                'label' => __( 'Heading', 'happy-elementor-addons' ),
                'tab'   => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'ha_ic_title_heading',
            [
                'label' => __( 'Title', 'happy-elementor-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'ha_ic_title_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'global' => [ 'default' => Global_Colors::COLOR_SECONDARY ],
                'selectors' => [
                    '{{WRAPPER}} .ha-ic-heading__main' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ha_ic_title_typography',
                'global' => [ 'default' => Global_Typography::TYPOGRAPHY_PRIMARY ],
                'selector' => '{{WRAPPER}} .ha-ic-heading__main',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'ha_ic_title_shadow',
                'selector' => '{{WRAPPER}} .ha-ic-heading__main',
            ]
        );

        $this->add_responsive_control(
            'ha_ic_title_margin',
            [
                'label' => __( 'Margin', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-ic-heading__main' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ha_ic_subtitle_heading',
            [
                'label' => __( 'Subtitle', 'happy-elementor-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'ha_ic_subtitle_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'global' => [ 'default' => Global_Colors::COLOR_SECONDARY ],
                'selectors' => [
                    '{{WRAPPER}} .ha-ic-heading__subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ha_ic_subtitle_typography',
                'global' => [ 'default' => Global_Typography::TYPOGRAPHY_SECONDARY ],
                'selector' => '{{WRAPPER}} .ha-ic-heading__subtitle',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'ha_ic_subtitle_shadow',
                'selector' => '{{WRAPPER}} .ha-ic-heading__subtitle',
            ]
        );

        $this->add_responsive_control(
            'ha_ic_subtitle_margin',
            [
                'label' => __( 'Margin', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-ic-heading__subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
            $settings  = $this->get_settings_for_display();

            $animation_mode = ! empty( $settings['ha_ic_animation_mode'] ) ? $settings['ha_ic_animation_mode'] : 'variation-1';
            $is_variation_3 = 'variation-3' === $animation_mode;

            $this->add_render_attribute( 'ha_image_cycle', [
                'class' => 'ha-ic-wrapper ha-ic--' . esc_attr( $animation_mode ),
                'data-animation-mode' => $animation_mode,
            ] );

            // Headings inline editing
            $this->add_inline_editing_attributes( 'ha_ic_title', 'none' );
            $this->add_render_attribute( 'ha_ic_title', 'class', 'ha-ic-heading__main' );
            $this->add_inline_editing_attributes( 'ha_ic_title_second', 'none' );
            $this->add_render_attribute( 'ha_ic_title_second', 'class', 'ha-ic-heading__main' );
            $this->add_inline_editing_attributes( 'ha_ic_subtitle', 'none' );
            $this->add_render_attribute( 'ha_ic_subtitle', 'class', 'ha-ic-heading__subtitle' );

            $title_tag    = ha_escape_tags( $settings['ha_ic_title_tag'], 'h2' );
            $subtitle_tag = ha_escape_tags( $settings['ha_ic_subtitle_tag'], 'h5' );

            $images = isset( $settings['ha_ic_images'] ) ? $settings['ha_ic_images'] : [];
            // Limit to 10
            if ( count( $images ) > 10 ) {
                $images = array_slice( $images, 0, 10 );
            }
            ?>
            <div <?php $this->print_render_attribute_string( 'ha_image_cycle' ); ?>>
                <div class="ha-ic-content">
                    <div class="ha-ic-scene">
                        <?php if ( $is_variation_3 ) : ?>
                        <div class="ha-ic-container">
                        <?php endif; ?>
                            <div class="ha-ic-group">
                                <?php foreach ( $images as $index => $item ) :
                                    $image_url = '';
                                    if ( ! empty( $item['ha_ic_image']['id'] ) ) {
                                        $image_src = Group_Control_Image_Size::get_attachment_image_src( $item['ha_ic_image']['id'], 'ha_ic_thumbnail', $item );
                                        $image_url = $image_src ? $image_src : $item['ha_ic_image']['url'];
                                    } elseif ( ! empty( $item['ha_ic_image']['url'] ) ) {
                                        $image_url = $item['ha_ic_image']['url'];
                                    } else {
                                        $image_url = Utils::get_placeholder_image_src();
                                    }
                                    $repeater_key = 'ha_ic_card_' . $index;
                                    $this->add_render_attribute( $repeater_key, 'class', 'ha-ic-card elementor-repeater-item-' . $item['_id'] );
                                    $has_link = ! empty( $item['ha_ic_link']['url'] );
                                    if ( $has_link ) {
                                        $link_key = 'ha_ic_link_' . $index;
                                        $this->add_link_attributes( $link_key, $item['ha_ic_link'] );
                                    }
                                ?>
                                <div <?php $this->print_render_attribute_string( $repeater_key ); ?>>
                                    <?php if ( $has_link ) : ?>
                                    <a <?php $this->print_render_attribute_string( $link_key ); ?> class="ha-ic-card__link">
                                    <?php endif; ?>
                                        <div class="ha-ic-card__img" style="background-image: url('<?php echo esc_url( $image_url ); ?>');"></div>
                                    <?php if ( $has_link ) : ?>
                                    </a>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php if ( $is_variation_3 ) : ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="ha-ic-headings">
                        <?php if ( ! empty( $settings['ha_ic_title'] ) ) : ?>
                            <<?php echo $title_tag; ?> <?php $this->print_render_attribute_string( 'ha_ic_title' ); ?>><?php echo esc_html( $settings['ha_ic_title'] ); ?></<?php echo $title_tag; ?>>
                        <?php endif; ?>
                        <?php if ( ! empty( $settings['ha_ic_title_second'] ) ) : ?>
                            <<?php echo $title_tag; ?> <?php $this->print_render_attribute_string( 'ha_ic_title_second' ); ?>><?php echo esc_html( $settings['ha_ic_title_second'] ); ?></<?php echo $title_tag; ?>>
                        <?php endif; ?>
                        <?php if ( ! empty( $settings['ha_ic_subtitle'] ) ) : ?>
                            <<?php echo $subtitle_tag; ?> <?php $this->print_render_attribute_string( 'ha_ic_subtitle' ); ?>><?php echo esc_html( $settings['ha_ic_subtitle'] ); ?></<?php echo $subtitle_tag; ?>>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
    }

}
