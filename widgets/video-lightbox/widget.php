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

defined( 'ABSPATH' ) || die();

class Video_Lightbox extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Video Lightbox', 'happy-elementor-addons' );
    }

	public function get_custom_help_url() {
		return 'https://happyaddons.com/docs/happy-addons-for-elementor/widgets/video-lightbox/';
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
        return 'hm hm-accordion-horizontal';
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
		$this->_section_button();
	}

    protected function _section_button() {

        $this->start_controls_section(
            '_section_video_lightbox',
            [
                'label' => __( 'Video Lightbox', 'happy-elementor-addons' ),
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
			'lightbox_item',
			[
				'label'       => __( 'Lightbox item', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'toggle'      => false,
				'separator' => 'before',
				'default'     => 'video',
				'options'     => [
					'video'  => [
						'title' => __( 'Text', 'happy-elementor-addons' ),
						'icon'  => 'eicon-video-camera',//fa fa-font
					],
					'image'  => [
						'title' => __( 'Icon', 'happy-elementor-addons' ),
						'icon'  => 'eicon-image-bold',
					],
				],
			]
		);

		$this->add_control(
			'button_link',
			[
				'label' => esc_html__( 'Video Link', 'happy-elementor-addons' ),
				'type' => Controls_Manager::URL,
				'show_label' => false,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => '#',
				],
                'condition' => [
					'lightbox_item' => 'video',
				],
			]
		);

		$this->add_control(
			'lightbox_image',
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
					'lightbox_item' => 'image',
				],
			]
		);

        $this->end_controls_section();
    }

	/**
     * Register widget style controls
     */
    protected function register_style_controls() {
		$this->__common_style_controls();
	}

    protected function __common_style_controls() {

        $this->start_controls_section(
            '_section_style_common',
            [
                'label' => __( 'Common', 'happy-elementor-addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
		);

		$this->add_responsive_control(
            'button_padding',
            [
                'label' => __( 'Padding', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ha-dual-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
		);

		$this->add_responsive_control(
            'button_gap',
            [
                'label' => __( 'Space Between Buttons', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '(desktop+){{WRAPPER}}.ha-dual-button--layout-queue .ha-dual-btn--left' => 'margin-right: calc({{button_gap.SIZE}}{{UNIT}}/2);',
                    '(desktop+){{WRAPPER}}.ha-dual-button--layout-stack .ha-dual-btn--left' => 'margin-bottom: calc({{button_gap.SIZE}}{{UNIT}}/2);',
                    '(desktop+){{WRAPPER}}.ha-dual-button--layout-queue .ha-dual-btn--right' => 'margin-left: calc({{button_gap.SIZE}}{{UNIT}}/2);',
                    '(desktop+){{WRAPPER}}.ha-dual-button--layout-stack .ha-dual-btn--right' => 'margin-top: calc({{button_gap.SIZE}}{{UNIT}}/2);',

                    '(tablet){{WRAPPER}}.ha-dual-button--tablet-layout-queue .ha-dual-btn--left' => 'margin-right: calc({{button_gap_tablet.SIZE || button_gap.SIZE}}{{UNIT}}/2); margin-bottom: 0;',
                    '(tablet){{WRAPPER}}.ha-dual-button--tablet-layout-stack .ha-dual-btn--left' => 'margin-bottom: calc({{button_gap_tablet.SIZE || button_gap.SIZE}}{{UNIT}}/2); margin-right: 0;',
                    '(tablet){{WRAPPER}}.ha-dual-button--tablet-layout-queue .ha-dual-btn--right' => 'margin-left: calc({{button_gap_tablet.SIZE || button_gap.SIZE}}{{UNIT}}/2); margin-top: 0;',
                    '(tablet){{WRAPPER}}.ha-dual-button--tablet-layout-stack .ha-dual-btn--right' => 'margin-top: calc({{button_gap_tablet.SIZE || button_gap.SIZE}}{{UNIT}}/2); margin-left: 0;',

                    '(mobile){{WRAPPER}}.ha-dual-button--mobile-layout-queue .ha-dual-btn--left' => 'margin-right: calc({{button_gap_mobile.SIZE || button_gap_tablet.SIZE || button_gap.SIZE}}{{UNIT}}/2); margin-bottom: 0;',
                    '(mobile){{WRAPPER}}.ha-dual-button--mobile-layout-stack .ha-dual-btn--left' => 'margin-bottom: calc({{button_gap_mobile.SIZE || button_gap_tablet.SIZE || button_gap.SIZE}}{{UNIT}}/2); margin-right: 0;',
                    '(mobile){{WRAPPER}}.ha-dual-button--mobile-layout-queue .ha-dual-btn--right' => 'margin-left: calc({{button_gap_mobile.SIZE || button_gap_tablet.SIZE || button_gap.SIZE}}{{UNIT}}/2); margin-top: 0;',
                    '(mobile){{WRAPPER}}.ha-dual-button--mobile-layout-stack .ha-dual-btn--right' => 'margin-top: calc({{button_gap_mobile.SIZE || button_gap_tablet.SIZE || button_gap.SIZE}}{{UNIT}}/2); margin-left: 0;',
                ],
            ]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => __( 'Typography', 'happy-elementor-addons' ),
                'selector' => '{{WRAPPER}} .ha-dual-btn',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
            ]
		);

		$this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .ha-dual-btn'
            ]
		);

        $this->add_responsive_control(
            'button_align_x',
            [
                'label' => __( 'Alignment', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'happy-elementor-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'happy-elementor-addons' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'happy-elementor-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
                'toggle' => true,
                'prefix_class' => 'ha-dual-button-%s-align-'
            ]
        );

		$this->end_controls_section();
	}

	protected function trigger_type_render() {
		$settings = $this->get_settings_for_display();
		$widget_id = $this->get_id();

		if( 'image' == $settings['trigger_type'] ){
			$this->add_render_attribute(
				'modal-button',
				[
					'class' => 'ha-video-lightbox-trigger  ha-video-lightbox-image',
					'data-id' => $widget_id,
					'src' => Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], '_image', $settings ) ? esc_url(Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], '_image', $settings )) : esc_url($settings['image']['url']),
					'title' => esc_attr(Control_Media::get_image_title( $settings['image'] )),
					'alt' => esc_attr(Control_Media::get_image_alt( $settings['image'] )),
					'data-elementor-open-lightbox' => 'yes'
				]
			);
		} elseif( 'button' == $settings['trigger_type'] ) {
			$this->add_render_attribute(
				'modal-button',
				[
					'class' => 'ha-video-lightbox-trigger ha-video-lightbox-btn',
					'data-id' => $widget_id,
					'href' => $settings['button_link']['url'] ? $settings['button_link']['url'] : '#',
					'data-elementor-open-lightbox' => 'yes'
				]
			);
		}

		?>
		<a <?php echo $this->get_render_attribute_string( 'modal-button' );?>>
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
				<img <?php echo $this->get_render_attribute_string( 'modal-button' )?> />
			<?php endif; ?>
		</a>
		<?php
	}

    protected function render() {
        $settings = $this->get_settings_for_display();


        ?>
        <div class="ha-video-lightbox-wrapper">
			<?php $this->trigger_type_render();?>
        </div>
        <?php
    }
}
