<?php
namespace Happy_Addons\Elementor;

use Happy_Addons\Elementor\Extension\Background_Overlay;
use Happy_Addons\Elementor\Extension\Happy_Effects;

defined( 'ABSPATH' ) || die();

class Extensions_Manager {

    /**
     * Initialize
     */
    public static function init() {
        include HAPPY_ADDONS_DIR_PATH . 'extensions/background-overlay.php';
        include HAPPY_ADDONS_DIR_PATH . 'extensions/happy-effects.php';

        Background_Overlay::init();
        Happy_Effects::init();
    }
}
