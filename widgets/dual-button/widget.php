<?php
/**
 * Dual Button widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

defined( 'ABSPATH' ) || die();

class Dual_Button extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Dual Button', 'happy-elementor-addons' );
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
        return 'hm hm-accordion-horizontal';
    }

    public function get_keywords() {
        return [ 'button', 'btn', 'dual', 'advance', 'link' ];
    }

    protected function register_content_controls() {
        $this->start_controls_section(
            '_section_button',
            [
                'label' => __( 'Dual Buttons', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->start_controls_tabs( '_tabs_buttons' );

        $this->start_controls_tab(
            '_tab_button_left',
            [
                'label' => __( 'Left', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'left_button_text',
            [
                'label' => __( 'Text', 'happy-elementor-addons' ),
                'label_block'=> true,
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Button Text', 'happy-elementor-addons' )
            ]
        );

        $this->add_control(
            'left_button_link',
            [
                'label' => __( 'Link', 'happy-elementor-addons' ),
                'type' => Controls_Manager::URL,
            ]
        );

        $this->add_control(
            'left_button_icon',
            [
                'label' => __( 'Icon', 'happy-elementor-addons' ),
                'type' => Controls_Manager::ICON,
                'options' => ha_get_happy_icons(),
            ]
        );

        $this->add_control(
            'left_button_icon_position',
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
                    ]
                ],
                'toggle' => false,
                'default' => 'before',
                'condition' => [
                    'left_button_icon!' => '',
                ]
            ]
        );

        $this->add_control(
            'left_button_icon_spacing',
            [
                'label' => __( 'Icon Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'condition' => [
                    'left_button_icon!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn--left .ha-dual-btn-icon--before' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ha-dual-btn--left .ha-dual-btn-icon--after' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_button_connector',
            [
                'label' => __( 'Connector', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'button_connector_hide',
            [
                'label' => __( 'Hide Connector?', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Hide', 'happy-elementor-addons' ),
                'label_off' => __( 'Show', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'button_connector_type',
            [
                'label' => __( 'Connector Type', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'text' => [
                        'title' => __( 'Text', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-text-width',
                    ],
                    'icon' => [
                        'title' => __( 'Icon', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-star',
                    ]
                ],
                'toggle' => false,
                'default' => 'text',
                'condition' => [
                    'button_connector_hide!' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'button_connector_text',
            [
                'label' => __( 'Text', 'happy-elementor-addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Or', 'happy-elementor-addons' ),
                'condition' => [
                    'button_connector_hide!' => 'yes',
                    'button_connector_type' => 'text',
                ]
            ]
        );

        $this->add_control(
            'button_connector_icon',
            [
                'label' => __( 'Icon', 'happy-elementor-addons' ),
                'type' => Controls_Manager::ICON,
                'options' => ha_get_happy_icons(),
                'condition' => [
                    'button_connector_hide!' => 'yes',
                    'button_connector_type' => 'icon',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_button_right',
            [
                'label' => __( 'Right', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'right_button_text',
            [
                'label' => __( 'Text', 'happy-elementor-addons' ),
                'label_block'=> true,
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Button Text', 'happy-elementor-addons' )
            ]
        );

        $this->add_control(
            'right_button_link',
            [
                'label' => __( 'Link', 'happy-elementor-addons' ),
                'type' => Controls_Manager::URL
            ]
        );

        $this->add_control(
            'right_button_icon',
            [
                'label' => __( 'Icon', 'happy-elementor-addons' ),
                'type' => Controls_Manager::ICON,
                'options' => ha_get_happy_icons(),
            ]
        );

        $this->add_control(
            'right_button_icon_position',
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
                    ]
                ],
                'toggle' => false,
                'default' => 'after',
                'condition' => [
                    'right_button_icon!' => ''
                ]
            ]
        );

        $this->add_control(
            'right_button_icon_spacing',
            [
                'label' => __( 'Icon Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'condition' => [
                    'right_button_icon!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn--right .ha-dual-btn-icon--before' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ha-dual-btn--right .ha-dual-btn-icon--after' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function register_style_controls() {
        $this->start_controls_section(
            '_section_style_common',
            [
                'label' => __( 'Common', 'happy-elementor-addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
		);

		$this->add_responsive_control(
            'button_padding',
            [
                'label' => __( 'Padding', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
		);

		$this->add_responsive_control(
            'button_gap',
            [
                'label' => __( 'Space Between Buttons', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn--left' => 'margin-right: calc({{SIZE}}{{UNIT}}/2);',
                    '{{WRAPPER}} .ha-dual-btn--right' => 'margin-left: calc({{SIZE}}{{UNIT}}/2);',
                ],
            ]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => __( 'Typography', 'happy-elementor-addons' ),
                'selector' => '{{WRAPPER}} .ha-dual-btn',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
            ]
		);

		$this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .ha-dual-btn'
            ]
		);

        $this->add_control(
            'button_align_x',
            [
                'label' => __( 'Horizontal Alignment', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'flex-start' => [
                        'title' => __( 'Left', 'happy-elementor-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'happy-elementor-addons' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => __( 'Right', 'happy-elementor-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_align_y',
            [
                'label' => __( 'Vertical Alignment', 'happy-elementor-addons' ),
                'description' => __( 'Only works when buttons have different height', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'flex-start' => [
                        'title' => __( 'Left', 'happy-elementor-addons' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'happy-elementor-addons' ),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => __( 'Right', 'happy-elementor-addons' ),
                        'icon' => 'eicon-v-align-bottom',
                    ]
                ],
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container' => 'align-items: {{VALUE}};',
                ],
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_style_left_button',
            [
                'label' => __( 'Left Button', 'happy-elementor-addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
		);

        $this->add_responsive_control(
            'left_button_padding',
            [
                'label' => __( 'Padding', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn--left' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'left_button_border',
                'selector' => '{{WRAPPER}} .ha-dual-btn--left'
            ]
		);

        $this->add_responsive_control(
            'left_button_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn--left' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
		);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'left_button_typography',
                'label' => __( 'Typography', 'happy-elementor-addons' ),
                'selector' => '{{WRAPPER}} .ha-dual-btn--left',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'left_button_box_shadow',
                'selector' => '{{WRAPPER}} .ha-dual-btn--left'
            ]
        );

		$this->start_controls_tabs( '_tabs_left_button' );

        $this->start_controls_tab(
            '_tab_left_button_normal',
            [
                'label' => __( 'Normal', 'happy-elementor-addons' ),
            ]
		);

		$this->add_control(
            'left_button_bg_color',
            [
                'label' => __( 'Background Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn--left' => 'background-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_control(
            'left_button_text_color',
            [
                'label' => __( 'Text Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn--left' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->end_controls_tab();

		$this->start_controls_tab(
            '_tabs_left_button_hover',
            [
                'label' => __( 'Hover', 'happy-elementor-addons' ),
            ]
		);

		$this->add_control(
            'left_button_hover_bg_color',
            [
                'label' => __( 'Background Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn--left:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_control(
            'left_button_hover_text_color',
            [
                'label' => __( 'Text Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn--left:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'left_button_hover_border_color',
            [
                'label' => __( 'Border Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn--left:hover' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'left_button_border_border!' => ''
                ]
            ]
        );

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_style_connector',
            [
                'label' => __( 'Connector', 'happy-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
            ]
		);

        $this->add_control(
            'connector_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __( 'Connector is hidden now, please enable connector from Content > Connector tab.', 'happy-elementor-addons' ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'button_connector_hide' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'connector_text_color',
            [
                'label' => __( 'Text Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn-connector' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->add_control(
            'connector_bg_color',
            [
                'label' => __( 'Background Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn-connector' => 'background-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'connector_typography',
                'label' => __( 'Typography', 'happy-elementor-addons' ),
                'selector' => '{{WRAPPER}} .ha-dual-btn-connector',
            ]
		);

		$this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'connector_box_shadow',
                'selector' => '{{WRAPPER}} .ha-dual-btn-connector'
            ]
		);

		$this->end_controls_section();

        $this->start_controls_section(
            '_section_style_right_button',
            [
                'label' => __( 'Right Button', 'happy-elementor-addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'right_button_padding',
            [
                'label' => __( 'Padding', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn--right' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'right_button_border',
                'selector' => '{{WRAPPER}} .ha-dual-btn--right'
            ]
        );

        $this->add_responsive_control(
            'right_button_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn--right' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'right_button_typography',
                'label' => __( 'Typography', 'happy-elementor-addons' ),
                'selector' => '{{WRAPPER}} .ha-dual-btn--right',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'right_button_box_shadow',
                'selector' => '{{WRAPPER}} .ha-dual-btn--right'
            ]
        );

        $this->start_controls_tabs( '_tabs_right_button' );

        $this->start_controls_tab(
            '_tab_right_button_normal',
            [
                'label' => __( 'Normal', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'right_button_bg_color',
            [
                'label' => __( 'Background Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn--right' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'right_button_text_color',
            [
                'label' => __( 'Text Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn--right' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tabs_right_button_hover',
            [
                'label' => __( 'Hover', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'right_button_hover_bg_color',
            [
                'label' => __( 'Background Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn--right:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'right_button_hover_text_color',
            [
                'label' => __( 'Text Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn--right:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'right_button_hover_border_color',
            [
                'label' => __( 'Border Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn--right:hover' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'right_button_border_border!' => ''
                ]
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Left button
        $this->add_render_attribute( 'left_button', 'class', 'ha-dual-btn ha-dual-btn--left' );
        $this->add_render_attribute( 'left_button', 'href', esc_url( $settings['left_button_link']['url'] ) );
        if ( ! empty( $settings['left_button_link']['is_external'] ) ) {
            $this->add_render_attribute( 'left_button', 'target', '_blank' );
        }
        if ( ! empty( $settings['left_button_link']['nofollow'] ) ) {
            $this->add_render_attribute( 'left_button', 'rel', 'nofollow' );
        }
        $this->add_inline_editing_attributes( 'left_button_text', 'none' );

        if ( $settings['left_button_icon'] ) {
            $this->add_render_attribute( 'left_button_icon', 'class', [
                'ha-dual-btn-icon',
                'ha-dual-btn-icon--' . esc_attr( $settings['left_button_icon_position'] ),
                esc_attr( $settings['left_button_icon'] )
            ] );
        }

        // Button connector
        $this->add_render_attribute( 'button_connector_text', 'class', 'ha-dual-btn-connector' );
        if ( $settings['button_connector_type'] === 'icon' ) {
            $this->add_render_attribute( 'button_connector_text', 'class', 'ha-dual-btn-connector--icon' );
            $connector = sprintf( '<i class="%s"></i>', esc_attr( $settings['button_connector_icon'] ) );
        } else {
            $this->add_render_attribute( 'button_connector_text', 'class', 'ha-dual-btn-connector--text' );
            $this->add_inline_editing_attributes( 'button_connector_text', 'none' );
            $connector = esc_html( $settings['button_connector_text'] );
        }

        // Right button
        $this->add_render_attribute( 'right_button', 'class', 'ha-dual-btn ha-dual-btn--right' );
        $this->add_render_attribute( 'right_button', 'href', esc_url( $settings['right_button_link']['url'] ) );
        if ( ! empty( $settings['right_button_link']['is_external'] ) ) {
            $this->add_render_attribute( 'right_button', 'target', '_blank' );
        }
        if ( ! empty( $settings['right_button_link']['nofollow'] ) ) {
            $this->add_render_attribute( 'right_button', 'rel', 'nofollow' );
        }
        $this->add_inline_editing_attributes( 'right_button_text', 'none' );

        if ( $settings['right_button_icon'] ) {
            $this->add_render_attribute( 'right_button_icon', 'class', [
                'ha-dual-btn-icon',
                'ha-dual-btn-icon--' . esc_attr( $settings['right_button_icon_position'] ),
                esc_attr( $settings['right_button_icon'] )
            ] );
        }
        ?>
        <div class="ha-dual-btn-wrapper">
            <a <?php echo $this->get_render_attribute_string( 'left_button' ); ?>>
                <?php if ( $settings['left_button_icon_position'] === 'before' ) : ?>
                    <i <?php echo $this->get_render_attribute_string( 'left_button_icon' ); ?>></i>
                <?php endif; ?>
                <span <?php echo $this->get_render_attribute_string( 'left_button_text' ); ?>><?php echo esc_html( $settings['left_button_text'] ); ?></span>
                <?php if ( $settings['left_button_icon_position'] === 'after' ) : ?>
                    <i <?php echo $this->get_render_attribute_string( 'left_button_icon' ); ?>></i>
                <?php endif; ?>
            </a>
            <?php if ( $settings['button_connector_hide'] !== 'yes' ) : ?>
                <span <?php echo $this->get_render_attribute_string( 'button_connector_text' ); ?>><?php echo $connector; ?></span>
            <?php endif; ?>
        </div>
        <div class="ha-dual-btn-wrapper">
            <a <?php echo $this->get_render_attribute_string( 'right_button' ); ?>>
                <?php if ( $settings['right_button_icon_position'] === 'before' ) : ?>
                    <i <?php echo $this->get_render_attribute_string( 'right_button_icon' ); ?>></i>
                <?php endif; ?>
                <span <?php echo $this->get_render_attribute_string( 'right_button_text' ); ?>><?php echo esc_html( $settings['right_button_text'] ); ?></span>
                <?php if ( $settings['right_button_icon_position'] === 'after' ) : ?>
                    <i <?php echo $this->get_render_attribute_string( 'right_button_icon' ); ?>></i>
                <?php endif; ?>
            </a>
        </div>
        <?php
    }

    /*
    public function _content_template() {
        ?>
        <#
        view.addInlineEditingAttributes( 'left_button_text', 'none' );
        view.addInlineEditingAttributes( 'right_button_text', 'none' );

        view.addInlineEditingAttributes( 'button_direction_text', 'none' );
        view.addRenderAttribute( 'button_direction_text', 'class', 'ha-middle-text' );
        #>
        <div class="ha-dual-btn">

            <div class="ha-dual-btn-link">
                <a href="{{{ settings.left_button_link.url }}}" class="ha-dual-btn-link-primary">

                    <i class="{{{ settings.left_icon_picker }}}"></i>
                    <span {{{ view.getRenderAttributeString( 'left_button_text' ) }}}>
                        {{{ settings.left_button_text }}}
                    </span>

                </a>
                <# if ( settings.button_direction_show === 'yes' ) { #>
                    <div {{{ view.getRenderAttributeString( 'button_direction_text' ) }}}>
                        {{{ settings.button_direction_text }}}
                    </div>
                <# } #>
            </div>

            <div class="ha-dual-btn-link">
                <a href="{{{ settings.right_button_link.url }}}" class="ha-dual-btn-link-secondary">

                    <i class="{{{ settings.right_icon_picker }}}"></i>
                    <span {{{ view.getRenderAttributeString( 'right_button_text' ) }}}>
                        {{{ settings.right_button_text }}}
                    </span>

                </a>
            </div>

        </div>

    <?php
    }
    */

}
