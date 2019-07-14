<?php
/**
 * Feature List widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
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
            'icon_heading',
            [
                'label' => __( 'Icon', 'happy-elementor-addons' ),
                'type' => Controls_Manager::HEADING,
            ]
        );

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
                'label_block' => true,
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
                'label' => __( 'Text', 'happy-elementor-addons' ),
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

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'thumbnail',
                'exclude' => [
                    'full',
                    'shop_catalog',
                    'shop_single',
                ],
            ]
        );

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
                'label' => __( 'Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

//        $this->add_responsive_control(
//            'content_position',
//            [
//                'label' => __( 'Content Position', 'happy-elementor-addons' ),
//                'type' => Controls_Manager::CHOOSE,
//                'default' => 'flex-start',
//                'toggle' => false,
//                'options' => [
//                    'flex-start' => [
//                        'title' => __( 'Left', 'happy-elementor-addons' ),
//                        'icon' => 'fa fa-align-left',
//                    ],
//                    'center' => [
//                        'title' => __( 'Center', 'happy-elementor-addons' ),
//                        'icon' => 'fa fa-align-left',
//                    ],
//                    'flex-end' => [
//                        'title' => __( 'Right', 'happy-elementor-addons' ),
//                        'icon' => 'fa fa-align-left',
//                    ],
//                    'selectors' => [
//                        '{{WRAPPER}} .ha-list-item' => 'justify-content: {{VALUE}}'
//                    ]
//                ],
//            ]
//        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_icon_style',
            [
                'label' => __( 'Icon', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
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
                'default' => '#375472',
                'selectors' => [
                    '{{WRAPPER}} .ha-icon i' => 'background: {{VALUE}}',
                    '{{WRAPPER}} .ha-icon span' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_font_size',
            [
                'label' => __( 'Icon Size', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_background_spacing',
            [
                'label' => __( 'Background Size', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'unit' => 'px',
                    'top' => 15,
                    'right' => 20,
                    'bottom' => 15,
                    'left' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-icon i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ha-icon span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'unit' => '%',
                    'top' => 50,
                    'right' => 50,
                    'bottom' => 50,
                    'left' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-icon i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ha-icon span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
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
                    <?php if( $item['icon'] ) : ?>
                        <div class="ha-icon">
                            <i class="<?php echo esc_attr( $item['icon'] ); ?>"></i>
                        </div>

                    <?php elseif( $item['number'] ) : ?>
                        <div class="ha-icon">
                            <span><?php echo esc_html( $item['number'] ); ?></span>
                        </div>

                    <?php elseif( $item['image'] ) :

                        $images = wp_get_attachment_image_src($item['image']['id'], $settings['thumbnail_size'], false);
                        $image = $images[0];
                        if( !$image ) {
                            $image = $item['image']['url'];
                        }
                        ?>
                        <div class="ha-icon">
                            <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" />
                        </div>

                    <?php
                    else: return;
                    endif;
                    ?>

                    <div class="ha-text">
                        <?php if ( !empty( $item['link']['url'] ) ) : ?>
                            <h2>
                                <a <?php echo $this->get_render_attribute_string( 'link' ); ?>>
                                    <?php echo esc_html( $item['title'] ); ?>
                                </a>
                            </h2>
                        <?php else : ?>

                            <h2><?php echo esc_html( $item['title'] ); ?></h2>

                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php
    }

//    public function _content_template() {}

}