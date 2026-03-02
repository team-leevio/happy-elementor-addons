<?php
/**
 * WhatsApp Button widget class
 *
 * @package Happy_Addons
 */

namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;

defined( 'ABSPATH' ) || die();

class Whatsapp_Button extends Base {

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_title() {
		return __( 'WhatsApp Button', 'happy-elementor-addons' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_icon() {
		return 'hm hm-circular-chat';
	}

	public function get_keywords() {
		return [ 'whatsapp', 'chat', 'button', 'floating', 'social', 'messenger', 'contact', 'wa' ];
	}

	public function get_style_depends() {
		return [
			'happy-elementor-addons',
			'elementor-icons-fa-solid',
			'elementor-icons-fa-brands',
			'elementor-icons-fa-regular',
		];
	}

	public function get_script_depends() {
		return [ 'ha-whatsapp-button' ];
	}

	protected function is_dynamic_content(): bool {
		return false;
	}

	/**
	 * Register widget content controls
	 */
	protected function register_content_controls() {
		$this->__whatsapp_settings_controls();
		$this->__floating_chat_controls();
		$this->__position_controls();
		$this->__tooltip_controls();
		$this->__animation_controls();
	}

	protected function __whatsapp_settings_controls() {

		$this->start_controls_section(
			'_section_whatsapp_settings',
			[
				'label' => __( 'WhatsApp Settings', 'happy-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'button_type',
			[
				'label'   => __( 'Button Type', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'standard',
				'options' => [
					'standard'      => __( 'Standard Button', 'happy-elementor-addons' ),
					'floating_chat' => __( 'Floating Chat', 'happy-elementor-addons' ),
				],
				'prefix_class' => 'ha-whatsapp--type-',
				'render_type'  => 'template',
			]
		);

		$this->add_control(
			'phone_number',
			[
				'label'       => __( 'Phone Number', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
				'placeholder' => __( 'e.g. 15551234567 (no + or dashes)', 'happy-elementor-addons' ),
				'description' => __( 'Enter phone number with country code, no spaces, plus sign or dashes. Example: 15551234567', 'happy-elementor-addons' ),
				'dynamic'     => [ 'active' => true ],
			]
		);

		$this->add_control(
			'pre_filled_message',
			[
				'label'       => __( 'Pre-filled Message', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default'     => '',
				'placeholder' => __( 'Hello! I would like to chat with you...', 'happy-elementor-addons' ),
				'description' => __( 'Optional message that will be pre-filled in the WhatsApp chat.', 'happy-elementor-addons' ),
				'dynamic'     => [ 'active' => true ],
			]
		);

		$this->add_control(
			'open_in',
			[
				'label'   => __( 'Open In', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '_blank',
				'options' => [
					'_blank' => __( 'New Tab', 'happy-elementor-addons' ),
					'_self'  => __( 'Same Tab', 'happy-elementor-addons' ),
				],
			]
		);

		$this->add_control(
			'button_style',
			[
				'label'        => __( 'Button Style', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'icon-only',
				'options'      => [
					'icon-only' => __( 'Icon Only (Circle)', 'happy-elementor-addons' ),
					'pill'      => __( 'Icon + Text (Pill)', 'happy-elementor-addons' ),
					'rounded'   => __( 'Icon + Text (Rounded)', 'happy-elementor-addons' ),
				],
				'separator'    => 'before',
				'prefix_class' => 'ha-whatsapp--style-',
				'render_type'  => 'template',
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'       => __( 'Button Text', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Chat on WhatsApp', 'happy-elementor-addons' ),
				'label_block' => true,
				'dynamic'     => [ 'active' => true ],
				'condition'   => [
					'button_style!' => 'icon-only',
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'type' => Controls_Manager::HIDDEN,
				'default' => 'fab fa-whatsapp',
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label'            => __( 'Icon', 'happy-elementor-addons' ),
				'type'             => Controls_Manager::ICONS,
				'default'          => [
					'value'   => 'fab fa-whatsapp',
					'library' => 'fa-brands',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __floating_chat_controls() {

		$this->start_controls_section(
			'_section_floating_chat',
			[
				'label'     => __( 'Floating Chat Box', 'happy-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'button_type' => 'floating_chat',
				],
			]
		);

		$this->add_control(
			'chat_theme',
			[
				'label'   => __( 'Chat Theme', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'general',
				'options' => [
					'general'       => __( 'General', 'happy-elementor-addons' ),
					'message_field' => __( 'Message Field', 'happy-elementor-addons' ),
					'white_theme'   => __( 'White Theme', 'happy-elementor-addons' ),
					'light_theme'   => __( 'Light Theme', 'happy-elementor-addons' ),
					'dark_theme'    => __( 'Dark Theme', 'happy-elementor-addons' ),
					'booking'       => __( 'Booking', 'happy-elementor-addons' ),
					'feedback'      => __( 'Feedback', 'happy-elementor-addons' ),
					'onboarding'    => __( 'Onboarding', 'happy-elementor-addons' ),
				],
				'prefix_class' => 'ha-whatsapp--chat-theme-',
				'render_type'  => 'template',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'agent_image',
			[
				'label'   => __( 'Agent Image', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => '',
				],
			]
		);

		$this->add_control(
			'agent_name',
			[
				'label'       => __( 'Agent Name', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'John Doe', 'happy-elementor-addons' ),
				'placeholder' => __( 'Enter agent name', 'happy-elementor-addons' ),
				'dynamic'     => [ 'active' => true ],
			]
		);

		$this->add_control(
			'agent_status',
			[
				'label'       => __( 'Agent Status', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Online', 'happy-elementor-addons' ),
				'placeholder' => __( 'Enter status (e.g. Online)', 'happy-elementor-addons' ),
				'dynamic'     => [ 'active' => true ],
			]
		);

		$this->add_control(
			'show_status_dot',
			[
				'label'        => __( 'Show Status Dot', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'happy-elementor-addons' ),
				'label_off'    => __( 'Hide', 'happy-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_typing_indicator',
			[
				'label'        => __( 'Show Typing Indicator', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'happy-elementor-addons' ),
				'label_off'    => __( 'No', 'happy-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'chat_initial_message',
			[
				'label'       => __( 'Initial Message', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( "Hi there 👋\n\nHow can I help you?", 'happy-elementor-addons' ),
				'placeholder' => __( 'Enter initial chat message', 'happy-elementor-addons' ),
				'dynamic'     => [ 'active' => true ],
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'chat_button_text',
			[
				'label'       => __( 'Chat Button Text', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Chat on WhatsApp', 'happy-elementor-addons' ),
				'placeholder' => __( 'Enter button text', 'happy-elementor-addons' ),
				'dynamic'     => [ 'active' => true ],
			]
		);

		$this->end_controls_section();
	}

	protected function __position_controls() {

		$this->start_controls_section(
			'_section_position',
			[
				'label' => __( 'Position Settings', 'happy-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'button_position',
			[
				'label'        => __( 'Position', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'bottom-right',
				'options'      => [
					'bottom-right' => __( 'Bottom Right', 'happy-elementor-addons' ),
					'bottom-left'  => __( 'Bottom Left', 'happy-elementor-addons' ),
					'custom'       => __( 'Custom', 'happy-elementor-addons' ),
				],
				'prefix_class' => 'ha-whatsapp--pos-',
				'render_type'  => 'template',
			]
		);

		$this->add_responsive_control(
			'offset_bottom',
			[
				'label'      => __( 'Offset Bottom', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'size' => 30,
					'unit' => 'px',
				],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 500 ],
					'em' => [ 'min' => 0, 'max' => 30 ],
					'%'  => [ 'min' => 0, 'max' => 100 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .ha-whatsapp-button' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'button_position!' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'offset_horizontal',
			[
				'label'      => __( 'Offset Horizontal', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'size' => 25,
					'unit' => 'px',
				],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 500 ],
					'em' => [ 'min' => 0, 'max' => 30 ],
					'%'  => [ 'min' => 0, 'max' => 100 ],
				],
				'selectors'  => [
					'{{WRAPPER}}.ha-whatsapp--pos-bottom-right .ha-whatsapp-button' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.ha-whatsapp--pos-bottom-left .ha-whatsapp-button'  => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'button_position!' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'custom_top',
			[
				'label'      => __( 'Top', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ha-whatsapp-button' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'button_position' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'custom_right',
			[
				'label'      => __( 'Right', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ha-whatsapp-button' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'button_position' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'custom_bottom',
			[
				'label'      => __( 'Bottom', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ha-whatsapp-button' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'button_position' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'custom_left',
			[
				'label'      => __( 'Left', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ha-whatsapp-button' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'button_position' => 'custom',
				],
			]
		);

		$this->add_control(
			'z_index',
			[
				'label'     => __( 'Z-Index', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 99999,
				'default'   => 9999,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .ha-whatsapp-button' => 'z-index: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hide_on_desktop',
			[
				'label'        => __( 'Hide on Desktop', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
				'prefix_class' => 'ha-whatsapp--hide-desktop-',
				'render_type'  => 'template',
			]
		);

		$this->add_control(
			'hide_on_tablet',
			[
				'label'        => __( 'Hide on Tablet', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'prefix_class' => 'ha-whatsapp--hide-tablet-',
				'render_type'  => 'template',
			]
		);

		$this->add_control(
			'hide_on_mobile',
			[
				'label'        => __( 'Hide on Mobile', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'prefix_class' => 'ha-whatsapp--hide-mobile-',
				'render_type'  => 'template',
			]
		);

		$this->end_controls_section();
	}

	protected function __tooltip_controls() {

		$this->start_controls_section(
			'_section_tooltip',
			[
				'label' => __( 'Tooltip', 'happy-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'enable_tooltip',
			[
				'label'        => __( 'Enable Tooltip', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'tooltip_text',
			[
				'label'       => __( 'Tooltip Text', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Chat with us!', 'happy-elementor-addons' ),
				'label_block' => true,
				'dynamic'     => [ 'active' => true ],
				'condition'   => [
					'enable_tooltip' => 'yes',
				],
			]
		);

		$this->add_control(
			'tooltip_position',
			[
				'label'        => __( 'Tooltip Direction', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'left',
				'options'      => [
					'top'    => __( 'Top', 'happy-elementor-addons' ),
					'bottom' => __( 'Bottom', 'happy-elementor-addons' ),
					'left'   => __( 'Left', 'happy-elementor-addons' ),
					'right'  => __( 'Right', 'happy-elementor-addons' ),
				],
				'prefix_class' => 'ha-whatsapp--tooltip-',
				'render_type'  => 'template',
				'condition'    => [
					'enable_tooltip' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __animation_controls() {

		$this->start_controls_section(
			'_section_animation',
			[
				'label' => __( 'Animation', 'happy-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'enable_pulse',
			[
				'label'        => __( 'Enable Pulse Animation', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'prefix_class' => 'ha-whatsapp--pulse-',
				'render_type'  => 'template',
			]
		);

		$this->add_control(
			'enable_entrance',
			[
				'label'        => __( 'Enable Entrance Animation', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
				'prefix_class' => 'ha-whatsapp--entrance-',
				'render_type'  => 'template',
			]
		);

		$this->add_control(
			'entrance_delay',
			[
				'label'      => __( 'Entrance Delay (seconds)', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 's' ],
				'default'    => [
					'size' => 1.5,
					'unit' => 's',
				],
				'range'      => [
					's' => [ 'min' => 0, 'max' => 10, 'step' => 0.1 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .ha-whatsapp-button' => 'animation-delay: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'enable_entrance' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register widget style controls
	 */
	protected function register_style_controls() {
		$this->__button_style_controls();
		$this->__text_style_controls();
		$this->__floating_chat_style_controls();
		$this->__tooltip_style_controls();
	}

	protected function __floating_chat_style_controls() {

		$this->start_controls_section(
			'_section_style_floating_chat',
			[
				'label'     => __( 'Floating Chat Box', 'happy-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'button_type' => 'floating_chat',
				],
			]
		);

		$this->add_control(
			'chat_box_width',
			[
				'label'      => __( 'Box Width', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [ 'min' => 250, 'max' => 500 ],
				],
				'default'    => [
					'size' => 330,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .ha-whatsapp-popup' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_chat_header',
			[
				'label'     => __( 'Header', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'chat_header_text_color',
			[
				'label'     => __( 'Text Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ha-whatsapp-popup__header-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ha-whatsapp-popup__header-status' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ha-whatsapp-popup__close' => 'color: {{VALUE}}; opacity: 0.8;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'chat_header_name_typography',
				'label'    => __( 'Name Typography', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-whatsapp-popup__header-title',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'chat_header_status_typography',
				'label'    => __( 'Status Typography', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-whatsapp-popup__header-status',
			]
		);

		$this->add_control(
			'chat_header_bg_color',
			[
				'label'     => __( 'Background Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#075E54',
				'selectors' => [
					'{{WRAPPER}} .ha-whatsapp-popup__header' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_chat_message',
			[
				'label'     => __( 'Message Bubble', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'chat_bubble_bg_color',
			[
				'label'     => __( 'Background Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ha-whatsapp-popup__message-bubble' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .ha-whatsapp-popup__message-bubble::before' => 'border-right-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'chat_bubble_text_color',
			[
				'label'     => __( 'Text Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333333',
				'selectors' => [
					'{{WRAPPER}} .ha-whatsapp-popup__message-bubble' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'chat_bubble_typography',
				'selector' => '{{WRAPPER}} .ha-whatsapp-popup__message-bubble',
			]
		);

		$this->add_control(
			'heading_chat_popup_button',
			[
				'label'     => __( 'Popup Button', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'chat_popup_button_typography',
				'selector' => '{{WRAPPER}} .ha-whatsapp-popup__footer-btn',
			]
		);

		$this->add_control(
			'chat_popup_button_bg_color',
			[
				'label'     => __( 'Background Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#25D366',
				'selectors' => [
					'{{WRAPPER}} .ha-whatsapp-popup__footer-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'chat_popup_button_text_color',
			[
				'label'     => __( 'Text Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ha-whatsapp-popup__footer-btn' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ha-whatsapp-popup__footer-btn svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_chat_popup_close_button',
			[
				'label'     => __( 'Close Button', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( '_tabs_close_button' );
		$this->start_controls_tab(
			'_tab_close_button_normal',
			[
				'label' => __( 'Normal', 'happy-addons-pro' ),
			]
		);

		$this->add_control(
			'close_button_color',
			[
				'label'     => __( 'Color', 'happy-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} button.ha-whatsapp-popup__close' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'close_button_bg_color',
			[
				'label'     => __( 'Background Color', 'happy-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} button.ha-whatsapp-popup__close' => 'background: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'_tab_close_button_hover',
			[
				'label' => __( 'Hover', 'happy-addons-pro' ),
			]
		);

		$this->add_control(
			'close_button_hover_color',
			[
				'label'     => __( 'Color', 'happy-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} button.ha-whatsapp-popup__close:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'nav_active_bg_color',
			[
				'label'     => __( 'Background Color', 'happy-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} button.ha-whatsapp-popup__close:hover' => 'background: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function __button_style_controls() {

		$this->start_controls_section(
			'_section_style_button',
			[
				'label' => __( 'Button', 'happy-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'      => __( 'Icon Size', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 28,
					'unit' => 'px',
				],
				'range'      => [
					'px' => [ 'min' => 10, 'max' => 100 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .ha-whatsapp-link .ha-whatsapp-icon, {{WRAPPER}} .ha-whatsapp-link .ha-whatsapp-close-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ha-whatsapp-link .ha-whatsapp-icon svg, {{WRAPPER}} .ha-whatsapp-link .ha-whatsapp-close-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_width',
			[
				'label'      => __( 'Width', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 60,
					'unit' => 'px',
				],
				'range'      => [
					'px' => [ 'min' => 30, 'max' => 300 ],
				],
				'selectors'  => [
					'{{WRAPPER}}.ha-whatsapp--style-icon-only .ha-whatsapp-link' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.ha-whatsapp-chat-opened .ha-whatsapp-link' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
				'condition'  => [
					'button_style' => 'icon-only',
				],
			]
		);

		$this->add_responsive_control(
			'button_height',
			[
				'label'      => __( 'Height', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 60,
					'unit' => 'px',
				],
				'range'      => [
					'px' => [ 'min' => 30, 'max' => 300 ],
				],
				'selectors'  => [
					'{{WRAPPER}}.ha-whatsapp--style-icon-only .ha-whatsapp-link' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.ha-whatsapp-chat-opened .ha-whatsapp-link' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
				'condition'  => [
					'button_style' => 'icon-only',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => __( 'Padding', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ha-whatsapp-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'button_style!' => 'icon-only',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'button_border',
				'selector'  => '{{WRAPPER}} .ha-whatsapp-link',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label'      => __( 'Border Radius', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ha-whatsapp-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( '_tabs_button_colors' );

		$this->start_controls_tab(
			'_tab_button_normal',
			[
				'label' => __( 'Normal', 'happy-elementor-addons' ),
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => __( 'Icon Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ha-whatsapp-link .ha-whatsapp-icon, {{WRAPPER}} .ha-whatsapp-link .ha-whatsapp-close-icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .ha-whatsapp-link .ha-whatsapp-icon svg, {{WRAPPER}} .ha-whatsapp-link .ha-whatsapp-close-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label'     => __( 'Background Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#25D366',
				'selectors' => [
					'{{WRAPPER}} .ha-whatsapp-link'        => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .ha-whatsapp-pulse-ring'  => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .ha-whatsapp-link',
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
			'icon_hover_color',
			[
				'label'     => __( 'Icon Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-whatsapp-link:hover .ha-whatsapp-icon, {{WRAPPER}} .ha-whatsapp-link:hover .ha-whatsapp-close-icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .ha-whatsapp-link:hover .ha-whatsapp-icon svg, {{WRAPPER}} .ha-whatsapp-link:hover .ha-whatsapp-close-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_bg_color',
			[
				'label'     => __( 'Background Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-whatsapp-link:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .ha-whatsapp-link:hover',
			]
		);

		// $this->add_control(
		// 	'button_hover_transform',
		// 	[
		// 		'label'     => __( 'Scale on Hover', 'happy-elementor-addons' ),
		// 		'type'      => Controls_Manager::SLIDER,
		// 		'default'   => [
		// 			'size' => 1.1,
		// 		],
		// 		'range'     => [
		// 			'px' => [ 'min' => 0.5, 'max' => 2, 'step' => 0.01 ],
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .ha-whatsapp-link:hover' => 'transform: scale({{SIZE}});',
		// 		],
		// 	]
		// );

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function __text_style_controls() {

		$this->start_controls_section(
			'_section_style_text',
			[
				'label'     => __( 'Button Text', 'happy-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'button_style!' => 'icon-only',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .ha-whatsapp-text',
			]
		);

		$this->add_responsive_control(
			'icon_text_gap',
			[
				'label'      => __( 'Gap Between Icon & Text', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 8,
					'unit' => 'px',
				],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 50 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .ha-whatsapp-link' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( '_tabs_text_colors' );

		$this->start_controls_tab(
			'_tab_text_normal',
			[
				'label' => __( 'Normal', 'happy-elementor-addons' ),
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => __( 'Text Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ha-whatsapp-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_text_hover',
			[
				'label' => __( 'Hover', 'happy-elementor-addons' ),
			]
		);

		$this->add_control(
			'text_hover_color',
			[
				'label'     => __( 'Text Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-whatsapp-link:hover .ha-whatsapp-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function __tooltip_style_controls() {

		$this->start_controls_section(
			'_section_style_tooltip',
			[
				'label'     => __( 'Tooltip', 'happy-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'enable_tooltip' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tooltip_typography',
				'selector' => '{{WRAPPER}} .ha-whatsapp-tooltip',
			]
		);

		$this->add_control(
			'tooltip_bg_color',
			[
				'label'     => __( 'Background Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333333',
				'selectors' => [
					'{{WRAPPER}} .ha-whatsapp-tooltip'        => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .ha-whatsapp-tooltip::after' => 'border-color: {{VALUE}} transparent transparent transparent;',
				],
			]
		);

		$this->add_control(
			'tooltip_text_color',
			[
				'label'     => __( 'Text Color', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ha-whatsapp-tooltip' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'tooltip_padding',
			[
				'label'      => __( 'Padding', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [
					'top'    => '6',
					'right'  => '12',
					'bottom' => '6',
					'left'   => '12',
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .ha-whatsapp-tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'tooltip_border_radius',
			[
				'label'      => __( 'Border Radius', 'happy-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'    => '4',
					'right'  => '4',
					'bottom' => '4',
					'left'   => '4',
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .ha-whatsapp-tooltip' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$phone   = ! empty( $settings['phone_number'] ) ? preg_replace( '/[^0-9]/', '', $settings['phone_number'] ) : '';
		$message = ! empty( $settings['pre_filled_message'] ) ? urlencode( $settings['pre_filled_message'] ) : '';
		$target  = ! empty( $settings['open_in'] ) ? $settings['open_in'] : '_blank';

		if ( empty( $phone ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="ha-whatsapp-notice" style="padding:15px;background:#25D366;color:#fff;border-radius:8px;text-align:center;">';
				echo '<i class="fab fa-whatsapp" style="font-size:24px;margin-bottom:8px;display:block;"></i>';
				echo esc_html__( 'WhatsApp Button: Please enter a phone number in the content settings.', 'happy-elementor-addons' );
				echo '</div>';
			}
			return;
		}

		$wa_url = 'https://wa.me/' . esc_attr( $phone );
		if ( ! empty( $message ) ) {
			$wa_url .= '?text=' . $message;
		}

		$button_type   = ! empty( $settings['button_type'] ) ? $settings['button_type'] : 'standard';
		$button_style  = ! empty( $settings['button_style'] ) ? $settings['button_style'] : 'icon-only';
		$enable_tooltip = ( 'yes' === $settings['enable_tooltip'] );
		$tooltip_text  = ! empty( $settings['tooltip_text'] ) ? $settings['tooltip_text'] : '';
		$button_text   = ! empty( $settings['button_text'] ) ? $settings['button_text'] : '';
		if ( 'icon-only' !== $button_style && $button_text ){
			$button_style_class='ha-whatsapp-pulse-non-ring';
		}else{
			$button_style_class='ha-whatsapp-pulse-ring';
		}

		$this->add_render_attribute( 'wrapper', 'class', 'ha-whatsapp-button' );

		$chat_theme = ! empty( $settings['chat_theme'] ) ? $settings['chat_theme'] : 'general';
		$this->add_render_attribute( 'wrapper', 'class', 'ha-whatsapp--chat-theme-' . $chat_theme );
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php if ( 'floating_chat' === $button_type ) : ?>
				<div class="ha-whatsapp-popup">
					<div class="ha-whatsapp-popup__header">
						<div class="ha-whatsapp-popup__header-agent">
							<?php if ( ! empty( $settings['agent_image']['url'] ) ) : ?>
								<div class="ha-whatsapp-popup__header-avatar">
									<img src="<?php echo esc_url( $settings['agent_image']['url'] ); ?>" alt="<?php echo esc_attr( $settings['agent_name'] ); ?>">
									<?php if ( 'yes' === $settings['show_status_dot'] ) : ?>
										<span class="ha-whatsapp-popup__header-status-dot"></span>
									<?php endif; ?>
								</div>
							<?php endif; ?>
							<div class="ha-whatsapp-popup__header-info">
								<h4 class="ha-whatsapp-popup__header-title"><?php echo esc_html( $settings['agent_name'] ); ?></h4>
								<span class="ha-whatsapp-popup__header-status"><?php echo esc_html( $settings['agent_status'] ); ?></span>
							</div>
						</div>
						<button type="button" class="ha-whatsapp-popup__close" aria-label="<?php echo esc_attr__( 'Close', 'happy-elementor-addons' ); ?>">
							<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M1 1L11 11M11 1L1 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</button>
					</div>

					<div class="ha-whatsapp-popup__body">
						<?php if ( 'yes' === $settings['show_typing_indicator'] ) : ?>
							<div class="ha-whatsapp-popup__typing-indicator">
								<span></span><span></span><span></span>
							</div>
						<?php endif; ?>
						<div class="ha-whatsapp-popup__message-bubble">
							<?php echo nl2br( esc_html( $settings['chat_initial_message'] ) ); ?>
						</div>
					</div>

					<div class="ha-whatsapp-popup__footer">
						<a class="ha-whatsapp-popup__footer-btn"
						   href="<?php echo esc_url( $wa_url ); ?>"
						   target="<?php echo esc_attr( $target ); ?>"
						   rel="noopener noreferrer">
							<span class="ha-whatsapp-popup__footer-icon">
								<i class="fab fa-whatsapp"></i>
							</span>
							<?php echo esc_html( $settings['chat_button_text'] ); ?>
						</a>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( $enable_tooltip && $tooltip_text ) : ?>
				<span class="ha-whatsapp-tooltip"><?php echo esc_html( $tooltip_text ); ?></span>
			<?php endif; ?>

			<a class="ha-whatsapp-link"
			   href="<?php echo ( 'floating_chat' === $button_type ) ? 'javascript:void(0);' : esc_url( $wa_url ); ?>"
			   target="<?php echo ( 'floating_chat' === $button_type ) ? '_self' : esc_attr( $target ); ?>"
			   <?php if ( 'standard' === $button_type ) : ?>
			   rel="noopener noreferrer"
			   <?php endif; ?>
			   aria-label="<?php echo esc_attr__( 'Chat on WhatsApp', 'happy-elementor-addons' ); ?>">
				<span class="ha-whatsapp-icon">
					<?php ha_render_icon( $settings, 'icon', 'selected_icon', [ 'aria-hidden' => 'true' ] ); ?>
				</span>
				<?php if ( 'floating_chat' === $button_type ) : ?>
					<span class="ha-whatsapp-close-icon">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</span>
				<?php endif; ?>
				<?php if ( 'icon-only' !== $button_style && $button_text ) : ?>
					<span class="ha-whatsapp-text"><?php echo esc_html( $button_text ); ?></span>
				<?php endif; ?>
			</a>

			<span class="<?php echo esc_attr( $button_style_class ); ?>"></span>
		</div>
		<?php
	}
}
