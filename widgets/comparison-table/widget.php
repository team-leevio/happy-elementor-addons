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
				'default' => __( 'Column One', 'happy-elementor-addons' ),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$repeater->add_control(
			'column_span',
			[
				'label' => __( 'Column Widthd', 'happy-elementor-addons' ),
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
					'image' => [
						'title' => __( 'Image', 'happy-elementor-addons' ),
						'icon' => 'eicon-image-bold',
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
            'column_image',
            [
                'label' => __( 'Image', 'happy-elementor-addons' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'column_media' => 'image'
                ]
            ]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'column_thumbnail',
				'default' => 'thumbnail',
				'separator' => 'none',
				'condition' => [
                    'column_media' => 'image'
                ]
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
					'{{WRAPPER}} {{CURRENT_ITEM}} .ha-table__head-column-cell-icon i' => 'color: {{VALUE}}',
				],
			]
		);

		

		$this->add_control(
			'columns_data',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ column_name }}}',
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
				'default' => 'center',
				'toggle' => false,
				'prefix_class' => 'ha-column-alignment-',
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
				'default' => 'right',
				'toggle' => false,
				'prefix_class' => 'ha-column-icon-'
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
			'column_content_type',
			[
				'label'   => __( 'Column Content Type', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'text',
				'options' => [
					'text' => __( 'Text', 'happy-elementor-addons' ),
					'icon' => __( 'Icon', 'happy-elementor-addons' ),
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
					'column_content_type' => 'text'
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
					'column_content_type' => 'icon'
				],
			]
		);


		$this->add_control(
			'rows_data',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'column_content_type' => 'text',
						'column_text' => __( 'Ready Blocks', 'happy-elementor-addons' )
					],
					[
						'column_content_type' => 'icon',
						'column_icon' => [
							'value' => 'hm hm-cross-circle',
							'library' => 'happy-icons',
						]
					],
					[
						'column_content_type' => 'icon',
						'column_icon' => [
							'value' => 'hm hm-tick-circle',
							'library' => 'happy-icons',
						]
					],
					[
						'column_content_type' => 'text',
						'column_text' => __( 'Ready Blocks', 'happy-elementor-addons' )
					],
					[
						'column_content_type' => 'icon',
						'column_icon' => [
							'value' => 'hm hm-cross-circle',
							'library' => 'happy-icons',
						]
					],
					[
						'column_content_type' => 'icon',
						'column_icon' => [
							'value' => 'hm hm-tick-circle',
							'library' => 'happy-icons',
						]
					],
				],
				'title_field' => '{{{ column_content_type == "text"  ? column_text : elementor.helpers.renderIcon( this, column_icon, {}, "i", "panel" ) || \'<i class="{{ icon }}" aria-hidden="true"></i>\' Icon }}}',
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

    }

    protected function render() {
        
    }
}


