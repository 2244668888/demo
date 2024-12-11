var options = {
	chart: {
		height: 280,
		type: "line",
		toolbar: {
			show: false,
		},
	},
	dataLabels: {
		enabled: false,
	},
	stroke: {
		curve: "smooth",
		width: 5,
	},
	series: [
		{
			name: "Visitors",
			data: [100, 500, 300, 700, 500, 900, 1200],
		},
	],
	grid: {
		borderColor: "#575e6d",
		strokeDashArray: 5,
		xaxis: {
			lines: {
				show: true,
			},
		},
		yaxis: {
			lines: {
				show: false,
			},
		},
		padding: {
			top: 0,
			right: 0,
			bottom: 0,
			left: 0,
		},
	},
	xaxis: {
		type: "day",
		categories: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
	},
	yaxis: {
		labels: {
			show: false,
		},
	},
	colors: ["#fe31d4", "#f03b59"],
	fill: {
		type: "gradient",
		gradient: {
			shadeIntensity: 1,
			opacityFrom: 0.7,
			opacityTo: 0.9,
			colorStops: [
				{
					offset: 0,
					color: "#fe31d4",
					opacity: 1,
				},
				{
					offset: 20,
					color: "#977dec",
					opacity: 1,
				},
				{
					offset: 60,
					color: "#1fe3ef",
					opacity: 1,
				},
				{
					offset: 100,
					color: "#23d16e",
					opacity: 1,
				},
			],
		},
	},
	markers: {
		size: 0,
	},
};

var chart = new ApexCharts(document.querySelector("#sales"), options);

chart.render();
