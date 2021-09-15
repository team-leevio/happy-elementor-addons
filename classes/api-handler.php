<?php

namespace Happy_Addons\Elementor;

use Happy_Addons\Elementor\Dashboard;

use function PHPSTORM_META\type;

defined('ABSPATH') || die();

class Api_Handler
{
    public static $catwise_free_widget_map = [];

    public static $disabled_widgets = [];

    public static $active_widgets = [];

    public static function init()
    {
        add_action('rest_api_init', [__CLASS__, 'ha_wizard_routes']);
    }

    public static function ha_wizard_routes()
    {
        /* Get Ends */
        register_rest_route('happy/v1', '/wizard/preset/normal', array(
            'methods' => 'GET',
            'callback' => [__CLASS__,'ha_wizard_get_preset'],
            'permission_callback' => function () {
                return current_user_can( 'edit_others_posts' );
            }
        ));

        register_rest_route('happy/v1', '/wizard/preset/pro', array(
            'methods' => 'GET',
            'callback' => [__CLASS__,'ha_wizard_get_preset_pro'],
            'permission_callback' => function () {
                return current_user_can( 'edit_others_posts' );
            }
        ));

        register_rest_route('happy/v1', '/widgets/all', array(
            'methods' => 'GET',
            'callback' => [__CLASS__,'ha_wizard_get_widgets'],
            'permission_callback' => function () {
                return current_user_can( 'edit_others_posts' );
            }
        ));

        register_rest_route('happy/v1', '/widgets/disabled', array(
            'methods' => 'GET',
            'callback' => [__CLASS__,'my_awesome_func'],
            'permission_callback' => function () {
                return current_user_can( 'edit_others_posts' );
            }
        ));

        register_rest_route('happy/v1', '/features/all', array(
            'methods' => 'GET',
            'callback' => [__CLASS__,'my_awesome_func'],
            'permission_callback' => function () {
                return current_user_can( 'edit_others_posts' );
            }
        ));

        register_rest_route('happy/v1', '/feature/disabled', array(
            'methods' => 'GET',
            'callback' => [__CLASS__,'my_awesome_func'],
            'permission_callback' => function () {
                return current_user_can( 'edit_others_posts' );
            }
        ));

        /* Get Ends */
    }

    public static function my_awesome_func($data)
    {
        $posts = get_posts(array(
            'author' => $data['id'],
        ));

        if (empty($posts)) {
            return null;
        }

        return $posts;
    }

    public static function ha_wizard_get_preset($data){
        $response = [
            'dataType' => $data['userType']
        ];
        return $response;
    }

    public static function ha_wizard_get_preset_pro($data){
        $response = [
            'dataType' => $data['userType']
        ];
        return $response;
    }

    public static function ha_wizard_get_widgets($disabled=true){
        self::$disabled_widgets = Widgets_Manager::get_inactive_widgets();

		$default_active = Widgets_Manager::get_default_active_widget();

		self::$active_widgets = array_intersect($default_active,self::$disabled_widgets);

        $widgets = Widgets_Manager::get_widgets_map();
        unset( $widgets[ Widgets_Manager::get_base_widget_key() ] );

        array_walk($widgets, function($item, $key){
		    self::$catwise_free_widget_map[$item["cat"]][$key] = [
		        'slug' => $key,
		        'demo' => isset($item["demo"])? $item["demo"]: '',
		        'title' => $item["title"],
		        'icon' => $item["icon"],
		        'is_pro' => isset($item["is_pro"])? $item["is_pro"]: false,
                // 'is_active' => (!in_array($key, self::$disabled_widgets))?true:false
                'is_active' => (!in_array($key, self::$active_widgets))?true:false
		    ];

            sort(self::$catwise_free_widget_map[$item["cat"]]);
		});

        $response = [
            'all' => self::$catwise_free_widget_map,
            'disabled' => self::$disabled_widgets
        ];

        return $response;
    }
}

Api_Handler::init();
