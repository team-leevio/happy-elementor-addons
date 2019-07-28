<?php
/**
 * Step Flow widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

defined( 'ABSPATH' ) || die();

class Step_flow extends Base {
    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Step Flow', 'happy-elementor-addons' );
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
        return 'hm hm-list';
    }

    public function get_keywords() {
        return [ 'step', 'flow' ];
    }

    protected function register_content_controls() {
        $this->start_controls_section(
            '_section_step',
            [
                'label' => __( 'Step Flow', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => __( 'Icon', 'happy-elementor-addons' ),
                'type' => Controls_Manager::ICON,
                'default' => 'fa fa-home',
                'options' => ha_get_happy_icons(),
            ]
        );

        $this->add_control(
            'badge',
            [
                'label' => __( 'Badge', 'happy-elementor-addons' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'Badge', 'happy-elementor-addons' ),
                'description' => __( 'Keep it blank, if you want to remove the Badge', 'happy-elementor-addons' ),
                'default' => __( '01', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'happy-elementor-addons' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => __( 'Title', 'happy-elementor-addons' ),
                'default' => __( 'Start Marketting', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __( 'Link', 'happy-elementor-addons' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://example.com', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'detail',
            [
                'label' => __( 'Description', 'happy-elementor-addons' ),
                'type' => Controls_Manager::TEXTAREA,
                'placeholder' => __( 'Description', 'happy-elementor-addons' ),
                'default' => __( 'consectetur adipiscing elit, sed do eiusmodLorem ipsum dolor sit amet consectetur.', 'happy-elementor-addons' ),
            ]
        );

        $this->add_control(
            'show_indicator',
            [
                'label' => __( 'Hide Indicator', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'happy-elementor-addons' ),
                'label_off' => __( 'Hide', 'happy-elementor-addons' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'separator' => 'before'
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
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'indicator_border',
                'selector' => '{{WRAPPER}} .ha-step-arrow',
                'condition' => [
                    'show_indicator' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'border_resize',
            [
                'label' => __( 'Resize Indicator', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ]
                ],
                'condition' => [
                    'show_indicator' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-step-arrow' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_icon_style',
            [
                'label' => __( 'Icon', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __( 'Size', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-steps-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => __( 'Padding', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-steps-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_spacing',
            [
                'label' => __( 'Bottom Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-steps-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'label' => __( 'Border', 'happy-elementor-addons' ),
                'selector' => '{{WRAPPER}} .ha-steps-icon',
            ]
        );

        $this->add_responsive_control(
            'icon_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-steps-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-steps-icon i' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_background_color',
            [
                'label' => __( 'Background Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-steps-icon' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_badge_style',
            [
                'label' => __('Badge', 'happy-elementor-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'badge_padding',
            [
                'label' => __( 'Padding', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-steps-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'badge_border',
                'label' => __( 'Border', 'happy-elementor-addons' ),
                'selector' => '{{WRAPPER}} .ha-steps-label',
            ]
        );

        $this->add_responsive_control(
            'badge_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-elementor-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-steps-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'badge_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-steps-label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'badge_background_color',
            [
                'label' => __( 'Background Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-steps-label' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'badge_typography',
                'selector' => '{{WRAPPER}} .ha-steps-label',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_title_style',
            [
                'label' => __( 'Title', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'detail_spacing',
            [
                'label' => __( 'Top Spacing', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-steps-title h4' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-steps-title h4' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'title_link_color',
            [
                'label' => __( 'Link Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-steps-title h4 a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => __( 'Hover Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-steps-title h4 a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .ha-steps-title h4',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_description_style',
            [
                'label' => __( 'Description', 'happy-elementor-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'detail_typography',
                'selector' => '{{WRAPPER}} .ha-step-detail',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
            ]
        );

        $this->add_control(
            'detail_color',
            [
                'label' => __( 'Color', 'happy-elementor-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-step-detail' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'detail', 'class', 'ha-step-detail' );
        $this->add_inline_editing_attributes( 'detail', 'basic' );

        $this->add_render_attribute( 'link', 'href', esc_url( $settings['link']['url'] ) );
        if ( ! empty( $settings['link']['is_external'] ) ) {
            $this->add_render_attribute( 'link', 'target', '_blank' );
        }
        if ( ! empty( $settings['link']['nofollow'] ) ) {
            $this->set_render_attribute( 'link', 'rel', 'nofollow' );
        }
        ?>

        <?php if ( $settings['show_indicator'] === 'yes' ) : ?>
            <div class="ha-step-arrow"></div>
        <?php endif; ?>

        <div class="ha-steps-icon">
            <i class="<?php echo esc_attr( $settings['icon'] ); ?>"></i>

            <?php if ( $settings['badge'] ) : ?>
                <div class="ha-steps-label"><?php echo esc_html( $settings['badge'] ); ?></div>
            <?php endif; ?>

        </div>

        <div class="ha-steps-title">
            <?php if ( !empty( $settings['link']['url'] ) ) : ?>
                <h4>
                    <a <?php echo $this->get_render_attribute_string( 'link' ); ?>>
                        <?php echo esc_html( $settings['title'] ); ?>
                    </a>
                </h4>
            <?php else : ?>
                <h4><?php echo esc_html( $settings['title'] ); ?></h4>
            <?php endif; ?>
        </div>

        <?php if ( $settings['detail'] ) : ?>
            <p <?php echo $this->get_render_attribute_string( 'detail' ); ?>>
                <?php echo esc_html( $settings['detail'] ); ?>
            </p>
        <?php endif; ?>

        <?php
    }

   

}