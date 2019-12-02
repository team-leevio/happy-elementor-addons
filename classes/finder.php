<?php
/**
 * Finder class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor;

use Elementor\Core\Common\Modules\Finder\Base_Category as Finder_Category;

defined( 'ABSPATH' ) || die();

class Finder extends Finder_Category {

    const SLUG = 'happy-addons';

    /**
     * Get title.
     *
     * @access public
     *
     * @return string
     */
    public function get_title() {
        return __( 'Happy Addons', 'happy-elementor-addons' );
    }

    /**
     * Get category items.
     *
     * @access public
     *
     * @param array $options
     *
     * @return array
     */
    public function get_category_items( array $options = [] ) {
        $items = [
            'home' => [
                'title' => __( 'HappyAddons', 'happy-elementor-addons' ),
                'description' => __( 'Control Panel Home', 'happy-elementor-addons' ),
                'url' => ha_get_dashboard_link(),
                'icon' => ' hm hm-happyaddons',
                'keywords' => [ 'happy', 'setting', 'happyaddons', 'dashboard', 'widget', 'control', 'panel', 'essential' ],
                'actions' => [
                    [
                        'name' => 'view',
                        'url' => ha_get_dashboard_link(),
                        'icon' => 'eye'
                    ]
                ]
            ],
            'widgets' => [
                'title' => __( 'HappyAddons', 'happy-elementor-addons' ),
                'description' => __( 'Widgets Control Panel', 'happy-elementor-addons' ),
                'icon' => ' hm hm-cross-game',
                'url' => ha_get_dashboard_link( '#widgets' ),
                'keywords' => [ 'happy', 'setting', 'happyaddons', 'dashboard', 'widget', 'control', 'panel', 'essential' ],
                'actions' => [
                    [
                        'name' => 'view',
                        'url' => ha_get_dashboard_link( '#widgets' ),
                        'icon' => 'eye'
                    ]
                ]
            ],
        ];

        return $items;
    }
}
