(function ($) {
	var modalTemplate = document.getElementById(
		"tmpl-modal-template-condition"
	);

	var conditionTemplate = document.getElementById(
		"tmpl-elementor-new-template"
	);

	if (typeof elementor !== "undefined") {
		elementor.on("panel:init", function ($e) {
			elementor
				.getPanelView()
				.footer.currentView.addSubMenuItem("saver-options", {
					before: "save-draft",
					name: "haconditions",
					icon: "ha-template-elements",
					title: "Template Conditions",
					callback: function callback() {
						return elementor.trigger("ha:templateCondition");
						// return $e.route('theme-builder-publish/conditions');
						// return $e.route(component.getTabRoute(type));
					},
				});
		});
	}

	$("body").append(modalTemplate.innerHTML);
	if (typeof elementor !== "undefined") {
		elementor.on("ha:templateCondition", function ($e) {
			MicroModal.show("modal-template-condition");
		});
	}

	$(document).on("click", ".ha-cond-repeater-add", function () {
		var conditionContainer = $(".ha-template-condition-wrap");
		conditionContainer.append(conditionTemplate.innerHTML);
	});
})(jQuery);
