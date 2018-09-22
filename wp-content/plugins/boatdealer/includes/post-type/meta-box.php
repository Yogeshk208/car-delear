<?php if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
$afields = array(
    array(
        'name' => 'Featured',
        'desc' => __('Mark to show up at Featured Widget.', 'boatdealer'),
        'id' => 'boat-featured',
        'type' => 'checkbox'),
    array(
        'name' => 'Price',
        'desc' => __('No special characters here ("$" "," "."), the plugin will auto format the number.',
            'boatdealer'),
        'id' => 'boat-price',
        'type' => 'text',
        'default' => ''),
    array(
        'name' => 'Year',
        'desc' => __('The year of the product. Only numbers, no point, no comma.',
            'boatdealer'),
        'id' => 'boat-year',
        'type' => 'text',
        'default' => ''),
    array(
        'name' => get_option("BoatDealer_measure", "Miles"),
        'desc' => __('The amount of ' . get_option("BoatDealer_measure", "Miles") .
            ' on the engine. Only numbers, no point, no comma.', 'boatdealer'),
        'id' => 'boat-miles',
        'type' => 'text',
        'default' => ''),
    array(
        'name' => 'HP',
        'desc' => __('Engine HP. Only numbers, no point, no comma.', 'boatdealer'),
        'id' => 'boat-hp',
        'type' => 'text',
        'default' => ''),
	array(
				'name' => 'Condition',
				'desc' => __('The amount of miles on this boat . Only numbers, no point, no comma.', 'boatdealer'),
				'id' => 'boat-con',
				'type' => 'select',
				'options' => array (
				'New' => __('New',  'boatdealer'),
				'Used' => __('Used',  'boatdealer'),
				'Damaged' => __('Damaged',  'boatdealer'), 
				),
				'default' => ''
			),
            
    /*        
    array(
        'name' => 'Transmission',
        'desc' => __('What kind of Transmission is this', 'boatdealer'),
        'id' => 'transmission-type',
        'type' => 'select',
        'options' => array(
            'Automatic' => __('Automatic', 'boatdealer'),
            'Manual' => __('Manual', 'boatdealer'),
            'Tiptronic' => __('Tiptronic', 'boatdealer'))),
    */
    array(
        'name' => 'Fuel Type',
        'desc' => __('Fuel Type.', 'boatdealer'),
        'id' => 'boat-fuel',
        'type' => 'select',
        'options' => array(
            'Diesel' => __('Diesel', 'boatdealer'),
            'Gasoline' => __('Gasoline', 'boatdealer'),
            'Hybrid' => __('Hybrid', 'boatdealer'),
            'Eletric' => __('Electric', 'boatdealer'),
            'Biodiesel' => __('Biodiesel', 'boatdealer'),
            'CNG' => __('CNG', 'boatdealer'),
            'Ethanol' => __('Ethanol', 'boatdealer'),
            'Other' => __('Other', 'boatdealer'))));
$afieldsId = boatdealer_get_fields('all');
$totfields = count($afieldsId);
$ametadataoptions = array();
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
        $label = $ametadata[0];
    else
        $label = $ametadata[12];
    if ($ametadata[1] == 'checkbox') {
        $afields[] = array(
            'name' => $label,
            'desc' => ' ',
            'id' => 'boat-' . $ametadata[12],
            'type' => $ametadata[1],
            );
    } elseif ($ametadata[1] == 'text') {
       // if($ametadata[12] != 'body_color')
       //   $ametadata[12] = 'boat-'.$ametadata[12];
        $afields[] = array(
            'name' => $label,
            'desc' => ' ',
            'id' => 'boat-'.$ametadata[12],
            'type' => $ametadata[1],
            'default' => '');
    } elseif ($ametadata[1] == 'dropdown') {
        $arr = explode("\n", $ametadata[2]);
        $options = array();
        for ($z = 0; $z < count($arr); $z++) {
            // $options[$arr[$z]] = $arr[$z];
            $options[$z] = $arr[$z];
        }
        $afields[] = array(
            'name' => $label,
            'desc' => ' ',
            'id' => 'boat-' . $ametadata[12],
            'type' => 'select',
            'options' => $options,
            'default' => '');
    } elseif ($ametadata[1] == 'rangeselect') {
        $init = $ametadata[5];
        $max = $ametadata[6];
        $step = $ametadata[7];
        if (empty($init))
            $init = 0;
        $options = array();
        if (!empty($max) and !empty($step)) {
            for ($z = $init; $z <= $max; $z += $step) {
                $options[$z] = $z;
            }
        }
        $afields[] = array(
            'name' => $label,
            'desc' => ' ',
            'id' => 'boat-' . $ametadata[12],
            'type' => 'select',
            'options' => $options,
            'default' => '');
    } elseif ($ametadata[1] == 'rangeslider') {
        $init = $ametadata[8];
        $max = $ametadata[9];
        $step = $ametadata[10];
        $options = array();
        for ($z = $init; $z <= $max; $z += $step) {
            $options[$z] = $z;
        }
        $afields[] = array(
            'name' => $label,
            'desc' => ' ',
            'id' => 'boat-' . $ametadata[12],
            'type' => 'select',
            'options' => $options,
            'default' => '');
    } elseif ($ametadata[1] == 'rangeselect') {
        $init = $ametadata[5];
        $max = $ametadata[6];
        $step = $ametadata[7];
        $options = array();
        for ($z = $init; $z <= $max; $z += $step) {
            $options[$z] = $z;
        }
    }
}
$meta_box['boats'] = array(
    'id' => 'listing-details',
    'title' => __('Details', 'boatdealer'),
    'context' => 'normal',
    'priority' => 'high',
    'fields' => $afields);
add_action('admin_menu', 'boatdealer_listing_add_box');
update_option('meta_boxes', $meta_box);
function boatdealer_listing_add_box()
{
    global $meta_box;
    foreach ($meta_box as $post_type => $value) {
        add_meta_box($value['id'], $value['title'], 'boatdealer_listing_format_box', $post_type,
            $value['context'], $value['priority']);
    }
}
function boatdealer_listing_format_box()
{
    global $meta_box, $post;
    wp_enqueue_style('meta', '/wp-content/plugins/boatdealer/includes/post-type/meta.css'); 

    echo '<input type="hidden" name="listing_meta_box_nonce" value="',
    
    wp_create_nonce(basename(__file__)), '" />';
    
    foreach ($meta_box[$post->post_type]['fields'] as $field) {
        $meta = get_post_meta($post->ID, $field['id'], true);
        $title = $field['name'];
        switch ($field['type']) {
            case 'text':
                echo '<div class="boxes-small">';
                echo '<div class="box-label"><label for="' . $field['id'] . '">' . $title =
                    str_replace("_", " ", $title) . '</label></div>';
                echo '<div class="box-content"><p>';
                echo '<input type="text" name="' . $field['id'] . '" class="' . $field['name'] .
                    '" id="' . $field['id'] . '" value="' . ($meta ? $meta : $field['default']) .
                    '" size="30" style="width:97%" />' . '<br />' . $field['desc'];
                echo '</div></div>';
                break;
            case 'select':
                echo '<div class="boxes-small">' . '<div class="box-label"><label for="' . $field['id'] .
                    '">' . $title = str_replace("_", " ", $title) . '</label></div>' .
                    '<div class="box-content"><p>';
                echo '<select name="' . $field['id'] . '" id="' . $field['id'] . '" class="' . $field['name'] .
                    '">';
                foreach ($field['options'] as $option100) {
                    echo '<option ' . ($meta == $option100 ? ' selected="selected"' : '') . '>' . $option100 .
                        '</option>';
                }
                echo '</select>';
                echo '<br />';
                echo $field['desc'];
                echo '</div></div>';
                break;
            case 'checkbox':
                echo '<div class="boxes-small">' . '<div class="box-label"><label for="' . $field['id'] .
                    '">' . $title = str_replace("_", " ", $title) . '</label></div>' .
                    '<div class="box-content"><p>';
                echo '<div class = "checkboxSlide">';
                echo '<input type="checkbox" class="' . $field['name'] .
                    '" value="enabled" name="' . $field['id'] . '" id="CheckboxSlide"' . ($meta ?
                    ' checked="checked"' : '') . '<br />' . $field['desc'];
                echo '</div>';
                echo '</div></div>';
                break;
        } // end Switch
        //   echo '</div></div>';
    }
//     echo '<div class="clear"> </div></div>';

} // end function listing_format_box
add_action('save_post', 'BoatDealer_listing_save_data');
function BoatDealer_listing_save_data($post_id)
{
    global $current_post_id, $meta_box, $post, $aboatdealer_features;
    $current_post_id = $post_id;
    if (!is_object($post))
        return;
    if (!isset($meta_box[$post->post_type]['fields'])) {
        return;
    }
    //Verify nonce
    if (isset($_POST['listing_meta_box_nonce'])) {
        if (!wp_verify_nonce($_POST['listing_meta_box_nonce'], basename(__file__))) {
            return $post_id;
        }
    }
    //Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    //Check permissions
    if (isset($_POST['post_type'])) {
        if ('page' == sanitize_text_field($_POST['post_type'])) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
    } else
        return;
    foreach ($meta_box[$post->post_type]['fields'] as $field)
     {
        $old = get_post_meta($post_id, $field['id'], true);
        if (isset($_POST[$field['id']])) {
            $new = sanitize_text_field($_POST[$field['id']]);
        } else {
            $new = '';
        }
        if($field['id'] == 'boat-price' )
          { 
            if($new == '')
              $new = '0';    
         }
        if($field['id'] != 'boat-price' )
        {
            if ($new && $new != $old) {
                update_post_meta($post_id, $field['id'], $new);
            } elseif ('' == $new && $old) {
                delete_post_meta($post_id, $field['id'], $old);
            }
        } 
         else
           update_post_meta($post_id, $field['id'], $new);
        //  }
    } // end loop
    //Save Features
    $boatdealer_features = trim(get_option( 'boatdealer_fieldfeatures' ));
    if(empty($boatdealer_features))
      return;
    $aboatdealer_features = explode(PHP_EOL, $boatdealer_features);
    $qnew = count($aboatdealer_features);
    for($i=0; $i < $qnew; $i++)
    {
        $field_name =  trim($aboatdealer_features[$i]);
        $field_name = str_replace(' ','_',$field_name);
        $field_id = 'car_'.$field_name;
        $old = get_post_meta($post_id, $field_id, true); 
        $new = sanitize_text_field($_POST[$field_id]);
        if ($new && $new != $old) {
            update_post_meta($post_id, $field_id, $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field_id, $old);
        }  
    }  // end fornext
} // end Function Save Data