@extends('layouts.app')
@section('title')
    STOCK RELOCATION LIST
@endsection
@section('button')
    <button type="button" class="btn btn-warning" id="export-btn">
        <i class="bi bi-download"></i> Export
    </button>
    <a type="button" class="btn btn-info" href="{{ route('stock_relocation.create') }}">
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
                            <th>Date</th>
                            <th>Ref No</th>
                            <th>Description</th>
                            <th>Transfer By</th>
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
                                <input type="text" class="all_column " placeholder="search Ref No">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Description">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Transfer By">
                            </th>


                            <th></th>
                        </tr>
                    <tbody>
                        {{-- @foreach ($stock_relocations as $stock_relocation)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $stock_relocation->date }}</td>
                                <td>{{ $stock_relocation->ref_no }}</td>
                                <td>
                                    <div class="description">
                                        <p class="short-desc">{{ Str::limit($stock_relocation->description, 100) }}</p>
                                        <p class="full-desc" style="display:none;">{{ $stock_relocation->description }}</p>
                                        <a href="#" class="read-more">Read More</a>
                                    </div>
                                </td>
                                <td>{{ $stock_relocation->user->user_name }}</td>
                                <td>
                                    <a class="btn btn-success btn-sm"
                                        href="{{ route('stock_relocation.view', $stock_relocation->id) }}"><i
                                            class="bi bi-eye"></i></a>
                                    <a class="btn btn-info btn-sm"
                                        href="{{ route('stock_relocation.edit', $stock_relocation->id) }}"><i
                                            class="bi bi-pencil"></i></a>
                                    <a class="btn btn-danger btn-sm"
                                        href="{{ route('stock_relocation.destroy', $stock_relocation->id) }}"><i
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

var data = "{{ route('stock_relocation.data') }}";
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
                        data: 'description',
                        name: 'description',
                        render: function(data, type, row) {
                            if (data != null) {
                    return '<div class="description">' +
                            '<p class="short-desc">' + data.substring(0, 100) + '</p>' +
                            '<p class="full-desc" style="display:none;">' + data + '</p>' +
                            '<a href="#" class="read-more">Read More</a>' +
                           '</div>';
                        }else{
                            return '';
                        }

                    }
                    },
                     {
                        data: 'user.user_name',
                        name: 'user.user_name',
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
                        data: 'description',
                        name: 'description',
                        render: function(data, type, row) {
                    return '<div class="description">' +
                            '<p class="short-desc">' + data.substring(0, 100) + '</p>' +
                            '<p class="full-desc" style="display:none;">' + data + '</p>' +
                            '<a href="#" class="read-more">Read More</a>' +
                           '</div>';
                    }
                    },
                     {
                        data: 'user.user_name',
                        name: 'user.user_name',
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
            csv.push('"STOCK RELOCATION LIST"');
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
            link.setAttribute("download", "stock-relocation.csv");
            document.body.appendChild(link);
            link.click();
        }

        document.getElementById("export-btn").addEventListener("click", exportToExcel);
    </script>
@endsection
