<?php

/**
 * Member widget class
 *
 * @package Happy_Addons
 */

namespace Happy_Addons\Elementor\Widget;

use Elementor\Group_Control_Text_Shadow;
use Elementor\Repeater;
use Elementor\Core\Schemes\Typography;
use Elementor\Utils;
use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Happy_Addons\Elementor\Traits\Button_Renderer;
use Happy_Addons\Elementor\Widget\MailChimp\Mailchimp_api;

defined('ABSPATH') || die();

class Mailchimp extends Base {

    /**
     * Get widget title.
     *
     * @since 2.24.1
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('MailChimp', 'happy-elementor-addons');
    }

    /**
     * Get widget icon.
     *
     * @since 2.24.1
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'hm hm-mail-open';
    }

    public function get_keywords() {
        return ['email', 'mail chimp', 'mail', 'subscription'];
    }

    /**
     * Register content related controls
     */
    protected function register_content_controls() {

        include_once HAPPY_ADDONS_DIR_PATH . 'widgets/mailchimp/mailchimp-api.php';

        $this->start_controls_section(
            '_section_mailchimp',
            [
                'label' => __('MailChimp', 'happy-elementor-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'mailchimp_api',
            [
                'label' => __('MailChimp API', 'happy-elementor-addons'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => __('Enter your mailchimp api here', 'happy-elementor-addons'),
            ]
        );

        $this->add_control(
            'mailchimp_lists',
            [
                'label' => __('Lists', 'happy-elementor-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => '01',
                'options' => ['01' => esc_html__('Select a List', 'happy-elementor-addons')] + Mailchimp_api::get_mailchimp_lists(),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_mailchimp_form',
            [
                'label' => __('Form', 'happy-elementor-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'form_alignment',
            [
                'label' => __('Form Aligment', 'happy-elementor-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal' => esc_html__('Horizontal', 'happy-elementor-addons'),
                    'vertical' => esc_html__('Vertical', 'happy-elementor-addons'),
                ],
            ]
        );

        $this->add_control(
            'enable_name',
            [
                'label' => __('Enable Name?', 'happy-elementor-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'happy-elementor-addons'),
                'label_off' => __('No', 'happy-elementor-addons'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            '_fname_heading',
            [
                'label' => __('First Name:', 'happy-elementor-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'enable_name' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'fname_label',
            [
                'label' => __('Label', 'happy-elementor-addons'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('First Name input label', 'happy-elementor-addons'),
                'condition' => [
                    'enable_name' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'fname_placeholder',
            [
                'label' => __('Placeholder', 'happy-elementor-addons'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('First Name input placeholder', 'happy-elementor-addons'),
                'condition' => [
                    'enable_name' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'fname_enable_icon',
            [
                'label' => __('Enable Icon With Input?', 'happy-elementor-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'happy-elementor-addons'),
                'label_off' => __('No', 'happy-elementor-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'enable_name' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'fname_icon',
            [
                'label' => __('Icon', 'happy-elementor-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'hm hm-envelop',
                    'library' => 'regular',
                ],
                'condition' => [
                    'enable_name' => 'yes',
                    'fname_enable_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'fname_icon_position',
            [
                'label' => __('Icon Position', 'plugin-domain'),
                'type' => Controls_Manager::SELECT,
                'default' => 'before',
                'options' => [
                    'before'  => __('Before Input', 'plugin-domain'),
                    'after' => __('After Input', 'plugin-domain'),
                ],
                'condition' => [
                    'enable_name' => 'yes',
                    'fname_enable_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_lname_heading',
            [
                'label' => __('Last Name:', 'happy-elementor-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'enable_name' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'lname_label',
            [
                'label' => __('Label', 'happy-elementor-addons'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('Last Name input label', 'happy-elementor-addons'),
                'condition' => [
                    'enable_name' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'lname_placeholder',
            [
                'label' => __('Placeholder', 'happy-elementor-addons'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('Last Name input placeholder', 'happy-elementor-addons'),
                'condition' => [
                    'enable_name' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'lname_enable_icon',
            [
                'label' => __('Enable Icon With Input?', 'happy-elementor-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'happy-elementor-addons'),
                'label_off' => __('No', 'happy-elementor-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'enable_name' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'lname_icon',
            [
                'label' => __('Icon', 'happy-elementor-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'hm hm-envelop',
                    'library' => 'regular',
                ],
                'condition' => [
                    'enable_name' => 'yes',
                    'lname_enable_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'lname_icon_position',
            [
                'label' => __('Icon Position', 'plugin-domain'),
                'type' => Controls_Manager::SELECT,
                'default' => 'before',
                'options' => [
                    'before'  => __('Before Input', 'plugin-domain'),
                    'after' => __('After Input', 'plugin-domain'),
                ],
                'condition' => [
                    'enable_name' => 'yes',
                    'lname_enable_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'enable_phone',
            [
                'label' => __('Enable Phone?', 'happy-elementor-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'happy-elementor-addons'),
                'label_off' => __('No', 'happy-elementor-addons'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            '_phone_heading',
            [
                'label' => __('Phone:', 'happy-elementor-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'enable_phone' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'phone_label',
            [
                'label' => __('Label', 'happy-elementor-addons'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('Phone input label', 'happy-elementor-addons'),
                'condition' => [
                    'enable_phone' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'phone_placeholder',
            [
                'label' => __('Placeholder', 'happy-elementor-addons'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('Phone input placeholder', 'happy-elementor-addons'),
                'condition' => [
                    'enable_phone' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'phone_enable_icon',
            [
                'label' => __('Enable Icon With Input?', 'happy-elementor-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'happy-elementor-addons'),
                'label_off' => __('No', 'happy-elementor-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'enable_phone' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'phone_icon',
            [
                'label' => __('Icon', 'happy-elementor-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'hm hm-envelop',
                    'library' => 'regular',
                ],
                'condition' => [
                    'enable_phone' => 'yes',
                    'phone_enable_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'phone_icon_position',
            [
                'label' => __('Icon Position', 'plugin-domain'),
                'type' => Controls_Manager::SELECT,
                'default' => 'before',
                'options' => [
                    'before'  => __('Before Input', 'plugin-domain'),
                    'after' => __('After Input', 'plugin-domain'),
                ],
                'condition' => [
                    'enable_phone' => 'yes',
                    'phone_enable_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_email_heading',
            [
                'label' => __('Email:', 'happy-elementor-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'email_label',
            [
                'label' => __('Label', 'happy-elementor-addons'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('Email input label', 'happy-elementor-addons'),
            ]
        );

        $this->add_control(
            'email_placeholder',
            [
                'label' => __('Placeholder', 'happy-elementor-addons'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('Email input placeholder', 'happy-elementor-addons'),
            ]
        );

        $this->add_control(
            'email_enable_icon',
            [
                'label' => __('Enable Icon With Input?', 'happy-elementor-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'happy-elementor-addons'),
                'label_off' => __('No', 'happy-elementor-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'email_icon',
            [
                'label' => __('Icon', 'happy-elementor-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'hm hm-envelop',
                    'library' => 'regular',
                ],
                'condition' => [
                    'email_enable_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'email_icon_position',
            [
                'label' => __('Icon Position', 'plugin-domain'),
                'type' => Controls_Manager::SELECT,
                'default' => 'before',
                'options' => [
                    'before'  => __('Before Input', 'plugin-domain'),
                    'after' => __('After Input', 'plugin-domain'),
                ],
                'condition' => [
                    'email_enable_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            '_button_heading',
            [
                'label' => __('Button:', 'happy-elementor-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Text', 'happy-elementor-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Subscribe',
            ]
        );

        $this->add_control(
            'button_enable_icon',
            [
                'label' => __('Enable Icon With Button?', 'happy-elementor-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'happy-elementor-addons'),
                'label_off' => __('No', 'happy-elementor-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'button_icon',
            [
                'label' => __('Icon', 'happy-elementor-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'hm hm-tick',
                    'library' => 'regular',
                ],
                'condition' => [
                    'button_enable_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'button_icon_position',
            [
                'label' => __('Icon Position', 'plugin-domain'),
                'type' => Controls_Manager::SELECT,
                'default' => 'before',
                'options' => [
                    'before'  => __('Before Input', 'plugin-domain'),
                    'after' => __('After Input', 'plugin-domain'),
                ],
                'condition' => [
                    'button_enable_icon' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register styles related controls
     */
    protected function register_style_controls() {

        $this->start_controls_section(
            '_section_style_mailchimp_form',
            [
                'label' => __('Form', 'happy-elementor-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->end_controls_section();
    }

    // public function get_widget_settings(){
    //     return $this->get_settings_for_display();
    // }

    protected function render() {

        // add_action('wp_ajax_ha_mailchimp_ajax', [$this, 'mailchimp_ajax_handler']);
        // add_action('wp_ajax_nopriv_ha_mailchimp_ajax', [$this, 'mailchimp_ajax_handler']);

        $settings = $this->get_settings_for_display();
        // echo "<pre>";
        // print_r($settings);
        // echo "</pre>";
?>
        <div class="ha-mailchimp-wrapper">
            <div class="ha-mc-response-message"></div>
            <form class="ha-mailchimp-form <?php echo esc_attr($settings['form_alignment']); ?>" data-list-id="<?php echo esc_attr(isset($settings['mailchimp_lists']) ? $settings['mailchimp_lists'] : ''); ?>">
                <?php if ($settings['enable_name'] == 'yes') : ?>
                    <div class="ha-mc-input-wrapper">
                        <label><?php echo esc_html($settings['fname_label']); ?></label>
                        <div class="ha-mc-input">
                            <?php if ($settings['fname_enable_icon'] == 'yes' && $settings['fname_icon_position'] == 'before') : ?>
                                <div class="ha-mc-icon-wrapper"><?php ha_render_icon($settings, null, 'fname_icon'); ?></div>
                            <?php endif; ?>
                            <input type="text" name="fname" placeholder="<?php echo esc_attr($settings['fname_placeholder']); ?>">
                            <?php if ($settings['fname_enable_icon'] == 'yes' && $settings['fname_icon_position'] == 'after') : ?>
                                <div class="ha-mc-icon-wrapper"><?php ha_render_icon($settings, null, 'fname_icon'); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="ha-mc-input-wrapper">
                        <label><?php echo esc_html($settings['lname_label']); ?></label>
                        <div class="ha-mc-input">
                            <?php if ($settings['lname_enable_icon'] == 'yes' && $settings['lname_icon_position'] == 'before') : ?>
                                <div class="ha-mc-icon-wrapper"><?php ha_render_icon($settings, null, 'lname_icon'); ?></div>
                            <?php endif; ?>
                            <input type="text" name="lname" placeholder="<?php echo esc_attr($settings['lname_placeholder']); ?>">
                            <?php if ($settings['lname_enable_icon'] == 'yes' && $settings['lname_icon_position'] == 'after') : ?>
                                <div class="ha-mc-icon-wrapper"><?php ha_render_icon($settings, null, 'lname_icon'); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($settings['enable_phone'] == 'yes') : ?>
                    <div class="ha-mc-input-wrapper">
                        <label><?php echo esc_html($settings['phone_label']); ?></label>
                        <div class="ha-mc-input">
                            <?php if ($settings['phone_enable_icon'] == 'yes' && $settings['phone_icon_position'] == 'before') : ?>
                                <div class="ha-mc-icon-wrapper"><?php ha_render_icon($settings, null, 'phone_icon'); ?></div>
                            <?php endif; ?>
                            <input type="text" name="phone" placeholder="<?php echo esc_attr($settings['phone_placeholder']); ?>">
                            <?php if ($settings['phone_enable_icon'] == 'yes' && $settings['phone_icon_position'] == 'after') : ?>
                                <div class="ha-mc-icon-wrapper"><?php ha_render_icon($settings, null, 'phone_icon'); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="ha-mc-input-wrapper">
                    <label><?php echo esc_html($settings['email_label']); ?></label>
                    <div class="ha-mc-input">
                        <?php if ($settings['email_enable_icon'] == 'yes' && $settings['email_icon_position'] == 'before') : ?>
                            <div class="ha-mc-icon-wrapper"><?php ha_render_icon($settings, null, 'email_icon'); ?></div>
                        <?php endif; ?>
                        <input type="email" name="email" placeholder="<?php echo esc_attr($settings['email_placeholder']); ?>">
                        <?php if ($settings['email_enable_icon'] == 'yes' && $settings['email_icon_position'] == 'after') : ?>
                            <div class="ha-mc-icon-wrapper"><?php ha_render_icon($settings, null, 'email_icon'); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="ha-mc-button-wrapper">

                    <button class="ha-mc-button">
                        <?php if ($settings['button_enable_icon'] == 'yes' && $settings['button_icon_position'] == 'before') : ?>
                            <div class="ha-mc-icon-wrapper"><?php ha_render_icon($settings, null, 'button_icon'); ?></div>
                        <?php endif; ?>
                        <?php echo esc_attr($settings['button_text']); ?>
                        <?php if ($settings['button_enable_icon'] == 'yes' && $settings['button_icon_position'] == 'after') : ?>
                            <div class="ha-mc-icon-wrapper"><?php ha_render_icon($settings, null, 'button_icon'); ?></div>
                        <?php endif; ?>
                    </button>
                </div>
            </form>
        </div>
<?php
    }

    // public function mailchimp_ajax_handler() {
    //     echo wp_send_json(['test' => 'data']);

    //     wp_die();
    // }
}
