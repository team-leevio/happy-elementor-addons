<?php
namespace Happy_Addons\Elementor;

defined( 'ABSPATH' ) || die();

class Cache_Manager {

    private static $widgets_cache;

    public static function init() {
        add_action( 'elementor/editor/after_save', [ __CLASS__, 'cache_widgets' ], 10, 2 );
    }

    public static function cache_widgets( $post_id, $data ) {
        self::$widgets_cache = new Widgets_Cache( $post_id, $data );
        self::$widgets_cache->save();

        // Delete to regenerate cache file
        $assets_cache = new Assets_Cache( $post_id, self::$widgets_cache );
        $assets_cache->delete();
    }

    public static function is_editing_mode() {
        return (
            ha_elementor()->editor->is_edit_mode() ||
            ha_elementor()->preview->is_preview_mode() ||
            is_preview()
        );
    }

    public static function is_built_with_elementor( $post_id ) {
        return ha_elementor()->db->is_built_with_elementor( $post_id );
    }

    public static function should_enqueue( $post_id ) {
        if ( ! ha_is_happy_mode_enabled() ||
            ! self::is_built_with_elementor( $post_id ) ||
            self::is_editing_mode() ) {
            return false;
        }

        self::$widgets_cache = new Widgets_Cache( $post_id );
        if ( ! self::$widgets_cache->has() ) {
            return false;
        }

        return true;
    }

    public static function enqueue( $post_id ) {
        $assets_cache = new Assets_Cache( $post_id, self::$widgets_cache );
        $assets_cache->enqueue_libraries();
        $assets_cache->enqueue();
        wp_enqueue_script( 'happy-elementor-addons' );
    }
}
