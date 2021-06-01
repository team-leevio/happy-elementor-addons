<?php

/**
 * Dashboard widgets tab template
 */

defined('ABSPATH') || die();

?>
<div class="ha-dashboard-panel">
    <div class="ha-dashboard-panel__header">
        <div class="ha-dashboard-panel__header-content">
            <h2><?php esc_html_e('Happy Credentials', 'happy-elementor-addons'); ?></h2>
            <p class="f16"><?php printf(esc_html__('Here is the list of our all %s widgets. You can enable or disable widgets from here to optimize loading speed and Elementor editor experience. %sAfter enabling or disabling any widget make sure to click the Save Changes button.%s', 'happy-elementor-addons'), $total_widgets_count, '<strong>', '</strong>'); ?></p>

            <div class="ha-action-list">
                <button type="button" class="ha-action--btn" data-filter="*"><?php esc_html_e('All', 'happy-elementor-addons'); ?></button>
                <button type="button" class="ha-action--btn" data-filter="free"><?php esc_html_e('Free', 'happy-elementor-addons'); ?></button>
                <button type="button" class="ha-action--btn" data-filter="pro"><?php esc_html_e('Pro', 'happy-elementor-addons'); ?></button>
                <span class="ha-action--divider">|</span>
                <button type="button" class="ha-action--btn" data-action="enable"><?php esc_html_e('Enable All', 'happy-elementor-addons'); ?></button>
                <button type="button" class="ha-action--btn" data-action="disable"><?php esc_html_e('Disable All', 'happy-elementor-addons'); ?></button>
            </div>
        </div>
    </div>

    <div class="ha-dashboard-widgets">
        <div class="ha-dashboard-widgets__item item--is-pro">
            <span class="ha-dashboard-widgets__item-badge">Pro</span>
            <span class="ha-dashboard-widgets__item-icon"><i class="hm hm-accordion-vertical"></i></span>
            <h3 class="ha-dashboard-widgets__item-title">
                <label for="ha-widget-accordion">Advanced Accordion</label>
            </h3>
            <div class="ha-dashboard-widgets__item-toggle ha-toggle">
                <input id="ha-widget-accordion" checked="checked" type="checkbox" class="ha-toggle__check ha-widget" name="widgets[]" value="accordion">
                <b class="ha-toggle__switch"></b>
                <b class="ha-toggle__track"></b>
            </div>
        </div>
        <div class="ha-dashboard-widgets__item item--is-pro">
            <span class="ha-dashboard-widgets__item-badge">Pro</span>
            <span class="ha-dashboard-widgets__item-icon"><i class="hm hm-accordion-vertical"></i></span>
            <h3 class="ha-dashboard-widgets__item-title">
                <label for="ha-widget-accordion">Advanced Accordion</label>
            </h3>
            <div class="ha-dashboard-widgets__item-toggle ha-toggle">
                <input id="ha-widget-accordion" checked="checked" type="checkbox" class="ha-toggle__check ha-widget" name="widgets[]" value="accordion">
                <b class="ha-toggle__switch"></b>
                <b class="ha-toggle__track"></b>
            </div>
        </div>
    </div>

    <div class="ha-dashboard-panel__footer">
        <button disabled class="ha-dashboard-btn ha-dashboard-btn--save" type="submit"><?php esc_html_e('Save Settings', 'happy-elementor-addons'); ?></button>
    </div>
</div>