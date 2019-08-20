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

        $upload_dir        = wp_upload_dir();
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
        foreach( $files as $file ) {
            if ( is_file( $file ) ) {
                unlink( $file );
            }
        }
    }

    public function enqueue() {
        if ( $this->has() ) {
            wp_enqueue_style(
                'happy-elementor-addons-' . $this->get_post_id(),
                $this->get_cache_url(),
                [ 'elementor-frontend' ],
                Base::VERSION . '.' . get_post_modified_time()
            );
        }
    }

    public function enqueue_libraries() {
        $widgets = $this->widgets_cache->get();

        if ( is_array( $widgets ) && ! empty( $widgets ) ) {
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

    public function save() {
        $widgets = $this->widgets_cache->get();

        if ( is_array( $widgets ) && ! empty( $widgets ) ) {
            $widgets_map = Widgets_Manager::get_widgets_map();
            $base_widget = isset( $widgets_map[ Widgets_Manager::get_base_widget_key() ] ) ? $widgets_map[ Widgets_Manager::get_base_widget_key() ] : [];
            $data = '';

            // Get common css styles
            if ( isset( $base_widget['css'] ) && is_array( $base_widget['css'] ) ) {
                $this->get_files_contents( $base_widget['css'], $data );
            }

            // Get widget specific styles
            foreach ( $widgets as $widget_id ) {
                $widget_map_key = substr( $widget_id, 3 );
                if ( ! isset( $widgets_map[ $widget_map_key ], $widgets_map[ $widget_map_key ]['css'] ) ) {
                    continue;
                }
                $this->get_files_contents( $widgets_map[ $widget_map_key ]['css'], $data );
            }

            if ( ! is_dir( $this->get_cache_dir() ) ) {
                @mkdir( $this->get_cache_dir(), 0777, true );
            }

            $filename = $this->get_file_name();
            file_put_contents( $filename, $data );
        }
    }

    protected function get_files_contents( $files, &$data ) {
        foreach ( $files as $file ) {
            if ( file_exists( HAPPY_DIR_PATH . "assets/css/widgets/{$file}.min.css" ) ) {
                $data .= file_get_contents( HAPPY_DIR_PATH . "assets/css/widgets/{$file}.min.css" );
            };
        }
    }
}
