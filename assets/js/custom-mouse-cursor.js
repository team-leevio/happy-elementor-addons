"use strict";

;
var haCursorOptions = null;
var haCursor = null;
function initiateHaCursorObject() {
  var speed = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0.2;
  haCursorOptions = {
    el: null,
    container: document.body,
    className: 'mf-cursor ha-cursor ha-init-hide',
    innerClassName: 'mf-cursor-inner ha-cursor-inner',
    textClassName: 'mf-cursor-text ha-cursor-text',
    mediaClassName: 'mf-cursor-media ha-cursor-media',
    mediaBoxClassName: 'mf-cursor-media-box ha-cursor-media-box',
    iconSvgClassName: 'mf-svgsprite ha-svgsprite',
    iconSvgNamePrefix: '-',
    iconSvgSrc: '',
    dataAttr: 'cursor',
    hiddenState: '-hidden',
    textState: '-text ha-text',
    iconState: '-icon ha-icon',
    activeState: false,
    mediaState: '-media ha-media',
    stateDetection: {
      // '-pointer': 'a,button',
      '-hidden': 'iframe'
    },
    visible: true,
    visibleOnState: false,
    speed: speed,
    ease: 'expo.out',
    overwrite: true,
    skewing: 0,
    skewingIcon: 0,
    skewingDelta: 0,
    skewingDeltaMax: 0,
    stickDelta: 0,
    showTimeout: 20,
    hideOnLeave: true,
    hideTimeout: 500,
    hideMediaTimeout: 500,
    rotation: false
  };
  haCursor = new MouseFollower(haCursorOptions);
}
(function ($, w) {
  'use strict';

  var $window = $(w);
  $window.on("elementor/frontend/init", function ($e) {
    if (typeof elementorModules === 'undefined') {
      return;
    }
    var HappyCustomMouseCursor = elementorModules.frontend.handlers.Base.extend({
      // Add options as a class property
      options: haCursorOptions,
      onInit: function onInit() {
        elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
        this.run();
      },
      onElementChange: function onElementChange(e) {
        if (e === 'ha_cmc_switcher' || e === 'ha_cmc_enable_liquid_effect' || e === 'ha_cmc_type' || e === 'ha_cmc_text' || e === 'ha_cmc_icon' || e === 'ha_cmc_image' || e === 'ha_cmc_video' || e === 'ha_cmc_enable_icon') {
          this.run();
        }
      },
      getReadySettings: function getReadySettings() {
        var settings = {};

        // Get settings 
        var cmc_switcher = this.getElementSettings('ha_cmc_switcher');
        var type = this.getElementSettings('ha_cmc_type');
        var icon = this.getElementSettings('ha_cmc_icon');
        var text = this.getElementSettings('ha_cmc_text');
        var image = this.getElementSettings('ha_cmc_image');
        var video = this.getElementSettings('ha_cmc_video');

        // Assign values to settings object
        if (cmc_switcher) settings.cmc_switcher = cmc_switcher;
        if (type) settings.type = type;
        if (icon) settings.icon = "<i  class=\"".concat(icon.value, "\"></i>");
        if (text) settings.text = text;
        if (image) settings.image = image.url;
        if (video) settings.video = video.url;

        // Return the full settings object
        return $.extend({}, this.getSettings(), settings);
      },
      run: function run() {
        var settings = this.getReadySettings();
        var self = this;
        if ('yes' == settings.cmc_switcher || settings.cmc_switcher != undefined) {
          if (haCursor == null) {
            initiateHaCursorObject();
          }
          var parentElement = this.$element.parents('.e-parent');
          var hasText = undefined;
          var hasImg = undefined;
          var hasVideo = undefined;
          if (parentElement.length > 0) {
            hasText = parentElement.attr('data-cursor-text');
            hasImg = parentElement.attr('data-cursor-img');
            hasVideo = parentElement.attr('data-cursor-video');
          }
          if (!(typeof hasImg != 'undefined' || typeof hasVideo != 'undefined' || typeof hasText != 'undefined')) {
            if ('color' == settings.type) {
              this.$element.attr('data-cursor-text', ' ');
              this.$element.removeAttr('data-cursor-img');
              this.$element.removeAttr('data-cursor-video');
            }
            if ('text' == settings.type) {
              if (settings.text && settings.text != undefined) {
                this.$element.attr('data-cursor-text', DOMPurify.sanitize(settings.text));
              } else {
                this.$element.attr('data-cursor-text', ' ');
              }
              this.$element.removeAttr('data-cursor-img');
              this.$element.removeAttr('data-cursor-video');
            }
            if ('icon' == settings.type) {
              if (settings.icon && settings.icon != undefined) {
                this.$element.attr('data-cursor-text', settings.icon);
              } else {
                this.$element.attr('data-cursor-text', ' ');
              }
              this.$element.removeAttr('data-cursor-img');
              this.$element.removeAttr('data-cursor-video');
            }
            if ('image' == settings.type) {
              if (settings.image) {
                this.$element.attr('data-cursor-img', settings.image);
              } else {
                this.$element.attr('data-cursor-img', ' ');
              }
              this.$element.removeAttr('data-cursor-text');
              this.$element.removeAttr('data-cursor-video');
            }
            if ('video' == settings.type) {
              if (settings.video) {
                this.$element.attr('data-cursor-video', settings.video);
              } else {
                this.$element.attr('data-cursor-video', ' ');
              }
              this.$element.removeAttr('data-cursor-text');
              this.$element.removeAttr('data-cursor-img');
            }
            var uniqueID = this.$element.data('id');
            var uniqueSelector = 'elementor-element-' + uniqueID;
            this.$element.on('mouseenter', function (e) {
              haCursor.el.classList.add(uniqueSelector);
            });
            this.$element.on('mouseleave', function (e) {
              haCursor.el.classList.remove(uniqueSelector);
            });
          }
        } else {
          if ('text' == settings.type || 'icon' == settings.type || settings.type == undefined) {
            this.$element.removeAttr('data-cursor-text');
          }
          if ('image' == settings.type || settings.type == undefined) {
            this.$element.removeAttr('data-cursor-img');
          }
          if ('video' == settings.type || settings.type == undefined) {
            this.$element.removeAttr('data-cursor-video');
          }
        }
      }
    });

    // Global hook
    elementorFrontend.hooks.addAction("frontend/element_ready/global", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(HappyCustomMouseCursor, {
        $element: $scope
      });
    });
  });
})(jQuery, window);