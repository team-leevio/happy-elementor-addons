<?php
/**
 * Feature List widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class Feature_List extends Base {
    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Feature List', 'happy-elementor-addons' );
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
        return 'hm hm-spark';
    }

    public function get_keywords() {
        return [ 'list', 'feature list' ];
    }

    protected function register_content_controls() {
        $this->start_controls_section(
            '_section_lists',
            [
                'label' => __( 'Feature List', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'icon_type',
            [
                'label' => __( 'Icon Type', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'icon' => __( 'Icon', 'happy-elementor-addons' ),
                    'number' => __( 'Number', 'happy-elementor-addons' ),
                    'image' => __( 'Image', 'happy-elementor-addons' ),
                ],
            ]
        );

        $repeater->add_control(
            'icon',
            [
                'label' => __( 'Icon', 'happy-elementor-addons' ),
                'type' => Controls_Manager::ICON,
                'default' => 'fa fa-smile-o',
                'condition' => [
                    'icon_type' => 'icon'
                ],
                'options' => ha_get_happy_icons(),
            ]
        );

        $repeater->add_control(
            'number',
            [
                'label' => __( 'Item Number', 'happy-elementor-addons' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'List Item Number', 'happy-elementor-addons' ),
                'default' => __( '1', 'happy-elementor-addons' ),
                'condition' => [
                    'icon_type' => 'number'
                ],
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label' => __( 'Image', 'happy-elementor-addons' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'icon_type' => 'image'
                ]
            ]
        );

        $repeater->add_control(
            'text_heading',
            [
                'label' => __( 'Text & Link', 'happy-elementor-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => __( 'Title', 'happy-elementor-addons' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => __( 'List Item', 'happy-elementor-addons' ),
                'default' => __( 'List Item', 'happy-elementor-addons' ),
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => __( 'Link', 'happy-elementor-addons' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://example.com', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'list_item',
            [
                'show_label' => false,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ text }}}',
                'default' => [
                    [
                        'text' => __( 'List Item', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-smile-o',
                    ],
                    [
                        'text' => __( 'List Item', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-smile-o',
                    ],
                    [
                        'text' => __( 'List Item', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-smile-o',
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_settings',
            [
                'label' => __( 'Settings', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'content_layout',
            [
                'label' => __( 'Layout', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'column' => [
                        'title' => __( 'Default', 'happy-elementor-addons' ),
                        'icon' => 'eicon-editor-list-ul',
                    ],
                    'row' => [
                        'title' => __( 'Inline', 'happy-elementor-addons' ),
                        'icon' => 'eicon-ellipsis-h',
                    ],
                ],
                'toggle' => false,
                'default' => 'row',
                'prefix_class' => 'ha-content--',
                'selectors' => [
                    '{{WRAPPER}} .ha-list' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_alignment',
            [
                'label' => __( 'Alignment', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'flex-start' => [
                        'title' => __( 'Left', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Left', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'flex-end' => [
                        'title' => __( 'Right', 'happy-elementor-addons' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'toggle' => false,
                'default' => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .ha-list' => 'align-items: {{VALUE}};',
                    '{{WRAPPER}}.ha-content--row .ha-list' => 'justify-content: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'icon_position',
            [
                'label' => __( 'Bullet Position', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'row' => [
                        'title' => __( 'Left', 'happy-elementor-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'row-reverse' => [
                        'title' => __( 'Right', 'happy-elementor-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => false,
                'default' => 'row',
                'prefix_class' => 'ha-icon--',
                'selectors' => [
                    '{{WRAPPER}} .ha-content' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

//        $this->add_group_control(
//            Group_Control_Image_Size::get_type(),
//            [
//                'name' => 'thumbnail',
//                'default' => 'thumbnail',
//                'exclude' => [
//                    'full',
//                    'shop_catalog',
//                    'shop_single',
//                ],
//            ]
//        );

        $this->end_controls_section();

    }

    protected function register_style_controls() {
        $this->start_controls_section(
            '_section_common_style',
            [
                'label' => __( 'Common', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_spacing',
            [
                'label' => __( 'Bottom Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => __( 'Padding', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'divider',
            [
                'label' => __( 'Divider', 'elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'Off', 'elementor' ),
                'label_on' => __( 'On', 'elementor' ),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'divider_style',
            [
                'label' => __( 'Style', 'elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'solid' => __( 'Solid', 'elementor' ),
                    'double' => __( 'Double', 'elementor' ),
                    'dotted' => __( 'Dotted', 'elementor' ),
                    'dashed' => __( 'Dashed', 'elementor' ),
                ],
                'condition' => [
                    'divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item:not(:last-child) .ha-content:after' => 'border-top-style: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'divider_weight',
            [
                'label' => __( 'Weight', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 20,
                    ],
                ],
                'condition' => [
                    'divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item:not(:last-child) .ha-content:after' => 'border-top-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'divider_width',
            [
                'label' => __( 'Width', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 600,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 300,
                    ]
                ],
                'condition' => [
                    'divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item:not(:last-child) .ha-content:after' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'divider_color',
            [
                'label' => __( 'Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
                'condition' => [
                    'divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item:not(:last-child) .ha-content:after' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_icon_style',
            [
                'label' => __( 'Icon Type', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __( 'Size', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 250,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-icon.icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ha-icon.image img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_background_spacing',
            [
                'label' => __( 'Padding', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'selector' => '{{WRAPPER}} .ha-icon',
            ]
        );

        $this->add_control(
            'icon_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ha-icon.image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'number_typography',
                'label' => __( 'Number Typography', 'happy-elementor-addons' ),
                'selector' => '{{WRAPPER}} .ha-icon.number span',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-icon i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .ha-icon span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_background',
            [
                'label' => __( 'Background Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-icon' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_icon_text',
            [
                'label' => __( 'Text', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'text_spacing',
            [
                'label' => __( 'Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-content .ha-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .ha-text h2',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-text h2' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'text_link_color',
            [
                'label' => __( 'Link Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-text h2 a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'text_link_hover_color',
            [
                'label' => __( 'Hover Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-text h2 a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>

        <ul class="ha-list">
            <?php foreach ( $settings['list_item'] as $index => $item ) :
                // link
                $this->add_render_attribute( 'link', 'class', 'ha-link' );
                $this->add_render_attribute( 'link', 'href', esc_url( $item['link']['url'] ) );
                if ( ! empty( $item['link']['is_external'] ) ) {
                    $this->add_render_attribute( 'link', 'target', '_blank' );
                }
                if ( ! empty( $item['link']['nofollow'] ) ) {
                    $this->set_render_attribute( 'link', 'rel', 'nofollow' );
                }
                ?>
                <li class="ha-list-item">
                    <div class="ha-content">

                        <?php if( $item['icon'] ) : ?>
                            <div class="ha-icon icon">
                                <i class="<?php echo esc_attr( $item['icon'] ); ?>"></i>
                            </div>

                        <?php elseif( $item['number'] ) : ?>
                            <div class="ha-icon number">
                                <span><?php echo esc_html( $item['number'] ); ?></span>
                            </div>

                        <?php elseif( $item['image'] ) :

                            $images = wp_get_attachment_image_src( $item['image']['id'], 'thumbnail', false );
                            $image = $images[0];
                            if( !$image ) {
                                $image = $item['image']['url'];
                            }
                            ?>
                            <div class="ha-icon image">
                                <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" />
                            </div>

                        <?php
                        else: return;
                        endif;
                        ?>

                        <div class="ha-text">
                            <?php if ( !empty( $item['link']['url'] ) ) : ?>
                                <h4>
                                    <a <?php echo $this->get_render_attribute_string( 'link' ); ?>>
                                        <?php echo esc_html( $item['title'] ); ?>
                                    </a>
                                </h4>
                            <?php else : ?>

                                <h4><?php echo esc_html( $item['title'] ); ?></h4>

                            <?php endif; ?>
                        </div>

                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php
    }

//    public function _content_template() {}

}