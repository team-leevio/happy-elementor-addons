"use strict";

(function ($) {
  "use strict";

  window.haHasIconLibrary = function () {
    return elementor.helpers && elementor.helpers.renderIcon;
  };
  window.haGetFeatureLabel = function (text) {
    var div = document.createElement("DIV");
    div.innerHTML = text;
    text = div.textContent || div.innerText || text;
    return text.length > 20 ? text.substring(0, 20) + "..." : text;
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
      oldIcon: "button_icon",
      iconPos: "button_icon_position",
      newIcon: "button_selected_icon",
      text: "button_text",
      link: "button_link",
      "class": "ha-btn ha-btn--link",
      textClass: "ha-btn-text"
    });
    if (!_.isObject(view)) {
      return;
    }
    settings = view.model.attributes.settings.toJSON();
    var buttonText = !_.isUndefined(settings[args.text]) ? settings[args.text] : "",
      hasOldIcon = !_.isUndefined(settings[args.oldIcon]) && settings[args.oldIcon] ? true : false,
      hasNewIcon = !_.isUndefined(settings[args.newIcon]) && _.isObject(settings[args.newIcon]) && settings[args.newIcon].value ? true : false;
    if (!buttonText && !hasNewIcon && !hasOldIcon) {
      return;
    }
    if (haHasIconLibrary()) {
      btnIconHTML = elementor.helpers.renderIcon(view, settings[args.newIcon], {
        "aria-hidden": true,
        "class": "ha-btn-icon"
      }, "i", "object"), btnMigrated = elementor.helpers.isIconMigrated(settings, args.newIcon);
    }
    view.addInlineEditingAttributes(args.text, "none");
    view.addRenderAttribute(args.text, "class", args.textClass);
    view.addRenderAttribute("button", "class", args["class"]);
    view.addRenderAttribute("button", "href", settings[args.link].url);
    if (hasNewIcon || hasOldIcon) {
      if (haHasIconLibrary() && btnIconHTML && btnIconHTML.rendered && (!hasOldIcon || btnMigrated)) {
        if (settings[args.newIcon].library === "svg") {
          btnIcon = '<span class="ha-btn-icon ha-btn-icon--svg">' + btnIconHTML.value + "</span>";
        } else {
          btnIcon = btnIconHTML.value;
        }
      } else if (hasOldIcon) {
        btnIcon = '<i class="ha-btn-icon ' + args.oldIcon + '" aria-hidden="true"></i>';
      }
    }
    if (buttonText && !hasNewIcon && !hasOldIcon) {
      buttonMarkup = ["<a " + view.getRenderAttributeString("button") + ">", "<span " + view.getRenderAttributeString(args.text) + ">", buttonText, "</span>", "</a>"];
    } else if (!buttonText && (hasNewIcon || hasOldIcon)) {
      buttonMarkup = ["<a " + view.getRenderAttributeString("button") + ">", btnIcon, "</a>"];
    } else if (buttonText && (hasNewIcon || hasOldIcon)) {
      if (settings[args.iconPos] === "before") {
        view.addRenderAttribute("button", "class", "ha-btn--icon-before");
        buttonBefore = btnIcon;
        buttonAfter = "<span " + view.getRenderAttributeString(args.text) + ">" + buttonText + "</span>";
      } else {
        view.addRenderAttribute("button", "class", "ha-btn--icon-after");
        buttonAfter = btnIcon;
        buttonBefore = "<span " + view.getRenderAttributeString(args.text) + ">" + buttonText + "</span>";
      }
      buttonMarkup = ["<a " + view.getRenderAttributeString("button") + ">", buttonBefore, buttonAfter, "</a>"];
    }
    return buttonMarkup.join("");
  };
  var registerDarkModeStylesheet = function registerDarkModeStylesheet() {
    var darkModeLinkID = "happy-addons-editor-dark-css",
      $darkModeLink = $("#" + darkModeLinkID);
    if (!$darkModeLink.length) {
      $darkModeLink = $("<link>", {
        id: darkModeLinkID,
        rel: "stylesheet",
        href: HappyAddonsEditor.dark_stylesheet_url
      });
    }
    elementor.settings.editorPreferences.model.on("change:ui_theme", function (model, newValue) {
      if ("light" === newValue) {
        $darkModeLink.remove();
        return;
      }
      $darkModeLink.attr("media", "auto" === newValue ? "(prefers-color-scheme: dark)" : "").appendTo(elementorCommon.elements.$body);
    });
  };
  elementor.on("panel:init", function () {
    $("#elementor-panel-elements-search-input").on("keyup", _.debounce(function () {
      $("#elementor-panel-elements").find(".hm").parents(".elementor-element").addClass("is-ha-widget");
    }, 100));
    function scrollToTop(newValue) {
      // $e.run( 'document/save/update' ).then( _.debounce( function () {
      // 	elementor.reloadPreview();
      // }, 1500));

      var changeItem = Object.entries(this.model.changed)[0];
      var settings = this.getSettings().settings; //get saved value
      var attributes = this.model.attributes;
      var stt_data = {
        'check': 'sttMessage',
        'changeValue': newValue,
        'changeItem': changeItem
      };
      if ('ha_scroll_to_top_single_disable' != changeItem[0]) {
        var data = {
          'enable_global_stt': attributes.ha_scroll_to_top_global,
          'media_type': attributes.ha_scroll_to_top_media_type,
          'icon': attributes.ha_scroll_to_top_button_icon,
          'image': attributes.ha_scroll_to_top_button_image,
          'text': attributes.ha_scroll_to_top_button_text
        };
        stt_data = Object.assign(stt_data, data);
      } else {
        $e.run('document/save/update').then(_.debounce(function () {
          elementor.reloadPreview();
        }, 1500));
      }
      $("#elementor-preview-iframe")[0].contentWindow.postMessage(stt_data);
    }
    var changeHandler = ["ha_scroll_to_top_global", "ha_scroll_to_top_media_type", "ha_scroll_to_top_button_icon", "ha_scroll_to_top_button_image", "ha_scroll_to_top_button_text", "ha_scroll_to_top_single_disable"];
    $.each(changeHandler, function (index, value) {
      elementor.settings.page.addChangeCallback(value, scrollToTop);
    });

    /**
     * Register grid layer shortcut
     */
    if (typeof $e !== "undefined" || $e !== null) {
      var option = {
        callback: function callback() {
          var ha_grid = elementor.settings.page.model.attributes.ha_grid;
          if ("" === ha_grid) {
            elementor.settings.page.model.setExternalChange("ha_grid", "yes");
          } else if ("yes" === ha_grid) {
            elementor.settings.page.model.setExternalChange("ha_grid", "");
          }
        }
      };
      $e.shortcuts.register("ctrl+shift+g", option);
      $e.shortcuts.register("cmd+shift+g", option);
    }
    registerDarkModeStylesheet();
  });

  /**
   * Add pro widgets placeholder
   */
  elementor.hooks.addFilter("panel/elements/regionViews", function (regionViews) {
    if (HappyAddonsEditor.hasPro || _.isEmpty(HappyAddonsEditor.placeholder_widgets)) {
      return regionViews;
    }
    var CATEGORY_NAME = "happy_addons_pro",
      elementsView = regionViews.elements.view,
      categoriesView = regionViews.categories.view,
      elementsCollection = regionViews.elements.options.collection,
      categoriesCollection = regionViews.categories.options.collection,
      proWidgets = [],
      ElementView,
      freeCategoryIndex;
    _.each(HappyAddonsEditor.placeholder_widgets, function (widget, name) {
      elementsCollection.add({
        name: "ha-" + name,
        title: widget.title,
        icon: widget.icon,
        categories: [CATEGORY_NAME],
        editable: false
      });
    });
    elementsCollection.each(function (element) {
      if (element.get("categories")[0] === CATEGORY_NAME) {
        proWidgets.push(element);
      }
    });
    freeCategoryIndex = categoriesCollection.findIndex({
      name: "happy_addons_category"
    });
    if (freeCategoryIndex) {
      categoriesCollection.add({
        name: "happy_addons_pro_category",
        title: "Happy Addons Pro",
        icon: "hm hm-happyaddons",
        defaultActive: false,
        sort: true,
        hideIfEmpty: true,
        items: proWidgets,
        promotion: false
      }, {
        at: freeCategoryIndex + 1
      });
    }
    ElementView = {
      className: function className() {
        var className = this.constructor.__super__.className.call(this);
        if (!this.isEditable() && this.isHappyWidget()) {
          className += " ha-element--promotion";
        }
        return className;
      },
      isHappyWidget: function isHappyWidget() {
        var widgetName = this.model.get("name");
        return widgetName != undefined && widgetName.indexOf("ha-") === 0;
      },
      onMouseDown: function onMouseDown() {
        if (!this.isHappyWidget()) {
          this.constructor.__super__.onMouseDown.call(this);
          return;
        }
        elementor.promotion.showDialog({
          title: haGetTranslated("promotionDialogHeader", [this.model.get("title")]),
          content: haGetTranslated("promotionDialogMessage", [this.model.get("title")]),
          targetElement: this.el,
          position: {
            blockStart: '-7'
          },
          actionButton: {
            url: "https://happyaddons.com/pricing/?utm_source=ha-editor-pro-widgets&utm_medium=wp-elementor-editor&utm_campaign=ha-upgrade-pro",
            text: HappyAddonsEditor.i18n.promotionDialogBtnTxt,
            classes: ['elementor-button', 'ha-btn--promotion', 'go-pro']
          }
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
    onBeforeRender: function onBeforeRender() {
      if (this.container && (this.container.type === "section" || this.container.type === "container")) {
        var widgetsConfig = elementor.widgetsCache || elementor.config.widgets,
          widgets = {};
        if (this.container.type === "section") {
          this.container.children.forEach(function (column) {
            var $widgets = column.view.$childViewContainer.children("[data-widget_type]");
            $widgets.each(function (index, widget) {
              var name = $(widget).data("widget_type"),
                name = name.slice(0, name.lastIndexOf(".")),
                config = !_.isUndefined(widgetsConfig[name]) ? widgetsConfig[name] : false;
              if (config) {
                widgets[config.widget_type] = config.title + " (" + config.widget_type + ")";
              }
            });
          });
        }
        ;
        if (this.container.type === "container") {
          var $has_widget = false;
          this.container.children.some(function (column) {
            if (column.view.children.length == 0) {
              $has_widget = column.view.children.length == 0;
            }
            return column.view.children.length == 0;
          });
          this.container.children.forEach(function (column) {
            var $widgets = column.view.$el.data("element_type") == 'widget' ? column.view.$el : column.view.$el.find('div[data-element_type="widget"]');
            $widgets.each(function (index, widget) {
              if ($(widget).data("element_type") == 'widget') {
                var name = $(widget).data("widget_type"),
                  name = name.slice(0, name.lastIndexOf(".")),
                  config = !_.isUndefined(widgetsConfig[name]) ? widgetsConfig[name] : false;
                if (config) {
                  widgets[config.widget_type] = config.title + " (" + config.widget_type + ")";
                }
              }
            });
          });
        }
        ;
        this.model.set("options", widgets);
      }
    }
  });
  elementor.addControlView("widget-list", WidgetList);
  var AdvancedSelect2 = elementor.modules.controls.BaseData.extend({
    getSelect2Placeholder: function getSelect2Placeholder() {
      return this.ui.select.children('option:first[value=""]').text() || this.model.get("placeholder");
    },
    getDependencyArgs: function getDependencyArgs() {
      var self = this,
        args = self.model.get("dynamic_params");
      if (!_.isObject(args)) {
        args = {};
      }
      if (args.control_dependency && _.isObject(args.control_dependency)) {
        _.each(args.control_dependency, function (prop, key) {
          args[key] = self.container.settings.get(prop);
        });
      }
      return args;
    },
    getSelect2DefaultOptions: function getSelect2DefaultOptions() {
      var _this = this;
      return {
        allowClear: true,
        placeholder: this.getSelect2Placeholder(),
        dir: elementorCommon.config.isRTL ? "rtl" : "ltr",
        minimumInputLength: 1,
        ajax: {
          url: ajaxurl,
          dataType: "json",
          method: "POST",
          delay: 250,
          data: function data(params) {
            var defaults = {
              nonce: HappyAddonsEditor.editor_nonce,
              action: "ha_process_dynamic_select",
              object_type: "post",
              query_term: params.term
            };
            return $.extend(defaults, _this.model.get("dynamic_params"), _this.getDependencyArgs());
          },
          processResults: function processResults(response) {
            if (!response.success || response.data.length === 0) {
              return {
                results: [{
                  id: -1,
                  text: "No results found",
                  disabled: true
                }]
              };
            }
            var data = [];
            _.each(response.data, function (title, id) {
              data.push({
                id: id,
                text: title
              });
            });
            return {
              results: data
            };
          },
          cache: true
        }
      };
    },
    getSelect2Options: function getSelect2Options() {
      return $.extend(this.getSelect2DefaultOptions(), this.model.get("select2options"));
    },
    addLoadingSpinner: function addLoadingSpinner() {
      this.$el.find(".elementor-control-title").after('<span class="elementor-control-spinner">&nbsp;<i class="eicon-spinner eicon-animation-spin"></i>&nbsp;</span>');
    },
    onBeforeRender: function onBeforeRender() {
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
        nonce: HappyAddonsEditor.editor_nonce,
        action: "ha_process_dynamic_select",
        object_type: "post",
        saved_values: savedValues
      };
      $.ajax({
        url: ajaxurl,
        type: "POST",
        data: $.extend(defaults, _this.model.get("dynamic_params"), _this.getDependencyArgs()),
        beforeSend: _this.addLoadingSpinner.bind(this),
        success: function success(response) {
          if (response.success && response.data.length !== 0) {
            // Prefix an extra space to maintain order and backward compatibility
            var ids = ids = _.keys(response.data).map(function (id) {
              return " " + $.trim(id);
            });
            _this.container.settings.set(_this.model.get("name"), ids);
            _this.model.set("options", response.data);
            _this.render();
          }
        }
      });
    },
    applySavedValue: function applySavedValue() {
      elementor.modules.controls.BaseData.prototype.applySavedValue.apply(this, arguments);
      var select2Instance = this.ui.select.data("select2");
      if (!select2Instance) {
        this.ui.select.select2(this.getSelect2Options());
        if (this.model.get("sortable")) {
          this.initSortable();
        }
      } else {
        this.ui.select.trigger("change");
      }
    },
    initSortable: function initSortable() {
      var $sortable = this.$el.find("ul.select2-selection__rendered"),
        _this = this;
      $sortable.sortable({
        containment: "parent",
        update: function update() {
          _this._orderSortedOption($sortable);
          _this.container.settings.setExternalChange(_this.model.get("name"), _this.ui.select.val());
          _this.model.set("options", _this.ui.select.val());
        }
      });
    },
    _orderSortedOption: function _orderSortedOption($sortable) {
      var _this = this;
      $sortable.children("li[title]").each(function (i, obj) {
        var $elment = _this.ui.select.children("option").filter(function () {
          return $(this).html() == obj.title;
        });
        _this._moveOptionToEnd($elment);
      });
    },
    _moveOptionToEnd: function _moveOptionToEnd($elment) {
      var $parent = $elment.parent();
      $elment.detach();
      $parent.append($elment);
    },
    onBeforeDestroy: function onBeforeDestroy() {
      // We always destroy the select2 instance because there are cases where the DOM element's data cache
      // itself has been destroyed but the select2 instance on it still exists
      this.ui.select.select2("destroy");
      this.$el.remove();
    }
  });
  elementor.addControlView("ha_advanced_select2", AdvancedSelect2);
})(jQuery);