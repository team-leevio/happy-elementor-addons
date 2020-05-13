;(function($, elementor) {
	var Library = {
		View: {},
		Model: {},
		Collection: {},
		Layout: null,
		Manager: null,
	};

	Library.View.Loading = Marionette.ItemView.extend({
		template: '#tmpl-haTemplateLibrary__loading',
		id: 'haTemplateLibrary__loading',
	});

	Library.View.Logo = Marionette.ItemView.extend({
		template: '#tmpl-haTemplateLibrary__header-logo',
		className: 'haTemplateLibrary__header-logo',

		templateHelpers: function() {
			return {
				title: this.getOption('title'),
			};
		},
	});

	Library.View.BackButton = Marionette.ItemView.extend( {
		template: '#tmpl-haTemplateLibrary__header-back',
		id: 'elementor-template-library-header-preview-back',
		className:'haTemplateLibrary__header-back',

		events: function() {
			return {
				click: 'onClick',
			};
		},

		onClick: function() {
			libraryManager.getModal().showBlocks();
		},
	} );

	Library.View.Menu = Marionette.ItemView.extend( {
		template: '#tmpl-haTemplateLibrary__header-menu',
		id: 'elementor-template-library-header-menu',
		className: 'haTemplateLibrary__header-menu',

		templateHelpers: function() {
			return libraryManager.getTabs();
		},
	} );

	Library.View.ResponsiveMenu = Marionette.ItemView.extend( {
		template: '#tmpl-haTemplateLibrary__header-menu-responsive',
		id: 'elementor-template-library-header-menu-responsive',
		className: 'haTemplateLibrary__header-menu-responsive',

		ui: {
			items: '> .elementor-component-tab'
		},

		events: {
			'click @ui.items': 'onTabItemClick'
		},

		onTabItemClick: function(event) {
			var $target = $(event.currentTarget),
				device = $target.data('tab');

			libraryManager.channels.tabs.trigger('change:device', device, $target);
		}
	} );

	Library.View.Actions = Marionette.ItemView.extend( {
		template: '#tmpl-haTemplateLibrary__header-actions',
		id: 'elementor-template-library-header-actions',

		ui: {
			sync: '#haTemplateLibrary__header-sync i',
		},

		events: {
			'click @ui.sync': 'onSyncClick',
		},

		onSyncClick: function() {
			var self = this;

			self.ui.sync.addClass( 'eicon-animation-spin' );

			elementor.templates.requestLibraryData( {
				onUpdate: function() {
					self.ui.sync.removeClass( 'eicon-animation-spin' );

					$e.routes.refreshContainer( 'library' );
				},
				forceUpdate: true,
				forceSync: true,
			} );
		},
	} );

	// var TemplateLibraryInsertTemplateBehavior = require( 'elementor-templates/behaviors/insert-template' );

	Library.View.InsertWrapper = Marionette.ItemView.extend( {
		template: '#tmpl-haTemplateLibrary__header-insert',

		id: 'elementor-template-library-header-preview',

		// behaviors: {
		// 	insertTemplate: {
		// 		behaviorClass: TemplateLibraryInsertTemplateBehavior,
		// 	},
		// },
	} );

	Library.View.Preview = Marionette.ItemView.extend({
		template: '#tmpl-haTemplateLibrary__preview',
		className: 'haTemplateLibrary__preview',

		ui: function() {
			return {
				iframe: '> iframe'
			}
		},

		onRender: function() {
			this.ui.iframe.attr('src', this.getOption('url')).hide();
			var self = this,
				loadingScreen = (new Library.View.Loading()).render();

			this.$el.append(loadingScreen.el);

			this.ui.iframe.on('load', function() {
				self.$el.find('#haTemplateLibrary__loading').remove();
				self.ui.iframe.show();
			});
		}
	});

	Library.View.Templates = Marionette.ItemView.extend({
		template: '#tmpl-haTemplateLibrary__collection',
		className: 'haTemplateLibrary__collection',

		ui: function() {
			return {
				showBack: '.btn-back',
				goBack: '.btn-go',
			}
		},

		events: function() {
			return {
				'click @ui.showBack': 'onShowBack',
				'click @ui.goBack': 'onGoBack',
			};
		},

		onShowBack: function(event) {
			event.preventDefault();
			console.log('show back')
			libraryManager.getModal().showPreview({url:'https://obiPlabon.im'});
		},

		onGoBack: function(event) {
			event.preventDefault();
			console.log('go back')
		}
	});

	Library.Modal = elementorModules.common.views.modal.Layout.extend({
		getModalOptions: function() {
			return {
				id: 'haTemplateLibrary__modal',
				hide: {
					onOutsideClick: false,
					onEscKeyPress: true,
					onBackgroundClick: false,
				}
			};
		},

		getTemplateActionButton: function getTemplateActionButton( templateData ) {
			var viewId = '#tmpl-haTemplateLibrary__' + ( templateData.isPro ? 'pro-button' : 'insert-button' ),
				template = Marionette.TemplateCache.get(viewId);

			return Marionette.Renderer.render(template);
		},

		showLogo: function(args) {
			this.getHeaderView().logoArea.show( new Library.View.Logo(args) );
		},

		showDefaultHeader: function() {
			this.showLogo({
				title: 'HAPPY LIBRARY'
			});

			var headerView = this.getHeaderView();
			headerView.tools.show( new Library.View.Actions() );
			headerView.menuArea.show( new Library.View.Menu() );
		},

		showPreview: function(args) {
			var headerView = this.getHeaderView();

			headerView.menuArea.show( new Library.View.ResponsiveMenu() );
			headerView.logoArea.show( new Library.View.BackButton() );
			headerView.tools.show( new Library.View.InsertWrapper({isPro: true}) );

			this.modalContent.show( new Library.View.Preview( {
				url: 'https://obiplabon.im',
			} ) );
		},

		showBlocks: function(args) {
			this.showDefaultHeader();
			this.modalContent.show(new Library.View.Templates(args));
		}
	});

	Library.Manager = function() {
		var self = this,
			modal,
			FIND_SELECTOR = '.elementor-add-new-section .elementor-add-template-button',
			$openLibraryButton = '<div class="elementor-add-section-area-button ha-add-template-button"><i class="hm hm-happyaddons"></i></div>',
			devicesResponsiveMap = {
				desktop: {
					width: '100%',
					height: '100%'
				},
				tab: {
					width: '768px',
					height: '1025px'
				},
				mobile: {
					width: '360px',
    				height: '640px'
				}
			};

		this.atIndex = -1;

		this.channels = {
			tabs: Backbone.Radio.channel( 'tabs' )
		};

		function onAddElementButtonClick() {
			var $topSection = $(this).closest('.elementor-top-section'),
				modelId = $topSection.data('model-cid'),
				sections = window.elementor.sections;

			if (sections.currentView.collection.length) {
				_.each(sections.currentView.collection.models, function(model, index) {
					if (modelId === model.cid) {
						self.atIndex = index
					}
				});
			}

			$topSection
				.prev('.elementor-add-section')
				.find( FIND_SELECTOR )
				.after($openLibraryButton);
		}

		function addLibraryModalOpenButton( $previewContents ) {
			var $addNewSection = $previewContents.find( FIND_SELECTOR );

			$addNewSection.length && $addNewSection.after( $openLibraryButton );

			$previewContents.on(
				'click.onAddElement',
				'.elementor-editor-section-settings .elementor-editor-element-add',
				onAddElementButtonClick
			);
		}

		function onDeviceChange( device, $target ) {
			$target
				.addClass('elementor-active')
				.siblings()
				.removeClass('elementor-active');

			var sizes = devicesResponsiveMap[device] || devicesResponsiveMap['desktop'];
			$('.haTemplateLibrary__preview > iframe').css(sizes);
		}

		function onPreviewLoaded() {
			var $previewContents = window.elementor.$previewContents,
				time = setInterval( function() {
					addLibraryModalOpenButton( $previewContents );
					$previewContents.find('.elementor-add-new-section').length > 0 && clearInterval(time);
				}, 100 );

			$previewContents.on(
				'click.onAddTemplateButton',
				'.ha-add-template-button',
				self.showModal.bind(self)
			);

			this.channels.tabs.on('change:device', onDeviceChange);
		}

		this.showModal = function() {
			this.getModal().showModal();
			this.getModal().showBlocks();


			// this.layout.showLoadingView()
			// this.setTab(this.defaultTab, !0)
			// // this.requestTemplates(this.defaultTab);
			// this.setPreview('initial');
		};

		this.closeModal = function() {
			this.getModal().hideModal();
		};

		this.getModal = function() {
			if ( ! modal ) {
				modal = new Library.Modal();
			}

			return modal;
		};

		this.init = function() {
			elementor.on( 'preview:loaded', onPreviewLoaded.bind( this ) );
		}

		this.getTabs = function() {
			return {
				tabs: {
					blocks: {
						title: 'Blocks',
						active: true
					},
				},
			};
		};

		// getKeywords: function() {
		//     return _.each(this.keywords, function(e, t) {
		//         tabs.push({
		//             slug: t,
		//             title: e
		//         })
		//     }), []
		// },

		// requestTemplates: function(t) {
		//     var n = this,
		//         a = n.tabs[t];
		//     n.setFilter("category", !1), a.data.templates && a.data.categories ? n.layout.showTemplatesView(a.data.templates, a.data.categories, a.data.keywords) : e.ajax({
		//         url: ajaxurl,
		//         type: "get",
		//         dataType: "json",
		//         data: {
		//             action: "elementskit_get_layouts",
		//             tab: t
		//         },
		//         success: function(e) {
		//             var a = new i.LibraryCollection(e.data.templates),
		//                 l = new i.CategoriesCollection(e.data.categories);
		//             n.tabs[t].data = {
		//                 templates: a,
		//                 categories: l,
		//                 keywords: e.data.keywords
		//             }, n.layout.showTemplatesView(a, l, e.data.keywords)
		//         }
		//     })
		// },
	};

	var libraryManager = new Library.Manager();

	window.haLibary = libraryManager;
	window.haLibary.init();

}(jQuery, window.elementor));
