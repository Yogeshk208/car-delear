<?php if (!defined('ABSPATH')) die('No direct access allowed');

$child_sections = array();
$tab_key = basename(__FILE__, '.php');
$pagepath = TMM_THEME_PATH . '/admin/theme_options/sections/' . $tab_key . '/custom_html/';

//***

$content = array(
	'api_key_google' => array(
		'title' => __('Google Map API', 'cardealer'),
		'type' => 'text',
		'show_title' => 0,
		'default_value' => 'AIzaSyCPmvaxJrvCIbuLEQ9uHRpOnXPW95gMoC4',
		'description' => __('Find the instructions on the folowing page to <a class="admin-link" target="_blank" href="http://forums.webtemplatemasters.com/how-to-obtain-the-google-api-key-for-google-maps/">get your API key</a>', 'cardealer'),
		'custom_html' => ''
	),
	'api_key_alphavantage' => array(
		'title' => __('Currency Converter API', 'cardealer'),
		'type' => 'text',
		'show_title' => 0,
		'default_value' => 'LEQ9uHRpOnXPW95gMoC4',
		'description' => __('Claim your free API key <a class="admin-link" target="_blank" href="https://www.alphavantage.co/support/#api-key">Here</a>', 'cardealer'),
		'custom_html' => ''
	),
	'twitter_widget_id' => array(
		'title' => __('Twitter widget ID', 'cardealer'),
		'type' => 'text',
		'default_value' => "351293746240958465",
		'description' => __('You can find the instructions on the folowing page to <a class="admin-link" target="_blank" href="https://twitter.com/settings/widgets">get your Twitter widget ID</a>', 'cardealer'),
		'custom_html' => ''
	),
	'tracking_code' => array(
		'title' => __('Tracking Code', 'cardealer'),
		'type' => 'textarea',
		'default_value' => '',
		'description' => __('Place here your Google Analytics (or other) tracking code. It will be inserted before closing head tag in your theme.', 'cardealer'),
		'custom_html' => ''
	)
);

$content = apply_filters('tmm_add_api_theme_option', $content);

$sections = array(
	'name' => __("API Settings", 'cardealer'),
	'css_class' => 'shortcut-api',
	'show_general_page' => true,
	'content' => $content,
	'child_sections' => $child_sections,
        'menu_icon' => 'dashicons-cloud'
);

TMM_OptionsHelper::$sections[$tab_key] = $sections;
