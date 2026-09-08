<?php
/**
 * Dashboard GSAP tab template.
 */

defined( 'ABSPATH' ) || die();

$features = self::get_gsap_features();
$widgets = self::get_gsap_widgets();
$inactive_features = \Happy_Addons\Elementor\Classes\Extensions_Manager::get_inactive_features();
$inactive_widgets = \Happy_Addons\Elementor\Classes\Widgets_Manager::get_inactive_widgets();
$has_pro = ha_has_pro();

// GSAP items are managed ONLY here. Non-GSAP items are provided by the
// Features and Widgets tab checkboxes, since all tabs share one form
// and the save endpoint serializes the entire form.
?>
<div class="ha-dashboard-panel">
    <div class="ha-dashboard-panel__header">
        <div class="ha-dashboard-panel__header-content">
            <h2><?php esc_html_e( 'GSAP', 'happy-elementor-addons' ); ?></h2>
            <p class="f16"><?php printf( esc_html__( 'Here is the list of our %1$s GSAP features and %2$s GSAP widgets. These settings are also reflected on the Features and Widgets pages. %3$sAfter enabling or disabling any feature or widget make sure to click the Save Changes button.%4$s', 'happy-elementor-addons' ), count( $features ), count( $widgets ), '<strong>', '</strong>' ); ?></p>

            <div class="ha-action-list">
                <button type="button" class="ha-action--btn" data-filter="*"><?php esc_html_e( 'All', 'happy-elementor-addons' ); ?></button>
                <button type="button" class="ha-action--btn" data-filter="free"><?php esc_html_e( 'Free', 'happy-elementor-addons' ); ?></button>
                <button type="button" class="ha-action--btn" data-filter="pro"><?php esc_html_e( 'Pro', 'happy-elementor-addons' ); ?></button>
                <span class="ha-action--divider">|</span>
                <button type="button" class="ha-action--btn" data-action="enable_gsap"><?php esc_html_e( 'Enable All', 'happy-elementor-addons' ); ?></button>
                <button type="button" class="ha-action--btn" data-action="disable_gsap"><?php esc_html_e( 'Disable All', 'happy-elementor-addons' ); ?></button>
            </div>
        </div>
    </div>

    <div class="ha-dashboard-widgets ha-dashboard-widgets--tab">
        <h2 style="width: 100%; margin-left: 10px;"><?php esc_html_e( 'GSAP Features', 'happy-elementor-addons' ); ?></h2>
        <?php foreach ( $features as $feature_key => $feature_data ) :
            $is_pro = ! empty( $feature_data['is_pro'] );
            $is_placeholder = $is_pro && ! $has_pro;
            $class_attr = 'ha-dashboard-widgets__item' . ( $is_pro ? ' item--is-pro' : '' ) . ( $is_placeholder ? ' item--is-placeholder' : '' );
            $checked = ! in_array( $feature_key, $inactive_features, true ) ? 'checked="checked"' : '';
            if ( $is_placeholder ) { $checked = 'disabled="disabled"'; }
            ?>
            <div class="<?php echo esc_attr( $class_attr ); ?>">
                <?php if ( $is_pro ) : ?><span class="ha-dashboard-widgets__item-badge"><?php esc_html_e( 'Pro', 'happy-elementor-addons' ); ?></span><?php endif; ?>
                <span class="ha-dashboard-widgets__item-icon"><i class="<?php echo esc_attr( $feature_data['icon'] ); ?>"></i></span>
                <h3 class="ha-dashboard-widgets__item-title"><label for="ha-gsap-feature-<?php echo esc_attr( $feature_key ); ?>"><?php echo esc_html( $feature_data['title'] ); ?></label></h3>
                <div class="ha-dashboard-widgets__item-toggle ha-toggle">
                    <input id="ha-gsap-feature-<?php echo esc_attr( $feature_key ); ?>" <?php echo $checked; ?> type="checkbox" class="ha-toggle__check ha-feature" name="features[]" value="<?php echo esc_attr( $feature_key ); ?>">
                    <b class="ha-toggle__switch"></b><b class="ha-toggle__track"></b>
                </div>
            </div>
        <?php endforeach; ?>

        <h2 style="width: 100%; margin-left: 10px;"><?php esc_html_e( 'GSAP Widgets', 'happy-elementor-addons' ); ?></h2>
        <?php foreach ( $widgets as $widget_key => $widget_data ) :
            $is_pro = ! empty( $widget_data['is_pro'] );
            $is_placeholder = $is_pro && ! $has_pro;
            $class_attr = 'ha-dashboard-widgets__item' . ( $is_pro ? ' item--is-pro' : '' ) . ( $is_placeholder ? ' item--is-placeholder' : '' );
            $checked = ! in_array( $widget_key, $inactive_widgets, true ) ? 'checked="checked"' : '';
            if ( $is_placeholder ) { $checked = 'disabled="disabled"'; }
            ?>
            <div class="<?php echo esc_attr( $class_attr ); ?>">
                <?php if ( $is_pro ) : ?><span class="ha-dashboard-widgets__item-badge"><?php esc_html_e( 'Pro', 'happy-elementor-addons' ); ?></span><?php endif; ?>
                <span class="ha-dashboard-widgets__item-icon"><i class="<?php echo esc_attr( $widget_data['icon'] ); ?>"></i></span>
                <h3 class="ha-dashboard-widgets__item-title"><label for="ha-gsap-widget-<?php echo esc_attr( $widget_key ); ?>"><?php echo esc_html( $widget_data['title'] ); ?></label></h3>
                <div class="ha-dashboard-widgets__item-toggle ha-toggle">
                    <input id="ha-gsap-widget-<?php echo esc_attr( $widget_key ); ?>" <?php echo $checked; ?> type="checkbox" class="ha-toggle__check ha-widget" name="widgets[]" value="<?php echo esc_attr( $widget_key ); ?>">
                    <b class="ha-toggle__switch"></b><b class="ha-toggle__track"></b>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="ha-dashboard-panel__footer"><button disabled class="ha-dashboard-btn ha-dashboard-btn--save" type="submit"><?php esc_html_e( 'Save Settings', 'happy-elementor-addons' ); ?></button></div>
</div>
