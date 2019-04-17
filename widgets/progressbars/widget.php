<?php
/**
 * Progressbar widget class
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

class ProgressBars extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Happy Progress Bars', 'happy_addons' );
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
        return [ 'progressbar', 'skillbar' ];
    }

	protected function register_content_controls() {
        $this->start_controls_section(
            '_section_bars',
            [
                'label' => __( 'Bars', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'view',
            [
                'type' => Controls_Manager::SELECT,
                'label' => __( 'View', 'happy_addons' ),
                'separator' => 'after',
                'default' => 'inside',
                'options' => [
                    'inside' => __( 'Label Inside', 'happy_addons' ),
                    'outside' => __( 'Label Outside', 'happy_addons' ),
                ]
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'label',
            [
                'type' => Controls_Manager::TEXT,
                'label' => __( 'Label', 'happy_addons' ),
                'placeholder' => __( 'Type bar label text', 'happy_addons' ),
            ]
        );

        $repeater->add_control(
            'completed',
            [
                'label' => __( 'Completed (%)', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                    'size' => 80
                ],
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ]
            ]
        );

        $repeater->add_control(
            'customized',
            [
                'label' => __( 'Override Style', 'happy_addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'happy_addons' ),
                'label_off' => __( 'No', 'happy_addons' ),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => __( 'Use widget main style or override from here', 'happy_addons' )
            ]
        );

        $repeater->add_control(
            'color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ha-progressbar-info' => 'color: {{VALUE}}',
                ],
                'condition' => ['customized' => 'yes']
            ]
        );

        $repeater->add_control(
            'completed_color',
            [
                'label' => __( 'Completed Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ha-progressbar-completed' => 'background-color: {{VALUE}}',
                ],
                'condition' => ['customized' => 'yes']
            ]
        );

        $repeater->add_control(
            'uncompleted_color',
            [
                'label' => __( 'Uncompleted Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
                ],
                'condition' => ['customized' => 'yes']
            ]
        );

        $this->add_control(
            'bars',
            [
                'show_label' => false,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '<# print((label || completed.size) ? (label || "Label") + " - " + completed.size + completed.unit : "Label - 0%") #>'
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_controls() {
        $this->start_controls_section(
            '_section_bars_style',
            [
                'label' => __( 'Bars', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'height',
            [
                'label' => __( 'Height', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 250,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-progressbar--outside' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ha-progressbar--inside' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'distance',
            [
                'label' => __( 'Distance', 'happy_addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 250,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-progressbar--outside' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ha-progressbar--inside:not(:first-child)' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => __( 'Border Radius', 'happy_addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-progressbar, {{WRAPPER}} .ha-progressbar-completed' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .ha-progressbar'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_bars_label',
            [
                'label' => __( 'Label', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            '_editing_notice',
            [
                'show_label' => false,
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __( 'You can change each bar colors by overriding style respectively.', 'happy_addons' ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->add_control(
            'color',
            [
                'label' => __( 'Text Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-progressbar-info' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'completed_color',
            [
                'label' => __( 'Completed Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-progressbar-completed' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'uncompleted_color',
            [
                'label' => __( 'Uncompleted Color', 'happy_addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-progressbar' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'label' => __( 'Typography', 'happy_addons' ),
                'selector' => '{{WRAPPER}} .ha-progressbar-info',
            ]
        );
    }

	protected function render() {
        $settings = $this->get_settings_for_display();

        if ( ! is_array( $settings['bars'] ) ) {
            return;
        }

        foreach ( $settings['bars'] as $index => $bar ) :
            $label_key = $this->get_repeater_setting_key( 'label', 'bars', $index );
            $this->add_inline_editing_attributes( $label_key, 'none' );
            ?>
            <div class="item ha-progressbar ha-progressbar--<?php echo esc_attr( $settings['view'] ); ?> elementor-repeater-item-<?php echo $bar['_id']; ?>">
                <div class="ha-progressbar-completed" style="width: <?php echo esc_attr( $bar['completed']['size'] ); ?>%;">
                    <div class="ha-progressbar-info"><span <?php echo $this->get_render_attribute_string( $label_key ); ?>><?php echo esc_html( $bar['label'] ); ?></span><span class="ha-progressbar-percent"><?php echo esc_html( $bar['completed']['size'] ); ?>%</span></div>
                </div>
            </div>
            <?php
        endforeach;
    }

    protected function _content_template() {
        ?>
        <#
        if (!_.isArray(settings.bars)) {
            return;
        }
        _.each(settings.bars, function(bar, index) {
            var labelKey = view.getRepeaterSettingKey( 'label', 'bars', index);
            view.addInlineEditingAttributes( labelKey, 'none' );
            #>
            <div class="item ha-progressbar ha-progressbar--{{settings.view}} elementor-repeater-item-{{bar._id}}">
                <div class="ha-progressbar-completed" style="width: {{bar.completed.size}}%;">
                    <div class="ha-progressbar-info"><span {{{view.getRenderAttributeString( labelKey )}}}>{{bar.label}}</span><span class="ha-progressbar-percent">{{bar.completed.size}}%</span></div>
                </div>
            </div>
        <# }); #>
        <?php
    }
}
