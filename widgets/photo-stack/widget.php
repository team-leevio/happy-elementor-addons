<?php
/**
 * Photo Stack widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Utils;

defined('ABSPATH') || die();

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
        return __('Photo Stack', 'happy-elementor-addons');
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
        return ['photo', 'img-box', 'photo-gallery'];
    }

    /**
     * Register widget content controls
     */
    protected function register_content_controls() {
        $this->__photo_stack_content_controls();
        // $this->__photo_stack_animation_controls();
        // $this->__photo_stack_effect_controls();
        // $this->__photo_stack_container_controls();
    }

    protected function __photo_stack_content_controls() {
        $this->start_controls_section(
            '_section_photo_stack',
            [
                'label' => __('Image Stack', 'happy-elementor-addons'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'label'   => __('Image', 'happy-elementor-addons'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );
        $repeater->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'thumbnail',
                'default'   => 'medium',
                'separator' => 'before',
                'dynamic'   => [
                    'active' => true,
                ],
            ]
        );
        
        if (ha_has_pro() && !in_array('image-masking', get_option('happyaddons_inactive_features', []))) {
            $repeater->add_group_control(
                \Happy_Addons_Pro\Controls\Group_Control_Mask_Image::get_type(),
                [
                    'name'     => 'image_masking',
                    'selector' => '{{WRAPPER}} .ha-photo-stack-item{{CURRENT_ITEM}}',
                    // 'condition' => $args['condition'],
                ]
            );
        }
        $repeater->add_responsive_control(
			'image_offset_y',
			[
				'label' => __( 'Offset Y', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .ha-photo-stack-item{{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $repeater->add_responsive_control(
			'image_offset_x',
			[
				'label' => __( 'Offset X', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .ha-photo-stack-item{{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);


        $repeater->add_responsive_control(
            'image_z_index',
            [
                'label'     => esc_html__('Z-Index', 'happy-elementor-addons'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => -1000,
                'max'       => 1000,
                'step'      => 1,
                'selectors' => [
                    '{{WRAPPER}} .ha-photo-stack-item{{CURRENT_ITEM}}' => 'z-index: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        

        $this->add_control(
            'image_list',
            [
                'show_label'  => true,
                'label'       => __('Items', 'happy-elementor-addons'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ name }}}',
                'default'     => [
                    [
                        'image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'thumbnail_size'=> 'medium',
                        'image_offset_x'=>[
                            'size' => 20,
                            'unit' => 'px'
                        ],
                        'image_offset_y'=>[
                            'size' => 20,
                            'unit' => 'px'
                        ]
                    ],
                    [
                        'image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'thumbnail_size'=> 'thumbnail',
                        'image_offset_x'=>[
                            'size' => 200,
                            'unit' => 'px'
                        ],
                        'image_offset_y'=>[
                            'size' => 20,
                            'unit' => 'px'
                        ]
                    ],
                    [
                        'image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'thumbnail_size'=> 'thumbnail',
                        'image_offset_x'=>[
                            'size' => 600,
                            'unit' => 'px'
                        ],
                        'image_offset_y'=>[
                            'size' => 20,
                            'unit' => 'px'
                        ]
                    ],
                ],
            ]
        );

        $this->add_control(
            'image_infinite_animation',
            [
                'label'     => __('Infinite Animation', 'happy-elementor-addons'),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    ''                    => __('None', 'happy-elementor-addons'),
                    'ha-rotating'         => __('Rotating', 'happy-elementor-addons'),
                    'ha-rotating-inverse' => __('Rotating inverse', 'happy-elementor-addons'),
                    'ha-fade'             => __('Fade', 'happy-elementor-addons'),
                    'ha-bounce-sm'        => __('Bounce Small', 'happy-elementor-addons'),
                    'ha-bounce-md'        => __('Bounce Medium', 'happy-elementor-addons'),
                    'ha-bounce-lg'        => __('Bounce Large', 'happy-elementor-addons'),
                    'ha-scale-sm'         => __('Scale Small', 'happy-elementor-addons'),
                    'ha-scale-md'         => __('Scale Medium', 'happy-elementor-addons'),
                    'ha-scale-lg'         => __('Scale Large', 'happy-elementor-addons'),
                ],
                'default'   => '',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'hover_animation_style',
            [
                'label'     => __('Hover Animation', 'happy-elementor-addons'),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'none'             => __('None', 'happy-elementor-addons'),
                    'fly-sm'           => __('Fly Small', 'happy-elementor-addons'),
                    'fly'              => __('Fly Medium', 'happy-elementor-addons'),
                    'fly-lg'           => __('Fly Large', 'happy-elementor-addons'),
                    'scale-sm'         => __('Scale Small', 'happy-elementor-addons'),
                    'scale'            => __('Scale Medium', 'happy-elementor-addons'),
                    'scale-lg'         => __('Scale Large', 'happy-elementor-addons'),
                    'scale-inverse-sm' => __('Scale Inverse Small', 'happy-elementor-addons'),
                    'scale-inverse'    => __('Scale Inverse Medium', 'happy-elementor-addons'),
                    'scale-inverse-lg' => __('Scale Inverse Large', 'happy-elementor-addons'),
                ],
                'default'   => 'none',
                'separator' => 'before',
            ]
        );
        $this->add_control(
			'animation_speed',
			[
				'label' => __( 'Animation speed', 'happy-elementor-addons' ),
				'type' => Controls_Manager::NUMBER,
                'min' => 0,
				'max' => 100,
				'step' => 1,
				'default' => 5,
				'selectors' => [
					'{{WRAPPER}} .ha-photo-stack-wrapper' => '--animation_speed:{{SIZE}}s',
				],
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

    protected function __photo_stack_style_controls() {
        $this->start_controls_section(
            '_section_photo_stack_style',
            [
                'label' => __('Common', 'happy-elementor-addons'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_container_height',
            [
                'label'      => __('Minimum Height', 'happy-elementor-addons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh', 'em'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'size' => 300,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ha-photo-stack-wrapper' => 'min-height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_layers_overflow',
            [
                'label'     => __('Overflow', 'happy-elementor-addons'),
                'type'      => Controls_Manager::SELECT,
                'options'   => array(
                    'visible' => __('Visible', 'happy-elementor-addons'),
                    'hidden'  => __('Hidden', 'happy-elementor-addons'),
                    'scroll'  => __('Scroll', 'happy-elementor-addons'),
                ),
                'default'   => 'visible',
                'selectors' => array(
                    '{{WRAPPER}} .ha-photo-stack-wrapper' => 'overflow: {{VALUE}}',
                ),
                'separator' => 'after',
            ]
        );
        $this->start_controls_tabs( 'tabs_hover_style' );
        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __( 'Normal', 'happy-elementor-addons' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'box_shadow',
                'label'    => __('Box Shadow', 'happy-elementor-addons'),
                'selector' => '{{WRAPPER}} .ha-photo-stack-item img',

            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __( 'Hover', 'happy-elementor-addons' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'box_shadow_hover',
                'label'    => __('Box Shadow Hover', 'happy-elementor-addons'),
                'selector' => '{{WRAPPER}} .ha-photo-stack-item img:hover',

            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'box_shadow',
                'label'    => __('Hover Box Shadow', 'happy-elementor-addons'),
                'selector' => '{{WRAPPER}} .ha-photo-stack-item img:hover',

            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'border',
                'label'    => __('Border', 'happy-elementor-addons'),
                'selector' => '{{WRAPPER}} .ha-photo-stack-item img',
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label'      => __('Border Radius', 'happy-elementor-addons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .ha-photo-stack-item'     => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ha-photo-stack-item img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }
    /**
     * @return null
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        // print_r($settings['image_list']);
        if (empty($settings['image_list'])) {
            return;
        }

        ?>

        <div class="ha-photo-stack-wrapper">
			<?php foreach ($settings['image_list'] as $index => $item):
            // var_dump($item);
            $image         = wp_get_attachment_image_url($item['image']['id'], $item['thumbnail_size']);
            $repeater_key  = 'ha_ps_item' . $index;
            $dynamic_class = 'elementor-repeater-item-' . $item['_id'];
            $tag           = 'div';
            $this->add_render_attribute($repeater_key, 'class', 'ha-photo-stack-item');
            $this->add_render_attribute($repeater_key, 'class', $dynamic_class);
            $this->add_render_attribute($repeater_key, 'class', $settings['image_infinite_animation']);
            $this->add_render_attribute($repeater_key, 'class', $settings['hover_animation_style']);
            // $this->add_render_attribute($repeater_key . '_img', 'class', 'ha-photo-stack-img');
            // $this->add_render_attribute($repeater_key . '_img', 'class', $settings['_shadow_style'] );
            // $this->add_render_attribute($repeater_key . '_img', 'class', $settings['_shadow_hover_style'] );
            // $this->add_render_attribute($repeater_key, 'class', $settings['_hover_animation_style']);
            ?>
			<<?php echo $tag; ?> <?php $this->print_render_attribute_string($repeater_key);?>>
				<?php if ($image):
                    echo Group_Control_Image_Size::get_attachment_image_html($item, 'thumbnail', 'image');

                else:
                    echo $this->image_placeholder($item);
                endif;
                ?>
            </<?php echo $tag; ?>>

            <?php endforeach;?>
        </div>


		<?php    
}

    protected function image_placeholder($item){
        $width =  get_option($item['thumbnail_size'].'_size_w');
        $height =  get_option($item['thumbnail_size'].'_size_h');
        $height =  '0' == $height ? 'auto' : $height.'px';
        echo '<img src="'.$item['image']['url'].'" style="width: '.$width.'px; height: '.$height.';">';
    }

}
