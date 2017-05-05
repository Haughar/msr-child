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
	        <li>
	            <a href="#overview">Overview</a>
	        </li>
	        <li>
	            <a href="#fundraisers">My Fundraisers</a>
	        </li>
	        <li>
	            <a href="#contributions">My Contributions</a>
	        </li>
	    </ul>


		<!-- Make call to stripe for user info -->
		<div id="overview">

		<?php if ($json_object['recurring_donation']) { ?>
			<h2>Your Recurring Donations</h2>
			<?php foreach ($json_object['subscription_data'] as $data) { ?>
				<p><?php echo $data['id']; ?></p>
			<?php } 
		} ?>
			


			<canvas id="user-contributions" height="400px" width="500px"></canvas>
			<p>$<?php echo $json_object['total']; ?> contributed</p>
		</div>

		<div id="fundraisers">
			<?php get_fundraiser_list($user_id); ?>
		</div>

		<div id="contributions">
			<?php foreach ($json_object['charge-data'] as $charge) { ?>
				<p>Contributed <?php echo $charge['amount'] / 100; ?> to <?php echo $charge['description']; ?></p>
			<?php } ?>
		</div>
	</div>
</main>





<script type="text/javascript">
var ctx = document.getElementById('user-contributions').getContext('2d');

	var data = {
		labels: ['', '', '', '', '', ''],
		datasets: [
			{
				label: 'past-contributions',
				backgroundColor: [
					'rgb(234, 193, 16, 1)',
					'rgb(234, 193, 16, 1)',
					'rgb(234, 193, 16, 1)',
					'rgb(234, 193, 16, 1)',
					'rgb(234, 193, 16, 1)',
					'rgb(234, 193, 16, 1)'
				],
				borderColor: [
					'rgb(234, 193, 16, 1)',
					'rgb(234, 193, 16, 1)',
					'rgb(234, 193, 16, 1)',
					'rgb(234, 193, 16, 1)',
					'rgb(234, 193, 16, 1)',
					'rgb(234, 193, 16, 1)'
				],
				borderWidth: 1,
				data: <?php echo json_encode( $json_object['contribution-data'] ); ?>,
			}
		]
	};

	var options = {
        scales: {
            xAxes: [{
                gridLines: {
                	display: false
                },
                ticks: {
                	display: false
                }
            }],
            yAxes: [{
                gridLines: {
                	display: false
                },
                ticks: {
                	display: false
                }
            }]
        },
        responsive: false,
        scaleShowLabels : false
    };

	var myBarChart = new Chart(ctx, {
	    type: 'bar',
	    data: data,
	    options: options
	});


	$('#tabs')
	    .tabs()
	    .addClass('ui-tabs-vertical ui-helper-clearfix');

</script>

<link rel="stylesheet" type="text/css" href="style.css">
<?php 
get_footer();
?>