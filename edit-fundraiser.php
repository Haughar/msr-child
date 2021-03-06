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
	if (isset($_GET['new'])) {
		$new = "true";
	} else {
		$new = "false";
	}
}

get_header(); ?>

<?php global $user_ID;
$query_array = array('p' => $id,
					  'post_type' => 'fundraiser',
					  'post_status' => array('pending', 'publish'),
					  'post_author' => get_current_user_id()
					  );
query_posts($query_array);

if (have_posts()) : while (have_posts()) : the_post();
$amount_raised = get_fundraiser_amount_raised(get_the_ID());
$goal_amount = get_post_meta(get_the_id(), 'fundraiserGoal', true); ?>

<div class="edit-subheader">
	<div class="subheader-wrapper">
		<h2><?php the_title(); ?></h2>
		<?php if ( get_post_status ( get_the_ID() ) != 'pending' ) { ?>
			<a id="view-fundraiser" href="<?php echo get_permalink(); ?>">View</a>
		<?php } ?>
		<div id="fundraiser-progress" class="progress-bar"></div>
		<p class="percentage"><?php echo get_percentage_to_goal($amount_raised, $goal_amount); ?>%</p>

		<p class="goal">Fundraiser Goal: $<?php echo $goal_amount; ?></p>
	</div>
</div>

<?php if ( get_post_status ( $id ) == 'pending' ) { ?>
	<div class="pending-notice">
		<p>This fundraiser is currently <span>Pending Approval</span></p>
	</div>
<?php } ?>

<main id="main" class="fundraising edit-fundraising">

<?php if ($new == "true") { ?>
	<div class="new-fundraiser-prompt">
		<p><strong>Thank you for creating a new fundraiser!</strong> Your fundraiser has been submitted for review by our team. You will be notified via email when it has been published.</p>
	</div>
<?php } ?>

<div class="title-wrapper">
	<h2>Create a New Fundraiser</h2>
</div>

<form id="fundraiser" name="fundraiser" enctype="multipart/form-data" method="post" action="">

	<div class="form-line">
		<label for="fundraiser-name">Fundraiser Name</label>
		<input type="text" id="fundraiserName" name="fundraiserName" value="<?php echo isset($_POST['fundraiserName']) ? htmlspecialchars($_POST['fundraiserName']) : the_title(); ?>" placeholder="Give your fundraiser a title"  data-validation="alphanumeric" data-validation-allowing=" " autocomplete="off" required>
	</div>

	<div class="form-line image">
			<label>Cover Image</label>
			<div class="cover-image">
				<div id="current-image">
					<?php if ( has_post_thumbnail() ) {
						the_post_thumbnail( array(400,400) );
					}?>
				</div>
				<img id="image-preview" />
			</div>
			<label for="thumbnail" class="upload-btn btn"><span>Upload</span><br>your own image</label>
			<div class="spacer"></div>
			<label class="btn" data-toggle="modal" data-target="#default-img-modal"><span>Choose</span><br>one of ours</label>
			<input type="file" name="thumbnail" id="thumbnail" value="Choose File">
		</div>

	<div class="form-line goal">
		<div class="split-line">
			<label for="fundraiser-goal">Goal</label>
			<?php echo dollar_svg(); ?>
			<input type="text" id="fundraiserGoal" name="fundraiserGoal" value="<?php echo isset($_POST['fundraiserGoal']) ? htmlspecialchars($_POST['fundraiserGoal']) : get_post_meta($id, 'fundraiserGoal', true); ?>" placeholder="Enter Amount" data-validation="number" autocomplete="off" required>
		</div>
		<div class="split-line">
			<p><span id="amount-raised">$<?php echo number_format(get_fundraiser_amount_raised($id), 0, '.', ','); ?></span> USD raised</p>
		</div>
	</div>

	<div class="form-line">
		<div class="split-line">
			<label for="end-date">End Date</label>
			<div class="date-wrapper">
				<input type="date" id="fundraiserEnd" name="fundraiserEnd" value="<?php echo isset($_POST['fundraiserEnd']) ? htmlspecialchars($_POST['fundraiserEnd']) : get_post_meta($id, 'fundraiserEnd', true); ?>" data-validation="date" autocomplete="off" required>
				<?php echo calendar_svg(); ?>
			</div>
		</div>
		<div class="split-line">
			<?php if (get_post_meta($id, 'fundraiserEnd', true)) { ?>
				<p>
					<?php $days_left = get_fundraising_days_left(get_post_meta($id, 'fundraiserEnd', true));
						if ($days_left > 1) {
							echo $days_left . "&nbsp;days left";
						} else if ($days_left == 1) {
							echo $days_left . "&nbsp;day left";
						} else if ($days_left == 0){
							echo "Ending tonight";
						} else if ($days_left < 0) {
							echo "Closed";
						}
					?>
				</p>
			<?php } ?>
		</div>
	</div>

	<div class="form-line">
		<label for="description">Story</label>
		<textarea id="description" name="description"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : get_the_content(); ?></textarea>
	</div>

	<input type="hidden" name="post_id" value="<?php echo $id ?>" />

	<?php wp_nonce_field( 'create_fundraiser_action','create_fundraiser_nonce' ); ?>

	<div class="action-btns">

		<input type="submit" name="create-button" value="Save" class="btn">
	</div>
</form>

<?php
endwhile;
endif; ?>

</main>

<?php echo default_image_modal(); ?>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
	$( function() {
		$( "#fundraiser-progress" ).progressbar({
		  value: <?php echo get_percentage_to_goal($amount_raised, $goal_amount); ?>
		});

		<?php if ($new == "true") { ?>
			setTimeout( function(){ 
			    $('.new-fundraiser-prompt').fadeOut(1000);
			}  , 6000 );
		<?php } ?>
	});

	$('.default').click(function() {
		$('#current-image').hide();
		$('#image-preview').attr('src', $(this).children('img').attr('src'));
		$('input#default-image-input').val($(this).attr('id'));
		$('.cover-image-plchdr').hide();
		$('.cover-image').css({'background-color': 'transparent', 'border': '0'});
	});

	function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
            	$('#current-image').hide();
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
    });
</script>

<?php get_footer(); ?>