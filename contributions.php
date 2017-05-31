<?php
/**
 * Template Name: My Contributions
 *
 * My Contributions Page Template.
 *
 * @author Michael Nguyen
 * @since 1.0.0
 */

$user_id = get_current_user_id();
$json_object = get_customer_contributions($user_id);

get_header(); ?>
	<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">

				<div id="contributions">
					<div id="title"> 
						<h1>My Contributions</h1>
						<p> Total Contribution: $<?php echo $json_object['total']; ?></p>
					</div>
					<div id="fundraise-btn" class="inline-top">
						<button onclick="window.location.href='/create-fundraiser/'">New Fundraiser</button>
					</div>

					<?php if($json_object['recurring_donation']) { ?>
						<div class="user-profile-header marg-bot-sevenfive">
							<p class="recurr-display">Recurring Contributions</p>
							<div class="inline-top cancel-txt cncl">
								Cancel
							</div>
						</div>
						<div class="all-recurr">
							<?php foreach ($json_object['subscription_data'] as $data) { ?>
								<div class="recurr_div">
									<span class="amt-text"><?php echo '$' . $data['quantity']; ?></span>
									<span class="cancel-txt pad-left-ten">Monthly</span>
									<span class="cancel-txt">Last Contribution: <?php echo date("M j, Y", $data['current_period_start']); ?></span>
									<span class="cancel-txt next-recurr">Next Contribution: <?php echo date("M j, Y", $data['current_period_end']); ?></span>
									<span class="recurr-box"><input id="cancel-sub" type="checkbox" name="cancel"></span>
								</div>
								<div class="save-btn">
									<form id="cancel-recurring" name="cancel-recurring" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>">
										<input id="subID" name="subID" type="hidden" value="<?php echo $data['id']; ?>">
										<?php wp_nonce_field( 'cancel-recurring-action', 'cancel-recurring-nonce' ); ?>
										<input name="action" value="cancel_recurring" type="hidden">
										<input type="button" id="save-btn" value="Save" class="disabled-btn" disabled="disabled" data-toggle="modal" data-target="#cancel-confirm">
									</form>
									<div class="modal fade" id="cancel-confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
											  <div class="modal-header">
											    	<button type="button" class="close login-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle fa-3" aria-hidden="true"></i></span></button>
										    		<h5 class="modal-title" id="myModalLabel">Cancellation Confirmation</h5>
											  	</div>
											  	<div class="modal-body">
											  		<p>Are you sure you want to cancel your monthly payment of $<?php echo $data['quantity'];?>?</p>
											  	</div>
											  	<div class="modal-footer">
									        		<button type="button" class="cancel-btn" data-dismiss="modal">Close</button>
										        	<input type="submit" class="confirm-btn" id="confirm-cancel" value="Confirm">
										     	 </div>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
						<div class="dashboard-space"></div>
						<div class="dashboard-space"></div>
						<div class="dashboard-space"></div> 
					<?php } ?>
					<div class="user-profile-header">
						<p>Your Contributions</p> 
					</div>
					<div>
						<span class="day-text"><?php 
							if($json_object['charge-data']) {
								create_contributions_list($user_id, $json_object); ?>
								</span>
							<?php } else {  ?>
								</span>
								<p class="none-p">You have not made any contributions.</p>
							<?php } ?>
					</div>
					<div class="dashboard-space"></div>
				</div>
			</main><!-- #main -->
	</div><!-- #primary -->

	<script type="text/javascript">
		$("#cancel-sub").on("click", function () {
			if($("#cancel-sub").is(':checked')) {
				$("#save-btn").attr("disabled", false);
				$("#save-btn").removeClass("disabled-btn");
				$("#save-btn").addClass("blck-btn-contribute");
			} else {
				$("#save-btn").attr("disabled", true);
				$("#save-btn").addClass("disabled-btn");
				$("#save-btn").removeClass("blck-btn-contribute");
			}
		});

		$('#confirm-cancel').click(function() {
			$('#cancel-recurring').submit();
		});
	</script>

		
<?php get_footer(); ?>