<?php
/**
 * Person widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || die();

class Member extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Happy Member', 'happy_addons' );
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
        return [ 'team', 'member', 'crew', 'staff', 'person' ];
    }

    protected static function get_link_items() {
        return [
            '500px' => __( '500px', 'happy_addons' ),
            'behance' => __( 'Behance', 'happy_addons' ),
            'deviantart' => __( 'DeviantArt', 'happy_addons' ),
            'dribbble' => __( 'Dribbble', 'happy_addons' ),
            'facebook' => __( 'Facebook', 'happy_addons' ),
            'flickr' => __( 'Flickr', 'happy_addons' ),
            'github' => __( 'Github', 'happy_addons' ),
            'instagram' => __( 'Instagram', 'happy_addons' ),
            'jsfiddle' => __( 'JSFiddle', 'happy_addons' ),
            'linkedin' => __( 'LinkedIn', 'happy_addons' ),
            'medium' => __( 'Medium', 'happy_addons' ),
            'pinterest' => __( 'Pinterest', 'happy_addons' ),
            'reddit' => __( 'Reddit', 'happy_addons' ),
            'soundcloud' => __( 'SoundCloud', 'happy_addons' ),
            'stack-exchange' => __( 'Stack Exchange', 'happy_addons' ),
            'stack-overflow' => __( 'Stack Overflow', 'happy_addons' ),
            'tumblr' => __( 'Tumblr', 'happy_addons' ),
            'twitter' => __( 'Twitter', 'happy_addons' ),
            'web' => __( 'Website', 'happy_addons' ),
            'wordpress' => __( 'WordPress', 'happy_addons' ),
            'youtube' => __( 'Youtube', 'happy_addons' ),
        ];
    }

    /**
     * Register content related controls
     */
	protected function register_content_controls() {
		$this->start_controls_section(
			'_section_info',
			[
				'label' => __( 'Basic Information', 'happy_addons' ),
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
                'label' => __( 'Photo Size', 'happy_addons' ),
                'default' => 'medium',
                'separator' => 'none',
            ]
        );

        $this->add_control(
            'name',
            [
                'label' => __( 'Name', 'happy_addons' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Jhon Doe', 'happy_addons' ),
                'placeholder' => __( 'Type member name', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'role',
            [
                'label' => __( 'Job Role', 'happy_addons' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Head Of Ideas', 'happy_addons' ),
                'placeholder' => __( 'Type member job role', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'bio',
            [
                'label' => __( 'Short Bio', 'happy_addons' ),
                'type' => Controls_Manager::TEXTAREA,
                'placeholder' => __( 'Write something amazing about the member', 'happy_addons' ),
                'rows' => 5
            ]
        );

        $this->add_control(
            'name_tag',
            [
                'label' => __( 'Name HTML Tag', 'happy_addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1'  => __( 'H1', 'happy_addons' ),
                    'h2'  => __( 'H2', 'happy_addons' ),
                    'h3'  => __( 'H3', 'happy_addons' ),
                    'h4'  => __( 'H4', 'happy_addons' ),
                    'h5'  => __( 'H5', 'happy_addons' ),
                    'h6'  => __( 'H6', 'happy_addons' ),
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
                'desktop_default' => 'left',
                'toggle' => false,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'prefix_class' => 'ha-text--%s'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_social',
            [
                'label' => __( 'Social Information', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'media',
            [
                'label' => __( 'Media', 'happy_addons' ),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'select2options' => [
                    'allowClear' => false,
                ],
                'options' => self::get_link_items()
            ]
        );

        $repeater->add_control(
            'link', [
                'label' => __( 'Link', 'happy_addons' ),
                'placeholder' => __( 'Type or paste social link', 'happy_addons' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'autocomplete' => false,
                'show_external' => false,
            ]
        );

        $this->add_control(
            'social_links',
            [
                'label' => __( 'Social Links', 'happy_addons' ),
                'label_block' => true,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{ media }}',
                'default' => [
                    [
                        'link' => [
                            'url' => '#',
                        ],
                        'media' => 'facebook'
                    ],
                    [
                        'link' => [
                            'url' => '#',
                        ],
                        'media' => 'twitter'
                    ],
                    [
                        'link' => [
                            'url' => '#',
                        ],
                        'media' => 'linkedin'
                    ]
                ]
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register styles related controls
     */
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
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px', '%'],
                'range' => [
                    '%' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 150,
                        'max' => 400,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-member-figure' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => __( 'Padding', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-member-figure > img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .ha-member-figure > img, {{WRAPPER}} .ha-blurb-figure--icon'
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-member-figure > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .ha-member-figure > img'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_info_style',
            [
                'label' => __( 'Name, Role & Bio', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            '_name_heading',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Name', 'happy_addons' ),
            ]
        );

        $this->add_responsive_control(
            'name_margin',
            [
                'label' => __( 'Margin', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-member-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-member-name' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'label' => __( 'Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-member-name',
            ]
        );

        $this->add_control(
            '_role_heading',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Job Role', 'happy_addons' ),
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
                    '{{WRAPPER}} .ha-member-role' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'role_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-member-role' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'role_typography',
                'label' => __( 'Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-member-role',
            ]
        );

        $this->add_control(
            '_bio_heading',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Short Bio', 'happy_addons' ),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'bio_margin',
            [
                'label' => __( 'Margin', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-member-bio' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'bio_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-member-bio' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'bio_typography',
                'label' => __( 'Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-member-bio',
            ]
        );

        $this->end_controls_section();
    }

	protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_inline_editing_attributes( 'name', 'none' );
        $this->add_render_attribute( 'name', 'class', 'ha-member-name' );

        $this->add_inline_editing_attributes( 'role', 'none' );
        $this->add_render_attribute( 'role', 'class', 'ha-member-role' );

        $this->add_inline_editing_attributes( 'bio', 'basic' );
        $this->add_render_attribute( 'bio', 'class', 'ha-member-bio' );
        ?>

        <?php if ( ! empty( $settings['image']['url'] ) ) :
            $this->add_render_attribute( 'image', 'src', $settings['image']['url'] );
            $this->add_render_attribute( 'image', 'alt', Control_Media::get_image_alt( $settings['image'] ) );
            $this->add_render_attribute( 'image', 'title', Control_Media::get_image_title( $settings['image'] ) );
            $settings['hover_animation'] = 'disable-animation'; // hack to prevent image hover animation
            ?>
            <figure class="ha-member-figure">
                <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'image' ); ?>
            </figure>
        <?php endif; ?>

        <?php printf( '<%1$s %2$s>%3$s</%1$s>',
            tag_escape( $settings['name_tag'] ),
            $this->get_render_attribute_string( 'name' ),
            esc_html( $settings['name' ] )
        ); ?>
        <div <?php echo $this->get_render_attribute_string( 'role' ); ?>><?php echo esc_html( $settings['role' ] ); ?></div>
        <div <?php echo $this->get_render_attribute_string( 'bio' ); ?>>
            <p><?php echo wp_kses_data( $settings['bio'] ); ?></p>
        </div>

        <?php if ( ! empty( $settings['social_links' ] ) ) : ?>
            <div class="ha-member-social-link">
                <?php
                foreach ( $settings['social_links'] as $props ) :
                    $url = $props['link']['url'];
                    $media = $props['media'];
                    if ( ! $url ) {
                        continue;
                    }

                    if ( $media === 'web' ) {
                        $media = 'globe';
                    }

                    printf( '<a target="_blank" rel="noopener" href="%s"><i class="fa fa-%s" aria-hidden="true"></i></a>',
                        esc_url( $url ),
                        esc_attr( $media )
                    );
                endforeach; ?>
            </div>
        <?php endif;
    }

    public function _content_template() {
        ?>
        <#
        view.addInlineEditingAttributes( 'name', 'none' );
        view.addRenderAttribute( 'name', 'class', 'ha-member-name' );

        view.addInlineEditingAttributes( 'role', 'none' );
        view.addRenderAttribute( 'role', 'class', 'ha-member-role' );

        view.addInlineEditingAttributes( 'bio', 'basic' );
        view.addRenderAttribute( 'bio', 'class', 'ha-member-bio' );

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
            <figure class="ha-member-figure">
                <img src="{{ image_url }}">
            </figure>
        <# } #>
        <{{ settings.name_tag }} {{{ view.getRenderAttributeString( 'name' ) }}}>{{ settings.name }}</{{ settings.name_tag }}>
        <div {{{ view.getRenderAttributeString( 'role' ) }}}>{{ settings.role }}</div>
        <div {{{ view.getRenderAttributeString( 'bio' ) }}}>
            <p>{{{ settings.bio }}}</p>
        </div>
        <div class="ha-member-social-link">
            <#
            if ( ! _.isEmpty(settings.social_links) && _.isArray(settings.social_links) ) {
                var links = [];
                _.each(settings.social_links, function(val) {
                    var media = val.media;
                    if ( _.isEmpty(val.link.url) ) {
                        return;
                    }

                    if ( media === 'web' ) {
                        media = 'globe';
                    }

                    links.push('<a href="'+ val.link.url +'"><i class="fa fa-'+ media +'"></i></a>');
                });
                print(links.join('\n'));
            }
            #>
        </div>
        <?php
    }
}
