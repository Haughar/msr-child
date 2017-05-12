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

get_header();
$json_object = get_customer_contributions($user_id);

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>
<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
<script src="http://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>

<main id="main">

	<div id="tabs">
	    <ul>
			<li class="prof-pic"><?php echo get_avatar($user_id, 145); ?></li>
    		<li class="dashb-username"> <?php echo get_user_meta( $user_id, 'nickname', true); ?> </li>
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

		<div id="fundraisers">
			<div id="title"> 
				<h1>My Campaigns</h1>
				<p id="totalText"></p>
			</div>
			<div id="fundraise-btn" class="inline-top">
				<button onclick="window.location.href='/create-fundraiser/'">New Fundraiser</button>
			</div>
			
			<?php $totalRaisedActive += get_fundraiser_list($user_id, "active"); ?>
			<div class="dashboard-space"></div> 
				<!-- Get Pending Fundraisers -->	
			<?php 
				$totalRaisedPending += get_fundraiser_list($user_id, "pending"); 
			?>
			<div class="dashboard-space"></div>
				<!-- Get Past Fundraisers -->
			<?php 
				$totalRaisedExpired += get_fundraiser_list($user_id, "expired"); 
			?>
			<input id="totalRaised" type="hidden" value="<?php echo $totalRaisedActive + $totalRaisedPending + $totalRaisedExpired ?>">
			<div class="dashboard-space"></div>


 		</div>

		<div id="contributions">
			<div id="title"> 
				<h1>My Contributions</h1>
				<p> Total Contribution: $<?php echo $json_object['total']; ?></p>
			</div>
			<div id="fundraise-btn" class="inline-top">
				<button onclick="window.location.href='/create-fundraiser/'">New Fundraiser</button>
			</div>

			<div class="user-profile-header recurring-header">
				<div class="with-cancel inline-top">
					<span class="with-cancel">Recurring Contributions</span>
				</div>
				<div class="inline-top cancel-txt">
					Cancel
				</div>
			</div>
			<?php if ($json_object['recurring_donation']) { ?>
				<div class="all-recurr">
					<?php foreach ($json_object['subscription_data'] as $data) { ?>
						<div class="recurr_div">
							<input id="subID" type="hidden" value="<?php echo $data['id']; ?>">
							<span class="amt-text"><?php echo '$' . $data['quantity']; ?></span>
							<span class="cancel-txt pad-left-ten">Monthly</span>
							<span class="cancel-txt">Last Contribution: <?php echo date("M j, Y", $data['current_period_start']); ?></span>
							<span class="cancel-txt next-recurr">Next Contribution: <?php echo date("M j, Y", $data['current_period_end']); ?></span>
							<span class="recurr-box"><input type="checkbox" name="cancel"></span>
						</div>
					<?php } ?>
					<div class="save-btn">
						<button id="save-btn" class="disabled-btn" disabled>Save</button>
					</div>
				</div>
			<?php } else { ?>
				<div class="recurr-div">
					<p>You have no recurring payments.</p> 
				</div>
			<?php } ?>
			<div class="dashboard-space"></div> 
			<div class="dashboard-space"></div>
			<div class="dashboard-space"></div>
			<div class="user-profile-header">
				<p>Your Contributions</p> 
			</div>
			<div>
				<span class="day-text"><?php create_contributions_list($user_id, $json_object); ?></span>
			</div>
			<div class="dashboard-space"></div>
		</div>
		<div id="settings">
			<h1>Account Settings</h1>
			<?php echo do_shortcode("[ultimatemember_account]"); ?>
		</div>
	</div>
</main>





<script type="text/javascript">
	//var ctx = document.getElementById('user-contributions').getContext('2d');

	// var data = {
	// 	labels: ['', '', '', '', '', ''],
	// 	datasets: [
	// 		{
	// 			label: 'past-contributions',
	// 			backgroundColor: [
	// 				'rgb(234, 193, 16, 1)',
	// 				'rgb(234, 193, 16, 1)',
	// 				'rgb(234, 193, 16, 1)',
	// 				'rgb(234, 193, 16, 1)',
	// 				'rgb(234, 193, 16, 1)',
	// 				'rgb(234, 193, 16, 1)'
	// 			],
	// 			borderColor: [
	// 				'rgb(234, 193, 16, 1)',
	// 				'rgb(234, 193, 16, 1)',
	// 				'rgb(234, 193, 16, 1)',
	// 				'rgb(234, 193, 16, 1)',
	// 				'rgb(234, 193, 16, 1)',
	// 				'rgb(234, 193, 16, 1)'
	// 			],
	// 			borderWidth: 1,
	// 			data: <?php echo json_encode( $json_object['contribution-data'] ); ?>,
	// 		}
	// 	]
	// };

	// var options = {
 //        scales: {
 //            xAxes: [{
 //                gridLines: {
 //                	display: false
 //                },
 //                ticks: {
 //                	display: false
 //                }
 //            }],
 //            yAxes: [{
 //                gridLines: {
 //                	display: false
 //                },
 //                ticks: {
 //                	display: false
 //                }
 //            }]
 //        },
 //        responsive: false,
 //        scaleShowLabels : false
 //    };

	// var myBarChart = new Chart(ctx, {
	//     type: 'bar',
	//     data: data,
	//     options: options
	// });


	$('#tabs')
	    .tabs()
	    .addClass('ui-tabs-vertical ui-helper-clearfix');

  	var hash = $.trim( window.location.hash);
    if (hash) $('#tabs a[href$="'+hash+'"]').trigger('click');
	
	$("input").on("click", function () {
		if($("input").is(':checked')) {
			$("#save-btn").attr("disabled", false);
			$("#save-btn").removeClass("disabled-btn");
			$("#save-btn").addClass("blck-btn");
		} else {
			$("#save-btn").attr("disabled", true);
			$("#save-btn").addClass("disabled-btn");
			$("#save-btn").removeClass("blck-btn");
		}
	});

	$("#save-btn").click(function() {
		$dataString = "subID=" + $('#subID').val();
		alert("Subscription deleted");
		// $.ajax({
		// 	data: $dataString,
		// 	type: "GET",
		// 	url:'/recurring.php',
		// 	success: function (response) {
		// 	  	alert("Recurring payment deleted");
		// 	},
		// 	error: function(xhr) {
		// 		alert(xhr.status + " " + xhr.statusText);
		// 	}
		// });
	});

	document.getElementById("totalText").textContent = "Total Raised: $" + $("#totalRaised").val();
</script>

<!-- <link rel="stylesheet" type="text/css" href="style.css">
 --><?php 
get_footer();
?>