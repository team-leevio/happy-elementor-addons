<?php

/**
 * MailChimp api
 *
 * @package Happy_Addons
 */

namespace Happy_Addons\Elementor\Widget\Mailchimp;

defined('ABSPATH') || die();

// use Happy_Addons\Elementor\Widget\Mailchimp;

class Mailchimp_api {

    private static $apiKey;
    public static $list_id;

    public static function set_ajax_call() {

        // self::$apiKey  = 'b5626fed144e863d6ae61f56e764d6fb-us17';
        self::$apiKey  = 'b5626fed144e863d6ae61f56e764d6fb-us1';

        add_action('wp_ajax_ha_mailchimp_ajax', [__CLASS__, 'mailchimp_prepare_ajax']);
        add_action('wp_ajax_nopriv_ha_mailchimp_ajax', [__CLASS__, 'mailchimp_prepare_ajax']);
    }

    public static function mailchimp_prepare_ajax() {

        $security = check_ajax_referer('happy_addons_nonce', 'security');

        if (!$security) return;

        $subscriber_data = $_POST;

        $auth = [
            'api_key' => self::$apiKey,
            'list_id' => $subscriber_data['list_id']
        ];

        parse_str(isset($subscriber_data['subscriber_info']) ? $subscriber_data['subscriber_info'] : '', $subsciber);

        $response = self::insert_subscriber_to_mailchimp($auth, $subsciber);

        echo wp_send_json($response);

        wp_die();
    }

    /**
     * request
     *
     * @param array $settings
     * @param array $submitted_data
     * @return array | int error
     */
    protected static function insert_subscriber_to_mailchimp($settings, $submitted_data) {
        $return = [];
        $auth = [
            'api_key' => ($settings['api_key'] != '') ? $settings['api_key'] : null,
            'list_id' => ($settings['list_id'] != '') ? $settings['list_id'] : null,

        ];

        $data = [
            'email_address' => (isset($submitted_data['email']) ? $submitted_data['email'] : ''),
            'status' => 'subscribed',
            'status_if_new' => 'subscribed',
            'merge_fields' => [
                'FNAME' => (isset($submitted_data['fname']) ? $submitted_data['fname'] : ''),
                'LNAME' => (isset($submitted_data['lname']) ? $submitted_data['lname'] : ''),
                'PHONE' => (isset($submitted_data['phone']) ? $submitted_data['phone'] : ''),
            ],
        ];

        $server = explode('-', $auth['api_key']);
        $url = 'https://' . $server[1] . '.api.mailchimp.com/3.0/lists/' . $auth['list_id'] . '/members/';

        $response = wp_remote_post(
            $url,
            [
                'method' => 'POST',
                'data_format' => 'body',
                'timeout' => 45,
                'headers' => [
                    'Authorization' => 'apikey ' . $auth['api_key'],
                    'Content-Type' => 'application/json; charset=utf-8'
                ],
                'body' => json_encode($data)
            ]
        );

        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            $return['status'] = 0;
            $return['msg'] = "Something went wrong: " . esc_html($error_message);
        } else {
            $body = (array) json_decode($response['body']);
            if ($body['status'] > 399 && $body['status'] < 600) {
                $return['status'] = 0;
                $return['msg'] = $body['title'];
            } else if($body['status'] == 'subscribed') {
                $return['status'] = 1;
                $return['msg'] = esc_html__('Your data inserted on Mailchimp.', 'happy-elementor-addons');
            }else {
                $return['status'] = 0;
                $return['msg'] = esc_html__('Something went wrong. Try again later.', 'happy-elementor-addons');
            }
        }

        return $return;
    }

    /**
     * Get request
     *
     * @return array all list
     */
    public static function get_mailchimp_lists() {

        $options = [];

        $server = explode('-', self::$apiKey);

        $url = 'https://' . $server[1] . '.api.mailchimp.com/3.0/lists';

        $response = wp_remote_post(
            $url,
            [
                'method' => 'GET',
                'data_format' => 'body',
                'timeout' => 45,
                'headers' => [

                    'Authorization' => 'apikey ' . self::$apiKey,
                    'Content-Type' => 'application/json; charset=utf-8'
                ],
                'body' => ''
            ]
        );

        if (is_array($response) && !is_wp_error($response)) {

            $body    = (array) json_decode($response['body']);
            $listed = isset($body['lists']) ? $body['lists'] : [];

            if (is_array($listed) && sizeof($listed) > 0) {

                $options = array_reduce($listed, function ($result, $item) {
                    $result[$item->id] = $item->name;
                    return $result;
                }, array());
            }
        }

        return  $options;
    }
}
