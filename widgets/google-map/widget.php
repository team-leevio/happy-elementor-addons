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
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Google Map', 'happy-elementor-addons' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
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
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'google_map_type',
			[
				'label'   => __( 'Map Type', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'address'     => __( 'Location Address', 'happy-elementor-addons' ),
					'coordinates' => __( 'Location Coordinates', 'happy-elementor-addons' ),
				],
				'default' => 'address'
			]
		);

		$this->add_control(
			'google_map_address',
			[
				'label'       => __( 'Address', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Area 51, Nevada', 'happy-elementor-addons' ),
				'condition'   => [
					'google_map_type' => 'address'
				],
				'placeholder' => __( 'Type your address here', 'happy-elementor-addons' ),
			]
		);

		$this->add_control(
			'google_map_latitude',
			[
				'label'       => __( 'Location Latitude', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '37.233333',
				'condition'   => [
					'google_map_type' => 'coordinates'
				],
				'placeholder' => __( 'Latitude', 'happy-elementor-addons' ),
			]
		);
		$this->add_control(
			'google_map_longitude',
			[
				'label'       => __( 'Location Longitude', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '-115.808333',
				'condition'   => [
					'google_map_type' => 'coordinates'
				],
				'placeholder' => __( 'Longitude', 'happy-elementor-addons' ),
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
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_responsive_control(
			'google_map_zoom',
			[
				'label'      => __( 'Map Zoom', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 19,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 13,
				],

			]
		);


		$this->add_responsive_control(
			'google_map_width',
			[
				'label'      => __( 'Map Width', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 5000,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors'  => [
					'{{WRAPPER}} iframe'              => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ha-google-map-body' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_responsive_control(
			'google_map_height',
			[
				'label'      => __( 'Map Height', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 5000,
						'step' => 5,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 300,
				],
				'selectors'  => [
					'{{WRAPPER}} iframe'        => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .maps-wrapper' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<?php if ( 'address' == $settings['google_map_type'] ): ?>
            <div class="ha-google-map-body">
                <iframe style="max-width: 100%;"
                        src="https://maps.google.com/maps?q=<?php echo esc_attr( urlencode( $settings['google_map_address'] ) ); ?>&z=<?php echo esc_attr( $settings['google_map_zoom']['size'] ); ?>&ie=UTF8&iwloc=&output=embed"
                        frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
            </div>

		<?php
		else:
			?>
            <div class="ha-google-map-body">
                <iframe style="max-width: 100%;"
                        src="https://maps.google.com/maps?q=<?php echo esc_attr( $settings['google_map_latitude'] ) . "," . esc_attr( $settings['google_map_longitude'] ); ?>&z=<?php echo esc_attr( $settings['google_map_zoom']['size'] ); ?>&ie=UTF8&iwloc=&output=embed"
                        frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
            </div>
		<?php
		endif;
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) :
			?>
            <div class="maps-wrapper" style="width:100%; position:absolute; top:0; left:0; z-index:100;"></div>
		<?php
		endif;

	}
}


