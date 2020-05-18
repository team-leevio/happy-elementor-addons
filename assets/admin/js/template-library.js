;(function($, elementor) {
	window.ha = window.ha || {};

	var Library = {
		View: {},
		Model: {},
		Collection: {},
		Behavior: {},
		Layout: null,
		Manager: null,
	};

	Library.Model.Template = Backbone.Model.extend( {
		defaults: {
			template_id: 0,
			title: '',
			type: '',
			thumbnail: '',
			url: '',
			tags: [],
			isPro: false
		},
	} );

	Library.Collection.Template = Backbone.Collection.extend( {
		model: Library.Model.Template
	} );

	Library.Behavior.InsertTemplate = Marionette.Behavior.extend( {
		ui: {
			insertButton: '.elementor-template-library-template-insert',
		},

		events: {
			'click @ui.insertButton': 'onInsertButtonClick',
		},

		onInsertButtonClick: function() {
			console.log(this);
			// const args = {
			// 	model: this.view.model,
			// };

			// $e.run( 'library/insert-template', args );
		},
	} );

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
			ha.library.showBlocksView();
		},
	} );

	Library.View.Menu = Marionette.ItemView.extend( {
		template: '#tmpl-haTemplateLibrary__header-menu',
		id: 'elementor-template-library-header-menu',
		className: 'haTemplateLibrary__header-menu',

		templateHelpers: function() {
			return ha.library.getTabs();
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

			ha.library.channels.tabs.trigger('change:device', device, $target);
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

			ha.library.requestLibraryData( {
				onUpdate: function() {
					self.ui.sync.removeClass( 'eicon-animation-spin' );
					ha.library.updateBlocksView();
				},
				forceUpdate: true,
				forceSync: true,
			} );
		},
	} );

	Library.View.InsertWrapper = Marionette.ItemView.extend( {
		template: '#tmpl-haTemplateLibrary__header-insert',

		id: 'elementor-template-library-header-preview',

		behaviors: {
			insertTemplate: {
				behaviorClass: Library.Behavior.InsertTemplate,
			},
		},
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

	Library.View.TemplateCollection = Marionette.CompositeView.extend( {
		template: '#tmpl-haTemplateLibrary__templates',

		id: 'haTemplateLibrary__templates',

		childViewContainer: '#haTemplateLibrary__templates-list',

		emptyView: function() {
			// var EmptyView = require( 'elementor-templates/views/parts/templates-empty' );

			// return new EmptyView();
		},

		ui: {
			textFilter: '#haTemplateLibrary__search',
			tagsFilter: '#haTemplateLibrary__filter-tags > li',
		},

		events: {
			'input @ui.textFilter': 'onTextFilterInput',
			'click @ui.tagsFilter': 'onTagsFilterClick',
		},

		getChildView: function( childModel ) {
			return Library.View.Template;
		},

		initialize: function() {
			this.listenTo( ha.library.channels.templates, 'filter:change', this._renderChildren );
		},

		filter: function filter( childModel ) {
			var filterTerms = ha.library.getFilterTerms(),
				passingFilter = true;

			_.each( filterTerms, function( filterTerm, filterTermName ) {
				var filterValue = ha.library.getFilter( filterTermName );

				if ( ! filterValue ) {
					return;
				}

				if ( filterTerm.callback ) {
					var callbackResult = filterTerm.callback.call( childModel, filterValue );

					if ( ! callbackResult ) {
						passingFilter = false;
					}

					return callbackResult;
				}
			});

			return passingFilter;
		},

		setMasonrySkin: function() {
			var masonry = new elementorModules.utils.Masonry( {
				container: this.$childViewContainer,
				items: this.$childViewContainer.children(),
			} );

			this.$childViewContainer.imagesLoaded( masonry.run.bind( masonry ) );
		},

		onRenderCollection: function() {
			this.setMasonrySkin();
		},

		onTextFilterInput: function() {
			ha.library.setFilter( 'text', this.ui.textFilter.val() );
		},

		onTagsFilterClick: function( event ) {
			var $select = $( event.currentTarget );
			ha.library.setFilter( 'tags', $select.data('tag') );
		},
	} );

	Library.View.Template = Marionette.ItemView.extend( {
		template: '#tmpl-haTemplateLibrary__template',

		className: 'haTemplateLibrary__template',

		ui: {
			previewButton: '.haTemplateLibrary__template-preview',
		},

		events: {
			'click @ui.previewButton': 'onPreviewButtonClick',
		},

		behaviors: {
			insertTemplate: {
				behaviorClass: Library.Behavior.InsertTemplate,
			},
		},

		onPreviewButtonClick: function() {
			ha.library.showPreviewView( this.model )
		},
	} );

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
			// headerView.menuArea.show( new Library.View.Menu() );
			headerView.menuArea.reset();
		},

		showPreviewView: function( templateModel ) {
			var headerView = this.getHeaderView();

			headerView.menuArea.show( new Library.View.ResponsiveMenu() );
			headerView.logoArea.show( new Library.View.BackButton() );
			headerView.tools.show( new Library.View.InsertWrapper( { model: templateModel } ) );

			this.modalContent.show( new Library.View.Preview( {
				url: templateModel.get( 'url' )
			} ) );
		},

		showBlocksView: function( blocksCollection ) {
			this.modalContent.show( new Library.View.TemplateCollection( {
				collection: blocksCollection,
			} ) );
		}
	});

	Library.Manager = function() {
		var modal,
			tags,
			self = this,
			templatesCollection,
			FIND_SELECTOR = '.elementor-add-new-section .elementor-add-section-drag-title',
			$openLibraryButton = '<div class="elementor-add-section-area-button ha-add-template-button"><i class="hm hm-happyaddons"></i></div>',
			devicesResponsiveMap = {
				desktop: '100%',
				tab: '768px',
				mobile: '360px',
			};

		this.atIndex = -1;

		this.channels = {
			tabs: Backbone.Radio.channel( 'tabs' ),
			templates: Backbone.Radio.channel( 'templates' ),
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
				.before($openLibraryButton);
		}

		function addLibraryModalOpenButton( $previewContents ) {
			var $addNewSection = $previewContents.find( FIND_SELECTOR );

			$addNewSection.length && $addNewSection.before( $openLibraryButton );

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

			var width = devicesResponsiveMap[device] || devicesResponsiveMap['desktop'];
			$('.haTemplateLibrary__preview').css('width', width);
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

		this.updateBlocksView = function() {
			self.getModal().showBlocksView( templatesCollection );
		}

		this.setFilter = function( name, value, silent ) {
			self.channels.templates.reply( 'filter:' + name, value );

			if ( ! silent ) {
				self.channels.templates.trigger( 'filter:change' );
			}
		}

		this.getFilter = function( name ) {
			return self.channels.templates.request( 'filter:' + name );
		};

		this.getFilterTerms = function() {
			return {
				tags: {
					callback: function callback( value ) {
						return _.any(this.get('tags'), function (tag) {
							return tag.indexOf(value) >= 0;
						});
					}
				},
				text: {
					callback: function callback( value ) {
						value = value.toLowerCase();

						if ( this.get('title').toLowerCase().indexOf( value ) >= 0 ) {
							return true;
						}

						return _.any(this.get('tags'), function (tag) {
							return tag.indexOf(value) >= 0;
						});
					}
				},
			};
		}

		this.showModal = function() {
			self.getModal().showModal();
			self.showBlocksView();
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

		this.getTags = function() {
			return tags;
		}

		this.showBlocksView = function() {
			self.getModal().showDefaultHeader();
			self.loadTemplates( function() {
				self.getModal().showBlocksView( templatesCollection );
			} );
		};

		this.showPreviewView = function( templateModel ) {
			self.getModal().showPreviewView( templateModel );
		};

		this.loadTemplates = function( onUpdate ) {
			self.requestLibraryData( {
				onBeforeUpdate: self.getModal().showLoadingView.bind( self.getModal() ),
				onUpdate: function() {
					self.getModal().hideLoadingView();

					if ( onUpdate ) {
						onUpdate();
					}
				},
			} );
		};

		this.requestLibraryData = function( options ) {
			if ( templatesCollection && ! options.forceUpdate ) {
				if ( options.onUpdate ) {
					options.onUpdate();
				}

				return;
			}

			if ( options.onBeforeUpdate ) {
				options.onBeforeUpdate();
			}

			var ajaxOptions = {
				data: {},
				success: function( data ) {
					templatesCollection = new Library.Collection.Template( data.templates );

					if ( data.tags ) {
						tags = data.tags;
					}

					if ( options.onUpdate ) {
						options.onUpdate();
					}
				},
			};

			if ( options.forceSync ) {
				ajaxOptions.data.sync = true;
			}

			elementorCommon.ajax.addRequest( 'get_happy_library_data', ajaxOptions );
		};
	};

	window.ha.library = new Library.Manager();
	window.ha.library.init();
}(jQuery, window.elementor));
