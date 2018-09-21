<?php

/**
 * Plugin Name: ThemeMakers PayPal Express Checkout
 * Plugin URI: http://webtemplatemasters.com
 * Description: Integration of PayPal Express Checkout
 * Author: ThemeMakers
 * Version: 1.1.6
 * Author URI: http://themeforest.net/user/ThemeMakers
 * Text Domain: tmm_paypal_checkout
 */

define('TMM_PAYPAL_PLUGIN_URL', plugin_dir_url(__FILE__));
define('TMM_PAYPAL_PLUGIN_PATH', dirname(__FILE__));
define('TMM_PAYPAL_PLUGIN_TEXTDOMAIN', 'tmm_paypal_checkout');
require_once TMM_PAYPAL_PLUGIN_PATH . '/classes/paypalConfig.php';
require_once TMM_PAYPAL_PLUGIN_PATH . '/classes/paypalShortcode.php';
require_once TMM_PAYPAL_PLUGIN_PATH . '/classes/paypalAdmin.php';

function tmm_paypal_init () {

	/* Set base configuration */
	$config = paypalConfig::getInstance();

	$config->addItem('plugin_name', 'Dealer PayPal');
	// plugin menu pages - admin
	$config->addItem('plugin_id', 'paypal-express-checkout');
	$config->addItem('plugin_folder', 'tmm_paypal_checkout');
	$config->addItem('plugin_history_id', 'paypal-express-checkout-history');
	// plugin paths and urls
	$config->addItem('plugin_url', admin_url('admin.php?page=' . $config->getItem('plugin_id')));
	$config->addItem('plugin_history_url', admin_url('admin.php?page=' . $config->getItem('plugin_history_id')));
	$config->addItem('plugin_form_handler_url', TMM_PAYPAL_PLUGIN_URL . 'form-handler.php');
	$config->addItem('views_path', TMM_PAYPAL_PLUGIN_PATH . '/views/');
	$config->addItem('curl_certificate_path', TMM_PAYPAL_PLUGIN_PATH . '/cacert.pem');
	// payPal Api options
	$config->addItem('paypal_api_version', '98.0'); // version of PayPal NVP API
	$config->addItem('paypal_sandbox_api_url', 'https://api-3t.sandbox.paypal.com/nvp');
	$config->addItem('paypal_live_api_url', 'https://api-3t.paypal.com/nvp');
	$config->addItem('paypal_sandbox_server_url', 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=');
	$config->addItem('paypal_live_server_url', 'https://www.paypal.com/webscr?cmd=_express-checkout&token=');
	$config->addItem('buy_now_button_src', 'http://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif');
	$config->addItem('checkout_button_src', 'https://www.paypalobjects.com/en_US/i/btn/btn_xpressCheckout.gif');
	// default currency
	if (defined('TMM_APP_CARDEALER_PREFIX')) {
		$config->addItem('default_currency', TMM::get_option('default_currency', TMM_APP_CARDEALER_PREFIX) );
	}else {
		$config->addItem('default_currency', false);
	}
	// supported currencies
	$config->addItem('supported_currencies',
		array(
			'AUD',
			'BRL',
			'CAD',
			'CZK',
			'DKK',
			'EUR',
			'HKD',
			'HUF',
			'ILS',
			'JPY',
			'MYR',
			'MXN',
			'NOK',
			'NZD',
			'PHP',
			'PLN',
			'GBP',
			'RUB',
			'SGD',
			'SEK',
			'CHF',
			'TWD',
			'THB',
			'TRY',
			'USD',
		)
	);
	// success and cancel pages - front
	if (get_option('paypal_cancel_page')) {
		$config->addItem('cancel_page', get_permalink(get_option('paypal_cancel_page')));
	} else {
		$config->addItem('cancel_page', home_url());
	}
	if (get_option('paypal_success_page')) {
		$config->addItem('success_page', get_permalink(get_option('paypal_success_page')));
	} else {
		$config->addItem('success_page', home_url());
	}

	$config->addItem('history_page_pagination_limit', 20);

	/* create shortcode */
	add_shortcode('paypal', array('paypalShortcode', 'frontendIndex'));

}

add_action('init', 'tmm_paypal_init', 2);

/**
 * Load plugin textdomain.
 */
function tmm_paypal_load_textdomain() {
	load_plugin_textdomain( 'tmm_paypal_checkout', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}

add_action( 'plugins_loaded', 'tmm_paypal_load_textdomain' );

/**
 * Create admin menus
 */
function adminMenu() {
    $config = paypalConfig::getInstance();

    add_menu_page($config->getItem('plugin_name'), $config->getItem('plugin_name'), 'level_10', $config->getItem('plugin_id'), array('paypalAdmin', 'adminConfiguration'), TMM_PAYPAL_PLUGIN_URL . '/images/icon.png');
    add_submenu_page($config->getItem('plugin_id'), __('Payments history', $config->getItem('plugin_id')), __('Payments history', $config->getItem('plugin_id')), 'level_10', $config->getItem('plugin_history_id'), array('paypalAdmin', 'adminHistory'));
}

add_action('admin_menu', 'adminMenu');

/**
 * Display amount in currency that supported by Paypal
 */
function tmm_paypal_default_currency($amount) {
	if ($amount <= 0) {
		return;
	}

    $currency = TMM_Ext_Car_Dealer::$default_currency['name'];
	$checked = apply_filters('tmm_paypal_currency', $currency, (float)$amount);

	if ($currency !== $checked['currency']) {
		if (TMM::get_option( 'car_price_symbol_pos', TMM_APP_CARDEALER_PREFIX ) === 'right') {
			$price = $checked['amount'] . ' ' . $checked['currency'];
		} else {
			$price = $checked['currency'] . ' ' . $checked['amount'];
		}
		echo '<div class="currency" style="font-size: 22px"> (' . $price . ')</div>';
	}
}

add_action('tmm_paypal_default_currency', 'tmm_paypal_default_currency');

/**
 * Check currency.
 * If currency is not supported by Paypal convert it to default
 */
function tmm_paypal_currency($currency, $amount) {
	$config = paypalConfig::getInstance();

	if ( !in_array($currency, $config->getItem('supported_currencies')) ){

		$def_currency = get_option('paypal_currency');

		if ($def_currency) {
			$currency = $def_currency;
			$new_amount = tmm_get_currency_rate($amount, $currency, $def_currency);

			if ((float) $new_amount) {
				$amount = $new_amount;
				$currency = $def_currency;
			}

		}

	}

	return array('currency'=>$currency, 'amount'=>$amount);
}

add_filter('tmm_paypal_currency', 'tmm_paypal_currency', 10, 2);

/**
 * Create table for payment history on plugin activation
 */
register_activation_hook(__FILE__, array('paypalAdmin', 'pluginInstall'));
