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
        </div>
    </div>

    <div class="ha-dashboard-widgets">
        <div class="ha-dashboard-widgets__item">
            <span class="ha-dashboard-widgets__item-icon"><i class="hm hm-accordion-vertical"></i></span>
            <h3 class="ha-dashboard-widgets__item-title">
                <label for="ha-widget-accordion">MailChimp</label>
            </h3>
            <div class="ha-dashboard-widgets__item-toggle">
                <input id="ha-widget-accordion" type="text" class="ha-widget" name="credentials[mailchimp_api]" value="">
            </div>
        </div>
        <!-- <div class="ha-dashboard-widgets__item item--is-pro">
            <span class="ha-dashboard-widgets__item-badge">Pro</span>
            <span class="ha-dashboard-widgets__item-icon"><i class="hm hm-accordion-vertical"></i></span>
            <h3 class="ha-dashboard-widgets__item-title">
                <label for="ha-widget-accordion">Advanced Accordion</label>
            </h3>
            <div class="ha-dashboard-widgets__item-toggle">
                <input id="ha-widget-accordion" type="text" class="ha-widget" name="credentials[instragram][user_id]" value="accordion">
                <input id="ha-widget-accordion" type="text" class="ha-widget" name="credentials[instragram][user_api]" value="accordion">
                <b class="ha-toggle__switch"></b>
                <b class="ha-toggle__track"></b>
            </div>
        </div> -->
    </div>

    <div class="ha-dashboard-panel__footer">
        <button disabled class="ha-dashboard-btn ha-dashboard-btn--save" type="submit"><?php esc_html_e('Save Settings', 'happy-elementor-addons'); ?></button>
    </div>
</div>