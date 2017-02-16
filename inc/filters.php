<?php 

/*
*	Filters the 'order' post type to put the date in the Title Field
*
*
*/
add_filter( 'wp_insert_post_data' , 'eggo_modify_post_title' , '99', 1 ); // Grabs the inserted post data so you can modify it.

function eggo_modify_post_title( $data )
{
  if($data['post_type'] == 'order' && isset($_POST['order_date'])) { // meta key
    $date = date('M, d Y', strtotime($_POST['order_date']));
    $title = 'Eggs ordered for ' . $date;
    $data['post_title'] =  $title ; //Updates the post title to your new title.
  }
  return $data; // Returns the modified data.
} 


/*
*	Filters the 'egg' post type to put the date in the Title Field
*
*
*/
add_filter( 'wp_insert_post_data' , 'egg_modify_post_title' , '99', 1 ); // Grabs the inserted post data so you can modify it.

function egg_modify_post_title( $data )
{
  if($data['post_type'] == 'egg' && isset($_POST['egg_date'])) { // meta key
    $date = date('M, d Y', strtotime($_POST['egg_date']));
    $title = 'Eggs collected for ' . $date;
    $data['post_title'] =  $title ; //Updates the post title to your new title.
  }
  return $data; // Returns the modified data.
} 