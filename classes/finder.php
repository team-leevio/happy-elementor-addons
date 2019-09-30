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
                'title' => __( 'Home', 'elementor' ),
                'url' => ha_get_dashboard_link(),
                'icon' => ' hm hm-home',
                'keywords' => [ 'happy', 'setting', 'happyaddons', 'dashboard', 'widget', 'control', 'panel' ],
            ],
            'widgets' => [
                'title' => __( 'Widgets Control Panel', 'elementor' ),
                'url' => ha_get_dashboard_link( '#tab-content-widgets'),
                'icon' => ' hm hm-happyaddons',
                'keywords' => [ 'happy', 'setting', 'happyaddons', 'dashboard', 'widget', 'control', 'panel' ],
            ],
        ];

        return $items;
    }
}
