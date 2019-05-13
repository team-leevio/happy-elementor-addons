<?php
/**
 * Testimonial widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Control_Media;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class Testimonial extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Testimonial', 'happy_addons' );
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
        return 'hm hm-testimonial';
    }

    public function get_keywords() {
        return [ 'Testimonial', 'testimonials' ];
    }

	protected function register_content_controls() {
        $this->start_controls_section(
            '_testimonial_image',
            [
                'label' => __( 'Image', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

		$this->add_control(
            'image',
            [
                'label' => __( 'Photo', 'happy_addons' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
		);

		$this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image_size',
				'default' => 'thumbnail',
				'exclude' => [ 'custom' ],
				'include' => [],
                'separator' => 'none',
            ]
		);

		$this->end_controls_section();

		$this->start_controls_section(
            '_testimonial_name',
            [
                'label' => __( 'Name', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

		$this->add_control(
            'name',
            [
                'label' => __( 'Name', 'happy_addons' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => __( 'John Doe', 'happy_addons' ),
                'placeholder' => __( 'Name', 'happy_addons' ),
            ]
		);

		$this->end_controls_section();

		$this->start_controls_section(
            '_testimonial_designation',
            [
                'label' => __( 'Designation', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
		);

		$this->add_control(
            'designation',
            [
                'label' => __( 'Designation', 'happy_addons' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Communication Director', 'happy_addons' ),
                'placeholder' => __( 'Designation', 'happy_addons' ),
            ]
		);

		$this->end_controls_section();

		$this->start_controls_section(
            '_testimonial_description',
            [
                'label' => __( 'Description', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
		);

		$this->add_control(
            'description',
            [
                'label' => __( 'Description', 'happy_addons' ),
                'label_block' => true,
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'Write detail here', 'happy_addons' ),
                'placeholder' => __( 'Description', 'happy_addons' ),
            ]
		);

        $this->end_controls_section();

    }

    protected function register_style_controls() {
        $this->start_controls_section(
            '_section_style_content',
            [
                'label' => __( 'Content Position', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
		);

		$this->add_control(
            'content_position',
            [
                'label' => __( 'Content horizontal Position', 'happy_addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options' => [
					'left' => [
						'title' => __( 'Left', 'happy_addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
					'center' => [
						'title' => __( 'Center', 'happy_addons' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'happy_addons' ),
						'icon' => 'eicon-h-align-right',
					]
                ],
				'toggle' => true,
				'prefix_class' => 'ha-position--',
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
            '_section_style_image',
            [
                'label' => __( 'Image', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
		);

		$this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .ha-testimonial-thumb > img',
            ]
		);

		$this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-testimonial-thumb > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
            '_section_style_title',
            [
                'label' => __( 'Title', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
		);

		$this->add_control(
            'title_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-testimonial-author' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __( 'Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-testimonial-author',
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
            '_section_style_designation',
            [
                'label' => __( 'Designation', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
		);

		$this->add_control(
            'designation_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-testimonial-designation' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'designation_typography',
                'label' => __( 'Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-testimonial-designation',
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
            '_section_style_description',
            [
                'label' => __( 'Description', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
		);

		$this->add_control(
            'description_background_color',
            [
                'label' => __( 'Background Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-testimonial-info' => 'background-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_control(
            'description_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-testimonial-info' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => __( 'Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-testimonial-info',
            ]
		);

		$this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'description_border',
                'selector' => '{{WRAPPER}} .ha-testimonial-info',
            ]
		);

		$this->add_responsive_control(
            'description_border_radius',
            [
                'label' => __( 'Border Radius', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-testimonial-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
		);

		$this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'description_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .ha-testimonial-info',
            ]
        );

		$this->add_responsive_control(
            'description_spacing',
            [
                'label' => __( 'Spacing', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-testimonial-info' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>

		<div class="ha-testimonial-info">
			<?php echo $settings['description']; ?>
		</div>
		<div class="ha-testimonial-media">
			<div class="ha-testimonial-thumb">
				<?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image_size', 'image' ); ?>
			</div>
			<div class="ha-testimonial-media-body">
				<div class="ha-testimonial-author">
					<?php echo $settings['name']; ?>
				</div>
				<div class="ha-testimonial-designation">
					<?php echo $settings['designation']; ?>
				</div>
			</div>
		</div>
	<?php
	}

	/* public function _content_template() {
		?>


	<?php
	} */

}
