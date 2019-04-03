<?php
/**
 * Image Comparison widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Utils;
use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || die();

class Image_Comparison extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Happy Image Comparison', 'happy_addons' );
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
        return [ 'compare', 'image', 'before', 'after' ];
    }

	protected function register_content_controls() {
		$this->start_controls_section(
			'_section_images',
			[
				'label' => __( 'Images', 'happy_addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
            'before_image',
            [
                'label' => __( 'Before Image', 'happy_addons' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'before_text',
            [
                'label' => __( 'Before Text', 'happy_addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Before', 'happy_addons' ),
                'placeholder' => __( 'Type before image text', 'happy_addons' ),
                'description' => __( 'Text will not be shown if Hide Overlay is enabled in Settings', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'after_image',
            [
                'label' => __( 'After Image', 'happy_addons' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'after_text',
            [
                'label' => __( 'After Text', 'happy_addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'After', 'happy_addons' ),
                'placeholder' => __( 'Type after image text', 'happy_addons' ),
                'description' => __( 'Text will not be shown if Hide Overlay is enabled in Settings', 'happy_addons' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'large',
                'separator' => 'none',
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
            'offset',
            [
                'label' => __( 'Slider Position', 'happy_addons' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'description' => __( 'Set how much of the before image is visible when the page loads', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'orientation',
            [
                'label' => __( 'Orientation', 'happy_addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'horizontal' => [
                        'title' => __( 'Horizontal', 'happy_addons' ),
                        'icon' => 'fa fa-arrows-h',
                    ],
                    'vertical' => [
                        'title' => __( 'Vertical', 'happy_addons' ),
                        'icon' => 'fa fa-arrows-v',
                    ],
                ],
                'default' => 'horizontal',
                'description' => __( 'Orientation of the before and after images', 'happy_addons' ),
            ]
        );

        $this->add_control(
            'hide_overlay',
            [
                'label' => __( 'Hide Overlay', 'happy_addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'happy_addons' ),
                'label_off' => __( 'No', 'happy_addons' ),
                'return_value' => 'yes',
                'description' => __( 'Do not show the overlay with before and after', 'happy_addons' )
            ]
        );

        $this->add_control(
            'move_on_hover',
            [
                'label' => __( 'Move On Hover', 'happy_addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'happy_addons' ),
                'label_off' => __( 'No', 'happy_addons' ),
                'return_value' => 'yes',
                'description' => __( 'Move slider on mouse hover', 'happy_addons' )
            ]
        );

        $this->add_control(
            'move_handle_only',
            [
                'label' => __( 'Move Handle Only', 'happy_addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'happy_addons' ),
                'label_off' => __( 'No', 'happy_addons' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __( 'Swipe anywhere on the image to control slider movement', 'happy_addons' )
            ]
        );

        $this->add_control(
            'click_to_move',
            [
                'label' => __( 'Click To Move', 'happy_addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'happy_addons' ),
                'label_off' => __( 'No', 'happy_addons' ),
                'description' => __( 'Click (or tap) anywhere on the image to move the slider to that location', 'happy_addons' )
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_controls() {

    }

    protected static function get_data_settings( $settings ) {
        $field_map = [
            'offset' => 'default_offset_pct',
            'orientation' => 'orientation',
            'hide_overlay' => 'no_overlay',
            'move_on_hover' => 'move_slider_on_hover',
            'move_handle_only' => 'move_with_handle_only',
            'click_to_move' => 'click_to_move',
            'before_text' => 'before_label',
            'after_text' => 'after_label',
        ];

        $data = [];
        foreach ( $field_map as $setting_key => $param_key ) {
            if ( ! isset( $settings[ $setting_key ] ) ) {
                continue;
            }

            $val = ( $setting_key === 'offset' ? ( $settings[ $setting_key ] / 10 ) : $settings[ $setting_key ] );
            $data[ $param_key ] = $val;
        }
        return $data;
    }

	protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'container', 'class', [
            'twentytwenty-container',
            'hajs-image-comparison',
        ] );
        $this->add_render_attribute( 'container', 'data-happy-settings', wp_json_encode( self::get_data_settings( $settings ) ) );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'container' ); ?>>
            <?php if ( ! empty( $settings['before_image']['url'] ) ) :
                $this->add_render_attribute( 'before_image', 'src', $settings['before_image']['url'] );
                $this->add_render_attribute( 'before_image', 'alt', Control_Media::get_image_alt( $settings['before_image'] ) );
                $this->add_render_attribute( 'before_image', 'title', Control_Media::get_image_title( $settings['before_image'] ) );
                $settings['hover_animation'] = 'disable-animation'; // hack to prevent image hover animation
                echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'before_image' );
            endif;

            if ( ! empty( $settings['after_image']['url'] ) ) :
                $this->add_render_attribute( 'after_image', 'src', $settings['after_image']['url'] );
                $this->add_render_attribute( 'after_image', 'alt', Control_Media::get_image_alt( $settings['after_image'] ) );
                $this->add_render_attribute( 'after_image', 'title', Control_Media::get_image_title( $settings['after_image'] ) );
                $settings['hover_animation'] = 'disable-animation'; // hack to prevent image hover animation
                echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'after_image' );
            endif; ?>
        </div>
        <?php
    }


    public function _content_template() {
        ?>
        <#
        view.addRenderAttribute( 'container', 'class', [
            'twentytwenty-container',
            'hajs-image-comparison',
        ] );

        var map = {
            'offset': 'default_offset_pct',
            'orientation': 'orientation',
            'hide_overlay': 'no_overlay',
            'move_on_hover': 'move_slider_on_hover',
            'move_handle_only': 'move_with_handle_only',
            'click_to_move': 'click_to_move',
            'before_text': 'before_label',
            'after_text': 'after_label',
        };

        var dataSettings = {};

        _.each(map, function(dKey, sKey) {
            if (_.isUndefined(settings[sKey])) {
                return;
            }

            var val = ( sKey === 'offset' ? ( settings[sKey] / 10 ) : settings[sKey] );
            dataSettings[dKey] = val;
        });

        view.addRenderAttribute( 'container', 'data-happy-settings', JSON.stringify(dataSettings) ); #>

        <div {{{ view.getRenderAttributeString( 'container' ) }}}>
            <# if ( settings.before_image.url ) {
                var image = {
                    id: settings.before_image.id,
                    url: settings.before_image.url,
                    size: settings.thumbnail_size,
                    dimension: settings.thumbnail_custom_dimension,
                    model: view.getEditModel()
                };

                var image_url = elementor.imagesManager.getImageUrl( image ); #>
                <img src="{{ image_url }}">
            <# }

            if ( settings.after_image.url ) {
                var image = {
                    id: settings.after_image.id,
                    url: settings.after_image.url,
                    size: settings.thumbnail_size,
                    dimension: settings.thumbnail_custom_dimension,
                    model: view.getEditModel()
                };

                var image_url = elementor.imagesManager.getImageUrl( image ); #>
                <img src="{{ image_url }}">
            <# } #>
        </div>
        <?php
    }
}
