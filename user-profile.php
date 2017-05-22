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
				echo get_avatar($user_id, 145);
			} ?>
		</div>
		<div class="user-info inline-top">
			<p class="name-on-profile"><?php $user = get_userdata(get_current_user_id());
			$name = $user->first_name . " " . $user->last_name;
			echo $name; ?></p>
			<p class="user-on-profile"><?php echo get_user_meta( $user_id, 'nickname', true); ?></p>
		</div>
	</div>

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
				$count++; ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class('post-grid'); ?>>
		
					<?php if ( has_post_thumbnail() ) { 
					/** Normal container for posts with thumbnail */ ?>
					
					<?php $image_thumb_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'msr-post-grid-thumb');?>
					
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
					
						<?php if(has_tag('feature', null)){ ?>
							<div class="feature-content"><span>Feature</span></div>
						<?php } ?>

						<div class="entry-content">
							<?php echo the_excerpt(); ?>
						</div><!-- .entry-content -->
					</div>
					<div class="entry-more">
						
						<?php if ( has_post_format( 'video' )) { ?>
							<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"> Watch Video</a>
						<?php } else { ?>
							<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo __('Read More', 'msr') ?></a>
						<?php } ?>
					</div>
				</article><!-- #post-## -->
				<?php 
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

</main>

<script type="text/javascript">
	document.getElementById("titleText").textContent = "Active Fundraisers (" + $("#total").val() + ")";
</script>

<?php get_footer(); ?>
