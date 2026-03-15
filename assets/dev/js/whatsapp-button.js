(function ($) {
	"use strict";
	var WhatsAppButton = function ($scope) {
		elementor.channels.editor.on("change", function (event, value) {
			if (!event.model) return;

			var controlName = event.model.get("name");

			// Phone number
			if (controlName === "phone_number") {
				var editedElement = elementor
					.getPanelView()
					.getCurrentPageView()
					.getOption("editedElementView");
				var settings = editedElement.model.get("settings");
				var currentValue = settings.get("phone_number");
				var newValue = currentValue.replace(/\D/g, "");

				if (newValue !== currentValue) {
					// elementor.notifications.showToast({
					// 	message: "Phone number accepts digits only.",
					// });
					setTimeout(function () {
						settings.set("phone_number", newValue);
						var panelView = elementor
							.getPanelView()
							.getCurrentPageView();
						var controlView = panelView.children.findByModelCid(
							panelView.collection.findWhere({
								name: "phone_number",
							}).cid,
						);
						if (
							controlView &&
							controlView.ui &&
							controlView.ui.input
						) {
							controlView.ui.input.val(newValue).trigger("input");
							var $el = controlView.$el;
							if ($el.find(".ha-phone-hint").length === 0) {
								var $hint = $(
									'<p class="ha-phone-hint" style="color:#e74c3c;font-size:11px;margin:4px 0 0 0;">⚠ Only numbers are allowed.</p>',
								);
								$el.append($hint);
								setTimeout(function () {
									$hint.remove();
								}, 2000);
							}
						}
					}, 0);
				}
			}
		});
		// Listen to any widget being edited

		var $popup = $scope.find(".ha-whatsapp-popup");

		// // If no popup element exists in this widget, it's not floating chat mode
		if (!$popup.length) {
			return;
		}

		var $button = $scope.find(".ha-whatsapp-link");
		var $close = $scope.find(".ha-whatsapp-popup__close");
		var $wrapper = $scope.find(".ha-whatsapp-button");
		var hasTypingIndicator =
			$popup.find(".ha-whatsapp-popup__typing-indicator").length > 0;
		var typingShown = false;

		// Toggle popup on button click
		$button.on("click", function (e) {
			e.preventDefault();
			e.stopPropagation();

			var isOpening = !$popup.hasClass("ha-whatsapp-popup--active");
			$popup.toggleClass("ha-whatsapp-popup--active");
			$wrapper.toggleClass("ha-whatsapp-chat-opened");

			if (isOpening && hasTypingIndicator && !typingShown) {
				$popup.addClass("is-typing");
				setTimeout(function () {
					$popup.removeClass("is-typing");
					typingShown = true;
				}, 1500);
			}
		});

		// Close popup on close button click
		$close.on("click", function (e) {
			e.preventDefault();
			e.stopPropagation();
			$popup.removeClass("ha-whatsapp-popup--active");
			$wrapper.removeClass("ha-whatsapp-chat-opened");
		});

		// Close popup when clicking outside the widget
		$(document).on(
			"click.whatsapp-popup-" + $scope.attr("data-id"),
			function (event) {
				if (!$(event.target).closest($scope).length) {
					$popup.removeClass("ha-whatsapp-popup--active");
					$wrapper.removeClass("ha-whatsapp-chat-opened");
				}
			},
		);
	};

	$(window).on("elementor/frontend/init", function () {
		elementorFrontend.hooks.addAction(
			"frontend/element_ready/ha-whatsapp-button.default",
			WhatsAppButton,
		);
	});
})(jQuery);
