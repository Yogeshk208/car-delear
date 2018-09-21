<?php

/**
 * Form posting handler
 */

require_once '../../../wp-load.php';
require_once TMM_PAYPAL_PLUGIN_PATH . '/classes/paypalConfig.php';
require_once TMM_PAYPAL_PLUGIN_PATH . '/classes/paypalApi.php';

if (isset($_POST['func']) && $_POST['func'] === 'start') {

	paypalApi::startExpressCheckout();

} else if (isset($_GET['func']) && $_GET['func'] == 'confirm' && isset($_GET['token']) && isset($_GET['PayerID'])) {

	$message_num = 0;
	$paypal_data = paypalApi::confirmExpressCheckout();
	$config = paypalConfig::getInstance();

	if ( isset($paypal_data['ACK']) && ($paypal_data['ACK'] == 'Success' || $paypal_data['ACK'] == 'SuccessWithWarning') ) {
		$message_num = TMM_Cardealer_User::user_paid_money($paypal_data);
		header('Location: ' . $config->getItem('success_page'));
	} else {
		$message_num = $paypal_data['L_ERRORCODE0'];
		header('Location: ' . $config->getItem('cancel_page') . '?errorcode=' . $message_num);
	}

} else {

	header('Location: ' . $config->getItem('cancel_page'));

}
