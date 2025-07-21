<?php
namespace Happy_Addons\Elementor\Extensions;

use \Elementor\Controls_Manager;


class Custom_Js {

	private static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		 return self::$instance;
	}


	public function init() {
		add_action( 'elementor/documents/register_controls', [$this, 'scroll_to_top_controls'], 10 );
		add_filter( 'elementor/document/save/data', [$this, 'before_save_data'], 10, 1 );
		add_action( 'wp_footer', [$this, 'render_scroll_to_top_html'] );
	}

	public function scroll_to_top_controls( $element ) {

		$element->start_controls_section(
            'ha_page_custom_js_section',
            [
                'label' => __( 'Custom JS', 'happy-elementor-addons' ) . ha_get_section_icon(),
                'tab'   => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $element->add_control(
            'ha_page_custom_js',
            [
                'label' => __('Add your own custom JS here', 'happy-elementor-addons'),
                'show_label' => true,
                'type' => Controls_Manager::CODE,
                'language' => 'javascript',
            ]
        );

	    if ( ! current_user_can( 'administrator' ) ) {
			$element->add_control(
				'ha_page_custom_js_admin_notice',
				[
					'type' => Controls_Manager::NOTICE,
					'notice_type' => 'warning',
					'dismissible' => false,
					'content' => __( 'Only the Administrator can add or edit JavaScript code from here', 'happy-elementor-addons' ),
				]
			);
	    }

        $element->end_controls_section();
	}

	public function before_save_data( $data ) {
		if ( ! current_user_can( 'administrator' ) ) {
			$page_setting = get_post_meta( get_the_ID(), '_elementor_page_settings', true );
			if ( isset( $data['settings']['ha_page_custom_js'] ) && isset( $page_setting['ha_page_custom_js'] ) ) {
				$prev_js = isset( $page_setting['ha_page_custom_js'] ) ? trim( $page_setting['ha_page_custom_js'] ) : '';
				// $data['settings']['eael_custom_js'] = get_post_meta( get_the_ID(), '_eael_custom_js', true );
				$data['settings']['ha_page_custom_js'] = $prev_js;
			}
		}
		return $data;
	}

	public function render_scroll_to_top_html() {

		$post_id                = get_the_ID();
		// $document               = [];
		// $document_settings_data = [];

		// if ( ha_elementor()->preview->is_preview_mode() ) {
		// 	// get auto save data
		// 	$document = \Elementor\Plugin::$instance->documents->get_doc_for_frontend( $post_id );
		// } else {
		// 	$document = \Elementor\Plugin::$instance->documents->get( $post_id, false );
		// }

		$page_setting = get_post_meta( $post_id, '_elementor_page_settings', true );
		$custom_js    = isset( $page_setting['ha_page_custom_js'] ) ? trim( $page_setting['ha_page_custom_js'] ) : '';
		// error_log( print_r( $custom_js , 1 ) );

		if ( $custom_js ) {
			wp_add_inline_script( 'happy-elementor-addons', $custom_js );
		}


	}

	public function render_scroll_to_top_html2() {

		$post_id                = get_the_ID();
		$document               = [];
		$document_settings_data = [];

		if ( ha_elementor()->preview->is_preview_mode() ) {
			// get auto save data
			$document = \Elementor\Plugin::$instance->documents->get_doc_for_frontend( $post_id );
		} else {
			$document = \Elementor\Plugin::$instance->documents->get( $post_id, false );
		}
		if ( isset( $document ) && is_object( $document ) ) {
			$document_settings_data = $document->get_settings();
		}

		$scroll_to_top_global = $this->elementor_get_setting( 'ha_scroll_to_top_global' );

		$scroll_to_top = false;

		if ( 'yes' == $scroll_to_top_global ) {
			$scroll_to_top = true;
		}

		if ( isset( $document_settings_data['ha_scroll_to_top_single_disable'] ) && 'yes' == $document_settings_data['ha_scroll_to_top_single_disable'] ) {
			$scroll_to_top = false;
		}

		if ( ! ha_elementor()->preview->is_preview_mode() && $scroll_to_top ) {

			$stt_media_type = ! empty( $this->elementor_get_setting( 'ha_scroll_to_top_media_type' ) ) ? $this->elementor_get_setting( 'ha_scroll_to_top_media_type' ) : 'icon';
			$stt_icon_html  = '';
			if ( 'icon' == $stt_media_type ) {
				$stt_icon      = ! empty( $this->elementor_get_setting( 'ha_scroll_to_top_button_icon' ) ) ? $this->elementor_get_setting( 'ha_scroll_to_top_button_icon' )['value'] : 'fas fa-chevron-up';
				$stt_icon_html = "<i class='$stt_icon'></i>";
			} elseif ( 'image' == $stt_media_type ) {
				$stt_image     = ! empty( $this->elementor_get_setting( 'ha_scroll_to_top_button_image' ) ) ? $this->elementor_get_setting( 'ha_scroll_to_top_button_image' )['url'] : '';
				$stt_icon_html = "<img src='$stt_image'>";
			} elseif ( 'text' == $stt_media_type ) {
				$stt_text      = ! empty( $this->elementor_get_setting( 'ha_scroll_to_top_button_text' ) ) ? $this->elementor_get_setting( 'ha_scroll_to_top_button_text' ) : '';
				$stt_icon_html = "<span>$stt_text</span>";
			}

			$scroll_to_top_html = "<div class='ha-scroll-to-top-wrap ha-scroll-to-top-hide'><span class='ha-scroll-to-top-button'>$stt_icon_html</span></div>";

			$elementor_page = get_post_meta( get_the_ID(), '_elementor_edit_mode', true );
			if( (bool)$elementor_page ) {
				printf( '%1$s', $scroll_to_top_html );
			}

			wp_add_inline_script(
				'happy-elementor-addons',
				'!function(o){"use strict";o((function(){o(this).scrollTop()>100&&o(".ha-scroll-to-top-wrap").removeClass("ha-scroll-to-top-hide"),o(window).scroll((function(){o(this).scrollTop()<100?o(".ha-scroll-to-top-wrap").fadeOut(300):o(".ha-scroll-to-top-wrap").fadeIn(300)})),o(".ha-scroll-to-top-wrap").on("click",(function(){return o("html, body").animate({scrollTop:0},300),!1}))}))}(jQuery);'
			);
		}

		if ( ha_elementor()->preview->is_preview_mode() ) {
			if ( $scroll_to_top ) {
				$stt_media_type = ! empty( $this->elementor_get_setting( 'ha_scroll_to_top_media_type' ) ) ? $this->elementor_get_setting( 'ha_scroll_to_top_media_type' ) : 'icon';
				$stt_icon_html  = '';
				if ( 'icon' == $stt_media_type ) {
					$stt_icon      = ! empty( $this->elementor_get_setting( 'ha_scroll_to_top_button_icon' ) ) ? $this->elementor_get_setting( 'ha_scroll_to_top_button_icon' )['value'] : 'fas fa-chevron-up';
					$stt_icon_html = "<i class='$stt_icon'></i>";
				} elseif ( 'image' == $stt_media_type ) {
					$stt_image     = ! empty( $this->elementor_get_setting( 'ha_scroll_to_top_button_image' ) ) ? $this->elementor_get_setting( 'ha_scroll_to_top_button_image' )['url'] : '';
					$stt_icon_html = "<img src='$stt_image'>";
				} elseif ( 'text' == $stt_media_type ) {
					$stt_text      = ! empty( $this->elementor_get_setting( 'ha_scroll_to_top_button_text' ) ) ? $this->elementor_get_setting( 'ha_scroll_to_top_button_text' ) : '';
					$stt_icon_html = "<span>$stt_text</span>";
				}
				$scroll_to_top_html = "<div class='ha-scroll-to-top-wrap ha-scroll-to-top-hide'><span class='ha-scroll-to-top-button'>$stt_icon_html</span></div>";

				$elementor_page = get_post_meta( get_the_ID(), '_elementor_edit_mode', true );
				if( (bool)$elementor_page ) {
					printf( '%1$s', $scroll_to_top_html );
				}
			}
			?>
			<script>
				;(function($) {
					'use strict';
					var markup = '<div class="ha-scroll-to-top-wrap edit-mode ha-scroll-to-top-hide"><span class="ha-scroll-to-top-button"><i class="fas fa-chevron-up"></i></span></div>';
					var stt = jQuery('.ha-scroll-to-top-wrap');
					//console.log(stt);
					if ( ! stt.length ) {
						jQuery('body').append(markup);
					}

					function haSanitizeString(input) {
						var htmlTags = /<[^>]*>/g;
						var sanitizedInput = input.replace(htmlTags, "");
						return sanitizedInput;
					}

					function haSanitizeURL(url) {
						var urlPattern = /^(https?|ftp):\/\/[^\s/$.?#].[^\s]*$/i;
						if (url.match(urlPattern)) {
							return url;
						} else {
							return "";
						}
					}

					window.addEventListener('message',function(e) {
						var data = e.data;
						if( 'sttMessage' == data.check ) {
							if (e.origin != window.origin) {
								return;
							}
							if (e.source.location.href != window.parent.location.href) {
								return;
							}
							var sttWrap = jQuery('.ha-scroll-to-top-wrap');
							var button = sttWrap.find('.ha-scroll-to-top-button');
							var changeValue = data.changeValue;
							var changeItem = data.changeItem;

							if ( 'ha_scroll_to_top_single_disable' != changeItem[0] ) {
								var icon = '';
								var image = '';
								var text = '';
								var items = {
									'enable_global_stt' : ('ha_scroll_to_top_global' == changeItem[0]) ? changeValue : data.enable_global_stt,
									'media_type' : ('ha_scroll_to_top_media_type' == changeItem[0]) ? changeValue : data.media_type,
									'icon' : ('ha_scroll_to_top_button_icon' == changeItem[0]) ? changeValue : data.icon,
									'image' : ('ha_scroll_to_top_button_image' == changeItem[0]) ? changeValue : data.image,
									'text' : ('ha_scroll_to_top_button_text' == changeItem[0]) ? changeValue : data.text,
								};

								if( 'ha_scroll_to_top_button_icon' == changeItem[0] ) {
									items.media_type = 'icon';
								} else if( 'ha_scroll_to_top_button_image' == changeItem[0] ) {
									items.media_type = 'image';
								} else if( 'ha_scroll_to_top_button_text' == changeItem[0] ) {
									items.media_type = 'text';
								}

								if ('icon' == items.media_type) {
									icon = '<i class="' + haSanitizeString(items.icon.value) + '"></i>';
									button.html(icon);
								} else if ('image' == items.media_type) {
									image = '<img src="' + haSanitizeURL(items.image.url) + '">';
									button.html(image);
								} else if ('text' == items.media_type) {
									text = '<span>' + haSanitizeString(items.text) + '</span>';
									button.html(text);
								}

								if( 'yes' == items.enable_global_stt && sttWrap.hasClass("edit-mode") ) {
									sttWrap.removeClass("edit-mode");
								} else if( '' == changeValue && !sttWrap.hasClass("edit-mode") ) {
									sttWrap.addClass("edit-mode");
								}
							}

							if( 'ha_scroll_to_top_single_disable' == changeItem[0] ) {
								if( 'yes' == changeValue && !sttWrap.hasClass("single-page-off") ) {
									sttWrap.addClass("single-page-off");
								} else if( '' == changeValue && sttWrap.hasClass("single-page-off") ) {
									sttWrap.removeClass("single-page-off");
								}
							}
						}
					})
				}(jQuery));
				!function(o){"use strict";o((function(){o(this).scrollTop()>100&&o(".ha-scroll-to-top-wrap").removeClass("ha-scroll-to-top-hide"),o(window).scroll((function(){o(this).scrollTop()<100?o(".ha-scroll-to-top-wrap").fadeOut(300):o(".ha-scroll-to-top-wrap").fadeIn(300)})),o(".ha-scroll-to-top-wrap").on("click",(function(){return o("html, body").animate({scrollTop:0},300),!1}))}))}(jQuery);
			</script>
			<?php
		}

	}

	public function scroll_to_top_controls2( $element ) {

		$element->start_controls_section(
			'ha_scroll_to_top_single_section',
			[
				'label' => __( 'Scroll to Top', 'happy-elementor-addons' ) . ha_get_section_icon(),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);

		$element->add_control(
			'ha_scroll_to_top_single_disable',
			[
				'label'        => __( 'Disable Scroll to Top', 'happy-elementor-addons' ),
				'description'  => __( 'Disable Scroll to Top For This Page', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'happy-elementor-addons' ),
				'label_off'    => __( 'No', 'happy-elementor-addons' ),
				'return_value' => 'yes',
			]
		);

		$element->end_controls_section();
	}
}
