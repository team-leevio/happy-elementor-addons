<?php
namespace Happy_Addons\Elementor;

use Elementor\Core\Base\Background_Task;

defined( 'ABSPATH' ) || die();

/**
 * HappyAddons updater
 *
 * @since 1.4.1
 */
class Updater extends Background_Task {

    public function __construct( $manager ) {
        $this->manager = $manager;
        // Uses unique prefix per blog so each blog has separate queue.
        $this->prefix = 'happyaddons_' . get_current_blog_id();
        $this->action = $this->manager->get_action();

        $gpc = get_parent_class( get_parent_class( $this ) );
        call_user_func( [ $gpc, '__construct' ] );
    }

    protected function format_callback_log( $item ) {
        return $this->manager->get_plugin_label() . '/Upgrades - ' . $item['callback'][1];
    }
}
