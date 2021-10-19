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
			'important_note',
			[
				'label' => __( 'Important Note', 'happy-elementor-addons' ),
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'To remove watermark in PDFjs.express. please signup and get free license key. <a href="https://pdfjs.express/signup">Sign up</a>', 'happy-elementor-addons' ),
				'content_classes' => 'elementor-control-field-description',
                'condition' => [
					'pdf_view_type' => 'yes',
				]

			]
		);

        $this->add_control(
			'pdf_license',
			[
				'label' => __( 'PDFjs.express License', 'happy-elementor-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'MBgnIWi14J', 'happy-elementor-addons' ),
				'condition' => [
					'pdf_view_type' => 'yes',
				]

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
                'default' => [
                    'url' =>  'http://www.africau.edu/images/default/sample.pdf'
                ],
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
                'default' => [
                    'url' => HAPPY_ADDONS_ASSETS . '/vendor/pdfjs/sample.pdf'
                ],
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
        
        $this->add_control(
			'pdf_title',
			[
				'label' => __( 'PDF Title', 'happy-elementor-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'PDF Title', 'happy-elementor-addons' ),
				'placeholder' => __( 'Type PDF title', 'happy-elementor-addons' ),
				'separator' => 'before',
				'dynamic' => [
					'active' => true,
				]
			]
		);

        $this->add_control(
			'enable_download',
			[
				'label'        => __( 'Download', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			]
		);

        $this->add_responsive_control(
            'pdf_height',
            [
                'label'      => __('Height', 'happy-elementor-addons'),
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
            ]
        );

        $this->add_responsive_control(
            'pdf_width',
            [
                'label'      => __('Width', 'happy-elementor-addons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['%','px',],
                'range'      => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min'  => 0,
                        'max'  => 2000,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'size' => 100,
                    'unit' => '%',
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

		// $pdf_url = ('yes' == $settings['pdf_view_type'] && is_array($settings['pdf_file'])) ? $settings['pdf_file']['url'] : '';
        $pdf_url_i = '';
        if('url' == $file_type){
            $pdf_url_i =  $settings['pdf_url']['url'];
        }else{
            $pdf_url_i =  $settings['pdf_file']['url'];
        }
        $json_settings = [
            'unique_id' => $unique_id,
            'pdf_url' => $pdf_url_i,
            'license' => (! empty($settings['pdf_license']) ) ? $settings['pdf_license'] : ''
        ];
        $this->add_render_attribute( 'pdf_viewer_container', 'data-pdf-settings', wp_json_encode( $json_settings ) );
        ?>
        <div class="pdf_viewer_container" <?php echo $this->print_render_attribute_string('pdf_viewer_container'); ?>>
            <div class="pdf_viewer_options">
            <?php if($settings['pdf_title']){
                printf( '<h2>%s</h2>',
                    esc_html( $settings['pdf_title'] )
                );
            }
             if('yes' == $settings['enable_download']){
                printf( '<a href="%1$s" class="ha-btn" download title="%2$s">%3$s</a>',
                    esc_url($pdf_url_i),
                    esc_html( $settings['pdf_title'] ),
                    __('Download', 'happy-elementor-addons')
                );
            }

            ?>
            </div>
            <?php if('yes' ==  $settings['pdf_view_type']) : 
                printf( '<div id="%1$s" style="height:%2$s; width:%3$s"></div>',
                        esc_attr( $unique_id ),
                        esc_attr($settings['pdf_height']['size'].$settings['pdf_height']['unit']),
                        esc_attr($settings['pdf_width']['size'].$settings['pdf_width']['unit']),

                );
            else:
                echo '<iframe src="//docs.google.com/viewer?url=' . $pdf_url_i . '&amp;embedded=true" frameborder="1" marginheight="0px" marginwidth="0px" height="'.$settings['pdf_height']['size'].$settings['pdf_height']['unit'].'" allowfullscreen></iframe>';
            endif; ?>
        </div>
        <?php
	}

    

}
