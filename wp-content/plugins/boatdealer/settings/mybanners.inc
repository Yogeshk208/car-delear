<?php

/**
 * @author William Sergio Minossi
 * @copyright 2018
 */

  	submit_button();
		echo '</form>';
	//  $this->render_reset_form( $active_tab ); 
   echo '</div>'; //containerleft
     $when_installed = get_option('bill_installed');
     $now = time();
     $delta = $now - $when_installed;
     
     $delta = 1000000;
     
  
      if ($delta > (3600 * 24 * 7))
     {
        echo '<div id="containerright">';
        echo '<ul>';
        echo '<h2>Help & Support</h2>';
        echo '<li><a href="http://BoatDealerPlugin.com/help">OnLine Guide</a></li>';
        echo '<li><a href="http://billminozzi.com/dove/">Support</a></li>';
        echo '<li><a href="http://siterightaway.net/troubleshooting/">Troubleshooting</a></li>';
        echo '</ul>';
        echo '<ul>';
  
   
   
    $x = rand(1,5);
        //$x = 5;
        if($x < 3){
            echo '<h2>Like This Plugin?</h2>';
            _e( 'If you like this product, please write a few words about it. It will help other people find this useful plugin more quickly.<br><b>Thank you!</b>', 'boatdealer' ); 
            ?>
            <br /><br />
            <a href="http://BoatDealerPlugin.com/share/" class="button button-medium button-primary"><?php _e( 'Rate or Share', 'boatdealer' ); ?></a>
            <?php
            echo '</ul>';
        }
        elseif ($x == 5 )
        {
            echo '<h2>We Need Your Support</h2>';
            _e( 'It is hard to continue development and support for this plugin without contributions from users like you. If you enjoy it and find it useful, please consider making a donation. Your donation will help encourage and support the plugin\'s continued development and better user support.      
', 'boatdealer' ); 
            ?>
            <br /><br />
            <a href="http://BoatDealerPlugin.com/donate/" class="button button-medium button-primary"><?php _e( 'Donate', 'boatdealer' ); ?></a>
            <?php
            echo '</ul>';
        }
        else
        {
            echo '<h2>Please help us keep the plugin free & up-to-date</h2>';
            _e( 'If you use & enjoy Real Estate Plugin, please rate it on WordPress.org. It only takes a second and helps us keep the plugin free and maintained. Thank you!', 'boatdealer' ); 
            ?>
            <br /><br />
            <a href="https://wordpress.org/support/plugin/boatdealer/reviews/#new-post" class="button button-medium button-primary"><?php _e( 'Rate', 'boatdealer' ); ?></a>
            <?php
            echo '</ul>';
        }  
        if($x <> 5)
           echo '<ul>';      
       //        $banner_image = BOATDEALERIMAGES.'/keys_from_left.png';
        if($x == 1){
            echo '<h2>Make Your Website<br />Look More Professional</h2>';           
            echo '<img src="'.BOATDEALERIMAGES.'/apple.jpg" width="250" />';
        }
        if($x == 2){
            echo '<h2>Make Your Website<br />Look More Professional</h2>';
            // Chave
            echo '<img src="'.BOATDEALERIMAGES.'/keys.jpg" width="250" />';
        }
        if($x == 3){
            echo '<h2>Power for Your site: <br />Pro Version + Top Features.</h2>';
            // Leao
            echo '<img src="'.BOATDEALERIMAGES.'/lion.jpg" width="250" />';
        }
        if($x == 4){
            echo '<h2>Get Premium Performance: <br />Pro Version + Top Features.</h2>';
            // Corrida
            echo '<img src="'.BOATDEALERIMAGES.'/racing.jpg" width="250" />';
        }
        if($x <> 5){
            ?>
            <li>More Shortcodes to increase your control over the show room page</li>
            <li>Unlimited Colours Setup to match your site theme</li>
            <li>Dedicated Premium Support</li>
            <li>More...</li>
             <br />
            <a href="http://boatdealerplugin.com/premium/" class="button button-medium button-primary" ><?php _e( 'Learn More', 'boatdealer' ); ?></a>
            <?php
            echo '</ul>';  
            echo '</div>'; //containerright
        } 
        
        
      
        
        
        
        
        
        
    } // if delta...
	echo $this->page->markup_bottom;

?>