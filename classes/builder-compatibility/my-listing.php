<?php
namespace Happy_Addons\Elementor\Classes\Builder_Compatibility;

use Happy_Addons\Elementor\Classes\Theme_Builder;

defined( 'ABSPATH' ) || exit;

/**
 * My_Listing support for the header footer.
 */
class My_Listing {


	/**
	 * Run all the Actions / Filters.
	 */
	function __construct($template_ids) {
		global $ha__template_ids;

		$ha__template_ids = $template_ids;
		include 'my-listing-functions.php';
	}
}
