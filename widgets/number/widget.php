<?php
/**
 * Blurb widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

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

class Number extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Number', 'happy_addons' );
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
		return 'hm hm-madel';
	}

	public function get_keywords() {
		return [ 'info', 'blurb', 'box', 'text', 'content' ];
	}

	/**
	 * Register content related controls
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'_section_number',
			[
				'label' => __( 'Number', 'happy_addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'number_text',
			[
				'label' => __( 'Number Text', 'happy_addons' ),
				'label_block' => false,
				'type' => Controls_Manager::TEXT,
				'default' => 1
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register styles related controls
	 */
	protected function register_style_controls() {
		$this->start_controls_section(
			'_section_style_number',
			[
				'label' => __( 'Text Style', 'happy_addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
            'number_text_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-number-border' => 'color: {{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'number_text_typography',
				'label' => __( 'Typography', 'happy_addons' ),
				'selector' => '{{WRAPPER}} .ha-number-border',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'number_text_shadow',
				'label' => __( 'Text Shadow', 'happy_addons' ),
				'selector' => '{{WRAPPER}} .ha-number-border span',
			]
		);

		$this->add_control(
			'number_text_rotate',
			[
				'label' => __( 'Text Rotate', 'happy_addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 360,
						'step' => 5,
					],

				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .ha-number-text' => '  transform: rotate(-{{SIZE}}deg);
														  -webkit-transform: rotate(-{{SIZE}}deg);
														  -moz-transform: rotate(-{{SIZE}}deg);
														  -ms-transform: rotate(-{{SIZE}}deg);
														  -o-transform: rotate(-{{SIZE}}deg);',

				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'number_background_style',
			[
				'label' => __( 'Background Style', 'happy_addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'number_width_height',
			[
				'label' => __( 'Width and Height', 'happy_addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .ha-number-border' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'number_background_color',
				'label' => __( 'Background', 'happy_addons' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .ha-number-border',
			]
		);

		$this->add_control(
			'number_padding',
			[
				'label' => __( 'Padding', 'happy_addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ha-number-border' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'number_border',
				'label' => __( 'Border', 'happy_addons' ),
				'selector' => '{{WRAPPER}} .ha-number-border',
			]
		);

		$this->add_control(
			'number_border_radius',
			[
				'label' => __( 'Border Radius', 'happy_addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ha-number-border' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'number_box_shadow',
				'label' => __( 'Box Shadow', 'happy_addons' ),
				'selector' => '{{WRAPPER}} .ha-number-border',
			]
		);

		$this->add_responsive_control(
			'number_align',
			[
				'label' => __( 'Alignment', 'happy_addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'float:left' => [
						'title' => __( 'Left', 'happy_addons' ),
						'icon' => 'fa fa-align-left',
					],
					'margin: 0 auto;' => [
						'title' => __( 'Center', 'happy_addons' ),
						'icon' => 'fa fa-align-center',
					],
					'float:right' => [
						'title' => __( 'Right', 'happy_addons' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .ha-number-border'  => '{{VALUE}};'
				]
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>

		<div class="ha-number-body">
			<div class="ha-number-border">
				<span class="ha-number-text"><?php echo esc_html( $settings['number_text'] ); ?></span>
			</div>
		</div>

		<?php
	}
}
