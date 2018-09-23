<?php 
/**
 * @author William Sergio Minozzi
 * @copyright 2017
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
define('BOATDEALERHOMEURL',admin_url());
$urlfields = BOATDEALERHOMEURL."/edit.php?post_type=boatdealerfields";
$urlboats = BOATDEALERHOMEURL."/edit.php?post_type=boats";
$urlmodels = BOATDEALERHOMEURL."/edit-tags.php?taxonomy=model&post_type=boats";
$urlfeatures =  BOATDEALERHOMEURL."/edit-tags.php?taxonomy=features&post_type=boats";
$urlsettings = BOATDEALERHOMEURL."/options.php?page=boatdealer_settings";
$urlteam =  BOATDEALERHOMEURL."/edit-tags.php?taxonomy=team&post_type=cars";
// ob_start();
add_action( 'admin_init', 'boatdealer_plugin_settings_init' );
add_action( 'admin_menu', 'boatdealer_plugin_add_admin_menu' );
function boatdealer_plugin_enqueue_scripts() {
      	wp_enqueue_style( 'bill-help' , BOATDEALERPLUGINURL.'/dashboard/css/help.css');
}
add_action('admin_init', 'boatdealer_plugin_enqueue_scripts');
function boatdealer_fields_callback() {
    global $urlfields;
    ?>
    <script type="text/javascript">
    <!--
     window.location  = "<?php echo $urlfields;?>";
    -->
    </script>
<?php
}
 function boatdealer_boats_callback() {
    Global $urlboats;
    ?>
    <script type="text/javascript">
    <!--
      window.location  = "<?php echo $urlboats;?>";
    -->
</script>
<?php
}
function boatdealer_models_callback() {
    Global $urlmodels;
    ?>
    <script type="text/javascript">
    <!--
     window.location  = "<?php echo $urlmodels;?>";
    -->
</script>
<?php
 }
function boatdealer_features_callback() {
    Global $urlfeatures;
    ?>
    <script type="text/javascript">
    <!--
     window.location  = "<?php echo $urlfeatures;?>";
    -->
</script>
<?php
 }
function boatdealer_team_callback() {
    global $urlteam;
    ?>
    <script type="text/javascript">
    <!--
     window.location  = "<?php echo $urlteam;?>";
    -->
</script>
<?php
 }
function boatdealer_locations_callback() {
    Global $urllocations;
    ?>
    <script type="text/javascript">
    <!--
     window.location  = "<?php echo $urlfeatures;?>";
    -->
</script>
<?php
 }
function boatdealer_settings_callback() {
        global $urlsettings;
    ?>
    <script type="text/javascript">
    <!--
     window.location  = "<?php echo $urlsettings;?>";
    -->
</script>
<?php
 }
function boatdealer_plugin_add_admin_menu(  ) {
 //   global $vmtheme_hook;
 //   $vmtheme_hook = add_theme_page( 'For Dummies', 'For Dummies Help', 'manage_options', 'for_dummies', 'boatdealer_options_page' );
 //   add_action('load-'.$vmtheme_hook, 'vmtheme_contextual_help');     
    Global $menu;
    add_menu_page(
    'Boat Dealer', 
    'Boat Dealer', 
    'manage_options', 
    'boat_dealer_plugin',
    'boatdealer_plugin_options_page', 
    BOATDEALERPLUGINURL.'assets/images/ancora-ico.png' , 
    '30' );
 include_once(ABSPATH . 'wp-includes/pluggable.php');
$link_our_new_CPT = urlencode('edit.php?post_type=boatdealerfields');
   add_submenu_page('boat_dealer_plugin', 'Fields Table', 'Fields Table', 'manage_options', 'fields-table', 'boatdealer_fields_callback');
   add_submenu_page('boat_dealer_plugin', 'Boats table', 'Boats table', 'manage_options', 'boats-table', 'boatdealer_boats_callback');
   add_submenu_page('boat_dealer_plugin', 'Models', 'Models', 'manage_options', 'md-makes', 'boatdealer_models_callback');
   add_submenu_page('boat_dealer_plugin', 'Features', 'Features', 'manage_options', 'md-locations', 'boatdealer_features_callback');
   add_submenu_page('boat_dealer_plugin', 'Team', 'Team', 'manage_options', 'md-team', 'boatdealer_team_callback');
   add_submenu_page('boat_dealer_plugin', 'Settings', 'Settings', 'manage_options', 'md-settings', 'boatdealer_settings_callback');
}
function boatdealer_plugin_settings_init(  ) { 
	register_setting( 'boatdealer', 'boatdealer_plugin_settings' );
}
function boatdealer_plugin_options_page(  ) { 
    global $activated, $boatdealer_update_theme;
            $wpversion = get_bloginfo('version');
            $current_user = wp_get_current_user();
            $plugin = plugin_basename(__FILE__); 
            $email = $current_user->user_email;
            $username =  trim($current_user->user_firstname);
            $user = $current_user->user_login;
            $user_display = trim($current_user->display_name);
            if(empty($username))
               $username = $user;
            if(empty($username))
               $username = $user_display;
            $theme = wp_get_theme( );
            $themeversion = $theme->version ; 
  ?>
    <!-- Begin Page -->
<div id = "boatdealer-theme-help-wrapper">   
     <div id="boatdealer-not-activated"></div>
     <div id="boatdealer-logo">
       <img alt="logo" src="<?php echo BOATDEALERIMAGES;?>logosmall.png" />
     </div>
     <div id="boatdealer_help_title">
         Help and Support Page
     </div> 
 <?php
if( isset( $_GET[ 'tab' ] ) ) 
    $active_tab = sanitize_text_field($_GET[ 'tab' ]);
else
   $active_tab = 'dashboard';
?>
    <h2 class="nav-tab-wrapper">
    <a href="?page=boat_dealer_plugin&tab=memory&tab=dashboard" class="nav-tab">Dashboard</a>
    <a href="?page=boat_dealer_plugin&tab=memory" class="nav-tab">Memory Check Up</a>
    </h2>
<?php  
if($active_tab == 'memory') {     
   require_once (BOATDEALERPLUGINPATH . 'dashboard/memory.php');
} 
else
{ 
    require_once (BOATDEALERPLUGINPATH . 'dashboard/dashboard.php');
}
 echo '</div> <!-- "boatdealer-theme_help-wrapper"> -->';
} // end Function boatdealer_options_page
     require_once(ABSPATH . 'wp-admin/includes/screen.php');
// ob_end_clean();
 include_once(ABSPATH . 'wp-includes/pluggable.php');
?>