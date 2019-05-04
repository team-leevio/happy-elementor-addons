<?php
/**
 * Carousel widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Group_Control_Background;
use Elementor\Repeater;
use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class Carousel extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Happy Carousel', 'happy_addons' );
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
        return [ 'slider', 'image', 'gallery', 'carousel' ];
    }

	protected function register_content_controls() {
        $this->start_controls_section(
            '_section_images',
            [
                'label' => __( 'Images', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'type' => Controls_Manager::MEDIA,
                'label' => __( 'Image', 'happy_addons' ),
            ]
        );

        $repeater->add_control(
            'title',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' => __( 'Title', 'happy_addons' ),
                'placeholder' => __( 'Type title here', 'happy_addons' )
            ]
        );

        $repeater->add_control(
            'subtitle',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' => __( 'Subtitle', 'happy_addons' ),
                'placeholder' => __( 'Type subtitle here', 'happy_addons' )
            ]
        );

        $this->add_control(
            'slides',
            [
                'show_label' => false,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '<# print(title || "Carousel Item"); #>'
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'medium_large',
                'separator' => 'before',
                'exclude' => [
                    'custom'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_settings',
            [
                'label' => __( 'Settings', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'nav',
            [
                'label' => __( 'Display Navigation?', 'happy_addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_block' => true,
                'label_on' => __( 'Yes', 'happy_addons' ),
                'label_off' => __( 'No', 'happy_addons' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __( 'Enable to display next and previous navigation', 'happy_addons' )
            ]
        );

        $this->add_responsive_control(
            'dots',
            [
                'label' => __( 'Display Pagination?', 'happy_addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_block' => true,
                'label_on' => __( 'Yes', 'happy_addons' ),
                'label_off' => __( 'No', 'happy_addons' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __( 'Enable to display dots pagination', 'happy_addons' )
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __( 'Enable Autoplay', 'happy_addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'happy_addons' ),
                'label_off' => __( 'No', 'happy_addons' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_timeout',
            [
                'label' => __( 'Autoplay Timeout', 'happy_addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1000,
                'step' => 100,
                'max' => 10000,
                'default' => 5000,
                'description' => __( 'Set autoplay start time in milliseconds', 'happy_addons' ),
                'condition' => ['autoplay' => ['yes']],
            ]
        );

        $this->add_control(
            'hover_pause',
            [
                'label' => __( 'Pause On Hover', 'happy_addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'happy_addons' ),
                'label_off' => __( 'No', 'happy_addons' ),
                'return_value' => 'yes',
                'description' => __( 'Enable to pause the slides on mouse over', 'happy_addons' ),
                'condition' => ['autoplay' => ['yes']],
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => __( 'Enable Loop', 'happy_addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'happy_addons' ),
                'label_off' => __( 'No', 'happy_addons' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'items',
            [
                'label' => __( 'Columns', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 2,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'size' => 3,
                ],
            ]
        );

        $this->add_control(
            'center',
            [
                'label' => __( 'Enable Center', 'happy_addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'happy_addons' ),
                'label_off' => __( 'No', 'happy_addons' ),
                'return_value' => 'yes',
                'description' => __( 'This setting best works with loop enabled and even number of items', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'margin',
            [
                'label' => __( 'Margin', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 10,
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_controls() {
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
                'selector' => '{{WRAPPER}} .owl-carousel .owl-item',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .owl-carousel .owl-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_style_content',
            [
                'label' => __( 'Content', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __( 'Content Padding', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-carousel-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'content_background',
                'selector' => '{{WRAPPER}} .ha-carousel-text',
                'exclude' => [
                     'image'
                ]
            ]
        );

        $this->add_control(
            '_heading_title',
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
                    '{{WRAPPER}} .ha-carousel-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-carousel-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title',
                'label' => __( 'Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-carousel-title',
            ]
        );

        $this->add_control(
            '_heading_subtitle',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Subtitle', 'happy_addons' ),
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'subtitle_spacing',
            [
                'label' => __( 'Spacing', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-carousel-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-carousel-subtitle' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle',
                'label' => __( 'Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-carousel-subtitle',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_style_navigation',
            [
                'label' => __( 'Navigation', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'offset_toggle',
            [
                'label' => __( 'Offset', 'happy_addons' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'label_off' => __( 'None', 'happy_addons' ),
                'label_on' => __( 'Custom', 'happy_addons' ),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_responsive_control(
            'navigation_offset_x',
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
            'navigation_offset_y',
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
                    '{{WRAPPER}} .ha-card-figure' => '-ms-transform: translate({{image_offset_x.SIZE}}{{UNIT}}, {{SIZE}}{{UNIT}}); -webkit-transform: translate({{image_offset_x.SIZE}}{{UNIT}}, {{SIZE}}{{UNIT}}); transform: translate({{image_offset_x.SIZE}}{{UNIT}}, {{SIZE}}{{UNIT}});',
                    '{{WRAPPER}}.ha-card--top .ha-card-body' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ha-card--left .ha-card-body' => 'margin-left: {{image_offset_x.SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ha-card--right .ha-card-body' => 'margin-right: calc(-1 * {{image_offset_x.SIZE}}{{UNIT}});',
                ],
            ]
        );
        $this->end_popover();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'navigation_border',
                'selector' => '{{WRAPPER}} .owl-carousel .owl-item',
            ]
        );

        $this->add_responsive_control(
            'navigation_border_radius',
            [
                'label' => __( 'Border Radius', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .owl-carousel .owl-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_style_pagination',
            [
                'label' => __( 'Pagination', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'pagination_color',
            [
                'label' => __( 'Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .owl-theme .owl-dots .owl-dot span, {{WRAPPER}} .owl-theme .owl-dots .owl-dot.active span' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pagination_hover_color',
            [
                'label' => __( 'Hover Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .owl-theme .owl-dots .owl-dot:hover span' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected static function get_data_prop_settings( $settings ) {
        $field_map = [
            'nav' => 'nav.bool',
            'nav_tablet' => 'nav_tablet.bool',
            'nav_mobile' => 'nav_mobile.bool',
            'dots' => 'dots.bool',
            'dots_tablet' => 'dots_tablet.bool',
            'dots_mobile' => 'dots_mobile.bool',
            'autoplay' => 'autoplay.bool',
            'autoplay_timeout' => 'autoplayTimeout.int',
            'hover_pause' => 'autoplayHoverPause.bool',
            'loop' => 'loop.bool',
            'center' => 'center.bool',
            'items.size' => 'items.int',
            'items_tablet.size' => 'items_tablet.int',
            'items_mobile.size' => 'items_mobile.int',
            'margin.size' => 'margin.int'
        ];

        return ha_prepare_data_prop_settings( $settings, $field_map );
    }

	protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['slides'] ) ) {
            return;
        }

        $this->add_render_attribute( 'container', 'class', 'owl-carousel owl-theme hajs-carousel ha-carousel--regular-nav' );
        $this->add_render_attribute( 'container', 'data-happy-settings', self::get_data_prop_settings( $settings ) );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'container' ); ?>>
            <?php foreach ( $settings['slides'] as $slide ) :
                if ( ! ( $image = wp_get_attachment_image_url( $slide['image']['id'], $settings['thumbnail_size'] ) ) ) {
                    continue;
                }
                ?>
                <div class="item">
                    <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr( wp_get_attachment_caption( $slide['image']['id'] ) ); ?>">
                    <?php if ( $slide['title'] || $slide['subtitle'] ) : ?>
                        <div class="ha-carousel-text">
                            <?php if ( $slide['title'] ) : ?>
                                <h2 class="ha-carousel-title"><?php echo esc_html( $slide['title'] ); ?></h2>
                            <?php endif; ?>
                            <?php if ( $slide['subtitle'] ) : ?>
                                <p class="ha-carousel-subtitle"><?php echo esc_html( $slide['subtitle'] ); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}
