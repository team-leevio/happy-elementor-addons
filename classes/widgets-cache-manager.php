<?php
namespace Happy_Addons\Elementor;

defined( 'ABSPATH' ) || die();

class Widgets_Cache_Manager {

    const ELEMENTS_USAGE_META_KEY = '_elementor_elements_usage';

    public static function init() {
        add_action( 'save_post', [ __CLASS__, 'save_post_callback' ] );
    }

    public static function save_post_callback( $post_id ) {
        if ( version_compare( ELEMENTOR_VERSION, '2.6.5', '<=' ) ) {
            self::save( $post_id );
        }
        Assets_Cache_Manager::delete( $post_id );
    }

    public static function has_support( $post_id ) {
        return \Elementor\Plugin::instance()->db->is_built_with_elementor( $post_id );
    }

    public static function get( $post_id ) {
        return get_post_meta( $post_id, self::ELEMENTS_USAGE_META_KEY, true );
    }

    public static function has( $post_id ) {
        $usage = self::get( $post_id );
        return ( ! empty( $usage ) );
    }

    public static function save( $post_id ) {
        // Return early if post isn't built with elementor
        if ( ! self::has_support( $post_id ) ) {
            return [];
        }

        $usage = [];
        $document = \Elementor\Plugin::instance()->documents->get( $post_id );
        $data = $document ? $document->get_elements_data() : [];

        \Elementor\Plugin::instance()->db->iterate_data( $data, function ( $element ) use ( & $usage ) {
            if ( empty( $element['widgetType'] ) ) {
                $type = $element['elType'];
            } else {
                $type = $element['widgetType'];
            }

            if ( ! isset( $usage[ $type ] ) ) {
                $usage[ $type ] = 0;
            }

            $usage[ $type ] ++;

            return $element;
        } );

        update_post_meta( $post_id, self::ELEMENTS_USAGE_META_KEY, $usage );

        return $usage;
    }

    public static function delete( $post_id ) {
        delete_post_meta( $post_id, self::ELEMENTS_USAGE_META_KEY );
    }
}
