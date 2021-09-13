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
        $default_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

        // Backup and delete our new dashboard widget from the end of the array.
        $example_widget_backup = ['happy_addons_news_update' => $default_dashboard['happy_addons_news_update']];
        // unset( $default_dashboard['happy_addons_news_update'] );

        // Merge the two arrays together so our widget is at the beginning.
        $sorted_dashboard = array_merge($example_widget_backup, $default_dashboard);

        // Save the sorted array back into the original metaboxes. 
        $wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
    }

    /**
     * Create the function to output the content of our Dashboard Widget.
     */
    public function happy_addons_news_update_function() {
        // Display whatever you want to show.
        // esc_html_e("Howdy! I'm a great Dashboard Widget.", "happy-elementor-addons");
?>
        <img style="width: 100%" src="https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?ixid=MnwxMjA3fDF8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1189&q=80" alt="">
        <h3><?php //_e('News & Updates:', 'happy-elementor-addons'); 
            ?></h3>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Natus perspiciatis eos veritatis mollitia nobis illo explicabo, iure nostrum suscipit fuga! Fugit cupiditate at, eveniet voluptate doloremque nisi earum sunt temporibus.</p>

        <?php // Get RSS Feed(s)
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

        <ul>
            <?php if ($maxitems == 0) : ?>
                <li><?php _e('No items', 'happy-elementor-addons'); ?></li>
            <?php else : ?>
                <?php // Loop through each feed item and display each item as a hyperlink. 
                ?>
                <?php foreach ($rss_items as $item) : ?>
                    <li>
                        <a href="<?php echo esc_url($item->get_permalink()); ?>" title="<?php printf(__('Posted %s', 'happy-elementor-addons'), $item->get_date('j F Y | g:i a')); ?>">
                            <?php echo esc_html($item->get_title()); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        <div class="e-overview__footer e-divider_top">
            <ul style="display: flex; list-style: none;border-top: 1px solid #ddd;padding-top: 10px;">
                <li class="e-overview__blog"><a href="https://go.elementor.com/overview-widget-blog/" target="_blank">Blog<span aria-hidden="true" class="dashicons dashicons-external"></span></a></li>
                <li style="border-left: 1px solid #ddd; padding-left: 5px; margin-right: 5px;" class="e-overview__help"><a href="https://go.elementor.com/overview-widget-docs/" target="_blank">Help <span aria-hidden="true" class="dashicons dashicons-external"></span></a></li>
                <li style="border-left: 1px solid #ddd; padding-left: 5px; margin-right: 5px;" class="e-overview__go-pro"><a href="https://elementor.com/pro/?utm_source=wp-overview-widget&amp;utm_campaign=gopro&amp;utm_medium=wp-dash&amp;utm_term=helloelementor" target="_blank">Go Pro <span aria-hidden="true" class="dashicons dashicons-external"></span></a></li>
                <li style="border-left: 1px solid #ddd; padding-left: 5px; margin-right: 5px;" class="e-overview__find_an_expert"><a href="https://go.elementor.com/go-pro-find-an-expert" target="_blank">Community <span aria-hidden="true" class="dashicons dashicons-external"></span></a></li>
                <li style="border-left: 1px solid #ddd; padding-left: 5px; margin-right: 5px;" class="e-overview__find_an_expert"><a href="https://go.elementor.com/go-pro-find-an-expert" target="_blank">What’s New <span aria-hidden="true" class="dashicons dashicons-external"></span></a></li>
            </ul>
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
