<?php
namespace Happy_Addons\Elementor;

defined( 'ABSPATH' ) || die();

class Extensions_Manager {

	/**
	 * Initialize
	 */
	public static function init() {
		include_once HAPPY_ADDONS_DIR_PATH . 'extensions/column-extended.php';
		include_once HAPPY_ADDONS_DIR_PATH . 'extensions/widgets-extended.php';

		if ( ha_is_background_overlay_enabled() ) {
			include_once HAPPY_ADDONS_DIR_PATH . 'extensions/background-overlay.php';
		}

		if ( ha_is_grid_layer_enabled() ) {
			include_once HAPPY_ADDONS_DIR_PATH . 'extensions/grid-layer.php';
		}

		if ( ha_is_wrapper_link_enabled() ) {
			include_once HAPPY_ADDONS_DIR_PATH . 'extensions/wrapper-link.php';
		}

		if ( ha_is_floating_effects_enabled() ) {
			include_once HAPPY_ADDONS_DIR_PATH . 'extensions/floating-effects.php';
		}

		if ( ha_is_css_transform_enabled() ) {
			include_once HAPPY_ADDONS_DIR_PATH . 'extensions/css-transform.php';
		}

		if ( is_user_logged_in() && ha_is_adminbar_menu_enabled() ) {
			include_once HAPPY_ADDONS_DIR_PATH . 'classes/admin-bar.php';
		}

		if ( is_user_logged_in() && ha_is_happy_clone_enabled() ) {
			include_once HAPPY_ADDONS_DIR_PATH . 'classes/clone-handler.php';
		}

		if ( ha_is_equal_height_enabled() ) {
			include_once HAPPY_ADDONS_DIR_PATH . 'extensions/equal-height.php';
		}
	}

}

Extensions_Manager::init();
