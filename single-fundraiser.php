<?php
get_header();

if (have_posts()) : while (have_posts()) : the_post(); ?>

<div class="left">

	<?php if ( has_post_thumbnail() ) {
		the_post_thumbnail( array(500,500) );
	}?>

	<h2>Story</h2>
	<p><?php the_content(); ?></p>

	<h2>Updates</h2>
	<p>Not sure what this is going to be?</p>

	<h2>Comments(<?php comments_number('0', '1', '%'); ?>)</h2>
	<p><?php get_comments(); ?></p>

	<h2>Contributors</h2>
	<p>Some method that will do this</p>

</div>

<div class="right">

	<h1><?php the_title(); ?></h1>
	<?php if (get_post_meta($id, 'fundraiser-tagline', true)) { ?>
	<h3><?php echo wpautop(get_post_meta($id, 'fundraiser-tagline', true)); ?></h3>
	<?php } ?>

	<?php if (get_post_meta($id, 'fundraiser-amount-raised', true)) { ?>
	<p><?php echo wpautop(get_post_meta($id, 'fundraiser-amount-raised', true)); ?></p>
	<?php } ?>

	<?php if (get_post_meta($id, 'fundraiser-goal', true)) { ?>
	<p><?php echo wpautop(get_post_meta($id, 'fundraiser-goal', true)); ?></p>
	<?php } ?>

	<?php if (get_post_meta($id, 'fundraiser-goal', true) && get_post_meta($id, 'fundraiser-amount-raised', true)) { ?>
	<p><?php echo get_percentage_to_goal(floatval(get_post_meta($id, 'fundraiser-amount-raised', true)), floatval(get_post_meta($id, 'fundraiser-goal', true))); ?>%</p>
	<?php } ?>

	<?php if (get_post_meta($id, 'fundraiser-end', true)) { ?>
	<p><?php echo get_fundraising_days_left(get_post_meta($id, 'fundraiser-end', true)); ?>&nbsp;day(s) left</p>
	<?php } ?>

</div>


<?php
endwhile;
endif;
get_footer();

?>