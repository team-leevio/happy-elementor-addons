<?php
/**
 * Link Hover widget class
 *
 * @package Happy_Addons
 */

namespace Happy_Addons\Elementor\Widget;

// use Elementor\Scheme_Typography;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Happy_Addons\Elementor\Traits\Link_Hover_Markup;

class LinkHover extends Base {
    use Link_Hover_Markup;

    /**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
		return __( 'Link Hover', 'happy-elementor-addons' );
	}

	public function get_custom_help_url() {
		return 'https://happyaddons.com/docs/happy-addons-for-elementor/widgets/link-hover/';
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
		return 'hm hm-blog-content';
	}

	public function get_keywords() {
		return [ 'link', 'hover', 'animation' ];
	}

    /**
	 * Register content related controls
	 */
	protected function register_content_controls() {
        $this->start_controls_section(
			'_section_title',
			[
				'label' => __( 'Title & Description', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'happy-elementor-addons' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Happy Info Box Title', 'happy-elementor-addons' ),
				'placeholder' => __( 'Type Info Box Title', 'happy-elementor-addons' ),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_control(
			'description',
			[
				'label' => __( 'Description', 'happy-elementor-addons' ),
				'description' => ha_get_allowed_html_desc( 'intermediate' ),
				'type' => Controls_Manager::URL,
				'default' => __( 'Happy info box description goes here', 'happy-elementor-addons' ),
				'placeholder' => __( 'Type info box description', 'happy-elementor-addons' ),
				'rows' => 5,
				'dynamic' => [
					'active' => true,
				]
			]
		);
    }

    /**
	 * Register styles related controls
	 */
	protected function register_style_controls() {

    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        self::render_dia_markup($settings);
    }
}
