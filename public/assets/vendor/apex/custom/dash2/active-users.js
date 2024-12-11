var options = {
	chart: {
		height: 320,
		type: "radialBar",
		toolbar: {
			show: false,
		},
	},
	plotOptions: {
		radialBar: {
			hollow: {
				size: "40%",
			},
			dataLabels: {
				name: {
					fontSize: "12px",
					fontColor: "black",
				},
				value: {
					fontSize: "21px",
				},
				total: {
					show: true,
					label: "Total",
					formatter: function (w) {
						// By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
						return "250";
					},
				},
			},
			track: {
				background: "#2b3048",
			},
		},
	},
	series: [80, 60, 40],
	labels: ["Desktop", "Tablet", "Mobile"],
	colors: ["#f03b59", "#3975f9", "#0fc079"],
};

var chart = new ApexCharts(document.querySelector("#device-sessions"), options);
chart.render();
