"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
;
(function ($, elementor, ha) {
  var Library = {
    Views: {},
    Models: {},
    Collections: {},
    Behaviors: {},
    Layout: null,
    Manager: null
  };
  Library.Models.Template = Backbone.Model.extend({
    defaults: {
      template_id: 0,
      title: '',
      type: '',
      thumbnail: '',
      url: '',
      tags: [],
      isPro: false
    }
  });
  Library.Collections.Template = Backbone.Collection.extend({
    model: Library.Models.Template
  });
  Library.Behaviors.InsertTemplate = Marionette.Behavior.extend({
    ui: {
      insertButton: '.haTemplateLibrary__insert-button'
    },
    events: {
      'click @ui.insertButton': 'onInsertButtonClick'
    },
    onInsertButtonClick: function onInsertButtonClick() {
      ha.library.insertTemplate({
        model: this.view.model
      });
    }
  });
  Library.Views.EmptyTemplateCollection = Marionette.ItemView.extend({
    id: 'elementor-template-library-templates-empty',
    template: '#tmpl-haTemplateLibrary__empty',
    ui: {
      title: '.elementor-template-library-blank-title',
      message: '.elementor-template-library-blank-message'
    },
    modesStrings: {
      empty: {
        title: haGetTranslated('templatesEmptyTitle'),
        message: haGetTranslated('templatesEmptyMessage')
      },
      noResults: {
        title: haGetTranslated('templatesNoResultsTitle'),
        message: haGetTranslated('templatesNoResultsMessage')
      }
    },
    getCurrentMode: function getCurrentMode() {
      if (ha.library.getFilter('text')) {
        return 'noResults';
      }
      return 'empty';
    },
    onRender: function onRender() {
      var modeStrings = this.modesStrings[this.getCurrentMode()];
      this.ui.title.html(modeStrings.title);
      this.ui.message.html(modeStrings.message);
    }
  });
  Library.Views.Loading = Marionette.ItemView.extend({
    template: '#tmpl-haTemplateLibrary__loading',
    id: 'haTemplateLibrary__loading'
  });
  Library.Views.Logo = Marionette.ItemView.extend({
    template: '#tmpl-haTemplateLibrary__header-logo',
    className: 'haTemplateLibrary__header-logo',
    templateHelpers: function templateHelpers() {
      return {
        title: this.getOption('title')
      };
    }
  });
  Library.Views.BackButton = Marionette.ItemView.extend({
    template: '#tmpl-haTemplateLibrary__header-back',
    id: 'elementor-template-library-header-preview-back',
    className: 'haTemplateLibrary__header-back',
    events: function events() {
      return {
        click: 'onClick'
      };
    },
    onClick: function onClick() {
      ha.library.showTemplatesView();
    }
  });
  Library.Views.Menu = Marionette.ItemView.extend({
    template: '#tmpl-haTemplateLibrary__header-menu',
    id: 'elementor-template-library-header-menu',
    className: 'haTemplateLibrary__header-menu',
    templateHelpers: function templateHelpers() {
      return ha.library.getTabs();
    },
    ui: {
      menuItem: '.elementor-template-library-menu-item'
    },
    events: {
      'click @ui.menuItem': 'onMenuItemClick'
    },
    onMenuItemClick: function onMenuItemClick(event) {
      ha.library.setFilter('tags', '');
      ha.library.setFilter('text', '');
      ha.library.setFilter('type', event.currentTarget.dataset.tab, true);
      ha.library.showTemplatesView();
    }
  });
  Library.Views.ResponsiveMenu = Marionette.ItemView.extend({
    template: '#tmpl-haTemplateLibrary__header-menu-responsive',
    id: 'elementor-template-library-header-menu-responsive',
    className: 'haTemplateLibrary__header-menu-responsive',
    ui: {
      items: '> .elementor-component-tab'
    },
    events: {
      'click @ui.items': 'onTabItemClick'
    },
    onTabItemClick: function onTabItemClick(event) {
      var $target = $(event.currentTarget),
        device = $target.data('tab');
      ha.library.channels.tabs.trigger('change:device', device, $target);
    }
  });
  Library.Views.Actions = Marionette.ItemView.extend({
    template: '#tmpl-haTemplateLibrary__header-actions',
    id: 'elementor-template-library-header-actions',
    ui: {
      sync: '#haTemplateLibrary__header-sync i'
    },
    events: {
      'click @ui.sync': 'onSyncClick'
    },
    onSyncClick: function onSyncClick() {
      var self = this;
      self.ui.sync.addClass('eicon-animation-spin');
      ha.library.requestLibraryData({
        onUpdate: function onUpdate() {
          self.ui.sync.removeClass('eicon-animation-spin');
          ha.library.updateBlocksView();
        },
        forceUpdate: true,
        forceSync: true
      });
    }
  });
  Library.Views.InsertWrapper = Marionette.ItemView.extend({
    template: '#tmpl-haTemplateLibrary__header-insert',
    id: 'elementor-template-library-header-preview',
    behaviors: {
      insertTemplate: {
        behaviorClass: Library.Behaviors.InsertTemplate
      }
    }
  });
  Library.Views.Preview = Marionette.ItemView.extend({
    template: '#tmpl-haTemplateLibrary__preview',
    className: 'haTemplateLibrary__preview',
    ui: function ui() {
      return {
        iframe: '> iframe'
      };
    },
    onRender: function onRender() {
      this.ui.iframe.attr('src', this.getOption('url')).hide();
      var self = this,
        loadingScreen = new Library.Views.Loading().render();
      this.$el.append(loadingScreen.el);
      this.ui.iframe.on('load', function () {
        self.$el.find('#haTemplateLibrary__loading').remove();
        self.ui.iframe.show();
      });
    }
  });
  Library.Views.TemplateCollection = Marionette.CompositeView.extend({
    template: '#tmpl-haTemplateLibrary__templates',
    id: 'haTemplateLibrary__templates',
    className: function className() {
      return 'haTemplateLibrary__templates haTemplateLibrary__templates--' + ha.library.getFilter('type');
    },
    childViewContainer: '#haTemplateLibrary__templates-list',
    emptyView: function emptyView() {
      return new Library.Views.EmptyTemplateCollection();
    },
    ui: {
      templatesWindow: '.haTemplateLibrary__templates-window',
      textFilter: '#haTemplateLibrary__search',
      tagsFilter: '#haTemplateLibrary__filter-tags',
      filterBar: '#haTemplateLibrary__toolbar-filter',
      counter: '#haTemplateLibrary__toolbar-counter'
    },
    events: {
      'input @ui.textFilter': 'onTextFilterInput',
      'click @ui.tagsFilter li': 'onTagsFilterClick'
    },
    getChildView: function getChildView(childModel) {
      return Library.Views.Template;
    },
    initialize: function initialize() {
      this.listenTo(ha.library.channels.templates, 'filter:change', this._renderChildren);
    },
    filter: function filter(childModel) {
      var filterTerms = ha.library.getFilterTerms(),
        passingFilter = true;
      _.each(filterTerms, function (filterTerm, filterTermName) {
        var filterValue = ha.library.getFilter(filterTermName);
        if (!filterValue) {
          return;
        }
        if (filterTerm.callback) {
          var callbackResult = filterTerm.callback.call(childModel, filterValue);
          if (!callbackResult) {
            passingFilter = false;
          }
          return callbackResult;
        }
      });
      return passingFilter;
    },
    setMasonrySkin: function setMasonrySkin() {
      if (ha.library.getFilter('type') === 'section') {
        var masonry = new elementorModules.utils.Masonry({
          container: this.$childViewContainer,
          items: this.$childViewContainer.children()
        });
        this.$childViewContainer.imagesLoaded(masonry.run.bind(masonry));
      }
    },
    onRenderCollection: function onRenderCollection() {
      this.setMasonrySkin();
      this.updatePerfectScrollbar();
      this.setTemplatesFoundText();
    },
    setTemplatesFoundText: function setTemplatesFoundText() {
      var type = ha.library.getFilter('type'),
        len = this.children.length,
        text = '<b>' + len + '</b>';
      text += type === 'section' ? ' block' : ' ' + type;
      if (len > 1) {
        text += 's';
      }
      text += ' found';
      this.ui.counter.html(text);
    },
    onTextFilterInput: function onTextFilterInput() {
      var self = this;
      _.defer(function () {
        ha.library.setFilter('text', self.ui.textFilter.val());
      });
    },
    onTagsFilterClick: function onTagsFilterClick(event) {
      var $select = $(event.currentTarget),
        tag = $select.data('tag');
      ha.library.setFilter('tags', tag);
      $select.addClass('active').siblings().removeClass('active');
      if (!tag) {
        tag = 'Filter';
      } else {
        tag = ha.library.getTags()[tag];
      }
      this.ui.filterBar.find('.haTemplateLibrary__filter-btn').html(tag + ' <i class="eicon-caret-down"></i>');
    },
    updatePerfectScrollbar: function updatePerfectScrollbar() {
      if (!this.perfectScrollbar) {
        this.perfectScrollbar = new PerfectScrollbar(this.ui.templatesWindow[0], {
          suppressScrollX: true
        }); // The RTL is buggy, so always keep it LTR.
      }

      this.perfectScrollbar.isRtl = false;
      this.perfectScrollbar.update();
    },
    setTagsFilterHover: function setTagsFilterHover() {
      var self = this;
      self.ui.filterBar.hoverIntent(function () {
        self.ui.tagsFilter.css('display', 'block');
        self.ui.filterBar.find('.haTemplateLibrary__filter-btn i').addClass('eicon-caret-down').removeClass('eicon-caret-right');
      }, function () {
        self.ui.tagsFilter.css('display', 'none');
        self.ui.filterBar.find('.haTemplateLibrary__filter-btn i').addClass('eicon-caret-right').removeClass('eicon-caret-down');
      }, {
        sensitivity: 50,
        interval: 150,
        timeout: 100
      });
    },
    onRender: function onRender() {
      this.setTagsFilterHover();
      this.updatePerfectScrollbar();
    }
  });
  Library.Views.Template = Marionette.ItemView.extend({
    template: '#tmpl-haTemplateLibrary__template',
    className: 'haTemplateLibrary__template',
    ui: {
      previewButton: '.haTemplateLibrary__preview-button, .haTemplateLibrary__template-preview'
    },
    events: {
      'click @ui.previewButton': 'onPreviewButtonClick'
    },
    behaviors: {
      insertTemplate: {
        behaviorClass: Library.Behaviors.InsertTemplate
      }
    },
    onPreviewButtonClick: function onPreviewButtonClick() {
      ha.library.showPreviewView(this.model);
    }
  });
  Library.Modal = elementorModules.common.views.modal.Layout.extend({
    getModalOptions: function getModalOptions() {
      return {
        id: 'haTemplateLibrary__modal',
        hide: {
          onOutsideClick: false,
          onEscKeyPress: true,
          onBackgroundClick: false
        }
      };
    },
    getTemplateActionButton: function getTemplateActionButton(templateData) {
      var templateName = templateData.isPro && !HappyAddonsEditor.hasPro ? 'pro-button' : 'insert-button',
        viewId = '#tmpl-haTemplateLibrary__' + templateName,
        template = Marionette.TemplateCache.get(viewId);
      return Marionette.Renderer.render(template);
    },
    showLogo: function showLogo(args) {
      this.getHeaderView().logoArea.show(new Library.Views.Logo(args));
    },
    showDefaultHeader: function showDefaultHeader() {
      this.showLogo({
        title: 'TEMPLATES'
      });
      var headerView = this.getHeaderView();
      headerView.tools.show(new Library.Views.Actions());
      headerView.menuArea.show(new Library.Views.Menu());
      // headerView.menuArea.reset();
    },

    showPreviewView: function showPreviewView(templateModel) {
      var headerView = this.getHeaderView();
      headerView.menuArea.show(new Library.Views.ResponsiveMenu());
      headerView.logoArea.show(new Library.Views.BackButton());
      headerView.tools.show(new Library.Views.InsertWrapper({
        model: templateModel
      }));
      this.modalContent.show(new Library.Views.Preview({
        url: templateModel.get('url')
      }));
    },
    showTemplatesView: function showTemplatesView(templatesCollection) {
      this.showDefaultHeader();
      this.modalContent.show(new Library.Views.TemplateCollection({
        collection: templatesCollection
      }));
    }
  });
  Library.Manager = function () {
    var modal,
      tags,
      typeTags,
      self = this,
      templatesCollection,
      errorDialog,
      FIND_SELECTOR = '.elementor-add-new-section .elementor-add-section-drag-title',
      $openLibraryButton = '<div class="elementor-add-section-area-button elementor-add-ha-button"> <i class="hm hm-happyaddons"></i> </div>',
      devicesResponsiveMap = {
        desktop: '100%',
        tab: '768px',
        mobile: '360px'
      };
    this.atIndex = -1;
    this.channels = {
      tabs: Backbone.Radio.channel('tabs'),
      templates: Backbone.Radio.channel('templates')
    };
    function onAddElementButtonClick() {
      var $topSection = $(this).closest('.elementor-top-section'),
        sectionId = $topSection.data('id'),
        documentSections = elementor.documents.getCurrent().container.children,
        $addSection = $topSection.prev('.elementor-add-section');
      if (documentSections) {
        _.each(documentSections, function (sectionContainer, index) {
          if (sectionId === sectionContainer.id) {
            self.atIndex = index;
          }
        });
      }
      if (!$addSection.find('.elementor-add-ha-button').length) {
        $addSection.find(FIND_SELECTOR).before($openLibraryButton);
      }
    }
    function addLibraryModalOpenButton($previewContents) {
      var $addNewSection = $previewContents.find(FIND_SELECTOR);
      if ($addNewSection.length && !$previewContents.find('.elementor-add-ha-button').length) {
        $addNewSection.before($openLibraryButton);
      }
      $previewContents.on('click.onAddElement', '.elementor-editor-section-settings .elementor-editor-element-add', onAddElementButtonClick);
    }
    function onDeviceChange(device, $target) {
      $target.addClass('elementor-active').siblings().removeClass('elementor-active');
      var width = devicesResponsiveMap[device] || devicesResponsiveMap['desktop'];
      $('.haTemplateLibrary__preview').css('width', width);
    }
    function onPreviewLoaded() {
      var $previewContents = window.elementor.$previewContents,
        time = setInterval(function () {
          addLibraryModalOpenButton($previewContents);
          $previewContents.find('.elementor-add-new-section').length > 0 && clearInterval(time);
        }, 100);
      $previewContents.on('click.onAddTemplateButton', '.elementor-add-ha-button', self.showModal.bind(self));
      this.channels.tabs.on('change:device', onDeviceChange);
    }
    this.updateBlocksView = function () {
      self.setFilter('tags', '', true);
      self.setFilter('text', '', true);
      self.getModal().showTemplatesView(templatesCollection);
    };
    this.setFilter = function (name, value, silent) {
      self.channels.templates.reply('filter:' + name, value);
      if (!silent) {
        self.channels.templates.trigger('filter:change');
      }
    };
    this.getFilter = function (name) {
      return self.channels.templates.request('filter:' + name);
    };
    this.getFilterTerms = function () {
      return {
        tags: {
          callback: function callback(value) {
            return _.any(this.get('tags'), function (tag) {
              return tag.indexOf(value) >= 0;
            });
          }
        },
        text: {
          callback: function callback(value) {
            value = value.toLowerCase();
            if (this.get('title').toLowerCase().indexOf(value) >= 0) {
              return true;
            }
            return _.any(this.get('tags'), function (tag) {
              return tag.indexOf(value) >= 0;
            });
          }
        },
        type: {
          callback: function callback(value) {
            return this.get('type') === value;
          }
        }
      };
    };
    this.showModal = function () {
      self.getModal().showModal();
      self.showTemplatesView();
    };
    this.closeModal = function () {
      this.getModal().hideModal();
    };
    this.getModal = function () {
      if (!modal) {
        modal = new Library.Modal();
      }
      return modal;
    };
    this.init = function () {
      self.setFilter('type', 'section', true);
      elementor.on('preview:loaded', onPreviewLoaded.bind(this));
    };
    this.getTabs = function () {
      var type = this.getFilter('type'),
        tabs = {
          section: {
            title: 'Blocks'
          },
          page: {
            title: 'Pages'
          }
        };
      _.each(tabs, function (obj, key) {
        if (type === key) {
          tabs[type].active = true;
        }
      });
      return {
        tabs: tabs
      };
    };
    this.getTags = function () {
      return tags;
    };
    this.getTypeTags = function () {
      var type = self.getFilter('type');
      return typeTags[type];
    };
    this.showTemplatesView = function () {
      self.setFilter('tags', '', true);
      self.setFilter('text', '', true);
      if (!templatesCollection) {
        self.loadTemplates(function () {
          self.getModal().showTemplatesView(templatesCollection);
        });
      } else {
        self.getModal().showTemplatesView(templatesCollection);
      }
    };
    this.showPreviewView = function (templateModel) {
      self.getModal().showPreviewView(templateModel);
    };
    this.loadTemplates = function (_onUpdate) {
      self.requestLibraryData({
        onBeforeUpdate: self.getModal().showLoadingView.bind(self.getModal()),
        onUpdate: function onUpdate() {
          self.getModal().hideLoadingView();
          if (_onUpdate) {
            _onUpdate();
          }
        }
      });
    };
    this.requestLibraryData = function (options) {
      if (templatesCollection && !options.forceUpdate) {
        if (options.onUpdate) {
          options.onUpdate();
        }
        return;
      }
      if (options.onBeforeUpdate) {
        options.onBeforeUpdate();
      }
      var ajaxOptions = {
        data: {},
        success: function success(data) {
          templatesCollection = new Library.Collections.Template(data.templates);
          if (data.tags) {
            tags = data.tags;
          }
          if (data.type_tags) {
            typeTags = data.type_tags;
          }
          if (options.onUpdate) {
            options.onUpdate();
          }
        }
      };
      if (options.forceSync) {
        ajaxOptions.data.sync = true;
      }
      elementorCommon.ajax.addRequest('get_ha_library_data', ajaxOptions);
    };
    this.requestTemplateData = function (template_id, ajaxOptions) {
      var options = {
        unique_id: template_id,
        data: {
          edit_mode: true,
          display: true,
          template_id: template_id
        }
      };
      if (ajaxOptions) {
        jQuery.extend(true, options, ajaxOptions);
      }
      elementorCommon.ajax.addRequest('get_ha_template_data', options);
    };
    this.insertTemplate = function (args) {
      var model = args.model,
        self = this;
      self.getModal().showLoadingView();
      self.requestTemplateData(model.get('template_id'), {
        success: function success(data) {
          self.getModal().hideLoadingView();
          self.getModal().hideModal();
          var options = {};
          if (self.atIndex !== -1) {
            options.at = self.atIndex;
          }
          $e.run('document/elements/import', {
            model: model,
            data: data,
            options: options
          });
          self.atIndex = -1;
        },
        error: function error(data) {
          self.showErrorDialog(data);
        },
        complete: function complete(data) {
          self.getModal().hideLoadingView();
          window.elementor.$previewContents.find('.elementor-add-section .elementor-add-section-close').click();
        }
      });
    };
    this.showErrorDialog = function (errorMessage) {
      if ('object' === _typeof(errorMessage)) {
        var message = '';
        _.each(errorMessage, function (error) {
          message += '<div>' + error.message + '.</div>';
        });
        errorMessage = message;
      } else if (errorMessage) {
        errorMessage += '.';
      } else {
        errorMessage = '<i>&#60;The error message is empty&#62;</i>';
      }
      self.getErrorDialog().setMessage('The following error(s) occurred while processing the request:' + '<div id="elementor-template-library-error-info">' + errorMessage + '</div>').show();
    };
    this.getErrorDialog = function () {
      if (!errorDialog) {
        errorDialog = elementorCommon.dialogsManager.createWidget('alert', {
          id: 'elementor-template-library-error-dialog',
          headerMessage: 'An error occurred'
        });
      }
      return errorDialog;
    };
  };
  ha.library = new Library.Manager();
  ha.library.init();
  window.ha = ha;
})(jQuery, window.elementor, window.ha || {});