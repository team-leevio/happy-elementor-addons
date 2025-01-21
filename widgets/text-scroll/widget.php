<?php
	/**
	 * Text Scroll widget class
	 *
	 * @package Happy_Addons
	 */
	namespace Happy_Addons\Elementor\Widget;

	use Elementor\Controls_Manager;
	use Elementor\Group_Control_Typography;
	use Elementor\Group_Control_Text_Stroke;

	defined( 'ABSPATH' ) || die();

	class Text_Scroll extends Base {

		/**
		 * Get widget title.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @return string Widget title.
		 */
		public function get_title() {
			return __( 'Text Scroll', 'happy-elementor-addons' );
		}

		public function get_custom_help_url() {
			return 'https://happyaddons.com/docs/happy-addons-for-elementor/widgets/text-scroll/';
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
			return 'hm hm-mouse-scroll-v';
		}

		public function get_keywords() {
			return ['Text', 'text scroll', 'Text Scroll', 'scroll'];
		}

		protected function is_dynamic_content(): bool {
			return false;
		}

		/**
		 * Register widget content controls
		 */
		protected function register_content_controls() {
			$this->text_scroll_content_control();
		}

		protected function text_scroll_content_control() {
			$this->start_controls_section(
				'section_text_scroll',
				[
					'label' => __( 'Text Scroll', 'happy-elementor-addons' ),
					'tab'   => Controls_Manager::TAB_CONTENT
				]
			);

			$this->add_control(
				'text_scroll_type',
				[
					'label'              => __( 'Scroll Type', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SELECT,
					'label_block'        => true,
					'default'            => 'vertical_line_highlight',
					'options'            => [
						'vertical_line_highlight'   => __( 'Vertical Line Highlight', 'happy-elementor-addons' ),
						'horizontal_line_highlight' => __( 'Horizontal Line Highlight', 'happy-elementor-addons' ),
						'vertical_line_mask'        => __( 'Vertical Line Mask', 'happy-elementor-addons' ),
						'horizontal_line_mask'      => __( 'Horizontal Line Mask', 'happy-elementor-addons' )
					],
					'render_type'        => 'template',
					'style_transfer'     => true,
					'frontend_available' => true
				]
			);

			$this->add_control(
				'scroll_text',
				[
					'label'          => __( 'Scroll Text', 'happy-elementor-addons' ),
					'type'           => Controls_Manager::TEXTAREA,
					'rows'           => 10,
					'default'        => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit. Maecenas in erat non urna placerat consectetur. Curabitur rhoncus iaculis tincidunt. Fusce vel lectus consequat nisl posuere pellentesque vel et metus. Nam egestas sodales semet mattis.', 'happy-elementor-addons' ),
					'placeholder'    => __( 'Type your scroll text here', 'happy-elementor-addons' ),
					'dynamic'        => [
						'active' => true
					],
					'style_transfer' => true
				]
			);

			$this->end_controls_section();
		}

		/**
		 * Register styles related controls
		 */
		protected function register_style_controls() {
			$this->text_scroll_style_controls();
		}

		protected function text_scroll_style_controls() {
			$this->start_controls_section(
				'section_text_scroll_style',
				[
					'label' => __( 'Text Scroll', 'happy-elementor-addons' ),
					'tab'   => Controls_Manager::TAB_STYLE
				]
			);

			$this->add_responsive_control(
				'text_scroll_align',
				[
					'label'          => __( 'Alignment', 'happy-elementor-addons' ),
					'type'           => Controls_Manager::CHOOSE,
					'options'        => [
						'start'  => [
							'title' => __( 'Left', 'happy-elementor-addons' ),
							'icon'  => 'eicon-text-align-left'
						],
						'center' => [
							'title' => __( 'Center', 'happy-elementor-addons' ),
							'icon'  => 'eicon-text-align-center'
						],
						'end'    => [
							'title' => __( 'Right', 'happy-elementor-addons' ),
							'icon'  => 'eicon-text-align-right'
						]
					],
					'default'        => 'start',
					'style_transfer' => true,
					'selectors'      => [
						'{{WRAPPER}}.ha-text-scroll .ha-split-lines .line' => 'text-align: {{VALUE}} !important;',
						'{{WRAPPER}}.ha-text-scroll .ha-split-lines'       => 'text-align: {{VALUE}} !important;'
					]
				]
			);

			$this->add_control(
				'text_scroll_color',
				[
					'label'          => __( 'Text Color', 'happy-elementor-addons' ),
					'type'           => Controls_Manager::COLOR,
					'style_transfer' => true,
					'selectors'      => [
						'{{WRAPPER}}.ha-text-scroll .ha-split-lines .line' => 'color: {{VALUE}};',
						'{{WRAPPER}}.ha-text-scroll .ha-split-lines .word' => 'color: {{VALUE}};'
					]
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'           => 'text_scroll_typography',
					'label'          => __( 'Typography', 'happy-elementor-addons' ),
					'style_transfer' => true,
					'selector'       => '{{WRAPPER}}.ha-text-scroll .ha-split-lines .line, {{WRAPPER}}.ha-text-scroll .ha-split-lines .word'
				]
			);

			$this->add_group_control(
				Group_Control_Text_Stroke::get_type(),
				[
					'name'           => 'text_scroll_stroke',
					'style_transfer' => true,
					'selectors'      => [
						'{{WRAPPER}}.ha-text-scroll .ha-split-lines .line',
						'{{WRAPPER}}.ha-text-scroll .ha-split-lines .word'
					]
				]
			);

			$this->add_control(
				'text_scroll_bg_color',
				[
					'label'          => __( 'Background', 'happy-elementor-addons' ),
					'type'           => Controls_Manager::COLOR,
					'style_transfer' => true,
					'selectors'      => [
						'{{WRAPPER}}.ha-text-scroll'               => 'background: {{VALUE}};',
						'{{WRAPPER}}.ha-text-scroll .ha-line-mask' => 'background: {{VALUE}};'
					]
				]
			);

			$this->add_control(
				'text_scroll_masking_opacity',
				[
					'label'          => __( 'Masking Opacity', 'happy-elementor-addons' ),
					'type'           => Controls_Manager::SLIDER,
					'style_transfer' => true,
					'range'          => [
						'px' => [
							'min'  => 0,
							'max'  => 1,
							'step' => 0.1
						]
					],
					'default'        => [
						'size' => 0.65
					],
					'selectors'      => [
						'{{WRAPPER}}.ha-text-scroll .ha-split-lines .ha-line-mask' => 'opacity: {{SIZE}};'
					],
					'condition'      => [
						'text_scroll_type!' => ['vertical_line_highlight', 'horizontal_line_highlight']
					]
				]
			);

			$this->add_control(
				'text_scroll_highlight_opacity',
				[
					'label'              => __( 'Highlight Opacity', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SLIDER,
					'render_type'        => 'template',
					'style_transfer'     => true,
					'frontend_available' => true,
					'range'              => [
						'px' => [
							'min'  => 0,
							'max'  => 1,
							'step' => 0.1
						]
					],
					'default'            => [
						'size' => 0.2
					],
					'selectors'          => [
						'{{WRAPPER}}.ha-text-scroll .ha-split-lines .line .word' => 'opacity: {{SIZE}};',
						'{{WRAPPER}}.ha-text-scroll .ha-split-lines .word .char' => 'opacity: {{SIZE}};'
					],
					'condition'          => [
						'text_scroll_type' => ['vertical_line_highlight', 'horizontal_line_highlight']
					]
				]
			);

			$this->add_responsive_control(
				'text_scroll_padding',
				[
					'label'          => __( 'Line Padding', 'happy-elementor-addons' ),
					'type'           => Controls_Manager::DIMENSIONS,
					'size_units'     => ['px', 'em', '%'],
					'default'        => [
						'top'    => '0',
						'right'  => '0',
						'bottom' => '0',
						'left'   => '0',
						'unit'   => 'px'
					],
					'style_transfer' => true,
					'selectors'      => [
						'{{WRAPPER}}.ha-text-scroll .ha-split-lines' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					]
				]
			);

			$this->end_controls_section();
		}

		protected function render() {
			$settings    = $this->get_settings_for_display();
			$scroll_text = ! empty( $settings['scroll_text'] ) ? $settings['scroll_text'] : '';

		?>

		<div class="ha-split-lines">
			<?php echo esc_html( $scroll_text ); ?>
		</div>

		<?php
			}

		}