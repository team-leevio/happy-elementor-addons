(function ($) {
	$(function () {
		var $clearCache = $(".hajs-clear-cache"),
			$haMenu = $(
				"#toplevel_page_happy-addons .toplevel_page_happy-addons .wp-menu-name"
			),
			menuText = $haMenu.text();

		$haMenu.text(menuText.replace(/\s/, ""));

		$clearCache.on("click", "a", function (e) {
			e.preventDefault();

			var type = "all",
				$m = $(e.delegateTarget);

			if ($m.hasClass("ha-clear-page-cache")) {
				type = "page";
			}

			$m.addClass("ha-clear-cache--init");

			$.post(HappyAdmin.ajax_url, {
				action: "ha_clear_cache",
				type: type,
				nonce: HappyAdmin.nonce,
				post_id: HappyAdmin.post_id,
			}).done(function (res) {
				$m.removeClass("ha-clear-cache--init").addClass(
					"ha-clear-cache--done"
				);
			});
		});
	});
})(jQuery);

var Logo = Marionette.ItemView.extend({
	getTemplate() {
		return '#tmpl-ha-templates-modal__header__logo';
	},

	className() {
		return 'elementor-templates-modal__header__logo';
	},

	events() {
		return {
			click: 'onClick',
		};
	},

	templateHelpers() {
		return {
			title: this.getOption( 'title' ),
		};
	},

	onClick() {
		const clickCallback = this.getOption( 'click' );

		if ( clickCallback ) {
			clickCallback();
		}
	}
});
var NewTemplateView = Marionette.ItemView.extend({
	id: "elementor-new-template-dialog-content",

	template: "#tmpl-elementor-new-template",

	ui: {},

	events: {},

	onRender: function () {},
});

var NewTemplateLayout = elementorModules.common.views.modal.Layout.extend({
	getModalOptions: function () {
		return {
			id: "elementor-new-template-modal",
		};
	},

	getLogoOptions: function () {
		return {
			title: "New Template",
		};
	},

	initialize: function () {
		elementorModules.common.views.modal.Layout.prototype.initialize.apply(
			this,
			arguments
		);
		this.showLogo();
		this.showContentView();
	},

	showContentView: function () {
		this.modalContent.show(new NewTemplateView());
	},

    showLogo: function () {
		this.getHeaderView().logoArea.show( new Logo( this.getLogoOptions() ) );
	}
});

var NewTemplateModule = elementorModules.ViewModule.extend({
	getDefaultSettings: function () {
		return {
			selectors: {
				addButton:
					"#ha-template-library-add-new",
			},
		};
	},

	getDefaultElements: function () {
		var selectors = this.getSettings("selectors");

		return {
			$addButton: jQuery(selectors.addButton),
		};
	},

	bindEvents: function () {
		this.elements.$addButton.on("click", this.onAddButtonClick);

		elementorCommon.elements.$window.on(
			"hashchange",
			this.showModalByHash.bind(this)
		);
	},

	showModalByHash: function () {
		if ("#add_new" === location.hash) {
			this.layout.showModal();

			location.hash = "";
		}
	},

	onInit: function () {
		elementorModules.ViewModule.prototype.onInit.apply(this, arguments);

		this.layout = new NewTemplateLayout();

		this.showModalByHash();
	},

	onAddButtonClick: function (event) {
		event.preventDefault();

		this.layout.showModal();
	},
});

jQuery(function () {
	window.haNewTemplate = new NewTemplateModule();
});
