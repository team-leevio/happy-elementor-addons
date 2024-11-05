"use strict";

function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _defineProperty(e, r, t) { return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, { value: t, enumerable: !0, configurable: !0, writable: !0 }) : e[r] = t, e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
;
(function ($, w) {
  'use strict';

  var $window = $(w);
  $window.on("elementor/frontend/init", function ($e) {
    if (typeof elementorModules === 'undefined') {
      return;
    }
    var HappyCustomMouseCursor = elementorModules.frontend.handlers.Base.extend({
      onInit: function onInit() {
        elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
        this.run();
      },
      onElementChange: function onElementChange(e) {
        if (e == 'ha_cmc_switcher' || e == 'ha_cmc_enable_liquid_effect' || e == 'ha_cmc_type' || e == 'ha_cmc_text' || e == 'ha_cmc_icon' || e == 'ha_cmc_image' || e == 'ha_cmc_video') {
          this.run();
        }
      },
      getReadySettings: function getReadySettings() {
        var settings = {};

        // Get settings 
        var cmc_switcher = this.getElementSettings('ha_cmc_switcher');
        var type = this.getElementSettings('ha_cmc_type');
        var liquid_effect = this.getElementSettings('ha_cmc_enable_liquid_effect');
        var enable_icon = this.getElementSettings('ha_cmc_enable_icon');
        var icon = this.getElementSettings('ha_cmc_icon');
        var enable_text = this.getElementSettings('ha_cmc_enable_text');
        var text = this.getElementSettings('ha_cmc_text');
        var image = this.getElementSettings('ha_cmc_image');
        var video = this.getElementSettings('ha_cmc_video');

        // Assign values to settings object
        if (cmc_switcher) settings.cmc_switcher = cmc_switcher;
        if (type) settings.type = type;
        if (liquid_effect) settings.liquid_effect = liquid_effect;
        if (enable_icon) settings.enable_icon = enable_icon;
        if (icon) settings.icon = icon.value;
        if (enable_text) settings.enable_text = enable_text;
        if (text) settings.text = text;
        if (image) settings.image = image.url;
        if (video) settings.video = video.url;

        // Return the full settings object
        return $.extend({}, this.getSettings(), settings);
      },
      run: function run() {
        var settings = this.getReadySettings();
        var cursor = null;
        var uniqueClass = this.$element.data('id');
        var uniqueSelector = '.elementor-element-' + uniqueClass;
        var targetElement = this.$element.hasClass('ha-cmc-yes') ? this.$element : $(uniqueSelector).find('.ha-cmc-yes');
        if (!uniqueSelector) return;
        var liquid_effect = settings.liquid_effect || 'no';
        if (liquid_effect === 'yes') {
          cursor = new MouseFollower(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty({
            el: null,
            container: uniqueSelector,
            className: 'mf-cursor',
            textColor: '#000',
            opacity: 1,
            visible: true,
            hideOnLeave: true,
            stickDelta: 0,
            speed: 0.2
          }, "hideOnLeave", true), "skewingText", 90), "skewingIcon", 90), "skewingMedia", 90), "skewingDelta", 0.0001));
        } else {
          cursor = new MouseFollower(_defineProperty(_defineProperty(_defineProperty(_defineProperty({
            el: null,
            container: uniqueSelector,
            className: 'mf-cursor',
            textColor: '#000',
            opacity: 1,
            visible: true,
            hideOnLeave: true,
            stickDelta: 0,
            skewingDelta: 0,
            speed: 0.2
          }, "hideOnLeave", true), "skewingText", 90), "skewingIcon", 90), "skewingMedia", 90));
        }
        if (targetElement != undefined || targetElement != null) {
          targetElement.on('mouseenter', function () {
            if (settings.cmc_switcher == 'yes') {
              $('.ha-cmc-init-yes').addClass('hm-cmc-init-hidden');
            } else {
              $('.ha-cmc-init-yes').removeClass('hm-cmc-init-hidden');
            }
          });
          targetElement.on('mouseleave', function () {
            $('.ha-cmc-init-yes').removeClass('hm-cmc-init-hidden');
          });
        } else {
          targetElement.on('mouseleave', function () {
            $('.ha-cmc-init-yes').removeClass('hm-cmc-init-hidden');
          });
        }
        $(window).on('mouseleave', function () {
          $('.ha-cmc-init-yes').css({
            'opacity': 0
          });
        });
        $(window).on('mouseenter', function () {
          $('.ha-cmc-init-yes').css({
            'opacity': 1
          });
        });
        this.$element.on('mouseenter', function (e) {
          var enable_text = settings.enable_text || '';
          var type = settings.type || '';
          var text = settings.text || '';
          var enable_icon = settings.enable_icon || '';
          var icon = settings.icon || '';
          var image = settings.image || '';
          var video = settings.video || '';
          if (type == 'text') {
            if (enable_text == 'yes') {
              if (text) {
                cursor.setText(text);
              } else {
                cursor.setText('');
              }
            } else {
              cursor.setText('');
            }
          }
          if (type == 'icon') {
            if (enable_icon == 'yes') {
              var getIcon = "<i class='".concat(icon, "'></i>");
              if (icon) {
                cursor.setText(getIcon);
              } else {
                cursor.setText('');
              }
            } else {
              cursor.setText('');
            }
          }
          if (type == 'image') {
            if (image) {
              cursor.setImg(image);
            } else {
              cursor.setImg('');
            }
          }
          if (type == 'video') {
            if (video) {
              cursor.setVideo(video);
            } else {
              cursor.setVideo('');
            }
          }
        });

        // On mouseleave, remove text from the cursor
        this.$element.on('mouseleave', function () {
          cursor.removeText();
          cursor.removeImg();
          cursor.removeMedia();
        });
      }
    });

    //global hook
    elementorFrontend.hooks.addAction("frontend/element_ready/global", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(HappyCustomMouseCursor, {
        $element: $scope
      });
    });
  });
})(jQuery, window);