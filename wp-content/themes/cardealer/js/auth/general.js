var THEMEMAKERS_APP_AUTHENTICATION = function () {
	var self = {
		init: function () {

			var hiddenPanel = jQuery('.register-hidden-panel'),
				is_hidden_panel_active = false;

			//*****

			jQuery(document.body).on('click', '#user_logout_button', function () {
				var data = {
					action: "app_authentication_user_logout"
				};
				//send data to server
				jQuery.post(ajaxurl, data, function (response) {
					window.location.reload();
				});

				return false;
			});

			jQuery(document.body).on('click', '#user_login_button', function () {
				self.login();
				return false;
			});

			if (jQuery('#tmm_user_pass').length && jQuery('#tmm_user_login').length) {
				jQuery('#tmm_user_pass').add('#tmm_user_login').on('focus', function () {
					jQuery(window).on('keyup', function (e) {
						e.preventDefault();
						if (e.keyCode === 13) {
							self.login();
						}
					});
				});
			}

			jQuery(document.body).on('click', '#user_register_button', function () {

				if (is_hidden_panel_active === true) {
					hiddenPanel.delay(350).animate({
						marginTop: '0'
					}, 450);
					is_hidden_panel_active = false;
					return false;
				}

				var user_name = jQuery("#user_name").val();
				var user_email = jQuery("#user_email").val();

				if (user_name == "" || user_email == "") {
					alert(tmm_l10n.empty_fields);
					return false;
				}

				var data = {
					action: "app_authentication_user_register",
					user_name: user_name,
					user_email: user_email
				};

				//send data to server
				jQuery.post(ajaxurl, data, function (response) {

					var userEntry = jQuery('.register-user-entry');
					userEntry.height(userEntry.height());

					if (response == 0) {
						response = tmm_l10n.server_error;
					}

					jQuery('.error-register').html(response);
					jQuery('.error-register').slideDown(400);

					is_hidden_panel_active = true;

				});

				return false;
			});

			jQuery(document.body).on('click', '#user_register_button2', function () {
				var user_name = jQuery("#user_name2").val();
				var user_email = jQuery("#user_email2").val();

				if (user_name == "" || user_email == "") {
					alert(tmm_l10n.empty_fields);
					return false;
				}

				var data = {
					action: "app_authentication_user_register",
					user_name: user_name,
					user_email: user_email
				};
				//send data to server
				jQuery.post(ajaxurl, data, function (response) {
					jQuery("#register-info2").html(response);
					jQuery("#register-info2").show();
				});

				return false;
			});

			var lostpasswordform = jQuery('#lostpasswordform');

			if (lostpasswordform.length && jQuery('#user_login').length) {

				lostpasswordform.on('submit', function () {
					var user_login = jQuery("#user_login").val(),
						info_block = jQuery('<div class="lostpass-info"></div>').hide(),
						active = lostpasswordform.data('active');

					if (active) {
						return false;
					}

					lostpasswordform.data('active', true);

					if (!jQuery(this).find('.lostpass-info').length) {
						jQuery(this).append(info_block);
					}

					info_block = jQuery(this).find('.lostpass-info');
					info_block.hide().removeClass().addClass('lostpass-info');

					if (!user_login) {
						info_block.text(tmm_l10n.auth_enter_username).addClass('error').show();
						lostpasswordform.data('active', false);
						return false;
					}

					var data = {
						action: "tmm_auth_lostpass",
						user_login: user_login
					};

					jQuery.post(ajaxurl, data, function (response) {

						lostpasswordform.data('active', false).find('#user_login').val('');

						if (response.error && response.error != '') {
							info_block.text(response.error).addClass('error').show();
							return false;
						} else if (response.message && response.message === 'check_email') {
							info_block.text(tmm_l10n.auth_lostpass_email_sent).addClass('success').show();
						}

					}, 'json');

					return false;
				});

			}


		},
		login: function () {

			var user_login = jQuery("#tmm_user_login").val();
			var user_pass = jQuery("#tmm_user_pass").val();


			if (user_login == "" || user_pass == "") {
				jQuery(".error-login").slideDown(400);
				return false;
			}


			var data = {
				action: "app_authentication_user_login",
				user_login: user_login,
				user_pass: user_pass
			};

			//send data to server
			jQuery.post(ajaxurl, data, function (response) {
				if (parseInt(response, 10) == 1) {
					window.location.reload();
				} else {
					jQuery(".error-login").slideDown(400);
				}
			});

			return false;
		}
	};

	return self;
};
//*****

var thememakers_app_authentication = null;
jQuery(document).ready(function () {
	thememakers_app_authentication = new THEMEMAKERS_APP_AUTHENTICATION();
	thememakers_app_authentication.init();
});
