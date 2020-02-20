<?php
/**
 * Post List widget class
 *
 * @package Happy_Addons
 */

namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Background;
use Happy_Addons\Elementor\Controls\Select2;

defined( 'ABSPATH' ) || die();

class Post_List extends Base {

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title () {
		return __( 'Post List', 'happy-elementor-addons' );
	}

	public function get_custom_help_url () {
		return 'https://happyaddons.com/docs/happy-addons-for-elementor/widgets/post-list/';
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
		return 'hm hm-post-list';
	}

	public function get_keywords () {
		return [ 'posts', 'post', 'post-list', 'list', 'news' ];
	}

	/**
	 * Get a list of all WPForms
	 *
	 * @return array
	 */
	public function ha_get_posts () {
		$posts = [];
		$_posts = get_posts( [
			'post_type' => 'post',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC',
		] );

		if ( ! empty( $_posts ) ) {
			$posts = wp_list_pluck( $_posts, 'post_title', 'ID' );
		}
		return $posts;

	}

	public function ha_get_post_types () {
		$post_types = get_post_types( [ 'public' => true, 'show_in_nav_menus' => true ], 'objects' );
		$post_types = wp_list_pluck( $post_types, 'label', 'name' );
		$extra = [
			'key' => 'Extar'
		];

		$post_types = array_merge( $extra, $post_types );

		return array_diff_key( $post_types, [ 'elementor_library', 'attachment' ] );
	}

	protected function register_content_controls () {
		$this->start_controls_section(
			'_section_list',
			[
				'label' => __( 'List', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'post_type',
			[
				'label' => __( 'Source', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->ha_get_post_types(),
				'default' => key( $this->ha_get_post_types() ),
			]
		);

		$this->add_control(
			'show_post_by',
			[
				'label' => __( 'Show post by:', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'selected',
				'options' => [
					'recent' => __( 'Recent Post', 'happy-elementor-addons' ),
					//'popular'          => __( 'Popular Post', 'happy-elementor-addons' ),
					'selected' => __( 'Selected Post', 'happy-elementor-addons' ),
				],

			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label' => __( 'Item Limit', 'happy-elementor-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'condition' => [
					'show_post_by' => [ 'recent' ]
				]
			]
		);

		$repeater = [];

		foreach ( $this->ha_get_post_types() as $key => $value ) {

			$repeater[$key] = new Repeater();

			$repeater[$key]->add_control(
				'title',
				[
					'label' => __( 'Title', 'happy-elementor-addons' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'placeholder' => __( 'Customize Title', 'happy-elementor-addons' ),
					'dynamic' => [
						'active' => true,
					],
				]
			);

			$repeater[$key]->add_control(
				'post_id',
				[
					'label' => __( 'Select ', 'happy-elementor-addons' ) . $value,
					'label_block' => true,
					'type' => Select2::TYPE,
					'multiple' => false,
					//'mininput' => '2',
					'placeholder' => 'Search ' . $value,
					'data_options' => [
						'post_type' => $key,
						'action' => 'ha_post_list_query'
					],
				]
			);

			$this->add_control(
				'selected_list_' . $key,
				[
					'label' => '',
					'type' => Controls_Manager::REPEATER,
					'fields' => $repeater[$key]->get_controls(),
					'title_field' => '{{ title }}',
					'condition' => [
						'show_post_by' => 'selected',
						'post_type' => $key
					]
				]
			);
		}


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
				'render_type' => 'template',
				'classes' => 'elementor-control-start-end',
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
                'condition' => [
	                'feature_image' => 'yes'
                ]
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
				'condition' => [
					'feature_image!' => 'yes'
				]
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'happy-elementor-addons' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => true,
				'default' => [
					'value' => 'far fa-circle',
					'library' => 'regular'
				],
				'condition' => [
					'list_icon' => 'yes',
					'feature_image!' => 'yes'
				]
			]
		);

		$this->add_control(
			'meta',
			[
				'label' => __( 'Show Meta', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'happy-elementor-addons' ),
				'label_off' => __( 'Hide', 'happy-elementor-addons' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'author_meta',
			[
				'label' => __( 'Author', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'happy-elementor-addons' ),
				'label_off' => __( 'Hide', 'happy-elementor-addons' ),
				'return_value' => 'yes',
				'default' => '',
				'condition' => [
					'meta' => 'yes',
				]
			]
		);

		$this->add_control(
			'author_icon',
			[
				'label' => __( 'Author Icon', 'happy-elementor-addons' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'icon icon-folder',
					'library' => 'ekiticons',
				],
				'condition' => [
					'meta' => 'yes',
					'author_meta' => 'yes',
				]
			]
		);

		$this->add_control(
			'date_meta',
			[
				'label' => __( 'Date', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'happy-elementor-addons' ),
				'label_off' => __( 'Hide', 'happy-elementor-addons' ),
				'return_value' => 'yes',
				'default' => '',
				'condition' => [
					'meta' => 'yes',
				]
			]
		);

		$this->add_control(
			'date_icon',
			[
				'label' => __( 'Date Icon', 'happy-elementor-addons' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'icon icon-calendar-page-empty',
					'library' => 'ekiticons',
				],
				'condition' => [
					'meta' => 'yes',
					'date_meta' => 'yes',
				]
			]
		);

		$this->add_control(
			'category_meta',
			[
				'label' => __( 'Category', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'happy-elementor-addons' ),
				'label_off' => __( 'Hide', 'happy-elementor-addons' ),
				'return_value' => 'yes',
				'default' => '',
				'condition' => [
					'meta' => 'yes',
					'post_type' => 'post',
				]
			]
		);

		$this->add_control(
			'category_icon',
			[
				'label' => __( 'Category Icon', 'happy-elementor-addons' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'icon icon-folder',
					'library' => 'ekiticons',
				],
				'condition' => [
					'meta' => 'yes',
					'category_meta' => 'yes',
					'post_type' => 'post',
				]
			]
		);

		$this->add_control(
			'meta_position',
			[
				'label' => __( 'Meta Position', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => [
					'top' => __( 'Top', 'happy-elementor-addons' ),
					'bottom' => __( 'Bottom', 'happy-elementor-addons' ),
				],
				'condition' => [
					'meta' => 'yes',
				]
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => __( 'Title HTML Tag', 'happy-elementor-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'h1'  => [
						'title' => __( 'H1', 'happy-elementor-addons' ),
						'icon' => 'eicon-editor-h1'
					],
					'h2'  => [
						'title' => __( 'H2', 'happy-elementor-addons' ),
						'icon' => 'eicon-editor-h2'
					],
					'h3'  => [
						'title' => __( 'H3', 'happy-elementor-addons' ),
						'icon' => 'eicon-editor-h3'
					],
					'h4'  => [
						'title' => __( 'H4', 'happy-elementor-addons' ),
						'icon' => 'eicon-editor-h4'
					],
					'h5'  => [
						'title' => __( 'H5', 'happy-elementor-addons' ),
						'icon' => 'eicon-editor-h5'
					],
					'h6'  => [
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
			'_style_news_ticker_wrapper',
			[
				'label' => __( 'Wrapper', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'wrapper_background',
				'label' => __( 'Background', 'happy-elementor-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ha-news-ticker-wrapper',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wrapper_border',
				'label' => __( 'Border', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-news-ticker-wrapper',
			]
		);

		$this->add_control(
			'wrapper_border_radius',
			[
				'label' => __( 'Border Radius', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .ha-news-ticker-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wrapper_box_shadow',
				'label' => __( 'Box Shadow', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-news-ticker-wrapper',
			]
		);

		$this->add_responsive_control(
			'wrapper_padding',
			[
				'label' => __( 'Padding', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ha-news-ticker-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'sticky_title_position_left',
			[
				'label' => __( 'Sticky Title Position Left', 'happy-elementor-addons' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'left',
				'selectors' => [
					'(desktop){{WRAPPER}}  .ha-news-ticker-wrapper  span.ha-news-ticker-sticky-title' => 'left: {{wrapper_padding.LEFT || 0}}{{wrapper_padding.UNIT}}; right:auto;',
					'(tablet){{WRAPPER}}  .ha-news-ticker-wrapper  span.ha-news-ticker-sticky-title' => 'left: {{wrapper_padding_tablet.LEFT}}{{wrapper_padding_tablet.UNIT}}; right:auto;',
					'(mobile){{WRAPPER}}  .ha-news-ticker-wrapper  span.ha-news-ticker-sticky-title' => 'left: {{wrapper_padding_mobile.LEFT}}{{wrapper_padding_mobile.UNIT}}; right:auto;',
				],
				'condition' => [
					'sticky_title!' => '',
					'sticky_title_position' => 'left',
				]
			]
		);

		$this->add_control(
			'sticky_title_position_right',
			[
				'label' => __( 'Sticky Title Position Right', 'happy-elementor-addons' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'right',
				'selectors' => [
					'(desktop){{WRAPPER}}  .ha-news-ticker-wrapper  span.ha-news-ticker-sticky-title' => 'right: {{wrapper_padding.RIGHT || 0}}{{wrapper_padding.UNIT}}; left:auto;',
					'(tablet){{WRAPPER}}  .ha-news-ticker-wrapper  span.ha-news-ticker-sticky-title' => 'right: {{wrapper_padding_tablet.RIGHT}}{{wrapper_padding_tablet.UNIT}}; left:auto;',
					'(mobile){{WRAPPER}}  .ha-news-ticker-wrapper  span.ha-news-ticker-sticky-title' => 'right: {{wrapper_padding_mobile.RIGHT}}{{wrapper_padding_mobile.UNIT}}; left:auto;',
				],
				'condition' => [
					'sticky_title!' => '',
					'sticky_title_position' => 'right',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_style_news_ticker_sticky_title',
			[
				'label' => __( 'Sticky Title', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'sticky_title_color',
			[
				'label' => __( 'Title Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-news-ticker-wrapper  span.ha-news-ticker-sticky-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sticky_title_typography',
				'label' => __( 'Typography', 'happy-elementor-addons' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .ha-news-ticker-wrapper  span.ha-news-ticker-sticky-title',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'sticky_title_background',
				'label' => __( 'Background', 'happy-elementor-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ha-news-ticker-wrapper span.ha-news-ticker-sticky-title',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'sticky_title_border',
				'label' => __( 'Border', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-news-ticker-wrapper span.ha-news-ticker-sticky-title',
			]
		);

		$this->add_control(
			'sticky_title_border_radius',
			[
				'label' => __( 'Border Radius', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .ha-news-ticker-wrapper span.ha-news-ticker-sticky-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sticky_title_padding',
			[
				'label' => __( 'Padding', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ha-news-ticker-wrapper span.ha-news-ticker-sticky-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_style_news_ticker_title',
			[
				'label' => __( 'Title', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( '_tabs_title' );

		$this->start_controls_tab(
			'_tab_title_normal',
			[
				'label' => __( 'Normal', 'happy-elementor-addons' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Title Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-news-ticker-wrapper  li.ha-news-ticker-item a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_title_hover',
			[
				'label' => __( 'Hover', 'happy-elementor-addons' ),
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label' => __( 'Title Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-news-ticker-wrapper  li.ha-news-ticker-item a:hover, {{WRAPPER}} .ha-news-ticker-wrapper  li.ha-news-ticker-item a:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'happy-elementor-addons' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .ha-news-ticker-wrapper  li.ha-news-ticker-item .ha-news-ticker-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_shadow',
				'label' => __( 'Title Shadow', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-news-ticker-wrapper  li.ha-news-ticker-item a',
				'style_transfer' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function render () {

		$settings = $this->get_settings_for_display();
		//var_dump( $this->ha_get_post_types() );
		$array = [
			$settings['post_type'],
			$settings['selected_list_' . $settings['post_type']],
		];
//		show_post_by
		//echo '<pre>';
		//var_dump( $array );
		//echo '</pre>';
		$args = [
			'post_status' => 'publish',
			'post_type' => $settings['post_type'],
		];
		$args['ignore_sticky_posts'] = 1;
		if ( 'recent' === $settings['show_post_by'] ) {
			$args['posts_per_page'] = $settings['posts_per_page'];
			//$args['order'] =  $settings['ASC'];
		}

		$customize_title = [];
		if ( 'selected' === $settings['show_post_by'] && ! empty( $settings['selected_list_' . $settings['post_type']] ) ) {
			$lists = $settings['selected_list_' . $settings['post_type']];
			$ids = [];
			foreach ( $lists as $index => $value ) {
				$ids[] = $value['post_id'];
				if( $value['title'] ) $customize_title[$value['post_id']] = $value['title'];
			}
			$args['post__in'] = (array) $ids;
		}
		//$args['post__in'] =  (array) $settings['selected_posts'];
		$posts = get_posts( $args );

		//var_dump($posts);
		$this->add_render_attribute( 'wrapper', 'class', [ 'ha-post-list-wrapper' ] );
		$this->add_render_attribute( 'wrapper-inner', 'class', [ 'ha-post-list' ] );
		$this->add_render_attribute( 'item', 'class', [ 'ha-post-list-item' ] );

		if ( count( $posts ) !== 0 ) :?>
			<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
				<ul <?php $this->print_render_attribute_string( 'wrapper-inner' ); ?> >
					<?php foreach ( $posts as $post ): ?>
						<li <?php $this->print_render_attribute_string( 'item' ); ?>>
							<a href="<?php echo esc_url( get_the_permalink( $post->ID ) ); ?>">
								<?php if ( 'yes' === $settings['feature_image'] ):
									echo get_the_post_thumbnail( $post->ID, $settings['post_image_size'] );
								elseif ( 'yes' === $settings['list_icon'] && $settings['icon'] ) :
									echo '<span class="ha-post-list-icon">';
									Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
									echo '</span>';
								endif; ?>
								<div class="ha-post-list-content">
									<?php
									$title = $post->post_title;
									if ( 'selected' === $settings['show_post_by'] && array_key_exists( $post->ID ,$customize_title ) ){
										$title = $customize_title[$post->ID];
									}
									if('top' === $settings['meta_position'] ){
										printf( '<%1$s %2$s>%3$s</%1$s>',
											tag_escape( $settings['title_tag'] ),
											'class="ha-post-list-title"',
											esc_html( $title )
										);
									}
									?>
									<?php if ( 'yes' === $settings['meta'] || 'yes' === $settings['category_meta'] ): ?>
										<div class="ha-post-list-meta-wrap">

											<?php if ( 'yes' === $settings['author_meta'] ):
												?>
												<span class="ha-post-list-author">
												<?php if ( $settings['author_icon'] ):
													Icons_Manager::render_icon( $settings['author_icon'], [ 'aria-hidden' => 'true' ] );
												endif;
												echo esc_html( get_the_author_meta( 'display_name', $post->post_author ) ); ?>
												</span>
											<?php endif; ?>

											<?php if ( 'yes' === $settings['date_meta'] ): ?>
												<span class="ha-post-list-date">
													<?php if ( $settings['date_icon'] ):
														Icons_Manager::render_icon( $settings['date_icon'], [ 'aria-hidden' => 'true' ] );
													endif;
													echo get_the_date( "d M Y" );
													?>
												</span>
											<?php endif; ?>

											<?php if ( 'post' === $settings['post_type'] && 'yes' === $settings['category_meta'] ):
												$categories = get_the_category( $post->ID );
												?>
												<span class="ha-post-list-category">
												<?php if ( $settings['category_icon'] ):
													Icons_Manager::render_icon( $settings['category_icon'], [ 'aria-hidden' => 'true' ] );
												endif;
												echo esc_html( $categories[0]->name ); ?>
												</span>
											<?php endif; ?>

										</div>
									<?php endif; ?>
									<?php
									if('bottom' === $settings['meta_position'] ){
										printf( '<%1$s %2$s>%3$s</%1$s>',
											tag_escape( $settings['title_tag'] ),
											'class="ha-post-list-title"',
											esc_html( $title )
										);
									}
									?>
								</div>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php
		endif;
	}
}
