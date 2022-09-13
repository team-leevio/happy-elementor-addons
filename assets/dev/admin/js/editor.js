;(function($) {
	'use strict';

	window.haHasIconLibrary = function () {
		return (elementor.helpers && elementor.helpers.renderIcon);
	};

	window.haGetFeatureLabel = function (text) {
		var div = document.createElement('DIV');

		div.innerHTML = text;
		text = div.textContent || div.innerText || text;

		return text.length > 20 ? text.substring(0, 20) + '...' : text;
	};

	window.haGetTranslated = function (stringKey, templateArgs) {
		return elementorCommon.translate(stringKey, null, templateArgs, HappyAddonsEditor.i18n);
	};

	window.haGetButtonWithIcon = function (view, args) {
		var buttonMarkup = [],
			settings = {},
			btnIconHTML,
			btnMigrated,
			btnIcon,
			buttonBefore,
			buttonAfter;

		args = args || {};
		args = _.defaults(args, {
			oldIcon  : 'button_icon',
			iconPos  : 'button_icon_position',
			newIcon  : 'button_selected_icon',
			text     : 'button_text',
			link     : 'button_link',
			class    : 'ha-btn ha-btn--link',
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

		if (haHasIconLibrary()) {
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
			if (haHasIconLibrary() && btnIconHTML && btnIconHTML.rendered && (!hasOldIcon || btnMigrated)) {
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

	var registerDarkModeStylesheet = function() {
		var darkModeLinkID = 'happy-addons-editor-dark-css',
			$darkModeLink = $('#' + darkModeLinkID);

		if (!$darkModeLink.length) {
			$darkModeLink = $('<link>', {
				id: darkModeLinkID,
				rel: 'stylesheet',
				href: HappyAddonsEditor.dark_stylesheet_url,
			});
		}

		elementor.settings.editorPreferences.model.on('change:ui_theme', function (model, newValue) {
			if ('light' === newValue) {
				$darkModeLink.remove();
				return;
			}

			$darkModeLink
				.attr('media', 'auto' === newValue ? '(prefers-color-scheme: dark)' : '')
				.appendTo(elementorCommon.elements.$body);
		});
	}

	elementor.on('panel:init', function() {
		$('#elementor-panel-elements-search-input').on('keyup', _.debounce(function() {
			$('#elementor-panel-elements')
				.find('.hm')
				.parents('.elementor-element')
				.addClass('is-ha-widget');
		}, 100));

		function scroll_to_top_reloadPreview (newValue,agfasd) {
			// console.log(newValue);
			// $e.run( 'document/save/publish' );
			//$e.run( 'document/save/update' );
			// $e.run( 'document/save/update' ).then( _.debounce( function () {
			// 	elementor.reloadPreview();
			// 	// location.reload();
			// }, 1500));
			var changeItem = Object.entries( this.model.changed )[0];
			var settings = this.getSettings().settings;
			var attributes = this.model.attributes;
			// this.model.setExternalChange('ha_grid', 'yes')
			var stt_data = {
				'check' : 'sttMessage',
				'changeValue' : newValue,
				'changeItem' : changeItem
			};

			if( 'ha_scroll_to_top_single_disable' != changeItem[0] ) {
				var data = {
					'enable_global_stt' : attributes.ha_scroll_to_top_global,
					'media_type' : attributes.ha_scroll_to_top_media_type,
					'icon' : attributes.ha_scroll_to_top_button_icon,
					'image' : attributes.ha_scroll_to_top_button_image,
					'text' : attributes.ha_scroll_to_top_button_text,
				};
				stt_data = Object.assign(stt_data, data);
			}

			// if( 'ha_scroll_to_top_single_disable' != changeItem[0] ) {
			// 	var data = {
			// 		'enable_global_stt' : settings.ha_scroll_to_top_global,
			// 		'media_type' : settings.ha_scroll_to_top_media_type,
			// 		'icon' : settings.ha_scroll_to_top_button_icon,
			// 		'image' : settings.ha_scroll_to_top_button_image,
			// 		'text' : settings.ha_scroll_to_top_button_text,
			// 	};
			// 	stt_data = Object.assign(stt_data, data);
			// }

			console.log( stt_data );

			// console.log(this);
			// console.log(attributes);
			// console.log(this.model.controls);
			// console.log(this.model.getControl('ha_scroll_to_top_button_text'));
			//console.log(this.setSetting());

				// console.log(stt_data);
				// this.save();

			// var message = {
			// 	'check' : 'sttMessage',
			//
			// };
			// console.log(message);

			$("#elementor-preview-iframe")[0].contentWindow.postMessage(stt_data);
		}
		elementor.settings.page.addChangeCallback("ha_scroll_to_top_global", scroll_to_top_reloadPreview);
		elementor.settings.page.addChangeCallback("ha_scroll_to_top_media_type", scroll_to_top_reloadPreview);
		elementor.settings.page.addChangeCallback("ha_scroll_to_top_button_icon", scroll_to_top_reloadPreview);
		elementor.settings.page.addChangeCallback("ha_scroll_to_top_button_image", scroll_to_top_reloadPreview);
		elementor.settings.page.addChangeCallback("ha_scroll_to_top_button_text", scroll_to_top_reloadPreview);
		elementor.settings.page.addChangeCallback("ha_scroll_to_top_single_disable", scroll_to_top_reloadPreview);

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

		registerDarkModeStylesheet();
	});

	/**
	 * Add pro widgets placeholder
	 */
	elementor.hooks.addFilter('panel/elements/regionViews', function (regionViews) {
		if (HappyAddonsEditor.hasPro || _.isEmpty(HappyAddonsEditor.placeholder_widgets)) {
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

		_.each(HappyAddonsEditor.placeholder_widgets, function (widget, name) {
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
					headerMessage: haGetTranslated('promotionDialogHeader', [this.model.get('title')]),
					message: haGetTranslated('promotionDialogMessage', [this.model.get('title')]),
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
	var WidgetList = elementor.modules.controls.Select2.extend({
		onBeforeRender: function() {
			if (this.container && this.container.type === 'section') {
				var widgetsConfig = elementor.widgetsCache || elementor.config.widgets,
					widgets = {};

				this.container.children.forEach(function(column) {
					var $widgets = column.view.$childViewContainer.children('[data-widget_type]');

					$widgets.each(function(index, widget) {
						var name = $(widget).data('widget_type'),
							name = name.slice(0, name.lastIndexOf('.')),
							config = !_.isUndefined(widgetsConfig[name]) ? widgetsConfig[name] : false;

						if (config) {
							widgets[config.widget_type] = config.title + ' ('+config.widget_type+')';
						}
					});
				});

				this.model.set('options', widgets);
			}
		}
	});

	elementor.addControlView('widget-list', WidgetList);

	var AdvancedSelect2 = elementor.modules.controls.BaseData.extend({
		getSelect2Placeholder: function() {
			return this.ui.select.children( 'option:first[value=""]' ).text() || this.model.get('placeholder');
		},

		getDependencyArgs: function() {
			var self = this,
				args = self.model.get('dynamic_params');

			if (!_.isObject(args)) {
				args = {};
			}
			// console.log(self.container.settings.get('post_type'));
			if (args.control_dependency && _.isObject(args.control_dependency)) {
				_.each(args.control_dependency, function(prop, key) {
					args[key] = self.container.settings.get(prop);
				});
			}

			return args;
		},

		getSelect2DefaultOptions: function() {
			var _this = this;

			return {
				allowClear: true,
				placeholder: this.getSelect2Placeholder(),
				dir: elementorCommon.config.isRTL ? 'rtl' : 'ltr',
				minimumInputLength: 1,
				ajax: {
					url     : ajaxurl,
					dataType: 'json',
					method  : 'POST',
					delay   : 250,
					data: function(params) {
						var defaults = {
							nonce      : HappyAddonsEditor.editor_nonce,
							action     : 'ha_process_dynamic_select',
							object_type: 'post',
							query_term : params.term,
						};
						return $.extend(defaults, _this.model.get('dynamic_params'), _this.getDependencyArgs());
					},

					processResults: function(response) {
						if (!response.success || response.data.length === 0) {
							return {
								results: [{
									'id'      : -1,
									'text'    : 'No results found',
									'disabled': true
								}]
							}
						}

						var data = [];

						_.each(response.data, function(title, id) {
							data.push({
								id: id,
								text: title,
							});
						});

						return {
							results: data
						}
					},

					cache: true
				}
			};
		},

		getSelect2Options: function() {
			return $.extend(this.getSelect2DefaultOptions(), this.model.get('select2options'));
		},

		addLoadingSpinner: function() {
			this.$el.find('.elementor-control-title').after('<span class="elementor-control-spinner">&nbsp;<i class="eicon-spinner eicon-animation-spin"></i>&nbsp;</span>');
		},

		onBeforeRender: function() {
			if (this.isRendered) {
				return;
			}

			var _this = this,
				savedValues = this.getControlValue();

			if (_.isEmpty(savedValues)) {
				return;
			}

			if (!_.isArray(savedValues)) {
				savedValues = [savedValues];
			}

			var defaults = {
				nonce       : HappyAddonsEditor.editor_nonce,
				action      : 'ha_process_dynamic_select',
				object_type : 'post',
				saved_values: savedValues,
			};

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: $.extend(defaults, _this.model.get('dynamic_params'), _this.getDependencyArgs()),
				beforeSend: _this.addLoadingSpinner.bind(this),
				success: function(response) {
					if (response.success && response.data.length !== 0) {
						// Prefix an extra space to maintain order and backward compatibility
						var ids = ids = _.keys(response.data).map(function(id) {
							return ' ' + $.trim(id);
						});

						_this.container.settings.set(_this.model.get('name'), ids);
						_this.model.set('options', response.data);
						_this.render();
					}
				}
			});
		},

		applySavedValue: function() {
			elementor.modules.controls.BaseData.prototype.applySavedValue.apply( this, arguments );

			var select2Instance = this.ui.select.data( 'select2' );

			if ( ! select2Instance ) {
				this.ui.select.select2( this.getSelect2Options() );

				if ( this.model.get('sortable') ) {
					this.initSortable();
				}
			} else {
				this.ui.select.trigger( 'change' );
			}
		},

		initSortable: function() {
			var $sortable = this.$el.find('ul.select2-selection__rendered'),
				_this = this;

			$sortable.sortable({
				containment: 'parent',

				update: function() {
					_this._orderSortedOption($sortable);

					_this.container.settings.setExternalChange(
						_this.model.get('name'),
						_this.ui.select.val()
					);

					_this.model.set('options', _this.ui.select.val());
				}
			});
		},

		_orderSortedOption: function($sortable) {
			var _this = this;

			$sortable.children('li[title]').each(function(i, obj) {
				var $elment = _this.ui.select.children('option').filter(function() {
					return $(this).html() == obj.title;
				});

				_this._moveOptionToEnd($elment)
			});
		},

		_moveOptionToEnd: function($elment) {
			var $parent = $elment.parent();

			$elment.detach();
			$parent.append($elment);
		},

		onBeforeDestroy: function() {
			// We always destroy the select2 instance because there are cases where the DOM element's data cache
			// itself has been destroyed but the select2 instance on it still exists
			this.ui.select.select2( 'destroy' );
			this.$el.remove();
		},
	});

	elementor.addControlView('ha_advanced_select2', AdvancedSelect2);
}(jQuery));
