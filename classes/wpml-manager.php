<?php
/**
 * WPML integration and compatibility manager
 */
namespace Happy_Addons\Elementor;

defined( 'ABSPATH' ) || die();

class WPML_Manager {

	public static function init() {
		add_filter( 'wpml_elementor_widgets_to_translate', [ __CLASS__, 'add_widgets_to_translate' ] );
	}

	public static function add_widgets_to_translate( $widgets ) {
		$widgets_map = [
			/**
			 * Card
			 */
			'card' => [
				'fields' => [
					[
						'field'       => 'badge_text',
						'type'        => __( 'Card: Badge Text', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'title',
						'type'        => __( 'Card: Title', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'description',
						'type'        => __( 'Card: Description', 'happy-elementor-addons' ),
						'editor_type' => 'AREA'
					],
					[
						'field'       => 'button_text',
						'type'        => __( 'Card: Button Text', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'button_link',
						'type'        => __( 'Card: Button Link', 'happy-elementor-addons' ),
						'editor_type' => 'LINK',
					],
				],
			],

			/**
			 * Dual Button
			 */
			'dual-button' => [
				'fields' => [
					[
						'field'       => 'left_button_text',
						'type'        => __( 'Dual Button: Primary Button Text', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'left_button_link',
						'type'        => __( 'Dual Button: Primary Button Link', 'happy-elementor-addons' ),
						'editor_type' => 'LINK',
					],
					[
						'field'       => 'button_connector_text',
						'type'        => __( 'Dual Button: Connector Text', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'right_button_text',
						'type'        => __( 'Dual Button: Secondary Button Text', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'right_button_link',
						'type'        => __( 'Dual Button: Secondary Button Link', 'happy-elementor-addons' ),
						'editor_type' => 'LINK',
					],
				],
			],

			/**
			 * Flip Box
			 */
			'flip-box' => [
				'fields' => [
					[
						'field'       => 'front_title',
						'type'        => __( 'Flip Box: Front Title', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'front_description',
						'type'        => __( 'Flip Box: Front Description', 'happy-elementor-addons' ),
						'editor_type' => 'AREA',
					],
					[
						'field'       => 'back_title',
						'type'        => __( 'Flip Box: Back Title', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'back_description',
						'type'        => __( 'Flip Box: Back Description', 'happy-elementor-addons' ),
						'editor_type' => 'AREA',
					],
				],
			],

			/**
			 * Fun Factor
			 */
			'fun-factor' => [
				'fields' => [
					[
						'field'       => 'fun_factor_title',
						'type'        => __( 'Fun Factor: Title', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
				],
			],

			/**
			 * Fun Factor
			 */
			'gradient-heading' => [
				'fields' => [
					[
						'field'       => 'title',
						'type'        => __( 'Gradient_Heading: Title', 'happy-elementor-addons' ),
						'editor_type' => 'AREA',
					],
					[
						'field'       => 'link',
						'type'        => __( 'Gradient_Heading: Link', 'happy-elementor-addons' ),
						'editor_type' => 'LINK',
					],
				],
			],

			/**
			 * Icon Box
			 */
			'icon-box' => [
				'fields' => [
					[
						'field'       => 'title',
						'type'        => __( 'Icon Box: Title', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'badge_text',
						'type'        => __( 'Icon Box: Badge Text', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'link',
						'type'        => __( 'Icon Box: Link', 'happy-elementor-addons' ),
						'editor_type' => 'LINK',
					],
				],
			],

			/**
			 * Image Compare
			 */
			'image-compare' => [
				'fields' => [
					[
						'field'       => 'before_label',
						'type'        => __( 'Image Compare: Before Label', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'after_label',
						'type'        => __( 'Image Compare: After Label', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
				],
			],

			/**
			 * Info Box
			 */
			'infobox' => [
				'fields' => [
					[
						'field'       => 'title',
						'type'        => __( 'Info Box: Title', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'description',
						'type'        => __( 'Info Box: Description', 'happy-elementor-addons' ),
						'editor_type' => 'AREA',
					],
					[
						'field'       => 'button_text',
						'type'        => __( 'Info Box: Button Text', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'button_link',
						'type'        => __( 'Info Box: Button Link', 'happy-elementor-addons' ),
						'editor_type' => 'LINK',
					],
				],
			],

			/**
			 * Team Member
			 */
			'member' => [
				'fields' => [
					[
						'field'       => 'title',
						'type'        => __( 'Team Member: Name', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'job_title',
						'type'        => __( 'Team Member: Job Title', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'bio',
						'type'        => __( 'Team Member: Short Bio', 'happy-elementor-addons' ),
						'editor_type' => 'AREA',
					],
				],
			],

			/**
			 * News Ticker
			 */
			'news-ticker' => [
				'fields' => [
					[
						'field'       => 'sticky_title',
						'type'        => __( 'News Ticker: Sticky Title', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
				],
			],

			/**
			 * Number
			 */
			'number' => [
				'fields' => [
					[
						'field'       => 'number_text',
						'type'        => __( 'Number: Text', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
				],
			],

			/**
			 * Pricing Table
			 */
			'pricing-table' => [
				'fields' => [
					[
						'field'       => 'title',
						'type'        => __( 'Pricing Table: Title', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'price',
						'type'        => __( 'Pricing Table: Price', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'period',
						'type'        => __( 'Pricing Table: Period', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'features_title',
						'type'        => __( 'Pricing Table: Features Title', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'button_text',
						'type'        => __( 'Pricing Table: Button Text', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'button_link',
						'type'        => __( 'Pricing Table: Button Link', 'happy-elementor-addons' ),
						'editor_type' => 'LINK',
					],
					[
						'field'       => 'badge_text',
						'type'        => __( 'Pricing Table: Badge Text', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
				],
			],
		];

		foreach ( $widgets_map as $key => $data ) {
			$widget_name = 'ha-'.$key;

			$widgets[ $widget_name ] = [
				'conditions' => [
					'widgetType' => $widget_name,
				],
				'fields' => $data['fields'],
			];
		}

		return $widgets;
	}
}
