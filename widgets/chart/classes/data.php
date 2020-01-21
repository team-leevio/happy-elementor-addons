<?php
/**
 * Chart widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget\Chart;

defined( 'ABSPATH' ) || die();


class Data {

	public static function chart_data($settings) {

		$datasets = [];
		$items = $settings['chart_data'];

		if ( !empty( $items ) ) {
			foreach ( $items as $item ) {
				$item['label']                = !empty( $item['label'] ) ? $item['label'] : '';
				$item['data']                 = !empty( $item['data'] ) ? array_map('intval', explode(',', $item['data'])) : '';
				$item['backgroundColor']      = !empty( $item['background_color'] ) ? $item['background_color'] : '#cecece';
				$item['hoverBackgroundColor'] = !empty( $item['background_hover_color'] ) ? $item['background_hover_color'] : '#7a7a7a';
				$item['borderColor']          = !empty( $item['border_color'] ) ? $item['border_color'] : '#7a7a7a';
				$item['hoverBorderColor']     = !empty( $item['border_hover_color'] ) ? $item['border_hover_color'] : '#7a7a7a';
				$item['borderWidth']          = ( $settings['chart_border_width']['size'] !== '' ) ? $settings['chart_border_width']['size'] : 1;

				$datasets[] = $item;
			}
		}

		return $datasets;
	}


	public static function chart_options($settings) {

		$labels_display   = $settings['labels_display'];
		$tooltips_display = $settings['tooltip_display'];
		$legend_display   = $settings['legend_display'];
		$grid_display     = $settings['grid_display'];
		$title_display     = $settings['title_display'];

		$options = [
			'title' => [
				'display' => $title_display == 'yes' ? true : false,
				'text' => $settings['chart_title']
			],
			'tooltips' => [
				'enabled' => $tooltips_display,
			],
			'legend'	=> [
				'display'  => $legend_display,
				'position' => !empty( $settings['legend_position'] ) ? $settings['legend_position'] : 'top',
				'reverse'  => $settings['legend_reverse'] == 'yes' ? true : false,
			],
			'maintainAspectRatio' => false,
			'animation' => [
				'easing' => $settings['animation_options'],
				'duration' => $settings['chart_animation_duration'],
			]
		];

//		$tooltip_title_style_dictionary = array(
//			'titleFontFamily'  => 'chart_tooltip_title_font_family',
//			'titleFontSize'    => 'chart_tooltip_title_font_size',
//			'titleFontStyle'   => array( 'chart_tooltip_title_font_style', 'chart_tooltip_title_font_weight' ),
//			'titleFontColor'   => 'chart_tooltip_title_font_color',
//		);
//
//		$tooltip_style_dictionary = array(
//			'bodyFontFamily'  => 'chart_tooltip_font_family',
//			'bodyFontSize'    => 'chart_tooltip_font_size',
//			'bodyFontStyle'   => array( 'chart_tooltip_font_style', 'chart_tooltip_font_weight' ),
//			'bodyFontColor'   => 'chart_tooltip_font_color',
//		);
//
//		if ( $tooltips_enabled ) {
//
//			if ( ! empty( $settings['chart_tooltip_bg_color'] ) ) {
//				$options['tooltips'] = array(
//					'backgroundColor' => $settings['chart_tooltip_bg_color'],
//				);
//			}
//
//			foreach ( $tooltip_title_style_dictionary as $style_property => $setting_name ) {
//
//				if ( is_array( $setting_name ) ) {
//					$style_value = $this->get_chart_font_style_string( $setting_name );
//
//					if ( ! empty( $style_value ) ) {
//						$options['tooltips'][ $style_property ] = $style_value;
//					}
//				} else {
//					if ( ! empty( $settings[ $setting_name ] ) ) {
//						if ( is_array( $settings[ $setting_name ] ) ) {
//							if ( ! empty( $settings[ $setting_name ]['size'] ) ) {
//								$options['tooltips'][ $style_property ] = $settings[ $setting_name ]['size'];
//							}
//						} else {
//							$options['tooltips'][ $style_property ] = $settings[ $setting_name ];
//						}
//					}
//				}
//			}
//
//			foreach ( $tooltip_style_dictionary as $style_property => $setting_name ) {
//
//				if ( is_array( $setting_name ) ) {
//					$style_value = $this->get_chart_font_style_string( $setting_name );
//
//					if ( ! empty( $style_value ) ) {
//						$options['tooltips'][ $style_property ] = $style_value;
//					}
//				} else {
//					if ( ! empty( $settings[ $setting_name ] ) ) {
//						if ( is_array( $settings[ $setting_name ] ) ) {
//							if ( ! empty( $settings[ $setting_name ]['size'] ) ) {
//								$options['tooltips'][ $style_property ] = $settings[ $setting_name ]['size'];
//							}
//						} else {
//							$options['tooltips'][ $style_property ] = $settings[ $setting_name ];
//						}
//					}
//				}
//			}
//		}
//
//		$legend_style = array();
//
//		$legend_style_dictionary = array(
//			'boxWidth'   => 'chart_legend_box_width',
//			'fontFamily' => 'chart_legend_font_family',
//			'fontSize'   => 'chart_legend_font_size',
//			'fontStyle'  => array( 'chart_legend_font_style', 'chart_legend_font_weight' ),
//			'fontColor'  => 'chart_legend_font_color',
//		);
//
//		if ( $legend_display ) {
//
//			foreach ( $legend_style_dictionary as $style_property => $setting_name ) {
//
//				if ( is_array( $setting_name ) ) {
//					$style_value = $this->get_chart_font_style_string( $setting_name );
//
//					if ( ! empty( $style_value ) ) {
//						$legend_style[ $style_property ] = $style_value;
//					}
//				} else {
//					if ( ! empty( $settings[ $setting_name ] ) ) {
//						if ( is_array( $settings[ $setting_name ] ) ) {
//							if ( ! empty( $settings[ $setting_name ]['size'] ) ) {
//								$legend_style[ $style_property ] = $settings[ $setting_name ]['size'];
//							}
//						} else {
//							$legend_style[ $style_property ] = $settings[ $setting_name ];
//						}
//					}
//				}
//			}
//
//			if ( ! empty( $legend_style ) ) {
//				$options['legend']['labels'] = $legend_style;
//			}
//		}

		if ( $grid_display == 'yes' ) {
			$options['scales'] = [
				'yAxes' => [
					[
						'ticks' => [
							'display'     => $labels_display,
							'beginAtZero' => true,
							'max'         => isset( $settings['axis_range'] ) ? $settings['axis_range'] : 10,
							'stepSize'    => isset( $settings['step_size'] ) ? $settings['step_size'] : 1,
						],
						'gridLines'   => [
							'drawBorder' => false,
							'color'      => isset( $settings['chart_grid_color'] ) ? $settings['chart_grid_color'] : 'rgba(0,0,0,0.05)',
						]
					]
				],
				'xAxes' => [
					[
						'ticks'     => [
							'display'     => $labels_display,
							'beginAtZero' => true,
							'max'         => isset( $settings['axis_range'] ) ? $settings['axis_range'] : 10,
							'stepSize'    => isset( $settings['step_size'] ) ? $settings['step_size'] : 1,
						],
						'gridLines' => [
							'drawBorder' => false,
							'color'      => isset( $settings['chart_grid_color'] ) ? $settings['chart_grid_color'] : 'rgba(0,0,0,0.05)',
						]
					]
				]
			];
		} else {
			$options['scales'] = [
				'yAxes' => [
					[
						'ticks' => [
							'display'     => $labels_display,
							'beginAtZero' => true,
						],
						'gridLines' => [
							'display'    => false,
						]
					]
				],
				'xAxes' => [
					[
						'ticks' => [
							'display'     => $labels_display,
							'beginAtZero' => true,
						],
						'gridLines' => [
							'display'    => false,
						]
					]
				]
			];
		}

//		$labels_style = array();
//
//		$labels_style_dictionary = array(
//			'fontFamily' => 'chart_labels_font_family',
//			'fontSize'   => 'chart_labels_font_size',
//			'fontStyle'  => array( 'chart_labels_font_style', 'chart_labels_font_weight' ),
//			'fontColor'  => 'chart_labels_font_color',
//		);
//
//		if ( $labels_display ) {
//
//			foreach ( $labels_style_dictionary as $style_property => $setting_name ) {
//
//				if ( is_array( $setting_name ) ) {
//					$style_value = $this->get_chart_font_style_string( $setting_name );
//
//					if ( ! empty( $style_value ) ) {
//						$labels_style[ $style_property ] = $style_value;
//					}
//				} else {
//					if ( ! empty( $settings[ $setting_name ] ) ) {
//						if ( is_array( $settings[ $setting_name ] ) ) {
//							if ( ! empty( $settings[ $setting_name ]['size'] ) ) {
//								$labels_style[ $style_property ] = $settings[ $setting_name ]['size'];
//							}
//						} else {
//							$labels_style[ $style_property ] = $settings[ $setting_name ];
//						}
//					}
//				}
//			}
//
//			if ( ! empty( $labels_style ) ) {
//				$options['scales']['xAxes'][0]['ticks'] = array_merge( $options['scales']['xAxes'][0]['ticks'], $labels_style );
//				$options['scales']['yAxes'][0]['ticks'] = array_merge( $options['scales']['yAxes'][0]['ticks'], $labels_style );
//			}
//		}

		return $options;
	}
}
