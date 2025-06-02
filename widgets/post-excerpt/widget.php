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

class Post_Excerpt extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Post Excerpt', 'happy-elementor-addons' );
	}

	public function get_custom_help_url() {
		return 'https://happyaddons.com/docs/happy-addons-for-elementor/widgets/post-excerpt/';
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
		return 'hm hm-tb-post-excerpt';
	}

	public function get_keywords() {
		return [ 'excerpt', 'text' ];
	}

	public function get_categories() {
        return [ 'happy_addons_category', 'happy_addons_theme_builder' ];
    }

	/**
     * Register widget excerpt controls
     */
	protected function register_content_controls() {
		$this->__post_excerpt_controls();
	}

	protected function __post_excerpt_controls() {

		$this->start_controls_section(
			'_section_post_excerpt',
			[
				'label' => __( 'Post Excerpt', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'type'        => Controls_Manager::NUMBER,
				'label'       => __('Excerpt Length', 'happy-elementor-addons'),
				'description' => __('Leave it blank to hide it.', 'happy-elementor-addons'),
				'min'         => 0,
				'default'     => 15
			]
		);

		$this->add_control(
			'read_more',
			[
				'type' => Controls_Manager::TEXT,
				'label' => __('Read More', 'happy-elementor-addons'),
				'placeholder' => __('Read More Text', 'happy-elementor-addons'),
				'description' => __('Leave it blank to hide it.', 'happy-elementor-addons'),
				'default' => __('Continue Reading »', 'happy-elementor-addons'),
			]
		);

		$this->add_control(
			'read_more_new_tab',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => __('Open in new window', 'happy-elementor-addons'),
				'return_value' => 'yes',
				'default' => 'yes',
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
		$this->__excerpt_style_controls();
		$this->__readmore_style_controls();
	}


	protected function __excerpt_style_controls() {

        $this->start_controls_section(
            '_section_style_excerpt',
            [
                'label' => __( 'Excerpt Style', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
			'excerpt_color',
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
				'name' => 'excerpt_typography',
				'label' => __( 'Typography', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} p',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

        $this->end_controls_section();
	}

	protected function __readmore_style_controls() {
		$this->start_controls_section(
			'_section_readmore_style',
			[
				'label' => __('Read More', 'happy-elementor-addons'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'readmore_margin',
			[
				'label' => __('Margin', 'happy-elementor-addons'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .ha-pe-readmore' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'readmore_padding',
			[
				'label' => __('Padding', 'happy-elementor-addons'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .ha-pe-readmore a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'readmore_typography',
				'label' => __('Typography', 'happy-elementor-addons'),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector' => '{{WRAPPER}} .ha-pe-readmore a',
			]
		);

		$this->start_controls_tabs('readmore_tabs');
		$this->start_controls_tab(
			'readmore_normal_tab',
			[
				'label' => __('Normal', 'happy-elementor-addons'),
			]
		);

		$this->add_control(
			'readmore_color',
			[
				'label' => __('Text Color', 'happy-elementor-addons'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-pe-readmore a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'readmore_hover_tab',
			[
				'label' => __('Hover', 'happy-elementor-addons'),
			]
		);

		$this->add_control(
			'readmore_hover_color',
			[
				'label' => __('Text Color', 'happy-elementor-addons'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-pe-readmore a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
        $post = get_post();
		// Exit early if there's no valid post object
		if ( ! $post ) {
			return;
		}
		$settings = $this->get_settings_for_display();

		$length = $settings['excerpt_length'];
		$read_more_text = $settings['read_more'];
		$new_tab = $settings['read_more_new_tab'];

		if ( post_password_required( $post->ID ) ) {
			echo get_the_password_form( $post->ID );
			return;
		}

		if (ha_elementor()->editor->is_edit_mode()) {
			printf(
				'<div %s>%s</div>',
				'style="margin: 1rem;padding: 1rem 1.25rem;border-left: 5px solid #f5c848;color: #856404;background-color: #fff3cd;"',
				__( 'This content is for design purpose only. It won\'t be shown in the frontend.', 'happy-elementor-addons' )
				);
			echo '<p>'.wp_trim_words('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', $length).'</p>';
			if ( $read_more_text ) {
				printf(
					'<div class="%1$s"><a href="%2$s" target="%3$s">%4$s</a></div>',
					'ha-pe-readmore',
					esc_url( get_the_permalink( get_the_ID() ) ),
					'yes' === $new_tab ? '_blank' : '_self',
					esc_html( $read_more_text )
				);
			}
		}
		else {
			echo apply_filters( 'the_excerpt', wp_trim_words(get_the_excerpt(), $length) );
			if ( $read_more_text ) {
				printf(
					'<div class="%1$s"><a href="%2$s" target="%3$s">%4$s</a></div>',
					'ha-pe-readmore',
					esc_url( get_the_permalink( get_the_ID() ) ),
					'yes' === $new_tab ? '_blank' : '_self',
					esc_html( $read_more_text )
				);
			}
		}
	}
}
