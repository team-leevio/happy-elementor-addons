<?php
namespace Happy_Addons\Elementor;

defined( 'ABSPATH' ) || die();

class Assets_Cache {

    /**
     * @var int
     */
    protected $post_id;

    /**
     * @var Widgets_Cache
     */
    protected $widgets_cache;

    protected $upload_path;

    protected $upload_url;

    public function __construct( $post_id ) {
        $this->post_id = $post_id;
        $this->widgets_cache = new Widgets_Cache( $post_id );

        $upload_dir = wp_upload_dir();
        $this->upload_path = trailingslashit( $upload_dir['basedir'] );
        $this->upload_url = trailingslashit( $upload_dir['baseurl'] );
    }

    public function get_cache_dir_name() {
        return trailingslashit( 'happyaddons' . DIRECTORY_SEPARATOR . 'cache' );
    }

    public function get_post_id() {
        return $this->post_id;
    }

    public function get_cache_dir() {
        return $this->upload_path . $this->get_cache_dir_name();
    }

    public function get_cache_url() {
        return $this->upload_url . $this->get_cache_dir_name();
    }

    public function get_file_name() {
        return $this->get_cache_dir() . "post-{$this->get_post_id()}.css";
    }

    public function get_file_url() {
        return $this->get_cache_url() . "post-{$this->get_post_id()}.css";
    }

    public function cache_exists() {
        return file_exists( $this->get_file_name() );
    }

    public function has() {
        if ( ! $this->cache_exists() ) {
            $this->save();
        }
        return $this->cache_exists();
    }

    public function delete() {
        if ( $this->cache_exists() ) {
            unlink( $this->get_file_name() );
        }
    }

    public function delete_all() {
        $files = glob( $this->get_cache_dir() . '/*' );
        foreach ( $files as $file ) {
            if ( is_file( $file ) ) {
                unlink( $file );
            }
        }
    }

    public function enqueue() {
        if ( $this->has() ) {
            wp_enqueue_style(
                'happy-elementor-addons-' . $this->get_post_id(),
                $this->get_file_url(),
                [ 'elementor-frontend' ],
                HAPPY_ADDONS_VERSION . '.' . get_post_modified_time()
            );
        }
    }

    public function enqueue_libraries() {
        $widgets = $this->widgets_cache->get();

        if ( empty( $widgets ) || ! is_array( $widgets ) ) {
            return;
        }

        $widgets_map = Widgets_Manager::get_widgets_map();
        $base_widget = isset( $widgets_map[ Widgets_Manager::get_base_widget_key() ] ) ? $widgets_map[ Widgets_Manager::get_base_widget_key() ] : [];

        if ( isset( $base_widget['vendor'], $base_widget['vendor']['css'] ) && is_array( $base_widget['vendor']['css'] ) ) {
            foreach ( $base_widget['vendor']['css'] as $vendor_css_handle ) {
                wp_enqueue_style( $vendor_css_handle );
            }
        }

        if ( isset( $base_widget['vendor'], $base_widget['vendor']['js'] ) && is_array( $base_widget['vendor']['js'] ) ) {
            foreach ( $base_widget['vendor']['js'] as $vendor_js_handle ) {
                wp_enqueue_script( $vendor_js_handle );
            }
        }

        foreach ( $widgets as $widget_id ) {
            $widget_map_key = substr( $widget_id, 3 );
            if ( ! isset( $widgets_map[ $widget_map_key ], $widgets_map[ $widget_map_key ]['vendor'] ) ) {
                continue;
            }
            $vendor = $widgets_map[ $widget_map_key ]['vendor'];

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

    public function save() {
        $widgets = $this->widgets_cache->get();

        if ( empty( $widgets ) || ! is_array( $widgets ) ) {
            return;
        }

        $widgets_map = Widgets_Manager::get_widgets_map();
        $base_widget = isset( $widgets_map[ Widgets_Manager::get_base_widget_key() ] ) ? $widgets_map[ Widgets_Manager::get_base_widget_key() ] : [];
        $data = '';

        // Get common css styles
        if ( isset( $base_widget['css'] ) && is_array( $base_widget['css'] ) ) {
            $data .= $this->get_files_contents( $base_widget['css'] );
        }

        $cached_widgets = [];
        // Get widget specific styles
        foreach ( $widgets as $widget_id ) {
            $widget_map_key = substr( $widget_id, 3 );
            if ( isset( $cached_widgets[ $widget_map_key ] ) ||
                ! isset( $widgets_map[ $widget_map_key ], $widgets_map[ $widget_map_key ]['css'] )
            ) {
                continue;
            }
            $data .= $this->get_files_contents( $widgets_map[ $widget_map_key ]['css'], isset( $widgets_map['is_pro'] ) );
            $cached_widgets[ $widget_map_key ] = true;
        }
        $data .= sprintf( '/** Compiled CSS for: %s **/', implode(', ', array_keys( $cached_widgets ) ) );

        if ( ! is_dir( $this->get_cache_dir() ) ) {
            @mkdir( $this->get_cache_dir(), 0777, true );
        }

        $filename = $this->get_file_name();
        file_put_contents( $filename, $data );
    }

    protected function get_files_contents( $files_name, $is_pro = false ) {
        $data = '';
        foreach ( $files_name as $file_name ) {
            $file_path = HAPPY_ADDONS_DIR_PATH . "assets/css/widgets/{$file_name}.min.css";
            $file_path = apply_filters( 'happyaddons_cached_widget_css_file_path', $file_path, $file_name, $is_pro );
            if ( file_exists( $file_path ) ) {
                $data .= file_get_contents( $file_path );
            };
        }
        return $data;
    }
}
