<?php 

/**
 * Template Name: Dashboard
 *
 * Dashboard Template.
 *
 * @author Ali Haugh
 * @since 1.0.0
 */

$user_id = get_current_user_id();
$json_object = get_customer_contributions($user_id);

get_header();

?>
<main id="main">

	<div id="tabs">
	    <ul>
			<li id="prof-pic" class="prof-pic no-bullet">
				<?php if(get_user_meta($user_id, "user-profile-picture", true)) { ?>
					<img src="<?php echo get_user_meta($user_id, "user-profile-picture", true); ?>">
				<?php } else {
					echo get_avatar($user_id, 168);
				} ?>
				<span>Edit</span>
				<form id="upload-pic-form" name="upload-pic-form" method="post" enctype="multipart/form-data" action="<?php echo admin_url('admin-ajax.php'); ?>">
					<input type="file" name="pic-upload" id="pic-upload" class="no-bullet pic-upload" accept="image/*">
					<?php wp_nonce_field( 'prof-pic-upload-action', 'prof-pic-upload-nonce' ); ?>
					<input name="action" value="change_profile_picture" type="hidden">
					<input name="userID" value="<?php echo $user_id; ?>" type="hidden">
					<input type="button" id="submit-prof-pic" name="submit" class="pic-upload" data-toggle="modal" data-target="#upload-progress">
				</form>
			</li>
    		<li class="dashb-username no-bullet"> <?php $user = get_userdata(get_current_user_id());
			$name = $user->first_name . " " . $user->last_name;
			echo $name; ?> </li>
	        <li>
	            <a href="#fundraisers">My Fundraisers</a>
	        </li>
	        <li>
	            <a href="#contributions">My Contributions</a>
	        </li>
	        <li>
	            <a href="#settings">Settings</a>
	        </li>
	    </ul>

	    <!-- ** MY FUNDRAISERS TAB ** -->
		<div id="fundraisers">
			<div id="title"> 
				<h1>My Campaigns</h1>
				<p id="totalText"></p>
			</div>
			<div id="fundraise-btn" class="inline-top">
				<button onclick="window.location.href='/create-fundraiser/'">New Fundraiser</button>
			</div>
				<!-- User's Active Fundraisers -->
			<?php //$totalRaisedActive = get_fundraiser_list($user_id, "active"); ?>
				<!-- User's Pending Fundraisers -->	
			<?php 
				//$totalRaisedPending = get_fundraiser_list($user_id, "pending"); 
			?>
				<!-- User's Past Fundraisers -->
			<?php 
				//$totalRaisedExpired = get_fundraiser_list($user_id, "expired"); 
			?>
			<input id="totalRaised" type="hidden" value="<?php echo $totalRaisedActive + $totalRaisedPending + $totalRaisedExpired ?>">
 		</div>

	    <!-- ** MY CONTRIBUTIONS TAB ** -->
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
					<div class="inline-top cancel-txt">
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
						//create_contributions_list($user_id, $json_object); ?>
						</span>
					<?php } else {  ?>
						</span>
						<p class="none-p">You have not made any contributions.</p>
					<?php } ?>
			</div>
			<div class="dashboard-space"></div>
		</div>

	 	<!-- ** SETTINGS TAB ** -->
		<div id="settings">
			<h1>Account Settings</h1>
			<?php echo do_shortcode("[ultimatemember_account]"); ?>
		</div>
	</div>

	<!-- Progress Bar Upload Modal -->
	<div class="modal fade" id="upload-progress" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  	<div class="modal-dialog" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        		<h5 class="modal-title" id="myModalLabel">Upload Progress</h5>
	      		</div>
	      		<div class="modal-body">
	        		<div class="progress" style="display:none">
					  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">0%</div>
					</div>			      
        		</div>
	    	</div>
	  	</div>
	</div>

</main>





<script type="text/javascript">

	$(document).ready(function() {
		window.scrollTo(0,0);
	});

	$('#tabs')
	    .tabs()
	    .addClass('ui-tabs-vertical ui-helper-clearfix');

  	var hash = $.trim( window.location.hash);
    if (hash) $('#tabs a[href$="'+hash+'"]').trigger('click');
	
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

	document.getElementById("totalText").textContent = "Total Raised: $" + $("#totalRaised").val();

	document.getElementById('prof-pic').onclick = function() {
    	document.getElementById('pic-upload').click();
	};

	$("#pic-upload").change(function() {
		$("#submit-prof-pic").click();
	});

	$('#confirm-cancel').click(function() {
		$('#cancel-recurring').submit();
	});

 	var progressbar = $('.progress-bar');

    $("#submit-prof-pic").click(function(){
		$("#upload-pic-form").ajaxForm( {
	  		beforeSend: function() {
				$(".progress").css("display","block");
				progressbar.width('0%');
				progressbar.text('0%');
            },
	    	uploadProgress: function (event, position, total, percentComplete) {
	        	progressbar.width(percentComplete + '%');
	        	progressbar.text(percentComplete + '%');
	     	}
		}).submit();
    });
</script>

 <?php 
get_footer();
?>