'use strict';
window.Happy = window.Happy || {};

(function ($, Happy, w) {
    var $window = $(w);

    function isMobileBreakpoint() {
        return ($window.width() < elementorFrontend.config.breakpoints.md);
    }

    function isTabletBreakpoint() {
        return ($window.width() >= elementorFrontend.config.breakpoints.md && $window.width() < elementorFrontend.config.breakpoints.lg);
    }

    $.fn.getHappySettings = function() {
        return this.data('happy-settings');
    };

    function initFilterable($scope, filterFn) {
        var $filterable = $scope.find('.hajs-gallery-filter');
        if ($filterable.length) {
            $filterable.on('click', 'button', function(event) {
                event.stopPropagation();

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
        $item.imagesLoaded().done(function() {
            $item.twentytwenty(settings);
        });
    };

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
    };

    Happy.initImageGrid = function($scope) {
        var $item = $scope.find('.hajs-image-grid');

        // var t = setTimeout(function() {
        //
        // }, 500);
        //
        // // $item.imagesLoaded().progress(function( instance, image ) {
        // //     image.isLoaded && $item.isotope('layout');
        // // });

        $item.imagesLoaded().done(function() {
            $item.isotope({
                itemSelector: '.ha-image-grid-item',
                layoutMode: 'fitRows',
                percentPosition: true
            });
        });

        initFilterable($scope, function(filter) {
            $item.isotope({
                filter: filter
            });
        });
    };

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
    };

    Happy.initCarousel = function($scope) {
        var $item = $scope.find('.hajs-carousel'),
            happySettings = $item.getHappySettings(),
            breakpointSettingKeys = ['slidesToShow'],
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
            rows: 0,
            // centerMode: true, // default false
            // vertical: true, // default false - vertical slide mode
            prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-chevron-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next"><i class="fa fa-chevron-right"></i></button>',
        }, settings));
    };

    $window.on('elementor/frontend/init', function() {
        var HappyEffects = elementorModules.frontend.handlers.Base.extend({
            onInit: function() {
                elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
                this.$container = this.$element.find('.elementor-widget-container')[0];
                this.run();
            },

            getDefaultSettings: function() {
                return {
                    targets: this.$container,
                    loop: true,
                    direction: 'alternate',
                    easing: 'easeInOutSine',
                };
            },

            onElementChange: function() {
                this.animation && this.animation.restart();
                this.run();
            },

            getConfig: function(key) {
                return this.getElementSettings('ha_floating_fx_' + key);
            },

            run: function() {
                var config = this.getDefaultSettings();

                if (this.getConfig('translate_toggle')) {
                    if (this.getConfig('translate_x.size') || this.getConfig('translate_x.sizes.to')) {
                        config.translateX = {
                            value: [this.getConfig('translate_x.sizes.from') || 0, this.getConfig('translate_x.size') || this.getConfig('translate_x.sizes.to')],
                            duration: this.getConfig('translate_duration.size'),
                            delay: this.getConfig('translate_delay.size') || 0
                        }
                    }
                    if (this.getConfig('translate_y.size') || this.getConfig('translate_y.sizes.to')) {
                        config.translateY = {
                            value: [this.getConfig('translate_y.sizes.from') || 0, this.getConfig('translate_y.size') || this.getConfig('translate_y.sizes.to')],
                            duration: this.getConfig('translate_duration.size'),
                            delay: this.getConfig('translate_delay.size') || 0
                        }
                    }
                }

                if (this.getConfig('rotate_toggle')) {
                    if (this.getConfig('rotate_x.size') || this.getConfig('rotate_x.sizes.to')) {
                        config.rotateX = {
                            value: [this.getConfig('rotate_x.sizes.from') || 0, this.getConfig('rotate_x.size') || this.getConfig('rotate_x.sizes.to')],
                            duration: this.getConfig('rotate_duration.size'),
                            delay: this.getConfig('rotate_delay.size') || 0
                        }
                    }
                    if (this.getConfig('rotate_y.size') || this.getConfig('rotate_y.sizes.to')) {
                        config.rotateY = {
                            value: [this.getConfig('rotate_y.sizes.from') || 0, this.getConfig('rotate_y.size') || this.getConfig('rotate_y.sizes.to')],
                            duration: this.getConfig('rotate_duration.size'),
                            delay: this.getConfig('rotate_delay.size') || 0
                        }
                    }
                    if (this.getConfig('rotate_z.size') || this.getConfig('rotate_z.sizes.to')) {
                        config.rotateZ = {
                            value: [this.getConfig('rotate_z.sizes.from') || 0, this.getConfig('rotate_z.size') || this.getConfig('rotate_z.sizes.to')],
                            duration: this.getConfig('rotate_duration.size'),
                            delay: this.getConfig('rotate_delay.size') || 0
                        }
                    }
                }

                if (this.getConfig('scale_toggle')) {
                    if (this.getConfig('scale_x.size') || this.getConfig('scale_x.sizes.to')) {
                        config.scaleX = {
                            value: [this.getConfig('scale_x.sizes.from') || 0, this.getConfig('scale_x.size') || this.getConfig('scale_x.sizes.to')],
                            duration: this.getConfig('scale_duration.size'),
                            delay: this.getConfig('scale_delay.size') || 0
                        }
                    }
                    if (this.getConfig('scale_y.size') || this.getConfig('scale_y.sizes.to')) {
                        config.scaleY = {
                            value: [this.getConfig('scale_y.sizes.from') || 0, this.getConfig('scale_y.size') || this.getConfig('scale_y.sizes.to')],
                            duration: this.getConfig('scale_duration.size'),
                            delay: this.getConfig('scale_delay.size') || 0
                        }
                    }
                }

                if (this.getConfig('translate_toggle') || this.getConfig('rotate_toggle') || this.getConfig('scale_toggle')) {
                    this.$container.style.setProperty('will-change', 'transform');
                    this.animation = anime(config);
                }
            }
        });

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
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/widget',
            function($scope) {
                elementorFrontend.elementsHandler.addHandler(HappyEffects, {$element: $scope});
            }
        );
    });

} (jQuery, Happy, window));
