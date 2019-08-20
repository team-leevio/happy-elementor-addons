<?php
namespace Happy_Addons\Elementor;

defined( 'ABSPATH' ) || die();

class Widgets_Cache {

    const ELEMENTS_USAGE_META_KEY = '_elementor_elements_usage';

    protected $post_id;

    public function __construct( $post_id ) {
        $this->post_id = $post_id;
    }

    public function get_post_id() {
        return $this->post_id;
    }

    public function get_cache_data() {
        $data = get_post_meta( $this->get_post_id(), self::ELEMENTS_USAGE_META_KEY, true );
        if ( empty( $data ) ) {
            $data = $this->save();
        }
        return $data;
    }

    public function get() {
        $cache = $this->get_cache_data();
        return array_keys( array_filter( $cache, function( $widget_slug ) {
            return strpos( $widget_slug, 'ha-' ) !== false;
        }, ARRAY_FILTER_USE_KEY ) );
    }

    public function has() {
        $cache = $this->get();
        return count( $cache ) > 0;
    }

    public function delete() {
        delete_post_meta( $this->get_post_id(), self::ELEMENTS_USAGE_META_KEY );
    }

    public function save() {
        // Return early if post isn't built with elementor
        if ( ! Cache_Manager::is_built_with_elementor( $this->get_post_id() ) ) {
            return [];
        }

        $usage = [];
        $document = \Elementor\Plugin::instance()->documents->get( $this->get_post_id() );
        $data = $document ? $document->get_elements_data() : [];

        \Elementor\Plugin::instance()->db->iterate_data( $data, function ( $element ) use ( &$usage ) {
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

        update_post_meta( $this->get_post_id(), self::ELEMENTS_USAGE_META_KEY, $usage );
        return $usage;
    }
}
