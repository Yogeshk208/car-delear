<?php /**
 * @author Bill Minozzi
 * @copyright 2017
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 if( is_admin()) {
    add_action('current_screen', 'boatdealer_this_screen');
    function boatdealer_this_screen()
    {
        require_once(ABSPATH . 'wp-admin/includes/screen.php');
        $current_screen = get_current_screen();
        //echo $current_screen->id;
        //die();
        //boat-dealer_page_md-locations
        if ($current_screen->id === "edit-boatdealerfields") {
            add_filter('contextual_help', 'BoatDealer_contextual_help_fields', 10, 3);
        }
        elseif ($current_screen->id === "boats") {
            add_filter('contextual_help', 'BoatDealer_contextual_help_boats', 10, 3);
        } 
        elseif ($current_screen->id === "edit-model") {
            add_filter('contextual_help', 'BoatDealer_contextual_help_models', 10, 3);
        }
             elseif ($current_screen->id === "edit-team") {
            add_filter('contextual_help', 'BoatDealer_contextual_help_agents', 10, 3);
        }
        elseif ($current_screen->id === "edit-features") {
            add_filter('contextual_help', 'BoatDealer_contextual_help_features', 10, 3);
        }
         elseif ($current_screen->id === "edit-locations") {
            add_filter('contextual_help', 'BoatDealer_contextual_help_locations', 10, 3);
        }
        elseif ($current_screen->id === "toplevel_page_boat_dealer_plugin" or  $current_screen->id === "admin_page_boatdealer_settings") {
            add_filter('contextual_help', 'BoatDealer_main_help', 10, 3);
        }             
        else {
            if (isset($_GET['page'])) {
                if (sanitize_text_field($_GET['page']) == 'boat_dealer_plugin') {
                    add_filter('contextual_help', 'BoatDealer_main_help', 10, 3);
                }
            }
        }
    }
}
function BoatDealer_main_help($contextual_help, $screen_id, $screen)
{
    $myhelp = '<br> The easiest way to manage, list and sell yours boats online.';
    $myhelp .= '<br />';
    $myhelp .= 'Follow the 3 steps in this main screen after install the plugin. <br />';
    $myhelp .= '<br />';
    $myhelp .= 'You will find Context Help in many screens.';
    $myhelp .= '<br />';
    $myhelp .= 'You can find also our complete OnLine Guide  <a href="http://boatdealerplugin.com/help/index.html" target="_self">here.</a>';

        
        
 $myhelpdemo = '<br />';
    $myhelpdemo .= 'If you want to import demo data, download the demo data from this link:';

 $myhelpdemo .= '<br />';
 
    $myhelpdemo .= 'http://boatdealerplugin.com/demo-data/download-demo.php';

    $myhelpdemo .= '<br /><br />';

    $myhelpdemo .= 'After download:';
 $myhelpdemo .= '<br />';
 
    $myhelpdemo .= '1. Log in to that site as an administrator. ';
$myhelpdemo .= '<br />';
    $myhelpdemo .= '2. Go to Tools: Import in the WordPress admin panel.'; 
$myhelpdemo .= '<br />';
    $myhelpdemo .= '3. Install the "WordPress" importer from the list.'; 
$myhelpdemo .= '<br />';
    $myhelpdemo .= '4. Activate & Run Importer.'; 
$myhelpdemo .= '<br />';
    $myhelpdemo .= '5. Upload the file downloaded using the form provided on that page.'; 
$myhelpdemo .= '<br />';
    $myhelpdemo .= '6. You will first be asked to map the authors in this export file to users'; 
$myhelpdemo .= '<br />';
    $myhelpdemo .= 'on the site. For each author, you may choose to map to an';
$myhelpdemo .= '<br />';
    $myhelpdemo .= 'existing user on the site or to create a new user. ';
$myhelpdemo .= '<br />';
    $myhelpdemo .= '7. WordPress will then import the demo data into you site.';
$myhelpdemo .= '<br />';   
    
    
    $screen->add_help_tab(array(
        'id' => 'BoatDealer-overview-tab',
        'title' => __('Overview', 'boatdealer'),
        'content' => '<p>' . $myhelp . '</p>',
        ));

    $screen->add_help_tab(array(
        'id' => 'import-demo',
        'title' => __('Import Demo Data', 'boatdealer'),
        'content' => '<p>' . $myhelpdemo . '</p>',
        ));        
        
        
        
        
        
        
    return $contextual_help;
} 
function BoatDealer_contextual_help_fields($contextual_help, $screen_id, $screen)
{
     $myhelp = 'In the FIELDS screen you can manage the main table fields.
    This fields will show up 
    in your main boats form management, search bar and search widget.
    <br />
    Each row represents one field.
    <br /> 
    For example:
    <br />
    <ul>
    <li>Hull Material</li>
    <li>Number Passengers</li> 
    <li>Interior Color</li>    
    <li>And So On</li>  
    </ul>
    <br />
    You don\'t need include this fields: 
    Boat Type, Make, Price, Year, Miles, Engine, Interior Color, HP, Loa, Fuel Type and Featured. 
     <br />    <br />   
    Technical WordPress guys call this of Metadata.
    <br />
    Don\'t create 2 fields with the same name.
    <br />
    <br />
    ';
     $myhelpAdd = 'To add fields in the table, click the button Add New. This can open the empty window to include your information:
     <br />
    <ul>
    <li>Field Name</li>
    <li>Field Label</li>
    <li>Field Order</li>
    <li>Show in Search Bar (your frontpage)</li>
    <li>Show in Search Widget (your frontpage)</li>  
    <li>Type of Field</li>    
    <li>And So On</li>  
    </ul>    
    In that screen, move the mouse pointer over each field to get help about that field.
    <br />
    Just fill out and click OK button.
    <br />      
     ';
    $myhelpTypes = 'You have available this types of fields (Control Types):
    <br />
    <ul>
    <li>Text (Used by text and numbers)</li>
    <li>CheckBox</li>
    <li>Drop Down (also called select box)</li> 
    <li>Range Select (you can define de value min, max and step)</li>    
    <!-- <li>Range Slider (you can define de value min, max and step)</li>  -->
    </ul>    
    <br />
    For more details about HTML input types, please, check this page:
<a href="https://www.w3schools.com/html/html_form_input_types.asp ">https://www.w3schools.com/html/html_form_input_types.asp 
</a>
   <br />
'; 
    $myhelpEdit = 'You can manage the table, i mean, Add, Edit and Trash Fields.
    <br />
    At the Add Fields and Edit Fields forms, put the mouse over each row and the menu show up. Then, click over Edit or Trash.
    <br />
    To know more about Edit Fields, please, check the Add Fields Form Option at this help menu.
     ';  
    $screen->add_help_tab(array(
        'id' => 'BoatDealer-overview-tab',
        'title' => __('Overview', 'boatdealer'),
        'content' => '<p>' . $myhelp . '</p>',
        ));
      $screen->add_help_tab(array(
        'id' => 'BoatDealer-field-types',
        'title' => __('Field Types', 'boatdealer'),
        'content' => '<p>' . $myhelpTypes . '</p>',
        ));   
     $screen->add_help_tab(array(
        'id' => 'BoatDealer-overview-add',
        'title' => __('Add Fields Form', 'boatdealer'),
        'content' => '<p>' . $myhelpAdd . '</p>',
        )); 
     $screen->add_help_tab(array(
        'id' => 'BoatDealer-field-edit',
        'title' => __('Edit and Trash Fields', 'boatdealer'),
        'content' => '<p>' . $myhelpEdit . '</p>',
        ));      
    return $contextual_help;
} 
function BoatDealer_contextual_help_boats($contextual_help, $screen_id, $screen)
{
    $myhelp = 'In the BOATS screen you can manage (include, edit or delete) items in your Boats table.
    This boats will show up in your site front page.
    <br />
    We suggest you take some time to complete your Field table before this step.
    <br />
    Dashboard => BoatDealer => Fields Table.
    <br />
    You will find some fields automatically included by the system (Price, Year, Miles, HP, Transmission type, Type, Fuel and Featured).
).
    Just add your boats in this table.
    <br />
    ';
     $myhelpAdd = 'To add fields in the table, click the button Add New. This can open the empty window to include your information:
     <br />
    <ul>
    <li>Field Name</li>
    <li>Field Label</li>
    <li>Field Order</li>
    <li>Show in Search Bar (your frontpage)</li>
    <li>Show in Search Widget (your frontpage)</li>  
    <li>Type of Field</li>    
    <li>And So On</li>  
    </ul>    
    In that screen, move the mouse pointer over each field to get help about that field.
    <br />
    Just fill out and click OK button.
    <br />      
     ';
    $myhelpAgents = 'Use the Team control it is optional. To add new members, go to:
    <br />
    Dashboard=> Boat Dealer => Team
    <br />
    <br />
';

 
    $myhelpFeatures = 'Use the Features control it is optional. 
    To add new features, go to:
    <br />
    Dashboard=> Boat Dealer => Features
    <br />
    Some examples:  
    <ul>
    <li>WC</li>
    <li>Barbecue</li>
    <li>And So On...</li> 
    </ul>    
    <br />
   <br />
';



    $myhelpModel = 'Use the Models control it is optional. 
    To add new Moldels, go to:
    <br />
    Dashboard=> Boat Dealer => Models
    <br />  
    Maybe you want add: 
    <ul>
    <li>Open</li>
    <li>Fishing</li>
    <li>And So On...</li> 
    </ul>    
    <br />
   <br />
';  
    $myhelpEdit = 'You can manage the table, i mean, Add, Edit and Trash Boats.
    <br />
    Use the Add New Buttom or to Edit, put the mouse over each row and the menu will show up. Then, click over Edit or Trash.
    <br />
     ';  
    $myhelpFeatured = 'You can add one main image to each boats. 
    In the Boats Form, click the button Set Featured Image at bottom right corner.
    <br />
    <br />
     '; 

/*
    $myhelpModel = 'You can add one main image to each boats. 
    In the Boats Form, click the button Set Featured Image at bottom right corner.
    <br />
    <br />
     '; 
*/     
     

    $myhelpEdit = 'You can manage the table, i mean, Add, Edit and Trash Boats.
    <br />
    Use the Add New Buttom or to Edit, put the mouse over each row and the menu will show up. Then, click over Edit or Trash.
    <br />
     ';  
    $myhelpFeatured = 'You can add one main image to each boat. 
    In the Boat Form, click the button Set Featured Image at bottom right corner.
    <br />
    <br />
     '; 
    $myhelpGallery = 'You can add many Images or one gallery for each boat.
    Just go to Boats Form and add the images (or the gallery) in the main description field (click the Add Media buttom). 
    <br />
    Use the default WordPress Gallery or our plugin will create automatically one nice slider gallery. To enable the plugin gallery, go to
<br />
Dashboard => Boat Dealer => Settings
<br />
and look for <em>Replace the Wordpress Gallery with Flexslider Gallery</em>?
<br />
Then, check Yes and Save Changes.
<br />

This images and gallery will be visible in single boat page.
<br />
Look <a href="http://boatdealerplugin.com/how-upload-images/">our demo</a> about how to upload and crop images easily (less than 2 minutes).
<br />
    To get more info about galleries, <a href="https://en.support.wordpress.com/gallery/" target="_blank">visit WordPress Help site.</a>.
    
    <br />
     ';     
    $screen->add_help_tab(array(
        'id' => 'BoatDealer-overview-tab',
        'title' => __('Overview', 'boatdealer'),
        'content' => '<p>' . $myhelp . '</p>',
        ));
      $screen->add_help_tab(array(
        'id' => 'BoatDealer-boats-team',
        'title' => __('Team', 'boatdealer'),
        'content' => '<p>' . $myhelpAgents . '</p>',
        )); 
        
     /*  
     $screen->add_help_tab(array(
        'id' => 'BoatDealer-boats-location',
        'title' => __('Location', 'boatdealer'),
        'content' => '<p>' . $myhelpLocation . '</p>',
        ));
     */ 
     $screen->add_help_tab(array(
        'id' => 'BoatDealer-boats-model',
        'title' => __('Models', 'boatdealer'),
        'content' => '<p>' . $myhelpModel . '</p>',
        )); 
     $screen->add_help_tab(array(
        'id' => 'BoatDealer-boats-features',
        'title' => __('Features', 'boatdealer'),
        'content' => '<p>' . $myhelpFeatures . '</p>',
        ));
     $screen->add_help_tab(array(
        'id' => 'BoatDealer-boats-edit',
        'title' => __('Edit and Trash Boats', 'boatdealer'),
        'content' => '<p>' . $myhelpEdit . '</p>',
        ));
     $screen->add_help_tab(array(
        'id' => 'BoatDealer-boats-featured',
        'title' => __('Featured Images', 'boatdealer'),
        'content' => '<p>' . $myhelpFeatured . '</p>',
        ));
     $screen->add_help_tab(array(
        'id' => 'BoatDealer-boats-gallery',
        'title' => __('Images and Gallery', 'boatdealer'),
        'content' => '<p>' . $myhelpGallery . '</p>',
        ));           
    return $contextual_help;
} 
function BoatDealer_contextual_help_agents($contextual_help, $screen_id, $screen)
{
    $myhelpAgents = 'Use the Team table it is optional. 
    <br />
';
    $screen->add_help_tab(array(
        'id' => 'BoatDealer-overview-tab',
        'title' => __('Overview', 'boatdealer'),
        'content' => '<p>' . $myhelpAgents . '</p>',
        ));
    return $contextual_help;
}
function BoatDealer_contextual_help_locations($contextual_help, $screen_id, $screen)
{
    $myhelpLocation = 'Use the Location table it is optional. Maybe you want use it if you have more than one location.
    <br />  
    If you are, for example, in Florida, maybe you want add: 
    <ul>
    <li>Fort Lauderdale</li>
    <li>Miami</li>
    <li>And So On...</li> 
    </ul>    
   <br />
    ';
    $screen->add_help_tab(array(
        'id' => 'BoatDealer-overview-tab',
        'title' => __('Overview', 'boatdealer'),
        'content' => '<p>' . $myhelpLocation . '</p>',
        ));
     return $contextual_help;
}
function BoatDealer_contextual_help_features($contextual_help, $screen_id, $screen)
{
    $myhelpFeatures = 'Use the Features table it is optional. 
    Maybe you want include, for example: 
    <ul>
    <li>WC</li>
    <li>Barbecue</li>
    <li>And So On...</li> 
    </ul>
   <br />
    ';
    $screen->add_help_tab(array(
        'id' => 'BoatDealer-overview-tab',
        'title' => __('Overview', 'boatdealer'),
        'content' => '<p>' . $myhelpFeatures . '</p>',
        ));
     return $contextual_help;
}
function BoatDealer_contextual_help_models($contextual_help, $screen_id, $screen)
{
    $myhelpModel = 'Use the Models table it is optional. 
    Maybe you want include, for example: 
    <ul>
    <li>Open</li>
    <li>Fishing</li>
    <li>And So On...</li> 
    </ul>
   <br />
    ';
    $screen->add_help_tab(array(
        'id' => 'BoatDealer-overview-tab',
        'title' => __('Overview', 'boatdealer'),
        'content' => '<p>' . $myhelpModel . '</p>',
        ));
     return $contextual_help;
}
?>