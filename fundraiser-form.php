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

<main id="main" class="fundraising funraiser-form">

	<div class="title-wrapper">
		<h2>Create a New Fundraiser</h2>
	</div>
	<?php do_shortcode("[default_images]"); ?>

	<form id="fundraiser" name="fundraiser" method="post" action="" enctype="multipart/form-data">

		<div class="form-line">
			<label for="fundraiser-name">Fundraiser Name <?php echo info_svg(); ?></label>
			<input type="text" id="fundraiser-name" name="fundraiserName" value="<?php echo isset($_POST['fundraiserName']) ? htmlspecialchars($_POST['fundraiserName']) : ''; ?>" placeholder="Give your fundraiser a title">
		</div>

		<div class="form-line image">
			<label>Cover Image <?php echo info_svg(); ?></label>
			<div class="cover-image">
				<p class="cover-image-plchdr">Add a photo</p>
				<img id="image-preview" src="" />
			</div>
			<label for="thumbnail" class="upload-btn btn"><span>Upload</span><br>your own image</label>
			<div class="spacer"></div>
			<label class="btn"><span>Choose</span><br>one of ours</label>
			<input type="file" name="thumbnail" id="thumbnail" value="Choose File">
		</div>

		<div class="form-line goal">
			<label for="fundraiser-goal">Goal <?php echo info_svg(); ?></label>
			<?php echo dollar_svg(); ?>
			<input type="text" id="fundraiser-goal" name="fundraiserGoal" value="<?php echo isset($_POST['fundraiserGoal']) ? htmlspecialchars($_POST['fundraiserGoal']) : ''; ?>" placeholder="Enter Amount">
		</div>

		<div class="form-line">
			<div class="split-line">
				<label for="start-date">Start Date <?php echo info_svg(); ?></label>
				<div class="date-wrapper">
					<input type="date" id="start-date" name="startDate" value="<?php echo isset($_POST['startDate']) ? htmlspecialchars($_POST['startDate']) : ''; ?>" required>
					<?php echo calendar_svg(); ?>
				</div>
			</div>

			<div class="split-line">
				<label for="end-date">End Date <?php echo info_svg(); ?></label>
				<div class="date-wrapper">
					<input type="date" id="end-date" name="endDate" value="<?php echo isset($_POST['endDate']) ? htmlspecialchars($_POST['endDate']) : ''; ?>" required>
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

		<input type='hidden' id="default-image-input" name="defaultImage" value="not-here" />

		<?php wp_nonce_field( 'create_fundraiser_action','create_fundraiser_nonce' ); ?>

		<div class="action-btns">
			<a href="home" id="cancel">Cancel</a>
			<input type="submit" name="create-button" value="Submit">
		</div>

	</form>
</main>

<script type="text/javascript">
	$('.default').click(function() {
		$('#image-preview').attr('src', $(this).children('img').attr('src'));
		$('input#default-image-input').val($(this).attr('id'));
		$('.cover-image-plchdr').hide();
		$('.cover-image').css({'background-color': 'transparent', 'border': '0'});
		// make sure to close modal once we have it in a modal
	});

	function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#image-preview').attr('src', e.target.result);
                $('input#default-image-input').val("not-here");
                // change styling of surrounding div
                $('.cover-image-plchdr').hide();
                $('.cover-image').css({'background-color': 'transparent', 'border': '0'});
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#thumbnail").change(function(){
        readURL(this);
        // clear val of input field
    });
</script>

<?php get_footer(); ?>