<?php

/**
 * Class paypalAPI
 */
class paypalApi
{

	/**
	 * Start express checkout
	 */
	static function startExpressCheckout()
	{
		/* define options */
		$config = paypalConfig::getInstance();
		
		/* check account or packet price */
		$user_roles = TMM_Cardealer_User::get_user_roles();
		$featured_packets = TMM_Cardealer_User::get_features_packets();
		$currency = TMM_Ext_Car_Dealer::$default_currency['name'];
		
		if(isset($user_roles[$_POST['PAYMENTREQUEST_0_CUSTOM']])){
			$amount = $user_roles[$_POST['PAYMENTREQUEST_0_CUSTOM']]['packet_price'];
			$role_name = $user_roles[$_POST['PAYMENTREQUEST_0_CUSTOM']]['name'];
			$desc = __('Account Status Upgrade', TMM_PAYPAL_PLUGIN_TEXTDOMAIN);
		}else if(isset($featured_packets[$_POST['PAYMENTREQUEST_0_CUSTOM']])){
			$amount = $featured_packets[$_POST['PAYMENTREQUEST_0_CUSTOM']]['packet_price'];
			$role_name = $featured_packets[$_POST['PAYMENTREQUEST_0_CUSTOM']]['name'];
			$desc = __('Featured Cars Bundle', TMM_PAYPAL_PLUGIN_TEXTDOMAIN);
		}else{
			header('Location: ' . get_permalink(get_option('paypal_cancel_page')));
		}

		$desc .= ': `' . $role_name . '`, ' . $amount . ' ' . $currency;

		$checked = apply_filters('tmm_paypal_currency', $currency, (float)$amount);

		if ($currency !== $checked['currency']) {
			$desc .= ' (' . $checked['amount'] . ' ' . $checked['currency'] . ')';
		}
		$desc .= ', ' . home_url();

		$currency = $checked['currency'];
		$amount = $checked['amount'];

		$fields = array(
			'USER' => trim(get_option('paypal_api_username')),
			'PWD' => trim(get_option('paypal_api_password')),
			'SIGNATURE' => trim(get_option('paypal_api_signature')),
			'METHOD' => 'SetExpressCheckout',
			'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
			'RETURNURL' => $config->getItem('plugin_form_handler_url') . '?func=confirm',
			'CANCELURL' => $config->getItem('cancel_page'),
			'SOLUTIONTYPE' => get_option('paypal_solutiontype') === 'Mark' ? 'Mark' : 'Sole',
			'VERSION' => $config->getItem('paypal_api_version'),
			'PAYMENTREQUEST_0_AMT' => $amount,
			'PAYMENTREQUEST_0_ITEMAMT' => $amount,
			'PAYMENTREQUEST_0_AMT0' => $amount,
			'ITEMAMT' => $amount,
			'PAYMENTREQUEST_0_CURRENCYCODE' => $currency,
			'PAYMENTREQUEST_0_DESC' => $desc,
			'L_PAYMENTREQUEST_0_NAME0' => $role_name,
			'L_PAYMENTREQUEST_0_DESC0' => $desc,
			'L_PAYMENTREQUEST_0_AMT0' => $amount,
		);

		if (isset($_POST['PAYMENTREQUEST_0_CUSTOM'])) {
			$packet_key = $_POST['PAYMENTREQUEST_0_CUSTOM'];
			$fields['PAYMENTREQUEST_0_CUSTOM'] = $packet_key;
			//*** check if packet key exists
			$packets = TMM_Cardealer_User::get_user_roles();
			if (!isset($packets[$packet_key])) {
				$packets = TMM_Cardealer_User::get_features_packets();
				if (!isset($packets[$packet_key])) {
					header('Location: ' . $config->getItem('cancel_page'));
				}
			}
			//*** check if user paid full price or wrong price
			if ((float)$fields['PAYMENTREQUEST_0_AMT'] !== (float)$packets[$packet_key]['packet_price']) {
				header('Location: ' . $config->getItem('cancel_page'));
			}
		} else {
			header('Location: ' . $config->getItem('cancel_page'));
		}

		/* request to payPal for getting token */
		$result = self::doCurlRequest($fields);

		if (isset($result['ACK']) && ($result['ACK'] == 'Success' || $result['ACK'] == 'SuccessWithWarning')) {
			if (get_option('paypal_environment') == 'sandbox') {
				header('Location: ' . $config->getItem('paypal_sandbox_server_url') . $result['TOKEN']);
			} elseif (get_option('paypal_environment') == 'live') {
				header('Location: ' . $config->getItem('paypal_live_server_url') . $result['TOKEN']);
			}
		} else {
			header('Location: ' . get_permalink(get_option('paypal_cancel_page')));
		}
	}

	/**
	 * Validate payment
	 */
	static function confirmExpressCheckout()
	{
		/* define options */
		$config = paypalConfig::getInstance();

		$fields = array(
			'USER' => trim(get_option('paypal_api_username')),
			'PWD' => trim(get_option('paypal_api_password')),
			'SIGNATURE' => trim(get_option('paypal_api_signature')),
			'VERSION' => $config->getItem('paypal_api_version'),
			'TOKEN' => $_GET['token'],
			'METHOD' => 'GetExpressCheckoutDetails'
		);

		/* get express checkout details */
		$result = self::doCurlRequest($fields);
		$do_result = false;

		if ($result['ACK'] == 'Success' || $result['ACK'] == 'SuccessWithWarning') {
			self::savePayment($result, 'pending');
			$do_result = self::doExpressCheckout($result);
		} else {
			$status = 'failed  ' . $result['L_ERRORCODE0'] . ': ' . $result['L_SHORTMESSAGE0'];
			self::savePayment($result, $status);
		}

		if(!$do_result){
			return false;
		}

		return $result;

	}


	/**
	 * @param $result
	 * @return mixed
	 */
	static function doExpressCheckout($result)
	{

		$config = paypalConfig::getInstance();

		$fields = array(
			'USER' => trim(get_option('paypal_api_username')),
			'PWD' => trim(get_option('paypal_api_password')),
			'SIGNATURE' => trim(get_option('paypal_api_signature')),
			'VERSION' => $config->getItem('paypal_api_version'),
			'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
			'PAYERID' => $result['PAYERID'],
			'TOKEN' => $result['TOKEN'],
			'PAYMENTREQUEST_0_AMT' => $result['AMT'],
			'PAYMENTREQUEST_0_ITEMAMT' => $result['AMT'],
			'PAYMENTREQUEST_0_CURRENCYCODE' => $result['CURRENCYCODE'],
			'METHOD' => 'DoExpressCheckoutPayment'
		);

		/* do express checkout payment */
		$do_result = self::doCurlRequest($fields);

		if ($do_result['ACK'] == 'Success' || $do_result['ACK'] == 'SuccessWithWarning') {
			self::updatePayment($result, 'success');
			return $do_result;
		} else {
			$status = 'failed  ' . $do_result['L_ERRORCODE0'] . ': ' . $do_result['L_SHORTMESSAGE0'];
			self::updatePayment($result, $status);
			return false;
		}

	}


	/**
	 * @param $result
	 * @param $status
	 */
	static function savePayment($result, $status)
	{
		global $wpdb;

		$description = $result['PAYMENTREQUEST_0_DESC'];
		if (isset($result['PAYMENTREQUEST_1_DESC'])) {
			$description .= $result['PAYMENTREQUEST_1_DESC'];
		}
		if (isset($result['PAYMENTREQUEST_2_DESC'])) {
			$description .= $result['PAYMENTREQUEST_2_DESC'];
		}

		$insert_data = array(
			'token' => $result['TOKEN'],
			'amount' => $result['AMT'],
			'currency' => $result['CURRENCYCODE'],
			'status' => $status,
			'firstname' => $result['FIRSTNAME'],
			'lastname' => $result['LASTNAME'],
			'email' => $result['EMAIL'],
			'description' => $description,
			'packet_id' => $result['PAYMENTREQUEST_0_CUSTOM'],
			'summary' => serialize($result),
			'created' => time()
		);

		$insert_format = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d');

		$wpdb->insert('tmm_cars_hccoder_paypal', $insert_data, $insert_format);
	}


	/**
	 * @param $result
	 * @param $status
	 */
	static function updatePayment($result, $status)
	{
		global $wpdb;

		if (!isset($result['PAYMENTINFO_0_TRANSACTIONID']) || !isset($result['TOKEN'])) {
			header('Location: ' . get_permalink(get_option('paypal_cancel_page')));
		}

		$update_data = array(
			'transaction_id' => $result['PAYMENTINFO_0_TRANSACTIONID'],
			'status' => $status
		);

		$where = array('token' => $result['TOKEN']);

		$update_format = array('%s', '%s');

		$wpdb->update('tmm_cars_hccoder_paypal', $update_data, $where, $update_format);
	}

	/**
	 * @param $fields
	 * @return mixed
	 */
	static function doCurlRequest($fields)
	{
		if (!function_exists('curl_init')) {
			return false;
		}
		
		$fields_string = http_build_query($fields);
		$config = paypalConfig::getInstance();
		$ch = curl_init();

		if (get_option('paypal_environment') == 'sandbox') {
			curl_setopt($ch, CURLOPT_URL, $config->getItem('paypal_sandbox_api_url'));
		} elseif (get_option('paypal_environment') == 'live') {
			curl_setopt($ch, CURLOPT_URL, $config->getItem('paypal_live_api_url'));
		}
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_CAINFO, $config->getItem('curl_certificate_path'));

		$result = curl_exec($ch);
		curl_close($ch);
		parse_str($result, $result);
		return $result;
	}

}

/*
 * GetExpressCheckoutDetails request
 *

Error:
Array (
	[TIMESTAMP] => 2015-02-11T08:27:36Z
	[CORRELATIONID] => fc0e5228a4407
	[ACK] => Failure
	[VERSION] => 98.0
	[BUILD] => 15177679
	[L_ERRORCODE0] => 10002
	[L_SHORTMESSAGE0] => Security error
	[L_LONGMESSAGE0] => Security header is not valid
	[L_SEVERITYCODE0] => Error
)

Success:
Array (
	[TOKEN] => EC-26U45590UF381731K
	[BILLINGAGREEMENTACCEPTEDSTATUS] => 0
	[CHECKOUTSTATUS] => PaymentActionNotInitiated
	[TIMESTAMP] => 2015-02-11T08:34:40Z
	[CORRELATIONID] => 54ba9b821f2d4
	[ACK] => Success
	[VERSION] => 98.0
	[BUILD] => 15177679
	[EMAIL] => email@gmail.com
	[PAYERID] => HZNW78K4JG4C2
	[PAYERSTATUS] => verified
	[FIRSTNAME] => Test
	[LASTNAME] => Buyer
	[COUNTRYCODE] => US
	[SHIPTONAME] => Test Buyer
	[SHIPTOSTREET] => 1 Main St
	[SHIPTOCITY] => San Jose
	[SHIPTOSTATE] => CA
	[SHIPTOZIP] => 95131
	[SHIPTOCOUNTRYCODE] => US
	[SHIPTOCOUNTRYNAME] => United States
	[ADDRESSSTATUS] => Confirmed
	[CURRENCYCODE] => EUR
	[AMT] => 0.99
	[SHIPPINGAMT] => 0.00
	[HANDLINGAMT] => 0.00
	[TAXAMT] => 0.00
	[CUSTOM] => 5378f27c63e76
	[DESC] => Account Status Upgrade: `Small Business Dealer`, 0.99â‚¬, http://localhost/cardealer
	[INSURANCEAMT] => 0.00
	[SHIPDISCAMT] => 0.00
	[PAYMENTREQUEST_0_CURRENCYCODE] => EUR
	[PAYMENTREQUEST_0_AMT] => 0.99
	[PAYMENTREQUEST_0_SHIPPINGAMT] => 0.00
	[PAYMENTREQUEST_0_HANDLINGAMT] => 0.00
	[PAYMENTREQUEST_0_TAXAMT] => 0.00
	[PAYMENTREQUEST_0_CUSTOM] => 5378f27c63e76
	[PAYMENTREQUEST_0_DESC] => Account Status Upgrade: `Small Business Dealer`, 0.99â‚¬, http://localhost/cardealer
	[PAYMENTREQUEST_0_INSURANCEAMT] => 0.00
	[PAYMENTREQUEST_0_SHIPDISCAMT] => 0.00
	[PAYMENTREQUEST_0_INSURANCEOPTIONOFFERED] => false
	[PAYMENTREQUEST_0_SHIPTONAME] => Test Buyer
	[PAYMENTREQUEST_0_SHIPTOSTREET] => 1 Main St
	[PAYMENTREQUEST_0_SHIPTOCITY] => San Jose
	[PAYMENTREQUEST_0_SHIPTOSTATE] => CA
	[PAYMENTREQUEST_0_SHIPTOZIP] => 95131
	[PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE] => US
	[PAYMENTREQUEST_0_SHIPTOCOUNTRYNAME] => United States
	[PAYMENTREQUEST_0_ADDRESSSTATUS] => Confirmed
	[PAYMENTREQUEST_0_ADDRESSNORMALIZATIONSTATUS] => None
	[PAYMENTREQUESTINFO_0_ERRORCODE] => 0
)

 *
 * DoExpressCheckoutPayment request
 *

Error:
Array (
	[TIMESTAMP] => 2015-02-11T09:16:11Z
	[CORRELATIONID] => 85297337e6f5b
	[ACK] => Failure
	[VERSION] => 98.0
	[BUILD] => 15177679
	[L_ERRORCODE0] => 10444
	[L_SHORTMESSAGE0] => Transaction refused because of an invalid argument. See additional error messages for details.
	[L_LONGMESSAGE0] => The transaction currency specified must be the same as previously specified.
	[L_SEVERITYCODE0] => Error
)
*/