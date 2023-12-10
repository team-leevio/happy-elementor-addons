"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); enumerableOnly && (symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; })), keys.push.apply(keys, symbols); } return keys; }
function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = null != arguments[i] ? arguments[i] : {}; i % 2 ? ownKeys(Object(source), !0).forEach(function (key) { _defineProperty(target, key, source[key]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } return target; }
function _defineProperty(obj, key, value) { key = _toPropertyKey(key); if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
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
          // console.log(widget);
          // console.log(_this.$element);
          // console.log(_this.$element.data("element_type"));
          if (false && _this.$element.data("element_type") === "container") {
            var $key = 0;
            var $widgets = {};
            var $container = _this.$element;
            var cls = '.elementor-widget-' + widget + ' .elementor-widget-container';

            /*
            Container > e-con-inner > conteiner > widget
            Container > conteiner > widget
            Container > e-con-inner > conteiner > e-con-inner  > widget
            Container > conteiner > e-con-inner  > widget
            Container > widget
            Container > e-con-inner > widget
            */
            // console.group(_this.$element.data("element_type"));

            console.log($container);
            /* console.log($container.find(' > .e-con-inner > div[data-element_type="container"] > '+cls));
            console.log($container.find(' > div[data-element_type="container"] > '+cls));
            	console.log($container.find(' > .e-con-inner > div[data-element_type="container"] > .e-con-inner > '+cls));
            console.log($container.find(' > div[data-element_type="container"] > .e-con-inner > '+cls));
            	console.log($container.find(' > .e-con-inner > '+cls));
            console.log($container.find(' > '+cls)); */
            // console.log($container.find(' > div[data-element_type="container"]').length);

            if ($container.find(' > .e-con-inner > div[data-element_type="container"] > ' + cls).length) {
              $widgets = $container.find(' > .e-con-inner > div[data-element_type="container"] > ' + cls);
              // $container.find(' > .e-con-inner > div[data-element_type="container"] > '+cls).each(function(){
              // 	// console.log($(this));
              // 	let id = $(this).parent().data('id');
              // 	// $widgets.push($(this)[0]);
              // 	if( ! $widgets.hasOwnProperty(id) ){
              // 		$widgets[id] = $(this)[0];
              // 	}
              // 	$key += 1;
              //   });
            }

            if ($container.find(' > div[data-element_type="container"] > ' + cls).length) {
              if ($widgets.length) {
                var _$key = $widgets.length;
                $container.find(' > div[data-element_type="container"] > ' + cls).each(function () {
                  // console.log($(this).parent().data('id'));
                  var id = $(this).parent().data('id');
                  // $widgets.push($(this)[0]);
                  if (!$widgets.hasOwnProperty(_$key)) {
                    $widgets[_$key] = $(this)[0];
                  }
                  _$key += 1;
                });
                $widgets.length = _$key;
              } else {
                $widgets = $container.find(' > div[data-element_type="container"] > ' + cls);
              }
            }
            if ($container.find(' > .e-con-inner > div[data-element_type="container"] > .e-con-inner > ' + cls).length) {
              if ($widgets.length) {
                var _$key2 = $widgets.length;
                $container.find(' > .e-con-inner > div[data-element_type="container"] > .e-con-inner > ' + cls).each(function () {
                  // console.log($(this));
                  var id = $(this).parent().data('id');
                  // $widgets.push($(this)[0]);
                  if (!$widgets.hasOwnProperty(_$key2)) {
                    $widgets[_$key2] = $(this)[0];
                  }
                  _$key2 += 1;
                });
                $widgets.length = _$key2;
              } else {
                $widgets = $container.find(' > .e-con-inner > div[data-element_type="container"] > .e-con-inner > ' + cls);
              }
            }
            if ($container.find(' > div[data-element_type="container"] > .e-con-inner > ' + cls).length) {
              if ($widgets.length) {
                var _$key3 = $widgets.length;
                $container.find(' > div[data-element_type="container"] > .e-con-inner > ' + cls).each(function () {
                  // console.log($(this));
                  var id = $(this).parent().data('id');
                  // $widgets.push($(this)[0]);
                  if (!$widgets.hasOwnProperty(_$key3)) {
                    $widgets[_$key3] = $(this)[0];
                  }
                  _$key3 += 1;
                });
                $widgets.length = _$key3;
              } else {
                $widgets = $container.find(' > div[data-element_type="container"] > .e-con-inner > ' + cls);
              }
            }
            if ($container.find(' > .e-con-inner > ' + cls).length) {
              if ($widgets.length) {
                var _$key4 = $widgets.length;
                $container.find(' > .e-con-inner > ' + cls).each(function () {
                  var id = $(this).parent().data('id');
                  // $widgets.push($(this)[0]);
                  if (!$widgets.hasOwnProperty(_$key4)) {
                    $widgets[_$key4] = $(this)[0];
                  }
                  _$key4 += 1;
                });
                $widgets.length = _$key4;
              } else {
                $widgets = $container.find(' > .e-con-inner > ' + cls);
              }
            }
            if ($container.find(' > ' + cls).length) {
              if ($widgets.length) {
                var _$key5 = $widgets.length;
                $container.find(' > ' + cls).each(function () {
                  var id = $(this).parent().data('id');
                  // $widgets.push($(this)[0]);
                  if (!$widgets.hasOwnProperty(_$key5)) {
                    $widgets[_$key5] = $(this)[0];
                  }
                  _$key5 += 1;
                });
                $widgets.length = _$key5;
              } else {
                $widgets = $container.find(' > ' + cls);
              }
            }
            console.log(_objectSpread({}, $widgets));
            // console.log( Object.assign({}, $widgets) );
            return $widgets;
            // if($widgets.length){
            // return $widgets;
            // return Object.assign({}, $widgets);
            // }

            /* if( $container.find(' > .e-con-inner > div[data-element_type="container"] > '+cls).length > 0 ) {
            	//if has 3 level inner widget
            	console.log('if has immidiate widget');
            	// console.log( $container.find(' > .e-con-inner > div[data-element_type="container"] > '+cls) );
            	return $container.find(' > .e-con-inner > div[data-element_type="container"] > '+cls);
            	}
            else if( $container.find(' > '+cls).length > 0 ) {
            	//else if it has 1 level inner widget
            	console.log('if has immidiate widget');
            	// console.log( $container.find(' > '+cls) );
            	return $container.find(' > '+cls);
            	}
            else if ( $container.find(' > .e-con-inner > '+cls).length > 0 ) {
            	//else if it has 2 level inner widget
            	console.log('if has immidiate container');
            	// console.log( $container.find(' > .e-con-inner > '+cls) );
            	return $container.find(' > .e-con-inner > '+cls);
            }
            */
            // console.groupEnd();
          }
          // console.group(_this.$element.data("element_type"));
          // console.log(_this.$element.find('.elementor-widget-'+widget + ' .elementor-widget-container'));
          // console.groupEnd();

          return _this.$element.find('.elementor-widget-' + widget + ' .elementor-widget-container');
        });
      },
      bindEvents: function bindEvents() {
        if (this.isEqhEnabled()) {
          this.run();

          // $window.on('resize scroll orientationchange', debounce(this.run.bind(this), 500));
          $window.on('resize orientationchange', debounce(this.run.bind(this), 500));
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
    elementorFrontend.hooks.addAction('frontend/element_ready/container', function ($scope) {
      elementorFrontend.elementsHandler.addHandler(EqualHeightHandler, {
        $element: $scope
      });
    });
  });
})(jQuery);