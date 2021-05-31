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

    // public function __construct() {

    //     // include_once HAPPY_ADDONS_DIR_PATH . 'widgets/mailchimp/mailchimp-api.php';
    //     parent::__construct();
    //     // Mailchimp_api::set_ajax_call();

    // }

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

    // public function get_style_depends() {
    // 	return [
    // 		'elementor-icons-fa-solid',
    // 		'elementor-icons-fa-brands',
    // 	];
    // }

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
                'label' => __('Show With Input?', 'happy-elementor-addons'),
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
            <div class="ha-response-message"></div>
            <form class="ha-mailchimp-form" data-list-id="<?php echo esc_attr(isset($settings['mailchimp_lists']) ? $settings['mailchimp_lists'] : ''); ?>">
                <div class="ha-input-wrapper">
                    <label for="">First Name</label>
                    <div class="ha-input">
                        <div class="ha-icon-wrapper">Icon</div>
                        <input type="text" name="fname" placeholder="Enter your first name">
                    </div>
                </div>
                <div class="ha-input-wrapper">
                    <label for="">Last Name</label>
                    <div class="ha-input">
                        <div class="ha-icon-wrapper">Icon</div>
                        <input type="text" name="lname" placeholder="Enter your last name">
                    </div>
                </div>
                <div class="ha-input-wrapper">
                    <label for="">Phone</label>
                    <div class="ha-input">
                        <div class="ha-icon-wrapper">Icon</div>
                        <input type="text" name="phone" placeholder="Enter your phone">
                    </div>
                </div>
                <div class="ha-input-wrapper">
                    <label for="">Email</label>
                    <div class="ha-input">
                        <?php if ($settings['email_icon_position'] == 'before') : ?>
                            <div class="ha-icon-wrapper"><?php ha_render_icon($settings, null, 'email_icon'); ?></div>
                        <?php endif; ?>
                        <input type="email" name="email" placeholder="Enter your email">
                        <?php if ($settings['email_icon_position'] == 'after') : ?>
                            <div class="ha-icon-wrapper"><?php ha_render_icon($settings, null, 'email_icon'); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="ha-button-wrapper">
                    <div class="ha-icon-wrapper">Icon</div>
                    <input type="button" value="Submit">
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
