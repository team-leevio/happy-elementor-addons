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

class Google_Map extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Google Map', 'happy-elementor-addons' );
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
		return 'hm hm-map-marker';
	}

	public function get_keywords() {
		return [ 'info', 'blurb', 'box', 'text', 'content' ];
	}

	/**
	 * Register content related controls
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'_section_google_map',
			[
				'label' => __( 'Map Address', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'google_map_address',
			[
				'label' => __( 'Address', 'happy-elementor-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'New York', 'happy-elementor-addons' ),
				'placeholder' => __( 'Type your address here', 'happy-elementor-addons' ),
			]
		);




		$this->end_controls_section();
	}

	/**
	 * Register styles related controls
	 */
	protected function register_style_controls() {
		$this->start_controls_section(
			'_section_style_google_map',
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
					'{{WRAPPER}} iframe' => 'width: {{SIZE}}{{UNIT}};',
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
				],
				'default' => [
					'unit' => 'px',
					'size' => 300,
				],
				'selectors' => [
					'{{WRAPPER}} iframe' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);				


		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>

		 <?php // echo esc_html( $settings['number_text'] ); ?> 


			<div class="ha-google-map-body">
			 
						<iframe style="max-width: 100%;" width="600" height="500"   src="https://maps.google.com/maps?q=<?php echo esc_attr(urlencode($settings['google_map_address'])); ?>&t=&z=<?php echo esc_attr( $settings['google_map_zoom']['size'] ); ?>&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
						 
			 
			</div>

		<?php
	}
}
