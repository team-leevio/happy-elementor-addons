"use strict";

(function ($) {
  $(function () {
    var $clearCache = $(".hajs-clear-cache"),
      $haMenu = $("#toplevel_page_happy-addons .toplevel_page_happy-addons .wp-menu-name"),
      menuText = $haMenu.text();
    $haMenu.text(menuText.replace(/\s/, ""));
    $clearCache.on("click", "a", function (e) {
      e.preventDefault();
      var type = "all",
        $m = $(e.delegateTarget);
      if ($m.hasClass("ha-clear-page-cache")) {
        type = "page";
      }
      $m.addClass("ha-clear-cache--init");
      $.post(HappyAdmin.ajax_url, {
        action: "ha_clear_cache",
        type: type,
        nonce: HappyAdmin.nonce,
        post_id: HappyAdmin.post_id
      }).done(function (res) {
        $m.removeClass("ha-clear-cache--init").addClass("ha-clear-cache--done");
      });
    });
  });
})(jQuery);
jQuery(document).ready(function ($) {
  jQuery("[id^=wp-admin-bar-elementor_edit_doc_]").each(function (index) {
    var _long_id = $(this).attr("id");
    var _id = _long_id.replace("wp-admin-bar-elementor_edit_doc_", "");
    var _label = $("#" + _long_id + " .elementor-edit-link-type");
    jQuery.ajax({
      url: HappyAdmin.ajax_url,
      type: "get",
      dataType: "json",
      data: {
        nonce: HappyAdmin.nonce,
        action: "ha_cond_template_type",
        // AJAX action for admin-ajax.php
        post_id: _id
      },
      success: function success(response) {
        if (response && response.data) {
          var templateType = response.data;
          if (templateType && ["header", "footer", "single"].includes(templateType)) {
            _label.text("HA: " + templateType.toLowerCase().replace(/(?<= )[^\s]|^./g, function (a) {
              return a.toUpperCase();
            }));
          }
        }
      }
    });
  });
});
function getParameterByName(url, name) {
  var formatUrl = new URL(url);
  return formatUrl.searchParams.get(name);
}
jQuery(function ($) {
  var newTemplateModal = document.getElementById("tmpl-modal-new-template");
  if (newTemplateModal != null) {
    $("body").append(newTemplateModal.innerHTML);
  }
  MicroModal.init();
  var templateType = document.getElementById("ha-new-template-form__template-type");
  var templateName = document.getElementById("ha-new-template-form__post-title");
  var templateButton = document.getElementById("ha-new-template-form__submit");
  if (templateType != null) {
    templateType.addEventListener('change', checkButtonDisabled);
  }
  if (templateName != null) {
    templateName.addEventListener('input', checkButtonDisabled);
  }
  function checkButtonDisabled() {
    var typeVal = templateType.value;
    var nameVal = templateName.value;
    if (typeVal && nameVal) {
      templateButton.disabled = false;
    } else {
      templateButton.disabled = true;
    }
  }
  $("#ha-template-library-add-new").on("click", function (e) {
    e.preventDefault();
    MicroModal.show("modal-new-template");
  });
});