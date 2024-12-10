<?php
	/**
	 * Text Scroll widget class
	 *
	 * @package Happy_Addons
	 */
	namespace Happy_Addons\Elementor\Widget;

	use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
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
			return '';
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
			return 'huge huge-scroll';
		}

		public function get_keywords() {
			return ['Text', 'text scroll', 'Text Scroll', 'scroll'];
		}

		public function get_categories() {
			return ['happy_addons_category', 'happy_addons_theme_builder'];
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
						'vertical_line_highlight' => __( 'Vertical Line Highlight', 'happy-elementor-addons' ),
						'horizontal_line_mask'    => __( 'Horizontal Line Mask', 'happy-elementor-addons' ),
						'vertical_line_mask'      => __( 'Vertical Line Mask', 'happy-elementor-addons' )
					],
					'render_type'        => 'template',
					'style_transfer'     => true,
					'frontend_available' => true
				]
			);

			$this->add_control(
				'scroll_text',
				[
					'label'       => __( 'Scroll Text', 'happy-elementor-addons' ),
					'type'        => Controls_Manager::TEXTAREA,
					'rows'        => 10,
					'default'     => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas in erat non urna placerat consectetur. Curabitur rhoncus iaculis tincidunt. Fusce vel lectus consequat nisl posuere pellentesque vel et metus. Nam egestas sodales sem et mattis. Morbi sed quam vel sem tempus feugiat. Maecenas tempus ante ipsum, vel aliquam justo hendrerit nec.', 'happy-elementor-addons' ),
					'placeholder' => __( 'Type your scroll text here', 'happy-elementor-addons' ),
					'dynamic'     => [
						'active' => true
					]
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
					'label'     => __( 'Alignment', 'happy-elementor-addons' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => [
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
					'default'   => 'start',
					'selectors' => [
						'{{WRAPPER}}.ha-text-scroll .ha-split-lines .line' => 'text-align: {{VALUE}} !important;'
					]
				]
			);

			$this->add_control(
				'text_scroll_color',
				[
					'label'     => __( 'Text Color', 'happy-elementor-addons' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#f8ebd3',
					'selectors' => [
						'{{WRAPPER}}.ha-text-scroll .ha-split-lines .line' => 'color: {{VALUE}};'
					]
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'text_scroll_typography',
					'label'    => __( 'Typography', 'happy-elementor-addons' ),
					'selector' => '{{WRAPPER}}.ha-text-scroll .ha-split-lines .line'
				]
			);

			$this->add_group_control(
				Group_Control_Text_Stroke::get_type(),
				[
					'name'     => 'text_scroll_stroke',
					'selector' => '{{WRAPPER}}.ha-text-scroll .ha-split-lines .line'
				]
			);
			
			$this->add_control(
				'text_scroll_bg_color',
				[
					'label'     => __( 'Background', 'happy-elementor-addons' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#262626',
					'selectors' => [
						'{{WRAPPER}}.ha-text-scroll' => 'background: {{VALUE}};',
					]
				]
			);

			$this->add_control(
				'text_scroll_masking_color',
				[
					'label'     => __( 'Masking Color', 'happy-elementor-addons' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#262626',
					'selectors' => [
						'{{WRAPPER}}.ha-text-scroll .ha-line-mask' => 'background: {{VALUE}};',
					],
					'condition'  => [
						'text_scroll_type!' => 'vertical_line_highlight'
					]
				]
			);

			$this->add_responsive_control(
				'text_scroll_padding',
				[
					'label'      => __( 'Line Padding', 'happy-elementor-addons' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => ['px', 'em', '%'],
					'default'    => [
						'top'      => '0',
						'right'    => '15',
						'bottom'   => '0',
						'left'     => '15',
						'unit'     => 'px',
					],
					'selectors'  => [
						'{{WRAPPER}}.ha-text-scroll .ha-split-lines .line' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
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
			<?php echo esc_html__( $scroll_text, 'happy-elementor-addons' ); ?>
		</div>

		<?php
			}

		}