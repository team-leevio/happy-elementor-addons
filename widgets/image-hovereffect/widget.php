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
use Elementor\Scheme_Typography;

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
				'multiple' => false,
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
				'default' => ['ha-effect-sadie'],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_controls()
	{
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
?>
		<div class="grid">
			<figure class="<?php echo esc_attr($settings['hover_effect']); ?>">
				<img src="https://tympanus.net/Development/HoverEffectIdeas/img/9.jpg" alt="img12" />
				<figcaption>
					<?php if ($settings['hover_effect'] == 'ha-effect-lily') : ?>
						<div>
						<?php endif; ?>
						<h2>Holy <span>Sadie</span></h2>
						<?php if ($settings['hover_effect'] != 'ha-effect-honey') : ?>
						<p>Sadie never took her eyes off me. She had a dark soul.</p>
						<?php endif; ?>
						<?php if ($settings['hover_effect'] == 'ha-effect-lily') : ?>
						</div>
					<?php endif; ?>
					<a href="#">View more</a>
				</figcaption>
			</figure>
		</div>
<?php
	}
}
