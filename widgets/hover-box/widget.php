<?php
/**
 * Hover Box widget class
 *
 * @package happy_addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Scheme_Typography;

defined( 'ABSPATH' ) || die();

class Hover_Box extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Hover Box', 'happy-elementor-addons' );
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
		return 'hm hm-image';
	}

	public function get_keywords() {
		return [ 'image', 'hover', 'box', 'hover box' ];
	}

	/**
	 * Register content related controls
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'_section_content',
			[
				'label' => __( 'Text', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'happy-elementor-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Happy Addons', 'happy-elementor-addons' ),
				'placeholder' => __( 'Type your title here', 'happy-elementor-addons' ),
                'label_block' => true,
			]
		);

        $this->add_control(
            'sub_title',
            [
                'label' => __( 'Sub Title', 'happy-elementor-addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'WordPress', 'happy-elementor-addons' ),
                'placeholder' => __( 'Type your sub title here', 'happy-elementor-addons' ),
                'label_block' => true,
            ]
        );

		$this->add_control(
			'detail',
			[
				'label' => __( 'Description', 'happy-elementor-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'Best Elementor Addons', 'happy-elementor-addons' ),
				'placeholder' => __( 'Type your description here', 'happy-elementor-addons' ),
                'label_block' => true,
			]
		);

        $this->add_control(
            'link',
            [
                'label' => __( 'Link', 'happy-elementor-addons' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://example.com', 'happy-elementor-addons' ),
                'separator' => 'before',
                'label_block' => true,
            ]
        );

		$this->end_controls_section();

        $this->start_controls_section(
            '_section_common',
            [
                'label' => __( 'Common', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'content_position',
            [
                'label' => __( 'Content Position', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'top' => [
                        'title' => __( 'Top', 'happy-elementor-addons' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'happy-elementor-addons' ),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => __( 'Bottom', 'happy-elementor-addons' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors_dictionary' => [
                    'top' => 'align-items: flex-start',
                    'center' => 'align-items: center',
                    'bottom' => 'align-items: flex-end',
                ],
                'default' => 'bottom',
                'toggle' => false,
                'selectors' => [
                    '{{WRAPPER}} .ha-hover-box-wrapper' => '{{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'sub_title_position',
            [
                'label' => __( 'Sub Title Position', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'top' => [
                        'title' => __( 'Top', 'happy-elementor-addons' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'bottom' => [
                        'title' => __( 'Bottom', 'happy-elementor-addons' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'toggle' => false,
                'default' => 'top',
                'prefix_class' => 'ha-pre--',
                'selectors_dictionary' => [
                    'top' => 'flex-direction: column',
                    'bottom' => 'flex-direction: column-reverse',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-hover-box-content'  => '{{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'content_alignment',
            [
                'label' => __( 'Content Alignment', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ha-hover-box-content'  => 'text-align: {{VALUE}};'
                ],
            ]
        );

        $this->end_controls_section();
	}

	/**
	 * Register styles related controls
	 */
	protected function register_style_controls() {

		$this->start_controls_section(
			'_section_hover_box_style',
			[
				'label' => __( 'Common', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_responsive_control(
            'box_height',
            [
                'label' => __( 'Box Height', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 5,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-hover-box-wrapper' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __( 'Padding', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-hover-box-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'hover_box_border',
                'selector' => '{{WRAPPER}} .ha-hover-box-wrapper',
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => __( 'Border radius', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-hover-box-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'hover_box_shadow',
                'selector' => '{{WRAPPER}} .ha-hover-box-wrapper',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'background_image',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ha-hover-box-wrapper',
            ]
        );

        $this->add_control(
            'background_overlay',
            [
                'label' => __( 'Overlay', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'background_image_background' => 'classic'
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-hover-box-wrapper:before' => 'background-color: {{VALUE}}',
                ],
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_text_style',
			[
				'label' => __( 'Text', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->start_controls_tabs( '_tabs_text' );
        $this->start_controls_tab(
            '_tab_pre_title',
            [
                'label' => __( 'Sub Title', 'happy-elementor-addons' ),
            ]
        );

        $this->add_responsive_control(
            'pre_title_spacing',
            [
                'label' => __( 'Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}}.ha-pre--top .ha-hover-sub-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ha-pre--bottom .ha-hover-sub-title' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pre_title_padding',
            [
                'label' => __( 'Padding', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-hover-sub-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'pre_title_border',
				'selector' => '{{WRAPPER}} .ha-hover-sub-title',
			]
		);

		$this->add_control(
			'pre_title_border_radius',
			[
				'label' => __( 'Border Radius', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ha-hover-sub-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'pre_title_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-hover-sub-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pre_title_background_color',
            [
                'label' => __( 'Background Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-hover-sub-title' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pre_title_typography',
                'label' => __( 'Typography', 'happy-elementor-addons' ),
                'selector' => '{{WRAPPER}} .ha-hover-sub-title',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_title',
            [
                'label' => __( 'Title', 'happy-elementor-addons' ),
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __( 'Bottom Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-hover-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => __( 'Padding', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-hover-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'title_border',
                'selector' => '{{WRAPPER}} .ha-hover-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-hover-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'title_background_color',
            [
                'label' => __( 'Background Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-hover-title' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .ha-hover-title',
                'scheme' => Scheme_Typography::TYPOGRAPHY_2,
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_detail',
            [
                'label' => __( 'Description', 'happy-elementor-addons' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .ha-hover-description',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-hover-description' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'link', 'href', esc_url( $settings['link']['url'] ) );
        if ( ! empty( $settings['link']['is_external'] ) ) {
            $this->add_render_attribute( 'link', 'target', '_blank' );
        }
        if ( ! empty( $settings['link']['nofollow'] ) ) {
            $this->add_render_attribute( 'link', 'rel', 'nofollow' );
        }
		?>

        <?php if( $settings['link']['url'] ): ?>
            <a <?php echo $this->get_render_attribute_string( 'link' ); ?>>
        <?php endif;?>

            <div class="ha-hover-box-wrapper">
                <div class="ha-hover-box-content">

                    <?php if( $settings['sub_title'] ): ?>
                        <div>
                            <p class="ha-hover-sub-title"><?php echo esc_html( $settings['sub_title'] ); ?></p>
                        </div>
                    <?php endif;?>

                    <div>
                        <?php if( $settings['title'] ): ?>
                            <h2 class="ha-hover-title"><?php echo esc_html( $settings['title'] ); ?></h2>
                        <?php endif;?>

                        <?php if( $settings['detail'] ): ?>
                            <p class="ha-hover-description"><?php echo $settings['detail']; ?></p>
                        <?php endif;?>
                    </div>
                </div>
            </div>

        <?php if( $settings['link']['url'] ): ?>
           </a>
        <?php endif; ?>

        <?php
	}
}
