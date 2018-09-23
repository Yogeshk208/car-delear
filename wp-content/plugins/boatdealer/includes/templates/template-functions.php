<?php 
/**
 * @author Bill Minozzi
 * @copyright 2017
 */
 function boatdealer_content_detail(){
    Global $post_product_id;
    $post_product_id = get_the_ID();
    ?>
    <div class="multi-content">
         <div id="sliderWrapper">
        </div> <!-- end featuredCar -->      
             <div class="featuredTitle"> 
             <?php echo __('Details', 'boatdealer');?> 
             </div>
             <?php 
        $afieldsId = boatdealer_get_fields('all');
        $totfields = count($afieldsId);
        $ametadataoptions = array();
        echo '<div class="featuredCar">';
        for ($i = 0; $i < $totfields; $i++) {
            $post_id = $afieldsId[$i];
            $ametadata = boatdealer_get_meta($post_id);        
            if (!empty($ametadata[0]))
                $label = $ametadata[0];
            else
                $label = $ametadata[12];
            $field_id = 'boat-'.$ametadata[12];
            $value = get_post_meta($post_product_id, $field_id, true);
             $typefield = $ametadata[1];
             if ($value != '') 
             { 
                 if ($typefield == 'checkbox')
                 {
                   if($value == 'enabled')
                     $value = 'Ok';
                   else
                     $value = 'No';
                 }
                  ?>
                 <div class="featuredList">             
                 <span class="multiBold"> <?php echo $label;?>: </span><?php echo '<b>'.$value.'</b>';?> 
                 </div><!-- End of featured list -->
             <?php }
        } // end for ?>
        </div><!-- End of featured car -->
       <div class="featuredTitle"> 
       <?php echo __('Features', 'boatdealer');?> 
       </div> 
       <div class="featuredCar">
       <?php function boat_taxonomy( $taxonomy ) {
                         Global $post_product_id;
    					 $terms = get_the_terms( $post_product_id, $taxonomy );
                         $return = '';
    					 if ( $terms ) {
    						 foreach($terms as $term) {
    						       $return .= '<div class="featuredList">'.$term->name.'</div>';
                                } 
    					     }
    					 return $return;
                       } 
                     $output = boat_taxonomy( 'features' );
                     echo $output;
                 ?>
        </div> <!-- end featuredCar -->
     </div> <!-- end of Multi Content --> 
     </div> <!-- end of Slider Wrapper -->
     <?php 
  } 
 function boatdealer_content_info () {
        Global $post_product_id;
    ?>
 <div class="contentInfo">
         <div class="multiPriceSingle">
         	<?php 
            $price = get_post_meta(get_the_ID(), 'boat-price', true);
           if ($price <> '' and $price != '0')
             { 
                $price =   number_format_i18n($price,0);
                $price = boatdealer_currency() . $price;
             }
             else
                $price =  __('Call for Price', 'boatdealer'); 
            echo $price;
    		?> 
         </div>
         <div class="multiContent">
         	<?php the_content(); ?>
         </div> 
  </div>	        
         <?php
         $terms = get_the_terms( $post_product_id, 'model' );
         if ( $terms )
         {
             ?>
             <div class="featuredTitle"> 
             <?php echo __('Model', 'boatdealer');
             foreach($terms as $term) {
    			echo ': '.$term->name;
                break;
             }
            echo '</div>';
         }
         ?>
                <?php if(is_array($terms)) 
                 echo '<div class="featuredCar">'; 
                ?>    
            <div class="multiDetail">
                <div class="multiBasicRow"><span class="singleInfo"><?php echo __('Fuel', 'boatdealer')?>: </span><?php echo get_post_meta(get_the_ID(), 'boat-fuel', 'true'); ?></div>
                <div class="multiBasicRow"><span class="singleInfo"><?php echo __('Year', 'boatdealer')?>: </span><?php echo get_post_meta(get_the_ID(), 'boat-year', 'true'); ?></div> 
                <div class="multiBasicRow"><span class="singleInfo"><?php echo __(get_option('BoatDealer_measure', 'Miles'), 'boatdealer')?>: </span><?php echo get_post_meta(get_the_ID(), 'boat-miles', 'true'); ?></div>
                <div class="multiBasicRow"><span class="singleInfo"><?php echo __('Cond', 'boatdealer');?>: </span><?php echo get_post_meta(get_the_ID(), 'boat-con', 'true'); ?></div>
                <div class="multiBasicRow"><span class="singleInfo"><?php echo __('HP', 'boatdealer');?>:&nbsp; </span><?php echo get_post_meta(get_the_ID(), 'boat-hp', 'true'); ?></div>
            </div>
             <?php if(is_array($terms)) 
              echo '</div>'; 
             ?>       
 <?php }
function boatdealer_detail() {
  echo '<div class="multi-content">';
	while ( have_posts() ) : the_post(); 
       boatdealer_title_detail();
       boatdealer_content_info();
      ?> 
      <div class="multicontentWrap">
	    <?php boatdealer_content_detail (); ?>
      </div><?php
     break;
   endwhile; // end of the loop.
   echo '</div>';
}
function boatdealer_title_detail(){
global $boatdealer_the_title;
   $boatdealer_the_title = get_the_title(); 
  ?>
    <div class="multi-detail-title">  <?php echo SINGLE_TITLE; ?> 
    </div>
<?php }
require_once(BOATDEALERPLUGINPATH . "assets/php/boatdealer_mr_image_resize.php");
function BoatDealer_theme_thumb($url, $width, $height=0, $align='') {
    return $url;
        if (get_the_post_thumbnail()=='') {
    	  	$url = BOATDEALERIMAGES.'image-no-available.jpg';
		}
       return boatdealer_mr_image_resize($url, $width, $height, true, $align, false);
}