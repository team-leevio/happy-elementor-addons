;(function($) {
    'use strict';

    elementor.on('panel:init', function() {
        $('#elementor-panel-elements-search-input').on('keyup', _.debounce(function() {
            $('#elementor-panel-elements')
                .find('.hm')
                .parents('.elementor-element')
                .addClass('is-ha-widget');
        }, 100));
        //Happy Grid Layer Shortcut Register
	    if ( typeof $e !== 'undefined' || $e !== null ) {
		    var option = {
			    callback: function() {
				    var ha_grid = elementor.settings.page.model.attributes.ha_grid;
				    if ( '' === ha_grid ) {
					    elementor.settings.page.model.setExternalChange( 'ha_grid', 'yes' );
				    } else if ( 'yes' === ha_grid ) {
					    elementor.settings.page.model.setExternalChange( 'ha_grid', '' );
				    }
			    }
		    };
		    $e.shortcuts.register( 'ctrl+shift+g', option);
		    $e.shortcuts.register( 'cmd+shift+g', option);
	    }
    });

    function getCssEffectsControlsMap() {
        return {
            'translate' : ['x', 'y', 'x_tablet', 'y_tablet', 'x_mobile', 'y_mobile'],
            'skew' : ['x', 'y', 'x_tablet', 'y_tablet', 'x_mobile', 'y_mobile'],
            'scale': ['x', 'y', 'x_tablet', 'y_tablet', 'x_mobile', 'y_mobile'],
            'rotate' : ['x', 'y', 'z', 'x_tablet', 'y_tablet', 'z_tablet', 'x_mobile', 'y_mobile', 'z_mobile']
        };
    }

    function bindCssTransformControls(effectSwitch, effectControl, widgetModel) {
        var settingPrefix = 'ha_transform_fx_';
        effectSwitch = settingPrefix + effectSwitch;
        effectControl = settingPrefix + effectControl;

        widgetModel.on('change:'+ effectSwitch, function(model, isActive) {
            if (!isActive) {
                var controlView = elementor.getPanelView().getCurrentPageView().children.find(function(view) {
                    return view.model.get('name') === effectControl;
                });
                widgetModel.set(effectControl, _.extend({}, widgetModel.defaults[effectControl]));
                controlView && controlView.render();
            }
        });
    }

    function initCssTransformEffects(model) {
        var widgetModel = elementorFrontend.config.elements.data[model.cid];
        _.each(getCssEffectsControlsMap(), function(effectProps, effectKey) {
            _.each(effectProps, function(effectProp) {
                bindCssTransformControls(
                    effectKey + '_toggle',
                    effectKey + '_' + effectProp,
                    widgetModel
                );
            })
        });

        // Event bindings cleanup
        elementor.getPanelView().getCurrentPageView().model.on('editor:close', function() {
            _.each(getCssEffectsControlsMap(), function(effectConfig, effectKey) {
                widgetModel.off('change:ha_transform_fx_'+effectKey+'_toggle');
            });
        });
    }

    elementor.hooks.addAction('panel/open_editor/widget', function(panel, model) {
        initCssTransformEffects(model);
    });

    if ( elementor.modules.controls.Icons ) {
        var WithHappyIcons = elementor.modules.controls.Icons.extend({
            getControlValue: function() {
                var controlValue = this.constructor.__super__.getControlValue.call(this),
                    model = this.model,
                    valueToMigrate = this.getValueToMigrate(),
                    newValue = { value: '', library: 'happy-icons' },
                    elementSettingsModel = ( this.container && this.container.settings ) || this.elementSettingsModel;

                if ( _.isObject( controlValue ) &&
                    !_.isEmpty( controlValue ) &&
                    controlValue.library !== 'svg' &&
                    controlValue.value.indexOf( 'fashm' ) === 0
                ) {
                    newValue.value = controlValue.value.substr( controlValue.value.indexOf( 'hm hm-' ) );
                    elementSettingsModel.set( model.get( 'name' ), newValue );
                    return newValue;
                }

                if ( ! _.isObject( controlValue ) && valueToMigrate && valueToMigrate.indexOf( 'hm hm-' ) === 0 ) {
                    newValue.value = valueToMigrate;
                    elementSettingsModel.set( model.get( 'name' ), newValue );
                    return newValue;
                }

                if ( ! this.isMigrationAllowed() ) {
                    return valueToMigrate;
                }

                // Bail if no migration flag or no value to migrate
                if ( ! valueToMigrate ) {
                    return controlValue;
                }

                var didMigration = elementSettingsModel.get( this.dataKeys.migratedKey ),
                    controlName = model.get( 'name' );

                // Check if migration had been done and is stored locally
                if ( this.cache.migratedFlag[ controlName ] ) {
                    return this.cache.migratedFlag[ controlName ];
                }
                // Check if already migrated
                if ( didMigration && didMigration[ controlName ] ) {
                    return controlValue;
                }

                // Do migration
                return this.migrateFa4toFa5( valueToMigrate );
            }
        });

        elementor.addControlView( 'icons', WithHappyIcons );
    }

    window.ha_has_icon_library = function() {
        return ( elementor.helpers && elementor.helpers.renderIcon );
    };

    window.ha_get_feature_label = function( text ) {
        var div = document.createElement('DIV');

        div.innerHTML = text;
        text = div.textContent || div.innerText || text;

        return text.length > 20 ? text.substring(0, 20) + "..." : text;
    };

    elementor.modules.layouts.panel.pages.menu.Menu.addItem({
        name: 'happyaddons-home',
        icon: 'hm hm-happyaddons',
        title: HappyAddonsEditor.editorPanelHomeLinkTitle,
        type: 'link',
        link: HappyAddonsEditor.editorPanelHomeLinkURL,
        newTab: true
    }, 'settings');

    elementor.modules.layouts.panel.pages.menu.Menu.addItem({
        name: 'happyaddons-widgets',
        icon: 'hm hm-cross-game',
        title: HappyAddonsEditor.editorPanelWidgetsLinkTitle,
        type: 'link',
        link: HappyAddonsEditor.editorPanelWidgetsLinkURL,
        newTab: true
    }, 'settings');

}(jQuery));
