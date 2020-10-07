<?php
/**
 * Horizontal Timeline widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Scheme_Typography;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();

class Horizontal_Timeline extends Base {
    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Horizontal Timeline', 'happy-elementor-addons' );
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
        return 'hm hm-timeline';
    }

    public function get_keywords() {
        return [ 'horizontal', 'timeline' ];
    }

    protected function register_content_controls() {
        $this->start_controls_section(
            '_section_timeline',
            [
                'label' => __( 'Timeline', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'event_date',
            [
                'label' => __( 'Event Date', 'happy-elementor-addons' ),
                'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Event Date', 'happy-elementor-addons' ),
            ]
		);
		
		$repeater->add_control(
            'event_title',
            [
                'label' => __( 'Title', 'happy-elementor-addons' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => __( 'Event Title', 'happy-elementor-addons' ),
            ]
		);

		$repeater->add_control(
            'event_subtitle',
            [
                'label' => __( 'Sub Title', 'happy-elementor-addons' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => __( 'Event Sub Title', 'happy-elementor-addons' ),
            ]
		);
		
		$repeater->add_control(
            'event_description',
            [
                'label' => __( 'Description', 'happy-elementor-addons' ),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'placeholder' => __( 'Event Description', 'happy-elementor-addons' ),
                'default' => __( 'Best Elementor Addons Plugin.', 'happy-elementor-addons' ),
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label' => __( 'Image', 'happy-elementor-addons' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ]
            ]
		);
		
		$repeater->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'medium',
                'separator' => 'before',
                'exclude' => [
                    'custom'
                ]
            ]
        );

        $this->add_control(
            'timeline',
            [
                'show_label' => false,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ event_title }}}',
                'default' => [
                    [
						'event_date' => 'Jan 01, 2020',
                        'event_title' => __( 'Build beautiful websites', 'happy-elementor-addons' ),
                    ],
                    [
						'event_date' => 'Jan 02, 2020',
                        'event_title' => __( 'Cross Domain Copy Paste', 'happy-elementor-addons' ),
                    ],
                    [
						'event_date' => 'Jan 03, 2020',
                        'event_title' => __( 'CSS Transform', 'happy-elementor-addons' ),
                    ],
                    [
						'event_date' => 'Jan 04, 2020',
                        'event_title' => __( 'Fast and Lightweight', 'happy-elementor-addons' ),
					]
                ],
            ]
        );

		$this->end_controls_section();
		
		$this->start_controls_section(
            '_section_settings',
            [
                'label' => __( 'Settings', 'happy-elementor-addons' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'animation_speed',
            [
                'label' => __( 'Animation Speed', 'happy-elementor-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'step' => 10,
                'max' => 10000,
                'default' => 800,
                'description' => __( 'Slide speed in milliseconds', 'happy-elementor-addons' ),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => __( 'Autoplay Speed', 'happy-elementor-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'step' => 100,
                'max' => 10000,
                'default' => 3000,
                'description' => __( 'Autoplay speed in milliseconds', 'happy-elementor-addons' ),
                'condition' => [
                    'autoplay' => 'yes'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => __( 'Infinite Loop?', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'happy-elementor-addons' ),
                'label_off' => __( 'No', 'happy-elementor-addons' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );
		
		$this->add_responsive_control(
            'slides_to_show',
            [
                'label' => __( 'Slides To Show', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    1 => __( '1 Slide', 'happy-elementor-addons' ),
                    2 => __( '2 Slides', 'happy-elementor-addons' ),
                    3 => __( '3 Slides', 'happy-elementor-addons' ),
                    4 => __( '4 Slides', 'happy-elementor-addons' ),
                    5 => __( '5 Slides', 'happy-elementor-addons' ),
                    6 => __( '6 Slides', 'happy-elementor-addons' ),
                ],
                'desktop_default' => 2,
                'tablet_default' => 3,
                'mobile_default' => 2,
                'frontend_available' => true,
                'style_transfer' => true,
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_controls() {		
		$this->start_controls_section(
            '_section_events_style',
            [
                'label' => __( 'Events', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'event_typography',
                'label' => __( 'Typography', 'happy-elementor-addons' ),
                'selector' => '{{WRAPPER}} .ha-event-list li a',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3
            ]
		);
		
		$this->add_control(
            'event_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-event-list li a' => 'color: {{VALUE}}',
                ],
            ]
		);
		
		$this->add_control(
            'line_color',
            [
                'label' => __( 'Line Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-filling-line' => 'background-color: {{VALUE}}',
                ],
            ]
		);
		
		$this->add_control(
            'line_bullet_color',
            [
                'label' => __( 'Line Bullet Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .ha-event-list a:after' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .ha-event-list .ha-selected:after' => 'background-color: {{VALUE}}'
                ],
            ]
		);
		
		$this->add_control(
            'arrow_heading',
            [
                'label' => __( 'Arrow', 'happy-elementor-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
		);

		$this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'arrow_border',
                'selector' => '{{WRAPPER}} .ha-prev, {{WRAPPER}} .ha-next',
            ]
		);
		
		$this->add_control(
            'disable_arrow_border_type',
            [
                'label' => __( 'Disable Arrow Border', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'solid',
                'options' => [
					'none' => __( 'None', 'happy-elementor-addons' ),
					'solid' => __( 'Solid', 'happy-elementor-addons' ),
					'double' => __( 'Double', 'happy-elementor-addons' ),
					'dotted' => __( 'Dotted', 'happy-elementor-addons' ),  
					'dashed' => __( 'Dotted', 'happy-elementor-addons' ),  
				],
				'selectors' => [
					'{{WRAPPER}} .ha-timeline-navigation .ha-inactive' => 'border-style: {{VALUE}}'
                ],
            ]
		);
		
		$this->add_responsive_control(
            'disable_arrow_border_width',
            [
                'label' => __( 'Width', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'condition' => [
					'disable_arrow_border_type!' => 'none'
				],
                'selectors' => [
                    '{{WRAPPER}} .ha-timeline-navigation .ha-inactive' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
		);
		
		$this->add_control(
            'disable_arrow_border_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'disable_arrow_border_type!' => 'none'
				],
                'selectors' => [
					'{{WRAPPER}} .ha-timeline-navigation .ha-inactive' => 'border-color: {{VALUE}}',
                ],
            ]
		);
		
		$this->add_control(
            'arrow_color',
            [
                'label' => __( 'Arrow Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .ha-prev i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .ha-next i' => 'color: {{VALUE}}',
                ],
            ]
		);

		$this->add_control(
            'disable_arrow_color',
            [
                'label' => __( 'Disable Arrow Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .ha-timeline-navigation .ha-inactive i' => 'color: {{VALUE}}',
                ],
            ]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
            '_section_content_style',
            [
                'label' => __( 'Content', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
		);

		$this->add_control(
            'image_heading',
            [
                'label' => __( 'Image', 'happy-elementor-addons' ),
				'type' => Controls_Manager::HEADING,
            ]
		);

		$this->add_responsive_control(
            'image_spacing',
            [
                'label' => __( 'Right Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-event-image' => 'margin-right: {{SIZE}}{{UNIT}};'
                ],
            ]
		);

		$this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-event-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
		);

		$this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'selector' => '{{WRAPPER}} .ha-event-image img',
            ]
        );

		$this->add_control(
            'title_heading',
            [
                'label' => __( 'Title', 'happy-elementor-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
            ]
		);

		$this->add_responsive_control(
            'title_spacing',
            [
                'label' => __( 'Bottom Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-event-content h2' => 'margin-bottom: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __( 'Typography', 'happy-elementor-addons' ),
                'selector' => '{{WRAPPER}} .ha-event-content h2',
                'scheme' => Scheme_Typography::TYPOGRAPHY_2
            ]
		);

		$this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .ha-event-content h2' => 'color: {{VALUE}}',
                ],
            ]
		);

		$this->add_control(
            'content_heading',
            [
                'label' => __( 'Content', 'happy-elementor-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
            ]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'label' => __( 'Typography', 'happy-elementor-addons' ),
                'selector' => '{{WRAPPER}} .ha-event-content p',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3
            ]
		);

		$this->add_control(
            'content_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .ha-event-content p' => 'color: {{VALUE}}',
                ],
            ]
		);
		
		$this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['timeline'] ) ) {
            return;
		}

		$this->add_render_attribute( 'wrapper', 'class', 'ha-horizontal-timeline-wrapper' );
		$this->add_render_attribute( 'wrapper', 'class', 'ha-carousel' );
        ?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			
			<?php foreach ( $settings['timeline'] as $timeline ) : ?>
				<div class="ha-horizontal-timeline-block">
					<div class="ha-horizontal-timeline-icon-box">
						<div class="ha-horizontal-timeline-icon">
							<i class="fas fa-calendar-alt"></i>
						</div>
						<span class="ha-horizontal-timeline-date"><?php echo esc_html( $timeline['event_date'] ); ?></span>
						<div class="ha-horizontal-timeline-tree"></div>
					</div>

					<div class="ha-horizontal-timeline-content">
						<?php if ( ! empty( $timeline['image']['url'] ) ) : ?>
							<div class="ha-horizontal-timeline-image">
								<?php echo Group_Control_Image_Size::get_attachment_image_html( $timeline, 'thumbnail', 'image' ); ?>
							</div>
						<?php endif; ?>

						<h2 class="ha-horizontal-timeline-title"><?php echo esc_html( $timeline['event_title'] ); ?></h2>

						<?php if ( !empty( $timeline['event_subtitle'] ) ) : ?>
							<span class="ha-horizontal-timeline-subtitle"><?php echo esc_html( $timeline['event_subtitle'] ); ?></span>
						<?php endif; ?>

						<p class="ha-horizontal-timeline-description"><?php echo esc_html( $timeline['event_description'] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>

		</div>

        <?php
    }

}
