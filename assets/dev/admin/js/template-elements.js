(function ($) {
	var modalTemplate = document.getElementById(
		"tmpl-modal-template-condition"
	);

	var conditionTemplate = document.getElementById(
		"tmpl-elementor-new-template"
	);

	var templateType = "";
	var postId = 0;
	var newConditions = [];
	var oldConditionCache = "";

	if (typeof elementor !== "undefined") {
		elementor.on("panel:init", function ($e) {
			postId = elementor.config.document.id;
			handleHaTemplateType(postId);
			getHaTemplateConds(postId);
			elementor
				.getPanelView()
				.footer.currentView.addSubMenuItem("saver-options", {
					before: "save-draft",
					name: "haconditions",
					icon: "ha-template-elements",
					title: "Template Conditions",
					callback: function callback() {
						return elementor.trigger("ha:templateCondition");
					},
				});
		});
		elementor.channels.editor.on(
			"elementorThemeBuilder:ApplyPreview",
			function ($e) {
				handleHaTemplateType(postId);
			}
		);

		//elementor.getPanelView().getHeaderView().setTitle('a');
		elementor.on("set:page", function ($e) {
			console.log($e);
		});
	}

	$("body").append(modalTemplate.innerHTML);

	if (typeof elementor !== "undefined") {
		elementor.on("ha:templateCondition", function ($e) {
			//oldConditionCache
			var conditionContainer = $(".ha-template-condition-wrap");
			console.log(conditionContainer.html());
			if (conditionContainer.html() == "") {
				conditionContainer.append(oldConditionCache);
				conditionContainer.find("select").trigger("change");
				// elementor.trigger("ha:templateConditionChange");
			}

			// notice remove
			$('.ha-template-notice').removeClass('error').text('');

			MicroModal.show("modal-new-template-condition");
		});

		elementor.on("ha:templateConditionChange", function ($e) {
			handleHaTemplateCondition();
		});
	}

	$(document).on("click", ".ha-cond-repeater-add", function () {
		var conditionContainer = $(".ha-template-condition-wrap");
		var uniqify = generateUniqeDom(conditionTemplate.innerHTML);
		conditionContainer.append(uniqify);
		elementor.trigger("ha:templateConditionChange");
	});

	$(document).on("click", ".ha-template-condition-remove", function () {
		$(this).parent().remove();
		elementor.trigger("ha:templateConditionChange");
	});

	$(document).on("click", "#ha-template-save-data", function () {
		saveConditionData();
	});

	$(document).on(
		"change",
		".ha-template-condition-wrap select",
		function (event) {
			handleAssignEvent(event);
			elementor.trigger("ha:templateConditionChange");
		}
	);

	function generateUniqeDom(dom) {
		const randomid = Math.random().toString(36).replace("0.", "");
		dom = dom.replace(/{{([^{}]+)}}/g, randomid);
		return dom;
	}

	function handleAssignEvent(event) {
		if (event.target.localName == "select") {
			var parentID = event.target.dataset.parent;
			var selectedType = event.target.dataset.setting;
			var selected = event.target.value;

			var type = $("[data-id='type-" + parentID + "']");
			var name = $("[data-id='name-" + parentID + "']");
			var sub_name = $("[data-id='sub_name-" + parentID + "']");
			var sub_id = $("[data-id='sub_id-" + parentID + "']");

			if (selectedType == "type") {
				//TODO: Add prefix icon later on
			}

			if (selectedType == "name") {
				if (selected == "general") {
					sub_name.parent().hide();
					sub_id.parent().hide();
				} else {
					sub_name.parent().show();
					var selectedVal = sub_name.data("selected")
						? sub_name.data("selected")
						: "";

					add_sub_name(sub_name, name.val(), selectedVal);
				}
			}

			if (selectedType == "sub_name") {
				var dataPair = {
					post: "post",
					in_category: "category",
					in_category_children: "category",
					in_post_tag: "post_tag",
					post_by_author: "author",
					page: "page",
					page_by_author: "author",
					child_of: "page",
					any_child_of: "page",
					by_author: "author",
				};
				if (dataPair.hasOwnProperty(selected)) {
					// Toggle Visibility
					sub_id.parent().show();

					var dataType = dataPair[selected];
					var dataVal = selected;

					if (["post", "page"].includes(dataType)) {
						dataVal = dataType;
						dataType = "post";
					}

					if (["category", "post_tag"].includes(dataType)) {
						dataVal = dataType;
						dataType = "tax";
					}

					sub_id.select2({
						ajax: {
							url: ajaxurl,
							dataType: "json",
							delay: 250,
							data: function (params) {
								var query = {
									nonce: HappyAddonsEditor.editor_nonce,
									action: "ha_condition_autocomplete",
									q: params.term,
									object_type: dataType,
									object_term: dataVal,
								};
								return query;
							},
							processResults: function (response) {
								if (
									!response.success ||
									response.data.length === 0
								) {
									return {
										results: [
											{
												id: -1,
												text: "No results found",
												disabled: true,
											},
										],
									};
								}

								var data = [];

								_.each(response.data, function (title, id) {
									data.push({
										id: id,
										text: title,
									});
								});

								return {
									results: data,
								};
							},
						},
						minimumInputLength: 2,
						cache: true,
						placeholder: "All",
						allowClear: true,
						dropdownCssClass: "ha-template-condition-dropdown"
					});
				} else {
					sub_id.parent().hide();
				}
			}

			if (selectedType == "sub_id") {
			}
		}
	}

	function handleHaTemplateCondition() {
		var conditions = [];
		var conditionItems = $(".ha-template-condition-wrap").find(
			".ha-template-condition-item"
		);
		conditionItems.each(function () {
			var type = $(this).find(".ha-tce-type select").val();
			var name = $(this).find(".ha-tce-name select").val();
			var sub_name = $(this).find(".ha-tce-sub_name select").val();
			var sub_id = $(this).find(".ha-tce-sub_id select").val();

			var localCond = type + "/" + name;

			if (sub_name) {
				localCond += "/" + sub_name;
			}

			if (sub_id) {
				localCond += "/" + sub_id.trim();
			}

			conditions.push(localCond);
		});
		newConditions = conditions;
		// console.log(newConditions);
	}

	function handleHaTemplateType(id) {
		jQuery.ajax({
			url: ajaxurl,
			type: "get",
			dataType: "json",
			data: {
				nonce: HappyAddonsEditor.editor_nonce,
				action: "ha_cond_template_type", // AJAX action for admin-ajax.php
				post_id: id,
			},
			success: function (response) {
				if (response && response.data) {
					templateType = response.data;
				}
			},
		});
	}

	function getHaTemplateConds(id) {
		jQuery.ajax({
			url: ajaxurl,
			type: "get",
			dataType: "json",
			data: {
				nonce: HappyAddonsEditor.editor_nonce,
				action: "ha_cond_get_current", // AJAX action for admin-ajax.php
				template_id: id,
			},
			success: function (response) {
				if (response && response.data) {
					oldConditionCache = response.data;
				}
			},
		});
	}

	function add_sub_name(target, dataType, selectedVal) {
		jQuery.ajax({
			url: ajaxurl,
			type: "get",
			dataType: "json",
			data: {
				nonce: HappyAddonsEditor.editor_nonce,
				action: "ha_condition_autocomplete", // AJAX action for admin-ajax.php
				object_type: dataType,
			},
			success: function (data) {
				if (data) {
					if (data.data) {
						var optionHTML = populate_option(
							data.data,
							selectedVal
						);
						target.html(optionHTML);
					}
				}
			},
		});
	}

	function populate_option(optionData, selectedVal) {
		var optionHTML = "";
		for (const [key, option] of Object.entries(optionData)) {
			if (option.hasOwnProperty("type")) {
				optionHTML += "<optgroup label='" + option.title + "'>";
				for (const [subkey, suboption] of Object.entries(
					option.conditions
				)) {
					var isPro = suboption.is_pro;
					var optionTitle = suboption.title;
					var optionKey = subkey;
					var isDisabled = "";
					var isSelected =
						selectedVal == optionKey ? " selected" : "";
					if (isPro) {
						optionTitle = optionTitle + " [Pro]";
						isDisabled = " disabled";
					}

					optionHTML +=
						"<option value='" +
						optionKey +
						"' " +
						isDisabled +
						isSelected +
						">" +
						optionTitle +
						"</option>";
				}
				optionHTML += "</optgroup>";
			} else {
				var isPro = option.is_pro;
				var optionTitle = option.title;
				var optionKey = key;
				var isDisabled = "";
				var isSelected = selectedVal == optionKey ? " selected" : "";

				if (isPro) {
					optionTitle = optionTitle + " [Pro]";
					isDisabled = " disabled";
				}

				optionHTML +=
					"<option value='" +
					optionKey +
					"' " +
					isDisabled +
					isSelected +
					">" +
					optionTitle +
					"</option>";
			}
		}

		return optionHTML;
	}

	function saveConditionData() {
		postId = elementor.config.document.id;
		jQuery.ajax({
			url: ajaxurl,
			type: "post",
			dataType: "json",
			data: {
				nonce: HappyAddonsEditor.editor_nonce,
				action: "ha_condition_update", // AJAX action for admin-ajax.php
				conds: newConditions,
				template_id: postId,
			},
			success: function (response) {
				if (response) {
                    if(response.success) {
                        MicroModal.close("modal-new-template-condition");
						$('.ha-template-notice').removeClass('error').text('');
                    } else {
                        // show notice
                        if(response.hasOwnProperty('data') && response.data.hasOwnProperty('msg')) {
                            $('.ha-template-notice').addClass('error').text(response.data.msg);
                        } else {
                            MicroModal.close("modal-new-template-condition");
							$('.ha-template-notice').removeClass('error').text('');
                        }
                    }
                }
			},
		});
	}
})(jQuery);
