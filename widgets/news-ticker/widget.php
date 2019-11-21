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

defined( 'ABSPATH' ) || die();

class News_Ticker extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'News Ticker', 'happy-elementor-addons' );
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
        return 'hm hm-image-slider';
    }

    public function get_keywords() {
        return [ 'news', 'news-ticker', 'ticker', 'text-slider', 'slider' ];
    }

	protected function register_content_controls() {
		$this->start_controls_section(
			'_section_news_ticker',
			[
				'label' => __( 'News Ticker', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);


        $this->add_control(
            'news_ticker_type',
            [
	            'label' => __( 'News Ticker Type', 'plugin-domain' ),
	            'type' => Controls_Manager::SELECT,
	            'default' => 'post',
	            'options' => [
		            'post'  => __( 'Post', 'happy-elementor-addons' ),
		            'rss_feed'  => __( 'Rss Feed', 'happy-elementor-addons' ),
		            'custom'  => __( 'Custom', 'happy-elementor-addons' ),
	            ],
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

    protected function hapro_get_rss_feed( $url, $post_per_page = 10 ) {

    	$rss_feed = [];
	    $url = ! empty( $url ) ? $url : '';
	    while ( stristr( $url, 'http' ) != $url ) {
		    $url = substr( $url, 1 );
	    }

	    if ( empty( $url ) ) {
		    return '';
	    }

	    // self-url destruction sequence
	    if ( in_array( untrailingslashit( $url ), array( site_url(), home_url() ) ) ) {
		    return '';
	    }

	    $rss   = fetch_feed( $url );

	    if ( is_string( $rss ) ) {
		    $rss = fetch_feed( $rss );
	    } elseif ( is_array( $rss ) && isset( $rss['url'] ) ) {
		    $rss  = fetch_feed( $rss['url'] );
	    } elseif ( ! is_object( $rss ) ) {
		    return '';
	    }

		$error = '';
	    if ( is_wp_error( $rss ) && ( is_admin() || current_user_can( 'manage_options' ) ) ) {
		    $error = __( 'RSS Error:', 'happy-elementor-addons' ) . '</strong> ' . $rss->get_error_message();
		    return $error;
	    }

	    if ( ! $rss->get_item_quantity() ) {
		    $error = __( 'An error has occurred, which probably means the feed is down. Try again later.', 'happy-elementor-addons' );
		    $rss->__destruct();
		    unset( $rss );
		    return $error;
	    }

	    $post_per_page = (int) $post_per_page;
	    if (  $post_per_page == -1 ) {
		    $post_per_page = $rss->get_item_quantity();
	    }

	    foreach ( $rss->get_items( 0, $post_per_page ) as $item ) {
		    $link = $item->get_link();
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
	    $rss->__destruct();
	    unset( $rss );

	    return $rss_feed;
    }

    protected function render() {

    	$settings = $this->get_settings_for_display();

    	$url = 'asfasdhttps://happyaddons.com/feed/';
    	$post_per_page = 8;
//    	$url = 'https://www.feedforall.com/sample.xml';
//    	$url = 'https://www.feedforall.com/sample-feed.xml';
//    	$url = 'https://www.feedforall.com/blog-feed.xml';
    	$url = 'http://www.rss-specifications.com/blog-feed.xml';

	    $result = $this->hapro_get_rss_feed($url,'-1');

    	echo 'Hello News Ticker';
		echo '<pre>';
//	    var_dump( count($result) );
    	var_dump( $result );
    	echo '</pre>';
    }
}
