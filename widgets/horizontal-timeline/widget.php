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
                'label' => __( 'Date', 'happy-elementor-addons' ),
                'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Event Date', 'happy-elementor-addons' ),
				'description' => __( 'Date Format: d/m/Y. Add unique dates in a ascending order.', 'happy-elementor-addons' ),
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

        $this->add_control(
            'timeline',
            [
                'show_label' => false,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ event_title }}}',
                'default' => [
                    [
						'event_date' => '1/01/2020',
                        'event_title' => __( 'Build beautiful websites', 'happy-elementor-addons' ),
                    ],
                    [
						'event_date' => '2/01/2020',
                        'event_title' => __( 'Cross Domain Copy Paste', 'happy-elementor-addons' ),
                    ],
                    [
						'event_date' => '3/01/2020',
                        'event_title' => __( 'CSS Transform', 'happy-elementor-addons' ),
                    ],
                    [
						'event_date' => '5/01/2020',
                        'event_title' => __( 'Fast and Lightweight', 'happy-elementor-addons' ),
					],
					[
						'event_date' => '6/01/2020',
                        'event_title' => __( 'Amazing Widgets', 'happy-elementor-addons' ),
					],
					[
						'event_date' => '7/01/2020',
                        'event_title' => __( 'Floating Effect', 'happy-elementor-addons' ),
                    ],
                ],
            ]
        );

		$this->end_controls_section();
		
		$this->start_controls_section(
            '_section_settings',
            [
                'label' => __( 'Settings', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
		);

		$this->add_responsive_control(
			'events_distance',
			[
				'label' => __( 'Minimum Distance bitween Events', 'happy-elementor-addons' ),
				'description' => __( 'Reload the page to see changes.', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'default' => [
                    'unit' => 'px',
                    'size' => 200
                ],
			]
		);

		$this->add_responsive_control(
			'content_alignment',
			[
				'label' => __( 'Content Alignment', 'happy-elementor-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'happy-elementor-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'happy-elementor-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'happy-elementor-addons' ),
						'icon' => 'eicon-text-align-right',
					]
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .ha-events-content .ha-selected' => 'text-align: {{VALUE}};'
				]
			]
		);
		
		$this->end_controls_section();
    }

    protected function register_style_controls() {
        $this->start_controls_section(
            '_section_common_style',
            [
                'label' => __( 'Common', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
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
		$this->add_render_attribute( 'wrapper', 'data-event-distance', $settings['events_distance']['size'] );
        ?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			
			<div class="ha-timeline">
				<div class="ha-events-wrapper">
					<div class="ha-events">
						<ol class="ha-event-list">
							<?php foreach ( $settings['timeline'] as $timeline ) : ?>
								<li>
									<a href="#" data-date="<?php echo esc_attr( $timeline['event_date'] ); ?>">
										<?php echo esc_html( $timeline['event_title'] ); ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ol>

						<span class="ha-filling-line" aria-hidden="true"></span>
					</div>
				</div>
					
				<ul class="ha-timeline-navigation">
					<li><a href="#0" class="ha-prev ha-inactive"><i class="hm hm-arrow-left"></i></a></li>
					<li><a href="#0" class="ha-next"><i class="hm hm-arrow-right"></i></a></li>
				</ul>
			</div>

			<div class="ha-events-content">
				<ol>
					<?php foreach ( $settings['timeline'] as $timeline ) : ?>
						<li data-date="<?php echo esc_attr( $timeline['event_date'] ); ?>">
							<h2><?php echo esc_html( $timeline['event_title'] ); ?></h2>
							<p><?php echo $timeline['event_description']; ?></p>
						</li>				
					<?php endforeach; ?>	
				</ol>
			</div>
		</div>

        <?php
    }

}
