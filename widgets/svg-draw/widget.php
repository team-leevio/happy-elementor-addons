<?php
	/**
	 * SVG Draw widget class
	 *
	 * @package Happy_Addons
	 */
	namespace Happy_Addons\Elementor\Widget;

	use Elementor\Controls_Manager;
	use Elementor\Icons_Manager;
	use Elementor\Group_Control_Border;
	use Elementor\Group_Control_Background;
	use Elementor\Group_Control_Text_Shadow;

	defined( 'ABSPATH' ) || die();

	class Svg_Draw extends Base {

		/**
		 * Get widget title.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @return string Widget title.
		 */
		public function get_title() {
			return __( 'SVG Line Draw', 'happy-elementor-addons' );
		}

		public function get_custom_help_url() {
			return 'https://happyaddons.com/docs/happy-addons-for-elementor/widgets/#/';
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
			return 'hm hm-pen';
		}

		public function get_keywords() {
			return ['svg', 'icon', 'draw', 'svg line draw', 'Svg Line Draw', 'Svg', 'animation', 'creative', 'SVG Animation', 'SVG Draw', 'SVG', 'line', 'Line Draw', 'Line', 'Draw'];
		}

		protected function is_dynamic_content(): bool {
			return false;
		}

		/**
		 * Register widget content controls
		 */
		protected function register_content_controls() {
			$this->svg_draw_content_control();
		}

		protected function svg_draw_content_control() {

			$this->start_controls_section(
				'ha_section_svg_draw_settings',
				[
					'label' => __( 'Settings', 'happy-elementor-addons' ),
					'tab'   => Controls_Manager::TAB_CONTENT
				]
			);

			$this->add_control(
				'ha_icon_type',
				[
					'label'       => __( 'SVG Type', 'happy-elementor-addons' ),
					'type'        => Controls_Manager::SELECT,
					'options'     => [
						'icon'   => __( 'Font Awesome', 'happy-elementor-addons' ),
						'custom_code' => __( 'Custom SVG Code', 'happy-elementor-addons' )
					],
					'default'     => 'icon',
					'label_block' => true
				]
			);

			$this->add_control(
				'ha_font_icon',
				[
					'show_label'       => false,
					'type'             => Controls_Manager::ICONS,
					'fa4compatibility' => 'icon',
					'label_block'      => true,
					'default'          => [
						'value'   => 'fas fa-sun',
						'library' => 'fa-solid'
					],
					'condition'        => [
						'ha_icon_type' => 'icon'
					]
				]
			);

			$this->add_control(
				'ha_custom_svg',
				[
					'label'       => __( 'SVG Code', 'happy-elementor-addons' ),
					'type'        => Controls_Manager::TEXTAREA,
					'description' => 'You can use these sites to Convert SVG image to code: <a href="https://nikitahl.github.io/svg-2-code/" target="_blank">SVG 2 CODE</a> ',
					'condition'   => [
						'ha_icon_type' => 'custom_code'
					]
				]
			);

			$this->add_control(
				'ha_custom_svg_note',
				[
					'raw'             => __( 'Your SVG code must include a valid shape element such as path, circle, or rect to enable animation.', 'happy-elementor-addons' ),
					'type'            => Controls_Manager::RAW_HTML,
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
					'condition'       => [
						'ha_icon_type' => 'custom_code'
					]
				]
			);

			$this->add_responsive_control(
				'icon_width',
				[
					'label'      => __( 'Width', 'happy-elementor-addons' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => ['px', 'em', '%', 'custom'],
					'range'      => [
						'px' => [
							'min' => 1,
							'max' => 600
						],
						'em' => [
							'min' => 1,
							'max' => 30
						]
					],
					'default'    => [
						'size' => 150,
						'unit' => 'px'
					],
					'condition'  => [
						'ha_icon_type' => 'custom_code'
					],
					'selectors'  => [
						'{{WRAPPER}} .ha-svg-draw-container svg' => 'width: {{SIZE}}{{UNIT}};'
					]
				]
			);

			$this->add_responsive_control(
				'icon_height',
				[
					'label'      => __( 'Height', 'happy-elementor-addons' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => ['px', 'em', 'custom'],
					'range'      => [
						'px' => [
							'min' => 1,
							'max' => 600
						],
						'em' => [
							'min' => 1,
							'max' => 30
						]
					],
					'default'    => [
						'size' => 150,
						'unit' => 'px'
					],
					'condition'  => [
						'ha_icon_type' => 'custom_code'
					],
					'selectors'  => [
						'{{WRAPPER}} .ha-svg-draw-container svg' => 'height: {{SIZE}}{{UNIT}}'
					]
				]
			);

			$this->add_responsive_control(
				'icon_size',
				[
					'label'      => __( 'Size', 'happy-elementor-addons' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => ['px', 'em'],
					'range'      => [
						'px' => [
							'min' => 1,
							'max' => 500
						],
						'em' => [
							'min' => 1,
							'max' => 30
						]
					],
					'default'    => [
						'size' => 200,
						'unit' => 'px'
					],
					'condition'  => [
						'ha_icon_type' => 'icon'
					],
					'selectors'  => [
						'{{WRAPPER}} .ha-svg-draw-container svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}'
					]
				]
			);

			$this->add_responsive_control(
				'icon_align',
				[
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
					'default'   => 'center',
					'selectors' => [
						'{{WRAPPER}} .ha-svg-draw-container' => 'text-align: {{VALUE}};'
					],
					'toggle'    => false
				]
			);

			$this->add_control(
				'animate_icon',
				[
					'label'        => __( 'Enable SVG Draw?', 'happy-elementor-addons' ),
					'type'         => Controls_Manager::SWITCHER,
					'prefix_class' => 'ha-svg-animated-',
					'render_type'  => 'template',
					'separator'    => 'before',
					'default'      => 'yes'
				]
			);

			$this->add_control(
				'animation_reverse',
				[
					'label'        => __( 'Reverse Animation?', 'happy-elementor-addons' ),
					'type'         => Controls_Manager::SWITCHER,
					'prefix_class' => 'ha-svg-animation-rev-',
					'render_type'  => 'template',
					'separator'    => 'before',
					'condition'    => [
						'animate_icon' => 'yes'
					]
				]
			);

			$this->add_control(
				'animate_start_point',
				[
					'label'              => __( 'Start Point (%)', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SLIDER,
					'description'        => __( 'Set the point that the SVG should start from.', 'happy-elementor-addons' ),
					'default'            => [
						'unit' => '%',
						'size' => 0
					],
					'condition'          => [
						'animate_icon'       => 'yes',
						'animation_reverse!' => 'yes'
					],
					'frontend_available' => true
				]
			);

			$this->add_control(
				'animate_end_point',
				[
					'label'              => __( 'End Point (%)', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SLIDER,
					'description'        => __( 'Set the point that the SVG should end at.', 'happy-elementor-addons' ),
					'default'            => [
						'unit' => '%',
						'size' => 0
					],
					'condition'          => [
						'animate_icon'      => 'yes',
						'animation_reverse' => 'yes'
					],
					'frontend_available' => true
				]
			);

			$this->add_control(
				'scroll_action',
				[
					'label'              => __( 'Draw Behaviour?', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SELECT,
					'options'            => [
						'automatic' => __( 'Draw On Viewport Visibility', 'happy-elementor-addons' ),
						'viewport'  => __( 'Draw On Scroll', 'happy-elementor-addons' ),
						'hover'     => __( 'Draw On Hover', 'happy-elementor-addons' )
					],
					'default'            => 'viewport',
					'label_block'        => true,
					'condition'          => [
						'animate_icon' => 'yes'
					],
					'frontend_available' => true
				]
			);

			$this->add_control(
				'animate_trigger',
				[
					'label'              => __( 'Start Trigger?', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SELECT,
					'options'            => [
						'top'    => __( 'Top Edge of The Widget', 'happy-elementor-addons' ),
						'center' => __( 'Center Point of The Widget', 'happy-elementor-addons' ),
						'custom' => __( 'Custom Position', 'happy-elementor-addons' )
					],
					'default'            => 'center',
					'label_block'        => true,
					'condition'          => [
						'animate_icon'  => 'yes',
						'scroll_action' => 'automatic'
					],
					'frontend_available' => true
				]
			);

			$this->add_control(
				'frames',
				[
					'label'              => __( 'Draw Speed', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::NUMBER,
					'description'        => __( 'Larger value means longer draw duration.', 'happy-elementor-addons' ),
					'default'            => 5,
					'min'                => 1,
					'max'                => 100,
					'condition'          => [
						'animate_icon'   => 'yes',
						'scroll_action!' => 'viewport'
					],
					'frontend_available' => true
				]
			);

			$this->add_control(
				'loop',
				[
					'label'              => __( 'Loop', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SWITCHER,
					'condition'          => [
						'animate_icon'   => 'yes',
						'scroll_action!' => 'viewport'
					],
					'return_value'       => 'true',
					'frontend_available' => true
				]
			);

			$this->add_control(
				'repeat_delay',
				[
					'label' => __('Repeat Delay', 'happy-elementor-addons'),
					'type' => Controls_Manager::NUMBER,
					'description' => __('Delay before repeating the animation', 'happy-elementor-addons'),
					'default' => 5,
					'min' => 0.5,
					'max' => 50,
					'step' => 0.5,
					'condition' => [
						'loop' => 'true',
						'animate_icon'   => 'yes',
						'scroll_action!' => 'viewport'
					],
					'frontend_available' => true
				]
			);

			$this->add_control(
				'yoyo',
				[
					'label'              => __( 'Swing Effect', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SWITCHER,
					'condition'          => [
						'animate_icon'   => 'yes',
						'scroll_action!' => 'viewport',
						'loop'           => 'true'
					],
					'frontend_available' => true
				]
			);

			$this->add_control(
				'animate_offset',
				[
					'label'              => __( 'Offset (%)', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SLIDER,
					'default'            => [
						'size' => 50,
						'unit' => '%'
					],
					'frontend_available' => true,
					'conditions'         => [
						'terms' => [
							[
								'name'  => 'animate_icon',
								'value' => 'yes'
							],
							[
								'name'     => 'scroll_action',
								'operator' => '!=',
								'value'    => 'hover'
							],
							[
								'relation' => 'or',
								'terms'    => [
									[
										'name'  => 'scroll_action',
										'value' => 'viewport'
									],
									[
										'name'  => 'animate_trigger',
										'value' => 'custom'
									]
								]
							]
						]
					]
				]
			);

			$this->add_control(
				'draw_speed',
				[
					'label'              => __( 'Draw Speed Factor', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SLIDER,
					'description'        => __( 'Higher values = Slower animation.', 'happy-elementor-addons' ),
					'range'              => [
						'px' => [
							'min'  => 0,
							'max'  => 1,
							'step' => 0.1
						]
					],
					'default'            => [
						'size' => 0.3,
						'unit' => 'px'
					],
					'condition'          => [
						'animate_icon'  => 'yes',
						'scroll_action' => 'viewport'
					],
					'frontend_available' => true
				]
			);

			$this->add_control(
				'animation_sync',
				[
					'label'        => __( 'Draw All Strokes Together', 'happy-elementor-addons' ),
					'type'         => Controls_Manager::SWITCHER,
					'prefix_class' => 'ha-svg-sync-together-',
					'render_type'  => 'template',
					'style_transfer'       => true,
					'frontend_available'   => true,
					'condition'    => [
						'animate_icon' => 'yes',
						'ha_icon_type!'   => 'icon'
					]
				]
			);

			$this->add_control(
				'anim_rev',
				[
					'label'              => __( 'Restart Animation', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SWITCHER,
					'render_type'        => 'template',
					'default'            => 'yes',
					'condition'          => [
						'animate_icon'  => 'yes',
						'scroll_action' => 'automatic'
					],
					'frontend_available' => true
				]
			);

			$this->add_control(
				'svg_fill',
				[
					'label'              => __( 'Fill Color', 'happy-elementor-addons' ),
					'description'        => __( 'Enabling this will enable After Draw Fill Color, After Draw Stroke Color controls in Style Tab.', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SWITCHER,
					'condition'          => [
						'animate_icon' => 'yes'
					],
					'frontend_available' => true
				]
			);

			$this->add_control(
				'link',
				[
					'label'       => __( 'Link', 'happy-elementor-addons' ),
					'type'        => Controls_Manager::URL,
					'dynamic'     => ['active' => true],
					'placeholder' => 'https://happyaddons.com',
					'label_block' => true,
					'separator'   => 'before'
				]
			);

			$this->end_controls_section();
		}

		/**
		 * Register styles related controls
		 */
		protected function register_style_controls() {
			$this->svg_draw_style_controls();
		}

		protected function svg_draw_style_controls() {
			$this->start_controls_section(
				'section_svg_draw_style',
				[
					'label' => __( 'SVG Style', 'happy-elementor-addons' ),
					'tab'   => Controls_Manager::TAB_STYLE
				]
			);

			$this->add_control(
				'icon_color',
				[
					'label'     => __( 'Stroke Color', 'happy-elementor-addons' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#6EC1E4',
					'selectors' => [
						'{{WRAPPER}} .ha-svg-draw-container svg'   => 'color: {{VALUE}};overflow: visible;',
						'{{WRAPPER}} .ha-svg-draw-container svg *' => 'stroke: {{VALUE}}'
					]
				]
			);

			$this->add_control(
				'fill_color',
				[
					'label'     => __( 'Fill Color', 'happy-elementor-addons' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#FFF',
					'selectors' => [
						'{{WRAPPER}} .ha-svg-draw-container svg path, {{WRAPPER}} .ha-svg-draw-container svg circle, {{WRAPPER}} .ha-svg-draw-container svg square, {{WRAPPER}} .ha-svg-draw-container svg ellipse, {{WRAPPER}} .ha-svg-draw-container svg rect, {{WRAPPER}} .ha-svg-draw-container svg polyline, {{WRAPPER}} .ha-svg-draw-container svg line' => 'fill: {{VALUE}}'
					]
				]
			);

			$this->add_control(
				'svg_stroke',
				[
					'label'              => __( 'After Draw Stroke Color', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::COLOR,
					'global'             => false,
					'condition'          => [
						'animate_icon'  => 'yes',
						'svg_fill'      => 'yes',
					],
					'frontend_available' => true
				]
			);

			$this->add_control(
				'svg_color',
				[
					'label'              => __( 'After Draw Fill Color', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::COLOR,
					'global'             => false,
					'condition'          => [
						'animate_icon'  => 'yes',
						'svg_fill'      => 'yes',
					],
					'frontend_available' => true
				]
			);

			$this->add_responsive_control(
				'path_width',
				[
					'label'     => __( 'Stroke Thickness', 'happy-elementor-addons' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'min'  => 0,
							'max'  => 20,
							'step' => 0.1
						]
					],
					'default'   => [
						'size' => 3,
						'unit' => 'px'
					],
					'selectors' => [
						'{{WRAPPER}} .ha-svg-draw-container svg path, {{WRAPPER}} .ha-svg-draw-container svg circle, {{WRAPPER}} .ha-svg-draw-container svg square, {{WRAPPER}} .ha-svg-draw-container svg ellipse, {{WRAPPER}} .ha-svg-draw-container svg rect, {{WRAPPER}} .ha-svg-draw-container svg polyline, {{WRAPPER}} .ha-svg-draw-container svg line' => 'stroke-width: {{SIZE}}'
					]
				]
			);

			$this->add_control(
				'path_dashes',
				[
					'label'     => __( 'Space Between Dashes', 'happy-elementor-addons' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'min'  => 0,
							'max'  => 10,
							'step' => 0.1
						]
					],
					'condition' => [
						'animate_icon!' => 'yes'
					],
					'selectors' => [
						'{{WRAPPER}} .ha-svg-draw-container svg path, {{WRAPPER}} .ha-svg-draw-container svg circle, {{WRAPPER}} .ha-svg-draw-container svg square, {{WRAPPER}} .ha-svg-draw-container svg ellipse, {{WRAPPER}} .ha-svg-draw-container svg rect, {{WRAPPER}} .ha-svg-draw-container svg polyline, {{WRAPPER}} .ha-svg-draw-container svg line' => 'stroke-dasharray: {{SIZE}}'
					]
				]
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'     => 'icon_background',
					'types'    => ['classic', 'gradient'],
					'selector' => '{{WRAPPER}} .ha-svg-draw-container svg'
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'     => 'svg_icon_box',
					'selector' => '{{WRAPPER}} .ha-svg-draw-container svg'
				]
			);

			$this->add_responsive_control(
				'svg_icon_radius',
				[
					'label'      => __( 'Border Radius', 'happy-elementor-addons' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => ['px', 'em', '%'],
					'selectors'  => [
						'{{WRAPPER}} .ha-svg-draw-container svg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					]
				]
			);
			
			$this->add_responsive_control(
				'svg_icon_padding',
				[
					'label'      => __( 'Padding', 'happy-elementor-addons' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => ['px', 'em', '%'],
					'selectors'  => [
						'{{WRAPPER}} .ha-svg-draw-container svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					]
				]
			);

			$this->end_controls_section();
		}

		protected function render() {

			$settings = $this->get_settings_for_display();

			$type = $settings['ha_icon_type'];

			if ( ! empty( $settings['link']['url'] ) ) {
				$this->add_link_attributes( 'link', $settings['link'] );
			}

		?>

				<div class="ha-svg-draw-container">

				<?php if ( ! empty( $settings['link']['url'] ) ) { ?>
					<a <?php echo wp_kses_post( $this->get_render_attribute_string( 'link' ) ); ?>>
				<?php } ?>

					<?php if ( 'icon' === $type ){ ?>

						<?php
							$this->add_render_attribute(
										'fa_icon',
										[
											'id'          => 'ha-svg-icon-' . $this->get_id(),
											'class'       => [
												'ha-svg-icon',
												$settings['ha_font_icon']['value']
											],
											'aria-hidden' => 'true',
											'data-start'  => 'manual'
										]
									);

									echo $this->ha_get_svg_from_icon(
										$settings['ha_font_icon'],
										[
											'id'         => 'ha-svg-icon-' . $this->get_id(),
											'class'      => 'ha-svg-icon',
											'data-start' => 'manual'
										]
									);

								?>

					<?php } else { ?>

						<?php $this->print_unescaped_setting( 'ha_custom_svg' ); ?>

					<?php } ?>

					<?php if ( ! empty( $settings['link']['url'] ) ){ ?>
						</a>
					<?php } ?>

				</div>

			<?php
		}

		private function ha_get_svg_from_icon( $icon, $attributes = [] ) {

			if ( empty( $icon ) || empty( $icon['value'] ) || empty( $icon['library'] ) ) {
				return '';
			}

			if ( 'svg' === $icon['library'] ) {

				$svg_html = Icons_Manager::try_get_icon_html( $icon );

				return $svg_html;
			}

			$icon['font_family'] = 'font-awesome';

			$i_class = str_replace( ' ', '-', $icon['value'] );

			$svg_html = '<svg ';

			$icon = $this->ha_svg_icon_data( $icon );

			if ( ! $icon ) {
				Icons_Manager::render_icon( $icon, ['aria-hidden' => 'true'] );

				return;
			}

			$view_box = '0 0 ' . $icon['width'] . ' ' . $icon['height'];

			if ( is_array( $attributes ) ) {

				foreach ( $attributes as $key => $value ) {

					if ( 'class' === $key ) {

						$svg_html .= 'class="svg-inline--' . $i_class . ' ' . $value . '" ';

					} else {
						$svg_html .= " {$key}='{$value}' ";
					}
				}
			} else {

				$attributes = str_replace( 'class="', 'class="svg-inline--' . $i_class . ' ', $attributes );

				$svg_html .= $attributes;
			}

			$svg_html .= " aria-hidden='true' xmlns='http://www.w3.org/2000/svg' viewBox='{$view_box}'>";

			$svg_html .= '<path d="' . esc_attr( $icon['path'] ) . '"></path>';
			$svg_html .= '</svg>';

			return wp_kses( $svg_html, $this->ha_get_allowed_svg_tags() );
		}
		
		private function ha_get_allowed_svg_tags() {
			return [
				'svg'   => [
					'id'              => [],
					'class'           => [],
					'aria-hidden'     => [],
					'aria-labelledby' => [],
					'role'            => [],
					'xmlns'           => [],
					'width'           => [],
					'height'          => [],
					'viewbox'         => [],
					'data-*'          => true,
				],
				'g'     => [ 'fill' => [] ],
				'title' => [ 'title' => [] ],
				'path'  => [
					'd'    => [],
					'fill' => [],
				],
				'i'     => [
					'class' => [],
					'id'    => [],
					'style' => [],
				],
			];
		}

		private function ha_svg_icon_data( $icon ) {

			preg_match( '/fa(.*) fa-/', $icon['value'], $icon_name_matches );
	
			if( empty( $icon_name_matches ) ) {
				return;
			}
	
			$icon_name = str_replace( $icon_name_matches[0], '', $icon['value'] );
	
			$icon_key = str_replace( ' fa-', '-', $icon['value'] );
	
			$icon_file_name = str_replace( 'fa-', '', $icon['library'] );
	
			$path = ELEMENTOR_ASSETS_PATH . 'lib/font-awesome/json/' . $icon_file_name . '.json';
	
			$data = file_get_contents( $path );
	
			if( ! $data ) {
				return;
			}
	
			$data = json_decode( $data, true );
	
			$svg_data = $data['icons'][ $icon_name ];
	
			return [
				'width'  => $svg_data[0],
				'height' => $svg_data[1],
				'path'   => $svg_data[4],
			];
		}

}
