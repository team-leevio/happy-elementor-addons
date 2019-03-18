<?php
/**
 * Icon Box addon class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Addons;

use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die();

class Icon_Box extends Addon_Base {

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Happy Icon Box', 'happy_addons' );
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
        return [ 'info', 'box', 'icon' ];
    }

	protected function _register_controls() {
		$this->start_controls_section(
			'content',
			[
				'label' => __( 'Icon & Title', 'happy_addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
            'icon',
            [
                'label' => __( 'Icon', 'happy_addons' ),
                'type' => Controls_Manager::ICON,
                'default' => 'fa fa-smile-o',
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'happy_addons' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Default title', 'happy_addons' ),
                'placeholder' => __( 'Type your blurb title', 'happy_addons' ),
            ]
        );

        $this->add_control(
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

        $this->add_responsive_control(
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
                ],
                'desktop_default' => 'left',
                'toggle' => false,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'prefix_class' => 'ha-text--%s'
            ]
        );

        $this->add_control(
            'has_link',
            [
                'label' => __( 'Add Link?', 'plugin-domain' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'your-plugin' ),
                'label_off' => __( 'No', 'your-plugin' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __( 'Link', 'happy_addons' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://example.com/', 'happy_addons' ),
                'condition' => [
                    'has_link' => 'yes',
                ],
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'Icon',
            [
                'label' => __( 'Icon', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __( 'Size', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 250,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-icon-box-icon > i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => __( 'Padding', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-icon-box-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'selector' => '{{WRAPPER}} .ha-icon-box-icon'
            ]
        );

        $this->add_responsive_control(
            'icon_border_radius',
            [
                'label' => __( 'Border Radius', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-icon-box-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .ha-icon-box-icon'
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __( 'Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-icon-box-icon' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_color',
            [
                'label' => __( 'Background Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-icon-box-icon' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'content_style',
            [
                'label' => __( 'Title', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => __( 'Margin', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-icon-box-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-icon-box-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __( 'Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-icon-box-title',
            ]
        );

        $this->end_controls_section();
    }

	protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_inline_editing_attributes( 'title', 'none' );
        $this->add_render_attribute( 'title', 'class', 'ha-icon-box-title' );

        if ( $settings['has_link'] === 'yes' ) :
            $this->add_render_attribute( 'link', 'class', 'ha-icon-box-link' );
            $this->add_render_attribute( 'link', 'href', esc_url( $settings['link']['url'] ) );
            if ( ! empty( $settings['link']['is_external'] ) ) {
                $this->add_render_attribute( 'link', 'target', '_blank' );
            }
            if ( ! empty( $settings['link']['nofollow'] ) ) {
                $this->set_render_attribute( 'link', 'rel', 'nofollow' );
            }
            printf( '<a %1$s>', $this->get_render_attribute_string( 'link' ) );
        endif;
        ?>
        <span class="ha-icon-box-icon">
            <i aria-hidden="true" class="<?php echo esc_attr( $settings['icon'] ); ?>"></i>
        </span>
        <?php printf( '<%1$s %2$s>%3$s</%1$s>',
            tag_escape( $settings['title_tag'] ),
            $this->get_render_attribute_string( 'title' ),
            esc_html( $settings['title' ] )
        );
        if ( $settings['has_link'] === 'yes' ) :
            echo '</a>';
        endif;
    }

    public function _content_template() {
        ?>
        <#
        view.addInlineEditingAttributes( 'title', 'none' );
        view.addRenderAttribute( 'title', 'class', 'ha-icon-box-title' );

        if ( settings.has_link === 'yes' ) {
            view.addRenderAttribute( 'link', 'class', 'ha-icon-box-link' );
            view.addRenderAttribute( 'link', 'href', settings.link.url );

            print( '<a ' + view.getRenderAttributeString( 'link' ) + '>' );
        } #>
        <span class="ha-icon-box-icon">
            <i class="{{ settings.icon }}"></i>
        </span>
        <{{ settings.title_tag }} {{{ view.getRenderAttributeString( 'title' ) }}}>{{ settings.title }}</{{ settings.title_tag }}>
        <#
        if ( settings.has_link === 'yes' ) {
            print( '</a>' );
        } #>
        <?php
    }
}
