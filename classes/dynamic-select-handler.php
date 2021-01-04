<?php
namespace Happy_Addons\Elementor;

defined( 'ABSPATH' ) || die();

use Exception;

class Dynamic_Select_Handler {

	public static function init() {
		add_action( 'wp_ajax_ha_process_dynamic_select', [ __CLASS__, 'process_request' ] );
	}

	protected static function verify_request() {
		$nonce = ! empty( $_REQUEST['nonce'] ) ? $_REQUEST['nonce'] : '';

		return wp_verify_nonce( $nonce, 'ha_editor_nonce' );
	}

	public static function process_request() {
		try {
			if ( ! self::verify_request() ) {
				throw new Exception( 'Invalid request' );
			}

			$object_type = ! empty( $_REQUEST['object_type'] ) ? $_REQUEST['object_type'] : 'post';

			$response = [];

			if ( $object_type === 'post' ) {
				$response = self::process_post();
			}

			if ( $object_type === 'term' ) {
				$response = self::process_term();
			}

			wp_send_json_success( $response );
		} catch( Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}
	}

	public static function process_post() {
		$post_type    = ! empty( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : 'any';
		$query_term   = ! empty( $_REQUEST['query_term'] ) ? $_REQUEST['query_term'] : '';
		$saved_values = ! empty( $_REQUEST['saved_values'] ) ? $_REQUEST['saved_values'] : 0;

		$args = [
			'post_type'        => $post_type,
			'suppress_filters' => false,
			'posts_per_page'   => 20,
			'orderby'          => 'title',
			'order'            => 'ASC',
			'post_status'      => 'publish',
		];

		if ( $query_term ) {
			$args['s'] = $query_term;
		}

		if ( $saved_values ) {
			$args['post__in'] = $saved_values;
			$args['posts_per_page'] = count( $saved_values );
			$args['orderby'] = 'post__in';
		}

		$posts = get_posts( $args );

		if ( empty( $posts ) ) {
			return [];
		}

		$out = [];

		foreach ( $posts as $post ) {
			// empty space is must needed, otherwise the list become reordered in JS
			$out[" {$post->ID}"] = esc_html( $post->post_title );
		}

		return $out;
	}

	public static function process_term() {
		$term_taxonomy = ! empty( $_REQUEST['term_taxonomy'] ) ? $_REQUEST['term_taxonomy'] : '';
		$query_term    = ! empty( $_REQUEST['query_term'] ) ? $_REQUEST['query_term'] : '';
		$saved_values  = ! empty( $_REQUEST['saved_values'] ) ? $_REQUEST['saved_values'] : 0;

		if ( empty( $term_taxonomy ) ) {
			throw new Exception( 'Invalid taxonomy' );
		}

		$args = [
			'taxonomy'   => $term_taxonomy,
			'hide_empty' => false,
			'orderby'    => 'name',
			'order'      => 'ASC',
			'number'     => 20,
		];

		if ( $query_term ) {
			$args['search'] = $query_term;
			$args['count'] = true;
		}

		if ( $saved_values ) {
			$args['include'] = $saved_values;
			$args['number']  = count( $saved_values );
			$args['orderby'] = 'include';
		}

		$terms = get_terms( $args );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return [];
		}

		$out = [];

		foreach ( $terms as $term ) {
			$title = ! empty( $query_term ) ? "{$term->name} ({$term->count})" : $term->name;
			// empty space is must needed, otherwise the list become reordered in JS
			$out[" {$term->term_id}"] = $title;
		}

		return $out;
	}
}

Dynamic_Select_Handler::init();
