'use strict';
window.Happy = window.Happy || {};

;(function ($, Happy) {
    var $window = $(window);

    $.fn.getHappySettings = function() {
        return this.data('happy-settings');
    }

    function initFilterable(filterFn) {
        var $filterable = this.find('.hajs-gallery-filter');
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
        var $item = $scope.find('.hajs-image-comparison');
        $item.twentytwenty($item.getHappySettings());
    }

    Happy.initJustifiedGallery = function($scope) {
        var $item = $scope.find('.hajs-justified-gallery');

        $item.justifiedGallery($.extend({}, {
            rowHeight : 150,
            lastRow : 'justify',
            margins : 10,
        }, $item.getHappySettings()));

        initFilterable.call($scope, function(filter) {
            $item.justifiedGallery({
                filter: '.' + filter
            });
        });
    }

    Happy.initImageGrid = function($scope) {
        var $item = $scope.find('.hajs-image-grid');

        $item.isotope({
            itemSelector: '.ha-image-grid-item',
            filter: '*'
        });

        initFilterable.call($scope, function(filter) {
            $item.isotope({
                filter: '.' + filter
            });
        });
    }

    Happy.initSlider = function($scope) {
        var $item = $scope.find('.hajs-slider');

        $item.owlCarousel($.extend({}, {
            loop:true,
            margin:10,
            autoplay: true,
            checkVisible: false,
            nav:true,
            navText: ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"],
            responsive: {
                0:{
                    items:1
                },
                600:{
                    items:1
                },
                1000:{
                    items:1
                }
            }
        }, $item.getHappySettings()));
    }

    $window.on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/ha-image-comparison.default',
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
    });

} (jQuery, Happy));
