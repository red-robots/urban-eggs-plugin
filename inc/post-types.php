<?php 
add_action('init', 'urbegg_posttype');
function urbegg_posttype() 
{

	// Register the Homepage Eggs

	$labels = array(
		'name' => _x('Eggs', 'post type general name'),
		'singular_name' => _x('Egg', 'post type singular name'),
		'add_new' => _x('Add New', 'Egg'),
		'add_new_item' => __('Add New Egg'),
		'edit_item' => __('Edit Eggs'),
		'new_item' => __('New Egg'),
		'view_item' => __('View Eggs'),
		'search_items' => __('Search Eggs'),
		'not_found' =>  __('No Eggs found'),
		'not_found_in_trash' => __('No Eggs found in Trash'), 
		'parent_item_colon' => '',
		'menu_name' => 'Eggs'
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => false, 
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => false, 
		'hierarchical' => false, // 'false' acts like posts 'true' acts like pages
		'menu_position' => 20,
		'supports' => array('title','editor','custom-fields','thumbnail'),
	); 

	register_post_type('egg',$args); // name used in query

	$labels = array(
		'name' => _x('Egg Orders', 'post type general name'),
		'singular_name' => _x('Order', 'post type singular name'),
		'add_new' => _x('Add New', 'Order'),
		'add_new_item' => __('Add New Order'),
		'edit_item' => __('Edit Orders'),
		'new_item' => __('New Order'),
		'view_item' => __('View Orders'),
		'search_items' => __('Search Orders'),
		'not_found' =>  __('No Orders found'),
		'not_found_in_trash' => __('No Orders found in Trash'), 
		'parent_item_colon' => '',
		'menu_name' => 'Orders'
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => false, 
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => false, 
		'hierarchical' => false, // 'false' acts like posts 'true' acts like pages
		'menu_position' => 20,
		'supports' => array('title','editor','custom-fields','thumbnail'),
	); 


	register_post_type('order',$args); // name used in query


} // close custom post type
