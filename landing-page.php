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
<!-- Use PHP to grab current campaign by MSR -->
<div class="top-landing-banner">
	<img class="campaign-banner" src="<?php echo home_url() . '/wp-content/uploads/2016/02/RwanadaImagesForMSRBlog-9.jpg'; ?>">
	<div class="to-center">
		<h1>One Device, Endless Impact</h1>
		<button class="campaign-btn landing-campaign">Find Out More</button>
	</div>
</div>
<!-- General Contribution Banner -->
<div class="general">
	<div class="gen-text inline-top">
		<p class="no-margin banner-title">What is MSR Global Health working on?</p>
		<p class="no-margin gen-descr">MSR Global Health a leading innovator and manufacturer of low cost, field-proven products that improve access to basic human needs for people living in low-resource communities of the developing world. Please support MSR Global Heatlh by contributing directly to MSR campaigns or our supporters’ fundraisers.</p>
	</div>
	<div class="inline-top btn-spot">
		<?php echo cups(); ?>
		<br>
		<?php echo do_shortcode("[Wow-Modal-Windows id=1]"); ?>
		<div class="btn-wrapper">
			<button class="banner-btn blck-btn" id='wow-modal-id-1'>General Contribution</button>
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
			<button class="banner-btn" onclick="window.location.href='/create-fundraiser/'">New Fundraiser</button>
		</div>
	</div>
</div>

<?php get_footer(); ?>
