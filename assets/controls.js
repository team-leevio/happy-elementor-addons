;(function($) {
    'use strict';

    $( window ).on( 'elementor:init', function() {
        var SelectPreviewView = elementor.modules.controls.BaseData.extend( {
            ui: function() {
                var ui = elementor.modules.controls.BaseData.prototype.ui.apply( this, arguments );
                ui.preview = '.elementor-control-preview';
                return ui;
            },

            onBaseInputChange: function( event ) {
                elementor.modules.controls.BaseData.prototype.onBaseInputChange.apply(this, arguments);
                if ( this.model.get('options')[event.currentTarget.value] ) {
                    this.ui.preview.css( 'background-image', 'url(' + this.model.get('options')[event.currentTarget.value].src + ')' );
                }
            }
        } );

        elementor.addControlView( 'select_preview', SelectPreviewView );
        elementor.addControlView( 'image_choose', elementor.modules.controls.Choose );
    } );

})(jQuery);
