var options = {
	plotOptions: {
		pie: {
			customScale: 0.8,
			donut: {
				size: "20%",
			},
		},
	},
	chart: {
		width: 204,
		type: "donut",
	},
	labels: ["Pending", "Completed", "New"],
	series: [40, 30, 20],
	legend: { show: false },
	dataLabels: {
		enabled: false,
	},
	stroke: {
		width: 0,
	},

	colors: ["#3975f9", "#0fc079", "#f03b59"],
};
var chart = new ApexCharts(document.querySelector("#tasks"), options);
chart.render();
