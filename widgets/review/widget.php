<?php
/**
 * Review widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Scheme_Typography;
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
        return __( 'Review', 'happy_addons' );
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
        return 'hm hm-review';
    }

    public function get_keywords() {
        return [ 'review', 'comment', 'feedback', 'testimonial' ];
    }

	protected function register_content_controls() {
		$this->start_controls_section(
			'_section_content',
			[
				'label' => __( 'Content', 'happy_addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->start_controls_tabs( '_tab_content' );
        $this->start_controls_tab(
            '_tab_reviewer',
            [
                'label' => __( 'Reviewer', 'happy_addons' ),
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

        $this->add_control(
            'title',
            [
                'label' => __( 'Name', 'happy_addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Happy Reviewer', 'happy_addons' ),
                'placeholder' => __( 'Type Reviewer Name', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'job_title',
            [
                'label' => __( 'Job Title', 'happy_addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Happy Officer', 'happy_addons' ),
                'placeholder' => __( 'Type Reviewer Job Title', 'happy_addons' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'large',
                'separator' => 'none',
            ]
        );

        $this->add_control(
            'image_position',
            [
                'label' => __( 'Image Position', 'happy_addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'happy_addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'top' => [
                        'title' => __( 'Top', 'happy_addons' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'happy_addons' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'top',
                'toggle' => false,
                'prefix_class' => 'ha-review--'
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => __( 'Name HTML Tag', 'happy_addons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'h1'  => [
                        'title' => __( 'H1', 'happy_addons' ),
                        'icon' => 'eicon-editor-h1'
                    ],
                    'h2'  => [
                        'title' => __( 'H2', 'happy_addons' ),
                        'icon' => 'eicon-editor-h2'
                    ],
                    'h3'  => [
                        'title' => __( 'H3', 'happy_addons' ),
                        'icon' => 'eicon-editor-h3'
                    ],
                    'h4'  => [
                        'title' => __( 'H4', 'happy_addons' ),
                        'icon' => 'eicon-editor-h4'
                    ],
                    'h5'  => [
                        'title' => __( 'H5', 'happy_addons' ),
                        'icon' => 'eicon-editor-h5'
                    ],
                    'h6'  => [
                        'title' => __( 'H6', 'happy_addons' ),
                        'icon' => 'eicon-editor-h6'
                    ]
                ],
                'default' => 'h2',
                'toggle' => false,
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_review',
            [
                'label' => __( 'Review', 'happy_addons' ),
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
                'default' => __( 'Happy reviewer is super excited being part of happy addons family', 'happy_addons' ),
                'placeholder' => __( 'Type amazing review from happy reviewer', 'happy_addons' ),
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'align',
            [
                'label' => __( 'Alignment', 'happy_addons' ),
                'type' => Controls_Manager::CHOOSE,
                'separator' => 'before',
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
                    'justify' => [
                        'title' => __( 'Justify', 'happy_addons' ),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'review_position',
            [
                'label' => __( 'Review Position', 'happy_addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'before' => __( 'Review Before', 'happy_addons' ),
                    'after' => __( 'Review After', 'happy_addons' ),
                ],
                'default' => 'before',
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
                    '{{WRAPPER}}.ha-review--right .ha-review-body, {{WRAPPER}}.ha-review--left .ha-review-body' => 'flex: 0 0 calc(100% - {{SIZE}}{{UNIT}}); max-width: calc(100% - {{SIZE}}{{UNIT}});',
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
                        'min' => 70,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-review-figure' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

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
                    '{{WRAPPER}}.ha-review--left .ha-review-body' =>
                        'margin-left: {{image_offset_x.SIZE}}{{UNIT}};'
                        . 'flex: 0 0 calc((100% - {{image_width.SIZE}}{{image_width.UNIT}}) + (-1 * {{image_offset_x.SIZE}}{{UNIT}}));'
                        . 'max-width: calc((100% - {{image_width.SIZE}}{{image_width.UNIT}}) + (-1 * {{image_offset_x.SIZE}}{{UNIT}}));',
                    '{{WRAPPER}}.ha-review--right .ha-review-body' =>
                        'margin-right: calc(-1 * {{image_offset_x.SIZE}}{{UNIT}});'
                        . 'flex: 0 0 calc((100% - {{image_width.SIZE}}{{image_width.UNIT}}) + {{image_offset_x.SIZE}}{{UNIT}});'
                        . 'max-width: calc((100% - {{image_width.SIZE}}{{image_width.UNIT}}) + {{image_offset_x.SIZE}}{{UNIT}});',
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
            'ratting_spacing',
            [
                'label' => __( 'Bottom Spacing', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-review-ratting' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
            '_heading_name',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Name', 'happy_addons' ),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __( 'Bottom Spacing', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-review-reviewer' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .ha-review-reviewer',
                'scheme' => Scheme_Typography::TYPOGRAPHY_2,
            ]
        );

        $this->add_control(
            '_heading_job_title',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Job Title', 'happy_addons' ),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'job_title_spacing',
            [
                'label' => __( 'Bottom Spacing', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-review-position' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'job_title_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-review-position' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'job_title_typography',
                'selector' => '{{WRAPPER}} .ha-review-position',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            ]
        );

        $this->add_control(
            '_heading_review',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Review', 'happy_addons' ),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'review_spacing',
            [
                'label' => __( 'Bottom Spacing', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-review-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .ha-review-desc',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            ]
        );

        $this->end_controls_section();
    }

	protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_inline_editing_attributes( 'title', 'none' );
        $this->add_render_attribute( 'title', 'class', 'ha-review-reviewer' );

        $this->add_inline_editing_attributes( 'job_title', 'none' );
        $this->add_render_attribute( 'job_title', 'class', 'ha-review-position' );

        $this->add_inline_editing_attributes( 'review', 'basic' );
        $this->add_render_attribute( 'review', 'class', 'ha-review-desc' );

        $this->add_render_attribute( 'ratting', 'class', [
                'ha-review-ratting',
                'ha-review-ratting--' . $settings['ratting_style']
            ] );
        ?>

        <?php if ( $settings['image']['url'] || $settings['image']['id'] ) :
            $this->add_render_attribute( 'image', 'src', $settings['image']['url'] );
            $this->add_render_attribute( 'image', 'alt', Control_Media::get_image_alt( $settings['image'] ) );
            $this->add_render_attribute( 'image', 'title', Control_Media::get_image_title( $settings['image'] ) );
            $settings['hover_animation'] = 'disable-animation'; // hack to prevent image hover animation
            ?>
            <figure class="ha-review-figure">
                <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' ); ?>
            </figure>
        <?php endif; ?>

        <div class="ha-review-body">
            <?php if ( $settings['review_position'] === 'before' && $settings['review'] ) : ?>
                <div <?php echo $this->get_render_attribute_string( 'review' ); ?>>
                    <p><?php echo wp_kses_data( $settings['review'] ); ?></p>
                </div>
            <?php endif; ?>

            <div class="ha-review-header">
                <?php if ( $settings['title' ] ) :
                    printf( '<%1$s %2$s>%3$s</%1$s>',
                        tag_escape( $settings['title_tag'] ),
                        $this->get_render_attribute_string( 'title' ),
                        esc_html( $settings['title' ] )
                        );
                endif; ?>

                <?php if ( $settings['job_title' ] ) : ?>
                    <div <?php echo $this->get_render_attribute_string( 'job_title' ); ?>><?php echo esc_html( $settings['job_title' ] ); ?></div>
                <?php endif; ?>

                <div <?php echo $this->get_render_attribute_string( 'ratting' ); ?>>
                    <?php if ( $settings['ratting_style'] === 'num' ) : ?>
                        <?php echo esc_html( $settings['ratting']['size'] ); ?> <i class="fa fa-star"></i>
                    <?php else : ?>
                        <span><span style="width:<?php echo ($settings['ratting']['size'] * 20); ?>%"></span></span>
                    <?php endif; ?>
                 </div>
            </div>

            <?php if ( $settings['review_position'] === 'after' && $settings['review'] ) : ?>
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
        view.addInlineEditingAttributes( 'title', 'none' );
        view.addRenderAttribute( 'title', 'class', 'ha-review-reviewer' );

        view.addInlineEditingAttributes( 'job_title', 'none' );
        view.addRenderAttribute( 'job_title', 'class', 'ha-review-position' );

        view.addInlineEditingAttributes( 'review', 'basic' );
        view.addRenderAttribute( 'review', 'class', 'ha-review-desc' );

        if (settings.image.url && settings.image.id) {
            var image = {
                id: settings.image.id,
                url: settings.image.url,
                size: settings.thumbnail_size,
                dimension: settings.thumbnail_custom_dimension,
                model: view.getEditModel()
            };

            var image_url = elementor.imagesManager.getImageUrl( image );
            #>
            <figure class="ha-review-figure">
                <img src="{{ image_url }}">
            </figure>
        <# } #>

        <div class="ha-review-body">
            <# if (settings.review_position === 'before' && settings.review) { #>
                <div {{{ view.getRenderAttributeString( 'review' ) }}}>
                    <p>{{{ settings.review }}}</p>
                </div>
            <# } #>
            <div class="ha-review-header">
                <# if (settings.title) { #>
                    <{{ settings.title_tag }} {{{ view.getRenderAttributeString( 'title' ) }}}>{{ settings.title }}</{{ settings.title_tag }}>
                <# } #>
                <# if (settings.job_title) { #>
                    <div {{{ view.getRenderAttributeString( 'job_title' ) }}}>{{ settings.job_title }}</div>
                <# } #>
                <# if ( settings.ratting_style === 'num' ) { #>
                    <div class="ha-review-ratting ha-review-ratting--num">{{ settings.ratting.size }} <i class="fa fa-star"></i></div>
                <# } else { var ratingPercent = ( settings.ratting.size * 20 ) #>
                    <div class="ha-review-ratting ha-review-ratting--star">
                        <span><span style="width:{{ ratingPercent }}%"></span></span>
                    </div>
                <# } #>
            </div>
            <# if ( settings.review_position === 'after' && settings.review) { #>
                <div {{{ view.getRenderAttributeString( 'review' ) }}}>
                    <p>{{{ settings.review }}}</p>
                </div>
            <# } #>
        </div>
        <?php
    }
}
