<?php
namespace Happy_Addons\Elementor;

use Happy_Addons\Elementor\Extension\Background_Overlay;
use Happy_Addons\Elementor\Extension\Column_Extended;
use Happy_Addons\Elementor\Extension\Happy_Effects;

defined( 'ABSPATH' ) || die();

class Extensions_Manager {

    /**
     * Initialize
     */
    public static function init() {
        include_once HAPPY_ADDONS_DIR_PATH . 'extensions/background-overlay.php';
        include_once HAPPY_ADDONS_DIR_PATH . 'extensions/happy-effects.php';
        include_once HAPPY_ADDONS_DIR_PATH . 'extensions/column-extended.php';

        Happy_Effects::init();
        Column_Extended::init();
        Background_Overlay::init();
    }
}
