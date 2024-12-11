@extends('layouts.app')
@section('title')
    LEAVE LIST
@endsection
@section('button')
    <button type="button" class="btn btn-warning" id="export-btn">
        <i class="bi bi-download"></i> Export
    </button>
    <a type="button" class="btn btn-info" href="{{ route('leave.create') }}">
        <i class="bi bi-plus-square"></i> Add
    </a>
@endsection
@section('content')
    <style>
        .table thead tr input{
            background: transparent;
            color: white;

        }
    </style>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table datatable table-bordered m-0" id="table">
                    <thead>
                        <tr>
                            <th>Sr #</th>
                            <th>User Name</th>
                            <th>Date Application</th>
                            <th>Entitlement</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" id="search-user_name" placeholder="Search User Name"></th>
                            <th><input type="text" id="search-date" placeholder="Search Date Application"></th>
                            <th><input type="text" id="search-entitlement" placeholder="Search Entitlement"></th>
                            <th></th>
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
    {{-- <script async src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script> --}}

    <script>
        var data = "{{ route('leave.data') }}";
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
                        d.user_name = $('#search-user_name').val();
                        d.date = $('#search-date').val();
                        d.entitlement = $('#search-entitlement').val();
                        d.status = $('#search-status').val();
                    }
                }, // URL to fetch data
                columns: [{
                    data :'DT_RowIndex',
                         name: 'DT_RowIndex',
                         orderable: false,
                        searchable: false

            }, {
                        data: 'name',
                        name: 'name',
                    }, {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data, type, row) {
                            if (data) {
                                // Assuming the date is in ISO 8601 format
                                const date = new Date(data);
                                const day = String(date.getDate()).padStart(2, '0');
                                const month = String(date.getMonth() + 1).padStart(2, '0');
                                const year = date.getFullYear();
                                return `${day}-${month}-${year}`;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'entitlement',
                        name: 'entitlement',
                    },
                    {
                        data: 'status',
                        name: 'status',
                        searchable: false,
                        render: function(data, type, row) {
                            if (data === 'Approved') {
                                return '<span class="badge bg-success">Approved</span>';
                            } else if (data === 'Request') {
                                return '<span class="badge bg-secondary">Request</span>';
                            } else {
                                return '<span class="badge bg-danger">Not Approved</span>';
                            }
                        },
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false

                    },
                ],
                paging: true
                // Other DataTables options go here
            });
            bool = false;

            $(document).on('keyup','#dt-search-0', function() {
                console.log($(this).val())
            table.search($(this).val()).draw();
             });

        // $('.datatable thead tr').clone(true).appendTo('.datatable thead');
            $('.datatable thead tr:eq(1) th').each(function (i) {
                $('input', this).on('keyup change', function () {
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
            const table = document.getElementById("table");
            const rows = table.querySelectorAll("tr");
            let csv = [];
            csv.push('"LEAVE LIST"');
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

        document.getElementById("export-btn").addEventListener("click", exportToExcel);
    </script>
@endsection
