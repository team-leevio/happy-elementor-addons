<?php
namespace Happy_Addons\Elementor;

use Elementor\Core\Base\Background_Task_Manager;

defined( 'ABSPATH' ) || die();

/**
 * DB upgrades manager
 *
 * @since 1.4.1
 */
class Upgrades_Manager extends Background_Task_Manager {
    protected $current_version = null;

    public function get_name() {
        return 'upgrade';
    }

    public function get_action() {
        return 'happyaddons_updater';
    }

    public function get_plugin_name() {
        return 'happy-elementor-addons';
    }

    public function get_plugin_label() {
        return __( 'HappyAddons', 'happy-elementor-addons' );
    }

    public function get_updater_label() {
        return sprintf( '<strong>%s </strong> &#8211;', __( 'HappyAddons Data Updater', 'happy-elementor-addons' ) );
    }

    public function get_new_version() {
        return HAPPY_ADDONS_VERSION;
    }

    public function get_version_option_name() {
        return 'happyaddons_version';
    }

    public function get_upgrades_class() {
        return 'Happy_Addons\Elementor\Upgrades';
    }

    public function get_task_runner_class() {
        return 'Happy_Addons\Elementor\Updater';
    }

    public function get_query_limit() {
        return 100;
    }

    public function get_current_version() {
        if ( null === $this->current_version ) {
            $this->current_version = get_option( $this->get_version_option_name() );
        }

        return $this->current_version;
    }

    public function should_upgrade() {
        $current_version = $this->get_current_version();

        // It's a new install.
        if ( ! $current_version ) {
            $this->update_db_version();
            return true;
        }

        return version_compare( $this->get_new_version(), $current_version, '>' );
    }

    public function on_runner_start() {
        parent::on_runner_start();

        define( 'HAPPY_ADDONS_UPGRADES', true );
    }

    public function on_runner_complete( $did_tasks = false ) {
        $logger = \Elementor\Plugin::instance()->logger->get_logger();

        $logger->info( 'HappyAddons data updater process has been completed.', [
            'meta' => [
                'plugin' => $this->get_plugin_label(),
                'from' => $this->current_version,
                'to' => $this->get_new_version(),
            ],
        ] );

        $this->purge_assets_cache();
        $this->update_db_version();

        if ( $did_tasks ) {
            $this->add_flag( 'completed' );
        }
    }

    protected function purge_assets_cache() {
        $assets_cache = new Assets_Cache( 0 );
        $assets_cache->delete_all();
    }

    public function admin_notice_start_upgrade() {
        $upgrade_link = $this->get_start_action_url();
        $message = '<p>' . sprintf( __( '%s Your site database needs to be updated to the latest version.', 'happy-elementor-addons' ), $this->get_updater_label() ) . '</p>';
        $message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Now', 'happy-elementor-addons' ) ) . '</p>';

        echo '<div class="notice notice-error">' . $message . '</div>';
    }

    public function admin_notice_upgrade_is_running() {
        $upgrade_link = $this->get_continue_action_url();
        $message = '<p>' . sprintf( __( '%s Database update process is running in the background.', 'happy-elementor-addons' ), $this->get_updater_label() ) . '</p>';
        $message .= '<p>' . sprintf( 'Taking a while? <a href="%s" class="button-primary">Click here to run it now</a>', $upgrade_link ) . '</p>';

        echo '<div class="notice notice-warning">' . $message . '</div>';
    }

    public function admin_notice_upgrade_is_completed() {
        $this->delete_flag( 'completed' );

        $message = '<p>' . sprintf( __( '%s The database update process is now complete. Thank you for updating to the latest version!', 'happy-elementor-addons' ), $this->get_updater_label() ) . '</p>';

        echo '<div class="notice notice-success">' . $message . '</div>';
    }

    /**
     * @access protected
     */
    protected function start_run() {
        $updater = $this->get_task_runner();

        if ( $updater->is_running() ) {
            return;
        }

        $upgrade_callbacks = $this->get_upgrade_callbacks();

        if ( empty( $upgrade_callbacks ) ) {
            $this->on_runner_complete();
            return;
        }

        foreach ( $upgrade_callbacks as $callback ) {
            $updater->push_to_queue( [
                'callback' => $callback,
            ] );
        }

        file_put_contents( __DIR__ . '/data.txt', print_r( $upgrade_callbacks, 1 ), FILE_APPEND );

        $updater->save()->dispatch();

        \Elementor\Plugin::instance()->logger->get_logger()->info( 'HappyAddons data updater process has been queued.', [
            'meta' => [
                'plugin' => $this->get_plugin_label(),
                'from' => $this->current_version,
                'to' => $this->get_new_version(),
            ],
        ] );
    }

    protected function update_db_version() {
        update_option( $this->get_version_option_name(), $this->get_new_version() );
    }

    public function get_upgrade_callbacks() {
        $prefix = '_v_';
        $upgrades_class = $this->get_upgrades_class();
        $upgrades_reflection = new \ReflectionClass( $upgrades_class );

        $callbacks = [];

        foreach ( $upgrades_reflection->getMethods() as $method ) {
            $method_name = $method->getName();
            if ( false === strpos( $method_name, $prefix ) ) {
                continue;
            }

            if ( ! preg_match_all( "/$prefix(\d+_\d+_\d+)/", $method_name, $matches ) ) {
                continue;
            }

            $method_version = str_replace( '_', '.', $matches[1][0] );

            if ( ! version_compare( $method_version, $this->current_version, '>' ) ) {
                continue;
            }

            $callbacks[] = [ $upgrades_class, $method_name ];
        }

        return $callbacks;
    }

    public function __construct() {
        // If upgrade is completed - show the notice only for admins.
        // Note: in this case `should_upgrade` returns false, because it's already upgraded.
        if ( is_admin() && current_user_can( 'manage_options' ) && $this->get_flag( 'completed' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_upgrade_is_completed' ] );
        }

        if ( ! $this->should_upgrade() ) {
            return;
        }

        include_once __DIR__ . '/updater.php';
        include_once __DIR__ . '/upgrades.php';

        $updater = $this->get_task_runner();
        $this->start_run();

        if ( $updater->is_running() ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_upgrade_is_running' ] );
        }

        parent::__construct();
    }
}
