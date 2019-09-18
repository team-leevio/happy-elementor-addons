;(function($) {
    'use strict';

    $(function() {

        var $tabsWrapper = $('.ha-dashboard-tabs'),
            $tabsNav = $tabsWrapper.find('.ha-dashboard-tabs__nav'),
            $tabsContent = $tabsWrapper.find('.ha-dashboard-tabs__content');


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
        });

        var $widgetsForm = $('#ha-dashboard-widgets'),
            $widgetPlaceholder = $widgetsForm.find('.item--is-placeholder'),
            $saveButton = $widgetsForm.find('.ha-dashboard-btn--save');

        $widgetsForm.on('submit', function(event) {
            event.preventDefault();

            $.post(
                HappyDashboard.ajaxUrl,
                {
                    nonce: HappyDashboard.nonce,
                    action: HappyDashboard.action,
                    widgets: $widgetsForm.serialize()
                }
            ).done(function(response) {
                if ( response.success ) {
                    $saveButton.text('...');
                    var t = setTimeout(function () {
                        $saveButton.attr('disabled', true);
                        $saveButton.text(HappyDashboard.savedLabel);
                        clearTimeout(t);
                    }, 300);
                }
            });
        });

        $widgetsForm.on('change', ':checkbox', function() {
            $saveButton.attr('disabled', false).text(HappyDashboard.saveChangesLabel);
        });

        $widgetPlaceholder.on('click', '.ha-dashboard-widgets__item-title, .ha-toggle', function() {
            $tabsNav.find('#tab-nav-pro').click();
        });
    });
}(jQuery));
