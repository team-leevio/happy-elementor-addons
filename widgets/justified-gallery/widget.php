<?php
/**
 * Justified gallery widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class Justified_Gallery extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Justified Gallery', 'happy-elementor-addons' );
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
        return 'hm hm-brick-wall';
    }

    public function get_keywords() {
        return [ 'gallery', 'image', 'justified', 'filter' ];
    }

	protected function register_content_controls() {
        $this->start_controls_section(
            '_section_gallery',
            [
                'label' => __( 'Gallery', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'filter',
            [
                'label' => __( 'Filter Name', 'happy-elementor-addons' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'Type gallery filter name', 'happy-elementor-addons' ),
                'description' => __( 'Filter navigation will be built using filter name', 'happy-elementor-addons' ),
            ]
        );

        $repeater->add_control(
            'images',
            [
                'type' => Controls_Manager::GALLERY,
            ]
        );

        $this->add_control(
            'gallery',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'show_label' => false,
                'title_field' => 'Filter Group: {{filter}}',
                'default' => [
                    [
                        'filter' => __( 'Happy', 'happy-elementor-addons' ),
                    ]
                ]
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
                'label' => __( 'Settings', 'happy-elementor-addons' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_filter',
            [
                'label' => __( 'Show Filter?', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'happy-elementor-addons' ),
                'label_off' => __( 'No', 'happy-elementor-addons' ),
                'return_value' => 'yes',
                'description' => __( 'Enable to display filter navigation. Filter navigation will be built using filter name from gallery', 'happy-elementor-addons' )
            ]
        );

        $this->add_control(
            'show_all_filter',
            [
                'label' => __( 'Show Everything Filter?', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'happy-elementor-addons' ),
                'label_off' => __( 'No', 'happy-elementor-addons' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __( 'Enable to display everything filter', 'happy-elementor-addons' ),
                'condition' => [
                    'show_filter' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'all_filter_label',
            [
                'label' => __( 'Filter Label', 'happy-elementor-addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'All', 'happy-elementor-addons' ),
                'placeholder' => __( 'Type filter label', 'happy-elementor-addons' ),
                'description' => __( 'Type everything filter label', 'happy-elementor-addons' ),
                'condition' => [
                    'show_all_filter' => 'yes',
                    'show_filter' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'show_caption',
            [
                'label' => __( 'Show Caption?', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'happy-elementor-addons' ),
                'label_off' => __( 'No', 'happy-elementor-addons' ),
                'return_value' => 'yes',
                'description' => __( 'Make sure to add image caption otherwise you will not see anything', 'happy-elementor-addons' )
            ]
        );

        $this->add_control(
            'row_height',
            [
                'label' => __( 'Height', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default' => [
                    'size' => 150,
                ],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 500,
                    ],
                ],
            ]
        );

        $this->add_control(
            'margins',
            [
                'label' => __( 'Margins', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
            ]
        );

        $this->add_control(
            'last_row',
            [
                'label' => __( 'Last Row', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'justify',
                'options' => [
                    'nojustify' => __( 'No Justify', 'happy-elementor-addons' ),
                    'justify' => __( 'Justify', 'happy-elementor-addons' ),
                    'hide' => __( 'Hide', 'happy-elementor-addons' ),
                ]
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_controls() {
        $this->start_controls_section(
            '_section_style_gallery',
            [
                'label' => __( 'Gallery', 'happy-elementor-addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            '_heading_image',
            [
                'label' => __( 'Image', 'happy-elementor-addons' ),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-justified-gallery-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .ha-justified-gallery-item'
            ]
        );

        $this->add_control(
            '_heading_caption',
            [
                'label' => __( 'Caption', 'happy-elementor-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'caption_padding',
            [
                'label' => __( 'Padding', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-justified-gallery-grid > a > .caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'caption_color',
            [
                'label' => __( 'Text Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-justified-gallery-grid > a > .caption' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'caption_bg_color',
            [
                'label' => __( 'Background Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-justified-gallery-grid > a > .caption' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'caption_typography',
                'label' => __( 'Typography', 'happy-elementor-addons' ),
                'selector' => '{{WRAPPER}} .ha-justified-gallery-grid > a > .caption',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_style_menu',
            [
                'label' => __( 'Filter Menu', 'happy-elementor-addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            '_heading_menu',
            [
                'label' => __( 'Menu', 'happy-elementor-addons' ),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
            'menu_margin',
            [
                'label' => __( 'Margin', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-gallery-filter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            '_heading_buttons',
            [
                'label' => __( 'Filter Buttons', 'happy-elementor-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __( 'Padding', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-gallery-filter > li > button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_spacing',
            [
                'label' => __( 'Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ha-gallery-filter > li:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .ha-gallery-filter > li > button'
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-gallery-filter > li > button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .ha-gallery-filter > li > button'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .ha-gallery-filter > li > button',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            ]
        );

        $this->add_responsive_control(
            'button_align',
            [
                'label' => __( 'Alignment', 'happy-elementor-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'happy-elementor-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'happy-elementor-addons' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'happy-elementor-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'desktop_default' => 'left',
                'toggle' => false,
                'selectors' => [
                    '{{WRAPPER}} .ha-gallery-filter' => 'text-align: {{VALUE}};'
                ]
            ]
        );

        $this->start_controls_tabs( '_tabs_style_button' );

        $this->start_controls_tab(
            '_tab_button_normal',
            [
                'label' => __( 'Normal', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => __( 'Text Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ha-gallery-filter > li > button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => __( 'Background Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-gallery-filter > li > button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_button_hover',
            [
                'label' => __( 'Hover', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'button_hover_color',
            [
                'label' => __( 'Text Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-gallery-filter > li > button:hover, {{WRAPPER}} .ha-gallery-filter > li > button:focus, {{WRAPPER}} .ha-gallery-filter > .ha-filter-active > button:hover, {{WRAPPER}} .ha-gallery-filter > .ha-filter-active > button:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_bg_color',
            [
                'label' => __( 'Background Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-gallery-filter > li > button:hover, {{WRAPPER}} .ha-gallery-filter > li > button:focus, {{WRAPPER}} .ha-gallery-filter > .ha-filter-active > button:hover, {{WRAPPER}} .ha-gallery-filter > .ha-filter-active > button:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label' => __( 'Border Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'button_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-gallery-filter > li > button:hover, {{WRAPPER}} .ha-gallery-filter > li > button:focus, {{WRAPPER}} .ha-gallery-filter > .ha-filter-active > button:hover, {{WRAPPER}} .ha-gallery-filter > .ha-filter-active > button:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_button_active',
            [
                'label' => __( 'Active', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'button_active_color',
            [
                'label' => __( 'Text Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-gallery-filter > .ha-filter-active > button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_active_bg_color',
            [
                'label' => __( 'Background Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-gallery-filter > .ha-filter-active > button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_active_border_color',
            [
                'label' => __( 'Border Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'button_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-gallery-filter > .ha-filter-active > button' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected static function get_data_prop_settings( $settings ) {
        $field_map = [
            'show_caption' => 'captions.bool',
            'margins.size' => 'margins.int',
            'row_height.size' => 'rowHeight.int',
            'last_row' => 'lastRow.str',
        ];
        return ha_prepare_data_prop_settings( $settings, $field_map );
    }

    protected function get_gallery_data() {
        $gallery = $this->get_settings_for_display( 'gallery' );

        if ( ! is_array( $gallery ) || empty( $gallery ) ) {
            return [];
        }

        $menu = [];
        $items = [];

        foreach ( $gallery as $item ) {
            if ( empty( $item['images'] ) ) {
                continue;
            }

            $images = $item['images'];
            $filter = 'ha-filter-is--' . sanitize_title_with_dashes( $item['filter'] );

            if ( $filter && ! isset( $data[ $filter ] ) ) {
                $menu[ $filter ] = $item['filter'];
            }

            foreach ( $images as $image ) {
                if ( ! isset( $items[ $image['id'] ] ) ) {
                    $items[ $image['id'] ] = [ $filter ];
                } else {
                    array_push( $items[ $image['id'] ], $filter );
                }
            }
        }

        return [
            'menu' => $menu,
            'items' => $items
        ];
    }

	protected function render() {
        $settings = $this->get_settings_for_display();
        $gallery = $this->get_gallery_data();

        if ( empty( $gallery ) ) {
            return;
        }

        $this->add_render_attribute( 'container', 'class', [
            'ha-justified-gallery-wrapper',
            'hajs-justified-gallery',
        ] );

        $this->add_render_attribute( 'container', 'data-happy-settings', self::get_data_prop_settings( $settings ) );

        if ( $settings['show_filter'] === 'yes' ) : ?>
            <ul class="ha-gallery-filter hajs-gallery-filter">
                <?php if ( $settings['show_all_filter'] === 'yes' ) : ?>
                    <li class="ha-filter-active"><button type="button" data-filter="*"><?php echo esc_html( $settings['all_filter_label'] ); ?></button></li>
                <?php endif; ?>
                <?php foreach ( $gallery['menu'] as $key => $val ) : ?>
                    <li><button type="button" data-filter=".<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $val ); ?></button></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <div <?php echo $this->get_render_attribute_string( 'container' ); ?>>
            <?php foreach ( $gallery['items'] as $id => $filters ) :
                $caption = $settings['show_caption'] ? esc_attr( wp_get_attachment_caption( $id ) ) : '';
                ?>
                <a class="ha-justified-gallery-item <?php echo esc_attr( implode( ' ', $filters ) ); ?>">
                    <?php echo wp_get_attachment_image( $id, $settings['thumbnail_size'], false, [ 'alt' => $caption ] ); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php
    }
}
