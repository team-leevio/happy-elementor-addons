<?php
namespace Happy_Addons\Elementor\Admin;

use Elementor\Base;

defined( 'ABSPATH' ) || die();

class Dashboard {

    public static function init() {
        add_action( 'admin_menu', [__CLASS__, 'happy_admin_menu'], 600 );
    }

    public static function happy_admin_menu() {
        add_submenu_page(
            'elementor',
            __( 'Happy Addons' ),
            __( 'Happy Addons' ),
            'manage_options',
            'happy-settings',
            array( __CLASS__, 'happy_admin_settings' )
        );
    }

    public static function happy_admin_settings() {
        echo '<div class="happy-settings">';

            include_once HAPPY_DIR_PATH . 'inc/admin-templates/widgets.php';

        echo '</div>';
    }

//    public function save_settings() {
//
//    }

}