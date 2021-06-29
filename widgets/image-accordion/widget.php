<?php

/**
 * Member widget class
 *
 * @package Happy_Addons
 */

namespace Happy_Addons\Elementor\Widget;

use Elementor\Group_Control_Text_Shadow;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Happy_Addons\Elementor\Controls\Select2;
use \Happy_Addons\Elementor\Credentials_Manager;

defined('ABSPATH') || die();

class Image_Accordion extends Base {

    /**
     * Get widget title.
     *
     * @since 2.24.1
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Image Accordion', 'happy-elementor-addons');
    }

    // public function __construct($data = [], $args = null) {
    // 	parent::__construct($data, $args);
    // }

    /**
     * Get widget icon.
     *
     * @since 2.24.1
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'hm hm-image-accordion';
    }

    public function get_keywords() {
        return ['image', 'accordion', 'image accordion'];
    }

    protected function content_common() {
        $this->start_controls_section(
            '_section_content',
            [
                'label' => __('Content', 'happy-elementor-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'background_image',
            [
                'label' => __('Choose Image', 'happy-elementor-addons'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'label',
            [
                'label'   => __('Label', 'happy-elementor-addons'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Accordion Label', 'happy-elementor-addons'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label'   => __('Title', 'happy-elementor-addons'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Image Accordion', 'happy-elementor-addons'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'icon',
            [
                'label' => __('Title Icon', 'happy-elementor-addons'),
                'type'  => Controls_Manager::ICONS,
                'label_block' => false,
                'skin' => 'inline',
                'exclude_inline_options' => ['svg'],
            ]
        );

        $repeater->add_control(
            'icon_align',
            [
                'label'   => __('Icon Position', 'happy-elementor-addons'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left'  => __('Left', 'happy-elementor-addons'),
                    'right' => __('Right', 'happy-elementor-addons'),
                ],
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label' => __('Description', 'happy-elementor-addons'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => __('Image accordion content.', 'happy-elementor-addons'),
                'placeholder' => __('Type your description here', 'happy-elementor-addons'),
            ]
        );

        $repeater->add_control(
            'enable_button',
            [
                'label'        => __('Enable Button', 'happy-elementor-addons'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'happy-elementor-addons'),
                'label_off'    => __('No', 'happy-elementor-addons'),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before'
            ]
        );

        $repeater->add_control(
            'button_label',
            [
                'label'   => __('Button Label', 'happy-elementor-addons'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Read More', 'happy-elementor-addons'),
                'condition' => [
                    'enable_button' => 'yes',
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'button_url',
            [
                'label'   => __('Button URL', 'happy-elementor-addons'),
                'type'    => Controls_Manager::URL,
                'condition' => [
                    'enable_button' => 'yes',
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'enable_popup',
            [
                'label'        => __('Enable Popup', 'happy-elementor-addons'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'happy-elementor-addons'),
                'label_off'    => __('No', 'happy-elementor-addons'),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before'
            ]
        );

        $repeater->add_control(
            'popup_icon',
            [
                'label' => __('Popup Icon', 'happy-elementor-addons'),
                'type'  => Controls_Manager::ICONS,
                'label_block' => false,
                'default' => [
                    'value' => 'hm hm-popup',
                    'library' => 'solid',
                ],
                'skin' => 'inline',
                'exclude_inline_options' => ['svg'],
                'condition' => [
                    'enable_popup' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'enable_link',
            [
                'label'        => __('Enable Link', 'happy-elementor-addons'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'happy-elementor-addons'),
                'label_off'    => __('No', 'happy-elementor-addons'),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before'
            ]
        );

        $repeater->add_control(
            'link_url',
            [
                'label'   => __('Link URL', 'happy-elementor-addons'),
                'type'    => Controls_Manager::URL,
                'condition' => [
                    'enable_link' => 'yes',
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'link_icon',
            [
                'label' => __('Link Icon', 'happy-elementor-addons'),
                'type'  => Controls_Manager::ICONS,
                'label_block' => false,
                'default' => [
                    'value' => 'hm hm-link',
                    'library' => 'solid',
                ],
                'skin' => 'inline',
                'exclude_inline_options' => ['svg'],
                'condition' => [
                    'enable_link' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'active',
            [
                'label'        => __('Active', 'happy-elementor-addons'),
                'type'         => Controls_Manager::SWITCHER,
                'description'  => __('Active on Load', 'happy-elementor-addons'),
                'label_on'     => __('Yes', 'happy-elementor-addons'),
                'label_off'    => __('No', 'happy-elementor-addons'),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before'
            ]
        );

        $this->add_control(
            'accordion_items',
            [
                'label'         => __('Items', 'happy-elementor-addons'),
                'type'          => Controls_Manager::REPEATER,
                'fields'        => $repeater->get_controls(),
                'prevent_empty' => true,
                'default'       => [
                    [
                        'title'         => __('Image Accordion 1', 'happy-elementor-addons'),
                        'enable_button'  => 'yes',
                        'active'        => 'yes',
                    ],
                    [
                        'title'         => __('Image Accordion 2', 'happy-elementor-addons'),
                        'enable_button'  => 'yes',
                    ],
                    [
                        'title'         => __('Image Accordion 3', 'happy-elementor-addons'),
                        'enable_button'  => 'yes',
                    ],
                    [
                        'title'         => __('Image Accordion 4', 'happy-elementor-addons'),
                        'enable_button'  => 'yes',
                    ],
                ],
                'title_field'   => '{{{ title }}}',
            ]
        );

        $this->add_responsive_control(
            'items_style',
            [
                'label'         => esc_html__('Style', 'happy-elementor-addons'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'horizontal'    => esc_html__('Horizontal', 'happy-elementor-addons'),
                    'vertical'      => esc_html__('Vertical', 'happy-elementor-addons'),
                ],
                'default'       => 'horizontal',
                'prefix_class'  => 'ha-image-accordion%s-',
            ]
        );

        $this->add_control(
            'active_behavior',
            [
                'label'         => esc_html__('Active Behavoir', 'happy-elementor-addons'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'click' => esc_html__('Click', 'happy-elementor-addons'),
                    'hover' => esc_html__('Hover', 'happy-elementor-addons'),
                ],
                'default'       => 'click',
                'prefix_class'  => 'ha-image-accordion-',
            ]
        );

        $this->add_control(
            'active_behavior_notice',
            [
                'raw' => '<strong>' . esc_html__('Please note!', 'happy-elementor-addons') . '</strong> ' . esc_html__('Active on load won\'t be working with this active behavior.', 'happy-elementor-addons'),
                'type' => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'render_type' => 'ui',
                'condition' => [
                    'active_behavior' => 'hover',
                ],
            ]
        );

        $this->add_control(
			'content_text_align',
			[
				'label' => __( 'Text Align', 'happy-elementor-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => __( 'Left', 'happy-elementor-addons' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'happy-elementor-addons' ),
						'icon' => 'fa fa-align-center',
					],
					'flex-end' => [
						'title' => __( 'Right', 'happy-elementor-addons' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ha-ia-content-wrapper' => 'align-items: {{VALUE}};',
                ],
			]
		);

        $this->add_control(
			'content_h_align',
			[
				'label' => __( 'Horizontal Align', 'happy-elementor-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => __( 'Left', 'happy-elementor-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'happy-elementor-addons' ),
						'icon' => 'eicon-h-align-center',
					],
					'flex-end' => [
						'title' => __( 'Right', 'happy-elementor-addons' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'center',
				'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ha-overlay' => 'justify-content: {{VALUE}};',
                ],
			]
		);

        $this->add_control(
			'content_v_align',
			[
				'label' => __( 'Vertical Align', 'happy-elementor-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => __( 'Top', 'happy-elementor-addons' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => __( 'Center', 'happy-elementor-addons' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => __( 'Bottom', 'happy-elementor-addons' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'center',
				'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ha-overlay' => 'align-items: {{VALUE}};',
                ],
			]
		);

        $this->end_controls_section();
    }

    /**
     * Register content related controls
     */
    protected function register_content_controls() {
        $this->content_common();
    }

    protected function style_common() {
        $this->start_controls_section(
            '_section_common',
            [
                'label' => __('Common', 'happy-elementor-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'common_height',
            [
                'label' => __('Height', 'happy-elementor-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 500,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-ia-gallery-wrap' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'common_margin',
            [
                'label' => __('Margin', 'happy-elementor-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .ha-ia-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'common_padding',
            [
                'label' => __('Padding', 'happy-elementor-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .ha-ia-gallery-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'common_border',
                'label' => __('Border', 'happy-elementor-addons'),
                'selector' => '{{WRAPPER}} .ha-ia-gallery-wrap',
            ]
        );

        $this->add_responsive_control(
            'common_border_radius',
            [
                'label' => __('Border Radius', 'happy-elementor-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .ha-ia-gallery-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'common_box_shadow',
                'label' => __('Box Shadow', 'happy-elementor-addons'),
                'selector' => '{{WRAPPER}} .ha-ia-gallery-wrap',
            ]
        );

        $this->add_control(
            'common_background_color',
            [
                'label' => __('Background Color', 'happy-elementor-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-ia-gallery-wrap' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->start_controls_tabs('common_color');

        $this->start_controls_tab(
            'common_normal',
            [
                'label' => __('Normal', 'happy-elementor-addons'),

            ],
        );

        $this->add_control(
            'common_overlay_color',
            [
                'label' => __('Overlay Color', 'happy-elementor-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-ia-item .ha-overlay' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'common_hover',
            [
                'label' => __('Hover', 'happy-elementor-addons'),

            ],
        );

        $this->add_control(
            'common_overlay_color_hover',
            [
                'label' => __('Overlay Color', 'happy-elementor-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-ia-item:hover .ha-overlay' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        // $this->add_control(
        //     'common_border_color_hover',
        //     [
        //         'label' => __('Border Color', 'happy-elementor-addons'),
        //         'type' => Controls_Manager::COLOR,
        //         'selectors' => [
        //             '{{WRAPPER}} .title' => 'color: {{VALUE}}',
        //         ],
        //         'condition' => [
        //             'common_border_border!' => '',
        //         ],
        //     ]
        // );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'common_active',
            [
                'label' => __('Active', 'happy-elementor-addons'),

            ],
        );

        $this->add_control(
            'common_overlay_color_active',
            [
                'label' => __('Overlay Color', 'happy-elementor-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-ia-item.active .ha-overlay' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        // $this->add_control(
        //     'common_border_color_active',
        //     [
        //         'label' => __('Border Color', 'happy-elementor-addons'),
        //         'type' => Controls_Manager::COLOR,
        //         'selectors' => [
        //             '{{WRAPPER}} .title' => 'color: {{VALUE}}',
        //         ],
        //         'condition' => [
        //             'common_border_border!' => '',
        //         ],
        //     ]
        // );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function style_content() {

        $this->start_controls_section(
            '_section_style_content',
            [
                'label' => __('Content', 'happy-elementor-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register styles related controls
     */
    protected function register_style_controls() {
        $this->style_common();
        $this->style_content();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
?>
        <div class="ha-image-accordion-wrapper">
            <div class="ha-ia-container">
                <div class="ha-ia-gallery-wrap">
                    <?php foreach ($settings['accordion_items'] as $inx => $item) : ?>
                        <div style="background-image: url('<?php echo esc_url($item['background_image']['url']); ?>');" class="ha-ia-item <?php echo esc_attr(($item['active'] == 'yes') ? 'active' : ''); ?>">
                            <div class="ha-overlay">
                                <div class="ha-ia-content-wrapper">
                                    <?php if ($item['enable_popup'] == 'yes' || $item['enable_link'] == 'yes') : ?>
                                        <div class="ha-ia-actions">
                                            <?php if ($item['enable_popup'] == 'yes') : ?>
                                                <span class="ha-ia-popup">
                                                    <a href="<?php echo esc_url($item['background_image']['url']); ?>" data-elementor-open-lightbox="yes">
                                                        <?php ha_render_icon($item, null, 'popup_icon'); ?>
                                                    </a>
                                                </span>
                                            <?php endif; ?>
                                            <?php if ($item['enable_link'] == 'yes') : ?>
                                                <span class="ha-ia-link">
                                                    <a href="<?php echo esc_url($item['link_url']['url']); ?>" <?php echo esc_attr($item['link_url']['is_external'] ? 'target="_blank"' : ''); ?> <?php echo esc_attr($item['link_url']['nofollow'] ? 'rel="nofollow"' : ''); ?>>
                                                        <?php ha_render_icon($item, null, 'link_icon'); ?>
                                                    </a>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($item['label'])) : ?>
                                        <span class="ha-ia-content-label"><?php echo esc_html($item['label']); ?></span>
                                    <?php endif; ?>
                                    <div class="ha-ia-content-icon-title ha-ia-icon-<?php echo esc_attr($item['icon_align']); ?>">
                                        <?php ha_render_icon($item, null, 'icon'); ?>
                                        <span class="ha-ia-content-title"><?php echo esc_html($item['title']); ?></span>
                                    </div>
                                    <?php if (!empty($item['description'])) :
                                        printf('<div class="ha-ia-content-description">%s</div>', $this->parse_text_editor($item['description']));
                                    endif; ?>
                                    <?php if ($item['enable_button'] == 'yes') : ?>
                                        <a class="ha-ia-content-button" href="<?php echo esc_attr($item['button_url']['url']); ?>" <?php echo esc_attr($item['button_url']['is_external'] ? 'target="_blank"' : ''); ?> <?php echo esc_attr($item['button_url']['nofollow'] ? 'rel="nofollow"' : ''); ?>>
                                            <?php echo esc_html($item['button_label']); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
<?php
    }
}
