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
		var campaign_element = document.getElementById("fundraiser-campaign");
		var campaign_id = campaign_element.options[campaign_element.selectedIndex].value;
		if (campaign_id == "") {
			alert("Please choose a campaign");
			return false;
		}

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

		var campaignStart = new Date(campaign_element.options[campaign_element.selectedIndex].dataset.start);
		var campaignEnd = new Date(campaign_element.options[campaign_element.selectedIndex].dataset.end);
		if (start < campaignStart || start > campaignEnd || end < campaignStart || start > campaignEnd) {
			alert("Range needs to be within range of campaign " + campaignStart + " - " + campaignEnd);
			return false;
		}
		return( true );
	}

</script>

<main id="main">

	<form id="fundraiser" name="fundraiser" method="post" action="" encrypt="multipart/form-data" onsubmit="return(validate_form());">

		<h2>CONTRIBUTING TO</h2>

		<select name="fundraiser-campaign" id="fundraiser-campaign">
			<option value=""></option>
			<?php get_campaign_options(isset($_POST['fundraiser-campaign']) ? htmlspecialchars($_POST['fundraiser-campaign']) : ''); ?>
		</select>

		<label for="fundraiser-name">Fundraiser Name</label>
		<input type="text" id="fundraiser-name" name="fundraiserName" value="<?php echo isset($_POST['fundraiser-name']) ? htmlspecialchars($_POST['fundraiser-name']) : ''; ?>">

		<label for="fundraiser-goal">Fundraiser Goal</label>
		<input type="text" id="fundraiser-goal" name="fundraiserGoal" value="<?php echo isset($_POST['fundraiser-goal']) ? htmlspecialchars($_POST['fundraiser-goal']) : ''; ?>">

		<label for="thumbnail">Upload Cover Image</label>
		<input type="file" name="thumbnail" id="thumbnail" value="Choose File">

		<lable for="tagline">Quick Description</lable>
		<textarea id="tageline" name="tagline"><?php echo isset($_POST['tagline']) ? htmlspecialchars($_POST['tagline']) : ''; ?></textarea>

		<label for="start-date">Start Date</label>
		<input type="date" id="start-date" name="startDate" value="<?php echo isset($_POST['start-date']) ? htmlspecialchars($_POST['start-date']) : ''; ?>">

		<label for="end-date">End Date</label>
		<input type="date" id="end-date" name="endDate" value="<?php echo isset($_POST['end-date']) ? htmlspecialchars($_POST['end-date']) : ''; ?>">

		<label for="description">Detailed Description</label>
		<textarea id="description" name="description"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>

		<input type="hidden" name="post-type" id="post-type" value="fundraiser" />

		<input type="hidden" name="action" value="custom_posts" />

		<?php wp_nonce_field( 'create_fundraiser_action','create_fundraiser_nonce' ); ?>

		<a href="home">Cancel</a>
		<input type="submit" name="create-button" value="Create">

	</form>
</main>

<?php get_footer(); ?>