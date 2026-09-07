<?php
/**
 * Dashboard features tab template
 */

defined( 'ABSPATH' ) || die();

$features = self::get_features();
$inactive_features = \Happy_Addons\Elementor\Classes\Extensions_Manager::get_inactive_features();
$always_on_features = \Happy_Addons\Elementor\Classes\Extensions_Manager::get_always_on_features();
$has_pro = ha_has_pro();

$total_features_count = count( $features );

$always_on_group_keys = [];
$gsap_group_keys = array_keys( self::get_gsap_features() );
$general_group_keys = [];

foreach ( $features as $feature_key => $feature_data ) {
	$is_always_on = in_array( $feature_key, $always_on_features, true );

	if ( in_array( $feature_key, $gsap_group_keys, true ) ) {
		continue;
	} elseif ( $is_always_on ) {
		$always_on_group_keys[] = $feature_key;
	} else {
		$general_group_keys[] = $feature_key;
	}
}
?>
<div class="ha-dashboard-panel">
    <div class="ha-dashboard-panel__header">
        <div class="ha-dashboard-panel__header-content">
            <h2><?php esc_html_e( 'Happy Features', 'happy-elementor-addons' ); ?></h2>
            <p class="f16"><?php printf( esc_html__( 'Here is the list of our all %s features. You can enable or disable features from here to optimize loading speed and Elementor editor experience. %sAfter enabling or disabling any feature make sure to click the Save Changes button.%s', 'happy-elementor-addons' ), $total_features_count, '<strong>', '</strong>' ); ?></p>

            <div class="ha-action-list">
                <button type="button" class="ha-action--btn" data-filter="*"><?php esc_html_e( 'All', 'happy-elementor-addons' ); ?></button>
                <button type="button" class="ha-action--btn" data-filter="free"><?php esc_html_e( 'Free', 'happy-elementor-addons' ); ?></button>
                <button type="button" class="ha-action--btn" data-filter="pro"><?php esc_html_e( 'Pro', 'happy-elementor-addons' ); ?></button>
                <span class="ha-action--divider">|</span>
                <button type="button" class="ha-action--btn" data-action="enable_feature"><?php esc_html_e( 'Enable All', 'happy-elementor-addons' ); ?></button>
                <button type="button" class="ha-action--btn" data-action="disable_feature"><?php esc_html_e( 'Disable All', 'happy-elementor-addons' ); ?></button>
            </div>
        </div>
    </div>

    <div class="ha-dashboard-widgets ha-dashboard-widgets--tab">
        <?php if ( ! empty( $general_group_keys ) ) : ?>
            <h2 style="width: 100%; margin-left: 10px;"><?php esc_html_e( 'General Features', 'happy-elementor-addons' ); ?></h2>
            <?php foreach ( $general_group_keys as $feature_key ) :
                $feature_data = $features[ $feature_key ];
                $title = isset( $feature_data['title'] ) ? $feature_data['title'] : '';
                $icon = isset( $feature_data['icon'] ) ? $feature_data['icon'] : '';
                $is_pro = isset( $feature_data['is_pro'] ) && $feature_data['is_pro'] ? true : false;
                $demo_url = isset( $feature_data['demo'] ) && $feature_data['demo'] ? $feature_data['demo'] : '';
                $is_placeholder = $is_pro && ! $has_pro;
                $class_attr = 'ha-dashboard-widgets__item';

                if ( $is_pro ) {
                    $class_attr .= ' item--is-pro';
                }

                $checked = '';

                if ( ! in_array( $feature_key, $inactive_features ) ) {
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
                        <label for="ha-widget-<?php echo $feature_key; ?>" <?php echo $is_placeholder ? 'data-tooltip="Get pro"' : ''; ?>><?php echo $title; ?></label>
                        <?php if ( $demo_url ) : ?>
                            <a href="<?php echo esc_url( $demo_url ); ?>" target="_blank" rel="noopener" data-tooltip="<?php esc_attr_e( 'Click to view demo / docs', 'happy-elementor-addons' ); ?>" class="ha-dashboard-widgets__item-preview"><i aria-hidden="true" class="eicon-device-desktop"></i></a>
                        <?php endif; ?>
                    </h3>
                    <div class="ha-dashboard-widgets__item-toggle ha-toggle">
                        <input id="ha-widget-<?php echo $feature_key; ?>" <?php echo $checked; ?> type="checkbox" class="ha-toggle__check ha-feature" name="features[]" value="<?php echo $feature_key; ?>">
                        <b class="ha-toggle__switch"></b>
                        <b class="ha-toggle__track"></b>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if ( ! empty( $always_on_group_keys ) ) : ?>
            <h2 style="width: 100%; margin-left: 10px;"><?php esc_html_e( 'Always On', 'happy-elementor-addons' ); ?></h2>
            <?php foreach ( $always_on_group_keys as $feature_key ) :
                $feature_data = $features[ $feature_key ];
                $title = isset( $feature_data['title'] ) ? $feature_data['title'] : '';
                $icon = isset( $feature_data['icon'] ) ? $feature_data['icon'] : '';
                $is_pro = isset( $feature_data['is_pro'] ) && $feature_data['is_pro'] ? true : false;
                $demo_url = isset( $feature_data['demo'] ) && $feature_data['demo'] ? $feature_data['demo'] : '';
                $is_placeholder = $is_pro && ! $has_pro;
                $class_attr = 'ha-dashboard-widgets__item item--is-always-on';

                if ( $is_pro ) {
                    $class_attr .= ' item--is-pro';
                }

                if ( $is_placeholder ) {
                    $class_attr .= ' item--is-placeholder';
                }
                ?>
                <div class="<?php echo $class_attr; ?>">
                    <?php if ( $is_pro ) : ?>
                        <span class="ha-dashboard-widgets__item-badge"><?php esc_html_e( 'Pro', 'happy-elementor-addons' ); ?></span>
                    <?php endif; ?>
                    <span class="ha-dashboard-widgets__item-icon"><i class="<?php echo $icon; ?>"></i></span>
                    <h3 class="ha-dashboard-widgets__item-title">
                        <label for="ha-widget-<?php echo $feature_key; ?>" <?php echo $is_placeholder ? 'data-tooltip="Get pro"' : ''; ?>><?php echo $title; ?></label>
                        <?php if ( $demo_url ) : ?>
                            <a href="<?php echo esc_url( $demo_url ); ?>" target="_blank" rel="noopener" data-tooltip="<?php esc_attr_e( 'Click to view demo / docs', 'happy-elementor-addons' ); ?>" class="ha-dashboard-widgets__item-preview"><i aria-hidden="true" class="eicon-device-desktop"></i></a>
                        <?php endif; ?>
                    </h3>
                    <div class="ha-dashboard-widgets__item-toggle ha-toggle">
                        <?php if ( $is_placeholder ) : ?>
                            <input id="ha-widget-<?php echo $feature_key; ?>" type="checkbox" class="ha-toggle__check ha-feature" disabled="disabled">
                        <?php else : ?>
                            <input type="hidden" name="features[]" value="<?php echo $feature_key; ?>">
                            <input id="ha-widget-<?php echo $feature_key; ?>" checked="checked" type="checkbox" class="ha-toggle__check" disabled="disabled">
                        <?php endif; ?>
                        <b class="ha-toggle__switch"></b>
                        <b class="ha-toggle__track"></b>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="ha-dashboard-panel__footer">
        <button disabled class="ha-dashboard-btn ha-dashboard-btn--save" type="submit"><?php esc_html_e( 'Save Settings', 'happy-elementor-addons' ); ?></button>
    </div>
</div>
