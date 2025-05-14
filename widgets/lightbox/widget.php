<?php
/**
 * Lightbox widget class
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
use Elementor\Modules\DynamicTags\Module as TagsModule;

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
        return [ 'lightbox', 'light', 'box', 'video', 'link', 'button' ];
    }

	protected function is_dynamic_content(): bool {
		return false;
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

		$this->add_control(
            'video_type', [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Source', 'happy-elementor-addons'),
				'default'     => 'youtube',
                'options' => [
                    'youtube' => esc_html__('YouTube', 'happy-elementor-addons'),
                     'vimeo' => esc_html__('Vimeo', 'happy-elementor-addons'),
                     'hosted' => esc_html__('Self Hosted', 'happy-elementor-addons'),
                ],
                'condition' => [
					'lightbox_type' => 'video',
				],
            ]
        );

		$this->add_control(
			'youtube_link',
			[
				'label' => esc_html__( 'Link', 'happy-elementor-addons' ),
				'type' => Controls_Manager::URL,
				'show_label' => true,
				'dynamic' => [
					'active' => false,
				],
				'default' => [
					'url' => 'https://www.youtube.com/watch?v=3U67Cw2YoeQ',
				],
				'options' => false,
                'condition' => [
					'lightbox_type' => 'video',
					'video_type' => 'youtube',
				],
			]
		);

		$this->add_control(
			'vimeo_link',
			[
				'label' => esc_html__( 'Link', 'happy-elementor-addons' ),
				'type' => Controls_Manager::URL,
				'show_label' => true,
				'dynamic' => [
					'active' => false,
				],
				'default' => [
					'url' => 'https://vimeo.com/235215203',
				],
				'options' => false,
                'condition' => [
					'lightbox_type' => 'video',
					'video_type' => 'vimeo',
				],
			]
		);

		$this->add_control(
			'hosted_link',
			[
				'label' => esc_html__( 'Choose Video File', 'happy-elementor-addons' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::MEDIA_CATEGORY,
					],
				],
				'media_types' => [
					'video',
				],
                'condition' => [
					'lightbox_type' => 'video',
					'video_type' => 'hosted',
				],
			]
		);

		$this->add_control(
			'start',
			[
				'label' => esc_html__( 'Start Time', 'happy-elementor-addons' ),
				'type' => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Specify a start time (in seconds)', 'happy-elementor-addons' ),
				'frontend_available' => false,
				// 'separator' => 'before',
                'condition' => [
					'lightbox_type' => 'video',
				],
			]
		);

		$this->add_control(
			'end',
			[
				'label' => esc_html__( 'End Time', 'happy-elementor-addons' ),
				'type' => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Specify an end time (in seconds)', 'happy-elementor-addons' ),
				'condition' => [
					'lightbox_type' => 'video',
					'video_type' => [ 'youtube', 'hosted' ],
				],
				'frontend_available' => false,
			]
		);

		$this->add_responsive_control(
			'alignment',
			[
				'label' => __( 'Alignment', 'happy-elementor-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'separator' => 'before',
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
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};',
					'{{WRAPPER}}:not(:has(.elementor-widget-container))' => 'text-align: {{VALUE}};',
				]
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
		elseif ( 'video' == $settings['lightbox_type'] ) {
			$lightbox_url = '';
			$video_settings = [];

			if( 'hosted' === $settings['video_type'] && $settings['hosted_link']['url'] ) {
				$lightbox_url = $this->get_hosted_video_url();
				$video_settings = $this->get_video_settings( $lightbox_url );
			}
			elseif ( 'youtube' === $settings['video_type'] && $settings['youtube_link']['url'] ) {
				$start = $settings['start'];
				if ( ! $settings['start'] ) {
					$property = explode("t=", $settings['youtube_link']['url']);
					$start = isset( $property[1] ) ? $property[1] : $start;
				}
				$embed_url_params = [
					'start' => $start,
					'end' => $settings['end'],
					'autoplay' => 1,
					'rel' => 0,
					'controls' => 1,
				];
				$embed_options = $this->get_embed_options();
				$lightbox_url = Embed::get_embed_url( $settings['youtube_link']['url'], $embed_url_params, $embed_options );
				$video_settings = $this->get_video_settings( $lightbox_url );

			}
			elseif ( 'vimeo' === $settings['video_type'] && $settings['vimeo_link']['url'] ) {
				$embed_url_params = [
					'loop' => 0,
					'dnt' => true,
					'muted' => 0,
					'title' => 1,
					'portrait' => 1,
					'byline' => 1,

					'autoplay' => 1,
					'rel' => 0,
					'controls' => 1,
				];
				$embed_options = $this->get_embed_options();
				$lightbox_url = Embed::get_embed_url( $settings['vimeo_link']['url'], $embed_url_params, $embed_options );
				$video_settings = $this->get_video_settings( $lightbox_url );
			}
			$video_settings['modalOptions'] = [ 'id' => 'elementor-lightbox-' . $this->get_id() ];

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
		$settings = $this->get_settings_for_display();
		$video_type = $settings['video_type'];
		$video_url = null;
		$video_settings = [
			'type' => 'video'
		];
		if ( 'hosted' == $video_type ) {
			$video_url = $video_link;
			$video_settings['videoParams'] = [
				'controls' => 'yes',
				'preload' => 'metadata',
				'muted' => 'muted',
				'controlsList' => 'nodownload',
			];
		} else {
			$video_url = $video_link;
		}

		if ( null === $video_url ) {
			return '';
		}

		$video_settings['videoType'] = $video_type;
		$video_settings['url'] = $video_url;

		return $video_settings;
	}

	private function get_embed_options() {
		$settings = $this->get_settings_for_display();

		$embed_options = [];

		if ( 'youtube' === $settings['video_type'] ) {
			$embed_options['privacy'] = true;
		} elseif ( 'vimeo' === $settings['video_type'] ) {
			$embed_options['start'] = $settings['start'];
		}

		$embed_options['lazy_load'] = true;

		return $embed_options;
	}

	private function get_hosted_video_url() {
		$settings = $this->get_settings_for_display();
		$video_url = $settings['hosted_link']['url'];

		if ( empty( $video_url ) ) {
			return '';
		}

		if ( $settings['start'] || $settings['end'] ) {
			$video_url .= '#t=';
		}

		if ( $settings['start'] ) {
			$video_url .= $settings['start'];
		}

		if ( $settings['end'] ) {
			$video_url .= ',' . $settings['end'];
		}

		return $video_url;
	}
}
