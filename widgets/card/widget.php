<?php
/**
 * Card widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

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
        return __( 'Happy Card', 'happy_addons' );
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
        return 'fa fa-smile-o';
    }

    public function get_keywords() {
        return [ 'card', 'blurb', 'content', 'block', 'box' ];
    }

	protected function register_content_controls() {
		$this->start_controls_section(
			'_section_image',
			[
				'label' => __( 'Image & Label', 'happy_addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
            'image',
            [
                'label' => __( 'Image', 'happy_addons' ),
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
                'default' => 'large',
                'separator' => 'none',
            ]
        );

        $this->add_responsive_control(
            'image_position',
            [
                'label' => __( 'Image Position', 'happy_addons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'happy_addons' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'top' => [
                        'title' => __( 'Top', 'happy_addons' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'happy_addons' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'desktop_default' => 'top',
                'toggle' => false,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'prefix_class' => 'ha-card--%s'
            ]
        );

        $this->add_control(
            'label',
            [
                'label' => __( 'Label', 'happy_addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Default label', 'happy_addons' ),
                'placeholder' => __( 'Type your label text here', 'happy_addons' ),
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'label_position',
            [
                'label' => __( 'Label Position', 'happy_addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'top-left'  => __( 'Top Left', 'happy_addons' ),
                    'top-center'  => __( 'Top Center', 'happy_addons' ),
                    'top-right'  => __( 'Top Right', 'happy_addons' ),
                    'middle-left'  => __( 'Middle Left', 'happy_addons' ),
                    'middle-center'  => __( 'Middle Center', 'happy_addons' ),
                    'middle-right'  => __( 'Middle Right', 'happy_addons' ),
                    'bottom-left'  => __( 'Bottom Left', 'happy_addons' ),
                    'bottom-center'  => __( 'Bottom Center', 'happy_addons' ),
                    'bottom-right'  => __( 'Bottom Right', 'happy_addons' ),
                ],
                'default' => 'top-right',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_title',
            [
                'label' => __( 'Title & Description', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'happy_addons' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Default title', 'happy_addons' ),
                'placeholder' => __( 'Type your card title here', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __( 'Description', 'happy_addons' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __( 'Default description', 'happy_addons' ),
                'placeholder' => __( 'Type your card description here', 'happy_addons' ),
                'rows' => 5
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => __( 'Title HTML Tag', 'happy_addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1'  => __( 'H1', 'happy_addons' ),
                    'h2'  => __( 'H2', 'happy_addons' ),
                    'h3'  => __( 'H3', 'happy_addons' ),
                    'h4'  => __( 'H4', 'happy_addons' ),
                    'h5'  => __( 'H5', 'happy_addons' ),
                    'h6'  => __( 'H6', 'happy_addons' ),
                ],
                'default' => 'h2',
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
                ],
                'desktop_default' => 'left',
                'toggle' => false,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'prefix_class' => 'ha-text--%s'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_button',
            [
                'label' => __( 'Button', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'btn_text',
            [
                'label' => __( 'Text', 'happy_addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Default text', 'happy_addons' ),
                'placeholder' => __( 'Type your button text here', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'btn_link',
            [
                'label' => __( 'Link', 'happy_addons' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://example.com/', 'happy_addons' ),
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->add_control(
            'btn_icon',
            [
                'label' => __( 'Icon', 'happy_addons' ),
                'type' => Controls_Manager::ICON,
                'label_block' => true,
                'default' => '',
            ]
        );

        $this->add_control(
            'btn_icon_position',
            [
                'label' => __( 'Icon Position', 'happy_addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'before',
                'options' => [
                    'before' => __( 'Before', 'happy_addons' ),
                    'after' => __( 'After', 'happy_addons' ),
                ],
                'condition' => [
                    'btn_icon!' => '',
                ],
            ]
        );

        $this->add_control(
            'icon_indent',
            [
                'label' => __( 'Icon Spacing', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'condition' => [
                    'btn_icon!' => '',
                ],
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
            '_section_image_style',
            [
                'label' => __( 'Image', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label' => __( 'Width', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => [ '%', 'px' ],
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
                    '{{WRAPPER}}.ha-card--right .ha-card-figure, {{WRAPPER}}.ha-card--left .ha-card-figure' => 'flex: 0 0 {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ha-card--top .ha-card-figure' => 'width: {{SIZE}}{{UNIT}};',
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
                        'min' => 50,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-card-figure' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_offset',
            [
                'label' => __( 'Offset', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'allowed_dimensions' => ['top', 'left'],
                'selectors' => [
                    '{{WRAPPER}} .ha-card-figure' => '-ms-transform: translate({{LEFT}}{{UNIT}}, {{TOP}}{{UNIT}}); -webkit-transform: translate({{LEFT}}{{UNIT}}, {{TOP}}{{UNIT}}); transform: translate({{LEFT}}{{UNIT}}, {{TOP}}{{UNIT}});',
                    '{{WRAPPER}}.ha-card--top .ha-card-body' => 'margin-top: {{TOP}}{{UNIT}};',
                    '{{WRAPPER}}.ha-card--left .ha-card-body' => 'margin-left: {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.ha-card--right .ha-card-body' => 'margin-right: calc(-1 * {{LEFT}}{{UNIT}});',
                ],
                'default' => [
                    'isLinked' => false,
                ]
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => __( 'Padding', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-card-figure > img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .ha-card-figure > img',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-card-figure > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .ha-card-figure > img',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_label_style',
            [
                'label' => __( 'Label', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'label_margin',
            [
                'label' => __( 'Margin', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'label_padding',
            [
                'label' => __( 'Padding', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'label_bg_color',
            [
                'label' => __( 'Background Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-label' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'label_border',
                'selector' => '{{WRAPPER}} .ha-label',
            ]
        );

        $this->add_responsive_control(
            'label_border_radius',
            [
                'label' => __( 'Border Radius', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'label_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .ha-label',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'label' => __( 'Typography', 'happy_addons' ),
                'exclude' => [
                    'font_family',
                    'line_height'
                ],
                'selector' => '{{WRAPPER}} .ha-label',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_content_style',
            [
                'label' => __( 'Title & Description', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __( 'Container Padding', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-card-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_heading',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Title', 'happy_addons' ),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => __( 'Margin', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-card-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
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
                'label' => __( 'Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-card-title',
            ]
        );

        $this->add_control(
            'description_heading',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Description', 'happy_addons' ),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'description_margin',
            [
                'label' => __( 'Margin', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-card-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-card-text' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => __( 'Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-card-text',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_button_style',
            [
                'label' => __( 'Button', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'btn_padding',
            [
                'label' => __( 'Padding', 'happy_addons' ),
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
                'name' => 'btn_typography',
                'selector' => '{{WRAPPER}} .ha-btn',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btn_border',
                'selector' => '{{WRAPPER}} .ha-btn',
            ]
        );

        $this->add_control(
            'btn_border_radius',
            [
                'label' => __( 'Border Radius', 'happy_addons' ),
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
                'name' => 'btn_box_shadow',
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

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __( 'Normal', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'btn_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ha-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_bg_color',
            [
                'label' => __( 'Background Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __( 'Hover', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'btn_hover_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-btn:hover, {{WRAPPER}} .ha-btn:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_bg_color',
            [
                'label' => __( 'Background Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-btn:hover, {{WRAPPER}} .ha-btn:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_border_color',
            [
                'label' => __( 'Border Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'btn_border_border!' => '',
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

        $this->add_inline_editing_attributes( 'label', 'none' );
        $this->add_render_attribute(
            'label',
            'class',
            ['ha-label', sprintf( 'ha-label--%s', esc_attr( $settings['label_position'] ) )]
        );

        $this->add_inline_editing_attributes( 'title', 'none' );
        $this->add_render_attribute( 'title', 'class', 'ha-card-title' );

        $this->add_inline_editing_attributes( 'description', 'basic' );
        $this->add_render_attribute( 'description', 'class', 'ha-card-text' );

        $this->add_inline_editing_attributes( 'btn_text', 'none' );
        $this->add_render_attribute( 'btn_text', 'class', 'ha-btn-text' );

        $this->add_render_attribute( 'btn', 'class', 'ha-btn' );
        $this->add_render_attribute( 'btn', 'href', esc_url( $settings['btn_link']['url'] ) );
        if ( ! empty( $settings['btn_link']['is_external'] ) ) {
            $this->add_render_attribute( 'btn', 'target', '_blank' );
        }
        if ( ! empty( $settings['btn_link']['nofollow'] ) ) {
            $this->set_render_attribute( 'btn', 'rel', 'nofollow' );
        }
        ?>

        <?php if ( ! empty( $settings['image']['url'] ) ) :
            $this->add_render_attribute( 'image', 'src', $settings['image']['url'] );
            $this->add_render_attribute( 'image', 'alt', Control_Media::get_image_alt( $settings['image'] ) );
            $this->add_render_attribute( 'image', 'title', Control_Media::get_image_title( $settings['image'] ) );
            $settings['hover_animation'] = 'disable-animation'; // hack to prevent image hover animation
            ?>
            <figure class="ha-card-figure">
                <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'image' ); ?>
                <div <?php echo $this->get_render_attribute_string( 'label' ); ?>><?php echo esc_html( $settings['label'] ); ?></div>
            </figure>
        <?php endif; ?>

        <div class="ha-card-body">
            <?php printf( '<%1$s %2$s>%3$s</%1$s>',
                tag_escape( $settings['title_tag'] ),
                $this->get_render_attribute_string( 'title' ),
                esc_html( $settings['title' ] )
                ); ?>
            <div <?php echo $this->get_render_attribute_string( 'description' ); ?>>
                <p><?php echo wp_kses_data( $settings['description'] ); ?></p>
            </div>
            <?php
            if ( $settings['btn_text'] && empty( $settings['btn_icon'] ) ) :
                printf( '<a %1$s>%2$s</a>',
                    $this->get_render_attribute_string( 'btn' ),
                    sprintf( '<span %1$s>%2$s</span>', $this->get_render_attribute_string( 'btn_text' ), esc_html( $settings['btn_text'] ) )
                    );
            elseif ( empty( $settings['btn_text'] ) && $settings['btn_icon'] ) :
                printf( '<a %1$s>%2$s</a>',
                    $this->get_render_attribute_string( 'btn' ),
                    sprintf( '<i class="%1$s"></i>', esc_attr( $settings['btn_icon'] ) )
                );
            elseif ( $settings['btn_text'] && $settings['btn_icon'] ) :
                if ( $settings['btn_icon_position'] === 'before' ) :
                    $this->add_render_attribute( 'btn', 'class', 'ha-btn--icon-before' );
                    $btn_before = sprintf( '<i class="ha-btn-icon %1$s"></i>', esc_attr( $settings['btn_icon'] ) );
                    $btn_after = sprintf( '<span %1$s>%2$s</span>', $this->get_render_attribute_string( 'btn_text' ), esc_html( $settings['btn_text'] ) );
                else :
                    $this->add_render_attribute( 'btn', 'class', 'ha-btn--icon-after' );
                    $btn_before = sprintf( '<span %1$s>%2$s</span>', $this->get_render_attribute_string( 'btn_text' ), esc_html( $settings['btn_text'] ) );
                    $btn_after = sprintf( '<i class="ha-btn-icon %1$s"></i>', esc_attr( $settings['btn_icon'] ) );
                endif;
                printf( '<a %1$s>%2$s %3$s</a>',
                    $this->get_render_attribute_string( 'btn' ),
                    $btn_before,
                    $btn_after
                );
            endif;
            ?>
        </div>
        <?php
    }

    public function _content_template() {
        ?>
        <#
        view.addInlineEditingAttributes( 'label', 'none' );
        view.addRenderAttribute(
            'label',
            'class',
            ['ha-label', 'ha-label--' + settings.label_position]
        );

        view.addInlineEditingAttributes( 'title', 'none' );
        view.addRenderAttribute( 'title', 'class', 'ha-card-title' );

        view.addInlineEditingAttributes( 'description', 'basic' );
        view.addRenderAttribute( 'description', 'class', 'ha-card-text' );

        view.addInlineEditingAttributes( 'btn_text', 'none' );
        view.addRenderAttribute( 'btn_text', 'class', 'ha-btn-text' );

        view.addRenderAttribute( 'btn', 'class', 'ha-btn' );
        view.addRenderAttribute( 'btn', 'href', settings.btn_link.url );

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
            <figure class="ha-card-figure">
                <img src="{{ image_url }}">
                <div {{{ view.getRenderAttributeString( 'label' ) }}}>{{ settings.label }}</div>
            </figure>
        <# } #>

        <div class="ha-card-body">
            <{{ settings.title_tag }} {{{ view.getRenderAttributeString( 'title' ) }}}>{{ settings.title }}</{{ settings.title_tag }}>

            <div {{{ view.getRenderAttributeString( 'description' ) }}}>
                <p>{{{ settings.description }}}</p>
            </div>

            <# if ( settings.btn_text && ! settings.btn_icon ) { #>
                <a {{{ view.getRenderAttributeString( 'btn' ) }}}><span {{{ view.getRenderAttributeString( 'btn_text' ) }}}>{{ settings.btn_text }}</span></a>
            <# } else if ( ! settings.btn_text && settings.btn_icon ) { #>
                <a {{{ view.getRenderAttributeString( 'btn' ) }}}><i class="{{ settings.btn_icon }}"></i></a>
            <# } else if ( settings.btn_text && settings.btn_icon ) { #>
                <#
                if ( settings.btn_icon_position === 'before' ) {
                    view.addRenderAttribute( 'btn', 'class', 'ha-btn--icon-before' );
                    var btn_before = '<i class="ha-btn-icon ' + settings.btn_icon + '"></i>';
                    var btn_after = '<span ' + view.getRenderAttributeString( 'btn_text' ) + '>' + settings.btn_text + '</span>';
                } else {
                    view.addRenderAttribute( 'btn', 'class', 'ha-btn--icon-after' );
                    var btn_after = '<i class="ha-btn-icon ' + settings.btn_icon + '"></i>';
                    var btn_before = '<span ' + view.getRenderAttributeString( 'btn_text' ) + '>' + settings.btn_text + '</span>';
                }
                #>
                <a {{{ view.getRenderAttributeString( 'btn' ) }}}>{{{ btn_before }}} {{{ btn_after }}}</a>
            <# } #>
        </div>
        <?php
    }
}
