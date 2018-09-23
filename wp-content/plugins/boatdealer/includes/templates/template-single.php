<?php
/**
 * @author Bill Minozzi
 * @copyright 2017
 */
?> 
<script type="text/javascript">
function goBack() {
   window.history.back(); 
}
</script>
<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$my_theme =  strtolower(wp_get_theme());
if ($my_theme == 'twenty fourteen')
{
?>
<style type="text/css">
<!--
	.site::before {
    width: 0px !important;
}
-->
</style>
<?php 
}
define('SINGLE_TITLE', get_the_title() );
 get_header();
  ?>
	    <div id="container2"> 
        <?php 
        if(isset($_SERVER['HTTP_REFERER']))
         {?>
          <center>
          <button onclick="goBack()">
          <?php 
          echo __('Back', 'boatdealer');?> 
          </button>
          <br /><br />
          </center>
        <?php } ?>
            <div id="content2" role="main">
				<?php boatdealer_detail();
               $BoatDealer_enable_contact_form = trim(get_option('BoatDealer_enable_contact_form', 'yes'));
               if ($BoatDealer_enable_contact_form == 'yes')
               {               
                ?>
                 <br />
                 <center>
                 <button id="BoatDealer_cform">
                 <?php echo __('Contact Us', 'boatdealer'); ?>
                 </button>
                 </center>
                 <br />
			</div> 
            <?php 
            } 
            //
            //   $boatdealer_the_title = get_the_title();
               if ($BoatDealer_enable_contact_form == 'yes') 
               {
                   include_once (BOATDEALERPLUGINPATH . 'includes/contact-form/multi-contact-show-form.php');  
               }
         ?>  
		</div>
<?php 
        $registered_sidebars = wp_get_sidebars_widgets();
        foreach( $registered_sidebars as $sidebar_name => $sidebar_widgets ) {
        	unregister_sidebar( $sidebar_name );
        }
get_footer(); 
?>