<?php
namespace Happy_Addons\Elementor;

use Happy_Addons\Elementor\Extension\Happy_Effects;
use Happy_Addons\Elementor\Extension\Column_Extended;
use Happy_Addons\Elementor\Extension\Happy_Grid;
use Happy_Addons\Elementor\Extension\Widgets_Extended;
use Happy_Addons\Elementor\Extension\Background_Overlay;
use Happy_Addons\Elementor\Extension\Wrapper_Link;

defined( 'ABSPATH' ) || die();

class Extensions_Manager {

    /**
     * Initialize
     */
    public static function init() {
        self::include_extensions();

        Happy_Effects::init();
        Happy_Grid::init();
        Column_Extended::init();
        Background_Overlay::init();
        Widgets_Extended::init();
        Wrapper_Link::init();
    }

    public static function include_extensions() {
        include_once HAPPY_ADDONS_DIR_PATH . 'extensions/happy-effects.php';
        include_once HAPPY_ADDONS_DIR_PATH . 'extensions/happy-grid.php';
        include_once HAPPY_ADDONS_DIR_PATH . 'extensions/column-extended.php';
        include_once HAPPY_ADDONS_DIR_PATH . 'extensions/widgets-extended.php';
        include_once HAPPY_ADDONS_DIR_PATH . 'extensions/background-overlay.php';
        include_once HAPPY_ADDONS_DIR_PATH . 'extensions/wrapper-link.php';
    }
}
