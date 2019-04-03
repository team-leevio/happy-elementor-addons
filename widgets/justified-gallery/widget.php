<?php
/**
 * Justified gallery widget class
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
        return __( 'Happy Justified Gallery', 'happy_addons' );
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
        return [ 'gallery', 'image', 'justified', 'filter' ];
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
                'description' => __( 'Enable to display image caption', 'happy_addons' )
            ]
        );

        $this->add_control(
            'row_height',
            [
                'label' => __( 'Row Height', 'happy_addons' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 150,
                'min' => 50,
                'max' => 300,
                'step' => 1,
            ]
        );

        $this->add_control(
            'margins',
            [
                'label' => __( 'Margins', 'happy_addons' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 10,
                'min' => 0,
                'max' => 50,
                'step' => 1,
            ]
        );

        $this->add_control(
            'last_row',
            [
                'label' => __( 'Last Row', 'happy_addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'justify',
                'options' => [
                    'nojustify' => __( 'No Justify', 'happy_addons' ),
                    'justify' => __( 'Justify', 'happy_addons' ),
                    'hide' => __( 'Hide', 'happy_addons' ),
                ]
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_controls() {

    }

    protected static function get_data_prop_settings( $settings ) {
        $field_map = [
            'display_caption' => 'captions.bool',
            'margins' => 'margins.int',
            'row_height' => 'rowHeight.int',
            'last_row' => 'lastRow.str',
        ];

        return ha_prepare_data_prop_settings( $settings, $field_map );
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

                foreach ( $images as $image ) :
                    $image_url = wp_get_attachment_image_url( $image['id'], $settings['thumbnail_size'] );
                    if ( ! isset( $items[ $image['id'] ] ) ) :
                        $items[ $image['id'] ] = sprintf(
                            '<a class="ha-justified-gallery-item %1$s" %2$s %3$s><img src="%4$s" alt=""></a>',
                            $filter_class,
                            $settings['enable_popup'] === 'yes' ? 'href="' . esc_url( wp_get_attachment_image_url( $image['id'], 'large' ) ) . '"' : '',
                            $settings['display_caption'] === 'yes' ? 'title="' . esc_attr( wp_get_attachment_caption( $image['id'] ) ) . '"' : '',
                            esc_url( $image_url )
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
                'ha-justified-gallery-grid',
                'hajs-justified-gallery',
            ] );
            $this->add_render_attribute( 'container', 'data-happy-settings', self::get_data_prop_settings( $settings ) );

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
