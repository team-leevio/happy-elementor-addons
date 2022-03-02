(function ($) {
	if (typeof elementor !== "undefined") {
		elementor.on("panel:init", function ($e) {
			elementor
				.getPanelView()
				.footer.currentView.addSubMenuItem("saver-options", {
					before: "save-draft",
					name: "haconditions",
					icon: "ha-builder",
					title: "Hello Rony Vai",
					callback: function callback() {
						alert("Hola");
						// return $e.route('theme-builder-publish/conditions');
						// return $e.route(component.getTabRoute(type));
					},
				});
		});
	}
})(jQuery);
