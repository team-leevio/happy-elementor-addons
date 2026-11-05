<?php
	/**
	 * LordIcon sanitizer trait
	 *
	 * Shared security helpers for every widget that renders the
	 * <lord-icon> element (LordIcon, Icon Box, Info Box).
	 *
	 * @package Happy_Addons
	 */
	namespace Happy_Addons\Elementor\Traits;

	defined( 'ABSPATH' ) || die();

	trait Lordicon_Sanitizer {

		/**
		 * Animation JSON can carry executable lottie expressions, so only
		 * trusted sources are allowed: https URLs on Lordicon hosts and,
		 * for the media-library control, files from this site's uploads.
		 *
		 * @param string $url           Raw URL from widget settings.
		 * @param bool   $allow_uploads Whether this site's uploads/plugin assets are allowed.
		 *
		 * @return string Safe URL or empty string when the URL is rejected.
		 */
		public function sanitize_animation_url( $url, $allow_uploads = false ) {
			$url = is_string( $url ) ? trim( $url ) : '';

			if ( '' === $url ) {
				return '';
			}

			$url    = esc_url_raw( $url, [ 'http', 'https' ] );
			$parsed = wp_parse_url( $url );

			if ( empty( $parsed['host'] ) || empty( $parsed['scheme'] ) ) {
				return '';
			}

			$allowed_hosts = apply_filters( 'happyaddons_lordicon_allowed_hosts', [ 'cdn.lordicon.com' ] );
			if ( 'https' === $parsed['scheme'] && in_array( strtolower( $parsed['host'] ), array_map( 'strtolower', (array) $allowed_hosts ), true ) ) {
				return $url;
			}

			if ( $allow_uploads ) {
				$check   = set_url_scheme( $url, 'https' );
				$uploads = wp_get_upload_dir();
				$bases   = [
					set_url_scheme( $uploads['baseurl'], 'https' ),
					set_url_scheme( HAPPY_ADDONS_ASSETS, 'https' ),
				];
				foreach ( $bases as $base ) {
					if ( 0 === strpos( $check, trailingslashit( $base ) ) && false === strpos( $check, '..' ) ) {
						return $url;
					}
				}
			}

			return '';
		}

		/**
		 * Limit the custom trigger target to a simple class/ID/tag selector
		 * so a crafted setting cannot bind the animation trigger to
		 * arbitrary page elements.
		 *
		 * @param string $selector Raw selector from widget settings.
		 * @param string $fallback Fallback selector when the value is invalid.
		 *
		 * @return string Safe selector.
		 */
		public function sanitize_target_selector( $selector, $fallback = '.ha-lordicon-wrapper' ) {
			$selector = is_string( $selector ) ? trim( $selector ) : '';
			$fallback = is_string( $fallback ) && '' !== $fallback ? $fallback : '.ha-lordicon-wrapper';

			if ( ! preg_match( '/^[.#]?[A-Za-z_][\w-]*$/', $selector ) ) {
				return $fallback;
			}

			return $selector;
		}
	}
