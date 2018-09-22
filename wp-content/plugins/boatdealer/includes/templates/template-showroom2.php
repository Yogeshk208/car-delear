<?php /**

 * @author Bill Minozzi

 * @copyright 2017

 */

if (!defined('ABSPATH'))

    exit; // Exit if accessed directly

 ?>

<style type="text/css">

<!-- 

<?php if (get_option('sidebar_search_page_result', 'no') == 'yes') { ?>

    #secondary, .sidebar-container

    {

        display: none !important; 

    }

<?php } ?>

#main

{  width: 100%!important;

   position:  absolute;}

-->

</style>

<?php global $wp;

global $query, $wp_query, $meta_make, $meta_year;

$wp_query->is_404 = false;

get_header();

$output = '<div style="margin-top: 20px;">';

$output .= '<div id="boatdealer_content">';

if (!isset($_GET['submit'])) {

    $_GET['submit'] = '';

} else

    $submit = sanitize_text_field($_GET['submit']);

if (isset($_GET['post_type'])) {

    $post_type = sanitize_text_field($_GET['post_type']);

}

if (isset($_GET['postNumber'])) {

    $postNumber = sanitize_text_field($_GET['postNumber']);

}

if (empty($postNumber)) {

    $postNumber = get_option('BoatDealer_quantity', 6);

}

if (isset($submit)) {

    require_once (BOATDEALERPLUGINPATH . 'includes/search/search_get_par.php');

$output .= BoatDealer_search(2);

    if (get_query_var('paged')) {

        $paged = get_query_var('paged');

    } elseif (get_query_var('page')) {

        $paged = get_query_var('page');

    }

    if(! isset($paged))

       $paged = boatdealer_get_page();

//if (isset($submit)) {

//   require_once (BOATDEALERPLUGINPATH . 'includes/search/search_get_par.php');

    $afieldsId = boatdealer_get_fields('all');

    $totfields = count($afieldsId);

    $afilter = array();

    $afilter['relation'] = 'AND';

    for ($i = 0; $i < $totfields; $i++) {

        $post_id = $afieldsId[$i];

        $ametadata = boatdealer_get_meta($post_id);

        $keyname = 'boat-' . $ametadata[12];

        $metaname = 'meta_' . $ametadata[12];

        if (isset($_GET[$metaname])) {

            $keyval = trim(sanitize_text_field($_GET[$metaname]));

            if ($keyval != 'All') {

                if ($ametadata[1] == 'checkbox') {

                    if ($keyval == 'enabled') {

                        $afilter[] = array(

                            'key' => $keyname,

                            'value' => $keyval,

                            'compare' => 'EXISTS');

                    }

                    else

                    {

                        echo $keyname;

                        $afilter[] = array(

                            'key' => $keyname,

                            'value' => 'enabled',

                            'compare' => 'NOT EXISTS');                       

                    }

                } else // not checkbox

                {

                    if ( !empty($keyval))

                    {

                    $afilter[] = array(

                        'key' => $keyname,

                        // serialize())

                        'value' => $keyval,

                        'compare' => 'LIKE');

                    }

                }

            }

        }

    } // end Loop fields

    if ($price != '') {

        $pos = strpos($price, '-');

        if ($pos !== false) {

            $priceMin = trim(substr($price, 0, $pos - 1));

            $priceMax = trim(substr($price, $pos + 1));

        }

        else{

              $priceMin = '';

              $priceMax = '';

        }    

        $afilter[] = array(

                     // array(

                      'relation' => 'OR',

                       array(

                        'key' => 'boat-price',

                        'value' => array($priceMin, $priceMax),

                        'type' => 'numeric',

                        'compare' => 'BETWEEN'),

                      array(

                        'key' => 'boat-price',

                        'value' => '0',

                        'type' => 'numeric',  

                        'compare' => '='),

                  ); 

    } // end meta_price

              $afilter[] = array(

              array($yearKey => $yearName, $yearVal => $year),

              //  array($conKey => $conName, $conVal => $con),

                array($fuelKey => $fuelName,$fuelVal => $fuel,),

                array($transKey => $transName,$transVal => $trans,),

             //   array($typeKey => $typeName, $typeVal => $typecar),

);

    // Featured

    if (isset($_GET['meta_order']))

        $order = trim(sanitize_text_field($_GET['meta_order']));

    else

        $order = '';

    if (!empty($order)) {

        if ($order == 'price_high') {

            $wmetakey = 'boat-price';

            $wmetaorder = 'DESC';

        }

        if ($order == 'price_low') {

            $wmetakey = 'boat-price';

            $wmetaorder = 'ASC';

        }

        if ($order == 'year_high') {

            $wmetakey = 'boat-year';

            $wmetaorder = 'DESC';

        }

        if ($order == 'year_low') {

            $wmetakey = 'boat-year';

            $wmetaorder = 'ASC';

        }

        if ($order == 'mileage_high') {

            $wmetakey = 'boat-miles';

            $wmetaorder = 'DESC';

        }

        if ($order == 'mileage_low') {

            $wmetakey = 'boat-miles';

            $wmetaorder = 'ASC';

        } 

        

        if ($order == 'hours_high') {

            $wmetakey = 'boat-miles';

            $wmetaorder = 'DESC';

        }

        

        if ($order == 'hours_low') {

            $wmetakey = 'boat-miles';

            $wmetaorder = 'ASC';

        }         

        

        

        

               

    } // no order

    $args = array(

        'post_type' => 'boats',

        'showposts' => $postNumber,

        'paged' => $paged,

        );

    if (!empty($order)) {

        $args['orderby'] = 'meta_value';

        $args['meta_type'] = 'NUMERIC';

        $args['meta_key'] = $wmetakey;

        $args['order'] = $wmetaorder;

    }

    $args['meta_query'] = $afilter;

   if(!empty($make) and $make <> 'Any')

            {

               $args['tax_query'] = array(                

                               array(

                        'taxonomy' => 'makes',

                        'field' => 'name',

                        'terms' => $make,

                    ),

                 );

            }      

} else // submit

{

    $args = array(

        'post_type' => 'boats',

        'showposts' => $postNumber,

        'paged' => $paged,

        'order' => 'DESC');

}

global $wp_query;

wp_reset_query();

$wp_query = new WP_Query($args);

$qposts = $wp_query->post_count;

// echo 'q posts: '.$qposts;

$BoatDealer_measure = get_option('BoatDealer_measure', 'M2');

$ctd = 0;

    $output .= '<div class="carGallery">';

    $output .= '<div class="BoatDealer_container">';

    while ($wp_query->have_posts()):

        $wp_query->the_post();

        $ctd++;

        $price = get_post_meta(get_the_ID(), 'boat-price', true);

        if ($price <> '' and $price != '0') {

            $price = number_format($price);

        } else

            $price = '';

        $image_id = get_post_thumbnail_id();

        if (empty($image_id)) {

            $image = BOATDEALERIMAGES . 'image-no-available-800x400_br.jpg';

            $image = str_replace("-", "", $image);

        } else {

            $image_url = wp_get_attachment_image_src($image_id, 'medium', true);

            $image = str_replace("-" . $image_url[1] . "x" . $image_url[2], "", $image_url[0]);

        }

        $BoatDealer_thumbs_format = trim(get_option('BoatDealer_thumbs_format', '1'));

        if ($BoatDealer_thumbs_format == '2')

            $thumb = BoatDealer_theme_thumb($image, 300, 225, 'br'); // Crops from bottom right

        else

            $thumb = BoatDealer_theme_thumb($image, 400, 200, 'br'); // Crops from bottom right

        $hp = get_post_meta(get_the_ID(), 'boat-hp', true);

        $year = get_post_meta(get_the_ID(), 'boat-year', true);

        $fuel = get_post_meta(get_the_ID(), 'boat-fuel', true);

        $transmission = get_post_meta(get_the_ID(), 'transmission-type', true);

        $miles = get_post_meta(get_the_ID(), 'boat-miles', true);

        $output .= '<div>';

        $output .= '<a href="' . get_permalink() . '">';

        $output .= '<div class="BoatDealer_gallery_2016">';

        $output .= '<img class="BoatDealer_caption_img" src="' . $thumb . '" alt="' .

            get_the_title() . '" />';

        $output .= '<div class="BoatDealer_caption_text">';

        $output .= ($price <> '' ? boatdealer_currency() . $price : __('Call for Price',

            'boatdealer'));

        // $output .= ($price <> '' ? '<br />' : '');

        $output .= '<br />';

        $output .= ($hp <> '' ? $hp . ' ' . __('HP', 'boatdealer') . '<br />' : '');

        $output .= ($year <> '' ? __('Year', 'boatdealer') . ': ' . $year . '<br />' : '');

        $output .= ($fuel <> '' ? __('Fuel', 'boatdealer') . ': ' . $fuel . '<br />' : '');

        $output .= ($transmission <> '' ? __('Transmission', 'boatdealer') . ': ' . $transmission .

            '<br />' : '');

        $miles_label = get_option("BoatDealer_measure", "Miles");

        $output .= ($miles <> '' ? __($miles_label, 'boatdealer') . ': ' . $miles .

            '<br />' : '');

        $output .= '</div>';

        $output .= '<div class="carTitle">' . get_the_title() . '</div>';

        $output .= '</a>';

        $output .= '</div>';

        $output .= '</div>';

        if ($ctd < $qposts) {

            if ($ctd % 3 == 0) {

                $output .= '</div>';

                $output .= '<div class="BoatDealer_container">';

            }

        }

    endwhile;   

    $output .= '</div>'; 

    $output .= '<br/> <br/>';  

ob_start();

the_posts_pagination(array(

    'mid_size' => 2,

    'prev_text' => __('Back', 'boatdealer'),

    'next_text' => __('Onward', 'boatdealer'),

    ));

$output .= ob_get_contents();

ob_end_clean();

$output .= '</div>';

$output .= '</div>';

wp_reset_postdata();

wp_reset_query();

if ($qposts < 1) {

    $output .= '<br /><h4>' . __('Not Found !','boatdealer') . '</h4>';

}

echo $output;

$registered_sidebars = wp_get_sidebars_widgets();

if (get_option('sidebar_search_page_result', 'no') == 'yes') {

    foreach ($registered_sidebars as $sidebar_name => $sidebar_widgets) {

        unregister_sidebar($sidebar_name);

    }

}

get_footer(); ?>