<?php /**
 * @author Bill Minozzi
 * @copyright 2017
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_action('wp_enqueue_scripts', 'BoatDealerregister_slider');
function BoatDealerregister_slider()
{
    wp_register_script('search-slider', BOATDEALERPLUGINURL .
        'includes/search/search_slider.js', array('jquery'), null, true);
    wp_enqueue_script('search-slider');
}
function BoatDealer_search($is_show_room)
{
    global $postNumber, $wp, $post, $page_id, $meta_make, $meta_year;
    $my_title = __("Search", 'boatdealer');
    if ($is_show_room == '0') // widget
        {
        $searchlabel = 'search-label-widget';
        $selectboxmeta = 'select-box-meta-widget';
        $selectbox = 'select-box-widget';
        $inputbox = 'input-box-widget';
        $searchItem = 'searchItem-widget';
        $searchItem2 = 'searchItem2-widget';
        $BoatDealersubmitwrap = 'BoatDealer-submitBtn-widget';
        $BoatDealer_search_box = 'BoatDealer-search-box-widget';
        $current_page_url = esc_url(home_url() . '/BoatDealer_show_room_2/');
        $BoatDealer_search_type = 'search-widget';
        $afieldsId = boatdealer_get_fields('widget');
    } elseif ($is_show_room == '1') // pag
    {
        $searchlabel = 'search-label';
        $selectboxmeta = 'select-box-meta';
        $selectbox = 'select-box';
        $inputbox = 'input-box';
        $searchItem = 'searchItem';
        $searchItem2 = 'searchItem';
        $BoatDealersubmitwrap = 'BoatDealer-submitBtn';
        $BoatDealer_search_box = 'BoatDealer-search-box';
        $current_page_url = home_url(esc_url(add_query_arg(null, null)));
        $BoatDealer_search_type = 'page';
        $afieldsId = boatdealer_get_fields('search');
    } elseif ($is_show_room == '2') // search result
    {
        $searchlabel = 'search-label';
        $selectboxmeta = 'select-box-meta';
        $selectbox = 'select-box';
        $inputbox = 'input-box';
        $searchItem = 'searchItem';
        $searchItem2 = 'searchItem';
        $BoatDealersubmitwrap = 'BoatDealer-submitBtn';
        $BoatDealer_search_box = 'BoatDealer-search-box';
        $current_page_url = esc_url(home_url() . '/BoatDealer_show_room_2/');
        $BoatDealer_search_type = 'search-widget';
        $afieldsId = boatdealer_get_fields('search');
    }
      //  $showsubmit = false; 
        $totfields = count($afieldsId);
// print_r($afieldsId);
        $ametadataoptions = array();
        $output = '<div class="' . $BoatDealer_search_box . '">';
        $output .= '<div class="BoatDealer-search-cuore">';
        $output .= '<div class="BoatDealer-search-cuore-fields">';
        $output .= '<form method="get" id="searchform3" action="' . $current_page_url . '">';
        if (isset($page_id)) {
            if ($page_id <> '0') {
                $output .= '        <input type="hidden" name="page_id" value="' . $page_id .
                    '" />';
            }
        } 
    $showsubmit = false;
    // year
    if ((trim(get_option('BoatDealer_show_year', 'yes')) == 'yes' and $is_show_room !=
        0) or (trim(get_option('BoatDealer_widget_show_year', 'yes')) == 'yes' and $is_show_room ==
        0)) {
        $showsubmit = true;
        $output .= ' 
    					<div class="' . $searchItem2 . '">
    						<span class="' . $searchlabel . '">' . __('Year', 'boatdealer') .
            ':</span>';
        if ($is_show_room <> 0)
            $output .= '<div id="bdp_oneline"></div>';
        $output .= '           <select class="' . $selectboxmeta . '" name="meta_year">
    							<option value =""> ' . __('Any', 'boatdealer') . ' </option>';
        $_year = date("Y");
        $w = 50;
        for ($i = 0; $i <= $w; $i++) {
            $year = $_year - $i;
            $output .= '<option ';
            if ($meta_year == $year)
              $output .= 'selected="selected"';
            $output .= 'value ="';
            $output .= $year;
            $output .= '">';
            $output .= $year;
            $output .= '</option>';
        }
        $output .= '</select>
    					</div><!--end of item -->';
    }
    // Fuel
    if ((trim(get_option('BoatDealer_show_fuel', 'yes')) == 'yes' and $is_show_room !=
        0) or (trim(get_option('BoatDealer_widget_show_fuel', 'yes')) == 'yes' and $is_show_room ==
        0)) {
        $showsubmit = true;
        if (isset($_GET['meta_fuel']))
            $boatdealer_meta_fuel = sanitize_text_field($_GET['meta_fuel']);
        else
            $boatdealer_meta_fuel = '';
        $boatdealer_meta_fuel = sanitize_text_field($boatdealer_meta_fuel);
        $output .= ' <div class="' . $searchItem . '">
    						<span class="' . $searchlabel . '">' . __('Fuel', 'boatdealer') .
            ':</span>';
        if ($is_show_room <> 0)
            $output .= '<div id="bdp_oneline"></div>';
        $output .= '<select class="' . $selectboxmeta . '" name="meta_fuel">
    							<option ' . (($boatdealer_meta_fuel == '') ? 'selected="selected"' :
            '') . ' value =""> ' . __('Any', 'boatdealer') . ' </option>
    							<option ' . (($boatdealer_meta_fuel == 'Diesel') ?
            'selected="selected"' : '') . '  value ="Diesel"> ' . __('Diesel', 'boatdealer') .
            '</option>
    							<option ' . (($boatdealer_meta_fuel == 'Gasoline') ?
            'selected="selected"' : '') . '  value ="Gasoline"> ' . __('Gasoline',
            'boatdealer') . '</option>
    							<option ' . (($boatdealer_meta_fuel == 'Hybrid') ?
            'selected="selected"' : '') . '  value ="Hybrid"> ' . __('Hybrid', 'boatdealer') .
            '</option>
    							<option ' . (($boatdealer_meta_fuel == 'Electric') ?
            'selected="selected"' : '') . '  value ="Electric"> ' . __('Electric',
            'boatdealer') . '</option>
     							<option ' . (($boatdealer_meta_fuel == 'Biodiesel') ?
            'selected="selected"' : '') . '  value ="Biodiesel"> ' . __('Biodiesel',
            'boatdealer') . '</option>       
      							<option ' . (($boatdealer_meta_fuel == 'CNG') ?
            'selected="selected"' : '') . '  value ="CNG"> ' . __('CNG', 'boatdealer') .
            '</option>        
      							<option ' . (($boatdealer_meta_fuel == 'Ethanol') ?
            'selected="selected"' : '') . '  value ="Ethanol"> ' . __('Ethanol', 'boatdealer') .
            '</option>        
    							<option ' . (($boatdealer_meta_fuel == 'Other') ?
            'selected="selected"' : '') . '  value ="Other"> ' . __('Other', 'boatdealer') .
            '</option>
    						</select>  
    					</div>';
    }
    for ($i = 0; $i < $totfields; $i++) {
        $post_id = $afieldsId[$i];
        $ametadata = boatdealer_get_meta($post_id);
        $field_value = array(
            'field_label', // 0
            'field_typefield', // 1
            'field_drop_options', // 2
            'field_searchbar', // 3
            'field_searchwidget', //4
            'field_rangemin', // 5
            'field_rangemax', //6
            'field_rangestep', // 7
            'field_slidemin', // 8
            'field_slidemax', // 9
            'field_slidestep', // 10
            'field_order', // 11
            'field_name'); // 12
        if (!empty($ametadata[0]))
            $search_label = $ametadata[0];
        else
            $search_label = $ametadata[12];
        $search_name = $ametadata[12];
        $meta = 'meta_'.$ametadata[12];
        if (!isset($_GET[$search_name])) {
            $_GET[$search_name] = '';
        }
       if (isset($_GET[$meta]))
          $boatdealer_meta_con = trim(sanitize_text_field($_GET[$meta]));
       else
          $boatdealer_meta_con = ' '; 
        $typefield = $ametadata[1];
        // Dropdown
        if ($typefield == 'dropdown') {
            $showsubmit = true;
            $output .= '<div class="' . $searchItem . '">';
            $output .= '<span class="' . $searchlabel . '">' . $search_label . ':</span>';
            if ($is_show_room <> 0)
                $output .= '<div id="bdp_oneline"></div>';
            $output .= '<select class="' . $selectboxmeta . '" name="'.$meta.'">';
            $options = explode("\n", $ametadata[2]);
            $output .= '<option value="All">'. __('Any', 'boatdealer') .'</option>';
            foreach ($options as $option) {
                $output .= '<option ';
                if(trim($boatdealer_meta_con) == trim($option))
                  {
                    $output .= ' selected="selected" ';
                   }  
                $output .= '>' . $option . '</option>';
            }
            $output .= '</select>';
            $output .= '</div>'; // SearchItem;
        } // end Dropdown
        // Select Range
        if ($typefield == 'rangeselect') {
            $showsubmit = true;
            $output .= '<div class="' . $searchItem . '">';
            $output .= '<span class="' . $searchlabel . '">' . $search_label . ':</span>';
            if ($is_show_room <> 0)
                $output .= '<div id="bdp_oneline"></div>';
            $output .= '<select class="' . $selectboxmeta . '" name="'.$meta.'">';
            $init = $ametadata[5];
            $max = $ametadata[6];
            $step = $ametadata[7];
            $options = array();
            $output .= '<option value="All">'. __('Any', 'cardealer') .'</option>';
            for ($z = $init; $z <= $max; $z += $step) {
                $option = $z;
                $output .= '<option ' . ($boatdealer_meta_con == $option ?
                        ' selected="selected"' : '') . '>' . $option . '</option>';
            }
            $output .= '</select>';
            $output .= '</div>'; // SearchItem;
        } // end Dropdown       
         // Checkbox
        if ($typefield == 'checkbox') {
            $showsubmit = true;
            if (isset($_GET[$meta]))
                $boatdealer_meta_con = sanitize_text_field($_GET[$meta]);
            else
                $boatdealer_meta_con = ' ';
            $output .= '<div class="' . $searchItem . '">';
            $output .= '<span class="' . $searchlabel . '">' . $search_label . ':</span>';
            if ($is_show_room <> 0)
                $output .= '<div id="bdp_oneline"></div>';
            $output .= '<select class="' . $selectboxmeta .'" name="'.$meta.'">';
                $output .= '<option value = "All" ' . ($boatdealer_meta_con == 'All' ? ' selected="selected"' : '') . '>'. __('Any', 'cardealer') .'</option>';
                $output .= '<option value = "enabled" ' . ($boatdealer_meta_con == "enabled"  ? ' selected="selected"' : '') . '>Yes</option>';
                $output .= '<option value = "" ' . ($boatdealer_meta_con == '' ? ' selected="selected"' : '') . '>No</option>';
            $output .= '</select>';
            $output .= '</div>'; // SearchItem;
        } // end Checkbox
    } // end Loop 
     // Order by
    if ((trim(get_option('BoatDealer_show_orderby', 'yes')) == 'yes' and $is_show_room !=
        0) or (trim(get_option('BoatDealer_widget_show_orderby', 'yes')) == 'yes' and $is_show_room ==
        0)) {
        $showsubmit = true;
        $BoatDealer_measure = get_option('BoatDealer_measure', 'miles');
        if (isset($_GET['meta_order']))
            $boatdealer_meta_order = sanitize_text_field($_GET['meta_order']);
        else
            $boatdealer_meta_order = '';
        $boatdealer_meta_order = sanitize_text_field($boatdealer_meta_order);
        $output .= ' <div class="' . $searchItem . '">
    						<span class="' . $searchlabel . '">' . __('Order By', 'boatdealer') .
            ':</span>';
        if ($is_show_room <> 0)
            $output .= '<div id="bdp_oneline"></div>';
        $output .= '<select class="' . $selectboxmeta .
            '" name="meta_order" style="min-width: 120px;">
    							<option ' . (($boatdealer_meta_order == '') ? 'selected="selected"' :
            '') . ' value =""> ' . __('Any', 'boatdealer') . ' </option>
    							<option ' . (($boatdealer_meta_order == 'year_high') ?
            'selected="selected"' : '') . '  value ="year_high"> ' . __('Year newest first',
            'boatdealer') . '</option>
    							<option ' . (($boatdealer_meta_order == 'year_low') ?
            'selected="selected"' : '') . '  value ="year_low"> ' . __('Year oldest first',
            'boatdealer') . '</option>
    							<option ' . (($boatdealer_meta_order == 'price_high') ?
            'selected="selected"' : '') . '  value ="price_high"> ' . __('Price higher first',
            'boatdealer') . '</option>
    							<option ' . (($boatdealer_meta_order == 'price_low') ?
            'selected="selected"' : '') . '  value ="price_low"> ' . __('Price lower first',
            'boatdealer') . '</option>';
            if ($BoatDealer_measure == 'Hours')
            {
    		   $output .= '					<option ' . (($boatdealer_meta_order == 'hours_high') ?
               'selected="selected"' : '') . '  value ="hours_high"> ' . __('Hours higher first',
               'boatdealer') . '</option>
    							<option ' . (($boatdealer_meta_order == 'hours_low') ?
               'selected="selected"' : '') . '  value ="hours_low"> ' . __('Hours lower first','boatdealer')
               . '</option>';
            }
            else
           {
    		   $output .= '					<option ' . (($boatdealer_meta_order == 'mileage_high') ?
               'selected="selected"' : '') . '  value ="mileage_high"> ' . __('Mileage higher first',
               'boatdealer') . '</option>
    							<option ' . (($boatdealer_meta_order == 'mileage_low') ?
               'selected="selected"' : '') . '  value ="mileage_low"> ' . __('Mileage lower first','boatdealer')
               . '</option>';                
           }
    	   $output .= '</select>  
    					</div>';
    }   
    // Slider
    
    
    if($is_show_room == '0')
      $showslider =  trim(get_option('BoatDealer_widget_show_price', 'yes'));
    else
      $showslider =  trim(get_option('BoatDealer_show_price', 'yes'));
  
    
    if ($showslider == 'yes') {    
    
         $showsubmit = true;  
         $max_car_value = boatdealer_get_max();
        if ($is_show_room != '0') // no widget
           {
            $output .= '<div class="boatdealer-price-slider">';
            $output .= '<span class="boatdealerlabelprice">' . __('Price Range', 'boatdealer') . ':</span>';
            $output .= '<input type="text" name="meta_price" id="meta_price" readonly>';
            // slider
            if ($is_show_room == '1')
                $output .= '<div id="boatdealer_meta_price" class="boatdealerslider" ></div>';
            else
                $output .= '<div id="boatdealer_meta_price" class="boatdealerslider" style="margin-top:0px;" ></div>';
            $output .= '<input type="hidden" name="meta_price_max" id="meta_price_max" value="'.$max_car_value.'">';
            if(isset($_GET['meta_price']))
              $price = sanitize_text_field($_GET['meta_price']);
            else
              $price = '';
            $pos = strpos($price, '-');
            if ($pos === false)
                $price = '';
            else {
                $priceMin = trim(substr($price, 0, $pos - 1));
                $priceMax = trim(substr($price, $pos + 1));
                $output .= '<input type="hidden" name="choice_price_min" id="choice_price_min" value="' .
                    $priceMin . '">';
                $output .= '<input type="hidden" name="choice_price_max" id="choice_price_max" value="' .
                    $priceMax . '">';
            }
            $output .= '</div>';
         }  // show room != 0 
        if ($is_show_room == '0') // widget
           {
            $output .= '<div class="boatdealer-price-slider2">';
            $output .= '<span class="boatdealerlabelprice2">' . __('Price', 'boatdealer') . ':</span>';
            $output .= '<input type="text" name="meta_price2" id="meta_price2" readonly>';
                $output .= '<div id="boatdealer_meta_price2" class="boatdealerslider" "></div>';
            $output .= '<input type="hidden" name="meta_price_max2" id="meta_price_max2" value="'.$max_car_value.'">';
            if(isset($_GET['meta_price2']))
              $price = sanitize_text_field($_GET['meta_price2']);
            else
              $price = '';
            $pos = strpos($price, '-');
            if ($pos === false)
                $price = '';
            else {
                $priceMin = trim(substr($price, 0, $pos - 1));
                $priceMax = trim(substr($price, $pos + 1));
                $output .= '<input type="hidden" name="choice_price_min2" id="choice_price_min2" value="' .
                    $priceMin . '">';
                $output .= '<input type="hidden" name="choice_price_max2" id="choice_price_max2" value="' .
                    $priceMax . '">';
            }
            $output .= '</div>';
         }  // show room = 0 
    } // End Slider    
    // Submit
    if ($showsubmit) {
        $output .= '<div class="BoatDealer-submitBtnWrap">';
        $output .= '<input type="submit" name="submit" id="' . $BoatDealersubmitwrap .
            '" value=" ' . __('Search', 'boatdealer') . '" />';
        $output .= '</div>';
        $output .= '<input type="hidden" name="BoatDealer_post_type" value="boats" />';
        $output .= '<input type="hidden" name="postNumber" value="' . $postNumber .
            '" />';
        $output .= '<input type="hidden" name="BoatDealer_search_type" value="' . $BoatDealer_search_type .
            '" />';
    }
    $output .= '</form></div></div></div>  <!-- end of Basic -->';
     return $output;
} ?>