"use strict";

;
(function ($, HappyDashboard) {
  'use strict';

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
      $currentTab.addClass('tab--is-active').siblings().removeClass('tab--is-active');
      $currentTabContent.addClass('tab--is-active').siblings().removeClass('tab--is-active');
      window.location.hash = tabTargetHash;
      $sidebarSubmenu.find('a').filter(function (i, a) {
        return tabTargetHash === a.hash;
      }).parent().addClass('current').siblings().removeClass('current');
    });
    if (window.location.hash) {
      $tabsNav.find('a[href="' + window.location.hash + '"]').click();
      $sidebarSubmenu.find('a').filter(function (i, a) {
        return window.location.hash === a.hash;
      }).parent().addClass('current').siblings().removeClass('current');
    }
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
        beforeSend: function beforeSend() {
          $saveButton.text('.....').css('animation', 'animateTextIndent infinite 2.5s');
        },
        success: function success(response) {
          if (response.success) {
            var t = setTimeout(function () {
              $saveButton.css('animation', '').attr('disabled', true).text(HappyDashboard.savedLabel);
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
    $('.ha-action--btn').on('click', function (event) {
      event.preventDefault();
      var $currentAction = $(this),
        filter = $currentAction.data('filter'),
        action = $currentAction.data('action'),
        $all = $widgetsList.find('.ha-dashboard-widgets__item'),
        $free = $all.not('.item--is-pro'),
        $pro = $all.filter('.item--is-pro'),
        $toggle_widget = $all.not('.item--is-placeholder').find(':checkbox.ha-widget'),
        $toggle_feature = $all.not('.item--is-placeholder').find(':checkbox.ha-feature');
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
      if (action) {
        if ('enable' === action) {
          $toggle_widget.prop('checked', true);
        } else if ('disable' === action) {
          $toggle_widget.prop('checked', false);
        } else if ('enable_feature' === action) {
          $toggle_feature.prop('checked', true);
        } else if ('disable_feature' === action) {
          $toggle_feature.prop('checked', false);
        }
        $toggle_widget.trigger('change');
        $toggle_feature.trigger('change');
      }
    });
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
          data: $dashboardForm.serialize()
        },
        beforeSend: function beforeSend() {
          $dashboardAnalytics.text('.....').css('animation', 'animateTextIndent infinite 2.5s');
        },
        success: function success(response) {
          if (response.success) {
            location.reload();
          }
        }
      });
    });
  });
})(jQuery, window.HappyDashboard);