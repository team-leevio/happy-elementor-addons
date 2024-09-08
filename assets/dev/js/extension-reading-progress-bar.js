;(function($, window) {
    'use strict';

    let $window = window;

    let hmrpbsettings = $('.ha-reading-progress-bar').data('ha_rpbsettings');
        // console.log('hmrpbsettings ', hmrpbsettings);

    $($window).scroll(function () {

        let s = $($window).scrollTop(),
            d = $(document).height(),
            c = $($window).height();
        let scrollPercent = (s / (d-c)) * 100;
        let position = scrollPercent.toFixed(2);
        
        // Horizontal
        $('.hm-hrp-bar').css({
            'display': 'flex',
            // 'transition': 'width 0.4s ease'
        });
        $('.hm-hrp-bar').width(position + '%');

        // Vertical
        $('.hm-vrp-bar').css({
            'display': 'flex',
            // 'transition': 'width 0.4s ease'
        });
        // hide default scroll bar
        /**if (scrollPercent > 0) {
            $('body').addClass('no-scroll');
        } else {
            $('body').removeClass('no-scroll');
        }**/
        $('.hm-vrp-bar').height(position + '%');

        // Circular
        if (scrollPercent > 100) {
            scrollPercent = 100;
        }

        let circleRadius = 45;
        let circumference = 2 * Math.PI * circleRadius;

        let offset = Math.round(circumference - (scrollPercent / 100) * circumference);

        $('.hm-progress-circle').css('stroke-dashoffset', offset.toFixed(2));
        $('.hm-progress-percent-text').text(`${scrollPercent.toFixed(0)}%`);

      });
    

})(jQuery, window)