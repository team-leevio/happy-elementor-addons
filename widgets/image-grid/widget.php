<?php
/**
 * Image gallery widget class
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
        return 'fa fa-smile-o';
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
                'description' => __( 'Filter navigation will be built using filter name', 'happy_addons' ),
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
                'title_field' => '{{ filter }}',
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
            'display_filter',
            [
                'label' => __( 'Display Filter?', 'happy_addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'happy_addons' ),
                'label_off' => __( 'No', 'happy_addons' ),
                'return_value' => 'yes',
                'description' => __( 'Enable to display filter navigation. Filter navigation will be built using filter name from gallery', 'happy_addons' )
            ]
        );

        $this->add_control(
            'display_caption',
            [
                'label' => __( 'Display Caption?', 'happy_addons' ),
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
                'type' => Controls_Manager::SELECT,
                'default' => 3,
                'options' => [
                    2 => __( '2 Columns', 'happy_addons' ),
                    3 => __( '3 Columns', 'happy_addons' ),
                    4 => __( '4 Columns', 'happy_addons' ),
                    5 => __( '5 Columns', 'happy_addons' ),
                ]
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_controls() {

    }

	protected function render() {
        $settings = $this->get_settings_for_display();

        if ( ! empty( $settings['gallery'] ) ) :
            $items = [];
            $navigation = [];

            foreach ( $settings['gallery'] as $gallery_item ) :
                if ( empty( $gallery_item['images'] ) ) :
                    continue;
                endif;

                $images = $gallery_item['images'];
                $filter_class = '';

                if ( $settings['display_filter'] === 'yes' && ! empty( $gallery_item['filter'] ) ) {
                    $filter_class = sanitize_title_with_dashes( $gallery_item['filter'] );
                    $navigation[] = sprintf(
                        '<li><button type="button" data-filter="%1$s">%2$s</button></li>',
                        esc_attr( $filter_class ),
                        esc_html( $gallery_item['filter'] )
                        );

                    $filter_class .= ' hajs-filterable-item';
                }

                $template = '<div class="ha-image-grid-item %1$s">'
                    . '<a class="ha-image-grid-link" %2%s %3$s>'
                    . '<img src="%4$s" alt="">'
                    . '<div class="ha-image-grid-overlay">'
                        . '<div class="ha-image-grid-overlay-content">%5$s</div>'
                    . '</div>'
                    . '</a>'
                    . '</div>';

                foreach ( $images as $image ) :
                    $image_url = wp_get_attachment_image_url( $image['id'], $settings['thumbnail_size'] );
                    if ( ! isset( $items[ $image['id'] ] ) ) :
                        $caption = '';
                        if ( $settings['display_caption'] === 'yes' ) {
                            $caption = wp_get_attachment_caption( $image['id'] );
                        }
                        $items[ $image['id'] ] = sprintf( $template,
                            $filter_class,
                            $settings['enable_popup'] === 'yes' ? 'href="' . esc_url( wp_get_attachment_image_url( $image['id'], 'large' ) ) . '"' : '',
                            $caption ? 'title="' . esc_attr( $caption ) . '"' : '',
                            esc_url( $image_url ),
                            $caption ? esc_html( $caption ) : ''
                        );
                    else :
                        $items[ $image['id'] ] = str_replace(
                            'hajs-filterable-item',
                            $filter_class,
                            $items[ $image['id'] ]
                        );
                    endif;
                endforeach;
            endforeach;

            $this->add_render_attribute( 'container', 'class', [
                'hajs-image-grid',
                'ha-image-grid-wrapper',
                'ha-image-grid--' . $settings['columns'],
            ] );

            if ( $settings['display_filter'] === 'yes' ) :
                echo '<ul class="ha-gallery-filter hajs-gallery-filter">';
                    echo implode( "\n", $navigation );
                echo '</ul>';
            endif;

            echo '<div ' . $this->get_render_attribute_string( 'container' ) . '>';
                echo implode( "\n", $items );
            echo '</div>';
        endif;
    }

}
