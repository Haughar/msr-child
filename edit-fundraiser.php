<?php 

/**
 * Template Name: Edit Fundraiser
 *
 * Edit Fundraiser Template.
 *
 * @author Ali Haugh
 * @since 1.0.0
 */

if($_POST){
	edit_fundraiser($_POST['post_id']);
	$id = $_POST['post_id'];
} else {
	$id = $_GET['post_id'];
}

get_header(); ?>

<main id="main">

<?php global $user_ID;
$query_array = array('p' => $id,
					  'post_type' => 'fundraiser',
					  'post_status' => array('pending', 'publish'),
					  'post_author' => get_current_user_id()
					  );
query_posts($query_array);

if (have_posts()) : while (have_posts()) : the_post();
?>

<form id="fundraiser" name="fundraiser" enctype="multipart/form-data" method="post" action="">

	<h2>CONTRIBUTING TO</h2>
	<p>Use post meta's campaign id to get campagin title</p>

	<?php if ( has_post_thumbnail() ) {
		the_post_thumbnail( array(500,500) );
	}?>

	<?php if (get_post_meta($id, 'fundraiser-end', true)) { ?>
	<p><?php echo get_fundraising_days_left(get_post_meta($id, 'fundraiser-end', true)); ?>&nbsp;day(s) left</p>
	<?php } ?>

	<?php if (get_post_meta($id, 'fundraiser-goal', true) && get_post_meta($id, 'fundraiser-amount-raised', true)) { ?>
	<p><?php echo get_percentage_to_goal(floatval(get_post_meta($id, 'fundraiser-amount-raised', true)), floatval(get_post_meta($id, 'fundraiser-goal', true))); ?>%</p>
	<?php } ?>

	<?php if (get_post_meta($id, 'fundraiser-amount-raised', true)) { ?>
	<p><?php echo wpautop(get_post_meta($id, 'fundraiser-amount-raised', true)); ?></p>
	<?php } ?>

	<?php if (get_post_meta($id, 'fundraiser-goal', true)) { ?>
	<p>Fundraiser Goal: <?php echo wpautop(get_post_meta($id, 'fundraiser-goal', true)); ?></p>
	<?php } ?>

	<label for="fundraiser-tagline">Quick Description</label>
	<textarea id="fundraiser-tagline" name="fundraiser-tagline"><?php if (get_post_meta($id, 'fundraiser-tagline', true)) {
			echo get_post_meta($id, 'fundraiser-tagline', true);
		} ?></textarea>

	<label for="fundraiser-description">Detailed Description</label>
	<textarea id="fundraiser-description" name="fundraiser-description"><?php the_content(); ?></textarea>

	<input type="hidden" name="post_id" value="<?php echo $id ?>" />

	<?php wp_nonce_field( 'create_fundraiser_action','create_fundraiser_nonce' ); ?>

	<a href="home">Cancel</a>
	<input type="submit" name="create-button" value="Save">

</form>

<?php
endwhile;
endif; ?>

</main>

<?php get_footer(); ?>