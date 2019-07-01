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
            // centerMode: true, // default false
            // vertical: true, // default false - vertical slide mode
            prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-chevron-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next"><i class="fa fa-chevron-right"></i></button>',
        }, settings));
    };

    $window.on( 'elementor/frontend/init', function() {
        var Happy_Effects = elementorModules.frontend.handlers.Base.extend({
            onInit: function() {
                elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
                this.run();
            },

            getTheElement: function() {
                return this.$element.find('.elementor-widget-container')[0];
            },

            resetFx: function() {
                anime.remove(this.getTheElement());
                this.getTheElement() && this.getTheElement().removeAttribute('style');
            },

            onDestroy: function() {
                elementorModules.frontend.handlers.Base.prototype.onDestroy.apply(this, arguments);
                this.resetFx();
            },

            onElementChange: function() {
                this.transformCleanup();
                this.resetFx();
                this.run();
            },

            transformCleanup: function() {
                var model = elementorFrontend.config.elements.data[this.getModelCID()];

                // console.log(model.get('ha_transform_fx_translate_toggle'));

                if (!model.get('ha_transform_fx_translate_toggle')) {
                    // console.log(model.get('ha_transform_fx_translate_x'));
                    // model.set('ha_transform_fx_translate_x', $.extend({}, model.get('ha_transform_fx_translate_x'), {size: 0}));
                    // console.log(model.get('ha_transform_fx_translate_x'));
                    // model.set($.extend({}, model.get('ha_transform_fx_translate_y'), {size: ''}));
                    // model.set('ha_transform_fx_translate_x', {size: 0});
                    // model.set('ha_transform_fx_translate_y', {size: 0});
                    // elementorModules.frontend.handlers.Base.prototype.setSettings.apply(this, ['ha_transform_fx_translate_x.size', '']);
                    // this.setSettings('ha_transform_fx_translate_x.size', 0);
                    // this.setSettings('ha_transform_fx_translate_y.size', 0);
                }
            },

            run: function() {
                var settings = this.getElementSettings(),
                    fxSettings = {
                        targets: this.getTheElement(),
                        loop: true,
                        direction: 'alternate',
                        easing: 'easeInOutSine'
                    };

                if (settings.ha_floating_fx_translate_toggle) {
                    if (settings.ha_floating_fx_translate_x.size) {
                        fxSettings.translateX = {
                            value: settings.ha_floating_fx_translate_x.size,
                            duration: settings.ha_floating_fx_translate_duration.size,
                            delay: settings.ha_floating_fx_translate_delay.size || 0
                        }
                    }
                    if (settings.ha_floating_fx_translate_y.size) {
                        fxSettings.translateY = {
                            value: settings.ha_floating_fx_translate_y.size,
                            duration: settings.ha_floating_fx_translate_duration.size,
                            delay: settings.ha_floating_fx_translate_delay.size || 0
                        }
                    }
                }

                if (settings.ha_floating_fx_rotate_toggle) {
                    if (settings.ha_floating_fx_rotate_x.size) {
                        fxSettings.rotateX = {
                            value: settings.ha_floating_fx_rotate_x.size,
                            duration: settings.ha_floating_fx_rotate_duration.size,
                            delay: settings.ha_floating_fx_rotate_delay.size || 0
                        }
                    }
                    if (settings.ha_floating_fx_rotate_y.size) {
                        fxSettings.rotateY = {
                            value: settings.ha_floating_fx_rotate_y.size,
                            duration: settings.ha_floating_fx_rotate_duration.size,
                            delay: settings.ha_floating_fx_rotate_delay.size || 0
                        }
                    }
                    if (settings.ha_floating_fx_rotate_z.size) {
                        fxSettings.rotateZ = {
                            value: settings.ha_floating_fx_rotate_z.size,
                            duration: settings.ha_floating_fx_rotate_duration.size,
                            delay: settings.ha_floating_fx_rotate_delay.size || 0
                        }
                    }
                }

                if (settings.ha_floating_fx_scale_toggle) {
                    if (settings.ha_floating_fx_scale_x.size) {
                        fxSettings.scaleX = {
                            value: settings.ha_floating_fx_scale_x.size,
                            duration: settings.ha_floating_fx_scale_duration.size,
                            delay: settings.ha_floating_fx_scale_delay.size || 0
                        }
                    }
                    if (settings.ha_floating_fx_scale_y.size) {
                        fxSettings.scaleY = {
                            value: settings.ha_floating_fx_scale_y.size,
                            duration: settings.ha_floating_fx_scale_duration.size,
                            delay: settings.ha_floating_fx_scale_delay.size || 0
                        }
                    }
                }

                if (settings.ha_floating_fx_translate_toggle || settings.ha_floating_fx_rotate_toggle || settings.ha_floating_fx_scale_toggle) {
                    this.getTheElement() && this.getTheElement().style.setProperty('will-change', 'transform');
                    anime(fxSettings);
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
            function ($scope) {
                window.ele = new Happy_Effects({ $element: $scope });
            }
        );
    });

} (jQuery, Happy, window));
