<?php

/**
 * Content Switcher widget class
 *
 * @package Happy_Addons
 */

namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Core\Schemes\Typography;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;



defined('ABSPATH') || die();

class Comparison_Table extends Base {
    /**
	 * Get widget title.
	 *
	 * @since 2.24.2
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __('Comparison Table', 'happy-elementor-addons');
	}

    public function get_custom_help_url() {
		// return 'https://happyaddons.com/docs/happy-addons-for-elementor/widgets/';
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
		return 'hm hm-table-lamp';
	}

    /**
	 * Get widget keywords.
	 *
	 * Retrieve the widget keywords.
	 *
	 * @since 1.0.10
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
    public function get_keywords() {
		return ['comparison table', 'table', 'comparison'];
	}

    /**
     * Register widget content controls
     */
	protected function register_content_controls() {
        $this->__table_head_content_controls();
		$this->__table_row_content_controls();
    }

	protected function __table_head_content_controls() {

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
				'default' => __( 'Column %s', 'happy-elementor-addons' ),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$repeater->add_control(
			'column_span',
			[
				'label' => __( 'Column Width', 'happy-elementor-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 50,
				'step' => 1
			]
		);

		$repeater->add_responsive_control(
			'column_media',
			[
				'label' => __( 'Media', 'happy-elementor-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'toggle' => false,
				'default' => 'none',
				'options' => [
					'none' => [
						'title' => __( 'None', 'happy-elementor-addons' ),
						'icon' => 'eicon-editor-close',
					],
					'icon' => [
						'title' => __( 'Icon', 'happy-elementor-addons' ),
						'icon' => 'eicon-info-circle',
					],
				]
			]
		);

		$repeater->add_control(
			'column_icons',
			[
				'label' => __( 'Icon', 'happy-elementor-addons' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'column_icon',
				'label_block' => true,
				'condition' => [
					'column_media' => 'icon'
				],
			]
		);		

		$repeater->add_control(
			'head_custom_color',
			[
				'label' => __( 'Icon Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'column_media' => 'icon'
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .ha-comparison-table__head-column-cell-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .ha-comparison-table__head-column-cell-icon svg' => 'fill: {{VALUE}}',
				],
			]
		);

		

		$this->add_control(
			'columns_data',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ column_name }}}',
				'item_actions' => [
					'sort' => false,
				],
				'default' => [
					[
						'column_name' => __( 'Features', 'happy-elementor-addons' )
					],
					[
						'column_name' => __( 'Free', 'happy-elementor-addons' )
					],
					[
						'column_name' => __( 'Pro', 'happy-elementor-addons' )
					],
				],
				'prevent_empty' => false,
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
				'default' => 'left',
				'toggle' => false,
				'prefix_class' => 'ha-comparison-alignment-',
				'selectors' => [
					'{{WRAPPER}} .ha-comparison-table__head' => 'text-align: {{VALUE}}'
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
				'default' => 'right',
				'toggle' => false,
				'prefix_class' => 'ha-comparison-icon-'
			]
		);

		$this->end_controls_section();
	}

	protected function __table_row_content_controls() {

		$this->start_controls_section(
			'_section_table_row',
			[
				'label' => __( 'Table Row', 'happy-elementor-addons' ),
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

		$repeater->add_control(
			'column_content_type',
			[
				'label'   => __( 'Column Content Type', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'text',
				'options' => [
					'text' => __( 'Text', 'happy-elementor-addons' ),
					'icon' => __( 'Icon', 'happy-elementor-addons' ),
				],
				'condition' => [
					'row_column_type!' => 'row'
				],
			]
		);

		$repeater->add_control(
			'column_text',
			[
				'label' => __( 'Title', 'happy-elementor-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( '', 'happy-elementor-addons' ),
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'column_content_type' => 'text',
					'row_column_type!' => 'row'
				],
			]
		);

		$repeater->add_control(
			'column_icon',
			[
				'label' => __( 'Icon', 'happy-elementor-addons' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => true,
				'default' => [
					'value' => 'hm hm-cross-circle',
					'library' => 'happy-icons',
				],
				'condition' => [
					'column_content_type' => 'icon',
					'row_column_type' => 'column'
				],
			]
		);


		$this->add_control(
			'rows_data',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'item_actions' => [
					'sort' => true,
				],
				'default' => [
					[
						'row_column_type' => 'row',
					],
					[	
						'row_column_type' => 'column',
						'column_content_type' => 'text',
						'column_text' => __( 'Ready Blocks', 'happy-elementor-addons' )
					],
					[
						'row_column_type' => 'column',
						'column_content_type' => 'icon',
						'column_icon' => [
							'value' => 'hm hm-cross-circle',
							'library' => 'happy-icons',
						]
					],
					[
						'row_column_type' => 'column',
						'column_content_type' => 'icon',
						'column_icon' => [
							'value' => 'hm hm-tick-circle',
							'library' => 'happy-icons',
						]
					],
					[
						'row_column_type' => 'row',
					],
					[
						'row_column_type' => 'column',
						'column_content_type' => 'text',
						'column_text' => __( 'Ready Pages', 'happy-elementor-addons' )
					],
					[
						'row_column_type' => 'column',
						'column_content_type' => 'text',
						'column_text' => __( '150', 'happy-elementor-addons' )
					],
					[
						'row_column_type' => 'column',
						'column_content_type' => 'text',
						'column_text' => __( '250', 'happy-elementor-addons' )
					],
				],
				'title_field' => '{{{ row_column_type == "row" ? "Row Starts" : "" || column_content_type == "text"  ? column_text : elementor.helpers.renderIcon( this, column_icon, {}, "i", "panel" ) || \'<i class="{{ column_icon }}" aria-hidden="true"></i>\' || column_content_type == "icon" ? " Icon" : "" }}}'

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
				'default' => 'left',
				'toggle' => false,
				'prefix_class' => 'ha-row-alignment-',
				'selectors' => [
					'(desktop){{WRAPPER}} .ha-table__body-row-cell' => 'text-align: {{VALUE}}',
					'(tablet){{WRAPPER}} .ha-table__body-row-cell' => 'text-align: {{VALUE}}',
					'(mobile){{WRAPPER}} .ha-table__body-row-cell' => 'text-align: {{VALUE}}'
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
				'default' => 'right',
				'toggle' => false,
				'prefix_class' => 'ha-row-icon-'
			]
		);

		$this->end_controls_section();

	}

    /**
     * Register widget style controls
     */
	protected function register_style_controls() {
		$this->__table_head_stle_controls();
    }

	protected function __table_head_stle_controls() {

		$this->start_controls_section(
			'_section_table_head_style',
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
					'{{WRAPPER}} .ha-comparison-table-wrapper .ha-comparison-table__head' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'head_border_radius',
			[
				'label' => __( 'Border Radius', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'(desktop){{WRAPPER}} .ha-comparison-table-wrapper .ha-comparison-table__head' => 'border-top-left-radius: {{SIZE}}{{UNIT}};border-top-right-radius: {{SIZE}}{{UNIT}};',
					'(tablet){{WRAPPER}} .ha-comparison-table-wrapper .ha-comparison-table__head' => 'border-top-left-radius: {{SIZE}}{{UNIT}};border-top-right-radius: {{SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}} .ha-comparison-table-wrapper .ha-comparison-table__head' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'head_border',
				'selector' => '{{WRAPPER}} .ha-comparison-table-wrapper .ha-comparison-table__head',
			]
		);

		$this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'head_background_color',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .ha-comparison-table-wrapper .ha-comparison-table__head',
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
				'selector' => '{{WRAPPER}} .ha-comparison-table-wrapper .ha-comparison-table__head-item',
				'scheme' => Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_control(
			'head_text_color',
			[
				'label' => __( 'Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-comparison-table-wrapper  .ha-comparison-table__head-item' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .ha-comparison-table-wrapper .ha-table__head-column-cell-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'head_icon',
			[
				'label' => __( 'Icon Size', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .ha-comparison-table-wrapper .ha-table__head-column-cell-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ha-comparison-table-wrapper .ha-table__head-column-cell-icon svg' => 'width: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_control(
			'head_icon_color',
			[
				'label' => __( 'Icon Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-table__head-column-cell-icon i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'column_color_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => 'If you\'ve added <strong>Custom Style</strong> then Icon Color will be over written for that cell.',
			]
		);
		

		$this->end_controls_section();
	}

	protected function __table_row_style_controls() {
		
	}

    protected function render() {
        $settings = $this->get_settings_for_display();
		$columns_data = is_array($settings['columns_data'] ) ? $settings['columns_data'] : [];
		$rows_data = is_array($settings['rows_data'] ) ? $settings['rows_data'] : [];

		$table_row  = [];
		$table_cell = [];

		foreach ( $rows_data as $row ) {
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
					'repeater_id'        => $row['_id'],
					'row_id'             => isset($table_row[$cell_key]['id'])? $table_row[$cell_key]['id']: '',
					'title'              => $row['column_text'],
					'row_icons'          => ! empty( $row['column_icon']['value'] ) ? $row['column_icon'] : '',
				];
			}
		}
		// var_dump($rows_data);
		?>
		
		<div class="ha-comparison-table-wrapper">
			<div class="ha-comparison-table__head">
				<?php if ($columns_data): foreach ( $columns_data as $index => $head): 
					$column_repeater_key = $this->get_repeater_setting_key( 'column_span', 'columns_data', $index );
					$this->add_render_attribute( $column_repeater_key, 'class', [
						'ha-comparison-table__head-item',
						'elementor-repeater-item-' . $head['_id']
						] );

					?>
				<div <?php $this->print_render_attribute_string( $column_repeater_key ); ?>>
					<?php if( !empty( $head['column_icons'] ) ): ?>
					<div class="ha-comparison-table__head-column-cell-icon">
						<?php Icons_Manager::render_icon( $head['column_icons'] ); ?>
					</div>
					<?php endif; ?>
					
					<?php if( !empty( $head['column_name'])) : ?>
					<div class="ha-comparison-table__head-column-cell-icon">
						<?php echo ha_kses_basic($head['column_name']); ?>
					</div>
					<?php endif; ?>
				</div>
				<?php endforeach; endif; ?>
			</div>
			<div class="ha-comparison-table__row">
			<?php for ( $i = 0; $i < count( $table_row ); $i++ ) : ?>
				<div class="ha-comparison-table__row-item">
					<?php for ( $j = 0; $j < count( $table_cell ); $j++ ) : 
						if( $table_row[$i]['id'] == $table_cell[$j]['row_id'] ) :
						?>
						<div class="ha-comparison-table__row-item-cell">
							<?php if( ! empty( $table_cell[$j]['title'] ) ) : ?>
							<div class="ha-comparison-table__row-item-cell-title">
								<?php echo ha_kses_basic( $table_cell[$j]['title'] ); ?>
							</div>
							<?php endif; ?>
							<?php if ( ! empty( $table_cell[$j]['row_icons'] ) ) : ?>
								<div class="ha-comparison-table__row-cell-icon">
									<?php Icons_Manager::render_icon( $table_cell[$j]['row_icons'] ); ?>
								</div>
							<?php endif; ?>
						</div>
						
					<?php endif; endfor; ?>
				</div>
				<?php endfor; ?>
			</div>
		</div>
		
		

		<?php
    }
}


