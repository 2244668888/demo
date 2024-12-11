@extends('layouts.app')
@section('title')
    PRODUCT REORDERING LIST
@endsection
@section('button')
    <button type="button" class="btn btn-warning" id="export-btn">
        <i class="bi bi-download"></i> Export
    </button>
    <a type="button" class="btn btn-info" href="{{ route('product_reordering.create') }}">
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
                <table class="table datatable table-bordered m-0" id="DTable">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Part No</th>
                            <th>Part Name</th>
                            <th>Unit</th>
                            <th>Type of Product</th>
                            <th>Model</th>
                            <th>Variance</th>
                            <th>Critical Min</th>
                            <th>Minimum Qty</th>
                            <th>Maximum Qty</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Part No">
                            </th>

                            <th>
                                <input type="text" class="all_column " placeholder="search Part Name">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Unit">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Type of Product">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Model">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Variance">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Critical Min">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Minimum Qty">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Maximum Qty">
                            </th>


                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                        {{-- @foreach ($product_reorderings as $product_reordering)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $product_reordering->products->part_no }}</td>
                                <td>{{ $product_reordering->products->part_name }}</td>
                                <td>{{ $product_reordering->products->units->name ?? '' }}</td>
                                <td>{{ $product_reordering->products->type_of_products->type ?? '' }}</td>
                                <td>{{ $product_reordering->products->model }}</td>
                                <td>{{ $product_reordering->products->variance }}</td>
                                <td>{{ $product_reordering->critical_min }}</td>
                                <td>{{ $product_reordering->min_qty }}</td>
                                <td>{{ $product_reordering->max_qty }}</td>
                                <td>
                                    <a class="btn btn-success btn-sm"
                                        href="{{ route('product_reordering.view', $product_reordering->product_id) }}"><i
                                            class="bi bi-eye"></i></a>
                                    <a class="btn btn-info btn-sm"
                                        href="{{ route('product_reordering.edit', $product_reordering->product_id) }}"><i
                                            class="bi bi-pencil"></i></a>
                                    <a class="btn btn-danger btn-sm"
                                        href="{{ route('product_reordering.destroy', $product_reordering->product_id) }}"><i
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

var data = "{{ route('product_reordering.data') }}";
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
                        data: 'products.part_no',
                        name: 'products.part_no',
                    }, {
                        data: 'products.part_name',
                        name: 'products.part_name',
                    },
                     {
                        data: 'unit',
                        name: 'unit',
                    },
                     {
                        data: 'type',
                        name: 'type',
                    },
                     {
                        data: 'products.model',
                        name: 'products.model',
                    },
                     {
                        data: 'products.variance',
                        name: 'products.variance',
                    },
                     {
                        data: 'critical_min',
                        name: 'critical_min',
                    },
                     {
                        data: 'min_qty',
                        name: 'min_qty',
                    },
                     {
                        data: 'max_qty',
                        name: 'max_qty',
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
                        data: 'products.part_no',
                        name: 'products.part_no',
                    }, {
                        data: 'products.part_name',
                        name: 'products.part_name',
                    },
                     {
                        data: 'unit',
                        name: 'unit',
                    },
                     {
                        data: 'type',
                        name: 'type',
                    },
                     {
                        data: 'products.model',
                        name: 'products.model',
                    },
                     {
                        data: 'products.variance',
                        name: 'products.variance',
                    },
                     {
                        data: 'critical_min',
                        name: 'critical_min',
                    },
                     {
                        data: 'min_qty',
                        name: 'min_qty',
                    },
                     {
                        data: 'max_qty',
                        name: 'max_qty',
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
            csv.push('"PRODUCT REORDERING LIST"');
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
            link.setAttribute("download", "Product-Reordering.csv");
            document.body.appendChild(link);
            link.click();
        }

        document.getElementById("export-btn").addEventListener("click", exportToExcel);
    </script>
@endsection
