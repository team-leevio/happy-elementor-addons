<?php
/**
 * Photo Stack widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Group_Control_Css_Filter;
use Elementor\Core\Schemes\Typography;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || die();

class Photo_Stack extends Base {

	

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Photo Stack', 'happy-elementor-addons' );
	}

	public function get_custom_help_url() {
		return 'https://happyaddons.com/docs/happy-addons-for-elementor/widgets/photo-stack/';
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
		return 'hm hm-card';
	}

	public function get_keywords() {
		return [ 'photo', 'img-box', 'photo-gallery' ];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		// $this->__image_badge_content_controls();
		$this->__photo_stack_content_controls();
		$this->__photo_stack_effect_controls();
	}

	protected function __photo_stack_content_controls(){
		$this->start_controls_section(
            '_section_photo_stack',
            [
                'label' => __( 'Content', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'label' => __( 'Image', 'happy-elementor-addons' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );
		$repeater->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'large',
                'separator' => 'before',
            ]
        );

		
		$repeater->add_responsive_control(
			'_offset_x',
			[
				'label' => esc_html__( 'Offset X', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => -200,
						'max' => 200,
					],
					'vw' => [
						'min' => -200,
						'max' => 200,
					],
					'vh' => [
						'min' => -200,
						'max' => 200,
					],
				],
				'default' => [
					'size' => '0',
				],
				'size_units' => [ 'px', '%', 'vw', 'vh' ],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .ha-photo-stack-item{{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .ha-photo-stack-item{{CURRENT_ITEM}}' => 'right: {{SIZE}}{{UNIT}}',
				],
				// 'condition' => [
				// 	'_offset_orientation_h!' => 'end',
				// 	'_position!' => '',
				// ],
			]
		);

		$repeater->add_responsive_control(
			'_offset_y',
			[
				'label' => esc_html__( 'Offset Y', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => -200,
						'max' => 200,
					],
					'vw' => [
						'min' => -200,
						'max' => 200,
					],
					'vh' => [
						'min' => -200,
						'max' => 200,
					],
				],
				'default' => [
					'size' => '0',
				],
				'size_units' => [ 'px', '%', 'vw', 'vh' ],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .ha-photo-stack-item{{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .ha-photo-stack-item{{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}}',
				],
				
			]
		);
		$repeater->add_responsive_control(
			'_ps_z_index',
			[
				'label' => esc_html__( 'Z-Index', 'happy-elementor-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 1000,
				'step' => 1,
				'selectors' => [
					'{{WRAPPER}} .ha-photo-stack-item{{CURRENT_ITEM}}' => 'z-index: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
		
        $this->add_control(
            'image_list',
            [
                'show_label' => false,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ name }}}',
                'default' => [
                    [
						'image' => [
							'url' => Utils::get_placeholder_image_src()
						],
					],
                ]
            ]
        );

		$this->end_controls_section();
	}
	protected function __photo_stack_effect_controls() {
		$this->start_controls_section(
            '_section_photo_stack_animation',
            [
                'label' => __( 'Aniamtion', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

		$this->add_control(
			'_ps_infinite_animation',
			[
				'label' => __( 'Infinite Animation', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					' ' => __( 'Description', 'happy-elementor-addons' ),
				],
				'default' => '',
			]
		);

		$this->end_controls_section();
	}

	/**
     * Register widget style controls
     */
	protected function register_style_controls() {
		$this->__photo_stack_style_controls();
	}

	protected function __photo_stack_style_controls(){
		$this->start_controls_section(
            '_section_photo_stack_style',
            [
                'label' => __( 'Image Style', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => __( 'Box Shadow', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-photo-stack-item',
			]
		);

		$this->end_controls_section();
	}
	protected function render() {
		$settings = $this->get_settings_for_display();
		// print_r($settings['image_list']);
		if(empty($settings['image_list'])){
			return;
		}

		?>
		<div class="ha-photo-stack-wrapper">
			<?php foreach ($settings['image_list'] as $index => $item) : 
                $image = wp_get_attachment_image_url( $item['image']['id'], $item['thumbnail_size'] );
				$repeater_key = 'ha_ps_item' . $index;
				$dynamic_class = 'elementor-repeater-item-' . $item['_id'];
                $tag = 'div';
                $this->add_render_attribute( $repeater_key, 'class', 'ha-photo-stack-item' );
                $this->add_render_attribute( $repeater_key, 'class', $dynamic_class );
                // $this->add_render_attribute( $repeater_key, 'style', $item['_offset_y'] );
				// print_r($item['thumbnail_size']);
				?>
				<<?php echo $tag; ?> <?php $this->print_render_attribute_string( $repeater_key ); ?>>
				<?php if ( $image ) :
                            
						echo Group_Control_Image_Size::get_attachment_image_html($item, 'thumbnail', 'image' );

                        else :
							printf( '<img class="ha-photo-stack-img" src="%s" alt="%s">',
							esc_attr( '' ),
							Utils::get_placeholder_image_src(),
							esc_attr( $item['name'] )
							);
					endif;
				?>
				</<?php echo $tag; ?>>

			<?php endforeach; ?>
		</div>


		<?php
	}

}
