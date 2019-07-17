<?php
/**
 * Blurb widget class
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
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;
use Happy_Addons\Elementor\Controls\Group_Control_Foreground;

defined( 'ABSPATH' ) || die();

class Calendly extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Calendly', 'happy-elementor-addons' );
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
		return 'hm hm-calendar';
	}

	public function get_keywords() {
		return [ 'info', 'blurb', 'box', 'text', 'content' ];
	}

	/**
	 * Register content related controls
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'_section_calendly',
			[
				'label' => __( 'Map Address', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'calendly_event_link',
			[
				'label' => __( 'Calendly Event Link', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '', 'plugin-domain' ),
				'placeholder' => __( 'Type event link here', 'plugin-domain' ),
			]
		);




		$this->end_controls_section();
	}

	/**
	 * Register styles related controls
	 */
	protected function register_style_controls() {
		$this->start_controls_section(
			'_section_style_calendly',
			[
				'label' => __( 'Map Style', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);


 		$this->add_responsive_control(
			'google_map_zoom',
			[
				'label' => __( 'Map Zoom', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 19,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 13,
				],

			]
		);


 		$this->add_responsive_control(
			'google_map_width',
			[
				'label' => __( 'Map Width', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px','%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 600,
				],
				'selectors' => [
					'{{WRAPPER}} iframe' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);	


 		$this->add_responsive_control(
			'google_map_height',
			[
				'label' => __( 'Map Height', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px',  ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 300,
				],
				'selectors' => [
					'{{WRAPPER}} iframe' => 'height: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);				


		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>

		 <?php // echo esc_html( $settings['number_text'] ); ?> 


			<div class="ha-calendly-body">
			 
			<!-- Calendly inline widget begin -->
			<div class="calendly-inline-widget" data-url="https://calendly.com/rasel2339/15min?background_color=b74040&text_color=4681e2&primary_color=4da8dd" style="min-width:320px;height:630px;"></div>
			<script type="text/javascript" src="https://assets.calendly.com/assets/external/widget.js"></script>
			<!-- Calendly inline widget end -->


<!-- Calendly inline widget begin -->
<div class="calendly-inline-widget" data-url="https://calendly.com/rasel2339/15min" style="min-width:320px;height:630px;"></div>
<script type="text/javascript" src="https://assets.calendly.com/assets/external/widget.js"></script>
<!-- Calendly inline widget end -->




<!-- Calendly inline widget begin -->
<div class="calendly-inline-widget" data-url="https://calendly.com/rasel2339/15min?hide_event_type_details=1" style="min-width:320px;height:630px;"></div>
<script type="text/javascript" src="https://assets.calendly.com/assets/external/widget.js"></script>
<!-- Calendly inline widget end -->						 
			 
			</div>

		<?php
	}
}
