<?php

/**
 * MailChimp api
 *
 * @package Happy_Addons
 */

namespace Happy_Addons\Elementor\Widget\Mailchimp;

defined('ABSPATH') || die();

class Mailchimp_api {

    private static $apiKey;
    private static $credentials;
    public static $list_id;

    /**
     * request
     *
     * @param array $submitted_data
     * @return array | int error
     */
    public static function insert_subscriber_to_mailchimp($submitted_data) {
        $return = [];

        self::$credentials = ha_get_credentials('mailchimp');;

        self::$apiKey  = isset(self::$credentials['api'])? self::$credentials['api']: '';

        $widget_settings = ha_get_ele_widget_settings($_POST['post_id'], $_POST['widget_id']);

        $str_tags = isset($widget_settings['mailchimp_list_tags'])? $widget_settings['mailchimp_list_tags']: '';
        $tags = explode(', ', $str_tags);

        $auth = [
            'api_key' => self::$apiKey,
            'list_id' => $_POST['list_id'],
        ];

        if($widget_settings['mailchimp_api_choose'] == 'custom') {
            $auth['api_key'] = $widget_settings['mailchimp_api'];
        }

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

        if(!empty($str_tags)) {
            $data['tags'] = $tags;
        }

        $server = explode('-', $auth['api_key']);

        if(!isset($server[1])) {
            return ['status' => 0, 'msg' => esc_html__('Invalid API key.', 'happy-elementor-addons')];
        }

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
    public static function get_mailchimp_lists($api = null) {

        self::$apiKey  = isset(self::$credentials['api'])? self::$credentials['api']: '';

        $options = [];

        if($api != null) {
            self::$apiKey = $api;
        }

        $server = explode('-', self::$apiKey);

        if(!isset($server[1])) return 0;

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
                    // extra space is needed to maintain order in elementor control
                    $result[' '.$item->id] = $item->name;
                    return $result;
                }, array());
            }
        }

        return  $options;
    }
}
