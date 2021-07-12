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

        var GlobalWidgetBase = elementorModules.frontend.handlers.Base.extend({

            onInit: function () {
                elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
                if (this.$element.hasClass("ha-advanced-tooltip-enable")) {
                    this.run();
                }
            },
            getReadySettings: function () {
                if (this.$element.hasClass("ha-advanced-tooltip-enable")) {
                    var $tooltipUnqId = this.$element.data('id');

                    var settings = {
                        content: this.getElementSettings('ha_a_tooltip_section_content'),
                        speed: this.getElementSettings('ha_a_tooltip_section_duration') || 300,
                        size: this.getElementSettings('ha_a_tooltip_section_size') || 'default',
                        background: '#FFFFFF00',
                        color: '#FFFFFF00',
                        showArrow: this.getElementSettings('ha_a_tooltip_section_arrow') || false,
                        position: this.getElementSettings('ha_a_tooltip_section_position'),
                        // width: this.getElementSettings('null') || 100,
                        width: 100,
                        // maxWidth: this.getElementSettings('null') || '',
                        delay: this.getElementSettings('ha_a_tooltip_section_delay') || 100,
                        hideDelay: this.getElementSettings('ha_a_tooltip_section_delay') || 100,
                        // animationIn: this.getElementSettings('ha_a_tooltip_section_animation') || '',
                        // animationOut: this.getElementSettings('ha_a_tooltip_section_animation') || '',
                        // offsetX: this.getElementSettings('null') || 0,
                        // offsetY: this.getElementSettings('null') || 0,
                        tooltipHover: true,
                        templateEngineFunc: function (content) {
                            return '<span class="ha-tooltip-content-' + $tooltipUnqId + '">' + content + '</span>';
                        },
                        useTitle: false,
                        onShow: function ($element, element) {
                            $('.ha-tooltip-content-' + $tooltipUnqId).parent().parent().addClass('ha-tooltip-wrapper-' + $tooltipUnqId);
                        },
                        // onHide: this.getElementSettings('null') || null
                    };
                    return $.extend({}, settings);
                }
            },
            onElementChange: function (e) {
                console.log(e);
                var style_controls = ['ha_a_tooltip_section_typography', 'ha_a_tooltip_section_border_color', 'ha_a_tooltip_section_border_radius', 'ha_a_tooltip_section_padding', 'ha_a_tooltip_section_box_shadow', 'ha_a_tooltip_section_width', 'ha_a_tooltip_section_background_color', 'ha_a_tooltip_section_color'];
                var $currentTooltip = '.elementor-element-' + this.$element.data('id');
                if (!style_controls.includes(e)) {
                    if (this.$element.hasClass("ha-advanced-tooltip-enable")) {
                        this.run();
                    }
                    else {
                        // Destroy tipso tooltip
                        $($currentTooltip).tipso('destroy');
                    }
                }
            },
            run: function () {
                var $currentTooltip = '.elementor-element-' + this.$element.data('id');
                if (this.$element.hasClass("ha-advanced-tooltip-enable")) {
                    console.log(this.getReadySettings());
                    // Run tipso tooltip
                    $($currentTooltip).tipso(this.getReadySettings());
                    this.$element.removeClass('tipso_style');
                }
            }
        });

        elementorFrontend.hooks.addAction(
            'frontend/element_ready/widget',
            function ($scope) {
                elementorFrontend.elementsHandler.addHandler(GlobalWidgetBase, {
                    $element: $scope,
                });
            }
        );

    });

}(jQuery));
