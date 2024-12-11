var options = {
	series: [
		{
			name: "Sales",
			data: [200, 330, 548, 740, 880, 1100],
		},
	],
	chart: {
		type: "bar",
		height: 280,
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
		"#c02f47",
		"#d83550",
		"#f03b59",
		"#f24f6a",
		"#f3627a",
		"#f5768b",
		"#f6899b",
		"#f89dac",
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

var chart = new ApexCharts(document.querySelector("#qtwo"), options);
chart.render();
