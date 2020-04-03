(function ($) {
	"use strict";
	jQuery(window).on('elementor:init', function () {
		var ControlQueryPostSearch = elementor.modules.controls.BaseData.extend({

			isPostSearchReady: false,
			dataQueryOption: function(){
				var self = this;
				var data_options = self.model.get('data_options');
				if( !data_options && typeof data_options !== "object"){
					return false;
				}else{
					return data_options;
				}
			},
			getPostTitlesbyID: function () {
				var self = this,
					dataQueryOption = this.dataQueryOption(),
					ids = this.getControlValue();

				if (!ids || 0 === ids.length) {
					return;
				}

				if (!_.isArray(ids)) {
					ids = [ids];
				}
				var default_value = {
					security: HappyAddonsEditor.select2Secret,
					select_type: 'selected',
					id: ids
				};
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: $.extend( {}, default_value, dataQueryOption ),
					before: self.addControlSpinner(),
					success: function (results) {

						self.isPostSearchReady = true;
						self.model.set('options', results);
						self.render();
					}
				});
			},
			addControlSpinner: function () {
				this.ui.select.prop('disabled', true);
				this.$el.find('.elementor-control-title').after('<span class="elementor-control-spinner">&nbsp;<i class="eicon-spinner eicon-animation-spin"></i>&nbsp;</span>');
			},
			onReady: function () {
				var self = this,
					dataQueryOption = this.dataQueryOption();

				if( !dataQueryOption ){
					return;
				}
				this.ui.select.select2({
					placeholder: self.model.get('placeholder') ? self.model.get('placeholder') : 'Search',
					minimumInputLength: self.model.get('mininput') ? self.model.get('mininput') : 0,
					allowClear: true,
					ajax: {
						url: ajaxurl,
						dataType: 'json',
						method: 'post',
						delay: 250,
						data: function (params) {
							var default_value = {
								security: HappyAddonsEditor.select2Secret,
								select_type: 'choose',
								q: params.term,
							};
							var data_options = self.model.get('data_options');
							return $.extend( {}, default_value, data_options );
						},
						processResults: function (data) {
							// parse the results into the format expected by Select2.
							// since we are using custom formatting functions we do not need to
							// alter the remote JSON data
							var notFound = [{
									"id": -1,
									"text": "No results found",
									"disabled": true
								}];
							return {
								results: data !== null ? data : notFound
							}
						},
						cache: true
					}
				});
				if (!this.isPostSearchReady) {
					this.getPostTitlesbyID();
				}
			},
			onBeforeDestroy: function () {
				if (this.ui.select.data('select2')) {
					this.ui.select.select2('destroy');
				}
				this.$el.remove();
			}
		});
		elementor.addControlView('ha-select2', ControlQueryPostSearch);
	});
})(jQuery);
