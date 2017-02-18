<?php
/*
Plugin Name: Urban Eggs
Plugin URI:  https://urbaneg.gs
Description: Egg inventory and Sells
Version:     0.1
Author:      Austin Crane
Author URI:  https://redrobots.io/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: urbegg
Domain Path: /languages
*/

/*

to do's
- eggs 12 dozen = $5.
- make a field of "credits" for paid eggs. eg: paid $10, but only got a dozen.



*/ 
register_activation_hook( __FILE__, 'urbegg_create_egg_tables' );

function urbegg_create_egg_tables() {
	// clear the permalinks after the post type has been registered
    flush_rewrite_rules();
}


// add_action('admin_enqueue_scripts', 'enqueue_date_picker');
function hkdc_admin_styles() {
  wp_enqueue_style( 'jquery-ui-datepicker-style' , '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');
  wp_enqueue_style( 'egg-style' , plugin_dir_url( __FILE__ ) . 'css/style.css' );
}
add_action('admin_print_styles', 'hkdc_admin_styles');
function hkdc_admin_scripts() {
  wp_enqueue_script( 'jquery-ui-datepicker' );
  wp_enqueue_script( 'wp-jquery-date-picker', plugin_dir_url( __FILE__ ) . 'js/custom.js' );
}
add_action('admin_enqueue_scripts', 'hkdc_admin_scripts');


require_once ('inc/metaboxes-collected.php');
require_once ('inc/metaboxes-orders.php');
require_once ('inc/filters.php');
require_once ('inc/post-types.php');
require_once ('admin/orders.php');



/*
*  admin_menu
*
*  This function will add the ACF menu item to the WP admin
*
*  @type	action (admin_menu)
*  @date	28/09/13
*  @since	5.0.0
*
*  @param	n/a
*  @return	n/a
*/

function urbegg_admin_menu() {
	
	// bail early if no show_admin
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	
	// vars
	$slug_egg = 'edit.php?post_type=egg';
	$slug_order = 'edit.php?post_type=order';
	$cap = 'manage_options';
	
	
	// add parent
	add_menu_page(
		__("Urban Eggs",'urbegg'), 
		__("Eggs",'urbegg'), 
		$cap, 
		$slug_egg, 
		false, 
		plugin_dir_url( __FILE__ ) . 'images/dashicon.png', 
		'21'
	);
	add_menu_page(
		__("Orders Eggs",'urbegg'), 
		__("Orders",'urbegg'), 
		$cap, 
		$slug_order, 
		false, 
		plugin_dir_url( __FILE__ ) . 'images/dashicon.png', 
		'22'
	);
	
	
	// add children for Orders
	add_submenu_page(
		$slug_order, 
		__('Orders','urbegg'), 
		__('Orders','urbegg'), 
		$cap, 
		$slug_order );
	add_submenu_page(
		$slug_order, 
		__('Order Reports','urbegg'), 
		__('Order Reports',
		'urbegg'), 
		$cap, 
		'order-reports',
		'urbegg_reports_orders_page_html' );

	// add children for Collected
	add_submenu_page(
		$slug_egg, 
		__('Eggs Collected','urbegg'), 
		__('Eggs Collected',
		'urbegg'), 
		$cap, 
		$slug_egg );
	add_submenu_page(
		$slug_egg, 
		__('Reports','urbegg'),
		__('Reports','urbegg'), 
		$cap, 
		'egg-reports',
		'urbegg_reports_page_html'
	);
	add_submenu_page(
		$slug_egg, 
		__('Options','urbegg'),
		__('Options','urbegg'), 
		$cap, 
		'egg-options',
		'urbegg_options_page_html'
	);
	
}
add_action( 'admin_menu', 'urbegg_admin_menu' );


/**
 * OPtions page
 */
function urbegg_options_page_html()
{
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?= esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "wporg_options"
            settings_fields('urbegg_options');
            // output setting sections and their fields
            // (sections are registered for "wporg", each field is registered to a specific section)
            do_settings_sections('urbegg');
            // output save settings button
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}

function urbegg_reports_page_html() {
	require_once('inc/reports-collected.php');
}
function urbegg_orders_page_html() {
	require_once('inc/egg-orders.php');
}
function urbegg_reports_orders_page_html() {
	require_once('inc/reports-orders.php');
}





