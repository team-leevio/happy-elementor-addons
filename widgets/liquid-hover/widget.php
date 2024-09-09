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
		$this->__liquid_title_content_controls();
		$this->__liquid_image_content_controls();
	}

	protected function __liquid_image_content_controls() {

        $this->start_controls_section(
			'liquid_image_content_section',
			[
				'label' => __( 'Liquid Image', 'happy-elementor-addons' ),
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
				'label' => __( 'Liquid Title', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
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
					'style-5'  => __( 'Style 5', 'happy-elementor-addons' ),
				],
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'happy-elementor-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'OFFBEAT', 'happy-elementor-addons' ),
				'dynamic' => [
                    'active' => true,
                ],
			]
		);

		$this->add_control(
			'sub_title',
			[
				'label' => __( 'Sub Title', 'happy-elementor-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'Lorem ipsum dolor sit amet', 'happy-elementor-addons' ),
				'dynamic' => [
                    'active' => true,
                ],
			]
		);

		$this->end_controls_section();
	}

	/**
     * Register widget style controls
     */
	protected function register_style_controls() {
		$this->__title_style_controls();
		$this->__image_style_controls();
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

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// $width = '';
		// if( $settings['width']['size'] != '' ){
		// 	$width = 'style="width:' . $settings['width']['size'] . $settings['width']['unit'] . ';"';
		// }

		$target = $settings['link']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['link']['nofollow'] ? ' rel="nofollow"' : '';


        // $width_tablet = isset($settings['width_tablet']) ? $settings['width_tablet'] : $settings['width'];
        // $width_mobile = isset($settings['width_mobile']) ? $settings['width_mobile'] : $width_tablet;
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
            'angle' => $angle
		];

		$this->add_render_attribute(
			'img_wrap',[
				'class' => ['ha-liquid-image-area'],
				'data-settings' => [ json_encode($js_data) ]
			]
		);
		?>
		<div class="ha-liquid-hover-area style-2">
			<div class="ha-liquid-title" data-style="<?php echo esc_attr( $settings['title_hover_style'] );?>">
				<h2><?php echo esc_html($settings['title']); ?></h2>
				<p class="letter"><?php echo esc_html($settings['sub_title']); ?></p>
			</div>
			<div <?php echo $this->get_render_attribute_string( 'img_wrap' ); ?>>
				<div class="ha-liquid-image">
					<img src="<?php echo esc_url( $settings['first_image']['url'] ); ?>">
					<?php if( $settings['link']['url'] != '' ) : ?>
						<a href="<?php echo $settings['link']['url']; ?>"<?php echo $target . $nofollow; ?>></a>
					<?php endif; ?>
				</div>
			</div>
    	</div>
		<?php
	}

	protected function render2() {
		$settings = $this->get_settings_for_display();

		$right_img_class='';

		$this->add_render_attribute(
			'wrapper',
			'class',
			[
				'ha-liquid-hover-wrapper',
				'ha-liquid-hover-'.$settings['age_gate_style'],
			]
		);

		if( $settings['age_gate_cookies_time'] != '0' ) {
			$this->add_render_attribute( 'wrapper', 'data-age_gate_cookies_time', $settings['age_gate_cookies_time']);
		}

		if( $settings["editor_mood"] != 'yes' ) {
			$this->add_render_attribute( 'wrapper', 'data-editor_mood', 'no' );
		}

		if(!empty($settings['age_gate_style']) && $settings['age_gate_style']=='confirm-dob'){
			$birthyears = !empty($settings['dob_limit']) ? $settings['dob_limit'] : '18';
			$this->add_render_attribute( 'wrapper', 'data-userbirth', $birthyears);
		}

		$right_img_class = !empty($settings['side_img']['url']) ? 'ha-liquid-hover-equ-width-50' : '';
		$this->add_render_attribute( 'box', 'class', ['ha-liquid-hover-boxes',$right_img_class]);

		if((\Elementor\Plugin::$instance->editor->is_edit_mode()) && $settings["editor_mood"] != 'yes') {
			printf(
				"<p>%s</p>",
				esc_html__( 'Liquid Hover:- This is just a placeholder & will not be shown on the live page.', 'happy-elementor-addons' )
			);
			return;
		}
		?>

		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div class="ha-liquid-hover-inner-wrapper">

				<div <?php echo $this->get_render_attribute_string( 'box' ); ?>>

					<div class="ha-liquid-hover-header">
						<?php if(!empty($settings['warning_message'])): ?>
							<div class="ha-liquid-hover-warning-msg"><?php echo $settings['warning_message'] ?></div>
						<?php endif; ?>

						<?php if( !empty($settings['header_img']['url']) ): ?>
							<?php if( !empty($settings['header_img']['id']) ): ?>
								<?php $image_url = wp_get_attachment_image_url( $settings['header_img']['id'], 'full' ); ?>
								<div class="ha-liquid-hover-image"><img src="<?php echo esc_url($image_url); ?>"></div>
							<?php else: ?>
								<div class="ha-liquid-hover-image"><img src="<?php echo esc_url($settings['header_img']['url']); ?>"></div>
							<?php endif; ?>
						<?php endif; ?>

						<?php if( !empty($settings['title']) ): ?>
							<div class="ha-liquid-hover-title"><?php echo esc_html($settings['title']); ?></div>
						<?php endif; ?>

						<?php if( !empty($settings['desc']) ): ?>
							<div class="ha-liquid-hover-description"><?php $this->print_unescaped_setting( 'desc' ); ?></div>
						<?php endif; ?>
					</div>

					<div class="ha-liquid-hover-form-body">
						<?php if( !empty($settings['age_gate_style']) ): ?>

							<?php if($settings['age_gate_style']=='confirm-age'): ?>
								<button type="submit" class="ha-liquid-hover-confirm-age-btn ha-liquid-hover-btn-ex">
									<?php
										if ( $settings['icon_position'] == 'before' && !empty($settings['button_icon']['value']) ) {
											Icons_Manager::render_icon( $settings["button_icon"], [ 'aria-hidden' => 'true' ]);
										}
										echo esc_html($settings['button_text']);
										if ( $settings['icon_position'] == 'after' && !empty($settings['button_icon']['value']) ) {
											Icons_Manager::render_icon( $settings["button_icon"], [ 'aria-hidden' => 'true' ]);
										}
									?>
								</button>
							<?php endif; ?>

							<?php if($settings['age_gate_style']=='confirm-dob'): ?>
								<input type="date" class="ha-liquid-hover-date-input" name="ha-liquid-hover-birth" value="<?php echo date('Y-m-d');?>" min="1900-01-01" max="2100-01-01">
								<button type="submit" class="ha-liquid-hover-confirm-dob-btn ha-liquid-hover-btn-ex">
									<?php
										if ( $settings['icon_position'] == 'before' && !empty($settings['button_icon']['value']) ) {
											Icons_Manager::render_icon( $settings["button_icon"], [ 'aria-hidden' => 'true' ]);
										}
										echo esc_html($settings['button_text']);
										if ( $settings['icon_position'] == 'after' && !empty($settings['button_icon']['value']) ) {
											Icons_Manager::render_icon( $settings["button_icon"], [ 'aria-hidden' => 'true' ]);
										}
									?>
								</button>
							<?php endif; ?>

							<?php if($settings['age_gate_style']=='confirm-by-boolean'): ?>
								<button type="submit" class="ha-liquid-hover-confirm-yes-btn ha-liquid-hover-btn-ex" name="ha-liquid-hover-confirm-yes-btn">
									<?php
										if ( $settings['icon_position'] == 'before' && !empty($settings['button_icon']['value']) ) {
											Icons_Manager::render_icon( $settings["button_icon"], [ 'aria-hidden' => 'true' ]);
										}
										echo esc_html($settings['button_text']);
										if ( $settings['icon_position'] == 'after' && !empty($settings['button_icon']['value']) ) {
											Icons_Manager::render_icon( $settings["button_icon"], [ 'aria-hidden' => 'true' ]);
										}
									?>
								</button>
								<button type="submit" class="ha-liquid-hover-confirm-no-btn ha-liquid-hover-btn-ex" name="ha-liquid-hover-confirm-no-btn">
									<?php
										if ( $settings['btn_two_icon_position'] == 'second-icon-before' && !empty($settings['btn_two_icon']['value']) ) {
											Icons_Manager::render_icon( $settings["second_button_icon"], [ 'aria-hidden' => 'true' ]);
										}
										echo esc_html($settings['btn_two_text']);
										if ( $settings['btn_two_icon_position'] == 'second-icon-after' && !empty($settings['btn_two_icon']['value']) ) {
											Icons_Manager::render_icon( $settings["second_button_icon"], [ 'aria-hidden' => 'true' ]);
										}
									?>
								</button>
							<?php endif; ?>

						<?php endif; ?>
					</div>

					<?php if( !empty($settings['footer_text']) ): ?>
						<div class="ha-liquid-hover-footer-text"><p><?php $this->print_unescaped_setting( 'footer_text' ); ?></p></div>
					<?php endif; ?>
				</div>

				<?php if( !empty($settings['side_img']['url']) ): ?>
					<div class="ha-liquid-hover-boxes ha-liquid-hover-side-image <?php echo esc_attr($right_img_class); ?>" style="background-image:url(<?php echo esc_url($settings['side_img']['url']); ?>);background-size:cover;   background-attachment:inherit;"></div>
				<?php endif; ?>

			</div>
		</div>

		<?php
	}

}
