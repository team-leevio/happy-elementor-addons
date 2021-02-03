<?php

/**
 * Image grid widget class
 *
 * @package Happy_Addons
 */

namespace Happy_Addons\Elementor\Widget;

use Elementor\Group_Control_Css_Filter;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;

defined('ABSPATH') || die();

class Image_Hovereffect extends Base
{

	/**
	 * Default filter is the global filter
	 * and can be overriden from settings
	 *
	 * @var string
	 */

	public function get_title()
	{
		return __('Image Hover Effect', 'happy-elementor-addons');
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon()
	{
		return 'hm hm-finger-index';
	}

	public function get_keywords()
	{
		return ['hover', 'image', 'effect'];
	}

	protected function register_content_controls()
	{
		$this->start_controls_section(
			'_section_effects',
			[
				'label' => __('Effects', 'happy-elementor-addons'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'hover_effect',
			[
				'label' => __('Hover Effect', 'happy-elementor-addons'),
				'type' => Controls_Manager::SELECT2,
				'options' => [
					'ha-effect-sadie'  => __('Sadie', 'happy-elementor-addons'),
					'ha-effect-lily'  => __('Lily', 'happy-elementor-addons'),
					'ha-effect-honey'  => __('Honey', 'happy-elementor-addons'),
					'ha-effect-layla'  => __('Layla', 'happy-elementor-addons'),
					'ha-effect-oscar'  => __('Oscar', 'happy-elementor-addons'),
					'ha-effect-marley'  => __('Marley', 'happy-elementor-addons'),
					'ha-effect-ruby'  => __('Ruby', 'happy-elementor-addons'),
					'ha-effect-roxy'  => __('Roxy', 'happy-elementor-addons'),
					'ha-effect-bubba'  => __('Bubba', 'happy-elementor-addons'),
					'ha-effect-romeo'  => __('Romeo', 'happy-elementor-addons'),
					'ha-effect-dexter'  => __('Dexter', 'happy-elementor-addons'),
					'ha-effect-sarah'  => __('Sarah', 'happy-elementor-addons'),
					'ha-effect-chico'  => __('Chico', 'happy-elementor-addons'),
					'ha-effect-milo'  => __('Milo', 'happy-elementor-addons'),
					'ha-effect-goliath'  => __('Goliath', 'happy-elementor-addons'),
					'ha-effect-apollo'  => __('Apollo', 'happy-elementor-addons'),
					'ha-effect-moses'  => __('Moses', 'happy-elementor-addons'),
					'ha-effect-jazz'  => __('Jazz', 'happy-elementor-addons'),
					'ha-effect-ming'  => __('Ming', 'happy-elementor-addons'),
					'ha-effect-lexi'  => __('Lexi', 'happy-elementor-addons'),
					'ha-effect-duke'  => __('Duke', 'happy-elementor-addons'),
				],
				'default' => 'ha-effect-sadie',
			]
		);

		$this->add_control(
			'hover_title',
			[
				'label' => __('Title', 'happy-elementor-addons'),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 3,
				'default' => __('Holy <span>Sadie</span>', 'happy-elementor-addons'),
				'placeholder' => __('Type your title here', 'happy-elementor-addons'),
				'dynamic' => ['active' => true],
			]
		);

		$this->add_control(
			'hover_description',
			[
				'label' => __('Description', 'happy-elementor-addons'),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 10,
				'default' => __('Sadie never took her eyes off me. She had a dark soul.', 'happy-elementor-addons'),
				'placeholder' => __('Type your description here', 'happy-elementor-addons'),
				'dynamic' => ['active' => true],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_controls()
	{
		$this->start_controls_section(
			'_section_effects_style',
			[
				'label' => __('Style', 'happy-elementor-addons'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typo',
				'label' => 'Title Typography',
				'selector' => '{{WRAPPER}} .ha-ihe-wrapper .ha-ihe-fig .ha-ihe-title',
				'scheme' => Typography::TYPOGRAPHY_2,
			]
		);


		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typo',
				'label' => 'Description Typography',
				'selector' => '{{WRAPPER}} .ha-ihe-wrapper .ha-ihe-fig .ha-ihe-desc',
				'scheme' => Typography::TYPOGRAPHY_2,
			]
		);

		$this->start_controls_tabs('_tabs_style');

		$this->start_controls_tab(
			'_tab_normal',
			[
				'label' => __('Normal', 'happy-elementor-addons'),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __('Title Color', 'happy-elementor-addons'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-ihe-wrapper .ha-ihe-fig .ha-ihe-title' => 'color: {{VALUE}}; border-color: {{VALUE}};',
					'{{WRAPPER}} .ha-ihe-wrapper .ha-ihe-fig .ha-ihe-title::after' => 'background: {{VALUE}};',
					'{{WRAPPER}} .ha-ihe-wrapper .ha-ihe-fig .ha-ihe-caption::before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ha-ihe-wrapper .ha-ihe-fig .ha-ihe-caption::before' => 'border-color: {{VALUE}};',
					// '{{WRAPPER}} .ha-ihe-wrapper .ha-ihe-fig .ha-ihe-caption::before' => 'background: {{VALUE}};',
					'{{WRAPPER}} .ha-ihe-wrapper .ha-ihe-fig .ha-ihe-caption::after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ha-ihe-wrapper .ha-ihe-fig .ha-ihe-caption::after' => 'border-color: {{VALUE}};',
					// '{{WRAPPER}} .ha-ihe-wrapper .ha-ihe-fig .ha-ihe-caption::after' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __('Description Color', 'happy-elementor-addons'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-ihe-wrapper .ha-ihe-fig .ha-ihe-desc' => 'color: {{VALUE}}; border-color: {{VALUE}};',
					// '{{WRAPPER}} .ha-ihe-wrapper .ha-ihe-fig .ha-ihe-desc' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_hover',
			[
				'label' => __('Hover', 'happy-elementor-addons'),
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label' => __('Title Color', 'happy-elementor-addons'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-ihe-wrapper .ha-ihe-fig:hover .ha-ihe-title' => 'color: {{VALUE}}; border-color: {{VALUE}};',
					'{{WRAPPER}} .ha-ihe-wrapper .ha-ihe-fig:hover .ha-ihe-title::after' => 'background: {{VALUE}};',
					'{{WRAPPER}} .ha-ihe-wrapper .ha-ihe-fig:hover .ha-ihe-caption::before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ha-ihe-wrapper .ha-ihe-fig:hover .ha-ihe-caption::before' => 'border-color: {{VALUE}};',
					// '{{WRAPPER}} .ha-ihe-wrapper .ha-ihe-fig:hover .ha-ihe-caption::before' => 'background: {{VALUE}};',
					'{{WRAPPER}} .ha-ihe-wrapper .ha-ihe-fig:hover .ha-ihe-caption::after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ha-ihe-wrapper .ha-ihe-fig:hover .ha-ihe-caption::after' => 'border-color: {{VALUE}};',
					// '{{WRAPPER}} .ha-ihe-wrapper .ha-ihe-fig:hover .ha-ihe-caption::after' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'description_hover_color',
			[
				'label' => __('Description Color', 'happy-elementor-addons'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-ihe-wrapper .ha-ihe-fig:hover .ha-ihe-desc' => 'color: {{VALUE}}; border-color: {{VALUE}};',
					// '{{WRAPPER}} .ha-ihe-wrapper .ha-ihe-fig:hover .ha-ihe-desc' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
?>
		<div class="ha-ihe-wrapper grid">
			<figure class="ha-ihe-fig <?php echo esc_attr($settings['hover_effect']); ?>">
				<img class="ha-ihe-img" src="https://tympanus.net/Development/HoverEffectIdeas/img/9.jpg" alt="img12" />
				<figcaption class="ha-ihe-caption">
					<?php if ($settings['hover_effect'] == 'ha-effect-lily') : ?>
						<div>
						<?php endif; ?>
						<h2 class="ha-ihe-title"><?php echo ha_kses_intermediate($settings['hover_title']); ?></h2>
						<?php if ($settings['hover_effect'] != 'ha-effect-honey') : ?>
							<p class="ha-ihe-desc"><?php echo ha_kses_intermediate($settings['hover_description']); ?></p>
						<?php endif; ?>
						<?php if ($settings['hover_effect'] == 'ha-effect-lily') : ?>
						</div>
					<?php endif; ?>
				</figcaption>
			</figure>
		</div>
<?php
	}
}
