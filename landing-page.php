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
<!-- Contribution to MSR GH Overview -->
<div class="general fundraising">
	<p class="intro-banner-title">How does the fundraising platform work?</p>
	<p class="intro-descr">All funds raised on this platform will help us with the manufacturing cost of our products that improve access to basic human needs for people living in low-resource communities of the developing world. Please support MSR Global Heatlh by contributing directly to MSR campaigns or our supporters’ fundraisers.</p>
</div>

<!-- Create a Fundraiser banner -->
<div class="create">
	<div class="create-banner color-banner">
		<div class="gen-text inline-top">
			<p class="no-margin white-text banner-title">Create a Fundraiser</p>
			<p class="no-margin white-text gen-descr">Have your own outdoor adventure that you would like to share with others? Why not raise money at the same time, by creating a fundraiser. Share your outdoor adventure on our platform to help us achieve our global health initiatives.</p>
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
	</div>
</div>
<!-- Start Browsing banner -->
<div class="browse">
	<div class="browse-banner color-banner">
		<div class="inline-top middle-btn-spot">
			<?php echo water_drop(); ?>
			<br>
			<button class="banner-btn" onclick="window.location.href='/browse/'">Start Browsing</button>
		</div>
		<div class="browse-text inline-top">
			<p class="no-margin white-text banner-title">Contribute to a Fundraiser</p>
			<p class="no-margin white-text gen-descr">Explore fundraisers created by people just like you. If you stumble upon fundraisers that you connect with, you'll be able donate to those fundraisers and easily share them to your friends and family. </p>
		</div>
	</div>
</div>
<!-- General Contribution Banner -->
<div class="gen-contribute">
	<div class="general fundraising">
		<div class="gen-text inline-top">
			<p class="no-margin white-text banner-title">Make a General Contribution</p>
			<p class="no-margin white-text gen-descr">If you would like to donate directly to MSR Global Health, general contributions is the way to go. In just two clicks, you would have successfully completed your general contribution to us.</p>
		</div>
		<div class="inline-top btn-spot">
			<?php echo cups(); ?>
			<br>
			<div class="btn-wrapper">
				<button class="banner-btn" data-toggle="modal" data-target="#contribute-modal">General Contribution</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="contribute-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="myModalLabel">Contribute</h5>
			</div>
			<div class="modal-body">
				<?php echo do_shortcode('[payment]'); ?>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>