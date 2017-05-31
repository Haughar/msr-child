<?php
/**
 * Template Name: My Fundraisers
 *
 * My Fundraisers Page Template.
 *
 * @author Michael Nguyen
 * @since 1.0.0
 */

$user_id = get_current_user_id();
$json_object = get_customer_contributions($user_id);

get_header(); ?>
	<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">
				<div id="fundraisers">
					<div id="title"> 
						<h1>My Fundraisers</h1>
						<p id="totalText"></p>
					</div>
					<div id="fundraise-btn" class="inline-top">
						<button onclick="window.location.href='/create-fundraiser/'">New Fundraiser</button>
					</div>
						<!-- User's Active Fundraisers -->
					<?php $totalRaisedActive = get_fundraiser_list($user_id, "active"); ?>
						<!-- User's Pending Fundraisers -->	
					<?php 
						$totalRaisedPending = get_fundraiser_list($user_id, "pending"); 
					?>
						<!-- User's Past Fundraisers -->
					<?php 
						$totalRaisedExpired = get_fundraiser_list($user_id, "expired"); 
					?>
					<input id="totalRaised" type="hidden" value="<?php echo $totalRaisedActive + $totalRaisedPending + $totalRaisedExpired ?>">
		 		</div>
			</main><!-- #main -->
	</div><!-- #primary -->

	<script type="text/javascript">
		document.getElementById("totalText").textContent = "Total Raised: $" + $("#totalRaised").val();
	</script>

		
<?php get_footer(); ?>