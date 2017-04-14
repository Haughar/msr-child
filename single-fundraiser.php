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
	<?php $comments = get_comments(array('post_id' => $post->ID, 'type' => 'comment', 'status' => 'approve')); ?>
	<ol class="commentlist">
		<?php wp_list_comments(array('reverse_top_level' => false, 'style' => 'ol' ), $comments); ?>
	</ol>
	<div>
		<?php comment_form(array('title_reply' => __( 'Leave a Comment', 'textdomain' ), 'comment_notes_after' => ''), $post->ID); ?>
	</div>

	<h2>Contributors</h2>
	<?php $comments = get_comments(array('post_id' => $post->ID, 'type' => 'contribution', 'status' => 'approve')); ?>
	<ol class="commentlist">
		<?php wp_list_comments(array('reverse_top_level' => false, 'style' => 'ol' ), $comments); ?>
	</ol>

</div>

<div class="right">

	<h1><?php the_title(); ?></h1>
	<?php if (get_post_meta($id, 'fundraiser-tagline', true)) { ?>
	<h3><?php echo wpautop(get_post_meta($id, 'fundraiser-tagline', true)); ?></h3>
	<?php } ?>

	<p><?php echo the_author_meta('user_login'); ?></p>

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

	<h3>Feed</h3>
	<?php $comments = get_comments(array('post_id' => $post->ID)); ?>
	<ol class="commentlist">
		<?php wp_list_comments(array('reverse_top_level' => false, 'style' => 'ol' ), $comments); ?>
	</ol>


</div>


<?php
endwhile;
endif;
get_footer();

?>