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

if ( ! function_exists( 'ha_get_wpforms' ) ) {
    /**
     * Get a list of all WPForms
     *
     * @return array
     */
    function ha_get_wpforms() {
        $forms = get_posts( [
            'post_type'      => 'wpforms',
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

if ( ! function_exists( 'ha_get_ninjaform' ) ) {
    /**
     * Get a list of all Ninja Form
     *
     * @return array
     */
    function ha_get_ninjaform() {
        $options = array();

        if ( class_exists( 'Ninja_Forms' ) ) {
            $contact_forms = Ninja_Forms()->form()->get_forms();

            if ( !empty($contact_forms) && !is_wp_error( $contact_forms ) ) {

                $options[0] = esc_html__( 'Select Ninja Form', 'happy-elementor-addons' );

                foreach ( $contact_forms as $form ) {
                    $options[$form->get_id()] = $form->get_setting('title');
                }
            }
        } else {
            $options[0] = esc_html__( 'Create a Form First', 'happy-elementor-addons' );
        }

        return $options;
    }
}

if ( ! function_exists( 'ha_get_caldera_form' ) ) {
	/**
	 * Get a list of all Caldera Form
	 *
	 * @return array
	 */
	function ha_get_caldera_form() {
		$options = array();

		if ( class_exists( 'Caldera_Forms' ) ) {
			$contact_forms = \Caldera_Forms_Forms::get_forms(true, true);

			if ( !empty( $contact_forms ) && !is_wp_error( $contact_forms ) ) {
				$options[0] = esc_html__( 'Select a Caldera Form', 'happy-elementor-addons' );
				foreach ( $contact_forms as $form ) {
					$options[$form['ID']] = $form['name'];
				}
			}
		} else {
			$options[0] = esc_html__( 'Create a Form First', 'happy-elementor-addons' );
		}

		return $options;
	}
}

if ( ! function_exists( 'ha_get_we_form' ) ) {
	/**
	 * Get a list of all WeForm
	 *
	 * @return array
	 */
	function ha_get_we_forms() {
        $forms = get_posts( [
            'post_type'      => 'wpuf_contact_form',
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

if ( ! function_exists( 'ha_get_modula_gallery' ) ) {
	/**
	 * Get a list of all Modula Gallery
	 *
	 * @return array
	 */
	function ha_get_modula_gallery() {
		$gallery = get_posts( [
            'post_type'      => 'modula-gallery',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ] );

        if ( ! empty( $gallery ) ) {
            return wp_list_pluck( $gallery, 'post_title', 'ID' );
        } else {
			__( 'Create a Gallery', 'happy-elementor-addons' );
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

if ( ! function_exists( 'ha_is_wpf_activated' ) ) {
    /**
     * Check if WPForms is activated
     *
     * @return bool
     */
    function ha_is_wpf_activated() {
        return class_exists( 'WPForms_Lite' );
    }
}

if ( ! function_exists( 'ha_is_ninjaf_activated' ) ) {
    /**
     * Check if Ninja Form is activated
     *
     * @return bool
     */
    function ha_is_ninjaf_activated() {
        return class_exists( 'Ninja_Forms' );
    }
}

if ( ! function_exists( 'ha_is_calderaf_activated' ) ) {
    /**
     * Check if Caldera Form is activated
     *
     * @return bool
     */
    function ha_is_calderaf_activated() {
        return class_exists( 'Caldera_Forms' );
    }
}

if ( ! function_exists( 'ha_is_weform_activated' ) ) {
    /**
     * Check if We Form is activated
     *
     * @return bool
     */
    function ha_is_weform_activated() {
        return class_exists( 'WeForms' );
    }
}

if ( ! function_exists( 'ha_is_script_debug_enabled' ) ) {
    function ha_is_script_debug_enabled() {
        return ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG );
    }
}

function ha_prepare_data_prop_settings( &$settings, $field_map = [] ) {
    $data = [];
    foreach ( $field_map as $key => $data_key ) {
        $setting_value = ha_get_setting_value( $settings, $key );
        list( $data_field_key, $data_field_type ) = explode( '.', $data_key );
        $validator = $data_field_type . 'val';

        if ( is_callable( $validator ) ) {
            $val = call_user_func( $validator, $setting_value );
        } else {
            $val = $setting_value;
        }
        $data[ $data_field_key ] = $val;
    }
    return wp_json_encode( $data );
}

function ha_get_setting_value( &$settings, $keys ) {
    if ( ! is_array( $keys ) ) {
        $keys = explode( '.', $keys );
    }
    if ( is_array( $settings[ $keys[0] ] ) ) {
        return ha_get_setting_value( $settings[ $keys[0] ], array_slice( $keys, 1 ) );
    }
    return $settings[ $keys[0] ];
}

function ha_is_localhost() {
    return isset( $_SERVER['REMOTE_ADDR'] ) && in_array( $_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'] );
}

function ha_get_css_cursors() {
    return [
        'default' => __( 'Default', 'happy-elementor-addons' ),
        'alias' => __( 'Alias', 'happy-elementor-addons' ),
        'all-scroll' => __( 'All scroll', 'happy-elementor-addons' ),
        'auto' => __( 'Auto', 'happy-elementor-addons' ),
        'cell' => __( 'Cell', 'happy-elementor-addons' ),
        'context-menu' => __( 'Context menu', 'happy-elementor-addons' ),
        'col-resize' => __( 'Col-resize', 'happy-elementor-addons' ),
        'copy' => __( 'Copy', 'happy-elementor-addons' ),
        'crosshair' => __( 'Crosshair', 'happy-elementor-addons' ),
        'e-resize' => __( 'E-resize', 'happy-elementor-addons' ),
        'ew-resize' => __( 'EW-resize', 'happy-elementor-addons' ),
        'grab' => __( 'Grab', 'happy-elementor-addons' ),
        'grabbing' => __( 'Grabbing', 'happy-elementor-addons' ),
        'help' => __( 'Help', 'happy-elementor-addons' ),
        'move' => __( 'Move', 'happy-elementor-addons' ),
        'n-resize' => __( 'N-resize', 'happy-elementor-addons' ),
        'ne-resize' => __( 'NE-resize', 'happy-elementor-addons' ),
        'nesw-resize' => __( 'NESW-resize', 'happy-elementor-addons' ),
        'ns-resize' => __( 'NS-resize', 'happy-elementor-addons' ),
        'nw-resize' => __( 'NW-resize', 'happy-elementor-addons' ),
        'nwse-resize' => __( 'NWSE-resize', 'happy-elementor-addons' ),
        'no-drop' => __( 'No-drop', 'happy-elementor-addons' ),
        'not-allowed' => __( 'Not-allowed', 'happy-elementor-addons' ),
        'pointer' => __( 'Pointer', 'happy-elementor-addons' ),
        'progress' => __( 'Progress', 'happy-elementor-addons' ),
        'row-resize' => __( 'Row-resize', 'happy-elementor-addons' ),
        's-resize' => __( 'S-resize', 'happy-elementor-addons' ),
        'se-resize' => __( 'SE-resize', 'happy-elementor-addons' ),
        'sw-resize' => __( 'SW-resize', 'happy-elementor-addons' ),
        'text' => __( 'Text', 'happy-elementor-addons' ),
        'url' => __( 'URL', 'happy-elementor-addons' ),
        'w-resize' => __( 'W-resize', 'happy-elementor-addons' ),
        'wait' => __( 'Wait', 'happy-elementor-addons' ),
        'zoom-in' => __( 'Zoom-in', 'happy-elementor-addons' ),
        'zoom-out' => __( 'Zoom-out', 'happy-elementor-addons' ),
        'none' => __( 'None', 'happy-elementor-addons' ),
    ];
}

function ha_get_css_blend_modes() {
    return [
        'normal' => __( 'Normal', 'happy-elementor-addons' ),
        'multiply' => __( 'Multiply', 'happy-elementor-addons' ),
        'screen' => __( 'Screen', 'happy-elementor-addons' ),
        'overlay' => __( 'Overlay', 'happy-elementor-addons' ),
        'darken' => __( 'Darken', 'happy-elementor-addons' ),
        'lighten' => __( 'Lighten', 'happy-elementor-addons' ),
        'color-dodge' => __( 'Color Dodge', 'happy-elementor-addons' ),
        'color-burn' => __( 'Color Burn', 'happy-elementor-addons' ),
        'saturation' => __( 'Saturation', 'happy-elementor-addons' ),
        'difference' => __( 'Difference', 'happy-elementor-addons' ),
        'exclusion' => __( 'Exclusion', 'happy-elementor-addons' ),
        'hue' => __( 'Hue', 'happy-elementor-addons' ),
        'color' => __( 'Color', 'happy-elementor-addons' ),
        'luminosity' => __( 'Luminosity', 'happy-elementor-addons' ),
    ];
}

function ha_should_load_complied_assets() {
    return (
        apply_filters( 'happyaddons_ondemand_asset_compiling', true ) &&
        ! \Elementor\Plugin::$instance->editor->is_edit_mode() &&
        ! \Elementor\Plugin::$instance->preview->is_preview_mode() &&
        ! is_preview()
    );
}

function ha_should_load_used_library_only() {
    return (
        ! \Elementor\Plugin::$instance->editor->is_edit_mode() &&
        ! \Elementor\Plugin::$instance->preview->is_preview_mode() &&
        ! is_preview()
    );
}
