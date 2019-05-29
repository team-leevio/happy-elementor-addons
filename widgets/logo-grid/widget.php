<?php
/**
 * Logo grid widget class
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

class Logo_Grid extends Base
{

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('Logo Grid', 'happy_addons');
    }

    /**
     * Get widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'hm hm-logo-grid';
    }

    public function get_keywords()
    {
        return ['logo', 'grid', 'brand', 'client'];
    }

    protected function register_content_controls() {
        $this->start_controls_section(
            '_section_logo',
            [
                'label' => __( 'Logo Grid', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'label' => __('Logo', 'happy_addons'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => __('Website Url', 'happy_addons'),
                'type' => Controls_Manager::URL,
                'show_external' => false,
                'label_block' => false,
            ]
        );

        $repeater->add_control(
            'name',
            [
                'label' => __('Brand Name', 'happy_addons'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Brand Name', 'happy_addons'),
            ]
        );

        $this->add_control(
            'logo_list',
            [
                'show_label' => false,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ name }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_settings',
            [
                'label' => __( 'Settings', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'large',
                'separator' => 'before',
                'exclude' => [
                    'custom'
                ]
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => __( 'Grid Layout', 'happy_addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'box' => __( 'Box', 'happy_addons' ),
                    'border' => __( 'Border', 'happy_addons' ),
                    'tictactoe' => __( 'Tic Tac Toe', 'happy_addons' ),
                ],
                'default' => 'box',
                'prefix_class' => 'ha-logo-grid--'
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => __( 'Columns', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
                    'size' => 3,
                ],
                'range' => [
                    'px' => [
                        'min' => 2,
                        'max' => 6,
                    ],
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_controls() {
        $this->start_controls_section(
            '_section_style_grid',
            [
                'label' => __( 'Grid', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'grid_border_type',
            [
                'label' => __( 'Border Type', 'happy_addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => __( 'None', 'happy_addons' ),
                    'solid' => __( 'Solid', 'happy_addons' ),
                    'double' => __( 'Double', 'happy_addons' ),
                    'dotted' => __( 'Dotted', 'happy_addons' ),
                    'dashed' => __( 'Dashed', 'happy_addons' ),
                    'groove' => __( 'Groove', 'happy_addons' ),
                ],
                'default' => 'solid',
                'selectors' => [
                    '{{WRAPPER}} .ha-logo-grid-item' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'grid_border_width',
            [
                'label' => __( 'Border Width', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}}.ha-logo-grid--border .ha-logo-grid-item' => 'border-right-width: {{SIZE}}{{UNIT}}; border-bottom-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--border .ha-logo-grid--col-2 > .ha-logo-grid-item:nth-child(2n+1)' => 'border-left-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--border .ha-logo-grid--col-3 > .ha-logo-grid-item:nth-child(3n+1)' => 'border-left-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--border .ha-logo-grid--col-4 > .ha-logo-grid-item:nth-child(4n+1)' => 'border-left-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--border .ha-logo-grid--col-5 > .ha-logo-grid-item:nth-child(5n+1)' => 'border-left-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--border .ha-logo-grid--col-6 > .ha-logo-grid-item:nth-child(6n+1)' => 'border-left-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--border .ha-logo-grid--col-2 > .ha-logo-grid-item:nth-child(-n+2)' => 'border-top-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--border .ha-logo-grid--col-3 > .ha-logo-grid-item:nth-child(-n+3)' => 'border-top-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--border .ha-logo-grid--col-4 > .ha-logo-grid-item:nth-child(-n+4)' => 'border-top-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--border .ha-logo-grid--col-5 > .ha-logo-grid-item:nth-child(-n+5)' => 'border-top-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--border .ha-logo-grid--col-6 > .ha-logo-grid-item:nth-child(-n+6)' => 'border-top-width: {{SIZE}}{{UNIT}};',

                    '{{WRAPPER}}.ha-logo-grid--tictactoe .ha-logo-grid-item' => 'border-top-width: {{SIZE}}{{UNIT}}; border-right-width: {{SIZE}}{{UNIT}};',

                    '{{WRAPPER}}.ha-logo-grid--box .ha-logo-grid-item' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'grid_border_type!' => 'none',
                ]
            ]
        );

        $this->add_control(
            'grid_border_color',
            [
                'label' => __( 'Border Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-logo-grid-item' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'grid_border_type!' => 'none',
                ]
            ]
        );

        $this->add_control(
            'grid_bg_color',
            [
                'label' => __( 'Background Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-logo-grid-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'grid_border_radius',
            [
                'label' => __( 'Border Radius', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}}.ha-logo-grid--border .ha-logo-grid-wrapper, {{WRAPPER}}.ha-logo-grid--box .ha-logo-grid-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--border .ha-logo-grid-item:first-child' => 'border-top-left-radius: {{TOP}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--border .ha-logo-grid-item:last-child' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--border .ha-logo-grid--col-2 > .ha-logo-grid-item:nth-child(2)' => 'border-top-right-radius: {{RIGHT}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--border .ha-logo-grid--col-2 > .ha-logo-grid-item:nth-last-child(2)' => 'border-bottom-left-radius: {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--border .ha-logo-grid--col-3 > .ha-logo-grid-item:nth-child(3)' => 'border-top-right-radius: {{RIGHT}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--border .ha-logo-grid--col-3 > .ha-logo-grid-item:nth-last-child(3)' => 'border-bottom-left-radius: {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--border .ha-logo-grid--col-4 > .ha-logo-grid-item:nth-child(4)' => 'border-top-right-radius: {{RIGHT}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--border .ha-logo-grid--col-4 > .ha-logo-grid-item:nth-last-child(4)' => 'border-bottom-left-radius: {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--border .ha-logo-grid--col-5 > .ha-logo-grid-item:nth-child(5)' => 'border-top-right-radius: {{RIGHT}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--border .ha-logo-grid--col-5 > .ha-logo-grid-item:nth-last-child(5)' => 'border-bottom-left-radius: {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--border .ha-logo-grid--col-6 > .ha-logo-grid-item:nth-child(6)' => 'border-top-right-radius: {{RIGHT}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--border .ha-logo-grid--col-6 > .ha-logo-grid-item:nth-last-child(6)' => 'border-bottom-left-radius: {{LEFT}}{{UNIT}};',

                    '{{WRAPPER}}.ha-logo-grid--tictactoe .ha-logo-grid-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--tictactoe .ha-logo-grid-item:first-child' => 'border-top-left-radius: {{TOP}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--tictactoe .ha-logo-grid-item:last-child' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--tictactoe .ha-logo-grid--col-2 > .ha-logo-grid-item:nth-child(2)' => 'border-top-right-radius: {{RIGHT}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--tictactoe .ha-logo-grid--col-2 > .ha-logo-grid-item:nth-last-child(2)' => 'border-bottom-left-radius: {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--tictactoe .ha-logo-grid--col-3 > .ha-logo-grid-item:nth-child(3)' => 'border-top-right-radius: {{RIGHT}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--tictactoe .ha-logo-grid--col-3 > .ha-logo-grid-item:nth-last-child(3)' => 'border-bottom-left-radius: {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--tictactoe .ha-logo-grid--col-4 > .ha-logo-grid-item:nth-child(4)' => 'border-top-right-radius: {{RIGHT}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--tictactoe .ha-logo-grid--col-4 > .ha-logo-grid-item:nth-last-child(4)' => 'border-bottom-left-radius: {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--tictactoe .ha-logo-grid--col-5 > .ha-logo-grid-item:nth-child(5)' => 'border-top-right-radius: {{RIGHT}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--tictactoe .ha-logo-grid--col-5 > .ha-logo-grid-item:nth-last-child(5)' => 'border-bottom-left-radius: {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--tictactoe .ha-logo-grid--col-6 > .ha-logo-grid-item:nth-child(6)' => 'border-top-right-radius: {{RIGHT}}{{UNIT}};',
                    '{{WRAPPER}}.ha-logo-grid--tictactoe .ha-logo-grid--col-6 > .ha-logo-grid-item:nth-last-child(6)' => 'border-bottom-left-radius: {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'grid_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}}.ha-logo-grid--tictactoe .ha-logo-grid-wrapper, {{WRAPPER}}.ha-logo-grid--border .ha-logo-grid-wrapper, {{WRAPPER}}.ha-logo-grid--box .ha-logo-grid-item'
            ]
        );

        $this->end_controls_tab();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty($settings['logo_list'] ) ) {
            return;
        }

        $this->add_render_attribute( 'container', 'class', [
            'ha-logo-grid-wrapper',
            'ha-logo-grid--col-' . $settings['columns']['size'],
        ] );

        ?>

        <div <?php echo $this->get_render_attribute_string( 'container' ); ?>>
            <?php
            foreach ( $settings['logo_list'] as $index => $item ) :
                $image = wp_get_attachment_image_url( $item['image']['id'], $settings['thumbnail_size'] );
                if ( ! $image ) {
                    $image = Utils::get_placeholder_image_src();
                }
                $repeater_key = 'grid_item' . $index;
                $tag = 'div';
                $this->add_render_attribute( $repeater_key, 'class', 'ha-logo-grid-item' );
                if ( $item['link']['url'] ) {
                    $tag = 'a';
                    $this->add_render_attribute( $repeater_key, 'class', 'ha-logo-grid-link' );
                    $this->add_render_attribute( $repeater_key, 'target', '_blank' );
                    $this->add_render_attribute( $repeater_key, 'rel', 'noopener' );
                    $this->add_render_attribute( $repeater_key, 'href', esc_url( $item['link']['url'] ) );
                }
                ?>
                <<?php echo $tag; ?> <?php echo $this->get_render_attribute_string( $repeater_key ); ?>>
                    <img class="ha-logo-grid-img" src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr( $item['name'] ); ?>">
                </<?php echo $tag; ?>>
            <?php endforeach; ?>
        </div>

        <?php
    }

    /*
    public function _content_template() {
        ?>

        <div class="ha-logo ha-logo-grid-{{{ settings.columns.size }}}">
            <#
            _.each( settings.logo_list, function( list, index ) {
                var image = {
                    id: list.logo_image.id,
                    url: list.logo_image.url,
                    size: settings.thumbnail,
                    dimension: list.thumbnail_custom_dimension,
                    model: view.getEditModel()
                };
                var image_url = elementor.imagesManager.getImageUrl( image );
                #>
                <div class="ha-logo-item">
                    <div class="ha-logo-item-thumb">
                        <# if( list.logo_link ) { #>
                            <a href="{{{ list.logo_link }}}">
                                <img src="{{{ image_url }}}" alt="{{{ list.logo_name }}}">
                            </a>
                        <# } else { #>
                            <img src="{{{ image_url }}}" alt="{{{ list.logo_name }}}">
                        <# } #>
                    </div>
                </div>
            <#
            });
            #>
        </div>
    <?php
    }
    */
}
