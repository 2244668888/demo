
@extends('layouts.app')
@section('title')
    DISCREPANCY LIST
@endsection
@section('button')
    <button type="button" class="btn btn-warning" id="export-btn">
        <i class="bi bi-download"></i> Export
    </button>
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
                <table class="table datatable table-bordered m-0" id="DTable">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Date</th>
                            <th>Ref No.</th>
                            <th>MRF No./TR No.</th>
                            <!-- <th>Order No.</th> -->
                            <th>Part No.</th>
                            <th>Part Name</th>
                            <th>Issued/Transferred Qty</th>
                            <th>Received Qty</th>
                            <th>Status</th>
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
                                <input type="text" class="all_column " placeholder="search Order No.">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search MRF No./TR No.">
                            </th>
                            <!-- <th>
                                <input type="text" class="all_column " placeholder="search Order No.">
                            </th> -->
                            <th>
                                <input type="text" class="all_column " placeholder="search Part No.">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Part Name">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Issued/Transferd Qty">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Received Qty">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Status">
                            </th>


                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($discrepancies as $discrepancy)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ date('d-m-Y',strtotime($discrepancy->date)) }}</td>
                                <td>{{ $discrepancy->ref_no }}</td>
                                <td>
                                    @php
                                        if ($discrepancy->tr) {
                                            echo $discrepancy->tr->ref_no;
                                            $check = 'tr';
                                        }
                                        elseif ($discrepancy->mrf) {
                                            echo $discrepancy->mrf->ref_no;
                                            $check = 'mrf';
                                        }
                                    @endphp
                                </td>
                                <!-- <td>{{ $discrepancy->order_no }}</td> -->
                                <td>{{ $discrepancy->products->part_no }}</td>
                                <td>{{ $discrepancy->products->part_name }}</td>
                                <td>{{ $discrepancy->issue_qty }}</td>
                                <td>{{ $discrepancy->rcv_qty }}</td>
                                <td>
                                    @if ($discrepancy->status == 'Pending')
                                        <span class="badge border border-dark text-dark">Pending</span>
                                    @endif
                                    @if ($discrepancy->status == 'Issuer')
                                        <span class="badge border border-primary text-primary">Added to Issuer</span>
                                    @endif
                                    @if ($discrepancy->status == 'Reciever')
                                        <span class="badge border border-primary text-primary">Added to Reciever</span>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-success btn-sm" href="{{ route('discrepancy.view', [$discrepancy->id,$check]) }}"><i
                                            class="bi bi-eye"></i></a>
                                    @if ($discrepancy->status == 'Pending')
                                        <a class="btn btn-info btn-sm" href="{{ route('discrepancy.edit', [$discrepancy->id,$check]) }}"><i
                                                class="bi bi-pencil"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
          var data = "{{ route('discrepancy.data') }}";
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
                    },{
                        data: 'date',
                        name: 'date',
                    }, {
                        data: 'ref_no',
                        name: 'ref_no',
                    },
                     {
                        data: 'tr_mrf_number',
                        name: 'tr_mrf_number',
                    },
                    // {
                    //     data: 'order_no',
                    //     name: 'order_no',
                    // },
                     {
                        data: 'products.part_no',
                        name: 'products.part_no',
                    },
                    {
                        data: 'products.part_name',
                        name: 'products.part_name',
                    },
                    {
                        data: 'issue_qty',
                        name: 'issue_qty',
                    },
                    {
                        data: 'rcv_qty',
                        name: 'rcv_qty',
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
                        data: 'date',
                        name: 'date',
                    },{
                        data: 'ref_no',
                        name: 'ref_no',
                    },
                    // {
                    //     data: 'order_no',
                    //     name: 'order_no',
                    // },
                     {
                        data: 'mrf.ref_no',
                        name: 'mrf.ref_no',
                    },
                     {
                        data: 'products.part_no',
                        name: 'products.part_no',
                    },
                    {
                        data: 'products.part_name',
                        name: 'products.part_name',
                    },
                    {
                        data: 'issue_qty',
                        name: 'issue_qty',
                    },
                    {
                        data: 'rcv_qty',
                        name: 'rcv_qty',
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
            const table = document.getElementById("DTable");
            const rows = table.querySelectorAll("tr");
            let csv = [];
            csv.push('"Discrepancy List"');
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
            link.setAttribute("download", "Discrepancy.csv");
            document.body.appendChild(link);
            link.click();
        }

        document.getElementById("export-btn").addEventListener("click", exportToExcel);
    </script>
@endsection
