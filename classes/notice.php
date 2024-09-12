<?php
namespace Happy_Addons\Elementor;

defined( 'ABSPATH' ) || die();

class Advance_Notice {

    public static function init() {
		if ( !( in_array( 'happy-elementor-addons-pro/happy-elementors-addons-pro.php', (array) get_option( 'active_plugins', [] ), true ) ) ) {
			add_action( 'admin_init', [__CLASS__, 'ha_void_check_installation_time'] );
        	add_action( 'admin_init', [__CLASS__, 'ha_void_spare_me'], 5 );
		}
    }

    //check if the notice should be shown or not
    public static function ha_void_check_installation_time() {
		$nobug = get_option( 'ha__user_feedback_survey', "0");
		if ($nobug == "not_interested" || $nobug == "participated") {
           return;
        }

        add_action( 'admin_notices', [__CLASS__, 'ha_void_grid_display_admin_notice']);
    }

    /**
     * Display Admin Notice, asking for participate
     **/
    public static function ha_void_grid_display_admin_notice() {
        // wordpress global variable
        global $pagenow;

        $exclude = [ 'themes.php', 'users.php', 'tools.php', 'options-general.php', 'options-writing.php', 'options-reading.php', 'options-discussion.php', 'options-media.php', 'options-permalink.php', 'options-privacy.php', 'edit-comments.php', 'upload.php', 'media-new.php', 'admin.php', 'import.php', 'export.php', 'site-health.php', 'export-personal-data.php', 'erase-personal-data.php' ];

        if ( ! in_array( $pagenow, $exclude ) ) {
            $no_thanks = esc_url( add_query_arg( 'ha_not_interested', '1', self::ha_current_admin_url() ) );
            $participated        = esc_url( add_query_arg( 'ha_participated', '1', self::ha_current_admin_url() ) );

            printf( __( '<div class="notice ha-review-notice ha-review-notice--extended">
                <div class="ha-review-notice__aside">
                    <div class="ha-review-notice__icon-wrapper"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAzMiAzMiI+PGcgZmlsbD0iI0ZGRiI+PHBhdGggZD0iTTI4LjYgNy44aC44Yy41IDAgLjktLjUuOC0xIDAtLjUtLjUtLjktMS0uOC0zLjUuMy02LjgtMS45LTcuOC01LjMtLjEtLjUtLjYtLjctMS4xLS42cy0uNy42LS42IDEuMWMxLjIgMy45IDQuOSA2LjYgOC45IDYuNnoiLz48cGF0aCBkPSJNMzAgMTEuMWMtLjMtLjYtLjktMS0xLjYtMS0uOSAwLTEuOSAwLTIuOC0uMi00LS44LTctMy42LTguNC03LjEtLjMtLjYtLjktMS4xLTEuNi0xQzguMyAxLjkgMS44IDcuNC45IDE1LjEuMSAyMi4yIDQuNSAyOSAxMS4zIDMxLjIgMjAgMzQuMSAyOSAyOC43IDMwLjggMTkuOWMuNy0zLjEuMy02LjEtLjgtOC44em0tMTEuNiAxLjFjLjEtLjUuNi0uOCAxLjEtLjdsMy43LjhjLjUuMS44LjYuNyAxLjFzLS42LjgtMS4xLjdsLTMuNy0uOGMtLjQtLjEtLjgtLjYtLjctMS4xek0xMC4xIDExYy4yLTEuMSAxLjQtMS45IDIuNS0xLjYgMS4xLjIgMS45IDEuNCAxLjYgMi41LS4yIDEuMS0xLjQgMS45LTIuNSAxLjYtMS0uMi0xLjgtMS4zLTEuNi0yLjV6bTE0LjYgMTAuNkMyMi44IDI2IDE3LjggMjguNSAxMyAyN2MtMy42LTEuMi02LjItNC41LTYuNS04LjItLjEtMSAuOC0xLjcgMS43LTEuNmwxNS40IDIuNWMuOSAwIDEuNCAxIDEuMSAxLjl6Ii8+PHBhdGggZD0iTTE3LjEgMjIuOGMtMS45LS40LTMuNy4zLTQuNyAxLjctLjIuMy0uMS43LjIuOS42LjMgMS4yLjUgMS45LjcgMS44LjQgMy43LjEgNS4xLS43LjMtLjIuNC0uNi4yLS45LS43LS45LTEuNi0xLjUtMi43LTEuN3oiLz48L2c+PC9zdmc+"></div>
                </div>
                <div class="ha-review-notice__content">
                    <h3>Help Us Improve HappyAddons!</h3>
                    <p>Share your thoughts, and get exciting rewards! &#x1F389; We would love to hear your feedback on how we can improve your experience!</p>
                    <div class="ha-review-notice__actions">
                        <a href="%s" class="ha-review-button ha-review-button--cta" target="_blank"><span>👍 Participate Here</span></a>
						<a href="%s" class="ha-review-button ha-review-button--cta ha-review-button--outline"><span>🙌 Already Participated!</span></a>
                        <a href="%s" class="ha-review-button ha-review-button--cta ha-review-button--error ha-review-button--outline"><span>💔 No Thanks</span></a>
                    </div>
                </div>
            </div>' ), esc_url( 'https://forms.gle/ZEi53PDcywKjUP2i9' ), $participated, $no_thanks );
        }
    }

    // remove the notice if the user does not want to
    public static function ha_void_spare_me() {
        if ( isset( $_GET['ha_not_interested'] ) && ! empty( $_GET['ha_not_interested'] ) ) {
            $spare_me = absint($_GET['ha_not_interested']);
            if ( 1 == $spare_me ) {
                update_option( 'ha__user_feedback_survey', "not_interested" );
            }
        }

		if ( isset( $_GET['ha_participated'] ) && ! empty( $_GET['ha_participated'] ) ) {
            $ha_rated = absint($_GET['ha_participated']);
            if ( 1 == $ha_rated ) {
                update_option( 'ha__user_feedback_survey', "participated" );
            }
        }
    }

    protected static function ha_current_admin_url() {
        $uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
        $uri = preg_replace( '|^.*/wp-admin/|i', '', $uri );

        if ( ! $uri ) {
            return '';
        }
        return remove_query_arg( [ '_wpnonce', '_wc_notice_nonce', 'wc_db_update', 'wc_db_update_nonce', 'wc-hide-notice' ], admin_url( $uri ) );
    }
}

Advance_Notice::init();
