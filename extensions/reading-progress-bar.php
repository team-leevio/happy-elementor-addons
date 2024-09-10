<?php

namespace Happy_Addons\Elementor\Extension;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Plugin;

defined('ABSPATH') || die();

class Reading_Progress_Bar {

	private static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init() {
        if ($this->prevent_reading_progress_bar_rendering(get_the_ID())) {
            return;
        }

		add_action( 'elementor/documents/register_controls', [$this, 'reading_progress_bar_controls'], 10 );
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_scripts']);
        if ( !ha_elementor()->preview->is_preview_mode() ) {
            add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts_frontend']);
        }
		add_action( 'wp_footer', [$this, 'render_reading_progress_bar_html'] );
	}

    public function enqueue_scripts () {
        $suffix = ha_is_script_debug_enabled() ? '.' : '.min.';
        $extension_js = HAPPY_ADDONS_DIR_PATH . 'assets/js/extension-reading-progress-bar' . $suffix . 'js';

        if (file_exists($extension_js)) {
            wp_add_inline_script(
                'elementor-frontend',
                file_get_contents($extension_js)
            );
        }  
    }

    public function enqueue_scripts_frontend () {
        $suffix = ha_is_script_debug_enabled() ? '.' : '.min.';
        $extension_js = HAPPY_ADDONS_ASSETS . 'js/extension-reading-progress-bar' . $suffix . 'js';
        
        wp_enqueue_script(
            'happy-reading-progress-bar',
            $extension_js,
            ['jquery'],
            HAPPY_ADDONS_VERSION,
            true
        ); 
    }

	public function reading_progress_bar_controls( $element ) {

		$element->start_controls_section(
			'ha_reading_progress_bar_section',
			[
				'label' => __( 'Reading Progress Bar', 'happy-elementor-addons' ) . ha_get_section_icon(),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);

		$element->add_control(
			'ha_reading_progress_bar_enable',
			[
				'label'        => __( 'Enable ?', 'happy-elementor-addons' ),
				'description'  => __( 'Enable Progress Bar For This Page', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'happy-elementor-addons' ),
				'label_off'    => __( 'No', 'happy-elementor-addons' ),
				'return_value' => 'yes',
                'frontend_available' => true,
                'render_type'  => 'ui',
			]
		);

        $element->add_control(
			'ha_reading_progress_bar_enable_globaly',
			[
				'label'        => __( 'Enable Gobally?', 'happy-elementor-addons' ),
                'description' => __('Enabling progress bar on entire site.', 'happy-elementor-addons'),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'happy-elementor-addons' ),
				'label_off'    => __( 'No', 'happy-elementor-addons' ),
				'return_value' => 'yes',
                'condition' => [
                    'ha_reading_progress_bar_enable' => 'yes',
                ],
			]
		);

        $element->add_control(
            'ha_reading_progress_bar_global_display_condition',
            [
                'label' => __('Display On', 'happy-elementor-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'all',
                'options' => [
                    'posts' => __('All Posts', 'happy-elementor-addons'),
                    'pages' => __('All Pages', 'happy-elementor-addons'),
                    'all' => __('All Posts & Pages', 'happy-elementor-addons'),
                ],
                'condition' => [
                    'ha_reading_progress_bar_enable' => 'yes',
                    'ha_reading_progress_bar_enable_globaly' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'ha_reading_progress_bar_type',
            [
                'label' => __('Type', 'happy-elementor-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal' => __('Horizontal', 'happy-elementor-addons'),
                    'vertical' => __('Vertical', 'happy-elementor-addons'),
                    'circle' => __('Circle', 'happy-elementor-addons'),
                ],
                'frontend_available' => true,
                'condition' => [
                    'ha_reading_progress_bar_enable' => 'yes',
                ],
            ]
        );      

        // Start circle
		$element->add_control(
            'ha_reading_progress_bar_circle_position',
            [
                'label' => __('Position', 'happy-elementor-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'top-right',
                'options' => [
                    'top-right' => __('Top Right', 'happy-elementor-addons'),
                    'top-left' => __('Top Left', 'happy-elementor-addons'),
                    'bottom-right' => __('Bottom Right', 'happy-elementor-addons'),
                    'bottom-left' => __('Bottom Left', 'happy-elementor-addons'),
                ],
                'frontend_available' => true,
				'selectors_dictionary' => [
					'top-right' => 'top: 20px; right:20px; bottom: unset; left:unset',
					'top-left' => 'top: 20px; right: unset; bottom: unset; left:20px;',
					'bottom-right' => 'top: unset; right: 20px; bottom: 20px; left:unset;',
					'bottom-left' => 'top: unset; right: unset; bottom: 20px; left:20px;',
				],
				'selectors' => [
					'{{WRAPPER}} .hm-crp-wrapper' => '{{VALUE}}',
				],
                'condition' => [
                    'ha_reading_progress_bar_enable' => 'yes',
                    'ha_reading_progress_bar_type' => 'circle',
                ],
            ]
        );

		$element->add_responsive_control(
            'ha_reading_progress_bar_circle_size',
            [
				'label' => __( 'Size', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
                'frontend_available' => true,
				'size_units' => [ 'px' ],
				'desktop_default' => [
					'unit' => 'px'
				],
				'tablet_default' => [
					'unit' => 'px',
					'size' => 60
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 60
				],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 150,
					],
				],
                'default' => [
					'unit' => 'px',
					'size' => 60,
				],
				'selectors' => [
                    '{{WRAPPER}} .hm-crp-wrapper, {{WRAPPER}} .hm-circular-progress' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                    'ha_reading_progress_bar_enable' => 'yes',
                    'ha_reading_progress_bar_type' => 'circle',
                ],
			]
        );

		$element->add_control(
			'ha_reading_progress_bar_circle_offset',
			[
				'label' => __( 'Offset', 'happy-elementor-addons' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
                'condition' => [
                    'ha_reading_progress_bar_enable' => 'yes',
                    'ha_reading_progress_bar_type' => 'circle',
                ],
			]
		);

        $element->start_popover();
		$element->add_responsive_control(
			'ha_reading_progress_bar_circle_offset_x_tr',
			[
				'label' => __( 'Horizontal Align', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
				],
                'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .hm-crp-wrapper' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ha_reading_progress_bar_circle_offset' => 'yes',
					'ha_reading_progress_bar_circle_position' => 'top-right',
				]
			]
		);

		$element->add_responsive_control(
			'ha_reading_progress_bar_circle_offset_y_tr',
			[
				'label' => __( 'Vertical Align', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1
					],
				],
                'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .hm-crp-wrapper' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ha_reading_progress_bar_circle_offset' => 'yes',
                    'ha_reading_progress_bar_circle_position' => 'top-right',
				]
			]
		); //end top-right

        $element->add_responsive_control(
			'ha_reading_progress_bar_circle_offset_x_tl',
			[
				'label' => __( 'Horizontal Align', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
				],
                'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .hm-crp-wrapper' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ha_reading_progress_bar_circle_offset' => 'yes',
					'ha_reading_progress_bar_circle_position' => 'top-left',
				]
			]
		);

		$element->add_responsive_control(
			'ha_reading_progress_bar_circle_offset_y_tl',
			[
				'label' => __( 'Vertical Align', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1
					],
				],
                'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .hm-crp-wrapper' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ha_reading_progress_bar_circle_offset' => 'yes',
                    'ha_reading_progress_bar_circle_position' => 'top-left',
				]
			]
		); // end top-left
        
        $element->add_responsive_control(
			'ha_reading_progress_bar_circle_offset_x_br',
			[
				'label' => __( 'Horizontal Align', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
				],
                'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .hm-crp-wrapper' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ha_reading_progress_bar_circle_offset' => 'yes',
					'ha_reading_progress_bar_circle_position' => 'bottom-right',
				]
			]
		);

		$element->add_responsive_control(
			'ha_reading_progress_bar_circle_offset_y_br',
			[
				'label' => __( 'Vertical Align', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1
					],
				],
                'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .hm-crp-wrapper' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ha_reading_progress_bar_circle_offset' => 'yes',
                    'ha_reading_progress_bar_circle_position' => 'bottom-right',
				]
			]
		); // end bottom-right
        
        $element->add_responsive_control(
			'ha_reading_progress_bar_circle_offset_x_bl',
			[
				'label' => __( 'Horizontal Align', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
				],
                'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .hm-crp-wrapper' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ha_reading_progress_bar_circle_offset' => 'yes',
					'ha_reading_progress_bar_circle_position' => 'bottom-left',
				]
			]
		);

		$element->add_responsive_control(
			'ha_reading_progress_bar_circle_offset_y_bl',
			[
				'label' => __( 'Vertical Align', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1
					],
				],
                'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .hm-crp-wrapper' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ha_reading_progress_bar_circle_offset' => 'yes',
                    'ha_reading_progress_bar_circle_position' => 'bottom-left',
				]
			]
		); // end bottom-left
		$element->end_popover();

		$element->add_control(
			'ha_reading_progress_bar_circle_bg_color',
			[
				'label' => __('Circle Inner Background ', 'happy-elementor-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .hm-circular-progress' => 'background-color: {{VALUE}}',
				],
                'condition' => [
                    'ha_reading_progress_bar_type' => 'circle',
                    'ha_reading_progress_bar_enable' => 'yes',
                ],
			]
		);
		$element->add_control(
			'ha_reading_progress_bar_circle_fill_color',
			[
				'label' => __('Circle Fill Color', 'happy-elementor-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#833ab4',
				'selectors' => [
					'{{WRAPPER}} .hm-crp-wrapper .hm-circular-progress .hm-progress-circle' => 'stroke: {{VALUE}}',
				],
                'condition' => [
                    'ha_reading_progress_bar_type' => 'circle',
                    'ha_reading_progress_bar_enable' => 'yes',
                ],
			]
		);
		$element->add_responsive_control(
            'ha_reading_progress_bar_circle_fill_width',
            [
				'label' => __( 'Circle Fill Width', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
                'frontend_available' => true,
				'size_units' => [ 'px' ],
				'desktop_default' => [
					'unit' => 'px'
				],
				'tablet_default' => [
					'unit' => 'px',
					'size' => 5
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 5
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 25,
					],
				],
                'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .hm-crp-wrapper .hm-circular-progress .hm-progress-circle' => 'stroke-width: {{SIZE}}{{UNIT}}',
				],
                'condition' => [
                    'ha_reading_progress_bar_enable' => 'yes',
                    'ha_reading_progress_bar_type' => 'circle',
                ],
			]
        );
		$element->add_control(
			'ha_reading_progress_bar_circle_tracker_color',
			[
				'label' => __('Circle Bar Color', 'happy-elementor-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#e6e6e6',
				'selectors' => [
					'{{WRAPPER}} .hm-crp-wrapper .hm-circular-progress .hm-progress-background' => 'stroke: {{VALUE}}',
				],
                'condition' => [
                    'ha_reading_progress_bar_type' => 'circle',
                    'ha_reading_progress_bar_enable' => 'yes',
                ],
			]
		);
		$element->add_responsive_control(
            'ha_reading_progress_bar_circle_tracker_width',
            [
				'label' => __( 'Circle Bar Width', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
                'frontend_available' => true,
				'size_units' => [ 'px' ],
				'desktop_default' => [
					'unit' => 'px'
				],
				'tablet_default' => [
					'unit' => 'px',
					'size' => 5
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 5
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 25,
					],
				],
                'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .hm-crp-wrapper .hm-circular-progress .hm-progress-background' => 'stroke-width: {{SIZE}}{{UNIT}}',
				],
                'condition' => [
                    'ha_reading_progress_bar_enable' => 'yes',
                    'ha_reading_progress_bar_type' => 'circle',
                ],
			]
        );

		$element->add_control(
			'hm_rpb_percentage_heading',
			[
				'label' => __( 'Percentage', 'happy-elementor-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'after',
				'condition' => [
                    'ha_reading_progress_bar_enable' => 'yes',
                    'ha_reading_progress_bar_type' => 'circle',
                ],
			]
		);
		$element->add_control(
			'ha_rpb_enable_circle_percentage',
			[
				'label'        => __( 'Disable Percentage ?', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'happy-elementor-addons' ),
				'label_off'    => __( 'No', 'happy-elementor-addons' ),
				'return_value' => 'yes',
                'frontend_available' => true,
				'condition' => [
                    'ha_reading_progress_bar_enable' => 'yes',
                    'ha_reading_progress_bar_type' => 'circle',
                ],
			]
		);
		$element->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'hm_rpb_percentage_typography',
				'label' => __('Typography', 'happy-elementor-addons'),
				// 'global' => [
				// 	'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				// ],
				'selector' => '{{WRAPPER}} .hm-crp-wrapper .hm-progress-percent-text',
				'condition' => [
                    'ha_reading_progress_bar_enable' => 'yes',
                    'ha_reading_progress_bar_type' => 'circle',
                    'ha_rpb_enable_circle_percentage' => 'yes',
                ],
			]
		);
		$element->add_control(
			'chm_rpb_percentage_typography_color',
			[
				'label' => __('Color', 'happy-elementor-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .hm-crp-wrapper .hm-progress-percent-text' => 'color: {{VALUE}}',
				],
				'condition' => [
                    'ha_reading_progress_bar_enable' => 'yes',
                    'ha_reading_progress_bar_type' => 'circle',
					'ha_rpb_enable_circle_percentage' => 'yes',
                ],
			]
		);
        //End circle control

        // Start horizontal
        $element->add_control(
            'ha_rpb_horizontal_position',
            [
                'label' => __('Position', 'happy-elementor-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'top' => __('Top', 'happy-elementor-addons'),
                    'bottom' => __('Bottom', 'happy-elementor-addons'),
                ],
                'frontend_available' => true,
				'selectors_dictionary' => [
					'top' => 'top: 0; bottom: unset;',
					'bottom' => 'bottom: 0; top: unset;',
				],
				'selectors' => [
					'{{WRAPPER}} .hm-hrp-bar-container' => '{{VALUE}}',
				],
                'condition' => [
                    'ha_reading_progress_bar_enable' => 'yes',
                    'ha_reading_progress_bar_type' => 'horizontal',
                ],
            ]
        );

		$element->add_responsive_control(
            'ha_rpb_horizontal_height',
            [
				'label' => __( 'Height', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
                'frontend_available' => true,
				'size_units' => [ 'px' ],
				'desktop_default' => [
					'unit' => 'px'
				],
				'tablet_default' => [
					'unit' => 'px',
					'size' => 10
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 10
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
                'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
                    '{{WRAPPER}} .hm-hrp-bar-container' => 'height: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                    'ha_reading_progress_bar_enable' => 'yes',
                    'ha_reading_progress_bar_type' => 'horizontal',
                ],
			]
        );

		$element->add_control(
			'hm_rpb_horizontal_fill_heading',
			[
				'label' => __( 'Fill Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'after',
				'condition' => [
                    'ha_reading_progress_bar_enable' => 'yes',
                    'ha_reading_progress_bar_type' => 'horizontal',
                ],
			]
		);
		$element->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'ha_rpb_horizontal_fill_color',
				'label' => __('Fill Color', 'happy-elementor-addons'),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .hm-hrp-bar-container .hm-hrp-bar',
                'condition' => [
                    'ha_reading_progress_bar_type' => 'horizontal',
                    'ha_reading_progress_bar_enable' => 'yes',
                ],
				// 'default' => [
				// 	'background' => [
				// 		'color' => '#833ab4',
				// 		'gradient' => [
				// 			'color' => '#833ab4',
				// 			'location' => 0,
				// 			'color_b' => '#fd1d1d',
				// 			'location_b' => 100,
				// 			'type' => 'linear',
				// 			'angle' => 180,
				// 		],
				// 	],
				// ],
			]
		);

		$element->add_control(
			'hm_rpb_horizontal_bar_heading',
			[
				'label' => __( 'Bar Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'after',
				'condition' => [
                    'ha_reading_progress_bar_enable' => 'yes',
                    'ha_reading_progress_bar_type' => 'horizontal',
                ],
			]
		);
        $element->add_control(
			'ha_rpb_horizontal_bg_color',
			[
				'label' => __('Background Color', 'happy-elementor-addons'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hm-hrp-bar-container' => 'background-color: {{VALUE}}',
				],
                'condition' => [
                    'ha_reading_progress_bar_type' => 'horizontal',
                    'ha_reading_progress_bar_enable' => 'yes',
                ],
			]
		); // End Horizontal

        
        // Start vertical
		$element->add_control(
            'ha_rpb_vertical_position',
            [
                'label' => __('Position', 'happy-elementor-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'right',
                'options' => [
                    'right' => __('Right', 'happy-elementor-addons'),
                    'left' => __('Left', 'happy-elementor-addons'),
                ],
                'frontend_available' => true,
				'selectors_dictionary' => [
					'right' => 'right: 0; top:0; left: unset;',
					'left' => 'left: 0; top:0; right: unset;',
				],
				'selectors' => [
					'{{WRAPPER}} .hm-vrp-bar-container' => '{{VALUE}}',
				],
                'condition' => [
                    'ha_reading_progress_bar_enable' => 'yes',
                    'ha_reading_progress_bar_type' => 'vertical',
                ],
            ]
        );
		$element->add_responsive_control(
            'ha_rpb_vertical_width',
            [
				'label' => __( 'Width', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
                'frontend_available' => true,
				'size_units' => [ 'px' ],
				'desktop_default' => [
					'unit' => 'px'
				],
				'tablet_default' => [
					'unit' => 'px',
					'size' => 10
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 10
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
                'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
                    '{{WRAPPER}} .hm-vrp-bar-container' => 'width: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                    'ha_reading_progress_bar_enable' => 'yes',
                    'ha_reading_progress_bar_type' => 'vertical',
                ],
			]
        );
		$element->add_control(
			'hm_rpb_vertical_fill_heading',
			[
				'label' => __( 'Fill Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'after',
				'condition' => [
                    'ha_reading_progress_bar_enable' => 'yes',
                    'ha_reading_progress_bar_type' => 'vertical',
                ],
			]
		);
		$element->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'ha_rpb_vertical_fill_color',
				'label' => __('Fill Color', 'happy-elementor-addons'),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .hm-vrp-bar-container .hm-vrp-bar',
                'condition' => [
					'ha_reading_progress_bar_enable' => 'yes',
                    'ha_reading_progress_bar_type' => 'vertical',
                ],
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => '#ff0000',
					],
					'gradient' => [
						'default' => [
							'color' => '#ff0000',
							'color_b' => '#00ff00',
							'type' => 'linear',
							'angle' => 180,
						],
					],
				],
			]
		);

		$element->add_control(
			'hm_rpb_vertical_bar_heading',
			[
				'label' => __( 'Bar Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'after',
				'condition' => [
                    'ha_reading_progress_bar_enable' => 'yes',
                    'ha_reading_progress_bar_type' => 'vertical',
                ],
			]
		);
        $element->add_control(
			'ha_rpb_vertical_bg_color',
			[
				'label' => __('Background Color', 'happy-elementor-addons'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hm-vrp-bar-container' => 'background-color: {{VALUE}}',
				],
                'condition' => [
					'ha_reading_progress_bar_enable' => 'yes',
                    'ha_reading_progress_bar_type' => 'vertical',
                ],
			]
		);

		$element->end_controls_section();
	}

	public function render_reading_progress_bar_html() {

        $post_id                = get_the_ID();
		$document               = [];
		$document_settings_data = [];
        $settings_data = [];

        if ( ha_elementor()->preview->is_preview_mode() ) {
			$document = Plugin::$instance->documents->get_doc_for_frontend( $post_id );
		} else {
			$document = Plugin::$instance->documents->get( $post_id, false );
		}
		if ( isset( $document ) && is_object( $document ) ) {
			$document_settings_data = $document->get_settings();
		}

        if ( isset( $document_settings_data['ha_reading_progress_bar_enable'] ) && 'yes' !== $document_settings_data['ha_reading_progress_bar_enable'] ) {
			return;
		}

        $progress_bar_type = $document_settings_data['ha_reading_progress_bar_type'];
        $enable_circle_percentage = $document_settings_data['ha_rpb_enable_circle_percentage'];
        $settings_data['progress_bar_type'] = $progress_bar_type;
        $settings_data['rpb_vertical_position'] = !empty($document_settings_data['ha_rpb_vertical_position']) ? $document_settings_data['ha_rpb_vertical_position'] : '';
        
        
        if( 'circle' === $progress_bar_type ) { ?>
            <div class="hm-crp-wrapper ha-reading-progress-bar " data-ha_rpbsettings="<?php echo esc_attr(json_encode($settings_data)); ?>">
                <svg class="hm-circular-progress" width="60" height="60" viewBox="0 0 100 100">
                    <circle class="hm-progress-background" cx="50" cy="50" r="45"></circle>
                    <circle class="hm-progress-circle" cx="50" cy="50" r="45"></circle>
                </svg>
				<?php if( 'yes' == $enable_circle_percentage){ ?> 
					<div class="hm-progress-percent-text">0%</div>
				<?php } ?>
            </div>
        <?php } else if ('vertical' === $progress_bar_type) { ?>
        <div id="hm_vrp_bar_wrapper" class="hm-vrp-bar-container ha-reading-progress-bar" data-ha_rpbsettings="<?php echo esc_attr(json_encode($settings_data)); ?>">
            <div class="hm-vrp-bar"></div>
        </div>
        <?php } else { ?>
        <div id="hm_hrp_bar_wrapper" class="hm-hrp-bar-container ha-reading-progress-bar" data-ha_rpbsettings="<?php echo esc_attr(json_encode($settings_data)); ?>">
            <div class="hm-hrp-bar"></div>
        </div>
    <?php } 

	}


    public function prevent_reading_progress_bar_rendering($post_id)
    {
        $get_template_name = get_post_meta($post_id, '_elementor_template_type', true);
        $template_list = [
            'header',
            'footer',
            'single',
            'post',
            'page',
            'search-results',
            'error-404',
            'section',
        ];

        return in_array($get_template_name, $template_list);
    }

}

Reading_Progress_Bar::instance()->init();