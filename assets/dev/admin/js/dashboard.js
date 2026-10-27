;
(function ($, HappyDashboard) {
	'use strict';

	function debounce ( func, wait, immediate ) {
		var timeout;
		return function () {
			var context = this, args = arguments;
			var later = function () {
				timeout = null;
				if ( !immediate ) func.apply( context, args );
			};
			var callNow = immediate && !timeout;
			clearTimeout( timeout );
			timeout = setTimeout( later, wait );
			if ( callNow ) func.apply( context, args );
		};
	}

	$(function () {

		var $tabsWrapper = $('.ha-dashboard-tabs'),
			$tabsNav = $tabsWrapper.find('.ha-dashboard-tabs__nav'),
			$tabsContent = $tabsWrapper.find('.ha-dashboard-tabs__content'),
			$sidebarMenuWrapper = $('#toplevel_page_happy-addons'),
			$sidebarSubmenu = $sidebarMenuWrapper.find('.wp-submenu');

		$tabsNav.on('click', '.ha-dashboard-tabs__nav-item', function (event) {
			var $currentTab = $(event.currentTarget),
				tabTargetHash = event.currentTarget.hash,
				tabIdSelector = '#tab-content-' + tabTargetHash.substring(1),
				$currentTabContent = $tabsContent.find(tabIdSelector);

			if ($currentTab.is('.nav-item-is--link')) {
				return true;
			}

			event.preventDefault();

			$currentTab
				.addClass('tab--is-active')
				.siblings()
				.removeClass('tab--is-active');

			$currentTabContent
				.addClass('tab--is-active')
				.siblings()
				.removeClass('tab--is-active');

			window.location.hash = tabTargetHash;
			$sidebarSubmenu.find('a').filter(function (i, a) {
				return tabTargetHash === a.hash;
			}).parent().addClass('current').siblings().removeClass('current');
		});

		if (!window.location.hash) {
			window.location.hash = '#home';
		}

		$tabsNav.find('a[href="' + window.location.hash + '"]').click();
		$sidebarSubmenu.find('a').filter(function (i, a) {
			return window.location.hash === a.hash;
		}).parent().addClass('current').siblings().removeClass('current');

		$sidebarSubmenu.on('click', 'a', function (event) {
			if (!event.currentTarget.hash) {
				return true;
			}
			event.preventDefault();

			window.location.hash = event.currentTarget.hash;

			var $currentItem = $(event.currentTarget);
			$currentItem.parent().addClass('current').siblings().removeClass('current');

			$tabsNav.find('a[href="' + event.currentTarget.hash + '"]').click();
		});

		var $dashboardForm = $('#ha-dashboard-form'),
			$widgetsList = $dashboardForm.find('.ha-dashboard-widgets'),
			$saveButton = $dashboardForm.find('.ha-dashboard-btn--save');

		$dashboardForm.on('submit', function (event) {
			event.preventDefault();

			$.post({
				url: HappyDashboard.ajaxUrl,
				data: {
					nonce: HappyDashboard.nonce,
					action: HappyDashboard.action,
					data: $dashboardForm.serialize()
				},
				beforeSend: function () {
					$saveButton
						.text('.....')
						.css('animation', 'animateTextIndent infinite 2.5s');
				},
				success: function (response) {
					if (response.success) {
						var t = setTimeout(function () {
							$saveButton
								.css('animation', '')
								.attr('disabled', true)
								.text(HappyDashboard.savedLabel);
								location.reload();
							clearTimeout(t);
						}, 500);
					}
				}
			});
		});

		// $dashboardForm.on('change', ':checkbox, :radio', function () {
		// 	$saveButton.attr('disabled', false).text(HappyDashboard.saveChangesLabel);
		// });

		$dashboardForm.on('change keyup paste', 'input', function () {
			$saveButton.attr('disabled', false).text(HappyDashboard.saveChangesLabel);
		});

		// GSAP items are listed on both the Features/Widgets tabs and the GSAP
		// tab. The whole form is serialized on save, so every duplicate control
		// (same name + value) must mirror the same state.
		$dashboardForm.on('change', ':checkbox', function () {
			var source = this,
				name = source.name,
				value = source.value;

			if (!name) {
				return;
			}

			$dashboardForm.find(':checkbox').not(':disabled').each(function () {
				if (this !== source && this.name === name && this.value === value) {
					this.checked = source.checked;
				}
			});
		});

		$('.ha-action--btn').on('click', function (event) {
			event.preventDefault();

			var $currentAction = $(this),
				filter = $currentAction.data('filter'),
				action = $currentAction.data('action'),
				// A bulk action only touches the panel (V3 or V4) it was clicked in
				$scope = $currentAction.closest('.ha-version-panel').length
					? $currentAction.closest('.ha-version-panel')
					: $currentAction.closest('.ha-dashboard-tabs__content-item'),
				$all = ( $scope.length ? $scope : $widgetsList ).find('.ha-dashboard-widgets__item').not('.ha-atomic-switch'),
				$free = $all.not('.item--is-pro'),
				$pro = $all.filter('.item--is-pro');


			if (filter) {
				switch (filter) {
					case 'free':
						$free.show();
						$pro.hide();
						break;
					case 'pro':
						$free.hide();
						$pro.show();
						break;
					case '*':
					default:
						$all.show();
						break;
				}
			}


			var $toggle_widget = $all.not('.item--is-placeholder').not('[style*="display:none"]').not('[style*="display: none"]').find(':checkbox.ha-widget'),
				$toggle_feature = $all.not('.item--is-placeholder').not('[style*="display:none"]').not('[style*="display: none"]').find(':checkbox.ha-feature'),
				$toggle_extension = $all.not('.item--is-placeholder').not('[style*="display:none"]').not('[style*="display: none"]').find(':checkbox.ha-extension'),
				$toggle_atomic_widget = $all.not('[style*="display:none"]').not('[style*="display: none"]').find(':checkbox.ha-atomic-widget'),
				$toggle_atomic_feature = $all.not('[style*="display:none"]').not('[style*="display: none"]').find(':checkbox.ha-atomic-feature');


			if (action) {
				if ('enable' === action) {
					$toggle_widget.prop('checked', true);
				} else if ('disable' === action) {
					$toggle_widget.prop('checked', false);
				} else if ('enable_feature' === action) {
					$toggle_feature.prop('checked', true);
				} else if ('disable_feature' === action) {
					$toggle_feature.prop('checked', false);
				} else if ('enable_extension' === action) {
					$toggle_extension.prop('checked', true);
				} else if ('disable_extension' === action) {
					$toggle_extension.prop('checked', false);
				} else if ('enable_atomic_widget' === action) {
					$toggle_atomic_widget.prop('checked', true);
				} else if ('disable_atomic_widget' === action) {
					$toggle_atomic_widget.prop('checked', false);
				} else if ('enable_atomic_feature' === action) {
					$toggle_atomic_feature.prop('checked', true);
				} else if ('disable_atomic_feature' === action) {
					$toggle_atomic_feature.prop('checked', false);
				} else if ('enable_gsap' === action) {
					$toggle_feature.prop('checked', true);
					$toggle_widget.prop('checked', true);
				} else if ('disable_gsap' === action) {
					$toggle_feature.prop('checked', false);
					$toggle_widget.prop('checked', false);
				}
				$toggle_widget.trigger('change');
				$toggle_feature.trigger('change');
				$toggle_extension.trigger('change');
				$toggle_atomic_widget.trigger('change');
				$toggle_atomic_feature.trigger('change');
			}
		});

		// V3 / V4 (Atomic) version switcher. The choice is a per-browser
		// convenience, so storage failures are ignored.
		var versionKey = 'haDashboardVersion';

		function switchDashboardVersion(version) {
			$dashboardForm.find('.ha-version-switcher__btn').each(function () {
				$(this).toggleClass('is-active', $(this).data('version') === version);
			});

			$dashboardForm.find('.ha-version-panel').each(function () {
				this.hidden = $(this).data('version') !== version;
			});

			try {
				window.localStorage.setItem(versionKey, version);
			} catch (e) {}
		}

		$dashboardForm.on('click', '.ha-version-switcher__btn', function (event) {
			event.preventDefault();
			switchDashboardVersion($(this).data('version'));
		});

		var storedVersion = 'v3';

		try {
			storedVersion = window.localStorage.getItem(versionKey) === 'v4' ? 'v4' : 'v3';
		} catch (e) {}

		if ('v4' === storedVersion) {
			switchDashboardVersion('v4');
		}

		$('.ha-search-action--btn input').on('input', debounce( function ( event ) {
			event.preventDefault();
			// console.log( 'event type => ', event.type );
			// console.log( 'input value => ', event.target.value );
			// console.log( $widgetsList );

			var searchValue = event.target.value.toLowerCase();
			var $all = $widgetsList.find('.ha-dashboard-widgets__item');
			var $toggle_widget = $all.not('.item--is-placeholder').find(':checkbox.ha-widget');

			//$toggle_feature = $all.not('.item--is-placeholder').find(':checkbox.ha-feature');
			//console.log( $widgetsList.find(':checkbox.ha-widget') );
			// $dashboardForm.find(".ha-dashboard-widgets--tab > h2")

			if( '' != searchValue ) {
				$dashboardForm.find(".ha-dashboard-widgets--tab > h2").hide();
			} else {
				$dashboardForm.find(".ha-dashboard-widgets--tab > h2").show();
			}

			// $toggle_widget
			$toggle_widget.each( function ( index, item ) {
				// console.log( item, index );
				let item_wrapper = $(item).closest('.ha-dashboard-widgets__item');
				let condition = item_wrapper.data('title').toLowerCase().indexOf(searchValue) > -1;
				condition ? item_wrapper.show() : item_wrapper.hide();
			});
		}, 500));

		$('.ha-search-action--btn input').on('keypress', function (event) {
			if ( event.key === 'Enter') event.preventDefault(); return;
		})


		$('.ha-feature-sub-title-a').magnificPopup({
			disableOn: 700,
			type: 'iframe',
			mainClass: 'mfp-fade',
			removalDelay: 160,
			preloader: false,

			fixedContentPos: false
		});

		$('.btn-how-to-contribute').on('click', function (event) {
			event.preventDefault();
			$(this).next().show();
		});


		// Analytics
		var $dashboardAnalytics = $('#ha-dashboard-analytics-disable');
		$dashboardAnalytics.on('click', function (event) {
			event.preventDefault();
			$(this).next().val('true');
			$.post({
				url: HappyDashboard.ajaxUrl,
				data: {
					nonce: HappyDashboard.nonce,
					action: HappyDashboard.action,
					data: $dashboardForm.serialize(),
				},
				beforeSend: function () {
					$dashboardAnalytics
						.text('.....')
						.css('animation', 'animateTextIndent infinite 2.5s');
				},
				success: function (response) {
					if (response.success) {
						location.reload();
					}
				}
			});
		});

	});
}(jQuery, window.HappyDashboard));
