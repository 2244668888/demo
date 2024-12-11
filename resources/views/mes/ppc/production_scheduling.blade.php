@extends('layouts.app')
@section('title')
    PRODUCTION SCHEDULING
@endsection
@section('content')
    <style>
        .custom-button {
            padding: 5px;
            width: 100%;
            text-align: center;
            background-color: #CCDEFE !important;
            color: #3478F9 !important;
            border: none !important;
        }

        .custom-date {
            font-size: 0.8em;
            text-align: right;
            padding: 3px;
            cursor: pointer;
        }

        .fc-daygrid-day-number {
            width: 100% !important;
        }

        .fc-daygrid-day-frame.fc-scrollgrid-sync-inner {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
    </style>
    <div class="card">
        <div class="card-body">
            <div id='calendar'></div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">SCHEDULE EVENTS</h5>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered m-0" id="productiontable" style="width: 100% !important;">
                            <thead>
                                <tr>
                                    <th>Production Order No.</th>
                                    <th>Machine Name</th>
                                    <th>Process</th>
                                    <th>Shift</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/index.global.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            let productiontable = $('#productiontable').DataTable();
            $data = @json($production_schedules);
            const datesArray = Object.values($data).map(item => item.planned_date);
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                timeZone: 'Asia/Kuala_Lumpur',
                dayCellContent: function(info) {
                    var container = document.createElement('div');

                    var dateText = document.createElement('div');
                    dateText.classList.add('custom-date');
                    dateText.textContent = info.date.getDate();
                    container.appendChild(dateText);

                    var dateStr = formatDate(info.date);

                    var dateExists = datesArray.some(function(item) {
                        return formatDate(item) === dateStr;
                    });

                    if (dateExists) {
                        var button = document.createElement('button');
                        button.innerHTML = 'View Events';
                        button.classList.add('custom-button');
                        button.onclick = function() {
                            openModal(formatDate2(info.date));
                        };
                        container.appendChild(button);
                    }

                    return {
                        domNodes: [container]
                    };
                }
            });
            calendar.render();

            function openModal(date) {
                $('.modal-title').text(date);
                getData(date);
            }

            function formatDate(dateStr) {
                var date = new Date(dateStr);
                var day = String(date.getDate()).padStart(2, '0');
                var month = String(date.getMonth() + 1).padStart(2, '0');
                var year = date.getFullYear();
                return `${day}/${month}/${year}`;
            }

            function formatDate2(dateStr) {
                var date = new Date(dateStr);
                var day = String(date.getDate()).padStart(2, '0');
                var month = String(date.getMonth() + 1).padStart(2, '0');
                var year = date.getFullYear();
                return `${year}-${month}-${day}`;
            }

            function getData(date) {
                productiontable.clear().draw();
                $('#exampleModal').modal('show');
                $.ajax({
                    type: 'GET',
                    url: '{{ route('production-scheduling.getSchedules') }}',
                    data: {
                        "date": formatDate2(date)
                    },
                    success: function(data) {
                        data.forEach(element => {
                            status = element.status;
                            if (status == 'Checked') {
                                color = 'primary';
                            }
                            if (status == 'Start') {
                                color = 'success';
                            }
                            if (status == 'Not-initiated') {
                                color = 'secondary';
                            }
                            if (status == 'Stop') {
                                color = 'danger';
                            }
                            if (status == 'Pause') {
                                color = 'warning';
                            }
                            let currentData = [
                                element.po_no,
                                element.machine.name ?? '',
                                element.process,
                                element.shift,
                                `<span class="badge border border-${color} text-${color}">${status}</span>`,
                            ];
                            productiontable.row.add(currentData).draw();
                        });
                    }
                });
            }
        });
    </script>
@endsection
