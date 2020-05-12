<?php
namespace HappyMonster\HappyAddons;

defined('ABSPATH') || die();

class Template_Library {

	public static function init() {
		add_action( 'elementor/editor/footer', [ __CLASS__, 'print_template_views' ] );
	}

	public static function print_template_views() {
		include_once HAPPY_ADDONS_DIR_PATH . 'templates/template-library/templates.php';
	}

	public static function get_config() {
		return [
			'libraryButton'=> 'Elements Button',
			'modalRegions'=> [
				'modalHeader'=> '.dialog-header',
				'modalContent'=> '.dialog-message'
			],
			'license'=> [
				'activated'=> true,
				'link'=> ''
			],
			'tabs'=> [
				'elementskit_page'=> [
					'title'=> 'Ready Pages',
					'data'=> [],
					'sources'=> ['elementskit-theme', 'elementskit-api'],
					'settings'=> [
						'show_title'=> true,
						'show_keywords'=> true
					]
				],
				'elementskit_header'=> [
					'title'=> 'Headers',
					'data'=> [],
					'sources'=> ['elementskit-theme', 'elementskit-api'],
					'settings'=> [
						'show_title'=> false,
						'show_keywords'=> true
					]
				],
				'elementskit_footer'=> [
					'title'=> 'Footers',
					'data'=> [],
					'sources'=> ['elementskit-theme', 'elementskit-api'],
					'settings'=> [
						'show_title'=> false,
						'show_keywords'=> true
					]
				],
				'elementskit_section'=> [
					'title'=> 'Sections',
					'data'=> [],
					'sources'=> ['elementskit-theme', 'elementskit-api'],
					'settings'=> [
						'show_title'=> false,
						'show_keywords'=> true
					]
				],
				'elementskit_widget'=> [
					'title'=> 'Widget Presets',
					'data'=> [],
					'sources'=> ['elementskit-theme', 'elementskit-api'],
					'settings'=> [
						'show_title'=> false,
						'show_keywords'=> true
					]
				],
			],
			'defaultTab'=> 'elementskit_page'
		];
	}
}
