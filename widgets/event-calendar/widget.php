<?php
/**
 * Event Calendar widget class
 *
 * @package Happy_Addons
 */

namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Repeater;

defined( 'ABSPATH' ) || die();

class Event_Calendar extends Base {

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title () {
		return __( 'Event Calendar', 'happy-elementor-addons' );
	}

	public function get_custom_help_url() {
		return 'https://happyaddons.com/docs/happy-addons-for-elementor/widgets/event-calendar/';
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon () {
		return 'hm hm-calendar2';
	}

	public function get_keywords () {
		return [ 'event-calendar', 'event', 'calender', 'time', 'shedule', 'google-calender' ];
	}

	/**
	 * Get a list of all WPForms
	 *
	 * @return array
	 */
	public static function get_events () {
		if (!function_exists('tribe_get_events')) {
            return [];
        }
		$posts = [];
		// $_posts = get_posts( [
		// 	'post_type' => 'post',
		// 	'post_status' => 'publish',
		// 	'posts_per_page' => -1,
		// 	'orderby' => 'title',
		// 	'order' => 'ASC',
		// ] );

		$_posts = tribe_get_events();

		if ( ! empty( $_posts ) ) {
			$posts = wp_list_pluck( $_posts, 'post_title', 'ID' );
		}
		return $posts;

	}

	/**
	 * Get the event calender category list
	 *
	 * @return array
	 */
	public static function get_the_event_calendar_cat() {
		if (!function_exists('tribe_get_events')) {
            return [];
        }
		$args = [
			'taxonomy' => 'tribe_events_cat',
			'hide_empty' => false
		];
        $options = [];
        $tags = get_tags($args);

        if (is_wp_error($tags)) {
            return [];
        }

        foreach ($tags as $tag) {
            $options[$tag->term_id] = $tag->name;
        }
        return $options;
    }

	/**
	 * Get a language code
	 *
	 * @return array
	 */
	protected function language_code_list() {
        return [
            'af' => 'Afrikaans',
            'sq' => 'Albanian',
            'ar' => 'Arabic',
            'eu' => 'Basque',
            'bn' => 'Bengali',
            'bs' => 'Bosnian',
            'bg' => 'Bulgarian',
            'ca' => 'Catalan',
            'zh-cn' => 'Chinese',
            'zh-tw' => 'Chinese-tw',
            'hr' => 'Croatian',
            'cs' => 'Czech',
            'da' => 'Danish',
            'nl' => 'Dutch',
            'en' => 'English',
            'et' => 'Estonian',
            'fi' => 'Finnish',
            'fr' => 'French',
            'gl' => 'Galician',
            'ka' => 'Georgian',
            'de' => 'German',
            'el' => 'Greek (Modern)',
            'he' => 'Hebrew',
            'hi' => 'Hindi',
            'hu' => 'Hungarian',
            'is' => 'Icelandic',
            'io' => 'Ido',
            'id' => 'Indonesian',
            'it' => 'Italian',
            'ja' => 'Japanese',
            'kk' => 'Kazakh',
            'ko' => 'Korean',
            'lv' => 'Latvian',
            'lb' => 'Letzeburgesch',
            'lt' => 'Lithuanian',
            'lu' => 'Luba-Katanga',
            'mk' => 'Macedonian',
            'mg' => 'Malagasy',
            'ms' => 'Malay',
            'ro' => 'Moldovan, Moldavian, Romanian',
            'nb' => 'Norwegian Bokmål',
            'nn' => 'Norwegian Nynorsk',
            'fa' => 'Persian',
            'pl' => 'Polish',
            'pt' => 'Portuguese',
            'ru' => 'Russian',
            'sr' => 'Serbian',
            'sk' => 'Slovak',
            'sl' => 'Slovenian',
            'es' => 'Spanish',
            'sv' => 'Swedish',
            'tr' => 'Turkish',
            'uk' => 'Ukrainian',
            'vi' => 'Vietnamese',
        ];
    }

	protected function register_content_controls () {

		$this->event_content_controls();

		$this->event_google_content_controls();

		$this->the_event_calendar_content_controls();

		$this->event_settings_content_controls();
	}

	protected function event_content_controls () {

		$this->start_controls_section(
			'_section_event',
			[
				'label' => __( 'Event', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'event_calendar_type',
			[
				'label' => __( 'Source', 'happy-elementor-addons' ),
				'label_block' => false,
				'type' => Controls_Manager::SELECT,
				'default' => 'manual',
				'options' => [
					'manual' =>  __( 'Manual', 'happy-elementor-addons' ),
					'google_calendar' =>  __( 'Google Calendar', 'happy-elementor-addons' ),
					'the_events_calendar' =>  __( 'The Event Calendar', 'happy-elementor-addons' ),
				],
				// 'multiple' => true,
			]
		);

		$repeater = new Repeater();
		$repeater->start_controls_tabs('event_calendar_tabs');

        $repeater->start_controls_tab(
            'event_calendar_content_tab',
            [
                'label' => __('Content', 'happy-elementor-addons'),
            ]
		);

		$repeater->add_control(
			'title',
			[
				'label' => __('Title', 'happy-elementor-addons'),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __('Event Title', 'happy-elementor-addons'),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$repeater->add_control(
			'guest',
			[
				'label' => __('Guest/Speaker', 'happy-elementor-addons'),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __('Hasin Hayder', 'happy-elementor-addons'),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$repeater->add_control(
			'location',
			[
				'label' => __('Location', 'happy-elementor-addons'),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __('Mirpur DOHS, Dhaka', 'happy-elementor-addons'),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$repeater->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'happy-elementor-addons' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
            'details_link',
            [
                'label'         => __('Details Link', 'happy-elementor-addons'),
                'type'          => Controls_Manager::URL,
                'placeholder'   => __('https://example.com', 'happy-elementor-addons'),
                'show_external' => true,
            ]
		);

        $repeater->add_control(
            'all_day',
            [
                'label'        => __('All Day', 'happy-elementor-addons'),
                'type'         => Controls_Manager::SWITCHER,
                'label_block'  => false,
                'return_value' => 'yes',
            ]
        );

        $repeater->add_control(
            'start_date',
            [
                'label'     => __('Start Date', 'happy-elementor-addons'),
                'type'      => Controls_Manager::DATE_TIME,
                'default'   => date('Y-m-d H:i', current_time('timestamp', 0)),
                'condition' => [
                    'all_day' => '',
                ],
            ]
        );

        $repeater->add_control(
            'end_date',
            [
                'label'     => __('End Date', 'happy-elementor-addons'),
                'type'      => Controls_Manager::DATE_TIME,
                'default'   => date('Y-m-d H:i', strtotime("+59 minute", current_time('timestamp', 0))),
                'condition' => [
                    'all_day' => '',
                ],
            ]
        );

        $repeater->add_control(
            'start_date_allday',
            [
                'label'          => __('Start Date', 'happy-elementor-addons'),
                'type'           => Controls_Manager::DATE_TIME,
                'picker_options' => ['enableTime' => false],
                'default'        => date('Y-m-d', current_time('timestamp', 0)),
                'condition'      => [
                    'all_day' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'end_date_allday',
            [
                'label'          => __('End Date', 'happy-elementor-addons'),
                'type'           => Controls_Manager::DATE_TIME,
                'picker_options' => ['enableTime' => false],
                'default'        => date('Y-m-d', current_time('timestamp', 0)),
                'condition'      => [
                    'all_day' => 'yes',
                ],
            ]
		);

		$repeater->add_control(
			'individual_style',
			[
				'label' => __('Individual Style?', 'happy-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'happy-elementor-addons'),
				'label_off' => __('No', 'happy-elementor-addons'),
				'return_value' => 'yes',
				'default' => 'no',
                'style_transfer' => true,
				'separator' => 'after',
			]
		);

		$repeater->add_control(
			'text_color',
			[
				'label' => __('Text Color', 'happy-elementor-addons'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-ec-wrapper {{CURRENT_ITEM}}' => 'color: {{VALUE}}!important;',
					'{{WRAPPER}} .ha-ec-wrapper {{CURRENT_ITEM}} .fc-event-main' => 'color: {{VALUE}}!important;',
				],
				'condition' => [
					'individual_style' => 'yes',
                    // 'all_day' => 'yes',
				],
                'style_transfer' => true,
                // 'render_type' => 'template',
			]
		);

		$repeater->add_control(
			'bg_color',
			[
				'label' => __('Background', 'happy-elementor-addons'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-ec-wrapper {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}!important;',
				],
				'condition' => [
					'individual_style' => 'yes',
                    // 'all_day' => 'yes',
				],
                'style_transfer' => true,
                // 'render_type' => 'template',
			]
		);

		$repeater->add_control(
			'border_color',
			[
				'label' => __('Dot Color', 'happy-elementor-addons'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-ec-wrapper {{CURRENT_ITEM}} .fc-daygrid-event-dot' => 'border-color: {{VALUE}}!important;',
					'{{WRAPPER}} .ha-ec-wrapper {{CURRENT_ITEM}} .fc-list-event-dot' => 'border-color: {{VALUE}}!important;',
				],
				'condition' => [
					'individual_style' => 'yes'
				],
                'style_transfer' => true,
                // 'render_type' => 'template',
			]
		);

		$repeater->end_controls_tab();

        $repeater->start_controls_tab(
            'event_calendar_description_tab',
            [
                'label' => __('Description', 'happy-elementor-addons'),
            ]
		);

        $repeater->add_control(
            'description',
            [
                'label' => __('Description', 'happy-elementor-addons'),
                'show_label' => true,
                'type'  => Controls_Manager::WYSIWYG,
            ]
        );


		$repeater->end_controls_tab();
		$repeater->end_controls_tabs();

		$this->add_control(
			'manual_event_list',
			[
				'show_label' => false,
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default' => [
					[
						'title' => __('Event Title', 'happy-elementor-addons'),
					],
				],
				'condition' => [
					'event_calendar_type' => 'manual'
				],
			]
		);

		$this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'thumbnail',
                'separator' => 'before',
                'exclude' => [
                    'custom'
				],
				'condition' => [
					'event_calendar_type' => 'manual'
				],
            ]
		);

		if ( !class_exists( 'Tribe__Events__Main' ) ) {
			$this->add_control(
				'the_event_calendar_warning_text',
				[
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => __('<strong>The Events Calendar</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=the-events-calendar&tab=search&type=term" target="_blank">The Events Calendar</a> first.',
						'essential-addons-for-elementor'),
					'content_classes' => 'eael-warning',
					'condition' => [
						'event_calendar_type' => 'the_events_calendar',
					],
				]
			);
		}
		$this->end_controls_section();
	}

	protected function event_google_content_controls () {

		$this->start_controls_section(
            '_section_event_google_calendar',
            [
                'label'     => __('Google Calendar', 'happy-elementor-addons'),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'event_calendar_type' => 'google_calendar',
                ],
            ]
        );

        $this->add_control(
            'google_calendar_api_key',
            [
                'label'       => __('API Key', 'happy-elementor-addons'),
                'label_block' => true,
                'type'        => Controls_Manager::TEXT,
                'default' => 'AIzaSyDiXYw6nDZIE6RzqIy2WNZxvrze73smMo0',
                //'description' => sprintf(__('<a href="#" class="eael-btn" target="_blank">%s</a>','happy-elementor-addons'), 'Get API Key'),
            ]
        );

        $this->add_control(
            'google_calendar_id',
            [
                'label'       => __('Calendar ID', 'happy-elementor-addons'),
                'label_block' => true,
                'type'        => Controls_Manager::TEXT,
                'default' => 'en.bd#holiday@group.v.calendar.google.com',
                //'description' => sprintf(__('<a href="#" target="_blank">%s</a>','happy-elementor-addons'), 'Get google calendar ID'),
            ]
        );

        $this->add_control(
            'google_calendar_start_date',
            [
                'label'   => __('Start Date', 'happy-elementor-addons'),
                'type'    => Controls_Manager::DATE_TIME,
                'default' => date('Y-m-d H:i', current_time('timestamp', 0)),
            ]
        );

        $this->add_control(
            'google_calendar_end_date',
            [
                'label'   => __('End Date', 'happy-elementor-addons'),
                'type'    => Controls_Manager::DATE_TIME,
                'default' => date('Y-m-d H:i', strtotime("+6 months", current_time('timestamp', 0))),
            ]
        );

        $this->add_control(
            'google_calendar_max_item',
            [
                'label'   => __('Item Number', 'happy-elementor-addons'),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 1,
                'default' => 100,
            ]
        );

        $this->end_controls_section();
	}

	protected function the_event_calendar_content_controls () {

		//the events calendar
        if ( class_exists( 'Tribe__Events__Main' ) ) {

			$this->start_controls_section(
				'_section_the_events_calendar',
				[
					'label'     => __('The Event Calendar', 'happy-elementor-addons'),
					'tab'       => Controls_Manager::TAB_CONTENT,
					'condition' => [
						'event_calendar_type' => 'the_events_calendar',
					],
				]
			);

			$this->add_control(
				'the_events_calendar_source',
				[
					'label'       => __('Get Events By', 'happy-elementor-addons'),
					'type'        => Controls_Manager::SELECT,
					'label_block' => true,
					'default'     => ['all'],
					'options'     => [
						'all'        => __('All Event', 'happy-elementor-addons'),
						'category'        => __('By Category', 'happy-elementor-addons'),
						'selected_event' => __('Selected Event', 'happy-elementor-addons'),
					],
					// 'render_type' => 'none',
				]
			);


			$this->add_control(
				'the_events_calendar_category',
				[
					'label'       => __('Event Category', 'happy-elementor-addons'),
					'label_block' => true,
					'type'        => Controls_Manager::SELECT2,
					'multiple'    => true,
					'options'     => self::get_the_event_calendar_cat(),
					'condition' => [
						'the_events_calendar_source' => 'category',
					],
				]
			);

			$this->add_control(
				'the_events_calendar_selected',
				[
					'label'       => __('Select Events', 'happy-elementor-addons'),
					'label_block' => true,
					'type'        => Controls_Manager::SELECT2,
					'multiple'    => true,
					'options'     => self::get_events(),
					'condition' => [
						'the_events_calendar_source' => 'selected_event',
					],
				]
			);

			$this->add_control(
				'the_events_calendar_item',
				[
					'label'   => __('Event Item', 'happy-elementor-addons'),
					'type'    => Controls_Manager::NUMBER,
					'min'     => 1,
					'default' => 12,
					'condition' => [
						'the_events_calendar_source!' => 'selected_event',
					],
				]
			);

			$this->end_controls_section();
		}

	}

	protected function event_settings_content_controls () {

		$this->start_controls_section(
			'_section_event_settings',
			[
				'label' => __( 'Settings', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		/* $this->add_control(
            'details_link_hide',
            [
                'label'        => __('Hide Event Details Link', 'happy-elementor-addons'),
                'label_block'  => false,
                'type'         => Controls_Manager::SWITCHER,
                'description'  => __('Hide Event Details link in event popup','happy-elementor-addons'),
				'return_value' => 'yes',
                'condition' => [
                    'event_calendar_type!' => 'manual',
                ],
            ]
		); */

		$this->add_control(
            'show_event_popup',
            [
                'label'        => __('Show Event Popup', 'happy-elementor-addons'),
                'label_block'  => false,
                'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
                // 'condition' => [
                //     'event_calendar_type!' => 'manual',
                // ],
            ]
		);

		$this->add_control(
            'language',
            [
                'label'   => __('Language', 'happy-elementor-addons'),
                'type'    => Controls_Manager::SELECT,
				'options' => [
					'manual' =>  __( 'Manual', 'happy-elementor-addons' ),
					'google_calendar' =>  __( 'Google Calendar', 'happy-elementor-addons' ),
				],
				'options' => $this->language_code_list(),
                'default' => 'en'
            ]
        );

        $this->add_control(
            'default_view',
            [
                'label'   => __('Calendar Default View', 'happy-elementor-addons'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'timeGridDay'  => __('Day', 'happy-elementor-addons'),
                    'timeGridWeek' => __('Week', 'happy-elementor-addons'),
                    'dayGridMonth' => __('Month', 'happy-elementor-addons'),
                    'listMonth'    => __('List', 'happy-elementor-addons'),
                ],
                'default' => 'dayGridMonth',
            ]
        );

        $this->add_control(
            'event_calendar_first_day',
            [
                'label'   => __('First Day of Week', 'happy-elementor-addons'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    '0' => __('Sunday', 'happy-elementor-addons'),
                    '1' => __('Monday', 'happy-elementor-addons'),
                    '2' => __('Tuesday', 'happy-elementor-addons'),
                    '3' => __('Wednesday', 'happy-elementor-addons'),
                    '4' => __('Thursday', 'happy-elementor-addons'),
                    '5' => __('Friday', 'happy-elementor-addons'),
                    '6' => __('Saturday', 'happy-elementor-addons'),
                ],
                'default' => '0',
            ]
		);

		$this->end_controls_section();
	}


	/*
	* Register Style control
	*/
	protected function register_style_controls () {

		$this->calendar_style_controls();

		$this->topbar_style_controls();

		$this->event_style_controls();

		$this->popup_style_controls();

	}

	protected function calendar_style_controls () {

		$this->start_controls_section(
			'_section_style_calendar_wrapper',
			[
				'label' => __( 'Calendar', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'calendar_typography',
				'label'    => __('Calendar Font Family', 'happy-elementor-addons'),
				'include' => [
					'font_family',
				],
                'selector' => '{{WRAPPER}} .ha-ec-wrapper * :not(i),{{WRAPPER}} .ha-ec-popup-wrapper * :not(i)',
            ]
		);

		$this->add_control(
            'calendar_background_color',
            [
                'label'     => __('Background', 'happy-elementor-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-ec-wrapper .fc-view > table'=> 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .ha-ec-wrapper .fc-view table.fc-list-table'=> 'background-color: {{VALUE}}',
                    // '{{WRAPPER}} .ha-ec-wrapper table tbody tr td:not(.fc-timegrid-slot):not(.fc-timegrid-col),
					// {{WRAPPER}} .ha-ec-wrapper table tbody tr td,
					// {{WRAPPER}} .ha-ec-wrapper table tbody tr th' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'calendar_border_color',
            [
                'label'     => __('Border Color', 'happy-elementor-addons'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#CFCFDA',
                'selectors' => [
                    '{{WRAPPER}} .ha-ec-wrapper table thead:first-child tr:first-child th,
					{{WRAPPER}} .ha-ec-wrapper .fc-theme-standard .fc-scrollgrid,
					{{WRAPPER}} .ha-ec-wrapper .fc-theme-standard .fc-list,
					{{WRAPPER}} .ha-ec-wrapper .fc-theme-standard td,
					{{WRAPPER}} .ha-ec-wrapper .fc-theme-standard th' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'calendar_box_shadow',
                'label'    => __('Box Shadow', 'happy-elementor-addons'),
                'selector' => '{{WRAPPER}} .ha-ec-wrapper .fc-view table.fc-scrollgrid,
				{{WRAPPER}} .ha-ec-wrapper .fc-view table.fc-list-table',
            ]
		);

		$this->add_control(
			'calendar_todays_background',
			[
				'label'     => __('Today\'s Background', 'happy-elementor-addons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-ec-wrapper .fc .fc-daygrid-day.fc-day-today' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .ha-ec-wrapper .fc .fc-timegrid-col.fc-day-today' => 'background-color: {{VALUE}};',
					// '{{WRAPPER}} .ha-ec-wrapper .fc .fc-list-event.fc-event-today' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'calendar_heading_heading',
			[
				'label' => __('Calendar Heading', 'happy-elementor-addons'),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
            'calendar_heading_padding',
            [
                'label'      => __('Padding', 'happy-elementor-addons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .ha-ec-wrapper th.fc-col-header-cell.fc-day' => 'Padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ha-ec-wrapper .fc .fc-list-table th .fc-list-day-cushion' => 'Padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        /* $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'calendar_heading_typography',
				'label'    => __('Typography', 'happy-elementor-addons'),
				'exclude' => [
					'font_family',
				],
				'selector' => '{{WRAPPER}} .ha-ec-wrapper .fc .fc-list-table th .fc-list-day-cushion,
								{{WRAPPER}} .ha-ec-wrapper th.fc-col-header-cell.fc-day',
            ]
		); */

        $this->add_responsive_control(
			'calendar_heading_font_size',
			[
				'label' => __( 'Font Size', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ha-ec-wrapper .fc .fc-list-table th .fc-list-day-cushion' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ha-ec-wrapper th.fc-col-header-cell.fc-day' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'calendar_heading_color',
            [
                'label'     => __('Color', 'happy-elementor-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-ec-wrapper .fc .fc-list-table th .fc-list-day-cushion' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ha-ec-wrapper th.fc-col-header-cell.fc-day' => 'color: {{VALUE}};',
                ],
            ]
        );

		$this->add_control(
			'calendar_heading_background',
			[
				'label'     => __('Background', 'happy-elementor-addons'),
				'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-ec-wrapper th.fc-col-header-cell.fc-day' => 'background-color: {{VALUE}};',
                ],
			]
		);

		$this->add_control(
			'calendar_date_and_time_heading',
			[
				'label' => __('Date&Time', 'happy-elementor-addons'),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_responsive_control(
			'calendar_date_and_time_font_size',
			[
				'label' => __( 'Font Size', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ha-ec-wrapper span.fc-timegrid-axis-cushion.fc-scrollgrid-shrink-cushion.fc-scrollgrid-sync-inner' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ha-ec-wrapper .fc-timegrid-slot-label-cushion.fc-scrollgrid-shrink-cushion' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ha-ec-wrapper .fc .fc-daygrid-day-top' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'calendar_text_color',
            [
                'label'     => __('Color', 'happy-elementor-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-ec-wrapper span.fc-timegrid-axis-cushion.fc-scrollgrid-shrink-cushion.fc-scrollgrid-sync-inner' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ha-ec-wrapper .fc-timegrid-slot-label-cushion.fc-scrollgrid-shrink-cushion' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ha-ec-wrapper .fc .fc-daygrid-day-top' => 'color: {{VALUE}};',
                ],
            ]
        );

        /* $this->add_responsive_control(
            'calendar_inside',
            [
                'label'      => __('Inside Space', 'happy-elementor-addons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .ha-ec-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'calendar_outside',
            [
                'label'      => __('Outside Space', 'happy-elementor-addons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .ha-ec-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'after',
            ]
        ); */

        /* $this->add_control(
            'calendar_title_heading',
            [
                'label' => __('Title', 'happy-elementor-addons'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'calendar_title_typography',
                'label'    => __('Typography', 'happy-elementor-addons'),
                'selector' => '{{WRAPPER}} .fc-toolbar h2',
            ]
        );

        $this->add_control(
            'calendar_title_color',
            [
                'label'     => __('Color', 'happy-elementor-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fc-toolbar h2' => 'color: {{VALUE}};',
                ],
            ]
        ); */

		$this->end_controls_section();
	}

	protected function topbar_style_controls () {

		$this->start_controls_section(
			'_section_style_calendar_topbar',
			[
				'label' => __( 'Top Bar', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'topbar_margin_bottom',
			[
				'label'      => __('Margin Bottom', 'happy-elementor-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ha-ec-wrapper .fc-toolbar.fc-header-toolbar' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'topbar_background',
			[
				'label'     => __('Background', 'happy-elementor-addons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-ec-wrapper .fc-toolbar.fc-header-toolbar' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'topbar_title_heading',
			[
				'label' => __('Title', 'happy-elementor-addons'),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'topbar_title_typography',
				'label'    => __('Typography', 'happy-elementor-addons'),
				'exclude' => [
					'font_family',
				],
                'selector' => '{{WRAPPER}} .ha-ec-wrapper .fc-toolbar h2.fc-toolbar-title',
            ]
        );

        $this->add_control(
            'topbar_title_color',
            [
                'label'     => __('Color', 'happy-elementor-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-ec-wrapper .fc-toolbar h2.fc-toolbar-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Buttons style
        $this->add_control(
            'topbar_buttons_heading',
            [
                'label'     => __('Button', 'happy-elementor-addons'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
		);

		$this->add_responsive_control(
			'topbar_buttons_space',
			[
				'label'      => __('Space Between', 'happy-elementor-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ha-ec-wrapper .fc-toolbar.fc-header-toolbar button.fc-today-button' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ha-ec-wrapper .fc-toolbar.fc-header-toolbar .fc-button-group button:not(:first-child)' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'topbar_buttons_typography',
				'label'    => __('Typography', 'happy-elementor-addons'),
				'exclude' => [
					'font_family',
				],
                'selector' => '{{WRAPPER}} .ha-ec-wrapper .fc-toolbar.fc-header-toolbar button.fc-button',
            ]
		);

		$this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'topbar_buttons_border',
                'label'    => __('Border', 'happy-elementor-addons'),
				'exclude' => ['color'], //remove border color
                'selector' => '{{WRAPPER}} .ha-ec-wrapper .fc-toolbar.fc-header-toolbar button.fc-button',
            ]
		);

        $this->start_controls_tabs('calendar_buttons_style');

        // Normal
        $this->start_controls_tab(
            'topbar_buttons_normal_state',
            [
                'label' => __('Normal', 'happy-elementor-addons'),
            ]
        );

        $this->add_control(
            'topbar_buttons_color_normal',
            [
                'label'     => __('Color', 'happy-elementor-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-ec-wrapper .fc-toolbar.fc-header-toolbar button.fc-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'topbar_buttons_background_normal',
            [
                'label'     => __('Background', 'happy-elementor-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-ec-wrapper .fc-toolbar.fc-header-toolbar button.fc-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

		$this->add_control(
			'topbar_buttons_border_color_normal',
			[
				'label' => __( 'Border Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-ec-wrapper .fc-toolbar.fc-header-toolbar button.fc-button' => 'border-color: {{VALUE}}',
				],
			]
		);
        $this->end_controls_tab();

        // Hover
        $this->start_controls_tab(
            'topbar_buttons_hover_state',
            [
                'label' => __('Hover', 'happy-elementor-addons'),
            ]
        );

        $this->add_control(
            'topbar_buttons_color_hover',
            [
                'label'     => __('Color', 'happy-elementor-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-ec-wrapper .fc-toolbar.fc-header-toolbar button.fc-button:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ha-ec-wrapper .fc-toolbar.fc-header-toolbar button.fc-button.fc-button-active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'topbar_buttons_background_hover',
            [
                'label'     => __('Background', 'happy-elementor-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-ec-wrapper .fc-toolbar.fc-header-toolbar button.fc-button:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ha-ec-wrapper .fc-toolbar.fc-header-toolbar button.fc-button.fc-button-active' => 'background-color: {{VALUE}};',
                ],
            ]
		);

		$this->add_control(
			'topbar_buttons_border_color_hover',
			[
				'label' => __( 'Border Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-ec-wrapper .fc-toolbar.fc-header-toolbar button.fc-button:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .ha-ec-wrapper .fc-toolbar.fc-header-toolbar button.fc-button.fc-button-active' => 'border-color: {{VALUE}}',
				],
			]
		);

        $this->end_controls_tab();
		$this->end_controls_tabs();

        $this->add_responsive_control(
            'topbar_buttons_border_radius_normal',
            [
                'label'      => __('Border Radius', 'happy-elementor-addons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .ha-ec-wrapper .fc-toolbar.fc-header-toolbar button.fc-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->end_controls_section();
	}

	protected function event_style_controls () {

		$this->start_controls_section(
			'_section_style_event',
			[
				'label' => __( 'Event', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'event_item_font_size',
			[
				'label'      => __('Font Size', 'happy-elementor-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ha-ec-wrapper .fc-daygrid-event' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ha-ec-wrapper .fc-daygrid-event .fc-event-main' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ha-ec-wrapper .fc-timegrid-event' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ha-ec-wrapper .fc-timegrid-event .fc-event-main' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ha-ec-wrapper .fc-list-event' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'event_item_color',
            [
                'label'     => __('Color', 'happy-elementor-addons'),
                'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-ec-wrapper .fc-daygrid-event' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ha-ec-wrapper .fc-daygrid-event .fc-event-main' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ha-ec-wrapper .fc-timegrid-event' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ha-ec-wrapper .fc-timegrid-event .fc-event-main' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ha-ec-wrapper .fc-list-event' => 'color: {{VALUE}};',
				],
            ]
        );

		$this->add_control(
			'event_item_background',
			[
				'label'     => __('Background', 'happy-elementor-addons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-ec-wrapper .fc-daygrid-event' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .ha-ec-wrapper .fc-timegrid-event' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .ha-ec-wrapper .fc-list-event' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'event_item_dot_color',
			[
				'label'     => __('Dot Color', 'happy-elementor-addons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-ec-wrapper .fc-daygrid-event .fc-daygrid-event-dot' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .ha-ec-wrapper .fc-list-event .fc-list-event-dot' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function popup_style_controls () {

		$this->start_controls_section(
			'_section_style_event_popup',
			[
				'label' => __( 'Event Popup', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'event_popup_width',
			[
				'label'      => __('Width', 'happy-elementor-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ha-ec-popup-wrapper .ha-ec-popup' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
            'event_popup_padding',
            [
                'label'      => __('Padding', 'happy-elementor-addons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .ha-ec-popup-wrapper .ha-ec-popup' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'after',
            ]
		);

        $this->add_control(
            'event_popup_background',
            [
                'label'     => __('Background', 'happy-elementor-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-ec-popup-wrapper .ha-ec-popup' => 'background-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_responsive_control(
            'event_popup_border_radius',
            [
                'label'      => __('Border Radius', 'happy-elementor-addons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .ha-ec-popup-wrapper .ha-ec-popup' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
		);

		$this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'event_popup_border',
                'label'    => __('Border', 'happy-elementor-addons'),
				//'exclude' => ['color'], //remove border color
                'selector' => '{{WRAPPER}} .ha-ec-popup-wrapper .ha-ec-popup',
            ]
		);

        $this->add_control(
            'event_popup_image_heading',
            [
                'label' => __('Image', 'happy-elementor-addons'),
                'type'  => Controls_Manager::HEADING,
                'separator'  => 'before',
            ]
		);

		$this->add_control(
			'event_popup_image_width',
			[
				'label'      => __('Width', 'happy-elementor-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ha-ec-popup-wrapper' => '--ha-ec-popup-image-width: {{SIZE}}{{UNIT}};',
					//'{{WRAPPER}} .ha-ec-popup-wrapper' => '--ha-ec-popup-content-width: calc(100% - {{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_control(
            'event_popup_image_border_radius',
            [
                'label'      => __('Border Radius', 'happy-elementor-addons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .ha-ec-popup-wrapper .ha-ec-popup .ha-ec-popup-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
		);

        $this->add_control(
            'event_popup_title_heading',
            [
                'label' => __('Title', 'happy-elementor-addons'),
                'type'  => Controls_Manager::HEADING,
                'separator'  => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'event_popup_title_typography',
				'label'    => __('Typography', 'happy-elementor-addons'),
				'exclude' => [
					'font_family',
				],
                'selector' => '{{WRAPPER}} .ha-ec-popup-wrapper .ha-ec-popup-content h3',
            ]
        );

        $this->add_control(
            'event_popup_title_color',
            [
                'label'     => __('Title Color', 'happy-elementor-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-ec-popup-wrapper .ha-ec-popup-content h3' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'event_popup_desc_heading',
            [
                'label' => __('Description', 'happy-elementor-addons'),
                'type'  => Controls_Manager::HEADING,
                'separator'  => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'event_popup_desc_typography',
				'label'    => __('Typography', 'happy-elementor-addons'),
				'exclude' => [
					'font_family',
				],
                'selector' => '{{WRAPPER}} .ha-ec-popup-wrapper p.ha-ec-popup-desc',
            ]
        );

        $this->add_control(
            'event_popup_desc_color',
            [
                'label'     => __('Title Color', 'happy-elementor-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-ec-popup-wrapper p.ha-ec-popup-desc' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'event_popup_meta_heading',
            [
                'label' => __('Meta', 'happy-elementor-addons'),
                'type'  => Controls_Manager::HEADING,
                'separator'  => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'event_popup_meta_typography',
				'label'    => __('Typography', 'happy-elementor-addons'),
				'exclude' => [
					'font_family',
				],
                'selector' => '{{WRAPPER}} .ha-ec-popup-wrapper .ha-ec-popup-content ul li',
            ]
        );

        $this->add_control(
            'event_popup_meta_color',
            [
                'label'     => __('Title Color', 'happy-elementor-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-ec-popup-wrapper .ha-ec-popup-content ul li' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'event_popup_readmore_heading',
            [
                'label' => __('ReadMore', 'happy-elementor-addons'),
                'type'  => Controls_Manager::HEADING,
                'separator'  => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'event_popup_readmore_typography',
				'label'    => __('Typography', 'happy-elementor-addons'),
				'exclude' => [
					'font_family',
				],
                'selector' => '{{WRAPPER}} .ha-ec-popup-wrapper a.ha-ec-popup-readmore-link',
            ]
        );

        $this->add_control(
            'event_popup_readmore_color',
            [
                'label'     => __('Title Color', 'happy-elementor-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-ec-popup-wrapper a.ha-ec-popup-readmore-link' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
	}


	protected function render () {

		$settings = $this->get_settings_for_display();

		if ($settings['event_calendar_type'] == 'google_calendar') {
            $data = $this->get_google_calendar_events($settings);
        } elseif ($settings['event_calendar_type'] == 'the_events_calendar') {
            $data = $this->get_the_events_calendar_events($settings);
        } else {
            $data = $this->get_manual_calendar_events($settings);
        }

        $local = $settings['language'];
        $default_view = $settings['default_view'];

		$this->add_render_attribute( 'wrapper', 'class', 'ha-ec-wrapper' );

		$this->add_render_attribute(
			'event-calendar',
			[
				'id' => 'ha-ec-' . $this->get_id(),
				'class' => 'ha-ec',
				'data-cal-id' => $this->get_id(),
				'data-locale' => $local,
				'data-initialview' => $default_view,
				'data-firstday' => $settings['event_calendar_first_day'],
				'data-events' => htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8'),
				'data-show-popup' => !empty( $settings['show_event_popup'] ) ? esc_attr( $settings['show_event_popup'] ) : '',
			]
		);

		// echo '<pre>';
		// var_dump($data);
		// echo '</pre>';

		if ( $data ) :?>
			<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
				<div <?php $this->print_render_attribute_string( 'event-calendar' ); ?>></div>
			</div>

		<?php
		if( 'yes' === $settings['show_event_popup'] ){
			$this->get_popup_markup();
		}
		endif;
	}

	public function get_manual_calendar_events ($settings) {
		$events = $settings['manual_event_list'];

				// echo '<pre>';
				// var_dump($events);
				// echo '</pre>';
        $data = [];
        if ($events) {
            $i = 0;

            foreach ($events as $event) {
				// echo '<pre>';
				// var_dump($event['image']);
				// echo '</pre>';

                if ( $event['all_day'] == 'yes' ) {
                    $start = $event["start_date_allday"];
					$end = date('Y-m-d', strtotime("+1 days", strtotime($event["end_date_allday"])));

					$colors["textColor"] = !empty($event['text_color']) ? $event['text_color'] : '';
					$colors["backgroundColor"] = !empty($event['bg_color']) ? $event['bg_color'] : '';

                } else {
                    $start = $event["start_date"];
                    $end = date('Y-m-d H:i', strtotime($event["end_date"])).":01";
				}

				$image = !empty( $event['image']['url'] ) ? esc_url( $event['image']['url'] ) : '' ;
				if( !empty( $event['image']['id'] ) ){
					$image = esc_url( wp_get_attachment_image_url( $event['image']['id'], $settings['thumbnail_size'] ) );
				}

                $data[] = [
                    'id' => $i,
                    'classNames' => 'elementor-repeater-item-'.$event["_id"],
                    'title' => !empty($event["title"]) ? $event["title"] : '',
                    'description' => $event["description"],
                    'start' => $start,
                    'end' => $end,
                    'url' => !empty($event["details_link"]["url"]) ? $event["details_link"]["url"] : '',
                    'allDay' => $event['all_day'],
                    'external' => $event['details_link']['is_external'],
					'nofollow' => $event['details_link']['nofollow'],
					'guest' => $event['guest'],
					'location' => $event['location'],
					'image' => $image,

					//"textColor" =>  ( $event['all_day'] == 'yes' && !empty($event['text_color']) ) ? $event['text_color'] : '',
					//"backgroundColor" =>  ( $event['all_day'] == 'yes' && !empty($event['bg_color']) ) ? $event['bg_color'] : '',
					//"borderColor" => !empty($event['border_color']) ? $event['border_color'] : '',
                ];

                $i++;
            }
        }
        return $data;
	}


    public function get_google_calendar_events ($settings) {

        if ( empty( $settings['google_calendar_api_key'] ) && empty( $settings['google_calendar_id'] ) ) {
			$message = __('Please input API key & Calendar ID.', 'happy-elementor-addons');
			printf('<span class="ha-ec-error-message">%1$s</span>', esc_html( $message ) );
            return [];
		}

		// GET https://www.googleapis.com/calendar/v3/calendars/en.bd%23holiday%40group.v.calendar.google.com/events?key=AIzaSyDiXYw6nDZIE6RzqIy2WNZxvrze73smMo0&orderBy=updated&showDeleted=true

		// GET https://www.googleapis.com/calendar/v3/calendars/iqbalrony30%40gmail.com/events?key=AIzaSyDiXYw6nDZIE6RzqIy2WNZxvrze73smMo0&orderBy=updated&showDeleted=true

        // [calendar_id] en.bd%23holiday%40group.v.calendar.google.com
        // [calendar_id] iqbalrony30%40gmail.com
        // key= AIzaSyDiXYw6nDZIE6RzqIy2WNZxvrze73smMo0


        $calendar_id = urlencode($settings['google_calendar_id']);
        $base_url = "https://www.googleapis.com/calendar/v3/calendars/" . $calendar_id . "/events";

        $start_date = strtotime( $settings['google_calendar_start_date'] );
        $end_date = strtotime( $settings['google_calendar_end_date'] );

        $arg = [
            'key' => $settings['google_calendar_api_key'],
            'maxResults' => $settings['google_calendar_max_item'],
            'timeMin' => urlencode( date( 'c', $start_date ) ),
            'singleEvents' => 'true',
        ];

        if ( ! empty( $end_date ) && $end_date > $start_date ) {
            $arg['timeMax'] = urlencode( date( 'c', $end_date ) );
        }

        $transient_key = 'ha_ec_google_calendar_'.md5( urlencode($settings['google_calendar_id']) . implode('', $arg) );
        $data = get_transient($transient_key);

		// delete_transient($transient_key);

        if( false === $data ){
			$data = wp_remote_retrieve_body(wp_remote_get(add_query_arg($arg, $base_url)));
			// echo '<pre>';
			// var_dump(json_decode($data));
			// echo '</pre>';
			if( is_object( json_decode($data) ) && !array_key_exists('error', json_decode($data) ) ){
				// echo 'cacheeed';
				set_transient($transient_key, $data, 1 * HOUR_IN_SECONDS);
				set_transient($transient_key, $data, 10 * MINUTE_IN_SECONDS);
			}
        }

		if( is_object( json_decode( $data ) ) && array_key_exists('error', json_decode( $data ) ) ){
			$message = __('Please input valid API key & Calendar ID.', 'happy-elementor-addons');
			printf('<span class="ha-ec-error-message">%1$s</span>', esc_html( $message ));
			return [];
		}

		$data = false !== $data ? json_decode( $data ) : '';


        // echo '<pre>';
		// var_dump($data);
		// echo '</pre>';

		$calendar_data = [];
        if ( isset ( $data->items ) ) {

            foreach ( $data->items as $key => $item ) {

                if ( $item->status !== 'confirmed' ) {
                    continue;
				}

				$all_day = '';

                if ( isset( $item->start->date ) ) {
                    $all_day = 'yes';
                    $ev_start_date = $item->start->date;
                    $ev_end_date = $item->end->date;
                } else {
                    $ev_start_date = $item->start->dateTime;
                    $ev_end_date = $item->end->dateTime;
				}

                $calendar_data[] = [
                    'id' => ++$key,
                    'title' => !empty( $item->summary ) ? $item->summary : 'No Title',
                    'description' => isset( $item->description ) ? $item->description : '',
                    'start' => $ev_start_date,
					'end' => $ev_end_date,

                    // 'borderColor' => !empty($settings['global_popup_ribbon_color']) ? $settings['global_popup_ribbon_color'] : '#10ecab',
                    // 'textColor' => $settings['global_text_color'],
					// 'color' => $settings['global_bg_color'],

					'url'         => !empty( $item->htmlLink ) ? $item->htmlLink : '',
					// 'url'         => $item->htmlLink ,

                    'allDay' => $all_day,
                    'external' => 'on',
					'nofollow' => 'on',

					'guest' => !empty( $item->creator->displayName ) ? $item->creator->displayName : '',
					'location' => !empty( $item->location ) ? $item->location : '',
                ];
            }


        }

        return $calendar_data;
    }


    public function get_the_events_calendar_events ( $settings ) {

        if ( ! function_exists('tribe_get_events') ) {
            return [];
		}

		if ( 'selected_event' !== $settings['the_events_calendar_source'] ) {
            $arg = [
				'posts_per_page' => $settings['the_events_calendar_item'],
			];
		}

        if ( 'category' == $settings['the_events_calendar_source'] && !empty( $settings['the_events_calendar_category'] ) ) {
            $arg['tax_query'] = [
                [
                    'taxonomy' => 'tribe_events_cat',
                    'field' => 'id',
                    'terms' => $settings['the_events_calendar_category']
                ]
            ];
		}

        if ( 'selected_event' == $settings['the_events_calendar_source'] && !empty( $settings['the_events_calendar_selected'] ) ) {
            $arg['post__in'] = $settings['the_events_calendar_selected'];
		}

		$events = tribe_get_events( $arg );

        if ( empty( $events ) ) {
            return [];
		}

        $calendar_data = [];
        foreach ($events as $key => $event) {

            $date_format = 'Y-m-d';
			$all_day = 'yes';

            if (!tribe_event_is_all_day($event->ID)) {
                $date_format .= ' H:i';
                $all_day = '';
			}

			$image = get_the_post_thumbnail_url( $event->ID );

            $calendar_data[] = [
                'id' => ++$key,
                'title' => !empty($event->post_title) ? $event->post_title :'',
                'description' => $event->post_content,
                'start' => tribe_get_start_date($event->ID, true, $date_format),
				'end' => tribe_get_end_date($event->ID, true, $date_format),

                // 'borderColor' => !empty($settings['eael_event_global_popup_ribbon_color']) ? $settings['eael_event_global_popup_ribbon_color'] : '#10ecab',
                // 'textColor' => $settings['eael_event_global_text_color'],
				// 'color' => $settings['eael_event_global_bg_color'],

                'url' => !empty( get_the_permalink( $event->ID ) ) ? get_the_permalink( $event->ID ) : '',
                'allDay' => $all_day,
                'external' => 'on',
				'nofollow' => 'on',
				'guest' => tribe_get_organizer( $event->ID ),
				'location' => !empty( tribe_get_venue( $event->ID ) ) ? tribe_get_venue( $event->ID ) : '',
				'image' => $image ? $image : '',
            ];
        }
		return $calendar_data;

    }

	public function get_popup_markup () {
       $popup = '<div class="ha-ec-popup-wrapper">
					<div class="ha-ec-popup">

						<span class="ha-ec-popup-close"><i class="eicon-editor-close"></i></span>
						<div class="ha-ec-popup-body">

							<div class="ha-ec-popup-image">
								<img src="" alt="">
							</div>


							<div class="ha-ec-popup-content">
							<ul>
								<li class="ha-ec-event-time">'.$this->render_svg_icon('clock').'<span></span></li>
								<li class="ha-ec-event-guest">'.$this->render_svg_icon('user').'<span></span></li>
								<li class="ha-ec-event-location">'.$this->render_svg_icon('map').'<span></span></li>
							</ul>
							<h3 class="ha-ec-event-title">Business Strategy</h3>
							<p class="ha-ec-popup-desc"></p>
							<div class="ha-ec-popup-readmore">
								<a class="ha-ec-popup-readmore-link" href="">'.__('Read More', 'happy-elementor-addons').'</a>
							</div>
							</div>

						</div>

					</div>
				</div>';
        echo $popup;
	}

	public function render_svg_icon( $icon_name ) {
		?>
			<?php if ( 'clock' === $icon_name ) :
				return '<svg xmlns="http://www.w3.org/2000/svg" height="512pt" viewBox="0 0 512 512" width="512pt"><path d="m256 0c-141.164062 0-256 114.835938-256 256s114.835938 256 256 256 256-114.835938 256-256-114.835938-256-256-256zm121.75 388.414062c-4.160156 4.160157-9.621094 6.253907-15.082031 6.253907-5.460938 0-10.925781-2.09375-15.082031-6.253907l-106.667969-106.664062c-4.011719-3.988281-6.25-9.410156-6.25-15.082031v-138.667969c0-11.796875 9.554687-21.332031 21.332031-21.332031s21.332031 9.535156 21.332031 21.332031v129.835938l100.417969 100.414062c8.339844 8.34375 8.339844 21.824219 0 30.164062zm0 0"/></svg>';
			endif; ?>
			<?php if ( 'user' === $icon_name ) :
				return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="-42 0 512 512.002"><path d="m210.351562 246.632812c33.882813 0 63.222657-12.152343 87.195313-36.128906 23.972656-23.972656 36.125-53.304687 36.125-87.191406 0-33.875-12.152344-63.210938-36.128906-87.191406-23.976563-23.96875-53.3125-36.121094-87.191407-36.121094-33.886718 0-63.21875 12.152344-87.191406 36.125s-36.128906 53.308594-36.128906 87.1875c0 33.886719 12.15625 63.222656 36.132812 87.195312 23.976563 23.96875 53.3125 36.125 87.1875 36.125zm0 0"/><path d="m426.128906 393.703125c-.691406-9.976563-2.089844-20.859375-4.148437-32.351563-2.078125-11.578124-4.753907-22.523437-7.957031-32.527343-3.308594-10.339844-7.808594-20.550781-13.371094-30.335938-5.773438-10.15625-12.554688-19-20.164063-26.277343-7.957031-7.613282-17.699219-13.734376-28.964843-18.199219-11.226563-4.441407-23.667969-6.691407-36.976563-6.691407-5.226563 0-10.28125 2.144532-20.042969 8.5-6.007812 3.917969-13.035156 8.449219-20.878906 13.460938-6.707031 4.273438-15.792969 8.277344-27.015625 11.902344-10.949219 3.542968-22.066406 5.339844-33.039063 5.339844-10.972656 0-22.085937-1.796876-33.046874-5.339844-11.210938-3.621094-20.296876-7.625-26.996094-11.898438-7.769532-4.964844-14.800782-9.496094-20.898438-13.46875-9.75-6.355468-14.808594-8.5-20.035156-8.5-13.3125 0-25.75 2.253906-36.972656 6.699219-11.257813 4.457031-21.003906 10.578125-28.96875 18.199219-7.605469 7.28125-14.390625 16.121094-20.15625 26.273437-5.558594 9.785157-10.058594 19.992188-13.371094 30.339844-3.199219 10.003906-5.875 20.945313-7.953125 32.523437-2.058594 11.476563-3.457031 22.363282-4.148437 32.363282-.679688 9.796875-1.023438 19.964844-1.023438 30.234375 0 26.726562 8.496094 48.363281 25.25 64.320312 16.546875 15.746094 38.441406 23.734375 65.066406 23.734375h246.53125c26.625 0 48.511719-7.984375 65.0625-23.734375 16.757813-15.945312 25.253906-37.585937 25.253906-64.324219-.003906-10.316406-.351562-20.492187-1.035156-30.242187zm0 0"/></svg>';
			endif; ?>
			<?php if ( 'map' === $icon_name ) :
				return '<svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512"><g><path d="m407.579 87.677c-31.073-53.624-86.265-86.385-147.64-87.637-2.62-.054-5.257-.054-7.878 0-61.374 1.252-116.566 34.013-147.64 87.637-31.762 54.812-32.631 120.652-2.325 176.123l126.963 232.387c.057.103.114.206.173.308 5.586 9.709 15.593 15.505 26.77 15.505 11.176 0 21.183-5.797 26.768-15.505.059-.102.116-.205.173-.308l126.963-232.387c30.304-55.471 29.435-121.311-2.327-176.123zm-151.579 144.323c-39.701 0-72-32.299-72-72s32.299-72 72-72 72 32.299 72 72-32.298 72-72 72z"/></g></svg>';
			endif; ?>
		<?php
	}

	public function render_svg_icon2( $icon_name ) {
		?>
			<?php if ( 'clock' === $icon_name ) :
				return '<svg xmlns="http://www.w3.org/2000/svg" id="Capa_1" enable-background="new 0 0 443.294 443.294" height="512" viewBox="0 0 443.294 443.294" width="512"><path d="m221.647 0c-122.214 0-221.647 99.433-221.647 221.647s99.433 221.647 221.647 221.647 221.647-99.433 221.647-221.647-99.433-221.647-221.647-221.647zm0 415.588c-106.941 0-193.941-87-193.941-193.941s87-193.941 193.941-193.941 193.941 87 193.941 193.941-87 193.941-193.941 193.941z"/><path d="m235.5 83.118h-27.706v144.265l87.176 87.176 19.589-19.589-79.059-79.059z"/></svg>';
			endif; ?>
			<?php if ( 'user' === $icon_name ) :
				return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="-42 0 512 512.001"><path d="m210.351562 246.632812c33.882813 0 63.21875-12.152343 87.195313-36.128906 23.96875-23.972656 36.125-53.304687 36.125-87.191406 0-33.875-12.152344-63.210938-36.128906-87.191406-23.976563-23.96875-53.3125-36.121094-87.191407-36.121094-33.886718 0-63.21875 12.152344-87.191406 36.125s-36.128906 53.308594-36.128906 87.1875c0 33.886719 12.15625 63.222656 36.128906 87.195312 23.980469 23.96875 53.316406 36.125 87.191406 36.125zm-65.972656-189.292968c18.394532-18.394532 39.972656-27.335938 65.972656-27.335938 25.996094 0 47.578126 8.941406 65.976563 27.335938 18.394531 18.398437 27.339844 39.980468 27.339844 65.972656 0 26-8.945313 47.578125-27.339844 65.976562-18.398437 18.398438-39.980469 27.339844-65.976563 27.339844-25.992187 0-47.570312-8.945312-65.972656-27.339844-18.398437-18.394531-27.34375-39.976562-27.34375-65.976562 0-25.992188 8.945313-47.574219 27.34375-65.972656zm0 0"/><path d="m426.128906 393.703125c-.691406-9.976563-2.089844-20.859375-4.148437-32.351563-2.078125-11.578124-4.753907-22.523437-7.957031-32.527343-3.3125-10.339844-7.808594-20.550781-13.375-30.335938-5.769532-10.15625-12.550782-19-20.160157-26.277343-7.957031-7.613282-17.699219-13.734376-28.964843-18.199219-11.226563-4.441407-23.667969-6.691407-36.976563-6.691407-5.226563 0-10.28125 2.144532-20.042969 8.5-6.007812 3.917969-13.035156 8.449219-20.878906 13.460938-6.707031 4.273438-15.792969 8.277344-27.015625 11.902344-10.949219 3.542968-22.066406 5.339844-33.042969 5.339844-10.96875 0-22.085937-1.796876-33.042968-5.339844-11.210938-3.621094-20.300782-7.625-26.996094-11.898438-7.769532-4.964844-14.800782-9.496094-20.898438-13.46875-9.753906-6.355468-14.808594-8.5-20.035156-8.5-13.3125 0-25.75 2.253906-36.972656 6.699219-11.257813 4.457031-21.003906 10.578125-28.96875 18.199219-7.609375 7.28125-14.390625 16.121094-20.15625 26.273437-5.558594 9.785157-10.058594 19.992188-13.371094 30.339844-3.199219 10.003906-5.875 20.945313-7.953125 32.523437-2.0625 11.476563-3.457031 22.363282-4.148437 32.363282-.679688 9.777344-1.023438 19.953125-1.023438 30.234375 0 26.726562 8.496094 48.363281 25.25 64.320312 16.546875 15.746094 38.4375 23.730469 65.066406 23.730469h246.53125c26.621094 0 48.511719-7.984375 65.0625-23.730469 16.757813-15.945312 25.253906-37.589843 25.253906-64.324219-.003906-10.316406-.351562-20.492187-1.035156-30.242187zm-44.90625 72.828125c-10.933594 10.40625-25.449218 15.464844-44.378906 15.464844h-246.527344c-18.933594 0-33.449218-5.058594-44.378906-15.460938-10.722656-10.207031-15.933594-24.140625-15.933594-42.585937 0-9.59375.316406-19.066407.949219-28.160157.617187-8.921874 1.878906-18.722656 3.75-29.136718 1.847656-10.285156 4.199219-19.9375 6.996094-28.675782 2.683593-8.378906 6.34375-16.675781 10.882812-24.667968 4.332031-7.617188 9.316407-14.152344 14.816407-19.417969 5.144531-4.925781 11.628906-8.957031 19.269531-11.980469 7.066406-2.796875 15.007812-4.328125 23.628906-4.558594 1.050781.558594 2.921875 1.625 5.953125 3.601563 6.167969 4.019531 13.277344 8.605469 21.136719 13.625 8.859375 5.648437 20.273437 10.75 33.910156 15.152344 13.941406 4.507812 28.160156 6.796875 42.273437 6.796875 14.113282 0 28.335938-2.289063 42.269532-6.792969 13.648437-4.410156 25.058594-9.507813 33.929687-15.164063 8.042969-5.140624 14.953125-9.59375 21.121094-13.617187 3.03125-1.972656 4.902344-3.042969 5.953125-3.601563 8.625.230469 16.566406 1.761719 23.636719 4.558594 7.636719 3.023438 14.121093 7.058594 19.265625 11.980469 5.5 5.261719 10.484375 11.796875 14.816406 19.421875 4.542969 7.988281 8.207031 16.289062 10.886719 24.660156 2.800781 8.75 5.15625 18.398438 7 28.675782 1.867187 10.433593 3.132812 20.238281 3.75 29.144531v.007812c.636719 9.058594.957031 18.527344.960937 28.148438-.003906 18.449219-5.214844 32.378906-15.9375 42.582031zm0 0"/></svg>';
			endif; ?>
			<?php if ( 'map' === $icon_name ) :
				return '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
					<g>
						<g>
							<path d="M256,0C156.748,0,76,80.748,76,180c0,33.534,9.289,66.26,26.869,94.652l142.885,230.257    c2.737,4.411,7.559,7.091,12.745,7.091c0.04,0,0.079,0,0.119,0c5.231-0.041,10.063-2.804,12.75-7.292L410.611,272.22    C427.221,244.428,436,212.539,436,180C436,80.748,355.252,0,256,0z M384.866,256.818L258.272,468.186l-129.905-209.34    C113.734,235.214,105.8,207.95,105.8,180c0-82.71,67.49-150.2,150.2-150.2S406.1,97.29,406.1,180    C406.1,207.121,398.689,233.688,384.866,256.818z"/>
						</g>
					</g>
					<g>
						<g>
							<path d="M256,90c-49.626,0-90,40.374-90,90c0,49.309,39.717,90,90,90c50.903,0,90-41.233,90-90C346,130.374,305.626,90,256,90z     M256,240.2c-33.257,0-60.2-27.033-60.2-60.2c0-33.084,27.116-60.2,60.2-60.2s60.1,27.116,60.1,60.2    C316.1,212.683,289.784,240.2,256,240.2z"/>
						</g>
					</g>
				</svg>';
			endif; ?>
		<?php
	}

}
