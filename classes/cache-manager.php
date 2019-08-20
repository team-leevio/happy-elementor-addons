<?php
namespace Happy_Addons\Elementor;

defined( 'ABSPATH' ) || die();

class Cache_Manager {

    public static function init() {
        add_action( 'save_post', [ __CLASS__, 'cache_widgets' ] );
    }

    public static function cache_widgets( $post_id ) {
        if ( ! self::is_built_with_elementor( $post_id ) ) {
            return;
        }

        if ( version_compare( ELEMENTOR_VERSION, '2.6.5', '<=' ) ) {
            $widgets_cache = new Widgets_Cache( $post_id );
            $widgets_cache->save();
        }

        // Delete to regenerate cache file
        $assets_cache = new Assets_Cache( $post_id );
        $assets_cache->delete();
    }

    public static function is_built_with_elementor( $post_id ) {
        return \Elementor\Plugin::instance()->db->is_built_with_elementor( $post_id );
    }

    public static function is_editing_mode() {
        return (
            \Elementor\Plugin::$instance->editor->is_edit_mode() ||
            \Elementor\Plugin::$instance->preview->is_preview_mode() ||
            is_preview()
        );
    }
}
