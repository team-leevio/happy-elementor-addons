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
		return "#tmpl-ha-templates-modal__header__logo";
	},

	className() {
		return "elementor-templates-modal__header__logo";
	},

	events() {
		return {
			click: "onClick",
		};
	},

	templateHelpers() {
		return {
			title: this.getOption("title"),
		};
	},

	onClick() {
		const clickCallback = this.getOption("click");

		if (clickCallback) {
			clickCallback();
		}
	},
});
var NewTemplateView = Marionette.ItemView.extend({
	id: "elementor-new-template-dialog-content",

	template: "#tmpl-elementor-new-template",

	ui: {},

	events: {},

	onRender: function () {},
});

window.newTemplateStep = 1;

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

		// var selectContainer = jQuery('#elementor-new-template__display_type_selected');
		// if(selectContainer){
		// 	selectContainer.select2();
		// }
	},

	showContentView: function () {
		this.modalContent.show(new NewTemplateView());
	},

	showLogo: function () {
		this.getHeaderView().logoArea.show(new Logo(this.getLogoOptions()));
	},

	showModal() {
		this.getModal().show();
		console.log("Show");
	},

	hideModal() {
		this.getModal().hide();
		console.log("Hide");
		this.resetForm();
	},

	resetForm() {
		// document.getElementById("newViewGroup").__x.$data.step = 1;
		document.getElementById('newViewGroup')._x_dataStack[0].step = 1;
	},
});

var NewTemplateModule = elementorModules.ViewModule.extend({
	getDefaultSettings: function () {
		return {
			selectors: {
				addButton: "#ha-template-library-add-new",
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

function newTemplateForm() {
	return {
		loading: true,
		step: 1,
		conditionType: {
			general: "Entire Website",
			singular: "Sigular",
			archive: "Archive",
		},
		singularData: {
			all: "All Singular",
			"front-page": "Front Page",
			posts: "All Posts",
			pages: "All Pages",
			selective: "Selective Pages",
			404: "404 Pages",
		},
		selectedType: "singular",
		selectedSingular: null,
		selectedSingularData: null,
		selectiveData: {
			2: {
				id: 2,
				title: {
					rendered: "Sample Page",
				},
			},
			6: {
				id: 6,
				title: {
					rendered: "List group",
				},
			},
			234: {
				id: 234,
				title: {
					rendered: "Shop",
				},
			},
			235: {
				id: 235,
				title: {
					rendered: "Cart",
				},
			},
			236: {
				id: 236,
				title: {
					rendered: "Checkout",
				},
			},
			237: {
				id: 237,
				title: {
					rendered: "My account",
				},
			},
			466: {
				id: 466,
				title: {
					rendered: "Off Canvas",
				},
			},
			742: {
				id: 742,
				title: {
					rendered: "MultiScroll",
				},
			},
		},
		getSelective() {
			var localThis = this;
			this.loading = true;
			console.log(this.selectedSingular);
			if(this.selectedSingular == 'selective'){
				var pageCollection = new wp.api.collections.Pages();
				pageCollection
					.fetch({
						data: {
							_fields: "id,title",
							filter: {
								posts_per_page: -1,
								orderby: "title",
								order: "ASC",
							},
						},
					})
					.done(function (data) {
						if(data){
							localThis.selectiveData = data.reduce((obj, item) => ((obj[[item['id']]] = item), obj), {});
						}
						console.log(localThis.selectiveData);
						localThis.loading = false;
					});
			}else{
				localThis.selectiveData = null;
				console.log(localThis.selectiveData);
			}
		},
	};
}

function newTemplateFormInit(){
	console.log(this.$refs);
	var selectContainer = document.getElementById("elementor-new-template__display_type_selected");
	if(selectContainer){
		this.select2 = this.selectContainer.select2();
		this.select2.on("select2:select", (event) => {
			this.selectedSingularData = event.target.value;
		});
		this.$watch("selectedSingularData", (value) => {
			this.select2.val(value).trigger("change");
		});
	}
}
