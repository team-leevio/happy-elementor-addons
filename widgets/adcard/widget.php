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
                    'desc' => __( 'Description', 'happy_addons' ),
                    'divider' => __( 'Divider', 'happy_addons' ),
                    'image' => __( 'Image', 'happy_addons' ),
                    'title' => __( 'Title', 'happy_addons' ),
                ]
            ]
        );

        $repeater->add_control(
            'image',
            [
                'type' => Controls_Manager::MEDIA,
                'label' => __( 'Image', 'happy_addons' ),
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
                'label' => __( 'Title', 'happy_addons' ),
                'separator' => 'before',
                'condition' => [
                    'type' => 'title',
                ]
            ]
        );

        $repeater->start_controls_tabs('title_tabs');

        $repeater->start_controls_tab(
            'title_style_tab',
            [
                'label' => __( 'Style', 'happy_addons' ),
                'condition' => [
                    'type' => 'title',
                ]
            ]
        );

        $repeater->add_control(
            'title_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
//                'selectors' => [
//                    '{{WRAPPER}} .ha-label' => 'color: {{VALUE}}',
//                ],
                'condition' => [
                    'type' => 'title',
                ]
            ]
        );

        $repeater->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title_text_shadow',
                'label' => __( 'Text Shadow', 'happy_addons' ),
//                'selector' => '{{WRAPPER}} .ha-label',
                'condition' => [
                    'type' => 'title',
                ]
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __( 'Typography', 'happy_addons' ),
//                'selector' => '{{WRAPPER}} .ha-label',
                'condition' => [
                    'type' => 'title',
                ]
            ]
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab(
            'title_advanced_tab',
            [
                'label' => __( 'Advanced', 'happy_addons' ),
                'condition' => [
                    'type' => 'title',
                ]
            ]
        );

        $repeater->add_responsive_control(
            'title_margin',
            [
                'label' => __( 'Margin', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-card-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_control(
            'title_tag',
            [
                'label' => __( 'Title HTML Tag', 'happy_addons' ),
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

        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();

        $repeater->add_control(
            'desc',
            [
                'type' => Controls_Manager::TEXTAREA,
                'label' => __( 'Description', 'happy_addons' ),
                'separator' => 'before',
                'condition' => [
                    'type' => 'desc',
                ]
            ]
        );

        $repeater->start_controls_tabs('desc_tabs');

        $repeater->start_controls_tab(
            'desc_style_tab',
            [
                'label' => __( 'Style', 'happy_addons' ),
                'condition' => [
                    'type' => 'desc',
                ]
            ]
        );

        $repeater->add_control(
            'desc_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
//                'selectors' => [
//                    '{{WRAPPER}} .ha-label' => 'color: {{VALUE}}',
//                ],
                'condition' => [
                    'type' => 'desc',
                ]
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typography',
                'label' => __( 'Typography', 'happy_addons' ),
//                'selector' => '{{WRAPPER}} .ha-label',
                'condition' => [
                    'type' => 'desc',
                ]
            ]
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab(
            'desc_advanced_tab',
            [
                'label' => __( 'Advanced', 'happy_addons' ),
                'condition' => [
                    'type' => 'desc',
                ]
            ]
        );

        $repeater->add_responsive_control(
            'desc_margin',
            [
                'label' => __( 'Margin', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-card-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'type' => 'desc',
                ]
            ]
        );

        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();

        $this->add_control(
            'contents',
            [
                'label' => __( 'Contents', 'happy_addons' ),
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
