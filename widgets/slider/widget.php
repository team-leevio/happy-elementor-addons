<?php
/**
 * Slider widget class
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

class Slider extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Happy Slider', 'happy_addons' );
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
        return [ 'slider', 'image', 'gallery', 'carousel' ];
    }

	protected function register_content_controls() {
        $this->start_controls_section(
            '_section_slides',
            [
                'label' => __( 'Slides', 'happy_addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'type' => Controls_Manager::MEDIA,
                'label' => __( 'Image', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'slides',
            [
                'label' => __( 'Slides', 'happy_addons' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'medium_large',
                'separator' => 'before',
                'exclude' => [
                    'custom'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_settings',
            [
                'label' => __( 'Settings', 'happy_addons' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'nav',
            [
                'label' => __( 'Display Navigation?', 'happy_addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'happy_addons' ),
                'label_off' => __( 'No', 'happy_addons' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __( 'Enable to display next and previous navigation', 'happy_addons' )
            ]
        );

        $this->add_control(
            'dots',
            [
                'label' => __( 'Display Pagination?', 'happy_addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'happy_addons' ),
                'label_off' => __( 'No', 'happy_addons' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __( 'Enable to display dots pagination', 'happy_addons' )
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __( 'Enable Autoplay', 'happy_addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'happy_addons' ),
                'label_off' => __( 'No', 'happy_addons' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_timeout',
            [
                'label' => __( 'Autoplay Timeout', 'happy_addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1000,
                'step' => 100,
                'max' => 10000,
                'default' => 5000,
                'description' => __( 'Set autoplay start time in milliseconds', 'happy_addons' ),
                'condition' => ['autoplay' => ['yes']],
            ]
        );

        $this->add_control(
            'hover_pause',
            [
                'label' => __( 'Pause On Hover', 'happy_addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'happy_addons' ),
                'label_off' => __( 'No', 'happy_addons' ),
                'return_value' => 'yes',
                'description' => __( 'Enable to pause the slides on mouse over', 'happy_addons' ),
                'condition' => ['autoplay' => ['yes']],
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => __( 'Enable Loop', 'happy_addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'happy_addons' ),
                'label_off' => __( 'No', 'happy_addons' ),
                'return_value' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_controls() {

    }

    protected static function get_data_prop_settings( $settings ) {
        $field_map = [
            'nav' => 'nav.bool',
            'dots' => 'dots.bool',
            'autoplay' => 'autoplay.bool',
            'autoplay_timeout' => 'autoplayTimeout.int',
            'hover_pause' => 'autoplayHoverPause.bool',
            'loop' => 'loop.bool',
        ];

        return ha_prepare_data_prop_settings( $settings, $field_map );
    }

	protected function render() {
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
    }
}
