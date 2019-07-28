<?php

namespace Happy_Addons\Elementor\Assets;

class AssetCompiler {
	private $upload_path;

	function __construct() {
		add_action( 'save_post', [ $this, 'compile_assets' ] );
		$upload_dir        = wp_upload_dir();
		$this->upload_path = trailingslashit( $upload_dir['basedir'] );
	}


	public function compile_assets( $post_id ) {
		if ( apply_filters( 'happyaddons_ondemand_asset_compiling', true ) ) {

			if ( get_post_type( $post_id ) == 'page' ) {
				//failsafe
				if ( version_compare( ELEMENTOR_VERSION, '2.6.5', '<=' ) || ! get_post_meta( $post_id, '_elementor_elements_usage', true ) ) {
					//we need to populate _elementor_elements_usage;
					$usage = [];
					$data  = json_decode( get_post_meta( $post_id, '_elementor_data', true ), true );
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
					update_post_meta( $post_id, '_elementor_elements_usage', $usage );
				}


				$_widgets = [];
				$filename = $this->upload_path . "happyaddons/compiled/compiled-{$post_id}.css";
				$widgets  = get_post_meta( $post_id, '_elementor_elements_usage', true );

				if ( is_array( $widgets ) ) {
					foreach ( $widgets as $widget_name => $usage ) {
						if ( strpos( $widget_name, 'ha-' ) !== false ) {
							array_push( $_widgets, $widget_name );
						}
					}

					$data = file_get_contents( HAPPY_DIR_PATH . 'assets/css/widgets/ha-common.min.css' );
					$data .= file_get_contents( HAPPY_DIR_PATH . 'assets/css/widgets/btn.min.css' );


					foreach ( $_widgets as $_widget ) {
						if ( 'ha-justified-gallery' == $_widget || 'image-grid' == $_widget ) {
							$data .= file_get_contents( HAPPY_DIR_PATH . 'assets/css/widgets/gallery-filter.min.css' );
						}
						if ( file_exists( HAPPY_DIR_PATH . "assets/css/widgets/{$_widget}.min.css" ) ) {
							$data .= file_get_contents( HAPPY_DIR_PATH . "assets/css/widgets/{$_widget}.min.css" );
						};
					}
					if ( ! is_dir( $this->upload_path . 'happyaddons/compiled/' ) ) {
						@mkdir( $this->upload_path . 'happyaddons/compiled/', 0777, true );
					}
					file_put_contents( $filename, $data );
				}
			}
		}
	}
}

new AssetCompiler();