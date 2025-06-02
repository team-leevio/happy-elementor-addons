<?php
/**
 * WPML integration and compatibility manager
 */
namespace Happy_Addons\Elementor\Classes;

defined( 'ABSPATH' ) || die();

class WPML_Manager {

	/**
	 * Recreate HappyAddons widgets usage on transtion save
	 *
	 * @param int $new_post_id
	 * @param array $fields
	 * @param object $job
	 *
	 * @return void
	 */
	public static function on_translation_job_saved( $new_post_id, $fields, $job ) {
		$elements_data = get_post_meta( $job->original_doc_id, Widgets_Cache::META_KEY, true );

		if ( ! empty( $elements_data ) ) {
			update_post_meta( $new_post_id, Widgets_Cache::META_KEY, $elements_data );

			$assets_cache = new Assets_Cache( $new_post_id );
			$assets_cache->delete();
		}
	}

	public static function load_integration_files() {
		// Load repeatable module class
		include_once( HAPPY_ADDONS_DIR_PATH . 'classes/wpml-module-with-items.php' );
	}

	public static function add_widgets_to_translate( $widgets ) {
		self::load_integration_files();

		$widgets_map = [
			/**
			 * Age Gate
			 */
			'age-gate' => [
				'fields' => [
					[
						'field'       => 'title',
						'type'        => __( 'Age Gate: Title', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'desc',
						'type'        => __( 'Age Gate: Description', 'happy-elementor-addons' ),
						'editor_type' => 'AREA',
					],
					[
						'field'       => 'button_text',
						'type'        => __( 'Age Gate: Text', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'btn_two_text',
						'type'        => __( 'Age Gate: Text', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'footer_text',
						'type'        => __( 'Age Gate: Footer Text', 'happy-elementor-addons' ),
						'editor_type' => 'AREA',
					],
					[
						'field'       => 'warning_message',
						'type'        => __( 'Age Gate: Warning Message', 'happy-elementor-addons' ),
						'editor_type' => 'AREA',
					],
				],
			],

			/**
			 * Archive Posts
			 */
			'archive-posts' => [
				'fields' => [
					[
						'field'       => 'meta_separator',
						'type'        => __( 'Archive Posts: Separator Between', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'read_more_text',
						'type'        => __( 'Archive Posts: Read More Text', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'nothing_found_message',
						'type'        => __( 'Archive Posts: Nothing Found Message', 'happy-elementor-addons' ),
						'editor_type' => 'AREA',
					],
				],
			],

			/**
			 * Bar Chart
			 */
			'bar-chart' => [
				'fields' => [
					[
						'field'       => 'labels',
						'type'        => __( 'Bar Chart: Labels', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'chart_title',
						'type'        => __( 'Bar Chart: Title', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
				],
				'integration-class' => [
					'Happy_Addons\Elementor\Wpml\Bar_Chart',
				]
			],

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
					'button_link' => [
						'field'       => 'url',
						'type'        => __( 'Card: Button Link', 'happy-elementor-addons' ),
						'editor_type' => 'LINK',
					],
				],
			],

			/**
			 * Carousel
			 */
			'carousel' => [
				'fields' => [],
				'integration-class' => [
					'Happy_Addons\Elementor\Wpml\Carousel',
				]
			],

			/**
			 * Comparison Table
			 */
			'comparison-table' => [
				'fields' => [],
				'integration-class' => [
					'Happy_Addons\Elementor\Wpml\Comparison_Table_Columns_Data',
					'Happy_Addons\Elementor\Wpml\Comparison_Table_Rows_Data',
					'Happy_Addons\Elementor\Wpml\Comparison_Table_Table_Btns',
				]
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
					'left_button_link' => [
						'field'       => 'url',
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
					'right_button_link' => [
						'field'       => 'url',
						'type'        => __( 'Dual Button: Secondary Button Link', 'happy-elementor-addons' ),
						'editor_type' => 'LINK',
					],
				],
			],

			/**
			 * Event Calendar
			 */
			'events-calendar' => [
				'fields' => [
					[
						'field'       => 'allday_text',
						'type'        => __( 'Event Calendar: All Day Text', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'readmore_text',
						'type'        => __( 'Event Calendar: Read More Text', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'time_title',
						'type'        => __( 'Event Calendar: Time Title', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'speaker_title',
						'type'        => __( 'Event Calendar: Speaker Title', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'location_title',
						'type'        => __( 'Event Calendar: Location Title', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
				],
				'integration-class' => [
					'Happy_Addons\Elementor\Wpml\Event_Calendar_Manual_Event_List',
				]
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
			 * Gradient Heading
			 */
			'gradient-heading' => [
				'fields' => [
					[
						'field'       => 'title',
						'type'        => __( 'Gradient_Heading: Title', 'happy-elementor-addons' ),
						'editor_type' => 'AREA',
					],
					'link' => [
						'field'       => 'url',
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
					'link' => [
						'field'       => 'url',
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
			 * Image Grid
			 */
			'image-grid' => [
				'fields' => [
					[
						'field'       => 'all_filter_label',
						'type'        => __( 'Image Grid: All Filter Label', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
				],
				'integration-class' => [
					'Happy_Addons\Elementor\Wpml\Image_Grid',
				]
			],

			/**
			 * Image Hover Effect
			 */
			'image-hover-effect' => [
				'fields' => [
					[
						'field'       => 'hover_image_alt_tag',
						'type'        => __( 'Image Hover Effect: Image ALT Tag', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'hover_title',
						'type'        => __( 'Image Hover Effect: Title', 'happy-elementor-addons' ),
						'editor_type' => 'AREA',
					],
					'hover_link' => [
						'field'       => 'url',
						'type'        => __( 'Image Hover Effect: Link URL', 'happy-elementor-addons' ),
						'editor_type' => 'LINK',
					],
				]
			],

			/**
			 * Image Stack Group
			 */
			'image-stack-group' => [
				'fields' => [],
				'integration-class' => [
					'Happy_Addons\Elementor\Wpml\Image_Stack_Group_Images',
				]
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
					'button_link' => [
						'field'       => 'url',
						'type'        => __( 'Info Box: Button Link', 'happy-elementor-addons' ),
						'editor_type' => 'LINK',
					],
				],
			],

			/**
			 * Justified Gallery
			 */
			'justified-gallery' => [
				'fields' => [
					[
						'field'       => 'all_filter_label',
						'type'        => __( 'Justified Grid: All Filter Label', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
				],
				'integration-class' => [
					'Happy_Addons\Elementor\Wpml\Justified_Gallery',
				]
			],

			/**
			 * Animated Link
			 */
			'link-hover' => [
				'fields' => [
					[
						'field'       => 'link_text',
						'type'        => __( 'Animated Link: Title', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					'link_url' => [
						'field'       => 'url',
						'type'        => __( 'Animated Link: Link', 'happy-elementor-addons' ),
						'editor_type' => 'LINK',
					],
				]
			],

			/**
			 * Logo Grid
			 */
			'logo-grid' => [
				'fields' => [],
				'integration-class' => [
					'Happy_Addons\Elementor\Wpml\Logo_Grid',
				]
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
			 * PDF View
			 */
			'pdf-view' => [
				'fields' => [
					[
						'field'       => 'pdf_title',
						'type'        => __( 'PDF View: PDF Title', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
				],
			],

			/**
			 * Photo Stack
			 */
			'photo-stack' => [
				'fields' => [],
				'integration-class' => [
					'Happy_Addons\Elementor\Wpml\Photo_Stack_Image_List',
				]
			],

			/**
			 * Post Info
			 */
			'post-info' => [
				'fields' => [],
				'integration-class' => [
					'Happy_Addons\Elementor\Wpml\Post_Info_Icon_List',
				]
			],

			/**
			 * Post List
			 */
			'post-list' => [
				'fields' => [],
				'integration-class' => [
					'Happy_Addons\Elementor\Wpml\Post_List_Selected_List_Post',
					'Happy_Addons\Elementor\Wpml\Post_List_Selected_List_Page',
					'Happy_Addons\Elementor\Wpml\Post_List_Selected_List_Product',
					'Happy_Addons\Elementor\Wpml\Post_List_Selected_List_E_Landing_Page',
				]
			],

			/**
			 * Post Navigation
			 */
			'post-navigation' => [
				'fields' => [
					[
						'field'       => 'prev_label',
						'type'        => __( 'Post Navigation: Previous Label', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'next_label',
						'type'        => __( 'Post Navigation: Next Label', 'happy-elementor-addons' ),
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
					'button_link' => [
						'field'       => 'url',
						'type'        => __( 'Pricing Table: Button Link', 'happy-elementor-addons' ),
						'editor_type' => 'LINK',
					],
					[
						'field'       => 'badge_text',
						'type'        => __( 'Pricing Table: Badge Text', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
				],
				'integration-class' => [
					'Happy_Addons\Elementor\Wpml\Pricing_Table',
				]
			],

			/**
			 * Review
			 */
			'review' => [
				'fields' => [
					[
						'field'       => 'review',
						'type'        => __( 'Review: Review Text', 'happy-elementor-addons' ),
						'editor_type' => 'AREA',
					],
					[
						'field'       => 'title',
						'type'        => __( 'Review: Reviewer Name', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'job_title',
						'type'        => __( 'Review: Job Title', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
				],
			],

			/**
			 * Skills
			 */
			'skills' => [
				'fields' => [],
				'integration-class' => [
					'Happy_Addons\Elementor\Wpml\Skills',
				]
			],

			/**
			 * Slider
			 */
			'slider' => [
				'fields' => [],
				'integration-class' => [
					'Happy_Addons\Elementor\Wpml\Slider',
				]
			],

			/**
			 * Social Icons
			 */
			'social-icons' => [
				'fields' => [],
				'integration-class' => [
					'Happy_Addons\Elementor\Wpml\Social_Icons',
				]
			],

			/**
			 * Social Share
			 */
			'social-share' => [
				'fields' => [],
				'integration-class' => [
					'Happy_Addons\Elementor\Wpml\Social_Share',
				]
			],

			/**
			 * Step Flow
			 */
			'step-flow' => [
				'fields' => [
					[
						'field'       => 'badge',
						'type'        => __( 'Step Flow: Badge Text', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'title',
						'type'        => __( 'Step Flow: Title', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'description',
						'type'        => __( 'Step Flow: Description', 'happy-elementor-addons' ),
						'editor_type' => 'AREA',
					],
					'link' => [
						'field'       => 'url',
						'type'        => __( 'Step Flow: Link', 'happy-elementor-addons' ),
						'editor_type' => 'LINK',
					],
				],
			],

			/**
			 * Taxonomy List
			 */
			'taxonomy-list' => [
				'fields' => [],
				'integration-class' => [
					'Happy_Addons\Elementor\Wpml\Taxonomy_List_Selected_List_Category',
					'Happy_Addons\Elementor\Wpml\Taxonomy_List_Selected_List_Post_Tag',
					'Happy_Addons\Elementor\Wpml\Taxonomy_List_Selected_List_Post_Format',
					'Happy_Addons\Elementor\Wpml\Taxonomy_List_Selected_List_Product_Cat',
					'Happy_Addons\Elementor\Wpml\Taxonomy_List_Selected_List_Product_Tag',
					'Happy_Addons\Elementor\Wpml\Taxonomy_List_Selected_List_Product_Shipping_Class',
				]
			],

			/**
			 * Testimonial
			 */
			'testimonial' => [
				'fields' => [
					[
						'field'       => 'testimonial',
						'type'        => __( 'Testimonial: Testimonial Text', 'happy-elementor-addons' ),
						'editor_type' => 'AREA',
					],
					[
						'field'       => 'name',
						'type'        => __( 'Testimonial: Reviewer Name', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'title',
						'type'        => __( 'Testimonial: Job Title', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
				],
			],

			/**
			 * Twitter Feed
			 */
			'twitter-feed' => [
				'fields' => [
					[
						'field'       => 'read_more_text',
						'type'        => __( 'Twitter Feed: Read More Text', 'happy-elementor-addons' ),
						'editor_type' => 'AREA',
					],
					[
						'field'       => 'load_more_text',
						'type'        => __( 'Twitter Feed: Load More Text', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
				],
			],

			/**
			 * Data table
			 */
			'data-table' => [
				'fields' => [],
				'integration-class' => [
					'Happy_Addons\Elementor\Wpml\Data_Table_Column_Data',
					'Happy_Addons\Elementor\Wpml\Data_Table_Row_Data',
				]
			],

			/**
			 * Horizontal Timeline
			 */
			'horizontal-timeline' => [
				'fields' => [],
				'integration-class' => [
					'Happy_Addons\Elementor\Wpml\Horizontal_Timeline',
				]
			],


			/**
			 * Mailchimp
			 */
			'mailchimp' => [
				'fields' => [
					[
						'field'       => 'fname_label',
						'type'        => __( 'MailChimp: First Name Label', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'fname_placeholder',
						'type'        => __( 'MailChimp: First Name Place Holder', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'lname_label',
						'type'        => __( 'MailChimp: Last Name Label', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'lname_placeholder',
						'type'        => __( 'MailChimp: Last Name Place Holder', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'phone_label',
						'type'        => __( 'MailChimp: Phone Label', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'phone_placeholder',
						'type'        => __( 'MailChimp: Phone Place Holder', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'email_label',
						'type'        => __( 'MailChimp: Email Label', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'email_placeholder',
						'type'        => __( 'MailChimp: Email Place Holder', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'button_text',
						'type'        => __( 'MailChimp: Button Text', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'mailchimp_success_message',
						'type'        => __( 'MailChimp: Success Message', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
				],
			],

			/**
			 * Image Accordion
			 */
			'image-accordion' => [
				'fields' => [],
				'integration-class' => [
					'Happy_Addons\Elementor\Wpml\Image_Accordion',
				]
			],

			/*
			 * Content Switcher
			 */
			'content-switcher' => [
				'fields' => [],
				'integration-class' => 'Happy_Addons\Elementor\Wpml\Content_Switcher',
			],

			/**
			 * Creative Button
			 */
			'creative-button' => [
				'fields' => [
					[
						'field'       => 'button_text',
						'type'        => __( 'Creative Button: Text', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					'button_link' => [
						'field'       => 'url',
						'type'        => __( 'Creative Button: Link', 'happy-elementor-addons' ),
						'editor_type' => 'LINK',
					],
				],
			],

			/**
			 * Lightbox
			 */
			'lightbox' => [
				'fields' => [
					[
						'field'       => 'button',
						'type'        => __( 'Lightbox: Button', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					'youtube_link' => [
						'field'       => 'url',
						'type'        => __( 'Lightbox: Link', 'happy-elementor-addons' ),
						'editor_type' => 'LINK',
					],
					'vimeo_link' => [
						'field'       => 'url',
						'type'        => __( 'Lightbox: Link', 'happy-elementor-addons' ),
						'editor_type' => 'LINK',
					],
				],
			],

			/**
			 * Liquid Hover Image
			 */
			'liquid-hover-image' => [
				'fields' => [
					'link' => [
						'field'       => 'url',
						'type'        => __( 'Liquid Hover Image: Link', 'happy-elementor-addons' ),
						'editor_type' => 'LINK',
					],
					[
						'field'       => 'title',
						'type'        => __( 'Liquid Hover Image: Title', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					],
					[
						'field'       => 'sub_title',
						'type'        => __( 'Liquid Hover Image: Sub Title', 'happy-elementor-addons' ),
						'editor_type' => 'LINE',
					]
				],
			],

			/**
			 * Text Scroll
			 */
			'text-scroll' => [
				'fields' => [
					[
						'field'       => 'scroll_text',
						'type'        => __( 'Text Scroll: Scroll Text', 'happy-elementor-addons' ),
						'editor_type' => 'AREA',
					],
				],
			],
		];

		foreach ( $widgets_map as $key => $data ) {
			$widget_name = 'ha-'.$key;

			$entry = [
				'conditions' => [
					'widgetType' => $widget_name,
				],
				'fields' => $data['fields'],
			];

			if ( isset( $data['integration-class'] ) ) {
				$entry['integration-class'] = $data['integration-class'];
			}

			$widgets[ $widget_name ] = $entry;
		}

		return $widgets;
	}
}
