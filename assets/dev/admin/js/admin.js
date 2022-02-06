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
		document.getElementById("newViewGroup")._x_dataStack[0].step = 1;
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

function getParameterByName(url, name) {
	var regexS = "[\\?&]" + name + "=([^&#]*)",
		regex = new RegExp(regexS),
		results = regex.exec(url);
	if (results == null) {
		return "";
	} else {
		return decodeURIComponent(results[1].replace(/\+/g, " "));
	}
}

jQuery(function ($) {
	MicroModal.init();
	window.haNewTemplate = new NewTemplateModule();

	var htf_template_active = $("#ha-template-activate");
	var htf_display_type = $("#template_display_type");
	var htf_display_singular = $("#condition_singular");
	var htf_display_singular_id = $("#ha-template-singular-select2");
	var htf_post_id = 0;

	htf_display_singular.parent().parent().hide();
	htf_display_singular_id.parent().parent().hide();

	htf_display_type.on("change", function () {
		if (this.value == "general" || this.value == "archive") {
			// htf_display_type.parent().parent().show();
			htf_display_singular.parent().parent().hide();
			htf_display_singular_id.parent().parent().hide();
		} else {
			htf_display_singular.parent().parent().show();
			if (htf_display_singular.val() == "selective") {
				htf_display_singular_id.parent().parent().show();
			}
		}
	});

	htf_display_singular.on("change", function () {
		if (this.value == "selective") {
			htf_display_singular_id.parent().parent().show();
		} else {
			htf_display_singular_id.parent().parent().hide();
		}
	});

	$(".row-actions .edit a, .row-title").on("click", function (e) {
		e.preventDefault();
		var editUrl = $(this).attr("href");
		htf_post_id = getParameterByName(editUrl, "post");
		var elementorUrl = editUrl.replace("edit", "elementor");
		var tplTypeView = $("#edit-template-type");

		jQuery.ajax({
			url: ajaxurl,
			type: "get",
			dataType: "json",
			data: {
				action: "ha_template_get_data", // AJAX action for admin-ajax.php
				post_id: htf_post_id,
			},
			success: function (data) {
				if(data){
					if(data.active){
						htf_template_active.prop( "checked", true );
					}else{
						htf_template_active.prop( "checked", false );
					}
					if(data.type){
						tplTypeView.text(ucwords(data.type)+" ");
					}
					if(data.cond){
						var parts = data.cond.split("/");
						console.log(parts);
						if(parts[0]){
							htf_display_type.val(parts[0]).change();
						}

						if(parts[1]){
							htf_display_singular.val(parts[1]).change();
						}

						if(parts[2]){
							htf_display_singular_id.val(parts[2]).change();
						}
					}
				}
				console.log(data);
			},
		});

		MicroModal.show("modal-login");
	});

	$("#ha-template-save-data").on("click", function (e) {
		e.preventDefault();
		var formData = {
			post_id: htf_post_id,
			template_active: htf_template_active.is(":checked"),
			template_display_type: htf_display_type.val(),
			condition_singular: htf_display_singular.val(),
			condition_singular_id: htf_display_singular_id.val(),
		};

		jQuery.ajax({
			url: ajaxurl,
			type: "post",
			dataType: "json",
			data: {
				action: "ha_template_save_data", // AJAX action for admin-ajax.php
				settings: JSON.stringify(formData),
			},
			success: function (data) {
				if(htf_template_active.is(":checked")){
					console.log('hit1');
					$("#htlt-"+htf_post_id).html(" - <b>Active</b>");
				}else{
					console.log('hit2');
					$("#htlt-"+htf_post_id).html('');
				}
			},
		});
	});

	$("#ha-template-singular-select2").select2({
		ajax: {
			url: ajaxurl, // AJAX URL is predefined in WordPress admin
			dataType: "json",
			delay: 250, // delay in ms while typing when to perform a AJAX search
			data: function (params) {
				return {
					s: params.term, // search query
					action: "ha_template_singulars", // AJAX action for admin-ajax.php
				};
			},
			placeholder: "--",
			processResults: function (data) {
				var options = [];
				if (data) {
					console.log(data);
					// data is the array of arrays, and each of them contains ID and the Label of the option
					$.each(data, function (index, text) {
						// do not forget that "index" is just auto incremented value
						options.push({ id: text[0], text: text[1] });
					});
				}
				return {
					results: options,
				};
			},
			cache: true,
		},
		minimumInputLength: 3, // the minimum of symbols to input before perform a search
	});
});


function ucwords (str) {
    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
    });
}

function newTemplateForm() {
	return {
		loading: true,
		step: 1,
		postTitle: "",
		templateType: "",
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
			if (this.selectedSingular == "selective") {
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
						if (data) {
							// localThis.selectiveData = data.reduce((obj, item) => ((obj[[item['id']]] = item), obj), {});
							var formatted = data.map((item) => {
								const container = {};
								container.id = item.id;
								container.text = item.title.rendered;
								return container;
							});
							localThis.selectiveData = formatted;
						}
						console.log(localThis.selectiveData);
						localThis.loading = false;
					});
			} else {
				localThis.selectiveData = null;
				console.log(localThis.selectiveData);
			}
		},
		buttonDisabled() {
			if (this.postTitle && this.templateType) {
				return false;
			}

			return true;
		},
	};
}

function newTemplateFormInit() {
	console.log(this.$refs);
	var selectContainer = document.getElementById(
		"elementor-new-template__display_type_selected"
	);
	if (selectContainer) {
		this.select2 = this.selectContainer.select2();
		this.select2.on("select2:select", (event) => {
			this.selectedSingularData = event.target.value;
		});
		this.$watch("selectedSingularData", (value) => {
			this.select2.val(value).trigger("change");
		});
	}
}
