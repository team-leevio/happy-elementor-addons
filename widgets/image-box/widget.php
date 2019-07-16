<?php
/**
 * Blurb widget class
 *
 * @package happy_addons
 */
namespace happy_addons\Elementor\Widget;

use Elementor\Utils;
use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;
use happy_addons\Elementor\Controls\Group_Control_Foreground;

defined( 'ABSPATH' ) || die();

class Image_Box extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Image Box', 'happy-elementor-addons' );
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
		return [ 'info', 'blurb', 'box', 'text', 'content' ];
	}

	/**
	 * Register content related controls
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'_section_image_box_image',
			[
				'label' => __( 'Backgroud', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

 
 		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'image_box_background',
				'label' => __( 'Image/Background', 'happy-elementor-addons' ),
				'types' => [ 'classic', 'gradient',  ],
				'selector' => '{{WRAPPER}} .ha-image-box-background',
			]
		);


		$this->add_control( 
			'image_box_link',
			[
				'label' => __( 'Link', 'happy-elementor-addons' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'happy-elementor-addons' ),
				'label_block' => false,
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
				],
				'dynamic' => [
					'active' => true,
				],				
			]
		);

 
		$this->end_controls_section();

		$this->start_controls_section(
			'image_box_content',
			[
				'label' => __( 'Text', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'_heading_image_box_sub_title',
			[
				'label' => __( 'Sub Heading', 'happy-elementor-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Default sub heading', 'happy-elementor-addons' ),
				'placeholder' => __( 'Type your sub title here', 'happy-elementor-addons' ),
			]
		);

		$this->add_control(
			'_heading_image_box_title',
			[
				'label' => __( 'Heading', 'happy-elementor-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Default Heading', 'happy-elementor-addons' ),
				'placeholder' => __( 'Type your title here', 'happy-elementor-addons' ),
			]
		);

		$this->add_control(
			'image_box_description',
			[
				'label' => __( 'Description', 'happy-elementor-addons' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => __( 'Default description: Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.', 'happy-elementor-addons' ),
				'placeholder' => __( 'Type your description here', 'happy-elementor-addons' ),
			]
		);		


		$this->end_controls_section();
	}

	/**
	 * Register styles related controls
	 */
	protected function register_style_controls() {

		$this->start_controls_section(
			'_section_style_image_box_background_style',
			[
				'label' => __( 'Box Style', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);



		$this->add_responsive_control(
			'image_box_background_height',
			[
				'label' => __( 'Box Height', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 400,
				],
				'selectors' => [
					'{{WRAPPER}} .ha-image-box-background' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);		



		$this->end_controls_section();

		$this->start_controls_section(
			'_section_style_image_box_overlay_style',
			[
				'label' => __( 'Background Overlay', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);


 		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'image_box_overlay_color',
				'label' => __( 'Overlay Type', 'happy-elementor-addons' ),
				'types' => [ 'classic', 'gradient',  ],
				'selector' => '{{WRAPPER}}  .ha-image-box-overlay',
			]
		);		

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_style_image_box_text_style',
			[
				'label' => __( 'Content Style', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_box_content_padding',
			[
				'label' => __( 'Paddding', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
								'top' => '150',
								'right' => '30',
								'bottom' => '70',
								'left' => '30',
								'unit' => 'px',
								'isLinked' => '',
						],
				'selectors' => [
					'{{WRAPPER}} .ha-image-box-body .ha-image-box-content ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ha-image-box-body-reverse .ha-image-box-content ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_style_image_box_sub_title_style',
			[
				'label' => __( 'Sub Heading Style', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

 
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'image_box_sub_title_typography',
				'label' => __( 'Typography', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-image-sub-title',
			]
		);

		$this->add_control(
			'image_box_sub_title_color',
			[
				'label' => __( 'Text Color', 'happy-elementor-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-image-sub-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'image_box_sub_title_padding',
			[
				'label' => __( 'Padding', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ha-image-sub-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_box_sub_title_border',
				'label' => __( 'Border', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-image-sub-title',
			]
		);

		$this->add_control(
			'image_box_sub_title_border_radius',
			[
				'label' => __( 'Border radius', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ha-image-sub-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);	


		$this->end_controls_section();







		$this->start_controls_section(
			'_section_style_image_box_title_style',
			[
				'label' => __( 'Heading Style', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

 
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'image_box_title_typography',
				'label' => __( 'Typography', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-image-title',
			]
		);

		$this->add_control(
			'image_box_title_color',
			[
				'label' => __( 'Text Color', 'happy-elementor-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-image-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'image_box_title_padding',
			[
				'label' => __( 'Padding', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ha-image-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		
 


		$this->end_controls_section();






		$this->start_controls_section(
			'_section_style_image_box_description_style',
			[
				'label' => __( 'Description Style', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

 
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'image_box_description_typography',
				'label' => __( 'Typography', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-image-description',
			]
		);

		$this->add_control(
			'image_box_description_color',
			[
				'label' => __( 'Text Color', 'happy-elementor-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-image-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'image_box_description_padding',
			[
				'label' => __( 'Padding', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ha-image-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		
 


		$this->end_controls_section();

		$this->start_controls_section(
			'_section_style_image_box_hover',
			[
				'label' => __( 'Text Hover Style', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_control(
			'image_box_hover_style',
			[
				'label' => __( 'Display Text on hover', 'happy-elementor-addons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'your-plugin' ),
				'label_off' => __( 'Off', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);


		$this->add_control(
			'image_box_hover_style_animation',
			[
				'label' => __( 'Text animation', 'happy-elementor-addons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'your-plugin' ),
				'label_off' => __( 'Off', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);		


 


		$this->end_controls_section();


 	


	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$target = $settings['image_box_link']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['image_box_link']['nofollow'] ? ' rel="nofollow"' : '';	

		?>
		  


		
		<?php  if($settings['image_box_link']['url']): ?> 
			<a href="<?php  echo esc_url( $settings['image_box_link']['url'] ); ?>"  <?php echo esc_attr($target) ?>  <?php echo esc_attr($nofollow) ?>  >
			<?php  endif;?>
		<div class="<?php if ( 'yes' === $settings['image_box_hover_style'] ) { echo 'ha-image-box-body-reverse'; } else { echo 'ha-image-box-body'; } ?>  <?php if ( 'yes' === $settings['image_box_hover_style_animation'] ) { echo ''; } else { echo 'ha-image-box-body-animation-off'; } ?> ">

		<div class="ha-image-box-background">
			 <div class="ha-image-box-overlay"></div>
			 	<div class="ha-image-box-content">
			 		<h5 class="ha-image-sub-title"><?php  echo esc_html( $settings['_heading_image_box_sub_title'] ); ?></h5>
			 		<h1 class="ha-image-title"><?php  echo esc_html( $settings['_heading_image_box_title'] ); ?></h1>
			 		<div class="ha-image-description">
			 			 
			 				<?php  echo  $settings['image_box_description']; ?>
			 				
			 			 
			 		</div>
			 	</div>
			 
		</div>
		</div>
		<?php  if($settings['image_box_link']['url']): ?> 
			</a>
		<?php  endif;?>
		<?php
	}


}
