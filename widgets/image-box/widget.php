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
				'label' => __( 'image', 'happy_addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

 
 		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'image_box_background',
				'label' => __( 'Image/Background', 'happy_addons' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .ha-image-box-background',
			]
		);


		$this->add_control(
			'image_box_link',
			[
				'label' => __( 'Link', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'plugin-domain' ),
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
				'label' => __( 'Title & Description', 'happy_addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'image_box_sub_title',
			[
				'label' => __( 'Sub Title', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Default sub title', 'plugin-domain' ),
				'placeholder' => __( 'Type your sub title here', 'plugin-domain' ),
			]
		);

		$this->add_control(
			'image_box_title',
			[
				'label' => __( 'Title', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Default title', 'plugin-domain' ),
				'placeholder' => __( 'Type your title here', 'plugin-domain' ),
			]
		);

		$this->add_control(
			'image_box_description',
			[
				'label' => __( 'Description', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __( 'Default description', 'plugin-domain' ),
				'placeholder' => __( 'Type your description here', 'plugin-domain' ),
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
				'label' => __( 'Overlay Color', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-image-box-inner' => 'background: {{VALUE}}',
				],
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'image_box_sub_title_style',
			[
				'label' => __( 'Sub Title Style', 'happy_addons' ),
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
				'label' => __( 'Padding', 'plugin-domain' ),
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
				'label' => __( 'Border', 'plugin-domain' ),
				'selector' => '{{WRAPPER}} .ha-image-sub-title',
			]
		);

		$this->add_control(
			'image_box_sub_title_border_radius',
			[
				'label' => __( 'Border radius', 'plugin-domain' ),
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
				'label' => __( 'Title Style', 'happy_addons' ),
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
				'label' => __( 'Padding', 'plugin-domain' ),
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
				'label' => __( 'Padding', 'plugin-domain' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ha-image-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		
 


		$this->end_controls_section();


 	


	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$target = $settings['image_box_link']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['image_box_link']['nofollow'] ? ' rel="nofollow"' : '';	

		?>
		  
		
		<?php // if((settings['image_box_link']['url'])): ?> 
			<a href="<?php  echo esc_url( $settings['image_box_link']['url'] ); ?>"  <?php echo esc_attr($target) ?>  <?php echo esc_attr($nofollow) ?>  >
			<?php // endif;?>
		<div class="ha-image-box-body">
		<div class="ha-image-box-background">
			 <div class="ha-image-box-inner">
			 	<div class="ha-image-box-content">
			 		<h5 class="ha-image-sub-title"><?php  echo esc_html( $settings['image_box_sub_title'] ); ?></h5>
			 		<h1 class="ha-image-title"><?php  echo esc_html( $settings['image_box_title'] ); ?></h1>
			 		<div class="ha-image-description">
			 			<p>
			 				<?php  echo esc_html( $settings['image_box_description'] ); ?>
			 				<?php print_r(settings['image_box_link']); ?>
			 			</p>
			 		</div>
			 	</div>
			 </div>
		</div>
		</div>
		<?php // if((settings['image_box_link']['url'])): ?> </a><?php // endif;?>
		<?php
	}


}
