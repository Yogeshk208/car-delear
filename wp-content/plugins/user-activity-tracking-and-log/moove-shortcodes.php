<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Moove_Activity_Shortcodes File Doc Comment
 *
 * @category Moove_Activity_Shortcodes
 * @package   moove-activity-tracking
 * @author    Gaspar Nemes
 */

/**
 * Moove_Activity_Shortcodes Class Doc Comment
 *
 * @category Class
 * @package  Moove_Activity_Shortcodes
 * @author   Gaspar Nemes
 */
class Moove_Activity_Shortcodes {
	/**
	 * Construct function
	 */
	function __construct() {
		$this->moove_activity_register_shortcodes();
	}
	/**
	 * Register shortcodes
	 *
	 * @return void
	 */
	function moove_activity_register_shortcodes() {
		add_shortcode( 'show_ip', array( &$this, 'moove_get_the_user_ip' ) );
	}

	/**
	 * User IP address
	 *
	 * @return string IP Address
	 */
	function moove_get_the_user_ip() {
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) :
			// Check ip from share internet.
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) :
			// To check ip is pass from proxy.
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else :
			$ip = $_SERVER['REMOTE_ADDR'];
		endif;
		return $ip;
	}
}
$moove_activity_shortcodes_provider = new Moove_Activity_Shortcodes();
