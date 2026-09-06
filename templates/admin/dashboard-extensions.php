<?php
/**
 * Dashboard extensions tab template
 */

defined( 'ABSPATH' ) || die();

$extensions = self::get_extensions();
$inactive_extensions = \Happy_Addons\Elementor\Classes\Extensions_Manager::get_inactive_extensions();
$has_pro = ha_has_pro();

$total_extensions_count = count( $extensions );
?>
<div class="ha-dashboard-panel">
    <div class="ha-dashboard-panel__header">
        <div class="ha-dashboard-panel__header-content">
            <h2><?php esc_html_e( 'Happy Extensions', 'happy-elementor-addons' ); ?></h2>
            <p class="f16"><?php printf( esc_html__( 'Here is the list of our all %s extensions. You can enable or disable extensions from here to optimize loading speed and Elementor editor experience. %sAfter enabling or disabling any extension make sure to click the Save Changes button.%s', 'happy-elementor-addons' ), $total_extensions_count, '<strong>', '</strong>' ); ?></p>

            <div class="ha-action-list">
                <button type="button" class="ha-action--btn" data-filter="*"><?php esc_html_e( 'All', 'happy-elementor-addons' ); ?></button>
                <button type="button" class="ha-action--btn" data-filter="free"><?php esc_html_e( 'Free', 'happy-elementor-addons' ); ?></button>
                <button type="button" class="ha-action--btn" data-filter="pro"><?php esc_html_e( 'Pro', 'happy-elementor-addons' ); ?></button>
                <span class="ha-action--divider">|</span>
                <button type="button" class="ha-action--btn" data-action="enable_extension"><?php esc_html_e( 'Enable All', 'happy-elementor-addons' ); ?></button>
                <button type="button" class="ha-action--btn" data-action="disable_extension"><?php esc_html_e( 'Disable All', 'happy-elementor-addons' ); ?></button>
            </div>
        </div>
    </div>

    <div class="ha-dashboard-widgets">
        <?php
        foreach ( $extensions as $extension_key => $extension_data ) :
            $title = isset( $extension_data['title'] ) ? $extension_data['title'] : '';
            $icon = isset( $extension_data['icon'] ) ? $extension_data['icon'] : '';
            $is_pro = isset( $extension_data['is_pro'] ) && $extension_data['is_pro'] ? true : false;
            $demo_url = isset( $extension_data['demo'] ) && $extension_data['demo'] ? $extension_data['demo'] : '';
            $is_placeholder = $is_pro && ! ha_has_pro();
            $class_attr = 'ha-dashboard-widgets__item';

            if ( $is_pro ) {
                $class_attr .= ' item--is-pro';
            }

            $checked = '';

            if ( ! in_array( $extension_key, $inactive_extensions ) ) {
                $checked = 'checked="checked"';
            }

            if ( $is_placeholder ) {
                $class_attr .= ' item--is-placeholder';
                $checked = 'disabled="disabled"';
            }
            ?>
            <div class="<?php echo $class_attr; ?>">
                <?php if ( $is_pro ) : ?>
                    <span class="ha-dashboard-widgets__item-badge"><?php esc_html_e( 'Pro', 'happy-elementor-addons' ); ?></span>
                <?php endif; ?>
                <span class="ha-dashboard-widgets__item-icon"><i class="<?php echo $icon; ?>"></i></span>
                <h3 class="ha-dashboard-widgets__item-title">
                    <label for="ha-extension-<?php echo $extension_key; ?>" <?php echo $is_placeholder ? 'data-tooltip="Get pro"' : ''; ?>><?php echo $title; ?></label>
                    <?php if ( $demo_url ) : ?>
                        <a href="<?php echo esc_url( $demo_url ); ?>" target="_blank" rel="noopener" data-tooltip="<?php esc_attr_e( 'Click to view demo / docs', 'happy-elementor-addons' ); ?>" class="ha-dashboard-widgets__item-preview"><i aria-hidden="true" class="eicon-device-desktop"></i></a>
                    <?php endif; ?>
                </h3>
                <div class="ha-dashboard-widgets__item-toggle ha-toggle">
                    <input id="ha-extension-<?php echo $extension_key; ?>" <?php echo $checked; ?> type="checkbox" class="ha-toggle__check ha-extension" name="extensions[]" value="<?php echo $extension_key; ?>">
                    <b class="ha-toggle__switch"></b>
                    <b class="ha-toggle__track"></b>
                </div>
            </div>
        <?php
        endforeach;
        ?>
    </div>

    <div class="ha-dashboard-panel__footer">
        <button disabled class="ha-dashboard-btn ha-dashboard-btn--save" type="submit"><?php esc_html_e( 'Save Settings', 'happy-elementor-addons' ); ?></button>
    </div>
</div>
