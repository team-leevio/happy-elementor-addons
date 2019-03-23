;(function($) {
    'use strict';

    $( window ).on( 'elementor:init', function() {

        elementor.hooks.addAction( 'panel/open_editor/widget', function( panel, model ) {
            if (model.get('widgetType').indexOf('ha-') === -1) {
                return;
            }
            // panel.
            // panel.currentPageView.$childViewContainer.find('.elementor-panel-heading-title').append('Wowl')
            // console.log(model);
        } );

        var ExtendedSelect = elementor.modules.controls.Select.extend({
            onReady: function() {
                // console.log(this);
            }
        });

        var SelectPreviewView = elementor.modules.controls.BaseData.extend( {
            ui: function() {
                var ui = elementor.modules.controls.BaseData.prototype.ui.apply( this, arguments );
                ui.preview = '.elementor-control-preview';
                return ui;
            },

            onBaseInputChange: function( event ) {
                elementor.modules.controls.BaseData.prototype.onBaseInputChange.apply(this, arguments);
                if ( this.model.get('options')[event.currentTarget.value] ) {
                    this.setPreviewImage( event.currentTarget.value );
                }
            },

            setPreviewImage: function( option ) {
                this.ui.preview.css( 'background-image', 'url(' + this.model.get('options')[ option ].src + ')' );
            }
        } );

        elementor.addControlView( 'select_preview', SelectPreviewView );
        elementor.addControlView( 'image_choose', elementor.modules.controls.Choose );
        elementor.addControlView( 'select', ExtendedSelect );
    } );

})(jQuery);
