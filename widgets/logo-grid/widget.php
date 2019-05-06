<?php
/**
 * Logo grid widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Repeater;
use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class Logo_grid extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Logo Grid', 'happy_addons' );
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
        return 'fa fa-smile-o';
    }

    public function get_keywords() {
        return [ 'logo', 'grid' ];
    }

	protected function register_content_controls() {
        $this->start_controls_section(
            '_section_logo',
            [
                'label' => __( 'Logo Grid', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'logo_name',
            [
                'label' => __( 'Brand Name', 'happy_addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Brand Name', 'happy_addons' ),
            ]
        );

		$repeater->add_control(
            'logo_image',
            [
                'label' => __( 'Logo', 'happy_addons' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

		$repeater->add_control(
            'logo_link',
            [
                'label' => __( 'Website Url', 'happy_addons' ),
				'type' => Controls_Manager::TEXT
            ]
        );

        $repeater->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'thumbnail',
                'separator' => 'before',
                'exclude' => [
                    'custom'
                ]
            ]
        );

        $this->add_control(
			'logo_list',
			array(
                'show_label' => false,
				'type'      => Controls_Manager::REPEATER,
				'fields'    => $repeater->get_controls(),
				'title_field' => '{{{ logo_name }}}',
				'default'   => array(
					array(
                        'logo_name'	=> __( 'Brand Name', 'happy_addons' ),
                        'logo_link'	=> esc_url( '' ),
					),
					array(
						'logo_name'	=> __( 'Brand Name', 'happy_addons' ),
						'logo_link'	=> esc_url( '' ),
					),
					array(
						'logo_name'	=> __( 'Brand Name', 'happy_addons' ),
						'logo_link'	=> esc_url( '' ),
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_settings',
            [
                'label' => __( 'Settings', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
            'columns',
            [
                'label' => __( 'Columns', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default' => [
                    'size' => 3,
                ],
                'range' => [
                    'px' => [
                        'min' => 2,
                        'max' => 5,
                    ],
				],
            ]
        );

        $this->end_controls_section();

    }

    protected function register_style_controls() {
        $this->start_controls_section(
            '_section_style_logo',
            [
                'label' => __( 'Logo', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
		);

		$this->add_responsive_control(
            'logo_spacing',
            [
                'label' => __( 'Logo Spacing', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-logo-item-thumb img' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
		);

		$this->add_responsive_control(
            'box_spacing',
            [
                'label' => __( 'Logo Box Spacing', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-logo-item' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
		);

		$this->add_control(
            'logo_background_color',
            [
                'label' => __( 'Logo Background Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-logo-item-thumb' => 'background-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_control(
            'hr',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

		$this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'logo_border',
                'selector' => '{{WRAPPER}} .ha-logo-item-thumb'
            ]
		);

        $this->add_responsive_control(
            'logo_border_radius',
            [
                'label' => __( 'Logo Border Radius', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-logo-item-thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
		);

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'logo_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .ha-logo-item-thumb'
            ]
		);

        $this->end_controls_tab();

	}

	protected function render() {
        $settings = $this->get_settings_for_display();

            $this->add_render_attribute( 'container', 'class', [
                'ha-logo',
                'ha-logo-grid-' . $settings['columns']['size'],
			] );

			echo '<div ' . $this->get_render_attribute_string( 'container' ) . '>';

			foreach( $settings['logo_list'] as $list ) {
				echo '<div class="ha-logo-item">';
				echo '<div class="ha-logo-item-thumb">';

				echo '<a target="_blank" rel="noopener" href="' . esc_url( $list['logo_link'] ) . '">';
				echo Group_Control_Image_Size::get_attachment_image_html( $list, 'thumbnail', 'logo_image' );
				echo '</a>';

				echo '</div>';
				echo '</div>';
			}

			echo '</div>';

	}

	public function _content_template() {}

}
