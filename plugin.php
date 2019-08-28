<?php
/**
 * Plugin Name: Happy Elementor Addons
 * Plugin URI: https://happyaddons.com/
 * Description: <a href="https://happyaddons.com/">HappyAddons</a> is a collection of slick, powerful widgets that works seamlessly with Elementor page builder. It’s trendy look with detail customization features allows to create extraordinary designs instantly. <a href="https://happyaddons.com/">HappyAddons</a> is free, rapidly growing and comes with great support.
 * Version: 1.5.0
 * Author: HappyMonster
 * Author URI: https://happyaddons.com/
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: happy-elementor-addons
 * Domain Path: /languages/
 *
 * @package Happy_Addons
 */

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2019 HappyMonster <http://happymonster.me>
*/

defined( 'ABSPATH' ) || die();

define( 'HAPPY_ADDONS_VERSION', '1.5.0' );
define( 'HAPPY_ADDONS__FILE__', __FILE__ );
define( 'HAPPY_ADDONS_DIR_PATH', plugin_dir_path( HAPPY_ADDONS__FILE__ ) );
define( 'HAPPY_ADDONS_DIR_URL', plugin_dir_url( HAPPY_ADDONS__FILE__ ) );
define( 'HAPPY_ADDONS_ASSETS', trailingslashit( HAPPY_ADDONS_DIR_URL . 'assets' ) );

require HAPPY_ADDONS_DIR_PATH . 'base.php';

\Happy_Addons\Elementor\Base::instance();
