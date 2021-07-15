; (function ($) {
    'use strict';

    var $window = $(window)

    $.fn.getHappySettings = function () {
        return this.data('happy-settings');
    };

    function debounce(func, wait, immediate) {
        var timeout;
        return function () {
            var context = this, args = arguments;
            var later = function () {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }

    $window.on('elementor/frontend/init', function () {

		var AdvancedTooltip = elementorModules.frontend.handlers.Base.extend({

			onInit: function () {
				elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
				if ( this.$element.hasClass( "ha-advanced-tooltip-enable" ) ) {
					this.$element.append("<span class='ha-advanced-tooltip-content'></span>");
					this.run();
				}
			},
			getReadySettings: function () {
				if ( this.$element.hasClass( "ha-advanced-tooltip-enable" ) ) {

					var settings = {
						trigger: this.getElementSettings('ha_tooltip_section_trigger'),
						content: this.getElementSettings('ha_tooltip_section_content'),
						duration: this.getElementSettings('ha_tooltip_section_duration') || 500,
						size: this.getElementSettings('ha_tooltip_section_size') || 'default',
						showArrow: this.getElementSettings('ha_tooltip_section_arrow') || false,
						position: this.getElementSettings('ha_tooltip_section_position'),
						width: 100,
						delay: this.getElementSettings('ha_tooltip_section_delay') || 100,
						hideDelay: this.getElementSettings('ha_tooltip_section_delay') || 100,
					};
					return $.extend({}, settings);
				}
			},
			onElementChange: function (e) {
				console.log(e);
				var style_controls = ['ha_advanced_tooltip_enable', 'ha_tooltip_section_content', 'ha_tooltip_section_position', 'ha_tooltip_section_arrow', 'ha_tooltip_section_duration', 'ha_tooltip_section_delay', 'ha_tooltip_section_size', 'ha_tooltip_section_background_color', 'ha_tooltip_section_color'];
				if(style_controls.includes(e)) {
					if ( !this.$element.hasClass( "ha-advanced-tooltip-enable" ) ) {
						this.$element.find('.ha-advanced-tooltip-content').remove();
					}
					this.run();
				}
			},
			run: function () {
				var $scope = this.$element;
				if ( this.$element.hasClass( "ha-advanced-tooltip-enable" ) ) {
					var settings = this.getReadySettings();
					var content = $scope.find('.ha-advanced-tooltip-content');
					content.text(settings.content);
					content.css('transition', 'opacity '+settings.duration+'ms ease '+settings.delay+'ms');
					
					if( !settings.showArrow) {
						content.addClass('no-arrow');
					}

					if (settings.trigger == 'click') {
						this.$element.on('click', function() {
							if ( content.hasClass('show')){
								content.removeClass('show');
							}else {
								content.addClass('show');
							}
						});
					}else if (settings.trigger == 'hover') {
						this.$element.on('mouseenter', function() {
							content.addClass('show');
						});
						this.$element.on('mouseleave', function() {
							content.removeClass('show');
						});
					}
				}
			}
		});

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/widget',
			function ($scope) {
				if($scope.hasClass('ha-advanced-tooltip-enable')) {
					elementorFrontend.elementsHandler.addHandler(AdvancedTooltip, {
						$element: $scope,
					});
				}
			}
		);

    });

}(jQuery));
