<?php
/**
 * Dashboard manager
 *
 * Package: Happy_Addons
 * @since 2.0.0
 */
namespace Happy_Addons\Elementor;

defined( 'ABSPATH' ) || die();

class Dashboard {

    const PAGE_SLUG = 'happy-addons';

    const WIDGETS_NONCE = 'ha_save_dashboard';

    static $menu_slug = '';

    public static function init() {
        add_action( 'admin_menu', [ __CLASS__, 'add_menu' ], 30 );
        add_action( 'admin_menu', [ __CLASS__, 'update_menu_items' ], 99 );
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
        add_action( 'wp_ajax_' . self::WIDGETS_NONCE, [ __CLASS__, 'save_dashboard' ] );
    }

    public static function save_dashboard() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( ! check_ajax_referer( self::WIDGETS_NONCE, 'nonce' ) ) {
            wp_send_json_error();
        }

        $posted_data = ! empty( $_POST['data'] ) ? $_POST['data'] : '';
        $data = [];
        parse_str( $posted_data, $data );

        self::save_widgets_data( $data );

        do_action( 'happyaddons_save_dashboard', $data );

        wp_send_json_success();
    }

    private static function save_widgets_data( $data ) {
        $widgets = ! empty( $data['widgets'] ) ? $data['widgets'] : [];
        $inactive_widgets = array_values( array_diff( array_keys( self::get_real_widgets_map() ), $widgets ) );
        Widgets_Manager::save_inactive_widgets( $inactive_widgets );
    }

    public static function enqueue_scripts( $hook ) {
        if ( self::$menu_slug !== $hook ) {
            return;
        }

        wp_enqueue_style(
            'happy-icons',
            HAPPY_ADDONS_ASSETS . 'fonts/style.min.css',
            null,
            HAPPY_ADDONS_VERSION
        );

        wp_enqueue_style(
            'happy-elementor-addons-dashboard',
            HAPPY_ADDONS_ASSETS . 'admin/css/dashboard.min.css',
            null,
            HAPPY_ADDONS_VERSION
        );

        wp_enqueue_script(
            'happy-elementor-addons-dashboard',
            HAPPY_ADDONS_ASSETS . 'admin/js/dashboard.min.js',
            [ 'jquery' ],
            HAPPY_ADDONS_VERSION,
            true
        );

        wp_localize_script(
            'happy-elementor-addons-dashboard',
            'HappyDashboard',
            [
                'nonce' => wp_create_nonce( self::WIDGETS_NONCE ),
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'action' => self::WIDGETS_NONCE,
                'saveChangesLabel' => esc_html__( 'Save Changes', 'happy-elementor-addons' ),
                'savedLabel' => esc_html__( 'Changes Saved', 'happy-elementor-addons' ),
            ]
        );
    }

    private static function get_real_widgets_map() {
        $widgets_map = Widgets_Manager::get_widgets_map();
        unset( $widgets_map[ Widgets_Manager::get_base_widget_key() ] );
        return $widgets_map;
    }

    public static function get_widgets() {
        $widgets_map = self::get_real_widgets_map();

        if ( ! ha_has_pro() ) {
            $widgets_map = array_merge( $widgets_map, Widgets_Manager::get_pro_widget_map() );
        }

        uksort( $widgets_map, [ __CLASS__, 'sort_widgets' ] );
        return $widgets_map;
    }

    public static function sort_widgets( $k1, $k2 ) {
        return strcasecmp( $k1, $k2 );
    }

    public static function add_menu() {
        self::$menu_slug = add_menu_page(
            __( 'Happy Elementor Addons Dashboard', 'happy-elementor-addons' ),
            __( 'Happy Addons', 'happy-elementor-addons' ),
            'manage_options',
            self::PAGE_SLUG,
            [ __CLASS__, 'render_main' ],
            ha_get_b64_icon(),
            58.5
        );

        foreach ( self::get_tabs() as $tab_key => $tab_data ) {
            if ( empty( $tab_data['renderer'] ) || ! is_callable( $tab_data['renderer'] ) ) {
                continue;
            }

            add_submenu_page(
                self::PAGE_SLUG,
                sprintf( __( '%s - Happy Elementor Addons', 'happy-elementor-addons' ), $tab_data['title'] ),
                $tab_data['title'],
                'manage_options',
                self::PAGE_SLUG . '#tab-content-' . $tab_key,
                [ __CLASS__, 'render_main' ]
            );
        }
    }

    public static function update_menu_items() {
        global $submenu;
        $menu = $submenu[ self::PAGE_SLUG ];
        array_shift( $menu );
        $submenu[ self::PAGE_SLUG ] = $menu;
    }

    public static function get_tabs() {
        $tabs = [
            'home' => [
                'title' => esc_html__( 'Home', 'happy-elementor-addons' ),
                'renderer' => [ __CLASS__, 'render_home' ],
            ],
            'widgets' => [
                'title' => esc_html__( 'Widgets', 'happy-elementor-addons' ),
                'renderer' => [ __CLASS__, 'render_widgets' ],
            ],
            'extras' => [
                'title' => esc_html__( 'Extras', 'happy-elementor-addons' ),
                'renderer' => [ __CLASS__, 'render_extras' ],
            ],
            'pro' => [
                'title' => esc_html__( 'Get Pro', 'happy-elementor-addons' ),
                'renderer' => [ __CLASS__, 'render_pro' ],
            ],
        ];

        return apply_filters( 'happyaddons_dashboard_get_tabs', $tabs );
    }

    public static function render_main() {
        ?>
        <div class="wrap">
            <h1 class="screen-reader-text"><?php esc_html_e( 'Happy Elementor Addons', 'happy-elementor-addons' ); ?></h1>
            <form class="ha-dashboard" id="ha-dashboard-form">
                <div class="ha-dashboard-tabs" role="tablist">
                    <div class="ha-dashboard-tabs__nav">
                        <?php
                        $tab_count = 1;
                        foreach ( self::get_tabs() as $slug => $data ) :
                            if ( empty( $data['renderer'] ) || ! is_callable( $data['renderer'] ) ) {
                                continue;
                            }

                            $slug = esc_attr( strtolower( $slug ) );

                            $class = 'ha-dashboard-tabs__nav-item ha-dashboard-tabs__nav-item--' . $slug;
                            if ( $tab_count === 1 ) {
                                $class .= ' tab--is-active';
                            }

                            printf( '<a href="#tab-content-%1$s" aria-controls="tab-content-%1$s" id="tab-nav-%1$s" class="%2$s" role="tab">%3$s</a>',
                                $slug,
                                $class,
                                isset( $data['title'] ) ? $data['title'] : sprintf( esc_html__( 'Tab %s', 'happy-elementor-addons' ), $tab_count )
                                );

                            ++$tab_count;
                        endforeach;
                        ?>

                        <button disabled class="ha-dashboard-tabs__nav-btn ha-dashboard-btn ha-dashboard-btn--lg ha-dashboard-btn--save" type="submit"><?php esc_html_e( 'Save Settings', 'happy-elementor-addons' ); ?></button>
                    </div>
                    <div class="ha-dashboard-tabs__content">
                        <?php
                        $tab_count = 1;
                        foreach ( self::get_tabs() as $slug => $data ) :
                            if ( empty( $data['renderer'] ) || ! is_callable( $data['renderer'] ) ) {
                                continue;
                            }

                            $class = 'ha-dashboard-tabs__content-item';
                            if ( $tab_count === 1 ) {
                                $class .= ' tab--is-active';
                            }

                            $slug = esc_attr( strtolower( $slug ) );
                            ?>
                            <div class="<?php echo $class; ?>" id="tab-content-<?php echo $slug; ?>" role="tabpanel" aria-labelledby="tab-nav-<?php echo $slug; ?>">
                                <?php call_user_func( $data['renderer'], $slug, $data ); ?>
                            </div>
                            <?php
                            ++$tab_count;
                        endforeach;
                        ?>
                    </div>
                </div>
            </form>
        </div>
        <?php
    }

    public static function render_home( $slug ) {
        echo '<h2>', $slug ,'</h2>';
    }

    public static function render_widgets() {
        ?>
        <div class="ha-dashboard-panel">
            <div class="ha-dashboard-panel__header">
                <div class="ha-dashboard-panel__header-content">
                    <h2><?php esc_html_e( 'Happy Widgets', 'happy-elementor-addons' ); ?></h2>
                    <p><?php esc_html_e( 'Here is the list of our all widgets. You can enable or disable widgets from here to optimize loading speed and Elementor editor experience.', 'happy-elementor-addons' ); ?></p>

                    <div class="ha-action-list">
                        <button type="button" class="ha-action--btn" data-filter="*"><?php esc_html_e( 'All', 'happy-elementor-addons' ); ?></button>
                        <button type="button" class="ha-action--btn" data-filter="free"><?php esc_html_e( 'Free', 'happy-elementor-addons' ); ?></button>
                        <button type="button" class="ha-action--btn" data-filter="pro"><?php esc_html_e( 'Pro', 'happy-elementor-addons' ); ?></button>
                        <span class="ha-action--divider">|</span>
                        <button type="button" class="ha-action--btn" data-action="enable"><?php esc_html_e( 'Enable All', 'happy-elementor-addons' ); ?></button>
                        <button type="button" class="ha-action--btn" data-action="disable"><?php esc_html_e( 'Disable All', 'happy-elementor-addons' ); ?></button>
                    </div>
                </div>
            </div>

            <div class="ha-dashboard-widgets">
                <?php
                $widgets = self::get_widgets();
                $inactive_widgets = Widgets_Manager::get_inactive_widgets();

                foreach ( $widgets as $widget_key => $widget_data ) :
                    $title = isset( $widget_data['title'] ) ? $widget_data['title'] : '';
                    $icon = isset( $widget_data['icon'] ) ? $widget_data['icon'] : '';
                    $is_pro = isset( $widget_data['is_pro'] ) && $widget_data['is_pro'] ? true : false;
                    $demo_url = isset( $widget_data['demo'] ) && $widget_data['demo'] ? $widget_data['demo'] : '';
                    $is_placeholder = $is_pro && ! ha_has_pro();
                    $class_attr = 'ha-dashboard-widgets__item';

                    if ( $is_pro ) {
                        $class_attr .= ' item--is-pro';
                    }

                    $checked = 'checked="checked"';

                    if ( in_array( $widget_key, $inactive_widgets ) ) {
                        $checked = '';
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
                            <label for="ha-widget-<?php echo $widget_key; ?>"><?php echo $title; ?></label>
                            <?php if ( $demo_url ) : ?>
                                <a href="<?php echo esc_url( $demo_url ); ?>" target="_blank" rel="noopener" class="ha-dashboard-widgets__item-preview"><i aria-hidden="true" class="eicon-device-desktop"></i></a>
                            <?php endif; ?>
                        </h3>
                        <div class="ha-dashboard-widgets__item-toggle ha-toggle">
                            <input id="ha-widget-<?php echo $widget_key; ?>" <?php echo $checked; ?> type="checkbox" class="ha-toggle__check" name="widgets[]" value="<?php echo $widget_key; ?>">
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
        <?php
    }

    public static function render_extras( $slug ) {
        echo '<h2>', $slug ,'</h2>';
    }

    public static function render_pro( $slug ) {
        echo '<h2>', $slug ,'</h2>';
    }
}
