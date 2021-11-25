<?php

/**
 * Content Switcher widget class
 *
 * @package Happy_Addons
 */

namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;


defined('ABSPATH') || die();

class Comparison_Table extends Base {
    /**
	 * Get widget title.
	 *
	 * @since 2.24.2
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __('Comparison Table', 'happy-elementor-addons');
	}

    public function get_custom_help_url() {
		// return 'https://happyaddons.com/docs/happy-addons-for-elementor/widgets/';
	}

    /**
	 * Get widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'hm hm-table-lamp';
	}

    /**
	 * Get widget keywords.
	 *
	 * Retrieve the widget keywords.
	 *
	 * @since 1.0.10
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
    public function get_keywords() {
		return ['comparison table', 'table', 'comparison'];
	}

    /**
     * Register widget content controls
     */
	protected function register_content_controls() {
        $this->start_controls_section(
            '_section_contet',
            [
                'label' => __('Content', 'happy-elementor-addons'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register widget style controls
     */
	protected function register_style_controls() {

    }

    protected function render() {
        
    }
}


