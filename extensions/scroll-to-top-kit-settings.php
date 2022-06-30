<?php
namespace Happy_Addons\Elementor\Extension;

use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Core\Responsive\Responsive;
use Elementor\Core\Kits\Documents\Tabs\Tab_Base;

class Scroll_To_Top_Kit_Setings extends Tab_Base {

	public function get_id() {
		return 'hello-settings-header-2';
	}

	public function get_title() {
		return __( 'Scroll to Top', 'happy-elementor-addons' ) . ha_get_section_icon();
	}

	public function get_icon() {
		return 'hm hm-happyaddons';
	}

	public function get_help_url() {
		return '';
	}

	public function get_group() {
		return 'settings';
	}

	protected function register_tab_controls() {
		$this->start_controls_section(
			'hello_header_section_2',
			[
				'tab' => 'hello-settings-header-2',
				'label' => __( 'Scroll to Top', 'happy-elementor-addons' ),
			]
		);

		$this->add_control(
			'site_name_____5',
			[
				'label' => esc_html__( 'BG', 'happy-elementor-addons' ),
				'placeholder' => esc_html__( 'Choose name', 'happy-elementor-addons' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'test_kit_switcher',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => __( 'Test Switcher', 'happy-elementor-addons' ),
				'default' => 'yes',
				'label_on' => __( 'Show', 'happy-elementor-addons' ),
				'label_off' => __( 'Hide', 'happy-elementor-addons' ),
			]
		);

		$this->add_control(
			'color_test',
			[
				'label' => esc_html__( 'color test', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'separator' => 'before',
				// 'selector' => '{{WRAPPER}} .eael-ext-scroll-to-top-wrap.scroll-to-top-hide span.eael-ext-scroll-to-top-button',

				'selectors' => [
					'{{WRAPPER}} .eael-ext-scroll-to-top-wrap.scroll-to-top-hide span.eael-ext-scroll-to-top-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}
}
