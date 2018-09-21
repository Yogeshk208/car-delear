<?php
/**
 * Shortcode class
 *
 * usage:
 * add_shortcode('your_shortcode_name', array('paypalShortcode', 'frontendIndex'));
 */

class paypalShortcode {

    public static function frontendIndex($atts) {

        require TMM_PAYPAL_PLUGIN_PATH . '/views/frontendshortcode.php';

    }

}