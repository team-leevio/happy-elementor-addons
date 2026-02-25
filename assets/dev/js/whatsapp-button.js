(function ($) {
	"use strict";

	var WhatsAppButton = function ($scope) {
		var $popup = $scope.find('.ha-whatsapp-popup');

		// If no popup element exists in this widget, it's not floating chat mode
		if (!$popup.length) {
			return;
		}

		var $button = $scope.find('.ha-whatsapp-link');
		var $close  = $scope.find('.ha-whatsapp-popup__close');

		// Toggle popup on button click
		$button.on('click', function (e) {
			e.preventDefault();
			e.stopPropagation();
			$popup.toggleClass('ha-whatsapp-popup--active');
		});

		// Close popup on close button click
		$close.on('click', function (e) {
			e.preventDefault();
			e.stopPropagation();
			$popup.removeClass('ha-whatsapp-popup--active');
		});

		// Close popup when clicking outside the widget
		$(document).on('click.whatsapp-popup-' + $scope.attr('data-id'), function (event) {
			if (!$(event.target).closest($scope).length) {
				$popup.removeClass('ha-whatsapp-popup--active');
			}
		});
	};

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/ha-whatsapp-button.default', WhatsAppButton);
	});

})(jQuery);
