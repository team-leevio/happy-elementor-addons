"use strict";

;
(function ($, window) {
  'use strict';

  var $window = window;
  var hmprbEl = $('.ha-reading-progress-bar');
  if (hmprbEl.length <= 0) {
    return;
  }
  var hmrpbsettings = {};
  hmrpbsettings = JSON.parse(hmprbEl.attr('data-ha_rpbsettings'));
  if (hmrpbsettings.ha_rpb_enable !== 'yes') return;
  if (hmrpbsettings.hasOwnProperty('progress_bar_type') && hmrpbsettings.progress_bar_type === 'vertical' && hmrpbsettings.hasOwnProperty('rpb_vertical_position') && hmrpbsettings.rpb_vertical_position == 'right') {
    $('body').addClass('no-scroll');
  } else {
    $('body').removeClass('no-scroll');
  }
  $($window).scroll(function () {
    var scrollPercent = 0;
    var hmSt = $($window).scrollTop() || 0,
      hmDt = $(document).height() || 1,
      hmCt = $($window).height() || 1;
    scrollPercent = hmSt / (hmDt - hmCt) * 100;
    var position = scrollPercent.toFixed(0);
    if (scrollPercent > 100) {
      scrollPercent = 100;
    }

    // check progress bar type
    if (hmrpbsettings.hasOwnProperty('progress_bar_type') && hmrpbsettings.progress_bar_type === 'horizontal') {
      $('.hm-hrp-bar').css({
        'display': 'flex'
      });
      $('.hm-hrp-bar').width(position + '%');
      if (position > 1 && scrollPercent > 0) {
        $('.hm-tool-tip').css({
          'opacity': 1,
          'transition': 'opacity 0.3s'
        });
        $('.hm-tool-tip').text(position + '%');
        if (position >= 98) {
          $('.hm-tool-tip').css({
            'right': '5px'
          });
        } else {
          $('.hm-tool-tip').css({
            'right': '-28px'
          });
        }
      } else {
        $('.hm-tool-tip').css({
          'opacity': 0,
          'transition': 'opacity 0.3s'
        });
        $('.hm-tool-tip').text('0%');
      }
    } else if (hmrpbsettings.hasOwnProperty('progress_bar_type') && hmrpbsettings.progress_bar_type === 'vertical') {
      $('.hm-vrp-bar').css({
        'display': 'flex'
        // 'transition': 'width 0.4s ease'
      });
      if (scrollPercent > 0 && position > 1) {
        $('.hm-vrp-bar').height(position + '%');
      } else {
        $('.hm-vrp-bar').height('0%');
      }
    } else if (hmrpbsettings.hasOwnProperty('progress_bar_type') && hmrpbsettings.progress_bar_type === 'circle') {
      var circleRadius = 45;
      var circumference = 2 * Math.PI * circleRadius;
      var offset = Math.round(circumference - scrollPercent / 100 * circumference);
      if (scrollPercent >= 0) {
        $('.hm-progress-circle').css('stroke-dashoffset', offset.toFixed(2));
        $('.hm-progress-percent-text').text("".concat(scrollPercent.toFixed(0), "%"));
      }
    } else {
      //TODO::
    }
  });
})(jQuery, window);