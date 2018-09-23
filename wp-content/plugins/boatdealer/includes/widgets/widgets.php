<?php 
/**
 * @author Bill Minozzi
 * @copyright 2017
 */
function boatdealer_RecentWidget() {
	register_widget( 'boatdealer_RecentWidget' );
}
add_action( 'widgets_init', 'boatdealer_RecentWidget' );
class boatdealer_RecentWidget extends WP_Widget {
       public function __construct() {
        parent::__construct(
        'RecentWidget',         
        'Recent boats',                
        array( 'description' => __('A list of Recent boats', 'boatdealer'), ) 
        );
    }   
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'amount' => '','Fwidth' => '','Fheight' => '') );
        if(isset($instance['Ramount']))
          {$Ramount = $instance['Ramount'];}
        else
          {$Ramount = 3;}
		echo '<p>
			<label for="'.$this->get_field_id('Ramount').'">
				Number of boats to show: <input maxlength="1" size="1" id="'. $this->get_field_id('Ramount') .'" name="'. $this->get_field_name('Ramount') .'" type="text" value="'. esc_attr($Ramount) .'" />
			</label>
		</p>';
	}
	function update($new_instance, $old_instance) { 
		$instance = $old_instance;
        if(is_numeric($new_instance['Ramount']))
		    {$instance['Ramount'] = $new_instance['Ramount'];}
      	return $instance;
	}
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$Ramount = empty($instance['Ramount']) ? ' ' : apply_filters('widget_title', $instance['Ramount']); 
		if($Ramount == '') {$Ramount = 3; }
        ?>
	    <div class="sideTitle"> <?php echo __('New Arrivals', 'boatdealer');?> </div><?php 
		$args = array(
			'post_type'      => 'boats',
			'order'    => 'DESC',
			'showposts' => $Ramount,
		);
        $_query3 = new WP_Query( $args );
    $output = '<div class="BoatDealer-listing-wrap"> <div class="multiGallery">';
	while ($_query3->have_posts()) : $_query3->the_post();
		$image_id = get_post_thumbnail_id();
		$image_url = wp_get_attachment_image_src($image_id,'medium', true);	


        $price = get_post_meta(get_the_ID(), 'boat-price', true);
   
        if($price == '0')
          $price = '';
   
            if (!empty($price))
                 {$price =   number_format_i18n($price,0);}



		$image = str_replace("-".$image_url[1]."x".$image_url[2], "", $image_url[0]);
		$featured = trim(get_post_meta(get_the_ID(), 'boat-featured', true));
 
 
 
    $BoatDealer_thumbs_format = trim(get_option('BoatDealer_thumbs_format', '1'));
    if ($BoatDealer_thumbs_format == '2')
        $thumb = BoatDealer_theme_thumb($image, 300, 225, 'br'); // Crops from bottom right
    else
        $thumb = BoatDealer_theme_thumb($image, 400, 200, 'br'); // Crops from bottom right
 
 
 
 
        $year = get_post_meta(get_the_ID(), 'boat-year', true);
            $output .= '<div>';
            $output .=  '<a href="' . get_permalink() . '">';
            $output .= '<div class="BoatDealer_gallery_2016_widget">';
            $output .=  '<img class="BoatDealer_caption_img_widget" src="' . $thumb .'" alt="'. get_the_title() . '" />';
            $output .= '<div class="BoatDealer_caption_text_widget">';
            $output .= ($price <> '' ? boatdealer_currency() . $price : __('Call for Price', 'boatdealer'));
            $output .= '<br />';
            $output .= ($year <> '' ? __('Year', 'boatdealer') .': '. $year.'<br />' : '');
            $output .= '</div>';
            $output .= '<div class="multiTitle-widget">' . get_the_title() . '</div>';
            $output .= '</div>';
            $output .= '</a>';
            $output .= '</div>';     
            $output .= '<br />';        
		endwhile; 
        $output .= '</div></div>'; 
        echo $output;
	}
}
function boatdealer_FeaturedWidget() {
	register_widget( 'boatdealer_FeaturedWidget' );
}
add_action( 'widgets_init', 'boatdealer_FeaturedWidget' );
class boatdealer_featuredWidget extends WP_Widget {
    public function __construct() {
        parent::__construct(
        'FeaturedWidget',         
        'Featured boats',                
        array( 'description' => __('A list of Featured boat-s', 'boatdealer'), ) 
        );
    } 
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'amount' => '') );
		$amount = $instance['amount'];
		echo '<p>
			<label for="'.$this->get_field_id('amount').'">
				Number of boats to show: <input maxlength="1" size="1" id="'. $this->get_field_id('amount') .'" name="'. $this->get_field_name('amount') .'" type="text" value="'. esc_attr($amount) .'" maxlength="3" size="3" />
			</label>
		</p>';
	}
	function update($new_instance, $old_instance) { 
		$instance = $old_instance;
        if(is_numeric($new_instance['amount']))
		    {$instance['amount'] = $new_instance['amount'];}       
		return $instance;
	}
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$amount = empty($instance['amount']) ? ' ' : apply_filters('widget_title', $instance['amount']); 
		if($amount == '') {$amount = 3; }
    ?>
        <div class="sideTitle"> 
        <?php echo __('Featured boats', 'boatdealer');?> 
        </div><?php 
		$args = array(
			'post_type'      => 'boats',
			'order'    => 'DESC',
			'showposts' => $amount,
			'meta_query' => array(
								array(
										'key' => 'boat-featured',
										'value' => 'enabled',
									  )
								   )
		);
        $_query2 = new WP_Query( $args );
        
		$output = '<div class="BoatDealer-listing-wrap"> <div class="multiGallery">';
		while ($_query2->have_posts()) : $_query2->the_post();
		$image_id = get_post_thumbnail_id();
		$image_url = wp_get_attachment_image_src($image_id,'medium', true);	
        $price = trim(get_post_meta(get_the_ID(), 'boat-price', true));


        if($price == '0')
          $price = '';
 

        if(! empty($price))
           $price = number_format_i18n($price);
        $image = str_replace("-".$image_url[1]."x".$image_url[2], "", $image_url[0]);
        $featured = get_post_meta(get_the_ID(), 'boat-featured', true);
       

 
 
     $BoatDealer_thumbs_format = trim(get_option('BoatDealer_thumbs_format', '1'));
    if ($BoatDealer_thumbs_format == '2')
        $thumb = BoatDealer_theme_thumb($image, 300, 225, 'br'); // Crops from bottom right
    else
        $thumb = BoatDealer_theme_thumb($image, 400, 200, 'br'); // Crops from bottom right

 
 
 
        $year = get_post_meta(get_the_ID(), 'boat-year', true);
            $output .= '<div>';
            $output .=  '<a href="' . get_permalink() . '">';
            $output .= '<div class="BoatDealer_gallery_2016_widget">';
            $output .=  '<img class="BoatDealer_caption_img_widget" src="' . $thumb .'" alt="'. get_the_title() . '" />';
            $output .= '<div class="BoatDealer_caption_text_widget">';
            $output .= ($price <> '' ? boatdealer_currency() . $price : __('Call for Price', 'boatdealer'));
            $output .= '<br />';
            $output .= ($year <> '' ? __('Year', 'boatdealer') .': '. $year : '');
            $output .= '</div>';
            $output .= '<div class="multiTitle-widget">' . get_the_title() . '</div>';
            $output .= '</div>';
            $output .= '</a>';
            $output .= '</div>';     
            $output .= '<br />';
        endwhile; 
        $output .= '</div></div>'; 
        echo $output;
	}
}
add_action( 'widgets_init', create_function('', 'return register_widget("boatdealer_SearchWidget");') );
class boatdealer_SearchWidget extends WP_Widget {

// https://wordpress.stackexchange.com/questions/32103/how-do-i-create-a-widget-that-only-allows-a-single-instance

/*
    // You static instance switch
    static $instance;

    function My_Widget() {
            // We set the static class var to true on the first run
            if ( ! $this->instance ) {
                    $this->instance = true;
            } 
            // abort silently for the second instance of the widget
            else {
                    return;
            }
        // widget actual processes
    }
*/   
    
    


public function __construct() {
        parent::__construct(
        'SearchWidget',         
        'Search boats',                
        array( 'description' => __('Search boats', 'boatdealer'), ) 
        );
}     
	function SearchWidget()	{
		$widget_ops = array('classname' => 'SearchWidget', 'description' => 'Search Boats' );
		$this->WP_Widget('SearchWidget', 'Search Widget', $widget_ops);
	}
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'BoatDealer_search_name' => '') );
		$BoatDealer_search_name = $instance['BoatDealer_search_name'];
		echo '<p>
			<label for="'.$this->get_field_id('BoatDealer_search_name').'">';
				echo __('Title', 'boatdealer');
                echo ': <input class="widefat" id="'. $this->get_field_id('BoatDealer_search_name') .'" name="'. $this->get_field_name('BoatDealer_search_name') .'" type="text" value="'. esc_attr($BoatDealer_search_name) .'" />
			</label>
		</p>';
	}
	function update($new_instance, $old_instance) { 
		$instance = $old_instance;
		$instance['BoatDealer_search_name'] = $new_instance['BoatDealer_search_name'];
		return $instance;
	}
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$BoatDealer_search_name = empty($instance['BoatDealer_search_name']) ? ' ' : apply_filters('widget_title', $instance['BoatDealer_search_name']); 
		if(trim($BoatDealer_search_name) == '') {$BoatDealer_search_name = __('Search', 'boatdealer'); }        
        echo '<div class="sideTitle">';
        echo $BoatDealer_search_name;
        echo '</div>';        
		echo BoatDealer_search(0);
	}   
}