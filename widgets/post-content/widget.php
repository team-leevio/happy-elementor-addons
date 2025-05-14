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

defined( 'ABSPATH' ) || die();

class Post_Content extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Post Content', 'happy-elementor-addons' );
	}

	public function get_custom_help_url() {
		return 'https://happyaddons.com/docs/happy-addons-for-elementor/widgets/post-content/';
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
		return 'hm hm-tb-post-content';
	}

	public function get_keywords() {
		return [ 'content', 'text' ];
	}

	public function get_categories() {
        return [ 'happy_addons_category', 'happy_addons_theme_builder' ];
    }

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->__post_content_control();
	}

	protected function __post_content_control(){
		$this->start_controls_section(
			'_section_post_content',
			[
				'label' => __( 'Post Content', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
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
					'{{WRAPPER}}:not(:has(.elementor-widget-container))' => 'text-align: {{VALUE}};',
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
            '_section_style_content',
            [
                'label' => __( 'Post Content', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_control(
			'ha_ps_title_style',
			[
				'label' => __( 'Title', 'happy-elementor-addons' ),
				'type' => Controls_Manager::HEADING,
				// 'separator' => 'after',
			]
		);

        $this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} h1' => 'color: {{VALUE}};',
					'{{WRAPPER}} h2' => 'color: {{VALUE}};',
					'{{WRAPPER}} h3' => 'color: {{VALUE}};',
					'{{WRAPPER}} h4' => 'color: {{VALUE}};',
					'{{WRAPPER}} h5' => 'color: {{VALUE}};',
					'{{WRAPPER}} h6' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_typography_type',
			[
				'label' => esc_html__( 'Individual Typography?', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'happy-elementor-addons' ),
				'label_off' => esc_html__( 'No', 'happy-elementor-addons' ),
				'return_value' => 'individual',
				'default' => 'global',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Global Typography', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} h1, {{WRAPPER}} h2, {{WRAPPER}} h3, {{WRAPPER}} h4, {{WRAPPER}} h5, {{WRAPPER}} h6',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'condition' => [
					'title_typography_type!' => 'individual'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography_h1',
				'label' => __( 'HTML Tag H1', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} h1',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'condition' => [
					'title_typography_type' => 'individual'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography_h2',
				'label' => __( 'HTML Tag H2', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} h2',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'condition' => [
					'title_typography_type' => 'individual'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography_h3',
				'label' => __( 'HTML Tag H3', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} h3',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'condition' => [
					'title_typography_type' => 'individual'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography_h4',
				'label' => __( 'HTML Tag H4', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} h4',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'condition' => [
					'title_typography_type' => 'individual'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography_h5',
				'label' => __( 'HTML Tag H5', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} h5',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'condition' => [
					'title_typography_type' => 'individual'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography_h6',
				'label' => __( 'HTML Tag H6', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} h6',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'condition' => [
					'title_typography_type' => 'individual'
				]
			]
		);

		$this->add_control(
			'ha_ps_content_style',
			[
				'label' => __( 'Content', 'happy-elementor-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);
		$this->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'label' => __( 'Typography', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} p',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

        $this->end_controls_section();
	}

	protected function render() {
		static $have_posts = [];
        $post = get_post();
		// Exit early if there's no valid post object
		if ( ! $post ) {
			return;
		}
		if ( post_password_required( $post->ID ) ) {
			echo get_the_password_form( $post->ID );
			return;
		}

		if ( isset( $have_posts[ $post->ID ] ) ) { return; }
		$have_posts[ $post->ID ] = true;
		if (ha_elementor()->editor->is_edit_mode()) {
			printf(
				'<div %s>%s</div>',
				'style="margin: 1rem;padding: 1rem 1.25rem;border-left: 5px solid #f5c848;color: #856404;background-color: #fff3cd;"',
				__( 'This content is for design purpose only. It won\'t be shown in the frontend.', 'happy-elementor-addons' )
			);
			echo '<h1>What is Lorem Ipsum?</h1> <p><strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry.</p> <h2>Why do we use it?</h2> <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout</p> <h3>Where does it come from?</h3> <p>Contrary to popular belief, Lorem Ipsum is not simply random text.</p> <p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested.</p> <h4>Where can I get some?</h4> <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable.</p> <h5>How can I got it?</h5> <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable.</p> <h6>How can I do?</h6> <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable.</p>';
		}else {
			echo apply_filters( 'the_content', get_the_content() );
		}
	}
}
