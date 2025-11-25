<div id="canvasWrapper">
	<canvas id="myChart"></canvas>
</div>

<script>
var chartData = {
	labels: [<?php echo $graphData['date']; ?>],
	datasets: [
		{
			type: 'bar',
			label: 'ユーザー数',
			data: [<?php echo $graphData['access']; ?>],
			yAxisID: "y-access",
		},
		{
			type: 'bar',
			label: 'クリック数',
			data: [<?php echo $graphData['click']; ?>],
			yAxisID: "y-click",
		},
		{
			type: 'line',
			label: 'クリック率',
			data: [<?php echo $graphData['click_rate']; ?>],
			yAxisID: "y-clickRate",
			fill: false,
		},
	],
};

var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: chartData,
    options: {
		responsive: true,
		maintainAspectRatio: false,
		plugins: {
	      colorschemes: {
	        scheme: 'brewer.Paired12'
	      }
	    },
        scales: {
            yAxes: [
	            {
	                id: "y-access",
		            type: "linear",
		            position: "left",
		            ticks: {
		                max: <?php echo $this->report->ceil_1($graphData['max_access']); ?>,
		                min: 0,
		            },
		        },
		        {
		            id: "y-click",
		            type: "linear", 
		            position: "right",
		            ticks: {
		                max: <?php echo $this->report->ceil_1($graphData['max_access']); ?>,
		                min: 0,
		            },
					gridLines: {
	                	drawOnChartArea: false, 
	            	},
		        },
		        {
		            id: "y-clickRate",
		            type: "linear", 
		            ticks: {
		                max: 100,
		                min: 0,
		            },
					gridLines: {
	                	drawOnChartArea: false, 
	            	},
	            	display: false,
		        }
	        ],
        }
    }
});
</script>