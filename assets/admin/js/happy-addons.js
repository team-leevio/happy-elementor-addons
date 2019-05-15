;(function($, elementor) {
    'use strict';

    function initHappyAddonsAddon( $wrapper ) {
        $wrapper
            .find('.hm')
            .parents('.elementor-element')
            .addClass('happy-addons-addon');
    }

    elementor.on('panel:init', function() {
        initHappyAddonsAddon($('#elementor-panel-category-happy_addons'));

        $('#elementor-panel-elements-search-input').on('keyup', _.debounce(function() {
            initHappyAddonsAddon($('#elementor-panel-elements'));
        }, 170));
    });
}(jQuery, window.elementor));
