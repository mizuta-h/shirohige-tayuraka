<div id="canvasWrapper">
	<canvas id="myChart"></canvas>
</div>

<script>
var chartData = {
	labels: [<?php echo $optData['graphData']['date']; ?>],
	datasets: [
		{
			type: 'line',
			label: 'クリック率(メイン)',
			data: [<?php echo $optData['graphData']['main']; ?>],
			yAxisID: "y-clickRate",
			fill: false,
		},
		{
			type: 'line',
			label: 'クリック率(サブ)',
			data: [<?php echo $optData['graphData']['sub']; ?>],
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
		            id: "y-clickRate",
		            type: "linear", 
		            ticks: {
		                max: <?php echo $total_maxClickRate; ?>,
		                min: 0,
		            },
					gridLines: {
	                	drawOnChartArea: false, 
	            	},
	            	//display: false,
		        }
	        ],
        }
    }
});
</script>