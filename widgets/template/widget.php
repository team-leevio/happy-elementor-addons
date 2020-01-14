<?php
/**
 * Template widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die();

class Template extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Template', 'happy-elementor-addons' );
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
		return 'hm hm-madel';
	}

	public function get_keywords() {
		return [ 'template', 'header', 'footer', 'section' ];
	}

	/**
	 * Register content related controls
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'_section_templates',
			[
				'label' => __( 'Templates', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'template_id',
			[
				'label' => __( 'Template', 'happy-elementor-addons' ),
				'description' => __( 'Choose a template name here that you already created or available in templates library.', 'happy-elementor-addons' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT2,
                'select2options' => [
                    'placeholder' => __( 'Type a template name', 'happy-elementor-addons' ),
                ]
			]
		);

        $this->end_controls_section();
	}

	/**
	 * Register styles related controls
	 */
	protected function register_style_controls() {}

	protected function render() {

	}
}
