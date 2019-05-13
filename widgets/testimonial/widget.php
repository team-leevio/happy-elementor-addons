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
        return 'fa fa-smile-o';
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
                'name' => 'image',
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
                'label' => __( 'Identity & Detail', 'happy_addons' ),
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

		$this->add_responsive_control(
            'image_width',
            [
                'label' => __( 'Width', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 65,
                        'max' => 200,
                    ],
				],
                'selectors' => [
                    '{{WRAPPER}} .ha-testimonial-thumb' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
            'image_height',
            [
                'label' => __( 'Height', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 65,
                        'max' => 200,
                    ],
				],
                'selectors' => [
                    '{{WRAPPER}} .ha-testimonial-thumb' => 'height: {{SIZE}}{{UNIT}};',
                ],
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

		$this->add_responsive_control(
            'image_spacing',
            [
                'label' => __( 'Image Spacing', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-testimonial-thumb' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
		);

		$this->end_controls_section();

		$this->start_controls_section(
            '_section_style_title',
            [
                'label' => __( 'Identity', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
		);

		$this->add_control(
            'title_color',
            [
                'label' => __( 'Name Text Color', 'happy_addons' ),
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
                'label' => __( 'Name Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-testimonial-author',
            ]
		);

		$this->add_responsive_control(
            'name_spaceing',
            [
                'label' => __( 'Name Spacing', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-testimonial-author' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$this->add_control(
            'designation_color',
            [
                'label' => __( 'Designation Text Color', 'happy_addons' ),
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
                'label' => __( 'Designation Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-testimonial-designation',
            ]
		);

		$this->add_responsive_control(
            'designation_spaceing',
            [
                'label' => __( 'Designation Spacing', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-testimonial-designation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->end_controls_section();


		$this->start_controls_section(
            '_section_style_description',
            [
                'label' => __( 'Detail', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
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

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes( 'description', 'none' );
		$this->add_render_attribute( 'description', 'class', 'ha-testimonial-info' );

		$this->add_inline_editing_attributes( 'name', 'none' );
		$this->add_render_attribute( 'name', 'class', 'ha-testimonial-author' );

		$this->add_inline_editing_attributes( 'designation', 'none' );
		$this->add_render_attribute( 'designation', 'class', 'ha-testimonial-designation' );
		?>

		<div <?php echo $this->get_render_attribute_string('description'); ?>>
			<?php echo $settings['description']; ?>
		</div>
		<div class="ha-testimonial-media">

		<?php if ( ! empty( $settings['image']['url'] ) ) : ?>
			<div class="ha-testimonial-thumb">
				<?php
				echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'image' );
				?>
			</div>
		<?php endif; ?>

			<div class="ha-testimonial-media-body">
				<div <?php echo $this->get_render_attribute_string('name'); ?>>
					<?php echo $settings['name']; ?>
				</div>
				<div <?php echo $this->get_render_attribute_string('designation'); ?>>
					<?php echo $settings['designation']; ?>
				</div>
			</div>
		</div>
	<?php
	}

	public function _content_template() {
		?>
		<#
		view.addInlineEditingAttributes( 'description', 'none' );
		view.addRenderAttribute( 'description', 'class', 'ha-testimonial-info' );

		view.addInlineEditingAttributes( 'name', 'none' );
		view.addRenderAttribute( 'name', 'class', 'ha-testimonial-author' );

		view.addInlineEditingAttributes( 'designation', 'none' );
		view.addRenderAttribute( 'designation', 'class', 'ha-testimonial-designation' );

		if ( settings.image.url ) {
			var image = {
				id: settings.image.id,
				url: settings.image.url,
				size: settings.image_size,
				dimension: settings.image_custom_dimension,
				model: view.getEditModel()
			};
			var image_url = elementor.imagesManager.getImageUrl( image );
		}
		#>

		<div {{{ view.getRenderAttributeString( 'description' ) }}}>
			{{{ settings.description }}}
		</div>
		<div class="ha-testimonial-media">

			<# if ( settings.image.url ) { #>
				<div class="ha-testimonial-thumb">
					<img src="{{{ image_url }}}" />
				</div>
			<# } #>

			<div class="ha-testimonial-media-body">
				<div {{{ view.getRenderAttributeString( 'name' ) }}}>
					{{{ settings.name }}}
				</div>
				<div {{{ view.getRenderAttributeString( 'designation' ) }}}>
					{{{ settings.designation }}}
				</div>
			</div>
		</div>

	<?php
	}

}
