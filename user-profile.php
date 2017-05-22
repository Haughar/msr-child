<?php
/**
 * Template Name: User Profile
 *
 * User Profile Template.
 *
 * @author Michael Nguyen
 * @since 1.0.0
 */

$user_id = get_current_user_id();

get_header(); ?>

<main id="main">
	<div class="profile-head">
		<div class="user-profile-pic inline-top">
			<?php if(get_user_meta($user_id, "user-profile-picture", true)) { ?>
				<img src="<?php echo get_user_meta($user_id, "user-profile-picture", true); ?>">
			<?php } else {
				echo get_avatar($user_id, 168);
			} ?>
		</div>
		<div class="user-info inline-top">
			<p class="name-on-profile"><?php $user = get_userdata(get_current_user_id());
			$name = $user->first_name . " " . $user->last_name;
			echo $name; ?></p>
			<p class="user-on-profile"><?php echo get_user_meta( $user_id, 'nickname', true); ?></p>
		</div>
	</div>
	<div class="profile-fundraiser-list">
	<?php while ( have_posts() ) : the_post(); ?>
		<!-- ******Fix this redundancy with this and functions.php -->
		<?php
		$args = array(
	    	'post_type' => 'fundraiser',
	  		'post_status' => 'publish',
	  		'author' => $user_id
		);

		$post_query = new WP_Query($args); ?>
		<div class="user-profile-header">
			<p id="titleText">Active Fundraisers <?php echo '(' . $post_query->post_count . ')'; ?> </p>
		</div>
		<?php
		$count = 0;
			while($post_query->have_posts() ) {
				$post_query->the_post();
				$post = get_post();
				$id = $post->ID;
				$endDate = strtotime(get_post_meta($id, 'fundraiser-end', true));
				$difference = $endDate - time();
				$totalDiff = floor($difference/60/60/24);
				if($totalDiff >= 0) {
					$count++;
					get_active_fundraisers($id);
					/* echo '<br>';
					if ( has_post_thumbnail() ) {
						the_post_thumbnail( array(100,100) );
					}
					if (get_the_title($id)) {
						echo get_the_title($id);
					} 
					echo '<br>';

					if (get_post_meta($id, 'fundraiser-goal', true) && get_post_meta($id, 'fundraiser-amount-raised', true)) { ?>
						<p><?php echo get_percentage_to_goal(floatval(get_post_meta($id, 'fundraiser-amount-raised', true)), floatval(get_post_meta($id, 'fundraiser-goal', true)));?> %</p>
					<?php } */
				}
			} ?>
		<input type="hidden" value="<?php echo $count ?>" id="total">
	<?php endwhile; // end of the loop. ?>
	</div>
</main>

<script type="text/javascript">
	document.getElementById("titleText").textContent = "Active Fundraisers (" + $("#total").val() + ")";
</script>


<?php get_footer(); ?>
