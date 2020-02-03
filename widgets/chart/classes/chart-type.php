<?php
/**
 * Chart_type widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget\Chart;

defined( 'ABSPATH' ) || die();


include_once HAPPY_ADDONS_DIR_PATH . "widgets/chart/classes/bar-chart.php";

class Chart_type {

	public static function initial($settings) {
		$data_settings = json_encode(
			[
				'type'    => $settings['chart_type'] == 'bar' ? $settings['chart_position'] : $settings['chart_type'],
				'data'    => [
					'labels'   => explode(',', esc_html( $settings['labels'] ) ),
					'datasets' => self::chart_data($settings),
				],
				'options' => self::chart_options($settings)
			]
		);
		return $data_settings;

	}

	private static function chart_data($settings) {
		if ( $settings['chart_type'] == 'bar' ) {
			return Bar_Chart::chart_dataset($settings);
		}

		return Bar_Chart::chart_dataset($settings);
	}

	private static function chart_options($settings) {

		return Bar_Chart::chart_options($settings);
	}
}
