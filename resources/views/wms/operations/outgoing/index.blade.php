@extends('layouts.app')
@section('title')
    OUTGOING LIST
@endsection
@section('button')
    <button type="button" class="btn btn-warning" id="export-btn">
        <i class="bi bi-download"></i> Export
    </button>
    <a type="button" class="btn btn-info" href="{{ route('outgoing.create') }}">
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
                <table  class="table datatable table-bordered m-0" id="DTable">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Date</th>
                            <th>DO No</th>
                            <th>Ref No</th>
                            <th>Customer/Supplier</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Date">
                            </th>

                            <th>
                                <input type="text" class="all_column " placeholder="search DO No">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Ref No">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Customer/Supplier">
                            </th>



                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($outgoings as $outgoing)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $outgoing->date }}</td>
                                <td>{{ $outgoing->ref_no }}</td>
                                @if ($outgoing->category == 1)
                                    <td>{{ $outgoing->sales_return->ref_no ?? '' }}</td>
                                    <td>{{ $outgoing->sales_return->customer->name ?? '' }}</td>
                                @elseif($outgoing->category == 2)
                                    <td>{{ $outgoing->purchase_return->grd_no ?? '' }}</td>
                                    <td>{{ $outgoing->purchase_return->supplier->name ?? '' }}</td>
                                @elseif($outgoing->category == 3)
                                    <td>{{ $outgoing->order->order_no ?? '' }}</td>
                                    <td>{{ $outgoing->order->customers->name ?? '' }}</td>
                                @endif
                                <td>
                                    <a class="btn btn-success btn-sm" href="{{ route('outgoing.view', $outgoing->id) }}"><i
                                            class="bi bi-eye"></i></a>
                                    <a class="btn btn-danger btn-sm" href="{{ route('outgoing.preview', $outgoing->id) }}"
                                        target="_blank"><i class="bi bi-file-pdf"></i></a>
                                    <a class="btn btn-info btn-sm" href="{{ route('outgoing.edit', $outgoing->id) }}"><i
                                            class="bi bi-pencil"></i></a>
                                    <a class="btn btn-danger btn-sm"
                                        href="{{ route('outgoing.destroy', $outgoing->id) }}"><i
                                            class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        var data = "{{ route('outgoing.data') }}";
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
                        data: 'date',
                        name: 'date',
                    }, {
                        data: 'ref_no',
                        name: 'ref_no',
                    },
                     {
                        data: 'number',
                        name: 'number',
                    },
                     {
                        data: 'vendor_or_customer',
                        name: 'vendor_or_customer',
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
                        data: 'date',
                        name: 'date',
                    }, {
                        data: 'ref_no',
                        name: 'ref_no',
                    },
                     {
                        data: 'number',
                        name: 'number',
                    },
                     {
                        data: 'vendor_or_customer',
                        name: 'vendor_or_customer',
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
            const table = document.getElementById("DTable");
            const rows = table.querySelectorAll("tr");
            let csv = [];
            csv.push('"OUTGOING LIST"');
            csv.push("");
            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].querySelectorAll("td, th");
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
            link.setAttribute("download", "outgoing.csv");
            document.body.appendChild(link);
            link.click();
        }

        document.getElementById("export-btn").addEventListener("click", exportToExcel);
    </script>
@endsection
