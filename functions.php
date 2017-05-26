<?php

include 'svg-icons.php';

function msr_child_enqueue_styles() {
    $parent_style = 'msr-style'; 
 	
 	wp_enqueue_style( 'bootstrap_css', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' );
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'msr-cild-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version'));
}


add_action( 'wp_enqueue_scripts', 'msr_child_enqueue_styles' );

function msr_child_enqueue_js() {
	global $wp_scripts;
	wp_enqueue_script( 'bootstrap_js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js');
}

add_action( 'wp_enqueue_scripts', 'msr_child_enqueue_js');

remove_filter('get_the_content', 'wpautop');

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
	return floor($difference/60/60/24) + 2;
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
		echo 'Sorry, your nonce did not verify.';
		exit;
	}
	// input validation goes here
	$fundraiser = array(
		'post_title' => $_POST['fundraiserName'],
		'post_content' => $_POST['description'],
		'meta_input' => array (
			'fundraiser-goal' => $_POST['fundraiserGoal'],
			'fundraiser-start' => $_POST['startDate'],
			'fundraiser-end' => $_POST['endDate']
		),
		'post_status' => 'pending',            // Choose: publish, preview, future, etc.
		'post_type' => $_POST['post-type']  // Use a custom post type if you want to
	);

	$new_fundraiser = wp_insert_post($fundraiser);
	$attach_id = 0;
	if ($_POST['defaultImage'] != "not-here") {
		$attach_id = intval($_POST['defaultImage']);
	} else {

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
	}
    if ($attach_id > 0){
        update_post_meta($new_fundraiser,'_thumbnail_id',$attach_id);
    }
    var_dump($_FILES);

    wp_redirect( home_url() . '/edit-fundraiser/?post_id=' . $new_fundraiser . '&new=true' );
}

function edit_fundraiser($id) {
	if ( empty($_POST) || !wp_verify_nonce($_POST['create_fundraiser_nonce'],'create_fundraiser_action') ){
		print 'Sorry, your nonce did not verify.';
		exit;
	}

	$edited_post = array(
		'ID' => $id,
		'post_title' => $_POST['fundraiserName'],
		'post_content' => $_POST['description'],
		'meta_input' => array (
			'fundraiser-goal' => $_POST['fundraiserGoal'],
			'fundraiser-end' => $_POST['endDate']
		),
		'post_content' => $_POST['description']
	);

	wp_update_post($edited_post);
	flush_rewrite_rules();
}

function leave_comment($post_id, $customer_name, $email, $content, $user_id) {
 	$commentdata = array(
 		'comment_post_ID' => $post_id, // to which post the comment will show up
 		'comment_author' => $customer_name, //fixed value - can be dynamic 
 		'comment_author_email' => $email, //fixed value - can be dynamic 
 		'comment_author_url' => 'http://doesntmatter.com', //fixed value - can be dynamic 
 		'comment_content' => $content, //fixed value - can be dynamic 
 		'comment_type' => '', //empty for regular comments, 'pingback' for pingbacks, 'trackback' for trackbacks
 		'comment_parent' => 0, //0 if it's not a reply to another comment; if it's a reply, mention the parent comment ID here
 		'user_id' => $user_id
 	);
 
 	wp_new_comment( $commentdata );
 }

function get_customer_contributions($user_id) {
	global $stripe_options;

	// load the stripe libraries
	require_once(STRIPE_BASE_DIR . '/lib/latest/init.php');	

	// check if we are using test mode
	if(isset($stripe_options['test_mode']) && $stripe_options['test_mode']) {
		$secret_key = $stripe_options['test_secret_key'];
	} else {
		$secret_key = $stripe_options['live_secret_key'];
	}

	\Stripe\Stripe::setApiKey($secret_key);

	$customer_id = get_user_meta( $user_id, '_stripe_customer_id', true);

	$json_object = [];
	if($customer_id) {
		$charges = \Stripe\Charge::all(array('customer' => $customer_id, 'limit' => 100));

		$subscription = \Stripe\Subscription::all(array('customer' => $customer_id));
		$subscription_json = $subscription->__toJSON();
		$sub_array = json_decode($subscription_json, true);
		$sub_data = $sub_array['data'];

		$json_object['recurring_donation'] = !empty($sub_data);
		if (!empty($sub_data)) {
			$json_object['subscription_data'] = $sub_data;
		}

		$current_month = date("m", time());

		$months = array_fill(0, 6, 0);
		$contributions = [];

		$total = 0;
		if($charges) {
			foreach($charges['data'] as $data) {
				if($data['refunded'] == false) {
					$month = date('m', intval($data['created']));
					$diff = (intval($current_month) - intval($month) + 12) % 12;
					$dollars = $data['amount'] / 100;
					switch($diff) {
						case 0:
							$months[0] += $dollars;
							break;
						case 1:
							$months[1] += $dollars;
							break;
						case 2:
							$months[2] += $dollars;
							break;
						case 3:
							$months[3] += $dollars;
							break;
						case 4:
							$months[4] += $dollars;
							break;
						case 5:
							$months[5] += $dollars;
							break;
					}
					$total += $data['amount'];
				}				
			}
		}
		$total = $total / 100; 

		$json_object['total'] = $total;
		$json_object['contribution-data'] = $months;
		$json_object['charge-data'] = $charges['data'];
	} else {
		$json_object['total'] = 0;
	}
	return $json_object;
}

function get_fundraiser_stripe_info($post_id) {
	global $stripe_options;

	// load the stripe libraries
	require_once(STRIPE_BASE_DIR . '/lib/latest/init.php');	

	// check if we are using test mode
	if(isset($stripe_options['test_mode']) && $stripe_options['test_mode']) {
		$secret_key = $stripe_options['test_secret_key'];
	} else {
		$secret_key = $stripe_options['live_secret_key'];
	}

	\Stripe\Stripe::setApiKey($secret_key);

	$charges = \Stripe\Charge::all(array('limit' => 100)); // need to be able to do pagination

	$json_object = [];

	$contributions = [];

	$total = 0;
	if($charges) {
		foreach($charges['data'] as $data) {
			if ($data['description'] == $post_id) {
				array_push($contributions, $data);
				$total += $data['amount'];
			}
		}
	}
	$total = $total / 100;

	$json_object['total'] = $total;
	$json_object['contribution-data'] = $contributions;

	return $json_object;
}

function get_fundraiser_amount_raised($post_id) {
	global $stripe_options;

	// load the stripe libraries
	require_once(STRIPE_BASE_DIR . '/lib/latest/init.php');	

	// check if we are using test mode
	if(isset($stripe_options['test_mode']) && $stripe_options['test_mode']) {
		$secret_key = $stripe_options['test_secret_key'];
	} else {
		$secret_key = $stripe_options['live_secret_key'];
	}

	\Stripe\Stripe::setApiKey($secret_key);

	$charges = \Stripe\Charge::all(array('limit' => 100)); // need to be able to do pagination

	$contributions = [];

	$total = 0;
	if($charges) {
		foreach($charges['data'] as $data) {
			if ($data['description'] == $post_id) {
				$total += $data['amount'];
			}
		}
	}
	$total = $total / 100;

	return $total;
}

function get_fundraiser_list($user_id, $type) {
	if($type == 'active') {
		$args = array(
		    'post_type' => 'fundraiser',
		  	'post_status' => 'publish',
		  	'author' => $user_id
		); 
	} else if ($type == 'pending') {
		$args = array(
		    'post_type' => 'fundraiser',
		  	'post_status' => 'pending',
		  	'author' => $user_id
		); 
	} else if($type == 'expired') {
		$args = array(
		    'post_type' => 'fundraiser',
		  	'post_status' => 'publish',
		  	'author' => $user_id
		);
	}

	$post_query = new WP_Query($args); 
	$count = 0;
	if($post_query->have_posts() ) {
		while($post_query->have_posts() ) {
			$post_query->the_post();
			$post = get_post();
			$id = $post->ID;
			$endDate = strtotime(get_post_meta($id, 'fundraiser-end', true));
			$difference = $endDate - time();
			$totalDiff = floor($difference/60/60/24);
			if($totalDiff >= 0 && $type == 'active') {
				$count++;
			} else if($type == 'expired') {
				$count++;
			} else if($post->post_status == "pending" && $type == "pending") {
				$count++;
			}
		}
	}
	if($type == 'active') { ?>
		<div class="user-profile-header">
			<p>Active Fundraisers <?php 
				echo '(' . $count . ')'; ?> 
			</p> 
		</div>
	<?php } else if ($type == 'pending' && $count > 0) { ?>
		 <div class="user-profile-header">
			<p>Pending Fundraisers <?php echo '(' . $post_query->post_count . ')'; ?> </p>
		</div>
	<?php } else if($type == 'expired') { ?>
		<div class="user-profile-header">
			<p>Past Fundraisers <?php
				echo '(' . $count . ')'; ?> 
			</p>
		</div>
	<?php } ?>
	<?php
	$totalRaised = 0;
	if ($count == 0 && $type != "pending") { 
		if($type == "active") { ?>
			<div class="recurr-div">
				<p class="none-p">You have no active fundraisers.</p> 
			</div>
			<div class="dashboard-space"></div>
		<?php } else if($type == "expired") { ?>
			<div class="recurr-div">
				<p class="none-p">You have no past fundraisers.</p> 
			</div>
		<?php } 
	} else if($post_query->have_posts() ) {
		while($post_query->have_posts() ) {
			$post_query->the_post();
			$post = get_post();
			$id = $post->ID;
			$endDate = strtotime(get_post_meta($id, 'fundraiser-end', true));
			$difference = $endDate - time();
			$totalDiff = floor($difference/60/60/24);
			$fundraiser_details = get_fundraiser_stripe_info($id);
			if (($totalDiff < 0) && ($type == 'expired')) { ?>
				<div class="dashb-fundraisers">
					<?php if ( has_post_thumbnail() ) {
						the_post_thumbnail( array(100,100) );
					} ?>
					<div class="fundraise-info inline-top">
					<?php
						if (get_the_title($id)) {
							?> <span class="normal-text"><a href="<?php echo get_permalink($id); ?>"><?php echo get_the_title($id); ?> </span></a><?php
						}  ?>
						<p class="date-text">Ended <?php 
							$sqldate = get_post_meta($id, 'fundraiser-end', true);
							$end = strtotime($sqldate);
							echo date('n/j/y', $end) ?> 
						</p>
					</div>
					<div class="inline-top dashb-amt">
						<!-- Amount Raised -->
						<span class="amt-text">$<?php
							$fundraised = $fundraiser_details['total'];
							$totalRaised += $fundraised;
							echo $fundraised; ?>			
						</span>
						<p class="raise-text"> raised</p>
					</div>
					<div class="inline-top manage-div"> 
						<button onclick="window.location.href='<?php echo home_url()?>'">Insights</button>
					</div>
				</div>
				<div class="dashboard-space"></div>
			<?php
			} else if (($totalDiff >= 0 && $type == 'active') || $type == 'pending') { ?>
				<div class="dashb-fundraisers">
					<?php if ( has_post_thumbnail() ) {
						the_post_thumbnail( array(100,100) );
					} ?>
					<div class="fundraise-info inline-top">
						<?php
						if (get_the_title($id)) {
							?> <span class="normal-text"><a href="<?php echo get_permalink($id); ?>"><?php echo get_the_title($id); ?></a> </span> <?php
						}  ?>
						<!-- Progress bar -->
						<div>
							<div class="myProgress inline-top">
						  		<div class="myBar" style="width: <?php 
						  			$pct = get_percentage_to_goal($fundraiser_details['total'],  get_post_meta($id, 'fundraiser-goal', true)); 
						  			if ($pct > 100) {
						  				$pct = 100;
						  			}
						  			echo $pct ?>%"></div>
							</div>
							<div class="pct inline-top"> 
								<!-- Percentage of amount made -->
								<span><?php echo get_percentage_to_goal($fundraiser_details['total'],  get_post_meta($id, 'fundraiser-goal', true)); ?>%</span>
							</div>
						</div>
						<!-- Amount of days remaining -->
						<span class="day-text <?php 
							if (get_fundraising_days_left(get_post_meta($id, 'fundraiser-end', true)) <= 10) {
								echo "red-text";
							}
						 	?>"><?php echo get_fundraising_days_left(get_post_meta($id, 'fundraiser-end', true)); ?> days left</span>
					</div>
					<div class="inline-top dashb-amt">
						<!-- Amount Raised -->
						<span class="amt-text">$<?php 
							$fundraised = $fundraiser_details['total'];
							$totalRaised += $fundraised;
							echo $fundraised; ?>
						</span>
						<p class="raise-text"> raised</p>
					</div>
					<div class="inline-top manage-div"> 
						<button onclick="window.location.href='<?php echo home_url() . '/edit-fundraiser?post_id=' . $id ?>'">Manage</button>
					</div>
				</div>
				<div class="dashboard-space"></div>
			<?php }
		}
	}
	return $totalRaised;
}

function create_contributions_list($user_id, $json_object) {
	foreach ($json_object['charge-data'] as $charge) { 
		if($charge['refunded'] == false) {
			if($charge['invoice']) { 
				// recurring payments ?>
				<div class="dashb-fundraisers">
					<img src="<?php echo home_url() . '/wp-content/uploads/2015/12/MSR_WEB-1.gif'; ?>" height="100" width="100">
					<div class="gen-contrib-info inline-top">
						<span class="normal-text">Recurring General Contribution</span>
					</div>
					<div class="inline-top contrib-amt">
						<!-- Amount Raised -->
						<span class="amt-text">
							<?php echo '$' . $charge['amount'] / 100; ?>
						</span>
					</div>
					<div class="inline-top manage-div"> 
						<p class="raise-text">Contributed on</p>
						<?php 
							$con_date = $charge['created'];
							echo date("M j, Y", $con_date);
						?>
					</div>
				</div>

			<?php } else { 
				// single time payments
				$post_id = $charge['description'];
				$fundraiser_details = get_fundraiser_stripe_info($post_id); ?>
				<div class="dashb-fundraisers"> 
					<?php if($post_id == "general") { ?>
						<img src="<?php echo home_url() . '/wp-content/uploads/2015/12/MSR_WEB-1.gif'; ?>" height="100" width="100">
						<div class="gen-contrib-info inline-top">
							<span class="normal-text">General Contribution</span>
						</div>
					<?php } else {
						if ( has_post_thumbnail($post_id) ) {
							echo get_the_post_thumbnail($post_id, array(100, 100));
						} ?>
						<div class="fundraise-info inline-top">
							<?php
							if (get_the_title($post_id)) {
								?> <span class="normal-text"><a href="<?php echo get_permalink($post_id); ?>"><?php echo get_the_title($post_id); ?> </span></a><?php
							}  ?>
							<!-- Progress bar -->
							<div class="myProgress">
						  		<div class="myBar" style="width: <?php 
						  			$pct = get_percentage_to_goal($fundraiser_details['total'],  get_post_meta($post_id, 'fundraiser-goal', true)); 
						  			if ($pct > 100) {
						  				$pct = 100;
						  			}
						  			echo $pct ?>%"></div>
							</div>
							<!-- Amount of days remaining -->
							<span class="day-text"><?php echo get_fundraising_days_left(get_post_meta($post_id, 'fundraiser-end', true)); ?> days left</span>
						</div>
						<div class="c-pct inline-top"> 
							<!-- Percentage of amount made -->
							<span><?php echo get_percentage_to_goal($fundraiser_details['total'],  get_post_meta($post_id, 'fundraiser-goal', true)); ?>%</span>
						</div>
					<?php } ?>
					<div class="inline-top contrib-amt">
						<!-- Amount Raised -->
						<span class="amt-text">
							<?php echo '$' . $charge['amount'] / 100; ?>
						</span>
					</div>
					<div class="inline-top manage-div"> 
						<p class="raise-text">Contributed on</p>
						<?php 
							$con_date = $charge['created'];
							echo date("M j, Y", $con_date);
						?>
					</div>
				</div>  <?php
			}
		}
	} 
}

function get_msr_campaign() {
	$args = array(
		'post_type' => 'fundraiser',
		'post_status' => 'publish',
		'author_name' => 'globalhealth_admin'
	);
	$post_query = new WP_Query($args);
	if($post_query->have_posts()) {
		while($post_query->have_posts()) {
			$post_query->the_post();
			$post = get_post();
			$id = $post->ID; ?>
			<img class="campaign-banner" src="<?php if(has_post_thumbnail() ) { echo get_the_post_thumbnail_url(); }	?>">
			<div class="to-center">
				<?php if(get_the_title()) {?>
					<h1><?php echo get_the_title(); ?></h1>
				<?php } ?>
				<button class="campaign-btn landing-campaign" onclick="window.location.href='<?php echo get_permalink($post_id); ?>'">Find Out More</button>
			</div>
			<?php
			$post = NULL;
			break;
		}
	}
}


function change_profile_picture() {
	if ( empty($_POST) || !wp_verify_nonce($_POST['prof-pic-upload-nonce'],'prof-pic-upload-action') ){

		// Just redirect to dashboard 
		echo 'Sorry, your nonce did not verify.';
		exit;
	}

	$user_id = $_POST['userID'];

	if (!function_exists('wp_generate_attachment_metadata')){
        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');
    }

    if ($_FILES) {
		$uploadedfile = $_FILES['pic-upload'];
		$allowed_file_types = array('image/jpg','image/jpeg', 'image/png');
		$uploaded_file_type = $uploadedfile['type'];

		if(in_array($uploaded_file_type, $allowed_file_types)) {
			// Only process the file if the file is a jpeg or png file. 
			$upload_overrides = array( 'test_form' => false );

			$pic_file = wp_handle_upload( $uploadedfile, $upload_overrides);
			$pic_url = $pic_file['url'];
			$pic_locate = $pic_file['file'];

			$current_user_pic = get_user_meta($user_id, 'user-profile-picture', true);
			$current_user_pic_locate = get_user_meta($user_id, 'user-profile-file', true);

			// Check if user already has a url assigned to them, delete the picture in the uploads folder
			if($current_user_pic && $current_user_pic_locate) {
				unlink($current_user_pic_locate);
			}
			update_user_meta( $user_id, 'user-profile-picture', $pic_url);
			update_user_meta( $user_id, 'user-profile-file', $pic_locate);
			wp_redirect("/dashboard");
		} else {
			// Modal popup with this message. 
			?>
			<!-- How to run this after the redirect??? -->
			<div class="modal fade" tabindex="-1" role="dialog">
				  <div class="modal-dialog" role="document">
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				        <h4 class="modal-title">Modal title</h4>
				      </div>
				      <div class="modal-body">
				        <p>That's an incorrect format, please upload a PNG, JPEG, or JPG.</p>
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				        <button type="button" class="btn btn-primary">Save changes</button>
				      </div>
				    </div><!-- /.modal-content -->
				  </div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
			<?php
			wp_redirect("/dashboard");
		}
	}
}

add_action('wp_ajax_change_profile_picture', 'change_profile_picture');

function get_active_fundraisers($fundraiser_id) {
	$fundraiser_details = get_fundraiser_stripe_info($fundraiser_id); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class('post-grid'); ?>>
		<?php if ( has_post_thumbnail() ) { 
		/** Normal container for posts with thumbnail */ ?>
		
		<?php $image_thumb_src = wp_get_attachment_image_src(get_post_thumbnail_id($fundraiser_id), 'msr-post-grid-thumb');?>
		
		<a class="entry-thumb" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" style="background-image:url( <?php echo $image_thumb_src[0]; ?> );">
			<div class="entry-thumb-icon"></div>
		</a>

		<div class="entry-text-content">
			
		<?php } else {
		/** Added class for adjusting post size without thumbnail */ ?>
		
		<div class="entry-text-content no-thumb">
		
		<?php } ?>
			
			<header class="entry-header">
				<?php the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
				<?php if ( 'post' == get_post_type() ) : ?>
				<div class="entry-meta">
					<?php the_time('M j, Y') ?> 
				</div><!-- .entry-meta -->
				<?php endif; ?>
			</header><!-- .entry-header -->
		
			<?php if(get_post_field( 'post_author', $fundraiser_id) == 2){ ?>
				<div class="feature-content"><span>Feature</span></div>
			<?php } ?>

			<div class="author-name">
				<?php 
					$author_id = get_post_field( 'post_author', $fundraiser_id); 
					echo get_user_meta($author_id, 'nickname',true);	
				?>
			</div>
			<div class="entry-content">
				<?php echo the_excerpt(); ?>
			</div><!-- .entry-content -->
		</div>
		<div class="entry-more">
			<?php if(get_fundraising_days_left(get_post_meta($fundraiser_id, 'fundraiser-end', true)) >= 0) { ?>
				<!-- Active fundraisers -->
				<div class="myProgress">
		  		<div class="myBar" style="width: <?php 
		  			$pct = get_percentage_to_goal($fundraiser_details['total'],  get_post_meta($fundraiser_id, 'fundraiser-goal', true)); 
		  			if ($pct > 100) {
		  				$pct = 100;
		  			}
		  			echo $pct ?>%"></div>
				</div>
				<!-- Percentage of amount made -->
				<span class="profile-pct"><?php echo get_percentage_to_goal($fundraiser_details['total'],  get_post_meta($fundraiser_id, 'fundraiser-goal', true)); ?>%</span>
				<span class="profile-days <?php 
					if(get_fundraising_days_left(get_post_meta($fundraiser_id, 'fundraiser-end', true)) <= 10) {
						echo "red-text";
					} ?>"><?php echo get_fundraising_days_left(get_post_meta($fundraiser_id, 'fundraiser-end', true)); ?> days left</span>
			<?php } else if (get_fundraising_days_left(get_post_meta($fundraiser_id, 'fundraiser-end', true)) < 0) { ?> 
				<!-- Past campaigns -->
				<p class="date-text">Ended <?php 
					$sqldate = get_post_meta($fundraiser_id, 'fundraiser-end', true);
					$end = strtotime($sqldate);
					echo date('n/j/y', $end) ?>
				</p>
			<?php }  ?>
		</div>
	</article><!-- #post-## --> 
	<?php
}

function cancel_recurring_payment() {
	if ( empty($_POST) || !wp_verify_nonce($_POST['cancel-recurring-nonce'],'cancel-recurring-action') ) {
	    echo 'You targeted the right function, but sorry, your nonce did not verify.';
	    die();
	} else {
    	global $stripe_options;

		$subID = $_POST['subID'];

		// load the stripe libraries
		require_once(STRIPE_BASE_DIR . '/lib/latest/init.php');	

		// check if we are using test mode
		if(isset($stripe_options['test_mode']) && $stripe_options['test_mode']) {
			$secret_key = $stripe_options['test_secret_key'];
		} else {
			$secret_key = $stripe_options['live_secret_key'];
		}

		\Stripe\Stripe::setApiKey($secret_key);
		$sub = \Stripe\Subscription::retrieve($subID);
		$sub->cancel();
	    wp_redirect("/dashboard/#contributions");
	    // Pop up a modal that says the cancellation was successful. 
	}
}
add_action('wp_ajax_cancel_recurring', 'cancel_recurring_payment');

function default_image_modal() { ?>

	<div class="modal fade" id="default-img-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h5 class="modal-title" id="myModalLabel">Choose an Image</h5>
				</div>
				<div class="modal-body">
					<?php echo do_shortcode('[default_images]'); ?>
				</div>
			</div>
		</div>
	</div>

<?php }

function console_log( $data ){
	echo '<script>';
	echo 'console.log("'. $data .'")';
	echo '</script>';
}

function alert( $data ){
	echo '<script>';
	echo 'alert("'. $data .'")';
	echo '</script>';
}
?>