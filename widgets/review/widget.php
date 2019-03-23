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
			'_section_review',
			[
				'label' => __( 'Review', 'happy_addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
            'image',
            [
                'label' => __( 'Reviewer Photo', 'happy_addons' ),
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
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'prefix_class' => 'ha-review--%s',
            ]
        );

        $this->add_control(
            'reviewer',
            [
                'label' => __( 'Reviewer Name', 'happy_addons' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Jhon Doe', 'happy_addons' ),
                'placeholder' => __( 'Type reviewer name', 'happy_addons' ),
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'role',
            [
                'label' => __( 'Role', 'happy_addons' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Chief Operation Officer', 'happy_addons' ),
                'placeholder' => __( 'Type reviewer role', 'happy_addons' ),
                'description' => __( 'Add reviewer job role or designation', 'happy_addons' )
            ]
        );

        $this->add_control(
            'ratting',
            [
                'label' => __( 'Ratting', 'happy_addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 5,
                'step' => .1,
                'default' => 4.2,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'ratting_style',
            [
                'label' => __( 'Style', 'happy_addons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'star' => [
                        'title' => __( 'Only Star Rating', 'happy_addons' ),
                        'icon' => 'fa fa-star-half-o',
                    ],
                    'text' => [
                        'title' => __( 'Text Star Rating', 'happy_addons' ),
                        'icon' => 'fa fa-i-cursor',
                    ],
                ],
                'default' => 'star',
                'toggle' => false,
            ]
        );

        $this->add_control(
            'review',
            [
                'label' => __( 'Review', 'happy_addons' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __( 'Happy Addons is an amazing collection of Elementor widgets!', 'happy_addons' ),
                'placeholder' => __( 'Type review text', 'happy_addons' ),
                'rows' => 5,
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => __( 'Text Alignment', 'happy_addons' ),
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
            'priority',
            [
                'label' => __( 'Visual Priority', 'happy_addons' ),
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
            '_section_image_style',
            [
                'label' => __( 'Image', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'width',
            [
                'label' => __( 'Width', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => [ '%', 'px' ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-card-figure' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_offset',
            [
                'label' => __( 'Offset', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'allowed_dimensions' => ['top', 'left'],
                'selectors' => [
                    '{{WRAPPER}} .ha-card-figure' => '-ms-transform: translate({{LEFT}}{{UNIT}}, {{TOP}}{{UNIT}}); -webkit-transform: translate({{LEFT}}{{UNIT}}, {{TOP}}{{UNIT}}); transform: translate({{LEFT}}{{UNIT}}, {{TOP}}{{UNIT}});',
                    '{{WRAPPER}}.ha-card--top .ha-card-body' => 'margin-top: {{TOP}}{{UNIT}};',
                    '{{WRAPPER}}.ha-card--left .ha-card-body' => 'margin-left: {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.ha-card--right .ha-card-body' => 'margin-right: calc(-1 * {{LEFT}}{{UNIT}});',
                ],
                'default' => [
                    'isLinked' => false,
                ]
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => __( 'Padding', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-card-figure > img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .ha-card-figure > img',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-card-figure > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .ha-card-figure > img',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_label_style',
            [
                'label' => __( 'Label', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'label_margin',
            [
                'label' => __( 'Margin', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'label_padding',
            [
                'label' => __( 'Padding', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'label_bg_color',
            [
                'label' => __( 'Background Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-label' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'label_border',
                'selector' => '{{WRAPPER}} .ha-label',
            ]
        );

        $this->add_responsive_control(
            'label_border_radius',
            [
                'label' => __( 'Border Radius', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'label_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .ha-label',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'label' => __( 'Typography', 'happy_addons' ),
                'exclude' => [
                    'font_family',
                    'line_height'
                ],
                'selector' => '{{WRAPPER}} .ha-label',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_content_style',
            [
                'label' => __( 'Title & Description', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __( 'Container Padding', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-card-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_heading',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Title', 'happy_addons' ),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => __( 'Margin', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-card-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-card-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __( 'Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-card-title',
            ]
        );

        $this->add_control(
            'description_heading',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Description', 'happy_addons' ),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'description_margin',
            [
                'label' => __( 'Margin', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-card-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-card-text' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => __( 'Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-card-text',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_button_style',
            [
                'label' => __( 'Button', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'btn_padding',
            [
                'label' => __( 'Padding', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typography',
                'selector' => '{{WRAPPER}} .ha-btn',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btn_border',
                'selector' => '{{WRAPPER}} .ha-btn',
            ]
        );

        $this->add_control(
            'btn_border_radius',
            [
                'label' => __( 'Border Radius', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_box_shadow',
                'selector' => '{{WRAPPER}} .ha-btn',
            ]
        );

        $this->add_control(
            'hr',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __( 'Normal', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'btn_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ha-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_bg_color',
            [
                'label' => __( 'Background Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __( 'Hover', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'btn_hover_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-btn:hover, {{WRAPPER}} .ha-btn:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_bg_color',
            [
                'label' => __( 'Background Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-btn:hover, {{WRAPPER}} .ha-btn:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_border_color',
            [
                'label' => __( 'Border Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'btn_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-btn:hover, {{WRAPPER}} .ha-btn:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

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
            <?php if ( $settings['priority'] === 'review' ) : ?>
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

                <?php if ( $settings['ratting_style'] === 'text' ) : ?>
                    <div class="ha-review-ratting-text"><?php echo esc_html( $settings['ratting'] ); ?> <i class="fa fa-star"></i></div>
                <?php else : ?>
                    <div class="ha-review-ratting-star">
                        <span style="width:<?php echo ($settings['ratting'] * 20); ?>%"></span>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ( $settings['priority'] === 'reviewer' ) : ?>
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
            <# if ( settings.priority === 'review' ) { #>
                <div {{{ view.getRenderAttributeString( 'review' ) }}}>
                    <p>{{{ settings.review }}}</p>
                </div>
            <# } #>
            <div class="ha-review-header">
                <{{ settings.name_tag }} {{{ view.getRenderAttributeString( 'reviewer' ) }}}>{{ settings.reviewer }}</{{ settings.name_tag }}>
                <div {{{ view.getRenderAttributeString( 'role' ) }}}>{{ settings.role }}</div>
                <# if ( settings.ratting_style === 'text' ) { #>
                    <div class="ha-review-ratting-text">{{ settings.ratting }} <i class="fa fa-star"></i></div>
                <# } else { var ratingPercent = ( settings.ratting * 20 ) #>
                    <div class="ha-review-ratting-star">
                        <span style="width:{{ ratingPercent }}%"></span>
                    </div>
                <# } #>
            </div>
            <# if ( settings.priority === 'reviewer' ) { #>
                <div {{{ view.getRenderAttributeString( 'review' ) }}}>
                    <p>{{{ settings.review }}}</p>
                </div>
            <# } #>
        </div>
        <?php
    }
}
