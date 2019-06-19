;(function($, elementor) {
    'use strict';

    elementor.on('panel:init', function() {
        $('#elementor-panel-elements-search-input').on('keyup', _.debounce(function() {
            $('#elementor-panel-elements')
                .find('.hm')
                .parents('.elementor-element')
                .addClass('happy-addons-addon');
        }, 100));
    });

    elementor.hooks.addAction( 'panel/open_editor/widget', function( panel, model, view ) {
        if (model.get('widgetType').indexOf('ha-') === -1) {
            return;
        }
        var controller = panel.currentPageView.$childViewContainer.find('[data-setting="_preset"]');
        controller.on('change.haPresetChange', function(e) {
            e.stopPropagation();

            view.copy();

            var d = elementorCommon.storage.get('transfer');

            console.log(d);
            // $.get(
            //     happy.ajax_url,
            //     {
            //         'action': 'ha_get_preset',
            //         'name': $(this).val(),
            //         'widget': model.get('widgetType'),
            //         'nonce': happy.nonce
            //     }
            // ).done(function(res) {
            //     console.log(res);
            //     model.setSetting(JSON.parse(res.data));
            // });
        });
    } );
}(jQuery, window.elementor));
