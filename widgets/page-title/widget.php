<?php
/**
 * Page_Title widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;

defined( 'ABSPATH' ) || die();

class Page_Title extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Page Title', 'happy-elementor-addons' );
	}

	public function get_custom_help_url() {
		return 'https://happyaddons.com/docs/happy-addons-for-elementor/widgets/page-title/';
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
		return 'hm hm-tb-page-title';
	}

	public function get_keywords() {
		return [ 'page title', 'Title', 'text' ];
	}

	public function get_categories() {
        return [ 'happy_addons_category', 'happy_addons_theme_builder' ];
    }

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->__page_title_content_control();

	}
	protected function __page_title_content_control() {
		$this->start_controls_section(
			'_section_page_tile',
			[
				'label' => __( 'Page Title', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'page_title_tag',
			[
				'label' => __( 'Title HTML Tag', 'happy-elementor-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'h1'  => [
						'title' => __( 'H1', 'happy-elementor-addons' ),
						'icon' => 'eicon-editor-h1'
					],
					'h2'  => [
						'title' => __( 'H2', 'happy-elementor-addons' ),
						'icon' => 'eicon-editor-h2'
					],
					'h3'  => [
						'title' => __( 'H3', 'happy-elementor-addons' ),
						'icon' => 'eicon-editor-h3'
					],
					'h4'  => [
						'title' => __( 'H4', 'happy-elementor-addons' ),
						'icon' => 'eicon-editor-h4'
					],
					'h5'  => [
						'title' => __( 'H5', 'happy-elementor-addons' ),
						'icon' => 'eicon-editor-h5'
					],
					'h6'  => [
						'title' => __( 'H6', 'happy-elementor-addons' ),
						'icon' => 'eicon-editor-h6'
					]
				],
				'default' => 'h2',
				'toggle' => false,
			]
		);
        $this->add_control(
			'size',
			[
				'label' => esc_html__( 'Size', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'happy-elementor-addons' ),
					'small' => esc_html__( 'Small', 'happy-elementor-addons' ),
					'medium' => esc_html__( 'Medium', 'happy-elementor-addons' ),
					'large' => esc_html__( 'Large', 'happy-elementor-addons' ),
					'xl' => esc_html__( 'XL', 'happy-elementor-addons' ),
					'xxl' => esc_html__( 'XXL', 'happy-elementor-addons' ),
				],
			]
		);

        $this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'happy-elementor-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'happy-elementor-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'happy-elementor-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'happy-elementor-addons' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justify', 'happy-elementor-addons' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};',
					'{{WRAPPER}}:not(:has(.elementor-widget-container)) .ha-page-title' => 'text-align: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'enable_link',
			[
				'label' => esc_html__('Enable Link', 'happy-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'happy-elementor-addons'),
				'label_off' => esc_html__('No', 'happy-elementor-addons'),
				'return_value' => 'yes',
				'default' => '',
			]
		);
		$this->add_control(
			'link_type',
			[
				'label' => esc_html__('Link Type', 'happy-elementor-addons'),
				'type' => Controls_Manager::SELECT,
				'default' => 'dynamic',
				'options' => [
					'dynamic' => esc_html__('Dynamic', 'happy-elementor-addons'),
					'custom' => esc_html__('Custom', 'happy-elementor-addons')
				],
				'condition' => [
					'enable_link!' => ''
				]
			]
		);
		$this->add_control(
			'dynamic_link_options',
			[
				'label' => esc_html__( 'Link Options', 'happy-elementor-addons' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => esc_html__( 'Default', 'happy-elementor-addons' ),
				'label_on' => esc_html__( 'Custom', 'happy-elementor-addons' ),
				'return_value' => 'yes',
				'default' => '',
				'condition' => [
					'enable_link!' => '',
					'link_type' => 'dynamic'
				]
			]
		);
		$this->start_popover();
		$this->add_control(
			'dynamic_link_external',
			[
				'label' => esc_html__('Open in new window', 'happy-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'happy-elementor-addons'),
				'label_off' => esc_html__('No', 'happy-elementor-addons'),
				'return_value' => 'yes',
				'default' => '',
			]
		);
		$this->add_control(
			'dynamic_link_nofollow',
			[
				'label' => esc_html__('Add nofollow', 'happy-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'happy-elementor-addons'),
				'label_off' => esc_html__('No', 'happy-elementor-addons'),
				'return_value' => 'yes',
				'default' => '',
			]
		);
		$this->end_popover();
		$this->add_control(
			'custom_link',
			[
				'label' => esc_html__('Custom Link', 'happy-elementor-addons'),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__('https://your-link.com', 'happy-elementor-addons'),
				'options' => ['url', 'is_external', 'nofollow'],
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'enable_link!' => '',
					'link_type' => 'custom'
				]
			]
		);

        $this->end_controls_section();
	}

	/**
	 * Register styles related controls
	 */
	protected function register_style_controls() {
		$this->__page_title_style_controls();
	}


	protected function __page_title_style_controls() {

        $this->start_controls_section(
            '_section_style_page',
            [
                'label' => __( 'Title', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
			'page_title_color',
			[
				'label' => esc_html__( 'Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-page-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'page_title_typography',
				'label' => __( 'Typography', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-page-title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'page_text_shadow',
				'selector' => '{{WRAPPER}} .ha-page-title',
			]
		);


        $this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->add_render_attribute('title', 'class', 'ha-page-title');

		if (!empty($settings['size'])) {
			$this->add_render_attribute('title', 'class', 'elementor-size-' . $settings['size']);
		}
		if (!empty($settings['enable_link'])) {
			$link_type = isset($settings['link_type']) ? $settings['link_type'] : 'dynamic';
			if ($link_type == 'dynamic') {
				$this->add_link_attributes('single_link', [
					'url' => get_the_permalink(),
					'is_external' => ((!empty($settings['dynamic_link_options']))? ($settings['dynamic_link_external'] == 'yes'): false),
					'nofollow' => ((!empty($settings['dynamic_link_options']))? ($settings['dynamic_link_nofollow'] == 'yes'): false),
				]);
			} else if (! empty( $settings['custom_link']['url'] )){
				$this->add_link_attributes('single_link', $settings['custom_link']);
			}
			echo '<a '.$this->get_render_attribute_string( 'single_link' ).'>';
		}
		printf('<%1$s %2$s>%3$s</%1$s>', ha_escape_tags($settings['page_title_tag'], 'H2'), $this->get_render_attribute_string('title'), esc_html(get_the_title()));
		if (!empty($settings['enable_link'])) {
			echo '</a>';
		}
	}
}
