<?php

/**
 * Post Comments widget class
 *
 * @package Happy_Addons
 */

namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Happy_Addons\Elementor\Controls\Select2;

defined('ABSPATH') || die();

class Post_Comments extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Post Comments', 'happy-elementor-addons');
    }

    public function get_custom_help_url() {
        return 'https://happyaddons.com/docs/happy-addons-for-elementor/widgets/post-navigation/';
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
        return 'hm hm-comment-square';
    }

    public function get_keywords() {
        return ['comments', 'post', 'response', 'form'];
    }

    /**
     * Register widget content controls
     */
    protected function register_content_controls() {
        $this->__post_comments_controls();
    }

    protected function __post_comments_controls() {
        $this->start_controls_section(
            '_section_post_comments',
            [
                'label' => __('Post Comments', 'happy-elementor-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'source_type',
            [
                'label' => esc_html__('Source', 'happy-elementor-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'current_post' => esc_html__('Current Post', 'happy-elementor-addons'),
                    'custom' => esc_html__('Custom', 'happy-elementor-addons'),
                ],
                'default' => 'current_post',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'source_custom',
            [
                'label' => esc_html__('Search & Select', 'happy-elementor-addons'),
                'type' => Select2::TYPE,
				'multiple' => false,
				'placeholder' => 'Search Post',
				'dynamic_params' => [
					'object_type' => 'post',
					'post_type'   => 'any',
				],
				'select2options' => [
					'minimumInputLength' => 2,
				],
                'label_block' => true,
                'condition' => [
                    'source_type' => 'custom',
                ],
            ]
        );


        $this->end_controls_section();
    }
    /**
     * Register styles related controls
     */
    protected function register_style_controls() {
        $this->__post_comments_style_controls();
    }


    protected function __post_comments_style_controls() {

        $this->start_controls_section(
            'label_style',
            [
                'label' => esc_html__('Comments', 'happy-elementor-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings();

        if ('custom' === $settings['source_type']) {
            $post_id = (int) $settings['source_custom'];
            ha_elementor()->db->switch_to_post($post_id);
        }

        if (!comments_open() && (ha_elementor()->preview->is_preview_mode() || ha_elementor()->editor->is_edit_mode())) :
?>
            <div class="elementor-alert elementor-alert-danger" role="alert">
                <span class="elementor-alert-title">
                    <?php esc_html_e('Comments are closed.', 'elementor-pro'); ?>
                </span>
                <span class="elementor-alert-description">
                    <?php esc_html_e('Switch on comments from either the discussion box on the WordPress post edit screen or from the WordPress discussion settings.', 'elementor-pro'); ?>
                </span>
            </div>
<?php
        else :
            comments_template();
        endif;

        if ('custom' === $settings['source_type']) {
            ha_elementor()->db->restore_current_post();
        }
    }
}
