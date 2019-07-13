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
		return __( 'Image Box', 'happy_addons' );
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
		return 'hm hm-testimonial';
	}

	public function get_keywords() {
		return [ 'info', 'blurb', 'box', 'text', 'content' ];
	}

	/**
	 * Register content related controls
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'image_box_image',
			[
				'label' => __( 'Backgroud', 'happy_addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

 
 		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'image_box_background',
				'label' => __( 'Image/Background', 'happy_addons' ),
				'types' => [ 'classic', 'gradient',  ],
				'selector' => '{{WRAPPER}} .ha-image-box-background',
			]
		);


		$this->add_control(
			'image_box_link',
			[
				'label' => __( 'Link', 'happy_addons' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'happy_addons' ),
				'label_block' => false,
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
				],
			]
		);

 
		$this->end_controls_section();

		$this->start_controls_section(
			'image_box_content',
			[
				'label' => __( 'Text', 'happy_addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'image_box_sub_title',
			[
				'label' => __( 'Sub Heading', 'happy_addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Default sub heading', 'happy_addons' ),
				'placeholder' => __( 'Type your sub title here', 'happy_addons' ),
			]
		);

		$this->add_control(
			'image_box_title',
			[
				'label' => __( 'Heading', 'happy_addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Default Heading', 'happy_addons' ),
				'placeholder' => __( 'Type your title here', 'happy_addons' ),
			]
		);

		$this->add_control(
			'image_box_description',
			[
				'label' => __( 'Description', 'happy_addons' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => __( 'Default description: Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.', 'happy_addons' ),
				'placeholder' => __( 'Type your description here', 'happy_addons' ),
			]
		);		


		$this->end_controls_section();
	}

	/**
	 * Register styles related controls
	 */
	protected function register_style_controls() {

		$this->start_controls_section(
			'image_box_background_style',
			[
				'label' => __( 'Background Style', 'happy_addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_control(
			'image_box_background_overlay_color',
			[
				'label' => __( 'Overlay Color', 'happy_addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-image-box-body .ha-image-box-inner' => 'background: {{VALUE}}',
					'{{WRAPPER}} .ha-image-box-body-reverse:hover .ha-image-box-inner' => 'background: {{VALUE}}',
				],
			]
		);


		$this->add_control(
			'image_box_background_padding',
			[
				'label' => __( 'Paddding', 'plugin-domain' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
								'top' => '250',
								'right' => '30',
								'bottom' => '70',
								'left' => '30',
								'unit' => 'px',
								'isLinked' => '',
						],
				'selectors' => [
					'{{WRAPPER}} .ha-image-box-body .ha-image-box-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);



		$this->end_controls_section();

		$this->start_controls_section(
			'image_box_sub_title_style',
			[
				'label' => __( 'Sub Heading Style', 'happy_addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

 
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'image_box_sub_title_typography',
				'label' => __( 'Typography', 'happy_addons' ),
				'selector' => '{{WRAPPER}} .ha-image-sub-title',
			]
		);

		$this->add_control(
			'image_box_sub_title_color',
			[
				'label' => __( 'Text Color', 'happy_addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-image-sub-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'image_box_sub_title_padding',
			[
				'label' => __( 'Padding', 'happy_addons' ),
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
				'label' => __( 'Border', 'happy_addons' ),
				'selector' => '{{WRAPPER}} .ha-image-sub-title',
			]
		);

		$this->add_control(
			'image_box_sub_title_border_radius',
			[
				'label' => __( 'Border radius', 'happy_addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ha-image-sub-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);	


		$this->end_controls_section();







		$this->start_controls_section(
			'image_box_title_style',
			[
				'label' => __( 'Heading Style', 'happy_addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

 
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'image_box_title_typography',
				'label' => __( 'Typography', 'happy_addons' ),
				'selector' => '{{WRAPPER}} .ha-image-title',
			]
		);

		$this->add_control(
			'image_box_title_color',
			[
				'label' => __( 'Text Color', 'happy_addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-image-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'image_box_title_padding',
			[
				'label' => __( 'Padding', 'happy_addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ha-image-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		
 


		$this->end_controls_section();






		$this->start_controls_section(
			'image_box_description_style',
			[
				'label' => __( 'Description Style', 'happy_addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

 
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'image_box_description_typography',
				'label' => __( 'Typography', 'happy_addons' ),
				'selector' => '{{WRAPPER}} .ha-image-description',
			]
		);

		$this->add_control(
			'image_box_description_color',
			[
				'label' => __( 'Text Color', 'happy_addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-image-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'image_box_description_padding',
			[
				'label' => __( 'Padding', 'happy_addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ha-image-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		
 


		$this->end_controls_section();

		$this->start_controls_section(
			'image_box_hover',
			[
				'label' => __( 'Text Hover Style', 'happy_addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_control(
			'image_box_hover_style',
			[
				'label' => __( 'Display Text on hover', 'happy_addons' ),
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
				'label' => __( 'Text animation', 'happy_addons' ),
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
			 <div class="ha-image-box-inner">
			 	<div class="ha-image-box-content">
			 		<h5 class="ha-image-sub-title"><?php  echo esc_html( $settings['image_box_sub_title'] ); ?></h5>
			 		<h1 class="ha-image-title"><?php  echo esc_html( $settings['image_box_title'] ); ?></h1>
			 		<div class="ha-image-description">
			 			 
			 				<?php  echo  $settings['image_box_description']; ?>
			 				
			 			 
			 		</div>
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
