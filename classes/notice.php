<?php
namespace Happy_Addons\Elementor\Classes;

defined( 'ABSPATH' ) || die();

class Notice {

	//check if the notice should be shown or not
	public static function ha_void_check_installation_time() {
		$feedback = get_option( 'ha__user_feedback_survey', '0' );
		if ( 'not_interested' == $feedback || 'participated' == $feedback ) {
			//remove old option data
			delete_option( 'ha__user_feedback_survey' );
		}

		// $black_friday_notice = get_option( 'ha__black_friday_24_notice', '0' );
		// if ( 'not_interested' == $black_friday_notice ) {
		// 	return;
		// }

		$black_friday_notice = get_option( 'ha__wedevs_12th_anniversary_notice', '0' );
		if ( 'not_interested' == $black_friday_notice ) {
			return;
		}

		$start_utc = '2025-03-18 13:00:00'; // 18 March, 7 UTC
		$end_utc   = '2025-03-30 16:00:00'; // 30 March, 4 UTC
		if ( true != self::check_notice_period( $start_utc, $end_utc ) ) {
			return;
		}

		add_action( 'admin_notices', [__CLASS__, 'ha_void_grid_display_admin_notice'] );
	}

	public static function check_notice_period( $start_utc, $end_utc ) {
		// Get the current time in UTC
		$time_zone        = new \DateTimeZone( 'UTC' );
		$current_utc_time = new \DateTime( 'now', $time_zone );
		$current_time     = $current_utc_time->getTimestamp();

		// Convert the start and end UTC timestamps to integers
		$start_time = strtotime( $start_utc );
		$end_time   = strtotime( $end_utc );

		// Check if the current time falls within the specified period
		if ( $current_time >= $start_time && $current_time <= $end_time ) {
			return true;
		}

		return false;
	}

	/**
	 * Display Admin Notice, asking for participate
	 **/
	public static function ha_void_grid_display_admin_notice() {
		// wordpress global variable
		global $pagenow;

		$exclude = ['themes.php', 'users.php', 'tools.php', 'options-general.php', 'options-writing.php', 'options-reading.php', 'options-discussion.php', 'options-media.php', 'options-permalink.php', 'options-privacy.php', 'edit-comments.php', 'upload.php', 'media-new.php', 'admin.php', 'import.php', 'export.php', 'site-health.php', 'export-personal-data.php', 'erase-personal-data.php'];

		if ( ! in_array( $pagenow, $exclude ) ) {
			$no_thanks    = esc_url( add_query_arg( 'ha_not_interested', '1', self::ha_current_admin_url() ) );
			$participated = esc_url( add_query_arg( 'ha_participated', '1', self::ha_current_admin_url() ) );

			printf( __( '<div class="notice ha-review-notice ha-review-notice--extended">
                <div class="ha-review-notice__aside">
                    <div class="ha-review-notice__icon-wrapper"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAzMiAzMiI+PGcgZmlsbD0iI0ZGRiI+PHBhdGggZD0iTTI4LjYgNy44aC44Yy41IDAgLjktLjUuOC0xIDAtLjUtLjUtLjktMS0uOC0zLjUuMy02LjgtMS45LTcuOC01LjMtLjEtLjUtLjYtLjctMS4xLS42cy0uNy42LS42IDEuMWMxLjIgMy45IDQuOSA2LjYgOC45IDYuNnoiLz48cGF0aCBkPSJNMzAgMTEuMWMtLjMtLjYtLjktMS0xLjYtMS0uOSAwLTEuOSAwLTIuOC0uMi00LS44LTctMy42LTguNC03LjEtLjMtLjYtLjktMS4xLTEuNi0xQzguMyAxLjkgMS44IDcuNC45IDE1LjEuMSAyMi4yIDQuNSAyOSAxMS4zIDMxLjIgMjAgMzQuMSAyOSAyOC43IDMwLjggMTkuOWMuNy0zLjEuMy02LjEtLjgtOC44em0tMTEuNiAxLjFjLjEtLjUuNi0uOCAxLjEtLjdsMy43LjhjLjUuMS44LjYuNyAxLjFzLS42LjgtMS4xLjdsLTMuNy0uOGMtLjQtLjEtLjgtLjYtLjctMS4xek0xMC4xIDExYy4yLTEuMSAxLjQtMS45IDIuNS0xLjYgMS4xLjIgMS45IDEuNCAxLjYgMi41LS4yIDEuMS0xLjQgMS45LTIuNSAxLjYtMS0uMi0xLjgtMS4zLTEuNi0yLjV6bTE0LjYgMTAuNkMyMi44IDI2IDE3LjggMjguNSAxMyAyN2MtMy42LTEuMi02LjItNC41LTYuNS04LjItLjEtMSAuOC0xLjcgMS43LTEuNmwxNS40IDIuNWMuOSAwIDEuNCAxIDEuMSAxLjl6Ii8+PHBhdGggZD0iTTE3LjEgMjIuOGMtMS45LS40LTMuNy4zLTQuNyAxLjctLjIuMy0uMS43LjIuOS42LjMgMS4yLjUgMS45LjcgMS44LjQgMy43LjEgNS4xLS43LjMtLjIuNC0uNi4yLS45LS43LS45LTEuNi0xLjUtMi43LTEuN3oiLz48L2c+PC9zdmc+"></div>
                </div>
                <div class="ha-review-notice__content">
                    <h3>Celebrate weDevs 12th Anniversary with HappyAddons! 🎉</h3>
                    <p>Upgrade your website design with premium widgets and features, at up to %s discount!</p>
                    <div class="ha-review-notice__actions">
                        <a href="%s" class="ha-review-button ha-review-button--cta" target="_blank"><span>👍 Upgrade to Pro</span></a>
                        <a href="%s" class="ha-review-button ha-review-button--cta ha-review-button--error ha-review-button--outline"><span>💔 No Thanks</span></a>
                    </div>
                </div>
            </div>' ), esc_html( '40%' ), esc_url( 'https://happyaddons.com/pricing/?utm_source=wpbanner&utm_medium=wpdashboard&utm_campaign=anniversary25_sale&utm_id=weAnniversary25Campaign' ), $no_thanks );
		}
	}

	// remove the notice if the user does not want to
	public static function ha_void_spare_me() {
		if ( isset( $_GET['ha_not_interested'] ) && ! empty( $_GET['ha_not_interested'] ) ) {
			$spare_me = absint( $_GET['ha_not_interested'] );
			// if ( 1 == $spare_me ) {
			// 	update_option( 'ha__black_friday_24_notice', 'not_interested' );
			// }
			if ( 1 == $spare_me ) {
				update_option( 'ha__wedevs_12th_anniversary_notice', 'not_interested' );
			}
		}

		// if ( isset( $_GET['ha_participated'] ) && ! empty( $_GET['ha_participated'] ) ) {
		//     $ha_rated = absint($_GET['ha_participated']);
		//     if ( 1 == $ha_rated ) {
		//         update_option( 'ha__user_feedback_survey', "participated" );
		//     }
		// }
	}

	protected static function ha_current_admin_url() {
		$uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		$uri = preg_replace( '|^.*/wp-admin/|i', '', $uri );

		if ( ! $uri ) {
			return '';
		}

		return remove_query_arg( ['_wpnonce', '_wc_notice_nonce', 'wc_db_update', 'wc_db_update_nonce', 'wc-hide-notice'], admin_url( $uri ) );
	}
}
