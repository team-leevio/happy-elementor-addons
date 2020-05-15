<?php
namespace Happy_Addons\Elementor;

use Elementor\Core\Common\Modules\Ajax\Module as Ajax;

defined('ABSPATH') || die();

class Library_Manager {

	public static function init() {
		add_action( 'elementor/editor/footer', [ __CLASS__, 'print_template_views' ] );
		add_action( 'elementor/ajax/register_actions', [ __CLASS__, 'register_ajax_actions' ] );
	}

	public static function print_template_views() {
		include_once HAPPY_ADDONS_DIR_PATH . 'templates/template-library/templates.php';
	}

	public static function register_ajax_actions( Ajax $ajax ) {
		$ajax->register_ajax_action( 'get_happy_library_data', function( $data ) {
			if ( ! current_user_can( 'edit_posts' ) ) {
				throw new \Exception( 'Access Denied' );
			}

			if ( ! empty( $data['editor_post_id'] ) ) {
				$editor_post_id = absint( $data['editor_post_id'] );

				if ( ! get_post( $editor_post_id ) ) {
					throw new \Exception( __( 'Post not found.', 'happy-elementor-addons' ) );
				}

				ha_elementor()->db->switch_to_post( $editor_post_id );
			}

			$result = self::get_library_data( $data );

			if ( is_wp_error( $result ) ) {
				throw new \Exception( $result->get_error_message() );
			}

			return $result;
		} );
	}

	public static function get_library_data( array $args ) {
		$library_data = Library_Api::get_data( ! empty( $args['sync'] ) );

		return [
			'templates' => self::get_templates( $library_data ),
			'tags' => ! empty( $library_data['tags'] ) ? $library_data['tags'] : [],
		];
	}

	/**
	 * Get templates from library data
	 *
	 * @param array $library_data
	 * @return array
	 */
	private static function get_templates( array $library_data ) {
		$templates = [];

		if ( ! empty( $library_data['templates'] ) ) {
			foreach ( $library_data['templates'] as $template_data ) {
				$templates[] = self::prepare_template( $template_data );
			}
		}

		return $templates;
	}

	/**
	 * Prepare template items to match model
	 *
	 * @param array $template_data
	 * @return array
	 */
	private static function prepare_template( array $template_data ) {
		return [
			'template_id' => $template_data['id'],
			'title' => $template_data['title'],
			'type' => $template_data['type'],
			'thumbnail' => $template_data['thumbnail'],
			'date' => $template_data['created_at'],
			'tags' => $template_data['tags'],
			'isPro' => $template_data['is_pro'],
			'url' => $template_data['url'],
		];
	}
}
