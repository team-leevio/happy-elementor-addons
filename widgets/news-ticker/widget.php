<?php
/**
 * News Ticker widget class
 *
 * @package Happy_Addons
 */

namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Background;
use Happy_Addons\Elementor\Controls\Group_Control_Foreground;

defined( 'ABSPATH' ) || die();

class News_Ticker extends Base {

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title () {
		return __( 'News Ticker', 'happy-elementor-addons' );
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
		return 'hm hm-image-slider';
	}

	public function get_keywords () {
		return [ 'news', 'news-ticker', 'ticker', 'text-slider', 'slider' ];
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

	protected function register_content_controls () {
		$this->start_controls_section(
			'_section_news_ticker',
			[
				'label' => __( 'News Ticker', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);


		$this->add_control(
			'sticky_title',
			[
				'label' => __( 'Sticky Title', 'happy-elementor-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Breaking News', 'happy-elementor-addons' ),
			]
		);

		$this->add_control(
			'sticky_title_position',
			[
				'label' => __( 'Sticky Title Position', 'happy-elementor-addons' ),
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
				],
				'default' => 'left',
				'toggle' => false,
				'style_transfer' => true,
				'selectors' => [
					'{{WRAPPER}}  .ha-news-ticker-wrapper  span.ha-news-ticker-sticky-title' => '{{VALUE}};'
				],
				'selectors_dictionary' => [
					'left' => 'left: 0',
					'right' => 'right: 0'
				]
			]
		);


		$this->add_control(
			'selected_posts',
			[
				'label' => __( 'Select Posts', 'happy-elementor-addons' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT2,
				'default' => '',
				'options' => $this->ha_get_posts(),
				'multiple' => true,
			]
		);

		$this->add_control(
			'slide_direction',
			[
				'label' => __( 'Slide direction', 'happy-elementor-addons' ),
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
				],
				'default' => 'left',
				'toggle' => false,
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'item_space',
			[
				'label' => __( 'Space between items', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ha-news-ticker-wrapper .ha-news-ticker-item' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ha-news-ticker-wrapper .ha-news-ticker-item:last-child' => 'margin-right: 0;',
				],
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'speed',
			[
				'label' => __( 'Slide Speed', 'happy-elementor-addons' ),
				'description' => __( 'Autoplay speed in seconds. Default 30', 'happy-elementor-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 10000,
				'default' => 30,
				'frontend_available' => true,
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

		$this->add_control(
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

		$this->add_control(
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

		$this->start_controls_tabs( '_tabs_button' );

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

	public function prefix_get_rss_feed ( $url, $post_per_page = 10 ) {

		$rss_feed = [];
		$url = ! empty( $url ) ? $url : '';
		while ( stristr( $url, 'http' ) != $url ) {
			$url = substr( $url, 1 );
		}

		if ( empty( $url ) ) {
			return;
		}

		// self-url destruction sequence
		if ( in_array( untrailingslashit( $url ), array( site_url(), home_url() ) ) ) {
			return;
		}

		$rss = fetch_feed( $url );

		if ( is_string( $rss ) ) {
			$rss = fetch_feed( $rss );
		} elseif ( is_array( $rss ) && isset( $rss['url'] ) ) {
			$rss = fetch_feed( $rss['url'] );
		} elseif ( ! is_object( $rss ) ) {
			return;
		}

		$error = '';
		if ( is_wp_error( $rss ) && ( is_admin() || current_user_can( 'manage_options' ) ) ) {
			$error = __( 'RSS Error:', 'text-domain' ) . '</strong> ' . $rss->get_error_message();
			return $error;
		}

		if ( ! $rss->get_item_quantity() ) {
			$error = __( 'An error has occurred, which probably means the feed is down. Try again later.', 'text-domain' );
			$rss->__destruct();
			unset( $rss );
			return $error;
		}

		$post_per_page = (int) $post_per_page;
		if ( $post_per_page == -1 ) {
			$post_per_page = $rss->get_item_quantity();
		}

		foreach ( $rss->get_items( 0, 10 ) as $item ) {
			$link = $item->get_image_link();
			while ( stristr( $link, 'http' ) != $link ) {
				$link = substr( $link, 1 );
			}
			$link = esc_url( strip_tags( $link ) );

			$title = esc_html( trim( strip_tags( $item->get_title() ) ) );
			if ( empty( $title ) ) {
				$title = __( 'Untitled' );
			}
			$rss_feed[$link] = $title;
		}
//		$rss->__destruct();
//		unset( $rss );

		return $rss->get_favicon();
	}

	protected function render () {

		$settings = $this->get_settings_for_display();
		if ( empty( $settings['selected_posts'] ) ) {
			return;
		}
		$query_args = [
			'post_type' => 'post',
			'post_status' => 'publish',
			'ignore_sticky_posts' => 1,
			'post__in' => (array) $settings['selected_posts'],
		];

		$the_query = new \WP_Query( $query_args );

		$this->add_render_attribute( 'wrapper', 'class', [ 'ha-news-ticker-wrapper' ] );
		$this->add_render_attribute( 'wrapper', 'data-duration', $settings['speed'] ? ( $settings['speed'] * '1000' ) : '30000' );
		$this->add_render_attribute( 'wrapper', 'data-scroll-direction', $settings['slide_direction'] );
		$this->add_render_attribute( 'container', 'class', [ 'ha-news-ticker-container' ] );
		$this->add_render_attribute( 'item', 'class', [ 'ha-news-ticker-item' ] );

		$rss = $this->prefix_get_rss_feed('https://happyaddons.com/feed/','10');
		echo '<pre>';
		var_dump($rss);
		echo '</pre>';
		if ( $the_query->have_posts() ) :?>
			<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
				<span class="ha-news-ticker-sticky-title"><?php echo esc_html( $settings['sticky_title'] ); ?></span>
				<ul <?php $this->print_render_attribute_string( 'container' ); ?>>
					<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
						<li <?php $this->print_render_attribute_string( 'item' ); ?>>
							<h2 class="ha-news-ticker-title">
								<a href="<?php echo esc_url( get_permalink() ); ?>">
									<?php echo esc_html( get_the_title() ); ?>
								</a>
							</h2>
						</li>
					<?php endwhile;
					wp_reset_postdata(); ?>
				</ul>
			</div>
		<?php
		endif;
	}
}
