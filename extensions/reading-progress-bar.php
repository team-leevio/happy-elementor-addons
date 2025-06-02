<?php

namespace Happy_Addons\Elementor\Extensions;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Plugin;

defined('ABSPATH') || die();

class Reading_Progress_Bar {

	private static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init() {
		$feature_file = HAPPY_ADDONS_DIR_PATH . 'extensions/reading-progress-bar-kit-settings.php';

		if ( is_readable( $feature_file ) ) {
			include_once $feature_file;
		}

		add_action( 'elementor/kit/register_tabs', [ $this, 'init_site_settings' ], 1, 40 );

		add_action( 'elementor/documents/register_controls', [$this, 'reading_progress_bar_controls'], 10 );
        // add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_scripts']);
        if ( !ha_elementor()->preview->is_preview_mode() ) {
            add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts_frontend']);
        }
		add_action( 'wp_footer', [$this, 'render_reading_progress_bar_html'] );

	}

    public function enqueue_scripts () {
        $suffix = ha_is_script_debug_enabled() ? '.' : '.min.';
        $extension_js = HAPPY_ADDONS_DIR_PATH . 'assets/js/extension-reading-progress-bar' . $suffix . 'js';

        if (file_exists($extension_js)) {
            wp_add_inline_script(
                'elementor-frontend',
                file_get_contents($extension_js)
            );
        }
    }

    public function enqueue_scripts_frontend () {
        $suffix = ha_is_script_debug_enabled() ? '.' : '.min.';
        $extension_js = HAPPY_ADDONS_ASSETS . 'js/extension-reading-progress-bar' . $suffix . 'js';

        wp_enqueue_script(
            'happy-reading-progress-bar',
            $extension_js,
            ['jquery'],
            HAPPY_ADDONS_VERSION,
            true
        );

    }

	public function reading_progress_bar_controls( $element ) {

		if($this->elementor_get_setting('ha_rpb_enable') !== 'yes') return;

		$element->start_controls_section(
			'ha_rpb_single_section',
			[
				'label' => __( 'Reading Progress Bar', 'happy-elementor-addons' ) . ha_get_section_icon(),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);

		if($this->elementor_get_setting('ha_rpb_apply_globally') === 'globally') {
			$element->add_control(
				'ha_rpb_single_disable',
				[
					'label'        => __( 'Disable', 'happy-elementor-addons' ),
					'description'  => __( 'Disable Reading Progress Bar For This Page', 'happy-elementor-addons' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'no',
					'label_on'     => __( 'Yes', 'happy-elementor-addons' ),
					'label_off'    => __( 'No', 'happy-elementor-addons' ),
					'return_value' => 'yes',
				]
			);
		} else {
			$element->add_control(
				'ha_rpb_single_enable',
				[
					'label'        => __( 'Enable', 'happy-elementor-addons' ),
					'description'  => __( 'Enable Reading Progress Bar For This Page', 'happy-elementor-addons' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'no',
					'label_on'     => __( 'Yes', 'happy-elementor-addons' ),
					'label_off'    => __( 'No', 'happy-elementor-addons' ),
					'return_value' => 'yes',
				]
			);
		}


		$element->end_controls_section();
	}

	public function render_reading_progress_bar_html() {

        $post_id                = get_the_ID();
		$document               = [];
		$document_settings_data = [];
        $settings_data = [];

		if ( ! is_singular() && ! is_archive() ) {
			return;
		}

		$is_archive_template = $this->hm_is_theme_builder_archive_template();
		if( ! empty ( $is_archive_template ) ){
			$template_id = $this->hm_get_theme_builder_archive_template_id();
			if ( ! empty( $template_id ) ) {
				$post_id = $template_id;
			}
		}

		if ($this->prevent_reading_progress_bar_rendering($post_id)) {
			return;
		}

		$document = Plugin::$instance->documents->get( $post_id, false );

		if ( is_object( $document ) ) {
			$document_settings_data = $document->get_settings();
		}

		$rpb_enable = $this->elementor_get_setting('ha_rpb_enable');
		$global_enable = $this->elementor_get_setting('ha_rpb_apply_globally');

		$single_enable = isset( $document_settings_data['ha_rpb_single_enable'] ) ? $document_settings_data['ha_rpb_single_enable'] : 'no' ;
		$single_disable = isset( $document_settings_data['ha_rpb_single_disable'] ) ? $document_settings_data['ha_rpb_single_disable'] : 'no' ;

		//render rbp
		$reading_progress_is_enable = false;

		if ($rpb_enable === 'yes') {

			if ($global_enable === 'globally') {
				$display_condition = $this->elementor_get_setting('ha_rpb_global_display_condition');

				$current_post_type = get_post_type();

				if (is_array($display_condition) && in_array($current_post_type, $display_condition)) {
					$reading_progress_is_enable = true;
				}

				if ($single_disable === 'yes') {
					$reading_progress_is_enable = false;
				}
			} elseif ($global_enable === 'individually') {
				if ($single_enable === 'yes') {
					$reading_progress_is_enable = true;
				}
			}
		}

        $progress_bar_type = $this->elementor_get_setting('ha_rpb_type');
        $horizontal_position = $this->elementor_get_setting('ha_rpb_horizontal_position');
        $enable_horizontal_percentage = $this->elementor_get_setting('ha_rpb_enable_horizontal_percentage');
    	$enable_circle_percentage = $this->elementor_get_setting('ha_rpb_enable_circle_percentage');
		$rpb_vertical_position = $this->elementor_get_setting('ha_rpb_vertical_position');
        $settings_data = [
			'ha_rpb_enable' => $this->elementor_get_setting('ha_rpb_enable'),
			'progress_bar_type' => $progress_bar_type,
			'rpb_vertical_position' => $rpb_vertical_position,
		];

        if ( ha_elementor()->preview->is_preview_mode() ) {

			/*if ($global_enable === 'globally') {
				if($single_disable == 'yes') {
					return;
				}
			} elseif ($global_enable === 'individually') {
				if ($single_enable !== 'yes') {
					return;
				}
			}*/

			?>
				<div class="hm-crp-wrapper ha-reading-progress-bar" data-ha_rpbsettings="<?php echo esc_attr(json_encode($settings_data)); ?>" style="opacity:0">
					<svg class="hm-circular-progress" width="60" height="60" viewBox="0 0 100 100">
						<circle class="hm-progress-background" cx="50" cy="50" r="45"></circle>
						<circle class="hm-progress-circle" cx="50" cy="50" r="45"></circle>
					</svg>
					<div class="hm-progress-percent-text">0%</div>
				</div>

			<div id="hm_vrp_bar_wrapper" class="hm-vrp-bar-container ha-reading-progress-bar" data-ha_rpbsettings="<?php echo esc_attr(json_encode($settings_data)); ?>" style="opacity:0">
				<div class="hm-vrp-bar"></div>
			</div>

			<div id="hm_hrp_bar_wrapper" class="hm-hrp-bar-container ha-reading-progress-bar" data-ha_rpbsettings="<?php echo esc_attr(json_encode($settings_data)); ?>" style="opacity:0">
				<div class="hm-hrp-bar">
					<span class="hm-tool-tip hm-tool-tip-<?php echo esc_attr($horizontal_position); ?>">0%</span>
				</div>
			</div>

			<script>
				;(function($) {
					'use strict';

					let rpbContainer = $('.ha-reading-progress-bar');

					if(rpbContainer.rpbContainer <= 0) {
						return;
					}

					let rpbDefaultType = "<?php echo $progress_bar_type;  ?>";

					// Check display on
					let global_enable = "<?php echo $global_enable; ?>";
					let single_enable = "<?php echo $single_enable; ?>";
					let single_disable = "<?php echo $single_disable; ?>";

					if( global_enable == 'globally' ) {
						if( single_disable !== 'yes' ) {
							if( rpbDefaultType == 'horizontal' ) {
								$('.ha-reading-progress-bar').css({'opacity':1, 'transition':'opacity 0.3s'});
								$('.hm-hrp-bar-container').css({'opacity':1, 'transition':'opacity 0.3s'});
								$('.hm-vrp-bar-container').css({'opacity':0, 'transition':'opacity 0.3s'});
								$('.hm-crp-wrapper').css({'opacity':0, 'transition':'opacity 0.3s'});
							} else if ( rpbDefaultType == 'vertical' ) {
								$('.ha-reading-progress-bar').css({'opacity':1, 'transition':'opacity 0.3s'});
								$('.hm-hrp-bar-container').css({'opacity':0, 'transition':'opacity 0.3s'});
								$('.hm-vrp-bar-container').css({'opacity':1, 'transition':'opacity 0.3s'});
								$('.hm-crp-wrapper').css({'opacity':0, 'transition':'opacity 0.3s'});
							} else if ( rpbDefaultType == 'circle' ) {
								$('.ha-reading-progress-bar').css({'opacity':1, 'transition':'opacity 0.3s'});
								$('.hm-hrp-bar-container').css({'opacity':0, 'transition':'opacity 0.3s'});
								$('.hm-vrp-bar-container').css({'opacity':0, 'transition':'opacity 0.3s'});
								$('.hm-crp-wrapper').css({'opacity':1, 'transition':'opacity 0.3s'});
							}
						} else {
							$('.ha-reading-progress-bar').css({'opacity':0, 'transition':'opacity 0.3s'});
							return;
						}
					} else if ( global_enable == 'individually' ) {
						if ( single_enable !== 'yes' ) {
							$('.ha-reading-progress-bar').css({'opacity':0, 'transition':'opacity 0.3s'});
							return;
						} else {
							if( rpbDefaultType == 'horizontal' ) {
								$('.ha-reading-progress-bar').css({'opacity':1, 'transition':'opacity 0.3s'});
								$('.hm-hrp-bar-container').css({'opacity':1, 'transition':'opacity 0.3s'});
								$('.hm-vrp-bar-container').css({'opacity':0, 'transition':'opacity 0.3s'});
								$('.hm-crp-wrapper').css({'opacity':0, 'transition':'opacity 0.3s'});
							} else if ( rpbDefaultType == 'vertical' ) {
								$('.ha-reading-progress-bar').css({'opacity':1, 'transition':'opacity 0.3s'});
								$('.hm-hrp-bar-container').css({'opacity':0, 'transition':'opacity 0.3s'});
								$('.hm-vrp-bar-container').css({'opacity':1, 'transition':'opacity 0.3s'});
								$('.hm-crp-wrapper').css({'opacity':0, 'transition':'opacity 0.3s'});
							} else if ( rpbDefaultType == 'circle' ) {
								$('.ha-reading-progress-bar').css({'opacity':1, 'transition':'opacity 0.3s'});
								$('.hm-hrp-bar-container').css({'opacity':0, 'transition':'opacity 0.3s'});
								$('.hm-vrp-bar-container').css({'opacity':0, 'transition':'opacity 0.3s'});
								$('.hm-crp-wrapper').css({'opacity':1, 'transition':'opacity 0.3s'});
							}
						}
					}

					// check type
					if( rpbDefaultType == 'horizontal' ) {
						$('.hm-hrp-bar-container').css({'opacity':1, 'transition':'opacity 0.3s'});
						$('.hm-vrp-bar-container').css({'opacity':0, 'transition':'opacity 0.3s'});
						$('.hm-crp-wrapper').css({'opacity':0, 'transition':'opacity 0.3s'});
					} else if ( rpbDefaultType == 'vertical' ) {
						$('.hm-hrp-bar-container').css({'opacity':0, 'transition':'opacity 0.3s'});
						$('.hm-vrp-bar-container').css({'opacity':1, 'transition':'opacity 0.3s'});
						$('.hm-crp-wrapper').css({'opacity':0, 'transition':'opacity 0.3s'});
					} else if ( rpbDefaultType == 'circle' ) {
						$('.hm-hrp-bar-container').css({'opacity':0, 'transition':'opacity 0.3s'});
						$('.hm-vrp-bar-container').css({'opacity':0, 'transition':'opacity 0.3s'});
						$('.hm-crp-wrapper').css({'opacity':1, 'transition':'opacity 0.3s'});
					}

					window.addEventListener('message',function(e) {
						let data = e.data;

						if( 'rpbMessage' == data.check ) {

							if (e.origin != window.origin) {
								return;
							}
							if (e.source.location.href != window.parent.location.href) {
								return;
							}

							let changeValue = data.changeValue;
							let changeItem = data.changeItem;
							let rpbDefaultType = "<?php echo $progress_bar_type;  ?>";

							// Check enable
							if (changeItem[0] == 'ha_rpb_enable') {
								if ( changeValue == 'yes' ) {
									if( rpbDefaultType == 'horizontal' ) {
										$('.ha-reading-progress-bar').css({'opacity':1, 'transition':'opacity 0.3s'});
										$('.hm-hrp-bar-container').css({'opacity':1, 'transition':'opacity 0.3s'});
										$('.hm-vrp-bar-container').css({'opacity':0, 'transition':'opacity 0.3s'});
										$('.hm-crp-wrapper').css({'opacity':0, 'transition':'opacity 0.3s'});
									} else if ( rpbDefaultType == 'vertical' ) {
										$('.ha-reading-progress-bar').css({'opacity':1, 'transition':'opacity 0.3s'});
										$('.hm-hrp-bar-container').css({'opacity':0, 'transition':'opacity 0.3s'});
										$('.hm-vrp-bar-container').css({'opacity':1, 'transition':'opacity 0.3s'});
										$('.hm-crp-wrapper').css({'opacity':0, 'transition':'opacity 0.3s'});
									} else if ( rpbDefaultType == 'circle' ) {
										$('.ha-reading-progress-bar').css({'opacity':1, 'transition':'opacity 0.3s'});
										$('.hm-hrp-bar-container').css({'opacity':0, 'transition':'opacity 0.3s'});
										$('.hm-vrp-bar-container').css({'opacity':0, 'transition':'opacity 0.3s'});
										$('.hm-crp-wrapper').css({'opacity':1, 'transition':'opacity 0.3s'});
									}
								} else {
									$('.ha-reading-progress-bar').css({'opacity':0, 'transition':'opacity 0.3s'});
								}
							}

							// Check display on
							if ( changeItem[0] == 'ha_rpb_apply_globally' ) {
								let single_enable = "<?php echo $single_enable; ?>";
								let single_disable = "<?php echo $single_disable; ?>";

								if( changeValue == 'globally' ) {
									if( single_disable !== 'yes' ) {
										if( rpbDefaultType == 'horizontal' ) {
											$('.ha-reading-progress-bar').css({'opacity':1, 'transition':'opacity 0.3s'});
											$('.hm-hrp-bar-container').css({'opacity':1, 'transition':'opacity 0.3s'});
											$('.hm-vrp-bar-container').css({'opacity':0, 'transition':'opacity 0.3s'});
											$('.hm-crp-wrapper').css({'opacity':0, 'transition':'opacity 0.3s'});
										} else if ( rpbDefaultType == 'vertical' ) {
											$('.ha-reading-progress-bar').css({'opacity':1, 'transition':'opacity 0.3s'});
											$('.hm-hrp-bar-container').css({'opacity':0, 'transition':'opacity 0.3s'});
											$('.hm-vrp-bar-container').css({'opacity':1, 'transition':'opacity 0.3s'});
											$('.hm-crp-wrapper').css({'opacity':0, 'transition':'opacity 0.3s'});
										} else if ( rpbDefaultType == 'circle' ) {
											$('.ha-reading-progress-bar').css({'opacity':1, 'transition':'opacity 0.3s'});
											$('.hm-hrp-bar-container').css({'opacity':0, 'transition':'opacity 0.3s'});
											$('.hm-vrp-bar-container').css({'opacity':0, 'transition':'opacity 0.3s'});
											$('.hm-crp-wrapper').css({'opacity':1, 'transition':'opacity 0.3s'});
										}
									}
								} else if ( changeValue == 'individually' ) {
									if ( single_enable !== 'yes' ) {
										$('.ha-reading-progress-bar').css({'opacity':0, 'transition':'opacity 0.3s'});
										return;
									}
								}

							}

							// Check type
							if ( changeItem[0] == 'ha_rpb_type' ) {
								rpbDefaultType = changeValue;
								if( changeValue == 'horizontal' ) {
									$('.hm-hrp-bar-container').css({'opacity':1, 'transition':'opacity 0.3s'});
									$('.hm-vrp-bar-container').css({'opacity':0, 'transition':'opacity 0.3s'});
									$('.hm-crp-wrapper').css({'opacity':0, 'transition':'opacity 0.3s'});
								} else if ( changeValue == 'vertical' ) {
									$('.hm-hrp-bar-container').css({'opacity':0, 'transition':'opacity 0.3s'});
									$('.hm-vrp-bar-container').css({'opacity':1, 'transition':'opacity 0.3s'});
									$('.hm-crp-wrapper').css({'opacity':0, 'transition':'opacity 0.3s'});
									let vertical_position = "<?php echo $rpb_vertical_position; ?>";
									if(vertical_position == 'right') {
										$('body').addClass('no-scroll');
									} else {
										$('body').removeClass('no-scroll');
									}
								} else if ( changeValue == 'circle' ) {
									$('.hm-hrp-bar-container').css({'opacity':0, 'transition':'opacity 0.3s'});
									$('.hm-vrp-bar-container').css({'opacity':0, 'transition':'opacity 0.3s'});
									$('.hm-crp-wrapper').css({'opacity':1, 'transition':'opacity 0.3s'});
								}

							}

							// Start scrolling
							$(window).scroll(function () {
								let scrollPercent = 0;
								let hmSt = $(window).scrollTop() || 0,
									hmDt = $(document).height() || 1,
									hmCt = $(window).height() || 1;
								scrollPercent = ( hmSt / (hmDt - hmCt) ) * 100;
								let position = scrollPercent.toFixed(0);

								if (scrollPercent > 100) {
									scrollPercent = 100;
								}

								if( rpbDefaultType == 'horizontal' ) {
									$('.hm-hrp-bar').css({'display': 'flex'});
									$('.hm-hrp-bar').width(position + '%');

									if (position > 1 && scrollPercent > 0 ) {
										$('.hm-tool-tip').css({'opacity':1, 'transition':'opacity 0.3s'});
										$('.hm-tool-tip').text(position + '%');
										if( position >= 98 ) {
											$('.hm-tool-tip').css({'right':'5px'});
										} else {
											$('.hm-tool-tip').css({'right':'-28px'});
										}
									} else {
										$('.hm-tool-tip').css({'opacity':0, 'transition':'opacity 0.3s'});
										$('.hm-tool-tip').text('0%');
									}
								} else if ( rpbDefaultType == 'vertical' ) {
									$('.hm-vrp-bar').css({
										'display': 'flex',
									});

									if( scrollPercent > 0 && position > 1) {
										$('.hm-vrp-bar').height(position + '%');
									} else {
										$('.hm-vrp-bar').height('0%');
									}
								} else if (rpbDefaultType == 'circle') {
									let circleRadius = 45;
									let circumference = 2 * Math.PI * circleRadius;

									let offset = Math.round(circumference - (scrollPercent / 100) * circumference);

									if( scrollPercent >= 0 ) {
										$('.hm-progress-circle').css('stroke-dashoffset', offset.toFixed(2));
										$('.hm-progress-percent-text').text(`${scrollPercent.toFixed(0)}%`);
									}
								}

							});

							// check horizontal tool tip
							if ( changeItem[0] == 'ha_rpb_horizontal_position' ) {
								if ( changeValue == 'bottom' ) {
									$('.hm-hrp-bar .hm-tool-tip').removeClass('hm-tool-tip-top');
									$('.hm-hrp-bar .hm-tool-tip').addClass('hm-tool-tip-bottom');
								} else if( changeValue == 'top' ) {
									$('.hm-hrp-bar .hm-tool-tip').removeClass('hm-tool-tip-bottom');
									$('.hm-hrp-bar .hm-tool-tip').addClass('hm-tool-tip-top');
								}
							}

							// Check vertical position
							if ( changeItem[0] == 'ha_rpb_vertical_position' ) {
								if ( changeValue == 'right' ) {
									$('body').addClass('no-scroll');
								} else if( changeValue == 'left' ) {
									$('body').removeClass('no-scroll');
								}
							}

						}

					});

				}(jQuery));
			</script>

		<?php }

		if ( ! ha_elementor()->preview->is_preview_mode() ) {

			if( ! $reading_progress_is_enable ) {
				return;
			}

			if( 'circle' === $progress_bar_type ) { ?>
				<div class="hm-crp-wrapper ha-reading-progress-bar" data-ha_rpbsettings="<?php echo esc_attr(json_encode($settings_data)); ?>">
					<svg class="hm-circular-progress" width="60" height="60" viewBox="0 0 100 100">
						<circle class="hm-progress-background" cx="50" cy="50" r="45"></circle>
						<circle class="hm-progress-circle" cx="50" cy="50" r="45"></circle>
					</svg>
					<?php if( 'yes' == $enable_circle_percentage){ ?>
						<div class="hm-progress-percent-text">0%</div>
					<?php } ?>
				</div>
			<?php } else if ('vertical' === $progress_bar_type) { ?>
			<div id="hm_vrp_bar_wrapper" class="hm-vrp-bar-container ha-reading-progress-bar" data-ha_rpbsettings="<?php echo esc_attr(json_encode($settings_data)); ?>">
				<div class="hm-vrp-bar"></div>
			</div>
			<?php } else { ?>
			<div id="hm_hrp_bar_wrapper" class="hm-hrp-bar-container ha-reading-progress-bar" data-ha_rpbsettings="<?php echo esc_attr(json_encode($settings_data)); ?>">
				<div class="hm-hrp-bar">
					<span class="hm-tool-tip hm-tool-tip-<?php echo esc_attr($horizontal_position); ?>">0%</span>
				</div>
			</div>
		<?php }
		} ?>

		<style>
			.hm-hrp-bar-container {
				width: 100%;
				background: transparent;
				position: fixed;
				top: 0;
				left: 0;
				height: 8px;
				max-height: 100px;
				z-index: 999999;
			}

			.hm-hrp-bar {
				position: relative;
				height: 100%;
				background-color: #e2498a;
				font-size: 14px;
				font-weight: 500;
				color: #FFFFFF;
				display: none;
				justify-content: center;
				align-items: center;
			}

			.hm-hrp-bar .hm-tool-tip {
				right: -28px;
				margin-left: 15px;
				position: absolute;
				padding: 3px 0;
				width: 60px;
				border-radius: 5px;
				background: #444;
				color: #fff;
				font-size: 13px;
				text-align: center;
				opacity: 0;
				transition: opacity 0.3s;
				font-weight: 500;
				font-style: normal;
			}

			.hm-hrp-bar .hm-tool-tip:after {
				content: '';
				border-width: 5px;
				position: absolute;
				border-style: solid;
				right: 40%;
			}

			.hm-hrp-bar .hm-tool-tip-top {
				bottom: -30px;
			}

			.hm-hrp-bar .hm-tool-tip-top:after {
				bottom: 100%;
				border-color: transparent transparent #444 transparent;
			}

			.hm-hrp-bar .hm-tool-tip-bottom {
				top: -31px;
			}

			.hm-hrp-bar .hm-tool-tip-bottom:after {
				bottom: -10px;
				border-color: #444 transparent transparent transparent;
			}

			.hm-vrp-bar-container {
				position: fixed;
				top: 0;
				right: 0;
				background: transparent;
				width: 8px;
				height: 100%;
				max-width: 100px;
				z-index: 99999;
			}

			.hm-vrp-bar {
				position: absolute;
				top: 0;
				right: 0;
				display: none;
				max-height: 100%;
				width: 100%;
				background-color: #e2498a;
				max-width: 100px;
			}

			.hm-crp-wrapper {
				position: fixed;
				top: 20px;
				right: 20px;
				width: 60px;
				height: 60px;
				max-width: 150px;
				max-height: 150px;
				z-index: 99999;
			}

			.hm-crp-wrapper .hm-circular-progress {
				transform: rotate(-90deg);
				border-radius: 100%;
			}

			.hm-crp-wrapper .hm-circular-progress .hm-progress-background {
				fill: none;
				stroke: #e6e6e6;
				stroke-width: 5;
			}

			.hm-crp-wrapper .hm-circular-progress .hm-progress-circle {
				fill: none;
				stroke: #e2498a;
				stroke-width: 5;
				stroke-dasharray: 283;
				stroke-dashoffset: 283;
				transition: stroke-dashoffset 0.4s ease;
			}

			.hm-crp-wrapper .hm-progress-percent-text {
				position: absolute;
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%);
				font-size: 13px;
				font-weight: 500;
				color: #000000;
			}

			body.no-scroll {
				scrollbar-width: 0px;
			}

			body.no-scroll::-webkit-scrollbar {
				width: 0px;
				background: transparent;
			}
		</style>

	<?php }

	public function init_site_settings( \Elementor\Core\Kits\Documents\Kit $kit ) {
		$kit->register_tab( 'ha-reading-progress-bar-kit-settings', Reading_Progress_Bar_Kit_Setings::class );
	}

	public function elementor_get_setting( $setting_id ) {

		$return = '';

		if ( ! isset( $hello_elementor_settings['kit_settings'] ) ) {
			if ( ha_elementor()->preview->is_preview_mode() ) {
				// get auto save data
				$kit = Plugin::$instance->documents->get_doc_for_frontend( Plugin::$instance->kits_manager->get_active_id() );
			} else {
				$kit = Plugin::$instance->documents->get( Plugin::$instance->kits_manager->get_active_id(), true );
			}
			$hello_elementor_settings['kit_settings'] = $kit->get_settings();
		}

		if ( isset( $hello_elementor_settings['kit_settings'][ $setting_id ] ) ) {
			$return = $hello_elementor_settings['kit_settings'][ $setting_id ];
		}

		return $return;
	}

    public function prevent_reading_progress_bar_rendering($post_id)
    {
        $get_template_name = get_post_meta($post_id, '_elementor_template_type', true);
        $template_list = [
            'header',
            'footer',
            'search-results',
            'error-404',
            'section',
        ];

        return in_array($get_template_name, $template_list);
    }

	public function hm_is_theme_builder_archive_template( $type = 'archive' ): bool{
		$is_archive_template = false;

		if ( class_exists( 'ElementorPro\Modules\ThemeBuilder\Module' ) ) {
			$conditions_manager = \ElementorPro\Plugin::instance()->modules_manager->get_modules( 'theme-builder' )->get_conditions_manager();

			if( ! empty( $conditions_manager->get_documents_for_location( 'archive') ) || ! empty( $conditions_manager->get_documents_for_location( 'single') ) ) {
				$is_archive_template = true;
			}
		}

		return $is_archive_template;
	}

	public function hm_get_theme_builder_archive_template_id(){
		$template_id = 0;
		if ( class_exists( 'ElementorPro\Modules\ThemeBuilder\Module' ) ) {
			if ( $this->hm_is_theme_builder_archive_template() ) {
				$page_body_classes = get_body_class();

				if( is_array( $page_body_classes ) && count( $page_body_classes ) ){
					foreach( $page_body_classes as $page_body_class){
						if ( strpos( $page_body_class, 'elementor-page-' ) !== FALSE ) {
							$template_id = intval( str_replace('elementor-page-', '', $page_body_class) );
						}
					}
				}
			}
		}

		return $template_id;
	}

}
