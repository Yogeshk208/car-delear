<?php
/**
 * @author William Sergio Minozzi
 * @copyright 2017
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly 
 
 
$boatdealer_memory = boatdealer_check_memory();

echo '<div id="boatdealer-memory-page">';
echo '<div class="boatdealer-block-title">';

    if ( $boatdealer_memory['msg_type'] == 'notok')
       {
        echo 'Unable to get your Memory Info';
        echo '</div>';

    }
    else
    {
        
echo 'Memory Info';
echo '</div>';
echo '<div id="memory-tab">';
echo '<br />';
if($boatdealer_memory['msg_type']  == 'ok')
 $mb = 'MB';
else
 $mb = '';
echo 'Current memory WordPress Limit: ' . $boatdealer_memory['wp_limit'] . $mb .
    '&nbsp;&nbsp;&nbsp;  |&nbsp;&nbsp;&nbsp;';
$perc = $boatdealer_memory['usage'] / $boatdealer_memory['wp_limit'];
if ($perc > .7)
   echo '<span style="'.$boatdealer_memory['color'].';">';
echo 'Your usage now: ' . $boatdealer_memory['usage'] .
    'MB &nbsp;&nbsp;&nbsp;';
if ($perc > .7)
   echo '</span>';    
echo '|&nbsp;&nbsp;&nbsp;   Total Server Memory: ' . $boatdealer_memory['limit'] .
    'MB';
// echo 'Current memory WordPress Limit: '.$boatdealer_memory['wp_limit'].$mb.'&nbsp;&nbsp;&nbsp;  |&nbsp;&nbsp;&nbsp;   Your usage: '.$boatdealer_memory['usage'].'MB of '.$boatdealer_memory['limit'];
   echo '<br />';    
   echo '<br />'; 
   echo '<br />';
?>  
   </strong>
<!-- <div id="memory-tab"> -->
    <br />
    To increase the WordPress memory limit, add this info to your file wp-config.php (located at root folder of your server)
    <br />
    (just copy and paste)
    <br />    <br />
<strong>    
define('WP_MEMORY_LIMIT', '128M');
</strong>
    <br />    <br />
    before this row:
    <br />
    /* That's all, stop editing! Happy blogging. */
    <br />
    <br />
    If you need more, just replace 128 with the new memory limit.
    <br /> 
    To increase your total server memory, talk with your hosting company.
    <br />   <br />
    <hr />
    <br />    
<strong>    How to Tell if Your Site Needs a Shot of more Memory:</strong>
        <br />    <br />
    If your site is behaving slowly, or pages fail to load, you 
    get random white screens of death or 500 
    internal server error you may need more memory. 
Several things consume memory, such as WordPress itself, the plugins installed, the 
theme you're using and the site content.
     <br />  
Basically, the more content and features you add to your site, 
the bigger your memory limit has to be.
if you're only running a small 
site with basic functions without a Page Builder and Theme 
Options (for example the native Twenty Sixteen). However, once 
you use a Premium WordPress theme and you start encountering 
unexpected issues, it may be time to adjust your memory limit 
to meet the standards for a modern WordPress installation.
     <br /> <br />    
    Increase the WP Memory Limit is a standard practice in 
WordPress and you find instructions also in the official 
WordPress documentation (Increasing memory allocated to PHP).
    <br /><br />
Here is the link:    
<br />
<a href="https://codex.wordpress.org/Editing_wp-config.php" target="_blank">https://codex.wordpress.org/Editing_wp-config.php</a>
<br /><br />
</div>
</div>
<?php
}
?>