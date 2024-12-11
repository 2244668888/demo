// Morris Donut
Morris.Donut({
  element: "donutColors",
  data: [
    { value: 30, label: "foo" },
    { value: 15, label: "bar" },
    { value: 10, label: "baz" },
    { value: 5, label: "A really really long label" },
  ],
  backgroundColor: "#2a3039",
  labelColor: "#95a0b1",
  colors: ["#4361ee",
    "#3a0ca3",
    "#480ca8",
    "#560bad",
    "#7209b7",
    "#b5179e",],
  resize: true,
  hideHover: "auto",
  gridLineColor: "#575e6d",
  formatter: function (x) {
    return x + "%";
  },
});
