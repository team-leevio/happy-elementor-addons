<?php
/**
 * AdCard widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Group_Control_Text_Shadow;
use Elementor\Repeater;
use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class AdCard extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Happy AdCard', 'happy_addons' );
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
        return [ 'ad', 'promo', 'advertisement', 'card', 'promotion' ];
    }

	protected function register_content_controls() {
        $this->start_controls_section(
            '_section_content',
            [
                'label' => __( 'Ad Content', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'type',
            [
                'type' => Controls_Manager::SELECT,
                'label' => __( 'Type', 'happy_addons' ),
                'default' => 'title',
                'options' => [
                    'button' => __( 'Button', 'happy_addons' ),
                    'divider' => __( 'Divider', 'happy_addons' ),
                    'image' => __( 'Image', 'happy_addons' ),
                    'title' => __( 'Title', 'happy_addons' ),
                    'text' => __( 'Text', 'happy_addons' ),
                ]
            ]
        );

        $repeater->add_control(
            'image',
            [
                'type' => Controls_Manager::MEDIA,
                'show_label' => false,
                'separator' => 'before',
                'condition' => [
                    'type' => 'image',
                ]
            ]
        );

        $repeater->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'medium',
                'condition' => [
                    'type' => 'image',
                ]
            ]
        );

        $repeater->add_control(
            'title',
            [
                'type' => Controls_Manager::TEXT,
                'show_label' => false,
                'label_block' => true,
                'separator' => 'before',
                'placeholder' => __( 'Type title text', 'happy_addons' ),
                'condition' => [
                    'type' => 'title',
                ]
            ]
        );

        $repeater->add_control(
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
                'condition' => [
                    'type' => 'title',
                ]
            ]
        );

        $repeater->add_control(
            'text',
            [
                'type' => Controls_Manager::TEXTAREA,
                'show_label' => false,
                'separator' => 'before',
                'placeholder' => __( 'Type text content', 'happy_addons' ),
                'condition' => [
                    'type' => 'text',
                ]
            ]
        );

        $repeater->add_responsive_control(
            'width',
            [
                'label' => __( 'Width', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
//                    '{{WRAPPER}} .ha-blurb-figure--image' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'type' => ['image', 'divider']
                ]
            ]
        );

        $repeater->add_responsive_control(
            'height',
            [
                'label' => __( 'Height', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
//                    '{{WRAPPER}} .ha-blurb-figure--image' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'type' => ['image', 'divider']
                ]
            ]
        );

        /* =======================
         * Styles goes here
         * ======================= */

        $repeater->add_responsive_control(
            'margin',
            [
                'label' => __( 'Margin', 'happy_addons' ),
                'separator' => 'before',
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
//                'selectors' => [
//                    '{{WRAPPER}} .ha-card-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
//                ],
            ]
        );

        $repeater->add_responsive_control(
            'padding',
            [
                'label' => __( 'Padding', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
//                'selectors' => [
//                    '{{WRAPPER}} .ha-card-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
//                ],
            ]
        );

        $repeater->add_responsive_control(
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
                    'justify' => [
                        'title' => __( 'Justify', 'happy_addons' ),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}}'
                ]
            ]
        );

        $repeater->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'type' => ['title','text'],
                ]
            ]
        );

        $repeater->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'label' => __( 'Text Shadow', 'happy_addons' ),
                'condition' => [
                    'type' => ['title', 'text'],
                ]
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'label' => __( 'Typography', 'happy_addons' ),
                'condition' => [
                    'type!' => ['divider', 'image']
                ]
            ]
        );

        $this->add_control(
            'contents',
            [
                'show_label' => false,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => 'Type: <b><# print(type.slice(0,1).toUpperCase() + type.slice(1)) #></b>'
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_controls() {

    }

	protected function render() {
        /*
        $settings = $this->get_settings_for_display();
        if ( empty( $settings['slides'] ) ) {
            return;
        }

        $this->add_render_attribute( 'container', 'class', 'owl-carousel owl-theme hajs-slider ha-slider--regular-nav' );
        $this->add_render_attribute( 'container', 'data-happy-settings', self::get_data_prop_settings( $settings ) );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'container' ); ?>>
            <?php foreach ( $settings['slides'] as $slide ) :
                $image = wp_get_attachment_image_url( $slide['image']['id'], $settings['thumbnail_size'] );
                if ( ! $image ) {
                    continue;
                }
                ?>
                <div class="item">
                    <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr( wp_get_attachment_caption( $slide['image']['id'] ) ); ?>">
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        */
    }
}
