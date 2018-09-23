<?php /**

 * @author Bill Minozzi

 * @copyright 2017

 */

if (!defined('ABSPATH'))

    exit; // Exit if accessed directly



    Function boatdealer_reorder_terms()

{

     global $wpdb;   

     $args = array(

      'taxonomy' => 'team',

      'hide_empty' => false,

     );

     $terms = get_terms($args); 

     $qteam = count($terms);

     $Cardealerteam = array();

     if ($qteam > 0)

     {

       $i = 0;

       foreach ( $terms as $term ) 

       {

              $id = $term->term_id;

              $termMeta = get_option( 'team_' . $id );

              $Cardealerteam[$i]['name'] =  $term->name;

              $Cardealerteam[$i]['description'] =  $term->description;

              $Cardealerteam[$i]['image'] = $termMeta['image'];      

              $Cardealerteam[$i]['function'] = $termMeta['function'];

              $Cardealerteam[$i]['phone'] = $termMeta['phone'];

              $Cardealerteam[$i]['email'] = $termMeta['email'];

              $Cardealerteam[$i]['skype'] = $termMeta['skype'];

              $Cardealerteam[$i]['facebook'] = $termMeta['facebook'];

              $Cardealerteam[$i]['twitter'] = $termMeta['twitter'];

              $Cardealerteam[$i]['linkedin'] = $termMeta['linkedin'];

              $Cardealerteam[$i]['vimeo'] = $termMeta['vimeo'];

              $Cardealerteam[$i]['instagram'] = $termMeta['instagram'];

              $Cardealerteam[$i]['youtube'] = $termMeta['youtube'];         

              $Cardealerteam[$i]['myorder'] = $termMeta['myorder'];         

              $i ++;

       } 

        function cmp($a, $b)

        {

            return strcmp($a["myorder"], $b["myorder"]);

        }

        if ($i > 1)

          usort($Cardealerteam, "cmp");

    }

    Return $Cardealerteam;

}

    

function boatdealer_message_low_memory()

{

    echo '<div class="notice notice-warning">

                     <br />

                     <b>

                     Boat Dealer Plugin Warning: Your server running Low Memory !

                     <br />

                     Please, check 

                     <br />

                     Dashboard => Boat Dealer => (tab) Memory Checkup

                     <br /><br />

                     </b>

                     </div>';

}

Function boatdealer_check_memory()

{

      global $boatdealer_memory;

      $boatdealer_memory['limit'] = (int) ini_get('memory_limit') ;	

      $boatdealer_memory['usage'] = function_exists('memory_get_usage') ? round(memory_get_usage() / 1024 / 1024, 0) : 0;

      if(!defined("WP_MEMORY_LIMIT"))

      {

        $boatdealer_memory['msg_type'] = 'notok';  

        return;

      }

      $boatdealer_memory['wp_limit'] =  trim(WP_MEMORY_LIMIT) ;

      

    if ($boatdealer_memory['wp_limit'] > 9999999)

        $boatdealer_memory['wp_limit'] = ($boatdealer_memory['wp_limit'] / 1024) / 1024;

    if (!is_numeric($boatdealer_memory['usage'])) {

        $boatdealer_memory['msg_type'] = 'notok';  

        return;

    }

    if (!is_numeric($boatdealer_memory['limit'])) {

        $boatdealer_memory['msg_type'] = 'notok';  

        return;

    }

    if ($boatdealer_memory['usage'] < 1) {

        $boatdealer_memory['msg_type'] = 'notok';  

        return;

    }

  $wplimit = $boatdealer_memory['wp_limit'];  

  $wplimit = substr($wplimit,0,strlen($wplimit)-1);

  $boatdealer_memory['wp_limit'] = $wplimit;

  $boatdealer_memory['percent'] = $boatdealer_memory['usage'] / $boatdealer_memory['wp_limit'];

  $boatdealer_memory['color'] = 'font-weight:normal;';

  if ($boatdealer_memory['percent'] > .7) $boatdealer_memory['color'] = 'font-weight:bold;color:#E66F00';

  if ($boatdealer_memory['percent'] > .85) $boatdealer_memory['color'] = 'font-weight:bold;color:red';

  $boatdealer_memory['msg_type'] = 'ok';  

  return $boatdealer_memory;

}





function boatdealer_howmanyboats()

 {

  global $wpdb;  

  $posts = get_posts(array(

	'post_type'			=> 'boats'

  ));

  $number = 0;

  if( $posts )

    {

	 foreach( $posts as $post )

     {

       $number ++; 

     } 

   }

   return $number;

}

if (!defined('ABSPATH'))

    exit; // Exit if accessed directly

function boatdealer_update_files()

{

    global $wpdb;

    $args = array('post_type' => 'boats');

    $posts = get_posts($args);

    foreach ($posts as $post) {

        $post_id = $post->ID;

        $value = get_post_meta($post_id, 'LOA-mpg', true);

         if (!empty($value))

             update_post_meta( $post_id, 'boat-loa', $value );

    }

}

function boatdealer_check_field_exist($field_id)

{

    $afieldsId = boatdealer_get_fields('all');

    $totfields = count($afieldsId);

    $ametadataoptions = array();

    for ($i = 0; $i < $totfields; $i++) {

        $post_id = $afieldsId[$i];

        $ametadata = boatdealer_get_meta($post_id);

        if (trim($ametadata[12]) == trim($field_id))

            return true;

    }

    return false;

}

function boatdealer_add_new_field($fields, $fieldsv)

{

    $mypost = array(

        'post_title' => sanitize_text_field($fieldsv[12]),

        'post_type' => 'boatdealerfields',

        'post_status' => 'publish',

        );

    $post_id = wp_insert_post($mypost);

    $tot = count($fields);

    for ($i = 0; $i < ($tot) - 1; $i++) {

        $meta_key = $fields[$i];

        $meta_value = trim($fieldsv[$i]);

        update_post_meta($post_id, $meta_key, $meta_value);

    }

}

function boatdealer_add_default_fields()

{

    $fields = array(

        'field-label',

        'field-typefield',

        'field-drop_options',

        'field-searchbar',

        'field-searchwidget',

        'field-rangemin',

        'field-rangemax',

        'field-rangestep',

        'field-slidemin',

        'field-slidemax',

        'field-slidestep',

        'field-order',

        'field-name');

        $atypes = array(

            "Canoe-Kayak",

            "Dinghy",

            "Engines",

            "Inflatable",

            "Power",

            "PWC",

            "Sail",

            "Other");

    $n = count($atypes);

    $bodytypes = '';

    for ($j = 0; $j < $n; $j++) {

        $bodytypes .= $atypes[$j];

        if (($j + 1) < $n)

            $bodytypes .= PHP_EOL;

    }

     $acondition = 'New';

    $acondition .= PHP_EOL;

    $acondition .= 'Used';

    $acondition .= PHP_EOL;

    $acondition .= 'Damaged';

    $allfields = array(

        $fieldsv = array(

            'Boat Type',

            'dropdown',

            $bodytypes,

            // '',

            '1',

            '1',

            '',

            '',

            '',

            '',

            '',

            '',

            '10',

            'type'),

        $fieldsv = array(

            'Fuel Capacity',

            'text',

            '',

            '0',

            '0',

            '',

            '',

            '',

            '',

            '',

            '',

            '11',

            'fuel-capacity'),

        $fieldsv = array(

            'Engine',

            'text',

            '',

            '0',

            '0',

            '',

            '',

            '', 

            '',

            '',

            '',

            '12',

            'engine'),

        $fieldsv = array(

            'Make',

            'text',

            '',

            '0',

            '0',

            '',

            '',

            '', 

            '',

            '',

            '',

            '13',

            'make'),

        $fieldsv = array(

        'Condition',

        'dropdown',

         $acondition,

        '1',

        '1',

        '',

        '',

        '',

        '',

        '',

        '',

        '14',

        'con'),    

        $fieldsv = array(

            'Engine',

            'text',

            '',

            '0',

            '0',

            '',

            '',

            '',

            '',

            '',

            '',

            '15',

            'engine'),

        $fieldsv = array(

            'Passenger Capacity',

            'text',

            '',

            '0',

            '0',

            '',

            '',

            '',

            '',

            '',

            '',

            '16',

            'capacity'),            

        $fieldsv = array(

            'Interior Color',

            'text',

            '',

            '0',

            '0',

            '',

            '',

            '',

            '',

            '',

            '',

            '17',

            'int'),

        $fieldsv = array(

            'Interior Material',

            'text',

            '',

            '0',

            '0',

            '',

            '',

            '',

            '',

            '',

            '',

            '18',

            'mat'),

        $fieldsv = array(

            'LOA',

            'text',

            '',

            '0',

            '0',

            '',

            '',

            '',

            '',

            '',

            '',

            '19',

            'loa'),            

        $fieldsv = array(

            'Hull Material',

            'text',

            '',

            '0',

            '0',

            '',

            '',

            '',

            '',

            '',

            '',

            '20',

            'hull')              

            );

    // end all fields

    $totnewfields = count($allfields);

    for ($i = 0; $i < $totnewfields; $i++) {

        if (!boatdealer_check_field_exist($allfields[$i][12])) {

            boatdealer_add_new_field($fields, $allfields[$i]);

        }

    }

}

function boatdealer_get_fields($type)

{

    global $wpdb;

    if (!function_exists('get_userdata()')) {

        include (ABSPATH . "/wp-includes/pluggable.php");

    }

    if ($type == 'search') {

        $args = array(

            'post_status' => 'publish',

            'post_type' => 'boatdealerfields',

            'meta_key' => 'field-order',

            'posts_per_page' => -1,

            'orderby' => 'meta_value_num',

            'order' => 'ASC',

            'meta_query' => array(array('key' => 'field-searchbar', 'value' => '1')));

    } elseif ($type == 'all') {

        $args = array(

            'post_status' => 'publish',

            'post_type' => 'boatdealerfields',

            'posts_per_page' => -1,

            'meta_key' => 'field-order',

            'orderby' => 'meta_value_num',

            'order' => 'ASC');

    } elseif ($type == 'widget') {

        $args = array(

            'post_status' => 'publish',

            'post_type' => 'boatdealerfields',

            'meta_key' => 'field-order',

            'posts_per_page' => -1,

            'orderby' => 'meta_value_num',

            'order' => 'ASC',

            'meta_query' => array(array('key' => 'field-searchwidget', 'value' => '1')));

    }

    query_posts($args);

    $afields = array();

    $afieldsid = array();

    while (have_posts()):

        the_post();

        $afieldsid[] = esc_attr(get_the_ID());

    endwhile;

    ob_start();

    wp_reset_query();

    ob_end_clean(); 

   // wp_reset_postdata(); 

    return $afieldsid;

} // end Function

function boatdealer_get_meta($post_id)

{

    $fields = array(

        'field-label',

        'field-typefield',

        'field-drop_options',

        'field-searchbar',

        'field-searchwidget',

        'field-rangemin',

        'field-rangemax',

        'field-rangestep',

        'field-slidemin',

        'field-slidemax',

        'field-slidestep',

        'field-order',

        'field-name');

    $tot = count($fields);

    for ($i = 0; $i < $tot; $i++) {

        $field_value[$i] = esc_attr(get_post_meta($post_id, $fields[$i], true));

    }

    $field_value[$tot - 1] = esc_attr(get_the_title($post_id));

    return $field_value;

}

function boatdealer_get_max()

{

    global $wpdb;

    $args = array(

        'numberposts' => 1,

        'post_type' => 'boats',

        'meta_key' => 'boat-price',

        'orderby' => 'meta_value_num',

        'order' => 'DESC');

    $posts = get_posts($args);

    foreach ($posts as $post) {

        $x = get_post_meta($post->ID, 'boat-price', true);

        if (!empty($x)) {

            $x = (int)$x;

            if (is_int($x)) {

                $x = ($x) * 1.2;

                $x = round($x, 0, PHP_ROUND_HALF_EVEN);

                //return $x;

            }

        }

        if ($x < 1)

            return '100000';

        else

            return $x;

    }

}

//add_action( 'wp_loaded', 'boatdealer_get_types' );

function boatdealer_currency()

{

    if (get_option('BoatDealercurrency') == 'Dollar') {

        return "$";

    }

    if (get_option('BoatDealercurrency') == 'Pound') {

        return "&pound;";

    }

    if (get_option('BoatDealercurrency') == 'Yen') {

        return "&yen;";

    }

    if (get_option('BoatDealercurrency') == 'Euro') {

        return "&euro;";

    }

    if (get_option('BoatDealercurrency') == 'Universal') {

        return "&curren;";

    }

    if (get_option('BoatDealercurrency') == 'AUD') {

        return "AUD";

    }

    if (get_option('BoatDealercurrency') == 'Real') {

        return "$R";

    }

    if (get_option('BoatDealercurrency') == 'Krone') {

        return "kr";

    } 

    if (get_option('BoatDealercurrency') == 'Forint') {

        return "Ft"; /* Ft or HUF is also perfect for me. */ 

    }

    if (get_option('BoatDealercurrency') == 'Swiss') {

        return "CHF "; 

    }

    // Afric Sul

    if (get_option('BoatDealercurrency') == 'Zar') {

        return "R"; /* Ft or HUF is also perfect for me. */ 

    } 

}

function BoatDealer_localization_init_fail()

{

    echo '<div class="error notice">

                     <br />

                     boatdealerPlugin: Could not load the localization file (Language file).

                     <br />

                     Please, take a look the online Guide item Plugin Setup => Language.

                     <br /><br />

                     </div>';

}

function BoatDealer_Show_Notices1()

{

    echo '<div class="update-nag notice"><br />';

    echo 'Warning: Upload directory not found (BoatDealer Plugin). Enable debug for more info.';

    echo '<br /><br /></div>';

}

function BoatDealer_plugin_was_activated()

{

    echo '<div class="updated"><p>';

    $bd_msg = '<img src="' . BOATDEALERPLUGINURL . 'assets/images/infox350.png" />';

    $bd_msg .= '<h2>BoatDealer Plugin was activated! </h2>';

    $bd_msg .= '<h3>For details and help, take a look at Boat Dealer Dashboard at your left menu <br />';

    $bd_url = '  <a class="button button-primary" href="admin.php?page=car_dealer_plugin">or click here</a>';

    $bd_msg .= $bd_url;

    echo $bd_msg;

    echo "</p></h3></div>";

    $Multidealerplugin_installed = trim(get_option('Multidealerplugin_installed', ''));

    if (empty($Multidealerplugin_installed)) {

        add_option('Multidealerplugin_installed', time());

        update_option('Multidealerplugin_installed', time());

    }

}

/*

if (is_admin()) {

    if (get_option('BoatDealer_activated', '0') == '1') {

        add_action('admin_notices', 'BoatDealer_plugin_was_activated');

        $r = update_option('BoatDealer_activated', '0');

        if (!$r)

            add_option('BoatDealer_activated', '0');

    }

}

*/

if (!function_exists('write_log')) {

    function write_log($log)

    {

        if (true === WP_DEBUG) {

            if (is_array($log) || is_object($log)) {

                error_log(print_r($log, true));

            } else {

                error_log($log);

            }

        }

    }

}

add_filter('plugin_row_meta', 'boatdealer_custom_plugin_row_meta', 10, 2);

function boatdealer_custom_plugin_row_meta($links, $file)

{

    if (strpos($file, 'boatdealer.php') !== false) {

        $new_links = array('OnLine Guide' =>

                '<a href="http://boatdealerplugin.com/guide/" target="_blank">OnLine Guide</a>',

                'Pro' => '<a href="http://boatdealerplugin.com/premium/" target="_blank"><b><font color="#FF6600">Go Pro</font></b></a>');

        $links = array_merge($links, $new_links);

    }

    return $links;

}

function boatdealer_get_page()

{

    $page = 1;

    $url = esc_url($_SERVER['REQUEST_URI']);

    $pieces = explode("/", $url);

    for ($i = 0; $i < count($pieces); $i++) {

        if ($pieces[$i] == 'page' and ($i + 1) < count($pieces)) {

            $page = $pieces[$i + 1];

            if (is_numeric($page))

                return $page;

        }

    }

    return $page;

}

function BoatDealer_wrong_permalink()

{

    echo '<div class="notice notice-warning">

                     <br />

                     Boat Dealer Plugin: Wrong Permalink settings !

                     <br />

                     Please, fix it to avoid 404 error page.

                     <br />

                     To correct, just follow this steps:

                     <br />

                     Dashboard => Settings => Permalinks => Post Name (check)

                     <br />  

                     Click Save Changes

                     <br /><br />

                     </div>';

}

$boatdealerurl = esc_url($_SERVER['REQUEST_URI']);

if (strpos($boatdealerurl, '/options-permalink.php') === false) {

    $permalinkopt = get_option('permalink_structure');

    if ($permalinkopt != '/%postname%/')

        add_action('admin_notices', 'BoatDealer_wrong_permalink');

}

function bill_ask_for_upgrade()

 { 

    $x = rand(0,1);

    if ($x == 0)

       $banner_image = BOATDEALERIMAGES.'/introductory.png';

    else

       $banner_image = BOATDEALERIMAGES.'/keys_from_left.png';
       
       
       
       
    $x = rand(0,4);
    if ($x == 0)
    {
       $banner_image = BOATDEALERIMAGES.'/introductory.png';
       $bill_banner_bkg_color = 'turquoise';
       $banner_txt = __( 'Extend standard plugin functionality with new great options.', 'boatdealer'); 
    }
    elseif ($x == 1)
    {
       $banner_image = BOATDEALERIMAGES.'/lion.jpg';  
       $bill_banner_bkg_color = 'turquoise';
       $banner_txt = __( 'Make Your Website Look More Professional.', 'boatdealer'); 
    }
       elseif ($x == 2)
     {
       $banner_image = BOATDEALERIMAGES.'/apple.jpg';
       $bill_banner_bkg_color = 'orange';
       $banner_txt = __( 'Extend standard plugin functionality with new great options.', 'boatdealer'); 
    } 
       elseif ($x == 3)
    {
       $banner_image = BOATDEALERIMAGES.'/racing.jpg';
       $bill_banner_bkg_color = 'orange';
      $banner_txt = __( 'Make Your Website Look More Professional.', 'boatdealer'); 
    }     
    else
    {
       $banner_image = BOATDEALERIMAGES.'/keys_from_left.png';
       $bill_banner_bkg_color = 'orange';
       $banner_txt = __( 'Make Your Website Look More Professional.', 'boatdealer'); 
    }
   $banner_tit = __( 'It is time to upgrade your', 'boatdealer');      
       
       
       
       
       
       
       
       
       
       

    echo '<script type="text/javascript" src="' .BOATDEALERPLUGINURL .

            'assets/js/c_o_o_k_i_e.js' . '"></script>';

    ?>

	<script type="text/javascript">

        jQuery(document).ready(function() {

        	var hide_message = jQuery.cookie('bill_go_pro_hide');

  /* hide_message = false; */ 

        	if (hide_message == "true") {

        		jQuery(".bill_go_pro_container").css("display", "none");

        	} else {

                   setTimeout( function(){ 
                   jQuery(".bill_go_pro_container").slideDown("slow");
                  }  , 2000 );

        	};

        	jQuery(".bill_go_pro_close_icon").click(function() {

        		jQuery(".bill_go_pro_message").css("display", "none");

        		jQuery.cookie("bill_go_pro_hide", "true", {

        			expires: 15

        		});

        		jQuery(".bill_go_pro_container").css("display", "none");

        	});

        	jQuery(".bill_go_pro_dismiss").click(function(event) {

        		jQuery(".bill_go_pro_message").css("display", "none");

        		jQuery.cookie("bill_go_pro_hide", "true", {

        			expires: 15

        		});

        		event.preventDefault()

        		jQuery(".bill_go_pro_container").css("display", "none");

        	});

        }); // end (jQuery);

	</script>

    <style type="text/css">

            .bill_go_pro_close_icon {

            width:31px;

            height:31px;

            border: 0px solid red;

            /* background: url("http://xxxxxx.com/wp-content/plugins/boatdealer/assets/images/close_banner.png") no-repeat center center; */

            box-shadow:none;

            float:right;

            margin:8px;

            margin:60px 40px 8px 8px;

            }

            .bill_hide_settings_notice:hover,.bill_hide_premium_options:hover {

            cursor:pointer;

            }

            .bill_hide_premium_options {

            position:relative;

            }

            .bill_go_pro_image {

            float:left;

            margin-right:20px;

            max-height:90px !important;

            }

            .bill_image_go_pro {

            max-width:200px;

            max-height:88px;

            }

            .bill_go_pro_text {

            font-size:18px;

            padding:10px;

            }

            .bill_go_pro_button_primary_container {

            float:left;

            margin-top: 0px;

            }

            .bill_go_pro_dismiss_container

            {

              margin-top: 0px;

            }

            .bill_go_pro_buttons {

              display: flex;

              max-height: 30px;

              margin-top: -10px;

            }        

            .bill_go_pro_container {

                border:1px solid darkgray;

                height:88px;

                padding: 0; 

                margin: 0; 

                background: <?php echo $bill_banner_bkg_color; ?>

            }

            .bill_go_pro_dismiss {

              margin-left:15px !important;

            }

             .button {

                vertical-align: top;

            }           

            @media screen and (max-width:900px) {

                .bill_go_pro_text {

                  font-size:16px;

                  padding:5px;

                  margin-bottom: 10px;

                }

            }

            @media screen and (max-width:800px) {

                .bill_go_pro_container {

                  display:none !important;

                }

            }

	</style>

    <div class="notice notice-success bill_go_pro_container" style="display: none;">

    	<div class="bill_go_pro_message bill_banner_on_plugin_page bill_go_pro_banner">

    		<button class="bill_go_pro_close_icon close_icon notice-dismiss bill_hide_settings_notice" title="<?php _e('Close notice',

    		'boatdealer'); ?>">

    		</button>

    		<div class="bill_go_pro_image">

    			<img class="bill_image_go_pro" title="" src="<?php echo $banner_image;?>" alt="" />

    		</div>

    		<div class="bill_go_pro_text">

    			<?php echo $banner_tit;?>

    				<strong>

    					BoatDealer Plugin

    				</strong>

    				<?php _e( 'to', 'boatdealer'); ?>

    					<strong>

    						Pro

    					</strong>

    					<?php _e( 'version!', 'boatdealer'); ?>

    						<br />

    						<span>

    						  	<?php echo $banner_txt;?>

    						</span>

    		</div>

            <div class="bill_go_pro_buttons">

        		<div class="bill_go_pro_button_primary_container">

        			<a class="button button-primary" target="_blank" href="http://boatdealerplugin.com/premium/"><?php _e('Learn More',

        			'boatdealer'); ?></a>

        		</div>

        		<div class="bill_go_pro_dismiss_container">

        			<a class="button button-secondary bill_go_pro_dismiss" target="_blank" href="http://boatdealerplugin.com/premium/"><?php _e('Dismiss',

        			'boatdealer'); ?></a>

        		</div>

            </div>

    	</div>

    </div>

<?php               

 } // end Bill ask for upgrade 

 $when_installed = get_option('bill_installed', '');

 $now = time();

 $delta = $now - $when_installed;


 $delta = 1000000000;
 

 if ($delta > (3600 * 24 * 8))

 {

    $boatdealerurl = esc_url($_SERVER['REQUEST_URI']);

    if (strpos($boatdealerurl, 'post_type=boats') !== false or strpos($boatdealerurl, 'post_type=boatdealerfields') !== false )

       if (strpos($boatdealerurl, 'settings') === false)

          add_action( 'admin_notices', 'bill_ask_for_upgrade' );

 }

 ob_start();

 $num_fields = count(boatdealer_get_fields('all'));

 $num_boats = boatdealer_howmanyboats();

 $updated_version =  trim(get_option('BoatDealer_updated', ''));

 if($num_fields < 8 and $num_boats > 0 )

{

    if($updated_version < 2)

    {

       $w = update_option('BoatDealer_updated', '2');

       if (!$w)

        add_option('BoatDealer_updated', '2');

       boatdealer_add_default_fields();   

    }

}

ob_end_clean();

ob_start();

   //boatdealer_add_default_fields();

   //boatdealer_update_files(); 

ob_end_clean();

function boatdealer_control_availablememory()

{

    $boatdealer_memory = boatdealer_check_memory();

    if ( $boatdealer_memory['msg_type'] == 'notok')

       return;

     if ($boatdealer_memory['percent'] > .7) 

      add_action( 'admin_notices', 'boatdealer_message_low_memory' ); 

} 



if (wp_get_theme() <> 'boatdealer')     

  add_action( 'wp_loaded', 'boatdealer_control_availablememory' );



?>