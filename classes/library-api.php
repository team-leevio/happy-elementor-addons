<?php
/**
 * Library api class
 *
 * @package HappyAddons
 * @author HappyMonster
 */
namespace Happy_Addons\Elementor;

defined( 'ABSPATH' ) || die();

class Library_Api {

	/**
	 * Template library data cache
	 */
	const LIBRARY_CACHE_KEY = 'ha_library_cache';

	/**
	 * Template info api url
	 */
	const API_TEMPLATES_INFO_URL = 'https://templates.happyaddons.com/wp-json/ha/v1/templates-info';

	/**
	 * Template data api url
	 */
	const API_TEMPLATE_DATA_URL = 'https://happyaddons.local/wp-json/ha/v1/templates/';

	/**
	 * Get library data from remote source and cache
	 *
	 * @param boolean $force_update
	 * @return array
	 */
	private static function request_data( $force_update = false ) {
		$data = get_option( self::LIBRARY_CACHE_KEY );

		if ( $force_update || false === $data ) {
			$timeout = ( $force_update ) ? 25 : 8;

			$response = wp_remote_get( self::API_TEMPLATES_INFO_URL, [
				'timeout' => $timeout,
			] );

			if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
				update_option( self::LIBRARY_CACHE_KEY, [] );
				return false;
			}

			$data = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( empty( $data ) || ! is_array( $data ) ) {
				update_option( self::LIBRARY_CACHE_KEY, [] );
				return false;
			}

			update_option( self::LIBRARY_CACHE_KEY, $data, 'no' );
		}

		return $data;
	}

	/**
	 * Get library data
	 *
	 * @param boolean $force_update
	 * @return array
	 */
	public static function get_data( $force_update = false ) {
		self::request_data( $force_update );

		$data = get_option( self::LIBRARY_CACHE_KEY );

		if ( empty( $data ) ) {
			return [];
		}

		return $data;
	}
}
