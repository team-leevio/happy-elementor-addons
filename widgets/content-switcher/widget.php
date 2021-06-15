<?php

/**
 * Contact form 7 widget class
 *
 * @package Happy_Addons
 */

namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Repeater;

defined('ABSPATH') || die();

class Content_Switcher extends Base {

    /**
     * Get widget title.
     *
     * @since 2.24.2
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Content Switcher', 'happy-elementor-addons');
    }

    public function get_custom_help_url() {
        // return 'https://happyaddons.com/docs/happy-addons-for-elementor/widgets/';
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
        return 'hm hm-switcher';
    }

    public function get_keywords() {
        return ['content', 'switcher', 'toggle'];
    }

    protected function register_content_controls() {

        $this->start_controls_section(
            '_section_design',
            [
                'label' => __('Design', 'happy-elementor-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'select_design',
            [
                'label'   => __('Choose Design', 'happy-elementor-addons'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'round' => __('Round', 'happy-elementor-addons'),
                    'round-2'   => __('Round 2', 'happy-elementor-addons'),
                    'square' => __('Square', 'happy-elementor-addons'),
                    'button'    => __('Button', 'happy-elementor-addons'),
                ],
                'default' => 'round',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_content',
            [
                'label' => __('Content', 'happy-elementor-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'design_warning_message',
            [
                'raw' => '<strong>' . esc_html__('Please note!', 'happy-elementor-addons') . '</strong> ' . esc_html__('This design requires only two items.', 'happy-elementor-addons'),
                'type' => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'render_type' => 'ui',
                'condition' => [
                    'select_design' => ['round', 'round-2', 'square'],
                ],
            ]
        );

        // $this->add_control(
        // 	'skin_msg',
        // 	[
        // 		'label'      => __( 'Skin Note', 'happy-elementor-addons' ),
        // 		'type'       => Controls_Manager::RAW_HTML,
        // 		'raw'        => __( 'NOTE : This Skin requires only two items.', 'happy-elementor-addons' ),
        // 		'show_label' => false,
        // 		'condition'  => [
        // 			'_skin' => [ 'skin3', 'skin4' ],
        // 		],
        // 	]
        // );

        $repeater = new Repeater();

        $repeater->add_control(
            'title',
            [
                'label'   => __('Title', 'happy-elementor-addons'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Content1', 'happy-elementor-addons'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'content_type',
            [
                'label'   => __('Type', 'happy-elementor-addons'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'plain_content' => __('WYSIWYG', 'happy-elementor-addons'),
                    'saved_section' => __('Saved Section', 'happy-elementor-addons'),
                    'saved_page'    => __('Saved Page', 'happy-elementor-addons'),
                ],
                'default' => 'plain_content',
            ]
        );
        $repeater->add_control(
            'plain_content',
            [
                'label'     => __('Type', 'happy-elementor-addons'),
                'type'      => Controls_Manager::WYSIWYG,
                'condition' => [
                    'content_type' => 'plain_content',
                ],
                'dynamic'   => [
                    'active' => true,
                ],
                'default'   => __('Add some nice text here.', 'happy-elementor-addons'),
            ]
        );
        // $saved_sections[''] = __( 'Select Section', 'happy-elementor-addons' );
        // $saved_sections     = $saved_sections + Helper::select_elementor_page( 'section' );
        $saved_sections = [
            '01' => '01',
            '02' => '02',
            '03' => '03',
            '04' => '04',
        ];

        $repeater->add_control(
            'saved_section',
            [
                'label'     => __('Sections', 'happy-elementor-addons'),
                'type'      => Controls_Manager::SELECT,
                'options'   => $saved_sections,
                'condition' => [
                    'content_type' => 'saved_section',
                ],
            ]
        );

        // $saved_page[''] = __( 'Select Pages', 'happy-elementor-addons' );
        // $saved_page     = $saved_page + Helper::select_elementor_page( 'page' );

        $saved_page = [
            '01' => '01',
            '02' => '02',
            '03' => '03',
            '04' => '04',
        ];

        $repeater->add_control(
            'saved_pages',
            [
                'label'     => __('Pages', 'happy-elementor-addons'),
                'type'      => Controls_Manager::SELECT,
                'options'   => $saved_page,
                'condition' => [
                    'content_type' => 'saved_page',
                ],
            ]
        );

        // $saved_ae_template[''] = __( 'Select AE Template', 'happy-elementor-addons' );
        // $saved_ae_template     = $saved_ae_template + Helper::select_ae_templates();

        // $saved_page = [
        //     '01' => '01',
        //     '02' => '02',
        //     '03' => '03',
        //     '04' => '04',
        // ];

        // $repeater->add_control(
        // 	'ae_templates',
        // 	[
        // 		'label'     => __( 'AE-Templates', 'happy-elementor-addons' ),
        // 		'type'      => Controls_Manager::SELECT,
        // 		'options'   => $saved_ae_template,
        // 		'condition' => [
        // 			'content_type' => 'ae_template',
        // 		],
        // 	]
        // );

        $repeater->add_control(
            'icon',
            [
                'label' => __('Icon', 'happy-elementor-addons'),
                'type'  => Controls_Manager::ICONS,
            ]
        );

        $repeater->add_control(
            'icon_align',
            [
                'label'   => __('Icon Position', 'happy-elementor-addons'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left'  => __('Left', 'happy-elementor-addons'),
                    'right' => __('Right', 'happy-elementor-addons'),
                ],
            ]
        );

        $repeater->add_control(
            'active',
            [
                'label'        => __('Active', 'happy-elementor-addons'),
                'type'         => Controls_Manager::SWITCHER,
                'description'  => __('Active on Load', 'happy-elementor-addons'),
                'label_on'     => __('Yes', 'happy-elementor-addons'),
                'label_off'    => __('No', 'happy-elementor-addons'),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'content_list',
            [
                'label'         => __('List', 'happy-elementor-addons'),
                'type'          => Controls_Manager::REPEATER,
                'fields'        => $repeater->get_controls(),
                'prevent_empty' => true,
                'default'       => [
                    [
                        'title'         => __('Primary', 'happy-elementor-addons'),
                        'content_type'  => 'plain_content',
                        'plain_content' => __('Nam sit amet magna a ex tincidunt faucibus nec nec velit. Pellentesque posuere ac metus vitae luctus. Vivamus congue leo ut posuere consectetur. Proin mattis turpis non dignissim faucibus. Aenean iaculis urna non purus consectetur, auctor suscipit elit cursus. Ut quis vehicula ex. Ut pulvinar velit sed nulla gravida, id euismod ipsum finibus', 'happy-elementor-addons'),
                        'active'        => 'yes',
                    ],
                    [
                        'title'         => __('Secondary', 'happy-elementor-addons'),
                        'content_type'  => 'plain_content',
                        'plain_content' => __('Nam sit amet magna a ex tincidunt faucibus nec nec velit. Pellentesque posuere ac metus vitae luctus. Vivamus congue leo ut posuere consectetur. Proin mattis turpis non dignissim faucibus. Aenean iaculis urna non purus consectetur, auctor suscipit elit cursus. Ut quis vehicula ex. Ut pulvinar velit sed nulla gravida, id euismod ipsum finibus', 'happy-elementor-addons'),
                    ],
                ],
                'title_field'   => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_display_settings',
            [
                'label' => __('Display Settings', 'happy-elementor-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'switch_align',
            [
                'label'     => __('Switch Alignment', 'happy-elementor-addons'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left' => [
                        'title' => __('Left', 'happy-elementor-addons'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'happy-elementor-addons'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'happy-elementor-addons'),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' => 'text-align : {{VALUE}}',
                ],
                'default'   => 'center',
                'toggle'    => true,
            ]
        );

        $this->add_responsive_control(
            'space_between',
            [
                'label'       => __('Space', 'happy-elementor-addons'),
                'description' => __('Set Space between switcher and content section', 'happy-elementor-addons'),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => ['px', '%'],
                'range'       => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'     => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors'   => [
                    '{{WRAPPER}} ' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'anim_duration',
            [
                'label'       => __('Animation Speed', 'happy-elementor-addons'),
                'type'        => Controls_Manager::NUMBER,
                'description' => __('Set Animation Duration in Millisecond', 'happy-elementor-addons'),
                'min'         => 100,
                'max'         => 3000,
                'step'        => 100,
                'default'     => 400,
                'selectors'   => [
                    '{{WRAPPER}} ' => 'transition-duration: {{VALUE}}ms',
                    '{{WRAPPER}} ' => 'transition-duration: {{VALUE}}ms',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_controls() {

        $this->start_controls_section(
            '_section_style_switch',
            [
                'label' => __('Switch', 'happy-elementor-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $primary = (isset($settings['content_list'][0]) ? $settings['content_list'][0] : '');
        $secondary = (isset($settings['content_list'][1]) ? $settings['content_list'][1] : '');
?>
        <div class="ha-content-switcher-wrapper ha-cs-design-<?php echo esc_attr($settings['select_design']); ?>">
            <div class="ha-cs-switch-container">
                <div class="ha-cs-switch-wrapper">
                    <?php if ($settings['select_design'] == 'button') : ?>
                        <?php foreach ($settings['content_list'] as $i => $item) : ?>
                            <button class="ha-cs-button <?php echo esc_attr(($item['active'] == 'yes') ? 'active' : ''); ?>" data-content-id="<?php echo esc_attr($item['_id']); ?>">
                                <div class="ha-cs-icon-wrapper"><?php ha_render_icon($item, null, 'icon'); ?></div><span><?php echo esc_html($item['title']); ?></span>
                            </button>
                        <?php endforeach; ?>
                    <?php else :
                    ?>
                        <div class="ha-cs-switch <?php echo esc_attr(($primary['active'] == 'yes') ? 'active' : ''); ?>" data-content-id="<?php echo esc_attr($primary['_id']); ?>">
                            <div class="ha-cs-icon-wrapper"><?php ha_render_icon($primary, null, 'icon'); ?></div><span><?php echo esc_html($primary['title']); ?></span>
                        </div>

                        <label class="ha-cs-switch">
                            <input type="checkbox">
                            <span class="ha-cs-slider ha-cs-<?php echo esc_attr($settings['select_design']); ?>"></span>
                        </label>

                        <div class="ha-cs-switch <?php echo esc_attr(($secondary['active'] == 'yes') ? 'active' : ''); ?>" data-content-id="<?php echo esc_attr($secondary['_id']); ?>">
                            <div class="ha-cs-icon-wrapper"><?php ha_render_icon($secondary, null, 'icon'); ?></div><span><?php echo esc_html($secondary['title']); ?></span>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
            <div class="ha-cs-content-container">
                <div class="ha-cs-content-wrapper">
                    <?php if ($settings['select_design'] == 'button') : ?>
                        <?php foreach ($settings['content_list'] as $i => $item) : ?>
                            <div id="<?php echo esc_attr($item['_id']); ?>" class="ha-cs-content-section <?php echo esc_attr(($item['active'] == 'yes') ? 'active' : ''); ?>"><?php echo $item['plain_content'] ?></div>
                        <?php endforeach; ?>
                    <?php else :
                    ?>
                        <div id="<?php echo esc_attr($primary['_id']); ?>" class="ha-cs-content-section <?php echo esc_attr(($primary['active'] == 'yes') ? 'active' : ''); ?>"><?php echo $primary['plain_content'] ?></div>

                        <div id="<?php echo esc_attr($secondary['_id']); ?>" class="ha-cs-content-section <?php echo esc_attr(($secondary['active'] == 'yes') ? 'active' : ''); ?>"><?php echo $secondary['plain_content'] ?></div>

                    <?php endif; ?>
                </div>
            </div>
        </div>
<?php
    }
}
