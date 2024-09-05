<?php

namespace Happy_Addons\Elementor\Extension;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;

defined('ABSPATH') || die();

class Reading_Progress_Bar {

    private static $instance = null;

    public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		 return self::$instance;
	}

    public function init() {
        add_action('elementor/element/common/_section_style/after_section_end', [$this, 'add_controls_section'], 1);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_scripts']);
        if ( !ha_elementor()->preview->is_preview_mode() ) {
            add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts_frontend']);
        } 
        add_action( 'wp_footer', [$this, 'render_reading_progress_bar_html'] );
    }


    public function enqueue_scripts () {
        $suffix = ha_is_script_debug_enabled() ? '.' : '.min.';
        $extension_js = HAPPY_ADDONS_DIR_PATH . 'assets/js/extension-reading-progress-bar' . $suffix . 'js';

        if (file_exists($extension_js)) {
            wp_add_inline_script(
                'elementor-frontend',
                file_get_contents($extension_js)
            );
        }
        
    }

    public function enqueue_scripts_frontend () {
        

        $suffix = ha_is_script_debug_enabled() ? '.' : '.min.';
        $extension_js = HAPPY_ADDONS_ASSETS . 'js/extension-reading-progress-bar' . $suffix . 'js';
        
        wp_enqueue_script(
            'happy-reading-progress-bar',
            $extension_js,
            ['jquery'],
            HAPPY_ADDONS_VERSION,
            true
        );
        
    }

    public function add_controls_section ($element) {
        $element->start_controls_section(
            '_section_ha_reading_progres_bar',
            [
                'label' => __('Happy Reading Progress Bar', 'happy-elementor-addons') . ha_get_section_icon(),
                'tab'   => Controls_Manager::TAB_ADVANCED,
            ]
        );

        $element->add_control(
            'ha_reading_progres_bar_enable',
            [
                'label'       => __('Enable Reading Progress Bar?', 'happy-elementor-addons'),
                'type'        => Controls_Manager::SWITCHER,
                'label_on' => __('On', 'happy-elementor-addons'),
                'label_off' => __('Off', 'happy-elementor-addons'),
                'return_value' => 'enable',
                'prefix_class' => 'ha-reading-progress-bar-',
                'default' => '',
                'frontend_available' => true,
            ]
        );
        $element->end_controls_tabs();

        $element->end_controls_section();
    }

    public function render_reading_progress_bar_html () {
        ?>

        <div class="hm-reading-progress-bar-container">
            <div class="hm-readin-progress-bar">Happy Reading Progress Bar..</div>
        </div>

    <?php
    }

}

Reading_Progress_Bar::instance()->init();