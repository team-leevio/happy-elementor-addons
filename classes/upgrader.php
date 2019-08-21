<?php
namespace Happy_Addons\Elementor;

defined( 'ABSPATH' ) || die();

class Upgrader {

    const DB_VERSION_KEY = 'happyaddons_version';

    public static function init() {
        if ( ! self::get_db_version() || version_compare( self::get_db_version(), HAPPY_ADDONS_VERSION, '<' ) ) {
            self::enable_icon_migration();
            self::set_db_version();
        }
    }

    protected static function enable_icon_migration() {
        add_option( 'elementor_icon_manager_needs_update', 'yes' );
    }

    protected static function get_db_version() {
        return get_option( self::DB_VERSION_KEY, false );
    }

    protected static function set_db_version() {
        return add_option( self::DB_VERSION_KEY, HAPPY_ADDONS_VERSION );
    }
}
