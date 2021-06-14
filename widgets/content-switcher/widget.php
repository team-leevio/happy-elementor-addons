<?php

/**
 * Contact form 7 widget class
 *
 * @package Happy_Addons
 */

namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;

defined('ABSPATH') || die();

class Content_Switcher extends Base {

    /**
     * Get widget title.
     *
     * @since 2.24.2
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Content Switcher', 'happy-elementor-addons');
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
        return 'hm hm-switcher';
    }

    public function get_keywords() {
        return ['content', 'switcher', 'toggle'];
    }

    protected function register_content_controls() {

        $this->start_controls_section(
            '_section_preset',
            [
                'label' => __('Preset', 'happy-elementor-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
			'select_preset',
			[
				'label'   => __( 'Choose Preset', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'ha-cs-round' => __( 'Round', 'happy-elementor-addons' ),
					'ha-cs-round-2'   => __( 'Round 2', 'happy-elementor-addons' ),
					'ha-cs-square' => __( 'Square', 'happy-elementor-addons' ),
					'ha-cs-button'    => __( 'Button', 'happy-elementor-addons' ),
				],
				'default' => 'round',
			]
		);

        $this->end_controls_section();
    }

    protected function register_style_controls() {

        $this->start_controls_section(
            '_section_style_switch',
            [
                'label' => __('Switch', 'happy-elementor-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
?>
        <label class="ha-cs-switch">
            <input type="checkbox">
            <span class="ha-cs-slider ha-cs-square"></span>
        </label>

        <label class="ha-cs-switch">
            <input type="checkbox">
            <span class="ha-cs-slider ha-cs-round"></span>
        </label>

        <label class="ha-cs-switch">
            <input type="checkbox">
            <span class="ha-cs-slider ha-cs-round-2"></span>
        </label>
<?php
    }
}
