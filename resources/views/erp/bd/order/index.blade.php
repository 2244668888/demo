@extends('layouts.app')
@section('title')
    ORDER LIST
@endsection
@section('button')
    <a type="button" class="btn btn-info" href="{{ route('order.create') }}">
        <i class="bi bi-plus-square"></i> Add
    </a>
@endsection
@section('content')
    <style>
       .table thead tr input {
            background: transparent;
            color: white;

        }
    </style>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered m-0 datatable">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Order No</th>
                            <th>PO NO</th>
                            <th>Customer Name</th>
                            <th>Created Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" id="search-order_no" placeholder="Search Order No"></th>
                            <th><input type="text" id="search-po_no" placeholder="Search PO NO"></th>
                            <th><input type="text" id="search-customer" placeholder="Search Customer Name"></th>
                            <th><input type="text" id="search-date" placeholder="Search Created Date"></th>
                            <th><input type="text" id="search-status" placeholder="Search Status"></th>
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
        var data = "{{ route('order.data') }}";
        $(document).ready(function() {
            let bool = true;
           var table =  $('.datatable').DataTable({
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
                        d.order_no = $('#search-order_no').val();
                        d.po_no = $('#search-po_no').val();
                        d.customer = $('#search-customer').val();
                        d.date = $('#search-date').val();
                        d.status = $('#search-status').val();
                    }
                }, // URL to fetch data
                columns: [{
                    data :'DT_RowIndex',
                         name: 'DT_RowIndex',
                         orderable: false,
                         searchable: false
                    }, {
                        data: 'order_no',
                        name: 'order_no',
                    }, {
                        data: 'po_no',
                        name: 'po_no',
                    },
                    {
                        data: 'customers.name',
                        name: 'customers.name',
                    },
                    {
                        data: 'date',
                        name: 'date',
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            if (data === 'in-progress') {
                                return '<span class="badge border border-warning text-warning">In Progress</span>';
                            } else if (data === 'complete') {
                                return '<span class="badge border border-success text-success">Completed</span>';
                            } else {
                                return data;
                            }
                        }
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


    </script>
@endsection
