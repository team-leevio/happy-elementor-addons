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

    static $menu_slug = '';

    public static function init() {
        add_action( 'admin_menu', [ __CLASS__, 'add_menu' ], 30 );
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
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
    }

    public static function get_widgets() {
        $widgets_map = Widgets_Manager::get_widgets_map();
        unset( $widgets_map[ Widgets_Manager::get_base_widget_key() ] );

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
            'data:image/svg+xml;base64,PHN2ZyBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMzIiIGhlaWdodD0iMzIiPjxzdHlsZT48L3N0eWxlPjxwYXRoIGQ9Ik0yOC42IDcuOGguOGMuNSAwIC45LS41LjgtMSAwLS41LS41LS45LTEtLjgtMy41LjMtNi44LTEuOS03LjgtNS4zLS4xLS41LS42LS43LTEuMS0uNi0uNS4xLS43LjYtLjYgMS4xIDEuMiAzLjkgNC45IDYuNiA4LjkgNi42eiIvPjxwYXRoIGQ9Ik0zMCAxMS4xYy0uMy0uNi0uOS0xLTEuNi0xLS45IDAtMS45IDAtMi44LS4yLTQtLjgtNy0zLjYtOC40LTcuMS0uMy0uNi0uOS0xLjEtMS42LTFDOC4zIDEuOSAxLjggNy40LjkgMTUuMS4xIDIyLjIgNC41IDI5IDExLjMgMzEuMiAyMCAzNC4xIDI5IDI4LjcgMzAuOCAxOS45Yy43LTMuMS4zLTYuMS0uOC04Ljh6bS0xMS42IDEuMWMuMS0uNS42LS44IDEuMS0uN2wzLjcuOGMuNS4xLjguNi43IDEuMXMtLjYuOC0xLjEuN2wtMy43LS44Yy0uNC0uMS0uOC0uNi0uNy0xLjF6TTEwLjEgMTFjLjItMS4xIDEuNC0xLjkgMi41LTEuNiAxLjEuMiAxLjkgMS40IDEuNiAyLjUtLjIgMS4xLTEuNCAxLjktMi41IDEuNi0xLS4yLTEuOC0xLjMtMS42LTIuNXptMTQuNiAxMC42QzIyLjggMjYgMTcuOCAyOC41IDEzIDI3Yy0zLjYtMS4yLTYuMi00LjUtNi41LTguMi0uMS0xIC44LTEuNyAxLjctMS42bDE1LjQgMi41Yy45IDAgMS40IDEgMS4xIDEuOXoiLz48cGF0aCBkPSJNMTcuMSAyMi44Yy0xLjktLjQtMy43LjMtNC43IDEuNy0uMi4zLS4xLjcuMi45LjYuMyAxLjIuNSAxLjkuNyAxLjguNCAzLjcuMSA1LjEtLjcuMy0uMi40LS42LjItLjktLjctLjktMS42LTEuNS0yLjctMS43eiIvPjwvc3ZnPg==',
            58.5
        );
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

        return apply_filters( 'happyaddons_dashbaord_get_tabs', $tabs );
    }

    public static function render_main() {
        ?>
        <div class="wrap">
            <h1 class="screen-reader-text"><?php esc_html_e( 'Happy Addons', 'happy-elementor-addons' ); ?></h1>
            <div class="ha-dashboard">
                <div class="ha-dashboard-tabs" role="tablist">
                    <div class="ha-dashboard-tabs__nav">
                        <?php
                        $tab_count = 1;
                        foreach ( self::get_tabs() as $slug => $data ) :
                            if ( empty( $data['renderer'] ) || ! is_callable( $data['renderer'] ) ) {
                                continue;
                            }

                            $class = 'ha-dashboard-tabs__nav-item';
                            if ( $tab_count === 1 ) {
                                $class .= ' tab--is-active';
                            }

                            $slug = esc_attr( strtolower( $slug ) );
                            printf( '<a href="#tab-content-%1$s" aria-controls="tab-content-%1$s" id="tab-nav-%1$s" class="%2$s" role="tab"><i class="hm hm-happyaddons"></i> %3$s</a>',
                                $slug,
                                $class,
                                isset( $data['title'] ) ? $data['title'] : sprintf( esc_html__( 'Tab %s', 'happy-elementor-addons' ), $tab_count )
                                );

                            ++$tab_count;
                        endforeach;
                        ?>

                        <a target="_blank" rel="noopener" href="#" class="ha-dashboard-tabs__nav-btn ha-dashboard-btn ha-dashboard-btn--pro"><?php esc_html_e( 'Upgrade To Pro', 'happy-elementor-addons' ); ?> <span class="dashicons dashicons-external"></span></a>
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
            </div>
        </div>
        <?php
    }

    public static function render_home() {
        ?>
        <form action="" class="ha-dashboard-widgets">
            <?php
            $widgets = self::get_widgets();
            foreach ( $widgets as $widget_key => $widget_data ) :
                $title = isset( $widget_data['title'] ) ? $widget_data['title'] : '';
                $icon = isset( $widget_data['icon'] ) ? $widget_data['icon'] : '';
                $is_pro = isset( $widget_data['is_pro'] ) && $widget_data['is_pro'] ? true : false;
                $is_placeholder = $is_pro && ! ha_has_pro();
                $class_attr = 'ha-dashboard-widgets__item';

                if ( $is_pro ) {
                    $class_attr .= ' item--is-pro';
                }

                $checked = 'checked="checked"';

                if ( $is_placeholder ) {
                    $class_attr .= ' item--is-placeholder';
                    $checked = '';
                }
                ?>
                <div class="<?php echo $class_attr; ?>">
                    <?php if ( $is_pro ) : ?>
                        <span class="ha-dashboard-widgets__item-label"><?php esc_html_e( 'Pro', 'happy-elementor-addons' ); ?></span>
                    <?php endif; ?>
                    <span class="ha-dashboard-widgets__item-icon"><i class="<?php echo $icon; ?>"></i></span>
                    <h3 class="ha-dashboard-widgets__item-title">
                        <label for="ha-widget-<?php echo $widget_key; ?>"><?php echo $title; ?></label>
                    </h3>
                    <div class="ha-dashboard-widgets__item-toggle ha-toggle">
                        <input id="ha-widget-<?php echo $widget_key; ?>" <?php echo $checked; ?> type="checkbox" class="ha-toggle__check" value="widgets[<?php echo $widget_key; ?>]">
                        <b class="ha-toggle__switch"></b>
                        <b class="ha-toggle__track"></b>
                    </div>
                </div>
                <?php
            endforeach;
            ?>
        </form>
        <?php
    }

    public static function render_widgets( $slug ) {

    }

    public static function render_extras( $slug ) {

    }

    public static function render_pro( $slug ) {
        echo '<h2>', $slug ,'</h2>';
    }
}
