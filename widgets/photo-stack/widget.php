<?php
/**
 * Photo Stack widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
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
        // $this->__image_badge_content_controls();
        $this->__photo_stack_content_controls();
        $this->__photo_stack_animation_controls();
        $this->__photo_stack_effect_controls();
        $this->__photo_stack_container_controls();
    }

    protected function __photo_stack_content_controls() {
        $this->start_controls_section(
            '_section_photo_stack',
            [
                'label' => __('Content', 'happy-elementor-addons'),
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
                // 'dynamic' => [
                //     'active' => true,
                // ],
            ]
        );
        $repeater->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'thumbnail',
                'default'   => 'thumbnail',
                'separator' => 'before',
            ]
        );

        $repeater->add_responsive_control(
            '_offset_y',
            [
                'label'      => esc_html__('Offset Y', 'happy-elementor-addons'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'default'    => [
                    'size' => '0',
                ],
                'size_units' => ['px', '%'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .ha-photo-stack-item{{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}}',
                    'body.rtl {{WRAPPER}} .ha-photo-stack-item{{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}}',
                ],

            ]
        );

        $repeater->add_responsive_control(
            '_offset_x',
            [
                'label'      => esc_html__('Offset X', 'happy-elementor-addons'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'default'    => [
                    'size' => '0',
                ],
                'size_units' => ['px', '%'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .ha-photo-stack-item{{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}}',
                    'body.rtl {{WRAPPER}} .ha-photo-stack-item{{CURRENT_ITEM}}' => 'right: {{SIZE}}{{UNIT}}',
                ],

            ]
        );
        $repeater->add_responsive_control(
            '_ps_z_index',
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
                'show_label'  => false,
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ name }}}',
                'default'     => [
                    [
                        'image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                    ],
                ],
            ]
        );

        $this->end_controls_section();
    }
    protected function __photo_stack_animation_controls() {
        $this->start_controls_section(
            '_section_photo_stack_animation',
            [
                'label' => __('Aniamtion', 'happy-elementor-addons'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            '_ps_infinite_animation',
            [
                'label'   => __('Infinite Animation', 'happy-elementor-addons'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    ''                    => __('None', 'happy-elementor-addons'),
                    'ha-rotating'         => __('Rotating', 'happy-elementor-addons'),
                    'ha-rotating-inverse' => __('rotating-inverse', 'happy-elementor-addons'),
                    'ha-fade'             => __('Fade', 'happy-elementor-addons'),
                    'ha-bounce-sm'        => __('Bounce Small', 'happy-elementor-addons'),
                    'ha-bounce-md'        => __('Bounce Medium', 'happy-elementor-addons'),
                    'ha-bounce-lg'        => __('Bounce Large', 'happy-elementor-addons'),
                    'ha-scale-sm'         => __('Scale Small', 'happy-elementor-addons'),
                    'ha-scale-md'         => __('Scale Medium', 'happy-elementor-addons'),
                    'ha-scale-lg'         => __('Scale Large', 'happy-elementor-addons'),
                ],
                'default' => '',
            ]
        );

        $this->add_control(
            '_ps_animation_speed',
            [
                'label'   => __('Infinite Animation Speed', 'happy-elementor-addons'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'ha-duration-fast' => __('Fast', 'happy-elementor-addons'),
                    'ha-duration-md'   => __('Medium', 'happy-elementor-addons'),
                    'ha-duration-sl'   => __('Slow', 'happy-elementor-addons'),
                ],
                'default' => 'ha-duration-md',
            ]
        );

        $this->end_controls_section();
    }

    protected function __photo_stack_effect_controls() {
        $this->start_controls_section(
            '_section_photo_stack_effect',
            [
                'label' => __('Effects', 'happy-elementor-addons'),
                'tab'   => Controls_Manager::SECTION,
            ]
        );
        $this->add_control(
            '_shadow_style',
            [
                'label'   => __('Shadow Style', 'happy-elementor-addons'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    "shadow-0"  => "Default",
                    "shadow-sm" => "Small shadow",
                    "shadow" => "Medium shadow",
                    "shadow-lg" => "Large shadow",
                    "shadow-inverse-sm" => "Inverse Small shadow",
                    "shadow-inverse" => "Inverse Medium shadow",
                    "shadow-inverse-lg" => "Inverse Large shadow",
                ],
                'default' => 'shadow-0',
            ]
        );

        $this->add_control(
            '_shadow_hover_style',
            [
                'label'   => __('Shadow Hover Style', 'happy-elementor-addons'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    "shadow-0"  => __('Default', 'happy-elementor-addons'),
                    "shadow-hover-sm" => __('Small Hover shadow', 'happy-elementor-addons'),
                    "shadow-hover" => __('Medium Hover shadow', 'happy-elementor-addons'),
                    "shadow-hover-lg" => __('Large Hover shadow', 'happy-elementor-addons'),
                    "shadow-inverse-sm" => __('Inverse Small Hover shadow', 'happy-elementor-addons'),
                    "shadow-inverse" => __('Inverse Medium Hover shadow', 'happy-elementor-addons'),
                    "shadow-inverse-sm" => __('Inverse Large Hover shadow', 'happy-elementor-addons'),
                ],
                'default' => 'shadow-0',
            ]
        );

        $this->add_control(
            '_hover_animation_style',
            [
                'label'   => __('Hover Animation', 'happy-elementor-addons'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'none' => __('Default', 'happy-elementor-addons'),
                    'fly-sm' => __('Fly Small', 'happy-elementor-addons'),
                    'fly' => __('Fly Medium', 'happy-elementor-addons'),
                    'fly-lg' => __('Fly Large', 'happy-elementor-addons'),
                    'scale-sm' => __('Scale Small', 'happy-elementor-addons'),
                    'scale' => __('Scale Medium', 'happy-elementor-addons'),
                    'scale-lg' => __('Scale Large', 'happy-elementor-addons'),
                    'scale-inverse-sm' => __('Scale Inverse Small', 'happy-elementor-addons'),
                    'scale-inverse' => __('Scale Inverse Medium', 'happy-elementor-addons'),
                    'scale-inverse-lg' => __('Scale Inverse Large', 'happy-elementor-addons'),
                ],
                'default' => 'none',
            ]
        );

        $this->end_controls_section();
    }

    protected function __photo_stack_container_controls(){
        $this->start_controls_section(
            '_section_photo_stack_container',
            [
                'label' => __('Container', 'happy-elementor-addons'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
			'_container_height',
			[
				'label' => __( 'Minimum Height', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh', 'em'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    'em'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'vh'  => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'default'    => [
                    'size' => '0',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-photo-stack-wrapper' => 'min-height: {{SIZE}}{{UNIT}}',
                ],
			]
		);

        $this->add_responsive_control(
			'_img_layers_overflow',
			array(
				'label'     => __( 'Overflow', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'auto'    => __( 'Auto', 'happy-elementor-addons' ),
					'visible' => __( 'Visible', 'happy-elementor-addons' ),
					'hidden'  => __( 'Hidden', 'happy-elementor-addons' ),
					'scroll'  => __( 'Scroll', 'happy-elementor-addons' ),
				),
				'default'   => 'visible',
				'selectors' => array(
					'{{WRAPPER}} .ha-photo-stack-wrapper'   => 'overflow: {{VALUE}}',
				),
			)
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
                'label' => __('Image Style', 'happy-elementor-addons'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // $this->add_group_control(
        //     Group_Control_Box_Shadow::get_type(),
        //     [
        //         'name'     => 'box_shadow',
        //         'label'    => __('Box Shadow', 'happy-elementor-addons'),
        //         'selector' => '{{WRAPPER}} .ha-photo-stack-item',
        //     ]
        // );
        $this->add_responsive_control(
            '_img_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
					'{{WRAPPER}} .ha-photo-stack-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
            $image         = wp_get_attachment_image_url($item['image']['id'], $item['thumbnail_size']);
            $repeater_key  = 'ha_ps_item' . $index;
            $dynamic_class = 'elementor-repeater-item-' . $item['_id'];
            $tag           = 'div';
            $this->add_render_attribute($repeater_key, 'class', 'ha-photo-stack-item');
            $this->add_render_attribute($repeater_key, 'class', $dynamic_class);
            $this->add_render_attribute($repeater_key, 'class', $settings['_ps_infinite_animation']);
            $this->add_render_attribute($repeater_key, 'class', $settings['_ps_animation_speed']);
            $this->add_render_attribute( $repeater_key, 'class', $settings['_shadow_style'] );
            $this->add_render_attribute( $repeater_key, 'class', $settings['_shadow_hover_style'] );
            $this->add_render_attribute( $repeater_key, 'class', $settings['_hover_animation_style'] );
            ?>
			<<?php echo $tag; ?> <?php $this->print_render_attribute_string($repeater_key);?>>
				<?php if ($image):

                	echo Group_Control_Image_Size::get_attachment_image_html($item, 'thumbnail', 'image');

            	else:
                // printf('<img class="ha-photo-stack-img" src="%s" alt="%s">',
                //     esc_attr(''),
                //     Utils::get_placeholder_image_src(),
                //     esc_attr($item['name'])
                // );
					echo Group_Control_Image_Size::get_attachment_image_html($item, 'thumbnail', 'image');
            	endif;
            ?>
			</<?php echo $tag; ?>>

			<?php endforeach;?>
        </div>


		<?php
}

}
