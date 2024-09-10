;(function($, window) {
    'use strict';

    let $window = window;
    let hmprbEl = $('.ha-reading-progress-bar');
    
    if(hmprbEl.length <= 0){
        return;
    }

    let hmrpbsettings = {};
        hmrpbsettings = JSON.parse(hmprbEl.attr('data-ha_rpbsettings')); 
    
    if( hmrpbsettings.hasOwnProperty('progress_bar_type') && (hmrpbsettings.progress_bar_type === 'vertical') && hmrpbsettings.hasOwnProperty('rpb_vertical_position') && hmrpbsettings.rpb_vertical_position == 'right' ) {
        $('body').addClass('no-scroll'); 
    } else {
        $('body').removeClass('no-scroll');
    }

    $($window).scroll(function () {
        let scrollPercent = 0;
        let hmSt = $($window).scrollTop(),
            hmDt = $(document).height(),
            hmCt = $($window).height();
        scrollPercent = ( hmSt / (hmDt - hmCt) ) * 100;
        let position = scrollPercent.toFixed(2);

        if (scrollPercent > 100) {
            scrollPercent = 100;
        }
        
        // check progress bar type
        if(hmrpbsettings.hasOwnProperty('progress_bar_type') && hmrpbsettings.progress_bar_type === 'horizontal') {
            
            $('.hm-hrp-bar').css({
                'display': 'flex',
                // 'transition': 'width 0.4s ease'
            });
            $('.hm-hrp-bar').width(position + '%');

        } else if( hmrpbsettings.hasOwnProperty('progress_bar_type') && hmrpbsettings.progress_bar_type === 'vertical' ) {
            
            $('.hm-vrp-bar').css({
                'display': 'flex',
                // 'transition': 'width 0.4s ease'
            });
            // hide default scroll bar
            // if (scrollPercent > 0) {
            //     $('body').addClass('no-scroll');
            // } else {
            //     $('body').removeClass('no-scroll');
            // }
            $('.hm-vrp-bar').height(position + '%');

        } else if ( hmrpbsettings.hasOwnProperty('progress_bar_type') && hmrpbsettings.progress_bar_type === 'circle' ) {
            
            let circleRadius = 45;
            let circumference = 2 * Math.PI * circleRadius;
    
            let offset = Math.round(circumference - (scrollPercent / 100) * circumference);
    
            $('.hm-progress-circle').css('stroke-dashoffset', offset.toFixed(2));
            $('.hm-progress-percent-text').text(`${scrollPercent.toFixed(0)}%`);

        } else {
            //TODO::
        }
        

      });
    

})(jQuery, window)