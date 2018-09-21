<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * 	Contributors: MooveAgency, gaspar.nemes
 *  Plugin Name: User Activity Tracking and Log
 *  Plugin URI: http://www.mooveagency.com
 *  Description: This plugin gives you the ability to track user activity on your website.
 *  Version: 1.0.9
 *  Author: Moove Agency
 *  Author URI: http://www.mooveagency.com
 *  License: GPLv2
 *  Text Domain: moove
 */

register_activation_hook( __FILE__ , 'moove_activity_activate' );
register_deactivation_hook( __FILE__ , 'moove_activity_deactivate' );

/**
 * Set options page for the plugin
 */
function moove_set_options_values() {
	$settings = get_option( 'moove_post_act' );
	$post_types = get_post_types( array( 'public' => true ) );
	unset( $post_types['attachment'] );
	if ( ! $settings ) :
		foreach ( $post_types as $post_type ) :
			if ( $settings[ $post_type ] !== 1 || ! isset( $settings[ $post_type ] ) ) :
				$settings[ $post_type ] = 0;
				update_option( 'moove_post_act', $settings );
			endif;
			if ( $settings[ $post_type.'_transient' ] !== 1 || ! isset( $settings[ $post_type.'_transient' ] ) ) :
				$settings[ $post_type.'_transient' ] = 7;
				update_option( 'moove_post_act', $settings );
			endif;
		endforeach;
	endif;
}

/**
 * Functions on plugin activation, create relevant pages and defaults for settings page.
 */
function moove_activity_activate() {
	moove_set_options_values();
}


/**
 * Function on plugin deactivation. It removes the pages created before.
 */
function moove_activity_deactivate() {
}

include_once( dirname( __FILE__ ).DIRECTORY_SEPARATOR.'moove-view.php' );
include_once( dirname( __FILE__ ).DIRECTORY_SEPARATOR.'moove-content.php' );
include_once( dirname( __FILE__ ).DIRECTORY_SEPARATOR.'moove-options.php' );
include_once( dirname( __FILE__ ).DIRECTORY_SEPARATOR.'moove-controller.php' );
include_once( dirname( __FILE__ ).DIRECTORY_SEPARATOR.'moove-actions.php' );
include_once( dirname( __FILE__ ).DIRECTORY_SEPARATOR.'moove-shortcodes.php' );
include_once( dirname( __FILE__ ).DIRECTORY_SEPARATOR.'moove-functions.php' );

