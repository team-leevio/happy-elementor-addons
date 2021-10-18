<?php
/**
 * Photo Stack widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Utils;

defined('ABSPATH') || die();

class PDF_View extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('PDF View', 'happy-elementor-addons');
    }

    public function get_custom_help_url() {
        return 'https://happyaddons.com/docs/happy-addons-for-elementor/widgets/pdf-view/';
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
        return 'hm hm-slider-doc';
    }

    public function get_keywords() {
        return ['pdf', 'document', 'docs'];
    }

    /**
     * Register widget content controls
     */
    protected function register_content_controls() {
        $this->__pdf_content_controls();
    }

    protected function __pdf_content_controls() {
        $this->start_controls_section(
            '_section_photo_stack',
            [
                'label' => __('PDF Source', 'happy-elementor-addons'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
			'pdf_view_type',
			[
				'label'        => __( 'PDFjs View', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'happy-elementor-addons' ),
				'label_off' => __( 'No', 'happy-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'file_type',
			[
				'label' => __('File Source', 'happy-elementor-addons'),
				'type' => Controls_Manager::SELECT,
				'options' =>[
					'url' => __('URL', 'happy-elementor-addons'),
					'upload_file' => __('Upload File', 'happy-elementor-addons'),
				],
                'default' => 'url',
                'condition' => [
					'pdf_view_type' => '',
				]
			]
		);

		$this->add_control(
			'pdf_url',
			[
				'label' => __('PDF URL', 'happy-elementor-addons'),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'http://www.example.com/sample.pdf', 'happy-elementor-addons'),
				'show_external' => false,
				'dynamic' => [
					'active' => false,
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'pdf_view_type',
							'operator' => '==',
							'value' => '',
						],
						[
							'name' => 'file_type',
							'operator' => '==',
							'value' => 'url',
						],
					],
				],
            ]
		);

		$this->add_control(
			'pdf_file',
			[
				'label' => __( 'Choose PDF',  'happy-elementor-addons' ),
				'type' => Controls_Manager::MEDIA,
				'media_type' => 'application/pdf',
				'dynamic' => [
					'active' => true,
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'pdf_view_type',
							'operator' => '==',
							'value' => 'yes',
						],
						[
							'name' => 'file_type',
							'operator' => '==',
							'value' => 'upload_file',
						],
					],
				],
			]
		);

        $this->add_responsive_control(
            'image_container_height',
            [
                'label'      => __('Minimum Height', 'happy-elementor-addons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh', 'em'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'size' => 600,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .pdf_viewer_container iframe' => 'min-height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        
        $this->end_controls_section();
    }

    /**
     * Register widget style controls
     */
    protected function register_style_controls() {
        // $this->__photo_stack_style_controls();
    }

   
    /**
     * @return null
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $unique_id = wp_unique_id('viewer-');
		$file_type = $settings['file_type'];

		// $pdf_url = ('url' == $file_type) ? $settings['pdf_url']['url'] : '';
		$pdf_url = ('yes' == $settings['pdf_view_type'] && is_array($settings['pdf_file'])) ? $settings['pdf_file']['url'] : '';
        $json_settings = [
            'unique_id' => $unique_id,
            'pdf_url' => $pdf_url
        ];
        $this->add_render_attribute( 'pdf_viewer_container', 'data-pdf-settings', wp_json_encode( $json_settings ) );
		
		// if('url' == $file_type && !empty($pdf_url)) {
		// 	echo '<iframe src="https://docs.google.com/viewer?url=' . $pdf_url . '&amp;embedded=true" frameborder="1" marginheight="0px" marginwidth="0px" allowfullscreen></iframe>';
		// }
        ?>
        <div class="pdf_viewer_container" <?php echo $this->print_render_attribute_string('pdf_viewer_container'); ?>>
            <a href="<?php echo esc_url($pdf_url); ?>" class="button" download="true">Download</a>
            <div id="<?php echo $unique_id; ?>" style='width: 1024px; height: 600px; margin: 0 auto;'></div>
        </div>
        <?php
	}

    

}
