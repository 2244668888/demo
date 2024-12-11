document.addEventListener("DOMContentLoaded", function () {
  var calendarEl = document.getElementById("dayGrid");
  var calendar = new FullCalendar.Calendar(calendarEl, {
    headerToolbar: {
      left: "prevYear,prev,next,nextYear today",
      center: "title",
      right: "dayGridMonth,dayGridWeek,dayGridDay",
    },
    initialDate: "2023-10-12",
    navLinks: true, // can click day/week names to navigate views
    editable: true,
    dayMaxEvents: true, // allow "more" link when too many events
    events: [
      {
        title: "All Day Event",
        start: "2023-10-01",
        color: "#2463ea",
      },
      {
        title: "Long Event",
        start: "2023-10-07",
        end: "2023-10-10",
        color: "#2059d3",
      },
      {
        groupId: 999,
        title: "Birthday",
        start: "2023-10-09T16:00:00",
        color: "#1d4fbb",
      },
      {
        groupId: 999,
        title: "Birthday",
        start: "2023-10-16T16:00:00",
        color: "#3a73ec",
      },
      {
        title: "Conference",
        start: "2023-10-11",
        end: "2023-10-13",
        color: "#5082ee",
      },
      {
        title: "Meeting",
        start: "2023-10-14T10:30:00",
        end: "2023-10-14T12:30:00",
        color: "#6692f0",
      },
      {
        title: "Lunch",
        start: "2023-10-16T12:00:00",
        color: "#7ca1f2",
      },
      {
        title: "Meeting",
        start: "2023-10-18T14:30:00",
        color: "#92b1f5",
      },
      {
        title: "Interview",
        start: "2023-10-21T17:30:00",
        color: "#a7c1f7",
      },
      {
        title: "Meeting",
        start: "2023-10-22T20:00:00",
        color: "#3a73ec",
      },
      {
        title: "Birthday",
        start: "2023-10-13T07:00:00",
        color: "#7ca1f2",
      },
      {
        title: "Click for Google",
        url: "http://bootstrap.gallery/",
        start: "2023-10-28",
        color: "#5082ee",
      },
      {
        title: "Interview",
        start: "2023-10-20",
        color: "#2463ea",
      },
      {
        title: "Product Launch",
        start: "2023-10-29",
        color: "#7ca1f2",
      },
      {
        title: "Leave",
        start: "2023-10-25",
        color: "#6692f0",
      },
    ],
  });

  calendar.render();
});
