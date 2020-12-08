;
(function ($, ha) {
	'use strict';

	var Select2Base = elementor.modules.controls.Select2;

	ha.hasIconLibrary = function () {
		return (elementor.helpers && elementor.helpers.renderIcon);
	};

	ha.getFeatureLabel = function (text) {
		var div = document.createElement('DIV');

		div.innerHTML = text;
		text = div.textContent || div.innerText || text;

		return text.length > 20 ? text.substring(0, 20) + '...' : text;
	};

	ha.translate = function (stringKey, templateArgs) {
		return elementorCommon.translate(stringKey, null, templateArgs, HappyAddonsEditor.i18n);
	};

	// For BC
	window.ha_get_feature_label = ha.getFeatureLabel;
	window.ha_has_icon_library = ha.hasIconLibrary;
	window.ha_translate = ha.translate;

	ha.getButtonWithIcon = function (view, args) {
		var buttonMarkup = [],
			settings = {},
			btnIconHTML,
			btnMigrated,
			btnIcon,
			buttonBefore,
			buttonAfter;

		args = args || {};
		args = _.defaults(args, {
			oldIcon: 'button_icon',
			iconPos: 'button_icon_position',
			newIcon: 'button_selected_icon',
			text: 'button_text',
			link: 'button_link',
			class: 'ha-btn ha-btn--link',
			textClass: 'ha-btn-text',
		});

		if (!_.isObject(view)) {
			return;
		}

		settings = view.model.attributes.settings.toJSON();

		var buttonText = !_.isUndefined(settings[args.text]) ? settings[args.text] : '',
			hasOldIcon = (!_.isUndefined(settings[args.oldIcon]) && settings[args.oldIcon]) ? true : false,
			hasNewIcon = (!_.isUndefined(settings[args.newIcon]) && _.isObject(settings[args.newIcon]) && settings[args.newIcon].value) ? true : false;

		if (!buttonText && !hasNewIcon && !hasOldIcon) {
			return;
		}

		if (ha.hasIconLibrary()) {
			btnIconHTML = elementor.helpers.renderIcon(view, settings[args.newIcon], {
					'aria-hidden': true,
					'class': 'ha-btn-icon'
				}, 'i', 'object'),
				btnMigrated = elementor.helpers.isIconMigrated(settings, args.newIcon);
		}

		view.addInlineEditingAttributes(args.text, 'none');
		view.addRenderAttribute(args.text, 'class', args.textClass);

		view.addRenderAttribute('button', 'class', args.class);
		view.addRenderAttribute('button', 'href', settings[args.link].url);

		if (hasNewIcon || hasOldIcon) {
			if (ha.hasIconLibrary() && btnIconHTML && btnIconHTML.rendered && (!hasOldIcon || btnMigrated)) {
				if (settings[args.newIcon].library === 'svg') {
					btnIcon = '<span class="ha-btn-icon ha-btn-icon--svg">' + btnIconHTML.value + '</span>';
				} else {
					btnIcon = btnIconHTML.value;
				}
			} else if (hasOldIcon) {
				btnIcon = '<i class="ha-btn-icon ' + args.oldIcon + '" aria-hidden="true"></i>';
			}
		}

		if (buttonText && (!hasNewIcon && !hasOldIcon)) {
			buttonMarkup = [
				'<a ' + view.getRenderAttributeString('button') + '>',
				'<span ' + view.getRenderAttributeString(args.text) + '>',
				buttonText,
				'</span>',
				'</a>',
			];
		} else if (!buttonText && (hasNewIcon || hasOldIcon)) {
			buttonMarkup = [
				'<a ' + view.getRenderAttributeString('button') + '>',
				btnIcon,
				'</a>',
			];
		} else if (buttonText && (hasNewIcon || hasOldIcon)) {
			if (settings[args.iconPos] === 'before') {
				view.addRenderAttribute('button', 'class', 'ha-btn--icon-before');
				buttonBefore = btnIcon;
				buttonAfter = '<span ' + view.getRenderAttributeString(args.text) + '>' + buttonText + '</span>';
			} else {
				view.addRenderAttribute('button', 'class', 'ha-btn--icon-after');
				buttonAfter = btnIcon;
				buttonBefore = '<span ' + view.getRenderAttributeString(args.text) + '>' + buttonText + '</span>';
			}
			buttonMarkup = [
				'<a ' + view.getRenderAttributeString('button') + '>',
				buttonBefore,
				buttonAfter,
				'</a>',
			];
		}

		return buttonMarkup.join('');
	}

	function setupDarkModeStylesheet() {
		var darkModeLinkID = 'happy-addons-editor-dark-css',
			$darkModeLink = $('#' + darkModeLinkID);

		if (!$darkModeLink.length) {
			$darkModeLink = $('<link>', {
				id: darkModeLinkID,
				rel: 'stylesheet',
				href: HappyAddonsEditor.darkStylesheetURL,
			});
		}

		elementor.settings.editorPreferences.model.on('change:ui_theme', function (m, newValue) {
			if ('light' === newValue) {
				$darkModeLink.remove();
				return;
			}

			$darkModeLink
				.attr('media', 'auto' === newValue ? '(prefers-color-scheme: dark)' : '')
				.appendTo(elementorCommon.elements.$body);
		});
	}

	elementor.on('panel:init', function () {
		$('#elementor-panel-elements-search-input').on('keyup', _.debounce(function () {
			$('#elementor-panel-elements')
				.find('.hm')
				.parents('.elementor-element')
				.addClass('is-ha-widget');
		}, 100));

		/**
		 * Register grid layer shortcut
		 */
		if (typeof $e !== 'undefined' || $e !== null) {
			var option = {
				callback: function () {
					var ha_grid = elementor.settings.page.model.attributes.ha_grid;
					if ('' === ha_grid) {
						elementor.settings.page.model.setExternalChange('ha_grid', 'yes');
					} else if ('yes' === ha_grid) {
						elementor.settings.page.model.setExternalChange('ha_grid', '');
					}
				}
			};
			$e.shortcuts.register('ctrl+shift+g', option);
			$e.shortcuts.register('cmd+shift+g', option);
		}


		setupDarkModeStylesheet();
	});

	elementor.modules.layouts.panel.pages.menu.Menu.addItem({
		name: 'happyaddons-home',
		icon: 'hm hm-happyaddons',
		title: ha.translate('editorPanelHomeLinkTitle'),
		type: 'link',
		link: HappyAddonsEditor.editorPanelHomeLinkURL,
		newTab: true
	}, 'settings');

	/**
	 * Add pro widgets placeholder
	 */
	elementor.hooks.addFilter('panel/elements/regionViews', function (regionViews) {
		if (HappyAddonsEditor.hasPro || _.isEmpty(HappyAddonsEditor.proWidgets)) {
			return regionViews;
		}

		var CATEGOERY_NAME = 'happy_addons_pro',
			elementsView = regionViews.elements.view,
			categoriesView = regionViews.categories.view,
			elementsCollection = regionViews.elements.options.collection,
			categoriesCollection = regionViews.categories.options.collection,
			proWidgets = [],
			ElementView,
			freeCategoryIndex;

		_.each(HappyAddonsEditor.proWidgets, function (widget, name) {
			elementsCollection.add({
				name: 'ha-' + name,
				title: widget.title,
				icon: widget.icon,
				categories: [CATEGOERY_NAME],
				editable: false,
			});
		});

		elementsCollection.each(function (element) {
			if (element.get('categories')[0] === CATEGOERY_NAME) {
				proWidgets.push(element);
			}
		});

		freeCategoryIndex = categoriesCollection.findIndex({
			name: 'happy_addons_category'
		});

		if (freeCategoryIndex) {
			categoriesCollection.add({
				name: 'happy_addons_pro_category',
				title: 'Happy Addons Pro',
				icon: 'hm hm-happyaddons',
				defaultActive: false,
				items: proWidgets,
			}, {
				at: freeCategoryIndex + 1
			});
		}

		ElementView = {
			className: function () {
				var className = this.constructor.__super__.className.call(this);
				if (!this.isEditable() && this.isHappyWidget()) {
					className += ' ha-element--promotion';
				}

				return className;
			},

			isHappyWidget: function () {
				return this.model.get('name').indexOf('ha-') === 0;
			},

			onMouseDown: function () {
				if (!this.isHappyWidget()) {
					elementor.promotion.dialog.buttons[0].removeClass('ha-btn--promotion');
					this.constructor.__super__.onMouseDown.call(this);
					return;
				}

				elementor.promotion.dialog.buttons[0].addClass('ha-btn--promotion');

				elementor.promotion.showDialog({
					headerMessage: ha.translate('promotionDialogHeader', [this.model.get('title')]),
					message: ha.translate('promotionDialogMessage', [this.model.get('title')]),
					top: '-7',
					element: this.el,
					actionURL: 'https://demo.happyaddons.com/',
				});
			}
		};

		regionViews.elements.view = elementsView.extend({
			childView: elementsView.prototype.childView.extend(ElementView)
		});

		regionViews.categories.view = categoriesView.extend({
			childView: categoriesView.prototype.childView.extend({
				childView: categoriesView.prototype.childView.prototype.childView.extend(ElementView)
			})
		});

		return regionViews;
	});

	// Widget List controller view
	var WidgetList = Select2Base.extend({
		ALLOW_RERENDER: true,

		getSelect2DefaultOptions: function() {
			var options = Select2Base.prototype.getSelect2DefaultOptions.apply(this, arguments);

			if (this.container && this.container.type === 'section') {
				var widgets = {}, data = [];
				this.container.children.forEach(function(column) {
					if (column.children.length) {
						column.children.forEach(function(widget) {
							if (widget.type !== 'widget') {
								return;
							}

							widgets[ widget.model.get('widgetType') ] = widget.label + ' ('+widget.model.get('widgetType')+')';
						});
					}
				});

				_.each(widgets, function(label, id) {
					data.push({
						id: id,
						text: label
					});
				});

				options.data = data;
				this.model.set('options', widgets);
			}

			return options;
		},

		onRender: function() {
			Select2Base.prototype.onRender.apply(this, arguments);

			if (this.container && this.container.type === 'section' && this.ALLOW_RERENDER) {
				var _this = this;
				var t = setTimeout(function() {
					_this.render();
					clearTimeout(t);
				}, 20);

				this.ALLOW_RERENDER = false;
			}
		},
	});

	elementor.addControlView('widget-list', WidgetList);

	window.ha = ha;
}(jQuery, window.ha || {}));
