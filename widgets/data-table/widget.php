<?php
/**
 * Data Table
 *
 * @package Happy_Addons
 */

namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Control_Media;

defined('ABSPATH') || die();

class Data_Table extends Base {

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Data Table', 'happy-elementor-addons' );
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
		return 'hm hm-tablet';
	}

	public function get_keywords() {
		return ['data', 'table', 'statistics'];
	}


	/**
	 * Register content related controls
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'_section_table',
			[
				'label' => __( 'Data Table', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'head_align',
			[
				'label' => __( 'Head Alignment', 'happy-elementor-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'separator' => 'before',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'happy-elementor-addons' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'happy-elementor-addons' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'happy-elementor-addons' ),
						'icon' => 'fa fa-align-right',
					]
				],
				'selectors' => [
					'{{WRAPPER}} .ha-advanced-table__head-column-cell' => 'text-align: {{VALUE}}'
				]
			]
		);

		$this->add_responsive_control(
			'row_align',
			[
				'label' => __( 'Row Alignment', 'happy-elementor-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'happy-elementor-addons' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'happy-elementor-addons' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'happy-elementor-addons' ),
						'icon' => 'fa fa-align-right',
					]
				],
				'selectors' => [
					'{{WRAPPER}} .ha-advanced-table__body-row-cell' => 'text-align: {{VALUE}}'
				]
			]
		);

		$this->end_controls_section();
	}


	/**
	 * Register styles related controls
	 */
	protected function register_style_controls() {

		$this->start_controls_section(
			'_section_table_head',
			[
				'label' => __( 'Table Head', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'table_head_padding',
			[
				'label' => __( 'Padding', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ha-advanced-table__head .ha-advanced-table__head-column-cell' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'head_border',
				'selector' => '{{WRAPPER}} .ha-advanced-table__head .ha-advanced-table__head-column-cell',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'head_typography',
				'selector' => '{{WRAPPER}} .ha-advanced-table__head .ha-advanced-table__head-column-cell',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_control(
			'head_background_color',
			[
				'label' => __( 'Background Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-advanced-table__head .ha-advanced-table__head-column' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'head_color',
			[
				'label' => __( 'Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-advanced-table__head .ha-advanced-table__head-column-cell' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_table_row',
			[
				'label' => __( 'Table Row', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'table_row_padding',
			[
				'label' => __( 'Padding', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ha-advanced-table__body .ha-advanced-table__body-row-cell' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'row_border',
				'selector' => '{{WRAPPER}} .ha-advanced-table__body .ha-advanced-table__body-row-cell',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'row_typography',
				'selector' => '{{WRAPPER}} .ha-advanced-table__body .ha-advanced-table__body-row-cell',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->start_controls_tabs( '_tabs_rows' );
		$this->start_controls_tab(
			'_tab_head_row',
			[
				'label' => __( 'Normal', 'happy-elementor-addons' )
			]
		);

		$this->add_control(
			'row_background_color_even',
			[
				'label' => __( 'Background Color (Even)', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-advanced-table__body .ha-advanced-table__body-row:nth-child(even)' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'row_background_color_odd',
			[
				'label' => __( 'Background Color (Odd)', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-advanced-table__body .ha-advanced-table__body-row:nth-child(odd)' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'row_color_even',
			[
				'label' => __( 'Color (Even) ', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-advanced-table__body .ha-advanced-table__body-row:nth-child(even)' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'row_color_odd',
			[
				'label' => __( 'Color (Odd)', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-advanced-table__body .ha-advanced-table__body-row:nth-child(odd)' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_row',
			[
				'label' => __( 'Hover', 'happy-elementor-addons' )
			]
		);

		$this->add_control(
			'row_hover_background_color_even',
			[
				'label' => __( 'Background Color (Even)', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-advanced-table__body .ha-advanced-table__body-row:nth-child(even):hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'row_hover_background_color_odd',
			[
				'label' => __( 'Background Color (Odd)', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-advanced-table__body .ha-advanced-table__body-row:nth-child(odd):hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'row_hover_color_even',
			[
				'label' => __( 'Color (Even)', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-advanced-table__body .ha-advanced-table__body-row:nth-child(even):hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'row_hover_color_odd',
			[
				'label' => __( 'Color (Odd)', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-advanced-table__body .ha-advanced-table__body-row:nth-child(odd):hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$this->data_table_render( $this->get_id() );
	}

	protected function data_table_render($id) {
		$settings = $this->get_settings_for_display();
		?>

		<table class="ha-table">

			<thead class="ha-table__head">
				<tr class="ha-table__head-column">
					<th class="ha-table__head-column-cell"><?php echo esc_html( 'Column' ); ?></th>
				</tr>
			</thead>

			<tbody class="ha-table__body">
				<tr class="ha-table__body-row">
					<td class="ha-table__body-row-cell"><?php echo esc_html( 'Row' ); ?></td>
				</tr>
			</tbody>

		</table>

		<?php

	}

}
