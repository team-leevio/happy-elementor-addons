<?php
/**
 * Data Table
 *
 * @package Happy_Addons
 */

namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Repeater;
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
			'_section_table_column',
			[
				'label' => __( 'Table Head', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'column_name',
			[
				'label' => __( 'Title', 'happy-elementor-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Column Name', 'happy-elementor-addons' ),
				'default' => __( 'Column One', 'happy-elementor-addons' ),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$repeater->add_control(
			'column_span',
			[
				'label' => __( 'Col Span', 'happy-elementor-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 50,
				'step' => 1
			]
		);

		if ( ha_is_elementor_version( '<', '2.6.0' ) ) {
			$repeater->add_control(
				'column_icon',
				[
					'label' => __( 'Icon', 'happy-elementor-addons' ),
					'type' => Controls_Manager::ICON,
					'options' => ha_get_happy_icons()
				]
			);
		} else {
			$repeater->add_control(
				'column_icons',
				[
					'label' => __( 'Icon', 'happy-elementor-addons' ),
					'type' => Controls_Manager::ICONS,
					'fa4compatibility' => 'column_icon',
					'label_block' => true
				]
			);
		}

		$this->add_control(
			'columns_data',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ column_name }}}',
				'default' => [
					[
						'column_name' => __( 'WordPress', 'happy-elementor-addons' )
					],
					[
						'column_name' => __( 'Elementor', 'happy-elementor-addons' )
					],
					[
						'column_name' => __( 'Happy Addons', 'happy-elementor-addons' )
					],
				]
			]
		);

		$this->add_responsive_control(
			'head_align',
			[
				'label' => __( 'Alignment', 'happy-elementor-addons' ),
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
					'{{WRAPPER}} .ha-table__head-column-cell' => 'text-align: {{VALUE}}'
				]
			]
		);

		$this->add_responsive_control(
			'icon_position',
			[
				'label' => __( 'Icon Position', 'happy-elementor-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'happy-elementor-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'happy-elementor-addons' ),
						'icon' => 'eicon-h-align-right',
					],
					'top' => [
						'title' => __( 'Top', 'happy-elementor-addons' ),
						'icon' => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'happy-elementor-addons' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary' => [
					'right' => 'flex-direction: row',
					'left' => 'flex-direction: row-reverse',
					'bottom' => 'flex-direction: column',
					'top' => 'flex-direction: column-reverse',
				],
				'selectors' => [
					'{{WRAPPER}} .ha-table__head-column-cell-wrap' => '{{VALUE}}'
				]
			]
		);

		$this->add_responsive_control(
			'icon_alignment',
			[
				'label' => __( 'Icon Alignment', 'happy-elementor-addons' ),
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
					'{{WRAPPER}} .ha-table__head-column-cell-wrap' => 'text-align: {{VALUE}}'
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_table_row',
			[
				'label' => __( 'Row', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'row_column_type',
			[
				'label'   => __( 'Row/Column', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'row',
				'options' => [
					'row' => __( 'Row', 'happy-elementor-addons' ),
					'column' => __( 'Column', 'happy-elementor-addons' ),
				],
			]
		);

		$this->add_control(
			'row_starts',
			[
				'label' => false,
				'type' => Controls_Manager::HIDDEN,
				'default' => __( 'Row Starts', 'happy-elementor-addons' ),
				'condition' => [
					'row_column_type' => 'row'
				],
			]
		);

		$repeater->add_control(
			'cell_name',
			[
				'label' => __( 'Title', 'happy-elementor-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Cell Name', 'happy-elementor-addons' ),
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'row_column_type' => 'column'
				],
			]
		);

		$repeater->add_control(
			'row_column_span',
			[
				'label' => __( 'Col Span', 'happy-elementor-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 50,
				'step' => 1,
				'condition' => [
					'row_column_type' => 'column'
				],
			]
		);

		$repeater->add_control(
			'row_span',
			[
				'label' => __( 'Row Span', 'happy-elementor-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 50,
				'step' => 1,
				'condition' => [
					'row_column_type' => 'column'
				],
			]
		);

		if ( ha_is_elementor_version( '<', '2.6.0' ) ) {
			$repeater->add_control(
				'row_icon',
				[
					'label' => __( 'Icon', 'happy-elementor-addons' ),
					'type' => Controls_Manager::ICON,
					'options' => ha_get_happy_icons()
				]
			);
		} else {
			$repeater->add_control(
				'row_icons',
				[
					'label' => __( 'Icon', 'happy-elementor-addons' ),
					'type' => Controls_Manager::ICONS,
					'fa4compatibility' => 'row_icon',
					'label_block' => true
				]
			);
		}

		$this->add_control(
			'rows_data',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '<# print( (row_column_type == "column" ) ? cell_name : ("Row Starts") ) #>',
				'default' => [
					[
						'row_column_type' => 'row',
						'row_starts' => __( 'Row Starts', 'happy-elementor-addons' ),
					],
					[
						'row_column_type' => 'column',
						'cell_name' => __( 'Stay Happy', 'happy-elementor-addons' )
					],
					[
						'row_column_type' => 'column',
						'cell_name' => __( 'Stay Safe', 'happy-elementor-addons' )
					],
					[
						'row_column_type' => 'column',
						'cell_name' => __( 'Spread Happiness', 'happy-elementor-addons' )
					],
				]
			]
		);

		$this->add_responsive_control(
			'row_align',
			[
				'label' => __( 'Alignment', 'happy-elementor-addons' ),
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
					'{{WRAPPER}} .ha-table__body-row-cell' => 'text-align: {{VALUE}}'
				]
			]
		);

		$this->add_responsive_control(
			'row_icon_position',
			[
				'label' => __( 'Icon Position', 'happy-elementor-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'happy-elementor-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'happy-elementor-addons' ),
						'icon' => 'eicon-h-align-right',
					],
					'top' => [
						'title' => __( 'Top', 'happy-elementor-addons' ),
						'icon' => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'happy-elementor-addons' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary' => [
					'right' => 'flex-direction: row',
					'left' => 'flex-direction: row-reverse',
					'bottom' => 'flex-direction: column',
					'top' => 'flex-direction: column-reverse',
				],
				'selectors' => [
					'{{WRAPPER}} .ha-table__body-row-cell-wrap' => '{{VALUE}}'
				]
			]
		);

		$this->add_responsive_control(
			'row_icon_alignment',
			[
				'label' => __( 'Icon Alignment', 'happy-elementor-addons' ),
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
					'{{WRAPPER}} .ha-table__body-row-cell' => 'text-align: {{VALUE}}'
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
					'{{WRAPPER}} .ha-table__head .ha-table__head-column-cell' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'head_border',
				'selector' => '{{WRAPPER}} .ha-table__head .ha-table__head-column-cell',
			]
		);

		$this->add_control(
			'head_background_color',
			[
				'label' => __( 'Background Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-table__head .ha-table__head-column-cell' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'_heading_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Title', 'happy-elementor-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'head_typography',
				'selector' => '{{WRAPPER}} .ha-table__head .ha-table__head-column-cell-text',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_control(
			'head_text_color',
			[
				'label' => __( 'Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-table__head .ha-table__head-column-cell-text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'_heading_icon',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Icon', 'happy-elementor-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label' => __( 'Spacing', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ha-table__head .ha-table__head-column-cell-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'head_icon',
			[
				'label' => __( 'Icon Size', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .ha-table__head .ha-table__head-column-cell-icon' => 'font-size: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_table_row_style',
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
					'{{WRAPPER}} .ha-table__body .ha-table__body-row-cell' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'row_border',
				'selector' => '{{WRAPPER}} .ha-table__body .ha-table__body-row-cell',
			]
		);

		$this->add_control(
			'_row_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Title', 'happy-elementor-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'row_text_typography',
				'selector' => '{{WRAPPER}} .ha-table__body .ha-table__body-row-cell-text',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_control(
			'row_text_color',
			[
				'label' => __( 'Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-table__body .ha-table__body-row-cell-text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'_row_icon',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Icon', 'happy-elementor-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'row_icon_spacing',
			[
				'label' => __( 'Spacing', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ha-table__body .ha-table__body-row-cell-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'row_icon_size',
			[
				'label' => __( 'Icon Size', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .ha-table__body .ha-table__body-row-cell-icon' => 'font-size: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_control(
			'row_icon_color',
			[
				'label' => __( 'Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-table__body .ha-table__body-row-cell-icon' => 'color: {{VALUE}}',
				],
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
					'{{WRAPPER}} .ha-table__body .ha-table__body-row:nth-child(even)' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'row_background_color_odd',
			[
				'label' => __( 'Background Color (Odd)', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-table__body .ha-table__body-row:nth-child(odd)' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'row_color_even',
			[
				'label' => __( 'Color (Even) ', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-table__body .ha-table__body-row:nth-child(even)' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'row_color_odd',
			[
				'label' => __( 'Color (Odd)', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-table__body .ha-table__body-row:nth-child(odd)' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .ha-table__body .ha-table__body-row:nth-child(even):hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'row_hover_background_color_odd',
			[
				'label' => __( 'Background Color (Odd)', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-table__body .ha-table__body-row:nth-child(odd):hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'row_hover_color_even',
			[
				'label' => __( 'Color (Even)', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-table__body .ha-table__body-row:nth-child(even):hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'row_hover_color_odd',
			[
				'label' => __( 'Color (Odd)', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-table__body .ha-table__body-row:nth-child(odd):hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$this->data_table_render();
	}

	protected function data_table_render() {
		$settings = $this->get_settings_for_display();

		$table_row = [];
		$table_cell = [];
		foreach ( $settings['rows_data'] as $row ) {
			$row_id = uniqid();

			if ( $row['row_column_type'] == 'row' ) {
				$table_row[] = [
					'id' => $row_id,
					'type' => $row['row_column_type'],
				];
			}

			if ( $row['row_column_type'] == 'column' ) {
				$table_row_keys = array_keys( $table_row );
				$cell_key = end($table_row_keys );

				$table_cell[] = [
					'row_id' => $table_row[$cell_key]['id'],
					'title' => $row['cell_name'],
					'row_span' => $row['row_span'],
					'row_column_span' => $row['row_column_span'],
					'row_icon' => ! empty( $row['row_icon'] ) ? $row['row_icon'] : '',
					'row_icons' => ! empty( $row['row_icons']['value'] ) ? $row['row_icons'] : '',
				];
			}

		}

//		echo '<pre>';
//		print_r($table_cell);
//		echo '</pre>';
		?>

		<table class="ha-table">

			<thead class="ha-table__head">
				<tr class="ha-table__head-column">
					<?php foreach ( $settings['columns_data'] as $index => $column_cell ) :
						$column_repeater_key = $this->get_repeater_setting_key( 'column_span', 'columns_data', $index );
						$this->add_render_attribute( $column_repeater_key, 'class', 'ha-table__head-column-cell' );
						if ( $column_cell['column_span'] ) {
							$this->add_render_attribute( $column_repeater_key, 'colspan', $column_cell['column_span'] );
						}
						?>
						<th <?php echo $this->get_render_attribute_string( $column_repeater_key ); ?>>
							<div class="ha-table__head-column-cell-wrap">
								<div class="ha-table__head-column-cell-text"><?php echo esc_html( $column_cell['column_name'] ); ?></div>
									<?php if ( ha_is_elementor_version( '>', '2.6.0' ) && ! empty( $column_cell['column_icons'] ) ) : ?>
										<div class="ha-table__head-column-cell-icon">
											<?php Icons_Manager::render_icon( $column_cell['column_icons'] ); ?>
										</div>
									<?php endif; ?>

									<?php if ( ha_is_elementor_version( '<', '2.6.0' ) && ! empty( $column_cell['column_icon'] ) ) : ?>
										<div class="ha-table__head-column-cell-icon">
											<i class="<?php echo esc_attr( $column_cell['icon'] ); ?>"></i>
										</div>
									<?php endif; ?>
							</div>
						</th>
					<?php endforeach; ?>
				</tr>
			</thead>

			<tbody class="ha-table__body">
				<?php for ( $i = 0; $i < count( $table_row ); $i++ ) : ?>
					<tr class="ha-table__body-row">
						<?php
						for ( $j = 0; $j < count( $table_cell ); $j++ ) :
							if( $table_row[$i]['id'] == $table_cell[$j]['row_id'] ) :
								$row_repeater_key = $this->get_repeater_setting_key( 'row_span', 'rows_data', $table_cell[$j]['row_id'].$i.$j );
								$this->add_render_attribute( $row_repeater_key, 'class', 'ha-table__body-row-cell' );
								if ( ! empty( $table_cell[$j]['row_column_span'] ) ) {
									$this->add_render_attribute( $row_repeater_key, 'colspan', $table_cell[$j]['row_column_span'] );
								}
								if ( ! empty( $table_cell[$j]['row_span'] ) ) {
									$this->add_render_attribute( $row_repeater_key, 'rowspan', $table_cell[$j]['row_span'] );
								}
							?>
								<td <?php echo $this->get_render_attribute_string( $row_repeater_key ); ?>>
									<div class="ha-table__body-row-cell-wrap">
										<div class="ha-table__body-row-cell-text"><?php echo esc_html( $table_cell[$j]['title'] ); ?></div>

										<?php if ( ha_is_elementor_version( '>', '2.6.0' ) && ! empty( $table_cell[$j]['row_icons'] ) ) : ?>
											<div class="ha-table__body-row-cell-icon">
												<?php Icons_Manager::render_icon( $table_cell[$j]['row_icons'] ); ?>
											</div>
										<?php endif; ?>

										<?php if ( ha_is_elementor_version( '<', '2.6.0' ) && ! empty( $table_cell[$j]['row_icon'] ) ) : ?>
											<div class="ha-table__body-row-cell-icon">
												<i class="<?php echo esc_attr( $table_cell[$j]['row_icon'] ); ?>"></i>
											</div>
										<?php endif; ?>
									</div>
								</td>
							<?php
							endif;
						endfor;
						?>
					</tr>
				<?php endfor; ?>
			</tbody>

		</table>

		<?php

	}

}
