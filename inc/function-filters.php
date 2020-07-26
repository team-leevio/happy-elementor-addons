<?php
/**
 * Filters function defination
 *
 * @package Happy_Addons
 * @since 2.13.0
 * @author HappyMonster
 *
 */
defined( 'ABSPATH' ) || die();

/**
 * Check if Adminbar is enabled
 *
 * @return bool
 */
function ha_is_adminbar_enabled() {
	return apply_filters( 'happyaddons/extensions/adminbar', true );
}

/**
 * Check if Background Overlay is enabled
 *
 * @return bool
 */
function ha_is_background_overlay_enabled() {
	return apply_filters( 'happyaddons/extensions/background_overlay', true );
}

/**
 * Check if CSS Transform is enabled
 *
 * @return bool
 */
function ha_is_css_transform_enabled() {
	return apply_filters( 'happyaddons/extensions/css_transform', true );
}

/**
 * Check if Floating Effects is enabled
 *
 * @return bool
 */
function ha_is_floating_effects_enabled() {
	return apply_filters( 'happyaddons/extensions/floating_effects', true );
}

/**
 * Check if Grid Layer is enabled
 *
 * @return bool
 */
function ha_is_grid_layer_enabled() {
	return apply_filters( 'happyaddons/extensions/grid_layer', true );
}

/**
 * Check if Wrapper Link is enabled
 *
 * @return bool
 */
function ha_is_wrapper_link_enabled() {
	return apply_filters( 'happyaddons/extensions/wrapper_link', true );
}

/**
 * Check if Happy Clone is enabled
 *
 * @return bool
 */
function ha_is_happy_clone_enabled() {
	return apply_filters( 'happyaddons/extensions/happy_clone', true );
}

/**
 * Check if On Demand Cache is enabled
 *
 * @return bool
 */
function ha_is_on_demand_cache_enabled() {
	return apply_filters( 'happyaddons/extensions/on_demand_cache', true );
}
