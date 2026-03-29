(function ($) {
	"use strict";

	var WhatsAppButton = function ($scope) {
		if ( elementorFrontend.isEditMode() ) {
		//if (typeof elementor !== "undefined") {
			elementor.channels.editor.on("change", function (event, value) {
				if (!event.model) return;
				var controlName = event.model.get("name");

				if (controlName === "phone_number") {
					var editedElement = elementor
						.getPanelView()
						.getCurrentPageView()
						.getOption("editedElementView");
					var settings = editedElement.model.get("settings");
					var currentValue = settings.get("phone_number");
					var newValue = currentValue.replace(/\D/g, "");

					// ── Strip non-digits ─────────────────────────────────────────
					if (newValue !== currentValue) {
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
								controlView.ui.input
									.val(newValue)
									.trigger("input");
								showHint(
									controlView.$el,
									"⚠ Only numbers are allowed.",
								);
							}
						}, 0);
					}

					// ── WhatsApp number validation ───────────────────────────────
					// Only validate when user stops typing (7-15 digits is valid E.164 range)
					if (newValue.length > 0) {
						var panelView = elementor
							.getPanelView()
							.getCurrentPageView();
						var controlView = panelView.children.findByModelCid(
							panelView.collection.findWhere({
								name: "phone_number",
							}).cid,
						);

						if (controlView) {
							var $el = controlView.$el;

							// Remove any previous validation hint
							$el.find(
								".ha-phone-valid, .ha-phone-invalid",
							).remove();

							if (newValue.length < 7) {
								// Too short
								showHint(
									$el,
									"⚠ Number too short to be valid.",
									"warning",
								);
							} else if (newValue.length > 15) {
								// Too long (E.164 max is 15 digits)
								showHint(
									$el,
									"⚠ Number too long. Max 15 digits.",
									"warning",
								);
							} 
							else if (
								newValue.startsWith("0") &&
								newValue.length < 10
							) {
								// Local format without country code
								showHint(
									$el,
									"⚠ Add country code e.g. 880XXXXXXXXX.",
									"warning",
								);
							} 
							else {
								// Valid
								showHint(
									$el,
									"✓ Looks like a valid WhatsApp number.",
									"success",
								);
							}
						}
					}
				}
			});
		}

		// ── Helper: show hint message ────────────────────────────────────────────
		function showHint($el, message, type) {
			type = type || "error";

			var colorMap = {
				error: "#e74c3c",
				warning: "#e67e22",
				success: "#27ae60",
			};

			// Remove ALL previous hints regardless of type
			$el.find(".ha-phone-hint").remove();

			var className = "ha-phone-hint-" + type;

			var $hint = $(
				'<p class="ha-phone-hint ' +
					className +
					'" style="font-size:11px;margin:4px 0 0 0;color:' +
					colorMap[type] +
					';">' +
					message +
					"</p>",
			);

			$el.append($hint);

			if (type !== "success") {
				setTimeout(function () {
					$hint.remove();
				}, 3000);
			}
		}
		var $popup = $scope.find(".ha-whatsapp-popup");
		if (!$popup.length) return;

		var $button = $scope.find(".ha-whatsapp-link");
		var $close = $scope.find(".ha-whatsapp-popup__close");
		var $wrapper = $scope.find(".ha-whatsapp-button");
		var hasTypingIndicator =
			$popup.find(".ha-whatsapp-popup__typing-indicator").length > 0;
		var typingShown = false;

		// ── Smart Popup Positioning ──────────────────────────────────────
		function positionPopup() {
			// Reset inline styles so we can measure naturally
			$popup.css({
				position: "absolute",
				top: "auto",
				bottom: "auto",
				left: "auto",
				right: "auto",
				visibility: "hidden",
				display: "flex",
			});

			// Measurements
			var vpW = window.innerWidth;
			var vpH = window.innerHeight;
			var btnRect = $button[0].getBoundingClientRect();
			var popupW = $popup.outerWidth(true) || 320;
			var popupH = $popup.outerHeight(true) || 350;
			var gap = 12; // px gap between button and popup

			var spaceTop = btnRect.top;
			var spaceBottom = vpH - btnRect.bottom;
			var spaceLeft = btnRect.left;
			var spaceRight = vpW - btnRect.right;

			var css = {};

			// ── Vertical ────────────────────────────────────────────────
			if (spaceTop >= popupH + gap) {
				// Place ABOVE
				css.bottom = $wrapper.outerHeight(true) + gap + "px";
				css.top = "auto";
			} else if (spaceBottom >= popupH + gap) {
				// Place BELOW
				css.top = $wrapper.outerHeight(true) + gap + "px";
				css.bottom = "auto";
			} else {
				// Pick bigger space
				if (spaceTop >= spaceBottom) {
					css.bottom = $wrapper.outerHeight(true) + gap + "px";
					css.top = "auto";
				} else {
					css.top = $wrapper.outerHeight(true) + gap + "px";
					css.bottom = "auto";
				}
			}

			// ── Horizontal ──────────────────────────────────────────────
			if (spaceRight >= popupW) {
				// Align to button LEFT edge
				css.left = "0";
				css.right = "auto";
			} else if (spaceLeft >= popupW) {
				// Align to button RIGHT edge
				css.right = "0";
				css.left = "auto";
			} else {
				// Center under/above button, clamped to viewport
				var btnCenterX = btnRect.left + btnRect.width / 2;
				var idealLeft = btnCenterX - popupW / 2;
				var clampedLeft = Math.max(
					gap,
					Math.min(idealLeft, vpW - popupW - gap),
				);
				// Convert viewport-relative left → position relative to wrapper
				css.left =
					clampedLeft -
					btnRect.left +
					$wrapper[0].getBoundingClientRect().left -
					btnRect.left +
					"px";
				css.right = "auto";
			}

			// Apply & restore visibility
			$popup.css(css).css({ visibility: "", display: "" });

			console.log("VP:", vpW, vpH);
			console.log("Button rect:", btnRect);
			console.log("Popup size:", popupW, popupH);
			console.log(
				"Spaces — top:",
				spaceTop,
				"bottom:",
				spaceBottom,
				"left:",
				spaceLeft,
				"right:",
				spaceRight,
			);
			console.log("Applied CSS:", css);
		}

		// ── Toggle popup ─────────────────────────────────────────────────
		$button.on("click", function (e) {
			e.preventDefault();
			e.stopPropagation();

			var isOpening = !$popup.hasClass("ha-whatsapp-popup--active");

			if (isOpening) {
				positionPopup(); // Calculate before showing
			}

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

		// Reposition on window resize
		$(window).on("resize.whatsapp-" + $scope.attr("data-id"), function () {
			if ($popup.hasClass("ha-whatsapp-popup--active")) {
				positionPopup();
			}
		});

		// ── Close handlers ───────────────────────────────────────────────
		$close.on("click", function (e) {
			e.preventDefault();
			e.stopPropagation();
			$popup.removeClass("ha-whatsapp-popup--active");
			$wrapper.removeClass("ha-whatsapp-chat-opened");
		});

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
