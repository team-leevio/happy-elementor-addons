<?php
namespace Happy_Addons\Elementor;

use Elementor\Core\Files\CSS\Post as Post_CSS;

defined( 'ABSPATH' ) || die();

class Cache_Manager {

    private static $widgets_cache;

    public static function init() {
        add_action( 'elementor/editor/after_save', [ __CLASS__, 'cache_widgets' ], 10, 2 );
    }

    public static function cache_widgets( $post_id, $data ) {
        if ( ! self::is_published( $post_id ) ) {
            return;
        }

        self::$widgets_cache = new Widgets_Cache( $post_id, $data );
        self::$widgets_cache->save();

        // Delete to regenerate cache file
        $assets_cache = new Assets_Cache( $post_id, self::$widgets_cache );
        $assets_cache->delete();
    }

    public static function is_published( $post_id ) {
        return get_post_status( $post_id ) === 'publish';
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
            ! self::is_published( $post_id ) ||
            self::is_editing_mode() ) {
            return false;
        }

        self::$widgets_cache = new Widgets_Cache( $post_id );
        if ( ! self::$widgets_cache->has() ) {
            return false;
        }

        return true;
    }

    public static function enqueue_fa5_fonts( $post_id ) {
        $post_css = new Post_CSS( $post_id );
        $meta = $post_css->get_meta();
        if ( ! empty( $meta['icons'] ) ) {
            $icons_types = \Elementor\Icons_Manager::get_icon_manager_tabs();
            foreach ( $meta['icons'] as $icon_font ) {
                if ( ! isset( $icons_types[ $icon_font ] ) ) {
                    continue;
                }
                ha_elementor()->frontend->enqueue_font( $icon_font );
            }
        }
    }

    public static function enqueue( $post_id ) {
        $assets_cache = new Assets_Cache( $post_id, self::$widgets_cache );
        $assets_cache->enqueue_libraries();
        $assets_cache->enqueue();
        self::enqueue_fa5_fonts( $post_id );
        wp_enqueue_script( 'happy-elementor-addons' );
    }
}
