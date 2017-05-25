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
	<img src="http://ec2-54-70-94-165.us-west-2.compute.amazonaws.com/wp-content/uploads/2017/05/9H9A5730-dcp-1-BW.jpg">
</div>
<div class="browse-fundraisers">
	<!-- List all fundraisers -->
	<?php echo do_shortcode("[listing]"); ?>
</div>

<?php get_footer(); ?>