<?php
/**
 * Helper functions
 *
 * @package Happy_Addons
 */
defined( 'ABSPATH' ) || die();

if ( ! function_exists( 'ha_do_shortcode' ) ) {
    /**
     * Call a shortcode function by tag name.
     *
     * @since  1.0.0
     *
     * @param string $tag     The shortcode whose function to call.
     * @param array  $atts    The attributes to pass to the shortcode function. Optional.
     * @param array  $content The shortcode's content. Default is null (none).
     *
     * @return string|bool False on failure, the result of the shortcode on success.
     */
    function ha_do_shortcode( $tag, array $atts = array(), $content = null ) {
        global $shortcode_tags;
        if ( ! isset( $shortcode_tags[ $tag ] ) ) {
            return false;
        }
        return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
    }
}

if ( ! function_exists( 'ha_get_cf7_forms' ) ) {
    /**
     * Get a list of all CF7 forms
     *
     * @return array
     */
    function ha_get_cf7_forms() {
        $forms = get_posts( [
            'post_type'      => 'wpcf7_contact_form',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ] );

        if ( ! empty( $forms ) ) {
            return wp_list_pluck( $forms, 'post_title', 'ID' );
        }
        return [];
    }
}

if ( ! function_exists( 'ha_sanitize_html_class_param' ) ) {
    /**
     * Sanitize html class string
     *
     * @param $class
     * @return string
     */
    function ha_sanitize_html_class_param( $class ) {
        $classes = ! empty( $class ) ? explode( ' ', $class ) : [];
        $sanitized = [];
        if ( ! empty( $classes ) ) {
            $sanitized = array_map( function( $cls ) {
                return sanitize_html_class( $cls );
            }, $classes );
        }
        return implode( ' ', $sanitized );
    }
}

if ( ! function_exists( 'ha_is_cf7_activated' ) ) {
    /**
     * Check if contact form 7 is activated
     *
     * @return bool
     */
    function ha_is_cf7_activated() {
        return class_exists( 'WPCF7' );
    }
}

if ( ! function_exists( 'ha_is_script_debug_enabled' ) ) {
    function ha_is_script_debug_enabled() {
        return ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG );
    }
}

function ha_prepare_data_prop_settings( $settings = [], $field_map = [] ) {
    $data = [];
    foreach ( $field_map as $key => $data_key ) {
        if ( ! isset( $settings[ $key ] ) ) {
            continue;
        }

        list( $data_field_key, $data_field_type ) = explode( '.', $data_key );
        $validator = $data_field_type . 'val';
        if ( is_callable( $validator ) ) {
            $val = call_user_func( $validator, $settings[ $key ] );
        } else {
            $val = $settings[ $key ];
        }
        $data[ $data_field_key ] = $val;
    }
    return wp_json_encode( $data );
}
