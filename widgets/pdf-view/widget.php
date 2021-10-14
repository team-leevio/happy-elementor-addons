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
				'label'        => __( 'PDF View Type', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
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
				'condition' => [
					'file_type' => 'url',
				]
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
				'condition' => [
					'file_type' => 'upload_file',
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

		$file_type = $settings['file_type'];

		$pdf_url = ('url' == $file_type) ? $settings['pdf_url']['url'] : '';

		
		if('url' == $file_type && !empty($pdf_url)) {
			echo '<iframe src="https://docs.google.com/viewer?url=' . $pdf_url . '&amp;embedded=true" frameborder="1" marginheight="0px" marginwidth="0px" allowfullscreen></iframe>';
		}

	}

    

}
