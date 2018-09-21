<?php
if ( !defined('ABSPATH') ) exit;
$config = paypalConfig::getInstance();
?>

<form method="post" action="<?php echo $config->getItem('plugin_form_handler_url'); ?>">
	<?php if (isset($atts['amount'])) { ?>
	<input type="hidden" name="AMT" value="<?php echo $atts['amount']; ?>" autocomplete="off" />
	<?php } ?>

	<?php if (isset($atts['currency'])) { ?>
	<input type="hidden" name="CURRENCYCODE" value="<?php echo $atts['currency']; ?>" autocomplete="off" />
	<?php } ?>

	<?php if (isset($atts['packet_id'])) { ?>
	    <input type="hidden" name="PAYMENTREQUEST_0_CUSTOM" value="<?php echo $atts['packet_id']; ?>" autocomplete="off" />
	<?php } ?>

	<?php if (isset($atts['description'])) { ?>
	    <input type="hidden" name="PAYMENTREQUEST_0_DESC" value="<?php echo $atts['description']; ?>" autocomplete="off" />
	<?php } ?>

	<?php if (isset($atts['tax'])) { ?>
	    <input type="hidden" name="TAXAMT" value="<?php echo $atts['tax']; ?>" autocomplete="off" />
	<?php } ?>

	<?php if (isset($atts['shipping'])) { ?>
	    <input type="hidden" name="SHIPPINGAMT" value="<?php echo $atts['shipping']; ?>" autocomplete="off" />
	<?php } ?>

	<?php if (isset($atts['handling'])) { ?>
	    <input type="hidden" name="HANDLINGAMT" value="<?php echo $atts['handling']; ?>" autocomplete="off" />
	<?php } ?>

	<?php if (isset($atts['qty'])) { ?>
	    <input type="hidden" name="PAYMENTREQUEST_0_QTY" value="<?php echo $atts['qty']; ?>" autocomplete="off" />
	<?php } ?>

	<?php if (isset($atts['return_url'])) { ?>
	    <input type="hidden" name="RETURN_URL" value="<?php echo $atts['return_url']; ?>" autocomplete="off" />
	<?php } ?>

	<?php if (isset($atts['cancel_url'])) { ?>
	    <input type="hidden" name="CANCEL_URL" value="<?php echo $atts['cancel_url']; ?>" autocomplete="off" />
	<?php } ?>

	<input type="hidden" name="func" value="start" />

	<?php if (isset($atts['button_style'])) { ?>
		<?php if ($atts['button_style'] == 'buy_now') { ?>
			<input type="image" value="" src="<?php echo $config->getItem('buy_now_button_src'); ?>" alt="button" />
		<?php } elseif ($atts['button_style'] == 'checkout') { ?>
			<input type="image" value="" src="<?php echo $config->getItem('checkout_button_src'); ?>" alt="button" />
		<?php } ?>
	<?php } else { ?>
	    <input type="submit" value="<?php _e('Pay with PayPal', TMM_PAYPAL_PLUGIN_TEXTDOMAIN) ?>" />
	<?php } ?>
</form>