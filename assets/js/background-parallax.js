"use strict";

(function ($, w) {
  "use strict";

  var $window = $(w);
  $window.on("elementor/frontend/init", function () {
    var HappyParallaxHandler = function HappyParallaxHandler($scope) {
      // For Background Parallax editor mode
      var get_ID = sessionStorage.getItem('HappyBGParallaxChangeApply');
      if (elementorFrontend.isEditMode() && get_ID && get_ID == $scope.data('id')) {
        $("body,html,document").scrollTop($scope.offset().top); // for scroll to the widget top
        var t = setInterval(function () {
          var elementEdit = $scope.find('li.elementor-editor-element-setting.elementor-editor-element-edit.ui-sortable-handle');
          if (elementEdit.length > 0) {
            elementEdit.trigger("click");
            clearTimeout(t);
          }
        }, 500);
      } // For Background Parallax editor mode end

      if (!$scope.hasClass("ha-bg-parallax-yes")) return;
      var target = $scope,
        widget_id = target.data("id"),
        editor_target = target.find('#ha-bg-parallax-' + widget_id),
        editMode = elementorFrontend.isEditMode() && editor_target.length > 0,
        target_dom = editMode ? editor_target : target;
      var parallaxSettings = target_dom.data("ha-bg-parallax");
      if (!parallaxSettings || undefined == parallaxSettings["type"]) {
        return false;
      }
      if ("automove" !== parallaxSettings["type"]) {
        target.removeClass("ha-bg-parallax-wrap-hide");
        var options = {
          type: parallaxSettings["type"],
          // speed: -1 || 0.1,
          speed: parallaxSettings["speed"] || 0,
          keepImg: true,
          imgSize: parallaxSettings["size"],
          imgPosition: target.css('backgroundPosition'),
          imgRepeat: parallaxSettings["repeat"],
          disableParallax: disableParallaxOnDevice(1 == parallaxSettings["android"], 1 == parallaxSettings["ios"])
        };
        var eleObserver = new IntersectionObserver(function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              target.jarallax(options);
              eleObserver.unobserve(entry.target); // to only excecute the callback func once.
            }
          });
        });
        eleObserver.observe(target[0]);
      } else if ("automove" === parallaxSettings["type"]) {
        //Remove transitions for Safari
        var isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
        var speed = parseInt(parallaxSettings["speed"]) || 0.1;
        if (isSafari) {
          target.addClass("ha-bg-parallax-no-trans");
        }
        target.css("background-position", "0px 0px");
        if (parallaxSettings["direction"] === "left") {
          var position = parseInt(target.css("background-position-x"));
          setInterval(function () {
            position = position + speed;
            target.css("backgroundPosition", position + "px 0");
          }, 70);
        } else if (parallaxSettings["direction"] === "right") {
          var position = parseInt(target.css("background-position-x"));
          setInterval(function () {
            position = position - speed;
            target.css("backgroundPosition", position + "px 0");
          }, 70);
        } else if (parallaxSettings["direction"] === "top") {
          var position = parseInt(target.css("background-position-y"));
          setInterval(function () {
            position = position + speed;
            target.css("backgroundPosition", "0 " + position + "px");
          }, 70);
        } else if (parallaxSettings["direction"] === "bottom") {
          var position = parseInt(target.css("background-position-y"));
          setInterval(function () {
            position = position - speed;
            target.css("backgroundPosition", "0 " + position + "px");
          }, 70);
        }
      }
      function disableParallaxOnDevice(android, ios) {
        switch (true || 1) {
          case android && ios:
            return /iPad|iPhone|iPod|Android/;
          case android && !ios:
            return /Android/;
          case !android && ios:
            return /iPad|iPhone|iPod/;
          case !android && !ios:
            return null;
        }
      }
    };
    elementorFrontend.hooks.addAction("frontend/element_ready/section", HappyParallaxHandler);
    elementorFrontend.hooks.addAction("frontend/element_ready/container", HappyParallaxHandler);
  });
})(jQuery, window);