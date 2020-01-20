<?php
/**
 * Chart widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Scheme_Typography;

defined( 'ABSPATH' ) || die();

class Chart extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Chart', 'happy-elementor-addons' );
	}

	public function get_custom_help_url() {
		return '';
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
		return 'hm hm-icon-box';
	}

	public function get_keywords() {
		return [ 'chart', 'statistic' ];
	}

	protected function register_content_controls() {
		$this->start_controls_section(
			'_section_chart',
			[
				'label' => __( 'Chart', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'type',
			[
				'label'   => __( 'Bar Type', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontalBar',
				'options' => [
					'bar'           => __( 'Vertical', 'happy-elementor-addons' ),
					'horizontalBar' => __( 'Horizontal', 'happy-elementor-addons' ),
				],
			]
		);

		$this->add_control(
			'labels',
			[
				'label'       => __( 'Labels', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'January, February, March', 'happy-elementor-addons' ),
				'description' => __( 'Write multiple label with comma ( , ) separator. Example: March, April, May etc', 'happy-elementor-addons' ),
			]
		);

		$this->add_control(
			'axis_range',
			[
				'label'       => __( 'Scale Axis Range', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 10,
				'description' => __( 'User defined maximum number for the scale, overrides maximum value from data.', 'happy-elementor-addons' ),
			]
		);

		$this->add_control(
			'step_size',
			[
				'label'       => __( 'Step Size', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1,
				'step'        => 1,
				'description' => __( 'User defined fixed step size for the scale.', 'happy-elementor-addons' ),
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'bar_tabs' );

		$repeater->start_controls_tab(
			'bar_tab_content',
			[
				'label' => __( 'Content', 'happy-elementor-addons' ),
			]
		);

		$repeater->add_control(
			'label',
			[
				'label'   => __( 'Label', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => [ 'active' => true ],
			]
		);

		$repeater->add_control(
			'data',
			[
				'label'       => __( 'Data', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Enter data values width comma ( , ) separator. Example: 4, 2, 6', 'happy-elementor-addons' ),
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'bar_tab_style',
			[
				'label' => __( 'Style', 'happy-elementor-addons' ),
			]
		);

		$repeater->add_control(
			'bg_color',
			[
				'label' => __( 'Background Color', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$repeater->add_control(
			'bg_hover_color',
			[
				'label' => __( 'Background Hover Color', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$repeater->add_control(
			'border_color',
			[
				'label' => __( 'Border Color', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$repeater->add_control(
			'border_hover_color',
			[
				'label' => __( 'Border Hover Color', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$repeater->end_controls_tab();

		$this->add_control(
			'chart_data',
			[
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array_values( $repeater->get_controls() ),
				'title_field' => '{{{ label }}}',
				'default'     => [
					[
						'label'              => __( 'Google', 'happy-elementor-addons' ),
						'data'               => __( '2, 4, 8', 'happy-elementor-addons' ),
						'bg_color'           => 'rgba(221,75,57,0.4)',
						'bg_hover_color'     => '#dd4b39',
						'border_color'       => '#dd4b39',
						'border_hover_color' => '#dd4b39',
					],
					[
						'label'              => __( 'Facebook', 'happy-elementor-addons' ),
						'data'               => __( '1, 5, 3', 'happy-elementor-addons' ),
						'bg_color'           => 'rgba(59,89,152,0.4)',
						'bg_hover_color'     => '#3b5998',
						'border_color'       => '#3b5998',
						'border_hover_color' => '#3b5998',
					],
					[
						'label'              => __( 'Twitter', 'happy-elementor-addons' ),
						'data'               => __( '5, 9, 5', 'happy-elementor-addons' ),
						'bg_color'           => 'rgba(85,172,238,0.4)',
						'bg_hover_color'     => '#55acee',
						'border_color'       => '#55acee',
						'border_hover_color' => '#55acee',
					],
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'settings',
			[
				'label' => __( 'Settings', 'happy-elementor-addons' ),
			]
		);

		$this->add_responsive_control(
			'chart_height',
			[
				'label'       => __( 'Chart Height', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'min' => 50,
						'max' => 1500,
					],
				],
				'selectors'   => [
					'{{WRAPPER}} .chartjs-size-monitor' => 'height: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'grid_display',
			[
				'label'        => __( 'Show Grid Lines', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'labels_display',
			[
				'label'        => __( 'Show Labels', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'tooltip_display',
			[
				'label'        => __( 'Show Tooltips', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'legend_heading',
			[
				'label'     => __( 'Legend', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'legend_display',
			[
				'label'        => __( 'Show Legend', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'legend_position',
			[
				'label'     => __( 'Position', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'top',
				'options'   => [
					'top'    => __( 'Top', 'happy-elementor-addons' ),
					'left'   => __( 'Left', 'happy-elementor-addons' ),
					'bottom' => __( 'Bottom', 'happy-elementor-addons' ),
					'right'  => __( 'Right', 'happy-elementor-addons' ),
				],
				'condition' => [
					'legend_display' => 'yes',
				],
			]
		);

		$this->add_control(
			'legend_reverse',
			[
				'label'        => __( 'Reverse', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
				'condition'    => [
					'legend_display'  => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_controls() {
		$this->start_controls_section(
			'_section_style_chart',
			[
				'label' => __( 'Chart', 'happy-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'chart_border_width',
			[
				'label' => __( 'Border Width', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function chart_data() {
		$settings = $this->get_settings_for_display();

		$datasets = [];
		$items = $settings['chart_data'];

		if ( !empty( $items ) ) {
			foreach ( $items as $item ) {
				$item['label']                = !empty( $item['label'] ) ? $item['label'] : '';
				$item['data']                 = !empty( $item['data'] ) ? array_map('intval', explode(',', $item['data'])) : '';
				$item['backgroundColor']      = !empty( $item['bg_color'] ) ? $item['bg_color'] : '#cecece';
				$item['hoverBackgroundColor'] = !empty( $item['bg_hover_color'] ) ? $item['bg_hover_color'] : '#7a7a7a';
				$item['borderColor']          = !empty( $item['border_color'] ) ? $item['border_color'] : '#7a7a7a';
				$item['hoverBorderColor']     = !empty( $item['border_hover_color'] ) ? $item['border_hover_color'] : '#7a7a7a';
				$item['borderWidth']          = ( $settings['chart_border_width']['size'] !== '' ) ? $settings['chart_border_width']['size'] : 1;

				$datasets[] = $item;
			}
		}

		return $datasets;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$data_options = '';

		$data_settings = esc_attr( json_encode(
			[
				'type'    => $settings['type'],
				'data'    => array(
					'labels'   => explode(',', $settings['labels']),
					'datasets' => $this->chart_data(),
				),
				'options' => $data_options
			]
		) );
		$this->add_render_attribute(
			'container',
			[
				'class'         => 'ha-chart-container',
				'data-settings' => $data_settings
			]
		);

		$this->add_render_attribute( 'canvas', [ 'id' => 'ha-chart-bar', 'role'  => 'img', ] );
		?>

		<div <?php echo $this->get_render_attribute_string( 'container' ); ?>>

			<canvas <?php echo $this->get_render_attribute_string( 'canvas' ); ?>></canvas>

		</div>

	<?php
	}

}
