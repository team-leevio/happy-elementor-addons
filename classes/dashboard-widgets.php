<?php

namespace Happy_Addons\Elementor;

defined('ABSPATH') || die();

class Dashboard_Widgets {

    private static $instance;

    public function init() {
        add_action('wp_dashboard_setup', [$this, 'add_dashboard_widgets'], 999);
    }

    /**
     * Add a widget to the dashboard.
     *
     * This function is hooked into the 'wp_dashboard_setup' action below.
     */
    public function add_dashboard_widgets() {
        wp_add_dashboard_widget(
            'happy_addons_news_update',
            esc_html__('HappyAddons News & Updates', 'happy-elementor-addons'),
            [$this, 'happy_addons_news_update_function']
        );

        // Globalize the metaboxes array, this holds all the widgets for wp-admin.
        global $wp_meta_boxes;

        // Get the regular dashboard widgets array 
        // (which already has our new widget but appended at the end).
        $existing_dwidgets = $wp_meta_boxes['dashboard']['normal']['core'];

        // Backup and delete our new dashboard widget from the end of the array.
        $happy_dashboard_widget = ['happy_addons_news_update' => $existing_dwidgets['happy_addons_news_update']];

        // Merge the two arrays together so our widget is at the beginning.
        $sorted_dashboard = array_merge($happy_dashboard_widget, $existing_dwidgets);

        // Save the sorted array back into the original metaboxes. 
        $wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
    }

    /**
     * Create the function to output the content of our Dashboard Widget.
     */
    public function happy_addons_news_update_function() {
        // Get RSS Feed(s)
        include_once(ABSPATH . WPINC . '/feed.php');

        // Get a SimplePie feed object from the specified feed source.
        $rss = fetch_feed('https://happyaddons.com/feed/');

        if (!is_wp_error($rss)) : // Checks that the object is created correctly

            // Figure out how many total items there are, but limit it to 5. 
            $maxitems = $rss->get_item_quantity(5);

            // Build an array of all the items, starting with element 0 (first element).
            $rss_items = $rss->get_items(0, $maxitems);

        endif;
?>
        <div class="ha-dashboard-widget">
            <div class="ha-overview__feed">
                <img class="ha-overview--banner" src="https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?ixid=MnwxMjA3fDF8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1189&q=80" alt="<?php esc_attr_e('HappyAddons Banner', 'happy-elementor-addons'); ?>">
                <p class="ha-instruction ha-divider-bottom">Lorem ipsum dolor sit amet consectetur adipisicing elit. Minima dolores rerum consequuntur harum assumenda blanditiis nulla quam iure nisi commodi nesciunt dolor doloremque, sint incidunt eum odio, deleniti ipsa iusto!</p>
                <ul class="ha-overview__posts">
                    <?php if ($maxitems == 0) : ?>
                        <li class="ha-overview__post"><?php _e('No items', 'happy-elementor-addons'); ?></li>
                    <?php else : ?>
                        <?php foreach ($rss_items as $item) : ?>
                            <li class="ha-overview__post">
                                <a href="<?php echo esc_url($item->get_permalink()); ?>" title="<?php printf(__('Posted %s', 'happy-elementor-addons'), $item->get_date('j F Y | g:i a')); ?>" class="ha-overview__post-link" target="_blank"><?php echo esc_html($item->get_title()); ?></a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="ha-overview__footer ha-divider-top">
                <ul>
                    <li class="ha-overview__blog">
                        <a href="https://happyaddons.com/blog/" target="_blank"><?php esc_html_e('Blog', 'happy-elementor-addons'); ?> <span aria-hidden="true" class="dashicons dashicons-external"></span></a>
                    </li>
                    <li class="ha-overview__help">
                        <a href="https://happyaddons.com/docs/" target="_blank"><?php esc_html_e('Help', 'happy-elementor-addons'); ?> <span aria-hidden="true" class="dashicons dashicons-external"></span></a>
                    </li>
                    <li class="ha-overview__go-pro">
                        <a href="https://happyaddons.com/pricing/" target="_blank"><?php esc_html_e('Go Pro', 'happy-elementor-addons'); ?> <span aria-hidden="true" class="dashicons dashicons-external"></span>
                        </a>
                    </li>
                    <li class="ha-overview__community">
                        <a href="https://www.facebook.com/groups/HappyAddonsCommunity" target="_blank"><?php esc_html_e('Community', 'happy-elementor-addons'); ?> <span aria-hidden="true" class="dashicons dashicons-external"></span></a>
                    </li>
                    <li class="ha-overview__whats-new">
                        <a href="https://happyaddons.com/whats-new-in-happyaddons/" target="_blank"><?php esc_html_e('What’s New', 'happy-elementor-addons'); ?> <span aria-hidden="true" class="dashicons dashicons-external"></span></a>
                    </li>
                </ul>
            </div>
        </div>
<?php
    }

    public static function instance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}

Dashboard_Widgets::instance()->init();
