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

function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
}
?>