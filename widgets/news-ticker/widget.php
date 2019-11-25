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
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

defined('ABSPATH') || die();

class News_Ticker extends Base {

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __('News Ticker', 'happy-elementor-addons');
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
		return 'hm hm-image-slider';
	}

	public function get_keywords() {
		return ['news', 'news-ticker', 'ticker', 'text-slider', 'slider'];
	}

	/**
	 * Get a list of all WPForms
	 *
	 * @return array
	 */
	public function ha_get_posts() {
		$posts = [];
		$_posts = get_posts([
			'post_type' => 'post',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC',
		]);

		if (!empty($_posts)) {
			$posts = wp_list_pluck($_posts, 'post_title', 'ID');
		}
		return $posts;

	}

	protected function register_content_controls() {
		$this->start_controls_section(
			'_section_news_ticker',
			[
				'label' => __('News Ticker', 'happy-elementor-addons'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);


		$this->add_control(
			'selected_posts',
			[
				'label' => __('Select Posts', 'happy-elementor-addons'),
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
				'label' => __('Slide direction', 'happy-elementor-addons'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'happy-elementor-addons'),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __('Right', 'happy-elementor-addons'),
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
				'label' => __('Space between items', 'happy-elementor-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
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
				'label' => __('Slide Speed', 'happy-elementor-addons'),
				'description' => __('Autoplay speed in seconds. Default 30', 'happy-elementor-addons'),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 10000,
				'default' => 200,
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_controls() {
		/*$this->start_controls_section(
			'_section_fields_style',
			[
				'label' => __( 'Form Fields', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'field_width',
			[
				'label' => __( 'Width', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} control:not(.wpcf7-submit)' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ha- label' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->end_controls_section();*/
	}

	protected function render() {

		$settings = $this->get_settings_for_display();
		if (empty($settings['selected_posts'])) {
			return;
		}
		$query_args = [
			'post_type' => 'post',
			'post_status' => 'publish',
			'ignore_sticky_posts' => 1,
			'post__in' => (array)$settings['selected_posts'],
		];

		$the_query = new \WP_Query($query_args);

		$this->add_render_attribute('wrapper', 'class', ['ha-news-ticker-wrapper']);
		$this->add_render_attribute('wrapper', 'data-duration', $settings['speed'] ? ($settings['speed'] * '1000') : '30000');
		$this->add_render_attribute('wrapper', 'data-scroll-direction', $settings['slide_direction']);
		$this->add_render_attribute('container', 'class', ['ha-news-ticker-container']);
		$this->add_render_attribute('item', 'class', ['ha-news-ticker-item']);

		echo 'Hello News Ticker';
		if ( $the_query->have_posts() ) :?>
			<div <?php $this->print_render_attribute_string('wrapper'); ?>>
				<ul <?php $this->print_render_attribute_string('container'); ?>>
					<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
						<li <?php $this->print_render_attribute_string('item'); ?>>
							<h2 class="ha-news-ticker-title">
								<a href="<?php echo esc_url(get_permalink()); ?>">
									<?php echo esc_html(get_the_title()); ?>
								</a>
							</h2>
						</li>
					<?php endwhile;
					wp_reset_postdata(); ?>
				</ul>
			</div>
		<?php
		endif;
		//echo '<pre>';
//	    var_dump( count($result) );
		//var_dump( $settings['selected_posts'],$this->ha_get_posts() );
		//echo '</pre>';

	}
}
