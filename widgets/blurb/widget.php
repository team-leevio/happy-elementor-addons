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

defined( 'ABSPATH' ) || die();

class Blurb extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Happy Blurb', 'happy_addons' );
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
        return [ 'info', 'blurb', 'box', 'text', 'content' ];
    }

    /**
     * Register content related controls
     */
	protected function register_content_controls() {
		$this->start_controls_section(
			'_section_media',
			[
				'label' => __( 'Icon / Image', 'happy_addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
            'type',
            [
                'label' => __( 'Type', 'happy_addons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'icon' => [
                        'title' => __( 'Icon', 'happy_addons' ),
                        'icon' => 'eicon-icon-box',
                    ],
                    'image' => [
                        'title' => __( 'Image', 'happy_addons' ),
                        'icon' => 'eicon-image-box',
                    ],
                ],
				'default' => 'icon',
				'toggle' => false,
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => __( 'Image', 'happy_addons' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'type' => 'image'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image',
                'default' => 'thumbnail',
                'separator' => 'none',
                'exclude' => [
                    'full',
                    'custom',
                    'large',
                    'shop_catalog',
                    'shop_single',
                    'shop_thumbnail'
                ],
                'condition' => [
                    'type' => 'image'
                ]
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => __( 'Icon', 'happy_addons' ),
                'type' => Controls_Manager::ICON,
                'default' => 'fa fa-smile-o',
                'condition' => [
                    'type' => 'icon'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_title',
            [
                'label' => __( 'Title & Description', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'happy_addons' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Default title', 'happy_addons' ),
                'placeholder' => __( 'Type your blurb title', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __( 'Description', 'happy_addons' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __( 'Default description', 'happy_addons' ),
                'placeholder' => __( 'Type your blurb description', 'happy_addons' ),
                'rows' => 5
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => __( 'Title HTML Tag', 'happy_addons' ),
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
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}}'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_link',
            [
                'label' => __( 'Link', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'link_text',
            [
                'label' => __( 'Text', 'happy_addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Default text', 'happy_addons' ),
                'placeholder' => __( 'Type your link text', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'link_url',
            [
                'label' => __( 'URL', 'happy_addons' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://example.com/', 'happy_addons' ),
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register styles related controls
     */
    protected function register_style_controls() {
        $this->start_controls_section(
            '_section_media_style',
            [
                'label' => __( 'Icon / Image', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __( 'Size', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-blurb-figure--icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                     'type' => 'icon'
                ]
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
                        'min' => 1,
                        'max' => 400,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-blurb-figure--image' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'type' => 'image'
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
                        'min' => 1,
                        'max' => 400,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-blurb-figure--image' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'type' => 'image'
                ]
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
            'media_offset_x',
            [
                'label' => __( 'Offset Left', 'happy_addons' ),
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
            'media_offset_y',
            [
                'label' => __( 'Offset Top', 'happy_addons' ),
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
                    '{{WRAPPER}} .ha-blurb-figure' => '-ms-transform: translate({{media_offset_x.SIZE}}{{UNIT}}, {{SIZE}}{{UNIT}}); -webkit-transform: translate({{media_offset_x.SIZE}}{{UNIT}}, {{SIZE}}{{UNIT}}); transform: translate({{media_offset_x.SIZE}}{{UNIT}}, {{SIZE}}{{UNIT}});',
                    '{{WRAPPER}} .ha-blurb-body' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_popover();

        $this->add_responsive_control(
            'media_spacing',
            [
                'label' => __( 'Spacing', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-blurb-figure' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'media_padding',
            [
                'label' => __( 'Padding', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-blurb-figure--image > img, {{WRAPPER}} .ha-blurb-figure--icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'media_border',
                'selector' => '{{WRAPPER}} .ha-blurb-figure--image > img, {{WRAPPER}} .ha-blurb-figure--icon'
            ]
        );

        $this->add_responsive_control(
            'media_border_radius',
            [
                'label' => __( 'Border Radius', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-blurb-figure--image > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ha-blurb-figure--icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'media_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .ha-blurb-figure--image > img, {{WRAPPER}} .ha-blurb-figure--icon'
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __( 'Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-blurb-figure--icon' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'type' => 'icon'
                ]
            ]
        );

        $this->add_control(
            'icon_bg_color',
            [
                'label' => __( 'Background Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-blurb-figure--icon' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'type' => 'icon'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_title_style',
            [
                'label' => __( 'Title & Description', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __( 'Padding', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-blurb-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
            'title_spacing',
            [
                'label' => __( 'Spacing', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-blurb-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-blurb-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __( 'Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-blurb-title',
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
            'description_spacing',
            [
                'label' => __( 'Spacing', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-blurb-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-blurb-text' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => __( 'Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-blurb-text',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_link_style',
            [
                'label' => __( 'Link', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'link_padding',
            [
                'label' => __( 'Padding', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typography',
                'selector' => '{{WRAPPER}} .ha-link',
            ]
        );

        $this->start_controls_tabs( 'tabs_link_style' );

        $this->start_controls_tab(
            'tab_link_normal',
            [
                'label' => __( 'Normal', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ha-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_link_hover',
            [
                'label' => __( 'Hover', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'link_hover_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-link:hover, {{WRAPPER}} .ha-link:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

	protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_inline_editing_attributes( 'title', 'none' );
        $this->add_render_attribute( 'title', 'class', 'ha-blurb-title' );

        $this->add_inline_editing_attributes( 'description', 'basic' );
        $this->add_render_attribute( 'description', 'class', 'ha-blurb-text' );

        $this->add_inline_editing_attributes( 'link_text', 'none' );
        $this->add_render_attribute( 'link_text', 'class', 'ha-link' );

        $this->add_render_attribute( 'link_text', 'href', esc_url( $settings['link_url']['url'] ) );
        if ( ! empty( $settings['link_url']['is_external'] ) ) {
            $this->add_render_attribute( 'link_text', 'target', '_blank' );
        }
        if ( ! empty( $settings['link_url']['nofollow'] ) ) {
            $this->set_render_attribute( 'link_text', 'rel', 'nofollow' );
        }
        ?>

        <?php if ( $settings['type'] === 'image' ) : ?>
            <?php if ( ! empty( $settings['image']['url'] ) ) :
                $this->add_render_attribute( 'image', 'src', $settings['image']['url'] );
                $this->add_render_attribute( 'image', 'alt', Control_Media::get_image_alt( $settings['image'] ) );
                $this->add_render_attribute( 'image', 'title', Control_Media::get_image_title( $settings['image'] ) );
                $settings['hover_animation'] = 'disable-animation'; // hack to prevent image hover animation
                ?>
                <figure class="ha-blurb-figure ha-blurb-figure--image">
                    <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'image' ); ?>
                </figure>
            <?php endif; ?>
        <?php else: ?>
            <figure class="ha-blurb-figure ha-blurb-figure--icon">
                <i aria-hidden="true" class="<?php echo esc_attr( $settings['icon'] ); ?>"></i>
            </figure>
        <?php endif; ?>

        <div class="ha-blurb-body">
            <?php printf( '<%1$s %2$s>%3$s</%1$s>',
                tag_escape( $settings['title_tag'] ),
                $this->get_render_attribute_string( 'title' ),
                esc_html( $settings['title' ] )
            ); ?>
            <div <?php echo $this->get_render_attribute_string( 'description' ); ?>>
                <p><?php echo wp_kses_data( $settings['description'] ); ?></p>
            </div>
            <?php
            if ( $settings['link_text'] ):
                printf( '<a %1$s>%2$s</a>',
                    $this->get_render_attribute_string( 'link_text' ),
                    esc_html( $settings['link_text'] )
                );
            endif;
            ?>
        </div>
        <?php
    }

    public function _content_template() {
        ?>
        <#
        view.addInlineEditingAttributes( 'title', 'none' );
        view.addRenderAttribute( 'title', 'class', 'ha-blurb-title' );

        view.addInlineEditingAttributes( 'description', 'basic' );
        view.addRenderAttribute( 'description', 'class', 'ha-blurb-text' );

        view.addInlineEditingAttributes( 'link_text', 'none' );
        view.addRenderAttribute( 'link_text', 'class', 'ha-link' );
        view.addRenderAttribute( 'link_text', 'href', settings.link_url.url );

        if ( settings.type === 'image' ) {
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
                <figure class="ha-blurb-figure ha-blurb-figure--image">
                    <img src="{{ image_url }}">
                </figure>
            <# }
        } else { #>
            <figure class="ha-blurb-figure ha-blurb-figure--icon">
                <i aria-hidden="true" class="{{ settings.icon }}"></i>
            </figure>
        <# } #>

        <div class="ha-blurb-body">
            <{{ settings.title_tag }} {{{ view.getRenderAttributeString( 'title' ) }}}>{{ settings.title }}</{{ settings.title_tag }}>

            <div {{{ view.getRenderAttributeString( 'description' ) }}}>
                <p>{{{ settings.description }}}</p>
            </div>

            <# if ( settings.link_text ) { #>
                <a {{{ view.getRenderAttributeString( 'link_text' ) }}}>{{ settings.link_text }}</a>
            <# } #>
        </div>
        <?php
    }
}
