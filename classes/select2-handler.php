<?php
namespace Happy_Addons\Elementor;

defined( 'ABSPATH' ) || die();

class Select2_Handler {

	public static function init() {

		add_action( 'wp_ajax_ha_post_list_query', [ __CLASS__ , 'ha_post_list_query'] );

	}

	public static function ha_post_list_query() {
		$security = check_ajax_referer('HappyAddons_Select2_Secret', 'security');
		if( !$security ) return;
		//var_dump($security);
		//$one = isset( $_POST['one'] ) ? $_POST['one'] : 'no';
		$select_type = isset( $_POST['select_type'] ) ? $_POST['select_type'] : false;

		if ( $select_type === 'choose' ) {
			$search_string = isset( $_POST['q'] ) ? sanitize_text_field( $_POST['q'] ) : '';
			$post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( $_POST['post_type'] ) : '';
			if( !$post_type ) return;
			$req_post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( $_POST['post_type'] ) : 'all';
			//$data = [];

			$post_types = [
				'Posts' => 'post',
				'Pages' => 'page'
			];
			//foreach ( $post_types as $key => $post_type ) {

				$data = [];
				$arg = [
						'post_status' => 'publish',
						's' => $search_string,
						'post_type' => $post_type,
						'posts_per_page' => -1,

				];
				$query = new \WP_Query($arg);

				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();
						$title = get_the_title();
						//$title .= ( 0 != $query->post->post_parent ) ? ' (' . get_the_title( $query->post->post_parent ) . ')' : '';
						$id = get_the_id();
						$data[] = [
							'id' => $id,
							'text' => $title,
						];
					}
				}

				/*if ( is_array( $data ) && ! empty( $data ) ) {

					$result[] = [
						'text' => $key,
						'children' => $data,
					];
				}*/
			//}

			//$data = [];

			wp_reset_postdata();
			/*if ( is_array( $data ) && ! empty( $data ) ) {

			}*/
			// return the result in json
			wp_send_json( $data );
			//wp_send_json( ['results' => $result] );
		}

		if ( $select_type === 'selected' ) {
			$post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( $_POST['post_type'] ) : '';
			if( !$post_type ) return;
			$ids = isset( $_POST['id'] ) ? $_POST['id'] : array();
			$results = [];

			$query = new \WP_Query(
				[
					'post_status' => 'publish',
					'post_type' => $post_type,
					'post__in' => $ids,
					'posts_per_page' => -1,
				]
			);

			foreach ( $query->posts as $post ) {
				$results[$post->ID] = $post->post_title;
			}

			// return the results in json.
			wp_send_json( $results );
		}
	}

}
