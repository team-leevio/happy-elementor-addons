;(function($) {
    'use strict';

    $(function() {

        var initTabs = function() {
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
        };

        initTabs();
    });
}(jQuery));
