<?php

namespace Happy_Addons\Elementor\Extension;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Plugin;

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
		add_action( 'elementor/documents/register_controls', [$this, 'reading_progress_bar_controls'], 10 );
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

	public function reading_progress_bar_controls( $element ) {

		$element->start_controls_section(
			'ha_reading_progress_bar_section',
			[
				'label' => __( 'Reading Progress Bar', 'happy-elementor-addons' ) . ha_get_section_icon(),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);

		$element->add_control(
			'ha_reading_progress_bar_disable',
			[
				'label'        => __( 'Disable Scroll to Top', 'happy-elementor-addons' ),
				'description'  => __( 'Disable Scroll to Top For This Page', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'happy-elementor-addons' ),
				'label_off'    => __( 'No', 'happy-elementor-addons' ),
				'return_value' => 'yes',
			]
		);

		$element->end_controls_section();
	}

	public function render_reading_progress_bar_html() {

		// $document = Plugin::$instance->documents->get($post_id, false);
        // $document->get_settings('eael_ext_reading_progress_global') == 'yes' && $document->get_settings('eael_ext_reading_progress') == 'yes'
        ?>

        <!-- Horizontal -->
        <div id="hm_hrp_bar_wrapper" class="hm-hrp-bar-container ha-reading-progress-bar">
            <div class="hm-hrp-bar"></div>
        </div>

        <!-- Vertical -->
        <div id="hm_vrp_bar_wrapper" class="hm-vrp-bar-container ha-reading-progress-bar">
            <div class="hm-vrp-bar"></div>
        </div>

        <div class="hm-crp-wrapper ha-reading-progress-bar">
            <svg class="hm-circular-progress" width="80" height="80" viewBox="0 0 100 100">
                <circle class="hm-progress-background" cx="50" cy="50" r="45"></circle>
                <circle class="hm-progress-circle" cx="50" cy="50" r="45"></circle>
            </svg>
            <div class="hm-progress-percent-text">0%</div>
        </div>

    <?php

	}


}

Reading_Progress_Bar::instance()->init();