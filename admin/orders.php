<?php  

/*-------------------------------------------------------------------------------
	Sortable Columns
-------------------------------------------------------------------------------*/

add_filter( 'manage_edit-order_columns', 'my_edit_order_columns' ) ;

function my_edit_order_columns( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Order Date' ),
		'name' => __( 'Name' ),
		'credit' => __( 'Credits' ),
		'owe' => __( 'Owes' )
	);

	return $columns;
}

add_action( 'manage_order_posts_custom_column', 'my_manage_order_columns', 10, 2 );

function my_manage_order_columns( $column ) {
	
	global $post;
	$id = get_the_ID();
	
	if( $column == 'name' )
	{
		$orderName = get_post_meta( $id, 'order_name');

		if($orderName ) echo $orderName[0];
		
	}
	elseif( $column == 'credit' )
	{
		$orderCredit = get_post_meta( $id, 'order_credit');

		if( $orderCredit ) echo '$' . $orderCredit[0];
		
	}
	elseif( $column == 'owes' )
	{
		$orderOwe = get_post_meta( $id, 'order_owe');

		if( $orderOwe ) echo '$' . $orderOwe[0];
		
	}
}

/*-------------------------------------------------------------------------------
	Sortable Columns
-------------------------------------------------------------------------------*/

function my_column_register_sortable( $columns )
{
	// $columns['name'] = 'name';
	$columns = array(
		'name' => 'name',
		'credit' => 'credit',
		'owe' => 'owe'
	);
	return $columns;
}

add_filter("manage_edit-order_sortable_columns", "my_column_register_sortable" );


/* Only run our customization on the 'edit.php' page in the admin. */
add_action( 'load-edit.php', 'my_edit_order_load' );

function my_edit_order_load() {
	add_filter( 'request', 'my_sort_order' );
}

/* Sorts the movies. */
function my_sort_order( $vars ) {

	/* Check if we're viewing the 'movie' post type. */
	if ( isset( $vars['post_type'] ) && 'order' == $vars['post_type'] ) {

		/* Check if 'orderby' is set to 'duration'. */
		if ( isset( $vars['orderby'] ) && 'name' == $vars['orderby'] ) {

			/* Merge the query vars with our custom variables. */
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'order_name',
					'orderby' => 'meta_value_num'
				)
			);
		}
	}

	return $vars;
}
