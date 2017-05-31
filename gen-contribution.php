<?php
/**
 * Template Name: General Contribution Page
 *
 * General Contribution Page Template.
 *
 * @author Michael Nguyen
 * @since 1.0.0
 */
get_header(); ?>

	<!-- Large image on top of page -->
	<div class="gen-contribute-img">
		<img src="http://ec2-54-70-94-165.us-west-2.compute.amazonaws.com/wp-content/uploads/2017/05/9H9A5730-dcp-1-BW-1.jpg">
	</div>

	<!-- General Contribution Information Section -->
	<div class="general-contribute-info">
		<h1 class="gen-contribute-title">MSR Global Health General Contribution</h1>
		<p>When you give directly to MSR Global Health, your contribution enables us to help low-resource communities gain access to food, water, and shelter. A general contribution will go directly to MSR Global Health without being linked to any campaigns or fundraisers that are currently running.</p>
	</div>

	<!-- General Contribution Form -->
	<div class="gen-contribute-div">
		<button class="gen-contribute-btn" data-toggle="modal" data-target="#contribute-modal">General Contribution</button>
	</div>

	<div class="page-template-landing-page"> 
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
	</div>
<?php get_footer(); ?>