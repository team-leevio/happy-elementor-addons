"use strict";

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
function _iterableToArrayLimit(arr, i) { var _i = null == arr ? null : "undefined" != typeof Symbol && arr[Symbol.iterator] || arr["@@iterator"]; if (null != _i) { var _s, _e, _x, _r, _arr = [], _n = !0, _d = !1; try { if (_x = (_i = _i.call(arr)).next, 0 === i) { if (Object(_i) !== _i) return; _n = !1; } else for (; !(_n = (_s = _x.call(_i)).done) && (_arr.push(_s.value), _arr.length !== i); _n = !0); } catch (err) { _d = !0, _e = err; } finally { try { if (!_n && null != _i["return"] && (_r = _i["return"](), Object(_r) !== _r)) return; } finally { if (_d) throw _e; } } return _arr; } }
function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }
(function ($) {
  var modalTemplate = document.getElementById("tmpl-modal-template-condition");
  var conditionTemplate = document.getElementById("tmpl-elementor-new-template");
  var templateType = "";
  var postId = 0;
  var newConditions = [];
  var oldConditionCache = "";
  if (typeof elementor !== "undefined") {
    elementor.on("panel:init", function ($e) {
      postId = elementor.config.document.id;
      handleHaTemplateType(postId);
      getHaTemplateConds(postId);
      elementor.getPanelView().footer.currentView.addSubMenuItem("saver-options", {
        before: "save-draft",
        name: "haconditions",
        icon: "ha-template-elements",
        title: "Template Conditions",
        callback: function callback() {
          return elementor.trigger("ha:templateCondition");
        }
      });
    });
    elementor.channels.editor.on("elementorThemeBuilder:ApplyPreview", function ($e) {
      handleHaTemplateType(postId);
    });

    //elementor.getPanelView().getHeaderView().setTitle('a');
    elementor.on("set:page", function ($e) {});
  }
  $("body").append(modalTemplate.innerHTML);
  if (typeof elementor !== "undefined") {
    elementor.on("ha:templateCondition", function ($e) {
      //oldConditionCache
      var conditionContainer = $(".ha-template-condition-wrap");
      if (conditionContainer.html() == "") {
        conditionContainer.append(oldConditionCache);
        conditionContainer.find("select").trigger("change");
        // elementor.trigger("ha:templateConditionChange");
      }

      // notice remove
      $('.ha-template-notice').removeClass('error').text('');
      MicroModal.show("modal-new-template-condition");
    });
    elementor.on("ha:templateConditionChange", function ($e) {
      handleHaTemplateCondition();
    });
  }
  $(document).on("click", ".ha-cond-repeater-add", function () {
    var conditionContainer = $(".ha-template-condition-wrap");
    var uniqify = generateUniqeDom(conditionTemplate.innerHTML);
    conditionContainer.append(uniqify);
    elementor.trigger("ha:templateConditionChange");
    // ha_check_contradictory_condition();
  });

  $(document).on("click", ".ha-template-condition-remove", function () {
    $(this).parent().remove();
    elementor.trigger("ha:templateConditionChange");
  });
  $(document).on("click", "#ha-template-save-data", function () {
    saveConditionData();
  });
  $(document).on("change", ".ha-template-condition-wrap select", function (event) {
    handleAssignEvent(event);
    elementor.trigger("ha:templateConditionChange");
  });
  function generateUniqeDom(dom) {
    var randomid = Math.random().toString(36).replace("0.", "");
    dom = dom.replace(/{{([^{}]+)}}/g, randomid);
    return dom;
  }
  function handleAssignEvent(event) {
    if (event.target.localName == "select") {
      var parentID = event.target.dataset.parent;
      var selectedType = event.target.dataset.setting;
      var selected = event.target.value;
      var type = $("[data-id='type-" + parentID + "']");
      var name = $("[data-id='name-" + parentID + "']");
      var sub_name = $("[data-id='sub_name-" + parentID + "']");
      var sub_id = $("[data-id='sub_id-" + parentID + "']");
      if (selectedType == "type") {
        //TODO: Add prefix icon later on
      }
      if (selectedType == "name") {
        if (selected == "general") {
          sub_name.parent().hide();
          sub_id.parent().hide();
        } else {
          sub_name.parent().show();
          var selectedVal = sub_name.data("selected") ? sub_name.data("selected") : "";
          add_sub_name(sub_name, name.val(), selectedVal);
        }
      }
      if (selectedType == "sub_name") {
        var dataPair = {
          post: "post",
          in_category: "category",
          in_category_children: "category",
          in_post_tag: "post_tag",
          post_by_author: "author",
          page: "page",
          page_by_author: "author",
          child_of: "page",
          any_child_of: "page",
          by_author: "author"
        };
        if (dataPair.hasOwnProperty(selected)) {
          // Toggle Visibility
          sub_id.parent().show();
          var dataType = dataPair[selected];
          var dataVal = selected;
          if (["post", "page"].includes(dataType)) {
            dataVal = dataType;
            dataType = "post";
          }
          if (["category", "post_tag"].includes(dataType)) {
            dataVal = dataType;
            dataType = "tax";
          }
          sub_id.select2({
            ajax: {
              url: ajaxurl,
              dataType: "json",
              delay: 250,
              data: function data(params) {
                var query = {
                  nonce: HappyAddonsEditor.editor_nonce,
                  action: "ha_condition_autocomplete",
                  q: params.term,
                  object_type: dataType,
                  object_term: dataVal
                };
                return query;
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
              }
            },
            minimumInputLength: 2,
            cache: true,
            placeholder: "All",
            allowClear: true,
            dropdownCssClass: "ha-template-condition-dropdown"
          });
        } else {
          sub_id.parent().hide();
        }
      }
      if (selectedType == "sub_id") {}
    }
  }
  function handleHaTemplateCondition() {
    var conditions = [];
    var conditionItems = $(".ha-template-condition-wrap").find(".ha-template-condition-item");
    conditionItems.each(function () {
      var type = $(this).find(".ha-tce-type select").val();
      var name = $(this).find(".ha-tce-name select").val();
      var sub_name = $(this).find(".ha-tce-sub_name select").val();
      var sub_id = $(this).find(".ha-tce-sub_id select").val();
      var localCond = type + "/" + name;
      if (sub_name) {
        localCond += "/" + sub_name;
      }
      if (sub_id) {
        localCond += "/" + sub_id.trim();
      }
      conditions.push(localCond);
    });
    newConditions = conditions;
  }
  function handleHaTemplateType(id) {
    jQuery.ajax({
      url: ajaxurl,
      type: "get",
      dataType: "json",
      data: {
        nonce: HappyAddonsEditor.editor_nonce,
        action: "ha_cond_template_type",
        // AJAX action for admin-ajax.php
        post_id: id
      },
      success: function success(response) {
        if (response && response.data) {
          templateType = response.data;
        }
      }
    });
  }
  function getHaTemplateConds(id) {
    jQuery.ajax({
      url: ajaxurl,
      type: "get",
      dataType: "json",
      data: {
        nonce: HappyAddonsEditor.editor_nonce,
        action: "ha_cond_get_current",
        // AJAX action for admin-ajax.php
        template_id: id
      },
      success: function success(response) {
        if (response && response.data) {
          oldConditionCache = response.data;
        }
      }
    });
  }
  function add_sub_name(target, dataType, selectedVal) {
    jQuery.ajax({
      url: ajaxurl,
      type: "get",
      dataType: "json",
      data: {
        nonce: HappyAddonsEditor.editor_nonce,
        action: "ha_condition_autocomplete",
        // AJAX action for admin-ajax.php
        object_type: dataType
      },
      success: function success(data) {
        if (data) {
          if (data.data) {
            var optionHTML = populate_option(data.data, selectedVal);
            target.html(optionHTML);
          }
        }
      }
    });
  }
  function populate_option(optionData, selectedVal) {
    var optionHTML = "";
    for (var _i = 0, _Object$entries = Object.entries(optionData); _i < _Object$entries.length; _i++) {
      var _Object$entries$_i = _slicedToArray(_Object$entries[_i], 2),
        key = _Object$entries$_i[0],
        option = _Object$entries$_i[1];
      if (option.hasOwnProperty("type")) {
        optionHTML += "<optgroup label='" + option.title + "'>";
        for (var _i2 = 0, _Object$entries2 = Object.entries(option.conditions); _i2 < _Object$entries2.length; _i2++) {
          var _Object$entries2$_i = _slicedToArray(_Object$entries2[_i2], 2),
            subkey = _Object$entries2$_i[0],
            suboption = _Object$entries2$_i[1];
          var isPro = suboption.is_pro;
          var optionTitle = suboption.title;
          var optionKey = subkey;
          var isDisabled = "";
          var isSelected = selectedVal == optionKey ? " selected" : "";
          if (isPro) {
            optionTitle = optionTitle + " [Pro]";
            isDisabled = " disabled";
          }
          optionHTML += "<option value='" + optionKey + "' " + isDisabled + isSelected + ">" + optionTitle + "</option>";
        }
        optionHTML += "</optgroup>";
      } else {
        var isPro = option.is_pro;
        var optionTitle = option.title;
        var optionKey = key;
        var isDisabled = "";
        var isSelected = selectedVal == optionKey ? " selected" : "";
        if (isPro) {
          optionTitle = optionTitle + " [Pro]";
          isDisabled = " disabled";
        }
        optionHTML += "<option value='" + optionKey + "' " + isDisabled + isSelected + ">" + optionTitle + "</option>";
      }
    }
    return optionHTML;
  }
  function saveConditionData() {
    var $elBtn = document.getElementById("elementor-panel-saver-button-publish");
    $elBtn.classList.add("elementor-button-state");
    postId = elementor.config.document.id;
    jQuery.ajax({
      url: ajaxurl,
      type: "post",
      dataType: "json",
      data: {
        nonce: HappyAddonsEditor.editor_nonce,
        action: "ha_condition_update",
        // AJAX action for admin-ajax.php
        conds: newConditions,
        template_id: postId
      },
      success: function success(response) {
        if (response) {
          if (response.success) {
            MicroModal.close("modal-new-template-condition");
            $('.ha-template-notice').removeClass('error').text('');
          } else {
            // show notice
            if (response.hasOwnProperty('data') && response.data.hasOwnProperty('msg')) {
              $('.ha-template-notice').addClass('error').text(response.data.msg);
            } else {
              MicroModal.close("modal-new-template-condition");
              $('.ha-template-notice').removeClass('error').text('');
            }
          }
        }
      }
    });
    setTimeout(function () {
      $elBtn.classList.remove("elementor-button-state");
    }, 500);
  }
  elementor.saver.on('after:save', function (data) {
    if (data.status != "inherit") {
      elementor.trigger("ha:templateCondition");
    }
  });
})(jQuery);