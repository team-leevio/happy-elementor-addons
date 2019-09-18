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
            ha_get_b64_icon(),
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
            <h1 class="screen-reader-text"><?php esc_html_e( 'Happy Elementor Addons', 'happy-elementor-addons' ); ?></h1>
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
                            printf( '<a href="#tab-content-%1$s" aria-controls="tab-content-%1$s" id="tab-nav-%1$s" class="%2$s" role="tab">%3$s</a>',
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
                    $checked = 'disabled="disabled"';
                }
                ?>
                <div class="<?php echo $class_attr; ?>">
                    <?php if ( $is_pro ) : ?>
                        <span class="ha-dashboard-widgets__item-badge"><?php esc_html_e( 'Pro', 'happy-elementor-addons' ); ?></span>
                    <?php endif; ?>
                    <span class="ha-dashboard-widgets__item-icon"><i class="<?php echo $icon; ?>"></i></span>
                    <h3 class="ha-dashboard-widgets__item-title">
                        <label for="ha-widget-<?php echo $widget_key; ?>"><?php echo $title; ?></label> <a href="#" class="ha-dashboard-widgets__item-preview"><i class="eicon-device-desktop"></i></a>
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
