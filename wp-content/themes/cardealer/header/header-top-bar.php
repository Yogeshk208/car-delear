<?php if ( !defined('ABSPATH') ) exit; ?>

<div class="top-bar">

	<div class="container">
		<div class="row">

			<?php
			$auth_panel_is_active = TMM::get_option( 'show_auth_panel', TMM_APP_CARDEALER_PREFIX );
			?>

			<?php if ( $auth_panel_is_active ) { ?>

			<div class="col-md-3 col-xs-2">

				<?php get_template_part('header/header', 'login'); ?>

			</div>

			<?php } ?>

			<?php if ( class_exists('woocommerce') && tmm_show_header_cart() ) { ?>

				<div class="col-md-<?php echo $auth_panel_is_active ? '8' : '11'; ?> col-xs-<?php echo $auth_panel_is_active ? '8' : '11'; ?>">

					<?php if ( function_exists( 'dynamic_sidebar' ) AND dynamic_sidebar( 'top_sidebar' ) ); ?>

				</div>
				<div class="col-md-1 col-xs-2">

					<?php get_template_part('header/header', 'cart'); ?>

				</div>

			<?php } else { ?>

				<div class="col-md-<?php echo $auth_panel_is_active ? '9' : '12'; ?> col-xs-<?php echo $auth_panel_is_active ? '10' : '12'; ?>">

					<?php if ( function_exists( 'dynamic_sidebar' ) AND dynamic_sidebar( 'top_sidebar' ) ); ?>

				</div>

			<?php } ?>

		</div>
	</div>

</div>