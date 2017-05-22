<?php
/**
 * Template Name: Landing Page
 *
 * Landing Page Template.
 *
 * @author Michael Nguyen
 * @since 1.0.0
 */
get_header(); ?>

<!-- Current Campaign Banner -->
<div class="top-landing-banner">
	<?php echo get_msr_campaign(); ?>
</div>
<!-- General Contribution Banner -->
<div class="general fundraising">
	<div class="gen-text inline-top">
		<p class="no-margin banner-title">What is MSR Global Health working on?</p>
		<p class="no-margin gen-descr">MSR Global Health a leading innovator and manufacturer of low cost, field-proven products that improve access to basic human needs for people living in low-resource communities of the developing world. Please support MSR Global Heatlh by contributing directly to MSR campaigns or our supporters’ fundraisers.</p>
	</div>
	<div class="inline-top btn-spot">
		<?php echo cups(); ?>
		<br>
		<?php echo do_shortcode("[Wow-Modal-Windows id=1]"); ?>
		<div class="btn-wrapper">
			<button class="banner-btn blck-btn-landing" id='wow-modal-id-1'>General Contribution</button>
		</div>
	</div>
</div>
<!-- Start Browsing banner -->
<div class="browse">
	<div class="browse-banner color-banner">
		<div class="inline-top middle-btn-spot">
			<?php echo water_drop(); ?>
			<br>
			<button class="banner-btn" onclick="window.location.href='/fundraiser/'">Start Browsing</button>
		</div>
		<div class="browse-text inline-top">
			<p class="no-margin white-text banner-title">Contribute to a Fundraiser</p>
			<p class="no-margin white-text gen-descr">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </p>
		</div>
	</div>
</div>
<!-- Create a Fundraiser banner -->
<div class="create">
	<div class="create-banner color-banner">
		<div class="gen-text inline-top">
			<p class="no-margin white-text banner-title">Create a Fundraiser</p>
			<p class="no-margin white-text gen-descr">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </p>
		</div>	
		<div class="inline-top btn-spot">
			<?php echo hand_money(); ?>
			<br>
			<?php 
				if(is_user_logged_in()) {
					echo "<button class='banner-btn' onclick=\"window.location.href='/create-fundraiser/'\">New Fundraiser</button>"; 
				} else { ?>
					<!-- <button class='banner-btn' id='wow-modal-id-2'>New Fundraiser</button> -->
					<button type="button" class="banner-btn" data-toggle="modal" data-target="#loginModal">New Fundraiser</button>

					<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
							  <div class="modal-header">
							    <button type="button" class="close login-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-circle" aria-hidden="true"></i><i class="fa fa-times" aria-hidden="true"></i></span></button>
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
	</div>
</div>

<?php get_footer(); ?>
