<?php

namespace Happy_Addons\Elementor\Assets;

use Happy_Addons\Elementor\Base;
use Happy_Addons\Elementor\Manager\Widgets;

defined( 'ABSPATH' ) || die();

class OnDemand_Loader {

    const ELEMENTS_USAGE_META_KEY = '_elementor_elements_usage';

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

        add_action( 'save_post', [__CLASS__, 'save_elements_usage'] );
	}

    /**
     * Get used elements in a post
     *
     * @param $post_id
     * @return array
     */
	public static function get_elements_usage( $post_id ) {
        $usage = self::get_only_elements_usage( $post_id );
        if ( ! $usage ) {
            self::save_elements_usage( $post_id );
            $usage = self::get_only_elements_usage( $post_id );
        }
        return $usage;
    }

    private static function get_only_elements_usage( $post_id ) {
        $usage = get_post_meta( $post_id, self::ELEMENTS_USAGE_META_KEY, true );
        return ( is_array( $usage ) ? $usage : [] );
    }

    /**
     * Save used elements
     *
     * @param $post_id
     * @return void
     */
    public static function save_elements_usage( $post_id ) {
        /**
         * Before version 2.6.5 elementor didn't store elements usage data
         */
        if ( version_compare( ELEMENTOR_VERSION, '2.6.5', '<=' ) || ! self::get_only_elements_usage( $post_id ) ) {
            $usage = [];
            //we need to populate _elementor_elements_usage;
            $document = \Elementor\Plugin::$instance->documents->get( $post_id );
            $data = $document ? $document->get_elements_data() : [];

            \Elementor\Plugin::$instance->db->iterate_data( $data, function ( $element ) use ( & $usage ) {
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
            self::clean_only_cache( $post_id );
        }
    }

    public static function get_supported_types() {
        return get_option( 'elementor_cpt_support', ['post', 'page'] );
    }

    public static function get_compiled_asset() {
        global $post;
        if ( ! isset( $post ) || empty( $post ) ) {
            return;
        }

        $filename = self::$cache_dir . "post-{$post->ID}.css";

        if ( ! file_exists( $filename ) ) {
            self::compile_assets( $post->ID );
        }

        if ( file_exists( $filename ) ) {
            return [
                'url' => self::$cache_url . "post-{$post->ID}.css",
                'version' => Base::VERSION . '.' . get_post_modified_time( 'U', false, $post ),
            ];
        }

        return [];
    }

    public static function get_self_elements_usage( $post_id ) {
        return array_keys( array_filter( self::get_elements_usage( $post_id ), function( $widget_name ) {
            return strpos( $widget_name, 'ha-' ) !== false;
        }, ARRAY_FILTER_USE_KEY ) );
    }

	public static function compile_assets( $post_id ) {
		if ( ! apply_filters( 'happyaddons_ondemand_asset_compiling', true ) ) {
		    return;
		}

		if ( ! in_array( get_post_type( $post_id ), self::get_supported_types() ) ) {
		    return;
        }

        $filename = self::$cache_dir . "post-{$post_id}.css";
        $widgets = self::get_self_elements_usage( $post_id );

        if ( $widgets && is_array( $widgets ) ) {
            $widgets_map = Widgets::get_widgets_map();
            $base_widget = isset( $widgets_map[ Widgets::get_base_widget_key() ] ) ? $widgets_map[ Widgets::get_base_widget_key() ] : [];
            $data = '';

            if ( isset( $base_widget['css'] ) && is_array( $base_widget['css'] ) ) {
                foreach ( $base_widget['css'] as $_file_name_prefix ) {
                    if ( file_exists( HAPPY_DIR_PATH . "assets/css/widgets/{$_file_name_prefix}.min.css" ) ) {
                        $data .= file_get_contents( HAPPY_DIR_PATH . "assets/css/widgets/{$_file_name_prefix}.min.css" );
                    };
                }
            }

            foreach ( $widgets as $_widget ) {
                $map_key = substr( $_widget, 3 );
                if ( ! isset( $widgets_map[ $map_key ] ) ) {
                    continue;
                }

                if ( ! isset( $widgets_map[ $map_key ]['css'] ) ) {
                    continue;
                }

                foreach ( $widgets_map[ $map_key ]['css'] as $_file_name_prefix ) {
                    if ( file_exists( HAPPY_DIR_PATH . "assets/css/widgets/{$_file_name_prefix}.min.css" ) ) {
                        $data .= file_get_contents( HAPPY_DIR_PATH . "assets/css/widgets/{$_file_name_prefix}.min.css" );
                    };
                }
            }

            if ( ! is_dir( self::$cache_dir ) ) {
                @mkdir( self::$cache_dir, 0777, true );
            }

            file_put_contents( $filename, $data );
        }
	}

	public static function load_used_libraries() {
        global $post;
        if ( ! isset( $post ) || empty( $post ) ) {
            return;
        }

        $widgets_used = self::get_elements_usage( $post->ID );

        if ( is_array( $widgets_used ) ) {
            $widgets = Widgets::get_widgets_map();

            foreach( $widgets as $widget => $data ) {
                if ( ! isset( $data['vendor'] ) || ! is_array( $data['vendor'] ) ) {
                    continue;
                }

                // Handle common assets only
                if ( Widgets::get_base_widget_key() === $widget ) {
                    if ( isset( $data['vendor']['css'] ) && is_array( $data['vendor']['css'] ) ) {
                        foreach ( $data['vendor']['css'] as $vendor_css_handle ) {
                            wp_enqueue_style( $vendor_css_handle );
                        }
                    }

                    if ( isset( $data['vendor']['js'] ) && is_array( $data['vendor']['js'] ) ) {
                        foreach ( $data['vendor']['js'] as $vendor_css_handle ) {
                            wp_enqueue_script( $vendor_css_handle );
                        }
                    }
                    continue;
                }

                // Handle widgets assets only
                $widget_id = 'ha-' . $widget;

                if ( ! array_key_exists( $widget_id, $widgets_used ) ) {
                    continue;
                }

                if ( is_array( $data['vendor']['css'] ) ) {
                    foreach ( $data['vendor']['css'] as $vendor_css_handle ) {
                        wp_enqueue_style( $vendor_css_handle );
                    }
                }

                if ( is_array( $data['vendor']['js'] ) ) {
                    foreach ( $data['vendor']['js'] as $vendor_css_handle ) {
                        wp_enqueue_script( $vendor_css_handle );
                    }
                }
            }
        }
    }

    public static function clean_all_cache() {
        $files = glob( self::$cache_dir . '/*' );
        foreach( $files as $file ) {
            if ( is_file( $file ) ) {
                unlink( $file );
            }
        }
    }

    public static function clean_only_cache( $post_id ) {
        $filename = self::$cache_dir . "post-{$post_id}.css";
        if ( file_exists( $filename ) ) {
            unlink( $filename );
        }
    }
}
