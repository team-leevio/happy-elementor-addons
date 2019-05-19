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
        var $item = $scope.find('.hajs-slider'),
            settings = $item.getHappySettings(),
            pauseMap = {
                on_focus: 'pauseOnFocus',
                on_hover: 'pauseOnHover',
                on_dots_hover: 'pauseOnDotsHover'
            };

        settings[pauseMap[settings.pause || 'on_focus']] = true;
        switch (settings.navigation) {
            case 'arrow':
                settings.arrows = true;
                break;
            case 'dots':
                settings.dots = true;
                break;
            case 'both':
                settings.arrows = true;
                settings.dots = true;
                break;
        }
        delete settings.navigation;

        $item.slick($.extend({}, {
            // infinite:true, // default true
            // autoplay: true, // default true
            arrows: false, // default true
            dots: false, // default false
            checkVisible: false,
            // centerMode: true, // default false
            // vertical: true, // default false - vertical slide mode
            prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-chevron-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next"><i class="fa fa-chevron-right"></i></button>',
        }, settings));
    }

    Happy.initCarousel = function($scope) {
        var $item = $scope.find('.hajs-carousel'),
            happySettings = $item.getHappySettings(),
            breakpointSettingKeys = ['slidesToShow'],
            settings = {},
            pauseMap = {
                on_focus: 'pauseOnFocus',
                on_hover: 'pauseOnHover',
                on_dots_hover: 'pauseOnDotsHover'
            };

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

        settings[pauseMap[settings.pause || 'on_focus']] = true;
        settings.slidesToScroll = settings.slidesToShow;

        switch (settings.navigation) {
            case 'arrow':
                settings.arrows = true;
                break;
            case 'dots':
                settings.dots = true;
                break;
            case 'both':
                settings.arrows = true;
                settings.dots = true;
                break;
        }
        delete settings.navigation;

        $item.slick($.extend({}, {
            // infinite:true, // default true
            // autoplay: true, // default true
            arrows: false, // default true
            dots: false, // default false
            checkVisible: false,
            slidesToShow: 3,
            slidesToScroll: 3,
            // centerMode: true, // default false
            // vertical: true, // default false - vertical slide mode
            prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-chevron-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next"><i class="fa fa-chevron-right"></i></button>',
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
