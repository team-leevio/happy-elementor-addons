<?php
/**
 * Review widget class
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

defined( 'ABSPATH' ) || die();

class Review extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Happy Review', 'happy_addons' );
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
        return [ 'review', 'comment', 'feedback', 'testimonial' ];
    }

	protected function register_content_controls() {
		$this->start_controls_section(
			'_section_reviewer',
			[
				'label' => __( 'Reviewer', 'happy_addons' ),
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
                'separator' => 'none',
            ]
        );

        $this->add_responsive_control(
            'image_position',
            [
                'label' => __( 'Photo Position', 'happy_addons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'happy_addons' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'top' => [
                        'title' => __( 'Top', 'happy_addons' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'happy_addons' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'desktop_default' => 'top',
                'toggle' => false,
                'prefix_class' => 'ha-review--%s',
            ]
        );

        $this->add_control(
            'reviewer',
            [
                'label' => __( 'Name', 'happy_addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Jhon Doe', 'happy_addons' ),
                'placeholder' => __( 'Type reviewer name', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'role',
            [
                'label' => __( 'Role', 'happy_addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'COO', 'happy_addons' ),
                'placeholder' => __( 'Type reviewer role', 'happy_addons' ),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_review',
            [
                'label' => __( 'Review', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'ratting',
            [
                'label' => __( 'Ratting', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                    'size' => 4.2,
                ],
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => .1,
                    ],
                ],
            ]
        );

        $this->add_control(
            'ratting_style',
            [
                'label' => __( 'Style', 'happy_addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'star' => __( 'Star Rating', 'happy_addons' ),
                    'num' => __( 'Number Rating', 'happy_addons' ),
                ],
                'default' => 'star',
            ]
        );

        $this->add_control(
            'review',
            [
                'label' => __( 'Review', 'happy_addons' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __( 'Happy Addons is an amazing collection of Elementor widgets!', 'happy_addons' ),
                'placeholder' => __( 'Type review text', 'happy_addons' ),
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => __( 'Alignment', 'happy_addons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'happy_addons' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'happy_addons' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'happy_addons' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'desktop_default' => 'left',
                'toggle' => false,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'prefix_class' => 'ha-text--%s'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_advanced',
            [
                'label' => __( 'Advanced', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'name_tag',
            [
                'label' => __( 'Reviewer Name HTML Tag', 'happy_addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1'  => __( 'H1', 'happy_addons' ),
                    'h2'  => __( 'H2', 'happy_addons' ),
                    'h3'  => __( 'H3', 'happy_addons' ),
                    'h4'  => __( 'H4', 'happy_addons' ),
                    'h5'  => __( 'H5', 'happy_addons' ),
                    'h6'  => __( 'H6', 'happy_addons' ),
                ],
                'description' => __( 'In SEO (Search Engine Optimization) and in content hierarchy heading tag has a special purpose. So, make sure to select appropriate heading tag wisely based on content hierarchy.', 'happy_addons' ),
                'default' => 'h2',
            ]
        );

        $this->add_control(
            'content_position',
            [
                'label' => __( 'Content Position', 'happy_addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'reviewer' => __( 'Reviewer First', 'happy_addons' ),
                    'review' => __( 'Review First', 'happy_addons' ),
                ],
                'default' => 'reviewer',
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_controls() {
        $this->start_controls_section(
            '_section_photo_style',
            [
                'label' => __( 'Photo', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'size_toggle',
            [
                'label' => __( 'Size', 'plugin-name' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'label_off' => __( 'None', 'your-plugin' ),
                'label_on' => __( 'Custom', 'your-plugin' ),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();
        $this->add_responsive_control(
            'image_width',
            [
                'label' => __( 'Width', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 70,
                        'max' => 500,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-review-figure' => 'flex: 0 0 {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'size_toggle' => 'yes'
                ]
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
                        'min' => 70,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-review-figure' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'size_toggle' => 'yes'
                ]
            ]
        );
        $this->end_popover();

        $this->add_control(
            'offset_toggle',
            [
                'label' => __( 'Offset', 'plugin-name' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'label_off' => __( 'None', 'your-plugin' ),
                'label_on' => __( 'Custom', 'your-plugin' ),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_responsive_control(
            'image_offset_x',
            [
                'label' => __( 'Offset X', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'condition' => [
                    'offset_toggle' => 'yes'
                ],
                'default' => [
                    'size' => 1
                ],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'render_type' => 'ui'
            ]
        );

        $this->add_responsive_control(
            'image_offset_y',
            [
                'label' => __( 'Offset Y', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'condition' => [
                    'offset_toggle' => 'yes'
                ],
                'default' => [
                    'size' => 1
                ],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-review-figure' => '-ms-transform: translate({{image_offset_x.SIZE}}{{UNIT}}, {{SIZE}}{{UNIT}}); -webkit-transform: translate({{image_offset_x.SIZE}}{{UNIT}}, {{SIZE}}{{UNIT}}); transform: translate({{image_offset_x.SIZE}}{{UNIT}}, {{SIZE}}{{UNIT}});',
                    '{{WRAPPER}}.ha-review--top .ha-review-body' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ha-review--left .ha-review-body' => 'margin-left: {{image_offset_x.SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ha-review--right .ha-review-body' => 'margin-right: calc(-1 * {{image_offset_x.SIZE}}{{UNIT}});',
                ],
            ]
        );
        $this->end_popover();

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => __( 'Padding', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-review-figure > img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .ha-review-figure > img',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-review-figure > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .ha-review-figure > img',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_ratting_style',
            [
                'label' => __( 'Ratting', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'ratting_margin',
            [
                'label' => __( 'Margin', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-review-ratting' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ratting_padding',
            [
                'label' => __( 'Padding', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-review-ratting' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ratting_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-review-ratting' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'ratting_bg_color',
            [
                'label' => __( 'Background Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-review-ratting' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ratting_border',
                'selector' => '{{WRAPPER}} .ha-review-ratting',
            ]
        );

        $this->add_control(
            'ratting_border_radius',
            [
                'label' => __( 'Border Radius', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-review-ratting' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            '_section_review_style',
            [
                'label' => __( 'Review', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'body_padding',
            [
                'label' => __( 'Text Box Padding', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-review-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            '_name_heading',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Reviewer Name', 'happy_addons' ),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'name_margin',
            [
                'label' => __( 'Margin', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-review-reviewer' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-review-reviewer' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'label' => __( 'Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-review-reviewer',
            ]
        );

        $this->add_control(
            '_role_heading',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Reviewer Role', 'happy_addons' ),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'role_margin',
            [
                'label' => __( 'Margin', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-review-role' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'role_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-review-role' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'role_typography',
                'label' => __( 'Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-review-role',
            ]
        );

        $this->add_control(
            '_review_heading',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Review', 'happy_addons' ),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'review_margin',
            [
                'label' => __( 'Margin', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-review-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'review_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-review-desc' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'review_typography',
                'label' => __( 'Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-review-desc',
            ]
        );

        $this->end_controls_section();
    }

	protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_inline_editing_attributes( 'reviewer', 'none' );
        $this->add_render_attribute( 'reviewer', 'class', 'ha-review-reviewer' );

        $this->add_inline_editing_attributes( 'role', 'none' );
        $this->add_render_attribute( 'role', 'class', 'ha-review-role' );

        $this->add_inline_editing_attributes( 'review', 'basic' );
        $this->add_render_attribute( 'review', 'class', 'ha-review-desc' );

        $this->add_render_attribute( 'ratting', 'class', [
                'ha-review-ratting',
                'ha-review-ratting--' . $settings['ratting_style']
            ] );
        ?>

        <?php if ( ! empty( $settings['image']['url'] ) ) :
            $this->add_render_attribute( 'image', 'src', $settings['image']['url'] );
            $this->add_render_attribute( 'image', 'alt', Control_Media::get_image_alt( $settings['image'] ) );
            $this->add_render_attribute( 'image', 'title', Control_Media::get_image_title( $settings['image'] ) );
            $settings['hover_animation'] = 'disable-animation'; // hack to prevent image hover animation
            ?>
            <figure class="ha-review-figure">
                <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'image' ); ?>
            </figure>
        <?php endif; ?>

        <div class="ha-review-body">
            <?php if ( $settings['content_position'] === 'review' ) : ?>
                <div <?php echo $this->get_render_attribute_string( 'review' ); ?>>
                    <p><?php echo wp_kses_data( $settings['review'] ); ?></p>
                </div>
            <?php endif; ?>

            <div class="ha-review-header">
                <?php printf( '<%1$s %2$s>%3$s</%1$s>',
                    tag_escape( $settings['name_tag'] ),
                    $this->get_render_attribute_string( 'reviewer' ),
                    esc_html( $settings['reviewer' ] )
                    ); ?>

                <div <?php echo $this->get_render_attribute_string( 'role' ); ?>><?php echo esc_html( $settings['role' ] ); ?></div>

                <div <?php echo $this->get_render_attribute_string( 'ratting' ); ?>>
                    <?php if ( $settings['ratting_style'] === 'num' ) : ?>
                        <?php echo esc_html( $settings['ratting']['size'] ); ?> <i class="fa fa-star"></i>
                    <?php else : ?>
                        <span><span style="width:<?php echo ($settings['ratting']['size'] * 20); ?>%"></span></span>
                    <?php endif; ?>
                 </div>
            </div>

            <?php if ( $settings['content_position'] === 'reviewer' ) : ?>
                <div <?php echo $this->get_render_attribute_string( 'review' ); ?>>
                    <p><?php echo wp_kses_data( $settings['review'] ); ?></p>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    public function _content_template() {
        ?>
        <#
        view.addInlineEditingAttributes( 'reviewer', 'none' );
        view.addRenderAttribute( 'reviewer', 'class', 'ha-review-reviewer' );

        view.addInlineEditingAttributes( 'role', 'none' );
        view.addRenderAttribute( 'role', 'class', 'ha-review-role' );

        view.addInlineEditingAttributes( 'review', 'basic' );
        view.addRenderAttribute( 'review', 'class', 'ha-review-desc' );

        if ( settings.image.url ) {
            var image = {
                id: settings.image.id,
                url: settings.image.url,
                size: settings.image_size,
                dimension: settings.image_custom_dimension,
                model: view.getEditModel()
            };

            var image_url = elementor.imagesManager.getImageUrl( image );
            #>
            <figure class="ha-review-figure">
                <img src="{{ image_url }}">
            </figure>
        <# } #>

        <div class="ha-review-body">
            <# if ( settings.content_position === 'review' ) { #>
                <div {{{ view.getRenderAttributeString( 'review' ) }}}>
                    <p>{{{ settings.review }}}</p>
                </div>
            <# } #>
            <div class="ha-review-header">
                <{{ settings.name_tag }} {{{ view.getRenderAttributeString( 'reviewer' ) }}}>{{ settings.reviewer }}</{{ settings.name_tag }}>
                <div {{{ view.getRenderAttributeString( 'role' ) }}}>{{ settings.role }}</div>
                <# if ( settings.ratting_style === 'num' ) { #>
                    <div class="ha-review-ratting ha-review-ratting--num">{{ settings.ratting.size }} <i class="fa fa-star"></i></div>
                <# } else { var ratingPercent = ( settings.ratting.size * 20 ) #>
                    <div class="ha-review-ratting ha-review-ratting--star">
                        <span><span style="width:{{ ratingPercent }}%"></span></span>
                    </div>
                <# } #>
            </div>
            <# if ( settings.content_position === 'reviewer' ) { #>
                <div {{{ view.getRenderAttributeString( 'review' ) }}}>
                    <p>{{{ settings.review }}}</p>
                </div>
            <# } #>
        </div>
        <?php
    }
}
