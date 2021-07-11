<?php

namespace Happy_Addons\Elementor\Extension;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

defined('ABSPATH') || die();

class Advanced_Tooltip {

    public static function init() {
        add_action('elementor/element/common/_section_style/after_section_end', [__CLASS__, 'add_controls_section'], 1);

        add_action('elementor/widget/print_template', [__CLASS__, '_print_template'], 10, 2);
        add_action('elementor/widget/before_render_content', [__CLASS__, 'before_section_render'], 10, 1);
        add_action('elementor/widget/before_render_content', [__CLASS__, 'inject_markup_with_script']);
    }

    public function get_name() {
        return 'ha-tooltip-section';
    }

    // public static function add_controls_section( Element_Base $element) {
    public static function add_controls_section($element) {

        $element->start_controls_section(
            '_section_ha_advanced_tooltip',
            [
                'label' => __('Advanced Tooltip', 'happy-elementor-addons') . ha_get_section_icon(),
                'tab'   => Controls_Manager::TAB_ADVANCED,
            ]
        );

        $element->add_control(
            'ha_advanced_tooltip_enable',
            [
                'label'       => __('Enable Advanced Tooltip?', 'happy-elementor-addons'),
                'type'        => Controls_Manager::SWITCHER,
                'label_on' => __('On', 'happy-elementor-addons'),
                'label_off' => __('Off', 'happy-elementor-addons'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $element->end_controls_section();
    }

    // public static function before_section_render( Element_Base $element ) {
    public static function before_section_render($element) {
        // $advanced_tooltip = $element->get_settings_for_display( 'ha_advanced_tooltip_enable' );
        $advanced_tooltip = $element->get_settings('ha_advanced_tooltip_enable');

        if ($advanced_tooltip == 'yes') {

            $element->add_render_attribute(
                '_wrapper',
                [
                    'id' => 'ha-advanced-tooltip-' . $element->get_id(),
                    'class' => 'ha-advanced-tooltip'
                ]
            );

            wp_enqueue_style('tipso');
            wp_enqueue_script('jquery-tipso');
        }
    }

    /**
     * Render Particles Background output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @access public
     * @param object $template for current template.
     * @param object $widget for current widget.
     */
    public static function _print_template($template, $widget) { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        $old_template = $template;
        ob_start(); ?>

        <# if( 'yes'==settings.ha_advanced_tooltip_enable ) { view.addRenderAttribute( 'tooltip_data' , 'id' , 'ha-advanced-tooltip-' + view.getID() ); view.addRenderAttribute( 'tooltip_data' , 'class' , 'ha-advanced-tooltip' ); #>
            <div {{{ view.getRenderAttributeString( 'tooltip_data' ) }}}></div>
            <# } #>
            
        <?php
        $slider_content = ob_get_contents();
        ob_end_clean();
        $template = $slider_content . $old_template;
        return $template;
    }

    public static function inject_markup_with_script($element) {
        $settings = $element->get_settings_for_display();
        $data = $element->get_data();

        if ($settings['ha_advanced_tooltip_enable'] == 'yes') {
        ?>
            <script>
                jQuery(window).on('elementor/frontend/init', function() {
                    var $currentTooltip = '#ha-advanced-tooltip-<?php echo $element->get_id(); ?>';

                    jQuery($currentTooltip).tipso({
                        content: 'This is tooltip',
                        speed: 400,
                        // size: 'tiny',
                        // size: 'small',
                        size: 'default',
                        // size: 'large',
                        background: '#000000',
                        color: '#ffffff',
                        showArrow: true,
                        position: 'top',
                        // position: 'bottom',
                        // position: 'left',
                        // position: 'right',
                        width: 100,
                        maxWidth: '',
                        delay: 200,
                        hideDelay: 200,
                        animationIn: '',
                        animationOut: '',
                        offsetX: 0,
                        offsetY: 0,
                        tooltipHover: false,
                        ajaxContentUrl: null,
                        ajaxContentBuffer: 0,
                        contentElementId: null,
                        templateEngineFunc: null,
                        onBeforeShow: null,
                        onShow: null,
                        onHide: null
                    });

                });
            </script>
        <?php
        }
    }
}

Advanced_Tooltip::init();
