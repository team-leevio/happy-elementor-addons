<?php

namespace Happy_Addons\Communication;

define( "HAPPY_API_COMMUNICATION_ENDPOINT", "https://happyaddons.com/communication.php" );
define( "HAPPY_API_COMMUNICATION_CHECK_INTERVAL", 60 ); //after how many seconds we will recheck - optimal value is 12*60*60


class Communciator {

	public function __construct() {
		add_action( "admin_menu", function () {
			$last_message            = get_option( "happyaddons_message", [ 'digest' => '404' ] );
			$next_communication_time = get_option( "happyaddons_communication_time", 0 );
			get_option( "happyaddons_communication_time", "0" );
			if ( $next_communication_time < time() ) {
				//make a call
				$happy_data = wp_remote_get( HAPPY_API_COMMUNICATION_ENDPOINT . '?s=happy&action=notice' );
				if ( is_array( $happy_data ) ) {
					$body = json_decode( wp_remote_retrieve_body( $happy_data ), true );
					if ( is_array($body) ) {
						if ( $body['digest'] != $last_message['digest'] ) {
							$options = [
								'happyaddons_message'            => $body,
								'happyaddons_communication_time' => time() + HAPPY_API_COMMUNICATION_CHECK_INTERVAL,
								'happyaddons_message_dismissed'  => 1
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


	}

	function display_admin_notice() {
		$last_message = get_option( "happyaddons_message", [] );

		if ( is_array( $last_message ) && count( $last_message ) > 0 ) {
			$dismissed = get_option( 'happyaddons_message_dismissed', 0 );
			if ( $dismissed != 0 && trim( $last_message['message'] != '' ) ) {
				$message_body        = isset( $last_message['message'] ) ? $last_message['message'] : '';
				$action_button_title = isset( $last_message['action'] ) ? $last_message['action'] : '';
				$action_button_url   = isset( $last_message['url'] ) ? $last_message['url'] : '';
				$message_style       = isset( $last_message['style'] ) ? $last_message['style'] : '';
				?>
                <div class="notice notice-<?php echo $message_style; ?> is-dismissible">
                    <p><?php echo esc_html( $message_body ); ?></p>
					<?php if ( $action_button_title ): ?>
                        <p><a class="button button-primary"
                              href="<?php echo esc_url( $action_button_url ); ?>"><?php echo( $action_button_title ); ?></a>
                        </p>
					<?php endif; ?>
                </div>
				<?php
			}
		}
	}
}

new Communciator();