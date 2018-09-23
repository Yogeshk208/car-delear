<?php 
/**
 * @author Bill Minozzi
 * @copyright 2017
 */
namespace boatdealer\WP\Settings;
// http://autosellerplugin.com/wp-admin/tools.php?page=md_settings1
// $mypage = new Page('Settings', array('type' => 'submenu2', 'parent_slug' =>'admin.php?page=real_estate_plugin'));
// $mypage = new Page('md_settings', array('type' => 'submenu', 'parent_slug' =>'tools.php'));
  $mypage = new Page('boatdealer_settings', array('type' => 'submenu2', 'parent_slug' =>'real_estate_plugin'));
 // $mypage = new Page('md_settings', array('type' => 'menu'));
$msg = 'This is a scction 1 ... ';
$settings = array();
//$settings['Mutidealer Settings']['Mutidealer Settings'] = array('info' => $msg );
$fields = array();
$settings['Boat Settings']['Boat Settings'] = array('info' => __('Choose your currency, metric system and so on.','boatdealer'));
$fields = array();
$fields[] = array(
	'type' 	=> 'select',
	'name' 	=> 'BoatDealercurrency',
	'label' => __('Currency', 'boatdealer'),
	'select_options' => array(
		array('value'=>'Dollar', 'label' => 'Dollar'),
		array('value'=>'Euro', 'label' => 'Euro'),
		array('value'=>'AUD', 'label' => 'Australian Dollar'),
		array('value'=>'Forint', 'label' => 'Forint'),        
        array('value'=>'Krone', 'label' => 'Danish Krone'),
		array('value'=>'Pound', 'label' => 'Pound'),
		array('value'=>'Real', 'label' => 'Brazil Real'),
		array('value'=>'Swiss', 'label' => 'Swiss Franc'),
		array('value'=>'Yen', 'label' => 'Yen'),
		array('value'=>'Zar', 'label' => 'Zar'),        
		array('value'=>'Universal', 'label' => 'Universal')     
		)			
	);
    $fields[] = array(
	'type' 	=> 'select',
	'name' 	=> 'BoatDealer_measure',
	'label' => __('Miles - Km - Hours','boatdealer'),
	'select_options' => array(
		array('value'=>'Miles', 'label' => __('Miles', 'boatdealer')),
		array('value'=>'Kms', 'label' => __('Kms', 'boatdealer')),
		array('value'=>'Hours', 'label' => __('Hours', 'boatdealer'))
		)			
	);
    $fields[] = array(
	'type' 	=> 'select',
	'name' 	=> 'BoatDealer_liter',
	'label' => __('Liters - Gallons','boatdealer'),
	'select_options' => array(
		array('value'=>'Liters', 'label' => __('Liters', 'boatdealer')),
		array('value'=>'Gallons', 'label' => __('Gallons', 'boatdealer')),
		)			
	);
    $fields[] = array(
	'type' 	=> 'select',
	'name' 	=> 'BoatDealer_lenght',
	'label' => __('Feet - Meters','boatdealer'),
	'select_options' => array(
		array('value'=>'Feet', 'label' => __('Feet', 'boatdealer')),
		array('value'=>'Meters', 'label' => __('Meters', 'boatdealer') ),
		)			
	);
	$fields[] =	array(
            	'type' 	=> 'select',
				'name' => 'BoatDealer_quantity',
				'label' => __('How many boats would you like to display per page?', 'boatdealer'),
				'select_options' => array (
                		array('value'=>'3', 'label' => '3'),
	                	array('value'=>'6', 'label' => '6'),
                		array('value'=>'9', 'label' => '9'),
                        array('value'=>'12', 'label' => '12'),
                        array('value'=>'15', 'label' => '15'),
                        array('value'=>'18', 'label' => '18'),
	         	)
 	); 
/*
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'sidebar_search_page_result',
	'label' => __('Use dedicated Search Results Page').'?',
	'radio_options' => array(
		array('value'=>'yes', 'label' => 'Yes'),
		array('value'=>'no', 'label' => 'No'),
		)			
	);
*/
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'sidebar_search_page_result',
	'label' => __('Remove Sidebar from Search Result Page','boatdealer').'?',
	'radio_options' => array(
		array('value'=>'yes', 'label' => 'Yes'),
		array('value'=>'no', 'label' => 'No'),
		)			
	);
 $fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'BoatDealer_overwrite_gallery',
	'label' => __('Replace the Wordpress Gallery with Flexslider Gallery','boatdealer').'?',
	'radio_options' => array(
		array('value'=>'yes', 'label' => 'Yes'),
		array('value'=>'no', 'label' => 'No'),
		)			
	);   
  $fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'BoatDealer_thumbs_format',
	'label' => __('Use thumbnails size 2:1 or 4:3 ?','boatdealer'),
	'radio_options' => array(
		array('value'=>'1', 'label' => '2 : 1'),
		array('value'=>'2', 'label' => '4 : 3'),
		)			
	);
  $fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'BoatDealer_enable_contact_form',
	'label' => __('Enable Contact Form in Single Product Page?','boatdealer'),
	'radio_options' => array(
		array('value'=>'yes', 'label' => 'Yes'),
		array('value'=>'no', 'label' => 'No'),
		)			
	);
$fields[] = array(
	'type' 	=> 'text',
	'name' 	=> 'BoatDealer_recipientEmail',
	'label' => __('Fill out your contact email to receive email from your Contact Form at bottom of the individual boat page.' ,'boatdealer')
    );   
 $fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'BoatDealer_template_gallery',
	'label' => __('In Show Room Page, use Gallery or List View Template','boatdealer').'?',
	'radio_options' => array(
		array('value'=>'yes', 'label' => 'Gallery'),
		array('value'=>'no', 'label' => 'List View'),
		)			
	);  
$settings['Boat Settings']['Boat Settings']['fields'] = $fields;
$settings['Search']['Search'] = array('info' => __('Customize your Search Options. Choose the fields to show on the front end search bar.','boatdealer'));
$fields = array();
 $fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'BoatDealer_show_fuel',
	'label' => __('Show the Fuel type control','boatdealer').'?',
	'radio_options' => array(
		array('value'=>'yes', 'label' => 'Yes'),
		array('value'=>'no', 'label' => 'No'),
		)			
	); 
 $fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'BoatDealer_show_year',
	'label' => __('Show the Year control','boatdealer').'?',
	'radio_options' => array(
		array('value'=>'yes', 'label' => 'Yes'),
		array('value'=>'no', 'label' => 'No'),
		)			
	);
 $fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'BoatDealer_show_price',
	'label' => __('Show the Price slider','boatdealer').'?',
	'radio_options' => array(
		array('value'=>'yes', 'label' => 'Yes'),
		array('value'=>'no', 'label' => 'No'),
		)			
	);   
    $fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'BoatDealer_show_orderby',
	'label' => __('Show the Order By Control','boatdealer').'?',
	'radio_options' => array(
		array('value'=>'yes', 'label' => 'Yes'),
		array('value'=>'no', 'label' => 'No'),
		)			
	);
$settings['Search']['Search']['fields'] = $fields;
$settings['Widget']['Widget'] = array('info' => __('Customize your Search Widget Options. Choose the fields to show on the Search Widget.','boatdealer'));
$fields = array(); 
 $fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'BoatDealer_widget_show_fuel',
	'label' => __('Show the Fuel type control','boatdealer').'?',
	'radio_options' => array(
		array('value'=>'yes', 'label' => 'Yes'),
		array('value'=>'no', 'label' => 'No'),
		)			
	); 
 $fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'BoatDealer_widget_show_year',
	'label' => __('Show the Year control','boatdealer').'?',
	'radio_options' => array(
		array('value'=>'yes', 'label' => 'Yes'),
		array('value'=>'no', 'label' => 'No'),
		)			
	);
 $fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'BoatDealer_widget_show_price',
	'label' => __('Show the Price control','boatdealer').'?',
	'radio_options' => array(
  		array('value'=>'yes', 'label' => 'Yes '),
		array('value'=>'no', 'label' => 'No'),
		)			
	);   
 $fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'BoatDealer_widget_show_orderby',
	'label' => __('Show the Order By Control','boatdealer').'?',
	'radio_options' => array(
		array('value'=>'yes', 'label' => 'Yes'),
		array('value'=>'no', 'label' => 'No'),
		)			
	);  
$settings['Widget']['Widget']['fields'] = $fields;
new OptionPageBuilderTabbed($mypage, $settings);