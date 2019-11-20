<?php
namespace Happy_Addons\Elementor;

defined( 'ABSPATH' ) || die();

class Attention_Seeker {

    public static function init() {
        add_action( 'admin_notices', [ __CLASS__, 'seek_attention' ] );
        add_action( 'wp_ajax_ignore_attention_seeker', [ __CLASS__, 'process_ignore_request' ] );
        add_action( 'admin_head', [ __CLASS__, 'setup_environment' ] );
    }

    public static function setup_environment() {
        ?>
        <script>
            jQuery(function($) {
                var $seeker = $('.ha-seeker'),
                    ajaxUrl = '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                    nonce = '<?php echo wp_create_nonce( 'ignore_attention_seeker' ); ?>';

                $seeker.on('click.onSeekerIgnore', '.notice-dismiss', function (e) {
                    e.preventDefault();
                    var $seeker = $(e.delegateTarget);

                    $.post(
                        ajaxUrl,
                        {
                            action: 'ignore_attention_seeker',
                            nonce: nonce,
                            id: $seeker.data('id')
                        },
                        function(res) {
                            if (res.success) {
                                console.log('Thanks! We will bring more awesome offer next time 🙂')
                            }
                        }
                    )
                });
            });
        </script>
        <?php
    }

    public static function process_ignore_request() {
        $nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
        $id = isset( $_POST['id'] ) ? $_POST['id'] : '';

        if ( wp_verify_nonce( $nonce, 'ignore_attention_seeker' ) && $id ) {
            $seeker = wp_list_filter( self::get_attentions(), ['_id' => $id] );
            $expire_date = $seeker['end_date'] + DAY_IN_SECONDS;
            set_transient( self::generate_db_key( $id ), 'ignore', $expire_date );
            wp_send_json_success();
        }

        exit;
    }

    private static function should_try( $attention ) {
        if ( ha_has_pro() ) {
            return false;
        }

        if ( ! isset( $attention['_id'], $attention['start_date'], $attention['end_date'], $attention['render_cb'] ) ) {
            return false;
        }

        if ( ! is_callable( $attention['render_cb'] ) ) {
            return false;
        }

        if ( get_transient( self::generate_db_key( $attention['_id'] ) ) === 'ignore' ) {
            return false;
        }

        if ( time() >= $attention['start_date'] && time() <= $attention['end_date'] ) {
            return true;
        }

        return false;
    }

    private static function generate_db_key( $id ) {
        return 'ha-seeker-' . md5( $id );
    }

    public static function seek_attention() {
        foreach ( self::get_attentions() as $attention ) {
            if ( self::should_try( $attention ) ) {
                call_user_func( $attention['render_cb'], $attention['_id'] );
            }
        }
    }

    private static function get_attentions() {
        return [
            [
                '_id' => '#000Friday',
                'start_date' => strtotime( '10th November 2019, 12AM' ),
                'end_date' => strtotime( '4th December 2019, 11:59:59 PM' ),
                'render_cb' => [ __CLASS__, 'render_000Friday_offer' ],
            ]
        ];
    }

    public static function render_000Friday_offer( $id ) {
        ?>
        <div class="notice updated elementor-message is-dismissible ha-seeker" data-id="<?php echo $id; ?>">
            <a href="https://happyaddons.com/pricing/" target="_blank" rel="noopener">
                <img style="max-width:100%; height: auto; display: block;" src="<?php echo HAPPY_ADDONS_ASSETS ?>/imgs/bf-img.png" alt="Black Friday & Cyber Monday Offer - BFCM2019">
            </a>
        </div>
        <?php
    }
}
