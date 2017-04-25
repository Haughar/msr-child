<?php 

/**
 * Template Name: Create Fundraiser
 *
 * Create Fundraiser Template.
 *
 * @author Ali Haugh
 * @since 1.0.0
 */

if($_POST){
	create_new_fundraiser();
}

get_header(); ?>

<form id="fundraiser" name="fundraiser" method="post" action="" encrypt="multipart/form-data">

	<h2>CONTRIBUTING TO</h2>

	<select name="fundraiser-campaign" id="fundraiser-campaign">
		<option></option>
		<?php get_campaign_options(isset($_POST['fundraiser-campaign']) ? htmlspecialchars($_POST['fundraiser-campaign']) : ''); ?>
	</select>
	<?php if (isset($_POST['fundraiser-campaign-error'])) { ?>
		<p>ERROR</p>
	<?php } ?>

	<label for="fundraiser-name">Fundraiser Name</label>
	<input type="text" id="fundraiser-name" name="fundraiser-name" value="<?php echo isset($_POST['fundraiser-name']) ? htmlspecialchars($_POST['fundraiser-name']) : ''; ?>">
	<?php if (isset($_POST['fundraiser-name-error'])) { ?>
		<p>ERROR</p>
	<?php } ?>

	<label for="fundraiser-goal">Fundraiser Goal</label>
	<input type="text" id="fundraiser-goal" name="fundraiser-goal" value="<?php echo isset($_POST['fundraiser-goal']) ? htmlspecialchars($_POST['fundraiser-goal']) : ''; ?>">
	<?php if (isset($_POST['fundraiser-goal-error'])) { ?>
		<p>ERROR</p>
	<?php } ?>

	<label for="thumbnail">Upload Cover Image</label>
	<input type="file" name="thumbnail" id="thumbnail" value="Choose File">

	<lable for="tagline">Quick Description</lable>
	<textarea id="tageline" name="tagline"><?php echo isset($_POST['tagline']) ? htmlspecialchars($_POST['tagline']) : ''; ?></textarea>
	<?php if (isset($_POST['tagline-error'])) { ?>
		<p>ERROR</p>
	<?php } ?>

	<label for="start-date">Start Date</label>
	<input type="date" id="start-date" name="start-date" value="<?php echo isset($_POST['start-date']) ? htmlspecialchars($_POST['start-date']) : ''; ?>">
	<?php if (isset($_POST['start-date-error'])) { ?>
		<p>ERROR</p>
	<?php } ?>

	<label for="end-date">End Date</label>
	<input type="date" id="end-date" name="end-date" value="<?php echo isset($_POST['end-date']) ? htmlspecialchars($_POST['end-date']) : ''; ?>">
	<?php if (isset($_POST['end-date-error'])) { ?>
		<p>ERROR</p>
	<?php } ?>

	<?php if (isset($_POST['reverse-date-error'])) { ?>
		<p>Start data cannot be before the end date.</p>
	<?php } ?>

	<label for="description">Detailed Description</label>
	<textarea id="description" name="description"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
	<?php if (isset($_POST['description-error'])) { ?>
		<p>ERROR</p>
	<?php } ?>

	<input type="hidden" name="post-type" id="post-type" value="fundraiser" />

	<input type="hidden" name="action" value="custom_posts" />

	<?php wp_nonce_field( 'create_fundraiser_action','create_fundraiser_nonce' ); ?>

	<a href="home">Cancel</a>
	<input type="submit" name="create-button" value="Create">

</form>

<?php

get_footer(); ?>