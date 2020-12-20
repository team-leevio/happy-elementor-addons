;(function( $ ) {
	'use strict';
	var $window = $(window),
		debounce = function(func, wait, immediate) {
			var timeout;
			return function() {
				var context = this, args = arguments;
				var later = function() {
					timeout = null;
					if (!immediate) func.apply(context, args);
				};
				var callNow = immediate && !timeout;
				clearTimeout(timeout);
				timeout = setTimeout(later, wait);
				if (callNow) func.apply(context, args);
			};
		},
		rcssescape = /([\0-\x1f\x7f]|^-?\d)|^-$|[^\0-\x1f\x7f-\uFFFF\w-]/g,
		fcssescape = function( ch, asCodePoint ) {
			if ( asCodePoint ) {

				// U+0000 NULL becomes U+FFFD REPLACEMENT CHARACTER
				if ( ch === "\0" ) {
					return "\uFFFD";
				}

				// Control characters and (dependent upon position) numbers get escaped as code points
				return ch.slice( 0, -1 ) + "\\" +
					ch.charCodeAt( ch.length - 1 ).toString( 16 ) + " ";
			}

			// Other potentially-special ASCII characters get backslash-escaped
			return "\\" + ch;
		},
		escapeSelector = function(selector) {
			return selector.replace(rcssescape, fcssescape)
		};

	$window.on('elementor/frontend/init', function() {
		var ModuleHandler = elementorModules.frontend.handlers.Base,
			EqualHeightHandler;

		EqualHeightHandler = ModuleHandler.extend({
			isEqhEnabled: function() {
				return (
					this.getElementSettings( '_ha_eqh_enable' ) === 'yes' &&
					$.fn.matchHeight &&
					!this.isEdit
				);
			},

			isDisabledOnDevice: function() {
				var windowWidth = $(window).width(),
					mobileWidth = elementorFrontendConfig.breakpoints.md,
					tabletWidth = elementorFrontendConfig.breakpoints.lg;

				if (this.getElementSettings('_ha_eqh_disable_on_mobile') && windowWidth < mobileWidth) {
					return true;
				}

				if (this.getElementSettings('_ha_eqh_disable_on_tablet') && windowWidth >= mobileWidth && windowWidth < tabletWidth) {
					return true;
				}

				return false;
			},

			getDefaultSettings: function() {
				return {
					widget: this.getElementSettings('_ha_eqh_widget'),
					selector: this.getElementSettings('_ha_eqh_selector')
				};
			},

			getEqhTo: function() {
				return this.getElementSettings('_ha_eqh_to') || 'widget';
			},

			getEqhSelectors: function() {
				return this.getSettings('selector') || '';
			},

			getEqhWidgets: function() {
				return this.getSettings('widget') || [];
			},

			getTargetElements: function() {
				var _this = this;
				if (this.getEqhTo() === 'selector') {
					return this.getEqhSelectors().split(',').map(function(selector) {
						selector = $.trim(selector);
						if (selector.indexOf('.') !== 0 && selector.indexOf('#') !== 0) {
							selector = escapeSelector(selector);
						}
						return _this.$element.find(selector);
					});
				} else {
					return this.getEqhWidgets().map(function(widget) {
						return widget && _this.$element.find('.elementor-widget-'+widget + ' .elementor-widget-container');
					});
				}
			},

			getArgs: function() {
				return this.getEqhTo() === 'selector' ? {byRow: false} : {};
			},

			bindEvents: function () {
				if (this.isEqhEnabled()) {
					this.run();
				}

				$window.on('resize orientationchange', debounce(this.run.bind(this), 80));
			},

			onElementChange: debounce(function(prop, ele) {
				if (prop.indexOf('_ha_eqh') === -1 || this.getEqhTo() !== 'widget') {
					return;
				}

				if (['_ha_eqh_widget', '_ha_eqh_selector', '_ha_eqh_to'].indexOf(prop) !== -1) {
					var _prop, changed, setting;
					if (prop === '_ha_eqh_to') {
						_prop = this.getEqhTo() === 'widget' ? '_ha_eqh_selector' : '_ha_eqh_widget';
						changed = ele.container.settings._previousAttributes[_prop]
					} else {
						_prop = prop;
						changed = ele.container.oldValues[_prop];
					}

					setting = _prop.replace('_ha_eqh_', '');
					this.setSettings(setting, changed);
					this.unbindMatchHeight();

					// Restore
					if (prop === '_ha_eqh_to') {
						_prop = this.getEqhTo() === 'widget' ? '_ha_eqh_widget' : '_ha_eqh_selector';
					} else {
						_prop = prop;
					}
					if (_prop === '_ha_eqh_selector') {
						changed = this.getElementSettings(_prop) || '';
					} else {
						changed = this.getElementSettings(_prop) || [];
					}

					setting = this.getEqhTo();
					this.setSettings(setting, changed);
				}

				this.run();
			}, 100),

			unbindMatchHeight: function() {
				this.getTargetElements().forEach(function($el) {
					$el && $el.matchHeight({remove: true});
				});
			},

			run: function() {
				if (this.isDisabledOnDevice()) {
					this.unbindMatchHeight();
					return;
				}

				var _this = this;

				this.getTargetElements().forEach(function($el) {
					$el && $el.matchHeight(_this.getArgs());
				});
			},
		});

		elementorFrontend.hooks.addAction( 'frontend/element_ready/section', function( $scope ) {
			elementorFrontend.elementsHandler.addHandler( EqualHeightHandler, { $element: $scope });
		});
	});

}( jQuery ));
