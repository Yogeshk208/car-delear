<?php if ( !defined('ABSPATH') ) exit; ?>

<div class="wrap">
	<h2><?php _e('PayPal Express Checkout - Configuration', TMM_PAYPAL_PLUGIN_TEXTDOMAIN) ?></h2>

	<?php if (isset($config_saved) && $config_saved === TRUE) { ?>
	    <div class="updated" id="message">
			<p><strong><?php _e('Configuration updated.', TMM_PAYPAL_PLUGIN_TEXTDOMAIN) ?></strong></p>
		</div>
	<?php } ?>

	<form method="post" action="<?php echo $config->getItem('plugin_url'); ?>">
		<table class="form-table">
			<tbody>
				<tr class="form-field">
					<th scope="row"><label for="environment"><strong><?php _e('PayPal environment', TMM_PAYPAL_PLUGIN_TEXTDOMAIN) ?>:</strong></label></th>
					<td>
						<select id="environment" name="environment">
							<!--<option value=""><?php //_e('Please select', TMM_PAYPAL_PLUGIN_TEXTDOMAIN) ?></option>-->
							<option value="sandbox" <?php echo get_option('paypal_environment') == 'sandbox' ? 'selected="selected"' : ''; ?>><?php _e('Sandbox - Testing', TMM_PAYPAL_PLUGIN_TEXTDOMAIN) ?></option>
							<option value="live" <?php echo get_option('paypal_environment') == 'live' ? 'selected="selected"' : ''; ?>><?php _e('Live - Production', TMM_PAYPAL_PLUGIN_TEXTDOMAIN) ?></option>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
        <hr>
        <h3 class="title"><?php _e('PayPal API credentials', TMM_PAYPAL_PLUGIN_TEXTDOMAIN) ?>:</h3>
		<table class="form-table">
			<tbody>
				<tr class="form-field form-required">
					<th scope="row"><label for="api_username"><?php _e('API Username', TMM_PAYPAL_PLUGIN_TEXTDOMAIN) ?> <span class="description">(<?php _e('required', TMM_PAYPAL_PLUGIN_TEXTDOMAIN) ?>)</span></label></th>
					<td><input type="text"  value="<?php echo get_option('paypal_api_username'); ?>" id="api_username" name="api_username" autocomplete="off"></td>
				</tr>
				<tr class="form-field form-required">
					<th scope="row"><label for="api_password"><?php _e('API Password', TMM_PAYPAL_PLUGIN_TEXTDOMAIN) ?><span class="description">(<?php _e('required', TMM_PAYPAL_PLUGIN_TEXTDOMAIN) ?>)</span></label></th>
					<td><input type="password"  value="<?php echo get_option('paypal_api_password'); ?>" id="api_password" name="api_password" autocomplete="off"></td>
				</tr>
				<tr class="form-field form-required">
					<th scope="row"><label for="api_signature"><?php _e('API Signature', TMM_PAYPAL_PLUGIN_TEXTDOMAIN) ?><span class="description">(<?php _e('required', TMM_PAYPAL_PLUGIN_TEXTDOMAIN) ?>)</span></label></th>
					<td><input type="text" aria-required="true" value="<?php echo get_option('paypal_api_signature'); ?>" id="api_signature" name="api_signature" autocomplete="off"></td>
				</tr>
			</tbody>
		</table>
        <hr>
		<table class="form-table">
			<tbody>
				<tr class="form-field">
					<th scope="row"><label for="success_page"><strong><?php _e('Thank you page', TMM_PAYPAL_PLUGIN_TEXTDOMAIN) ?> - <br /><?php _e('successful payment', TMM_PAYPAL_PLUGIN_TEXTDOMAIN) ?>:</strong></label></th>
					<td>
						<?php wp_dropdown_pages(array('name' => 'success_page', 'selected' => get_option('paypal_success_page'), 'show_option_none' => 'Please select')); ?>
					</td>
				</tr>
				<tr class="form-field">
					<th scope="row"><label for="cancel_page"><strong><?php _e('Error page', TMM_PAYPAL_PLUGIN_TEXTDOMAIN) ?> - <br /><?php _e('failed payment', TMM_PAYPAL_PLUGIN_TEXTDOMAIN) ?>:</strong></label></th>
					<td>
						<?php wp_dropdown_pages(array('name' => 'cancel_page', 'selected' => get_option('paypal_cancel_page'), 'show_option_none' => __('Please select', TMM_PAYPAL_PLUGIN_TEXTDOMAIN))); ?>
					</td>
				</tr>
			</tbody>
		</table>

		<hr>
		<table class="form-table">
			<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="paypal_solutiontype"><strong><?php _e('Type of checkout flow', TMM_PAYPAL_PLUGIN_TEXTDOMAIN) ?></strong></label></th>
				<td>
					<select id="paypal_solutiontype" name="paypal_solutiontype">
						<?php
							$selected = get_option('paypal_solutiontype');
							if (!$selected) {
								$selected = 'Sole';
							}
							?>
							<option value="Sole" <?php selected($selected, 'Sole');?>><?php _e('Buyer does not need to create a PayPal account to check out.', TMM_PAYPAL_PLUGIN_TEXTDOMAIN) ?></option>
							<option value="Mark" <?php selected($selected, 'Mark');?>><?php _e('Buyer must have a PayPal account to check out.', TMM_PAYPAL_PLUGIN_TEXTDOMAIN) ?></option>
					</select>
				</td>
			</tr>
			</tbody>
		</table>

		<?php if (!in_array($config->getItem('default_currency'), $config->getItem('supported_currencies'))) { ?>
		<hr>
		<h3 class="title"><?php _e('PayPal currency', TMM_PAYPAL_PLUGIN_TEXTDOMAIN) ?>:</h3>
		<table class="form-table">
			<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="paypal_currency"><strong><?php _e('Currency', TMM_PAYPAL_PLUGIN_TEXTDOMAIN) ?></strong></label></th>
				<td>
					<select id="paypal_currency" name="paypal_currency">
					<?php foreach ( $config->getItem('supported_currencies') as $val ) {
						$selected = get_option('paypal_currency');
						if (!$selected) {
							$selected = 'USD';
						}
						?>
						<option value="<?php echo $val; ?>" <?php selected($selected, $val);?>><?php echo $val; ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
			</tbody>
		</table>
		<?php } ?>

		<p class="submit">
			<input type="submit" value="<?php _e('Save', TMM_PAYPAL_PLUGIN_TEXTDOMAIN) ?>" class="button-primary" />
		</p>
	</form>
</div><!-- .wrap -->
