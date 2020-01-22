<?php
/**
 * Social Icons widget class
 *
 * @package Happy_Addons
 */

namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Border;
use Elementor\Scheme_Typography;

defined('ABSPATH') || die();

class Social_Icons extends Base {

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __('Social Icons', 'happy-elementor-addons');
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'hm hm-bond';
	}

	public function get_keywords() {
		return ['social', 'icons'];
	}

	protected function register_content_controls() {
		$this->start_controls_section(
			'_section_ha_social_icons_contents',
			[
				'label' => __('Icon', 'happy-elementor-addons'),
				'tab'   => Controls_Manager::TAB_CONTENT
			]
		);
		$repeater = new Repeater();

		$repeater->add_control(
			'ha_social_icon',
			[
				'label'       => __('Icon', 'happy-elementor-addons'),
				'type'        => Controls_Manager::ICONS,
				'label_block' => true,
				'default'     => [
					'value'   => 'fab fa-wordpress',
					'library' => 'fa-brands',
				],
				'recommended' => [
					'fa-brands' => [
						'android',
						'apple',
						'behance',
						'bitbucket',
						'codepen',
						'delicious',
						'deviantart',
						'digg',
						'dribbble',
						'happy-elementor-addons',
						'facebook',
						'flickr',
						'foursquare',
						'free-code-camp',
						'github',
						'gitlab',
						'globe',
						'google-plus',
						'houzz',
						'instagram',
						'jsfiddle',
						'linkedin',
						'medium',
						'meetup',
						'mixcloud',
						'odnoklassniki',
						'pinterest',
						'product-hunt',
						'reddit',
						'shopping-cart',
						'skype',
						'slideshare',
						'snapchat',
						'soundcloud',
						'spotify',
						'stack-overflow',
						'steam',
						'stumbleupon',
						'telegram',
						'thumb-tack',
						'tripadvisor',
						'tumblr',
						'twitch',
						'twitter',
						'viber',
						'vimeo',
						'vk',
						'weibo',
						'weixin',
						'whatsapp',
						'wordpress',
						'xing',
						'yelp',
						'youtube',
						'500px',
					],
				],
			]
		);

		$repeater->add_control(
			'ha_social_link',
			[
				'label'       => __('Link', 'happy-elementor-addons'),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => __('https://your-social-link.com', 'happy-elementor-addons'),
			]
		);


		// repeater icon text field
		$repeater->add_control(
			'ha_enable_text',
			[
				'label'          => __('Enable Text', 'happy-elementor-addons'),
				'type'           => Controls_Manager::SWITCHER,
				'label_on'       => __('Yes', 'happy-elementor-addons'),
				'label_off'      => __('No', 'happy-elementor-addons'),
				'return_value'   => 'yes',
				'style_transfer' => true,
				'separator'      => 'before'
			]
		);

		$repeater->add_control(
			'ha_social_icon_title',
			[
				'label'     => __('Social Name', 'happy-elementor-addons'),
				'type'      => Controls_Manager::TEXT,
				'condition' => [
					'ha_enable_text' => 'yes'
				],
			]
		);

		$repeater->add_control(
			'customize',
			[
				'label'          => __('Want To Customize?', 'happy-elementor-addons'),
				'type'           => Controls_Manager::SWITCHER,
				'label_on'       => __('Yes', 'happy-elementor-addons'),
				'label_off'      => __('No', 'happy-elementor-addons'),
				'return_value'   => 'yes',
				'style_transfer' => true,
				'separator'      => 'before'
			]
		);

		$repeater->start_controls_tabs(
			'_tab_social_icon_colors',
			[
				'condition' => ['customize' => 'yes']
			]
		);
		$repeater->start_controls_tab(
			'_tab_ha_social_icon_normal',
			[
				'label' => __('Normal', 'happy-elementor-addons'),
			]
		);

		$repeater->add_control(
			'ha_social_icon_color',
			[
				'label' => __('Color', 'happy-elementor-addons'),
				'type'  => Controls_Manager::COLOR,

				'selectors'      => [
					'{{WRAPPER}} .ha-social-icons-wrapper > {{CURRENT_ITEM}} i'                      => 'color: {{VALUE}};',
					'{{WRAPPER}} .ha-social-icons-wrapper > {{CURRENT_ITEM}} > svg'                  => 'fill: {{VALUE}};',
					'{{WRAPPER}} .ha-social-icons-wrapper > {{CURRENT_ITEM}} > .ha-social-icon-label' => 'color: {{VALUE}};',
				],
				'condition'      => ['customize' => 'yes'],
				'style_transfer' => true,
			]
		);
		$repeater->add_control(
			'ha_social_icon_bg_color',
			[
				'label' => __('Background Color', 'happy-elementor-addons'),
				'type'  => Controls_Manager::COLOR,

				'selectors'      => [
					'{{WRAPPER}} .ha-social-icons-wrapper > {{CURRENT_ITEM}}' => 'background-color: {{VALUE}};',
				],
				'condition'      => ['customize' => 'yes'],
				'style_transfer' => true,
			]
		);

		$repeater->end_controls_tab();
		$repeater->start_controls_tab(
			'_tab_social_icon_hover',
			[
				'label' => __('Hover', 'happy-elementor-addons'),
			]
		);

		$repeater->add_control(
			'ha_social_icon_hover_color',
			[
				'label'          => __('Color', 'happy-elementor-addons'),
				'type'           => Controls_Manager::COLOR,
				'selectors'      => [
					'{{WRAPPER}} .ha-social-icons-wrapper > {{CURRENT_ITEM}}:hover i'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .ha-social-icons-wrapper > {{CURRENT_ITEM}}:hover svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .ha-social-icons-wrapper > {{CURRENT_ITEM}} > .ha-social-icon-label' => 'color: {{VALUE}};',
				],
				'condition'      => ['customize' => 'yes'],
				'style_transfer' => true,
			]
		);
		$repeater->add_control(
			'ha_social_icon_hover_bg_color',
			[
				'label'          => __('Background Color', 'happy-elementor-addons'),
				'type'           => Controls_Manager::COLOR,
				'selectors'      => [
					'{{WRAPPER}} .ha-social-icon:hover' => 'background-color: {{VALUE}};',
				],
				'condition'      => ['customize' => 'yes'],
				'style_transfer' => true,
			]
		);
		$repeater->end_controls_tab();
		$repeater->end_controls_tabs();

		$this->add_control(
			'ha_social_icon_list',
			[
				'label'       => __('Social Icons', 'happy-elementor-addons'),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'ha_social_icon' => [
							'value'   => 'fab fa-facebook',
							'library' => 'fa-brands',
						],
					],
					[
						'ha_social_icon' => [
							'value'   => 'fab fa-twitter',
							'library' => 'fa-brands',
						],
					],
					[
						'ha_social_icon' => [
							'value'   => 'fab fa-linkedin',
							'library' => 'fa-brands',
						],
					],
				],
				'title_field' => '{{{elementor.helpers.getSocialNetworkNameFromIcon( ha_social_icon)}}}',
			]
		);

		$this->add_control(
			'custom_text_rotate',
			[
				'label'      => __('Rotate Text', 'happy-elementor-addons'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['deg'],
				'range'      => [
					'deg' => [
						'min'  => 0,
						'max'  => 360,
						'step' => 15
					]
				],
				'default'    => [
					'unit' => 'deg',
					'size' => 0
				],
				'separator'  => 'before',
				'selectors'  => [
					'{{WRAPPER}} .ha-social-icon' => 'transform: rotate({{SIZE}}{{UNIT}});'
				]
			]
		);

		$this->add_responsive_control(
			'ha_social_icon_align',
			[
				'label'     => __('Alignment', 'happy-elementor-addons'),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __('Left', 'happy-elementor-addons'),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __('Center', 'happy-elementor-addons'),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __('Right', 'happy-elementor-addons'),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'_section_sticky',
			[
				'label' => __('Sticky Option', 'happy-elementor-addons'),
				'tab'   => Controls_Manager::TAB_CONTENT
			]
		);

		$this->add_control(
			'sticky_options',
			[
				'label'        => __('Enable Sticky', 'happy-elementor-addons'),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'sticky_position',
			[
				'label'     => __('Position', 'happy-elementor-addons'),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'top-left'     => __('Top Left', 'happy-elementor-addons'),
					'top-right'    => __('Top Right', 'happy-elementor-addons'),
					'bottom-left'  => __('Bottom Left', 'happy-elementor-addons'),
					'bottom-right' => __('Bottom Right', 'happy-elementor-addons'),
				],
				'default'   => 'top-left',
				'condition' => [
					'sticky_options' => 'yes'
				],
			]
		);

		$this->add_control(
			'top_offset',
			[
				'label'      => __('Top', 'happy-elementor-addons'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000
					]
				],
				'selectors'  => [
					'{{WRAPPER}} .ha-social-icon-sticky' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;'
				],
				'condition'  => [
					'sticky_options'  => 'yes',
					'sticky_position' => ['top-left', 'top-right']
				]
			]
		);

		$this->add_control(
			'bottom_offset',
			[
				'label'      => __('Bottom', 'happy-elementor-addons'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000
					]
				],
				'selectors'  => [
					'{{WRAPPER}} .ha-social-icon-sticky' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;'
				],
				'condition'  => [
					'sticky_options'  => 'yes',
					'sticky_position' => ['bottom-left', 'bottom-right']
				]
			]
		);

		$this->add_control(
			'left_offset',
			[
				'label'      => __('Left', 'happy-elementor-addons'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000
					]
				],
				'selectors'  => [
					'{{WRAPPER}} .ha-social-icon-sticky' => 'left: {{SIZE}}{{UNIT}}; right: auto;'
				],
				'condition'  => [
					'sticky_options'  => 'yes',
					'sticky_position' => ['top-left', 'bottom-left']
				]
			]
		);

		$this->add_control(
			'right_offset',
			[
				'label'      => __('Right', 'happy-elementor-addons'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000
					]
				],
				'selectors'  => [
					'{{WRAPPER}} .ha-social-icon-sticky' => 'right: {{SIZE}}{{UNIT}}; left: auto;'
				],
				'condition'  => [
					'sticky_options'  => 'yes',
					'sticky_position' => ['top-right', 'bottom-right']
				]
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_controls() {
		$this->start_controls_section(
			'_section_style_ha_icon',
			[
				'label' => __('Icon', 'happy-elementor-addons'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'ha_social_icon_size',
			[
				'label'     => __('Size', 'happy-elementor-addons'),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 20,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ha-social-icon.ha-social-icon--network i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ha-social-icon.ha-social-icon--network svg' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'ha_social_icon_padding',
			[
				'label'          => __('Padding', 'happy-elementor-addons'),
				'type'           => Controls_Manager::SLIDER,
				'selectors'      => [
					'{{WRAPPER}} .ha-social-icon.ha-social-icon--network' => 'padding: {{SIZE}}{{UNIT}};',
				],
				'default'        => [
					'unit' => 'em',
				],
				'tablet_default' => [
					'unit' => 'em',
				],
				'mobile_default' => [
					'unit' => 'em',
				],
				'range'          => [
					'em' => [
						'min' => 0,
						'max' => 5,
					],
				],
			]
		);

		$icon_spacing = is_rtl() ? 'margin-left: {{SIZE}}{{UNIT}};' : 'margin-right: {{SIZE}}{{UNIT}};';

		$this->add_responsive_control(
			'ha_social_icon_spacing',
			[
				'label'     => __('Spacing', 'happy-elementor-addons'),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ha-social-icon.ha-social-icon--network :not(:last-child)' => $icon_spacing,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'image_border',
				'selector'  => '{{WRAPPER}} .ha-social-icon.ha-social-icon--network',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'ha_social_icon_border_radius',
			[
				'label'      => __('Border Radius', 'happy-elementor-addons'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .ha-social-icon.ha-social-icon--network ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_style_custom_label',
			[
				'label' => __('Social Name', 'happy-elementor-addons'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'custom_label_width',
			[
				'label'      => __('Width', 'happy-elementor-addons'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000
					],
					'em' => [
						'min' => 1,
						'max' => 100
					],
					'%' => [
						'min' => 5,
						'max' => 400
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ha-social-icon.ha-social-icon--custom-label' => 'width: {{SIZE}}{{UNIT}};'
				]
			]
		);
		$this->add_responsive_control(
			'custom_label_height',
			[
				'label'      => __('Height', 'happy-elementor-addons'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000
					],
					'em' => [
						'min' => 1,
						'max' => 100
					],
					'%' => [
						'min' => 5,
						'max' => 400
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ha-social-icon.ha-social-icon--custom-label' => 'height: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'custom_label_typography',
				'label'    => __('Typography', 'happy-elementor-addons'),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .ha-social-icon--custom-label .ha-social-icon-label'
			]

		);

		$this->add_responsive_control(
			'custom_label_padding',
			[
				'label'          => __('Padding', 'happy-elementor-addons'),
				'type'           => Controls_Manager::SLIDER,
				'selectors'      => [
					'{{WRAPPER}} .ha-social-icon' => 'padding: {{SIZE}}{{UNIT}};',
				],
				'default'        => [
					'unit' => 'em',
				],
				'tablet_default' => [
					'unit' => 'em',
				],
				'mobile_default' => [
					'unit' => 'em',
				],
				'range'          => [
					'em' => [
						'min' => 0,
						'max' => 5,
					],
				],
			]
		);

		$icon_spacing = is_rtl() ? 'margin-left: {{SIZE}}{{UNIT}};' : 'margin-right: {{SIZE}}{{UNIT}};';

		$this->add_responsive_control(
			'custom_label_spacing',
			[
				'label'     => __('Spacing', 'happy-elementor-addons'),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ha-social-icon:not(:last-child)' => $icon_spacing,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'image_border',
				'selector'  => '{{WRAPPER}} .ha-social-icon',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'custom_label_border_radius',
			[
				'label'      => __('Border Radius', 'happy-elementor-addons'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .ha-social-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

	}

	protected function render() {
		$settings      = $this->get_settings_for_display();
		$social_list   = $settings['ha_social_icon_list'];
		$sticky_option = $settings['sticky_options'];
		if ('yes' === $sticky_option) {
			$sticky_class = 'ha-social-icon-sticky';
		}

		?>
		<div class="ha-social-icons-wrapper <?php echo $sticky_class; ?>">
			<?php
			foreach ($social_list as $key => $icons) {
				$icon         = $icons['ha_social_icon']['value'];
				$url          = esc_url($icons['ha_social_link']['url']);
				$social_title = esc_html($icons['ha_social_icon_title']);
				$link_attr    = 'link_' . $key;

				$this->add_render_attribute($link_attr, 'href', $url);

				$this->add_render_attribute($link_attr, 'class', [
					'ha-social-icon',
					'elementor-repeater-item-' . $icons['_id'],

				]);

				if (!empty($icon)) {
					$this->add_render_attribute($link_attr, 'class', 'ha-social-icon--network');
				} else {
					$this->add_render_attribute($link_attr, 'class', 'ha-social-icon--custom-label');
				}

				if ($icons['link']['is_external']) {
					$this->add_render_attribute($link_attr, 'target', '_blank');
				}

				if ($icons['link']['nofollow']) {
					$this->add_render_attribute($link_attr, 'rel', 'nofollow');
				}

				?>
				<a <?php echo $this->get_render_attribute_string($link_attr); ?>>
					<?php
					Icons_Manager::render_icon($icons['ha_social_icon']);
					if (!empty($social_title) && '' != $social_title) {
						echo "<span class='ha-social-icon-label'>" . $social_title . "</span>";
					}
					?>
				</a>
				<?php

			}
			?>
		</div>
		<?php
	}
}
