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
        return __( 'Heading With Detail', 'happy_addons' );
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
				'label' => __( 'Title', 'happy_addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'happy_addons' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __( 'Happy Elementor Addon Rocks', 'happy_addons' ),
                'placeholder' => __( 'Heading Text', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __( 'Link', 'happy_addons' ),
                'type' => Controls_Manager::URL,
                'separator' => 'before',
                'placeholder' => __( 'https://example.com/', 'happy_addons' ),
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
            'heading_align',
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
                    '{{WRAPPER}} .ha-gradient-heading' => 'text-align: {{VALUE}}'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_detail',
            [
                'label' => __( 'Detail', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'detail',
            [
                'label' => __( 'Detail', 'happy_addons' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'happy_addons' ),
                'placeholder' => __( 'Detail Text here', 'happy_addons' ),
            ]
        );

        $this->add_responsive_control(
            'detail_align',
            [
                'label' => __( 'Alignment', 'happy_addons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __( 'Left', 'happy_addons' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'happy_addons' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'flex-end' => [
                        'title' => __( 'Right', 'happy_addons' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ha-detail' => 'align-items: {{VALUE}}'
                ]
            ]
        );

        $this->end_controls_section();

    }

    protected function register_style_controls() {
        $this->start_controls_section(
            '_section_style_title',
            [
                'label' => __( 'Title', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Foreground::get_type(),
            [
                'name' => 'title',
                'selector' => '{{WRAPPER}} .ha-gradient-heading',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title',
                'selector' => '{{WRAPPER}} .ha-gradient-heading',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title',
                'label' => __( 'Text Shadow', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-gradient-heading',
            ]
        );

        $this->add_control(
            'blend_mode',
            [
                'label' => __( 'Blend Mode', 'happy_addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'Normal', 'happy_addons' ),
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
                    '{{WRAPPER}} .ha-gradient-heading' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_style_detail',
            [
                'label' => __( 'Detail', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'detail_spacing',
            [
                'label' => __( 'Top Spacing', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
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
                    '{{WRAPPER}} .ha-detail' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'detail_color',
            [
                'label' => __( 'Color', 'happy_addons' ),
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
                'label' => __( 'Border Type', 'happy_addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'None', 'happy_addons' ),
                    'solid' => __( 'Solid', 'happy_addons' ),
                    'double' => __( 'Double', 'happy_addons' ),
                    'dotted' => __( 'Dotted', 'happy_addons' ),
                    'dashed' => __( 'Dashed', 'happy_addons' ),
                    'groove' => __( 'Groove', 'happy_addons' ),
                ],
                'default' => 'solid',
                'selectors' => [
                    '{{WRAPPER}} .ha-detail span:after' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'detail_border_length',
            [
                'label' => __( 'Border Length', 'happy_addons' ),
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
                    '{{WRAPPER}} .ha-detail span:after' => 'width: {{SIZE}}{{UNIT}};',
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
                'label' => __( 'Border Width', 'happy_addons' ),
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
                    '{{WRAPPER}} .ha-detail span:after' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
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
                'label' => __( 'Border Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e2498a',
                'selectors' => [
                    '{{WRAPPER}} .ha-detail span:after' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'detail_border_type!' => '',
                ],
            ]
        );

        $this->add_control(
            'detail_border_spacing',
            [
                'label' => __( 'Border Top Spacing', 'happy_addons' ),
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
                    '{{WRAPPER}} .ha-detail span' => 'margin-top: {{SIZE}}{{UNIT}};',
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

        $this->add_inline_editing_attributes( 'title', 'basic' );
        $this->add_render_attribute( 'title', 'class', 'ha-gradient-heading' );

        $title = wp_kses_post( $settings['title' ] );
        $detail = wp_kses_post( $settings['detail' ] );

        if ( ! empty( $settings['link']['url'] ) ) {
            $this->add_render_attribute( 'link', 'href', esc_url( $settings['link']['url'] ) );
            if ( ! empty( $settings['link']['is_external'] ) ) {
                $this->add_render_attribute( 'link', 'target', '_blank' );
            }

            if ( ! empty( $settings['link']['nofollow'] ) ) {
                $this->set_render_attribute( 'link', 'rel', 'nofollow' );
            }

            $title = sprintf( '<a %s>%s</a>',
                $this->get_render_attribute_string( 'link' ),
                $title
                );
        }

        printf( '<%1$s %2$s>%3$s</%1$s>'. '<p class="ha-detail">%4$s<span></span></p>',
            tag_escape( $settings['title_tag'] ),
            $this->get_render_attribute_string( 'title' ),
            $title,
            $detail
        );
    }

    public function _content_template() {
        ?>
        <#
        view.addInlineEditingAttributes( 'title', 'none' );
        view.addRenderAttribute( 'title', 'class', 'ha-gradient-heading' );

        view.addInlineEditingAttributes( 'detail', 'none' );
        view.addRenderAttribute( 'detail', 'class', 'ha-detail' );

        var title = _.isEmpty(settings.link.url) ? settings.title : '<a href="'+settings.link.url+'">'+settings.title+'</a>';
        #>
        <{{ settings.title_tag }} {{{ view.getRenderAttributeString( 'title' ) }}}>
            {{{ title }}}
        </{{ settings.title_tag }}>
        <p class="ha-detail">{{ settings.detail }}<span></span></p>

        <?php
    }
}
