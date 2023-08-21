;(function( $ ) {
	'use strict';
	var $window = $(window),
		debounce = function(func, wait, immediate) {
			var timeout;
			return function() {
				var context = this, args = arguments;
				var later = function() {
					timeout = null;
					if (!immediate) func.apply(context, args);
				};
				var callNow = immediate && !timeout;
				clearTimeout(timeout);
				timeout = setTimeout(later, wait);
				if (callNow) func.apply(context, args);
			};
		};

	$window.on('elementor/frontend/init', function() {
		var ModuleHandler = elementorModules.frontend.handlers.Base,
			EqualHeightHandler;

		EqualHeightHandler = ModuleHandler.extend({
			CACHED_ELEMENTS: [],

			isEqhEnabled: function() {
				return (
					this.getElementSettings( '_ha_eqh_enable' ) === 'yes' &&
					$.fn.matchHeight
				);
			},

			isDisabledOnDevice: function() {
				var windowWidth = $window.outerWidth(),
					mobileWidth = elementorFrontendConfig.breakpoints.md,
					tabletWidth = elementorFrontendConfig.breakpoints.lg;

				if ( 'yes' == this.getElementSettings('_ha_eqh_disable_on_mobile') && windowWidth < mobileWidth) {
					return true;
				}

				if ( 'yes' == this.getElementSettings('_ha_eqh_disable_on_tablet') && windowWidth >= mobileWidth && windowWidth < tabletWidth) {
					return true;
				}

				return false;
			},

			getEqhTo: function() {
				return this.getElementSettings('_ha_eqh_to') || 'widget';
			},

			getEqhWidgets: function() {
				return this.getElementSettings('_ha_eqh_widget') || [];
			},

			getTargetElements: function() {
				var _this = this;

				return this.getEqhWidgets().map(function(widget) {
					// console.log(widget);
					// console.log(_this.$element);
					// console.log(_this.$element.data("element_type"));
					if ( false && _this.$element.data("element_type") === "container" ) {
						var $key = 0;
						var $widgets = {};
						var $container = _this.$element;
						let cls = '.elementor-widget-'+widget + ' .elementor-widget-container';

						/*
						Container > e-con-inner > conteiner > widget
						Container > conteiner > widget
						Container > e-con-inner > conteiner > e-con-inner  > widget
						Container > conteiner > e-con-inner  > widget
						Container > widget
						Container > e-con-inner > widget
						*/
						// console.group(_this.$element.data("element_type"));

						console.log($container);
						/* console.log($container.find(' > .e-con-inner > div[data-element_type="container"] > '+cls));
						console.log($container.find(' > div[data-element_type="container"] > '+cls));

						console.log($container.find(' > .e-con-inner > div[data-element_type="container"] > .e-con-inner > '+cls));
						console.log($container.find(' > div[data-element_type="container"] > .e-con-inner > '+cls));

						console.log($container.find(' > .e-con-inner > '+cls));
						console.log($container.find(' > '+cls)); */
						// console.log($container.find(' > div[data-element_type="container"]').length);

						if($container.find(' > .e-con-inner > div[data-element_type="container"] > '+cls).length){
							$widgets = $container.find(' > .e-con-inner > div[data-element_type="container"] > '+cls);
							// $container.find(' > .e-con-inner > div[data-element_type="container"] > '+cls).each(function(){
							// 	// console.log($(this));
							// 	let id = $(this).parent().data('id');
							// 	// $widgets.push($(this)[0]);
							// 	if( ! $widgets.hasOwnProperty(id) ){
							// 		$widgets[id] = $(this)[0];
							// 	}
							// 	$key += 1;
							//   });
						}

						if($container.find(' > div[data-element_type="container"] > '+cls).length){

							if( $widgets.length ) {
								let $key = $widgets.length;
							$container.find(' > div[data-element_type="container"] > '+cls).each(function(){
								// console.log($(this).parent().data('id'));
								let id = $(this).parent().data('id');
								// $widgets.push($(this)[0]);
								if( ! $widgets.hasOwnProperty($key) ){
									$widgets[$key] = $(this)[0];

								}
								$key += 1;
								});
								$widgets.length = $key;
							} else {
								$widgets = $container.find(' > div[data-element_type="container"] > '+cls);
							}
						}

						if($container.find(' > .e-con-inner > div[data-element_type="container"] > .e-con-inner > '+cls).length){
							if( $widgets.length ) {
								let $key = $widgets.length;
								$container.find(' > .e-con-inner > div[data-element_type="container"] > .e-con-inner > '+cls).each(function(){
									// console.log($(this));
									let id = $(this).parent().data('id');
									// $widgets.push($(this)[0]);
									if( ! $widgets.hasOwnProperty($key) ){
										$widgets[$key] = $(this)[0];
									}
									$key += 1;
								  });
								  $widgets.length = $key;
							} else{
								$widgets = $container.find(' > .e-con-inner > div[data-element_type="container"] > .e-con-inner > '+cls);
							}
						}

						if($container.find(' > div[data-element_type="container"] > .e-con-inner > '+cls).length){

							if( $widgets.length ) {
								let $key = $widgets.length;
								$container.find(' > div[data-element_type="container"] > .e-con-inner > '+cls).each(function(){
									// console.log($(this));
									let id = $(this).parent().data('id');
									// $widgets.push($(this)[0]);
									if( ! $widgets.hasOwnProperty($key) ){
										$widgets[$key] = $(this)[0];
									}
									$key += 1;
								  });
								  $widgets.length = $key;

							} else{
								$widgets = $container.find(' > div[data-element_type="container"] > .e-con-inner > '+cls);
							}
						}

						if($container.find(' > .e-con-inner > '+cls).length){

							if( $widgets.length ) {
								let $key = $widgets.length;
								$container.find(' > .e-con-inner > '+cls).each(function(){
									let id = $(this).parent().data('id');
									// $widgets.push($(this)[0]);
									if( ! $widgets.hasOwnProperty($key) ){
										$widgets[$key] = $(this)[0];
									}
									$key += 1;
								});
								$widgets.length = $key;

							} else{
								$widgets = $container.find(' > .e-con-inner > '+cls);
							}
						}

						if($container.find(' > '+cls).length){

							if( $widgets.length ) {
								let $key = $widgets.length;
								$container.find(' > '+cls).each(function(){
									let id = $(this).parent().data('id');
									// $widgets.push($(this)[0]);
									if( ! $widgets.hasOwnProperty($key) ){
										$widgets[$key] = $(this)[0];
									}
									$key += 1;
								});
								$widgets.length = $key;

							} else{
								$widgets = $container.find(' > '+cls);
							}
						}

						console.log({...$widgets});
						// console.log( Object.assign({}, $widgets) );
						return $widgets;
						// if($widgets.length){
							// return $widgets;
							// return Object.assign({}, $widgets);
						// }



						/* if( $container.find(' > .e-con-inner > div[data-element_type="container"] > '+cls).length > 0 ) {
							//if has 3 level inner widget
							console.log('if has immidiate widget');
							// console.log( $container.find(' > .e-con-inner > div[data-element_type="container"] > '+cls) );
							return $container.find(' > .e-con-inner > div[data-element_type="container"] > '+cls);

						}
						else if( $container.find(' > '+cls).length > 0 ) {
							//else if it has 1 level inner widget
							console.log('if has immidiate widget');
							// console.log( $container.find(' > '+cls) );
							return $container.find(' > '+cls);

						}
						else if ( $container.find(' > .e-con-inner > '+cls).length > 0 ) {
							//else if it has 2 level inner widget
							console.log('if has immidiate container');
							// console.log( $container.find(' > .e-con-inner > '+cls) );
							return $container.find(' > .e-con-inner > '+cls);
						}
						*/
						// console.groupEnd();

					}
					console.group(_this.$element.data("element_type"));
					console.log(_this.$element.find('.elementor-widget-'+widget + ' .elementor-widget-container'));
					console.groupEnd();

					return _this.$element.find('.elementor-widget-'+widget + ' .elementor-widget-container');
				});
			},

			bindEvents: function () {
				if (this.isEqhEnabled()) {
					this.run();

					$window.on('resize scroll orientationchange', debounce(this.run.bind(this), 80));
					// $window.on('resize orientationchange', debounce(this.run.bind(this), 80));
				}
			},

			onElementChange: debounce(function(prop, ele) {
				if (prop.indexOf('_ha_eqh') === -1) {
					return;
				}

				this.unbindMatchHeight(true);
				this.run();
			}, 100),

			unbindMatchHeight: function(isCachedOnly) {
				if (isCachedOnly) {
					this.CACHED_ELEMENTS.forEach(function($el) {
						$el.matchHeight({remove: true});
					});

					this.CACHED_ELEMENTS = [];
				} else {
					this.getTargetElements().forEach(function($el) {
						$el && $el.matchHeight({remove: true});
					});
				}
			},

			run: function() {
				var _this = this;

				if (this.isDisabledOnDevice()) {
					this.unbindMatchHeight();
				} else {
					this.getTargetElements().forEach(function($el) {
						if ($el.length) {
							$el.matchHeight({
								byRow: false
							});

							_this.CACHED_ELEMENTS.push($el);
						}
					});
				}
			},
		});

		elementorFrontend.hooks.addAction( 'frontend/element_ready/section', function( $scope ) {
			elementorFrontend.elementsHandler.addHandler( EqualHeightHandler, { $element: $scope });
		});

		elementorFrontend.hooks.addAction( 'frontend/element_ready/container', function( $scope ) {
			elementorFrontend.elementsHandler.addHandler( EqualHeightHandler, { $element: $scope });
		});
	});

}( jQuery ));
