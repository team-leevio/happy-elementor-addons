<?php

/**
 * Member widget class
 *
 * @package Happy_Addons
 */

namespace Happy_Addons\Elementor\Widget;

use Elementor\Group_Control_Text_Shadow;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Happy_Addons\Elementor\Controls\Select2;
use \Happy_Addons\Elementor\Credentials_Manager;

defined('ABSPATH') || die();

class Image_Accordion extends Base {

    /**
     * Get widget title.
     *
     * @since 2.24.1
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Image Accordion', 'happy-elementor-addons');
    }

    // public function __construct($data = [], $args = null) {
    // 	parent::__construct($data, $args);
    // }

    /**
     * Get widget icon.
     *
     * @since 2.24.1
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'hm hm-image-accordion';
    }

    public function get_keywords() {
        return ['image', 'accordion', 'image accordion'];
    }

    protected function content() {
        $this->start_controls_section(
            '_section_content',
            [
                'label' => __('Content', 'happy-elementor-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'background_image',
            [
                'label' => __('Choose Image', 'plugin-domain'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label'   => __('Title', 'happy-elementor-addons'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Image Accordion', 'happy-elementor-addons'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

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
            'enable_button',
            [
                'label'        => __('Enable Button', 'happy-elementor-addons'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'happy-elementor-addons'),
                'label_off'    => __('No', 'happy-elementor-addons'),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before'
            ]
        );

        $repeater->add_control(
            'button_label',
            [
                'label'   => __('Button Label', 'happy-elementor-addons'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Read More', 'happy-elementor-addons'),
                'condition' => [
                    'enable_button' => 'yes',
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'button_url',
            [
                'label'   => __('Button URL', 'happy-elementor-addons'),
                'type'    => Controls_Manager::URL,
                'condition' => [
                    'enable_button' => 'yes',
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'enable_popup',
            [
                'label'        => __('Enable Popup', 'happy-elementor-addons'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'happy-elementor-addons'),
                'label_off'    => __('No', 'happy-elementor-addons'),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before'
            ]
        );

        $repeater->add_control(
            'popup_icon',
            [
                'label' => __('Popup Icon', 'happy-elementor-addons'),
                'type'  => Controls_Manager::ICONS,
                'condition' => [
                    'enable_popup' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'enable_link',
            [
                'label'        => __('Enable Link', 'happy-elementor-addons'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'happy-elementor-addons'),
                'label_off'    => __('No', 'happy-elementor-addons'),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before'
            ]
        );

        $repeater->add_control(
            'link_url',
            [
                'label'   => __('Link URL', 'happy-elementor-addons'),
                'type'    => Controls_Manager::URL,
                'condition' => [
                    'enable_link' => 'yes',
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
            'link_icon',
            [
                'label' => __('Link Icon', 'happy-elementor-addons'),
                'type'  => Controls_Manager::ICONS,
                'condition' => [
                    'enable_link' => 'yes',
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
                'separator'    => 'before'
            ]
        );

        $this->add_control(
            'accordion_items',
            [
                'label'         => __('Items', 'happy-elementor-addons'),
                'type'          => Controls_Manager::REPEATER,
                'fields'        => $repeater->get_controls(),
                'prevent_empty' => true,
                'default'       => [
                    [
                        'title'         => __('Image Accordion 1', 'happy-elementor-addons'),
                        'enable_button'  => 'yes',
                        'active'        => 'yes',
                    ],
                    [
                        'title'         => __('Image Accordion 2', 'happy-elementor-addons'),
                        'enable_button'  => 'yes',
                    ],
                    [
                        'title'         => __('Image Accordion 3', 'happy-elementor-addons'),
                        'enable_button'  => 'yes',
                    ],
                    [
                        'title'         => __('Image Accordion 4', 'happy-elementor-addons'),
                        'enable_button'  => 'yes',
                    ],
                ],
                'title_field'   => '{{{ title }}}',
            ]
        );

        $this->add_responsive_control(
            'items_style',
            [
                'label'         => esc_html__('Style', 'happy-elementor-addons'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'horizontal'    => esc_html__('Horizontal', 'happy-elementor-addons'),
                    'vertical'      => esc_html__('Vertical', 'happy-elementor-addons'),
                ],
                'default'       => 'horizontal',
                'prefix_class'  => 'ha-image-accordion%s-',
            ]
        );

        $this->add_control(
            'active_behavior',
            [
                'label'         => esc_html__('Active Behavoir', 'happy-elementor-addons'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'click' => esc_html__('Click', 'happy-elementor-addons'),
                    'hover' => esc_html__('Hover', 'happy-elementor-addons'),
                ],
                'default'       => 'click',
                'prefix_class'  => 'ha-image-accordion-',
            ]
        );

        $this->add_control(
            'active_behavior_notice',
            [
                'raw' => '<strong>' . esc_html__('Please note!', 'happy-elementor-addons') . '</strong> ' . esc_html__('Active on load won\'t be working with this active behavior.', 'happy-elementor-addons'),
                'type' => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'render_type' => 'ui',
                'condition' => [
                    'active_behavior' => 'hover',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register content related controls
     */
    protected function register_content_controls() {
        $this->content();
    }

    protected function common() {
        $this->start_controls_section(
            '_section_common',
            [
                'label' => __('Common', 'happy-elementor-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register styles related controls
     */
    protected function register_style_controls() {
        $this->common();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
?>
        <div class="ha-image-accordion-wrapper">
            <div class="ha-ia-container">
                <div class="ha-ia-gallery-wrap">
                    <?php foreach ($settings['accordion_items'] as $inx => $item) : ?>
                        <div style="background-image: url('<?php echo esc_url($item['background_image']['url']); ?>');" class="ha-ia-item <?php echo esc_attr(($item['active'] == 'yes') ? 'active' : ''); ?>">
                            <div class="ha-ia-content-wrapper">
                                <?php if ($item['enable_popup'] == 'yes' || $item['enable_link'] == 'yes') : ?>
                                    <div class="ha-ia-actions">
                                        <?php if ($item['enable_popup'] == 'yes') : ?>
                                            <span class="ha-ia-popup">
                                                <a href="<?php echo esc_url($item['background_image']['url']); ?>" data-elementor-open-lightbox="yes">
                                                    <?php ha_render_icon($item, null, 'popup_icon'); ?>
                                                </a>
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($item['enable_link'] == 'yes') : ?>
                                            <span class="ha-ia-link">
                                                <a href="<?php echo esc_url($item['link_url']['url']); ?>" <?php echo esc_attr($item['link_url']['is_external'] ? 'target="_blank"' : ''); ?> <?php echo esc_attr($item['link_url']['nofollow'] ? 'rel="nofollow"' : ''); ?>>
                                                    <?php ha_render_icon($item, null, 'link_icon'); ?>
                                                </a>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="ha-ia-content-icon-title ha-ia-icon-<?php echo esc_attr($item['icon_align']); ?>">
                                    <?php ha_render_icon($item, null, 'icon'); ?>
                                    <span class="ha-ia-content-title"><?php echo esc_html($item['title']); ?></span>
                                </div>
                                <?php if ($item['enable_button'] == 'yes') : ?>
                                    <div class="ha-ia-content-button">
                                        <a href="<?php echo esc_attr($item['button_url']['url']); ?>"  <?php echo esc_attr($item['button_url']['is_external'] ? 'target="_blank"' : ''); ?> <?php echo esc_attr($item['button_url']['nofollow'] ? 'rel="nofollow"' : ''); ?>><?php echo esc_html($item['button_label']); ?></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
<?php
    }
}
