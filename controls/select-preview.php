<?php
namespace Happy_Addons\Elementor\Controls;

use Elementor\Base_Data_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor select preview control.
 *
 * A base control for creating select preview control. Displays a simple select box with preview image.
 * It accepts an array in which the `key` is the option value and the `value` is
 * the option name.
 *
 * @since 1.0.0
 */
class Select_Preview extends Base_Data_Control {

	/**
	 * Get select control type.
	 *
	 * Retrieve the control type, in this case `select`.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'select_preview';
	}

	/**
	 * Get select control default settings.
	 *
	 * Retrieve the default settings of the select control. Used to return the
	 * default settings while initializing the select control.
	 *
	 * @since 2.0.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'options' => [],
		];
	}

	/**
	 * Render select control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<# if ( data.label ) { console.log(data) #>
				<label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<# } #>
			<div class="elementor-control-input-wrapper">
				<select id="<?php echo $control_uid; ?>" data-setting="{{ data.name }}">
				<# _.each( data.options, function( option, key ) { #>
                    <option value="{{ key }}">{{{ option.title }}}</option>
                <# } ); #>
				</select>
			</div>
		</div>
        <div class="elementor-control-preview-wrapper elementor-aspect-ratio-43">
            <# var previewSrc = ( data.options[data.controlValue] && data.options[data.controlValue].src ) ? data.options[data.controlValue].src : ''; #>
            <div class="elementor-control-preview elementor-fit-aspect-ratio" style="background-image: url({{ previewSrc }})"></div>
        </div>
		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}

    public function enqueue() {
        wp_enqueue_script(
            'ha-elementor-controls',
            HA_ASSETS . 'backend/controls.js'
        );

        wp_enqueue_style(
            'ha-elementor-controls',
            HA_ASSETS . 'backend/controls.css'
        );
    }
}
