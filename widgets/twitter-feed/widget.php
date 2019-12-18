<?php
/**
 * Twitter Feed
 *
 * @package Happy_Addons
 */

namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;

defined('ABSPATH') || die();

class Twitter_Feed extends Base {

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __('Twitter Feed', 'happy-elementor-addons');
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
		return 'hm hm-twitter';
	}

	public function get_keywords() {
		return ['twitter-feed', 'twitter', 'feed', 'social media'];
	}


	/**
	 * Register content related controls
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'_section_twitter',
			[
				'label' => __( 'Twitter Feed', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'user_name',
			[
				'label' => esc_html__('User Name', 'happy-elementor-addons'),
				'type' => Controls_Manager::TEXT,
				'default' => '@wpdevteam',
				'label_block' => false,
				'description' => esc_html__('Use @ sign with your account name.', 'happy-elementor-addons' ),

			]
		);

		$this->add_control(
			'consumer_key',
			[
				'label' => esc_html__('Consumer Key', 'happy-elementor-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => 'wwC72W809xRKd9ySwUzXzjkmS',
				'description' => '<a href="https://apps.twitter.com/app/" target="_blank">Get Consumer Key.</a> Create a new app or select existing app and grab the <b>consumer key.</b>',
			]
		);

		$this->add_control(
			'consumer_secret',
			[
				'label' => esc_html__('Consumer Secret', 'happy-elementor-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => 'rn54hBqxjve2CWOtZqwJigT3F5OEvrriK2XAcqoQVohzr2UA8h',
				'description' => '<a href="https://apps.twitter.com/app/" target="_blank">Get Consumer Secret.</a> Create a new app or select existing app and grab the <b>consumer secret.</b>',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_twitter_settings',
			[
				'label' => __('Settings', 'happy-elementor-addons'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'sort_by',
			[
				'label' => __( 'Sort By', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'recent-posts',
				'options' => [
					'recent-posts' => __( 'Recent Posts', 'happy-elementor-addons' ),
					'old-posts' => __( 'Old Posts', 'happy-elementor-addons' ),
					'favorite_count' => __( 'Favorite', 'happy-elementor-addons' ),
					'retweet_count' => __( 'Retweet', 'happy-elementor-addons' ),
				],
			]
		);

		$this->add_control(
			'tweets_limit',
			[
				'label' => __( 'Number of tweets to show', 'happy-elementor-addons' ),
				'type' => Controls_Manager::NUMBER,
				'separator' => 'after',
				'min' => 0,
				'max' => 100,
				'step' => 1,
				'default' => 6,
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'columns',
			[
				'label' => __('Column Number', 'happy-elementor-addons'),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'three',
				'options' => [
					'one' => __( '1 Column', 'happy-elementor-addons' ),
					'two' => __( '2 Column', 'happy-elementor-addons' ),
					'three' => __( '3 Column', 'happy-elementor-addons' ),
					'four' => __( '4 Column', 'happy-elementor-addons' ),
				],
				'prefix_class' => 'ha-tweet-column-',
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'show_favorite',
			[
				'label' => __('Show Favorite?', 'happy-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'show_retweet',
			[
				'label' => __('Show Retweets Count?', 'happy-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'show_user_image',
			[
				'label' => __('Show Profile image', 'happy-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_name',
			[
				'label' => __('Show Name', 'happy-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_user_name',
			[
				'label' => __('Show User Name', 'happy-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_date',
			[
				'label' => __('Show Date', 'happy-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'title_btn',
			[
				'label' => __('Title Button?', 'happy-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'title_btn_text',
			[
				'label' => __('Title Button Text', 'happy-elementor-addons'),
				'type' => Controls_Manager::TEXT,
				'default' => __('Instagram', 'happy-elementor-addons'),
				'condition' => [
					'title_btn' => 'yes',
				],
			]
		);

		$this->add_control(
			'load_more',
			[
				'label' => __('Load More Button?', 'happy-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => '',
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'load_more_text',
			[
				'label' => __('Load More Text', 'happy-elementor-addons'),
				'type' => Controls_Manager::TEXT,
				'default' => 'Load More',
				'condition' => [
					'load_more' => 'yes',
				],
			]
		);

		$this->add_control(
			'_heading_user_info',
			[
				'label' => __( 'User Info', 'plugin-name' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'view_style' => 'ha-feed-view',
				],
			]
		);

		$this->add_control(
			'show_user_picture',
			[
				'label' => __('Show Profile Picture?', 'happy-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'view_style' => 'ha-feed-view',
				],
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'show_username',
			[
				'label' => __('Show Username?', 'happy-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'view_style' => 'ha-feed-view',
				],
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'show_user_postdate',
			[
				'label' => __('Show Post Date?', 'happy-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'view_style' => 'ha-feed-view',
				],
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'show_user_insta_icon',
			[
				'label' => __('Show Insta Icon?', 'happy-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'view_style' => 'ha-feed-view',
				],
				'style_transfer' => true,
			]
		);

		$this->end_controls_section();
	}


	/**
	 * Register styles related controls
	 */
	protected function register_style_controls() {
		$this->start_controls_section(
			'_section_instagram_style',
			[
				'label' => __('Instagram', 'happy-elementor-addons'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->end_controls_section();
	}


	protected function instagram_get_b64_icon() {
		return 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxOC4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB3aWR0aD0iMzJweCIgaGVpZ2h0PSIzMnB4IiB2aWV3Qm94PSIwIDAgMzIgMzIiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDMyIDMyOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+DQo8c3R5bGUgdHlwZT0idGV4dC9jc3MiPg0KCS5zdDB7ZGlzcGxheTpub25lO2ZpbGw6bm9uZTtzdHJva2U6IzAwMDAwMDtzdHJva2Utd2lkdGg6MjtzdHJva2UtbGluZWpvaW46cm91bmQ7c3Ryb2tlLW1pdGVybGltaXQ6MTA7fQ0KCS5zdDF7ZGlzcGxheTpub25lO30NCjwvc3R5bGU+DQo8cGF0aCBkPSJNMTYsMzJDNy4yLDMyLDAsMjQuOCwwLDE2UzcuMiwwLDE2LDBzMTYsNy4yLDE2LDE2YzAsMi40LTAuNSw0LjgtMS42LDdsMS42LDYuN2MwLjEsMC4zLDAsMC43LTAuMywwLjkNCgljLTAuMiwwLjItMC42LDAuMy0wLjksMC4zbC02LjMtMS40QzIyLDMxLjIsMTksMzIsMTYsMzJ6IE0xNiwyQzguMywyLDIsOC4zLDIsMTZzNi4zLDE0LDE0LDE0YzIuOCwwLDUuNS0wLjgsNy44LTIuNA0KCWMwLjItMC4yLDAuNS0wLjIsMC44LTAuMWw1LjEsMS4xbC0xLjMtNS41Yy0wLjEtMC4yLDAtMC41LDAuMS0wLjdjMS0yLDEuNS00LjEsMS41LTYuNEMzMCw4LjMsMjMuNywyLDE2LDJ6Ii8+DQo8cGF0aCBjbGFzcz0ic3QwIiBkPSJNLTY3LDkwLjdjMy40LTMuNSwzLjQtOS4yLTAuMS0xMi43bDAsMGMtMy41LTMuNS05LjItMy41LTEyLjctMC4xYy0zLjUtMy40LTkuMi0zLjQtMTIuNywwLjFsMCwwDQoJYy0zLjUsMy41LTMuNSw5LjItMC4xLDEyLjdsMCwwbDAuMSwwLjFsMTEuMywxMS4zYzAuOCwwLjgsMiwwLjgsMi44LDBsMTEuMy0xMS4zTC02Nyw5MC43TC02Nyw5MC43eiIvPg0KPHBhdGggY2xhc3M9InN0MSIgZD0iTTIzLDMySDljLTUsMC05LTQtOS05VjljMC01LDQtOSw5LTloMTRjNSwwLDksNCw5LDl2MTRDMzIsMjgsMjgsMzIsMjMsMzJ6IE05LDJDNS4xLDIsMiw1LjEsMiw5djE0DQoJYzAsMy45LDMuMSw3LDcsN2gxNGMzLjksMCw3LTMuMSw3LTdWOWMwLTMuOS0zLjEtNy03LTdIOXoiLz4NCjxwYXRoIGNsYXNzPSJzdDEiIGQ9Ik0xNiwyNC4yYy00LjUsMC04LjItMy43LTguMi04LjJjMC00LjUsMy43LTguMiw4LjItOC4yYzQuNSwwLDguMiwzLjcsOC4yLDguMkMyNC4yLDIwLjUsMjAuNSwyNC4yLDE2LDI0LjJ6DQoJIE0xNiw5LjhjLTMuNCwwLTYuMiwyLjgtNi4yLDYuMnMyLjgsNi4yLDYuMiw2LjJzNi4yLTIuOCw2LjItNi4yUzE5LjQsOS44LDE2LDkuOHoiLz4NCjxjaXJjbGUgY2xhc3M9InN0MSIgY3g9IjE2IiBjeT0iMTYiIHI9IjEuOSIvPg0KPC9zdmc+DQo=';
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

//		$this->add_render_attribute('wrapper', 'class', 'ha-instagram-wrapper');
		?>
		<div class="ha-tweeter-feed-wrapper">
			<?php $this->twitter_feed_render($this->get_id(), $settings); ?>
		</div>
		<?php
	}

	protected function twitter_feed_render( $id, $settings ) {
		define( 'HA_TWEETS_TOKEN', '_tweet_token' );
		define( 'HA_TWEETS_CASH', '_tweet_cash' );

		if ( empty( $settings['user_name'] ) || empty( $settings['consumer_key'] ) || empty( $settings['consumer_secret'] ) ) {
			return;
		}

		$token = get_option( $id . '_'. $settings['user_name'] . HA_TWEETS_TOKEN );
		$transient_key = $id . '_' . $settings['user_name'] . HA_TWEETS_CASH;

//		$messages = array();
		$twitter_data = get_transient($transient_key);
		$credentials = base64_encode($settings['consumer_key'] . ':' . $settings['consumer_secret']);

		if ( $twitter_data === false ) {
			$auth_response = wp_remote_post('https://api.twitter.com/oauth2/token',
				array(
					'method' => 'POST',
					'httpversion' => '1.1',
					'blocking' => true,
					'headers' => [
						'Authorization' => 'Basic ' . $credentials,
						'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
					],
					'body' => ['grant_type' => 'client_credentials'],
				) );
			$response_code = wp_remote_retrieve_response_code($auth_response);

			if ( $response_code == 200 ) {
				$body = json_decode( wp_remote_retrieve_body( $auth_response ) );
				update_option($id . '_' . $settings['user_name'] . HA_TWEETS_TOKEN, $body->access_token);
				$token = $body->access_token;

				$tweets_response = wp_remote_get('https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=' . $settings['user_name'] . '&count=999&tweet_mode=extended',
					array(
						'httpversion' => '1.1',
						'blocking' => true,
						'headers' => [ 'Authorization' => "Bearer $token", ],
					) );

				if ( !is_wp_error( $tweets_response ) ) {
					$twitter_data = json_decode( wp_remote_retrieve_body( $tweets_response ), true );
					set_transient($id . '_' . $settings['user_name'] . HA_TWEETS_CASH, $twitter_data, 0 ); // 2 * MINUTE_IN_SECONDS
				}
			}

		}

		$query_settings = [
			'tweets_limit' => $settings['sort_by'],
		];
//		$query_settings = json_encode($query_settings, true);

		switch ($settings['sort_by']) {
			case 'old-posts':
				usort($twitter_data, function ($a,$b) {
					if ( $a['created_at'] == $b['created_at'] ) return 0;
					return ( $a['created_at'] < $b['created_at'] ) ? -1 : 1 ;
//					print_r($twitter_data);
				});
				break;
			case 'favorite_count':
				usort($twitter_data, function ($a,$b){
					if ($a['favorite_count'] == $b['favorite_count']) return 0;
					return ($a['favorite_count'] > $b['favorite_count']) ? -1 : 1 ;
				});
				break;
			case 'retweet_count':
				usort($twitter_data, function ($a,$b){
					if ($a['retweet_count'] == $b['retweet_count']) return 0;
					return ($a['retweet_count'] > $b['retweet_count']) ? -1 : 1 ;
				});
				break;
			default:
				$twitter_data;
		}

		if ( !empty( $settings['tweets_limit'] ) && count( $twitter_data ) > $settings['tweets_limit'] ) {
			$items = array_splice($twitter_data, 0, $settings['tweets_limit'] );
		}
		if ( empty( $settings['tweets_limit'] ) ) {
			$items = $twitter_data;
		}

		foreach ($items as $item) :
//			echo "<pre>";
//			var_dump( $item );
//			echo "</pre>";
			?>
			<div class="ha-tweet-item">

				<div class="ha-tweet-author">
					<?php if ( $settings['show_user_image'] == 'yes' ) : ?>
						<img
							src="<?php echo esc_url( $item['user']['profile_image_url_https'] ); ?>"
							alt="<?php echo esc_attr( $item['user']['name'] ); ?>"
							class="ha-tweet-avatar"
						>
					<?php endif; ?>

					<div class="ha-tweet-user">
						<?php if ( $settings['show_name'] == 'yes' ) : ?>
							<a href="<?php echo esc_url( 'https://twitter.com/'.$settings['user_name'] ); ?>" class="ha-tweet-author-name">
								<?php echo esc_html( $item['user']['name'] ); ?>
							</a>
						<?php endif; ?>

						<?php if ( $settings['show_user_name'] == 'yes' ) : ?>
							<a href="<?php echo esc_url( 'https://twitter.com/'.$settings['user_name'] ); ?>" class="ha-tweet-username">
								<?php echo esc_html( $settings['user_name'] ); ?>
							</a>
						<?php endif; ?>
					</div>
				</div>

				<div class="ha-tweet-description">
					<p><?php echo esc_html( $item['full_text'] ); ?></p>

					<?php if ( $settings['show_date'] == 'yes' ) : ?>
						<div class="ha-tweet-date">
							<?php echo esc_html( date("M d Y", strtotime( $item['created_at'] ) ) );?>
						</div>
					<?php endif; ?>
				</div>

				<?php if ( $settings['show_favorite'] == 'yes' || $settings['show_retweet'] == 'yes' ) : ?>
					<div class="ha-tweet-footer">

						<?php if ( $settings['show_favorite'] == 'yes' ) : ?>
							<div class="ha-tweet-favorite">
								<?php echo esc_html( $item['favorite_count'] ); ?>
								<i class="fa fa-heart"></i>
							</div>
						<?php endif; ?>

						<?php if ( $settings['show_retweet'] == 'yes' ) : ?>
							<div class="ha-tweet-retweet">
								<?php echo esc_html( $item['retweet_count'] ); ?>
								<i class="fa fa-retweet"></i>
							</div>
						<?php endif; ?>

					</div>
				<?php endif; ?>

			</div>
		<?php
		endforeach;
	}

}
