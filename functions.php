<?php

include 'svg-icons.php';

function msr_child_enqueue_styles() {
    $parent_style = 'msr-style'; 
 
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'msr-cild-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version'));
}
add_action( 'wp_enqueue_scripts', 'msr_child_enqueue_styles' );

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

    wp_redirect( home_url() . '/edit-fundraiser/?post_id=' . $new_fundraiser );
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
	flush_rewrite_rules();
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
		$charges = \Stripe\Charge::all(array('customer' => $customer_id, 'limit' => 50));

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
		$json_object['contribution-data'] = 0;
		$json_object['charge-data'] = null;
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

	$charges = \Stripe\Charge::all(array('limit' => 100)); // need to be able to do pagination stuff

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
			} else if($totalDiff < 0 && $type == 'expired') {
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
	<?php } else if ($type == 'pending') { ?>
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
	if($post_query->have_posts() ) {
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
							?> <span class="normal-text"> <?php echo get_the_title($id); ?> </span> <?php
						}  ?>
						<p class="date-text">Ended <?php 
							$sqldate = get_post_meta($id, 'fundraiser-end', true);
							$end = strtotime($sqldate);
							echo date('n/j/y', $end) ?> 
						</p>
					</div>
					<div class="pct inline-top"></div>
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
			<?php
			} else if ($totalDiff >= 0 && ($type == 'active' || $type == 'pending')) { ?>
				<div class="dashb-fundraisers">
					<?php if ( has_post_thumbnail() ) {
						the_post_thumbnail( array(100,100) );
					} ?>
					<div class="fundraise-info inline-top">
						<?php
						if (get_the_title($id)) {
							?> <span class="normal-text"> <?php echo get_the_title($id); ?> </span> <?php
						}  ?>
						<!-- Progress bar -->
						<div class="myProgress">
					  		<div class="myBar" style="width: <?php 
					  			$pct = get_percentage_to_goal($fundraiser_details['total'],  get_post_meta($id, 'fundraiser-goal', true)); 
					  			if ($pct > 100) {
					  				$pct = 100;
					  			}
					  			echo $pct ?>%"></div>
						</div>
						<!-- Amount of days remaining -->
						<span class="day-text"><?php echo get_fundraising_days_left(get_post_meta($id, 'fundraiser-end', true)); ?> days left</span>
					</div>
					<div class="pct inline-top"> 
						<!-- Percentage of amount made -->
						<span><?php echo get_percentage_to_goal($fundraiser_details['total'],  get_post_meta($id, 'fundraiser-goal', true)); ?>%</span>
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

			<?php }
		}
	}
	return $totalRaised;
}

function create_contributions_list($user_id, $json_object) {
	foreach ($json_object['charge-data'] as $charge) { 
		if($charge['refunded'] == false) {
			$post_id = $charge['description'];
			$post = get_post($post_id); 
			$fundraiser_details = get_fundraiser_stripe_info($post_id); ?>
			<div class="dashb-fundraisers"> 
				<?php if($post_id == "general") { ?>
					<img src="http://ec2-52-89-248-121.us-west-2.compute.amazonaws.com/wp-content/uploads/2015/12/MSR_WEB-1.gif" height="100" width="100">
					<div class="gen-contrib-info inline-top">
						<span class="normal-text">General Contribution</span>
					</div>
				<?php } else {
					if ( has_post_thumbnail() ) {
					the_post_thumbnail( array(100,100) );
					} ?>
					<div class="fundraise-info inline-top">
						<?php
						if (get_the_title($post_id)) {
							?> <span class="normal-text"> <?php echo get_the_title($post_id); ?> </span> <?php
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
					<div class="pct inline-top"> 
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
			</div> <?php 
		}
	}
}

function console_log( $data ){
	echo '<script>';
	echo 'console.log("'. $data .'")';
	echo '</script>';
}
?>