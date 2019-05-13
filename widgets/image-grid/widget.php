<?php
/**
 * Image grid widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Repeater;
use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class Image_Grid extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Happy Image Grid', 'happy_addons' );
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
        return 'hm hm-grid-even';
    }

    public function get_keywords() {
        return [ 'gallery', 'image', 'masonry', 'even' ];
    }

	protected function register_content_controls() {
        $this->start_controls_section(
            '_section_gallery',
            [
                'label' => __( 'Gallery', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'filter',
            [
                'label' => __( 'Filter Name', 'happy_addons' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'Type gallery filter name', 'happy_addons' ),
                'description' => __( 'Filter menu will be built using filter name', 'happy_addons' ),
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
                'title_field' => 'Filter:: {{ filter }}',
                'default' => [
                    [
                        'filter' => __( 'Happy', 'happy_addons' ),
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
                'label' => __( 'Settings', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'enable_popup',
            [
                'label' => __( 'Enable Popup?', 'happy_addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'happy_addons' ),
                'label_off' => __( 'No', 'happy_addons' ),
                'return_value' => 'yes',
                'description' => __( 'Enable popup to view the gallery images in a popup window', 'happy_addons' )
            ]
        );

        $this->add_control(
            'show_filter',
            [
                'label' => __( 'Show Filter?', 'happy_addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'happy_addons' ),
                'label_off' => __( 'No', 'happy_addons' ),
                'return_value' => 'yes',
                'description' => __( 'Enable to display filter menu. Filter menu will be built using filter name from gallery', 'happy_addons' )
            ]
        );

        $this->add_control(
            'show_all_filter',
            [
                'label' => __( 'Show Everything Filter?', 'happy_addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'happy_addons' ),
                'label_off' => __( 'No', 'happy_addons' ),
                'return_value' => 'yes',
                'default' => true,
                'description' => __( 'Enable to display everything filter', 'happy_addons' ),
                'condition' => [
                    'show_filter' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'all_filter_label',
            [
                'label' => __( 'Filter Label', 'happy_addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'All', 'happy_addons' ),
                'placeholder' => __( 'Type filter label', 'happy_addons' ),
                'description' => __( 'Type everything filter label', 'happy_addons' ),
                'condition' => [
                    'show_all_filter' => 'yes',
                    'show_filter' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'show_caption',
            [
                'label' => __( 'Show Caption?', 'happy_addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'happy_addons' ),
                'label_off' => __( 'No', 'happy_addons' ),
                'return_value' => 'yes',
                'description' => __( 'Enable to display overly image caption', 'happy_addons' )
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => __( 'Columns', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default' => [
                    'size' => 3,
                ],
                'range' => [
                    'px' => [
                        'min' => 2,
                        'max' => 5,
                    ],
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_controls() {
        $this->start_controls_section(
            '_section_style_gallery',
            [
                'label' => __( 'Gallery', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( '_tab_style_gallery' );
        $this->start_controls_tab(
            '_tab_style_image',
            [
                'label' => __( 'Image', 'happy_addons' ),
            ]
        );
        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', 'happy_addons' ),
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
        $this->end_controls_tab();
        $this->start_controls_tab(
            '_tab_style_caption',
            [
                'label' => __( 'Caption', 'happy_addons' ),
            ]
        );
        $this->add_responsive_control(
            'caption_padding',
            [
                'label' => __( 'Padding', 'happy_addons' ),
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
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-justified-gallery-grid > a > .caption' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'caption_bg_color',
            [
                'label' => __( 'Background Color', 'happy_addons' ),
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
                'label' => __( 'Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-justified-gallery-grid > a > .caption',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_style_menu',
            [
                'label' => __( 'Filter Menu', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'menu_margin',
            [
                'label' => __( 'Menu Margin', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-gallery-filter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_align',
            [
                'label' => __( 'Button Align', 'happy_addons' ),
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
            ]
        );

        $this->add_responsive_control(
            'button_margin',
            [
                'label' => __( 'Button Margin', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-gallery-filter > li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __( 'Button Padding', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-gallery-filter > li > button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'label' => __( 'Border Radius', 'happy_addons' ),
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
                'label' => __( 'Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-gallery-filter > li > button',
            ]
        );

        $this->start_controls_tabs( '_tab_style_button' );

        $this->start_controls_tab(
            '_tab_button_normal',
            [
                'label' => __( 'Normal', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
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
                'label' => __( 'Background Color', 'happy_addons' ),
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
                'label' => __( 'Hover', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'button_hover_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-gallery-filter > li > button:hover, {{WRAPPER}} .ha-gallery-filter > li > button:focus, {{WRAPPER}} .ha-gallery-filter > .ha-filter-active > button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_bg_color',
            [
                'label' => __( 'Background Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-gallery-filter > li > button:hover, {{WRAPPER}} .ha-gallery-filter > li > button:focus, {{WRAPPER}} .ha-gallery-filter > .ha-filter-active > button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label' => __( 'Border Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'button_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-gallery-filter > li > button:hover, {{WRAPPER}} .ha-gallery-filter > li > button:focus, {{WRAPPER}} .ha-gallery-filter > .ha-filter-active > button' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected static function render_grid_item( &$settings, $image_id, $filter_class ) {
        $image_url = wp_get_attachment_image_url( $image_id, $settings['thumbnail_size'] );
        $caption = $popup_link = '';
        if ( $settings['show_caption'] === 'yes' ) {
            $caption = wp_get_attachment_caption( $image_id );
        }

        if ( $settings['enable_popup'] === 'yes' ) {
            $popup_link = 'href="' . esc_url( wp_get_attachment_image_url( $image_id, 'large' ) ) . '"';
        }
        ?>
        <div class="ha-image-grid-item <?php echo esc_attr( $filter_class ); ?>">
            <a class="ha-image-grid-link" <?php echo $popup_link; ?>>
                <img src="<?php echo esc_url( $image_url ); ?>" alt="">
                <?php if ( $caption ): ?>
                    <div class="ha-image-grid-overlay">
                        <div class="ha-image-grid-content"><?php echo esc_html( $caption ); ?></div>
                    </div>
                <?php endif; ?>
            </a>
        </div>
        <?php
    }

	protected function render() {
        $settings = $this->get_settings_for_display();

        if ( is_array( $settings['gallery'] ) ) :
            $items = [];
            $menu = [];

            foreach ( $settings['gallery'] as $gallery_item ) :
                if ( empty( $gallery_item['images'] ) ) :
                    continue;
                endif;

                $images = $gallery_item['images'];

                if ( $settings['show_filter'] === 'yes' && $gallery_item['filter'] ) {
                    $filter = sanitize_title_with_dashes( $gallery_item['filter'] );
                    if ( ! isset( $menu[$filter] ) ) {
                        $menu[$filter] = sprintf(
                            '<li><button type="button" data-filter=".%s">%s</button></li>',
                            esc_attr( $filter ),
                            esc_html( $gallery_item['filter'] )
                        );
                    }
                }

                foreach ( $images as $image ) :
                    if ( ! isset( $items[ $image['id'] ] ) ) {
                        $items[ $image['id'] ] = [$filter];
                    } else {
                        array_push( $items[ $image['id'] ], $filter );
                    }
                endforeach;
            endforeach;

            $this->add_render_attribute( 'container', 'class', [
                'hajs-image-grid',
                'ha-image-grid-wrapper',
                'ha-image-grid--col-' . $settings['columns']['size'],
            ] );

            if ( $settings['show_filter'] === 'yes' ) :
                echo '<ul class="ha-gallery-filter hajs-gallery-filter ha-text--' . $settings['button_align'] . '">';
                    echo $settings['show_all_filter'] === 'yes' ? '<li class="ha-filter-active"><button type="button" data-filter="*">' . $settings['all_filter_label'] . '</button></li>' : '';
                    echo implode( "\n", $menu );
                echo '</ul>';
            endif;

            echo '<div ' . $this->get_render_attribute_string( 'container' ) . '>';
                foreach ( $items as $item_id => $item_filter ) :
                    self::render_grid_item( $settings, $item_id, implode( ' ', $item_filter ) );
                endforeach;
            echo '</div>';
        endif;
    }

}
