<?php 

// Add the Meta Box
function add_custom_meta_box_orders() {
    add_meta_box(
        'custom_meta_box_orders', // $id
        'Order Information', // $title 
        'egg_show_custom_meta_box_orders', // $callback
        'order', // $page
        'egghigh', // $context
        'high'); // $priority
}
add_action('add_meta_boxes', 'add_custom_meta_box_orders');


// Field Array
$prefix = 'order_';
$custom_meta_fields_orders = array(
    array(
        'label'=> 'Order Date',
        'desc'  => 'Click in the field to choose a date.',
        'id'    => $prefix.'date',
        'class' => 'js-datepicker',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Name',
        'desc'  => 'Please fill in your name.',
        'id'    => $prefix.'name',
        'class' => 'name',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Phone',
        'desc'  => 'Please fill in your phone number.',
        'id'    => $prefix.'phone',
        'class' => 'phone',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Email',
        'desc'  => '',
        'id'    => $prefix.'email',
        'class' => 'email',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Number of Eggs',
        'desc'  => 'How many eggs would you like to purchase?',
        'id'    => $prefix.'select',
        'type'  => 'select',
        'options' => array (
            'pick' => array (
                'label' => 'Select number of eggs',
                'value' => 'select_eggs'
            ),
            'one' => array (
                'label' => 'One Dozen',
                'value' => '1 Dozen'
            ),
            'two' => array (
                'label' => 'Two Dozen',
                'value' => '2 Dozen'
            ),
            'three' => array (
                'label' => 'Three Dozen',
                'value' => '3 Dozen'
            )
        )
    ),
     array(
        'label'=> 'Paid',
        'desc'  => 'How much did the customer pay?',
        'id'    => $prefix.'pay',
        'class' => 'pay',
        'type'  => 'number'
    ),
    array(
        'label'=> 'Credit',
        'desc'  => 'How much does the customer have for credit?',
        'id'    => $prefix.'credit',
        'class' => 'credit',
        'type'  => 'number'
    ),
     array(
        'label'=> 'Owe',
        'desc'  => 'How much does the customer owe?',
        'id'    => $prefix.'owe',
        'class' => 'owe',
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
    
);
// Move Metabox above Editor
function urbegg_move_deck_orders() {
	# Get the globals:
	global $post, $wp_meta_boxes;

	# Output the "advanced" meta boxes:
	do_meta_boxes( get_current_screen(), 'egghigh', $post );

	# Remove the initial "advanced" meta boxes:
	unset($wp_meta_boxes['post']['egghigh']);
}

add_action('edit_form_after_title', 'urbegg_move_deck');


// The Callback
function egg_show_custom_meta_box_orders() {
global $custom_meta_fields_orders, $post;
// Use nonce for verification
wp_nonce_field( basename( __FILE__ ), 'num_eggs_nonce_orders' );
     
    // Begin the field table and loop
    echo '<table class="form-table">';
    foreach ($custom_meta_fields_orders as $field) {
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
                    case 'number':
                        echo '<input type="number" class="'.$field['class'].'" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
                            <br /><span class="description">'.$field['desc'].'</span>';
                    break;
					// select
                    case 'select':
                        echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
                        foreach ($field['options'] as $option) {
                            echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';
                        }
                        echo '</select><br /><span class="description">'.$field['desc'].'</span>';
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
function save_custom_meta_orders($post_id) {
    global $custom_meta_fields_orders;
     
    /* Verify the nonce before proceeding. */
	if ( !isset( $_POST['num_eggs_nonce_orders'] ) || !wp_verify_nonce( $_POST['num_eggs_nonce_orders'], basename( __FILE__ ) ) )
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
    foreach ($custom_meta_fields_orders as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    } // end foreach
}
add_action('save_post', 'save_custom_meta_orders');