;(function(elementor, $, window) {
    'use strict';

    elementor.on('panel:init', function() {
        $('#elementor-panel-elements-search-input').on('keyup', _.debounce(function() {
            $('#elementor-panel-elements')
                .find('.hm')
                .parents('.elementor-element')
                .addClass('happy-addons-addon');
        }, 100));
    });

    function getCssEffectsControlsMap() {
        return {
            'translate' : {
                props: ['x', 'y']
            },
            'rotate' : {
                props: ['x', 'y', 'z']
            },
            'scale': {
                value: 1,
                props: ['x','y']
            }
        };
    }

    function bindCssTransformControls(effectSwitch, effectControl, widgetModel, value) {
        var settingPrefix = 'ha_transform_fx_';
        effectSwitch = settingPrefix + effectSwitch;
        effectControl = settingPrefix + effectControl;

        widgetModel.on('change:'+ effectSwitch, function (model, isActive) {
            if (!isActive) {
                var controlView = elementor.getPanelView().getCurrentPageView().children.find(function(view) {
                    return view.model.get('name') === effectControl;
                });
                widgetModel.set(effectControl, _.extend({}, widgetModel.get(effectControl), {size: value}));
                controlView && controlView.render();
            }
        });
    }

    function initCssTransformEffects(model) {
        var widgetModel = elementorFrontend.config.elements.data[model.cid];
        _.each(getCssEffectsControlsMap(), function(effectConfig, effectKey) {
            _.each(effectConfig.props, function(effectProp) {
                bindCssTransformControls(
                    effectKey + '_toggle',
                    effectKey + '_' + effectProp,
                    widgetModel,
                    _.isUndefined(effectConfig['value']) ? '' : effectConfig['value']
                );
            })
        });
    }

    function initPresetHandler(panel, model) {
        if (model.get('widgetType').indexOf('ha-') === -1) {
            return;
        }
        var controller = elementor.getPanelView().getCurrentPageView().$childViewContainer.find('[data-setting="_preset"]');
        controller.on('change.haPresetChange', function(e) {
            e.stopPropagation();

            view.copy();

            var d = elementorCommon.storage.get('transfer');

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
    }

    elementor.hooks.addAction('panel/open_editor/widget', function(panel, model) {
        initCssTransformEffects(model);
        initPresetHandler(panel, model);
    });

}(elementor, jQuery, window));
