function save_options(type) {
	var data = {
		type: type,
		action: "change_options",
		values: jQuery("#theme_options").serialize()
		//test sending options using base64
		//values: window.btoa(jQuery("#theme_options").serialize())
	};
	//send data to server
	jQuery.post(ajaxurl, data, function(response) {
		show_info_popup(response);
		if (type == 'reset') {
			window.location.href = tmm_theme_options_url;
		}
	});
}

function get_url_params(param){
	var vars = {};
	window.location.href.replace( location.hash, '' ).replace(
		/[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
		function( m, key, value ) { // callback
			vars[key] = value !== undefined ? value : '';
		}
	);

	if ( param ) {
		return vars[param] ? vars[param] : null;
	}
	return vars;
}

jQuery(function() {

	  var page_settings = get_url_params('page');

      jQuery('.admin-nav li a').on('click',function(){

        var activeTab = jQuery(this).attr('href');

	  	var date = new Date();
		var minutes = 10;
			date.setTime(date.getTime() + (minutes * 60 * 1000));
		if (page_settings == 'tmm_theme_options'){
			Cookies.set('selectedTab_cardealer', activeTab, {expires: date});
		}
		else{
			Cookies.set('selectedTab_cardealer_car_settings', activeTab, {expires: date});
		}


      })

});


function getURLParameter(name) {
	return decodeURI((RegExp(name + '=' + '(.+?)(&|$)').exec(location.search) || [, null])[1]);
}


        /* ---------------------------------------------------------------------- */
	/*	Admin Navigation Plugin
	 /* ---------------------------------------------------------------------- */
function init_tabs() {


(function($) {


	$.AdminNavigation = function(el, options) {
		this.el = $(el);
		this.init(options);
	};

	$.AdminNavigation.defaults = {
		slideSpeed: 555
	};

	$.AdminNavigation.prototype = {
		init: function(options) {
			this.o = $.extend({}, $.AdminNavigation.defaults, options);
			this.refreshElements();
			this.slideNav(this);
		},
		slideNav: function(self) {

			var page_settings = get_url_params('page');

			if (page_settings == 'tmm_theme_options'){
				var activeTab = Cookies.get('selectedTab_cardealer');
			}
			else{
				var activeTab = Cookies.get('selectedTab_cardealer_car_settings');
			}



			if (activeTab){
				var $navLi = this.adminNav.children('li'),
				sectionTab = this.sectionTab;
				jQuery('ul.admin-nav li:has(a[href="' + activeTab + '"])').first().addClass('current-shortcut');
				jQuery('ul.admin-nav li:has(a[href="' + activeTab + '"])').last().addClass('sub-current');

				if (jQuery('.current-shortcut > ul li').hasClass('current-shortcut') && jQuery('.current-shortcut > ul li').hasClass('sub-current')){
					jQuery('.sub-current').removeClass('current-shortcut');
				}
				jQuery('.current-shortcut > ul').addClass('curr-ul');
				jQuery('.curr-ul').slideDown(this.slideSpeed);

				sectionTab.hide();
				jQuery(activeTab).show();

			}

			else{
				var $navLi = this.adminNav.children('li'),
					sectionTab = this.sectionTab;
			sectionTab.hide().first().show();

			$navLi.eq(0).addClass('current-shortcut');
			}

			this.adminNav.on('click', 'a', function(e) {
				$navLi.find('li').removeClass('sub-current');
				var $target = $(e.target).parent('li'),
						$targetUl = $target.children('ul').stop(true, true);
				$targetUl.children('li:first').addClass('sub-current');

				self.tabs(sectionTab, $target);

				if ($target.parent('.admin-nav').length) {
					$navLi.removeClass('current-shortcut');
					$target.addClass('current-shortcut');
					self.adminNav.find('li > ul').stop(true, true).slideUp(this.slideSpeed);
				} else {
					$target.addClass('sub-current');
				}

				$target.children('ul:hidden').length ? $targetUl.slideDown(this.slideSpeed) : $targetUl.slideUp(this.slideSpeed);
				e.preventDefault();
			});
		},
		tabs: function(sectionTab, target) {
			sectionTab.hide();
			var href = target.children('a').attr('href');
			$(href).fadeIn();
		},
		elements: {
			'.admin-nav': 'adminNav',
			'.section-tab': 'sectionTab'
		},
		$: function(selector) {
			return $(selector, this.el);
		},
		refreshElements: function() {
			for (var key in this.elements) {
				this[this.elements[key]] = this.$(key);
			}
		}
	};

	$.fn.AdminNavigation = function(options) {
		return $.data(this, 'AdminNavigation', new $.AdminNavigation(this, options));
	};


        /* ---------------------------------------------------------------------- */
	/*	Custom Events
	 /* ---------------------------------------------------------------------- */

	(function() {

		$.fn.showHide = function() {

			return this.each(function(idx, val) {
				var target = $(val),
						checkTagName = function() {
							var check = false, tagName = target.prop('tagName').toLowerCase();
							switch (tagName) {
								case 'input':
									check = true;
									break;
								case 'select':
									check = false;
									break;
							}
							return check;
						}, eventtype = checkTagName() ? 'click' : 'change',
						methods = {
							init: function() {
								this.eventsListener();
							},
							eventsListener: function() {
								target.on(eventtype, function() {
									var tagName = $(this).prop('tagName').toLowerCase(), el;
									switch (tagName) {
										case 'input':
											el = $(this);
											target.closest('.section').find('.show-hide-items > li').stop(true, true).animate({height: 'hide', opacity: 0}, 300);
											$('.' + el.data('show-hide')).stop(true, true).animate({height: 'show', opacity: 1}, 300);
											break;
										case 'select':
											el = $(this).children(':selected');
											var list = target.closest('.section').find('.show-hide-items > li');
											list.stop(true, true).animate({height: 'hide', opacity: 0}, 300).eq(el.val()).stop(true, true).animate({height: 'show', opacity: 1}, 300);
											break;
									}

								});
							}
						}
				return methods.init();
			});
		}

		$.fn.multiChoice = function(name) {
			return this.each(function() {
				$(this).on('click', 'a', function() {
					var $this = $(this), $parent = $this.parent();
					$parent.siblings('li').removeClass('current').end().addClass('current');
					if (name) {
						$("[name = " + name + "]").val($this.attr('href'));
					}
					return false;
				});
			});
		};

	}());



})(jQuery);

}


function deinit_tabs() {
	var $nav = jQuery('#tm ul.admin-nav');
	var $sub = $nav.find('ul');
	var $navLi = $nav.children('li');
	$sub.find('a').unbind('click');
	$navLi.children('a').unbind('click');


	jQuery.fn.tabs = function($obj) {
		$obj.find('ul.admin-nav li > a').unbind('click');
	};
}

jQuery('#options-framework').css( 'min-height' ,
	jQuery(window).height() - 32 - 150 - 89 -
	jQuery('#title-bar').outerHeight(true) -
	jQuery('.set-holder').outerHeight(true) );

//*****

function save_color_pickers_states() {
	var pickers = jQuery(".bg_hex_color");

	jQuery.each(pickers, function(index, picker) {
		var name = jQuery(picker).attr('name');
		var color = jQuery(picker).val();
		var pickers_saved_values = jQuery.cookie(name);

		if (pickers_saved_values === null) {
			pickers_saved_values = [];
		} else {
			pickers_saved_values = pickers_saved_values.split('+');
		}

		if (pickers_saved_values.length > 20) {
			pickers_saved_values.pop();
		}

		var already_in_array = false;

		for (var i = 0; i < pickers_saved_values.length; i++) {
			if (color == pickers_saved_values[i]) {
				already_in_array = true;
				break;
			}
		}
		//do not save equaly colors
		if (!already_in_array) {
			pickers_saved_values.unshift(color);
		}

		//to string again
		pickers_saved_values = pickers_saved_values.join('+');
		jQuery.cookie(name, pickers_saved_values);
	});

}

function get_color_picker_value(input, index) {
	index = parseInt(index, 10);
	var name = jQuery(input).attr('name');

	var pickers_saved_values = jQuery.cookie(name);

	if (pickers_saved_values === null) {
		return false;
	}

	if (pickers_saved_values.length === 0) {
		return false;
	}

	//to array
	pickers_saved_values = pickers_saved_values.split('+');
	pickers_saved_values.pop();
	//***

	if (index < 0) {
		index = pickers_saved_values.length - 1;
		jQuery(input).attr('value-index', index);
	}


	if (pickers_saved_values[index] == undefined && index == 0) {
		return false;
	}

	if (pickers_saved_values[index] == undefined || pickers_saved_values[index].length == 0) {
		jQuery(input).attr('value-index', 0);
		index = 0;
	}


	if (index >= pickers_saved_values.length) {
		index = 0;
		jQuery(input).attr('value-index', 0);
	}


	return pickers_saved_values[index];
}



jQuery(document).ready(function($) {

	try {
		if (getURLParameter('tmm_action') === 'save_options') {
			save_options("save");
		}
	} catch (e) {
	}


	show_loader();

	init_tabs();

        jQuery('.admin-container').AdminNavigation();

        if ($('.showhide').length) {
			$('.showhide').showHide();
		}

	//option_checkbox
	jQuery(document.body).on('click', '.option_checkbox', function() {
		if (jQuery(this).is(":checked")) {
			jQuery(this).parents(".checker").prev("input[type=hidden]").val(1);
			jQuery(this).parents(".checker").next("input[type=hidden]").val(1);
		} else {
			jQuery(this).parents(".checker").prev("input[type=hidden]").val(0);
			jQuery(this).parents(".checker").next("input[type=hidden]").val(0);
		}
	});

	jQuery(document.body).on('click', '.button_save_options', function()
	{
		//save_color_pickers_states();
		save_options("save");
		return false;
	});


	jQuery(document.body).on('click', '.js_picker_val_ahead', function() {
		var input = jQuery(this).parent().find(".bg_hex_color").eq(0);
		var button = jQuery(this).parent().find(".bgpicker").eq(0);
		var index = parseInt(jQuery(input).attr('value-index'), 10);

		if (index >= 20) {
			index = 0;
		}

		var val = get_color_picker_value(input, index);
		if (val !== false) {
			jQuery(input).val(val);
			jQuery(button).css('background-color', val);

			if (index != parseInt(jQuery(input).attr('value-index'), 10)) {
				jQuery(input).attr('value-index', parseInt(jQuery(input).attr('value-index'), 10) + 1);
			} else {
				jQuery(input).attr('value-index', index + 1);
			}

		}

	});

	jQuery(document.body).on('click', '.js_picker_val_back', function() {
		var input = jQuery(this).parent().find(".bg_hex_color").eq(0);
		var button = jQuery(this).parent().find(".bgpicker").eq(0);
		var index = parseInt(jQuery(input).attr('value-index'), 10);

		var val = get_color_picker_value(input, index);

		if (val !== false) {
			jQuery(input).val(val);
			jQuery(button).css('background-color', val);

			if (index != parseInt(jQuery(input).attr('value-index'), 10)) {
				jQuery(input).attr('value-index', parseInt(jQuery(input).attr('value-index'), 10) - 1);
			} else {
				jQuery(input).attr('value-index', index - 1);
			}
		}

	});



	jQuery(document.body).on('click', '.js_picker_val_reset', function() {
		var input = jQuery(this).parent().find(".bg_hex_color").eq(0);
		var button = jQuery(this).parent().find(".bgpicker").eq(0);
		var def_val = jQuery(input).data('default-value');
		jQuery(input).val(def_val);
		jQuery(button).css('background-color', def_val);
	});



	jQuery(document.body).on('click', '.button_reset_options', function()
	{
		if (confirm(tmm_l10n.lang_sure)) {
			jQuery.each(tmm_options_reset_array, function(key, value) {
				var elem = jQuery("[name=" + value + "]");
                                elem.val(elem.data('default-value'));
			});
			save_options("reset");
		}

		return false;
	});

	//Pattern Selector
	jQuery('.thumb_pattern a').click(function() {
		jQuery('.thumb_pattern a').removeClass('current');
		jQuery(this).addClass('current');
		jQuery('[name=body_pattern]').val(jQuery(this).attr('href'));
		return false;
	});
    $('.thumb-pattern').multiChoice('body_pattern');

	jQuery(document.body).on('click', '.body_pattern', function()
	{
		get_tb_editor_image_link(jQuery('[name=body_pattern]'), jQuery('#body_pattern_upload'));
		return false;
	});

	//insert background by hands
	jQuery(document.body).on('blur', '#body_pattern_upload', function() {
		jQuery("#body_pattern_custom_image_preview").show();
		jQuery(".body_pattern_custom_image img").attr("src", jQuery(this).val());
		jQuery('[name=body_pattern]').val(jQuery(this).val());
		return false;
	});

	jQuery(document.body).on('change', '[name=body_pattern]', function() {
		jQuery("#body_pattern_custom_image_preview").show();
		jQuery(".body_pattern_custom_image img").attr("src", jQuery(this).val());
		return false;
	});


	jQuery('[name=body_pattern_selected]').change(function() {
		jQuery(".options_custom_body_pattern ul li").hide();

		switch (parseInt(jQuery(this).val(), 10)) {
			case 0:
				jQuery(".body_pattern_default_image").show(show_delay);
				break;
			case 1:
				jQuery(".body_pattern_custom_image").show(show_delay);
				break;
			case 2:
				jQuery(".body_pattern_custom_color").show(show_delay);
				break;
		}


	});


	//*****
	jQuery("[name=favicon_img]").change(function() {
		jQuery("#favicon_preview_image").show();
		jQuery("#favicon_preview_image").attr("src", jQuery(this).val());
	});
	jQuery("[name=logo_img]").change(function() {
		jQuery("#logo_preview_image").show();
		jQuery("#logo_preview_image").attr("src", jQuery(this).val());
	});


	//*****

	jQuery(document.body).on('click', '.delegate_click', function() {
		var id_data = jQuery(this).attr('id-data');
		jQuery("[href=#" + id_data + "]").trigger('click');
		return false;
	});



	//ACCORDION

	if (jQuery(".acc-trigger").length) {

		var $container = jQuery('.acc-container'),
				$trigger = jQuery('.acc-trigger');

		$container.hide();
		$trigger.first().addClass('active').next().show();

		$trigger.on('click', function(e) {
			var $this = jQuery(this);

			if ($this.attr('data-mode') === 'toggle') {
				$this.toggleClass('active').next().stop(true, true).slideToggle(300);
			} else if ($this.next().is(':hidden')) {
				$trigger.removeClass('active').next().slideUp(300);
				$this.toggleClass('active').next().slideDown(300);
			}
			e.preventDefault();
		});

	}


	//*****
	hide_loader();

});


/* ---------------------------------------------------- */
/*	jQuery Cookie
 /* ---------------------------------------------------- */
//for color options history
jQuery.cookie = function(name, value, options) {
		if (typeof value != 'undefined') {
			options = options || {};
			if (value === null) {
				value = '';
				options.expires = -1;
			}
			var expires = '';
			var date = new Date();
			date.setTime(date.getTime() + 24 * 60 * 60 * 30 * 1000);
			expires = date.toUTCString();


			var path = options.path ? options.path : '';
			var domain = options.domain ? options.domain : '';
			var secure = options.secure ? 'true' : 'false';
			//document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
			Cookies.set(name, encodeURIComponent(value), expires, path, domain, secure);


		} else {
			var cookieValue = null;
			if (Cookies.get() && Cookies.get() != '') {
				var cookies = Cookies.get();

				for (var key in cookies) {
				  if (cookies.hasOwnProperty(key)) {
				    	if (key == name){
				    		cookieValue = decodeURIComponent(cookies[key]);
				    		break;
				    	}
				  }
				}
			}
			return cookieValue;
		}
	};
