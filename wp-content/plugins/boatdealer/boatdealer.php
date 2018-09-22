<?php /*
Plugin Name: BoatDealer 
Plugin URI: http://boatdealerplugin.com
Description: The easiest way to manage, list and sell your boats and PWC online.
Version: 2.10
Text Domain: boatdealer
Domain Path: /language
Author: Bill Minozzi
Author URI: http://billminozzi.com
License:     GPL2
Copyright (c) 2017 Bill Minozzi
Boat Dealer Right Away is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
boatdealer is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with boatdealer. If not, see {License URI}.
Permission is hereby granted, free of charge subject to the following conditions:
The above copyright notice and this FULL permission notice shall be included in
all copies or substantial portions of the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
DEALINGS IN THE SOFTWARE.
*/
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
define('BOATDEALERPLUGINVERSION', '2.10');
define('BOATDEALERPLUGINPATH', plugin_dir_path(__file__));
define('BOATDEALERPLUGINURL', plugin_dir_url(__file__));
define('BOATDEALERIMAGES', plugin_dir_url(__file__) . 'assets/images/');
include_once (ABSPATH . 'wp-includes/pluggable.php');
$boatdealer_plugin = plugin_basename(__file__);
function boatdealer_plugin_settings_link($links)
{
    $settings_link = '<a href="options.php?page=boatdealer_settings">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}
if (is_admin()) {
    // $path = dirname(plugin_basename(__file__)) . '/language/';
    $path = basename( dirname( __FILE__ ) ) . '/language';
    $loaded = load_plugin_textdomain('boatdealer', false, $path);
    if (!$loaded and get_locale() <> 'en_US') {
        if (function_exists('BoatDealer_localization_init_fail'))
            add_action('admin_notices', 'BoatDealer_localization_init_fail');
    }
} else {
    add_action('plugins_loaded', 'BoatDealer_localization_init');
}
add_filter("plugin_action_links_$boatdealer_plugin",
    'boatdealer_plugin_settings_link');
require_once (BOATDEALERPLUGINPATH . "settings/load-plugin.php");
require_once (BOATDEALERPLUGINPATH . "settings/options/plugin_options_tabbed.php");
require_once (BOATDEALERPLUGINPATH . 'includes/help/help.php');
require_once (BOATDEALERPLUGINPATH . 'includes/functions/functions.php');
require_once (BOATDEALERPLUGINPATH . 'includes/post-type/meta-box.php');
require_once (BOATDEALERPLUGINPATH . 'includes/post-type/post-functions.php');
require_once (BOATDEALERPLUGINPATH . 'includes/templates/template-functions.php');
require_once (BOATDEALERPLUGINPATH . 'includes/templates/redirect.php');
require_once (BOATDEALERPLUGINPATH . 'includes/widgets/widgets.php');
require_once (BOATDEALERPLUGINPATH . 'includes/search/search-function.php');
require_once (BOATDEALERPLUGINPATH . 'includes/multi/multi.php');
require_once (BOATDEALERPLUGINPATH . 'dashboard/main.php');
require_once (BOATDEALERPLUGINPATH . 'includes/contact-form/multi-contact-form.php');
$Multidealer_template_gallery = trim(get_option('BoatDealer_template_gallery',
    'yes'));
$Boatdealer_template_gallery = trim(get_option('BoatDealer_template_gallery',
    'yes'));
if ($Boatdealer_template_gallery == 'yes')
    require_once (BOATDEALERPLUGINPATH . 'includes/templates/template-showroom.php');
else
    require_once (BOATDEALERPLUGINPATH . 'includes/templates/template-showroom1.php');
require_once (BOATDEALERPLUGINPATH . 'includes/multi/multi-functions.php');
require_once (BOATDEALERPLUGINPATH . 'includes/team/team.php');
$boatdealerurl = esc_url($_SERVER['REQUEST_URI']);
if (strpos($boatdealerurl, 'product') !== false) {
    $BoatDealer_overwrite_gallery = strtolower(get_option('BoatDealer_overwrite_gallery',
        'yes'));
    if ($BoatDealer_overwrite_gallery == 'yes')
        require_once (BOATDEALERPLUGINPATH . 'includes/gallery/gallery.php');
}
add_action('wp_enqueue_scripts', 'BoatDealer_add_files');
function BoatDealer_add_files()
{
    wp_enqueue_style('show-room', BOATDEALERPLUGINURL . 'includes/templates/show-room.css');
    wp_enqueue_style('pluginStyleGeneral', BOATDEALERPLUGINURL .
        'includes/templates/template-style.css');
    wp_enqueue_style('pluginStyleSearch2', BOATDEALERPLUGINURL .
        'includes/search/style-search-box.css');
    wp_enqueue_style('pluginStyleSearchwidget', BOATDEALERPLUGINURL .
        'includes/widgets/style-search-widget.css');
    wp_enqueue_style('pluginStyleGeneral4', BOATDEALERPLUGINURL .
        'includes/gallery/css/flexslider.css');
    wp_register_style('jqueryuiSkin', BOATDEALERPLUGINURL . 'assets/jquery/jqueryui.css',
        array(), '1.12.1');
    wp_enqueue_style('jqueryuiSkin');
    wp_enqueue_style('bill-caricons', BOATDEALERPLUGINURL . 'assets/icons/icons-style.css');
     wp_enqueue_style('pluginTeam2', BOATDEALERPLUGINURL .
        'includes/team/team-custom.css'); 
     wp_enqueue_style('pluginTeam1', BOATDEALERPLUGINURL .
        'includes/team/team-custom-bootstrap.css');       
    wp_register_style('fontawesome-css', BOATDEALERPLUGINURL . 'assets/fonts/font-awesome/css/font-awesome.min.css', array(), BOATDEALERPLUGINVERSION);
    wp_enqueue_style('fontawesome-css');
    wp_enqueue_script('jquery-ui-slider');
    wp_enqueue_style('pluginStyleGeneral5', BOATDEALERPLUGINURL .
        'includes/contact-form/css/multi-contact-form.css');
}
function BoatDealer_activated()
{
    $boatdealer_plugin_version = get_site_option('boatdealer_plugin_version', '');
    if ($boatdealer_plugin_version < BOATDEALERPLUGINVERSION) {
        if ($boatdealer_plugin_version < '2.3') {
            if (boatdealer_howmanyboats() > 0) {
                ob_start();
                boatdealer_add_default_fields();
                ob_end_clean();
            }
            add_action('wp_loaded', 'boatdealer_update_files');
        }
        if (!add_option('boatdealer_plugin_version', BOATDEALERPLUGINVERSION))
            update_option('boatdealer_plugin_version', BOATDEALERPLUGINVERSION);
    }
    if (trim(get_option('BoatDealer_activated', '')) != '')
        return;
    $w = update_option('BoatDealer_activated', '1');
    if (!$w)
        add_option('BoatDealer_activated', '1');
        add_action('admin_notices', 'BoatDealer_plugin_was_activated');
    $admin_email = get_option('admin_email');
    $old_admin_email = trim(get_option('BoatDealer_recipientEmail', ''));
    if (empty($old_admin_email)) {
        $w = update_option('BoatDealer_recipientEmail', $admin_email);
        if (!$w)
            add_option('BoatDealer_recipientEmail', $admin_email);
    }
    $a = array(
        'BoatDealer_show_make',
        'BoatDealer_show_type',
        'BoatDealer_show_price',
        'BoatDealer_show_year',
        'BoatDealer_show_condition',
        'BoatDealer_show_transmission',
        'BoatDealer_show_fuel',
        'BoatDealer_show_orderby',
        'BoatDealer_show_price');
    $q = count($a);
    for ($i = 0; $i < $q; $i++) {
        $x = trim(get_option($a[$i], ''));
        if ($x != 'yes' and $x != 'no') {
            $w = update_option($a[$i], 'yes');
            if (!$w)
                add_option($a[$i], 'yes');
        }
    }
}
register_activation_hook(__file__, 'BoatDealer_activated');
function BoatDealer_localization_init()
{
    // $path = BOATDEALERPLUGINPATH . '/language/';
    $path = basename( dirname( __FILE__ ) ) . '/language';
    $loaded = load_plugin_textdomain('boatdealer', false, $path);
}
function boatdealerplugin_load_bill_stuff()
{
    wp_enqueue_script('jquery-ui-core');
    if( is_admin())
    {
       if( isset( $_GET[ 'taxonomy' ] ) ) 
          $active_tax = sanitize_text_field($_GET[ 'taxonomy' ]);
       if(isset($active_tax))
         if($active_tax == 'team')
             wp_enqueue_media();
    }    
}
add_action('wp_loaded', 'boatdealerplugin_load_bill_stuff');
function boatdealerplugin_load_feedback()
{
    if (is_admin()) {
        //ob_start();
        require_once (BOATDEALERPLUGINPATH . "includes/feedback/feedback.php");
        if (get_option('bill_last_feedback', '') != '1')
            require_once (BOATDEALERPLUGINPATH . "includes/feedback/feedback-last.php");
        //       ob_end_clean();
    }
}
function boatdealerplugin_load_activate()
{
    if (is_admin()) {
        require_once (BOATDEALERPLUGINPATH . 'includes/feedback/activated-manager.php');
    }
}
add_action('wp_loaded', 'boatdealerplugin_load_feedback');
add_action('in_admin_footer', 'boatdealerplugin_load_activate'); 
if (is_admin()) {
    if (get_option('BoatDealer_activated', '0') == '1') {
        add_action('admin_notices', 'BoatDealer_plugin_was_activated');
        $r = update_option('BoatDealer_activated', '0');
        if (!$r)
            add_option('BoatDealer_activated', '0');
    }
}
?>