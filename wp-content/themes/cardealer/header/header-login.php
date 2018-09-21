<?php if ( !defined('ABSPATH') ) exit; ?>
<?php if ( TMM::get_option( 'show_auth_panel', TMM_APP_CARDEALER_PREFIX ) ) { ?>

	<?php $users_can_register = get_option( 'users_can_register' ); ?>

	<?php if ( ! is_user_logged_in() ): ?>

		<button data-dialog="somedialog-1" id="login-button" class="trigger"><i class="icon-user"></i><span><?php _e( "Sign In", 'cardealer' ) ?></span></button>

		<div id="somedialog-1" class="dialog">
			<div class="dialog__overlay"></div>
			<div class="dialog__content">
				<div class="morph-shape">
					<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 364 434" preserveAspectRatio="none">
						<rect x="2" y="2" fill="none" width="360" height="430"/>
					</svg>
				</div>
				<div class="dialog-inner">

					<button class="action" data-dialog-close><i class="icon-cancel"></i></button>

					<form method="post" action="/" onsubmit="return thememakers_app_authentication.login()">

						<div class="form-reg">

							<?php if (!is_user_logged_in()): ?>

								<?php if ($users_can_register): ?>

									<div class="tabs-auth">

										<!-- Radio button and lable for #tab-content1 -->
										<input type="radio" name="auth" id="tab-login" checked >
										<label for="tab-login" class="label-login">
											<?php _e( "Already a member?", 'cardealer' ) ?><br><i><?php _e( "Sign in Here", 'cardealer' ) ?></i>
										</label>

										<!-- Radio button and lable for #tab-content2 -->
										<input type="radio" name="auth" id="tab-reg">
										<label for="tab-reg" class="label-reg">
											<?php _e( "Not a member?", 'cardealer' ) ?><br><i><?php _e( "Create Account", 'cardealer' ) ?></i>
										</label>

										<div id="tab-content-login" class="tab-content">

											<i class="icon-user-7"></i>

											<h2><?php _e( "Sign In", 'cardealer' ) ?></h2>

											<p class="error-login">
												<strong><?php _e( "ERROR", 'cardealer' ) ?>:</strong>
												<?php _e( "The login/password you entered is incorrect.", 'cardealer' ) ?>
											</p>

											<p>
												<input placeholder="<?php _e( "Username", 'cardealer' ) ?>*" type="text" id="tmm_user_login" autocomplete="off"/>
											</p>

											<p>
												<input placeholder="<?php _e( "Password", 'cardealer' ) ?>*" type="password" id="tmm_user_pass" autocomplete="off"/>
											</p>

											<p>
												<a href="#" class="button dark" id="user_login_button"><?php _e( "Login", 'cardealer' ) ?></a>
											</p>

											<p>
												<a href="<?php echo wp_lostpassword_url( get_permalink() ); ?>"><?php _e( "Forgot your password?", 'cardealer' ) ?></a>
											</p>

											<input type="submit" style="display: none;"/>

										</div> <!-- #tab-content1 -->

										<div id="tab-content-reg" class="tab-content">

											<i class="icon-pencil"></i>

											<h2><?php _e( "Create Account", 'cardealer' ) ?></h2>

											<p class="error-register"></p>

											<div class="register-user-entry">

												<div class="register-hidden-panel">
													<p>
														<input placeholder="<?php _e( "Email", 'cardealer' ) ?>*" type="text" id="user_email"/>
													</p>

													<p>
														<input placeholder="<?php _e( "Name", 'cardealer' ) ?>*" type="text" id="user_name"/>
													</p>
												</div>

											</div><!--/ .register-user-entry-->

											<p>
												<a href="#" class="button dark" id="user_register_button"><?php _e( "Register", 'cardealer' ) ?></a>
											</p>

										</div> <!-- #tab-content2 -->

									</div>

								<?php else : ?>

									<i class="icon-user-7"></i>

									<h2><?php _e( "Sign In", 'cardealer' ) ?></h2>

									<p class="error-login">
										<strong><?php _e( "ERROR", 'cardealer' ) ?>:</strong>
										<?php _e( "The login/password you entered is incorrect.", 'cardealer' ) ?>
									</p>

									<p>
										<input placeholder="<?php _e( "Username", 'cardealer' ) ?>*" type="text" id="tmm_user_login" autocomplete="off"/>
									</p>

									<p>
										<input placeholder="<?php _e( "Password", 'cardealer' ) ?>*" type="password" id="tmm_user_pass" autocomplete="off"/>
									</p>

									<p>
										<a href="#" class="button dark" id="user_login_button"><?php _e( "Login", 'cardealer' ) ?></a>
									</p>

									<p>
										<a href="<?php echo wp_lostpassword_url( get_permalink() ); ?>"><?php _e( "Forgot your password?", 'cardealer' ) ?></a>
									</p>

									<input type="submit" style="display: none;"/>

								<?php endif; ?>

							<?php endif; ?>

						</div>

					</form>

				</div>
			</div>
		</div>

	<?php else : ?>

		<?php
		$user_profile_page = TMM::get_option( 'user_profile_page', TMM_APP_CARDEALER_PREFIX ) ? TMM_Helper::get_permalink_by_lang( TMM::get_option( 'user_profile_page', TMM_APP_CARDEALER_PREFIX ) ) : '';
		$user_cars_page    = TMM::get_option( 'user_cars_page', TMM_APP_CARDEALER_PREFIX ) ? TMM_Helper::get_permalink_by_lang( TMM::get_option( 'user_cars_page', TMM_APP_CARDEALER_PREFIX ) ) : '';
		$user_add_new_car  = TMM::get_option( 'user_add_new_car', TMM_APP_CARDEALER_PREFIX ) ? TMM_Helper::get_permalink_by_lang( TMM::get_option( 'user_add_new_car', TMM_APP_CARDEALER_PREFIX ) ) : '';
		$current_user = wp_get_current_user();
		?>

		<ul class="user_nav">
			<li>
				<i class="icon-user"></i> <span><?php _e( "Profile", 'cardealer' ) ?></span>

				<ul>
					<li><span><?php _e( "Howdy", 'cardealer' ) ?>, <?php echo $current_user->display_name; ?></span></li>

					<?php if ( ! empty( $user_profile_page ) ): ?>
						<li><a href="<?php echo $user_profile_page ?>"><?php _e( "My profile", 'cardealer' ) ?></a></li>
					<?php endif; ?>

					<?php if ( ! empty( $user_cars_page ) ): ?>
						<li><a href="<?php echo $user_cars_page ?>"><?php _e( "My Cars", 'cardealer' ) ?></a></li>
					<?php endif; ?>

					<?php if ( ! empty( $user_add_new_car ) ): ?>
						<?php
						$options         = TMM_Cardealer_User::get_default_user_role_options( get_current_user_id() );
						$user_post_count = TMM_Cardealer_User::count_users_cars( get_current_user_id() );

						if ( ! isset( $options['max_cars'] ) ) {
							$options['max_cars'] = 0;
						}

						if ( $user_post_count < $options['max_cars'] || user_can( get_current_user_id(), 'manage_options' ) ):
							?>
							<li><a href="<?php echo $user_add_new_car ?>"><?php _e( "Add new car", 'cardealer' ) ?></a></li>
						<?php endif; ?>

						<li><a href="#" id="user_logout_button"><?php _e( "Logout", 'cardealer' ) ?></a></li>
					<?php endif; ?>
				</ul>
			</li>
		</ul>

	<?php endif; ?>

<?php } ?>