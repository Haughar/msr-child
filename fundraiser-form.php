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
	console_log("Got here");
	create_new_fundraiser();
}

get_header(); ?>

<script type="text/javascript">
	function validate_form() {

		if (document.fundraiser.fundraiserName.value == "") {
			alert("please provide a name!");
			document.fundraiser.fundraiserName.focus();
			return false;
		}
		if (document.fundraiser.fundraiserGoal.value == "") {
			alert("Please provide a goal");
			document.fundraiser.fundraiserGoal.focus();
			return false;
		}

		if (document.fundraiser.tagline.value == "") {
			alert("Please provide a tagline");
			document.fundraiser.tagline.focus();
			return false;
		}

		if (document.fundraiser.startDate.value == "" && document.fundraiser.endDate.value == "") {
			alert("Please provide a date range");
			document.fundraiser.startDate.focus();
			return false;
		}

		if (document.fundraiser.startDate.value == "") {
			alert("Please provide a start date");
			document.fundraiser.startDate.focus();
			return false;
		}

		if (document.fundraiser.endDate.value == "") {
			alert("Please provide an end date");
			document.fundraiser.endDate.focus();
			return false;
		}

		if (document.fundraiser.description.value == "") {
			alert("Please provide a description");
			document.fundraiser.description.focus();
			return false;
		}

		var start = new Date(document.fundraiser.startDate.value);
		var end = new Date(document.fundraiser.endDate.value);
		if (start >= end) {
			alert("Please enter a valid date range-- start date needs to be before end date");
			document.fundraiser.startDate.focus();
			return false;
		}

		return( true );
	}

</script>

<main id="main" class="fundraising funraiser-form">

	<div class="title-wrapper">
		<h2>Create a New Fundraiser</h2>
	</div>

	<form id="fundraiser" name="fundraiser" method="post" action="" encrypt="multipart/form-data" onsubmit="return(validate_form());">

		<div class="form-line">
			<label for="fundraiser-name">Fundraiser Name <?php echo info_svg(); ?></label>
			<input type="text" id="fundraiser-name" name="fundraiserName" value="<?php echo isset($_POST['fundraiser-name']) ? htmlspecialchars($_POST['fundraiser-name']) : ''; ?>" placeholder="Give your fundraiser a title">
		</div>

		<div class="form-line image">
			<label>Cover Image <?php echo info_svg(); ?></label>
			<div class="cover-image"></div>
			<label for="thumbnail" class="upload-btn btn"><span>Upload</span><br>your own image</label>
			<div class="spacer"></div>
			<label class="btn"><span>Choose</span><br>one of ours</label>
			<input type="file" name="thumbnail" id="thumbnail" value="Choose File">
		</div>

		<div class="form-line goal">
			<label for="fundraiser-goal">Goal <?php echo info_svg(); ?></label>
			<?php echo dollar_svg(); ?>
			<input type="text" id="fundraiser-goal" name="fundraiserGoal" value="<?php echo isset($_POST['fundraiser-goal']) ? htmlspecialchars($_POST['fundraiser-goal']) : ''; ?>" placeholder="Enter Amount">
		</div>

		<div class="form-line">
			<div class="split-line">
				<label for="start-date">Start Date <?php echo info_svg(); ?></label>
				<div class="date-wrapper">
					<input type="date" id="start-date" name="startDate" value="<?php echo isset($_POST['start-date']) ? htmlspecialchars($_POST['start-date']) : ''; ?>" required>
					<?php echo calendar_svg(); ?>
				</div>
			</div>

			<div class="split-line">
				<label for="end-date">End Date <?php echo info_svg(); ?></label>
				<div class="date-wrapper">
					<input type="date" id="end-date" name="endDate" value="<?php echo isset($_POST['end-date']) ? htmlspecialchars($_POST['end-date']) : ''; ?>" required>
					<?php echo calendar_svg(); ?>
				</div>
			</div>
		</div>

		<div class="form-line">
			<label for="description">Story <?php echo info_svg(); ?></label>
			<textarea id="description" name="description"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
		</div>

		<input type="hidden" name="post-type" id="post-type" value="fundraiser" />

		<input type="hidden" name="action" value="custom_posts" />

		<?php wp_nonce_field( 'create_fundraiser_action','create_fundraiser_nonce' ); ?>

		<div class="action-btns">
			<a href="home" id="cancel">Cancel</a>
			<input type="submit" name="create-button" value="Submit">
		</div>

	</form>
</main>

<?php get_footer(); ?>