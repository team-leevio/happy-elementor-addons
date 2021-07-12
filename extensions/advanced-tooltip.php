<?php

namespace Happy_Addons\Elementor\Extension;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use \Elementor\Core\Schemes\Typography;

defined('ABSPATH') || die();

class Advanced_Tooltip {

    public static function init() {
        add_action('elementor/element/common/_section_style/after_section_end', [__CLASS__, 'add_controls_section'], 1);
        wp_enqueue_style('tipso');
        wp_enqueue_script('jquery-tipso');
    }

    public static function add_controls_section($element) {

        $element->start_controls_section(
            '_section_ha_advanced_tooltip',
            [
                'label' => __('Advanced Tooltip', 'happy-elementor-addons') . ha_get_section_icon(),
                'tab'   => Controls_Manager::TAB_ADVANCED,
            ]
        );

        $element->add_control(
            'ha_advanced_tooltip_enable',
            [
                'label'       => __('Enable Advanced Tooltip?', 'happy-elementor-addons'),
                'type'        => Controls_Manager::SWITCHER,
                'label_on' => __('On', 'happy-elementor-addons'),
                'label_off' => __('Off', 'happy-elementor-addons'),
                'return_value' => 'enable',
                'prefix_class' => 'ha-advanced-tooltip-',
                'default' => '',
            ]
        );

        $element->start_controls_tabs('ha_a_tooltip_tabs');

        $element->start_controls_tab('ha_a_tooltip_settings', [
            'label' => __('Settings', 'happy-elementor-addons'),
            'condition' => [
                'ha_advanced_tooltip_enable!' => '',
            ],
        ]);

        $element->add_control(
            'ha_a_tooltip_section_content',
            [
                'label' => __('Content', 'happy-elementor-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => __('I am a tooltip', 'happy-elementor-addons'),
                'dynamic' => ['active' => true],
                'frontend_available' => true,
                'condition' => [
                    'ha_advanced_tooltip_enable!' => '',
                ],
            ]
        );

        $element->add_control(
            'ha_a_tooltip_section_position',
            [
                'label' => __('Position', 'happy-elementor-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'top' => __('Top', 'happy-elementor-addons'),
                    'bottom' => __('Bottom', 'happy-elementor-addons'),
                    'left' => __('Left', 'happy-elementor-addons'),
                    'right' => __('Right', 'happy-elementor-addons'),
                ],
                'frontend_available' => true,
                'condition' => [
                    'ha_advanced_tooltip_enable!' => '',
                ],
            ]
        );

        // $element->add_control(
        //     'ha_a_tooltip_section_animation',
        //     [
        //         'label' => __('Animation', 'happy-elementor-addons'),
        //         'type' => Controls_Manager::SELECT,
        //         'default' => 'scale',
        //         'options' => [
        //             'shift-away' => __('Shift Away', 'happy-elementor-addons'),
        //             'shift-toward' => __('Shift Toward', 'happy-elementor-addons'),
        //             'scale' => __('Scale', 'happy-elementor-addons'),
        //             'fade' => __('Fade', 'happy-elementor-addons'),
        //             'perspective' => __('Perspective', 'happy-elementor-addons'),
        //         ],
        //         'frontend_available' => true,
        //         'condition' => [
        //             'ha_advanced_tooltip_enable!' => '',
        //         ],
        //     ]
        // );

        $element->add_control(
            'ha_a_tooltip_section_arrow',
            [
                'label' => __('Arrow', 'happy-elementor-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'happy-elementor-addons'),
                'label_off' => __('Hide', 'happy-elementor-addons'),
                'return_value' => true,
                'default' => true,
                'frontend_available' => true,
                'condition' => [
                    'ha_advanced_tooltip_enable!' => '',
                ],
            ]
        );

        // $element->add_control(
        //     'ha_a_tooltip_section_arrow_type',
        //     [
        //         'label' => __('Arrow Type', 'happy-elementor-addons'),
        //         'type' => Controls_Manager::SELECT,
        //         'default' => 'sharp',
        //         'options' => [
        //             'sharp' => __('Sharp', 'happy-elementor-addons'),
        //             'round' => __('Round', 'happy-elementor-addons'),
        //         ],
        //         'frontend_available' => true,
        //         'condition' => [
        //             'ha_advanced_tooltip_enable!' => '',
        //             'ha_a_tooltip_section_arrow!' => '',
        //         ],
        //     ]
        // );

        // $element->add_control(
        //     'ha_a_tooltip_section_trigger',
        //     [
        //         'label' => __('Trigger', 'happy-elementor-addons'),
        //         'type' => Controls_Manager::SELECT,
        //         'default' => 'mouseenter',
        //         'options' => [
        //             'click' => __('Click', 'happy-elementor-addons'),
        //             'mouseenter' => __('Hover', 'happy-elementor-addons'),
        //         ],
        //         'frontend_available' => true,
        //         'condition' => [
        //             'ha_advanced_tooltip_enable!' => '',
        //         ],
        //     ]
        // );

        $element->add_control(
            'ha_a_tooltip_section_duration',
            [
                'label' => __('Duration', 'happy-elementor-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 1000,
                'step' => 10,
                'default' => 300,
                'frontend_available' => true,
                'condition' => [
                    'ha_advanced_tooltip_enable!' => '',
                ],
            ]
        );

        $element->add_control(
            'ha_a_tooltip_section_delay',
            [
                'label' => __('Delay out (s)', 'happy-elementor-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 1000,
                'step' => 5,
                'default' => 100,
                'frontend_available' => true,
                'condition' => [
                    'ha_advanced_tooltip_enable!' => '',
                ],
            ]
        );

        $element->add_control(
            'ha_a_tooltip_section_size',
            [
                'label' => __('Size', 'happy-elementor-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'tiny' => __('Tiny', 'happy-elementor-addons'),
                    'small' => __('Small', 'happy-elementor-addons'),
                    'default' => __('Default', 'happy-elementor-addons'),
                    'large' => __('Large', 'happy-elementor-addons'),
                ],
                'frontend_available' => true,
                'condition' => [
                    'ha_advanced_tooltip_enable!' => '',
                ],
            ]
        );

        $element->end_controls_tab();

        $element->start_controls_tab('ha_a_tooltip_section_styles', [
            'label' => __('Styles', 'happy-elementor-addons'),
            'condition' => [
                'ha_advanced_tooltip_enable!' => '',
            ],
        ]);

        // $element->add_group_control(
        //     Group_Control_Typography::get_type(),
        //     [
        //         'name' => 'ha_a_tooltip_section_typography',
        //         'selector' => '.tipso_bubble.ha-tooltip-wrapper-{{ID}} .ha-tooltip-content-{{ID}}',
        //         'scheme' => Typography::TYPOGRAPHY_3,
        //         'separator' => 'after',
        //         'condition' => [
        //             'ha_advanced_tooltip_enable!' => '',
        //         ],
        //     ]
        // );
        
        $element->add_control(
            'ha_a_tooltip_section_background_color',
            [
                'label' => __('Background Color', 'happy-elementor-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                // 'frontend_available' => true,
                'selectors' => [
                    '.tipso_bubble.ha-tooltip-wrapper-{{ID}}' => 'background: {{VALUE}} !important;',
                    '.tipso_bubble.ha-tooltip-wrapper-{{ID}} .tipso_arrow' => 'border-left-color: {{VALUE}} !important;',
                ],
                'condition' => [
                    'ha_advanced_tooltip_enable!' => '',
                ],
            ]
        );

        $element->add_control(
            'ha_a_tooltip_section_color',
            [
                'label' => __('Color', 'happy-elementor-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                // 'frontend_available' => true,
                'selectors' => [
                    '.tipso_bubble.ha-tooltip-wrapper-{{ID}}' => 'color: {{VALUE}} !important;',
                ],
                'condition' => [
                    'ha_advanced_tooltip_enable!' => '',
                ],
            ]
        );

        $element->add_control(
            'ha_a_tooltip_section_border_color',
            [
                'label' => __('Border Color', 'happy-elementor-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '.tipso_bubble.ha-tooltip-wrapper-{{ID}}' => 'border: 1px solid {{VALUE}};',
                ],
                'condition' => [
                    'ha_advanced_tooltip_enable!' => '',
                    'ha_a_tooltip_section_arrow' => '',
                ],
            ]
        );

        $element->add_control(
            'ha_a_tooltip_section_border_radius',
            [
                'label' => __('Border Radius', 'happy-elementor-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '.tipso_bubble.ha-tooltip-wrapper-{{ID}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'ha_advanced_tooltip_enable!' => '',
                ],
            ]
        );

        // $element->add_control(
        //     'ha_a_tooltip_section_distance',
        //     [
        //         'label' => __('Distance', 'happy-elementor-addons'),
        //         'type' => Controls_Manager::NUMBER,
        //         'min' => 05,
        //         'max' => 50,
        //         'step' => 2,
        //         'default' => 10,
        //         'label_block' => false,
        //         'condition' => [
        //             'ha_advanced_tooltip_enable!' => '',
        //         ],
        //     ]
        // );

        $element->add_control(
            'ha_a_tooltip_section_padding',
            [
                'label' => __('Padding', 'happy-elementor-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '.tipso_bubble.ha-tooltip-wrapper-{{ID}} .ha-tooltip-content-{{ID}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'ha_advanced_tooltip_enable!' => '',
                ],
            ]
        );

        $element->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'ha_a_tooltip_section_box_shadow',
                'selector' => '.tipso_bubble.ha-tooltip-wrapper-{{ID}}',
                'separator' => '',
                'condition' => [
                    'ha_advanced_tooltip_enable!' => '',
                ],
            ]
        );

        $element->add_control(
            'ha_a_tooltip_section_width',
            [
                'label' => __('Max Width', 'happy-elementor-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => '350',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                ],
                'label_block' => false,
                'selectors' => [
                    '.tipso_bubble.ha-tooltip-wrapper-{{ID}}' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'ha_advanced_tooltip_enable!' => '',
                ],
            ]
        );

        $element->end_controls_tab();

        $element->end_controls_tabs();

        $element->end_controls_section();
    }
}

Advanced_Tooltip::init();
