@extends('layouts.app')
@section('title')
    Summary Attendance Report
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="filter p-3">
                <div class="row">
                    <div class="col-md-6">
                        <label for="" class="form-label">Date Start</label>
                        <input type="date" class="date_start form-control" id="start_date">
                    </div>
                    <div class="col-md-6">
                        <label for="" class="form-label">Date End</label>
                        <input type="date" class="date_end form-control" id="end_date">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label for="" class="form-label">Staff</label>
                        <select name="" id="staff_select" class="form-select">
                            <option value="-1" selected disabled>Select a Staff Member</option>
                            @foreach ($staff as $item)
                                <option value="{{ $item->code }}">{{ $item->code }} - {{ $item->user_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="" class="form-label">Department</label>
                        <select name="" id="department_select" class="form-select">
                            <option value="-1" selected disabled>Select a Department Member</option>
                            @foreach ($department as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mt-2 d-flex flex-row-reverse">
                    <div class="col-md-6 d-flex flex-row-reverse">
                        <button id="search" type="buton" class="btn btn-primary mx-2">Search</button>
                        <button id="export-btn" type="buton" class="btn btn-danger">Download</button>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="table-responsive">
                    <table id="table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Emp ID</th>
                                <th>Emp Name</th>
                                <th>Attend Days</th>
                                <th>Total Leave</th>
                                <th>Total Medical Leave</th>
                                <th>Absent Days</th>
                                <th>OT Time</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>

            </div>

        </div>
    </div>

    <script>
        $(document).ready(function() {
            generate()
            $('#search').click(function(e) {
                e.preventDefault();
                generate();
            });

            function generate() {
                var table = $('#table').DataTable();
                // Get the values from input fields
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();
                var staff_id = $('#staff_select').val();
                var depart_id = $('#department_select').val();

                // AJAX request using GET method with data for query parameters
                $.ajax({
                    url: '{{ route('summary_attendance.generate') }}', // The URL to send the request to
                    method: 'GET', // Use GET method to send data
                    data: {
                        start_date: start_date,
                        end_date: end_date,
                        staff_id: staff_id,
                        depart_id: depart_id
                    },
                    success: function(response) {
                        table.clear().draw();
                        // Loop through the response data
                        setTimeout(function() {
                        var serialNo = 1;
                        $.each(response, function(index, item) {
                            // Add row for each item in the response
                            table.row.add([

                                serialNo,
                                item.emp_id, // Emp ID
                                item.emp_name, // Emp Name
                                item
                                .attend_days, // Attendance Days (currently 0 in your data)
                                item.total_leave, // Total Leave
                                item.total_medical_leave, // Total Medical Leave
                                item.absent_days, // Absent Days
                                item.ot_time // OT Time
                            ]).draw();
                            serialNo++;
                        });
                        });
                    },
                    error: function(xhr, status, error) {
                    }
                });
            }
        });


        function exportToExcel() {
            const table = document.getElementById("table");
            const rows = table.querySelectorAll("tr");
            let csv = [];
            csv.push('"Summary Attendance Report"');
            csv.push(""); 
            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].querySelectorAll("td,th");
                let row = [];
                for (let j = 0; j < cells.length; j++) {
                    row.push('"' + cells[j].innerText.replace(/"/g, '""') + '"');
                }
                csv.push(row.join(","));
            }
            const csvContent = "data:text/csv;charset=utf-8," + csv.join("\n");
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "summary-attendance.csv");
            document.body.appendChild(link);
            link.click();
        }

        document.getElementById("export-btn").addEventListener("click", exportToExcel);
    </script>
@endsection
