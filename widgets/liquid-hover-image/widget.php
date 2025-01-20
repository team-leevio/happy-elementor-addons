<?php

	/**
	 * Liquid Hover Image class
	 *
	 * @package Happy_Addons
	 */
	namespace Happy_Addons\Elementor\Widget;

	use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

	defined( 'ABSPATH' ) || die();

	class Liquid_Hover_Image extends Base {
		/**
		 * Get widget title.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @return string Widget title.
		 */
		public function get_title() {
			return __( 'Liquid Hover Image', 'happy-elementor-addons' );
		}

		public function get_custom_help_url() {
			return 'https://happyaddons.com/docs/happy-addons-for-elementor/widgets/liquid-hover-image/';
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
			return 'hm hm-liquid-hover-image';
		}

		public function get_keywords() {
			return ['liquid-hover-image', 'liquid', 'hover', 'image'];
		}

		protected function is_dynamic_content(): bool {
			return false;
		}

		/**
		 * Register widget content controls
		 */
		protected function register_content_controls() {
			$this->__liquid_image_content_controls();
			$this->__liquid_title_content_controls();
		}

		protected function __liquid_image_content_controls() {
			$this->start_controls_section( 'lhi_content_section', [
				'label' => __( 'Image', 'happy-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT
			] );

			$this->add_control( 'first_image', [
				'label'      => __( 'Initial Image', 'happy-elementor-addons' ),
				'show_label' => true,
				'type'       => Controls_Manager::MEDIA,
				'default'    => [
					'url' => Utils::get_placeholder_image_src()
				],
				'dynamic'    => [
					'active' => true
				]
			] );

			$this->add_control( 'second_image', [
				'label'      => __( 'Hover Image', 'happy-elementor-addons' ),
				'show_label' => true,
				'type'       => Controls_Manager::MEDIA,
				'default'    => [
					'url' => Utils::get_placeholder_image_src()
				],
				'dynamic'    => [
					'active' => true
				]
			] );

			$this->add_control( 'important_note', [
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__(
					'Initial & Hover Image should have similar dimension for best effect.',
					'happy-elementor-addons'
				),
				'content_classes' =>
				'elementor-panel-alert elementor-panel-alert-info'
			] );

			$this->add_control( 'link', [
				'label'         => __( 'Link', 'happy-elementor-addons' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => __(
					'https://your-link.com',
					'happy-elementor-addons'
				),
				'show_external' => true,
				'default'       => [
					'url' => ''
				],
				'dynamic'       => [
					'active' => true
				]
			] );

			$this->add_control( 'animation_heading', [
				'label'     => esc_html__( 'Animation', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			] );

			$this->add_control( 'hover_effect', [
				'label'   => __( 'Hover Effect', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default.png',
				'options' => [
					'default.png'   => __( 'Default', 'happy-elementor-addons' ),
					'zigzag.jpg'    => __( 'Zigzag', 'happy-elementor-addons' ),
					'stripe.png'    => __( 'Stripe', 'happy-elementor-addons' ),
					'wave.jpg'      => __( 'Wave', 'happy-elementor-addons' ),
					'parallel.jpg'  => __( 'Parallel', 'happy-elementor-addons' ),
					'water.jpg'     => __( 'Water', 'happy-elementor-addons' ),
					'concrete.jpg'  => __( 'Concrete', 'happy-elementor-addons' ),
					'mosaic.jpg'    => __( 'Mosaic', 'happy-elementor-addons' ),
					'honeycomb.jpg' => __( 'Honeycomb', 'happy-elementor-addons' ),
					'noise.jpg'     => __( 'Noise', 'happy-elementor-addons' ),
					'paint.jpg'     => __( 'Paint', 'happy-elementor-addons' ),
					'custom'        => __( 'Custom Effect', 'happy-elementor-addons' )
				]
			] );

			$this->add_control( 'custom_effect', [
				'label'      => __( 'Custom Effect', 'happy-elementor-addons' ),
				'show_label' => false,
				'type'       => Controls_Manager::MEDIA,
				'default'    => [
					'url' => Utils::get_placeholder_image_src()
				],
				'dynamic'    => [
					'active' => false
				],
				'condition'  => [
					'hover_effect' => 'custom'
				]
			] );

			$this->add_control( 'intensity', [
				'label'      => __( 'Intensity', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1,
						'step' => 0.01
					]
				],
				'default'    => [
					'size' => 0.3
				]
			] );

			$this->add_control( 'duration', [
				'label'      => __( 'Duration', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 0.5,
						'max'  => 5,
						'step' => 0.1
					]
				],
				'default'    => [
					'size' => 1.5
				]
			] );

			$this->add_control( 'angle', [
				'label'      => __( 'Angle', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => -360,
						'max'  => 360,
						'step' => 1
					]
				],
				'default'    => [
					'size' => 0
				]
			] );

			$this->end_controls_section();
		}

		protected function __liquid_title_content_controls() {
			$this->start_controls_section( 'lhi_title_content_section', [
				'label' => __( 'Title & Subtitle', 'happy-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT
			] );

			$this->add_control( 'title', [
				'label'       => __( 'Title', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => [
					'active' => true
				]
			] );

			$this->add_control( 'sub_title', [
				'label'       => __( 'Sub Title', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => [
					'active' => true
				]
			] );

			$this->add_control( 'title_hover_style', [
				'label'   => __( 'Effects', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'style-1',
				'options' => [
					'style-1' => __( 'Style 1', 'happy-elementor-addons' ),
					'style-2' => __( 'Style 2', 'happy-elementor-addons' ),
					'style-3' => __( 'Style 3', 'happy-elementor-addons' ),
					'style-4' => __( 'Style 4', 'happy-elementor-addons' ),
					'style-5' => __( 'Style 5', 'happy-elementor-addons' ),
					'style-6' => __( 'Style 6', 'happy-elementor-addons' )
				]
			] );

			$this->add_control( 'style_1_direction', [
				'label'     => __( 'Direction', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'  => [
						'title' => __( 'Left', 'happy-elementor-addons' ),
						'icon'  => 'eicon-arrow-left'
					],
					'right' => [
						'title' => __( 'Right', 'happy-elementor-addons' ),
						'icon'  => 'eicon-arrow-right'
					],
					'up'    => [
						'title' => __( 'Up', 'happy-elementor-addons' ),
						'icon'  => 'eicon-arrow-up'
					],
					'down'  => [
						'title' => __( 'Down', 'happy-elementor-addons' ),
						'icon'  => 'eicon-arrow-down'
					]
				],
				'default'   => 'left',
				'condition' => [
					'title_hover_style' => 'style-1'
				]
			] );

			$this->end_controls_section();
		}

		/**
		 * Register widget style controls
		 */
		protected function register_style_controls() {
			$this->__image_style_controls();
			$this->__title_style_controls();
		}

		protected function __image_style_controls() {
			$this->start_controls_section( 'lhi_image_style', [
				'label' => __( 'Image', 'happy-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE
			] );

			$this->add_responsive_control( 'content_align', [
				'label'     => __( 'Alignment', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'happy-elementor-addons' ),
						'icon'  => 'eicon-text-align-left'
					],
					'center' => [
						'title' => __( 'Center', 'happy-elementor-addons' ),
						'icon'  => 'eicon-text-align-center'
					],
					'right'  => [
						'title' => __( 'Right', 'happy-elementor-addons' ),
						'icon'  => 'eicon-text-align-right'
					]
				],
				'selectors' => [
					'{{WRAPPER}} .ha-lhi-image-area' => 'text-align:{{VALUE}}'
				]
			] );

			$this->add_responsive_control( 'width', [
				'label'       => __( 'Width', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => ['px', '%'],
				'range'       => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1
					],
					'%'  => [
						'min' => 0,
						'max' => 100
					]
				],
				'selectors'   => [
					'{{WRAPPER}} .ha-lhi-image' => 'width: {{SIZE}}{{UNIT}}'
				],
				'render_type' => 'ui' //template
			] );

			$this->add_control( 'opacity', [
				'label'      => __( 'Opacity', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1,
						'step' => 0.01
					]
				],
				'selectors'  => [
					'{{WRAPPER}} canvas' => 'opacity: {{SIZE}};'
				]
			] );

			$this->add_group_control( Group_Control_Css_Filter::get_type(), [
				'name'     => 'filter',
				'label'    => __( 'CSS Filters', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} canvas'
			] );

			$this->add_group_control( Group_Control_Border::get_type(), [
				'name'     => 'border',
				'label'    => __( 'Border', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} canvas'
			] );

			$this->add_control( 'border_radius', [
				'label'      => __( 'Border Radius', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} canvas' =>
					'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			] );

			$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
				'name'     => 'box_shadow',
				'label'    => __( 'Box Shadow', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} canvas'
			] );

			$this->end_controls_section();
		}

		protected function __title_style_controls() {
			$this->start_controls_section( 'lhi_title_style', [
				'label'      => __( 'Title & Subtitle', 'happy-elementor-addons' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'title',
							'operator' => '!=',
							'value'    => ''
						],
						[
							'name'     => 'sub_title',
							'operator' => '!=',
							'value'    => ''
						]
					]
				]
			] );

			$this->add_control( 'title_heading', [
				'label' => esc_html__( 'Title', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::HEADING
			] );

			$this->add_group_control( Group_Control_Typography::get_type(), [
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-lhi-title h2',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY
				]
			] );

			$this->add_control( 'title_color', [
				'label'     => __( 'Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-lhi-title h2' => 'color: {{VALUE}}'
				]
			] );

			$this->add_control( 'title_hover_color', [
				'label'     => __( 'Hover Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .style-2 .ha-lhi-title h2 .hover'     =>
					'color: {{VALUE}}',
					'{{WRAPPER}} .style-4 .ha-lhi-title h2:before'     =>
					'color: {{VALUE}}',
					'{{WRAPPER}} .style-5 .ha-lhi-title h2 span.hover' =>
					'color: {{VALUE}}'
				],
				'condition' => [
					'title_hover_style' => ['style-2', 'style-4', 'style-5']
				]
			] );

			$this->add_control( 'title_secondary_hover_color', [
				'label'     => __( 'Secondary Hover Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .style-4 .ha-lhi-title h2:after' =>
					'color: {{VALUE}}'
				],
				'condition' => [
					'title_hover_style' => ['style-4']
				]
			] );

			$this->add_group_control( Group_Control_Text_Shadow::get_type(), [
				'name'     => 'title_text_shadow',
				'selector' => '{{WRAPPER}} .ha-lhi-title h2'
			] );

			$this->add_control( 'title_bottom_space', [
				'label'      => __( 'Bottom Space', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1
					]
				],
				'selectors'  => [
					'{{WRAPPER}} .ha-lhi-title h2' => 'margin-bottom: {{SIZE}}px'
				]
			] );

			$this->add_control( 'sub_title_heading', [
				'label'     => esc_html__( 'Subtitle', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			] );

			$this->add_group_control( Group_Control_Typography::get_type(), [
				'name'     => 'sub_title_typography',
				'label'    => __( 'Typography', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-lhi-title p'
			] );

			$this->add_control( 'sub_title_color', [
				'label'     => __( 'Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-lhi-title p' => 'color: {{VALUE}}'
				]
			] );

			$this->add_control( 'sub_title_hover_color', [
				'label'     => __( 'Hover Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .style-2 .ha-lhi-title p .hover' =>
					'color: {{VALUE}}'
				],
				'condition' => [
					'title_hover_style' => ['style-2']
				]
			] );

			$this->add_control( 'general_heading', [
				'label'     => esc_html__( 'General', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			] );

			$this->add_control( 'title_offset_toggle', [
				'label'        => __( 'Offset', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'None', 'happy-elementor-addons' ),
				'label_on'     => __( 'Custom', 'happy-elementor-addons' ),
				'return_value' => 'yes'
			] );

			$this->start_popover();

			$this->add_responsive_control( 'title_offset_x', [
				'label'      => __( 'Offset Left', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'condition'  => [
					'title_offset_toggle' => 'yes'
				],
				'range'      => [
					'px' => [
						'min' => -1000,
						'max' => 1000
					]
				],
				'selectors'  => [
					'{{WRAPPER}} .ha-lhi-title' =>
					'--ha-lhi-title-translate-x: {{SIZE}}{{UNIT}};'
				]
			] );

			$this->add_responsive_control( 'title_offset_y', [
				'label'      => __( 'Offset Top', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'condition'  => [
					'title_offset_toggle' => 'yes'
				],
				'range'      => [
					'px' => [
						'min' => -1000,
						'max' => 1000
					]
				],
				'selectors'  => [
					'{{WRAPPER}} .ha-lhi-title' =>
					'--ha-lhi-title-translate-y: {{SIZE}}{{UNIT}};'
				]
			] );
			$this->end_popover();

			$this->add_responsive_control( 'title_area_width', [
				'label'      => __( 'Width', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1
					],
					'%'  => [
						'min' => 0,
						'max' => 100
					]
				],
				'selectors'  => [
					'{{WRAPPER}} .ha-lhi-title' => 'width: {{SIZE}}{{UNIT}}'
				]
			] );

			$this->add_responsive_control( 'title_align', [
				'label'     => __( 'Alignment', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'happy-elementor-addons' ),
						'icon'  => 'eicon-text-align-left'
					],
					'center' => [
						'title' => __( 'Center', 'happy-elementor-addons' ),
						'icon'  => 'eicon-text-align-center'
					],
					'right'  => [
						'title' => __( 'Right', 'happy-elementor-addons' ),
						'icon'  => 'eicon-text-align-right'
					]
				],
				'selectors' => [
					'{{WRAPPER}} .ha-lhi-title' => 'text-align:{{VALUE}}'
				]
			] );

			$this->end_controls_section();
		}

		protected function render() {
			$settings = $this->get_settings_for_display();
			$target   = $settings['link']['is_external'] ? ' target="_blank"' : '';
			$nofollow = $settings['link']['nofollow'] ? ' rel="nofollow"' : '';

			$intensity = isset( $settings['intensity']['size'] )
				? $settings['intensity']['size']
				: 0.3;
			$angle = isset( $settings['angle']['size'] )
				? $settings['angle']['size']
				: 45;
			$speed = isset( $settings['duration']['size'] )
				? $settings['duration']['size']
				: 1.5;

			$data_json = [
				'plugin_url'        => HAPPY_ADDONS_ASSETS . 'imgs/',
				'first_image'       => esc_url( $settings['first_image']['url'] ),
				'second_image'      => esc_url( $settings['second_image']['url'] ),
				'hover_effect'      => esc_html( $settings['hover_effect'] ),
				'intensity'         => esc_html( $intensity ),
				'speed'             => (int) esc_html( $speed ),
				'angle'             => esc_html( $angle ),
				'hover_style'       => esc_html( $settings['title_hover_style'] ),
				'style_1_direction' => esc_html( $settings['style_1_direction'] )
			];

			if ( 'custom' == $settings['hover_effect'] && $settings['custom_effect']['url'] ) {
				$data_json['custom_effect'] = esc_url( $settings['custom_effect']['url'] );
			}

			$this->add_render_attribute( 'lhi_wrap', [
				'class' => [
					'ha-lhi-area',
					esc_html( $settings['title_hover_style'] )
				]
			] );

			$this->add_render_attribute( 'lhi_img_wrap', [
				'class'         => ['ha-lhi-image-area'],
				'data-settings' => [json_encode( $data_json )]
			] );
			// liquid-hover-image
		?>
		<div <?php $this->print_render_attribute_string( 'lhi_wrap' );?> >

			<?php if ( $settings['title'] || $settings['sub_title'] ): ?>
				<?php if ( 'style-1' == $settings['title_hover_style'] ): ?>
					<div class="ha-lhi-title">
						<?php if ( $settings['title'] ): ?>
							<h2><?php echo esc_html( $settings['title'] ); ?></h2>
						<?php endif;?>
						<?php if ( $settings['sub_title'] ): ?>
							<p><?php echo esc_html( $settings['sub_title'] ); ?></p>
						<?php endif;?>
					</div>
				<?php endif;?>
				<?php if ( 'style-2' == $settings['title_hover_style'] ): ?>
					<div class="ha-lhi-title">
						<?php if ( $settings['title'] ): ?>
							<h2>
								<span class="block normal"><?php echo esc_html( $settings['title'] ); ?></span>
								<span class="block hover"><?php echo esc_html( $settings['title'] ); ?></span>
							</h2>
						<?php endif;?>
						<?php if ( $settings['sub_title'] ): ?>
							<p>
								<span class="block normal"><?php echo esc_html( $settings['sub_title'] ); ?></span>
								<span class="block hover"><?php echo esc_html( $settings['sub_title'] ); ?></span>
							</p>
						<?php endif;?>
					</div>
				<?php endif;?>

				<?php if ( 'style-3' == $settings['title_hover_style'] ): ?>
					<div class="ha-lhi-title">
						<?php if ( $settings['title'] ): ?>
							<h2>
								<?php
									$title = str_split( $settings['title'] );
											if ( $title ) {
												foreach ( $title as $key => $value ) {
													if ( ' ' != $value ) {
														echo '<span style="animation-delay: ' . esc_attr( 8 * $key ) . '0ms;">' . esc_html( $value ) . '</span>';
													} else {
														echo '<span class="empty">' . esc_html( $value ) . '</span>';
													}
												}
											} else {
												echo '<span>' . esc_html( $settings['title'] ) . '</span>';
											}
										?>
							</h2>
						<?php endif;?>
							<?php if ( $settings['sub_title'] ): ?>
							<p><?php echo esc_html( $settings['sub_title'] ); ?></p>
						<?php endif;?>
					</div>
				<?php endif;?>

				<?php if ( 'style-4' == $settings['title_hover_style'] ): ?>
					<div class="ha-lhi-title">
						<?php if ( $settings['title'] ): ?>
							<h2 data-text="<?php echo esc_attr( $settings['title'] ); ?>"><?php echo esc_html( $settings['title'] ); ?></h2>
						<?php endif;?>
						<?php if ( $settings['sub_title'] ): ?>
							<p><?php echo esc_html( $settings['sub_title'] ); ?></p>
						<?php endif;?>
					</div>
				<?php endif;?>

				<?php if ( 'style-5' == $settings['title_hover_style'] ): ?>
					<div class="ha-lhi-title">
						<?php if ( $settings['title'] ): ?>
							<h2 data-text="<?php echo esc_attr( $settings['title'] ); ?>">
								<span class="block normal"><?php echo esc_html( $settings['title'] ); ?></span>
								<span class="block hover"><?php echo esc_html( $settings['title'] ); ?></span>
							</h2>
						<?php endif;?>
						<?php if ( $settings['sub_title'] ): ?>
							<p><?php echo esc_html( $settings['sub_title'] ); ?></p>
						<?php endif;?>
					</div>
				<?php endif;?>

				<?php if ( 'style-6' == $settings['title_hover_style'] ): ?>
					<div class="ha-lhi-title">
						<?php if ( $settings['title'] ): ?>
							<h2><?php echo esc_html( $settings['title'] ); ?></h2>
						<?php endif;?>
						<?php if ( $settings['sub_title'] ): ?>
							<p><?php echo esc_html( $settings['sub_title'] ); ?></p>
						<?php endif;?>
					</div>
				<?php endif;?>

			<?php endif;?>

			<div <?php $this->print_render_attribute_string( 'lhi_img_wrap' );?>>
				<div class="ha-lhi-image">
					<img src="<?php echo esc_url( $settings['first_image']['url'] ); ?>">
					<?php if ( '' != $settings['link']['url'] ): ?>
						<a href="<?php echo esc_url( $settings['link']['url'] ); ?>" <?php echo esc_attr( $target ) . esc_attr( $nofollow ); ?>></a>
					<?php endif;?>
				</div>
			</div>

		</div>
<?php
	}
}
