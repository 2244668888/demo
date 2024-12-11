Morris.Donut({
  element: "donutFormatter",
  data: [
    { value: 155, label: "voo", formatted: "at least 70%" },
    { value: 12, label: "bar", formatted: "approx. 15%" },
    { value: 10, label: "baz", formatted: "approx. 10%" },
    { value: 5, label: "A really really long label", formatted: "at most 5%" },
  ],
  resize: true,
  hideHover: "auto",
  formatter: function (x, data) {
    return data.formatted;
  },
  backgroundColor: "#2a3039",
  labelColor: "#95a0b1",
  colors: ["#4361ee",
    "#3a0ca3",
    "#480ca8",
    "#560bad",
    "#7209b7",
    "#b5179e",],
});
