"use strict";

;

(function ($) {
  'use strict';

  var $window = $(window),
      debounce = function debounce(func, wait, immediate) {
    var timeout;
    return function () {
      var context = this,
          args = arguments;

      var later = function later() {
        timeout = null;
        if (!immediate) func.apply(context, args);
      };

      var callNow = immediate && !timeout;
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
      if (callNow) func.apply(context, args);
    };
  };

  $window.on('elementor/frontend/init', function () {
    var ModuleHandler = elementorModules.frontend.handlers.Base,
        EqualHeightHandler;
    EqualHeightHandler = ModuleHandler.extend({
      CACHED_ELEMENTS: [],
      isEqhEnabled: function isEqhEnabled() {
        return this.getElementSettings('_ha_eqh_enable') === 'yes' && $.fn.matchHeight;
      },
      isDisabledOnDevice: function isDisabledOnDevice() {
        var windowWidth = $window.outerWidth(),
            mobileWidth = elementorFrontendConfig.breakpoints.md,
            tabletWidth = elementorFrontendConfig.breakpoints.lg;

        if ('yes' == this.getElementSettings('_ha_eqh_disable_on_mobile') && windowWidth < mobileWidth) {
          return true;
        }

        if ('yes' == this.getElementSettings('_ha_eqh_disable_on_tablet') && windowWidth >= mobileWidth && windowWidth < tabletWidth) {
          return true;
        }

        return false;
      },
      getEqhTo: function getEqhTo() {
        return this.getElementSettings('_ha_eqh_to') || 'widget';
      },
      getEqhWidgets: function getEqhWidgets() {
        return this.getElementSettings('_ha_eqh_widget') || [];
      },
      getTargetElements: function getTargetElements() {
        var _this = this;

        return this.getEqhWidgets().map(function (widget) {
          return _this.$element.find('.elementor-widget-' + widget + ' .elementor-widget-container');
        });
      },
      bindEvents: function bindEvents() {
        if (this.isEqhEnabled()) {
          this.run();
          $window.on('resize scroll orientationchange', debounce(this.run.bind(this), 80));
        }
      },
      onElementChange: debounce(function (prop, ele) {
        if (prop.indexOf('_ha_eqh') === -1) {
          return;
        }

        this.unbindMatchHeight(true);
        this.run();
      }, 100),
      unbindMatchHeight: function unbindMatchHeight(isCachedOnly) {
        if (isCachedOnly) {
          this.CACHED_ELEMENTS.forEach(function ($el) {
            $el.matchHeight({
              remove: true
            });
          });
          this.CACHED_ELEMENTS = [];
        } else {
          this.getTargetElements().forEach(function ($el) {
            $el && $el.matchHeight({
              remove: true
            });
          });
        }
      },
      run: function run() {
        var _this = this;

        if (this.isDisabledOnDevice()) {
          this.unbindMatchHeight();
        } else {
          this.getTargetElements().forEach(function ($el) {
            if ($el.length) {
              $el.matchHeight({
                byRow: false
              });

              _this.CACHED_ELEMENTS.push($el);
            }
          });
        }
      }
    });
    elementorFrontend.hooks.addAction('frontend/element_ready/section', function ($scope) {
      elementorFrontend.elementsHandler.addHandler(EqualHeightHandler, {
        $element: $scope
      });
    });
  });
})(jQuery);