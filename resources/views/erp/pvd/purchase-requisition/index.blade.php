@extends('layouts.app')
@section('title')
    PURCHASE REQUISITION LIST
@endsection
@section('button')
    <a type="button" class="btn btn-info" href="{{ route('purchase_requisition.create') }}">
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
                <table class="table table-bordered m-0 datatable" id="purchaseRequisitionTable">
                    <thead>
                        <tr>
                            <th colspan="8"></th>
                            <th colspan="3" class="text-center">Status</th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>NO</th>
                            <th>PR No.</th>
                            <th>Department</th>
                            <th>Date</th>
                            <th>Grand Total (RM)</th>
                            <th>PR Status</th>
                            <th>Category</th>
                            <th>Requested By</th>
                            <th>Current Status</th>
                            <th>By</th>
                            <th>Designation</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th>
                                <input type="text" class="all_column" id="pr_no" placeholder="search PR No">
                            </th>
                            <th>
                                <input type="text" class="all_column" id="department" placeholder="search Department">
                            </th>
                            <th>
                                <input type="text" class="all_column" id="date" placeholder="search Date">
                            </th>
                            <th>
                                <input type="text" class="all_column" id="total" placeholder="search Grand Total">
                            </th>
                            <th>
                                <input type="text" class="all_column" id="pr_status" placeholder="search PR Status">
                            </th>
                            <th>
                                <input type="text" class="all_column" id="category" placeholder="search Category">
                            </th>
                            <th>
                                <input type="text" class="all_column" id="request_by" placeholder="search Requested By">
                            </th>
                            <th>
                                <input type="text" class="all_column" id="name" placeholder="search Name">
                            </th>
                            <th>
                                <input type="text" class="all_column" id="designation" placeholder="search Designation">
                            </th>
                            <th>
                                <input type="text" class="all_column" id="current_status"
                                    placeholder="search Current Status">
                            </th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        var data = "{{ route('purchase_requisition.data') }}";
        $(document).ready(function() {
            var searchTimeout;
            var table = $('#purchaseRequisitionTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('purchase_requisition.data') }}',
                    data: function(d) {
                        // For each search input, pass its value to the request
                        d.pr_no = $('#pr_no').val();
                        d.department = $('#department').val();
                        d.date = $('#date').val();
                        d.total = $('#total').val();
                        d.pr_status = $('#pr_status').val();
                        d.category = $('#category').val();
                        d.request_by = $('#request_by').val();
                        d.current_status = $('#current_status').val();
                        d.name = $('#name').val();
                        d.designation = $('#designation').val();
                    }
                },
                columns: [{
                        data: null,
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'pr_no',
                        name: 'pr_no'
                    }, // PR Number
                    {
                        data: 'department.name',
                        name: 'department.name'
                    }, // Department Name
                    {
                        data: 'date',
                        name: 'date'
                    }, // Date
                    {
                        data: 'total',
                        name: 'total'
                    }, // Total Amount
                    {
                        data: 'status',
                        name: 'status'
                    }, // Status
                    {
                        data: 'category',
                        name: 'category'
                    }, // Category
                    {
                        data: 'user.user_name',
                        name: 'user.user_name'
                    }, // Requested By
                    {
                        data: 'current_status',
                        name: 'current_status',
                    },
                    {
                        data: 'verified_by_relation.user_name',
                        name: 'verified_by_relation.user_name'
                    }, // Requested By
                    {
                        data: 'verified_by_relation.designation.name',
                        name: 'verified_by_relation.designation.name'
                    }, // Requested By
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    } // Action buttons
                ],
                order: [
                    [3, 'desc']
                ], // Default ordering (on 'date' column, descending)
                lengthMenu: [
                    [10, 25, 50],
                    [10, 25, 50]
                ], // Page length options
                pageLength: 10, // Default page length
                rowCallback: function(row, data, index) {
                    // Add the row index number starting from 1 on each page
                    var pageInfo = table.page.info();
                    var rowIndex = pageInfo.start + index +
                        1; // Calculate row index based on current page
                    $('td:eq(0)', row).html(rowIndex);
                },
                drawCallback: function(settings) {
                    // Optional callback if you need to manipulate the table after it redraws
                }
            });

            // Handle search input dynamically
            $(document).on('keyup', '#dt-search-0', function() {
                table.search($('#dt-search-0').val()).draw();
            });

            // If you have custom filter inputs, trigger the table reload on change
            $('#purchaseRequisitionTable thead tr:eq(2) th').each(function(i) {
                $('input', this).on('keyup change', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(function() {
                        // Apply the search term to the specific column
                        table.column(i).search($('input', this).val());
                        table.draw(); // Draw the table after applying the search term
                    }.bind(this), 1000);
                });
            });
        });
    </script>
@endsection
