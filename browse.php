<?php
/**
 * Template Name: Browse Page
 *
 * Browse Page Template.
 *
 * @author Michael Nguyen
 * @since 1.0.0
 */
get_header(); ?>
<!-- Get large banner on top -->
<div id="browse-banner">
	<img src="http://ec2-54-70-94-165.us-west-2.compute.amazonaws.com/wp-content/uploads/2017/05/9H9A5730-dcp-1-BW-1.jpg">
	<?php 
	if(is_user_logged_in()) {
		echo "<button class='browse-new-btn' onclick=\"window.location.href='/create-fundraiser/'\">New Fundraiser</button>"; 
	} else { ?>
		<!-- <button class='banner-btn' id='wow-modal-id-2'>New Fundraiser</button> -->
		<button type="button" class="browse-new-btn" data-toggle="modal" data-target="#loginModal">New Fundraiser</button>

		<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header">
				    <button type="button" class="close login-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle fa-3" aria-hidden="true"></i></span>
				    </button>
				    <h4 class="modal-title" id="myModalLabel">Log in</h4>
				  </div>
				  <div class="modal-body">
				    <?php echo do_shortcode('[ultimatemember form_id=8849]'); ?>
				  </div>
				</div>
			</div>
		</div>
	<?php } ?>
</div>
<div class="browse-fundraisers">
	<!-- List all fundraisers -->
	<?php echo do_shortcode("[listing]"); ?>
</div>



<?php get_footer(); ?>