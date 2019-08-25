<?php
namespace Happy_Addons\Elementor;

use Elementor\Core\Upgrade\Upgrade_Utils;

defined( 'ABSPATH' ) || die();

/**
 * HappyAddons upgrades
 *
 * @since 1.4.1
 */
class Upgrades {

    /**
     * migrate Icon control string value to Icons control array value
     *
     * @param array $element
     * @param array $args
     *
     * @return mixed
     */
    public static function _migrate_icon_font_value( $element, $args ) {
        $widget_id = $args['widget_id'];

        if ( empty( $element['widgetType'] ) || $widget_id !== $element['widgetType'] ) {
            return $element;
        }

        foreach ( $args['control_ids'] as $old_name => $new_name ) {
            // exit if no value to migrate
            if ( ! isset( $element['settings'][ $old_name ] ) ) {
                continue;
            }

            // exit if new value exists
            if ( isset( $element['settings'][ $new_name ] ) && $element['settings'][ $old_name ] === $element['settings'][ $new_name ] ) {
                continue;
            }

            $element['settings'][ $new_name ] = Icons_Manager::migrate_font_value( $element['settings'][ $old_name ] );
            $args['do_update'] = true;
        }
        return $element;
    }

    /**
     * Font migration
     *
     * @param Updater $updater
     */
    public static function _v_1_5_6_migration( $updater ) {
        add_option( 'elementor_icon_manager_needs_update', 'yes' );
        add_option( 'elementor_load_fa4_shim', 'yes' );

        $changes = [
            [
                'callback' => [ 'Happy_Addons\Elementor\Upgrades', '_migrate_icon_font_value' ],
                'control_ids' => [
                    'icon' => 'selected_icon',
                ],
            ],
        ];

        Upgrade_Utils::_update_widget_settings( 'ha-icon-box', $updater, $changes );
    }
}
