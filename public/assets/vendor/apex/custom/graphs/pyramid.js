var options = {
	series: [
		{
			name: "Tickets",
			data: [200, 330, 548, 740, 880, 1100],
		},
	],
	chart: {
		type: "bar",
		height: 300,
		toolbar: {
			show: false,
		},
	},
	dataLabels: {
		enabled: false,
	},
	plotOptions: {
		bar: {
			borderRadius: 0,
			horizontal: true,
			distributed: true,
			barHeight: "80%",
			isFunnel: true,
		},
	},
	colors: [
		"#35364b",
		"#4c4d5f",
		"#626373",
		"#797987",
		"#8f909b",
		"#a5a6af",
		"#bcbcc3",
		"#d2d2d7",
	],
	dataLabels: {
		enabled: true,
		formatter: function (val, opt) {
			return opt.w.globals.labels[opt.dataPointIndex];
		},
		dropShadow: {
			enabled: true,
		},
	},
	xaxis: {
		categories: ["Closed", "Hold", "Resolved", "Waiting", "On Going", "Total"],
	},
	legend: {
		show: true,
	},
};

var chart = new ApexCharts(document.querySelector("#pyramid"), options);
chart.render();
