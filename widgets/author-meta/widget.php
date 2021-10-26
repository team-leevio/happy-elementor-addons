<?php
/**
 * Author Meta widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Core\Schemes\Typography;
use Elementor\Utils;
use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;
use Happy_Addons\Elementor\Controls\Group_Control_Foreground;

defined( 'ABSPATH' ) || die();

class Author_Meta extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Author Meta', 'happy-elementor-addons' );
	}

	public function get_custom_help_url() {
		return 'https://happyaddons.com/docs/happy-addons-for-elementor/widgets/post-title/';
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
		return 'hm hm-advanced-heading';
	}

	public function get_keywords() {
		return [ 'author', 'author_meta', 'author info' ];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {

		$this->start_controls_section(
			'_section_author_meta',
			[
				'label' => __( 'Author Meta', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_author',
			[
				'label'        => __( 'Show Author Name', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			]
		);


		$this->add_control(
			'author_meta_tag',
			[
				'label' => __( 'Author Name Tag', 'happy-elementor-addons' ),
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
				'default' => 'h4',
				'toggle' => false,
				'condition'=>[
					'show_author' => 'yes',
				]
			]
		);
        
		$this->add_control(
			'show_avatar',
			[
				'label'        => __( 'Show Avatar', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'show_bio',
			[
				'label'        => __( 'Show Short Bio', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'author_link_to',
			[
				'label' => __( 'Link', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::SELECT,
				'options' => [
					''              => __( 'None', 'happy-elementor-addons' ),
					'website'       => __( 'Website', 'happy-elementor-addons' ),
					'admin_archive' => __( 'Admin Posts', 'happy-elementor-addons' ),
				],
				'description'       => __( 'Link for the Author Name and Image', 'happy-elementor-addons' ),
			]
		);

		$this->add_control(
			'avatar_image_position',
			[
				'label'   => __( 'Avatar Image Position', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'happy-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'happy-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					],
					'top' => [
						'title' => __( 'Top', 'happy-elementor-addons' ),
						'icon'  => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'happy-elementor-addons' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default'     => 'left',
			]
		);

        $this->end_controls_section();
	}

	/**
	 * Register styles related controls
	 */
	protected function register_style_controls() {
		$this->__author_style_controls();
		$this->__avatar_style_controls();
		$this->__author_short_bio_controls();
	}

	
	protected function __author_style_controls() {

        $this->start_controls_section(
            '_section_style_text',
            [
                'label' => __( 'Author Name', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					// 'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-heading-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'global' => [
					// 'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .elementor-heading-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} .elementor-heading-title',
			]
		);
        

        $this->end_controls_section();
	}
	
	protected function __author_short_bio_controls() {

        $this->start_controls_section(
            '_section_style_short_bio',
            [
                'label' => __( 'Short Bio', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
			'bio_color',
			[
				'label' => esc_html__( 'Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					// 'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-heading-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'bio_typography',
				'global' => [
					// 'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .elementor-heading-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'bio_shadow',
				'selector' => '{{WRAPPER}} .elementor-heading-title',
			]
		);
        

        $this->end_controls_section();
	}
		
	protected function __avatar_style_controls() {

        $this->start_controls_section(
            '_section_avatar_style',
            [
                'label' => __( 'Avatar', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_control(
			'avatar_size',
			[
				'label' => __( 'Avatar Size', 'happy-elementor-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 500,
				'step' => 1,
				'default' => 96,
			]
		);

        $this->add_control(
			'avatar_margin',
			[
				'label' => __( 'Avatar Margin', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .your-class' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'avatar_padding',
			[
				'label' => __( 'Avatar Padding', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .your-class' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'avatar_border',
				'label' => __( 'Border', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .wrapper',
			]
		);

		$this->add_control(
			'avatar_radius',
			[
				'label' => __( 'Border Radius', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .your-class' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		
	}
}
