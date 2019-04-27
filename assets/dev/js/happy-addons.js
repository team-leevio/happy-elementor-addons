'use strict';
window.Happy = window.Happy || {};

;(function ($, Happy) {
    var $window = $(window);

    function isMobileBreakpoint() {
        return ($window.width() < elementorFrontend.config.breakpoints.md);
    }

    function isTabletBreakpoint() {
        return ($window.width() >= elementorFrontend.config.breakpoints.md && $window.width() < elementorFrontend.config.breakpoints.lg);
    }

    $.fn.getHappySettings = function() {
        return this.data('happy-settings');
    }

    function initFilterable($scope, filterFn) {
        var $filterable = $scope.find('.hajs-gallery-filter');
        if ($filterable.length) {
            $filterable.on('click', 'button', function() {
                var $current = $(this);
                $current
                    .parent()
                    .addClass('ha-filter-active')
                    .siblings()
                    .removeClass('ha-filter-active');
                filterFn($current.data('filter'));
            });
        }
    }

    Happy.initImageComparison = function($scope) {
        var $item = $scope.find('.hajs-image-comparison'),
            settings = $item.getHappySettings(),
            fieldMap = {
                on_hover: 'move_slider_on_hover',
                on_swipe: 'move_with_handle_only',
                on_click: 'click_to_move'
            };

        settings[fieldMap[settings.move_handle || 'on_swipe']] = true;
        delete settings.move_handle;

        $item.twentytwenty(settings);
    }

    Happy.initJustifiedGallery = function($scope) {
        var $item = $scope.find('.hajs-justified-gallery');

        $item.justifiedGallery($.extend({}, {
            rowHeight : 150,
            lastRow : 'justify',
            margins : 10,
        }, $item.getHappySettings()));

        initFilterable($scope, function(filter) {
            $item.justifiedGallery({
                filter: filter
            });
        });
    }

    Happy.initImageGrid = function($scope) {
        var $item = $scope.find('.hajs-image-grid');

        $item.isotope({
            itemSelector: '.ha-image-grid-item',
            filter: '*',
            percentPosition: true
        });

        $item.imagesLoaded().progress(function( instance, image ) {
            image.isLoaded && $item.isotope('layout');
        });

        initFilterable($scope, function(filter) {
            $item.isotope({
                filter: filter
            });
        });
    }

    Happy.initSlider = function($scope) {
        var $item = $scope.find('.hajs-slider');

        $item.owlCarousel($.extend({}, {
            loop: true,
            margin: 10,
            autoplay: true,
            checkVisible: false,
            nav: true,
            items: 1,
            navText: ['<i class="fa fa-chevron-left"></i>','<i class="fa fa-chevron-right"></i>'],
        }, $item.getHappySettings()));
    }

    Happy.initCarousel = function($scope) {
        var $item = $scope.find('.hajs-carousel'),
            happySettings = $item.getHappySettings(),
            breakpointSettingKeys = ['nav', 'dots', 'items'],
            settings = {};

        $.each(happySettings, function(key, val) {
            if (breakpointSettingKeys.indexOf(key) !== -1) {
                if (isMobileBreakpoint()) {
                    settings[key] = happySettings[key + '_mobile'];
                } else if (isTabletBreakpoint()) {
                    settings[key] = happySettings[key + '_tablet'];
                } else {
                    settings[key] = val;
                }
            } else {
                settings[key] = val;
            }
        });

        $item.owlCarousel($.extend({}, {
            loop:true,
            margin: 10,
            autoplay: true,
            checkVisible: false,
            nav: true,
            items: 3,
            center: true,
            navText: ['<i class="fa fa-chevron-left"></i>','<i class="fa fa-chevron-right"></i>'],
        }, settings));
    }

    $window.on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/ha-image-compare.default',
            Happy.initImageComparison
        );
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/ha-justified-gallery.default',
            Happy.initJustifiedGallery
        );
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/ha-image-grid.default',
            Happy.initImageGrid
        );
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/ha-slider.default',
            Happy.initSlider
        );
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/ha-carousel.default',
            Happy.initCarousel
        );
    });

} (jQuery, Happy));
