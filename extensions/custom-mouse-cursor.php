<?php

	namespace Happy_Addons\Elementor\Extensions;

	// Elementor Classes.
	use \Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;

	defined( 'ABSPATH' ) || die();

	class Custom_Mouse_Cursor {

		/**
		 * @var mixed
		 */
		private static $instance = null;

		/**
		 * @var mixed
		 */
		private $load_script = null;

		public static function instance() {
			if ( null === ( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function init() {

			// Enqueue the required JS file.
			add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts'] );
			add_action( 'wp_enqueue_scripts', [ $this, 'register_styles'] );

			add_action( 'elementor/preview/enqueue_scripts', [ $this, 'enqueue_preview_scripts'] );

			// Creates Custom Mouse Cursor tab at the end of layout/content tab.
			add_action( 'elementor/element/section/section_layout/after_section_end', [ $this, 'register_controls'], 10 );
			add_action( 'elementor/element/column/section_advanced/after_section_end', [ $this, 'register_controls'], 10 );
			add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'register_controls'], 10 );

			add_action( 'elementor/element/container/section_layout/after_section_end', [ $this, 'register_controls'], 10 );

			//Register on Page Settings
			add_action( 'elementor/documents/register_controls', [ $this, 'site_settings_controls'], 10 );
			add_action( 'wp_footer', [ $this, 'render_custom_mouse_cursor_html'] );
		}

		public function register_scripts() {
			$suffix = ha_is_script_debug_enabled() ? '.' : '.min.';

			wp_register_script(
				'happy-custom-mouse-cursor',
				HAPPY_ADDONS_ASSETS . 'js/custom-mouse-cursor' . $suffix . 'js',
				['jquery', 'happy-elementor-addons', 'mouse-follower'],
				HAPPY_ADDONS_VERSION,
				true
			);
		}

		public function register_styles() {
			$suffix = ha_is_script_debug_enabled() ? '.' : '.min.';

			wp_register_style(
				'happy-custom-mouse-cursor',
				HAPPY_ADDONS_ASSETS . 'css/widgets/custom-mouse-cursor.min.css',
				['mouse-follower'],
				HAPPY_ADDONS_VERSION
			);
		}

		public function enqueue_preview_scripts() {
			wp_enqueue_script( 'gsap' );
			wp_enqueue_script( 'mouse-follower' );
			wp_enqueue_script( 'happy-custom-mouse-cursor' );

			wp_enqueue_style( 'mouse-follower' );
			wp_enqueue_style( 'happy-custom-mouse-cursor' );
		}

		public function register_controls( $element ) {

			$tab = Controls_Manager::TAB_LAYOUT;
			if ( 'common' == $element->get_name() ) {
				$tab = Controls_Manager::TAB_CONTENT;
			}

			$element->start_controls_section(
				'ha_cmc_section',
				[
					'label' => esc_html__( 'Happy Mouse Cursor', 'happy-elementor-addons' ) . ha_get_section_icon(),
					'tab'   => $tab
				]
			);

			$this->add_content_controls( $element );

			$element->end_controls_section();
		}

		public function add_content_controls( $element ) {

			$element->add_control(
				'ha_cmc_init_notice',
				[
					'type'       => Controls_Manager::ALERT,
					'alert_type' => 'info',
					'heading'    => esc_html__( 'Note:', 'happy-elementor-addons' ),
					'content'    => esc_html__( 'To change mouse cursor for the whole page, use the page settings options.', 'happy-elementor-addons' )
				]
			);

			$element->add_control(
				'ha_cmc_switcher',
				[
					'label'              => __( 'Enable Happy Mouse Cursor', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SWITCHER,
					'render_type'        => 'template',
					'return_value'       => 'yes',
					'style_transfer'     => false,
					'frontend_available' => true,
					'assets'             => [
						'scripts' => [
							[
								'name'       => 'elementor-frontend',
								'conditions' => [
									'terms' => [
										[
											'name'     => 'ha_cmc_switcher',
											'operator' => '===',
											'value'    => 'yes'
										]
									]
								]
							],
							[
								'name'       => 'gsap',
								'conditions' => [
									'terms' => [
										[
											'name'     => 'ha_cmc_switcher',
											'operator' => '===',
											'value'    => 'yes'
										]
									]
								]
							],
							[
								'name'       => 'mouse-follower',
								'conditions' => [
									'terms' => [
										[
											'name'     => 'ha_cmc_switcher',
											'operator' => '===',
											'value'    => 'yes'
										]
									]
								]
							],
							[
								'name'       => 'happy-custom-mouse-cursor',
								'conditions' => [
									'terms' => [
										[
											'name'     => 'ha_cmc_switcher',
											'operator' => '===',
											'value'    => 'yes'
										]
									]
								]
							]
						],
						'styles'  => [
							[
								'name'       => 'mouse-follower',
								'conditions' => [
									'terms' => [
										[
											'name'     => 'ha_cmc_switcher',
											'operator' => '===',
											'value'    => 'yes'
										]
									]
								]
							],
							[
								'name'       => 'happy-custom-mouse-cursor',
								'conditions' => [
									'terms' => [
										[
											'name'     => 'ha_cmc_switcher',
											'operator' => '===',
											'value'    => 'yes'
										]
									]
								]
							]
						]
					]
				]
			);

			if ( 'common' === $element->get_name() ) {
				$element->add_control(
					'ha_cmc_priority_notice',
					[
						'type'       => Controls_Manager::ALERT,
						'alert_type' => 'danger',
						'heading'    => esc_html__( 'Keep in Mind:', 'happy-elementor-addons' ),
						'content'    => esc_html__( 'Enabling custom mouse cursor on a parent element will override the settings of the child element.', 'happy-elementor-addons' ),
						'condition'  => [
							'ha_cmc_switcher' => 'yes'
						]
					]
				);
			}

			$element->add_control(
				'ha_default_cmc',
				[
					'label'              => __( 'Default Cursor', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SELECT,
					'options'            => $this->hm_get_default_cursors(),
					'default'            => 'default',
					'selectors'          => [
						'.elementor-element-' . $element->get_id() => 'cursor: {{VALUE}} !important;',
						'{{WRAPPER}}'                              => 'cursor: {{VALUE}} !important;'
					],
					'condition'          => [
						'ha_cmc_switcher' => 'yes'
					],
					'style_transfer'     => true,
					'render_type'        => 'template',
					'frontend_available' => true
				]
			);

			$element->add_control(
				'ha_cmc_type',
				[
					'label'              => __( 'Cursor Type', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SELECT,
					'options'            => [
						'text'  => __( 'Text', 'happy-elementor-addons' ),
						'color' => __( 'Color', 'happy-elementor-addons' ),
						'icon'  => __( 'Icon', 'happy-elementor-addons' ),
						'image' => __( 'Image', 'happy-elementor-addons' ),
						'video' => __( 'Video', 'happy-elementor-addons' )
					],
					'default'            => 'text',
					'condition'          => [
						'ha_cmc_switcher' => 'yes'
					],
					'style_transfer'     => true,
					'render_type'        => 'template',
					'frontend_available' => true
				]
			);

			$this->hm_cmc_icon_control( $element );

			$this->hm_cmc_text_control( $element );

			$this->hm_cmc_image_control( $element );

			$this->hm_cmc_video_control( $element );

			$this->cursor_style_control( $element );
		}

		private function hm_cmc_icon_control( $element ) {

			$element->add_control(
				'ha_cmc_icon',
				[
					'label'              => __( 'Choose Icon', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::ICONS,
					'frontend_available' => true,
					'default'            => [
						'value'   => 'fas fa-mouse-pointer',
						'library' => 'fa-solid'
					],
					'condition'          => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type'     => 'icon'
					]
				]
			);

			$element->add_control(
				'ha_cmc_icon_color',
				[
					'label'              => __( 'Icon Color', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::COLOR,
					'default'            => '#FFF',
					'selectors'          => [
						'.elementor-element-{{ID}}.ha-cursor .mf-cursor-text' => 'color: {{VALUE}}'
					],
					'condition'          => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type'     => 'icon'
					],
					'style_transfer'     => true,
					'frontend_available' => true
				]
			);

			$element->add_control(
				'ha_cmc_icon_size',
				[
					'label'              => __( 'Icon Size', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::NUMBER,
					'default'            => 22,
					'step'               => 1,
					'selectors'          => [
						'.elementor-element-{{ID}}.ha-cursor .mf-cursor-text' => 'font-size: {{VALUE}}px'
					],
					'condition'          => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type'     => 'icon'
					],
					'style_transfer'     => true,
					'frontend_available' => true
				]
			);
		}

		private function hm_cmc_text_control( $element ) {
			$element->add_control(
				'ha_cmc_text',
				[
					'label'              => __( 'Cursor Text', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::TEXT,
					'default'            => 'Happy Addons',
					'frontend_available' => true,
					'label_block'        => true,
					'condition'          => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type'     => 'text'
					]
				]
			);

			$element->add_control(
				'ha_cmc_text_color',
				[
					'label'              => __( 'Text Color', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::COLOR,
					'default'            => '#FFF',
					'selectors'          => [
						'.elementor-element-{{ID}}.ha-cursor .mf-cursor-text' => 'color: {{VALUE}}'
					],
					'condition'          => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type'     => 'text'
					],
					'style_transfer'     => true,
					'frontend_available' => true
				]
			);

			$element->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'               => 'ha_cmc_text_typo',
					'selector'           => '.elementor-element-{{ID}}.ha-cursor .mf-cursor-text ',
					'condition'          => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type'     => 'text'
					],
					'style_transfer'     => true,
					'frontend_available' => true
				]
			);

		}

		private function hm_cmc_image_control( $element ) {
			$element->add_control(
				'ha_cmc_image',
				[
					'label'              => __( 'Choose Image', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::MEDIA,
					'media_types'        => ['image'],
					'frontend_available' => true,
					'condition'          => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type'     => 'image'

					]
				]
			);

			$element->add_control(
				'ha_cmc_image_fit',
				[
					'label'              => __( 'Object Fit', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SELECT,
					'options'            => [
						'contain' => __( 'Contain', 'happy-elementor-addons' ),
						'cover'   => __( 'Cover', 'happy-elementor-addons' ),
						'fill'    => __( 'Fill', 'happy-elementor-addons' )
					],
					'default'            => 'cover',
					'condition'          => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type'     => 'image'

					],
					'selectors'          => [
						'.elementor-element-{{ID}}.ha-cursor .mf-cursor-inner .mf-cursor-media-box img' => 'object-fit: {{VALUE}};'
					],

					'style_transfer'     => true,
					'render_type'        => 'template',
					'frontend_available' => true
				]
			);

			$element->add_responsive_control(
				'ha_cmc_img_width',
				[
					'label'              => __( 'Width', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SLIDER,
					'size_units'         => ['px'],
					'desktop_default'    => [
						'unit' => 'px',
						'size' => 150
					],
					'tablet_default'     => [
						'unit' => 'px',
						'size' => 150
					],
					'mobile_default'     => [
						'unit' => 'px',
						'size' => 100
					],
					'range'              => [
						'px' => [
							'min' => 0,
							'max' => 500
						]
					],
					'default'            => [
						'unit' => 'px',
						'size' => 150
					],
					'selectors'          => [
						'.elementor-element-{{ID}}.ha-cursor .mf-cursor-inner .mf-cursor-media-box'     => 'width: {{SIZE}}{{UNIT}}',
						'.elementor-element-{{ID}}.ha-cursor .mf-cursor-inner .mf-cursor-media-box img' => 'width: {{SIZE}}{{UNIT}}'
					],
					'condition'          => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type'     => 'image'
					],
					'style_transfer'     => true,
					'frontend_available' => true
				]
			);
			$element->add_responsive_control(
				'ha_cmc_img_height',
				[
					'label'              => __( 'Height', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SLIDER,
					'size_units'         => ['px'],
					'desktop_default'    => [
						'unit' => 'px',
						'size' => 150
					],
					'tablet_default'     => [
						'unit' => 'px',
						'size' => 150
					],
					'mobile_default'     => [
						'unit' => 'px',
						'size' => 100
					],
					'range'              => [
						'px' => [
							'min' => 0,
							'max' => 500
						]
					],
					'default'            => [
						'unit' => 'px',
						'size' => 150
					],
					'selectors'          => [
						'.elementor-element-{{ID}}.ha-cursor .mf-cursor-inner .mf-cursor-media-box'     => 'height: {{SIZE}}{{UNIT}}',
						'.elementor-element-{{ID}}.ha-cursor .mf-cursor-inner .mf-cursor-media-box img' => 'height: {{SIZE}}{{UNIT}}'
					],
					'condition'          => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type'     => 'image'
					],
					'style_transfer'     => true,
					'frontend_available' => true
				]
			);

			$element->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'               => 'ha_cmc_image_border',
					'selector'           => '.elementor-element-{{ID}}.ha-cursor .mf-cursor-inner .mf-cursor-media-box img',
					'condition'          => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type'     => 'image'
					],
					'style_transfer'     => true,
					'frontend_available' => true
				]
			);

			$element->add_responsive_control(
				'ha_cmc_img_border_radius',
				[
					'label'      => __( 'Border Radius', 'happy-elementor-addons' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => ['px', '%'],
					'default'    => [
						'unit'   => 'px',
						'top'    => '0',
						'right'  => '0',
						'bottom' => '0',
						'left'   => '0'
					],
					'selectors'  => [
						'.elementor-element-{{ID}}.ha-cursor .mf-cursor-inner .mf-cursor-media-box'     => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'.elementor-element-{{ID}}.ha-cursor .mf-cursor-inner .mf-cursor-media-box img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					],
					'condition'  => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type'     => 'image'
					]
				]
			);

			$element->add_responsive_control(
				'ha_cmc_img_rotate',
				[
					'label'              => __( '360 Rotate', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::NUMBER,
					'default'            => '0',
					'step'               => 1,
					'min'                => 0,
					'max'                => 360,
					'selectors'          => [
						'.elementor-element-{{ID}}.ha-cursor.-media .mf-cursor-media-box' => 'transform: rotate({{VALUE}}deg)'
					],
					'condition'          => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type'     => 'image'
					],
					'style_transfer'     => true,
					'frontend_available' => true
				]
			);

			$element->add_control(
				'ha_cmc_img_opacity',
				[
					'label'              => __( 'Opacity', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::NUMBER,
					'default'            => '1',
					'step'               => 0.1,
					'min'                => 0,
					'max'                => 1,
					'selectors'          => [
						'.elementor-element-{{ID}}.ha-cursor.-media .mf-cursor-media-box img' => 'opacity: {{VALUE}}',
						'.elementor-element-{{ID}}.ha-cursor.ha-media:before'                 => 'background: transparent'
					],
					'condition'          => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type'     => 'image'
					],
					'style_transfer'     => true,
					'frontend_available' => true
				]
			);

			$element->add_control(
				'ha_enable_img_rotation_switcher',
				[
					'label'                => __( 'Enable Rotation', 'happy-elementor-addons' ),
					'type'                 => Controls_Manager::SWITCHER,
					'render_type'          => 'template',
					'prefix_class'         => 'ha-cmc-img-rotation',
					'return_value'         => 'yes',
					'style_transfer'       => false,
					'frontend_available'   => true,
					'selectors_dictionary' => [
						''    => '',
						'yes' => 'animation: haImageRotationClockwise 3s linear infinite;'
					],
					'selectors'            => [
						'.elementor-element-{{ID}}.ha-cursor.-media .ha-cursor-media .ha-cursor-media-box' => '{{VALUE}}'
					],
					'condition'            => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type'     => 'image'
					]
				]
			);

			$element->add_control(
				'ha_cmc_img_rotation_delay',
				[
					'label'              => __( 'Delay', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SLIDER,
					'separator'          => 'before',
					'size_units'         => [],
					'range'              => [
						'px' => [
							'min'  => 1,
							'max'  => 50,
							'step' => 0.1
						]
					],
					'default'            => [
						'unit' => 'px',
						'size' => 3
					],
					'selectors'          => [
						'.elementor-element-{{ID}}.ha-cursor.-media .ha-cursor-media .ha-cursor-media-box' => 'animation-duration: {{SIZE}}s;'
					],
					'condition'          => [
						'ha_cmc_switcher'                 => 'yes',
						'ha_enable_img_rotation_switcher' => 'yes',
						'ha_cmc_type'                     => 'image'
					],
					'style_transfer'     => true,
					'frontend_available' => true
				]
			);

			$element->add_control(
				'ha_cmc_img_rotation_direction',
				[
					'label'                => __( 'Direction', 'happy-elementor-addons' ),
					'type'                 => Controls_Manager::CHOOSE,
					'options'              => [

						'anti-clockwise' => [
							'title' => __( 'Anti Clockwise', 'happy-elementor-addons' ),
							'icon'  => 'eicon-chevron-left'
						],
						'clockwise'      => [
							'title' => __( 'Clockwise', 'happy-elementor-addons' ),
							'icon'  => 'eicon-chevron-right'
						]
					],
					'default'              => 'clockwise',
					'selectors_dictionary' => [
						'clockwise'      => 'animation: haImageRotationClockwise 3s linear infinite;',
						'anti-clockwise' => 'animation: haImageRotationAntiClockwise 3s linear infinite;'
					],
					'selectors'            => [
						'.elementor-element-{{ID}}.ha-cursor.-media .ha-cursor-media .ha-cursor-media-box' => '{{VALUE}}'
					],
					'condition'            => [
						'ha_cmc_switcher'                 => 'yes',
						'ha_cmc_type'                     => 'image',
						'ha_enable_img_rotation_switcher' => 'yes'
					]
				]
			);

			$element->add_group_control(
				Group_Control_Css_Filter::get_type(),
				[
					'name'      => 'ha_cmc_img_css_filters',
					'selector'  => '.elementor-element-{{ID}}.ha-cursor.-media .ha-cursor-media .ha-cursor-media-box img',
					'condition' => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type'     => 'image'
					]
				]
			);
		}

		private function hm_cmc_video_control( $element ) {
			$element->add_control(
				'ha_cmc_video',
				[
					'label'              => __( 'Choose Video', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::MEDIA,
					'media_types'        => ['video'],
					'frontend_available' => true,
					'condition'          => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type'     => 'video'

					]
				]
			);

			$element->add_control(
				'ha_cmc_video_fit',
				[
					'label'              => __( 'Object Fit', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SELECT,
					'options'            => [
						'cover' => __( 'Cover', 'happy-elementor-addons' ),
						'fill'  => __( 'Fill', 'happy-elementor-addons' )
					],
					'default'            => 'cover',
					'condition'          => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type'     => 'video'

					],
					'selectors'          => [
						'.elementor-element-{{ID}}.ha-cursor .mf-cursor-inner .mf-cursor-media-box video' => 'object-fit: {{VALUE}};'
					],

					'style_transfer'     => true,
					'render_type'        => 'template',
					'frontend_available' => true
				]
			);

			$element->add_responsive_control(
				'ha_cmc_video_width',
				[
					'label'              => __( 'Width', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SLIDER,
					'size_units'         => ['px'],
					'desktop_default'    => [
						'unit' => 'px',
						'size' => 150
					],
					'tablet_default'     => [
						'unit' => 'px',
						'size' => 150
					],
					'mobile_default'     => [
						'unit' => 'px',
						'size' => 100
					],
					'range'              => [
						'px' => [
							'min' => 0,
							'max' => 500
						]
					],
					'default'            => [
						'unit' => 'px',
						'size' => 150
					],
					'selectors'          => [
						'.elementor-element-{{ID}}.ha-cursor .mf-cursor-inner .mf-cursor-media' => 'width: {{SIZE}}{{UNIT}}'
					],
					'condition'          => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type'     => 'video'
					],
					'style_transfer'     => true,
					'frontend_available' => true
				]
			);
			$element->add_responsive_control(
				'ha_cmc_video_height',
				[
					'label'              => __( 'Height', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SLIDER,
					'size_units'         => ['px'],
					'desktop_default'    => [
						'unit' => 'px',
						'size' => 150
					],
					'tablet_default'     => [
						'unit' => 'px',
						'size' => 150
					],
					'mobile_default'     => [
						'unit' => 'px',
						'size' => 100
					],
					'range'              => [
						'px' => [
							'min' => 0,
							'max' => 500
						]
					],
					'default'            => [
						'unit' => 'px',
						'size' => 150
					],
					'selectors'          => [
						'.elementor-element-{{ID}}.ha-cursor .mf-cursor-inner .mf-cursor-media' => 'height: {{SIZE}}{{UNIT}}'
					],
					'condition'          => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type'     => 'video'
					],
					'style_transfer'     => true,
					'frontend_available' => true
				]
			);

			$element->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'               => 'ha_cmc_video_border',
					'selector'           => '.elementor-element-{{ID}}.ha-cursor .mf-cursor-inner .mf-cursor-media-box',
					'condition'          => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type'     => 'video'
					],
					'style_transfer'     => true,
					'frontend_available' => true
				]
			);

			$element->add_responsive_control(
				'ha_cmc_video_border_radius',
				[
					'label'      => __( 'Border Radius', 'happy-elementor-addons' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => ['px', '%'],
					'default'    => [
						'unit'   => '%',
						'top'    => '50',
						'right'  => '50',
						'bottom' => '50',
						'left'   => '50'
					],
					'selectors'  => [
						'.elementor-element-{{ID}}.ha-cursor .mf-cursor-inner .mf-cursor-media-box'       => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'.elementor-element-{{ID}}.ha-cursor .mf-cursor-inner .mf-cursor-media-box video' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					],
					'condition'  => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type'     => 'video'
					]
				]
			);

			$element->add_control(
				'ha_cmc_video_opacity',
				[
					'label'              => __( 'Opacity', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::NUMBER,
					'default'            => '1',
					'step'               => 0.1,
					'min'                => 0,
					'max'                => 1,
					'selectors'          => [
						'.elementor-element-{{ID}}.ha-cursor.-media .mf-cursor-media-box' => 'opacity: {{VALUE}}',
						'.elementor-element-{{ID}}.ha-cursor.ha-media:before'             => 'background: transparent'
					],
					'condition'          => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type'     => 'video'
					],
					'style_transfer'     => true,
					'frontend_available' => true
				]
			);
		}

		private function cursor_style_control( $element ) {
			$element->add_control(
				'ha_cmc_cursor_style_heading',
				[
					'label'     => esc_html__( 'Background Style', 'happy-elementor-addons' ),
					'separator' => 'after',
					'type'      => Controls_Manager::HEADING,
					'condition' => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type!'    => ['video', 'image']
					]
				]
			);

			$element->add_control(
				'ha_cmc_cursor_blur_switcher',
				[
					'label'                => __( 'Enable Blur', 'happy-elementor-addons' ),
					'type'                 => Controls_Manager::SWITCHER,
					'render_type'          => 'template',
					'return_value'         => 'yes',
					'style_transfer'       => false,
					'frontend_available'   => true,
					'selectors_dictionary' => [
						''    => '',
						'yes' => 'backdrop-filter: blur(6px);'
					],
					'selectors'            => [
						'.elementor-element-{{ID}}.ha-cursor.mf-cursor.-text:before' => '{{VALUE}}'
					],
					'condition'            => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type!'    => ['video', 'image']
					]
				]
			);

			$element->add_control(
				'ha_cmc_cursor_blur',
				[
					'label'              => __( 'Blur', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SLIDER,
					'size_units'         => ['px'],

					'range'              => [
						'px' => [
							'min' => 1,
							'max' => 100
						]
					],
					'default'            => [
						'unit' => 'px',
						'size' => 6
					],
					'selectors'          => [
						'.elementor-element-{{ID}}.ha-cursor.mf-cursor.-text:before' => 'backdrop-filter: blur({{SIZE}}{{UNIT}})'
					],
					'style_transfer'     => true,
					'frontend_available' => true,
					'condition'          => [
						'ha_cmc_switcher'             => 'yes',
						'ha_cmc_cursor_blur_switcher' => 'yes',
						'ha_cmc_type!'                => ['video', 'image']
					]
				]
			);

			$element->add_control(
				'ha_cmc_cursor_blur_bg',
				[
					'label'              => __( 'Background Color', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::COLOR,
					'default'            => '#FFFFFF33',
					'selectors'          => [
						'.elementor-element-{{ID}}.ha-cursor.mf-cursor:before' => 'background: {{VALUE}}'
					],
					'condition'          => [
						'ha_cmc_switcher'             => 'yes',
						'ha_cmc_cursor_blur_switcher' => 'yes',
						'ha_cmc_type!'                => ['video', 'image']
					],
					'style_transfer'     => true,
					'frontend_available' => true
				]
			);

			$element->add_control(
				'ha_cmc_cursor_bg',
				[
					'label'              => __( 'Background Color', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::COLOR,
					'default'            => '#000',
					'selectors'          => [
						'.elementor-element-{{ID}}.ha-cursor.mf-cursor:before' => 'background: {{VALUE}}'
					],
					'condition'          => [
						'ha_cmc_switcher'              => 'yes',
						'ha_cmc_cursor_blur_switcher!' => 'yes',
						'ha_cmc_type!'                 => ['video', 'image']
					],
					'style_transfer'     => true,
					'frontend_available' => true
				]
			);

			$element->add_responsive_control(
				'ha_cmc_cursor_box_width',
				[
					'label'              => __( 'Width', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SLIDER,
					'size_units'         => ['px'],
					'desktop_default'    => [
						'unit' => 'px',
						'size' => 80
					],
					'tablet_default'     => [
						'unit' => 'px',
						'size' => 80
					],
					'mobile_default'     => [
						'unit' => 'px',
						'size' => 80
					],
					'range'              => [
						'px' => [
							'min' => 0,
							'max' => 500
						]
					],
					'default'            => [
						'unit' => 'px',
						'size' => 80
					],
					'selectors'          => [
						'.elementor-element-{{ID}}.ha-cursor.mf-cursor.-text:before' => 'width: {{SIZE}}{{UNIT}}',
						'.elementor-element-{{ID}}.ha-cursor.-media:before'          => 'width: {{SIZE}}{{UNIT}}',
						'.elementor-element-{{ID}}.ha-cursor .ha-cursor-text'        => 'width: {{SIZE}}{{UNIT}}'
					],
					'style_transfer'     => true,
					'frontend_available' => true,
					'condition'          => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type!'    => ['video', 'image']
					]
				]
			);
			$element->add_responsive_control(
				'ha_cmc_cursor_box_height',
				[
					'label'              => __( 'Height', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SLIDER,
					'size_units'         => ['px'],
					'desktop_default'    => [
						'unit' => 'px',
						'size' => 80
					],
					'tablet_default'     => [
						'unit' => 'px',
						'size' => 80
					],
					'mobile_default'     => [
						'unit' => 'px',
						'size' => 80
					],
					'range'              => [
						'px' => [
							'min' => 0,
							'max' => 500
						]
					],
					'default'            => [
						'unit' => 'px',
						'size' => 80
					],
					'selectors'          => [
						'.elementor-element-{{ID}}.ha-cursor.mf-cursor.-text:before' => 'height: {{SIZE}}{{UNIT}}',
						'.elementor-element-{{ID}}.ha-cursor.-media:before'          => 'height: {{SIZE}}{{UNIT}}',
						'.elementor-element-{{ID}}.ha-cursor .ha-cursor-text'        => 'height: {{SIZE}}{{UNIT}}'
					],
					'style_transfer'     => true,
					'frontend_available' => true,
					'condition'          => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type!'    => ['video', 'image']
					]
				]
			);

			$element->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'      => 'ha_cmc_box_shadow',
					'selector'  => '.elementor-element-{{ID}}.ha-cursor.mf-cursor.-text:before, .elementor-element-{{ID}}.ha-cursor.mf-cursor.-media:before',
					'condition' => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type!'    => ['video']
					]
				]
			);

			$element->add_responsive_control(
				'ha_cmc_cursor_padding',
				[
					'label'      => __( 'Padding', 'happy-elementor-addons' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => ['px', '%'],
					'default'    => [
						'unit'   => 'px',
						'top'    => '0',
						'right'  => '0',
						'bottom' => '0',
						'left'   => '0'
					],
					'selectors'  => [
						'.elementor-element-{{ID}}.ha-cursor.mf-cursor .ha-cursor-text'      => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'.elementor-element-{{ID}}.ha-cursor.mf-cursor .ha-cursor-media-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					],
					'condition'  => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type!'    => ['video', 'image']
					]
				]
			);

			$element->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'               => 'ha_cmc_cursor_border',
					'selector'           => '.elementor-element-{{ID}}.ha-cursor.mf-cursor:before',
					'condition'          => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type!'    => ['video', 'image']
					],
					'style_transfer'     => true,
					'frontend_available' => true
				]
			);

			$element->add_responsive_control(
				'ha_cmc_cursor_border_radius',
				[
					'label'      => __( 'Border Radius', 'happy-elementor-addons' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => ['px', '%'],
					'default'    => [
						'unit'   => 'px',
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => ''
					],
					'selectors'  => [
						'.elementor-element-{{ID}}.ha-cursor.mf-cursor:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					],
					'condition'  => [
						'ha_cmc_switcher' => 'yes',
						'ha_cmc_type!'    => ['video', 'image']
					]
				]
			);
		}

		private function hm_get_default_cursors() {

			return [
				'default'      => 'Default',
				'none'         => 'None',
				'alias'        => 'alias',
				'all-scroll'   => 'all-scroll',
				'auto'         => 'auto',
				'cell'         => 'cell',
				'col-resize'   => 'col-resize',
				'context-menu' => 'context-menu',
				'copy'         => 'copy',
				'crosshair'    => 'crosshair',
				'e-resize'     => 'e-resize',
				'ew-resize'    => 'ew-resize',
				'grab'         => 'grab',
				'help'         => 'help',
				'move'         => 'move',
				'n-resize'     => 'n-resize',
				'ne-resize'    => 'ne-resize',
				'nesw-resize'  => 'nesw-resize',
				'ns-resize'    => 'ns-resize',
				'nw-resize'    => 'nw-resize',
				'nwse-resize'  => 'nwse-resize',
				'no-drop'      => 'no-drop',
				'not-allowed'  => 'not-allowed',
				'pointer'      => 'pointer',
				'progress'     => 'progress',
				'row-resize'   => 'row-resize',
				's-resize'     => 's-resize',
				'se-resize'    => 'se-resize',
				'sw-resize'    => 'sw-resize',
				'text'         => 'text',
				'w-resize'     => 'w-resize',
				'wait'         => 'wait',
				'zoom-in'      => 'zoom-in',
				'zoom-out'     => 'zoom-out'
			];
		}

		// initial custom mouse cursor controls
		public function site_settings_controls( $element ) {

			$element->start_controls_section(
				'hm_cmc_init_section',
				[
					'label' => __( 'Happy Mouse Cursor', 'happy-elementor-addons' ) . ha_get_section_icon(),
					'tab'   => Controls_Manager::TAB_SETTINGS
				]
			);

			$element->add_control(
				'ha_cmc_init_switcher',
				[
					'label'              => __( 'Enable Happy Mouse Cursor', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SWITCHER,
					'description'        => __( 'Enable Initial mouse cursor', 'happy-elementor-addons' ),
					'default'            => 'no',
					'return_value'       => 'yes',
					'render_type'        => 'template',
					'style_transfer'     => false,
					'frontend_available' => true,
					'assets'             => [
						'scripts' => [
							[
								'name'       => 'elementor-frontend',
								'conditions' => [
									'terms' => [
										[
											'name'     => 'ha_cmc_init_switcher',
											'operator' => '===',
											'value'    => 'yes'
										]
									]
								]
							],
							[
								'name'       => 'gsap',
								'conditions' => [
									'terms' => [
										[
											'name'     => 'ha_cmc_init_switcher',
											'operator' => '===',
											'value'    => 'yes'
										]
									]
								]
							],
							[
								'name'       => 'mouse-follower',
								'conditions' => [
									'terms' => [
										[
											'name'     => 'ha_cmc_init_switcher',
											'operator' => '===',
											'value'    => 'yes'
										]
									]
								]
							],
							[
								'name'       => 'happy-custom-mouse-cursor',
								'conditions' => [
									'terms' => [
										[
											'name'     => 'ha_cmc_init_switcher',
											'operator' => '===',
											'value'    => 'yes'
										]
									]
								]
							]
						],
						'styles'  => [
							[
								'name'       => 'mouse-follower',
								'conditions' => [
									'terms' => [
										[
											'name'     => 'ha_cmc_init_switcher',
											'operator' => '===',
											'value'    => 'yes'
										]
									]
								]
							],
							[
								'name'       => 'happy-custom-mouse-cursor',
								'conditions' => [
									'terms' => [
										[
											'name'     => 'ha_cmc_init_switcher',
											'operator' => '===',
											'value'    => 'yes'
										]
									]
								]
							]
						]
					]
				]
			);

			$element->add_control(
				'ha_cmc_init_enable_lazy_move',
				[
					'label'              => __( 'Enable Lazy Move', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SWITCHER,
					'separator'          => 'before',
					'default'            => '',
					'render_type'        => 'template',
					'style_transfer'     => true,
					'frontend_available' => true,
					'condition'          => [
						'ha_cmc_init_switcher' => 'yes'
					]
				]
			);

			// $element->add_control(
			// 	'ha_cmc_init_lazy_move_seed',
			// 	[
			// 		'label'              => __( 'Speed', 'happy-elementor-addons' ),
			// 		'type'               => Controls_Manager::SLIDER,
			// 		'size_units'         => [],

			// 		'range'              => [
			// 			'px' => [
			// 				'min' => 0.1,
			// 				'max' => 10,
			// 				'step' => 0.1,
			// 			]
			// 		],
			// 		'default'            => [
			// 			'unit' => '',
			// 			'size' => 1
			// 		],
			// 		'render_type'          => 'template',
			// 		'style_transfer'     => true,
			// 		'frontend_available' => true,
			// 		'condition'            => [
			// 			'ha_cmc_init_switcher' => 'yes',
			// 			'ha_cmc_init_enable_lazy_move' => 'yes',
			// 		]
			// 	]
			// );

			$element->add_control(
				'ha_cmc_init_enable_mix_blend',
				[
					'label'                => __( 'Enable Mix Blend', 'happy-elementor-addons' ),
					'type'                 => Controls_Manager::SWITCHER,
					'default'              => '',
					'render_type'          => 'template',
					'style_transfer'       => true,
					'frontend_available'   => true,
					'selectors_dictionary' => [
						''    => '',
						'yes' => 'mix-blend-mode: difference;color:#FFF'
					],
					'selectors'            => [
						'.ha-cursor:not(.ha-text):not(.ha-media)'    => '{{VALUE}}',
						'.ha-cursor:not(.-text):not(.-media):before' => 'background: #FFF'
					],
					'condition'            => [
						'ha_cmc_init_switcher' => 'yes'
					]
				]
			);

			$element->add_control(
				'ha_cmc_init_mix_blend_color',
				[
					'label'              => __( 'Mix Blend Color', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::COLOR,
					'default'            => '#FFF',
					'style_transfer'     => true,
					'frontend_available' => true,
					'selectors'          => [
						'.ha-cursor:not(.-text):not(.-media)' => 'color: {{VALUE}}'
					],
					'condition'          => [
						'ha_cmc_init_switcher'         => 'yes',
						'ha_cmc_init_enable_mix_blend' => 'yes'
					]
				]
			);

			$this->site_settings_cmc_box_control( $element );

			$element->end_controls_section();
		}

		private function site_settings_cmc_box_control( $element ) {

			$element->add_control(
				'ha_cmc_init_box_heading',
				[
					'label'     => esc_html__( 'Cursor Style', 'happy-elementor-addons' ),
					'type'      => Controls_Manager::HEADING,
					'condition' => [
						'ha_cmc_init_switcher' => 'yes'
					]
				]
			);

			$element->add_control(
				'ha_cmc_init_box_bg_color',
				[
					'label'              => __( 'Background Color', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::COLOR,
					'separator'          => 'before',
					'default'            => '#000',
					'selectors'          => [
						'.ha-cursor:before' => 'background: {{VALUE}}'
					],
					'condition'          => [
						'ha_cmc_init_switcher'         => 'yes',
						'ha_cmc_init_enable_mix_blend' => ''
					],
					'style_transfer'     => true,
					'frontend_available' => true
				]
			);

			$element->add_control(
				'ha_cmc_init_box_mix_blend_bg_color',
				[
					'label'              => __( 'Background Color', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::COLOR,
					'separator'          => 'before',
					'default'            => '#FFF',
					'selectors'          => [
						'.ha-cursor:not(.-text):not(.-media):before' => 'background: {{VALUE}}'
					],
					'condition'          => [
						'ha_cmc_init_switcher'         => 'yes',
						'ha_cmc_init_enable_mix_blend' => 'yes'
					],
					'style_transfer'     => true,
					'frontend_available' => true
				]
			);

			$element->add_responsive_control(
				'ha_cmc_init_box_width',
				[
					'label'              => __( 'Width', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SLIDER,
					'size_units'         => ['px'],
					'range'              => [
						'px' => [
							'min'  => 0,
							'max'  => 500,
							'step' => 1
						]
					],
					'default'            => [
						'unit' => 'px',
						'size' => 80
					],
					'selectors'          => [
						'.ha-cursor:not(.-text):not(.-media):before' => 'width: {{SIZE}}{{UNIT}}'
					],
					'condition'          => [
						'ha_cmc_init_switcher' => 'yes'
					],
					'style_transfer'     => true,
					'frontend_available' => true
				]
			);
			$element->add_responsive_control(
				'ha_cmc_init_box_height',
				[
					'label'              => __( 'Height', 'happy-elementor-addons' ),
					'type'               => Controls_Manager::SLIDER,
					'size_units'         => ['px'],
					'range'              => [
						'px' => [
							'min'  => 0,
							'max'  => 500,
							'step' => 1
						]
					],
					'default'            => [
						'unit' => 'px',
						'size' => 80
					],
					'selectors'          => [
						'.ha-cursor:not(.-text):not(.-media):before' => 'height: {{SIZE}}{{UNIT}}'
					],
					'condition'          => [
						'ha_cmc_init_switcher' => 'yes'
					],
					'style_transfer'     => true,
					'frontend_available' => true
				]
			);

			$element->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'               => 'ha_cmc_init_box_border',
					'selector'           => '.ha-cursor:not(.-text):not(.-media):before',
					'condition'          => [
						'ha_cmc_init_switcher' => 'yes'
					],
					'style_transfer'     => true,
					'frontend_available' => true
				]
			);

			$element->add_responsive_control(
				'ha_cmc_init_box_border_radius',
				[
					'label'      => __( 'Border Radius', 'happy-elementor-addons' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => ['px', '%'],
					'default'    => [
						'unit'   => '%',
						'top'    => '50',
						'right'  => '50',
						'bottom' => '50',
						'left'   => '50'
					],
					'selectors'  => [
						'.ha-cursor:not(.-text):not(.-media):before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					],
					'condition'  => [
						'ha_cmc_init_switcher' => 'yes'
					]
				]
			);
		}

		// initial custom mouse cursor
		public function render_custom_mouse_cursor_html() {

			$settings_data = [];
			$post_id       = get_the_ID();
			$document      = Plugin::$instance->documents->get( $post_id, false );

			if ( is_object( $document ) ) {
				$settings_data = $document->get_settings();
			}

			$enableInitialCursor = isset( $settings_data['ha_cmc_init_switcher'] ) && 'yes' == $settings_data['ha_cmc_init_switcher'] ? true : false;

			$enableLazyMove = isset( $settings_data['ha_cmc_init_enable_lazy_move'] ) && 'yes' == $settings_data['ha_cmc_init_enable_lazy_move'] ? true : false;

			if ( ha_elementor()->preview->is_preview_mode() ) {
			?>
			<script>
				;
				(function($, w) {
					'use strict';
					let $window = $(w);

					$(document).ready(function() {

						let isEnable = "<?php echo $enableInitialCursor; ?>";
						let isEnableLazyMove = "<?php echo $enableLazyMove; ?>";
						let speed = isEnableLazyMove ? '0.7' : '0.2';

						if (typeof haCursor == 'undefined' || haCursor == null) {
							initiateHaCursorObject(speed);
						}

						setTimeout(function() {
							let targetCursor = $('.ha-cursor');
							if (targetCursor) {
								if (!isEnable) {
									$('body').removeClass('hm-init-cmc-cursor-none');
									$(w.document.body).removeClass('hm-init-default-cursor-none');
									$('.ha-cursor').addClass('ha-init-hide');
								} else {
									$('body').addClass('hm-init-cmc-cursor-none');
									$(w.document.body).addClass('hm-init-default-cursor-none');
									$('.ha-cursor').removeClass('ha-init-hide');
								}
							}
						}, 1500);

						window.addEventListener('message', function(e) {

							let data = e.data;

							if ('cmcInitMessage' == data.check) {
								if (e.origin != window.origin) {
									return;
								}
								if (e.source.location.href != window.parent.location.href) {
									return;
								}

								let changeValue = data.changeValue;
								let changeItem = data.changeItem;

								if (changeItem[0] == 'ha_cmc_init_switcher') {
									if (changeValue == 'yes') {
										$('.ha-cursor').removeClass('ha-init-hide');
										$('body').addClass('hm-init-default-cursor-none');
										$(w.parent.document.body).addClass('hm-init-default-cursor-none');
									} else {
										$('.ha-cursor').addClass('ha-init-hide');
										$('body').removeClass('hm-init-default-cursor-none');
										$(w.parent.document.body).removeClass('hm-init-default-cursor-none');
										return;
									}
								}

								if( changeItem[0] == 'ha_cmc_init_enable_lazy_move' ) {
									if( changeValue == 'yes' ) {
										if( haCursor != null) {
											haCursor.destroy();
										}
										initiateHaCursorObject('0.7');
										setTimeout(function(){
											$('.ha-cursor').removeClass('ha-init-hide');
										},500);
									} else {
										if( haCursor != null) {
											haCursor.destroy();
										}
										initiateHaCursorObject('0.2');
										setTimeout(function(){
											$('.ha-cursor').removeClass('ha-init-hide');
										},500);
									}
								}
							}
						});

					});

				}(jQuery, window));
			</script>

		<?php
			}

					if ( ! ha_elementor()->preview->is_preview_mode() ) {
					?>
			<script>
				;
				(function($, w) {
					'use strict';
					let $window = $(w);

					$(document).ready(function() {

						let isEnable = "<?php echo $enableInitialCursor; ?>";
						let isEnableLazyMove = "<?php echo $enableLazyMove; ?>";
						let speed = isEnableLazyMove ? '0.7' : '0.2';

						if( !isEnable ) {
							return;
						}

						if (typeof haCursor == 'undefined' || haCursor == null) {
							initiateHaCursorObject(speed);
						}

						setTimeout(function() {
							let targetCursor = $('.ha-cursor');
							if (targetCursor) {
								if (!isEnable) {
									$('body').removeClass('hm-init-default-cursor-none');
									$('.ha-cursor').addClass('ha-init-hide');
								} else {
									$('body').addClass('hm-init-default-cursor-none');
									$('.ha-cursor').removeClass('ha-init-hide');
								}
							}
						}, 500);

					});

				}(jQuery, window));
			</script>
		<?php
		}?>

		<?php
			}
			}
