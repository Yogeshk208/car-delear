<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Moove_Activity_Actions File Doc Comment
 *
 * @category  Moove_Activity_Actions
 * @package   moove-activity-tracking
 * @author    Gaspar Nemes
 */

/**
 * Moove_Activity_Actions Class Doc Comment
 *
 * @category Class
 * @package  Moove_Activity_Actions
 * @author   Gaspar Nemes
 */
class Moove_Activity_Actions {
	/**
	 * Global cariable used in localization
	 *
	 * @var array
	 */
	var $activity_loc_data;
	/**
	 * Construct
	 */
	function __construct() {
		$this->moove_register_scripts();

		add_action( 'wp_ajax_moove_activity_track_pageview', array( 'Moove_Activity_Controller', 'moove_track_user_access_ajax' ) );
		add_action( 'wp_ajax_nopriv_moove_activity_track_pageview', array( 'Moove_Activity_Controller', 'moove_track_user_access_ajax' ) );
		add_action( 'moove-activity-tab-content', array( &$this, 'moove_activity_tab_content' ), 5, 1 );
		add_action( 'moove-activity-tab-extensions', array( &$this, 'moove_activity_tab_extensions' ), 5, 1 );
		add_action( 'moove-activity-filters', array( &$this, 'moove_activity_filters' ), 5, 2 );
	}

	function moove_activity_tab_content( $data ) {
		if( $data['tab'] == 'post_type_activity' ) : ?>
            <form action="options.php" method="post" class="moove-activity-form">
                <?php
                settings_fields( 'moove_post_activity' );
                do_settings_sections( 'moove-activity' );
                submit_button();
                ?>
            </form>
        <?php elseif( $data['tab'] == 'plugin_documentation' ): ?>
            <?php echo Moove_Activity_View::load( 'moove.admin.settings.documentation' , true ); ?>
        <?php endif;
	}

	function moove_activity_tab_extensions( $active_tab ) {
		?>
		 <a href="?page=moove-activity&tab=post_type_activity" class="nav-tab <?php echo $active_tab == 'post_type_activity' ? 'nav-tab-active' : ''; ?>">
            <?php _e('Post type activity tracking','moove'); ?>
        </a>
		<?php
	}

	/**
	 * Register Front-end / Back-end scripts
	 *
	 * @return void
	 */
	function moove_register_scripts() {
		if ( is_admin() ) :
			add_action( 'admin_enqueue_scripts', array( &$this, 'moove_activity_admin_scripts' ) );
		else :
			add_action( 'wp_enqueue_scripts', array( &$this, 'moove_frontend_activity_scripts' ) );
		endif;
	}

	function moove_activity_filters( $filters, $content ) {
		echo $filters;
	}

	/**
	 * Register global variables to head, AJAX, Form validation messages
	 *
	 * @param  string $ascript The registered script handle you are attaching the data for.
	 * @return void
	 */
	public function moove_localize_script( $ascript ) {
		$this->activity_loc_data = array(
				'activityoptions'		=> 	get_option( 'moove_activity-options' ),
				'referer'				=> 	wp_get_referer(),
				'ajaxurl'				=>	admin_url( 'admin-ajax.php' ),
				'post_id'				=>	get_the_ID(),
				'is_page'				=>	is_page(),
				'is_single'				=>	is_single(),
				'current_user'			=>	get_current_user_id(),
				'referrer'				=>	esc_url( wp_get_referer() )
		);
		wp_localize_script( $ascript, 'moove_frontend_activity_scripts', $this->activity_loc_data );
	}

	/**
	 * Registe FRONT-END Javascripts and Styles
	 *
	 * @return void
	 */
	public function moove_frontend_activity_scripts() {
		wp_enqueue_script( 'moove_activity_frontend', plugins_url( basename( dirname( __FILE__ ) ) ) . '/assets/js/moove_activity_frontend.js', array( 'jquery' ), '1.0', true );
		wp_enqueue_style( 'moove_activity_frontend', plugins_url( basename( dirname( __FILE__ ) ) ) . '/assets/css/moove_activity_frontend.css' );
		$this->moove_localize_script( 'moove_activity_frontend' );
	}
	/**
	 * Registe BACK-END Javascripts and Styles
	 *
	 * @return void
	 */
	public function moove_activity_admin_scripts() {
		wp_enqueue_script( 'moove_activity_backend', plugins_url( basename( dirname( __FILE__ ) ) ) . '/assets/js/moove_activity_backend.js', array( 'jquery' ), '1.0', true );
		wp_enqueue_style( 'moove_activity_backend', plugins_url( basename( dirname( __FILE__ ) ) ) . '/assets/css/moove_activity_backend.css' );
	}
}
$moove_activity_actions_provider = new Moove_Activity_Actions();

