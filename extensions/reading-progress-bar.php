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
				'label'        => __( 'Enable Gobaly?', 'happy-elementor-addons' ),
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
                    'circle' => __('Circle', 'happy-elementor-addons'),
                    'horizontal' => __('Horizontal', 'happy-elementor-addons'),
                    'vertical' => __('Vertical', 'happy-elementor-addons'),
                ],
                'frontend_available' => true,
                'condition' => [
                    'ha_reading_progress_bar_enable' => 'yes',
                ],
            ]
        );      

        // Start circle
        $element->add_control(
			'ha_reading_progress_bar_circle_bg_color',
			[
				'label' => __('Background Color', 'happy-addons-pro'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hm-circular-progress' => 'background-color: {{VALUE}}',
				],
                'condition' => [
                    'ha_reading_progress_bar_type' => 'circle',
                ],
			]
		); 
        
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
                'condition' => [
                    'ha_reading_progress_bar_enable' => 'yes',
                    'ha_reading_progress_bar_type' => 'circle',
                ],
            ]
        );
        
        $element->add_responsive_control(
            'ha_reading_progress_bar_circle_width',
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
					'size' => 60
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 60
				],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 120,
					],
				],
                'default' => [
					'unit' => 'px',
					'size' => 60,
				],
				'selectors' => [
                    '{{WRAPPER}} .hm-crp-wrapper' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}}  .hm-circular-progress' => 'width: {{SIZE}}{{UNIT}}',
				],
                'condition' => [
                    'ha_reading_progress_bar_enable' => 'yes',
                    'ha_reading_progress_bar_type' => 'circle',
                ],
			]
        );
        $element->add_responsive_control(
            'ha_reading_progress_bar_circle_height',
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
					'size' => 60
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 60
				],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 120,
					],
				],
                'default' => [
					'unit' => 'px',
					'size' => 60,
				],
				'selectors' => [
                    '{{WRAPPER}} .hm-crp-wrapper' => 'height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}}  .hm-circular-progress' => 'height: {{SIZE}}{{UNIT}}',
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
				'label' => __( 'Offset', 'happy-addons-pro' ),
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
				'label' => __( 'Horizontal Align', 'happy-addons-pro' ),
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
					'{{WRAPPER}} .hm-crp-wrapper.hm-crp-top-right' => 'right: {{SIZE}}{{UNIT}};',
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
				'label' => __( 'Vertical Align', 'happy-addons-pro' ),
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
					'{{WRAPPER}} .hm-crp-wrapper.hm-crp-top-right' => 'top: {{SIZE}}{{UNIT}};',
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
				'label' => __( 'Horizontal Align', 'happy-addons-pro' ),
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
					'{{WRAPPER}} .hm-crp-wrapper.hm-crp-top-left' => 'left: {{SIZE}}{{UNIT}};',
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
				'label' => __( 'Vertical Align', 'happy-addons-pro' ),
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
					'{{WRAPPER}} .hm-crp-wrapper.hm-crp-top-left' => 'top: {{SIZE}}{{UNIT}};',
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
				'label' => __( 'Horizontal Align', 'happy-addons-pro' ),
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
					'{{WRAPPER}} .hm-crp-wrapper.hm-crp-bottom-right' => 'right: {{SIZE}}{{UNIT}};',
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
				'label' => __( 'Vertical Align', 'happy-addons-pro' ),
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
					'{{WRAPPER}} .hm-crp-wrapper.hm-crp-bottom-right' => 'bottom: {{SIZE}}{{UNIT}};',
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
				'label' => __( 'Horizontal Align', 'happy-addons-pro' ),
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
					'{{WRAPPER}} .hm-crp-wrapper.hm-crp-bottom-left' => 'left: {{SIZE}}{{UNIT}};',
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
				'label' => __( 'Vertical Align', 'happy-addons-pro' ),
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
					'{{WRAPPER}} .hm-crp-wrapper.hm-crp-bottom-left' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ha_reading_progress_bar_circle_offset' => 'yes',
                    'ha_reading_progress_bar_circle_position' => 'bottom-left',
				]
			]
		); // end bottom-left
		$element->end_popover();
        //End circle control

        // Start horizontal
        $element->add_control(
            'ha_reading_progress_bar_horizontal_position',
            [
                'label' => __('Position', 'happy-elementor-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'top' => __('Top', 'happy-elementor-addons'),
                    'bottom' => __('Bottom', 'happy-elementor-addons'),
                ],
                'frontend_available' => true,
                'condition' => [
                    'ha_reading_progress_bar_enable' => 'yes',
                    'ha_reading_progress_bar_type' => 'horizontal',
                ],
            ]
        );

        $element->add_control(
			'ha_reading_progress_bar_horizontal_bg_color',
			[
				'label' => __('Background Color', 'happy-addons-pro'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hm-hrp-bar-container' => 'background-color: {{VALUE}}',
				],
                'condition' => [
                    'ha_reading_progress_bar_type' => 'horizontal',
                ],
			]
		);
        
        // Start vertical
        $element->add_control(
            'ha_reading_progress_bar_vertical_position',
            [
                'label' => __('Position', 'happy-elementor-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'right',
                'options' => [
                    'left' => __('Left', 'happy-elementor-addons'),
                    'right' => __('Right', 'happy-elementor-addons'),
                ],
                'frontend_available' => true,
                'condition' => [
                    'ha_reading_progress_bar_enable' => 'yes',
                    'ha_reading_progress_bar_type' => 'vertical',
                ],
            ]
        );

        $element->add_control(
			'ha_reading_progress_bar_vertical_bg_color',
			[
				'label' => __('Background Color', 'happy-addons-pro'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hm-vrp-bar-container' => 'background-color: {{VALUE}}',
				],
                'condition' => [
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
        $settings_data['progress_bar_type'] = $progress_bar_type;
        
        
        if( 'circle' === $progress_bar_type ) { ?>
            <div class="hm-crp-wrapper ha-reading-progress-bar hm-crp-<?php echo esc_attr($document->get_settings('ha_reading_progress_bar_circle_position')); ?>" data-ha_rpbsettings="<?php echo esc_attr(json_encode($settings_data)); ?>">
                <svg class="hm-circular-progress" width="60" height="60" viewBox="0 0 100 100">
                    <circle class="hm-progress-background" cx="50" cy="50" r="45"></circle>
                    <circle class="hm-progress-circle" cx="50" cy="50" r="45"></circle>
                </svg>
                <div class="hm-progress-percent-text">0%</div>
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