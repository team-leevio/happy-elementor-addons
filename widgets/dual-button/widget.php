<?php
/**
 * Dual Button widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || die();

class Dual_button extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Dual Button', 'happy_addons' );
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
        return 'hm hm-spark';
    }

    public function get_keywords() {
        return [ 'Button', 'btn', 'dual' ];
    }

	protected function register_content_controls() {
        $this->start_controls_section(
            'left_button',
            [
                'label' => __( 'Left Button', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

		$this->add_control(
            'left_button_text',
            [
				'label' => __( 'Button Text', 'happy_addons' ),
				'label_block'=> true,
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Click Here', 'happy_addons' )
            ]
        );

		$this->add_control(
            'left_button_link',
            [
                'label' => __( 'Button Link', 'happy_addons' ),
				'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://example.com/', 'happy_addons' ),
            ]
		);

		$this->add_control(
            'left_icon_picker',
            [
                'label' => __( 'Icon Picker', 'happy_addons' ),
				'type' => Controls_Manager::ICON,
				'default' => 'fa fa-download'
            ]
		);

		$this->add_responsive_control(
            'left_icon_align',
            [
                'label' => __( 'Icon Alignment', 'happy_addons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'happy_addons' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'happy_addons' ),
                        'icon' => 'fa fa-align-right',
                    ]
                ],
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn-link:first-child a i' => 'float: {{VALUE}}'
                ]
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
            'button_derection',
            [
                'label' => __( 'Button Direction', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
		);

		$this->add_control(
            'button_direction_show',
            [
				'label' => __( 'Button Direction Show?', 'happy_addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'happy_addons' ),
				'label_off' => __( 'Hide', 'happy_addons' ),
                'default' => 'yes'
            ]
        );

		$this->add_control(
            'button_direction_text',
            [
				'label' => __( 'Direction Text', 'happy_addons' ),
				'label_block'=> true,
                'type' => Controls_Manager::TEXT,
				'default' => __( 'Or', 'happy_addons' ),
				'condition' => ['button_direction_show'=>'yes']
            ]
		);

		$this->end_controls_section();

		$this->start_controls_section(
            'right_button',
            [
                'label' => __( 'Right Button', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
		);

		$this->add_control(
            'right_button_text',
            [
				'label' => __( 'Button Text', 'happy_addons' ),
				'label_block'=> true,
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Click Here', 'happy_addons' )
            ]
        );

		$this->add_control(
            'right_button_link',
            [
                'label' => __( 'Button Link', 'happy_addons' ),
				'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://example.com/', 'happy_addons' ),
            ]
		);

		$this->add_control(
            'right_icon_picker',
            [
                'label' => __( 'Icon Picker', 'happy_addons' ),
				'type' => Controls_Manager::ICON,
				'default' => 'fa fa-refresh'
            ]
		);

		$this->add_responsive_control(
            'right_icon_align',
            [
                'label' => __( 'Icon Alignment', 'happy_addons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'happy_addons' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'happy_addons' ),
                        'icon' => 'fa fa-align-right',
                    ]
                ],
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn-link:last-child a i' => 'float: {{VALUE}}'
                ]
            ]
        );

        $this->end_controls_section();

    }

    protected function register_style_controls() {
        $this->start_controls_section(
            '_section_style_dual_button',
            [
                'label' => __( 'General Settings', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
		);

		$this->add_responsive_control(
            'button_spacing',
            [
                'label' => __( 'Spacing', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn-link a' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
		);

		$this->add_responsive_control(
            'button_margin',
            [
                'label' => __( 'Margin', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn' => 'margin: {{SIZE}}{{UNIT}};',
                ],
            ]
		);

		$this->add_responsive_control(
            'button_separator_margin',
            [
                'label' => __( 'Separator Margin', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn-link:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => __( 'Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-dual-btn-link a',
            ]
		);

		$this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .ha-dual-btn-link a'
            ]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_left_button',
            [
                'label' => __( 'Left Button', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
		);

		$this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .ha-dual-btn-link:first-child a'
            ]
		);

        $this->add_responsive_control(
            'left_btn_border_radius',
            [
                'label' => __( 'Left Button Border Radius', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn-link:first-child a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
		);

		$this->start_controls_tabs( '_left_button_tabs' );

        $this->start_controls_tab(
            '_left_button_tabs_normal',
            [
                'label' => __( 'Normal', 'happy_addons' ),
            ]
		);

		$this->add_control(
            'left_button_background_color',
            [
                'label' => __( 'Background Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn-link .ha-dual-btn-link-primary' => 'background: {{VALUE}}',
                ],
            ]
        );

		$this->add_control(
            'left_button_text_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn-link .ha-dual-btn-link-primary' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->end_controls_tab();

		$this->start_controls_tab(
            '_left_button_tabs_hover',
            [
                'label' => __( 'Hover', 'happy_addons' ),
            ]
		);

		$this->add_control(
            'left_button_hover_bg_color',
            [
                'label' => __( 'Background Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn-link .ha-dual-btn-link-primary:hover' => 'background: {{VALUE}}',
                ],
            ]
        );

		$this->add_control(
            'left_button_hover_text_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn-link .ha-dual-btn-link-primary:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
            'left_button_icon_spacing',
            [
                'label' => __( 'Icon Spacing', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn-link:first-child a i' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
                ],
            ]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'_section_button_direction',
            [
                'label' => __( 'Button Direction', 'happy_addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => ['button_direction_show'=>'yes']
            ]
		);

		$this->add_control(
            'button_direction_background_color',
            [
                'label' => __( 'Background Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-middle-text' => 'background: {{VALUE}}',
                ],
            ]
        );

		$this->add_control(
            'button_direction_text_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-middle-text' => 'color: {{VALUE}}',
                ],
            ]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_direction_typography',
                'label' => __( 'Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-middle-text',
            ]
		);

		$this->add_responsive_control(
            'direction_spacing',
            [
                'label' => __( 'Spacing', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-middle-text' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
		);

		$this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_direction_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .ha-middle-text'
            ]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'_section_right_button',
            [
                'label' => __( 'Right Button', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
		);

		$this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'right_button_border',
                'selector' => '{{WRAPPER}} .ha-dual-btn-link:last-child a'
            ]
		);

		$this->add_control(
            'right_btn_border_radius',
            [
                'label' => __( 'Border Radius', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn-link:last-child a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
		);

		$this->start_controls_tabs( '_right_button_tabs' );

        $this->start_controls_tab(
            '_right_button_tabs_normal',
            [
                'label' => __( 'Normal', 'happy_addons' ),
            ]
		);

		$this->add_control(
            'right_button_background_color',
            [
                'label' => __( 'Background Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn-link .ha-dual-btn-link-secondary' => 'background: {{VALUE}}',
                ],
            ]
        );

		$this->add_control(
            'right_button_text_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn-link .ha-dual-btn-link-secondary' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->end_controls_tab();

		$this->start_controls_tab(
            '_right_button_tabs_hover',
            [
                'label' => __( 'Hover', 'happy_addons' ),
            ]
		);

		$this->add_control(
            'right_button_hover_bg_color',
            [
                'label' => __( 'Background Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn-link .ha-dual-btn-link-secondary:hover' => 'background: {{VALUE}}',
                ],
            ]
        );

		$this->add_control(
            'right_button_hover_text_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn-link .ha-dual-btn-link-secondary:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
            'right_button_icon_spacing',
            [
                'label' => __( 'Icon Spacing', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn-link:last-child a i' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
                ],
            ]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes( 'left_button_text', 'none' );
		$this->add_inline_editing_attributes( 'right_button_text', 'none' );

		$this->add_inline_editing_attributes( 'button_direction_text', 'none' );
        $this->add_render_attribute( 'button_direction_text', 'class', 'ha-middle-text' );
		?>

			<div class="ha-dual-btn">

				<div class="ha-dual-btn-link">
					<a
					href="<?php echo esc_url( $settings['left_button_link']['url'] ); ?>"
					class="ha-dual-btn-link-primary"
					>

						<i class="<?php echo esc_attr( $settings['left_icon_picker'] ); ?>"></i>
						<span <?php echo $this->get_render_attribute_string( 'left_button_text' ); ?>>
							<?php echo esc_html( $settings['left_button_text'], 'happy_addons' ); ?>
						</span>

					</a>
					<?php if ( $settings['button_direction_show'] === 'yes' ) { ?>
						<div <?php echo $this->get_render_attribute_string( 'button_direction_text' ); ?>>
							<?php echo esc_html( $settings['button_direction_text'], 'happy_addons' ); ?>
						</div>
					<?php } ?>
				</div>

				<div class="ha-dual-btn-link">
					<a
					href="<?php echo esc_url( $settings['right_button_link']['url'] ); ?>"
					class="ha-dual-btn-link-secondary"
					>

						<i class="<?php echo esc_attr( $settings['right_icon_picker'] ); ?>"></i>
						<span <?php echo $this->get_render_attribute_string( 'right_button_text' ); ?>>
							<?php echo esc_html( $settings['right_button_text'], 'happy_addons' ); ?>
						</span>
					</a>
				</div>

			</div>
	<?php
	}

	public function _content_template() {
		?>
		<#
		view.addInlineEditingAttributes( 'left_button_text', 'none' );
		view.addInlineEditingAttributes( 'right_button_text', 'none' );

		view.addInlineEditingAttributes( 'button_direction_text', 'none' );
		view.addRenderAttribute( 'button_direction_text', 'class', 'ha-middle-text' );
		#>
		<div class="ha-dual-btn">

			<div class="ha-dual-btn-link">
				<a href="{{{ settings.left_button_link.url }}}" class="ha-dual-btn-link-primary">

					<i class="{{{ settings.left_icon_picker }}}"></i>
					<span {{{ view.getRenderAttributeString( 'left_button_text' ) }}}>
						{{{ settings.left_button_text }}}
					</span>

				</a>
				<# if ( settings.button_direction_show === 'yes' ) { #>
					<div {{{ view.getRenderAttributeString( 'button_direction_text' ) }}}>
						{{{ settings.button_direction_text }}}
					</div>
				<# } #>
			</div>

			<div class="ha-dual-btn-link">
				<a href="{{{ settings.right_button_link.url }}}" class="ha-dual-btn-link-secondary">

					<i class="{{{ settings.right_icon_picker }}}"></i>
					<span {{{ view.getRenderAttributeString( 'right_button_text' ) }}}>
						{{{ settings.right_button_text }}}
					</span>

				</a>
			</div>

		</div>

	<?php
	}

}
