<?php

/**
 * Plugin Name: ThemeMakers Cardealer Features
 * Plugin URI: http://webtemplatemasters.com
 * Description: Advanced Features for Cardealer Theme
 * Author: ThemeMakers
 * Version: 1.0.8
 * Author URI: http://themeforest.net/user/ThemeMakers
 * Text Domain: tmm_theme_features
 */

class TMM_Theme_Features {

	
	protected static $instance = null;
	
	protected $slug = 'tmm_theme_features';

	private function __construct() {
		
		/*
		 * Register Slidergroup Post Type
		 */
		$cpt_name = 'slidergroup';
		$arguments = array(
			'labels' => array(
				'name' => __('Slider Groups', $this->slug),
				'singular_name' => __('Group', $this->slug),
				'add_new' => __('Add New', $this->slug),
				'add_new_item' => __('Add New Slider Group', $this->slug),
				'edit_item' => __('Edit Slider Group', $this->slug),
				'new_item' => __('New Slider Group', $this->slug),
				'view_item' => __('View Slider Group', $this->slug),
				'search_items' => __('Search Slider Groups', $this->slug),
				'not_found' => __('No Slide Groups found', $this->slug),
				'not_found_in_trash' => __('No Slide Groups found in Trash', $this->slug),
				'parent_item_colon' => ''
			),
			'public' => false,
			'archive' => false,
			'exclude_from_search' => false,
			'publicly_queryable' => false,
			'show_ui' => true,
			'query_var' => true,
			'capability_type' => 'post',
			'has_archive' => false,
			'hierarchical' => true,
			'menu_position' => null,
			'supports' => array('title', 'thumbnail'),
			'rewrite' => array('slug' => $cpt_name),
			'show_in_admin_bar' => false,
			'menu_icon' => '',
			'taxonomies' => array()
		);

		register_post_type($cpt_name, $arguments);

		/*
		 * Register Staff Post Type
		 */
		$cpt_name = 'staff-page';
		$args = array(
            'labels' => array(
                'name' => __('Staff', $this->slug),
                'singular_name' => __('Staff', $this->slug),
                'add_new' => __('Add New', $this->slug),
                'add_new_item' => __('Add New Staff', $this->slug),
                'edit_item' => __('Edit Staff', $this->slug),
                'new_item' => __('New Staff', $this->slug),
                'view_item' => __('View Staff', $this->slug),
                'search_items' => __('Search In Staff', $this->slug),
                'not_found' => __('Nothing found', $this->slug),
                'not_found_in_trash' => __('Nothing found in Trash', $this->slug),
                'parent_item_colon' => ''
            ),
            'public' => false,
            'archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'show_ui' => true,
            'query_var' => true,
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => true,
            'menu_position' => null,
            'supports' => array('title', 'thumbnail'),
            'rewrite' => array('slug' => $cpt_name),
            'show_in_admin_bar' => true,
            'taxonomies' => array('position'), // this is IMPORTANT
            'menu_icon' => ''
        );
        register_post_type($cpt_name, $args);
		
		/*
		 * Register Carproducer Taxonomy and Car Post Type
		 */
		$cpt_name = 'car';
		register_taxonomy("carproducer", array($cpt_name), array(
            "labels" => array(
                'name' => __('Producers', $this->slug),
                'singular_name' => __('Producer', $this->slug),
                'add_new' => __('Add New', $this->slug),
                'add_new_item' => __('Add New Cars Producer', $this->slug),
                'edit_item' => __('Edit Cars Producer', $this->slug),
                'new_item' => __('New Cars Producer', $this->slug),
                'view_item' => __('View Cars Producer', $this->slug),
                'search_items' => __('Search Cars Producer', $this->slug),
                'not_found' => __('No Cars Producers found', $this->slug),
                'not_found_in_trash' => __('No Cars Producers found in Trash', $this->slug),
                'parent_item_colon' => ''
            ),
            "singular_label" => __("carproducer", $this->slug),
            'show_in_nav_menus' => true,
            'capabilities' => array('manage_terms'),
            'show_ui' => true,
            'term_group' => true,
            'hierarchical' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'carproducer'),
            'orderby' => 'name'
        ));
		
		$args = array(
            'labels' => array(
                'name' => __('Cars', $this->slug),
                'singular_name' => __('Car', $this->slug),
                'add_new' => __('Add New', $this->slug),
                'add_new_item' => __('Add New Car', $this->slug),
                'edit_item' => __('Edit Car', $this->slug),
                'new_item' => __('New Car', $this->slug),
                'view_item' => __('View Car', $this->slug),
                'search_items' => __('Search Cars', $this->slug),
                'not_found' => __('No Cars found', $this->slug),
                'not_found_in_trash' => __('No Cars found in Trash', $this->slug),
                'parent_item_colon' => ''
            ),
            'public' => true,
            'archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'show_ui' => true,
            'query_var' => true,
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array('title', 'excerpt', 'tags', 'comments'),
            'rewrite' => array('slug' => $cpt_name),
            'show_in_admin_bar' => true,
            'menu_icon' => '',
            'taxonomies' => array('carlocation', 'carproducer')
        );

        register_post_type($cpt_name, $args);

	}

	public static function flush_rewrite_rules() {

		self::get_instance();
		flush_rewrite_rules();
	}
	
	private function __clone() {
		
	}

	public static function get_instance() {
		
		if ( self::$instance === null) {
			self::$instance = new self;
		}

		return self::$instance;
	}
	
	public static function load_plugin_textdomain() {

		load_plugin_textdomain( 'tmm_theme_features', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}
}

add_action( 'plugins_loaded', array('TMM_Theme_Features', 'load_plugin_textdomain') );

add_action( 'init', array('TMM_Theme_Features', 'get_instance') );

register_activation_hook( __FILE__, array('TMM_Theme_Features', 'flush_rewrite_rules') );
