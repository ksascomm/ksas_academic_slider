<?php
/*
Plugin Name: KSAS Homepage Slider for Academic Template
Plugin URI: https://github.com/ksascomm/ksas_academic_slider
Description: Creates a custom post type for homepage slider.  This plugin is currently configured to only work with the Academic Template
Version: 2.0
Author: KSAS Communications
Author URI: mailto:ksasweb@jhu.edu
License: GPL2
*/
// hook into the init action and call create_book_taxonomies when it fires
add_action( 'init', 'create_slider_taxonomies', 0 );

// create three taxonomies for the post type "slider"
function create_slider_taxonomies() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
			'name' 					=> _x( 'Slider Types', 'taxonomy general name' ),
			'singular_name' 		=> _x( 'Slider Type', 'taxonomy singular name' ),
			'add_new' 				=> _x( 'Add New Slider Type', 'Slider Type'),
			'add_new_item' 			=> __( 'Add New Slider Type' ),
			'edit_item' 			=> __( 'Edit Slider Type' ),
			'new_item' 				=> __( 'New Slider Type' ),
			'view_item' 			=> __( 'View Slider Type' ),
			'search_items' 			=> __( 'Search Slider Types' ),
			'not_found' 			=> __( 'No Slider Type found' ),
			'not_found_in_trash' 	=> __( 'No Slider Type found in Trash' ),
		);

		$args = array(
			'labels' 			=> $labels,
			'singular_label' 	=> __('Slider Type'),
			'public' 			=> true,
			'show_ui' 			=> true,
			'hierarchical' 		=> true,
			'show_tagcloud' 	=> false,
			'show_in_nav_menus' => false,
			'rewrite' 			=> array('slug' => 'slider', 'with_front' => false ),
		 );
	register_taxonomy( 'slider_type', 'slider', $args );
}


// registration code for slider post type
	function register_slider_posttype() {
		$labels = array(
			'name' 				=> _x( 'Slides', 'post type general name' ),
			'singular_name'		=> _x( 'Slide', 'post type singular name' ),
			'add_new' 			=> _x( 'Add New', 'Slide'),
			'add_new_item' 		=> __( 'Add New Slide '),
			'edit_item' 		=> __( 'Edit Slide '),
			'new_item' 			=> __( 'New Slide '),
			'view_item' 		=> __( 'View Slide '),
			'search_items' 		=> __( 'Search Slides '),
			'not_found' 		=>  __( 'No Slide found' ),
			'not_found_in_trash'=> __( 'No Slides found in Trash' ),
			'parent_item_colon' => ''
		);
		
		//$taxonomies = array();
		
		$supports = array('title', 'editor','revisions', 'thumbnail');
		
		$post_type_args = array(
			'labels' 			=> $labels,
			'singular_label' 	=> __('Slide'),
			'public' 			=> true,
			'show_ui' 			=> true,
			'publicly_queryable'=> true,
			'exclude_from_search' => true,
			'query_var'			=> true,
			'capability_type'   => 'slide',
			'capabilities' => array(
				'publish_posts' => 'publish_slides',
				'edit_posts' => 'edit_slides',
				'edit_others_posts' => 'edit_others_slides',
				'delete_posts' => 'delete_slides',
				'delete_others_posts' => 'delete_others_slides',
				'read_private_posts' => 'read_private_slides',
				'edit_post' => 'edit_slide',
				'delete_post' => 'delete_slide',
				'read_post' => 'read_slide',),			
			'has_archive' 		=> false,
			'hierarchical' 		=> false,
			'rewrite' 			=> array('slug' => 'slider', 'with_front' => false ),
			'supports' 			=> $supports,
			'menu_position' 	=> 5,
			//'taxonomies'		=> $taxonomies
		 );
		 register_post_type('slider',$post_type_args);
	}
	add_action('init', 'register_slider_posttype');

//Add Slider details metabox
$sliderinfo_2_metabox = array( 
	'id' => 'sliderinfo',
	'title' => 'Slider Info',
	'page' => array('slider'),
	'context' => 'normal',
	'priority' => 'default',
	'fields' => array(

				
				array(
					'name' 			=> 'Slide Image',
					'desc' 			=> 'Image needs to be 670x360 for open themes 1000x425 for flagship themes',
					'id' 			=> 'ecpt_slideimage',
					'class' 		=> 'ecpt_slideimage',
					'type' 			=> 'upload',
					'rich_editor' 	=> 1,			
					'max' 			=> 0,
					'std'			=> ''													
				),
																														
				array(
					'name' 			=> 'URL Destination',
					'desc' 			=> 'Enter url of destination page',
					'id' 			=> 'ecpt_urldestination',
					'class' 		=> 'ecpt_urldestination',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''													
				),
															
				array(
					'name' 			=> 'Find Out More Button',
					'desc' 			=> 'Add a Find Out More button after the caption',
					'id' 			=> 'ecpt_button',
					'class' 		=> 'ecpt_button',
					'type' 			=> 'checkbox',
					'rich_editor' 	=> 1,			
					'max' 			=> 0,
					'std'			=> ''													
				),
												)
);			
			
add_action('admin_menu', 'ecpt_add_sliderinfo_2_meta_box');
function ecpt_add_sliderinfo_2_meta_box() {

	global $sliderinfo_2_metabox;		

	foreach($sliderinfo_2_metabox['page'] as $page) {
		add_meta_box($sliderinfo_2_metabox['id'], $sliderinfo_2_metabox['title'], 'ecpt_show_sliderinfo_2_box', $page, 'normal', 'default', $sliderinfo_2_metabox);
	}
}

// function to show meta boxes
function ecpt_show_sliderinfo_2_box()	{
	global $post;
	global $sliderinfo_2_metabox;
	global $ecpt_prefix;
	global $wp_version;
	
	// Use nonce for verification
	echo '<input type="hidden" name="ecpt_sliderinfo_2_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	echo '<table class="form-table">';

	foreach ($sliderinfo_2_metabox['fields'] as $field) {
		// get current post meta data

		$meta = get_post_meta($post->ID, $field['id'], true);
		
		echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td class="ecpt_field_type_' . str_replace(' ', '_', $field['type']) . '">';
		switch ($field['type']) {
			case 'text':
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" /><br/>', '', $field['desc'];
				break;
			case 'date':
				if($meta) { $value = ecpt_timestamp_to_date($meta); } else {  $value = ''; }
				echo '<input type="text" class="ecpt_datepicker" name="' . $field['id'] . '" id="' . $field['id'] . '" value="'. $value . '" size="30" style="width:97%" />' . '' . $field['desc'];
				break;
			case 'upload':
				echo '<input type="text" class="ecpt_upload_field" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:80%" /><br/>', '', stripslashes($field['desc']);
				break;
			case 'textarea':
			
				if($field['rich_editor'] == 1) {
					if($wp_version >= 3.3) {
						echo wp_editor($meta, $field['id'], array('textarea_name' => $field['id'], 'wpautop' => false));
					} else {
						// older versions of WP
						$editor = '';
						if(!post_type_supports($post->post_type, 'editor')) {
							$editor = wp_tiny_mce(true, array('editor_selector' => $field['class'], 'remove_linebreaks' => false) );
						}
						$field_html = '<div style="width: 97%; border: 1px solid #DFDFDF;"><textarea name="' . $field['id'] . '" class="' . $field['class'] . '" id="' . $field['id'] . '" cols="60" rows="8" style="width:100%">'. $meta . '</textarea></div><br/>' . __($field['desc']);
						echo $editor . $field_html;
					}
				} else {
					echo '<div style="width: 100%;"><textarea name="', $field['id'], '" class="', $field['class'], '" id="', $field['id'], '" cols="60" rows="8" style="width:97%">', $meta ? $meta : $field['std'], '</textarea></div>', '', $field['desc'];				
				}
				
				break;
			case 'select':
				echo '<select name="', $field['id'], '" id="', $field['id'], '">';
				foreach ($field['options'] as $option) {
					echo '<option value="' . $option . '"', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
				}
				echo '</select>', '', $field['desc'];
				break;
			case 'radio':
				foreach ($field['options'] as $option) {
					echo '<input type="radio" name="', $field['id'], '" value="', $option, '"', $meta == $option ? ' checked="checked"' : '', ' />&nbsp;', $option;
				}
				echo '<br/>' . $field['desc'];
				break;
			case 'checkbox':
				echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />&nbsp;';
				echo $field['desc'];
				break;
			case 'slider':
				echo '<input type="text" rel="' . $field['max'] . '" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . $meta . '" size="1" style="float: left; margin-right: 5px" />';
				echo '<div class="ecpt-slider" rel="' . $field['id'] . '" style="float: left; width: 60%; margin: 5px 0 0 0;"></div>';		
				echo '<div style="width: 100%; clear: both;">' . $field['desc'] . '</div>';
				break;
			case 'repeatable' :
				
				$field_html = '<input type="hidden" id="' . $field['id'] . '" class="ecpt_repeatable_field_name" value=""/>';
				if(is_array($meta)) {
					$count = 1;
					foreach($meta as $key => $value) {
						$field_html .= '<div class="ecpt_repeatable_wrapper"><input type="text" class="ecpt_repeatable_field" name="' . $field['id'] . '[' . $key . ']" id="' . $field['id'] . '[' . $key . ']" value="' . $meta[$key] . '" size="30" style="width:90%" />';
						if($count > 1) {
							$field_html .= '<a href="#" class="ecpt_remove_repeatable button-secondary">x</a><br/>';
						}
						$field_html .= '</div>';
						$count++;
					}
				} else {
					$field_html .= '<div class="ecpt_repeatable_wrapper"><input type="text" class="ecpt_repeatable_field" name="' . $field['id'] . '[0]" id="' . $field['id'] . '[0]" value="' . $meta . '" size="30" style="width:90%" /></div>';
				}
				$field_html .= '<button class="ecpt_add_new_field button-secondary">' . __('Add New', 'ecpt') . '</button>&nbsp;&nbsp;' . __(stripslashes($field['desc']));
				
				echo $field_html;
				
				break;
			
			case 'repeatable upload' :
			
				$field_html = '<input type="hidden" id="' . $field['id'] . '" class="ecpt_repeatable_upload_field_name" value=""/>';
				if(is_array($meta)) {
					$count = 1;
					foreach($meta as $key => $value) {
						$field_html .= '<div class="ecpt_repeatable_upload_wrapper"><input type="text" class="ecpt_repeatable_upload_field ecpt_upload_field" name="' . $field['id'] . '[' . $key . ']" id="' . $field['id'] . '[' . $key . ']" value="' . $meta[$key] . '" size="30" style="width:80%" /><button class="button-secondary ecpt_upload_image_button">Upload File</button>';
						if($count > 1) {
							$field_html .= '<a href="#" class="ecpt_remove_repeatable button-secondary">x</a><br/>';
						}
						$field_html .= '</div>';
						$count++;
					}
				} else {
					$field_html .= '<div class="ecpt_repeatable_upload_wrapper"><input type="text" class="ecpt_repeatable_upload_field ecpt_upload_field" name="' . $field['id'] . '[0]" id="' . $field['id'] . '[0]" value="' . $meta . '" size="30" style="width:80%" /><input class="button-secondary ecpt_upload_image_button" type="button" value="Upload File" /></div>';
				}
				$field_html .= '<button class="ecpt_add_new_upload_field button-secondary">' . __('Add New', 'ecpt') . '</button>&nbsp;&nbsp;' . __(stripslashes($field['desc']));		
			
				echo $field_html;
			
				break;
		}
		echo     '<td>',
			'</tr>';
	}
	
	echo '</table>';
}	

add_action('save_post', 'ecpt_sliderinfo_2_save');

// Save data from meta box
function ecpt_sliderinfo_2_save($post_id) {
	global $post;
	global $sliderinfo_2_metabox;
	
	// verify nonce
	if (!isset($_POST['ecpt_sliderinfo_2_meta_box_nonce']) || !wp_verify_nonce($_POST['ecpt_sliderinfo_2_meta_box_nonce'], basename(__FILE__))) {
		return $post_id;
	}

	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}

	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}
	
	foreach ($sliderinfo_2_metabox['fields'] as $field) {
	
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		
		if ($new && $new != $old) {
			if($field['type'] == 'date') {
				$new = ecpt_format_date($new);
				update_post_meta($post_id, $field['id'], $new);
			} else {
				update_post_meta($post_id, $field['id'], $new);
				
				
			}
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}

// Add to admin_init function
add_filter('manage_edit-slider_columns', 'my_slider_columns');

function my_slider_columns($columns) {
    $new_columns['cb'] = '<input type="checkbox" />';
    $new_columns['title'] = _x('Title', 'column name');
    $new_columns['type'] = __('Slider Type'); 
    $new_columns['image'] = __('Thumbnail');     
    return $new_columns;
}

// Add to admin_init function
add_action('manage_slider_posts_custom_column', 'my_manage_slider_columns', 10, 2);
 
function my_manage_slider_columns($column_name, $post_id) {
    global $post;
    switch ($column_name) {
    case 'image':
		if(has_post_thumbnail( $post->ID )) {
				echo the_post_thumbnail('medium');
				}
			/* If there is a duration, append 'minutes' to the text string. */
			else {
				
				echo __( 'No Thumbnail' );
			}
        break;
		/* If displaying the 'program_type' column. */

		case 'type' :

			/* Get the program_types for the post. */
			$terms = get_the_terms( $post_id, 'slider_type' );

			/* If terms were found. */
			if ( !empty( $terms ) ) {

				$out = array();

				/* Loop through each term, linking to the 'edit posts' page for the specific term. */
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'slider_type' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'slider_type', 'display' ) )
					);
				}

				/* Join the terms, separating them with a comma. */
				echo join( ', ', $out );
			}

			/* If no terms were found, output a default message. */
			else {
				_e( 'No Slider Type Assigned' );
			}

			break;        
    default:
        break;
    } // end switch
}

function define_slider_type_terms() {
	$terms = array(
		'0' => array( 'name' => 'Front Hero','slug' => 'front'),
		'1' => array( 'name' => 'Research Area','slug' => 'research'),
		'2' => array( 'name' => 'Program Parent','slug' => 'program'),
    	);
    return $terms;
}

function check_slider_type_terms(){

	//see if we already have populated any terms
	$terms = get_terms ('slider_type', array( 'hide_empty' => false ) );

	//if no terms then lets add our terms
	  if( empty( $terms ) ){
	$terms = array(
		'0' => array( 'name' => 'Front Hero','slug' => 'front'),
		'1' => array( 'name' => 'Research Area','slug' => 'research'),
		'2' => array( 'name' => 'Program Parent','slug' => 'program'),
    	);
        foreach( $terms as $term ){
            if( !term_exists( $term['name'], 'slider_type' ) ){
                wp_insert_term( $term['name'], 'slider_type', array( 'slug' => $term['slug'] ) );
            }
        }
    }

}

add_action ( 'init', 'check_slider_type_terms' ); 


?>