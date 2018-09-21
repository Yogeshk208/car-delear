<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Moove_Activity_Content File Doc Comment
 *
 * @category Moove_Activity_Content
 * @package   moove-activity-tracking
 * @author    Gaspar Nemes
 */

load_textdomain( 'moove', plugins_url( __FILE__ ) . DIRECTORY_SEPARATOR . 'languages' );

/**
 * Moove_Activity_Content Class Doc Comment
 *
 * @category Class
 * @package  Moove_Controller
 * @author   Gaspar Nemes
 */
class Moove_Activity_Content {
	/**
	 * Construct
	 */
	function __construct() {
		$this->moove_register_content_elements();
	}
	/**
	 * Register actions
	 *
	 * @return void
	 */
	function moove_register_content_elements() {
		// Custom meta box for protection.
		add_action( 'add_meta_boxes', array( &$this, 'moove_activity_meta_boxes' ) );
		add_action( 'save_post', array( &$this, 'moove_save_post' ) );
	}

	/**
	 * Checks the log status when the post being saved.
	 *
	 * @param int    $post_id  The post's id if the function is called from another controller.
	 * @param string $action Can be enabled or delete.
	 */
	public function moove_save_post( $post_id, $action = false ) {

		if ( isset( $post_id ) ) :
			$pid = $post_id;
		else :
			$pid = intval( $_POST['post_ID'] );
		endif;

		if ( ! $pid ) {
		  $pid = '';
		}

		// We are deleting campaign.
		$campaign_id_sanitized = sanitize_key( wp_unslash( $_POST['ma-delete-campaign'] ) );

		if ( ( isset( $campaign_id_sanitized ) && intval( $campaign_id_sanitized ) === 1) ) :
			delete_post_meta( $pid, 'ma_data' );
			return; // Break the function.
		endif;

		// We don't need to create any campaign.
		$trigger_campaign = sanitize_key( wp_unslash( $_POST['ma-trigger-campaign'] ) );
		if ( ! isset( $trigger_campaign ) ) :
			if ( $action !== 'enable' ) :
				return;
			endif;
		endif;

		// Get data for this post.
		$_post_meta = get_post_meta( $pid, 'ma_data' );
		$_ma_data_option = $_post_meta[0];
		$ma_data = unserialize( $_ma_data_option );
		// If we have the campaign ID set already, don't do anything.
		if ( isset( $ma_data['campaign_id'] ) && $ma_data['campaign_id'] !== '' ) :
			return;
		endif;


		// We can go ahead and create campaign.
		$campaign_id = current_time( 'timestamp' ).$post_id;
		$ma_data['campaign_id'] = $campaign_id;

		$post_type = get_post_type( $post_id );
		$settings = get_option( 'moove_post_act' );

		if ( intval( $settings[ $post_type ] ) !== 0 ) :
			update_post_meta( $pid, 'ma_data', serialize( $ma_data ) );
		endif;

		if ( intval( $trigger_campaign ) === 1 ) :
			update_post_meta( $pid, 'ma_data', serialize( $ma_data ) );
		endif;
	}

	/**
	 * Adding META-BOX for protection
	 */
	function moove_activity_meta_boxes() {
		$post_types = get_post_types( array( 'public' => true ) );
		unset( $post_types['attachment'] );
		foreach ( $post_types as $post_type ) :
			add_meta_box(
				'ma-main-meta-box',
				'Moove Activity',
				array( &$this, 'moove_main_meta_box_callback' ),
				$post_type,
				'normal',
				'default'
			);
		endforeach;
	}

	/**
	 * Meta box callback
	 */
	function moove_main_meta_box_callback() {
		$post_id = get_the_ID();
		$ma_data = array();
		if ( $post_id ) :
			if ( isset( $post_id  ) ) :
				$_post_meta = get_post_meta( $post_id, 'ma_data' );
				if ( isset( $_post_meta[0] ) ) :
					$_ma_data_option = $_post_meta[0];
					$ma_data = unserialize( $_ma_data_option );
				endif;
				$post_type = get_post_type( $post_id );
				$settings = get_option( 'moove_post_act' );
				$global_setup = $settings[ $post_type ];

			else :
				$ma_data = array();
			endif;
		else:
			$ma_data = array();
		endif;


		echo Moove_Activity_View::load(
			'moove.admin.activity_metabox',
			array(
				'activity'      => $ma_data,
				'global_setup'  => $global_setup,
			)
		);

	}
}
$moove_activity_content_provider = new Moove_Activity_Content();
