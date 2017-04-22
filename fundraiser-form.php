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

<form id="fundraiser" name="fundraiser" method="post" action="#">

	<h2>CONTRIBUTING TO</h2>

	<select name="fundraiser-campaign" id="fundraiser-campaign">
		<option></option>
		<?php get_campaign_options(" "); ?>
	</select>

	<label for="fundraiser-name">Fundraiser Name</label>
	<input type="text" id="fundraiser-name" name="fundraiser-name">

	<label for="fundraiser-goal">Fundraiser Goal</label>
	<input type="text" id="fundraiser-goal" name="fundraiser-goal">

	<label for="thumbnail">Upload Cover Image</label>
	<input type="file" name="thumbnail" id="thumbnail" value="Choose File">

	<lable for="tagline">Quick Description</lable>
	<textarea id="tageline" name="tagline"></textarea>

	<label for="start-date">Start Date</label>
	<input type="date" id="start-date" name="start-date">

	<label for="end-date">End Date</label>
	<input type="date" id="end-date" name="end-date">

	<label for="description">Detailed Description</label>
	<textarea id="description" name="description"></textarea>

	<input type="hidden" name="post-type" id="post-type" value="fundraiser" />

	<input type="hidden" name="action" value="custom_posts" />

	<?php wp_nonce_field( 'create_fundraiser_action','create_fundraiser_nonce' ); ?>

	<a href="home">Cancel</a>
	<input type="submit" name="create-button" value="Create">

</form>

<?php

get_footer(); ?>