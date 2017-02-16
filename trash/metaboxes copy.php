<?php  

/* Fire our meta box setup function on the post editor screen. */
add_action( 'load-post.php', 'urbegg_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'urbegg_post_meta_boxes_setup' );

/* Meta box setup function. */
function urbegg_post_meta_boxes_setup() {

  /* Add meta boxes on the 'add_meta_boxes' hook. */
  add_action( 'add_meta_boxes', 'urbegg_add_post_meta_boxes' );

  /* Save post meta on the 'save_post' hook. */
  add_action( 'save_post', 'urbegg_save_post_class_meta', 10, 2 );

   /* Save post meta on the 'save_post' hook. */
  add_action( 'save_post', 'urbegg_save_post_class_meta_date', 10, 2 );
}


/* Create one or more meta boxes to be displayed on the post editor screen. */
function urbegg_add_post_meta_boxes() {

	// Date Picker
  add_meta_box(
    'urbegg-date-pick',      // Unique ID
    esc_html__( 'Date', 'example' ),    // Title
    'num_eggs_meta_box',   // Callback function
    'egg',         // Admin page (or post type)
    'egghigh',         // Context
    'default'         // Priority
  );


	// Number of Eggs 
  add_meta_box(
    'urbegg-post-class',      // Unique ID
    esc_html__( 'Number of eggs', 'example' ),    // Title
    'num_eggs_meta_box',   // Callback function
    'egg',         // Admin page (or post type)
    'egghigh',         // Context
    'default'         // Priority
  );

  
}

// Move Metabox above Editor
function urbegg_move_deck() {
	# Get the globals:
	global $post, $wp_meta_boxes;

	# Output the "advanced" meta boxes:
	do_meta_boxes( get_current_screen(), 'egghigh', $post );

	# Remove the initial "advanced" meta boxes:
	unset($wp_meta_boxes['post']['egghigh']);
}

add_action('edit_form_after_title', 'urbegg_move_deck');

/* Display the post meta box. */
function num_eggs_meta_box( $object, $box ) { ?>

  <?php wp_nonce_field( basename( __FILE__ ), 'num_eggs_nonce' ); ?>
  <p>
    <label for="urbegg-date-pick"><?php _e( "Date you picked the eggs.", 'example' ); ?></label>
    <br />
    <input class="widefat js-datepicker" type="text" name="urbegg-date-pick" id="urbegg-date-pick" value="<?php echo esc_attr( get_post_meta( $object->ID, 'date_pick_eggs', true ) ); ?>" size="30" />
  </p>
  <p>
    <label for="urbegg-post-class"><?php _e( "How many did you get today?", 'example' ); ?></label>
    <br />
    <input class="widefat" type="number" name="urbegg-post-class" id="urbegg-post-class" value="<?php echo esc_attr( get_post_meta( $object->ID, 'num_eggs', true ) ); ?>" size="30" />
  </p>
<?php }

/* Save the meta box's post metadata. */
function urbegg_save_post_class_meta( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['num_eggs_nonce'] ) || !wp_verify_nonce( $_POST['num_eggs_nonce'], basename( __FILE__ ) ) )
	return $post_id;

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
	return $post_id;

	/* Get the posted data and sanitize it for use as an HTML class. */
	$new_meta_value = ( isset( $_POST['urbegg-post-class'] ) ? sanitize_html_class( $_POST['urbegg-post-class'] ) : '' );

	/* Get the meta key. */
	$meta_key = 'num_eggs';

	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );

	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value && '' == $meta_value )
	add_post_meta( $post_id, $meta_key, $new_meta_value, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value && $new_meta_value != $meta_value )
	update_post_meta( $post_id, $meta_key, $new_meta_value );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value && $meta_value )
	delete_post_meta( $post_id, $meta_key, $meta_value );

}
/* Save the meta box's date field. */
function urbegg_save_post_class_meta_date( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['num_eggs_nonce'] ) || !wp_verify_nonce( $_POST['num_eggs_nonce'], basename( __FILE__ ) ) )
	return $post_id;

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
	return $post_id;

	/* Get the posted data and sanitize it for use as an HTML class. */
	$new_meta_value = ( isset( $_POST['urbegg-date-pick'] ) ? sanitize_html_class( $_POST['urbegg-date-pick'] ) : '' );

	/* Get the meta key. */
	$meta_key = 'date_pick_eggs';

	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );

	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value && '' == $meta_value )
	add_post_meta( $post_id, $meta_key, $new_meta_value, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value && $new_meta_value != $meta_value )
	update_post_meta( $post_id, $meta_key, $new_meta_value );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value && $meta_value )
	delete_post_meta( $post_id, $meta_key, $meta_value );

}