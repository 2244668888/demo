@extends('layouts.app')
@section('title')
    DAILY PRODUCTION PLANNING
@endsection
@section('button')
    <a type="button" class="btn btn-info" href="{{ route('daily-production-planning.create') }}">
        <i class="bi bi-plus-square"></i> Add
    </a>
@endsection
@section('content')
<style>
    .all_column {
        background: transparent;
        color: white;

    }
</style>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table  class="table table-bordered m-0 datatable">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Order No.</th>
                            <th>Customer</th>
                            <th>Order Month</th>
                            <th>Planning Date</th>
                            <th>Created By</th>
                            <th>Create Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Order No.">
                            </th>

                            <th>
                                <input type="text" class="all_column " placeholder="search Customer">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Order Month">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Planning Date">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Created By">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Create Date">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Status">
                            </th>



                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($dpps as $dpp)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $dpp->order->order_no  ?? ''}}</td>
                            <td>{{ $dpp->order->customers->name ?? '' }}</td>
                            <td>{{ $dpp->order->order_month ?? '' }}</td>
                            <td>{{ $dpp->planning_date }}</td>
                            <td>{{ $dpp->users->user_name ?? '' }}</td>
                            <td>{{ $dpp->created_date }}</td>
                            <td>
                                @if ($dpp->status == 'In Progress')
                                    <span class="badge border border-warning text-warning">{{ $dpp->status }}</span>
                                @endif
                                @if ($dpp->status == 'Completed')
                                    <span class="badge border border-success text-success">{{ $dpp->status }}</span>
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-success btn-sm" title="View" href="{{ route('daily-production-planning.view', $dpp->id) }}"><i
                                    class="bi bi-eye"></i></a>
                                @if ($dpp->status == 'In Progress')
                                    <a class="btn btn-info btn-sm" title="Edit" href="{{ route('daily-production-planning.edit', $dpp->id) }}"><i
                                        class="bi bi-pencil"></i></a>
                                @endif
                                <a class="btn btn-danger btn-sm" href="{{ route('daily-production-planning.destroy', $dpp->id) }}" title="Delete"><i
                                    class="bi bi-trash" ></i></a>
                            </td>
                        </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        var data = "{{ route('daily-production-planning.data') }}";
        $(document).ready(function() {
            let bool = true;
            $('.datatable').DataTable({
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
                        d.draw = d.draw || 1; // Add 'draw' parameter with a default value
                        d.start = d.start || 0; // Add 'start' parameter with a default value
                        d.length = d.length || 10; // Add 'length' parameter with a default value
                        if (bool) {
                            d.order = [null, null];
                        } else {
                            d.order = d.order || [null,
                                null
                            ]; // Add sorting information with a default value
                        }
                    }
                }, // URL to fetch data
                columns: [{
                        data: 'sr_no',
                        name: 'sr_no',
                        orderable: false
                    }, {
                        data: 'order.order_no',
                        name: 'order.order_no',
                    }, {
                        data: 'customer',
                        name: 'customer',
                    },
                    {
                        data: 'order.order_month',
                        name: 'order.order_month',
                    },
                    {
                        data: 'planning_date',
                        name: 'planning_date',
                    },
                    {
                        data: 'user',
                        name: 'user',
                    },
                    {
                        data: 'created_date',
                        name: 'created_date',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    },
                ],
                paging: true
                // Other DataTables options go here
            });
            bool = false;
        });

        function AjaxCall(columnsData) {

            $('.datatable').DataTable().destroy();

            $('.datatable').DataTable({
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
                        d.draw = d.draw || 1; // Add 'draw' parameter with a default value
                        d.start = d.start || 0; // Add 'start' parameter with a default value
                        d.length = d.length || 10; // Add 'length' parameter with a default value
                        d.order = d.order || [null, null]; // Add sorting information with a default value
                        d.columnsData = columnsData;

                    }
                }, // URL to fetch data
                columns: [{
                        data: 'sr_no',
                        name: 'sr_no',
                        orderable: false
                    }, {
                        data: 'order.order_no',
                        name: 'order.order_no',
                    }, {
                        data: 'customer',
                        name: 'customer',
                    },
                    {
                        data: 'order.order_month',
                        name: 'order.order_month',
                    },
                    {
                        data: 'planning_date',
                        name: 'planning_date',
                    },
                    {
                        data: 'user',
                        name: 'user',
                    },
                    {
                        data: 'created_date',
                        name: 'created_date',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    },
                ],
                paging: true
                // Other DataTables options go here
            });

        }

        var typingTimer;
        var doneTypingInterval = 1000; // Adjust the time interval as needed (in milliseconds)

        $('.datatable .all_column').on('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                var columnIndex = $(this).closest('th').index();

                // Collect all column indices and values in an array
                var columnsData = $('.datatable .all_column').map(function() {
                    var index = $(this).closest('th').index();
                    var value = $(this).val();
                    return {
                        index: index,
                        value: value
                    };
                }).get();
                AjaxCall(columnsData);

                // Focus on the input in the same column after making the Ajax call
                $(this).closest('tr').find('th').eq(columnIndex).find('input').focus();
            }, doneTypingInterval);
        });

        function exportToExcel() {
            const table = document.getElementById("myTable");
            const rows = table.querySelectorAll("tr");
            let csv = [];

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
