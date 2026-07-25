<?php 
include "include/header.php";  
include "include/topnavbar.php"; 
?>
<div id="layoutSidenav">
	<div id="layoutSidenav_nav">
		<?php include "include/menubar.php"; ?>
	</div>
	<div id="layoutSidenav_content">
		<main>
			<div class="page-header page-header-light bg-white shadow">
				<div class="container-fluid">
					<div class="page-header-content py-3">
						<h1 class="page-header-title font-weight-light">
							<div class="page-header-icon"><i class="fas fa-desktop"></i></div>
							<span>Dashboard</span>
						</h1>
					</div>
				</div>
			</div>
			<div class="container-fluid mt-2 p-0 p-2">
				<div class="card rounded-0">
					<div class="card-body">
						<div class="row row-cols-1 row-cols-md-3">
							<div class="col mb-3">
								<div class="card shadow-none h-100">
									<div class="card-body">
										<h6 class="title-style small"><span>Monthly Income vs Expense (P&L Trend)</span></h6>
										<h4 >Rs. <?php echo $dataForChart['totalCurrenBal']; ?></h4>
										<h6 class="small">Current cash balance</h6>
										<div style="height: auto; min-height: 300px;">
											<canvas id="cashflowChart"></canvas>
										</div>
									</div>				
								</div>				
							</div>
							<div class="col mb-3">
								<div class="card shadow-none h-100">
									<div class="card-body">
										<h6 class="title-style small"><span>Expenses</span></h6>
										<h4>Rs. <?php echo number_format($dataForExpencesChart['totalExpenses'], 2); ?></h4>
										<h6 class="small">Business spending</h6>
										<div style="display: flex; align-items: center; justify-content: flex-start; background: #fff; padding: 20px; border-radius: 8px; min-width: 600px;">
											<div style="height: auto; min-height: 300px; flex-shrink: 0;">
												<canvas id="expenseChart"></canvas>
											</div>
											
											<div id="expense-chart-legend" style="margin-left: 15px; flex-grow: 1;"></div>
											
										</div>
									</div>				
								</div>				
							</div>
							<div class="col mb-3">
								<div class="card shadow-none h-100">
									<div class="card-body">
										<h6 class="title-style small"><span>SALES</span></h6>
										<h4>Rs. <?php echo $dataForSalesIncomeChart['income']['total']; ?></h4>
										<h6 class="small">Net income for <?php echo date('F Y'); ?></h6>
										<div class="chart-container pt-5" style="height: auto; min-height: 300px; font-family: sans-serif;">
											<div style="display: flex; align-items: center; margin-bottom: 20px;">
												<div style="width: 150px; line-height: 1.2;">
													<strong style="display: block;" class="small font-weight-bold">Rs. <?php echo $dataForSalesIncomeChart['income']['total']; ?></strong>
													<span style="color: #888; font-size: 14px;">Income</span>
												</div>
												<div style="flex-grow: 1; position: relative;">
													<span style="position: absolute; right: 0; top: -18px; color: #00bcd4; font-size: 12px; font-weight: bold;">&nbsp;</span>
													<canvas id="incomeChart" height="40"></canvas>
												</div>
											</div>

											<div style="display: flex; align-items: center;">
												<div style="width: 150px; line-height: 1.2;">
													<strong style="display: block;" class="small font-weight-bold">Rs. <?php echo $dataForSalesIncomeChart['expense']['total']; ?></strong>
													<span style="color: #888; font-size: 14px;">Expenses</span>
												</div>
												<div style="flex-grow: 1; position: relative;">
													<span style="position: absolute; right: 0; top: -18px; color: #00bcd4; font-size: 12px; font-weight: bold;">&nbsp;</span>
													<canvas id="expenseChart2" height="40"></canvas>
												</div>
											</div>
										</div>
									</div>				
								</div>				
							</div>
						</div>
						<div class="row row-cols-1 row-cols-md-2">
							<div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
								<div class="card shadow-none h-100">
									<div class="card-body">
										<h6 class="title-style small"><span>Invoices</span></h6>
										<h4>Rs. <?php echo number_format($dataForInvoiceSalesChart['total'], 2); ?></h4>
										<h6 class="small">Total invoiced sales</h6>
										<div style="height: auto; min-height: 300px;">
											<canvas id="invoiceLineChart"></canvas>
										</div>
									</div>				
								</div>				
							</div>
							<div class="col-sm-12 col-md-6 col-lg-8 col-xl-8">
								<div class="card shadow-none h-100">
									<div class="card-body">
										<h6 class="title-style small"><span>Recent Journal Entries</span></h6>
										<div class="scrollbar pb-3" id="style-2">
											<table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
												<thead>
													<tr>
														<th>#</th>
														<th>Company</th>
														<th>Branch</th>
														<th>Year</th>
														<th>Month</th>
														<th>Tra. Date</th>
														<th>Batch No</th>
														<th>C/D</th>
														<th>Account No</th>
														<th>Account Name</th>
														<th>Narration</th>
														<th>Amount</th>
													</tr>
												</thead>
											</table>
										</div>
									</div>				
								</div>				
							</div>
						</div>
					</div>
				</div>
			</div>
		</main>
		<?php include "include/footerbar.php"; ?>
	</div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
$(document).ready(function(){
	$('#dataTable').DataTable({
		"destroy": true,
		"processing": true,
		"serverSide": true,
		ajax: {
			url: "<?php echo base_url() ?>scripts/doubleentrylist.php",
			type: "POST", // you can use GET
			data: function(d) {
				d.userID = '<?php echo $_SESSION['userid']; ?>';
			}
		},
		"order": [[ 0, "desc" ]],
		"columns": [
			{
				"data": "idtbl_account_transaction"
			},
			{
				"data": "company"
			},
			{
				"data": "branch"
			},
			{
				"data": "desc"
			},
			{
				"data": "monthname"
			},
			{
				"data": "tradate"
			},
			{
				"data": "batchno"
			},
			{
				"data": "crdr"
			},
			{
				"data": "accountno"
			},
			{
				"data": "accountname"
			},
			{
				"data": "narration"
			},
			{
				"targets": -1,
				"className": 'text-right',
				"data": null,
				"render": function(data, type, full) {
					return addCommas(parseFloat(full['totamount']).toFixed(2));
				}
			},
		],
		drawCallback: function(settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

	var ctx = document.getElementById('expenseChart').getContext('2d');

	var data = {
		labels: <?php echo json_encode($dataForExpencesChart['labels']); ?>,
		datasets: [{
			data: <?php echo json_encode($dataForExpencesChart['amounts']); ?>,
			backgroundColor: [
				'#00ced1', // 1. Bright Teal
				'#20b2aa', // 2. Light Sea Green
				'#008b8b', // 3. Dark Cyan
				'#008080', // 4. Teal
				'#48d1cc', // 5. Medium Turquoise
				'#5f9ea0', // 6. Cadet Blue
				'#00a8a8', // 7. Custom Deep Sea
				'#76cb00', // 8. Primary Green (Matches your Income color)
				'#005f6b', // 9. Deep Petrol
				'#00ced1cc'// 10. Semi-transparent Teal
			],
			borderWidth: 2,
			borderColor: '#ffffff'
		}]
	};

	var options = {
		cutoutPercentage: 75,
		responsive: true,
		maintainAspectRatio: false,
		legend: {
			display: false // Hide the default legend to use the HTML version
		},
		// Define the custom HTML layout
		legendCallback: function(chart) {
			var text = [];
			text.push('<ul style="list-style: none; padding: 0; margin: 0;">');
			var data = chart.data;
			var dataset = data.datasets[0];

			for (var i = 0; i < data.labels.length; i++) {
				var val = dataset.data[i];
				var formattedVal = 'Rs.' + addCommas(parseFloat(val).toFixed(2));
				
				text.push('<li style="display: flex; align-items: flex-start; margin-bottom: 20px;">');
				// Square marker
				text.push('<span style="background-color:' + dataset.backgroundColor[i] + '; width:14px; height:14px; display:inline-block; border-radius:3px; margin-right:12px; margin-top:4px;"></span>');
				// Text block (Amount on top, Label on bottom)
				text.push('<span>');
				text.push('<div style="font-weight: 600; color: #444; font-size: 14px; line-height: 1.2;">' + formattedVal + '</div>');
				text.push('<div style="color: #888; font-size: 12px; line-height: 1.2;">' + data.labels[i] + '</div>');
				text.push('</span>');
				text.push('</li>');
			}
			text.push('</ul>');
			return text.join("");
		},
		tooltips: {
			enabled: true,
			callbacks: {
				label: function(tooltipItem, data) {
					var label = data.labels[tooltipItem.index];
					var val = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
					return label + ': Rs. ' + addCommas(parseFloat(val).toFixed(2));
				}
			}
		}
	};

	var myChart = new Chart(ctx, {
		type: 'doughnut',
		data: data,
		options: options
	});

	// IMPORTANT: This line generates the legend and injects it into the div
	document.getElementById('expense-chart-legend').innerHTML = myChart.generateLegend();

	var ctx = document.getElementById('cashflowChart').getContext('2d');

	var myBarChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: <?php echo json_encode($dataForChart['labels']); ?>,
			datasets: [
				{
					label: 'Money in',
					data: <?php echo json_encode($dataForChart['moneyIn']); ?>,
					backgroundColor: '#76cb00', // Green
					borderWidth: 0
				},
				{
					label: 'Money out',
					data: <?php echo json_encode($dataForChart['moneyOut']); ?>,
					backgroundColor: '#00ced1', // Teal
					borderWidth: 0
				}
			]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						stepSize: 1000000,
						// Format labels to show $ and K (e.g., $25K)
						callback: function(value) {
							return 'Rs.' + (value / 1000000).toFixed(1) + 'M';
						}
					},
					gridLines: {
						drawBorder: false,
						color: '#f0f0f0'
					}
				}],
				xAxes: [{
					gridLines: {
						display: false // Hides vertical grid lines to match image
					},
					barPercentage: 0.6,    // Adjusts width of individual bars
					categoryPercentage: 0.5 // Adjusts space between groups
				}]
			},
			legend: {
				position: 'bottom',
				labels: {
					usePointStyle: true,
					padding: 20
				}
			},
			tooltips: {
				mode: 'index',
				intersect: false,
				callbacks: {
					label: function(tooltipItem, data) {
						var label = data.datasets[tooltipItem.datasetIndex].label || '';
						return label + ': Rs.' + tooltipItem.yLabel.toLocaleString();
					}
				}
			}
		}
	});

	function createHatchPattern(color) {
		var shape = document.createElement('canvas');
		shape.width = 10;
		shape.height = 10;
		var c = shape.getContext('2d');
		c.strokeStyle = color;
		c.lineWidth = 2;
		c.beginPath();
		c.moveTo(2, 10);
		c.lineTo(10, 2);
		c.stroke();
		return c.createPattern(shape, 'repeat');
	}

	var ctxIncome = document.getElementById('incomeChart').getContext('2d');
	var ctxExpense = document.getElementById('expenseChart2').getContext('2d');

	var commonOptions = {
		maintainAspectRatio: false,
		legend: { display: false },
		tooltips: { enabled: false },
		scales: {
			xAxes: [{ stacked: true, display: false, ticks: { beginAtZero: true } }],
			yAxes: [{ stacked: true, display: false }]
		}
	};

	// Income Chart
	new Chart(ctxIncome, {
		type: 'horizontalBar',
		data: {
			datasets: [
				{ data: [<?php echo $dataForSalesIncomeChart['income']['solid']; ?>], backgroundColor: '#76cb00' }, // Solid Green
				{ data: [<?php echo $dataForSalesIncomeChart['income']['hatched']; ?>], backgroundColor: createHatchPattern('#76cb00') } // Pattern Green
			]
		},
		options: commonOptions
	});

	// Expense Chart
	new Chart(ctxExpense, {
		type: 'horizontalBar',
		data: {
			datasets: [
				{ data: [<?php echo $dataForSalesIncomeChart['expense']['solid']; ?>], backgroundColor: '#00ced1' }, // Solid Teal
				{ data: [<?php echo $dataForSalesIncomeChart['expense']['hatched']; ?>], backgroundColor: createHatchPattern('#00ced1') } // Pattern Teal
			]
		},
		options: commonOptions
	});

	var ctx = document.getElementById('invoiceLineChart').getContext('2d');

	var invoiceLineChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: <?php echo json_encode($dataForInvoiceSalesChart['labels']); ?>, // Matches the layout in your image
			datasets: [{
				label: 'Net Profit',
				data: <?php echo json_encode($dataForInvoiceSalesChart['amounts']); ?>,
				borderColor: '#76cb00',       // Green line
				backgroundColor: 'transparent', 
				borderWidth: 2,
				pointBackgroundColor: '#ffffff', // Hollow center
				pointBorderColor: '#76cb00',     // Green ring
				pointBorderWidth: 2,
				pointRadius: 5,
				pointHoverRadius: 7,
				lineTension: 0 // Straight lines (set to 0.4 for smooth curves)
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			legend: { display: false },
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						stepSize: 5000,
						callback: function(value) {
							return 'Rs.' + (value / 1000000).toFixed(1) + 'M';
						}
					},
					gridLines: {
						drawBorder: false,
						color: '#f0f0f0'
					}
				}],
				xAxes: [{
					gridLines: {
						display: false // Matches the clean look in your image
					},
					ticks: {
						fontStyle: 'bold'
					}
				}]
			},
			// Custom vertical line for "TODAY" can be added via a plugin if needed, 
			// but simple labeling in 'labels' usually suffices for standard UI.
		}
	});
});

function addCommas(nStr){
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}
</script>
<?php include "include/footer.php"; ?>
