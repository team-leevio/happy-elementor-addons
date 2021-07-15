<?php

namespace Happy_Addons\Elementor\Extension;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use \Elementor\Core\Schemes\Typography;

defined('ABSPATH') || die();

class Advanced_Tooltip {

    public static function init() {
        add_action('elementor/element/common/_section_style/after_section_end', [__CLASS__, 'add_controls_section'], 1);
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

        $element->start_controls_tabs('ha_tooltip_tabs');

        $element->start_controls_tab('ha_tooltip_settings', [
            'label' => __('Settings', 'happy-elementor-addons'),
            'condition' => [
                'ha_advanced_tooltip_enable!' => '',
            ],
        ]);

        $element->add_control(
            'ha_advanced_tooltip_content',
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
            'ha_advanced_tooltip_position',
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
                'prefix_class' => 'ha-advanced-tooltip-',
                'condition' => [
                    'ha_advanced_tooltip_enable!' => '',
                ],
            ]
        );

        // $element->add_control(
        //     'ha_advanced_tooltip_animation',
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
            'ha_advanced_tooltip_arrow',
            [
                'label' => __('Arrow', 'happy-elementor-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'happy-elementor-addons'),
                'label_off' => __('Hide', 'happy-elementor-addons'),
                'return_value' => 'true',
                'default' => 'true',
                'frontend_available' => true,
                'condition' => [
                    'ha_advanced_tooltip_enable!' => '',
                ],
            ]
        );

        $element->add_control(
            'ha_advanced_tooltip_trigger',
            [
                'label' => __('Trigger', 'happy-elementor-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'hover',
                'options' => [
                    'click' => __('Click', 'happy-elementor-addons'),
                    'hover' => __('Hover', 'happy-elementor-addons'),
                ],
                'frontend_available' => true,
                'condition' => [
                    'ha_advanced_tooltip_enable!' => '',
                ],
            ]
        );

        $element->add_control(
            'ha_advanced_tooltip_duration',
            [
                'label' => __('Duration (ms)', 'happy-elementor-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 5000,
                'step' => 50,
                'default' => 500,
                'frontend_available' => true,
                'condition' => [
                    'ha_advanced_tooltip_enable!' => '',
                ],
            ]
        );

        $element->add_control(
            'ha_advanced_tooltip_delay',
            [
                'label' => __('Delay (ms)', 'happy-elementor-addons'),
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

        $element->add_responsive_control(
            'ha_advanced_tooltip_distance',
            [
                'label' => __('Distance', 'happy-elementor-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => '0',
                ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.ha-advanced-tooltip-enable.ha-advanced-tooltip-top .ha-advanced-tooltip-content' => 'bottom: calc(101% + {{SIZE}}{{UNIT}});',
                    '{{WRAPPER}}.ha-advanced-tooltip-enable.ha-advanced-tooltip-bottom .ha-advanced-tooltip-content' => 'top: calc(101% + {{SIZE}}{{UNIT}});',
                    '{{WRAPPER}}.ha-advanced-tooltip-enable.ha-advanced-tooltip-left .ha-advanced-tooltip-content' => 'right: calc(101% + {{SIZE}}{{UNIT}});',
                    '{{WRAPPER}}.ha-advanced-tooltip-enable.ha-advanced-tooltip-right .ha-advanced-tooltip-content' => 'left: calc(101% + {{SIZE}}{{UNIT}});',
                ],
                'condition' => [
                    'ha_advanced_tooltip_enable!' => '',
                ],
            ]
        );

        $element->end_controls_tab();

        $element->start_controls_tab('ha_advanced_tooltip_styles', [
            'label' => __('Styles', 'happy-elementor-addons'),
            'condition' => [
                'ha_advanced_tooltip_enable!' => '',
            ],
        ]);

        $element->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ha_advanced_tooltip_typography',
                'separator' => 'after',
                'fields_options' => [
                    'typography' => [
                        'default' => 'yes'
                    ],
                    'font_family' => [
                        'default' => 'Nunito',
                    ],
                    'font_weight' => [
                        'default' => '500', // 100, 200, 300, 400, 500, 600, 700, 800, 900, normal, bold
                    ],
                    'font_size' => [
                        'default' => [
                            'unit' => 'px', // px, em, rem, vh
                            'size' => '14', // any number
                        ],
                    ],
                ],
                'selector' => '{{WRAPPER}} .ha-advanced-tooltip-content',
                'condition' => [
                    'ha_advanced_tooltip_enable!' => '',
                ],
            ]
        );

        $element->add_control(
            'ha_advanced_tooltip_background_color',
            [
                'label' => __('Background Color', 'happy-elementor-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .ha-advanced-tooltip-content' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .ha-advanced-tooltip-content::after' => '--ha-tooltip-arrow-color: {{VALUE}}',
                ],
                'condition' => [
                    'ha_advanced_tooltip_enable!' => '',
                ],
            ]
        );

        $element->add_control(
            'ha_advanced_tooltip_color',
            [
                'label' => __('Color', 'happy-elementor-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ha-advanced-tooltip-content' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'ha_advanced_tooltip_enable!' => '',
                ],
            ]
        );

        // $element->add_group_control(
        // 	Group_Control_Border::get_type(),
        // 	[
        // 		'name' => 'border',
        // 		'label' => __( 'Border', 'happy-elementor-addons' ),
        // 		'selector' => '{{WRAPPER}} .ha-advanced-tooltip-content',
        // 	]
        // );

        $element->add_control(
            'ha_advanced_tooltip_border_radius',
            [
                'label' => __('Border Radius', 'happy-elementor-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ha-advanced-tooltip-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'ha_advanced_tooltip_enable!' => '',
                ],
            ]
        );

        $element->add_control(
            'ha_advanced_tooltip_padding',
            [
                'label' => __('Padding', 'happy-elementor-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ha-advanced-tooltip-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'ha_advanced_tooltip_enable!' => '',
                ],
            ]
        );

        $element->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'ha_advanced_tooltip_box_shadow',
                'selector' => '{{WRAPPER}} .ha-advanced-tooltip-content',
                'separator' => '',
                'condition' => [
                    'ha_advanced_tooltip_enable!' => '',
                ],
            ]
        );

        $element->add_control(
            'ha_advanced_tooltip_width',
            [
                'label' => __('Width', 'happy-elementor-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => '120',
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-advanced-tooltip-content' => 'width: {{SIZE}}{{UNIT}};',
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
