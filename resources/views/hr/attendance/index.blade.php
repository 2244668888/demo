@extends('layouts.app')
@section('title')
    ATTENDANCE LIST
@endsection
@section('button')
    <div class="d-flex">
        <a href="{{ route('attendance.export') }}" class="btn btn-info m-0" id="export-btn">
            <i class="bi bi-download"></i> Export
        </a>

        <form id="formImport" method="post" action="{{ route('attendance.import') }}" enctype="multipart/form-data">
            @csrf
            <div class="upload-btn-wrapper mx-2">
                <button class="btn btn-warning" type="submit"><i class="bi bi-upload"></i> Import</button>
                <input type="file" name="import" class="fileExcel" accept=".xls" />
            </div>
        </form>
    </div>
@endsection
@section('content')
    <style>
        .table thead tr input {
            background: transparent;
            color: white;

        }

        .upload-btn-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }

        .btns {
            border: 2px solid rgb(201, 189, 21);
            color: white;
            background-color: rgb(201, 189, 21);
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 20px;
            font-weight: bold;
        }

        .upload-btn-wrapper input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
        }
    </style>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table datatable table-bordered m-0">
                    <thead>
                        <tr>
                            <th>Sr #</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Late</th>
                            <th>Date</th>
                            <th>Clock-in</th>
                            <th>Clock-out</th>
                            <th>Work Time</th>
                            <th>OT Time</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" id="search-code" placeholder="Search Code"></th>
                            <th><input type="text" id="search-name" placeholder="Search Name"></th>
                            <th><input type="text" id="search-department" placeholder="Search Designation"></th>
                            <th><input type="text" id="search-late" placeholder="Search Late"></th>
                            <th><input type="text" id="search-date" placeholder="Search Date"></th>
                            <th><input type="text" id="search-clock_in" placeholder="Search Clock-in"></th>
                            <th><input type="text" id="search-clock_out" placeholder="Search Clock-out"></th>
                            <th><input type="text" id="search-work_time" placeholder="Search Work Time"></th>
                            <th><input type="text" id="search-ot_time" placeholder="Search OT Time"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($leave as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->created_at->format('d-m-Y') }}</td>
                                <td>{{ $row->entitlement }}</td>
                                @if ($row->status == 'Request')
                                <td><span class="badge bg-warning">{{ $row->status }}</span></td>

                                @elseif($row->status == "Approved")
                                <td><span class="badge bg-success">{{ $row->status }}</span></td>
                                @else
                                <td><span class="badge bg-danger">{{ $row->status }}</span></td>

                                @endif

@if ($row->status == 'Not Approved')
<td>
    <a class="btn btn-success btn-sm mx-1" href="{{ route('leave.view', $row->id) }}"><i
        class="bi bi-eye"></i>
    </a>
</td>

@elseif ($row->status == "Approved")
<td>
<a class="btn btn-success btn-sm mx-1" href="{{ route('leave.view', $row->id) }}"><i
    class="bi bi-eye"></i>

</a>
</td>
@else
<td>
        <a class="btn btn-info btn-sm " href="{{ route('leave.edit', $row->id) }}"><i
                class="bi bi-pencil"></i>
        </a>
        <a class="btn btn-success btn-sm mx-1" href="{{ route('leave.view', $row->id) }}"><i
            class="bi bi-eye"></i>

    </a>
    <a title="Manage" class="btn btn-warning btn-sm mx-1" href="{{ route('leave.manage', $row->id) }}"><i
        class=" bi bi-slack "></i>
        <a class="btn btn-danger btn-icon btn-sm"
            href="{{ route('leave.destroy', $row->id) }}"><i class="bi bi-trash"></i>
        </a>
</td>

@endif

                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="remarksModal" tabindex="-1" aria-labelledby="remarksModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="remarksForm" action="{{ route('attendance.saveremarks') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <textarea id="remarksText" name="remarks" class="form-control" rows="4" placeholder="Enter your remarks here"></textarea>
                        <input type="hidden" name="row_id" id="rowId">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="save">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- <script async src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script> --}}

    <script>
        $('.fileExcel').on('change', function() {
            // $(this).file()
            $('#formImport').submit();
        })
    </script>
    <script>
        var data = "{{ route('attendance.data') }}";
        $(document).ready(function() {
            let bool = true;
            var table = $('.datatable').DataTable({
                perPageSelect: [5, 10, 15, ["All", -1]],
                processing: true,
                serverSide: true,
                language: {
                    processing: 'Processing' // Custom processing text
                },
                ajax: {
                    url: data, // URL for your server-side data endpoint
                    type: 'GET',
                    data: function(d) {
                        // Include server-side pagination parameters
                        d.code = $('#search-code').val();
                        d.name = $('#search-name').val();
                        d.department = $('#search-department').val();
                        d.late = $('#search-late').val();
                        d.date = $('#search-date').val();
                        d.clock_in = $('#search-clock_in').val();
                        d.clock_out = $('#search-clock_out').val();
                        d.work_time = $('#search-work_time').val();
                        d.ot_time = $('#search-ot_time').val();
                        d.remarks = $('#search-remarks').val();
                    }
                }, // URL to fetch data
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false

                    },
                    {
                        data: 'no',
                        name: 'no',
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'department',
                        name: 'department',
                    },
                    {
                        data: 'late',
                        name: 'late',
                    },
                    {
                        data: 'date',
                        name: 'date',
                    },
                    {
                        data: 'clock_in',
                        name: 'clock_in',
                    },
                    {
                        data: 'clock_out',
                        name: 'clock_out',
                    },
                    {
                        data: 'work_time',
                        name: 'work_time',
                    },
                    {
                        data: 'ot_time',
                        name: 'ot_time',
                    },
                    {
                        data: 'remarks',
                        name: 'remarks',
                    },

                ],
                paging: true
                // Other DataTables options go here
            });
            bool = false;

            $(document).on('keyup', '#dt-search-0', function() {
                console.log($(this).val())
                table.search($(this).val()).draw();
            });

            // $('.datatable thead tr').clone(true).appendTo('.datatable thead');
            $('.datatable thead tr:eq(1) th').each(function(i) {
                $('input', this).on('keyup change', function() {
                    if (table.column(i).search() !== this.value) {
                        table
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            });



        });



        function exportToExcel() {
            const table = document.getElementById("myTable");
            const rows = table.querySelectorAll("tr");
            let csv = [];
            csv.push('"ATTENDANCE LIST"');
            csv.push(""); 
            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].querySelectorAll("td, th");
                let row = [];
                for (let j = 0; j < cells.length; j++) {
                    row.push('"' + cells[j].innerText.replace(/"/g, '""') + '"');
                }
                csv.push(row.join(","));
            }

            const csvContent = "\uFEFF" + csv.join("\n");
            const blob = new Blob([csvContent], {
                type: 'text/csv;charset=utf-8;'
            });

            // if we're using IE/Edge, then use different download call
            if (typeof navigator.msSaveOrOpenBlob === 'function') {
                navigator.msSaveOrOpenBlob(blob, "leave.csv");
            } else {
                // the next code will generate a temp <a /> tag that you can trigger a hidden click for it to start downloading
                const link = document.createElement('a');
                const csvUrl = URL.createObjectURL(blob);

                link.href = csvUrl;
                link.setAttribute('download', "leave.csv");

                // set the visibility hidden so that there is no effect on your web-layout
                link.style.visibility = 'hidden';

                // finally we will append the anchor tag and remove it after clicking it
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }



            // var tableClone = $("#myTable").clone();

            // var wb = XLSX.utils.table_to_book(tableClone[0], { sheet: "Sheet1" });

            // XLSX.writeFile(wb, 'leave.xlsx');
        }

        let selectedRowId;
        $(document).on('click', '.add-remarks, .edit-remarks, .edit-view', function() {
    selectedRowId = $(this).data('id');
    var remarks = $(this).data('remark');
    $('#remarksModal #remarksText').val(remarks);
    $('#rowId').val(selectedRowId);

    // Check if the clicked button has the class 'edit-remarks' or 'edit-view'
    if ($(this).hasClass('edit-remarks')) {
        const existingRemarks = $(this).closest('tr').find('.remarks').val();
        $('#remarksText').val(existingRemarks);
        $('#remarksModalLabel').text('Edit Remarks');
        
        // Enable remarks field and save button for edit mode
        $('#remarksText').prop('disabled', false);
        $('#save').prop('disabled', false);
        
    } else if ($(this).hasClass('edit-view')) {
        // Disable remarks field and save button for view mode
        $('#remarksText').prop('disabled', true);
        $('#save').prop('disabled', true);
        $('#remarksModalLabel').text('View Remarks');
        
    } else {
        $('#remarksText').val('');
        $('#remarksModalLabel').text('Add Remarks');
        
        // Enable remarks field and save button for add mode
        $('#remarksText').prop('disabled', false);
        $('#save').prop('disabled', false);
    }

    $('#remarksModal').modal('show');
});


    </script>
@endsection
