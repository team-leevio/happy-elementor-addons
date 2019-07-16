<?php

namespace Happy_Addons\Communication;

defined( 'ABSPATH' ) || die();

define( 'HAPPY_API_COMMUNICATION_ENDPOINT', 'https://happyaddons.com/communication.php' );

if ( ! ha_is_localhost() ) {
	define( 'HAPPY_API_COMMUNICATION_CHECK_INTERVAL', ( DAY_IN_SECONDS / 2 ) );
} else {
	define( 'HAPPY_API_COMMUNICATION_CHECK_INTERVAL', 60 );
}

class Communicator {

	public function __construct() {
		add_action( 'admin_menu', function () {
			$last_message            = get_option( 'happyaddons_message', [ 'digest' => '404' ] );
			$next_communication_time = get_option( 'happyaddons_communication_time', 0 );

			if ( $next_communication_time < time() ) {
				//make a call
				$happy_data = wp_remote_get( HAPPY_API_COMMUNICATION_ENDPOINT . '?s=happy&action=notice' );

				if ( is_array( $happy_data ) ) {
					$body = json_decode( wp_remote_retrieve_body( $happy_data ), true );
					if ( is_array( $body ) ) {
						if ( $body['digest'] != $last_message['digest'] ) {
							$options = [
								'happyaddons_message'            => $body,
								'happyaddons_communication_time' => time() + HAPPY_API_COMMUNICATION_CHECK_INTERVAL,
								'happyaddons_message_dismissed'  => 0
							];
							foreach ( $options as $key => $value ) {
								update_option( $key, $value );
							}
						}
					} else {
						$options = [
							'happyaddons_communication_time' => time() + HAPPY_API_COMMUNICATION_CHECK_INTERVAL,
						];
						foreach ( $options as $key => $value ) {
							update_option( $key, $value );
						}
					}
				}
			}

			add_action( 'admin_notices', [ $this, 'display_admin_notice' ] );
		} );

		add_action( 'wp_ajax_happyaddons_dismiss_error', function () {
			if ( wp_verify_nonce( $_POST['nonce'], 'happyaddons_dismiss_error' ) ) {
				update_option( 'happyaddons_message_dismissed', '1' );
				die();
			}
		} );

		add_action( 'admin_footer', function () {
			?>
            <script>
                ;(function ($) {
                    $(document).ready(function () {

                        $('body').on('click', '#happyaddons_remote_notice .notice-dismiss', function () {
                            $.post('<?php echo admin_url( 'admin-ajax.php' ); ?>', {
                                action: 'happyaddons_dismiss_error',
                                nonce: $('#happyaddons_remote_dismiss').val()
                            }, function (data) {
                                //console.log(data);
                            });
                            return false;
                        });
                    })
                })(jQuery);
            </script>

			<?php
		} );
	}

	function display_admin_notice() {
		$last_message = get_option( 'happyaddons_message', [] );

		if ( is_array( $last_message ) && count( $last_message ) > 0 ) {
			$dismissed = get_option( 'happyaddons_message_dismissed', 1 );
			if ( $dismissed == 0 && trim( $last_message['message'] != '' ) ) {
				$message_body        = isset( $last_message['message'] ) ? $last_message['message'] : '';
				$action_button_title = isset( $last_message['action'] ) ? $last_message['action'] : '';
				$action_button_url   = isset( $last_message['url'] ) ? $last_message['url'] : '';
				$message_style       = isset( $last_message['style'] ) ? $last_message['style'] : '';
				?>
                <div id="happyaddons_remote_notice" class="notice notice-<?php echo $message_style; ?> is-dismissible">
					<?php wp_nonce_field( 'happyaddons_dismiss_error', 'happyaddons_remote_dismiss' ); ?>
                    <div class="happyaddons-massage wp-clearfix"><?php echo $message_body; ?></div>
					<?php if ( $action_button_title ): ?>
                        <p><a class="button button-primary"
                              href="<?php echo esc_url( $action_button_url ); ?>"><?php echo esc_html( $action_button_title ); ?></a>
                        </p>
					<?php endif; ?>
                </div>
				<?php
			}
		}
	}
}

new Communicator();
