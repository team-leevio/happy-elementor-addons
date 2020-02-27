<?php
/**
 * Post Tab widget class
 *
 * @package Happy_Addons
 */

namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Background;
use Happy_Addons\Elementor\Controls\Select2;

defined( 'ABSPATH' ) || die();

class Post_Tab extends Base {

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title () {
		return __( 'Post Tab', 'happy-elementor-addons' );
	}

	public function get_custom_help_url () {
		return 'https://happyaddons.com/docs/happy-addons-for-elementor/widgets/post-tab/';
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon () {
		return 'hm hm-post-tab';
	}

	public function get_keywords () {
		return [ 'posts', 'post', 'post-tab', 'tab', 'news' ];
	}

	/**
	 * Get a list of All Post Types
	 *
	 * @return array
	 */
	public static function get_post_types () {
		$diff_key = [
			'elementor_library' => '',
			'attachment' => '',
			'page' => ''
		];
		$post_types = ha_get_post_types( [], $diff_key );
		$some = [
			'test' => 'Test'
		];
		$post_types = array_merge( $post_types, $some );
		return $post_types;
	}

	public static function get_taxonomies ( $post_type = '' ) {
		//$taxonomies = ha_get_taxonomies([ 'public' => true ], 'object', true);
		$list = [];
		if ( $post_type ) {
			$tax = ha_get_taxonomies( [ 'public' => true, "object_type" => [ $post_type ] ], 'object', true );
			$list[$post_type] = count( $tax ) !== 0 ? $tax : '';
			//$list[$post_type] = count($tax) !== 0 ? $tax : ['' => __( 'No Taxonomy Found', 'happy-elementor-addons' )] ;
		} else {
			$list = ha_get_taxonomies( [ 'public' => true ], 'object', true );
		}
		return $list;
	}

	protected function register_content_controls () {
		$this->start_controls_section(
			'_section_post_tab_query',
			[
				'label' => __( 'Query', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'post_type',
			[
				'label' => __( 'Source', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_post_types(),
				'default' => key( $this->get_post_types() ),
			]
		);

		/*$this->add_control(
			'tax_type',
			[
				'label' => __( 'Taxonomies Type', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_taxonomies(),
				'default' => key( $this->get_taxonomies() ),
			]
		);*/

		foreach ( self::get_post_types() as $key => $value ) {
			$taxonomy = self::get_taxonomies( $key );
			if ( ! $taxonomy[$key] )
				continue;
			$this->add_control(
				'tax_type_' . $key,
				[
					'label' => __( 'Taxonomies', 'happy-elementor-addons' ),
					'type' => Controls_Manager::SELECT,
					'options' => $taxonomy[$key],
					//'options' => $this->get_taxonomies($key)[$key],
					'default' => key( $taxonomy[$key] ),
					'condition' => [
						'post_type' => $key
					],
				]
			);

			/*$this->add_control(
				'tax_type_'.$key,
				[
					'label' => __( 'Select ', 'happy-elementor-addons' ) . $value,
					'label_block' => false,
					'type' => Select2::TYPE,
					'multiple' => false,
					//'mininput' => '1',
					'placeholder' => 'Search ' . $value,
					'data_options' => [
						'post_type' => $key,
						'action' => 'ha_post_tab_query'
					],
					'condition' => [
						'post_type' => $key
					],
				]
			);*/
			//}

			foreach ( $taxonomy[$key] as $tax_key => $tax_value ) {

				$this->add_control(
					'tax_ids_' . $tax_key,
					[
						'label' => __( 'Select ', 'happy-elementor-addons' ) . $tax_value,
						'label_block' => true,
						'type' => Select2::TYPE,
						'multiple' => true,
						//'mininput' => '1',
						'placeholder' => 'Search ' . $tax_value,
						'data_options' => [
							'tax_id' => $tax_key,
							'action' => 'ha_post_tab_query'
						],
						'condition' => [
							'post_type' => $key,
							'tax_type_' . $key => $tax_key
						],
					]
				);
			}
		}

		$this->add_control(
			'item_limit',
			[
				'label' => __( 'Item Limit', 'happy-elementor-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
				'dynamic' => [ 'active' => true ],
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' => __( 'Order By', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'ID' => __( 'Post ID', 'happy-elementor-addons' ),
					'author' => __( 'Post Author', 'happy-elementor-addons' ),
					'title' => __( 'Title', 'happy-elementor-addons' ),
					'date' => __( 'Date', 'happy-elementor-addons' ),
					'modified' => __( 'Last Modified Date', 'happy-elementor-addons' ),
					'parent' => __( 'Parent Id', 'happy-elementor-addons' ),
					'rand' => __( 'Random', 'happy-elementor-addons' ),
					'comment_count' => __( 'Comment Count', 'happy-elementor-addons' ),
					'menu_order' => __( 'Menu Order', 'happy-elementor-addons' ),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label' => __( 'Order', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => [
					'asc' => __( 'ASC', 'happy-elementor-addons' ),
					'desc' => __( 'DESC', 'happy-elementor-addons' ),
				],
			]
		);

		$this->end_controls_section();

		//Settings
		$this->start_controls_section(
			'_section_settings',
			[
				'label' => __( 'Settings', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'Layout', 'happy-elementor-addons' ),
				'label_block' => false,
				'type' => Controls_Manager::CHOOSE,
				'default' => 'list',
				'options' => [
					'list' => [
						'title' => __( 'List', 'happy-elementor-addons' ),
						'icon' => 'eicon-editor-list-ul',
					],
					'inline' => [
						'title' => __( 'Inline', 'happy-elementor-addons' ),
						'icon' => 'eicon-ellipsis-h',
					],
				],
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'feature_image',
			[
				'label' => __( 'Featured Image', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'happy-elementor-addons' ),
				'label_off' => __( 'Hide', 'happy-elementor-addons' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'post_image',
				'default' => 'thumbnail',
				'exclude' => [
					'custom'
				],
				/*'condition' => [
					'feature_image' => 'yes'
				]*/
			]
		);

		$this->add_control(
			'list_icon',
			[
				'label' => __( 'List Icon', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'happy-elementor-addons' ),
				'label_off' => __( 'Hide', 'happy-elementor-addons' ),
				'return_value' => 'yes',
				'default' => 'yes',
				/*'condition' => [
					'feature_image!' => 'yes'
				]*/
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => __( 'Title HTML Tag', 'happy-elementor-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'h1' => [
						'title' => __( 'H1', 'happy-elementor-addons' ),
						'icon' => 'eicon-editor-h1'
					],
					'h2' => [
						'title' => __( 'H2', 'happy-elementor-addons' ),
						'icon' => 'eicon-editor-h2'
					],
					'h3' => [
						'title' => __( 'H3', 'happy-elementor-addons' ),
						'icon' => 'eicon-editor-h3'
					],
					'h4' => [
						'title' => __( 'H4', 'happy-elementor-addons' ),
						'icon' => 'eicon-editor-h4'
					],
					'h5' => [
						'title' => __( 'H5', 'happy-elementor-addons' ),
						'icon' => 'eicon-editor-h5'
					],
					'h6' => [
						'title' => __( 'H6', 'happy-elementor-addons' ),
						'icon' => 'eicon-editor-h6'
					]
				],
				'default' => 'h2',
				'toggle' => false,
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_controls () {

		$this->start_controls_section(
			'_section_post_tab_style',
			[
				'label' => __( 'List', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'list_item_common',
			[
				'label' => __( 'Common', 'happy-elementor-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'list_item_margin',
			[
				'label' => __( 'Margin', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ha-post-list .ha-post-list-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'list_item_padding',
			[
				'label' => __( 'Padding', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ha-post-list .ha-post-list-item a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'list_item_background',
				'label' => __( 'Background', 'happy-elementor-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ha-post-list .ha-post-list-item',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'list_item_box_shadow',
				'label' => __( 'Box Shadow', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-post-list .ha-post-list-item',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'list_item_border',
				'label' => __( 'Border', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-post-list .ha-post-list-item',
			]
		);

		$this->add_responsive_control(
			'list_item_border_radius',
			[
				'label' => __( 'Border Radius', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ha-post-list .ha-post-list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'advance_style',
			[
				'label' => __( 'Advance Style', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'happy-elementor-addons' ),
				'label_off' => __( 'Off', 'happy-elementor-addons' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_responsive_control(
			'list_item_first',
			[
				'label' => __( 'First Item', 'happy-elementor-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'advance_style' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'list_item_first_child_margin',
			[
				'label' => __( 'Margin', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ha-post-list .ha-post-list-item:first-child' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'advance_style' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'list_item_first_child_border',
				'label' => __( 'Border', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-post-list .ha-post-list-item:first-child',
				'condition' => [
					'advance_style' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'list_item_last',
			[
				'label' => __( 'Last Item', 'happy-elementor-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'advance_style' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'list_item_last_child_margin',
			[
				'label' => __( 'Margin', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ha-post-list .ha-post-list-item:last-child' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'advance_style' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'list_item_last_child_border',
				'label' => __( 'Border', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-post-list .ha-post-list-item:last-child',
				'condition' => [
					'advance_style' => 'yes',
				]
			]
		);

		$this->end_controls_section();
		//Title Style
		$this->start_controls_section(
			'_section_post_tab_title_style',
			[
				'label' => __( 'Title', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'happy-elementor-addons' ),
				'scheme' => Schemes\Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .ha-post-list-title',
			]
		);

		$this->start_controls_tabs( 'title_tabs' );
		$this->start_controls_tab(
			'title_normal_tab',
			[
				'label' => __( 'Normal', 'happy-elementor-addons' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-post-list-title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'title_hover_tab',
			[
				'label' => __( 'Hover', 'happy-elementor-addons' ),
			]
		);

		$this->add_control(
			'title_hvr_color',
			[
				'label' => __( 'Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-post-list .ha-post-list-item a:hover .ha-post-list-title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
		//List Icon Style
		$this->start_controls_section(
			'_section_list_icon_feature_iamge_style',
			[
				'label' => __( 'Icon & Feature Image', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'feature_image',
							'operator' => '==',
							'value' => 'yes',
						],
						[
							'name' => 'list_icon',
							'operator' => '==',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} span.ha-post-list-icon' => 'color: {{VALUE}};',
				],
				'condition' => [
					'feature_image!' => 'yes',
					'list_icon' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Font Size', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} span.ha-post-list-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'feature_image!' => 'yes',
					'list_icon' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'icon_line_height',
			[
				'label' => __( 'Line Height', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} span.ha-post-list-icon' => 'line-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'feature_image!' => 'yes',
					'list_icon' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label' => __( 'Image Width', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ha-post-list-item a img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'feature_image' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_boder',
				'label' => __( 'Border', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-post-list-item a img',
				'condition' => [
					'feature_image' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'image_boder_radius',
			[
				'label' => __( 'Border Radius', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ha-post-list-item a img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'feature_image' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'icon_margin_right',
			[
				'label' => __( 'Margin Right', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} span.ha-post-list-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ha-post-list-item a img' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		//List Meta Style
		$this->start_controls_section(
			'_section_list_meta_style',
			[
				'label' => __( 'Meta', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'meta' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'meta_typography',
				'label' => __( 'Typography', 'happy-elementor-addons' ),
				'scheme' => Schemes\Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .ha-post-list-meta-wrap span',
			]
		);

		$this->add_control(
			'meta_color',
			[
				'label' => __( 'Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ha-post-list-meta-wrap span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'meta_space',
			[
				'label' => __( 'Space Between', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .ha-post-list-meta-wrap span' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ha-post-list-meta-wrap span:last-child' => 'margin-right: 0;',
				],
			]
		);

		$this->add_responsive_control(
			'meta_box_margin',
			[
				'label' => __( 'Margin', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ha-post-list-meta-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'meta_icon_heading',
			[
				'label' => __( 'Meta Icon', 'happy-elementor-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'meta_icon_color',
			[
				'label' => __( 'Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ha-post-list-meta-wrap span i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'meta_icon_space',
			[
				'label' => __( 'Space Between', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .ha-post-list-meta-wrap span i' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render () {

		$settings = $this->get_settings_for_display();
		$terms = get_terms( array(
			'search' => 'b',
			'taxonomy' => 'category',
			'hide_empty' => false,
			'include' => [ 2, 3, 6 ],
		) );
		$data = [];
		foreach ( $terms as $value ) {
			$data[] = [
				'id' => $value->term_id,
				'text' => $value->name,
			];
		}
		echo '<pre>';
		var_dump( self::get_taxonomies( 'movies' ) );
		//var_dump( ha_get_taxonomies([ 'public' => true, "object_type"=> ['movies'] ], 'object', false) );
		//var_dump( ha_get_post_types( [], [ 'elementor_library'=>'', 'attachment'=>'','post'=>'' ] ) );
		//var_dump( $data );
		echo '</pre>';
		//return;
		if ( ! $settings['post_type'] || ! post_type_exists( $settings['post_type'] ) )
			return;

		$taxonomy = $settings['tax_type_' . $settings['post_type']];
		$terms_ids = $settings['tax_ids_' . $taxonomy];
		var_dump( $taxonomy, $settings['tax_ids_' . $taxonomy] );
		//return;
		$args = [
			'post_status' => 'publish',
			'post_type' => $settings['post_type'],
			'posts_per_page' => -1,
			/*'tax_query' => array(
				array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $terms_ids,
				),
			),*/
		];
		$args['tax_query'] = array(
			array(
				'taxonomy' => $taxonomy,
				'field' => 'term_id',
				'terms' => $terms_ids[0],
			),
		);


		$customize_title = [];
		$ids = [];
		if ( false ) {
			$args['posts_per_page'] = -1;
			$lists = $settings['selected_list_' . $settings['post_type']];
			if ( ! empty( $lists ) ) {
				foreach ( $lists as $index => $value ) {
					$ids[] = $value['post_id'];
					if ( $value['title'] ) $customize_title[$value['post_id']] = $value['title'];
				}
			}
			$args['post__in'] = (array) $ids;
			$args['orderby'] = 'post__in';
		}

		if ( false ) {
			$posts = [];
		} else {
			$posts = get_posts( $args );
		}

		$arg = [
			'taxonomy' => $taxonomy,
			//'taxonomy' => 'movies_cat',
			'hide_empty' => true,
			'include' => $terms_ids,
		];
		$tab_list = get_terms( $arg );

		echo '<pre>';

		foreach ( $tab_list as $list ) {
			var_dump( $list->name );
		}
		foreach ( $posts as $post ) {
			var_dump( $post->post_title );
		}
		echo '</pre>';

		$this->add_render_attribute( 'wrapper', 'class', [ 'ha-post-tab-wrapper' ] );
		$this->add_render_attribute( 'tab-list', 'class', [ 'ha-post-tab-list' ] );
		$this->add_render_attribute( 'tab-body', 'class', [ 'ha-post-tab-body' ] );
		// if ( 'inline' === $settings['view'] ) {
		// 	$this->add_render_attribute( 'wrapper-inner', 'class', [ 'ha-post-list-inline' ] );
		// }
		$this->add_render_attribute( 'item', 'class', [ 'ha-post-list-item' ] );
		$i = 1;
		if ( count( $posts ) !== 0 ) :?>
			<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
				<ul <?php //$this->print_render_attribute_string( 'tab-list' );
				?> >
					<?php
					/*
					$this->print_render_attribute_string( 'item' );
					echo esc_url( get_the_permalink( $post->ID ) );
					echo get_the_post_thumbnail( $post->ID, $settings['post_image_size'] );
					Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
					$title = $post->post_title;
					tag_escape( $settings['title_tag'] )
					echo esc_html( get_the_author_meta( 'display_name', $post->post_author ) );
					echo get_the_date( "M d, Y" );
					$categories = get_the_category( $post->ID );
					echo esc_html( $categories[0]->name );
					printf( '<%1$s %2$s>%3$s</%1$s>',
						tag_escape( $settings['title_tag'] ),
						'class="ha-post-list-title"',
						esc_html( $title )
					);
					*/
					?>

				</ul>

				<ul <?php $this->print_render_attribute_string( 'tab-list' ); ?>>
					<?php foreach ( $tab_list as $list ): ?>
						<li><?php echo esc_html( $list->name ); ?></li>
					<?php endforeach; ?>
				</ul>

				<div <?php $this->print_render_attribute_string( 'tab-body' ); ?>>
					<?php foreach ( $posts as $post ): ?>
						<h4 class="ha-post-tab-title">
							<?php echo esc_html( $post->post_title ); ?>
						</h4>
					<?php if( $i===$settings['item_limit'] ){break;}$i++; endforeach; ?>
				</div>

			</div>
		<?php
		else:
			printf( '%1$s %2$s %3$s',
				__( 'No ', 'happy-elementor-addons' ),
				esc_html( $settings['post_type'] ),
				__( 'Found', 'happy-elementor-addons' )
			);
		endif;
	}
}
