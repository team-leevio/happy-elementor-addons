<?php
/**
 * Blurb widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Utils;
use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;
use Happy_Addons\Elementor\Controls\Group_Control_Foreground;

defined( 'ABSPATH' ) || die();

class Calendly extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Calendly', 'happy-elementor-addons' );
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
		return 'hm hm-calendar';
	}

	public function get_keywords() {
		return [ 'info', 'blurb', 'box', 'text', 'content' ];
	}

	/**
	 * Register content related controls
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'_section_calendly',
			[
				'label' => __( 'Calendly', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'calendly_event_link',
			[
				'label' => __( 'Event Link', 'happy-elementor-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '', 'happy-elementor-addons' ),
				'placeholder' => __( 'Type event link here', 'happy-elementor-addons' ),
			]
		);

		$this->add_control(
			'event_type_details',
			[
				'label' => __( 'Hide Event Type Details', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'your-plugin' ),
				'label_off' => __( 'no', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register styles related controls
	 */
	protected function register_style_controls() {
		$this->start_controls_section(
			'_section_style_calendly',
			[
				'label' => __( 'Calendly', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'alpha' => false,
			]
		);

		$this->add_control(
			'button_link_color',
			[
				'label' => __( 'Button & Link Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => __( 'Background Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
			<?php if ($settings['calendly_event_link']): ?>
			<div class="calendly-inline-widget" data-url="<?php  echo esc_url( $settings['calendly_event_link'] ); ?>?<?php if ( 'yes' === $settings['event_type_details'] ): echo'hide_event_type_details=1'; endif; ?><?php if ($settings['text_color']): echo "&text_color=".str_replace('#', '', $settings['text_color']);  endif; ?><?php if ($settings['button_link_color']): echo "&primary_color=".str_replace('#', '', $settings['button_link_color']);  endif; ?><?php if ($settings['background_color']): echo "&background_color=".str_replace('#', '', $settings['background_color']);  endif; ?>" style="min-width:320px;height:630px;"></div>
			<script type="text/javascript" src="https://assets.calendly.com/assets/external/widget.js"></script>
			<?php endif; ?>
		<?php
	}
}
