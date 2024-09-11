<?php
/**
 * Liquid Hover class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Utils;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Text_Shadow;

defined( 'ABSPATH' ) || die();

class Liquid_Hover extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Liquid Hover', 'happy-elementor-addons' );
	}

	public function get_custom_help_url() {
		return 'https://happyaddons.com/docs/happy-addons-for-elementor/widgets/liquid-hover/';
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
		return 'hm hm-liquid-hover';
	}

	public function get_keywords() {
		return [ 'liquid-hover','liquid','hover' ];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->__liquid_image_content_controls();
		$this->__liquid_title_content_controls();
	}

	protected function __liquid_image_content_controls() {

        $this->start_controls_section(
			'liquid_image_content_section',
			[
				'label' => __( 'Image', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'first_image',
			[
				'label' => __( 'Initial Image', 'happy-elementor-addons' ),
				'show_label' => false,
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
                    'active' => true,
                ],
			]
		);

		$this->add_control(
			'second_image',
			[
				'label' => __( 'Hover Image', 'happy-elementor-addons' ),
				'show_label' => false,
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
	                'active' => true,
	            ],
            ],
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'happy-elementor-addons' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'happy-elementor-addons' ),
				'show_external' => true,
				'default' => [
					'url' => '',
				],
				'dynamic' => [
                    'active' => true,
                ],
			]
		);

		$this->add_control(
			'animation_heading',
			[
				'label' => esc_html__( 'Animation', 'happy-elementor-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'hover_effect',
			[
				'label' => __( 'Hover Effect', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default.png',
				'options' => [
					'default.png'  => __( 'Default', 'happy-elementor-addons' ),
					'zigzag.jpg'  => __( 'Zigzag', 'happy-elementor-addons' ),
					'stripe.png'  => __( 'Stripe', 'happy-elementor-addons' ),
					'wave.jpg'  => __( 'Wave', 'happy-elementor-addons' ),
					'parallel.jpg'  => __( 'Parallel', 'happy-elementor-addons' ),
					'water.jpg'  => __( 'Water', 'happy-elementor-addons' ),
					'concrete.jpg'  => __( 'Concrete', 'happy-elementor-addons' ),
					'mosaic.jpg'  => __( 'Mosaic', 'happy-elementor-addons' ),
					'honeycomb.jpg'  => __( 'Honeycomb', 'happy-elementor-addons' ),
					'noise.jpg'  => __( 'Noise', 'happy-elementor-addons' ),
					'paint.jpg'  => __( 'Paint', 'happy-elementor-addons' ),
				],
			]
		);

		$this->add_control(
			'intensity',
			[
				'label' => __( 'Intensity', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.01,
					],
				],
				'default' => [
					'size' => 0.3,
				],
			],
		);

		$this->add_control(
			'speed',
			[
				'label' => __( 'Speed', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0.5,
						'max' => 5,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 1.5,
				],
			],
		);

		$this->add_control(
			'angle',
			[
				'label' => __( 'Angle', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 360,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 0,
				],
			],
		);

		$this->end_controls_section();
	}

	protected function __liquid_title_content_controls() {

        $this->start_controls_section(
			'liquid_title_content_section',
			[
				'label' => __( 'Title & Subtitle', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'happy-elementor-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				// 'default' => __( 'OFFBEAT', 'happy-elementor-addons' ),
				'dynamic' => [
                    'active' => true,
                ],
			]
		);

		$this->add_control(
			'sub_title',
			[
				'label' => __( 'Sub Title', 'happy-elementor-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				// 'default' => __( 'Lorem ipsum dolor sit amet', 'happy-elementor-addons' ),
				'dynamic' => [
                    'active' => true,
                ],
			]
		);

		$this->add_control(
			'title_hover_style',
			[
				'label' => __( 'Style', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'style-1',
				'options' => [
					'style-1'  => __( 'Style 1', 'happy-elementor-addons' ),
					'style-2'  => __( 'Style 2', 'happy-elementor-addons' ),
					'style-3'  => __( 'Style 3', 'happy-elementor-addons' ),
					'style-4'  => __( 'Style 4', 'happy-elementor-addons' ),
				],
			]
		);

		$this->add_control(
			'style_1_direction',
			[
				'label' => __( 'Direction', 'happy-elementor-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'happy-elementor-addons' ),
						'icon' => 'eicon-arrow-left',
					],
					'right' => [
						'title' => __( 'Right', 'happy-elementor-addons' ),
						'icon' => 'eicon-arrow-right',
					],
					'up' => [
						'title' => __( 'Up', 'happy-elementor-addons' ),
						'icon' => 'eicon-arrow-up',
					],
					'down' => [
						'title' => __( 'Down', 'happy-elementor-addons' ),
						'icon' => 'eicon-arrow-down',
					],
				],
				'default' => 'left',
				'condition' => [
					'title_hover_style' => 'style-1'
				],
			]
		);

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

		$this->start_controls_section(
			'image_style',
			[
				'label' => __( 'Image', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_align',
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
				],
				'prefix_class' => 'content-align-%s',
                // 'selectors_dictionary' => [
                //     'left' => 'justify-content: flex-start',
                //     'center' => 'justify-content: center',
                //     'right' => 'justify-content: flex-end',
                // ],
                'selectors' => [
                    // '{{WRAPPER}} .ha-liquid-image-area' => '{{VALUE}}'
                    '{{WRAPPER}} .ha-liquid-image-area' => 'text-align:{{VALUE}}'
                ]
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label' => __( 'Width', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
                'selectors' => [
                    '{{WRAPPER}} .ha-liquid-image' => 'width: {{SIZE}}{{UNIT}}'
				],
				'render_type' => 'ui', //template
			],
		);

		$this->add_control(
			'opacity',
			[
				'label' => __( 'Opacity', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} canvas' => 'opacity: {{SIZE}};',
				],
			],
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'filter',
				'label' => __( 'CSS Filters', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} canvas',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => __( 'Border', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} canvas',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} canvas' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => __( 'Box Shadow', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} canvas',
			]
		);

		$this->end_controls_section();
	}

	protected function __title_style_controls() {

		$this->start_controls_section(
			'title_style',
			[
				'label' => __( 'Title & Subtitle', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'title',
							'operator' => '!=',
							'value' => '',
						],
						[
							'name' => 'sub_title',
							'operator' => '!=',
							'value' => '',
						],
					],
				],
			]
		);

		$this->add_control(
			'title_offset_toggle',
			[
				'label' => __( 'Offset', 'happy-elementor-addons' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => __( 'None', 'happy-elementor-addons' ),
				'label_on' => __( 'Custom', 'happy-elementor-addons' ),
				'return_value' => 'yes',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'title_offset_x',
			[
				'label' => __( 'Offset Left', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'condition' => [
					'title_offset_toggle' => 'yes'
				],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ha-liquid-title' => '--ha-lh-title-translate-x: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_offset_y',
			[
				'label' => __( 'Offset Top', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'condition' => [
					'title_offset_toggle' => 'yes'
				],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ha-liquid-title' => '--ha-lh-title-translate-y: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_popover();

		$this->add_responsive_control(
			'title_area_width',
			[
				'label' => __( 'Width', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
                'selectors' => [
                    '{{WRAPPER}} .ha-liquid-title' => 'width: {{SIZE}}{{UNIT}}'
				],
				// 'render_type' => 'ui', //template
			],
		);

		$this->add_responsive_control(
			'title_align',
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
				],
                'selectors' => [
                    '{{WRAPPER}} .ha-liquid-title' => 'text-align:{{VALUE}}'
                ]
			]
		);

		$this->add_control(
			'title_heading',
			[
				'label' => esc_html__( 'Title', 'happy-elementor-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-liquid-title h2',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-liquid-title h2' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_text_shadow',
				'selector' => '{{WRAPPER}} .ha-liquid-title h2',
			]
		);

		$this->add_control(
			'sub_title_heading',
			[
				'label' => esc_html__( 'Subtitle', 'happy-elementor-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sub_title_typography',
				'label'    => __( 'Typography', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-liquid-title p',
			]
		);

		$this->add_control(
			'sub_title_color',
			[
				'label' => __( 'Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-liquid-title p' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$target = $settings['link']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['link']['nofollow'] ? ' rel="nofollow"' : '';

        $intensity = isset($settings['intensity']['size']) ? $settings['intensity']['size'] : 0.3;
        $angle = isset($settings['angle']['size']) ? $settings['angle']['size'] : 45;
        $speed = isset($settings['speed']['size']) ? $settings['speed']['size'] : 1.5;

        $js_data = [
        	// 'plugin_url' => plugins_url( '', __FILE__ ),
        	'plugin_url' => HAPPY_ADDONS_ASSETS. 'imgs/',
        	'first_image' => $settings['first_image']['url'],
            'second_image'	=> $settings['second_image']['url'],
            'hover_effect'	=> $settings['hover_effect'],
            // 'width'	=> $settings['width'],
            // 'width_tablet'	=> $width_tablet,
            // 'width_mobile'	=> $width_mobile,
            'intensity' => $intensity,
            'speed' => $speed,
            'angle' => $angle,
            'hover_style' => $settings['title_hover_style'],
            'style_1_direction' => $settings['style_1_direction']
		];

		$this->add_render_attribute(
			'liquid_hover_wrap',[
				'class' => ['ha-liquid-hover-area',$settings['title_hover_style']],
			]
		);

		$this->add_render_attribute(
			'img_wrap',[
				'class' => ['ha-liquid-image-area'],
				'data-settings' => [ json_encode($js_data) ]
			]
		);
		?>
		<div <?php echo $this->get_render_attribute_string( 'liquid_hover_wrap' ); ?>>

			<?php if ( $settings['title'] && $settings['sub_title'] ) : ?>
				<?php if ( 'style-1' == $settings['title_hover_style'] ) : ?>
				<div class="ha-liquid-title" data-style="<?php echo esc_attr( $settings['title_hover_style'] );?>">
					<?php if ( $settings['title'] ) : ?>
						<h2><?php echo esc_html( $settings['title'] ); ?></h2>
					<?php endif;?>
					<?php if ( $settings['sub_title'] ) : ?>
						<p><?php echo esc_html( $settings['sub_title'] ); ?></p>
					<?php endif;?>
				</div>
				<?php endif;?>
				<?php if ( 'style-2' == $settings['title_hover_style'] ) : ?>
				<div class="ha-liquid-title" data-style="<?php echo esc_attr( $settings['title_hover_style'] );?>">
					<?php if ( $settings['title'] ) : ?>
						<h2>
							<span class="block normal"><?php echo esc_html( $settings['title'] ); ?></span>
							<span class="block hover"><?php echo esc_html( $settings['title'] ); ?></span>
						</h2>
					<?php endif;?>
					<?php if ( $settings['sub_title'] ) : ?>
						<p>
							<span class="block normal"><?php echo esc_html( $settings['sub_title'] ); ?></span>
							<span class="block hover"><?php echo esc_html( $settings['sub_title'] ); ?></span>
						</p>
					<?php endif;?>
				</div>
				<?php endif;?>
			<?php endif;?>

			<div <?php echo $this->get_render_attribute_string( 'img_wrap' ); ?>>
				<div class="ha-liquid-image">
					<img src="<?php echo esc_url( $settings['first_image']['url'] ); ?>">
					<?php if( $settings['link']['url'] != '' ) : ?>
						<a href="<?php echo esc_url( $settings['link']['url'] ); ?>"<?php echo $target . $nofollow; ?>></a>
					<?php endif; ?>
				</div>
			</div>

    	</div>
		<?php
	}

}
