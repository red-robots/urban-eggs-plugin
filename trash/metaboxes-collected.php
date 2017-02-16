<?php 

// Add the Meta Box
function add_custom_meta_box() {
    add_meta_box(
        'custom_meta_box', // $id
        'Egg Collection Information', // $title 
        'egg_show_custom_meta_box', // $callback
        'egg', // $page
        'egghigh', // $context
        'high'); // $priority
}
add_action('add_meta_boxes', 'add_custom_meta_box');


// Field Array
$prefix = 'egg_';
$custom_meta_fields = array(
    array(
        'label'=> 'Egg Collection Date',
        'desc'  => 'Click in the field to choose a date.',
        'id'    => $prefix.'date',
        'class' => 'js-datepicker',
        'type'  => 'text'
    ),
    array(
        'label'=> 'How many eggs did you collect',
        'desc'  => 'A description for the field.',
        'id'    => $prefix.'collect',
        'type'  => 'number'
    ),
    array(
        'label'=> 'Notes',
        'desc'  => 'A description for the field.',
        'id'    => $prefix.'notes',
        'type'  => 'textarea'
    ),
    // array(
    //     'label'=> 'Checkbox Input',
    //     'desc'  => 'A description for the field.',
    //     'id'    => $prefix.'checkbox',
    //     'type'  => 'checkbox'
    // ),
    // array(
    //     'label'=> 'Select Box',
    //     'desc'  => 'A description for the field.',
    //     'id'    => $prefix.'select',
    //     'type'  => 'select',
    //     'options' => array (
    //         'one' => array (
    //             'label' => 'Option One',
    //             'value' => 'one'
    //         ),
    //         'two' => array (
    //             'label' => 'Option Two',
    //             'value' => 'two'
    //         ),
    //         'three' => array (
    //             'label' => 'Option Three',
    //             'value' => 'three'
    //         )
    //     )
    // )
);
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


// The Callback
function egg_show_custom_meta_box() {
global $custom_meta_fields, $post;
// Use nonce for verification
wp_nonce_field( basename( __FILE__ ), 'num_eggs_nonce' );
     
    // Begin the field table and loop
    echo '<table class="form-table">';
    foreach ($custom_meta_fields as $field) {
        // get value of this field if it exists for this post
        $meta = get_post_meta($post->ID, $field['id'], true);
        // begin a table row with
        echo '<tr>
                <th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
                <td>';
                switch($field['type']) {
                    // case items will go here
                    // text
					case 'text':
					    echo '<input type="text" class="'.$field['class'].'" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
					        <br /><span class="description">'.$field['desc'].'</span>';
					break;
					// text
					case 'number':
					    echo '<input type="number" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
					        <br /><span class="description">'.$field['desc'].'</span>';
					break;
					// textarea
					case 'textarea':
					    echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea>
					        <br /><span class="description">'.$field['desc'].'</span>';
					break;
                } //end switch
        echo '</td></tr>';
    } // end foreach
    echo '</table>'; // end table
}


// Save the Data
function save_custom_meta($post_id) {
    global $custom_meta_fields;
     
    /* Verify the nonce before proceeding. */
	if ( !isset( $_POST['num_eggs_nonce'] ) || !wp_verify_nonce( $_POST['num_eggs_nonce'], basename( __FILE__ ) ) )
	return $post_id;
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;
    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
    }
     
    // loop through fields and save the data
    foreach ($custom_meta_fields as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    } // end foreach
}
add_action('save_post', 'save_custom_meta');