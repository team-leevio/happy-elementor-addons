;(function($, elementor) {
	var TemplateLibraryLogoView = Marionette.ItemView.extend({
		getTemplate: function() {
			return '#tmpl-haTemplateLibrary__header-logo';
		},

		className: function() {
			return 'haTemplateLibrary__header-logo';
		},

		events: function() {
			return {
				click: 'onClick',
			};
		},

		templateHelpers: function() {
			return {
				title: this.getOption('title'),
			};
		},

		onClick: function() {
			const clickCallback = this.getOption('click');
			clickCallback && clickCallback();
		}
	});

	var TemplateLibraryBackView = Marionette.ItemView.extend( {
		id: 'elementor-template-library-header-preview-back',

		getTemplate: function() {
			return '#tmpl-haTemplateLibrary__header-back';
		},

		className: function() {
			return 'haTemplateLibrary__header-back';
		},

		events: function() {
			return {
				click: 'onClick',
			};
		},

		onClick: function() {
			// $e.routes.restoreState( 'library' );
			tlm.getModal().showPages();
		},
	} );

	var TemplateLibraryMenuView = Marionette.ItemView.extend( {
		id: 'elementor-template-library-header-menu',

		getTemplate: function() {
			return '#tmpl-haTemplateLibrary__header-menu';
		},

		className: function() {
			return 'haTemplateLibrary__header-menu';
		},

		templateHelpers: function() {
			return {
				tabs: {
					blocks: {
						'title': 'Blocks'
					},
					pages: {
						'title': 'Pages'
					}
				},
			};
		},
	} );

	var TemplateLibraryIframeView = Marionette.ItemView.extend({
		getTemplate: function() {
			return '#tmpl-haTemplateLibrary__iframe';
		},

		className: function() {
			return 'haTemplateLibrary__iframe';
		},

		ui: function() {
			return {
				iframe: '> iframe'
			}
		},

		onRender: function() {
			// this.$el.css('height', '100%');
			this.ui.iframe.attr('src', this.getOption('url'));

			// const onSaveCallback = this.getOption('onSave');

			// onSaveCallback && this.ui.iframe.on('load', (event) => {
			// 	const editorWindow = event.target.contentWindow;
			// 	editorWindow.$e && editorWindow.$e.components.get('document/save').on('save', onSaveCallback);
			// });
		}
	});

	var TemplateLibraryCollectionView = Marionette.ItemView.extend({
		getTemplate: function() {
			return '#tmpl-haTemplateLibrary__collection';
		},

		className: function() {
			return 'haTemplateLibrary__collection';
		},

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
			tlm.getModal().showPreview({url:'https://obiPlabon.im'});
		},

		onGoBack: function(event) {
			event.preventDefault();
			console.log('go back')
		}
	});

	var TemplateLibraryModal = elementorModules.common.views.modal.Layout.extend({
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

		getLogoOptions: function() {
			return {
				title: 'Templates',
			};
		},

		showLogo: function(args) {
			this.getHeaderView().logoArea.show(new TemplateLibraryLogoView(args));
		},

		showPreview: function(args) {
			this.modalContent.show( new TemplateLibraryIframeView( {
				url: 'https://obiplabon.im',
			} ) );

			var headerView = this.getHeaderView();
			headerView.menuArea.reset();

			// headerView.tools.show( new TemplateLibraryHeaderPreviewView( {
			// 	model: templateModel,
			// } ) );

			headerView.logoArea.show( new TemplateLibraryBackView() );
		},

		showPages: function(args) {
			this.showLogo({title: 'TEMPLATES'});

			var headerView = this.getHeaderView();

			headerView.menuArea.show( new TemplateLibraryMenuView() );

			this.modalContent.show(new TemplateLibraryCollectionView(args));
		},

		showBlocks: function(args) {
			this.modalContent.show(new TemplateLibraryCollectionView(args));
		}
	});

	var TemplateLibraryManager = function() {
		var self = this,
			libraryModal,
			$openLibraryButton = '<div class="elementor-add-section-area-button ha-add-template-button"><i class="hm hm-happyaddons"></i></div>';

		this.atIndex = -1;

		// layout: !1,
		// collections: {},
		// tabs: {},
		// defaultTab: "",
		// channels: {},
		// atIndex: null,

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
				.find('.elementor-add-new-section')
				.append($openLibraryButton);
		}

		function addLibraryModalOpenButton( $previewContents ) {
			var $addNewSection = $previewContents.find('.elementor-add-new-section');

			$addNewSection.length && $addNewSection.append($openLibraryButton);

			$previewContents.on(
				'click',
				'.elementor-editor-section-settings .elementor-editor-element-add',
				onAddElementButtonClick
			);
		}

		function onPreviewLoaded() {
			var $previewContents = window.elementor.$previewContents,
				time = setInterval( function() {
					addLibraryModalOpenButton( $previewContents );
					$previewContents.find('.elementor-add-new-section').length > 0 && clearInterval(time);
				}, 100 );

				$previewContents.on(
					'click.onHAOpenTemplateLibrary',
					'.ha-add-template-button',
					self.showModal.bind(self)
				);

			self.channels = {
				templates: Backbone.Radio.channel('HAPPY_ADDONS:templates'),
				tabs: Backbone.Radio.channel('HAPPY_ADDONS:tabs'),
				layout: Backbone.Radio.channel('HAPPY_ADDONS:layout')
			};

			// this.tabs = []; //a.tabs;
			// this.defaultTab = a.defaultTab;
		}

		this.showModal = function() {
			this.getModal().showModal();
			this.getModal().showPages();

			// if ( ! this.layout ) {
			// 	this.layout = new TemplateLibrary.LibraryLayoutView;
			// }

			// this.layout.showLoadingView()
			// this.setTab(this.defaultTab, !0)
			// // this.requestTemplates(this.defaultTab);
			// this.setPreview('initial');
		},

		this.closeModal = function() {
			this.getModal().hideModal();
		};

		this.getModal = function() {
			if ( ! libraryModal ) {
				libraryModal = new TemplateLibraryModal();
			}
			return libraryModal;
		};

		function onInit() {
			elementor.on('preview:loaded', onPreviewLoaded.bind(this));
		};

		this.init = onInit;

		this.getFilter = function( name ) {
			return this.channels.templates.request( 'filter:' + name );
		};

		this.setFilter = function(name, callback) {
			this.channels.templates.reply( 'filter:' + name, callback );
			this.channels.templates.trigger( 'filter:change' );
		};

		this.getTab = function() {
			return this.channels.tabs.request( 'filter:tabs' );
		};

		this.setTab = function( name, callback ) {
			this.channels.tabs.reply( 'filter:tabs', name, callback );
			this.channels.tabs.trigger( 'filter:change' );
		},

		this.getTabs = function() {
			var tabs = [];
			_.each( this.tabs, function(t, i) {
				tabs.push({
					slug: i,
					title: t.title
				})
			});

			return tabs;
		},

		this.getPreview = function(e) {
			return this.channels.layout.request('preview');
		};

		this.setPreview = function(e, t) {
			this.channels.layout.reply('preview', e), t || this.channels.layout.trigger('preview:change')
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

	var tlm = new TemplateLibraryManager();
	tlm.init();

	window.tlm = tlm;

}(jQuery, window.elementor));
