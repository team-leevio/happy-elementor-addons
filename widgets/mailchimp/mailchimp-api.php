<?php

/**
 * MailChimp api
 *
 * @package Happy_Addons
 */

namespace Happy_Addons\Elementor\Widget\Mailchimp;

defined( 'ABSPATH' ) || die();

class Mailchimp_Api {

	private static $api_key;
	private static $credentials;
	public static $list_id;

	/**
	 * request
	 *
	 * @param array $submitted_data
	 * @return array | int error
	 */
	public static function insert_subscriber_to_mailchimp( $submitted_data ) {
		$return = [];

		// Rate limit: allow only a few submissions per minute per IP.
		$rate_key = 'ha_mc_rl_' . md5( isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '' );
		$attempts = (int) get_transient( $rate_key );
		if ( $attempts >= 10 ) {
			return [
				'status' => 0,
				'msg'    => esc_html__( 'Too many requests. Please wait a minute and try again.', 'happy-elementor-addons' ),
			];
		}
		set_transient( $rate_key, $attempts + 1, MINUTE_IN_SECONDS );

		if ( empty( $submitted_data['email'] ) || ! is_email( $submitted_data['email'] ) ) {
			return [
				'status' => 0,
				'msg'    => esc_html__( 'Please enter a valid email address.', 'happy-elementor-addons' ),
			];
		}

		self::$credentials = ha_get_credentials( 'mailchimp' );

		self::$api_key = isset( self::$credentials['api'] ) ? self::$credentials['api'] : '';

		// Never trust the client with the audience id: resolve the widget
		// saved on this site and use the audience configured in its settings.
		$post_id   = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
		$widget_id = isset( $_POST['widget_id'] ) ? sanitize_text_field( wp_unslash( $_POST['widget_id'] ) ) : '';

		// Only published content may subscribe anonymously; users who can
		// edit the post may still test the form from a draft preview.
		if ( ! $post_id || ( 'publish' !== get_post_status( $post_id ) && ! current_user_can( 'edit_post', $post_id ) ) ) {
			return [
				'status' => 0,
				'msg'    => esc_html__( 'Invalid request.', 'happy-elementor-addons' ),
			];
		}

		$widget_settings = $widget_id ? ha_get_ele_widget_settings( $post_id, $widget_id ) : '';

		$list_id = '';
		if ( ! empty( $widget_settings['mailchimp_lists'] ) ) {
			$lists   = $widget_settings['mailchimp_lists'];
			$list_id = is_array( $lists ) && isset( $lists[0] ) ? $lists[0] : $lists;
			$list_id = ltrim( $list_id ); // Select2 saves ids with a leading space.
		}

		// Mailchimp audience ids are 32 hex characters; refuse anything
		// else before it can reach the API url.
		if ( ! preg_match( '/^[a-f0-9]{32}$/i', (string) $list_id ) ) {
			return [
				'status' => 0,
				'msg'    => esc_html__( 'Invalid request.', 'happy-elementor-addons' ),
			];
		}

		$tags = '';
		if( !empty($widget_settings) ) {
			$str_tags = isset( $widget_settings['mailchimp_list_tags'] ) ? $widget_settings['mailchimp_list_tags'] : '';
			$tags     = explode( ', ', $str_tags );
		}

		$auth = [
			'api_key' => self::$api_key,
			'list_id' => $list_id,
		];

		if ( isset($widget_settings['mailchimp_api_choose']) && $widget_settings['mailchimp_api_choose'] == 'custom' ) {
			$auth['api_key'] = $widget_settings['mailchimp_api'];
		}

		$data = [
			'email_address' => ( isset( $submitted_data['email'] ) ? $submitted_data['email'] : '' ),
			'status'        => ( ( isset( $widget_settings['enable_double_opt_in'] ) && $widget_settings['enable_double_opt_in'] == 'yes' ) ? 'pending' : 'subscribed' ),
			'status_if_new' => ( ( isset( $widget_settings['enable_double_opt_in'] ) && $widget_settings['enable_double_opt_in'] == 'yes' ) ? 'pending' : 'subscribed' ),
			'merge_fields'  => [
				'FNAME' => ( isset( $submitted_data['fname'] ) ? $submitted_data['fname'] : '' ),
				'LNAME' => ( isset( $submitted_data['lname'] ) ? $submitted_data['lname'] : '' ),
				'PHONE' => ( isset( $submitted_data['phone'] ) ? $submitted_data['phone'] : '' ),
			],
		];

		if ( ! empty( $str_tags ) ) {
			$data['tags'] = $tags;
		}

		$server = explode( '-', $auth['api_key'] );

		if ( ! isset( $server[1] ) || ! preg_match( '/^[a-zA-Z0-9]+$/', $server[1] ) ) {
			return ['status' => 0, 'msg' => esc_html__( 'Invalid API key.', 'happy-elementor-addons' )];
		}

		$url = 'https://' . $server[1] . '.api.mailchimp.com/3.0/lists/' . $auth['list_id'] . '/members/';

		$response = wp_remote_post(
			$url,
			[
				'method'      => 'POST',
				'data_format' => 'body',
				'timeout'     => 45,
				'headers'     => [
					'Authorization' => 'apikey ' . $auth['api_key'],
					'Content-Type'  => 'application/json; charset=utf-8',
				],
				'body'        => json_encode( $data ),
			]
		);

		if ( is_wp_error( $response ) ) {
			$error_message    = $response->get_error_message();
			$return['status'] = 0;
			$return['msg']    = 'Something went wrong: ' . esc_html( $error_message );
		} else {
			$body   = json_decode( wp_remote_retrieve_body( $response ), true );
			$body   = is_array( $body ) ? $body : [];
			$status = isset( $body['status'] ) ? $body['status'] : '';

			if ( is_int( $status ) && $status > 399 && $status < 600 ) {
				$return['status'] = 0;
				$return['msg']    = isset( $body['title'] ) ? sanitize_text_field( $body['title'] ) : esc_html__( 'Something went wrong. Try again later.', 'happy-elementor-addons' );
			} elseif ( $status === 'subscribed' ) {
				$return['status'] = 1;
				$return['msg']    = isset($widget_settings['mailchimp_success_message']) ? $widget_settings['mailchimp_success_message']: "";
			} elseif ( $status === 'pending' ) {
				$return['status'] = 1;
				$return['msg']    = esc_html__( 'Confirm your subscription from your email.', 'happy-elementor-addons' );
			} else {
				$return['status'] = 0;
				$return['msg']    = esc_html__( 'Something went wrong. Try again later.', 'happy-elementor-addons' );
			}
		}

		return $return;
	}

	/**
	 * Get request
	 *
	 * @return array all list
	 */
	public static function get_mailchimp_lists( $api = null ) {

		self::$credentials = ha_get_credentials( 'mailchimp' );

		self::$api_key = isset( self::$credentials['api'] ) ? self::$credentials['api'] : '';

		$options = [];

		if ( $api != null ) {
			self::$api_key = $api;
		}

		$server = explode( '-', self::$api_key );

		if ( ! isset( $server[1] ) || ! preg_match( '/^[a-zA-Z0-9]+$/', $server[1] ) ) {
			return 0;
		}

		$url = 'https://' . $server[1] . '.api.mailchimp.com/3.0/lists';

		$response = wp_remote_post(
			$url,
			[
				'method'      => 'GET',
				'data_format' => 'body',
				'timeout'     => 45,
				'headers'     => [

					'Authorization' => 'apikey ' . self::$api_key,
					'Content-Type'  => 'application/json; charset=utf-8',
				],
				'body'        => '',
			]
		);

		if ( is_array( $response ) && ! is_wp_error( $response ) ) {

			$body   = (array) json_decode( $response['body'] );
			$listed = isset( $body['lists'] ) ? $body['lists'] : [];

			if ( is_array( $listed ) && count( $listed ) > 0 ) {

				$options = array_reduce(
					$listed,
					function ( $result, $item ) {
						// extra space is needed to maintain order in elementor control
						$result[ ' ' . $item->id ] = $item->name;
						return $result;
					},
					array()
				);
			}
		}

		return $options;
	}
}
