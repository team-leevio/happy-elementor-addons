<?php
/**
 * Dual Button widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Utils;
use Elementor\Control_Media;
use Elementor\Icons_Manager;
use Elementor\Embed;
use Elementor\Group_Control_Css_Filter;

defined( 'ABSPATH' ) || die();

class Lightbox extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Lightbox', 'happy-elementor-addons' );
    }

	public function get_custom_help_url() {
		return 'https://happyaddons.com/docs/happy-addons-for-elementor/widgets/lightbox/';
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
        return 'hm hm-video-gallery';
    }

    public function get_keywords() {
        return [ 'button', 'btn', 'dual', 'advance', 'link' ];
    }

	/**
     * Register widget content controls
     */


	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->_section_lightbox();
	}

    protected function _section_lightbox() {

        $this->start_controls_section(
            '_section_lightbox',
            [
                'label' => __( 'Lightbox', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

		$this->add_control(
			'trigger_type',
			[
				'label' => __( 'Type', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'button' => __( 'Button', 'happy-elementor-addons' ),
					'image' => __( 'Image', 'happy-elementor-addons' ),
				],
				'default' => 'button',
			]
		);

		$this->add_control(
			'image',
			[
				'label' => __( 'Image', 'happy-elementor-addons' ),
				// 'show_label' => false,
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'trigger_type' => 'image'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => '_image',
				'default' => 'thumbnail',
				'separator' => 'none',
				'condition' => [
					'trigger_type' => 'image'
				],
			]
		);

		$this->add_control(
			'button',
			[
				'label' => __( 'Button', 'happy-elementor-addons' ),
				'label_block' => true,
				// 'show_label' => false,
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Button Text', 'happy-elementor-addons' ),
				'default' => __( 'Happy Addons', 'happy-elementor-addons' ),
				'condition' => [
					'trigger_type' => 'button',
				],
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_control(
			'button_icon',
			[
				'label' => esc_html__( 'Icon', 'happy-elementor-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				// 'exclude_inline_options' => [ 'svg' ],
                'condition' => [
					'trigger_type' => 'button',
				],
			]
		);

		$this->add_control(
            'icon_position', [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Icon Position', 'happy-elementor-addons'),
                'default' => 'after',
                'options' => [
                    'before' => esc_html__('Before', 'happy-elementor-addons'),
                     'after' => esc_html__('After', 'happy-elementor-addons'),
                ],
                'condition' => [
					'button!' => '',
					'trigger_type' => 'button',
                	'button_icon[value]!' => '',
				],
            ]
        );

		$this->add_control(
			'lightbox_type',
			[
				'label'       => __( 'Lightbox Type', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'toggle'      => false,
				'separator' => 'before',
				'default'     => 'video',
				'options'     => [
					'video'  => [
						'title' => __( 'Video', 'happy-elementor-addons' ),
						'icon'  => 'eicon-video-camera',//fa fa-font
					],
					'image'  => [
						'title' => __( 'Image', 'happy-elementor-addons' ),
						'icon'  => 'eicon-image-bold',
					],
				],
			]
		);

		$this->add_control(
			'lightbox_video_link',
			[
				'label' => esc_html__( 'Video Link', 'happy-elementor-addons' ),
				'description' => esc_html__( 'YouTube or Vimeo link', 'happy-elementor-addons' ),
				'type' => Controls_Manager::URL,
				'show_label' => false,
				'dynamic' => [
					'active' => false,
				],
				'default' => [
					'url' => 'https://www.youtube.com/watch?v=-GvB9xTNf_o',
				],
				'options' => false,
                'condition' => [
					'lightbox_type' => 'video',
				],
			]
		);

		$this->add_control(
			'lightbox_image_link',
			[
				'label' => __( 'Image', 'happy-elementor-addons' ),
				'show_label' => false,
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
                'condition' => [
					'lightbox_type' => 'image',
				],
			]
		);

        $this->end_controls_section();
    }

	/**
     * Register widget style controls
     */
    protected function register_style_controls() {
		$this->__btn_style_controls();
		$this->__image_style_controls();
	}

	protected function __btn_style_controls() {

		$this->start_controls_section(
			'_section_button_style',
			[
				'label' => __( 'Button', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'trigger_type' => 'button'
				],
			]
		);

		$this->add_control(
			'button_padding',
			[
				'label' => __( 'Padding', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ha-lightbox-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .ha-lightbox-btn',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ha-lightbox-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .ha-lightbox-btn',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => __( 'Typography', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-lightbox-btn',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
                'condition' => [
					'button!' => '',
				],
			]
		);

        $this->add_control(
            'button_icon_size',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Icon Size', 'happy-elementor-addons'),
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 200,
						'step' => 1,
					],
				],
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .ha-lightbox-btn i' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .ha-lightbox-btn svg' => 'height: {{SIZE}}{{UNIT}}',
				],
                'condition' => [
					'button_icon[value]!' => '',
				],
            ]
        );

		$this->add_responsive_control(
            'button_icon_space_left',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Space', 'happy-elementor-addons'),
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .ha-lightbox-btn i' => 'margin-left: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .ha-lightbox-btn svg' => 'margin-left: {{SIZE}}{{UNIT}}',
				],
                'condition' => [
					'button_icon[value]!' => '',
					'button!' => '',
					'icon_position' => 'after',
				],
            ]
        );

		$this->add_responsive_control(
            'button_icon_space_right',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Space', 'happy-elementor-addons'),
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .ha-lightbox-btn i' => 'margin-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .ha-lightbox-btn svg' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
                'condition' => [
					'button_icon[value]!' => '',
					'button!' => '',
					'icon_position' => 'before',
				],
            ]
        );

		$this->start_controls_tabs( '_tabs_button' );
		$this->start_controls_tab(
			'_tab_button_normal',
			[
				'label' => __( 'Normal', 'happy-elementor-addons' )
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' => __( 'Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-lightbox-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => __( 'Background Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-lightbox-btn' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_button_hover',
			[
				'label' => __( 'Hover', 'happy-elementor-addons' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => __( 'Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-lightbox-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_hover_background_color',
			[
				'label' => __( 'Background Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-lightbox-btn:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
            'button_hover_border',
            [
                'label' => __( 'Border Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                     'button_border_border!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-lightbox-btn:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function __image_style_controls() {

		$this->start_controls_section(
			'_section_image_style',
			[
				'label' => __( 'Image', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'trigger_type' => 'image'
				],
			]
		);

		$this->add_responsive_control(
			'trigger_image_width',
			[
				'label' => __( 'Width', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px','%' ],
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
					'{{WRAPPER}} .ha-lightbox-image img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_responsive_control(
			'trigger_image_height',
			[
				'label' => __( 'Height', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px','%' ],
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
					'{{WRAPPER}} .ha-lightbox-image img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'trigger_image_shadow',
				'label' => __( 'Box Shadow', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-lightbox-image img',
			]
		);


		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'trigger_image_border',
				'label' => __( 'Border', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-lightbox-image img',
			]
		);



		$this->add_responsive_control(
			'trigger_image_border_radius',
			[
				'label' => __( 'Border Radius', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ha-lightbox-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->start_controls_tabs( 'trigger_image_tabs');
		$this->start_controls_tab(
			'trigger_image_normal_tab',
			[
				'label' => __( 'Normal', 'happy-elementor-addons' ),
			]
		);

		$this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'trigger_image_css_filters',
                'selector' => '{{WRAPPER}} .ha-lightbox-image img',
            ]
        );

		$this->end_controls_tab();

		$this->start_controls_tab(
			'trigger_image_hover_tab',
			[
				'label' => __( 'Hover', 'happy-elementor-addons' ),
			]
		);

		$this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'trigger_image_hover_css_filters',
                'selector' => '{{WRAPPER}} .ha-lightbox-image img:hover',
            ]
        );

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function renderold() {
		$settings = $this->get_settings_for_display();
		$widget_id = $this->get_id();
		$trigger_type = ('image' == $settings['trigger_type'] ) ? 'ha-lightbox-image' : 'ha-lightbox-btn';

		$this->add_render_attribute(
			'anchor',
			[
				'class' => 'ha-lightbox-trigger ' . $trigger_type,
				'data-id' => $widget_id,
				// 'data-elementor-open-lightbox' => 'yes',
				// 'href' => '#',
			]
		);

		if( 'image' == $settings['trigger_type'] ) {
			$this->add_render_attribute(
				'image',
				[
					'data-id' => $widget_id,
					'src' => Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], '_image', $settings ) ? esc_url(Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], '_image', $settings )) : esc_url($settings['image']['url']),
					'title' => esc_attr(Control_Media::get_image_title( $settings['image'] )),
					'alt' => esc_attr(Control_Media::get_image_alt( $settings['image'] )),
				]
			);
		}

		if( 'image' == $settings['lightbox_type'] && $settings['lightbox_image_link']['url'] ) {
			$this->add_lightbox_data_attributes( 'anchor', $settings['lightbox_image_link']['id'], 'yes', $widget_id );
			$this->add_render_attribute(
				'anchor',
				[
					'href' => esc_url($settings['lightbox_image_link']['url']),
					'data-mfp-src' => esc_url($settings['lightbox_image_link']['url']),
				]
			);
		}
		elseif ( 'video' == $settings['lightbox_type'] && $settings['lightbox_video_link']['url'] ) {
			$this->add_lightbox_data_attributes( 'anchor', '', 'yes', $widget_id );
			$embed_url_params = [
				'autoplay' => 1,
				'rel' => 0,
				'controls' => 0,
			];

			$this->add_render_attribute(
				'anchor',
				[
					'data-elementor-lightbox-video' => Embed::get_embed_url( $settings['lightbox_video_link']['url'], $embed_url_params ),
					'data-id' => $widget_id,
					'href' => $settings['lightbox_video_link']['url'] ? esc_url($settings['lightbox_video_link']['url']) : '#',
				]
			);
		}

		?>
		<a <?php echo $this->get_render_attribute_string( 'anchor' );?>>
			<?php if( 'button' == $settings['trigger_type'] ) : ?>
			<?php
				if ( 'before' == $settings['icon_position'] && !empty($settings['button_icon']['value']) ) {
					Icons_Manager::render_icon( $settings["button_icon"], [ 'aria-hidden' => 'true' ]);
				}
				echo esc_html( $settings['button'] );
				if ( 'after' == $settings['icon_position'] && !empty($settings['button_icon']['value']) ) {
					Icons_Manager::render_icon( $settings["button_icon"], [ 'aria-hidden' => 'true' ]);
				}
			?>
			<?php elseif( 'image' == $settings['trigger_type'] ): ?>
				<img <?php echo $this->get_render_attribute_string( 'image' )?> />
			<?php endif; ?>
		</a>
		<?php
	}

	protected function renderOld2() {
		$settings = $this->get_settings_for_display();
		$widget_id = $this->get_id();
		$trigger_type = ('image' == $settings['trigger_type'] ) ? 'ha-lightbox-image' : 'ha-lightbox-btn';

		$this->add_render_attribute(
			'anchor',
			[
				'class' => 'ha-lightbox-trigger ' . $trigger_type,
				// 'data-id' => $widget_id,
				// 'data-elementor-open-lightbox' => 'yes',
				// 'href' => '#',
			]
		);

		if( 'image' == $settings['trigger_type'] ) {
			$this->add_render_attribute(
				'image',
				[
					'data-id' => $widget_id,
					'src' => Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], '_image', $settings ) ? esc_url(Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], '_image', $settings )) : esc_url($settings['image']['url']),
					'title' => esc_attr(Control_Media::get_image_title( $settings['image'] )),
					'alt' => esc_attr(Control_Media::get_image_alt( $settings['image'] )),
				]
			);
		}

		if( 'image' == $settings['lightbox_type'] && $settings['lightbox_image_link']['url'] ) {
			$this->add_lightbox_data_attributes( 'anchor', $settings['lightbox_image_link']['id'], 'yes', $widget_id );
			$this->add_render_attribute(
				'anchor',
				[
					'href' => esc_url($settings['lightbox_image_link']['url']),
					'data-mfp-src' => esc_url($settings['lightbox_image_link']['url']),
				]
			);
		}
		elseif ( 'video' == $settings['lightbox_type'] && $settings['lightbox_video_link']['url'] ) {
			// $this->add_lightbox_data_attributes( 'anchor', '', 'yes', $widget_id );
			$embed_url_params = [
				'autoplay' => 1,
				'rel' => 0,
				'controls' => 1,
			];

			// $this->add_render_attribute(
			// 	'anchor',
			// 	[
			// 		'data-elementor-lightbox-video' => Embed::get_embed_url( $settings['lightbox_video_link']['url'], $embed_url_params ),
			// 		'data-id' => $widget_id,
			// 		'href' => $settings['lightbox_video_link']['url'] ? esc_url($settings['lightbox_video_link']['url']) : '#',
			// 	]
			// );

			// https://vimeo.com/943423282
			// https://www.youtube.com/watch?v=aiDYo6sBBWs
			// http://localhost/happy-test/wp-content/uploads/2022/09/iceberg.mp4
			// http://localhost/happy-test/wp-content/uploads/2021/11/mixkit-bubbling-water-in-slow-motion-182-large.mp4
			// http://localhost/happy-test/wp-content/uploads/2024/05/COWS_AT_THE_GRASS.mp4

			$settings['lightbox_video_link']['url'] = 'https://vimeo.com/943423282';

			$lightbox_options = [
				'type' => 'video',
				'videoType' => 'vimeo',
				'url' => Embed::get_embed_url( $settings['lightbox_video_link']['url'], $embed_url_params,[] ),
				// 'url' => 'http://localhost/happy-test/wp-content/uploads/2024/05/COWS_AT_THE_GRASS.mp4',
				'modalOptions' => [
					'id' => 'elementor-lightbox-' . $this->get_id(),
					// 'entranceAnimation' => $settings['lightbox_content_animation'],
					// 'entranceAnimation_tablet' => $settings['lightbox_content_animation_tablet'],
					// 'entranceAnimation_mobile' => $settings['lightbox_content_animation_mobile'],
					// 'videoAspectRatio' => $settings['aspect_ratio'],
				],
			];

			// if ( 'hosted' === $settings['video_type'] ) {
				// $lightbox_options['videoParams'] = $this->get_hosted_params();
			// }

			$video_settings = $this->get_video_settings( $settings['lightbox_video_link']['url'] );

			$this->add_render_attribute( 'anchor', [
				'data-elementor-open-lightbox' => 'yes',
				'data-elementor-lightbox-video' => Embed::get_embed_url( $settings['lightbox_video_link']['url'], $embed_url_params ),
				// 'data-elementor-lightbox-video' => 'http://localhost/happy-test/wp-content/uploads/2024/05/COWS_AT_THE_GRASS.mp4',
				'data-elementor-lightbox' => wp_json_encode( $lightbox_options ),
				'data-e-action-hash' => \Elementor\Plugin::instance()->frontend->create_action_hash( 'lightbox', $lightbox_options ),
			] );


		}

		?>
		<a <?php echo $this->get_render_attribute_string( 'anchor' );?>>
			<?php if( 'button' == $settings['trigger_type'] ) : ?>
			<?php
				if ( 'before' == $settings['icon_position'] && !empty($settings['button_icon']['value']) ) {
					Icons_Manager::render_icon( $settings["button_icon"], [ 'aria-hidden' => 'true' ]);
				}
				echo esc_html( $settings['button'] );
				if ( 'after' == $settings['icon_position'] && !empty($settings['button_icon']['value']) ) {
					Icons_Manager::render_icon( $settings["button_icon"], [ 'aria-hidden' => 'true' ]);
				}
			?>
			<?php elseif( 'image' == $settings['trigger_type'] ): ?>
				<img <?php echo $this->get_render_attribute_string( 'image' )?> />
			<?php endif; ?>
		</a>
		<?php
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$widget_id = $this->get_id();
		$trigger_type = ('image' == $settings['trigger_type'] ) ? 'ha-lightbox-image' : 'ha-lightbox-btn';

		$this->add_render_attribute(
			'anchor',
			[
				'class' => 'ha-lightbox-trigger ' . $trigger_type,
			]
		);

		if( 'image' == $settings['trigger_type'] ) {
			$this->add_render_attribute(
				'image',
				[
					'data-id' => $widget_id,
					'src' => Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], '_image', $settings ) ? esc_url(Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], '_image', $settings )) : esc_url($settings['image']['url']),
					'title' => esc_attr(Control_Media::get_image_title( $settings['image'] )),
					'alt' => esc_attr(Control_Media::get_image_alt( $settings['image'] )),
				]
			);
		}

		if( 'image' == $settings['lightbox_type'] && $settings['lightbox_image_link']['url'] ) {
			$this->add_lightbox_data_attributes( 'anchor', $settings['lightbox_image_link']['id'], 'yes', $widget_id );
			$this->add_render_attribute(
				'anchor',
				[
					'href' => esc_url($settings['lightbox_image_link']['url']),
					'data-mfp-src' => esc_url($settings['lightbox_image_link']['url']),
				]
			);
		}
		elseif ( 'video' == $settings['lightbox_type'] && $settings['lightbox_video_link']['url'] ) {
			// https://vimeo.com/235215203
			// https://vimeo.com/943423282
			// https://www.youtube.com/watch?v=aiDYo6sBBWs
			// http://localhost/happy-test/wp-content/uploads/2022/09/iceberg.mp4
			// http://localhost/happy-test/wp-content/uploads/2021/11/mixkit-bubbling-water-in-slow-motion-182-large.mp4
			// http://localhost/happy-test/wp-content/uploads/2024/05/COWS_AT_THE_GRASS.mp4

			$video_settings = $this->get_video_settings( $settings['lightbox_video_link']['url'] );
			$video_settings['modalOptions'] = [ 'id' => 'elementor-lightbox-' . $this->get_id() ];

			if( 'hosted' == $video_settings['videoType'] ) {
				$lightbox_url = $settings['lightbox_video_link']['url'];
			} else {
				$embed_url_params = [
					// 'autoplay' => 1,
					'rel' => 0,
					'controls' => 1,
				];
				$lightbox_url = Embed::get_embed_url( $settings['lightbox_video_link']['url'], $embed_url_params );
			}

			$this->add_render_attribute( 'anchor', [
				'href' => '#',
				'data-elementor-open-lightbox' => 'yes',
				'data-elementor-lightbox-video' => $lightbox_url,
				'data-elementor-lightbox' => wp_json_encode( $video_settings ),
				'data-e-action-hash' => \Elementor\Plugin::instance()->frontend->create_action_hash( 'lightbox', $video_settings ),
			] );

		}

		?>
		<a <?php echo $this->get_render_attribute_string( 'anchor' );?>>
			<?php if( 'button' == $settings['trigger_type'] ) : ?>
			<?php
				if ( 'before' == $settings['icon_position'] && !empty($settings['button_icon']['value']) ) {
					Icons_Manager::render_icon( $settings["button_icon"], [ 'aria-hidden' => 'true' ]);
				}
				echo esc_html( $settings['button'] );
				if ( 'after' == $settings['icon_position'] && !empty($settings['button_icon']['value']) ) {
					Icons_Manager::render_icon( $settings["button_icon"], [ 'aria-hidden' => 'true' ]);
				}
			?>
			<?php elseif( 'image' == $settings['trigger_type'] ): ?>
				<img <?php echo $this->get_render_attribute_string( 'image' )?> />
			<?php endif; ?>
		</a>
		<?php
	}

	private function get_video_settings( $video_link ) {
		$video_properties = Embed::get_video_properties( $video_link );
		$video_url = null;
		$video_settings = [
			'type' => 'video'
		];
		if ( ! $video_properties ) {
			$video_type = 'hosted';
			$video_url = $video_link;
			$video_settings['videoParams'] = [
				'controls' => 'yes',
				'preload' => 'metadata',
				'muted' => 'muted',
				'controlsList' => 'nodownload',
			];
		} else {
			$embed_url_params = [
				// 'autoplay' => 1,
				'rel' => 0,
				'controls' => 1,
			];
			$video_type = $video_properties['provider'];
			$video_url = Embed::get_embed_url( $video_link, $embed_url_params );
		}

		if ( null === $video_url ) {
			return '';
		}

		$video_settings['videoType'] = $video_type;
		$video_settings['url'] = $video_url;

		return $video_settings;
	}

	private function get_hosted_params() {
		$settings = $this->get_settings_for_display();

		$video_params = [];

		// foreach ( [ 'autoplay', 'loop', 'controls' ] as $option_name ) {
		// 	if ( $settings[ $option_name ] ) {
		// 		$video_params[ $option_name ] = '';
		// 	}
		// }

		$video_params['controls'] = 'yes';
		// if ( $settings['preload'] ) {
			$video_params['preload'] = 'metadata';
		// }

		// if ( $settings['mute'] ) {
			$video_params['muted'] = 'muted';
		// }

		// if ( $settings['play_on_mobile'] ) {
			$video_params['playsinline'] = '';
		// }

		// if ( ! $settings['download_button'] ) {
			$video_params['controlsList'] = 'nodownload';
		// }

		// if ( $settings['poster']['url'] ) {
		// 	$video_params['poster'] = $settings['poster']['url'];
		// }

		return $video_params;
	}
}
