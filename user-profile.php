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

	<?php while ( have_posts() ) : the_post(); ?>
		
				
			
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">
			<?php get_template_part( 'content', 'page' ); ?>
			</main><!-- #main -->
		</div><!-- #primary -->
		
		<!-- ******Fix this redundancy with this and functions.php -->
		<?php
		$args = array(
	    	'post_type' => 'fundraiser',
	  		'post_status' => 'publish',
	  		'author' => $user_id
		);

		$post_query = new WP_Query($args); ?>
		<div class="user-profile-header">
			<h3>Active Fundraisers <?php echo '(' . $post_query->post_count . ')'; ?> </h3>
		</div>
		<?php
		while($post_query->have_posts() ) {
			$post_query->the_post();
			$post = get_post();
			$id = $post->ID;
			echo '<br>';
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( array(100,100) );
			}
			if (get_the_title($id)) {
				echo get_the_title($id);
			} 
			echo '<br>';

			if (get_post_meta($id, 'fundraiser-goal', true) && get_post_meta($id, 'fundraiser-amount-raised', true)) { ?>
				<p><?php echo get_percentage_to_goal(floatval(get_post_meta($id, 'fundraiser-amount-raised', true)), floatval(get_post_meta($id, 'fundraiser-goal', true)));?> %</p>
			<?php
			}
		} ?>

		<!-- ******Show only active campaigns instead of both active and pending campaigns -->
		<?php // get_fundraiser_list($user_id); ?>

	<?php endwhile; // end of the loop. ?>

</main>

<?php get_footer(); ?>
