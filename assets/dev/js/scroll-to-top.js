;(function ($) {
	"use strict";
	$(function () {
	  var offset = 100;
	  var speed = 300;
	  var duration = 300;
	  if ($(this).scrollTop() > offset) {
		$(".ha-scroll-to-top-wrap").removeClass("ha-scroll-to-top-hide");
	  }
	  $(window).scroll(function () {
		if ($(this).scrollTop() < offset) {
		  $(".ha-scroll-to-top-wrap").fadeOut(duration);
		} else {
		  $(".ha-scroll-to-top-wrap").fadeIn(duration);
		}
	  });
	  $(".ha-scroll-to-top-wrap").on("click", function () {
		$("html, body").animate({ scrollTop: 0 }, speed);
		return false;
	  });
	});
  })(jQuery);
