;(function($) {
    'use strict';

    $(function() {

        var $tabsWrapper = $('.ha-dashboard-tabs'),
            $tabsNav = $tabsWrapper.find('.ha-dashboard-tabs__nav'),
            $tabsContent = $tabsWrapper.find('.ha-dashboard-tabs__content'),
            $sidebarMenuWrapper = $('#toplevel_page_happy-addons'),
            $sidebarSubmenu = $sidebarMenuWrapper.find('.wp-submenu');

        $tabsNav.on('click', '.ha-dashboard-tabs__nav-item', function(event) {
            event.preventDefault();

            var $currentTab = $(event.currentTarget),
                tabContentId = event.currentTarget.hash,
                $currentTabContent = $tabsContent.find(tabContentId);

            $currentTab
                .addClass('tab--is-active')
                .siblings()
                .removeClass('tab--is-active');

            $currentTabContent
                .addClass('tab--is-active')
                .siblings()
                .removeClass('tab--is-active');

            $sidebarSubmenu.find('a').filter(function(i, a) {
                return tabContentId === a.hash;
            }).parent().addClass('current').siblings().removeClass('current');
        });

        if (window.location.hash) {
            $tabsNav.find('a[href="'+window.location.hash+'"]').click();
            $sidebarSubmenu.find('a').filter(function(i, a) {
                return window.location.hash === a.hash;
            }).parent().addClass('current').siblings().removeClass('current');
        }

        $sidebarSubmenu.on('click', 'a', function(event) {
            if ( ! event.currentTarget.hash) {
                return true;
            }
            event.preventDefault();

            var $currentItem = $(event.currentTarget);
            $currentItem.parent().addClass('current').siblings().removeClass('current');

            $tabsNav.find('a[href="'+event.currentTarget.hash+'"]').click();
        });

        var $widgetsForm = $('#ha-dashboard-widgets-form'),
            $widgetPlaceholder = $widgetsForm.find('.item--is-placeholder'),
            $saveButton = $widgetsForm.find('.ha-dashboard-btn--save');

        $widgetsForm.on('submit', function(event) {
            event.preventDefault();

            $.post({
                url: HappyDashboard.ajaxUrl,
                data: {
                    nonce: HappyDashboard.nonce,
                    action: HappyDashboard.action,
                    widgets: $widgetsForm.serialize()
                },
                beforeSend: function() {
                    $saveButton
                        .text('.....')
                        .css('animation', 'animateTextIndent infinite 2.5s');
                },
                success: function(response) {
                    if ( response.success ) {
                        var t = setTimeout(function () {
                            $saveButton
                                .css('animation', '')
                                .attr('disabled', true)
                                .text(HappyDashboard.savedLabel);
                            clearTimeout(t);
                        }, 500);
                    }
                }
            });
        });

        $widgetsForm.on('change', ':checkbox', function() {
            $saveButton.attr('disabled', false).text(HappyDashboard.saveChangesLabel);
        });

        $widgetPlaceholder.on('click', 'label, .ha-toggle', function() {
            $tabsNav.find('#tab-nav-pro').click();
        });
    });
}(jQuery));
