<?php

namespace Happy_Addons\Elementor;

defined( 'ABSPATH' ) || die();

/**
 * Class Select2_Handler
 * @package Happy_Addons\Elementor
 */
class Select2_Handler {

	public static function init () {
		add_action( 'wp_ajax_ha_post_list_query', [ __CLASS__, 'ha_post_list_query' ] );
	}

	/**
	 * Return Post list based on post type
	 */
	public static function ha_post_list_query () {
		$security = check_ajax_referer( 'HappyAddons_Select2_Secret', 'security' );
		if ( ! $security ) return;
		$post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( $_POST['post_type'] ) : '';
		if ( ! $post_type ) return;

		$select_type = isset( $_POST['select_type'] ) ? $_POST['select_type'] : false;
		$search_string = isset( $_POST['q'] ) ? sanitize_text_field( $_POST['q'] ) : '';
		$ids = isset( $_POST['id'] ) ? $_POST['id'] : array();

		$data = [];
		$arg = [
			'post_status' => 'publish',
			'post_type' => $post_type,
			'posts_per_page' => -1,
		];
		$arg['s'] = $search_string;
		$arg['post__in'] = $ids;
		$query = new \WP_Query( $arg );
		if ( $select_type === 'choose' && $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$data[] = [
					'id' => get_the_id(),
					'text' => get_the_title(),
				];
			}
			wp_reset_postdata();
		}
		if ( $select_type === 'selected' && $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$data[get_the_id()] = get_the_title();
			}
			wp_reset_postdata();
		}
		// return the results in json.
		wp_send_json( $data );

	}

}
