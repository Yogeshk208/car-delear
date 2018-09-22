<?php add_action('init', 'boatdealerAddFieldsPost');
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
function boatdealerAddFieldsPost() {
	register_post_type( 'boatdealerfields', 
		array( 
			'labels' => array(
				'name' => 'Fields',
				'all_items' => 'Fields Table',
				'singular_name' => 'Fields',
				'add_new_item' => 'Add Fields',
				'edit_item' => 'Edit Fields',
				'search_items' => 'Search Fields',
				'not_found' => 'No Fields Found',
				'not_found_in_trash' => 'No Fields Found in Trash',
				'menu_name' => 'Boat Dealer'
			),
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
             'show_in_menu' => false, //'multi-dealer',
			'exclude_from_search' => false,
			'rewrite' => array("slug" => "boatdealerfields"),
		)
	);
};
function BoatDealer_fields_columns_head($defaults) {
    $defaults['field-order'] = 'Order';
    $defaults['field-typefield'] = 'Type Field';
    $defaults['field-label'] = 'Label';
    $defaults['field-searchbar'] = 'Search Bar';
    $defaults['field-searchwidget'] = 'Widget';
    return $defaults;
}
function BoatDealer_fields_columns_content($column_name, $post_ID) {
    if ($column_name == 'field-order'){
         echo get_post_meta( $post_ID, 'field-order', true ); 
    }  
    if ($column_name == 'field-typefield'){
         echo get_post_meta( $post_ID, 'field-typefield', true ); 
    }
    elseif ($column_name == 'field-label'){
         echo get_post_meta( $post_ID, 'field-label', true ); 
    }
    elseif ($column_name == 'field-searchbar'){
            if(get_post_meta( $post_ID, 'field-searchbar', true ) == '1')
             echo 'Ok';
            else
             echo 'No';
    }
    elseif ($column_name == 'field-searchwidget'){
        if(get_post_meta( $post_ID, 'field-searchwidget', true ) == '1')
             echo 'Ok';
            else
             echo 'No';      
        }
}
add_filter( 'manage_edit-boatdealerfields_sortable_columns', 'boatdealer_fields_sortable_column' );
function boatdealer_fields_sortable_column( $columns ) {
    $columns['field-label'] = 'Label';
    $columns['field-searchwidget'] = 'Widget';
    $columns['field-typefield'] = 'Type Field';
    $columns['field-searchbar'] = 'Search Bar';
    $columns['field-order'] = 'Order';   
    return $columns;
}
function boatdealer_multifields_list($query) {
    if( is_admin()) {
        return;
    }
        $query->set('orderby', 'meta_value');
        $query->set('meta_key', "field-order");
        $query->set('order', 'ASC');
}
if(isset($_GET['post_type'])){
    if (sanitize_text_field($_GET['post_type']) == 'boatdealerfields')
      {
        // add_action('pre_get_posts', 'boatdealer_multifields_list');
        add_filter('manage_boatdealerfields_posts_columns', 'BoatDealer_fields_columns_head');
        add_action('manage_boatdealerfields_posts_custom_column', 'BoatDealer_fields_columns_content', 10, 2);
     }
}