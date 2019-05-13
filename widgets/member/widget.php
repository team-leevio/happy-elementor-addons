<?php
/**
 * Member widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Group_Control_Text_Shadow;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || die();

class Member extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Team Member', 'happy_addons' );
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
        return 'hm hm-team-member';
    }

    public function get_keywords() {
        return [ 'team', 'member', 'crew', 'staff', 'person' ];
    }

    protected static function get_profile_names() {
        return [
            '500px' => __( '500px', 'happy_addons' ),
            'apple' => __( 'Apple', 'happy_addons' ),
            'behance' => __( 'Behance', 'happy_addons' ),
            'bitbucket' => __( 'BitBucket', 'happy_addons' ),
            'codepen' => __( 'CodePen', 'happy_addons' ),
            'delicious' => __( 'Delicious', 'happy_addons' ),
            'deviantart' => __( 'DeviantArt', 'happy_addons' ),
            'digg' => __( 'Digg', 'happy_addons' ),
            'dribbble' => __( 'Dribbble', 'happy_addons' ),
            'email' => __( 'Email', 'happy_addons' ),
            'facebook' => __( 'Facebook', 'happy_addons' ),
            'flickr' => __( 'Flicker', 'happy_addons' ),
            'foursquare' => __( 'FourSquare', 'happy_addons' ),
            'github' => __( 'Github', 'happy_addons' ),
            'houzz' => __( 'Houzz', 'happy_addons' ),
            'instagram' => __( 'Instagram', 'happy_addons' ),
            'jsfiddle' => __( 'JS Fiddle', 'happy_addons' ),
            'linkedin' => __( 'LinkedIn', 'happy_addons' ),
            'medium' => __( 'Medium', 'happy_addons' ),
            'pinterest' => __( 'Pinterest', 'happy_addons' ),
            'product-hunt' => __( 'Product Hunt', 'happy_addons' ),
            'reddit' => __( 'Reddit', 'happy_addons' ),
            'slideshare' => __( 'Slide Share', 'happy_addons' ),
            'snapchat' => __( 'Snapchat', 'happy_addons' ),
            'soundcloud' => __( 'SoundCloud', 'happy_addons' ),
            'spotify' => __( 'Spotify', 'happy_addons' ),
            'stack-overflow' => __( 'StackOverflow', 'happy_addons' ),
            'tripadvisor' => __( 'TripAdvisor', 'happy_addons' ),
            'tumblr' => __( 'Tumblr', 'happy_addons' ),
            'twitch' => __( 'Twitch', 'happy_addons' ),
            'twitter' => __( 'Twitter', 'happy_addons' ),
            'vimeo' => __( 'Vimeo', 'happy_addons' ),
            'vk' => __( 'VK', 'happy_addons' ),
            'website' => __( 'Website', 'happy_addons' ),
            'whatsapp' => __( 'WhatsApp', 'happy_addons' ),
            'wordpress' => __( 'WordPress', 'happy_addons' ),
            'xing' => __( 'Xing', 'happy_addons' ),
            'yelp' => __( 'Yelp', 'happy_addons' ),
            'youtube' => __( 'YouTube', 'happy_addons' ),
        ];
    }

    /**
     * Register content related controls
     */
	protected function register_content_controls() {
		$this->start_controls_section(
			'_section_info',
			[
				'label' => __( 'Information', 'happy_addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
            'image',
            [
                'label' => __( 'Photo', 'happy_addons' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image',
                'default' => 'medium',
                'separator' => 'none',
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __( 'Name', 'happy_addons' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Jhon Doe', 'happy_addons' ),
                'placeholder' => __( 'Type member name', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'job_title',
            [
                'label' => __( 'Job Title', 'happy_addons' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Head Of Ideas', 'happy_addons' ),
                'placeholder' => __( 'Type member job title', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'bio',
            [
                'label' => __( 'Short Bio', 'happy_addons' ),
                'type' => Controls_Manager::TEXTAREA,
                'placeholder' => __( 'Write something amazing about the member', 'happy_addons' ),
                'rows' => 5
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => __( 'Title HTML Tag', 'happy_addons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'h1'  => [
                        'title' => __( 'H1', 'happy_addons' ),
                        'icon' => 'eicon-editor-h1'
                    ],
                    'h2'  => [
                        'title' => __( 'H2', 'happy_addons' ),
                        'icon' => 'eicon-editor-h2'
                    ],
                    'h3'  => [
                        'title' => __( 'H3', 'happy_addons' ),
                        'icon' => 'eicon-editor-h3'
                    ],
                    'h4'  => [
                        'title' => __( 'H4', 'happy_addons' ),
                        'icon' => 'eicon-editor-h4'
                    ],
                    'h5'  => [
                        'title' => __( 'H5', 'happy_addons' ),
                        'icon' => 'eicon-editor-h5'
                    ],
                    'h6'  => [
                        'title' => __( 'H6', 'happy_addons' ),
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
                'label' => __( 'Alignment', 'happy_addons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'happy_addons' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'happy_addons' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'happy_addons' ),
                        'icon' => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => __( 'Justify', 'happy_addons' ),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}}'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_social',
            [
                'label' => __( 'Social Profiles', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'name',
            [
                'label' => __( 'Profile Name', 'happy_addons' ),
                'type' => Controls_Manager::SELECT2,
                'select2options' => [
                    'allowClear' => false,
                ],
                'options' => self::get_profile_names()
            ]
        );

        $repeater->add_control(
            'link', [
                'label' => __( 'Profile Link', 'happy_addons' ),
                'placeholder' => __( 'Add your profile link', 'happy_addons' ),
                'type' => Controls_Manager::URL,
                'label_block' => false,
                'autocomplete' => false,
                'show_external' => false,
                'condition' => [
                    'name!' => 'email'
                ]
            ]
        );

        $repeater->add_control(
            'email', [
                'label' => __( 'Email Address', 'happy_addons' ),
                'placeholder' => __( 'Add your email address', 'happy_addons' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => false,
                'input_type' => 'email',
                'condition' => [
                    'name' => 'email'
                ]
            ]
        );

        $repeater->add_control(
            'customize',
            [
                'label' => __( 'Want To Customize?', 'happy_addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'happy_addons' ),
                'label_off' => __( 'No', 'happy_addons' ),
                'return_value' => 'yes',
            ]
        );

        $repeater->start_controls_tabs(
            '_tab_icon_colors',
            [
                'condition' => ['customize' => 'yes']
            ]
        );
        $repeater->start_controls_tab(
            '_tab_icon_normal',
            [
                'label' => __( 'Normal', 'happy_addons' ),
            ]
        );

        $repeater->add_control(
            'color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-member-links > {{CURRENT_ITEM}}' => 'color: {{VALUE}}',
                ],
                'condition' => ['customize' => 'yes']
            ]
        );

        $repeater->add_control(
            'bg_color',
            [
                'label' => __( 'Background Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-member-links > {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
                ],
                'condition' => ['customize' => 'yes']
            ]
        );

        $repeater->end_controls_tab();
        $repeater->start_controls_tab(
            '_tab_icon_hover',
            [
                'label' => __( 'Hover', 'happy_addons' ),
            ]
        );

        $repeater->add_control(
            'hover_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-member-links > {{CURRENT_ITEM}}:hover, {{WRAPPER}} .ha-member-links > {{CURRENT_ITEM}}:focus' => 'color: {{VALUE}}',
                ],
                'condition' => ['customize' => 'yes']
            ]
        );

        $repeater->add_control(
            'hover_bg_color',
            [
                'label' => __( 'Background Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-member-links > {{CURRENT_ITEM}}:hover, {{WRAPPER}} .ha-member-links > {{CURRENT_ITEM}}:focus' => 'background-color: {{VALUE}}',
                ],
                'condition' => ['customize' => 'yes']
            ]
        );

        $repeater->add_control(
            'hover_border_color',
            [
                'label' => __( 'Border Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-member-links > {{CURRENT_ITEM}}:hover, {{WRAPPER}} .ha-member-links > {{CURRENT_ITEM}}:focus' => 'border-color: {{VALUE}}',
                ],
                'condition' => ['customize' => 'yes']
            ]
        );

        $repeater->end_controls_tab();
        $repeater->end_controls_tabs();

        $this->add_control(
            'profiles',
            [
                'show_label' => false,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '<# print(name.slice(0,1).toUpperCase() + name.slice(1)) #>',
                'default' => [
                    [
                        'link' => ['url' => 'https://facebook.com/'],
                        'name' => 'facebook'
                    ],
                    [
                        'link' => ['url' => 'https://twitter.com/'],
                        'name' => 'twitter'
                    ],
                    [
                        'link' => ['url' => 'https://linkedin.com/'],
                        'name' => 'linkedin'
                    ]
                ]
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register styles related controls
     */
    protected function register_style_controls() {
        $this->start_controls_section(
            '_section_style_image',
            [
                'label' => __( 'Photo', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label' => __( 'Width', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%'],
                'range' => [
                    '%' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 100,
                        'max' => 700,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-member-figure' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => __( 'Height', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 700,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-member-figure' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_spacing',
            [
                'label' => __( 'Spacing', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-member-figure' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => __( 'Padding', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-member-figure > img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .ha-member-figure > img, {{WRAPPER}} .ha-blurb-figure--icon'
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-member-figure > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .ha-member-figure > img'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_style_content',
            [
                'label' => __( 'Name, Job Title & Bio', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __( 'Content Padding', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-member-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            '_heading_title',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Name', 'happy_addons' ),
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __( 'Spacing', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-member-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-member-name' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .ha-member-name',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title_text_shadow',
                'selector' => '{{WRAPPER}} .ha-member-name',
            ]
        );

        $this->add_control(
            '_heading_job_title',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Job Title', 'happy_addons' ),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'job_title_spacing',
            [
                'label' => __( 'Spacing', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-member-position' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'job_title_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-member-position' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'job_title_typography',
                'label' => __( 'Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-member-position',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'job_title_text_shadow',
                'selector' => '{{WRAPPER}} .ha-member-position',
            ]
        );

        $this->add_control(
            '_heading_bio',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Short Bio', 'happy_addons' ),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'bio_spacing',
            [
                'label' => __( 'Spacing', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-member-bio' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'bio_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-member-bio' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'bio_typography',
                'label' => __( 'Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-member-bio',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'bio_text_shadow',
                'selector' => '{{WRAPPER}} .ha-member-bio',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_style_social',
            [
                'label' => __( 'Social Icons', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'links_spacing',
            [
                'label' => __( 'Spacing', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-member-links > a:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'links_padding',
            [
                'label' => __( 'Padding', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-member-links > a' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'links_icon_size',
            [
                'label' => __( 'Icon Size', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-member-links > a' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'links_border',
                'selector' => '{{WRAPPER}} .ha-member-links > a'
            ]
        );

        $this->add_responsive_control(
            'links_border_radius',
            [
                'label' => __( 'Border Radius', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-member-links > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( '_tab_links_colors' );
        $this->start_controls_tab(
            '_tab_links_normal',
            [
                'label' => __( 'Normal', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'links_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-member-links > a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'links_bg_color',
            [
                'label' => __( 'Background Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-member-links > a' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            '_tab_links_hover',
            [
                'label' => __( 'Hover', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'links_hover_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-member-links > a:hover, {{WRAPPER}} .ha-member-links > a:focus' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'links_hover_bg_color',
            [
                'label' => __( 'Background Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-member-links > a:hover, {{WRAPPER}} .ha-member-links > a:focus' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'links_hover_border_color',
            [
                'label' => __( 'Border Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-member-links > a:hover, {{WRAPPER}} .ha-member-links > a:focus' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'links_border_border!' => '',
                ]
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

	protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_inline_editing_attributes( 'title', 'none' );
        $this->add_render_attribute( 'title', 'class', 'ha-member-name' );

        $this->add_inline_editing_attributes( 'job_title', 'none' );
        $this->add_render_attribute( 'job_title', 'class', 'ha-member-position' );

        $this->add_inline_editing_attributes( 'bio', 'basic' );
        $this->add_render_attribute( 'bio', 'class', 'ha-member-bio' );
        ?>

        <?php if ( ! empty( $settings['image']['url'] ) ) :
            $this->add_render_attribute( 'image', 'src', $settings['image']['url'] );
            $this->add_render_attribute( 'image', 'alt', Control_Media::get_image_alt( $settings['image'] ) );
            $this->add_render_attribute( 'image', 'title', Control_Media::get_image_title( $settings['image'] ) );
            $settings['hover_animation'] = 'disable-animation'; // hack to prevent image hover animation
            ?>
            <figure class="ha-member-figure">
                <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'image' ); ?>
            </figure>
        <?php endif; ?>

        <div class="ha-member-body">
            <?php printf( '<%1$s %2$s>%3$s</%1$s>',
                tag_escape( $settings['title_tag'] ),
                $this->get_render_attribute_string( 'title' ),
                esc_html( $settings['title' ] )
            ); ?>
            <div <?php echo $this->get_render_attribute_string( 'job_title' ); ?>><?php echo esc_html( $settings['job_title' ] ); ?></div>
            <div <?php echo $this->get_render_attribute_string( 'bio' ); ?>>
                <p><?php echo wp_kses_data( $settings['bio'] ); ?></p>
            </div>

            <?php if ( ! empty( $settings['profiles' ] ) ) : ?>
                <div class="ha-member-links">
                    <?php
                    foreach ( $settings['profiles'] as $profile ) :
                        $icon = $profile['name'];
                        $url = esc_url( $profile['link']['url'] );

                        if ($profile['name'] === 'website') {
                            $icon = 'globe';
                        } elseif ($profile['name'] === 'email') {
                            $icon = 'envelope';
                            $url = 'mailto:' . antispambot( $profile['email'] );
                        }

                        printf( '<a target="_blank" rel="noopener" data-tooltip="hello" href="%s" class="elementor-repeater-item-%s"><i class="fa fa-%s" aria-hidden="true"></i></a>',
                            $url,
                            esc_attr( $profile['_id'] ),
                            esc_attr( $icon )
                        );
                    endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    public function _content_template() {
        ?>
        <#
        view.addInlineEditingAttributes( 'title', 'none' );
        view.addRenderAttribute( 'title', 'class', 'ha-member-name' );

        view.addInlineEditingAttributes( 'job_title', 'none' );
        view.addRenderAttribute( 'job_title', 'class', 'ha-member-position' );

        view.addInlineEditingAttributes( 'bio', 'basic' );
        view.addRenderAttribute( 'bio', 'class', 'ha-member-bio' );

        if ( settings.image.url ) {
            var image = {
                id: settings.image.id,
                url: settings.image.url,
                size: settings.image_size,
                dimension: settings.image_custom_dimension,
                model: view.getEditModel()
            };

            var image_url = elementor.imagesManager.getImageUrl( image );
            #>
            <figure class="ha-member-figure">
                <img src="{{ image_url }}">
            </figure>
        <# } #>
        <div class="ha-member-body">
            <{{ settings.title_tag }} {{{ view.getRenderAttributeString( 'title' ) }}}>{{ settings.title }}</{{ settings.title_tag }}>
            <div {{{ view.getRenderAttributeString( 'job_title' ) }}}>{{ settings.job_title }}</div>
            <div {{{ view.getRenderAttributeString( 'bio' ) }}}>
                <p>{{{ settings.bio }}}</p>
            </div>
            <# if (_.isArray(settings.profiles)) { #>
                <div class="ha-member-links">
                    <# _.each(settings.profiles, function(profile, index) {
                        var icon = profile.name,
                            url = profile.link.url,
                            linkKey = view.getRepeaterSettingKey( 'profile', 'profiles', index);

                        if (profile.name === 'website') {
                            icon = 'globe';
                        } else if (profile.name === 'email') {
                            icon = 'envelope'
                            url = 'mailto:' + profile.email;
                        }

                        view.addRenderAttribute( linkKey, 'class', 'elementor-repeater-item-' + profile._id );
                        view.addRenderAttribute( linkKey, 'href', url ); #>
                        <a {{{view.getRenderAttributeString( linkKey )}}}><i class="fa fa-{{{icon}}}"></i></a>
                    <# }); #>
                </div>
            <# } #>
        </div>
        <?php
    }
}
