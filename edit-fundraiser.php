<?php 

/**
 * Template Name: Edit Fundraiser
 *
 * Edit Fundraiser Template.
 *
 * @author Ali Haugh
 * @since 1.0.0
 */

$new = false;

if($_POST){
	edit_fundraiser($_POST['post_id']);
	$id = $_POST['post_id'];
} else {
	$id = $_GET['post_id'];
	$new = $_GET['new'];
}

get_header(); ?>

<main id="main" class="fundraising">

<?php if ($new) { ?>
	<div class="new-fundraiser-prompt">
		<p><strong>Thank you for creating a new fundraiser!</strong> Your fundraiser has been submitted for review by our team. You will be notified via email when it has been published.</p>
	</div>
<?php } ?>

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

	<div class="form-line">
		<label for="fundraiser-name">Fundraiser Name</label>
		<input type="text" id="fundraiser-name" name="fundraiserName" value="<?php echo isset($_POST['fundraiser-name']) ? htmlspecialchars($_POST['fundraiser-name']) : the_title(); ?>" placeholder="Give your fundraiser a title">
	</div>

	<div class="form-line image">
		<label>Cover Image</label>
		<div class="cover-image">
			<?php if ( has_post_thumbnail() ) {
				the_post_thumbnail( array(400,400) );
			}?>
		</div>
		<label for="thumbnail" class="upload-btn btn"><span>Upload</span><br>your own image</label>
		<div class="spacer"></div>
		<label class="btn"><span>Choose</span><br>one of ours</label>
		<input type="file" name="thumbnail" id="thumbnail" value="Choose File">
	</div>

	<div class="form-line goal">
		<div class="split-line">
			<label for="fundraiser-goal">Goal</label>
			<?php echo dollar_svg(); ?>
			<input type="text" id="fundraiser-goal" name="fundraiserGoal" value="<?php echo isset($_POST['fundraiser-goal']) ? htmlspecialchars($_POST['fundraiser-goal']) : get_post_meta($id, 'fundraiser-goal', true); ?>" placeholder="Enter Amount">
		</div>
		<div class="split-line">
			<p><span>$<?php echo number_format(get_fundraiser_amount_raised($id), 0, '.', ','); ?></span> USD raised</p>
		</div>
	</div>

	<div class="form-line">
		<div class="split-line">
			<label for="end-date">End Date</label>
			<div class="date-wrapper">
				<input type="date" id="end-date" name="endDate" value="<?php echo isset($_POST['end-date']) ? htmlspecialchars($_POST['end-date']) : get_post_meta($id, 'fundraiser-end', true); ?>" required>
				<?php echo calendar_svg(); ?>
			</div>
		</div>
		<div class="split-line">
			<?php if (get_post_meta($id, 'fundraiser-end', true)) { ?>
				<p><?php echo get_fundraising_days_left(get_post_meta($id, 'fundraiser-end', true)); ?>&nbsp;day(s) left</p>
			<?php } ?>
		</div>
	</div>

	<div class="form-line">
		<label for="description">Story</label>
		<textarea id="description" name="description"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : htmlspecialchars(the_content()); ?></textarea>
	</div>

	<input type="hidden" name="post_id" value="<?php echo $id ?>" />

	<?php wp_nonce_field( 'create_fundraiser_action','create_fundraiser_nonce' ); ?>

	<div class="action-btns">
		<a href="home" id="delete-fundraiser">Delete this fundraiser</a>
		<input type="submit" name="create-button" value="Save" class="btn">
	</div>

</form>

<?php
endwhile;
endif; ?>

</main>

<?php get_footer(); ?>