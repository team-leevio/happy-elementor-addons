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
        return __( 'Dual Button', 'happy_addons' );
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
        return [ 'button', 'btn', 'dual', 'advance', 'link' ];
    }

    protected function register_content_controls() {
        $this->start_controls_section(
            '_section_button',
            [
                'label' => __( 'Dual Buttons', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->start_controls_tabs( '_tabs_buttons' );

        $this->start_controls_tab(
            '_tab_button_left',
            [
                'label' => __( 'Left', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'left_button_text',
            [
                'label' => __( 'Text', 'happy_addons' ),
                'label_block'=> true,
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Button Text', 'happy_addons' )
            ]
        );

        $this->add_control(
            'left_button_link',
            [
                'label' => __( 'Link', 'happy_addons' ),
                'type' => Controls_Manager::URL,
            ]
        );

        $this->add_control(
            'left_button_icon',
            [
                'label' => __( 'Icon', 'happy_addons' ),
                'type' => Controls_Manager::ICON,
                'options' => ha_get_happy_icons(),
            ]
        );

        $this->add_control(
            'left_button_icon_position',
            [
                'label' => __( 'Icon Position', 'happy_addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'before' => [
                        'title' => __( 'Before', 'happy_addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'after' => [
                        'title' => __( 'After', 'happy_addons' ),
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
                'label' => __( 'Icon Spacing', 'happy_addons' ),
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
                'label' => __( 'Connector', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'button_connector_hide',
            [
                'label' => __( 'Hide Connector?', 'happy_addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Hide', 'happy_addons' ),
                'label_off' => __( 'Show', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'button_connector_type',
            [
                'label' => __( 'Connector Type', 'happy_addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'text' => [
                        'title' => __( 'Text', 'happy_addons' ),
                        'icon' => 'fa fa-text-width',
                    ],
                    'icon' => [
                        'title' => __( 'Icon', 'happy_addons' ),
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
                'label' => __( 'Text', 'happy_addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Or', 'happy_addons' ),
                'condition' => [
                    'button_connector_hide!' => 'yes',
                    'button_connector_type' => 'text',
                ]
            ]
        );

        $this->add_control(
            'button_connector_icon',
            [
                'label' => __( 'Icon', 'happy_addons' ),
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
                'label' => __( 'Right', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'right_button_text',
            [
                'label' => __( 'Text', 'happy_addons' ),
                'label_block'=> true,
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Button Text', 'happy_addons' )
            ]
        );

        $this->add_control(
            'right_button_link',
            [
                'label' => __( 'Link', 'happy_addons' ),
                'type' => Controls_Manager::URL
            ]
        );

        $this->add_control(
            'right_button_icon',
            [
                'label' => __( 'Icon', 'happy_addons' ),
                'type' => Controls_Manager::ICON,
                'options' => ha_get_happy_icons(),
            ]
        );

        $this->add_control(
            'right_button_icon_position',
            [
                'label' => __( 'Icon Position', 'happy_addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'before' => [
                        'title' => __( 'Before', 'happy_addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'after' => [
                        'title' => __( 'After', 'happy_addons' ),
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
                'label' => __( 'Icon Spacing', 'happy_addons' ),
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
//        $this->start_controls_section(
//            '_section_style_dual_button',
//            [
//                'label' => __( 'General Settings', 'happy_addons' ),
//                'tab'   => Controls_Manager::TAB_STYLE,
//            ]
//		);
//
//		$this->add_responsive_control(
//            'button_spacing',
//            [
//                'label' => __( 'Spacing Top/Bottom', 'happy_addons' ),
//                'type' => Controls_Manager::DIMENSIONS,
//				'size_units' => ['px'],
//                'allowed_dimensions' => 'vertical',
//                'default' => [
//                    'top' => 15,
//                    'bottom' => 15,
//                ],
//                'selectors' => [
//                    '{{WRAPPER}} .ha-dual-btn-link a' => 'padding-top: {{TOP}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}};',
//                ],
//            ]
//		);
//
//		$this->add_responsive_control(
//            'button_separator_margin',
//            [
//                'label' => __( 'Separator Margin', 'happy_addons' ),
//                'type' => Controls_Manager::SLIDER,
//                'size_units' => ['px'],
//                'selectors' => [
//                    '{{WRAPPER}} .ha-dual-btn-link:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
//                ],
//            ]
//		);
//
//		$this->add_group_control(
//            Group_Control_Typography::get_type(),
//            [
//                'name' => 'button_typography',
//                'label' => __( 'Typography', 'happy_addons' ),
//                'selector' => '{{WRAPPER}} .ha-dual-btn-link a',
//            ]
//		);
//
//		$this->add_group_control(
//            Group_Control_Box_Shadow::get_type(),
//            [
//                'name' => 'btn_box_shadow',
//                'exclude' => [
//                    'box_shadow_position',
//                ],
//                'selector' => '{{WRAPPER}} .ha-dual-btn-link a'
//            ]
//		);
//
//		$this->end_controls_section();
//
//		$this->start_controls_section(
//			'_section_left_button',
//            [
//                'label' => __( 'Left Button', 'happy_addons' ),
//                'tab'   => Controls_Manager::TAB_STYLE,
//            ]
//		);
//
//        $this->add_responsive_control(
//            'left_button_spacing',
//            [
//                'label' => __( 'Spacing Left/Right', 'happy_addons' ),
//                'type' => Controls_Manager::DIMENSIONS,
//                'size_units' => ['px'],
//                'allowed_dimensions' => 'horizontal',
//                'default' => [
//                    'left' => 15,
//                    'right' => 30,
//                ],
//                'selectors' => [
//                    '{{WRAPPER}} .ha-dual-btn-link a.ha-dual-btn-link-primary' => 'padding-left: {{LEFT}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}};',
//                ],
//            ]
//        );
//
//        $this->add_responsive_control(
//            'left_button_icon_spacing',
//            [
//                'label' => __( 'Icon Spacing', 'happy_addons' ),
//                'type' => Controls_Manager::SLIDER,
//                'size_units' => ['px'],
//                'default' => [
//                    'unit' => 'px',
//                    'size' => 8,
//                ],
//                'selectors' => [
//                    '{{WRAPPER}} .ha-dual-btn-link:first-child a i' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
//                ],
//            ]
//        );
//
//		$this->add_group_control(
//            Group_Control_Border::get_type(),
//            [
//                'name' => 'button_border',
//                'selector' => '{{WRAPPER}} .ha-dual-btn-link:first-child a'
//            ]
//		);
//
//        $this->add_responsive_control(
//            'left_btn_border_radius',
//            [
//                'label' => __( 'Left Button Border Radius', 'happy_addons' ),
//                'type' => Controls_Manager::DIMENSIONS,
//                'size_units' => [ 'px', '%' ],
//                'selectors' => [
//                    '{{WRAPPER}} .ha-dual-btn-link:first-child a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
//                ],
//            ]
//		);
//
//		$this->start_controls_tabs( '_left_button_tabs' );
//
//        $this->start_controls_tab(
//            '_left_button_tabs_normal',
//            [
//                'label' => __( 'Normal', 'happy_addons' ),
//            ]
//		);
//
//		$this->add_control(
//            'left_button_background_color',
//            [
//                'label' => __( 'Background Color', 'happy_addons' ),
//                'type' => Controls_Manager::COLOR,
//                'selectors' => [
//                    '{{WRAPPER}} .ha-dual-btn-link .ha-dual-btn-link-primary' => 'background: {{VALUE}}',
//                ],
//            ]
//        );
//
//		$this->add_control(
//            'left_button_text_color',
//            [
//                'label' => __( 'Text Color', 'happy_addons' ),
//                'type' => Controls_Manager::COLOR,
//                'selectors' => [
//                    '{{WRAPPER}} .ha-dual-btn-link .ha-dual-btn-link-primary' => 'color: {{VALUE}}',
//                ],
//            ]
//        );
//
//		$this->end_controls_tab();
//
//		$this->start_controls_tab(
//            '_left_button_tabs_hover',
//            [
//                'label' => __( 'Hover', 'happy_addons' ),
//            ]
//		);
//
//		$this->add_control(
//            'left_button_hover_bg_color',
//            [
//                'label' => __( 'Background Color', 'happy_addons' ),
//                'type' => Controls_Manager::COLOR,
//                'selectors' => [
//                    '{{WRAPPER}} .ha-dual-btn-link .ha-dual-btn-link-primary:hover' => 'background: {{VALUE}}',
//                ],
//            ]
//        );
//
//		$this->add_control(
//            'left_button_hover_text_color',
//            [
//                'label' => __( 'Text Color', 'happy_addons' ),
//                'type' => Controls_Manager::COLOR,
//                'selectors' => [
//                    '{{WRAPPER}} .ha-dual-btn-link .ha-dual-btn-link-primary:hover' => 'color: {{VALUE}}',
//                ],
//            ]
//        );
//
//        $this->add_control(
//            'left_button_hover_border_color',
//            [
//                'label' => __( 'Border Color', 'happy_addons' ),
//                'type' => Controls_Manager::COLOR,
//                'selectors' => [
//                    '{{WRAPPER}} .ha-dual-btn-link .ha-dual-btn-link-primary:hover' => 'border-color: {{VALUE}}',
//                ],
//            ]
//        );
//
//		$this->end_controls_tab();
//		$this->end_controls_tabs();
//
//		$this->end_controls_section();
//
//
//		$this->start_controls_section(
//			'_section_button_direction',
//            [
//                'label' => __( 'Button Direction', 'happy_addons' ),
//				'tab'   => Controls_Manager::TAB_STYLE,
//				'condition' => ['button_direction_show'=>'yes']
//            ]
//		);
//
//		$this->add_control(
//            'button_direction_background_color',
//            [
//                'label' => __( 'Background Color', 'happy_addons' ),
//                'type' => Controls_Manager::COLOR,
//                'selectors' => [
//                    '{{WRAPPER}} .ha-middle-text' => 'background: {{VALUE}}',
//                ],
//            ]
//        );
//
//		$this->add_control(
//            'button_direction_text_color',
//            [
//                'label' => __( 'Text Color', 'happy_addons' ),
//                'type' => Controls_Manager::COLOR,
//                'selectors' => [
//                    '{{WRAPPER}} .ha-middle-text' => 'color: {{VALUE}}',
//                ],
//            ]
//		);
//
//		$this->add_group_control(
//            Group_Control_Typography::get_type(),
//            [
//                'name' => 'button_direction_typography',
//                'label' => __( 'Typography', 'happy_addons' ),
//                'selector' => '{{WRAPPER}} .ha-middle-text',
//            ]
//		);
//
//		$this->add_responsive_control(
//            'direction_spacing',
//            [
//                'label' => __( 'Spacing', 'happy_addons' ),
//                'type' => Controls_Manager::SLIDER,
//				'size_units' => ['px'],
//                'selectors' => [
//                    '{{WRAPPER}} .ha-middle-text' => 'padding: {{SIZE}}{{UNIT}};',
//                ],
//            ]
//		);
//
//		$this->add_group_control(
//            Group_Control_Box_Shadow::get_type(),
//            [
//                'name' => 'btn_direction_box_shadow',
//                'exclude' => [
//                    'box_shadow_position',
//                ],
//                'selector' => '{{WRAPPER}} .ha-middle-text'
//            ]
//		);
//
//		$this->end_controls_section();
//
//
//		$this->start_controls_section(
//			'_section_right_button',
//            [
//                'label' => __( 'Right Button', 'happy_addons' ),
//                'tab'   => Controls_Manager::TAB_STYLE,
//            ]
//		);
//
//        $this->add_responsive_control(
//            'right_button_spacing',
//            [
//                'label' => __( 'Spacing Left/Right', 'happy_addons' ),
//                'type' => Controls_Manager::DIMENSIONS,
//                'size_units' => ['px'],
//                'allowed_dimensions' => 'horizontal',
//                'default' => [
//                    'left' => 30,
//                    'right' => 15,
//                ],
//                'selectors' => [
//                    '{{WRAPPER}} .ha-dual-btn-link a.ha-dual-btn-link-secondary' => 'padding-left: {{LEFT}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}};',
//                ],
//            ]
//        );
//
//        $this->add_responsive_control(
//            'right_button_icon_spacing',
//            [
//                'label' => __( 'Icon Spacing', 'happy_addons' ),
//                'type' => Controls_Manager::SLIDER,
//                'size_units' => ['px'],
//                'default' => [
//                    'unit' => 'px',
//                    'size' => 8,
//                ],
//                'selectors' => [
//                    '{{WRAPPER}} .ha-dual-btn-link:last-child a i' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
//                ],
//            ]
//        );
//
//		$this->add_group_control(
//            Group_Control_Border::get_type(),
//            [
//                'name' => 'right_button_border',
//                'selector' => '{{WRAPPER}} .ha-dual-btn-link:last-child a'
//            ]
//		);
//
//		$this->add_control(
//            'right_btn_border_radius',
//            [
//                'label' => __( 'Border Radius', 'happy_addons' ),
//                'type' => Controls_Manager::DIMENSIONS,
//                'size_units' => [ 'px', '%' ],
//                'selectors' => [
//                    '{{WRAPPER}} .ha-dual-btn-link:last-child a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
//                ],
//            ]
//		);
//
//		$this->start_controls_tabs( '_right_button_tabs' );
//
//        $this->start_controls_tab(
//            '_right_button_tabs_normal',
//            [
//                'label' => __( 'Normal', 'happy_addons' ),
//            ]
//		);
//
//		$this->add_control(
//            'right_button_background_color',
//            [
//                'label' => __( 'Background Color', 'happy_addons' ),
//                'type' => Controls_Manager::COLOR,
//                'selectors' => [
//                    '{{WRAPPER}} .ha-dual-btn-link .ha-dual-btn-link-secondary' => 'background: {{VALUE}}',
//                ],
//            ]
//        );
//
//		$this->add_control(
//            'right_button_text_color',
//            [
//                'label' => __( 'Text Color', 'happy_addons' ),
//                'type' => Controls_Manager::COLOR,
//                'selectors' => [
//                    '{{WRAPPER}} .ha-dual-btn-link .ha-dual-btn-link-secondary' => 'color: {{VALUE}}',
//                ],
//            ]
//        );
//
//		$this->end_controls_tab();
//
//		$this->start_controls_tab(
//            '_right_button_tabs_hover',
//            [
//                'label' => __( 'Hover', 'happy_addons' ),
//            ]
//		);
//
//		$this->add_control(
//            'right_button_hover_bg_color',
//            [
//                'label' => __( 'Background Color', 'happy_addons' ),
//                'type' => Controls_Manager::COLOR,
//                'selectors' => [
//                    '{{WRAPPER}} .ha-dual-btn-link .ha-dual-btn-link-secondary:hover' => 'background: {{VALUE}}',
//                ],
//            ]
//        );
//
//		$this->add_control(
//            'right_button_hover_text_color',
//            [
//                'label' => __( 'Text Color', 'happy_addons' ),
//                'type' => Controls_Manager::COLOR,
//                'selectors' => [
//                    '{{WRAPPER}} .ha-dual-btn-link .ha-dual-btn-link-secondary:hover' => 'color: {{VALUE}}',
//                ],
//            ]
//        );
//
//        $this->add_control(
//            'right_button_hover_border_color',
//            [
//                'label' => __( 'Border Color', 'happy_addons' ),
//                'type' => Controls_Manager::COLOR,
//                'selectors' => [
//                    '{{WRAPPER}} .ha-dual-btn-link .ha-dual-btn-link-secondary:hover' => 'border-color: {{VALUE}}',
//                ],
//            ]
//        );
//
//		$this->end_controls_tab();
//		$this->end_controls_tabs();
//
//		$this->end_controls_section();

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
        $this->add_render_attribute( 'button_connector', 'class', 'ha-dual-btn-connector' );
        if ( $settings['button_connector_type'] === 'icon' ) {
            $this->add_render_attribute( 'button_connector', 'class', 'ha-dual-btn-connector--icon' );
            $connector = sprintf( '<i class="%s"></i>', esc_attr( $settings['button_connector_icon'] ) );
        } else {
            $this->add_render_attribute( 'button_connector', 'class', 'ha-dual-btn-connector--text' );
            $this->add_inline_editing_attributes( 'button_connector', 'none' );
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
                <span <?php echo $this->get_render_attribute_string( 'button_connector' ); ?>><?php echo $connector; ?></span>
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
