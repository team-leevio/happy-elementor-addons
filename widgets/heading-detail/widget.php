<?php
/**
 * Gradient Heading widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Happy_Addons\Elementor\Controls\Group_Control_Foreground;

defined( 'ABSPATH' ) || die();

class Heading_Detail extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Heading With Detail', 'happy-elementor-addons' );
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
        return [ 'gradient', 'advanced', 'heading', 'detail', 'title', 'colorful' ];
    }

	protected function register_content_controls() {
		$this->start_controls_section(
			'_section_title',
			[
				'label' => __( 'Title', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
            'title_first',
            [
                'label' => __( 'First Section', 'happy-elementor-addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Happy Addon', 'happy-elementor-addons' ),
                'placeholder' => __( 'Heading Text', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'title_last',
            [
                'label' => __( 'Second Section', 'happy-elementor-addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Rocks', 'happy-elementor-addons' ),
                'placeholder' => __( 'Heading Text', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __( 'Link', 'happy-elementor-addons' ),
                'type' => Controls_Manager::URL,
                'separator' => 'before',
                'placeholder' => __( 'https://example.com/', 'happy-elementor-addons' ),
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
            'heading_align',
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
                    ]
                ],
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ha-heading' => 'text-align: {{VALUE}}'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_detail',
            [
                'label' => __( 'Detail', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'detail',
            [
                'label' => __( 'Detail', 'happy-elementor-addons' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'happy-elementor-addons' ),
                'placeholder' => __( 'Detail Text here', 'happy-elementor-addons' ),
            ]
        );

        $this->add_responsive_control(
            'detail_align',
            [
                'label' => __( 'Alignment', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __( 'Left', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'flex-end' => [
                        'title' => __( 'Right', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .description' => 'align-items: {{VALUE}}',
                ]
            ]
        );

        $this->end_controls_section();

    }

    protected function register_style_controls() {
        $this->start_controls_section(
            '_section_style_title',
            [
                'label' => __( 'Title', 'happy-elementor-addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'dual_color',
            [
                'label' => __( 'Dual Color on Title?', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'happy-elementor-addons' ),
                'label_off' => __( 'Hide', 'happy-elementor-addons' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->start_controls_tabs( '_tabs_section' );
        $this->start_controls_tab(
            '_tab_first_section',
            [
                'label' => __( 'First Section', 'happy-elementor-addons' ),
                'condition' => [
                    'dual_color' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'first_section_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#562dd4',
                'selectors' => [
                    '{{WRAPPER}} .ha-heading span:first-of-type' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'dual_color' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'first_section_background_color',
            [
                'label' => __( 'Background Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-heading span:first-of-type' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'dual_color' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'first_section_spacing',
            [
                'label' => __( 'Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'responsive' => true,
                'condition' => [
                    'dual_color' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-heading span:first-of-type' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'first_section_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-heading span:first-of-type' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'dual_color' => 'yes'
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_second_section',
            [
                'label' => __( 'Second Section', 'happy-elementor-addons' ),
                'condition' => [
                    'dual_color' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'last_section_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e2498a',
                'selectors' => [
                    '{{WRAPPER}} .ha-heading span:last-of-type' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'dual_color' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'last_section_background_color',
            [
                'label' => __( 'Background Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-heading span:last-of-type' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'dual_color' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'last_section_spacing',
            [
                'label' => __( 'Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'responsive' => true,
                'condition' => [
                    'dual_color' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-heading span:last-of-type' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'last_section_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-heading span:last-of-type' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'dual_color' => 'yes'
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Foreground::get_type(),
            [
                'name' => 'title',
                'selector' => '{{WRAPPER}} .ha-heading',
                'condition' => [
                    'dual_color!' => 'yes'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title',
                'selector' => '{{WRAPPER}} .ha-heading',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title',
                'label' => __( 'Text Shadow', 'happy-elementor-addons' ),
                'selector' => '{{WRAPPER}} .ha-heading',
            ]
        );

        $this->add_control(
            'blend_mode',
            [
                'label' => __( 'Blend Mode', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'Normal', 'happy-elementor-addons' ),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'difference' => 'Difference',
                    'exclusion' => 'Exclusion',
                    'hue' => 'Hue',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-heading' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'none',
                'condition' => [
                    'dual_color!' => 'yes'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_style_detail',
            [
                'label' => __( 'Detail', 'happy-elementor-addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'description_spacing',
            [
                'label' => __( 'Top Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'responsive' => true,
                'default' => [
                    'unit' => 'px',
                    'size' => 15
                ],
                'range' => [
                    'deg' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .description .ha-detail' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'detail_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#676767',
                'selectors' => [
                    '{{WRAPPER}} .ha-detail' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'detail_typhography',
                'selector' => '{{WRAPPER}} .ha-detail',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            ]
        );

        $this->add_control(
            'detail_border_type',
            [
                'label' => __( 'Border Type', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SELECT,
                'separator' => 'before',
                'options' => [
                    '' => __( 'None', 'happy-elementor-addons' ),
                    'solid' => __( 'Solid', 'happy-elementor-addons' ),
                    'double' => __( 'Double', 'happy-elementor-addons' ),
                    'dotted' => __( 'Dotted', 'happy-elementor-addons' ),
                    'dashed' => __( 'Dashed', 'happy-elementor-addons' ),
                    'groove' => __( 'Groove', 'happy-elementor-addons' ),
                ],
                'default' => 'solid',
                'selectors' => [
                    '{{WRAPPER}} span.ha-border:after' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'detail_border_length',
            [
                'label' => __( 'Border Length', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 150
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 800,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} span.ha-border:after' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'detail_border_type!' => '',
                ],
                'responsive' => true,
            ]
        );

        $this->add_control(
            'border_width',
            [
                'label' => __( 'Border Width', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                    'size' => 2
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} span.ha-border:after' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'detail_border_type!' => '',
                ],
                'responsive' => true,
            ]
        );

        $this->add_control(
            'detail_border_color',
            [
                'label' => __( 'Border Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e2498a',
                'selectors' => [
                    '{{WRAPPER}} span.ha-border:after' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'detail_border_type!' => '',
                ],
            ]
        );

        $this->add_control(
            'detail_border_spacing',
            [
                'label' => __( 'Border Top Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 80,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-border' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'detail_border_type!' => '',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_inline_editing_attributes( 'detail', 'basic' );
        $this->add_render_attribute( 'detail', 'class', 'ha-detail' );

        $title_first = wp_kses_post( $settings['title_first' ] );
        $title_last = wp_kses_post( $settings['title_last' ] );
        $detail = wp_kses_post( $settings['detail' ] );

        if ( ! empty( $settings['link']['url'] ) ) {
            $this->add_render_attribute( 'link', 'href', esc_url( $settings['link']['url'] ) );
            if ( ! empty( $settings['link']['is_external'] ) ) {
                $this->add_render_attribute( 'link', 'target', '_blank' );
            }

            if ( ! empty( $settings['link']['nofollow'] ) ) {
                $this->set_render_attribute( 'link', 'rel', 'nofollow' );
            }
            ?>

            <<?php echo tag_escape( $settings['title_tag'] ); ?> class="ha-heading">
                <a <?php echo $this->get_render_attribute_string( 'link' ) ?>>
                    <span><?php echo esc_html( $title_first ); ?></span>
                    <span><?php echo esc_html( $title_last ); ?></span>
                </a>
            </<?php echo tag_escape( $settings['title_tag'] ); ?>>

        <?php
        } else {
            ?>

            <<?php echo tag_escape( $settings['title_tag'] ); ?> class="ha-heading">
                <span><?php echo esc_html( $title_first ); ?></span>
                <span><?php echo esc_html( $title_last ); ?></span>
            </<?php echo tag_escape( $settings['title_tag'] ); ?>>

        <?php } ?>

        <div class="description">
            <?php if ( ! empty( $settings['detail'] ) ) { ?>
            <p <?php echo $this->get_render_attribute_string( 'detail' ); ?>>
                <?php echo esc_html( $detail ); ?>
            </p>
            <?php } ?>
            <span class="ha-border"></span>
        </div>

        <?php

    }

    public function _content_template() {}

}
