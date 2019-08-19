<?php
namespace Happy_Addons\Elementor;

defined( 'ABSPATH' ) || die();

class Assets_Cache_Manager {

    private static $upload_path;

    private static $upload_url;

    private static $cache_dir;

    private static $cache_dir_name;

    private static $cache_url;

    public static function init() {
        $upload_dir        = wp_upload_dir();
        self::$upload_path = trailingslashit( $upload_dir['basedir'] );
        self::$upload_url = trailingslashit( $upload_dir['baseurl'] );;

        self::$cache_dir_name = trailingslashit( 'happyaddons/cache' );

        self::$cache_dir = self::$upload_path . self::$cache_dir_name;
        self::$cache_url = self::$upload_url . self::$cache_dir_name;
    }

    public static function get_file_name( $post_id ) {
        return self::$cache_dir . "post-{$post_id}.css";
    }

    public static function get_used_widgets( $post_id ) {
        $all_widgets = self::get_all_used_widgets( $post_id );
        return array_keys( array_filter( $all_widgets, function( $widget_slug ) {
            return strpos( $widget_slug, 'ha-' ) !== false;
        }, ARRAY_FILTER_USE_KEY ) );
    }

    private static function get_all_used_widgets( $post_id ) {
        if ( ! Widgets_Cache_Manager::has( $post_id ) ) {
            return Widgets_Cache_Manager::save( $post_id );
        }

        return Widgets_Cache_Manager::get( $post_id );
    }

    public static function has_used_widgets( $post_id ) {
        return count( self::get_used_widgets( $post_id ) ) > 0;
    }

    public static function should_start() {
        global $post;
        if ( ! isset( $post ) || empty( $post ) ) {
            return false;
        }
        return ( Widgets_Cache_Manager::has_support( $post->ID ) && self::has_used_widgets( $post->ID ) );
    }

    private static function does_file_exists( $post_id ) {
        $cached_file = self::get_file_name( $post_id );
        return file_exists( $cached_file );
    }

    public static function has( $post_id ) {
        if ( ! self::does_file_exists( $post_id ) ) {
            self::save( $post_id );
        }
        return self::does_file_exists( $post_id );
    }

    public static function enqueue() {
        global $post;
        if ( ! isset( $post ) || empty( $post ) ) {
            return;
        }

        $post_id = $post->ID;

        if ( self::has( $post_id ) ) {
            wp_enqueue_style(
                'happy-elementor-addons-' . $post_id,
                self::$cache_url . "post-{$post_id}.css",
                [ 'elementor-frontend' ],
                Base::VERSION . '.' . get_post_modified_time( 'U', false, $post )
            );
        }
    }

    public static function enqueue_libraries() {
        global $post;
        if ( ! isset( $post ) || empty( $post ) ) {
            return;
        }

        $post_id = $post->ID;

        $widgets = self::get_used_widgets( $post_id );

        if ( is_array( $widgets ) && $widgets ) {
            $widgets_map = Widgets_Manager::get_widgets_map();
            $base_widget = isset( $widgets_map[ Widgets_Manager::get_base_widget_key() ] ) ? $widgets_map[ Widgets_Manager::get_base_widget_key() ] : [];

            if ( isset( $base_widget['vendor']['css'] ) && is_array( $base_widget['vendor']['css'] ) ) {
                foreach ( $base_widget['vendor']['css'] as $vendor_css_handle ) {
                    wp_enqueue_style( $vendor_css_handle );
                }
            }

            if ( isset( $base_widget['vendor']['js'] ) && is_array( $base_widget['vendor']['js'] ) ) {
                foreach ( $base_widget['vendor']['js'] as $vendor_js_handle ) {
                    wp_enqueue_script( $vendor_js_handle );
                }
            }

            foreach ( $widgets as $widget ) {
                $map_key = substr( $widget, 3 );
                if ( ! isset( $widgets_map[ $map_key ], $widgets_map[ $map_key ]['vendor'] ) ) {
                    continue;
                }
                $vendor = $widgets_map[ $map_key ]['vendor'];

                if ( isset( $vendor['css'] ) && is_array( $vendor['css'] ) ) {
                    foreach ( $vendor['css'] as $vendor_css_handle ) {
                        wp_enqueue_style( $vendor_css_handle );
                    }
                }

                if ( isset( $vendor['js'] ) && is_array( $vendor['js'] ) ) {
                    foreach ( $vendor['js'] as $vendor_js_handle ) {
                        wp_enqueue_script( $vendor_js_handle );
                    }
                }
            }
        }
    }

    public static function save( $post_id ) {
        if ( ! apply_filters( 'happyaddons_ondemand_asset_compiling', true ) ) {
            return;
        }

        $widgets = self::get_used_widgets( $post_id );

        if ( is_array( $widgets ) && $widgets ) {
            $widgets_map = Widgets_Manager::get_widgets_map();
            $base_widget = isset( $widgets_map[ Widgets_Manager::get_base_widget_key() ] ) ? $widgets_map[ Widgets_Manager::get_base_widget_key() ] : [];
            $data = '';

            // Get common css styles
            if ( isset( $base_widget['css'] ) && is_array( $base_widget['css'] ) ) {
                self::get_files_contents( $base_widget['css'], $data );
            }

            // Get widget specific styles
            foreach ( $widgets as $_widget ) {
                $map_key = substr( $_widget, 3 );

                if ( ! isset( $widgets_map[ $map_key ], $widgets_map[ $map_key ]['css'] ) ) {
                    continue;
                }
                self::get_files_contents( $widgets_map[ $map_key ]['css'], $data );
            }

//            $data .= sprintf( '/** Compiled CSS for: %s **/', implode(', ', array_keys( $compiled ) ) );

            if ( ! is_dir( self::$cache_dir ) ) {
                @mkdir( self::$cache_dir, 0777, true );
            }

            $filename = self::get_file_name( $post_id );
            file_put_contents( $filename, $data );
        }
    }

    private static function get_files_contents( $files, &$data ) {
        foreach ( $files as $file ) {
            if ( file_exists( HAPPY_DIR_PATH . "assets/css/widgets/{$file}.min.css" ) ) {
                $data .= file_get_contents( HAPPY_DIR_PATH . "assets/css/widgets/{$file}.min.css" );
            };
        }
    }

    public static function delete( $post_id ) {
        if ( self::does_file_exists( $post_id ) ) {
            unlink( self::get_file_name( $post_id ) );
        }
    }

    public static function delete_all() {
        $files = glob( self::$cache_dir . '/*' );
        foreach( $files as $file ) {
            if ( is_file( $file ) ) {
                unlink( $file );
            }
        }
    }

    public static function in_development() {
        return (
            \Elementor\Plugin::$instance->editor->is_edit_mode() ||
            \Elementor\Plugin::$instance->preview->is_preview_mode() ||
            is_preview()
        );
    }
}
