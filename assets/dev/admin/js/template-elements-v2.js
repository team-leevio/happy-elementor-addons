( function ( $ ) {
	"use strict";

	if ( window.elementorV2 && window.elementorV2.editorAppBar && window.elementorV2.editorAppBar.documentOptionsMenu ) {
		let documentOptionsMenu = window.elementorV2.editorAppBar.documentOptionsMenu;

		documentOptionsMenu.registerAction( {
			id: "ha-template-condition",
			group: [ "save" ],
			priority: 10,
			useProps: function () {
				return {
					icon:  function()  {
						return window.React ? window.React.createElement("i", { className: "hm hm-happyaddons" }): null;
					},
					title: window.wp ? window.wp.i18n.__("Template Conditions","happy-elementor-addons") : "Template Conditions",
					visible: true,
					onClick: function (e) {
						e.preventDefault();
						return elementor.trigger( "ha:templateCondition" );
					}
				};
			}
		} );

	}

} )( jQuery );