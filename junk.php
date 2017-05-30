<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>

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