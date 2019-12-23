<?php
/**
 * Twitter Feed
 *
 * @package Happy_Addons
 */

namespace Happy_Addons\Elementor\Widget;
//namespace Happy_Addons\Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
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
				'description' => esc_html__('Use @ sign with your Twitter user name.', 'happy-elementor-addons' ),

			]
		);

		$this->add_control(
			'consumer_key',
			[
				'label' => esc_html__('Consumer Key', 'happy-elementor-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => 'wwC72W809xRKd9ySwUzXzjkmS',
				'description' => '<a href="https://apps.twitter.com/app/" target="_blank">Get Consumer Key.</a>',
			]
		);

		$this->add_control(
			'consumer_secret',
			[
				'label' => esc_html__('Consumer Secret', 'happy-elementor-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => 'rn54hBqxjve2CWOtZqwJigT3F5OEvrriK2XAcqoQVohzr2UA8h',
				'description' => '<a href="https://apps.twitter.com/app/" target="_blank">Get Consumer Secret key.</a>',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_twitter_settings',
			[
				'label' => __('Twitter Settings', 'happy-elementor-addons'),
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
				'max' => 800,
				'step' => 1,
				'default' => 6,
				'style_transfer' => true,
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => __('Column Number', 'happy-elementor-addons'),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'desktop_default' => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options' => [
					'1' => __( '1 Column', 'happy-elementor-addons' ),
					'2' => __( '2 Column', 'happy-elementor-addons' ),
					'3' => __( '3 Column', 'happy-elementor-addons' ),
					'4' => __( '4 Column', 'happy-elementor-addons' ),
				],
				'selectors' => [
					'(desktop){{WRAPPER}} .ha-tweet-items' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
					'(tablet){{WRAPPER}} .ha-tweet-items' => 'grid-template-columns: repeat({{columns_tablet.VALUE || 0}}, 1fr);',
					'(mobile){{WRAPPER}} .ha-tweet-items' => 'grid-template-columns: repeat({{columns_mobile.VALUE || 0}}, 1fr);'
				]
			]
		);

		$this->add_control(
			'show_twitter_logo',
			[
				'label' => __('Show Twitter Logo?', 'happy-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'show_favorite',
			[
				'label' => __('Show Favorite?', 'happy-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'show_retweet',
			[
				'label' => __('Show Retweets Count?', 'happy-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
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

		$this->start_controls_section(
			'_section_general_settings',
			[
				'label' => __('General Settings', 'happy-elementor-addons'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'alignment',
			[
				'label' => __( 'Alignment', 'happy-elementor-addons' ),
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
					],
				],
				'default' => 'left',
				'toggle' => false,
				'prefix_class' => 'ha-twitter-',
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'favorite_retweet_position',
			[
				'label' => __( 'Favorite & Retweet Position', 'happy-elementor-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => true,
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
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .ha-tweet-footer' => 'text-align: {{VALUE}};'
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
			'_section_twitter_style',
			[
				'label' => __('Common', 'happy-elementor-addons'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'item_spacing',
			[
				'label' => __( 'Spacing between items', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .ha-tweet-items' => 'grid-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'items_border',
				'selector' => '{{WRAPPER}} .ha-tweet-item',
			]
		);

		$this->add_responsive_control(
			'items_border_radius',
			[
				'label' => __( 'Border Radius', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ha-tweet-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'items_box_shadow',
				'selector' => '{{WRAPPER}} .ha-tweet-item'
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'item_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ha-tweet-item',
			]
		);

		$this->add_control(
			'item_background_overlay',
			[
				'label' => __( 'Background Overlay', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'item_background_background' => 'classic'
				],
				'selectors' => [
					'{{WRAPPER}} .ha-tweet-item:before' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_twitter_header',
			[
				'label' => __('Header', 'happy-elementor-addons'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'twitter_logo_heading',
			[
				'label' => __( 'Twitter Icon', 'happy-elementor-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'twitter_logo_icon_size',
			[
				'label' => __( 'Size', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .ha-tweeter-feed-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'twitter_logo_icon_color',
			[
				'label' => __( 'Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-tweeter-feed-icon i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'profile_image_heading',
			[
				'label' => __( 'Profile Image', 'happy-elementor-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'profile_image_spacing',
			[
				'label' => __( 'Spacing', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}}.ha-twitter-left .ha-tweet-avatar' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.ha-twitter-center .ha-tweet-avatar' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.ha-twitter-right .ha-tweet-avatar' => 'margin-left: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'profile_image_border',
				'selector' => '{{WRAPPER}} .ha-tweet-avatar',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'profile_image_box_shadow',
				'selector' => '{{WRAPPER}} .ha-tweet-avatar',
			]
		);

		$this->add_control(
			'name_heading',
			[
				'label' => __( 'Name & User Name', 'happy-elementor-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'label' => __( 'Typography', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-tweet-author-name',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'user_name_typography',
				'label' => __( 'Typography', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-tweet-username',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->start_controls_tabs( '_tabs_image_effects' );
		$this->start_controls_tab(
			'_tab_name_normal',
			[
				'label' => __( 'Normal', 'happy-elementor-addons' ),
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' => __( 'Name Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-tweet-author-name' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'user_name_color',
			[
				'label' => __( 'User Name Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-tweet-username' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover',
			[
				'label' => __( 'Hover', 'happy-elementor-addons' ),
			]
		);

		$this->add_control(
			'name__hover_color',
			[
				'label' => __( 'Name Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-tweet-author-name:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'user_name_hover_color',
			[
				'label' => __( 'User Name Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-tweet-username:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="ha-tweeter-feed-wrapper">
			<?php $this->twitter_feed_render($this->get_id(), $settings); ?>
		</div>
		<?php
	}

	protected function twitter_feed_render( $id, $settings ) {
		define( 'HA_TWEETS_TOKEN', '_tweet_token' );
		define( 'HA_TWEETS_CASH', '_tweet_cash' );

		$user_name = trim($settings['user_name']);
		if ( empty( $user_name ) || empty( $settings['consumer_key'] ) || empty( $settings['consumer_secret'] ) ) {
			return;
		}

		$transient_key = $id . '_' . $user_name . HA_TWEETS_CASH;
		$twitter_data = get_transient($transient_key);
		$credentials = base64_encode($settings['consumer_key'] . ':' . $settings['consumer_secret']);

		$messages = [];
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

			$body = json_decode( wp_remote_retrieve_body( $auth_response ) );
			update_option($id . '_' . $user_name . HA_TWEETS_TOKEN, $body->access_token);
			$token = $body->access_token;

			$tweets_response = wp_remote_get('https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=' . $user_name . '&count=999&tweet_mode=extended',
				array(
					'httpversion' => '1.1',
					'blocking' => true,
					'headers' => [ 'Authorization' => "Bearer $token", ],
				) );

			$twitter_data = json_decode( wp_remote_retrieve_body( $tweets_response ), true );
			set_transient($id . '_' . $user_name . HA_TWEETS_CASH, $twitter_data, 2 * MINUTE_IN_SECONDS ); // 2 * MINUTE_IN_SECONDS

		}

		 if ( array_key_exists( 'errors', $twitter_data ) ) {
			foreach ( $twitter_data['errors'] as $error ) {
				$messages['error'] = $error['message'];
			}
		} elseif ( count( $twitter_data ) < $settings['tweets_limit'] ) {
			$messages['item_limit'] = __( ' "Number of Tweets to show"  is more than your actual total Tweets\'s number. You have only ' . count( $twitter_data ) . ' Tweets', 'happy-elementor-addons' );
		}

		if ( !empty( $messages ) ) {
			foreach ($messages as $key => $message) {
				printf('<div class="ha-tweet-error-message">%1$s</div>', esc_html( $message ) );
			}
			return;
		}

		$query_settings = [
			'credentials' 		=> $credentials,
			'id' 				=> $id,
			'user_name' 		=> $user_name,
			'show_twitter_logo' => $settings['show_twitter_logo'],
			'tweets_limit' 		=> $settings['tweets_limit'],
			'sort_by' 			=> $settings['sort_by'],
			'show_user_image' 	=> $settings['show_user_image'],
			'show_name' 		=> $settings['show_name'],
			'show_user_name' 	=> $settings['show_user_name'],
			'show_date' 		=> $settings['show_date'],
			'show_favorite' 	=> $settings['show_favorite'],
			'show_retweet' 		=> $settings['show_retweet'],
		];
		$query_settings = json_encode($query_settings, true);

		switch ($settings['sort_by']) {
			case 'old-posts':
				usort($twitter_data, function ($a,$b) {
					if ( $a['created_at'] == $b['created_at'] ) return 0;
					return ( $a['created_at'] < $b['created_at'] ) ? -1 : 1 ;
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
		?>
		<div class="ha-tweet-items">
			<?php foreach ( $items as $item ) : ?>
				<div class="ha-tweet-item">

					<?php if ( $settings['show_twitter_logo'] == 'yes' ) : ?>
						<div class="ha-tweeter-feed-icon">
							<i class="hm hm-twitter-bird"></i>
						</div>
					<?php endif; ?>

					<div class="ha-tweet-author">
						<?php if ( $settings['show_user_image'] == 'yes' ) : ?>
							<a href="<?php echo esc_url( 'https://twitter.com/'.$user_name ); ?>">
								<img
									src="<?php echo esc_url( $item['user']['profile_image_url_https'] ); ?>"
									alt="<?php echo esc_attr( $item['user']['name'] ); ?>"
									class="ha-tweet-avatar"
								>
							</a>
						<?php endif; ?>

						<div class="ha-tweet-user">
							<?php if ( $settings['show_name'] == 'yes' ) : ?>
								<a href="<?php echo esc_url( 'https://twitter.com/'.$user_name ); ?>" class="ha-tweet-author-name">
									<?php echo esc_html( $item['user']['name'] ); ?>
								</a>
							<?php endif; ?>

							<?php if ( $settings['show_user_name'] == 'yes' ) : ?>
								<a href="<?php echo esc_url( 'https://twitter.com/'.$user_name ); ?>" class="ha-tweet-username">
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
									<i class="fa fa-heart-o"></i>
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
			<?php endforeach; ?>
		</div>

		<?php if ( $settings['load_more'] == 'yes' ) : ?>
			<div class="ha-twitter-load-more-wrapper">
				<button class="ha-twitter-load-more" data-settings="<?php echo esc_attr( $query_settings ); ?>" data-total="<?php echo esc_attr( count( $twitter_data ) ); ?>">
					<?php echo esc_html( $settings['load_more_text'] ); ?>
				</button>
			</div>
		<?php
		endif;
	}

}
