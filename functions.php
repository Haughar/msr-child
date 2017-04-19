<?php

add_action( 'wp_enqueue_scripts', 'msr_child_enqueue_styles' );
function msr_child_enqueue_styles() {
    $parent_style = 'msr'; 
 
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'msr-cild',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version'));
}

function display_generic_comments($comments_array) {
	
}

function get_percentage_to_goal($amount, $goal) {
	$math = floatval($amount / $goal);
	return round($math, 2) * 100;
}

function get_fundraising_days_left($end_date) {
	$end = strtotime($end_date);
	$today = time();

	$difference = $end - $today;
	return floor($difference/60/60/24);
}

function new_contribution($post, $current_user) {
	$commentdata = array(
		'comment_post_ID' => $post->ID, // to which post the comment will show up
		'comment_author' => $current_user->name, //fixed value - can be dynamic 
		'comment_author_email' => 'someone@example.com', //fixed value - can be dynamic 
		'comment_author_url' => 'http://example.com', //fixed value - can be dynamic 
		'comment_content' => '$1000', //fixed value - can be dynamic 
		'comment_type' => 'contribution', //empty for regular comments, 'pingback' for pingbacks, 'trackback' for trackbacks
		'comment_parent' => 0, //0 if it's not a reply to another comment; if it's a reply, mention the parent comment ID here
		'user_id' => $current_user->ID, //passing current user ID or any predefined as per the demand
	);

	wp_new_comment( $commentdata );
}

function new_share($post, $current_user) {
	$commentdata = array(
		'comment_post_ID' => $post->ID, // to which post the comment will show up
		'comment_author' => $current_user->name, //fixed value - can be dynamic 
		'comment_author_email' => 'someone@example.com', //fixed value - can be dynamic 
		'comment_author_url' => 'http://example.com', //fixed value - can be dynamic 
		'comment_content' => 'Facebook', //fixed value - can be dynamic 
		'comment_type' => 'share', //empty for regular comments, 'pingback' for pingbacks, 'trackback' for trackbacks
		'comment_parent' => 0, //0 if it's not a reply to another comment; if it's a reply, mention the parent comment ID here
		'user_id' => $current_user->ID, //passing current user ID or any predefined as per the demand
	);

	wp_new_comment( $commentdata );
}

function create_new_fundraiser() {
	if ( empty($_POST) || !wp_verify_nonce($_POST['create_fundraiser_nonce'],'create_fundraiser_action') ){
		print 'Sorry, your nonce did not verify.';
		exit;
	}

	// input validation goes here

	$fundraiser = array(
		'post_title' => $_POST['fundraiser-name'],
		'post_content' => $_POST['description'],
		'meta_input' => array (
			'fundraiser-tagline' => $_POST['tagline'],
			'fundraiser-goal' => $_POST['fundraiser-goal'],
			'fundraiser-amount-raised' => '0',
			'fundraiser-start' => $_POST['start-date'],
			'fundraiser-end' => $_POST['end-date']
		),
		'post_status' => 'publish',            // Choose: publish, preview, future, etc.
		'post_type' => $_POST['post-type']  // Use a custom post type if you want to
	);

	$new_fundraiser = wp_insert_post($fundraiser);

	if (!function_exists('wp_generate_attachment_metadata')){
        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');
    }
    if ($_FILES) {
        foreach ($_FILES as $file => $array) {
            if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
                return "upload error : " . $_FILES[$file]['error'];
            }
            $attach_id = media_handle_upload( $file, $new_fundraiser );
        }   
    }
    if ($attach_id > 0){
        //and if you want to set that image as Post  then use:
        update_post_meta($new_fundraiser,'_thumbnail_id',$attach_id);
    }


	wp_redirect( get_permalink($new_fundraiser) );
}

function edit_fundraiser($id) {
	if ( empty($_POST) || !wp_verify_nonce($_POST['create_fundraiser_nonce'],'create_fundraiser_action') ){
		print 'Sorry, your nonce did not verify.';
		exit;
	}

	$edited_post = array(
		'ID' => $id,
		'post_content' => $_POST['fundraiser-description']
	);

	wp_update_post($edited_post);

	update_fundraiser($id, 'fundraiser_form', 'fundraiser-tagline');
	flush_rewrite_rules();
}

function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
}
?>