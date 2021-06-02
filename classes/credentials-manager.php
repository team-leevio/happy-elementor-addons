<?php

namespace Happy_Addons\Elementor;

defined('ABSPATH') || die();

class Credentials_Manager {
	const CREDENTIALS_DB_KEY = 'happyaddons_credentials';

	/**
	 * Initialize
	 */
	public static function init() {
		include_once HAPPY_ADDONS_DIR_PATH . 'extensions/column-extended.php';
		include_once HAPPY_ADDONS_DIR_PATH . 'extensions/widgets-extended.php';

		if (is_user_logged_in() && ha_is_adminbar_menu_enabled()) {
			include_once HAPPY_ADDONS_DIR_PATH . 'classes/admin-bar.php';
		}

		if (is_user_logged_in() && ha_is_happy_clone_enabled()) {
			include_once HAPPY_ADDONS_DIR_PATH . 'classes/clone-handler.php';
		}

		$credentials = self::get_credentials();

		foreach (self::get_local_credentials_map() as $feature_key => $data) {
			if (!in_array($feature_key, $credentials)) {
				self::enable_feature($feature_key);
			}
		}

		foreach (self::get_pro_credentials_map() as $feature_key => $data) {
			if (in_array($feature_key, $credentials)) {
				self::disable_pro_feature($feature_key);
			}
		}
	}

	public static function get_credentials_map() {
		$credentials_map = [];

		$local_credentials_map = self::get_local_credentials_map();
		$credentials_map = array_merge($credentials_map, $local_credentials_map);

		return apply_filters('happyaddons_get_credentials_map', $credentials_map);
	}

	public static function get_credentials() {
		return get_option(self::CREDENTIALS_DB_KEY, []);
	}

	public static function save_credentials($credentials = []) {
		update_option(self::CREDENTIALS_DB_KEY, $credentials);
	}

	/**
	 * Get the pro credentials map for dashboard only
	 *
	 * @return array
	 */
	public static function get_pro_credentials_map() {
		return [
			'display-conditions' => [
				'title' => __('Display Condition', 'happy-elementor-addons'),
				'icon' => 'hm hm-display-condition',
				'is_pro' => true,
			],
			'image-masking' => [
				'title' => __('Image Masking', 'happy-elementor-addons'),
				'icon' => 'hm hm-image-masking',
				'is_pro' => true,
			],
			'happy-particle-effects' => [
				'title' => __('Happy Particle Effects', 'happy-elementor-addons'),
				'icon' => 'hm hm-spark',
				'is_pro' => true,
			]
		];
	}

	/**
	 * Get the free credentials map
	 *
	 * @return array
	 */
	public static function get_local_credentials_map() {
		return [
			'mailchimp' => [
				'title' => __('MailChimp', 'happy-elementor-addons'),
				'icon' => 'hm hm-layer',
				'fiels' => [
					[
						'label' => esc_html__('MailChimp API', 'happy-elementor-addons'),
						'type' => 'text',
						'name' => 'mailchimp_api',
					],
				],
				'demo' => 'https://happyaddons.com/mailchimp/',
				'help' => 'https://happyaddons.com/mailchimp/',
				'is_pro' => false,
			],
			'instagram' => [
				'title' => __('Instagram', 'happy-elementor-addons'),
				'icon' => 'hm hm-layer',
				'fiels' => [
					[
						'label' => esc_html__('Instagram API', 'happy-elementor-addons'),
						'type' => 'text',
						'name' => 'mailchimp_api',
					],
				],
				'demo' => 'https://happyaddons.com/mailchimp/',
				'help' => 'https://happyaddons.com/mailchimp/',
				'is_pro' => false,
			],
		];
	}

	protected static function enable_feature($feature_key) {
		$feature_file = HAPPY_ADDONS_DIR_PATH . 'extensions/' . $feature_key . '.php';

		if (is_readable($feature_file)) {
			include_once($feature_file);
		}
	}

	protected static function disable_pro_feature($feature_key) {
		switch ($feature_key) {
			case 'display-conditions':
				add_filter('happyaddons/extensions/display_condition', '__return_false');
				break;

			case 'image-masking':
				add_filter('happyaddons/extensions/image_masking', '__return_false');
				break;

			case 'happy-particle-effects':
				add_filter('happyaddons/extensions/happy_particle_effects', '__return_false');
				break;
		}
	}
}

Extensions_Manager::init();
