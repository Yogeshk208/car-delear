jQuery(function($) {
	$(document).ready(function () {
		$("body").prepend('<div id="thememakers_cardealer_image_buffer"></div>');
		$("body").prepend('<div class="info_popup" style="display: none;"></div>');

		$(document.body).on('click', '.button_save_cardealer_options', function () {

			$("[name=cardealer_form]").find('input[data-typecheck=number]').each(function () {
				var self = jQuery(this),
					value = self.val();

				if (isNaN(value)) {
					value = parseFloat(value);
					if (isNaN(value)) {
						value = '';
					}
				}
				self.val(value);
			});

			var data = {
				action: "app_cardealer_save_settings",
				values: $("[name=cardealer_form]").serialize()
				//test sending options using base64
				//values: window.btoa(jQuery("[name=cardealer_form]").serialize())
			};
			$.post(ajaxurl, data, function (response) {
				show_info_popup(response);
			});
			return false;
		});

		$("[name=cardealer_form]").find('input[data-typecheck=number]').live('blur',function () {
			var self = $(this);
			if (isNaN(self.val())) {
				show_info_popup('Please! Fill in correct numeric value.');
				window.setTimeout(function () {
					self.trigger('focus');
				}, 100);
			}
		}).live('input', function () {
			var value = $(this).val();
			value = value.replace(/[^0-9\.]/g, '');
			$(this).val(value);
		});

		$("#add_car_video").click(function () {
			var template = $("ul#cars_videos li:first-child").html();
			$("ul#cars_videos").append('<li>' + template + '</li>');
			$("ul#cars_videos li:last-child input[type=text]").val("");
			return false;
		});

		$(".remove_car_video").live('click', function () {
			if ($('#cars_videos').find('li').length > 1) {
				$(this).parents('li').hide(200, function () {
					$(this).remove();
				});
			}

			return false;
		});

		$(".js_car_is_featured").on('change', function () {
			var is_checked = $(this).is(':checked');
			var post_id = $(this).attr('value');
			var data = {
				action: "app_cardealer_admin_set_car_as_featured",
				post_id: post_id,
				value: (is_checked ? 1 : 0)
			};
			$.post(ajaxurl, data, function (response) {
				if (is_checked) {
					show_info_popup(tmm_l10n.lang_thememakers_cardealer_featured_car_set);
				} else {
					show_info_popup(tmm_l10n.lang_thememakers_cardealer_featured_car_unset);
				}
			});
		});


		$(".js_car_is_draft").on('change', function () {
			var is_checked = $(this).is(':checked');
			var post_id = $(this).attr('value');
			var data = {
				action: "app_cardealer_draft_car",
				post_id: post_id,
				car_is_draft: is_checked ? 1 : 0
			};
			$.post(ajaxurl, data, function (response) {
				if (is_checked) {
					show_info_popup(tmm_l10n.lang_tmm_cardealer_draft_car_set);
				} else {
					show_info_popup(tmm_l10n.lang_tmm_cardealer_draft_car_unset);
					$('#post-' + post_id + ' .post-state').remove();
				}
			});
		});


		$(".js_car_is_sold").on('change', function () {
			var is_checked = $(this).is(':checked');
			var post_id = $(this).attr('value');
			var data = {
				action: "app_cardealer_sold_car",
				post_id: post_id,
				car_is_sold: is_checked ? 1 : 0
			};
			$.post(ajaxurl, data, function (response) {
				if (is_checked) {
					show_info_popup(tmm_l10n.lang_tmm_cardealer_sold_car_set);
				} else {
					show_info_popup(tmm_l10n.lang_tmm_cardealer_sold_car_unset);
				}
			});
		});

		$("[name=show_slider_as]").change(function () {
			var mode = $(this).val();
			if (mode == 0) {
				$(".js_slider_with_sidebar").parent().parent().hide(200);
				$(".js_slider_without_sidebar").parent().parent().show(200);
			} else {
				$(".js_slider_with_sidebar").parent().parent().show(200);
				$(".js_slider_without_sidebar").parent().parent().hide(200);
			}
		});


		$(".cardealer_update_sample").click(function () {
			show_static_info_popup('Moment ...');

			var data = {
				action: "app_cardealer_update_sample_watermark",
				watermark_size_percent: $("[name=watermark_size_percent]").val(),
				alpha_level: $("[name=alpha_level]").val(),
				watermark_position: $('[name="watermark_position"]').val(),
				watermark_src: $('#watermark_image').val()
			};
			$.post(ajaxurl, data, function (response) {
				$("#watermark_sample_preview img").attr('src', response);
				hide_static_info_popup();
			});

			return false;
		});

		$('#add_customer_currencies_new').live('click', function () {
			var name = $('#customer_currencies_new_name').val();
			var symbol = $('#customer_currencies_new_symbol').val();

			if (name.length <= 1 || symbol.lenght <= 1) {
				show_info_popup(tmm_l10n.lang_tmm_enter_data_right);
				return false;
			}

			//***

			$('#cars_currencies_list').append('<li><input type="text" name="customer_currencies_names[]" value="' + name + '" />&nbsp;<input type="text" name="customer_currencies_symbols[]" value="' + symbol + '" /></li>');
			$('#customer_currencies_new_name').val("");
			$('#customer_currencies_new_symbol').val("");
			return false;
		});


		$('.cardealer_max_images_size').change(function () {
			var user_id = parseInt($(this).data('user-id'), 10);
			var data = {
				action: "app_cardealer_user_max_images_size",
				user_id: user_id,
				value: $(this).val()
			};
			$.post(ajaxurl, data, function (response) {
				show_info_popup(tmm_l10n.lang_updated);
			});


			return false;
		});


		$('.button_import_cardealer_settings').live('click', function () {
			if (confirm(tmm_l10n.lang_sure)) {
				show_static_info_popup(tmm_l10n.lang_loading);
				var data = {
					action: "app_cardealer_import_cardealer_settings"
				};
				$.post(ajaxurl, data, function (response) {
					window.location.reload();
				});
			}

			return false;
		});

		$('#allow_custom_title').on('click', function () {
			if ($(this).is(':checked')) {
				$('#car_title_symbols_limit').parents('.option').slideDown();
				$('[name=car_link_type]').parents('.option').slideDown();
			} else {
				$('#car_title_symbols_limit').parents('.option').slideUp();
				$('[name=car_link_type]').parents('.option').slideUp();
			}
		});

		// blog archive (theme options)
		$('.blog_archive_header_type').on('change', function () {
			if ($(this).val() === 'alternate') {
				$('#blog_archive_show_title_bar').parents('.option').slideDown();
			} else {
				$('#blog_archive_show_title_bar').parents('.option').slideUp();
			}
		});

		$('.blog_archive_title_bar_bg_type').on('change', function () {
			if ($(this).val() === 'image') {
				$('#blog_archive_title_bar_bg_image').parents('.option').slideDown();
				$('.blog_archive_title_bar_bg_image_option').parents('.option').slideDown();
				$('[name=blog_archive_title_bar_bg_color]').parents('.option').slideUp();
			} else {
				$('[name=blog_archive_title_bar_bg_color]').parents('.option').slideDown();
				$('#blog_archive_title_bar_bg_image').parents('.option').slideUp();
				$('.blog_archive_title_bar_bg_image_option').parents('.option').slideUp();
			}
		});

		$('#blog_archive_show_title_bar').on('click', function () {
			if ($(this).is(':checked')) {
				$('#blog_archive_title_bar_content').slideDown();
			} else {
				$('#blog_archive_title_bar_content').slideUp();
			}
		});

		// search results page (theme options)
		$('.search_page_header_type').on('change', function () {
			if ($(this).val() === 'alternate') {
				$('#search_page_show_title_bar').parents('.option').slideDown();
			} else {
				$('#search_page_show_title_bar').parents('.option').slideUp();
			}
		});

		$('.search_page_title_bar_bg_type').on('change', function () {
			if ($(this).val() === 'image') {
				$('#search_page_title_bar_bg_image').parents('.option').slideDown();
				$('.search_page_title_bar_bg_image_option').parents('.option').slideDown();
				$('[name=search_page_title_bar_bg_color]').parents('.option').slideUp();
			} else {
				$('[name=search_page_title_bar_bg_color]').parents('.option').slideDown();
				$('#search_page_title_bar_bg_image').parents('.option').slideUp();
				$('.search_page_title_bar_bg_image_option').parents('.option').slideUp();
			}
		});

		$('#search_page_show_title_bar').on('click', function () {
			if ($(this).is(':checked')) {
				$('#search_page_title_bar_content').slideDown();
			} else {
				$('#search_page_title_bar_content').slideUp();
			}
		});

		// car archive page (car settings)
		$('.car_archive_header_type').on('change', function () {
			if ($(this).val() === 'alternate') {
				$('#car_archive_show_title_bar').parents('.option').slideDown();
			} else {
				$('#car_archive_show_title_bar').parents('.option').slideUp();
			}
		});

		$('.car_archive_title_bar_bg_type').on('change', function () {
			if ($(this).val() === 'image') {
				$('#car_archive_title_bar_bg_image').parents('.option').slideDown();
				$('.car_archive_title_bar_bg_image_option').parents('.option').slideDown();
				$('[name=car_archive_title_bar_bg_color]').parents('.option').slideUp();
			} else {
				$('[name=car_archive_title_bar_bg_color]').parents('.option').slideDown();
				$('#car_archive_title_bar_bg_image').parents('.option').slideUp();
				$('.car_archive_title_bar_bg_image_option').parents('.option').slideUp();
			}
		});

		$('#car_archive_show_title_bar').on('click', function () {
			if ($(this).is(':checked')) {
				$('#car_archive_title_bar_content').slideDown();
			} else {
				$('#car_archive_title_bar_content').slideUp();
			}
		});

		// car producer archive page (car settings)
		$('.car_producer_tax_header_type').on('change', function () {
			if ($(this).val() === 'alternate') {
				$('#car_producer_tax_show_title_bar').parents('.option').slideDown();
			} else {
				$('#car_producer_tax_show_title_bar').parents('.option').slideUp();
			}
		});

		$('.car_producer_tax_title_bar_bg_type').on('change', function () {
			if ($(this).val() === 'image') {
				$('#car_producer_tax_title_bar_bg_image').parents('.option').slideDown();
				$('.car_producer_tax_title_bar_bg_image_option').parents('.option').slideDown();
				$('[name=car_producer_tax_title_bar_bg_color]').parents('.option').slideUp();
			} else {
				$('[name=car_producer_tax_title_bar_bg_color]').parents('.option').slideDown();
				$('#car_producer_tax_title_bar_bg_image').parents('.option').slideUp();
				$('.car_producer_tax_title_bar_bg_image_option').parents('.option').slideUp();
			}
		});

		$('#car_producer_tax_show_title_bar').on('click', function () {
			if ($(this).is(':checked')) {
				$('#car_producer_tax_title_bar_content').slideDown();
			} else {
				$('#car_producer_tax_title_bar_content').slideUp();
			}
		});

		$(".similar_cars_params").sortable();

	});
});


(function($){

	$(function(){

		$('.qs_widget_carlocation0').live('change', function(){
			var select = $('.qs_widget_carlocation1'),
				parent_id = $(this).val();

			if(parent_id !== ''){
				var data = {
					action: "app_cardealer_draw_locations_select",
					hide_empty: 0,
					parent_id: parent_id,
					selected: 0,
					container: false
				};
				$.post(ajaxurl, data, function(responce) {
					temp = $('<span style="display: none"></span>');
					temp.appendTo('body').html(responce);
					select.html(temp.find('select').children());
					temp.empty();
				});
			}else{
				var default_option = select.find('option:first').eq(0);
				select.empty().append(default_option);
			}
			return false;
		});

		$('.qs_widget_checkbox').live('click', function(){
			if($(this).is(':checked')){
				$(this).siblings('select').addClass('hide');
			}else{
				$(this).siblings('select').removeClass('hide');
			}
		});

		$('.widget_dealer_type').live('change', function(){
			var select = $('.widget_specific_dealer'),
				data = {
					action: "app_cardealer_get_users_by_role",
					role: $(this).val()
				};

			$.post(ajaxurl, data, function(responce) {
				var default_option = select.find('option:first-child').eq(0),
					options = '',
					responce = $.parseJSON(responce),
					i;

				for(i in responce){
					options += '<option value="' + responce[i]['ID'] + '">' + responce[i]['user_nicename'] + '</option>';
				}

				select.empty().html(options).prepend(default_option).trigger('change');
			});

			return false;
		});

		$('select[name=car_listing_thumbnail_size]').live('change', function(){
			var thumb_size = $(this).val(),
				items_per_page_select = $('select[name=car_listing_items_per_page]'),
				pagination_values = {
					'small':[6, 12, 18, 24, 30],
					'middle': [4, 8, 12, 16, 20, 24, 36],
					'large': [3, 6, 9, 12, 15, 30, 45, 60]
				},
				i,
				options = '';

			if (pagination_values[thumb_size]) {
				pagination_values = pagination_values[thumb_size];

				for (i in pagination_values) {
					options += '<option value="'+pagination_values[i]+'">'+pagination_values[i]+'</option>';
				}
			}

			items_per_page_select.empty().append(options);
		});

		$('#show_layout_switcher').on('click', function() {
			if ($(this).is(':checked')) {
				$(this).parents('.section').next().hide();
			} else {
				$(this).parents('.section').next().show();
			}
		});

	});

})(jQuery);